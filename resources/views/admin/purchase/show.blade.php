@extends('adminlte::page')

@section('title', 'Purchase Order')

@section('content_header')
    <h1>Purchase Order</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-cart-plus"></i> Purchase Order
            </h3>
			@if ($purchase->isLocked == 0)
	            @can('purchase_order_update')
	            <a class="btn btn-primary btn-sm float-right" href="{{route('purchase.edit',$purchase->purchaseOrderID)}}">
	                <i class="fas fa-edit"></i> Edit Purchase Order
	            </a>
	            @endcan

    			<div class="float-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
    			@can('stock_create')
                {{-- <a onClick="return confirm('Are you sure you want to shift to stock?\nThis action is irreversible and purchase order will be locked.');" class="btn btn-warning btn-sm float-right" href="{{route('purchase.shift',$purchase->purchaseOrderID)}}">
                    <i class="fas fa-dolly-flatbed"></i> Shift to Stock
                </a> --}}
                    @if ($purchase->godown)
                        <div class="btn-group float-right">
                            <button type="button" class="btn btn-warning btn-xs">
            					<a onClick="return confirm('Are you sure you want to shift to stock?\nThis action is irreversible and purchase order will be locked.');" class="btn btn-xs btn-warning" href="{{route('purchase.shift',$purchase->purchaseOrderID)}}">
            						<i class="fas fa-dolly-flatbed"></i> Shift to Stock
            					</a>
            				</button>
            				<button type="button" class="btn btn-warning btn-xs btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
            					<span class="sr-only">Toggle Dropdown</span>
            				</button>
            				<div class="dropdown-menu" role="menu">
            					<a class="dropdown-item" onClick="return confirm('Are you sure you want to shift to stock?\nThis action is irreversible and purchase order will be locked.');" class="btn btn-xs btn-warning" href="{{route('purchase.customizeShift',$purchase->purchaseOrderID)}}">Customize</a>
            				</div>
            			</div>
                    @else
                        <span class="right badge badge-danger float-right">Please Select Unload Godown</span>
                    @endif
                @endcan
            @endif
        </div>
        <div class="card-body">
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Date:</div>
					{{ $purchase->purchaseOrderDate }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Balance:</div>
					{{ \App\Services\CurrencyService::getCurrencyFormatted($totalPayable) }}
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
					<div class="font-weight-bold">Godown:</div>
					{{ $purchase->godown ? $purchase->godown->name : '' }}
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
        </div>
    </div>

	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Product Details
			</h3>
		</div>
		<div class="card-body">
            <?php
                $colspanValue = 5;
            ?>
			<table class="table">
				<thead>
					<tr>
						<th>Product</th>
						<th>Units in Product</th>
						<th>Qty</th>
						<th>Total Units</th>
						@if ($globalSettings['client_settings.is_show_damaged_units'] == 1)
						<th>Damaged Qty</th>
                        <?php $colspanValue++; ?>
						@endif
                        @if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
						<th>Exchange Rate</th>
                        <?php $colspanValue++; ?>
                        @endif
						<th>Per Unit Price</th>
						<th>Total Amount</th>
						<th>Total Amount (PKR)</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$total = 0;
						$totalInPKR = 0;
						$rowTotal = 0;
						$rowTotalInPKR = 0;
					?>
					@foreach($purchase->purchaseOrderDetails as $purchaseOrderDetail)
						<?php
							$rowTotalInPKR = $purchaseOrderDetail->perUnitPrice*$purchaseOrderDetail->quantityUnits / $purchaseOrderDetail->exchangeRate;
							if ($globalSettings['client_settings.operatorToConvertToPKR'] == '*') {
								$rowTotalInPKR = $purchaseOrderDetail->perUnitPrice*$purchaseOrderDetail->quantityUnits * $purchaseOrderDetail->exchangeRate;
							}

							$totalInPKR+= $rowTotalInPKR;
							$total+= $purchaseOrderDetail->perUnitPrice*$purchaseOrderDetail->quantityUnits;
						?>
						<tr>
							<td>{{$purchaseOrderDetail->product->productName}} ({{$purchaseOrderDetail->product->category->categoryName}})</td>
							<td>{{ $purchaseOrderDetail->product->unitsInProduct }} {{ $purchaseOrderDetail->product->maximumUnit->symbol == 'Qty' ? 'Item' : $purchaseOrderDetail->product->maximumUnit->symbol }}(s)</td>
							<td>{{ $purchaseOrderDetail->quantity}}</td>
							<td>{{ $purchaseOrderDetail->quantityUnits }} {{ $purchaseOrderDetail->product->maximumUnit->symbol == 'Qty' ? 'Item' : $purchaseOrderDetail->product->maximumUnit->symbol }}(s)</td>
							@if ($globalSettings['client_settings.is_show_damaged_units'] == 1)
							<td>{{$purchaseOrderDetail->damaged}}</td>
							@endif
                            @if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
							<td>{{$purchaseOrderDetail->exchangeRate}}</td>
                            @endif
							<td>{{$purchaseOrderDetail->perUnitPrice}}</td>
							<td>{{$purchaseOrderDetail->perUnitPrice*$purchaseOrderDetail->quantityUnits}}</td>
							<td>@money('$rowTotalInPKR')</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="{{ $colspanValue }}" class="font-weight-bold text-right">Total:</td>
						<td class="font-weight-bold">@money('$total','')</td>
						<td class="font-weight-bold">@money('$totalInPKR')</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>

	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Expense Details
			</h3>
		</div>
		<div class="card-body">
            <?php
                $colspanValue = 3;
            ?>
			<table class="table">
				<thead>
					<tr>
                        <th></th>
						<th>Expense</th>
                        @if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
						<th>Exchange Rate</th>
                        <?php $colspanValue++; ?>
                        @endif
						<th>Amount</th>
						<th>Amount (PKR)</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$rowTotalInPKR = 0;
						$totalInPKR = 0;
					?>
					@foreach($purchase->transactions as $purchaseOrderTransactions)
						@if ($purchaseOrderTransactions->pivot->isExpense == 1)
							<?php
                           	 $rowTotalInPKR = 0;
                                if ($purchaseOrderTransactions->transactionDetails[0]->amount > 0) {
                                    $rowTotalInPKR = $purchaseOrderTransactions->transactionDetails[0]->amount / $purchaseOrderTransactions->exchangeRate;
                                }
								$totalInPKR+= $rowTotalInPKR;
							?>
							<tr>
								<td>
									@if ($lastGodownHeadID == $purchaseOrderTransactions->transactionDetails[0]->subHead->headID)
										<i title="Last Godown" class="fas fa-check-circle"></i>
									@endif
								</td>
								<td>
									{{$purchaseOrderTransactions->transactionDetails[0]->subHead->headName}}
								</td>
                                @if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
								<td>{{$purchaseOrderTransactions->exchangeRate}}</td>
                                @endif
								<td>{{$purchaseOrderTransactions->transactionDetails[0]->amount}}</td>
								<td>@money('$rowTotalInPKR')</td>
							</tr>

						@endif
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="{{$colspanValue}}" class="font-weight-bold text-right">Total:</td>
						<td class="font-weight-bold">@money('$totalInPKR','Rs.')</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
