@extends('adminlte::page')

@section('title', 'Suppliers')

@section('content_header')
    <h1>Suppliers</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-people-arrows"></i> Suppliers
			</h3>
			@can('supplier_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('supplier.create') }}">
				<i class="fas fa-plus-circle"></i> Add Supplier
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Supplier">
				<thead>
					<tr>
						<th>Name</th>
						<th>Phone #</th>
						<th>Address</th>
						<th>Balance</th>
						<th>Date Created</th>
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
                ajax: "{{ route('supplier.index') }}",
                columns: [
                    { data: 'supplierName', name: 'supplierName' },
					{ data: 'phone', name: 'phone' },
					{ data: 'address', name: 'address' },
					{ data: 'balance', name: 'balance' },
                    { data: 'dateCreated', name: 'dateCreated' },
                    { data: 'actions', name: 'Actions' }
                ],
                order: [[ 0, 'asc' ]],
                pageLength: 100,
            };

            $('.datatable-Supplier').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
