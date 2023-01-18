@extends('adminlte::page')

@section('title', 'Sales Order')

@section('content_header')
    <h1>Sales Return</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-share"></i> Sales Return
            </h3>
        </div>
        <div class="card-body">
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Customer:</div>
					{{ $salesOrder->customer->customerName }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Order Date:</div>
					{{ $salesOrder->orderDate }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Created On:</div>
					{{ $salesOrder->dateCreated }}
				</div>
			</div><br />
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Description:</div>
					{{ $salesOrder->description }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Invoice #:</div>
					{{ $salesOrder->invoiceNumber }}
				</div>
                <div class="col">
					<div class="font-weight-bold">Book Serial #:</div>
					{{ $salesOrder->bookSerial }}
				</div>
			</div>
			<hr />

			<h3>Product Details</h3>
            <?php $colspanValue = 5; ?>
            <form name="frm" id="formReturn" action="{{ route('sales.add_return', $salesOrder->salesOrderID) }}" method="post">
                @csrf
			<table class="table">
				<thead>
					<tr>
						<th>Product</th>
						<th>Quantity</th>
                        <th>Good Return</th>
                        <th>Bad Return</th>
                        <th>Godown</th>
						<th>Sale Price / Qty</th>
                        @if ($globalSettings['client_settings.is_show_discount_per_product'] == 1)
                            <th>Discount</th>
                            <?php $colspanValue++; ?>
                        @endif
						<th>Total Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$total = 0;
						$paid = 0;
                        $totalDiscount = $salesOrder->discount;
					?>
					@foreach($salesOrderInfo as $salesOrderInfoSingle)
                        <?php
                            $row_total = $salesOrderInfoSingle['quantity'] * $salesOrderInfoSingle['sale_price'];
                        ?>
						<tr class="totalRows">
							<td>
                                {{ $salesOrderInfoSingle['product'] }} ({{ $salesOrderInfoSingle['product_category'] }})
                            </td>
							<td>{{ $salesOrderInfoSingle['quantity'] }} <input type="hidden" value="{{ $salesOrderInfoSingle['quantity'] }}" class="totalQuantity"></td>
                            <td>
                                <input type="number" name="goodReturn_{{ $salesOrderInfoSingle['product_id'] }}_{{ $salesOrderInfoSingle['stock_detail_id'] }}" value="0" class="form-control goodReturn" max="{{ $salesOrderInfoSingle['quantity'] }}"/>
								<div class="text-danger errorMessage" style="display: none">Good and Bad Returns must be less than Total Quantity</div>
                            </td>
							<td>
								<input type="number" name="badReturn_{{ $salesOrderInfoSingle['product_id'] }}_{{ $salesOrderInfoSingle['stock_detail_id'] }}" value="0" class="form-control badReturn" max="{{ $salesOrderInfoSingle['quantity'] }}"/>
							</td>
							<td>{{ $salesOrderInfoSingle['godown_name'] }}</td>
							<td>@money('$salesOrderInfoSingle["sale_price"]*$salesOrderInfoSingle["units_in_product"]')/-</td>
                            @if ($globalSettings['client_settings.is_show_discount_per_product'] == 1)
                                <td>@money('$salesOrderInfoSingle["discount"]')/-</td>
                            @endif
							<td>@money('$row_total')/-</td>
						</tr>
						<?php
							$total+= $row_total;
                            $totalDiscount += $salesOrderInfoSingle['quantity'] * $salesOrderInfoSingle['discount'];
						?>
					@endforeach
				</tbody>
				@foreach ($salesOrder->transactions as $transaction)
					@foreach ($transaction->transactionDetails as $transactionDetail)
						@if ($transactionDetail->headID == $cashHeadID)
							<?php
								$paid+= $transactionDetail->amount;
							?>
						@endif
					@endforeach
				@endforeach
				<tfoot>
                    <tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right"></td>
						<td>
                            <button type="button" name="btnSubmit" class="btn btn-primary" onclick="validateReturns()">Submit Sales Return</button>
                        </td>
					</tr>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Sub Total:</td>
						<td class="font-weight-bold">@money('$total')</td>
					</tr>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Discount:</td>
						<td class="font-weight-bold">@money('$totalDiscount')</td>
					</tr>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Shipping Charges:</td>
						<td class="font-weight-bold">@money('$salesOrder->shippingCharges')</td>
					</tr>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Grand Total:</td>
						<td class="font-weight-bold">@money('$total + $salesOrder->shippingCharges - $totalDiscount')</td>
					</tr>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Paid:</td>
						<td class="font-weight-bold">@money('$paid')</td>
					</tr>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Balance:</td>
						<td class="font-weight-bold">@money('$total + $salesOrder->shippingCharges - $totalDiscount - $paid')</td>
					</tr>
				</tfoot>
			</table>
        </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('js')
	<script>
		var goodReturn = 0;
		var badReturn = 0;
		var totalQuantity = 0;
		var tr = '';
		var allReturns = 0;
		var isError = false;
		$(function () {
			$('.goodReturn').bind('change',function () {
				goodReturn = this.value;
			});
			$('.badReturn').bind('change',function () {
				badReturn = this.value;
			});
		});

		function validateReturns() {
			isError = false;
			 $('.totalQuantity').each(function () {
			 	tr = $(this).closest('tr');
				tr.find('.errorMessage').hide();
				totalQuantity = parseInt($(this).val());
				goodReturn = tr.find('.goodReturn').val();
				badReturn = tr.find('.badReturn').val();
				if(isNaN(parseInt(goodReturn))){
					goodReturn = 0;
				}
				if(isNaN(parseInt(badReturn))){
					badReturn = 0;
				}
				allReturns = parseInt(goodReturn) + parseInt(badReturn);
				if (totalQuantity < allReturns){
					tr.find('.errorMessage').show();
					isError = true;
				}
			 });
			 if(!isError){
			 	 document.getElementById('formReturn').submit();
			 }
		}
	</script>
@stop