@extends('adminlte::page')

@section('title', 'Duplicates Report')

@section('content_header')
    <h1>Duplicates Report</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i> Duplicates Report
            </h3>
        </div>
        <div class="card-body">
			<table class="table table-hover table-sm">
                <thead>
                    <tr class="table-info">
                        <th>#</th>
                        <th>Book #</th>
						<th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allTransactions as $transaction)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>
								@if (substr($transaction->transactionTypeNumber,0,2) == 'RB') 
									<a href="/admin/accountHead/paymentsReceipts?isIgnoreDates=1&transactionTypeNumber={{$transaction->transactionTypeNumber}}&customerID=" target="_blank">
										{{ $transaction->transactionTypeNumber }}
									</a>
								@else
									<a href="/admin/sales?bookSerial={{$transaction->transactionTypeNumber}}" target="_blank">
										{{ $transaction->transactionTypeNumber }}
									</a>
								@endif
							</td>
							<td>
								{{$transaction->billNo}}
							</td>
						</tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app_.css">
    <style>
        @font-face {
            font-family: '_pdms_jauhar_regular';
            src: url('/fonts/_pdms_jauhar_regular.ttf');
            font-weight: bold;
        }

        .font-urdu {
            font-family: _pdms_jauhar_regular;
            font-size: 25px;
            line-height: 1.5;
            letter-spacing: 3px;
        }
    </style>
@stop
