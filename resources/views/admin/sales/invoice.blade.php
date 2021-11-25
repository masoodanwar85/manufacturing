@extends('adminlte::page')

@section('title', 'Sales Order')

@section('content_header')
    <h1>Sales Order Invoice</h1>
@stop

@section('content')
	<div class="row">
		<div class="col-12">
			<!-- Main content -->
			<div class="invoice p-3 mb-3">
				<!-- title row -->
				<div class="row">
					<div class="col-12">
						<h4>
							<i class="fas fa-globe"></i> {{ $client->firstName }} {{ $client->lastName}}
							<small class="float-right">Date: {{ date('d/m/Y') }}</small>
						</h4>
					</div>
					<!-- /.col -->
				</div>
				<!-- info row -->
				<div class="row invoice-info">
					<div class="col-sm-4 invoice-col">
						From
						<address>
							<strong>{{ $client->firstName }} {{ $client->lastName}}</strong><br>
							{{ $client->address }}<br>
							{{ $client->city }}, {{ $client->state }}, Pakistan<br>
							Phone: {{ $client->phone }}
						</address>
					</div>
					<!-- /.col -->
					<div class="col-sm-4 invoice-col">
						To
						<address>
							<strong>{{ $salesOrder->customer->customerName }}</strong><br>
							{{ $salesOrder->customer->shopName }}<br>
							{{ $salesOrder->customer->address }}<br>
							Phone: {{ $salesOrder->customer->phone }}
						</address>
					</div>
					<!-- /.col -->

					<div class="col-sm-4 invoice-col">
						<b>Book Serial #:</b> {{ $salesOrder->bookSerial }}<br>
						<b>Invoice #:</b> {{ $salesOrder->invoiceNumber }}<br>
						<b>Order ID:</b> {{ $salesOrder->salesOrderID }}<br>
						<b>Discount:</b> {{ $salesOrder->discount }}<br>
						<b>Shipping Charges:</b> {{ $salesOrder->shippingCharges }}<br>
						<b>Payment Due:</b> {{ $salesOrder->paymentDueDate }}<br><br>
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->

				<!-- Table row -->
				<div class="row">
					<div class="col-12 table-responsive">
						<h3>Product Details</h3>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Product</th>
									<th>Quantity</th>
									<th>Units</th>
									<th>Sale Price</th>
									<th>Sub Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$total = 0;
									$paid = 0;
									$grandTotal = 0;
								?>
								@foreach($salesOrder->stockDetailStatuses as $stockDetailStatus)
									<tr>
										<td>{{$stockDetailStatus->stockDetail->product->productName}} ({{$stockDetailStatus->stockDetail->product->category->categoryName}})</td>
										<td>{{$stockDetailStatus->quantity}}</td>
										<td>
											{{$stockDetailStatus->quantityUnits}}
											{{ $stockDetailStatus->stockDetail->product->maximumUnit->symbol == 'Qty' ? 'Items' : $stockDetailStatus->stockDetail->product->maximumUnit->symbol }}
										</td>
										<td>@money('$stockDetailStatus->salePrice')</td>
										<td>@money('$stockDetailStatus->quantityUnits * $stockDetailStatus->salePrice')</td>
									</tr>
									<?php
										$total+= $stockDetailStatus->quantityUnits * $stockDetailStatus->salePrice;
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
						</table>
					</div>
					<!-- /.col -->
				</div><hr />
				<!-- /.row -->

				<div class="row">
					<!-- accepted payments column -->
					<div class="col-6">
						<p class="lead">Payment Methods:</p>
						<p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
							Account #: 12345678<br />
							Bank: 		Alfalah<br />
							Title: 		Masood Anwar<br /><hr />
							Account #: 12345678<br />
							Bank: 		Alfalah<br />
							Title: 		Masood Anwar<br />
						</p>
					</div>
					<!-- /.col -->
					<div class="col-6">
						<p class="lead">Amount Due on {{ date('d/m/Y',strtotime($salesOrder->paymentDueDate)) }}</p>

						<div class="table-responsive">
							<table class="table">
								<tbody>
									<tr>
										<th style="width:50%">Subtotal:</th>
										<td>@money('$total')</td>
									</tr>
									<tr>
										<th>Discount</th>
										<td>{{ $salesOrder->discount }}</td>
									</tr>
									<tr>
										<th>Shipping:</th>
										<td>{{ $salesOrder->shippingCharges }}</td>
									</tr>
									<tr>
										<th>Total:</th>
										<td>@money('$grandTotal')</td>
									</tr>
									<tr>
										<th>Balance:</th>
										<td>@money('$grandTotal - $paid')</td>
									</tr>
								</tbody>
							</table>
						</div>
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
