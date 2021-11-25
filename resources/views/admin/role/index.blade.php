@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1>Roles</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-user-tag"></i> Roles
			</h3>
			@can('roles_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route("role.create") }}">
				<i class="fas fa-plus-circle"></i> Add Role
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-role">
				<thead>
					<tr>
						<th>Role</th>
						<!-- <th>Description</th> -->
                        <th>Privileges</th>
						<!-- <th>Date Created</th> -->
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
            ajax: "{{ route('role.index') }}",
            columns: [
                { data: 'roleName', name: 'roleName' },
                // { data: 'description', name: 'description' },
                { data: 'privileges', name: 'privileges' },
                // { data: 'dateCreated', name: 'dateCreated' },
                { data: 'actions', name: 'Actions' }
            ],
            order: [[ 0, 'desc' ]],
            pageLength: 100,
        };

        $('.datatable-role').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@stop
