@extends('adminlte::page')

@section('title', 'Edit Delivery')

@section('content_header')
    <h1>Edit Delivery</h1>
@stop
@section('plugins.Select2', true)
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-shipping-fast"></i> Edit Delivery
            </h3>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('delivery.update',$delivery->deliveryID) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-group row {{ $errors->has('deliveryDate') ? 'has-error' : '' }}">
                    <label for="deliveryDate" class="col-sm-2 col-form-label">Delivery Date: *</label>
                    <div class="col-sm-10">
                        <input type="text" name="deliveryDate" onfocus="(this. type='date')" class="form-control @if($errors->has('deliveryDate')) is-invalid @endif" value="{{ $delivery->deliveryDate }}" required>
                        @if($errors->has('deliveryDate'))
                            <em class="invalid-feedback">
                                {{ $errors->first('deliveryDate') }}
                            </em>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('salesOrderID') ? 'has-error' : '' }}">
                    <label for="saleAgent" class="col-sm-2 col-form-label">Sales Order: *</label>
                    <div class="col-sm-10">
                        <select name="salesOrderID[]" class="form-control select2 @if($errors->has('salesOrderID')) is-invalid @endif" multiple>
                            @foreach ($salesOrders as $salesOrder)
                                @if($salesOrder->salesOrderStatusID == 1)
                                    <option value="{{ $salesOrder->salesOrderID }}" {{ in_array($salesOrder->salesOrderID, $selectedValues) ? 'selected' : '' }}>Invoice.{{ $salesOrder->invoiceNumber }} | {{$salesOrder->shopName}} | {{\App\Services\CurrencyService::getCurrencyFormatted($salesOrder->totalAmount)}} </option>
                                @endif
                            @endforeach
                        </select>
                        @if($errors->has('salesOrderID'))
                            <em class="invalid-feedback">
                                {{ $errors->first('salesOrderID') }}
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
                                <option value="{{ $route->routeID }}" @if($delivery->routeID == $route->routeID) selected @endif>{{ $route->route }}</option>
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
                                <option value="{{ $godown->godownID }}" @if($delivery->godownID == $godown->godownID) selected @endif>{{ $godown->name }}</option>
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
                                <option value="{{ $transport->transportID }}" @if($delivery->transportID == $transport->transportID) selected @endif>{{ $transport->name }}</option>
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
    <link rel="stylesheet" href="/css/_app.css">
@stop
@section('js')
    <script src="/js/utils.js"></script>
    <script>
        $('select.select2').select2();
    </script>
@stop
