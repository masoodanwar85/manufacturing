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
				<div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Supplier / Customer: *</label>
					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" name="isSupplier" value="1" {{ $purchase->supplierID != null ? 'checked' : '' }} > Supplier
						</label>&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="isSupplier" value="0" {{ $purchase->customerID != null ? 'checked' : '' }}> Customer
						</label>
					</div>
                </div>
				<div {!! $purchase->supplierID != null ? '' : 'style="display:none !important;"' !!} class="supplierDiv form-group row {{ $errors->has('supplierID') ? 'has-error' : '' }}">
					<label for="supplierID" class="col-sm-2 col-form-label">Supplier: *</label>
					<div class="col-sm-5">
						<select name="supplierID" class="form-control select2 @if($errors->has('supplierID')) is-invalid @endif" {!! $purchase->supplierID != null ? 'required' : '' !!} onChange="getSupplierBalance(this.value);">
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
					<div class="col-sm-5">
						<span style="color:red;font-weight:bold;" id="supplierBalance"> Balance: {{ \App\Services\CurrencyService::getCurrencyFormatted($totalPayable * -1) }}</span>
					</div>
				</div>
				<div {!! $purchase->customerID != null ? '' : 'style="display:none !important;"' !!} class="customerDiv form-group row {{ $errors->has('customerID') ? 'has-error' : '' }}">
					<label for="customerID" class="col-sm-2 col-form-label">Customer: *</label>
					<div class="col-sm-5">
						<select name="customerID" class="form-control select2 @if($errors->has('customerID')) is-invalid @endif" {!! $purchase->customerID != null ? 'required' : '' !!} onChange="getCustomerBalance(this.value);">
							<option value="">Please Select Customer</option>
							@foreach($customers as $customer)
								<option value="{{ $customer->customerID }}" {{ old('customerID', $purchase->customerID) == $customer->customerID ? 'selected' : '' }}>{{ $customer->customerName }}</option>
							@endforeach
						</select>
						@if($errors->has('customerID'))
							<em class="invalid-feedback">
								{{ $errors->first('customerID') }}
							</em>
						@endif
					</div>
					<div class="col-sm-5">
						<span style="color:red;font-weight:bold;" id="customerBalance">Balance: {{ \App\Services\CurrencyService::getCurrencyFormatted($totalPayable * -1) }}</span>
					</div>
				</div>
				<div class="form-group row {{ $errors->has('batchID') ? 'has-error' : '' }}">
					<label for="batchID" class="col-sm-2 col-form-label">Batch: *</label>
					<div class="col-sm-2">
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
					<label for="purchaseOrderDate" class="col-sm-2 col-form-label">Date: *</label>
					<div class="col-sm-2">
	                    <input type="date" name="purchaseOrderDate" class="form-control @if($errors->has('purchaseOrderDate')) is-invalid @endif" value="{{ old('purchaseOrderDate',$purchase->purchaseOrderDate) }}" required>
	                    @if($errors->has('purchaseOrderDate'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('purchaseOrderDate') }}
	                        </em>
	                    @endif
					</div>
					<label for="lastGodownID" class="col-sm-2 col-form-label">Unload to Godown:</label>
					<div class="col-sm-2">
						<select name="lastGodownID" class="form-control select2">
							<option value="">Please Select Godown</option>
							@foreach($godowns as $godown)
								<option value="{{ $godown->godownID }}" {{ old('lastGodownID',$purchase->lastGodownID) == $godown->godownID ? 'selected' : '' }}>{{ $godown->name }}</option>
							@endforeach
						</select>
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
                                <?php $colspanValue = 5; ?>
								<table class="table" id="myTable">
									<thead>
										<tr class="text-center">
											<th style="text-align:center;width:25%;">Product Name</th>
											<th style="text-align:center;width:10%;">Total Qty</th>
											<th style="text-align:center;width:10%;">Units</th>
											@if ($globalSettings['client_settings.is_show_damaged_units'] == 1)
											<th style="text-align:center;width:5%;">Damaged</th>
                                            <?php $colspanValue++; ?>
											@endif
                                            @if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
											<th style="text-align:center;width:10%;">Exchange Rate</th>
                                            <?php $colspanValue++; ?>
                                            @endif
											<th style="text-align:center;width:15%;">Per Unit Price</th>
                                            @if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
											<th style="text-align:center;width:15%;">Total</th>
                                            <?php $colspanValue++; ?>
                                            @endif
											<th style="text-align:center;width:15%;">Total (PKR)</th>
											<th style="text-align:center;width:5%;">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$gTotal = 0;
											$gTotalInPKR = 0;
                                            $totalUnits = 0;
										?>
										@foreach($purchase->purchaseOrderDetails as $purchaseOrderDetail)
											<?php
												$rowTotalInPKR = $purchaseOrderDetail->perUnitPrice*$purchaseOrderDetail->quantityUnits / $purchaseOrderDetail->exchangeRate;
												if ($globalSettings['client_settings.operatorToConvertToPKR'] == '*') {
													$rowTotalInPKR = $purchaseOrderDetail->perUnitPrice*$purchaseOrderDetail->quantityUnits * $purchaseOrderDetail->exchangeRate;
												}
												$gTotalInPKR+=$rowTotalInPKR;
												$gTotal+=$purchaseOrderDetail->perUnitPrice*$purchaseOrderDetail->quantityUnits;
                                                $totalUnits += $purchaseOrderDetail->quantityUnits;
											?>
											<tr>
												<td>
													<div class="form-group">
											  			<div class="col-sm-12">
											  				<select name="productID[]" class="form-control" required>
											                    <option value=""></option>
											                    @foreach ($products as $product)
											  					    <option value="{{$product->productID}}" {{ $purchaseOrderDetail->productID == $product->productID ? 'selected' : '' }}>{{$product->productName}} ( {{$product->category->categoryName}} ) @if ($product->unitsInProduct > 1) - {{ $product->unitsInProduct}} {{$product->maximumUnit->symbol}} / unit @endif</option>
															    @endforeach
											  				</select>
											  			</div>
														<input type="hidden" name="unitsInProduct[]" value="{{ $purchaseOrderDetail->product->unitsInProduct }}" class="form-control" min="1" required>
											    	</div>
												</td>
												<td>
													<div class="form-group">
											      		<div class="col-sm-12">
															<input type="number" name="quantity[]" value="{{ $purchaseOrderDetail->quantity }}" class="form-control" min="1" placeholder="Quantity" required>
															@if ($globalSettings['client_settings.is_units_in_product_fixed'] == 0)
																<input type="hidden" name="prevQty[]" value="{{$purchaseOrderDetail->quantity}}" >
															@endif
											      		</div>
											    	</div>
												</td>
												<td>
													<div class="form-group">
											      		<div class="col-sm-12">
															<input type="text" onkeyUp="calculateProductRowTotal(this);" name="totalUnits[]" value="{{ $purchaseOrderDetail->quantityUnits }}" class="form-control" min="1" required {{ $purchaseOrderDetail->product->isUnitsInProductFixed == 1 ? 'readonly': '' }} />
															<span class="productUnit[]">{{ $purchaseOrderDetail->product->maximumUnit->symbol }}(s)</span>
											      		</div>
											    	</div>
												</td>
												@if ($globalSettings['client_settings.is_show_damaged_units'] == 1)
												<td>
													<div class="form-group">
											      		<div class="col-sm-12">
															<input type="number" name="damaged[]" value="{{ $purchaseOrderDetail->damaged }}" class="form-control" min="0" placeholder="Damaged" required>
											      		</div>
											    	</div>
												</td>
												@else
												<input type="hidden" name="damaged[]" value="{{ $purchaseOrderDetail->damaged }}" />
												@endif
                                                @if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
												<td>
													<div class="form-group">
											            <div class="col-sm-12">
											        		<input type="text" onkeyUp="calculateProductRowTotal(this);" name="exchangeRate[]" value="{{ $purchaseOrderDetail->exchangeRate }}" class="form-control" min="1" placeholder="Exchange Rate" required>
											        	</div>
											        </div>
												</td>
                                                @else
                                                <input type="hidden" name="exchangeRate[]" value="1" />
                                                @endif
												<td>
													<div class="form-group">
											            <div class="col-sm-12">
											        		<input type="text" onkeyUp="calculateProductRowTotal(this);" name="perUnitPrice[]" value="{{ $purchaseOrderDetail->perUnitPrice }}" class="form-control" min="1" placeholder="Unit Price" required>
                                                            <div class="font-urdu perUnitPriceInUrdu">

                                                			</div>
											        	</div>
											        </div>
												</td>
                                                @if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
												<td>
													<div class="form-group">
											            <div class="col-sm-12">
											        		<input type="text" disabled name="total[]" value="{{ $purchaseOrderDetail->perUnitPrice * $purchaseOrderDetail->quantityUnits }}" class="form-control" />
											        	</div>
											        </div>
												</td>
                                                @else
                                                <input type="hidden" name="total[]" value="{{ $purchaseOrderDetail->perUnitPrice * $purchaseOrderDetail->quantityUnits }}" />
                                                @endif
												<td>
													<div class="form-group">
											            <div class="col-sm-12">
											        		<input type="text" disabled name="totalInPKR[]" value="@money('$rowTotalInPKR','')" class="form-control" />
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
                                            <td colspan="{{ $colspanValue - 2 }}" class="font-weight-bold text-right font-urdu" id="moneyInUrdu"></td>
                                            <td class="font-weight-bold text-right">Total:</td>
                                            @if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
                                            <td id="gTotal" class="font-weight-bold">&nbsp;&nbsp;&nbsp;&nbsp;{{ $gTotal }}</td>
                                            @endif
                                            <td id="gTotalInPKR" class="font-weight-bold">&nbsp;&nbsp;&nbsp;&nbsp;Rs. @money('$gTotalInPKR','')</td>
										</tr>
										<tr>
											<td colspan="{{ $colspanValue }}"></td>
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
                                <?php $colspanValue = 4; ?>
								<table class="table" id="expenseTable">
									<thead>
										<tr class="text-center">
											<th style="text-align:center;">Expense</th>
                                            @if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
											<th style="text-align:center;">Exchange Rate</th>
                                            <?php $colspanValue++; ?>
                                            @endif
											<th style="text-align:center;">Per Unit</th>
                                            <th style="text-align:center;">Amount</th>
                                            <th style="text-align:center;">Total (PKR)</th>
											<th style="text-align:center;">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$expenseGTotalInPKR = 0;
										?>
										@foreach($purchase->transactions as $purchaseOrderTransactions)
											@if ($purchaseOrderTransactions->pivot->isExpense == 1)
												<?php
													$rowTotalInPKR = $purchaseOrderTransactions->transactionDetails[0]->amount / $purchaseOrderTransactions->exchangeRate;
													if ($globalSettings['client_settings.operatorToConvertToPKR'] == '*') {
														$rowTotalInPKR = $purchaseOrderTransactions->transactionDetails[0]->amount * $purchaseOrderTransactions->exchangeRate;
													}

													$expenseGTotalInPKR+=$rowTotalInPKR;
													$subHeadID = $purchaseOrderTransactions->transactionDetails[0]->subHead->headID;
												?>
												<tr>
													<td>
														<div class="form-group">
												  			<div class="col-sm-12">
																@include('partials.accountHeadsDropdown',['accountHeads' => $purchaseOrderExpense,'name' => 'headID[]', 'value' => $subHeadID,'isRequired' => true])
												  				{{-- <select name="headID[]" class="form-control" required>
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
												  				</select>--}}
															</div>
												    	</div>
													</td>
                                                    @if ($globalSettings['client_settings.is_show_exchange_rate'] == 1)
                                                    <td>
												        <div class="form-group">
												            <div class="col-sm-12">
												        		<input type="text" onkeyUp="calculateExpenseRowTotal(this);" name="expenseExchangeRate[]" value="{{ $purchaseOrderTransactions->exchangeRate }}" class="form-control" min="1" placeholder="Exchange Rate" required>
												        	</div>
												        </div>
										        	</td>
                                                    @else
                                                        <input type="hidden" name="expenseExchangeRate[]" value="1" />
                                                    @endif
													<td>
												        <div class="form-group">
												            <div class="col-sm-12">
												        		<input type="text" name="perUnitExpense[]" value="@money('$purchaseOrderTransactions->transactionDetails[0]->amount/$totalUnits','')" min="1" class="form-control" required />
												        	</div>
												        </div>
													</td>
                                                    <td>
												        <div class="form-group">
												            <div class="col-sm-12">
												        		<input type="text" onkeyUp="calculateExpenseRowTotal(this);" name="amount[]" value="@money('$purchaseOrderTransactions->transactionDetails[0]->amount','')" min="1" class="form-control" placeholder="Expense Amount" required />
												        	</div>
												        </div>
													</td>
                                                    <td>
														<div class="form-group">
												            <div class="col-sm-12">
												        		<input type="text" disabled name="totalExpenseInPKR[]" value="@money('$rowTotalInPKR','')" class="form-control" />
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
											<td colspan="{{ $colspanValue-2 }}" class="font-weight-bold text-right font-urdu" id="expenseInUrdu"></td>
											<td class="font-weight-bold text-right">Total:</td>
											<td colspan="2" id="expenseGTotalInPKR" class="font-weight-bold">@money('$expenseGTotalInPKR')</td>
										</tr>
										<tr>
											<td colspan="{{ $colspanValue }}"></td>
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
	@include('admin.purchase.dynamicFields')
@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
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
	@include('admin.purchase.formJS', ['isNew' => false])
@stop
