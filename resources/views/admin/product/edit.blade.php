@extends('adminlte::page')

@section('title', 'Edit Product')

@section('content_header')
    <h1>Edit Product</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Edit Product
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('product.update',$product->productID) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('productName') ? 'has-error' : '' }}">
					<label for="productName" class="col-sm-2 col-form-label">Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="productName" class="form-control @if($errors->has('productName')) is-invalid @endif" value="{{ old('productName',$product->productName) }}" required>
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
								<option value="{{ $category->categoryID }}" {{ old('categoryID',$product->categoryID) == $category->categoryID ? 'selected' : '' }}>{{ $category->categoryName }}</option>
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
					<div class="col-sm-4">
						<select name="maximumUnitID" class="form-control select2 @if($errors->has('maximumUnitID')) is-invalid @endif" required>
							@foreach($measurementUnits as $measurementUnit)
								<option value="{{ $measurementUnit->unitID }}" {{ old('maximumUnitID',$product->maximumUnitID) == $measurementUnit->unitID ? 'selected' : '' }}>{{ $measurementUnit->unitName }} ({{ $measurementUnit->symbol }})</option>
							@endforeach
						</select>
						@if($errors->has('maximumUnitID'))
							<em class="invalid-feedback">
								{{ $errors->first('maximumUnitID') }}
							</em>
						@endif
					</div>
                    <label for="isBOM" class="text-right col-sm-2 col-form-label">Is Bill of Material?:</label>
					<div class="col-sm-4" style="padding-top:8px;">
						<label class="radio-inline">
							<input type="radio" name="isBOM" value="1" {{ old('isBOM',$product->isBOM) == 1 ? 'checked' : '' }} /> Yes
						</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="isBOM" value="0" {{ old('isBOM',$product->isBOM) == 0 ? 'checked' : '' }} /> No
						</label>
					</div>
				</div>
                <input type="hidden" name="unitsInProduct" value="1" >
                <input type="hidden" name="isUnitsInProductFixed" value="1">
				<div class="form-group row {{ $errors->has('thresholdUnit') ? 'has-error' : '' }}">
					<label for="unitsInProduct" class="col-sm-2 col-form-label">Alert Quantity: *</label>
					<div class="col-sm-10">
						<input type="number" name="thresholdUnit" class="form-control @if($errors->has('thresholdUnit')) is-invalid @endif" value="{{ old('thresholdUnit', $product->thresholdUnit) }}" required>
						@if($errors->has('thresholdUnit'))
							<em class="invalid-feedback">
								{{ $errors->first('thresholdUnit') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('unitSalePrice') ? 'has-error' : '' }}">
					<label for="unitPurchasePrice" class="col-sm-2 col-form-label">Sale Price: *</label>
					<div class="col-sm-4">
						<input type="number" name="unitSalePrice" class="form-control @if($errors->has('unitSalePrice')) is-invalid @endif" value="{{ old('unitSalePrice', $product->unitSalePrice) }}" required>
						@if($errors->has('unitSalePrice'))
							<em class="invalid-feedback">
								{{ $errors->first('unitSalePrice') }}
							</em>
						@endif
					</div>
					<label for="unitPurchasePrice" class="col-sm-2 col-form-label">Purchase Price: *</label>
					<div class="col-sm-4 {{ $errors->has('unitPurchasePrice') ? 'has-error' : '' }}">
						<input type="number" name="unitPurchasePrice" class="form-control @if($errors->has('unitPurchasePrice')) is-invalid @endif" value="{{ old('unitPurchasePrice', $product->unitPurchasePrice) }}" required>
						@if($errors->has('unitPurchasePrice'))
							<em class="invalid-feedback">
								{{ $errors->first('unitPurchasePrice') }}
							</em>
						@endif
					</div>
				</div>

                @include('admin.product.bomRows')
				<div class="mt-3 offset-2">
					<input class="btn btn-primary" type="submit" value="Update">
				</div>
            </form>
        </div>
    </div>
    @include('admin.product.bomFields')
@stop

@section('css')
    <link rel="stylesheet" href="/css/_app.css">
    <style>
        @font-face {
            font-family: '_pdms_jauhar_regular';
            src: url('/fonts/_pdms_jauhar_regular.ttf');
            font-weight: bold;
        }

        .font-urdu {
            font-family: _pdms_jauhar_regular;
            font-size: 25px;
            line-height: 1.5;
            letter-spacing: 3px;
        }
    </style>
@stop

@section('js')
    <script src="/js/utils.js"></script>
	@include('admin.product.formJS', ['isNew' => false])
@stop
