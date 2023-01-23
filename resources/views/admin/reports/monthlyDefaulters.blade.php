@extends('adminlte::page')
@section('title', 'Defaulters')

@section('content_header')
    <h1>Defaulters</h1>
@stop
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-male"></i> Defaulters
            </h3>
        </div>
        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Customer">
                <thead>
                <tr>
                    <th>Customer</th>
                    <th>Balance</th>
                    <th width="10%">Current Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customers as $customer)
                    @if(\App\Models\Customer::getBalance($customer->customerID)[0]->totalPayable > 0)
                    <tr>
                        <td>{{$customer->customerName}}</td>
                        <td>
                            {{\App\Services\CurrencyService::getCurrencyFormatted(\App\Models\Customer::getBalance($customer->customerID)[0]->totalPayable)}}
                        </td>
                        @if(\App\Models\Customer::getBalance($customer->customerID)[0]->transactionDate <= now()->subMonth())
                        <td class="table-danger">
                           Not Paid This Month
                        </td>
                        @else
                        <td>
                            Paid This Month
                        </td>
                        @endif
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop