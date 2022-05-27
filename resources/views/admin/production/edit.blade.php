@extends('adminlte::page')

@section('title', 'Edit Production')

@section('content_header')
    <h1>Edit Production</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Edit Production
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('production.update', $production->productionBOMID) }}" method="POST">
				@csrf
                @method('PUT')
				<div class="form-group row {{ $errors->has('productName') ? 'has-error' : '' }}">
					<label for="productName" class="col-sm-2 col-form-label">Product: *</label>
					<div class="col-sm-10">
                        <select name="productID" class="form-control select2 @if($errors->has('productID')) is-invalid @endif" required onchange="BOMProductChanged(this.value);">
							<option value="">Please Select Product</option>
							@foreach($BOMProducts as $idx => $product)
								<option value="{{ $product->productID }}" {{ old('productID', $production->productID) == $product->productID ? 'selected' : '' }}>{{ $product->productName }}</option>
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
						<input type="number" name="quantity" class="form-control @if($errors->has('quantity')) is-invalid @endif" value="{{ old('quantity', $production->quantity) }}" required>
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
                        @php
                            $itemTotal = 0;
                            $expenseTotal = 0;
                            $grandTotal = 0;
                        @endphp
                        @foreach ($production->items as $key => $productionItem)
                            <tr>
                    			<td>
                                    <div class="form-group">
                                        <div class="col-sm-12">{{ $productionItem->product->productName }}</div>
                                        <input type="hidden" name="productItemID[]" value="{{ $productionItem->productID }}" />
                                    </div>
                                </td>
                    			<td>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="number" class="form-control" onchange="calculateProductRowTotal();"name="productItemQuantity[]" value="{{ $productionItem->quantity  }}" />
                                        </div>
                                    </div>
                                </td>
                    			<td>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="number" class="form-control" name="productItemPrice[]" onchange="calculateProductRowTotal();" value="{{ round($productionItem->unitPrice,2) }}" />
                                        </div>
                                    </div>
                                </td>
                                @php
                                    $itemTotal = $productionItem->quantity * $production->quantity * $productionItem->unitPrice;
                                    $grandTotal += $itemTotal;
                                @endphp
                    			<td>
                                    <div class="form-group">
                                        <div class="col-sm-12">{{ $itemTotal }}</div>
                                    </div>
                                </td>
                			</tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right font-weight-bold">Total:</td>
                            <td class="font-weight-bold" id="product-bom-item-total">{{ $grandTotal }}</td>
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
                        @foreach ($production->expenses as $key => $productionExpense)
                            @php
                                $itemTotal = $production->quantity * $productionExpense->amount;
                                $expenseTotal += $itemTotal;
                            @endphp
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <div class="col-sm-12">{{ $productionExpense->head->headName }}</div>
                                        <input type="hidden" name="expenseHeadID[]" value="{{ $productionExpense->expenseHeadID }}" />
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="hidden" class="form-control" value="1" name="baseExpenseQty[]" />
                                            <input type="number" readonly class="form-control" value="1" name="expenseQuantity[]" />
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="number" class="form-control" name="expenseAmount[]" onchange="calculateProductRowTotal();" value="{{ $productionExpense->amount }}" />
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="col-sm-12">{{ $itemTotal }}</div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right font-weight-bold">Total:</td>
                            <td class="font-weight-bold" id="product-bom-expense-total">{{ $expenseTotal }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right font-weight-bold">Grand Total:</td>
                            <td class="font-weight-bold" id="product-bom-item-expense-grand-total">{{ $grandTotal + $expenseTotal }}</td>
                        </tr>
                    </tfoot>
                </table>

				<div class="mt-3 offset-2">
					<input class="btn btn-primary" type="submit" value="Save">
				</div>
            </form>
        </div>
    </div>
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
    @include('admin.production.formJS', ['isNew' => false])
@stop
