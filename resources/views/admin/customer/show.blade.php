@extends('adminlte::page')

@section('title', 'Customer')

@section('content_header')
    <h1>Customer</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-male"></i> {{ $customer->customerName }}
            </h3>
            @can('customer_update')
            <a class="btn btn-primary btn-sm float-right" href="{{route('customer.edit',$customer->customerID)}}">
                <i class="fas fa-edit"></i> Edit Customer
            </a>
            @endcan
        </div>
        <div class="card-body">
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Name:</div>
					{{ $customer->customerName }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Balance:</div>
					{{ \App\Services\CurrencyService::getCurrencyFormatted($totalPayable) }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Shop Name:</div>
					{{ $customer->shopName }}
				</div>
                <div class="col">
					<div class="font-weight-bold">Sales Agent:</div>
					{{ $customer->salesAgent ? $customer->salesAgent->staffName : '' }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Phone #:</div>
					{{ $customer->phone }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Address:</div>
					{{ $customer->address }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Description:</div>
					{{ $customer->description }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Created On:</div>
					{{ $customer->dateCreated }}
				</div>
			</div>
        </div>
    </div>

	<div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i> {{ $customer->customerName }} Ledger
            </h3>
        </div>
        <div class="card-body">
			<table class="table table-hover" id="myTable">
				<thead>
					<tr>
                        <th></th>
						<th>Date</th>
						<th>Account</th>
                        <th>Bill #</th>
						<th>Description</th>
						<th>Debit</th>
						<th>Credit</th>
						<th>Balance</th>
					</tr>
				</thead>
                <tbody>
					<?php
						$balance = 0;
					?>
					@foreach ($customerTransactions as $transaction)
						@foreach ($transaction->transactionDetails as $transactionDetail)
							@if ($transactionDetail->headID == \Config::get('constants.account_heads.customer_receivable') || $transactionDetail->headID == \Config::get('constants.account_heads.customer_payable'))
							<?php
								if ($transactionDetail->isDebit == 1) {
									$balance+=$transactionDetail->amount;
								} else {
									$balance-=$transactionDetail->amount;
								}
							?>
							<tr>
                                <td><sup>{{ $transaction->transactionID }}</sup></td>
								<td>
                                    {{ date('d-m-Y',strtotime($transaction->transactionDate)) }}
                                </td>
								<td>{{ $transactionDetail->head->headName }}</td>
                                <td>
                                    @if (!empty($transaction->salesOrders[0]))
                                        <a target="_blank" href="{{route('sales.show',$transaction->salesOrders[0]->salesOrderID)}}">{{ $transaction->salesOrders[0]->bookSerial }}</a>
                                    @else (!empty($transaction->transactionTypeNumber))
                                        {{ $transaction->transactionTypeNumber }}
                                    @endif
                                </td>
								<td>{{ $transactionDetail->description }}</td>
								<td>@if ($transactionDetail->isDebit == 1) {{ $transactionDetail->amount }} @endif</td>
								<td>@if ($transactionDetail->isDebit == 0) {{ $transactionDetail->amount }} @endif</td>
								<td></td>
							</tr>
							@endif
						@endforeach
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    @include('partials.runningTotalJS',['tableID' => 'myTable','debitColumn' => 6, 'creditColumn' => 7, 'balanceColumn' => 8,'debitOperator' => '+','creditOperator' => '-'])
@stop
