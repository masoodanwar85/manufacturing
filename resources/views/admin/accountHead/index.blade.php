@extends('adminlte::page')
@section('title', 'Account Heads')

@section('content_header')
    <h1>Account Heads</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-hand-holding-usd"></i> Account Heads
			</h3>
			@can('account_head_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('accountHead.create') }}">
				<i class="fas fa-plus-circle"></i> Add Account Head
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-accountHead">
				<thead>
					<tr>
						<th>Head</th>
						<th>Parent Head</th>
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
			@include('partials.datatableButtons')
            let dtOverrideGlobals = {
				buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
				ajax: "{{ route('accountHead.index') }}",
                columns: [
                    { data: 'head', name: 'head' },
                    { data: 'parentHeadName', name: 'parentHeadName' },
                    { data: 'dateCreated', name: 'dateCreated' },
                    { data: 'actions', name: 'Actions' }
                ],
                pageLength: {{ $globalSettings['user_settings.records_per_page'] }},
            };

            var table = $('.datatable-accountHead').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
