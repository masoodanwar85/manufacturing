@extends('adminlte::page')

@section('title', 'New Productionion')

@section('content_header')
    <h1>New Production</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> New Production
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('production.store') }}" method="POST">
				@csrf
				<div class="form-group row {{ $errors->has('productName') ? 'has-error' : '' }}">
					<label for="productName" class="col-sm-2 col-form-label">Product: *</label>
					<div class="col-sm-10">
                        <select name="productID" class="form-control select2 @if($errors->has('productID')) is-invalid @endif" required onchange="BOMProductChanged(this.value);">
							<option value="">Please Select Product</option>
							@foreach($BOMProducts as $idx => $product)
								<option value="{{ $product->productID }}" {{ old('productID') == $product->productID ? 'selected' : '' }}>{{ $product->productName }}</option>
							@endforeach
						</select>
						@if($errors->has('productID'))
							<em class="invalid-feedback">
								{{ $errors->first('productID') }}
							</em>
						@endif
					</div>
				</div>
                <div class="form-group row {{ $errors->has('quantity') ? 'has-error' : '' }}">
					<label for="quantity" class="col-sm-2 col-form-label">Quantity: *</label>
					<div class="col-sm-10">
						<input type="number" name="quantity" class="form-control @if($errors->has('quantity')) is-invalid @endif" value="{{ old('quantity', '1') }}" required>
						@if($errors->has('quantity'))
							<em class="invalid-feedback">
								{{ $errors->first('quantity') }}
							</em>
						@endif
					</div>
				</div>

                <h3>BOM Details</h3>

                <h4>Items</h4>
                <table class="table table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th width="50%">Item</th>
                            <th width="15%">Quantity</th>
                            <th width="15%">Unit Price</th>
                            <th width="15%">Total</th>
                        </tr>
                    </thead>
                    <tbody id="product-bom-items">

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right font-weight-bold">Total:</td>
                            <td class="font-weight-bold" id="product-bom-item-total"></td>
                        </tr>
                    </tfoot>
                </table>
                <h4>Expenses</h4>
                <table class="table table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th width="50%">Expense</th>
                            <th width="15%">Quantity</th>
                            <th width="15%">Amount</th>
                            <th width="15%">Total</th>
                        </tr>
                    </thead>
                    <tbody id="product-bom-expenses">

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right font-weight-bold">Total:</td>
                            <td class="font-weight-bold" id="product-bom-expense-total"></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right font-weight-bold">Grand Total:</td>
                            <td class="font-weight-bold" id="product-bom-item-expense-grand-total"></td>
                        </tr>
                    </tfoot>
                </table>

				<div class="mt-3 offset-2">
					<input class="btn btn-primary" type="submit" value="Save">
				</div>
            </form>
        </div>
    </div>
	@include('admin.production.bomFields')
@endsection

@section('css')
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
    @include('admin.production.formJS', ['isNew' => true])
@stop
