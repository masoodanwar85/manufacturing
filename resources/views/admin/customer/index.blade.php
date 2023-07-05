@extends('adminlte::page')
@section('title', 'Customers')

@section('content_header')
    <h1>Customers</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-male"></i> Customers
			</h3>
			@can('customer_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('customer.create') }}">
				<i class="fas fa-plus-circle"></i> Add Customer
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Customer">
				<thead>
					<tr>
						<th>Customer</th>
						<th>Shop Name</th>
						<th>Phone</th>
						<th>Address</th>
						<th>Balance</th>
						<th>Sales Agent</th>
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
                ajax: "{{ route('customer.index') }}",
                columns: [
                    { data: 'customerName', name: 'customerName' },
					{ data: 'shopName', name: 'shopName' },
                    { data: 'phone', name: 'phone' },
					{ data: 'address', name: 'address' },
					{ data: 'balance', name: 'balance' },
                    { data: 'salesAgent', name: 'salesAgent' },
                    { data: 'actions', name: 'Actions' }
                ],
                pageLength: 100,
            };

            $('.datatable-Customer').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
