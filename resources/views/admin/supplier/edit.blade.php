@extends('adminlte::page')

@section('title', 'Edit Supplier')

@section('content_header')
    <h1>Edit Supplier</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-people-arrows"></i> Edit Supplier
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('supplier.update',$supplier->supplierID) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('supplierName') ? 'has-error' : '' }}">
					<label for="supplierName" class="col-sm-2 col-form-label">Role Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="supplierName" class="form-control @if($errors->has('supplierName')) is-invalid @endif" value="{{ old('supplierName',$supplier->supplierName) }}" required>
						@if($errors->has('supplierName'))
							<em class="invalid-feedback">
								{{ $errors->first('supplierName') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row">
					<label for="phone" class="col-sm-2 col-form-label">Phone #:</label>
					<div class="col-sm-10">
						<input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}">
					</div>
				</div>
				<div class="form-group row">
					<label for="address" class="col-sm-2 col-form-label">Address:</label>
					<div class="col-sm-10">
						<input type="text" name="address" class="form-control" value="{{ old('address', $supplier->address) }}">
					</div>
				</div>
				<div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-10">
                    	<textarea name="description" class="form-control">{{ old('description',$supplier->description) }}</textarea>
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
