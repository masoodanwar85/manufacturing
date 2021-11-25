@extends('adminlte::page')
@section('title', 'Purchase Order')

@section('content_header')
    <h1>Purchase Order</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-cart-plus"></i> Purchase Order
			</h3>
			@can('purchase_order_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('purchase.create') }}">
				<i class="fas fa-plus-circle"></i> Add Purchase Order
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-purchaseOrder">
				<thead>
					<tr>
						<th>Date</th>
						<th>Supplier/Customer</th>
						<th>Batch</th>
						<th>Products Total Amount</th>
						<th>Expense Total Amount</th>
						<th>Grand Total</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('plugins.Datatables', true)
@section('js')
    <script>
        $(function () {
            let dtOverrideGlobals = {
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('purchase.index') }}",
                columns: [
					{ data: 'purchaseOrderDate', name: 'purchaseOrderDate' },
                    { data: 'supplierCustomer', name: 'supplierCustomer' },
                    { data: 'batchName', name: 'batchName' },
					{ data: 'totalInPKR', name: 'totalInPKR' },
					{ data: 'totalExpenseInPKR', name: 'totalExpenseInPKR' },
					{ data: 'grandTotal', name: 'grandTotal' },
                    { data: 'actions', name: 'Actions' }
                ],
                order: [[ 0, 'desc' ]],
                pageLength: 100,
            };

            $('.datatable-purchaseOrder').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
