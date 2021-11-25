<div id="po-row" style="display:none;">
	<div class="form-group">
		<div class="col-sm-12">
			<select name="productID[]" class="form-control" onChange="productChanged(this);" required>
				<option value=""></option>
				@foreach ($products as $product)
					<option value="{{$product->productID}}">{{$product->productName}} ( {{$product->category->categoryName}} ) @if ($product->unitsInProduct > 1) - {{ $product->unitsInProduct}} {{$product->maximumUnit->symbol}} / unit @endif</option>
				@endforeach
			</select>
		</div>
		<input type="hidden" name="unitsInProduct[]" value="0" class="form-control" min="1">
	</div>
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="number" name="quantity[]" value="1" class="form-control" min="1" placeholder="Quantity" required>
			@if ($globalSettings['client_settings.is_units_in_product_fixed'] == 0)
				<input type="hidden" name="prevQty[]" value="1" >
			@endif
		</div>
	</div>
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="number" onkeyUp="calculateProductRowTotal(this);" name="totalUnits[]" value="0" class="form-control" min="1" required readonly>
			<span class="productUnit[]"></span>
		</div>
	</div>
	@if ($globalSettings['client_settings.is_show_damaged_units'] == 1)
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="number" name="damaged[]" value="0" class="form-control" min="0" placeholder="Damaged" required>
		</div>
	</div>
	@else
	<input type="hidden" name="damaged[]" value="0" />
	@endif
	@if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="text" onkeyUp="calculateProductRowTotal(this);" name="exchangeRate[]" value="1" class="form-control" min="1" placeholder="Exchange Rate" required>
		</div>
	</div>
	@else
	<input type="hidden" name="exchangeRate[]" value="1" />
	@endif
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="text" onkeyUp="calculateProductRowTotal(this);" name="perUnitPrice[]" value="" class="form-control" min="1" placeholder="Unit Price" required>
			<div class="font-urdu perUnitPriceInUrdu">

			</div>
		</div>
	</div>
	@if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="text" disabled name="total[]" value="" class="form-control" />
		</div>
	</div>
	@else
	<input type="hidden" name="total[]" value="" />
	@endif
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="text" disabled name="totalInPKR[]" value="" class="form-control" />
		</div>
	</div>
	<span class="separator"></span>
	<button class="btn btn-danger btn-sm pull-right removePORow" type="button" title="Delete PO Item">
		<i class="nav-icon fas fa-fw fa-trash"></i>
	</button>
</div>

<div id="po-expense-row" style="display:none;">
	<div class="form-group">
		<div class="col-sm-12">
			@include('partials.accountHeadsDropdown',['accountHeads' => $purchaseOrderExpense,'name' => 'headID[]', 'value' => 0,'isRequired' => true])
			{{--<select name="headID[]" class="form-control" required>
				<option value=""></option>
				@foreach ($aryPOExpenseHeads as $key => $value)
					<option @if (sizeof($value['children'])) style="color:red;" disabled @else style="color:green;" @endif value="{{$value['headID']}}">{{$value['headName']}}</option>
					@if (sizeof($value['children']))
						@foreach ($value['children'] as $childKey => $childValue)
							<option @if (sizeof($childValue['children'])) style="color:red;" disabled @else style="color:green;" @endif value="{{$childValue['headID']}}">----> {{$childValue['headName']}}</option>
							@if (sizeof($childValue['children']))
							@foreach ($childValue['children'] as $childLevel2Key => $childLevel2Value)
									<option @if (sizeof($childLevel2Value['children'])) style="color:red;" disabled @else style="color:green;" @endif value="{{$childLevel2Value['headID']}}">---->----> {{$childLevel2Value['headName']}}</option>
								@endforeach
							@endif
						@endforeach
					@endif
				@endforeach
			</select>--}}
		</div>
	</div>
	@if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="text" onkeyUp="calculateExpenseRowTotal(this);" name="expenseExchangeRate[]" value="1" class="form-control" min="1" placeholder="Exchange Rate" required>
		</div>
	</div>
	@else
		<input type="hidden" name="expenseExchangeRate[]" value="1" />
	@endif
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="text" name="perUnitExpense[]" onkeyUp="calculateRowExpenseAmount(this);" value="" min="1" class="form-control" required />
		</div>
	</div>
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="text" onkeyUp="calculateRowPerUnitExpense(this);" name="amount[]" value="" min="1" class="form-control" placeholder="Expense Amount" required />
			<div class="font-urdu expenseAmountInUrdu">

			</div>
		</div>
	</div>
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="text" disabled name="totalExpenseInPKR[]" value="" class="form-control" />
		</div>
	</div>
	<span class="separator"></span>
	<button class="btn btn-danger btn-sm pull-right removePOExpenseRow" type="button" title="Delete PO Expense Item">
		<i class="nav-icon fas fa-fw fa-trash"></i>
	</button>
</div>
