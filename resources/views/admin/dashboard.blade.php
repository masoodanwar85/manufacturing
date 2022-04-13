@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
	<div class="row">
		@can('sales_create')
		<div class="col">
            <a href="{{ route('purchase.index') }}">
    			<div class="info-box mb-3">
    				<span class="info-box-icon bg-primary elevation-1"><i class="fas fa-shopping-cart"></i></span>
    				<div class="info-box-content">
    					<span class="info-box-text">Purchase Orders</span>
    					<span class="info-box-number">{{ $totalPurchases }}</span>
    				</div>
    			</div>
            </a>
			<a style="color:white;" href="{{ route('purchase.create') }}" type="button" class="btn btn-block btn-primary btn-lg"><i class="fas fa-plus-circle"></i> New Purchase</a>
		</div>
		@endcan
		@can('sales_create')
		<div class="col">
            <a href="{{ route('sales.index') }}">
    			<div class="info-box mb-3">
    				<span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-shopping-cart"></i></span>
    				<div class="info-box-content">
    					<span class="info-box-text">Sales</span>
    					<span class="info-box-number">{{ $totalSales }}</span>
    				</div>
    			</div>
            </a>
			<a style="color:white;" href="{{ route('sales.create') }}" type="button" class="btn btn-block btn-secondary btn-lg"><i class="fas fa-plus-circle"></i> New Sale</a>
		</div>
		@endcan
		@can('sales_create')
		<div class="col">
            <a href="{{ route('accountHead.paymentsReceipts') }}">
    			<div class="info-box mb-3">
    				<span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
    				<div class="info-box-content">
    					<span class="info-box-text">Payments</span>
    					<span class="info-box-number"></span>
    				</div>
    			</div>
            </a>
			<a style="color:white;" href="{{ route('accountHead.payment') }}" type="button" class="btn btn-block btn-success btn-lg"><i class="fas fa-plus-circle"></i> New Payment</a>
		</div>
		@endcan
		@can('sales_create')
		<div class="col">
            <a href="{{ route('accountHead.paymentsReceipts') }}">
    			<div class="info-box mb-3">
    				<span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>
    				<div class="info-box-content">
    					<span class="info-box-text">Receipts</span>
    					<span class="info-box-number"></span>
    				</div>
    			</div>
            </a>
			<a style="color:white;" href="{{ route('accountHead.receipt') }}" type="button" class="btn btn-block btn-info btn-lg"><i class="fas fa-plus-circle"></i> New Receipt</a>
		</div>
		@endcan
	</div>
	<hr />
    <div class="row">
		@can('attendance_create')
		<div class="col">
            <a href="{{ route('attendance.index') }}">
    			<div class="info-box mb-3">
    				<span class="info-box-icon @if($attendance) bg-success @else bg-danger @endif elevation-1"><i class="fas fa-clipboard-check"></i></span>
    				<div class="info-box-content">
    					<span class="info-box-text">Attendance</span>
    					<span class="info-box-number">@if ($attendance) Marked @else Not Marked @endif</span>
    				</div>
    			</div>
            </a>
		</div>
		@endcan
        <div class="col">

        </div>
        <div class="col">

        </div>
        <div class="col">

        </div>
    </div>
    <hr />
    <h3>Search</h3>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <form name="frmBB" method="get" action="{{ route('dashboard.search') }}">
                <input type="hidden" name="searchBy" value="BB" />
                <h6>Search By Bill Book #</h6>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="transactionTypeNumber" value="" placeholder="Bill Book #" />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">Search</button>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <form name="frmCB" method="get" action="{{ route('dashboard.search') }}">
                <input type="hidden" name="searchBy" value="CB" />
                <h6>Search By Cash Book #</h6>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="transactionTypeNumber" value="" placeholder="Cash Book #" />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">Search</button>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <form name="frmRB" method="get" action="{{ route('dashboard.search') }}">
                <input type="hidden" name="searchBy" value="RB" />
                <h6>Search By Receipt Book #</h6>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="transactionTypeNumber" value="" placeholder="Receipt Book #" />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">Search</button>
                    </span>
                </div>
            </form>
        </div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<h6>View Customer Sales</h6>
			<div class="input-group input-group-sm">
				<select name="customerID" class="form-control" id="customerID">
					<option value="">Select Customer</option>
					@foreach ($customers as $customer)
						<option value="{{$customer->customerID}}">{{$customer->customerName}} ({{ $customer->address }})</option>
					@endforeach
				</select>
				<span class="input-group-append">
					<button type="button" onclick="jumpToSales()" class="btn btn-info btn-flat">Search</button>
				</span>
			</div>
        </div>
    </div>
    <hr />

	<div class="row">
        <div class="col">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Statistics &nbsp;&nbsp;</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Head</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td>Customer Receivables</td>
                                    <td class="text-right">@money('$receivables["customers"]')</td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td>Staff Receivables</td>
                                    <td class="text-right">@money('$receivables["staff"]')</td>
                                </tr>
                                <tr>
                                    <td>3.</td>
                                    <td>Supplier Receivables</td>
                                    <td class="text-right">@money('$receivables["suppliers"]')</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
			<div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Monthly Receivables Statistics &nbsp;&nbsp;</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
						<div class="row">
							<div class="col"></div>
							<div class="col"></div>
							<div class="col">
								<form id="monthlyReceivables">
									@csrf
									<div class="input-group input-group-sm">
					                    <input type="month" class="form-control" name="month" value="<?php echo date('Y-m'); ?>" />
					                    <span class="input-group-append">
					                        <button type="button" onclick="sendAjaxRequest('getReceivableInfo','monthlyReceivables')" class="btn btn-info btn-flat">Submit</button>
					                    </span>
					                </div>
								</form>
							</div>
						</div>

                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Party</th>
									<th class="text-right">Receivables</th>
									<th class="text-right">Received</th>
									<th class="text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="receivable-customer-row">
                                    <td>1.</td>
                                    <td>Customer</td>
                                    <td class="text-right receivables">0</td>
									<td class="text-right received">0</td>
									<td class="text-right balance">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="row">
        		@if ($thresholdStocks)
        			<div class="col-12">
        				<div class="card card-warning">
        					<div class="card-header">
        						<h3 class="card-title">Stock Alert &nbsp;&nbsp;<span title="{{ count($thresholdStocks) }} Products" class="badge bg-danger">{{ count($thresholdStocks) }}</span></h3>
        						<div class="card-tools">
        							<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        						</div>
        					</div>
        					<div class="card-body">
        						<table class="table table-hover table-sm">
        							<thead>
        								<tr>
        									<th>#</th>
        									<th>Product</th>
        									<th class="text-center">Quantity Remaining</th>
        									<th class="text-center">Alert Quantity</th>
        								</tr>
        							</thead>
        							<tbody>
        								@foreach ($thresholdStocks as $thresholdProduct)
        								<tr>
        									<td>{{ $loop->iteration }}</td>
        									<td>{{ $thresholdProduct->productName }}</td>
        									<td class="text-center">{{ $thresholdProduct->inStockQuantity }}</td>
        									<td class="text-center">{{ $thresholdProduct->thresholdUnit }}</td>
        								</tr>
        								@endforeach
        							</tbody>
        						</table>
        					</div>
        				</div>
        			</div>
        		@endif
        		<div class="col-12">
        			<div class="card card-warning">
        				<div class="card-header">
        					<h3 class="card-title">Customer Sales Due Date Alert &nbsp;&nbsp;<span title="0 Customer" class="badge bg-danger">0</span></h3>
        					<div class="card-tools">
        						<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        					</div>
        				</div>
        				<div class="card-body">
        					<table class="table table-hover table-sm">
        						<thead>
        							<tr>
        								<th>Customer</th>
        								<th>Invoice</th>
        								<th>Due Date</th>
        							</tr>
        						</thead>
        						<tbody>
        							<tr>
        								<td colspan="3">Working on it..</td>
        							</tr>
        						</tbody>
        					</table>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>
    </div>
