@extends('adminlte::page')

@section('title', 'Deliveries')

@section('content_header')
    <h1>Deliveries</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-shipping-fast"></i> Deliveries
			</h3>
			@can('delivery_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('delivery.create') }}">
				<i class="fas fa-plus"></i> Add Delivery
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-delivery">
				<thead>
					<tr>
						<th>Delivery Date</th>
						<th>Route</th>
						<th>Godown</th>
						<th>Transport</th>
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
            ajax: "{{ route('delivery.index') }}",
            columns: [
                { data: 'deliveryDate', name: 'deliveryDate' },
                { data: 'route', name: 'route' },
				{ data: 'godown', name: 'godown' },
                { data: 'transport', name: 'transport' },
                { data: 'dateCreated', name: 'dateCreated' },
                { data: 'actions', name: 'Actions' }
            ],
            order: [[ 0, 'desc' ]],
            pageLength: 100,
        };

        $('.datatable-delivery').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@stop
