@extends('adminlte::page')

@section('title', 'New Sales Order')

@section('content_header')
    <h1>New Sales Order</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-share"></i> New Sales Order
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" id="frmSales" action="{{ route('sales.store') }}" method="POST" onSubmit="return formCheck();">
				@csrf
				<div class="form-group row {{ $errors->has('customerID') ? 'has-error' : '' }}">
					<label for="customerID" class="col-sm-2 col-form-label">Customer: *</label>
					<div class="col-sm-5">
						<select name="customerID" class="form-control select2 @if($errors->has('customerID')) is-invalid @endif" onChange="getCustomerData(this.value);" required>
							<option value="">Please Select Customer</option>
							@foreach($customers as $customer)
								<option value="{{ $customer->customerID }}" salesAgentID="{{ $customer->salesAgent ? $customer->salesAgent->staffID : null }}" {{ old('customerID') == $customer->customerID ? 'selected' : '' }}>{{ $customer->customerName }} ({{ $customer->shopName }}) ({{ $customer->address }})</option>
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
                        <div class="row">
                            <div class="col-sm-4">
                                <select name="bookType" id="bookType" class="form-control" required>
                                    <option value=""></option>
                                    <option value="BB">BB</option>
                                    <option value="CB">CB</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" readonly placeholder="Book Serial #" id="bookSerial" name="bookSerial" class="form-control" value="{{ old('bookSerial')}}" />
                            </div>
                            <div class="col-sm-2">
                                @can('invoice_books_create')
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#exampleModal" style="color:white;" title="Void Bill">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
				</div>
				<div class="form-group row {{ $errors->has('orderDate') ? 'has-error' : '' }}">
                    <label for="orderDate" class="col-sm-2 col-form-label">Date: *</label>
					<div class="col-sm-3">
	                    <input type="date" name="orderDate" class="form-control @if($errors->has('orderDate')) is-invalid @endif" value="{{ old('orderDate',$now) }}" required>
	                    @if($errors->has('orderDate'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('orderDate') }}
	                        </em>
	                    @endif
					</div>
					<label for="discount" class="offset-sm-2 col-sm-2 col-form-label">Discount: *</label>
					<div class="col-sm-3">
	                    <input type="number" name="discount" class="form-control @if($errors->has('discount')) is-invalid @endif" value="{{ old('discount',0) }}" required>
	                    @if($errors->has('discount'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('discount') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row {{ $errors->has('paymentDueDate') ? 'has-error' : '' }}">
                    <label for="orderDate" class="col-sm-2 col-form-label">Payment Due Date: *</label>
					<div class="col-sm-3">
	                    <input type="date" name="paymentDueDate" class="form-control @if($errors->has('paymentDueDate')) is-invalid @endif" value="{{ old('paymentDueDate',$now) }}" required>
	                    @if($errors->has('paymentDueDate'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('paymentDueDate') }}
	                        </em>
	                    @endif
					</div>
					<label for="shippingCharges" class="offset-sm-2 col-sm-2 col-form-label">Shipping Charges: *</label>
					<div class="col-sm-3">
	                    <input type="number" onkeyup="calculateGrandTotal();" name="shippingCharges" class="form-control @if($errors->has('shippingCharges')) is-invalid @endif" value="{{ old('shippingCharges',0) }}" required>
	                    @if($errors->has('shippingCharges'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('shippingCharges') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Sales Agent: </label>
					<div class="col-sm-3">
                        @if (auth()->user()->staffID == null)
                            <select name="salesAgentID" class="form-control select2">
                                <option value="">Please Select Sales Agent</option>
                                @foreach($saleAgents as $saleAgent)
                                    <option value="{{ $saleAgent->staffID }}" {{ old('salesAgentID') == $saleAgent->staffID ? 'selected' : '' }}>{{ $saleAgent->staffName }}</option>
                                @endforeach
                            </select>
                        @else
                            {!! auth()->user()->staff ? auth()->user()->staff->staffName: '' !!}
                            <input type="hidden" name="salesAgentID" value="{{ auth()->user()->staff->staffID }}" />
                        @endif
					</div>
                    <label for="description" class="offset-sm-2 col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-3">
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
								<table class="table" id="myTable">
									<thead>
										<tr class="text-center">
											<th style="text-align:center;width:25%;">Product Name</th>
											<th style="text-align:center;width:10%;">Total Units Available</th>
											<th style="text-align:center;width:15%;">Godown</th>
											<th style="text-align:center;width:10%;">Per Unit Price</th>
											<th style="text-align:center;width:8%;">Quantity</th>
											<th style="text-align:center;width:10%;">Units</th>
											<th style="text-align:center;width:10%;">Sale Price</th>
											<th style="text-align:center;width:15%;">Sub Total</th>
											<th style="text-align:center;width:5%;">Action</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
									<tfoot>
										<tr id="actionRow">
											<td colspan="8"></td>
											<td>
												<button class="btn btn-primary btn-sm pull-right " onclick="addSORow()" type="button" title="Add New Sale Item">
													<i class="nav-icon fas fa-fw fa-plus"></i>
												</button>
											</td>
										</tr>
										<tr>
											<td colspan="7" class="font-weight-bold text-right">Sub Total:</td>
											<td colspan="2" id="gSubTotal" class="font-weight-bold">0</td>
										</tr>
										<tr>
											<td colspan="7" class="font-weight-bold text-right">Shipping:</td>
											<td colspan="2" id="shippingChargesTotal" class="font-weight-bold">0</td>
										</tr>
										<tr>
											<td colspan="7" class="font-weight-bold text-right">Discount:</td>
											<td colspan="2" id="discountTotal" class="font-weight-bold">0</td>
										</tr>
										<tr>
											<td colspan="7" class="font-weight-bold text-right">Total:</td>
											<td colspan="2" id="gTotal" class="font-weight-bold">0</td>
										</tr>
										<tr>
											<td colspan="7" class="font-weight-bold text-right">Paid:</td>
											<td colspan="2">
												<input type="text" onKeyUp="calculateBalance();" name="amountPaid" id="amountPaid" value="0" class="form-control" />
											</td>
										</tr>
										<tr>
											<td colspan="6" class="font-weight-bold text-right font-urdu" id="balanceInUrdu"></td>
											<td class="font-weight-bold text-right">Balance:</td>
											<td colspan="2" id="balance" class="font-weight-bold">0</td>
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

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form name="frm" id="frmVoidSerial" action="" method="post">
					@csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Void Serial</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="prevGodown" class="col-sm-4 col-form-label">Invoice Book #:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="invoiceBookNum" readonly disabled value="" />
								<input type="hidden" id="invoiceBookNumber" name="invoiceBookNumber" value="" />
                            </div>
                        </div>
						<div class="form-group row">
							<label for="reason" class="col-sm-4 col-form-label">Reason:</label>
							<div class="col-sm-8">
								<textarea class="form-control" id="reason" name="reason" required></textarea>
							</div>
						</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="voidThisSerial();" data-dismiss="modal" class="btn btn-primary">Void</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.sales.dynamicFields')
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
@section('plugins.daterangepicker', true)
@section('js')
	<script src="/js/utils.js"></script>
	@include('admin.sales.formJS', ['isNew' => true])
@stop
