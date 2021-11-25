@extends('adminlte::page')

@section('title','Godowns')

@section('content_header')
    <h1>Godowns</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-warehouse"></i> Godowns
			</h3>
			@can('godown_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('godown.create') }}">
				<i class="fas fa-plus-circle"></i> Add Godown
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-godown">
				<thead>
					<tr>
						<th>Godown</th>
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
                ajax: "{{ route('godown.index') }}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'address', name: 'address' },
					{ data: 'balance', name: 'balance' },
                    { data: 'dateCreated', name: 'dateCreated' },
                    { data: 'actions', name: 'Actions' }
                ],
                order: [[ 0, 'asc' ]],
                pageLength: 100,
            };

            $('.datatable-godown').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
