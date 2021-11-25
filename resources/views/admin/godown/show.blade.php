@extends('adminlte::page')

@section('title', 'Godown')

@section('content_header')
    <h1>Godown</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-warehouse"></i> Godown
			</h3>
			@can('godown_update')
				<a class="btn btn-primary btn-sm float-right" href="{{ route('godown.edit',$godown->godownID) }}">
					<i class="fas fa-edit"></i> Edit Godown
				</a>
			@endcan
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Godown:</div>
					{{ $godown->name }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Balance:</div>
					{{ \App\Services\CurrencyService::getCurrencyFormatted($totalPayable) }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Address:</div>
					{{ $godown->address }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Description:</div>
					{{ $godown->description }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Date Created:</div>
					{{ $godown->dateCreated }}
				</div>
			</div>
        </div>
    </div>

	@if (count($purchaseOrders))
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Product Details (<strong>NOT shifted to Stock</strong>)
			</h3>
		</div>
		<div class="card-body">
			<table class="table table-hover">
				<thead>
					<tr class="table-danger">
						<th>Product</th>
						<th>Total Purchased</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($purchaseOrders as $purchaseOrder)
						@foreach ($purchaseOrder->purchaseOrderDetails as $purchaseOrderDetail)
							<tr class="table-warning">
								<td>{{ $purchaseOrderDetail->product->productName }} ({{ $purchaseOrderDetail->product->category->categoryName }})</td>
								<td>
									{{ $purchaseOrderDetail->quantity }}
									@if ($purchaseOrderDetail->quantity > 0)
										Qty :
										@if ($purchaseOrderDetail->product->maximumUnit->symbol == 'Qty')
											@if ($purchaseOrderDetail->product->unitsInProduct > 1)
												{{ $purchaseOrderDetail->quantityUnits }} items
											@endif
										@else
											{{ $purchaseOrderDetail->quantityUnits }} {{ $purchaseOrderDetail->product->maximumUnit->symbol }}(s)
										@endif
									@endif
								</td>
							</tr>
						@endforeach
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	@endif

	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Product Details (Shifted to Stock)
			</h3>
		</div>
		<div class="card-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Product</th>
						<th>Total Purchased</th>
						<th>In Stock</th>
						<th>Sold</th>
						@if ($globalSettings['client_settings.is_show_damaged_units'] == 1)
						<th>Damaged</th>
						@endif
						<th>Good Sales Return</th>
						<th>Bad Sales Return</th>
					</tr>
				</thead>
				<tbody>
					@if ($godownStocks)
						@foreach ($godownStocks as $stockData)
							<tr>
								<td>{{ $stockData->productName }}</td>
								<td>
									{{ $stockData->totalPurchasedQuantity }}
									@if ($stockData->totalPurchasedQuantity > 0)
										Qty :
										@if ($stockData->symbol == 'Qty')
											@if ($stockData->unitsInProduct > 1)
												{{ $stockData->totalPurchasedUnits }} items
											@endif
										@else
											{{ $stockData->totalPurchasedUnits }} {{ $stockData->symbol }}(s)
										@endif
									@endif
								</td>
								<td>
									{{ $stockData->inStockQuantity }}
									@if ($stockData->inStockQuantity > 0)
										Qty :
										@if ($stockData->symbol == 'Qty')
											@if ($stockData->unitsInProduct > 1)
												{{ $stockData->inStockUnits }} items
											@endif
										@else
											{{ $stockData->inStockUnits }} {{ $stockData->symbol }}(s)
										@endif
									@endif
								</td>
								<td>
									{{ $stockData->totalSoldQuantity }}
									@if ($stockData->totalSoldQuantity > 0)
										Qty :
										@if ($stockData->symbol == 'Qty')
											@if ($stockData->unitsInProduct > 1)
												{{ $stockData->totalSoldUnits }} items
											@endif
										@else
											{{ $stockData->totalSoldUnits }} {{ $stockData->symbol }}(s)
										@endif
									@endif
								</td>
								@if ($globalSettings['client_settings.is_show_damaged_units'] == 1)
								<td>
									{{ $stockData->totalDamagedQuantity }}
									@if ($stockData->totalDamagedQuantity > 0)
										Qty :
										@if ($stockData->symbol == 'Qty')
											@if ($stockData->unitsInProduct > 1)
												{{ $stockData->totalDamagedUnits }} items
											@endif
										@else
											{{ $stockData->totalDamagedUnits }} {{ $stockData->symbol }}(s)
										@endif
									@endif
								</td>
								@endif
								<td>
									{{ $stockData->totalGoodSalesReturnQuantity }}
									@if ($stockData->totalGoodSalesReturnQuantity > 0)
										Qty :
										@if ($stockData->symbol == 'Qty')
											@if ($stockData->unitsInProduct > 1)
												 {{ $stockData->totalGoodSalesReturnUnits }} items
											@endif
										@else
											{{ $stockData->totalGoodSalesReturnUnits }} {{ $stockData->symbol }}(s)
										@endif
									@endif
								</td>
								<td>
									{{ $stockData->totalBadSalesReturnQuantity }}
									@if ($stockData->totalBadSalesReturnQuantity > 0)
										Qty :
										@if ($stockData->symbol == 'Qty')
											@if ($stockData->unitsInProduct > 1)
												 {{ $stockData->totalBadSalesReturnUnits }} items
											@endif
										@else
											{{ $stockData->totalBadSalesReturnUnits }} {{ $stockData->symbol }}(s)
										@endif
									@endif
								</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="{{ $globalSettings['client_settings.is_show_damaged_units'] == 1 ? '7' : '6' }}">Still no product</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>

	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Ledger
			</h3>
		</div>
		<div class="card-body">
			<table class="table table-hover" id="myTable">
				<thead>
					<tr>
						<th>Date</th>
						<th>Account</th>
						<th>Description</th>
						<th>Debit</th>
						<th>Credit</th>
						<th>Balance</th>
					</tr>
				</thead>
					<?php
						$balance = 0;
					?>
					@foreach ($godownTransactions as $transaction)
						@foreach ($transaction->transactionDetails as $transactionDetail)
							@if ($transactionDetail->headID == \Config::get('constants.account_heads.accounts_payable') || $transactionDetail->headID == \Config::get('constants.account_heads.accounts_receivable'))
							<?php
								$amount = $transactionDetail->amount / $transaction->exchangeRate;
								if (\Config::get('constants.client_settings.operatorToConvertToPKR') == '*') {
									$amount = $transactionDetail->amount * $transaction->exchangeRate;
								}
								if ($transactionDetail->isDebit == 1) {
									$balance+=$amount;
								} else {
									$balance-=$amount;
								}
							?>
							<tr>
								<td>{{ date('d-m-Y',strtotime($transaction->dateCreated)) }}</td>
								<td>{{ $transactionDetail->head->headName }}</td>
								<td>{{ $transactionDetail->description }}</td>
								<td>@if ($transactionDetail->isDebit == 1) {{ $amount }} @endif</td>
								<td>@if ($transactionDetail->isDebit == 0) {{ $amount }} @endif</td>
								<td>{{ $balance }}</td>
							</tr>
							@endif
						@endforeach
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" class="font-weight-bold text-right">Total:</td>
						<td>{{ $balance }}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
