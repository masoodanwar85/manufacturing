@extends('adminlte::page')

@section('title','Staff')

@section('content_header')
    <h1>Staff</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-users"></i> Staff
			</h3>
			@can('staff_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('staff.create') }}">
				<i class="fas fa-plus-circle"></i> Add Staff
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-staff">
				<thead>
					<tr>
						<th>Name</th>
						<th>Staff Type</th>
						<th>Date Joined</th>
						<th>Salary</th>
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
                ajax: "{{ route('staff.index') }}",
                columns: [
                    { data: 'staffName', name: 'staffName' },
                    { data: 'staffType', name: 'staffType' },
                    { data: 'dateJoined', name: 'dateJoined' },
					{ data: 'salary', name: 'salary' },
                    { data: 'balance', name: 'balance' },
                    { data: 'dateCreated', name: 'dateCreated' },
                    { data: 'actions', name: 'Actions' }
                ],
                order: [[ 1, 'desc' ]],
                pageLength: 100,
            };

            $('.datatable-staff').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
