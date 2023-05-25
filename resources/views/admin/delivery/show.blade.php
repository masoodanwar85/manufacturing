@extends('adminlte::page')

@section('title', 'Delivery')

@section('content_header')
    <h1>Delivery</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-shipping-fast"></i> {{ $delivery->deliveryName }}
            </h3>
            <a class="btn btn-warning btn-sm float-right mr-2" href="{{ route('delivery.deliveryReport',$delivery->deliveryID) }}">
                <i class="fas fa-print"></i> Print Report
            </a>
            @can('delivery_update')
            <a class="btn btn-primary btn-sm float-right mr-2" href="{{ route('delivery.edit',$delivery->deliveryID) }}">
                <i class="fas fa-edit"></i> Edit Delivery
            </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="row">
                <div class="font-weight-bold col">Delivery Date: </div>
                <div class="col">{{ $delivery->deliveryDate }}</div>
                <div class="font-weight-bold col">Route: </div>
                <div class="col">{{ $delivery->route->route }}</div>
                <div class="font-weight-bold col">Godown: </div>
                <div class="col">{{ $delivery->godown->name }}</div>
                <div class="font-weight-bold col">Transport: </div>
                <div class="col">{{ $delivery->transport->name }} ({{ $delivery->transport->vehicleNumber }})</div>
                <div class="font-weight-bold col">Created On: </div>
                <div class="col">{{ $delivery->dateCreated}}</div>
                <div class="font-weight-bold col">Updated On: </div>
                <div class="col">{{ $delivery->dateUpdated}}</div>
            </div>
            <div class="row">
                <div class="col-6">
                    <h2 class="text-center">Order Details:</h2>
                    <table class="table">
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                        </tr>
                        @foreach($delivery->salesOrders as $salesOrder)
                            <tr>
                                <td>{{ $salesOrder->invoiceNumber }}</td>
                                <td>{{ $salesOrder->customer->shopName }}</td>
                            </tr>
                        @endforeach
                    </table>
                    
                </div>
                <div class="col-6">
                    <h2 class="text-center">Products</h2>
                    <table class="table">
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                        </tr>
                        @foreach($productsQuantities as $productQty)
                            <tr>
                                <td>{{ $productQty['product'] }}</td>
                                <td>{{ $productQty['qty'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
