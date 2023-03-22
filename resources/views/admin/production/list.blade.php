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
			<a class="btn btn-primary btn-sm float-right" href="{{ route('production.new') }}">
				<i class="fas fa-plus-circle"></i> New Production
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Production">
				<thead>
					<tr>
						<th>Serial</th>
						<th>Product (Quantity)</th>
						<th>Production Date</th>
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
                ajax: "{{ route('production.list') }}",
                columns: [
                    { data: 'productName', name: 'productName' },
                    { data: 'serial', name: 'serial' },
                    { data: 'productionDate', name: 'productionDate' },
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
