@extends('adminlte::page')

@section('title', 'New Purchase Order')

@section('content_header')
    <h1>New Purchase Order</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-cart-plus"></i> New Purchase Order
			</h3>
		</div>

		<div class="card-body">
			<form class="form-horizontal" action="{{ route('purchase.store') }}" method="POST">
				@csrf
				<div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Supplier / Customer: *</label>
					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" name="isSupplier" value="1" checked > Supplier
						</label>&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="isSupplier" value="0" > Customer
						</label>
					</div>
                </div>

				<div class="supplierDiv form-group row {{ $errors->has('supplierID') ? 'has-error' : '' }}">
					<label for="supplierID" class="col-sm-2 col-form-label">Supplier: *</label>
					<div class="col-sm-5">
						<select name="supplierID" class="form-control select2 @if($errors->has('supplierID')) is-invalid @endif" onChange="getSupplierBalance(this.value);" required>
							<option value="">Please Select Supplier</option>
							@foreach($suppliers as $supplier)
								<option value="{{ $supplier->supplierID }}" {{ old('supplierID') == $supplier->supplierID ? 'selected' : '' }}>{{ $supplier->supplierName }}</option>
							@endforeach
						</select>
						@if($errors->has('supplierID'))
							<em class="invalid-feedback">
								{{ $errors->first('supplierID') }}
							</em>
						@endif
					</div>
					<div class="col-sm-5">
						<span style="color:red;font-weight:bold;" id="supplierBalance"></span>
					</div>
				</div>

				<div style="display:none;" class="customerDiv form-group row {{ $errors->has('customerID') ? 'has-error' : '' }}">
					<label for="customerID" class="col-sm-2 col-form-label">Customer: *</label>
					<div class="col-sm-5">
						<select name="customerID" class="form-control select2 @if($errors->has('customerID')) is-invalid @endif" onChange="getCustomerBalance(this.value);">
							<option value="">Please Select Customer</option>
							@foreach($customers as $customer)
								<option value="{{ $customer->customerID }}" {{ old('customerID') == $customer->customerID ? 'selected' : '' }}>{{ $customer->customerName }}</option>
							@endforeach
						</select>
						@if($errors->has('customerID'))
							<em class="invalid-feedback">
								{{ $errors->first('customerID') }}
							</em>
						@endif
					</div>
					<div class="col-sm-5">
						<span style="color:red;font-weight:bold;" id="customerBalance"></span>
					</div>
				</div>
				<div class="form-group row {{ $errors->has('batchID') ? 'has-error' : '' }}">
					<label for="batchID" class="col-sm-2 col-form-label">Batch: *</label>
					<div class="col-sm-2">
						<select name="batchID" class="form-control select2 @if($errors->has('batchID')) is-invalid @endif" required>
							<option value="">Please Select Batch</option>
							@foreach($batches as $batch)
								<option value="{{ $batch->batchID }}" {{ old('batchID',$currentBatch->batchID) == $batch->batchID ? 'selected' : '' }}>{{ $batch->batchName }}</option>
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
	                    <input type="date" name="purchaseOrderDate" class="form-control @if($errors->has('purchaseOrderDate')) is-invalid @endif" value="{{ old('purchaseOrderDate',date('Y-m-d')) }}" required>
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
								<option value="{{ $godown->godownID }}" {{ old('lastGodownID') == $godown->godownID ? 'selected' : '' }}>{{ $godown->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-10">
                    	<textarea name="description" class="form-control">{{ old('description') }}</textarea>
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

									</tbody>
									<tfoot>
										<tr>
											<td colspan="{{ $colspanValue - 3 }}" class="font-weight-bold text-right font-urdu" id="moneyInUrdu"></td>
                                            <td class="font-weight-bold text-right">Total:</td>
											<td id="gTotal" class="font-weight-bold"></td>
											<td id="gTotalInPKR" class="font-weight-bold"></td>
										</tr>
										<tr id="actionRow">
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
                                <?php $colspanValue = 5; ?>
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

									</tbody>
									<tfoot>
										<tr>
											<td colspan="{{ $colspanValue-2 }}" class="font-weight-bold text-right font-urdu" id="expenseInUrdu"></td>
											<td class="font-weight-bold text-right">Total:</td>
											<td colspan="2" id="expenseGTotalInPKR" class="font-weight-bold"></td>
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
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </form>
        </div>
    </div>
	@include('admin.purchase.dynamicFields')
@endsection

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
	@include('admin.purchase.formJS', ['isNew' => true])
@stop
