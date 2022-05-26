@extends('adminlte::page')

@section('title', 'Productions')

@section('content_header')
    <h1>Productions</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Productions
			</h3>
			@can('production_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('productionBOM.create') }}">
				<i class="fas fa-plus-circle"></i> New Production
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Production">
				<thead>
					<tr>
						<th>Name</th>
						<th>Quantity</th>
						<th>Stage</th>
						<th>Date Created</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
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
                ajax: "{{ route('productionBOM.index') }}",
                columns: [
                    { data: 'productName', name: 'productName' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'productionStage', name: 'productionStage' },
                    { data: 'dateCreated', name: 'dateCreated' },
                    { data: 'actions', name: 'Actions' }
                ],
                order: [[ 1, 'desc' ]],
                pageLength: 100,
            };

            $('.datatable-Production').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
