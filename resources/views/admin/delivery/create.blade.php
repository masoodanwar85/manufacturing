@extends('adminlte::page')

@section('title', 'New Delivery')

@section('content_header')
    <h1>New Delivery</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-shipping-fast"></i> New Delivery
            </h3>
        </div>
        <div class="card-body">
			<form class="form-horizontal" action="{{ route('delivery.store') }}" method="POST">
				@csrf
                <div class="form-group row {{ $errors->has('deliveryDate') ? 'has-error' : '' }}">
                    <label for="deliveryDate" class="col-sm-2 col-form-label">Delivery Date: *</label>
					<div class="col-sm-10">
	                    <input type="text" name="deliveryDate" onfocus="(this. type='date')" class="form-control @if($errors->has('deliveryDate')) is-invalid @endif" value="{{ date('Y-m-d') }}" required>
	                    @if($errors->has('deliveryDate'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('deliveryDate') }}
	                        </em>
	                    @endif
					</div>
                </div>
                <div class="form-group row {{ $errors->has('routeID') ? 'has-error' : '' }}">
                    <label for="saleAgent" class="col-sm-2 col-form-label">Route: *</label>
                    <div class="col-sm-10">
                        <select name="routeID" class="form-control @if($errors->has('routeID')) is-invalid @endif">
                            <option value="">Select Route</option>
                            @foreach ($routes as $route)
                                <option value="{{ $route->routeID }}">{{ $route->route }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('routeID'))
                            <em class="invalid-feedback">
                                {{ $errors->first('routeID') }}
                            </em>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('godownID') ? 'has-error' : '' }}">
                    <label for="saleAgent" class="col-sm-2 col-form-label">Godown: *</label>
                    <div class="col-sm-10">
                        <select name="godownID" class="form-control @if($errors->has('godownID')) is-invalid @endif">
                            <option value="">Select Godown</option>
                            @foreach ($godowns as $godown)
                                <option value="{{ $godown->godownID }}">{{ $godown->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('godownID'))
                            <em class="invalid-feedback">
                                {{ $errors->first('godownID') }}
                            </em>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('transportID') ? 'has-error' : '' }}">
                    <label for="saleAgent" class="col-sm-2 col-form-label">Transport: *</label>
                    <div class="col-sm-10">
                        <select name="transportID" class="form-control @if($errors->has('transportID')) is-invalid @endif">
                            <option value="">Select Transport</option>
                            @foreach ($transports as $transport)
                                <option value="{{ $transport->transportID }}">{{ $transport->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('transportID'))
                            <em class="invalid-feedback">
                                {{ $errors->first('transportID') }}
                            </em>
                        @endif
                    </div>
                </div>
                <div>
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </form>
        </div>
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
