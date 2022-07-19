@extends('adminlte::page')

@section('title', 'Sales Order')

@section('content_header')
    <h1>Sales Order</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-share"></i> Sales Order
            </h3>
			@can('sales_update')
				<a class="btn btn-primary btn-sm float-right" href="{{route('sales.edit',$salesOrder->salesOrderID)}}">
					<i class="fas fa-edit"></i> Edit Sales Order
				</a>
			@endcan
			<div class="float-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			<a class="btn btn-warning btn-sm float-right" href="{{route('sales.invoice',$salesOrder->salesOrderID)}}">
                <i class="fas fa-file-invoice"></i> Invoice
            </a>
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
            <?php $colspanValue = 4; ?>
			<table class="table">
				<thead>
					<tr>
						<th>Product</th>
						<th>Quantity</th>
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
					?>
					@foreach($salesOrder->stockDetailStatuses as $stockDetailStatus)
						<tr>
							<td>{{$stockDetailStatus->stockDetail->product->productName}} ({{$stockDetailStatus->stockDetail->product->category->categoryName}})</td>
							<td>{{$stockDetailStatus->quantity }}</td>
							<td>{{$stockDetailStatus->godown->name}}</td>
							<td>@money('$stockDetailStatus->salePrice * $stockDetailStatus->stockDetail->product->unitsInProduct')/-</td>
                            @if ($globalSettings['client_settings.is_show_discount_per_product'] == 1)
                                <td>@money('$stockDetailStatus->discount')/-</td>
                            @endif
							<td>@money('($stockDetailStatus->quantity * $stockDetailStatus->salePrice)-($stockDetailStatus->quantity * $stockDetailStatus->discount)')/-</td>
						</tr>
						<?php
							$total+= ($stockDetailStatus->quantityUnits * $stockDetailStatus->salePrice) - ($stockDetailStatus->quantityUnits * $stockDetailStatus->discount);
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
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Sub Total:</td>
						<td class="font-weight-bold">@money('$total')</td>
					</tr>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Discount:</td>
						<td class="font-weight-bold">@money('$salesOrder->discount')</td>
					</tr>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Shipping Charges:</td>
						<td class="font-weight-bold">@money('$salesOrder->shippingCharges')</td>
					</tr>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Grand Total:</td>
						<td class="font-weight-bold">@money('$total + $salesOrder->shippingCharges - $salesOrder->discount')</td>
					</tr>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Paid:</td>
						<td class="font-weight-bold">@money('$paid')</td>
					</tr>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Balance:</td>
						<td class="font-weight-bold">@money('($total + $salesOrder->shippingCharges - $salesOrder->discount) - $paid')</td>
					</tr>
				</tfoot>
			</table>
			<hr />
        </div>
    </div>

    <div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Profit / Loss
			</h3>
		</div>
		<div class="card-body">
			<table class="table table-hover">
				<thead>
					<tr class="table-primary">
						<th>Product</th>
						<th>Purchase Price</th>
                        <th>Sale Price</th>
                        @if ($globalSettings['client_settings.is_show_discount_per_product'] == 1)
                            <th>Discount</th>
                            <th>Net Sale Price</th>
                        @endif
                        <th>Net Profit/Loss</th>
					</tr>
				</thead>
				<tbody>
                    <?php
						$totalProfitLoss = 0;
                        $rowPurchaseTotal = 0;
                        $rowSaleTotal = 0;
                        $rowProfitLoss = 0;
                        $totalPurchases = 0;
                        $totalSales = 0;
                        $totalNetSales = 0;
                        $totalDiscount = 0;
					?>
					@foreach($salesOrder->stockDetailStatuses as $stockDetailStatus)
                        <?php
                            $rowPurchaseTotal = $stockDetailStatus->stockDetail->purchasePrice * $stockDetailStatus->quantityUnits;
                            $rowSaleTotal = $stockDetailStatus->quantity * $stockDetailStatus->salePrice;
                            $rowDiscountTotal = $stockDetailStatus->quantity * $stockDetailStatus->discount;
                            $rowProfitLoss = $rowSaleTotal - $rowDiscountTotal - $rowPurchaseTotal;
                            $totalPurchases += $rowPurchaseTotal;
                            $totalSales += $rowSaleTotal;
                            $totalNetSales += $rowSaleTotal - $rowDiscountTotal;
                            $totalDiscount += $rowDiscountTotal;
                        ?>
						<tr>
							<td>{{$stockDetailStatus->stockDetail->product->productName}} ({{$stockDetailStatus->stockDetail->product->category->categoryName}})</td>
                            <td>@money('$rowPurchaseTotal')</td>
							<td>@money('$rowSaleTotal')</td>
                            @if ($globalSettings['client_settings.is_show_discount_per_product'] == 1)
                                <td>@money('$rowDiscountTotal')</td>
                                <td>@money('$rowSaleTotal - $rowDiscountTotal')</td>
                            @endif
                            <td>@money('$rowProfitLoss')</td>
						</tr>
					@endforeach
				</tbody>
                <tfoot>
                    <tr style="font-weight:bold;">
                        <td class="text-right">Total:</td>
                        <td>@money('$totalPurchases')</td>
                        <td>@money('$totalSales')</td>
                        <td>@money('$totalDiscount')</td>
                        <td>@money('$totalNetSales')</td>
                        <td>@money('$totalSales - $totalDiscount - $totalPurchases')</td>
                    </tr>
                </tfoot>
			</table>
		</div>
	</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
