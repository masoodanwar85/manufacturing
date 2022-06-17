@extends('adminlte::page')

@section('title', 'New Customer')

@section('content_header')
    <h1>New Customer</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-male"></i> New Customer
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('customer.store') }}" method="POST">
				@csrf
				<div class="form-group row {{ $errors->has('customerName') ? 'has-error' : '' }}">
					<label for="customerName" class="col-sm-2 col-form-label">Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="customerName" class="form-control @if($errors->has('customerName')) is-invalid @endif" value="{{ old('customerName') }}" required>
						@if($errors->has('customerName'))
							<em class="invalid-feedback">
								{{ $errors->first('customerName') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('shopName') ? 'has-error' : '' }}">
					<label for="shopName" class="col-sm-2 col-form-label">Shop Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="shopName" class="form-control @if($errors->has('shopName')) is-invalid @endif" value="{{ old('shopName') }}" required>
						@if($errors->has('shopName'))
							<em class="invalid-feedback">
								{{ $errors->first('shopName') }}
							</em>
						@endif
					</div>
				</div>

                <div class="form-group row">
					<label for="saleAgent" class="col-sm-2 col-form-label">Sales Agent: </label>
					<div class="col-sm-10">
						<select name="salesAgentID" class="form-control">
                            <option value="">Select Sales Agent</option>
                            @foreach ($saleAgents as $saleAgent)
                                <option value="{{ $saleAgent->staffID }}">{{ $saleAgent->staffName }}</option>
                            @endforeach
                        </select>
					</div>
				</div>

				<div class="form-group row">
					<label for="phone" class="col-sm-2 col-form-label">Phone: </label>
					<div class="col-sm-10">
						<input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
					</div>
				</div>

				<div class="form-group row">
					<label for="address" class="col-sm-2 col-form-label">Address: </label>
					<div class="col-sm-10">
						<input type="text" name="address" class="form-control" value="{{ old('address') }}">
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-10">
						<textarea name="description" class="form-control">{{ old('description') }}</textarea>
					</div>
				</div>

				<div>
					<input class="btn btn-primary" type="submit" value="Save">
				</div>
            </form>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
