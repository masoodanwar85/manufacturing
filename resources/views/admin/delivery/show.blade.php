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
			<dt>
				<dd class="font-weight-bold">Delivery Name: </dd>
				<dl>{{ $delivery->deliveryDate }}</dl>
				<dd class="font-weight-bold">Route: </dd>
				<dl>{{ $delivery->route->route }}</dl>
				<dd class="font-weight-bold">Godown: </dd>
				<dl>{{ $delivery->godown->name }}</dl>
				<dd class="font-weight-bold">Transport: </dd>
				<dl>{{ $delivery->transport->name }}</dl>
				<dd class="font-weight-bold">Created On: </dd>
				<dl>{{ $delivery->dateCreated}}</dl>
                <dd class="font-weight-bold">Updated On: </dd>
                <dl>{{ $delivery->dateUpdated}}</dl>
			</dt>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
