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
                    <th>Last Receiving Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($monthlyDefaulters as $d)
                    <tr>
                        <td>{{$d->customerName}}</td>
                        <td>@money('$d->transactionAmount')</td>
                        <td>{{date('d-m-Y', strtotime($d->lastReceivingDate))}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop