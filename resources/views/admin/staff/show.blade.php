@extends('adminlte::page')

@section('title', 'Staff')

@section('content_header')
    <h1>Staff</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-users"></i> Staff
			</h3>
			@can('staff_edit')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('staff.edit',$staff->staffID) }}">
				<i class="fas fa-edit"></i> Edit Staff
			</a>
			@endcan
		</div>
		<div class="card-body">
            <div class="row">
				<div class="col">
					<div class="font-weight-bold">Name:</div>
					{{ $staff->staffName }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Staff Type:</div>
					{{ $staff->staffType->staffType }}
				</div>
                <div class="col">
					<div class="font-weight-bold">Payment Frequency:</div>
					{!! $staff->paymentFrequencyID == 1 ? 'Monthly' : 'Daily' !!}
				</div>
				<div class="col">
					<div class="font-weight-bold">Payment Amount:</div>
					{{ $staff->paymentAmount }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Date Joined:</div>
					{{ $staff->dateJoined }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Date Created:</div>
					{{ $staff->dateCreated }}
				</div>
			</div>
        </div>
    </div>

    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i> {{ $staff->staffName }} Ledger
            </h3>
        </div>
        <div class="card-body">
			<table class="table table-hover" id="myTable">
				<thead>
					<tr>
						<th>Date</th>
						<th>Account</th>
                        <th>Bill #</th>
						<th>Description</th>
						<th>Debit</th>
						<th>Credit</th>
						<th>Balance</th>
					</tr>
				</thead>
                    <?php
                        $last_month = '';
                    ?>
					@foreach ($staffTransactions as $transaction)
						@foreach ($transaction->transactionDetails as $transactionDetail)
							<?php
								$thisTransactionAmount = round($transactionDetail->amount,\Config::get('constants.client_settings.decimal_places'));
							?>
                            @if ($transactionDetail->headID == \Config::get('constants.account_heads.staff_receivable') || $transactionDetail->headID == \Config::get('constants.account_heads.staff_payable'))
                            <?php
                            if ($last_month == '') {
                                $last_month = date('mY',strtotime($transaction->transactionDate));
                            }
                            ?>
                            <tr {!! $last_month == date('mY',strtotime($transaction->transactionDate)) ? 'class="table-success"' : '' !!}>
                                <td>
                                    {{ date('d-m-Y',strtotime($transaction->transactionDate)) }}
                                </td>
                                <td>{{ $transactionDetail->head->headName }}</td>
                                <td>
                                    {{ $transaction->transactionTypeNumber }}
                                </td>
                                <td>{{ $transactionDetail->description }}</td>
                                <td>@if ($transactionDetail->isDebit == 1) {{ $thisTransactionAmount }} @endif</td>
                                <td>@if ($transactionDetail->isDebit == 0) {{ $thisTransactionAmount }} @endif</td>
                                <td></td>
                            </tr>
                            @elseif ($transactionDetail->headID == \Config::get('constants.account_heads.salaries_payable'))
                            <?php
                            if ($last_month == '') {
                                $last_month = date('mY',strtotime($transaction->transactionDate));
                            }
                            ?>
                                <tr {!! $last_month == date('mY',strtotime($transaction->transactionDate)) ? 'class="table-success"' : '' !!}>
                                    <td>
                                        {{ date('d-m-Y',strtotime($transaction->transactionDate)) }}
                                    </td>
                                    <td>{{ $transactionDetail->head->headName }}</td>
                                    <td>
                                        {{ $transaction->transactionTypeNumber }}
                                    </td>
                                    <td>{{ $transactionDetail->description }}</td>
                                    <td>@if ($transactionDetail->isDebit == 1) {{ $thisTransactionAmount }} @endif</td>
                                    <td>@if ($transactionDetail->isDebit == 0) {{ $thisTransactionAmount }} @endif</td>
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
    @include('partials.runningTotalJS',['tableID' => 'myTable','debitColumn' => 5, 'creditColumn' => 6, 'balanceColumn' => 7,'debitOperator' => '+','creditOperator' => '-'])
@stop