@stop

@section('js')
    <script>
		$(function() {
			sendAjaxRequest('getReceivableInfo','monthlyReceivables');
		});
		function jumpToSales() {
			var customerID = $('#customerID').val();
			if (!isNaN(parseInt(customerID))) {
				location.href = '/admin/customer/' + customerID;
			}
		}

		function sendAjaxRequest(method, formID) {
			$.ajax({
				url: '/admin/ajax/'+method,
				type: 'POST',
				data: $('#'+formID).serialize(),
				success: function(returned) {
					handleResponse(method,returned);
				}
			});
		}

		function handleResponse(method,response) {
			switch(method) {
				case 'getReceivableInfo':
					var customerRow = $('tr.receivable-customer-row');
					var customerBalance = parseInt(response.customer.receivables) - parseInt(response.customer.received);
					customerRow.find('td.receivables').html('Rs. ' + Number(response.customer.receivables).toFixed(0));
					customerRow.find('td.received').html('Rs. ' + Number(response.customer.received).toFixed(0));
					customerRow.find('td.balance').html('Rs. ' + Math.abs(Number(customerBalance).toFixed(0)));
					if (Number(customerBalance) > 0) {
						customerRow.find('td.balance').removeClass('table-success').addClass('table-danger');
					} else {
						customerRow.find('td.balance').removeClass('table-danger').addClass('table-success');
					}
					break;
				default:
					break;
			}
		}
    </script>
@stop
