@extends('adminlte::page')

@section('title', 'Accounts Report')

@section('content_header')
    <h1>Accounts Report</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i> Accounts Report
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr class="table-info">
                        <th>Date</th>
                        <th>Transaction #</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allTransactions as $transaction)
                        <tr>
                            <td>{{ date('d-m-Y',strtotime($transaction->dateCreated)) }}</td>
                            <td>{{ $transaction->transactionTypeNumber }}</td>
                            <td>
                            @foreach ($transaction->transactionDetails as $transactionDetail)
                                <div>
                                    @if ($transactionDetail->isDebit == 0)
                                        <span style="margin-left:100px;"></span>
                                    @endif
                                    {{ $transactionDetail->description }}
                                    @if ($transactionDetail->isDebit == 1)
                                        (Debit)
                                    @else
                                        (Credit)
                                    @endif
                                    ----------------------------------------------------->
                                    @if ($transactionDetail->isDebit == 1)
                                        @toPKR('$transactionDetail->amount','$transaction->exchangeRate','')
                                    @endif
                                    @if ($transactionDetail->isDebit == 0)
                                        @toPKR('$transactionDetail->amount','$transaction->exchangeRate','')
                                    @endif
                                </div>
                            @endforeach
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
