@extends('adminlte::page')

@section('title', 'New Product')

@section('content_header')
    <h1>New Product</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> New Product
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('product.store') }}" method="POST">
				@csrf
				<div class="form-group row {{ $errors->has('productName') ? 'has-error' : '' }}">
					<label for="productName" class="col-sm-2 col-form-label">Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="productName" class="form-control @if($errors->has('productName')) is-invalid @endif" value="{{ old('productName') }}" required>
						@if($errors->has('productName'))
							<em class="invalid-feedback">
								{{ $errors->first('productName') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('categoryID') ? 'has-error' : '' }}">
					<label for="categoryID" class="col-sm-2 col-form-label">Category: *</label>
					<div class="col-sm-10">
						<select name="categoryID" class="form-control select2 @if($errors->has('categoryID')) is-invalid @endif" required>
							<option value="">Please Select Category</option>
							@foreach($categories as $idx => $category)
								<option value="{{ $category->categoryID }}" {{ old('categoryID') == $category->categoryID ? 'selected' : '' }}>{{ $category->categoryName }}</option>
							@endforeach
						</select>
						@if($errors->has('categoryID'))
							<em class="invalid-feedback">
								{{ $errors->first('categoryID') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('maximumUnitID') ? 'has-error' : '' }}">
					<label for="maximumUnitID" class="col-sm-2 col-form-label">Product Unit: *</label>
					<div class="col-sm-10">
						<select name="maximumUnitID" class="form-control select2 @if($errors->has('maximumUnitID')) is-invalid @endif" required>
							@foreach($measurementUnits as $measurementUnit)
								<option value="{{ $measurementUnit->unitID }}" {{ old('maximumUnitID') == $measurementUnit->unitID ? 'selected' : '' }}>{{ $measurementUnit->unitName }} ({{ $measurementUnit->symbol }})</option>
							@endforeach
						</select>
						@if($errors->has('maximumUnitID'))
							<em class="invalid-feedback">
								{{ $errors->first('maximumUnitID') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('unitsInProduct') ? 'has-error' : '' }}">
					<label for="unitsInProduct" class="col-sm-2 col-form-label">Units in Product: *</label>
					<div class="{{$globalSettings['client_settings.is_units_in_product_fixed'] == 1 ? 'col-sm-10' : 'col-sm-4'}}">
						<input type="number" name="unitsInProduct" class="form-control @if($errors->has('unitsInProduct')) is-invalid @endif" value="{{ old('unitsInProduct') }}" required>
						@if($errors->has('unitsInProduct'))
							<em class="invalid-feedback">
								{{ $errors->first('unitsInProduct') }}
							</em>
						@endif
					</div>
					@if ($globalSettings['client_settings.is_units_in_product_fixed'] == 0)
					<label for="isUnitsInProductFixed" class="text-right col-sm-2 col-form-label">Are units fixed?:</label>
					<div class="col-sm-4" style="padding-top:8px;">
						<label class="radio-inline">
							<input type="radio" name="isUnitsInProductFixed" value="1" checked> Yes
						</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="isUnitsInProductFixed" value="0" /> No
						</label>
					</div>
					@endif
				</div>
				<div class="form-group row {{ $errors->has('thresholdUnit') ? 'has-error' : '' }}">
					<label for="unitsInProduct" class="col-sm-2 col-form-label">Alert Quantity: *</label>
					<div class="col-sm-10">
						<input type="number" name="thresholdUnit" class="form-control @if($errors->has('thresholdUnit')) is-invalid @endif" value="{{ old('thresholdUnit') }}" required>
						@if($errors->has('thresholdUnit'))
							<em class="invalid-feedback">
								{{ $errors->first('thresholdUnit') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('unitPurchasePrice') ? 'has-error' : '' }}">
					<label for="unitPurchasePrice" class="col-sm-2 col-form-label">Purchase Price: *</label>
					<div class="col-sm-10">
						<input type="number" name="unitPurchasePrice" class="form-control @if($errors->has('unitPurchasePrice')) is-invalid @endif" value="{{ old('unitPurchasePrice', $product->unitPurchasePrice) }}" required>
						@if($errors->has('unitPurchasePrice'))
							<em class="invalid-feedback">
								{{ $errors->first('unitPurchasePrice') }}
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
@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
