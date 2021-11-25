@extends('adminlte::page')

@section('title', 'Role')

@section('content_header')
    <h1>Role</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-tag"></i> {{ $role->roleName }}
            </h3>
            @can('roles_update')
            <a class="btn btn-primary btn-sm float-right" href="{{ route("role.edit",$role->roleID) }}">
                <i class="fas fa-edit"></i> Edit Role
            </a>
            @endcan
        </div>
        <div class="card-body">
			<dt>
				<dd class="font-weight-bold">Role Name: </dd>
				<dl>{{ $role->roleName }}</dl>
				<dd class="font-weight-bold">Description: </dd>
				<dl>{{ $role->description }}</dl>
				<dd class="font-weight-bold">Role Privileges: </dd>
				<dl>
					<table class="table">
						@php
							$moduleID = 0;
						@endphp
						@foreach ($role->privileges as $rolePrivileges)
							@php
								if ($moduleID != $rolePrivileges->moduleID) {
									if ($moduleID != 0) {
										echo '</tr>';
									}
									echo '<tr><td><label>' . $rolePrivileges->module->moduleName . '</label></td>';
									$moduleID = $rolePrivileges->moduleID;
								}
							@endphp
							<td>{{ $rolePrivileges->privilegeName }}</td>
						@endforeach
					</table>
				</dl>
				<dd class="font-weight-bold">Created On: </dd>
				<dl>{{ $role->dateCreated}}</dl>
			</dt>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
