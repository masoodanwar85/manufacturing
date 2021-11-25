@extends('adminlte::page')

@section('title','Transport')

@section('content_header')
    <h1>Transport</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-truck"></i> Transport
			</h3>
			@can('transport_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('transport.create') }}">
				<i class="fas fa-plus-circle"></i> Add Transport
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-transport">
				<thead>
					<tr>
						<th>Name</th>
						<th>Owner</th>
						<th>Vehicle Number</th>
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
                ajax: "{{ route('transport.index') }}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'owner', name: 'owner' },
					{ data: 'vehicleNumber', name: 'vehicleNumber' },
					{ data: 'balance', name: 'balance' },
                    { data: 'dateCreated', name: 'dateCreated' },
                    { data: 'actions', name: 'Actions' }
                ],
                order: [[ 0, 'asc' ]],
                pageLength: 100,
            };

            $('.datatable-transport').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
