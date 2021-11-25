@extends('adminlte::page')

@section('title', 'Supplier')

@section('content_header')
    <h1>Supplier</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-people-arrows"></i> {{ $supplier->supplierName }}
			</h3>
			@can('roles_update')
			<a class="btn btn-primary btn-sm float-right" href="{{route('supplier.edit',$supplier->supplierID)}}">
				<i class="fas fa-edit"></i> Edit Supplier
			</a>
			@endcan
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Supplier:</div>
					{{ $supplier->supplierName }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Balance:</div>
					{{ \App\Services\CurrencyService::getCurrencyFormatted($totalPayable) }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Phone #:</div>
					{{ $supplier->phone }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Address:</div>
					{{ $supplier->address }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Description:</div>
					{{ $supplier->description }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Date Created:</div>
					{{ $supplier->dateCreated }}
				</div>
			</div>
		</div>
	</div>

	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> {{ $supplier->supplierName }} Ledger
			</h3>

		</div>
		<div class="card-body">
			<table class="table table-hover" id="myTable">
				<thead>
					<tr>
                        <th></th>
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
					@foreach ($supplierTransactions as $transaction)
						@foreach ($transaction->transactionDetails as $transactionDetail)
							@if ($transactionDetail->headID == \Config::get('constants.account_heads.supplier_receivable') || $transactionDetail->headID == \Config::get('constants.account_heads.supplier_payable'))
							<?php
								$amount = $transactionDetail->amount / $transaction->exchangeRate;
								if (\Config::get('constants.client_settings.operatorToConvertToPKR') == '*') {
									$amount = $transactionDetail->amount * $transaction->exchangeRate;
								}
								$amount = round((float)($amount), \Config::get('constants.client_settings.decimal_places'));
								if ($transactionDetail->isDebit == 1) {
									$balance+=$amount;
								} else {
									$balance-=$amount;
								}
							?>
							<tr>
                                <td><sup>{{ $transaction->transactionID }}</sup></td>
								<td>{{ date('d-m-Y',strtotime($transaction->transactionDate)) }}</td>
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
						<td colspan="6" class="font-weight-bold text-right">Total:</td>
						<td>@money('$balance')</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    @include('partials.runningTotalJS',['tableID' => 'myTable','debitColumn' => 5, 'creditColumn' => 6, 'balanceColumn' => 7,'debitOperator' => '+','creditOperator' => '-'])
@stop
