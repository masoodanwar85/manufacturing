@extends('adminlte::page')

@section('title', 'Sales Order')

@section('content_header')
    <h1>Sales Order Invoice</h1>
@stop

@section('content')
	<div class="row">
		<div class="col-12">
			<div class="invoice p-3 mb-3">
				<div class="row" style="border-bottom:5px solid grey;">
					<div class="col-2">
						<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Pepsi_logo_2014.svg/1200px-Pepsi_logo_2014.svg.png" width="70" />
                    </div>
                    <div class="col-2">
                        <h3>SB &amp; Co.</h3>
                    </div>
                    <div class="col-5">
                        <h3>SADA BAHAR TRADERS</h3>
                        <h5>
                            0848-413803, 03328083801<br />
                            Near Veternary Civil Hospital, Karachi Road<br />
                            Khuzdar
                        </h5>
					</div>
                    <div class="col-3">
                        <h3 class="text-right">SALE INVOICE</h3>
                        <h5 class="text-right">
                            INV-{{ $salesOrder->invoiceNumber }}<br />
                            {{ date('d-M-Y') }}
                        </h5>
                    </div>
				</div>
				<!-- info row -->
				<div class="row invoice-info">
					<div class="col-sm-6 invoice-col">
						<h4>Customer ID: {{ $salesOrder->customer->customerID }}</h4>
                        <address>
							<strong>{{ $salesOrder->customer->customerName }}</strong><br>
							{{ $salesOrder->customer->shopName }}<br>
							{{ $salesOrder->customer->address }}<br>
							Phone: {{ $salesOrder->customer->phone }}
						</address>
					</div>
					<div class="col-sm-4 invoice-col">

					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->

				<!-- Table row -->
				<div class="row">
					<div class="col-12 table-responsive">
						<table class="table table-striped table-sm">
							<thead>
								<tr>
									<th>#</th>
                                    <th>Product</th>
                                    <th>Unit Price</th>
									<th>Quantity</th>
									<th class="text-right">Sub Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
                                    $totalQty = 0;
									$total = 0;
									$paid = 0;
									$grandTotal = 0;
								?>
								@foreach($salesOrder->stockDetailStatuses as $stockDetailStatus)
									<tr>
                                        <td>{{ $loop->iteration }}</td>
										<td>{{$stockDetailStatus->stockDetail->product->productName}} ({{$stockDetailStatus->stockDetail->product->category->categoryName}})</td>
										<td>@money('$stockDetailStatus->salePrice')</td>
                                        <td>{{$stockDetailStatus->quantity}}</td>
										<td class="text-right">@money('$stockDetailStatus->quantity * $stockDetailStatus->salePrice')</td>
									</tr>
									<?php
										$total+= $stockDetailStatus->quantity * $stockDetailStatus->salePrice;
                                        $totalQty+=$stockDetailStatus->quantity;
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
					<div class="col-8">
						<h5 class="text-right">
                            Total Quantity: {{ $totalQty }}<br />
                        </h5>
                        <h5>
                            Salesman: {{ $salesOrder->salesAgent ? $salesOrder->salesAgent->staffName : '' }}
                        </h5>
					</div>
					<!-- /.col -->
					<div class="col-4">
						<div class="table-responsive">
							<table class="table table-striped table-sm">
								<tbody>
									<tr>
										<th class="text-right" style="border-top:0px;">Total Amount:</th>
										<td class="text-right" style="border-top:0px;">@money('$grandTotal')</td>
									</tr>
                                    <tr>
                                        <th class="text-right" style="border-top:0px;width:50%">Discount:</th>
										<td class="text-right">@money('$salesOrder->discount')</td>
									</tr>
									<tr>
                                        <th class="text-right" style="border-top:0px;width:50%">Paid Amount:</th>
										<td class="text-right">@money('$paid')</td>
									</tr>
									<tr>
										<th class="text-right" style="border-top:0px;">Balance:</th>
										<td class="text-right">@money('$grandTotal - $paid')</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
                    <div class="col-12">
                        <p>
                        نوٹ: برائے مہربانی مال وصول کرتے وقت پراڈکٹ کی ایکسپائری اور لیکیج ضرور چیک کر لیں۔ بعد میں کمپنی کی کسی
                        قسم کی کوئی ذمہ داری نہیں ہوگی
                        </p>
                    </div>
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
