@extends('adminlte::page')

@section('title', 'Edit Purchase Order')

@section('content_header')
    <h1>Edit Purchase Order</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-cart-plus"></i> Edit Purchase Order
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('purchase.update',$purchase->purchaseOrderID) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('supplierID') ? 'has-error' : '' }}">
					<label for="supplierID" class="col-sm-2 col-form-label">Supplier: *</label>
					<div class="col-sm-10">
						<select name="supplierID" class="form-control select2 @if($errors->has('supplierID')) is-invalid @endif" required>
							<option value="">Please Select Supplier</option>
							@foreach($suppliers as $supplier)
								<option value="{{ $supplier->supplierID }}" {{ old('supplierID',$purchase->supplierID) == $supplier->supplierID ? 'selected' : '' }}>{{ $supplier->supplierName }}</option>
							@endforeach
						</select>
						@if($errors->has('supplierID'))
							<em class="invalid-feedback">
								{{ $errors->first('supplierID') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('batchID') ? 'has-error' : '' }}">
					<label for="batchID" class="col-sm-2 col-form-label">Batch: *</label>
					<div class="col-sm-10">
						<select name="batchID" class="form-control select2 @if($errors->has('batchID')) is-invalid @endif" required>
							<option value="">Please Select Batch</option>
							@foreach($batches as $batch)
								<option value="{{ $batch->batchID }}" {{ old('batchID',$purchase->batchID) == $batch->batchID ? 'selected' : '' }}>{{ $batch->batchName }}</option>
							@endforeach
						</select>
						@if($errors->has('batchID'))
							<em class="invalid-feedback">
								{{ $errors->first('batchID') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('purchaseOrderDate') ? 'has-error' : '' }}">
                    <label for="purchaseOrderDate" class="col-sm-2 col-form-label">Date: *</label>
					<div class="col-sm-10">
	                    <input type="date" name="purchaseOrderDate" class="form-control @if($errors->has('purchaseOrderDate')) is-invalid @endif" value="{{ old('purchaseOrderDate',$purchase->purchaseOrderDate) }}" required>
	                    @if($errors->has('purchaseOrderDate'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('purchaseOrderDate') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-10">
                    	<textarea name="description" class="form-control">{{ old('description',$purchase->description) }}</textarea>
					</div>
                </div>

				<div class="row">
					<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h3>Products</h3>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<table class="table" id="myTable">
									<thead>
										<tr class="text-center">
											<th style="text-align:center;width:35%;">Product Name</th>
											<th style="text-align:center;width:10%;">Quantity</th>
											<th style="text-align:center;width:15%;">Exchange Rate</th>
											<th style="text-align:center;width:15%;">Per Unit Price</th>
											<th style="text-align:center;width:15%;">Total</th>
											<th style="text-align:center;width:15%;">Total (PKR)</th>
											<th style="text-align:center;width:5%;">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$gTotal = 0;
											$gTotalInPKR = 0;
											$total = 0;
											$totalInPKR = 0;
										?>
										@foreach($purchase->purchaseOrderDetails as $purchaseOrderDetail)
											<?php
												$total = $purchaseOrderDetail->quantity * $purchaseOrderDetail->perUnitPrice;
												$totalInPKR = $purchaseOrderDetail->quantity * $purchaseOrderDetail->perUnitPrice * $purchaseOrderDetail->exchangeRate;
												$gTotal+=$total;
												$gTotalInPKR+=$totalInPKR;
											?>
											<tr>
												<td>
													<div class="form-group">
											  			<div class="col-sm-12">
											  				<select name="productID[]" class="form-control" required>
											                    <option value=""></option>
											                    @foreach ($products as $product)
											  					    <option value="{{$product->productID}}" {{ $purchaseOrderDetail->productID == $product->productID ? 'selected' : '' }}>{{$product->productName}} ( {{$product->category->categoryName}} )</option>
															    @endforeach
											  				</select>
											  			</div>
											    	</div>
												</td>
												<td>
													<div class="form-group">
											      		<div class="col-sm-12">
															<input type="number" onkeyUp="calculateProductRowTotal(this);" name="quantity[]" value="{{ $purchaseOrderDetail->quantity }}" class="form-control" min="1" placeholder="Quantity" required>
											      		</div>
											    	</div>
												</td>
												<td>
													<div class="form-group">
											            <div class="col-sm-12">
											        		<input type="text" onkeyUp="calculateProductRowTotal(this);" name="exchangeRate[]" value="{{ $purchaseOrderDetail->exchangeRate }}" class="form-control" min="1" placeholder="Exchange Rate" required>
											        	</div>
											        </div>
												</td>
												<td>
													<div class="form-group">
											            <div class="col-sm-12">
											        		<input type="text" onkeyUp="calculateProductRowTotal(this);" name="perUnitPrice[]" value="{{ $purchaseOrderDetail->perUnitPrice }}" class="form-control" min="1" placeholder="Unit Price" required>
											        	</div>
											        </div>
												</td>
												<td>
													<div class="form-group">
											            <div class="col-sm-12">
											        		<input type="text" disabled name="total[]" value="{{ $total }}" class="form-control" />
											        	</div>
											        </div>
												</td>
												<td>
													<div class="form-group">
											            <div class="col-sm-12">
											        		<input type="text" disabled name="totalInPKR[]" value="{{ $totalInPKR }}" class="form-control" />
											        	</div>
											        </div>
												</td>
												<td>
													<button class="btn btn-danger btn-sm pull-right removePORow" type="button" title="Delete Item">
											            <i class="nav-icon fas fa-fw fa-trash"></i>
											        </button>
												</td>
											</tr>
										@endforeach
									</tbody>
									<tfoot>
										<tr>
											<td colspan="4" class="font-weight-bold text-right">Total:</td>
											<td id="gTotal" class="font-weight-bold">&nbsp;&nbsp;&nbsp;&nbsp;{{ $gTotal }}</td>
											<td id="gTotalInPKR" class="font-weight-bold">&nbsp;&nbsp;&nbsp;&nbsp;Rs. {{ $gTotalInPKR }}</td>
										</tr>
										<tr>
											<td colspan="6"></td>
											<td>
												<button class="btn btn-primary btn-sm pull-right " onclick="addPORow()" type="button" title="Add New PO Item">
													<i class="nav-icon fas fa-fw fa-plus"></i>
												</button>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<hr>
				</div>

				<div class="row">
					<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h3>Expenses</h3>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<table class="table" id="expenseTable">
									<thead>
										<tr class="text-center">
											<th style="text-align:center;width:40%;">Expense</th>
											<th style="text-align:center;width:20%;">Exchange Rate</th>
											<th style="text-align:center;width:15%;">Amount</th>
											<th style="text-align:center;width:20%;">Total (PKR)</th>
											<th style="text-align:center;width:5%;">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$expenseTotalInPKR = 0;
											$expenseGTotalInPKR = 0;
										?>
										@foreach($purchase->transactions as $purchaseOrderTransactions)
											@if ($purchaseOrderTransactions->pivot->isExpense == 1)
												<?php
													$expenseTotalInPKR = $purchaseOrderTransactions->transactionDetails[0]->amount;
													$expenseGTotalInPKR+=$expenseTotalInPKR;
													$subHeadID = $purchaseOrderTransactions->transactionDetails[0]->subHead->headID;
												?>
												<tr>
													<td>
														<div class="form-group">
												  			<div class="col-sm-12">
												  				<select name="headID[]" class="form-control" required>
												                    <option value=""></option>
																	@foreach ($aryPOExpenseHeads as $key => $value)
																		<option @if (sizeof($value['children'])) style="color:red;" disabled @else style="color:green;" @endif value="{{$value['headID']}}" {{$subHeadID == $value['headID'] ? 'selected' : '' }}>{{$value['headName']}}</option>
																		@if (sizeof($value['children']))
																			@foreach ($value['children'] as $childKey => $childValue)
																				<option @if (sizeof($childValue['children'])) style="color:red;" disabled @else style="color:green;" @endif value="{{$childValue['headID']}}" {{$subHeadID == $childValue['headID'] ? 'selected' : ''}}>----> {{$childValue['headName']}}</option>
																				@if (sizeof($childValue['children']))
																				@foreach ($childValue['children'] as $childLevel2Key => $childLevel2Value)
																						<option @if (sizeof($childLevel2Value['children'])) style="color:red;" disabled @else style="color:green;" @endif value="{{$childLevel2Value['headID']}}" {{$subHeadID == $childLevel2Value['headID'] ? 'selected' : ''}}>---->----> {{$childLevel2Value['headName']}}</option>
																					@endforeach
																				@endif
																			@endforeach
																		@endif
																	@endforeach
												  				</select>
															</div>
												    	</div>
													</td>
										    		<td>
												        <div class="form-group">
												            <div class="col-sm-12">
												        		<input type="text" onkeyUp="calculateExpenseRowTotal(this);" name="expenseExchangeRate[]" value="{{$purchaseOrderTransactions->exchangeRate}}" class="form-control" min="1" placeholder="Exchange Rate" required>
												        	</div>
												        </div>
										        	</td>
													<td>
												        <div class="form-group">
												            <div class="col-sm-12">
												        		<input type="text" onkeyUp="calculateExpenseRowTotal(this);" name="amount[]" value="{{$expenseTotalInPKR/$purchaseOrderTransactions->exchangeRate}}" min="1" class="form-control" placeholder="Expense Amount" required />
												        	</div>
												        </div>
													</td>
													<td>
														<div class="form-group">
												            <div class="col-sm-12">
												        		<input type="text" disabled name="totalExpenseInPKR[]" value="{{$expenseTotalInPKR}}" class="form-control" />
												        	</div>
												        </div>
													</td>
													<td>
														<button class="btn btn-danger btn-sm pull-right removePOExpenseRow" type="button" title="Delete PO Expense Item">
												            <i class="nav-icon fas fa-fw fa-trash"></i>
												        </button>
													</td>
										    	</tr>
											@endif
										@endforeach
									</tbody>
									<tfoot>
										<tr>
											<td colspan="3" class="font-weight-bold text-right">Total:</td>
											<td id="expenseGTotalInPKR" class="font-weight-bold">{{ $expenseGTotalInPKR }}</td>
										</tr>
										<tr>
											<td colspan="6"></td>
											<td>
												<button class="btn btn-primary btn-sm pull-right " onclick="addPOExpenseRow()" type="button" title="Add New Expense">
													<i class="nav-icon fas fa-fw fa-plus"></i>
												</button>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<hr>
				</div>

				<div>
                    <input class="btn btn-primary" type="submit" value="Update">
                </div>
            </form>
        </div>
    </div>

	<div id="po-row" style="display:none;">
    	<div class="form-group">
  			<div class="col-sm-12">
  				<select name="productID[]" class="form-control" required>
                    <option value=""></option>
                    @foreach ($products as $product)
  					    <option value="{{$product->productID}}">{{$product->productName}} ( {{$product->category->categoryName}} )</option>
				    @endforeach
  				</select>
  			</div>
    	</div>
    	<span class="separator"></span>
    	<div class="form-group">
      		<div class="col-sm-12">
    		    <input type="number" onkeyUp="calculateProductRowTotal(this);" name="quantity[]" value="1" class="form-control" min="1" placeholder="Quantity" required>
      		</div>
    	</div>
    	<span class="separator"></span>
        <div class="form-group">
            <div class="col-sm-12">
        		<input type="text" onkeyUp="calculateProductRowTotal(this);" name="exchangeRate[]" value="1" class="form-control" min="1" placeholder="Exchange Rate" required>
        	</div>
        </div>
        <span class="separator"></span>
        <div class="form-group">
            <div class="col-sm-12">
        		<input type="text" onkeyUp="calculateProductRowTotal(this);" name="perUnitPrice[]" value="" class="form-control" min="1" placeholder="Unit Price" required>
        	</div>
        </div>
		<span class="separator"></span>
        <div class="form-group">
            <div class="col-sm-12">
        		<input type="text" disabled name="total[]" value="" class="form-control" />
        	</div>
        </div>
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
  				<select name="headID[]" class="form-control" required>
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
  				</select>
			</div>
    	</div>
    	<span class="separator"></span>
        <div class="form-group">
            <div class="col-sm-12">
        		<input type="text" onkeyUp="calculateExpenseRowTotal(this);" name="expenseExchangeRate[]" value="1" class="form-control" min="1" placeholder="Exchange Rate" required>
        	</div>
        </div>
        <span class="separator"></span>
        <div class="form-group">
            <div class="col-sm-12">
        		<input type="text" onkeyUp="calculateExpenseRowTotal(this);" name="amount[]" value="" min="1" class="form-control" placeholder="Expense Amount" required />
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
@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('plugins.Select2', true)

@section('js')
	<script type="text/javascript">
		$(function() {
			//addPORow();
			$('select.select2').select2();
			$('button.removePORow').bind('click', function() {
				$(this).parent().parent().remove();
				calculateGrandTotal();
			});
		});

		function bindRemoveClick() {
			$('button.removePORow').bind('click', function() {
				$(this).parent().parent().remove();
				calculateGrandTotal();
			});
		}

		function addPORow() {
			var strPORowHTML = $('#po-row').html();
			//strPORowHTML = strPORowHTML.replace(/_ctr/g,'_'+poDetailCounter);
			strPORowHTML = strPORowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
			$('table#myTable tbody').append('<tr><td>' + strPORowHTML + '</td></tr>');
			bindRemoveClick();
			var isSelect2Implemented = false;
			$('select[name="productID[]"]').map(function(){
				if(!$(this).hasClass('select2-hidden-accessible') && !isSelect2Implemented) {
					$(this).select2();
					isSelect2Implemented = true;
				}
			});
		}

		function calculateProductRowTotal(elem) {
			var jQElem = $(elem);
			var trElem = jQElem.parent().parent().parent().parent();
			var areAllValuesFilled = true;
			var quantity, exchangeRate, perUnitPrice, total, totalInPKR;

			if (isNaN(parseInt(trElem.find('input[name="quantity[]"]').val()))) {
				areAllValuesFilled = false;
			}
			if (isNaN(parseFloat(trElem.find('input[name="exchangeRate[]"]').val()))) {
				areAllValuesFilled = false;
			}
			if (isNaN(parseFloat(trElem.find('input[name="perUnitPrice[]"]').val()))) {
				areAllValuesFilled = false;
			}

			if (areAllValuesFilled === true) {
				quantity = parseInt(trElem.find('input[name="quantity[]"]').val());
				exchangeRate = parseFloat(trElem.find('input[name="exchangeRate[]"]').val());
				perUnitPrice = parseFloat(trElem.find('input[name="perUnitPrice[]"]').val());
				total = quantity * perUnitPrice;
				totalInPKR = total * exchangeRate;
			} else {
				total = 0;
				totalInPKR = 0;
			}

			trElem.find('input[name="total[]"]').val(total);
			trElem.find('input[name="totalInPKR[]"]').val(totalInPKR);

			calculateGrandTotal();
		}

		function calculateGrandTotal() {
			var totalFields = $('input[name="total[]"]');
			var totalInPKRFields = $('input[name="totalInPKR[]"]');
			var total = 0;
			var totalInPKR = 0;
			$(totalFields).each(function(x,y){
				if (!isNaN(parseFloat($(y).val()))) {
					total+=parseFloat($(y).val());
				}
			});

			$(totalInPKRFields).each(function(x,y){
				if (!isNaN(parseFloat($(y).val()))) {
					totalInPKR+=parseFloat($(y).val());
				}
			});

			$('#gTotal').html('&nbsp;&nbsp;&nbsp;&nbsp;' + total);
			$('#gTotalInPKR').html('&nbsp;&nbsp;&nbsp;&nbsp;Rs. ' + totalInPKR);
		}

		function bindExpenseRemoveClick() {
			$('button.removePOExpenseRow').bind('click', function() {
				$(this).parent().parent().remove();
				calculateExpenseGrandTotal();
			});
		}

		function addPOExpenseRow() {
			var strPOExpenseRowHTML = $('#po-expense-row').html();
			strPOExpenseRowHTML = strPOExpenseRowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
			$('table#expenseTable tbody').append('<tr><td>' + strPOExpenseRowHTML + '</td></tr>');
			bindExpenseRemoveClick();
		}

		function calculateExpenseRowTotal(elem) {
			var jQElem = $(elem);
			var trElem = jQElem.parent().parent().parent().parent();
			var areAllValuesFilled = true;
			var exchangeRate, amount, totalInPKR;

			if (isNaN(parseFloat(trElem.find('input[name="expenseExchangeRate[]"]').val()))) {
				areAllValuesFilled = false;
			}
			if (isNaN(parseFloat(trElem.find('input[name="amount[]"]').val()))) {
				areAllValuesFilled = false;
			}

			if (areAllValuesFilled === true) {
				exchangeRate = parseFloat(trElem.find('input[name="expenseExchangeRate[]"]').val());
				amount = parseFloat(trElem.find('input[name="amount[]"]').val());
				totalInPKR = amount * exchangeRate;
			} else {
				totalInPKR = 0;
			}

			trElem.find('input[name="totalExpenseInPKR[]"]').val(totalInPKR);

			calculateExpenseGrandTotal();
		}

		function calculateExpenseGrandTotal() {
			var totalInPKRFields = $('input[name="totalExpenseInPKR[]"]');
			var totalInPKR = 0;
			$(totalInPKRFields).each(function(x,y){
				if (!isNaN(parseFloat($(y).val()))) {
					totalInPKR+=parseFloat($(y).val());
				}
			});

			$('#expenseGTotalInPKR').html('&nbsp;&nbsp;&nbsp;&nbsp;Rs. ' + totalInPKR);
		}
	</script>
@stop
