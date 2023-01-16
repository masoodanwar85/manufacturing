@extends('adminlte::page')

@section('title', 'Receivables Report')

@section('content_header')
    <h1>Receivables Report</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i> Receivables Report
            </h3>
        </div>
        <div class="card-body">
            <form>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">From Month</label>
                        <input type="month" class="form-control" name="start_date" value="{{$filter['start_date']}}" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">To Month</label>
                        <input type="month" class="form-control" name="end_date" value="{{$filter['end_date']}}" />
                    </div>
                    <div class="form-group col-md-2">
                        <button style="margin-top:30px;" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
            <table class="table  table-hover table-striped table-bordered">
                <tbody>
                @if(!blank($monthlyReceivables))
                <tr class="table-info">
                    <th>Months</th>
                    @foreach($monthlyReceivables as $month)
                    <td>
                        {{$month->transactionMonth}}
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <th>Receivables</th>
                    @foreach($monthlyReceivables as $receivable)
                        <td>
                            {{round($receivable->receivables, \Config::get('constants.client_settings.decimal_places')) }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <th>Received</th>
                    @foreach($monthlyReceivables as $received)
                        <td>
                            {{round($received->received, \Config::get('constants.client_settings.decimal_places')) }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <th>Balance</th>
                    @foreach($monthlyReceivables as $balance)
                        <td @if($balance->balance >= 0) class="table-success" @else class="table-danger" @endif>
                            {{round($balance->balance,\Config::get('constants.client_settings.decimal_places')) }}
                        </td>
                    @endforeach
                </tr>
                @else
                    <tr>
                        <td>
                            Please Select From and To Months
                        </td>
                    </tr>
                @endif

                </tbody>
            </table>
            </div>
        </div>
    </div>
@stop