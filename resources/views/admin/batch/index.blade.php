@extends('adminlte::page')

@section('title', 'Batches')

@section('content_header')
    <h1>Batches</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-stopwatch"></i> Batches
			</h3>
			@can('batch_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('batch.create') }}">
				<i class="fas fa-plus-circle"></i> Add Batch
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-batch">
				<thead>
					<tr>
						<th>Batch Name</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Date Created</th>
						<th>Actions</th>
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
            ajax: "{{ route('batch.index') }}",
            columns: [
                { data: 'batchName', name: 'batchName' },
                { data: 'startDate', name: 'startDate' },
				{ data: 'endDate', name: 'endDate' },
                { data: 'dateCreated', name: 'dateCreated' },
                { data: 'actions', name: 'Actions' }
            ],
            order: [[ 0, 'desc' ]],
            pageLength: 100,
        };

        $('.datatable-batch').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@stop
