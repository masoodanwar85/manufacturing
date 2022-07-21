@extends('adminlte::page')

@section('title', 'Sales Order')

@section('content_header')
    <h1>Sales Order Invoice</h1>
@stop

@section('content')
	<div class="row">
		<div class="col-12">
			<div class="invoice mb-3">
				<div class="row" style="border-bottom:5px solid grey;">
					<div class="col-3">
                        <h3><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Pepsi_logo_2014.svg/1200px-Pepsi_logo_2014.svg.png" width="65" /> &nbsp;&nbsp;&nbsp; SB &amp; Co.</h3>
                    </div>
                    <div class="col-6">
                        <h4>SADA BAHAR TRADERS</h4>
                        <h5>
                            0848-413803, 03328083801<br />
                            Near Veternary Civil Hospital, Karachi Road,
                            Khuzdar
                        </h5>
					</div>
                    <div class="col-3">
                        <h4 class="text-right">SALE INVOICE</h4>
                        <h5 class="text-right">
                            INV-{{ $salesOrder->invoiceNumber }}<br />
                            Bill Date: {{ $salesOrder->orderDate }}
                        </h5>
                    </div>
				</div>
				<!-- info row -->
				<div class="row invoice-info">
					<div class="col-sm-3 invoice-col">
						<h5>Customer ID: {{ $salesOrder->customer->customerID }}</h5>
                        <address>
							<strong>{{ $salesOrder->customer->customerName }}</strong><br>
							Phone: {{ $salesOrder->customer->phone }}
						</address>
					</div>
                    <div class="col-sm-4 invoice-col">
						<address>
							{{ $salesOrder->customer->shopName }}<br>
							{{ $salesOrder->customer->address }}
						</address>
					</div>
					<div class="col-sm-5 invoice-col">

					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->

				<!-- Table row -->
                <?php $colspanValue = 3; ?>
				<div class="row">
					<div class="col-12 table-responsive">
						<table class="table table-striped table-sm">
							<thead class="thead-light">
								<tr>
									<th style="background-color:#e9ecef">#</th>
                                    <th style="background-color:#e9ecef">Product</th>
                                    <th style="background-color:#e9ecef">Unit Price</th>
									<th style="background-color:#e9ecef">Quantity</th>
                                    @if ($globalSettings['client_settings.is_show_discount_per_product'] == 1)
                                        <th style="background-color:#e9ecef">Discount</th>
                                        <?php $colspanValue++; ?>
                                    @endif
									<th style="background-color:#e9ecef" class="text-right">Sub Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
                                    $totalQty = 0;
									$total = 0;
									$paid = 0;
                                    $totalDiscount = 0;
									$grandTotal = 0;
								?>
								@foreach($salesOrder->stockDetailStatuses as $stockDetailStatus)
									<tr>
                                        <td>{{ $loop->iteration }}</td>
										<td>{{$stockDetailStatus->stockDetail->product->productName}} ({{$stockDetailStatus->stockDetail->product->category->categoryName}})</td>
										<td>@money('$stockDetailStatus->salePrice')</td>
                                        <td>{{$stockDetailStatus->quantity}}</td>
                                        @if ($globalSettings['client_settings.is_show_discount_per_product'] == 1)
                                            <td>@money('$stockDetailStatus->discount')</td>
                                        @endif
										<td class="text-right">@money('($stockDetailStatus->quantity * $stockDetailStatus->salePrice)-($stockDetailStatus->quantity * $stockDetailStatus->discount)')</td>
									</tr>
									<?php
										$total+= ($stockDetailStatus->quantity * $stockDetailStatus->salePrice)-($stockDetailStatus->quantity * $stockDetailStatus->discount);
                                        $totalQty+=$stockDetailStatus->quantity;
                                        $totalDiscount += $stockDetailStatus->discount;
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
							<?php
								$grandTotal = $total + $salesOrder->shippingCharges - $salesOrder->discount;
							?>
                            <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <h5 style="margin-bottom:0px;">
                                            Salesman: {{ $salesOrder->salesAgent ? $salesOrder->salesAgent->staffName : '' }}
                                        </h5>
                                    </td>
                                    <th @if ($globalSettings['client_settings.is_show_discount_per_product'] == 0) style="width:20%;" @endif>
                                        <span style="float:left;">{{ $totalQty }}</span>
                                        @if ($globalSettings['client_settings.is_show_discount_per_product'] == 0)
                                            <span style="float:right;">Total Amount:</span>
                                        @endif
                                    </th>
                                    @if ($globalSettings['client_settings.is_show_discount_per_product'] == 1)
                                        <th class="text-right" style="width:20%;">
                                            <span style="float:left;">@money('$totalDiscount')</span>
                                            Total Amount:
                                        </th>
                                    @endif
                                    <td class="text-right">@money('$grandTotal')/-</td>
                                </tr>
                                <tr>
                                    <td colspan="{{ $colspanValue }}" style="border-top:0px;">
                                        <span style="float:left;">Printed On: {{ date('d-M-Y H:i:s') }}</span>
                                    </td>
                                    <th class="text-right" style="border-top:0px;">Discount:</th>
                                    <td class="text-right">@money('$salesOrder->discount')/-</td>
                                </tr>
                                <tr>
                                    <td rowspan="2" colspan="{{ $colspanValue }}" style="border-top:0px;">
                                        <p class="font-urdu">
                                        نوٹ: برائے مہربانی مال وصول کرتے وقت پراڈکٹ کی ایکسپائری اور لیکیج ضرور چیک کر لیں۔ بعد میں کمپنی کی کسی
                                        قسم کی کوئی ذمہ داری نہیں ہوگی
                                        </p>
                                    </td>
                                    <th class="text-right" style="border-top:0px;">Paid Amount:</th>
                                    <td class="text-right">@money('$paid')/-</td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="border-top:0px;">Balance:</th>
                                    <td class="text-right">@money('$grandTotal - $paid')/-</td>
                                </tr>
                            </tfoot>
						</table>
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
                <!-- this row will not appear when printing -->
				<div class="row no-print">
					<div class="col-12">
						<a href="javascript:void(0);" onclick="javascript:window.print();" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
						<a href="{{ route('sales.invoicePDF',$salesOrder->salesOrderID) }}" class="btn btn-primary float-right" style="margin-right: 5px;"><i class="fas fa-download"></i> Generate PDF </a>
					</div>
				</div>
			</div>
			<!-- /.invoice -->
		</div><!-- /.col -->
	</div><!-- /.row -->
@stop
@section('css')
    <style>
        @font-face {
            font-family: '_pdms_jauhar_regular';
            src: url('/fonts/_pdms_jauhar_regular.ttf');
            font-weight: bold;
        }

        .font-urdu {
            font-family: _pdms_jauhar_regular;
            font-size: 20px;
            text-align:right;
        }
    </style>
@stop
