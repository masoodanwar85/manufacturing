@extends('adminlte::page')

@section('title', 'Cash In/Out Report')

@section('content_header')
    <h1>Cash In/Out Report</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i> Cash In/Out Report
            </h3>
        </div>
        <div class="card-body">
            <form>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">Month</label>
                        <input type="month" class="form-control" name="monthReport" value="{{ $monthReport }}" />
                    </div>
                    {{--
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">From Date</label>
                        <input type="date" class="form-control" name="fromDate" value="{{ $fromDate }}" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">To Date</label>
                        <input type="date" class="form-control" name="toDate" value="{{ date('Y-m-d',strtotime($toDate)) }}" />
                    </div>
                     --}}
                    <div class="form-group col-md-2">
                        <button style="margin-top:30px;" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="form-group col-md-2">
                        {{ count($allTransactions) }} Records Found
                    </div>
                </div>
            </form>
            <table class="table table-hover table-sm">
                <thead>
                    <tr class="table-info">
                        <th>Date</th>
                        <th>Description</th>
                        <th>Cash In</th>
                        <th>Cash Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $currentBalance = 0;
                    ?>
                    @foreach ($allTransactions as $transaction)
                        @foreach ($transaction->transactionDetails as $transactionDetail)
                            @if (in_array($transactionDetail->headID, $aryCashAllHeadIDs))
                                <tr>
                                    <td>{{ date('d-m-Y',strtotime($transaction->transactionDate)) }}</td>
                                    <td>
                                        {{ $transactionDetail->description }} <i class="fas fa-arrow-right"></i> {{ $transactionDetail->subHead->headName }} <i class="fas fa-arrow-right"></i>
                                        {{ $transaction->transactionTypeNumber }}
                                    </td>
                                    <td>
                                        @if ($transactionDetail->isDebit == 1)
                                            <span style="color:green;"> @toPKR('$transactionDetail->amount','$transaction->exchangeRate','')</span>
                                            <?php
                                                if (\Config::get('constants.client_settings.operatorToConvertToPKR') == '*') {
                                                    $currentBalance += $transactionDetail->amount * $transaction->exchangeRate;
                                                } else {
                                                    $currentBalance += $transactionDetail->amount / $transaction->exchangeRate;
                                                }
                                            ?>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($transactionDetail->isDebit == 0)
                                            <span style="color:red;">-@toPKR('$transactionDetail->amount','$transaction->exchangeRate','')</span>
                                            <?php
                                            if (\Config::get('constants.client_settings.operatorToConvertToPKR') == '*') {
                                                $currentBalance -= $transactionDetail->amount * $transaction->exchangeRate;
                                            } else {
                                                $currentBalance -= $transactionDetail->amount / $transaction->exchangeRate;
                                            }
                                            ?>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                    <tr>
                        <td>Until {{ $monthReport }}</td>
                        <td>Opening Balance as of {{ $monthReport }}</td>
                        <td>
                            @if ($openingBalance > 0)
                                <span style="color:green;">{{ $openingBalance }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($openingBalance < 0)
                                <span style="color:red;">-{{ $openingBalance }}</span>
                            @endif
                        </td>
                    </tr>
                    <?php
                        $currentBalance += $openingBalance;
                    ?>
                </tbody>
                <tfoot>
                    <tr style="font-weight:bold;">
                        <td colspan="3" class="text-right">Total:</td>
                        <td>
                            <span style="color: @if($currentBalance > 0) green @else red @endif ">
                                @money('$currentBalance','')
                            </span>
                        </td>
                    </tr>
                </tfoot>
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
