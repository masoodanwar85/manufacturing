@extends('adminlte::page')
@section('title', 'Trial Balance')

@section('content_header')
    <h1>Trial Balance</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-hand-holding-usd"></i> Trial Balance
			</h3>
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-sm table-hover ajaxTable datatable datatable-accountHead">
				<thead>
					<tr>
						<!-- <th>Parent Head</th> -->
						<th>Head</th>
						<th>Debit (Rs.)</th>
						<th>Credit (Rs.)</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$debitBalance = 0;
						$creditBalance = 0;
						$currentTransID = 0;
						$trClass = "odd";
					?>
					@foreach ($transactionDetails as $transactionDetail)
					<?php
						$amount = $transactionDetail->amount / $transactionDetail->transaction->exchangeRate;
						if (\Config::get('constants.client_settings.operatorToConvertToPKR') == '*') {
							$amount = $transactionDetail->amount * $transactionDetail->transaction->exchangeRate;
						}
						if ($transactionDetail->isDebit == 1) {
							$debitBalance+=$amount;
						} else {
							$creditBalance+=$amount;
						}

						if ($currentTransID != $transactionDetail->transactionID) {
							if ($trClass == 'even') {
								$trClass = 'odd';
							} else {
								$trClass = 'even';
							}
							$currentTransID = $transactionDetail->transactionID;
						}

					?>
					<tr class="{{$trClass}}">
						<!-- <td>
							{{ $transactionDetail->subHead->rootHead->headName }}
						</td> -->
						<td title="{{ $transactionDetail->subHead->headName }}" style="border: 1px solid #dee2e6;">
							@if ($transactionDetail->isDebit == 0) &emsp;&emsp;&emsp;&emsp; @endif
							{{ $transactionDetail->head->headName }}
						</td>
						<td style="border: 1px solid #dee2e6;">
							@if ($transactionDetail->isDebit == 1)
								@money('$amount','')
							@endif
						</td>
						<td style="border: 1px solid #dee2e6;">
							@if ($transactionDetail->isDebit == 0)
								@money('$amount','')
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr class="table-primary" style="font-weight:bold;">
						<td align="right">Total</td>
						<td>@money('$debitBalance','')</td>
						<td>@money('$creditBalance','')</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
@stop
@section('css')
    <style type="text/css">
		tr.even {
			background-color: rgba(0,0,0,.05);
		}
	</style>
@stop
