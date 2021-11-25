@extends('adminlte::page')

@section('title', 'Purchase Order')

@section('content_header')
    <h1>Customized Shift Purchase Order To Stock</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-cart-plus"></i> Customized Shift Purchase Order
            </h3>
        </div>
        <div class="card-body">
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Date:</div>
					{{ $purchase->purchaseOrderDate }}
				</div>
				<div class="col">
					@if ($purchase->supplier)
						<div class="font-weight-bold">Supplier:</div>
						{{ $purchase->supplier->supplierName }}
					@else
						<div class="font-weight-bold">Customer:</div>
						{{ $purchase->customer->customerName }}
					@endif
				</div>
				<div class="col">
					<div class="font-weight-bold">Batch:</div>
					{{ $purchase->batch->batchName }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Created On:</div>
					{{ $purchase->dateCreated }}
				</div>
			</div><br />
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Description:</div>
					{{ $purchase->description }}
				</div>
			</div>
			<hr />

			<h3>Product Details</h3>
			<table class="table">
				<thead>
					<tr>
						<th>Product</th>
						<th>Quantity</th>
						<th>Damaged</th>
						<th>Exchange Rate</th>
						<th>Per Unit Price</th>
						<th>Total Amount</th>
						<th>Total Amount (PKR)</th>
                        <th>Quantity to Shift</th>
					</tr>
				</thead>
				<tbody>
                    <form class="form-horizontal" action="{{ route('purchase.doCustomizedShift') }}" method="POST">
        				@csrf
                        <input type="hidden" name="purchaseOrderID" value="{{$purchase->purchaseOrderID}}" />
    					<?php
    						$total = 0;
    						$totalInPKR = 0;
    					?>
    					@foreach($purchase->purchaseOrderDetails as $purchaseOrderDetail)
    						<tr>
    							<td>{{$purchaseOrderDetail->product->productName}} ({{$purchaseOrderDetail->product->category->categoryName}})</td>
    							<td>{{$purchaseOrderDetail->quantity}}</td>
								<td>{{$purchaseOrderDetail->damaged}}</td>
    							<td>{{$purchaseOrderDetail->exchangeRate}}</td>
    							<td>{{$purchaseOrderDetail->perUnitPrice}}</td>
    							<td>{{$purchaseOrderDetail->quantity * $purchaseOrderDetail->perUnitPrice}}</td>
    							<td>{{$purchaseOrderDetail->quantity * $purchaseOrderDetail->perUnitPrice * $purchaseOrderDetail->exchangeRate}}</td>
                                <td><input type="number" max="{{$purchaseOrderDetail->quantity}}" name="quantity_{{$purchaseOrderDetail->purchaseOrderDetailID}}" value="{{$purchaseOrderDetail->quantity}}" class="form-control" /></td>
    						</tr>
    						<?php
    							$total+= $purchaseOrderDetail->quantity * $purchaseOrderDetail->perUnitPrice;
    							$totalInPKR+= $purchaseOrderDetail->quantity * $purchaseOrderDetail->perUnitPrice * $purchaseOrderDetail->exchangeRate;
    						?>
    					@endforeach
                        <tr>
                            <td colspan="8">
                                <div class="text-right">
                                    <input class="btn btn-primary" type="submit" value="Shift to Stock">
                                </div>
                            </td>
                        </tr>
                    </form>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="6" class="font-weight-bold text-right">Total:</td>
						<td class="font-weight-bold">{{$total}}</td>
						<td class="font-weight-bold">{{$totalInPKR}}</td>
					</tr>
				</tfoot>
			</table>
			<hr />

			<h3>Expense Details</h3>
			<table class="table">
				<thead>
					<tr>
                        <th></th>
						<th>Expense</th>
						<th>Exchange Rate</th>
						<th>Amount</th>
						<th>Amount (PKR)</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$totalInPKR = 0;
					?>
					@foreach($purchase->transactions as $purchaseOrderTransactions)
						@if ($purchaseOrderTransactions->pivot->isExpense == 1)
							<tr>
                                <td>
                                    @if ($lastGodownHeadID == $purchaseOrderTransactions->transactionDetails[0]->subHead->headID)
                                        <i title="Last Godown" class="fas fa-check-circle"></i>
                                    @endif
                                </td>
								<td>
									{{$purchaseOrderTransactions->transactionDetails[0]->subHead->headName}}
								</td>
								<td>{{$purchaseOrderTransactions->exchangeRate}}</td>
								<td>{{$purchaseOrderTransactions->transactionDetails[0]->amount / $purchaseOrderTransactions->exchangeRate}}</td>
								<td>{{$purchaseOrderTransactions->transactionDetails[0]->amount}}</td>
							</tr>
							<?php
								$totalInPKR+= $purchaseOrderTransactions->transactionDetails[0]->amount;
							?>
						@endif
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" class="font-weight-bold text-right">Total:</td>
						<td class="font-weight-bold">{{$totalInPKR}}</td>
					</tr>
				</tfoot>
			</table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
