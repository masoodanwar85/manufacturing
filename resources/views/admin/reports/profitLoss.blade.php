@extends('adminlte::page')

@section('title', 'Profit/Loss Report')

@section('content_header')
    <h1>Profit/Loss Report</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-search"></i> Profit/Loss Filter By Date
            </h3>
        </div>
        <div class="card-body">
            <form>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">From Date</label>
                        <input type="date" class="form-control" name="start_date" id="start_date"  value="{{$filter['start_date']}}" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">To Date</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" value="{{$filter['end_date']}}" />
                    </div>
                    <div class="form-group mr-1">
                        <button style="margin-top:30px;" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="form-group">
                        <button style="margin-top:30px;" type="button" class="btn btn-secondary" onclick="removeFilters()">Remove Filters</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i> Profit/Loss Report
            </h3>
        </div>
        <div class="card-body">
            @if (count($profitLoss))
            <div class="row">
                <div class="col-md-6">
                    @if ($profitLoss[0]->profitLoss > 0)
                    <div class="info-box bg-success" style="height:94%;">
                        <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Profit</span>
                            <span class="info-box-number">
                                @money('$profitLoss[0]->profitLoss')/-
                                <span class="font-urdu" style="margin-left:100px;">
                                    {{ (new \App\Services\CurrencyService())->getMoneyTranslationInUrdu($profitLoss[0]->profitLoss) }}
                                </span>
                            </span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 70%"></div>
                            </div>
                            <span class="progress-description">
                                Profit
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="info-box bg-danger" style="height:94%;">
                        <span class="info-box-icon"><i class="far fa-thumbs-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Loss</span>
                            <span class="info-box-number">
                                @money('$profitLoss[0]->profitLoss')/-
                                <span class="font-urdu" style="margin-left:100px;">
                                    {{ (new \App\Services\CurrencyService())->getMoneyTranslationInUrdu($profitLoss[0]->profitLoss) }}
                                </span>
                            </span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 70%"></div>
                            </div>
                            <span class="progress-description">
                                Loss
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="far fa-bookmark"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Revenue/Income</span>
                            <span class="info-box-number">
                                @money('$profitLoss[0]->totalRevenue')/-
                                <span class="font-urdu" style="margin-left:100px;">
                                    {{ (new \App\Services\CurrencyService())->getMoneyTranslationInUrdu($profitLoss[0]->totalRevenue) }}
                                </span>
                            </span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 70%"></div>
                            </div>
                            <span class="progress-description">
                                Income earned from Sales <br />

                            </span>
                        </div>
                    </div>
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="far fa-flag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Expense</span>
                            <span class="info-box-number">
                                @money('$profitLoss[0]->totalExpense')/-
                                <span class="font-urdu" style="margin-left:100px;">
                                    {{ (new \App\Services\CurrencyService())->getMoneyTranslationInUrdu($profitLoss[0]->totalExpense) }}
                                </span>
                            </span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 70%"></div>
                            </div>
                            <span class="progress-description">
                                Expenses occurred
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if (count($profitLoss))
        <div class="card card-default color-palette-box">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-list"></i> Detail
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-hover">
    				<thead>
    					<tr class="table-info">
    						<th>Date</th>
    						<th>Description</th>
                            <th>Amount</th>
    					</tr>
    				</thead>
    				<tbody>
                        <?php
                            $currentBalance = 0;
                        ?>
                        @foreach ($allTransactions as $transaction)
                            @foreach ($transaction->transactionDetails as $transactionDetail)
                                @if (in_array($transactionDetail->headID, $aryIncomeExpenseAllHeadIDs))
                                    <tr>
                                        <td>{{ date('d-m-Y',strtotime($transaction->dateCreated)) }}</td>
                                        <td>{{ $transactionDetail->description }}</td>
                                        <td>
                                            @if ($transactionDetail->isDebit == 1)
                                                <span style="color:red;">- @toPKR('$transactionDetail->amount','$transaction->exchangeRate','')</span>
                                                <?php
                                                    if (\Config::get('constants.client_settings.operatorToConvertToPKR') == '*') {
                                                        $currentBalance -= $transactionDetail->amount * $transaction->exchangeRate;
                                                    } else {
                                                        $currentBalance -= $transactionDetail->amount / $transaction->exchangeRate;
                                                    }
                                                ?>
                                            @endif
                                            @if ($transactionDetail->isDebit == 0)
                                                <span style="color:green;">@toPKR('$transactionDetail->amount','$transaction->exchangeRate','')</span>
                                                <?php
                                                if (\Config::get('constants.client_settings.operatorToConvertToPKR') == '*') {
                                                    $currentBalance += $transactionDetail->amount * $transaction->exchangeRate;
                                                } else {
                                                    $currentBalance += $transactionDetail->amount / $transaction->exchangeRate;
                                                }
                                                ?>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="font-weight:bold;">
                            <td colspan="2" class="text-right">Total:</td>
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
    @endif
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

@section('js')
    <script>
        function removeFilters() {
            $('#start_date').val()
            $('#end_date').val()
            $('form').submit()
        }
    </script>
@stop