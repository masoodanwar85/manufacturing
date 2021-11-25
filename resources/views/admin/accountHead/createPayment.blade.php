@extends('adminlte::page')

@section('title', 'New Payment')

@section('content_header')
    <h1>New @if ($isPayment == true) Payment @else Receipt @endif</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-money-bill-alt"></i> New @if ($isPayment == true) Payment @else Receipt @endif
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="@if ($isPayment == true) {{ route('accountHead.doPayment') }} @else {{route('accountHead.doReceipt')}} @endif" method="POST">
				@csrf
				<div class="form-group row">
					<label for="paymentTo" class="col-sm-2 col-form-label">@if ($isPayment == true) Payment to @else Receipt from @endif: *</label>
					<div class="col-sm-3">
						<select name="paymentTo" class="form-control" onChange="changePaymentForField(this.value);" required>
							@if ($isPayment == true)
							<option value="supplier">Supplier</option>
							<option value="expense">Expense</option>
                            <option value="salaries">Salaries</option>
                            <option value="godown">Godown</option>
                            <option value="transport">Transport</option>
							@endif
							<option value="customer">Customer</option>
							@if ($isPayment == false)
							<option value="income">Income</option>
							@endif
							<option value="staff">Staff</option>
							<option value="bank">Bank</option>
						</select>
					</div>
					<label for="paymentDate" class="col-sm-2 offset-sm-2 col-form-label">Date: *</label>
					<div class="col-sm-3">
	                    <input type="date" name="transactionDate" class="form-control" value="{{ date('Y-m-d') }}" required>
					</div>
				</div>
				@if ($isPayment == true)
				<div class="supplierDiv form-group row {{ $errors->has('supplierID') ? 'has-error' : '' }}">
					<label for="supplierID" class="col-sm-2 col-form-label">Supplier: *</label>
					<div class="col-sm-5">
						<select name="supplierID" class="form-control select2 @if($errors->has('supplierID')) is-invalid @endif" onChange="getSupplierBalance(this.value);" required>
							<option value="">Please Select Supplier</option>
							@foreach($suppliers as $supplier)
								<option value="{{ $supplier->supplierID }}">{{ $supplier->supplierName }}</option>
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
				@endif
				<div @if ($isPayment == true) style="display:none;" @endif class="customerDiv form-group row {{ $errors->has('customerID') ? 'has-error' : '' }}">
					<label for="customerID" class="col-sm-2 col-form-label">Customer: *</label>
					<div class="col-sm-5">
						<select name="customerID" class="form-control select2 @if($errors->has('customerID')) is-invalid @endif" onChange="getCustomerBalance(this.value);">
							<option value="">Please Select Customer</option>
							@foreach($customers as $customer)
								<option value="{{ $customer->customerID }}">{{ $customer->customerName }} ({{ $customer->address }})</option>
							@endforeach
						</select>
						@if($errors->has('customerID'))
							<em class="invalid-feedback">
								{{ $errors->first('customerID') }}
							</em>
						@endif
					</div>
					<div class="col-sm-2">
						<span style="color:red;font-weight:bold;" id="customerBalance"></span>
					</div>
                    <div class="col-sm-3">
                        <input type="text" name="transactionTypeNumber" class="form-control" value="" placeholder="Bill Number">
                    </div>
				</div>
				@if ($isPayment == true)
				<div style="display:none;" class="expenseDiv form-group row">
					<label for="expenseHeadID" class="col-sm-2 col-form-label">Expense: *</label>
					<div class="col-sm-10">
						<select name="expenseHeadID" class="select2 form-control">
							<option value=""></option>
							@foreach ($expenses as $expense)
							<option value="{{$expense->headID}}">{{$expense->headName}}</option>
							@endforeach
						</select>
					</div>
				</div>
                <div style="display:none;" class="godownDiv form-group row">
					<label for="godownHeadID" class="col-sm-2 col-form-label">Godown: *</label>
					<div class="col-sm-5">
						<select name="godownHeadID" class="select2 form-control" onChange="getGodownBalance(this.value);">
                            <option value=""></option>
							@foreach ($godowns as $godown)
							<option value="{{$godown->head->headID}}">{{$godown->name}} {{$godown->address}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-sm-5">
						<span style="color:red;font-weight:bold;" id="godownBalance"></span>
					</div>
				</div>
                <div style="display:none;" class="transportDiv form-group row">
					<label for="transportHeadID" class="col-sm-2 col-form-label">Transport: *</label>
					<div class="col-sm-5">
						<select name="transportHeadID" class="select2 form-control" onChange="getTransportBalance(this.value);">
							<option value=""></option>
							@foreach ($transports as $transport)
							<option value="{{$transport->head->headID}}">{{$transport->name}} ({{$transport->vehicleNumber}}) - {{$transport->owner}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-sm-5">
						<span style="color:red;font-weight:bold;" id="transportBalance"></span>
					</div>
				</div>
				@else
				<div style="display:none;" class="incomeDiv form-group row">
					<label for="incomeHeadID" class="col-sm-2 col-form-label">Income: *</label>
					<div class="col-sm-10">
						<select name="incomeHeadID" class="select2 form-control">
							<option value=""></option>
							@foreach ($incomes as $income)
							<option value="{{$income->headID}}">{{$income->headName}}</option>
							@endforeach
						</select>
					</div>
				</div>
				@endif
				<div style="display:none;" class="staffDiv form-group row">
					<label for="customerID" class="col-sm-2 col-form-label">Staff: *</label>
					<div class="col-sm-5">
						<select name="staffID" class="select2 form-control" onChange="getStaffBalance(this.value);">
							@foreach ($staffs as $staff)
							<option value="{{$staff->staffID}}">{{$staff->staffName}} ({{$staff->staffType->staffType}})</option>
							@endforeach
						</select>
					</div>
					<div class="col-sm-5">
						<span style="color:red;font-weight:bold;" id="staffBalance"></span>
					</div>
				</div>
				<div style="display:none;" class="bankDiv form-group row">
					<label for="bankAccountID" class="col-sm-2 col-form-label">Bank Account: *</label>
					<div class="col-sm-5">
						<select name="bankAccountID" class="select2 form-control" onChange="getBankAccountBalance(this.value);">
							@foreach ($bankAccounts as $bankAccount)
							<option value="{{$bankAccount->bankAccountID}}">{{$bankAccount->bank->bankName}} - {{$bankAccount->accountTitle}} - {{$bankAccount->accountNumber}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-sm-2">
						<span style="color:red;font-weight:bold;" id="bankAccountBalance"></span>
					</div>
				</div>
				<hr />
				<div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">@if ($isPayment == true) Payment @else Receive @endif Via: *</label>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-sm-2">
								<label class="checkbox-inline">
									<input type="checkbox" name="paymentVia[]" value="cash" checked> Cash
								</label>
							</div>
							<div class="col-sm-2">
								<label class="checkbox-inline">
									<input type="checkbox" id="paymentViaCheque" name="paymentVia[]" value="cheque" > Cheque
								</label>
							</div>
							@if($isPayment == true)
							<div class="col-sm-2">
								<label class="checkbox-inline">
									<input type="checkbox" id="paymentViaStaff" name="paymentVia[]" value="staff" > Staff
								</label>
							</div>
							@endif
							<div class="col-sm-2">
								<label class="checkbox-inline">
									<input type="checkbox" name="paymentVia[]" value="bank" > Bank
								</label>
							</div>
						</div>
					</div>
                </div>
				<div class="viaCashDiv form-group row">
					<label for="cash" class="col-sm-2 col-form-label">Cash: *</label>
					<div class="col-sm-10">
						<input type="number" name="cashAmount" class="form-control" min="1" value="" required>
					</div>
				</div>
				<div style="display:none;" class="viaChequeDiv form-group row">
					<label for="cheques" class="col-sm-2 col-form-label">Cheques: *</label>
					<div class="col-sm-10">
						<table class="table table-hover" id="myTable">
							<thead class="table-secondary">
								<tr class="text-center">
									<th style="text-align:center;">Bank</th>
									<th style="text-align:center;">Cheque No.</th>
									<th style="text-align:center;">Cheque Date</th>
									<th style="text-align:center;">Cheque Amount</th>
									<th style="text-align:center;">Action</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
							<tfoot>
								<tr>
									<td colspan="3" class="font-weight-bold text-right">Total:</td>
									<td colspan="2" id="chequeTotal" class="font-weight-bold"></td>
								</tr>
								<tr id="actionRow">
									<td colspan="4"></td>
									<td>
										<button class="btn btn-primary btn-sm float-right " onclick="addChequeRow()" type="button" title="Add New Cheque">
											<i class="nav-icon fas fa-fw fa-plus"></i>
										</button>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div style="display:none;" class="viaStaffDiv form-group row">
					<label for="customerID" class="col-sm-2 col-form-label">Staff: *</label>
					<div class="col-sm-4">
						<select name="viaStaffID" class="select2 form-control">
							<option value=""></option>
							@foreach ($staffs as $staff)
							<option value="{{$staff->staffID}}">{{$staff->staffName}} ({{$staff->staffType->staffType}})</option>
							@endforeach
						</select>
					</div>
                    <label for="viaStaffAmount" class="offset-sm-1 col-sm-2 col-form-label">Amount: *</label>
                    <div class="col-sm-3">
						<input type="number" name="viaStaffAmount" class="form-control" min="1" value="" />
					</div>
				</div>
				<div style="display:none;" class="viaBankDiv form-group row">
					<label for="bankAccountID" class="col-sm-2 col-form-label">Bank Account: *</label>
					<div class="col-sm-4">
						<select name="viaBankAccountID" class="select2 form-control">
							<option value=""></option>
							@foreach ($bankAccounts as $bankAccount)
							<option value="{{$bankAccount->bankAccountID}}">{{$bankAccount->bank->bankName}} - {{$bankAccount->accountTitle}} - {{$bankAccount->accountNumber}}</option>
							@endforeach
						</select>
					</div>
                    <label for="viaBankAmount" class="offset-sm-1 col-sm-2 col-form-label">Amount: *</label>
                    <div class="col-sm-3">
						<input type="number" name="viaBankAmount" class="form-control" min="1" value="" />
					</div>
				</div>
				<hr />
				<div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-10">
                    	<textarea name="description" class="form-control"></textarea>
					</div>
                </div>

				<div>
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </form>
        </div>
    </div>
	@include('admin.accountHead.dynamicFields')
@endsection

@section('css')
    <link rel="stylesheet" href="/vendor/adminlte/dist/css/adminlte.css">
@stop

@section('js')
	@include('admin.accountHead.formJS', ['isPayment' => $isPayment])
@stop
