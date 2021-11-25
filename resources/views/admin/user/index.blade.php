@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>Users</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user"></i> Users
            </h3>
            @can('user_create')
            <a class="btn btn-primary btn-sm float-right" href="{{ route("user.create") }}">
                <i class="fas fa-plus-circle"></i> Add User
            </a>
            @endcan
        </div>
        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-user">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Roles</th>
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
            ajax: "{{ route('user.index') }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'userType', name: 'userType' },
                { data: 'roles', name: 'roles' },
                { data: 'dateCreated', name: 'dateCreated' },
                { data: 'actions', name: 'Actions' }
            ],
            order: [[ 0, 'desc' ]],
            pageLength: 100,
        };

        $('.datatable-user').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@stop
