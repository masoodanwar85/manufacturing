@extends('adminlte::page')

@section('title', 'Transport')

@section('content_header')
    <h1>Transport</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-truck"></i> Transport
			</h3>
			@can('transport_update')
				<a class="btn btn-primary btn-sm float-right" href="{{ route('transport.edit',$transport->transportID) }}">
					<i class="fas fa-edit"></i> Edit Transport
				</a>
			@endcan
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Name:</div>
					{{ $transport->name }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Balance:</div>
					{{ \App\Services\CurrencyService::getCurrencyFormatted($totalPayable) }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Owner:</div>
					{{ $transport->owner }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Vehicle Number:</div>
					{{ $transport->vehicleNumber }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Description:</div>
					{{ $transport->description }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Date Created:</div>
					{{ $transport->dateCreated }}
				</div>
			</div>
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
					@foreach ($transportTransactions as $transaction)
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
