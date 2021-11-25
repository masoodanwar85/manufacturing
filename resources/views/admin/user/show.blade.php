@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
    <h1>User</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user"></i> {{ $user->name }}
            </h3>
            @can('user_update')
            <a class="btn btn-primary btn-sm float-right" href="{{ route("user.edit",$user->userID) }}">
                <i class="fas fa-edit"></i> Edit User
            </a>
            @endcan
        </div>
        <div class="card-body">
			<dt>
				<dd class="font-weight-bold">Name: </dd>
				<dl>{{ $user->name}}</dl>
				<dd class="font-weight-bold">Email: </dd>
				<dl><a href="mailto:{{ $user->email}}">{{ $user->email}}</a></dl>
				<dd class="font-weight-bold">User Type: </dd>
				<dl>{{ $user->userType->userType}}</dl>
				<dd class="font-weight-bold">User Roles: </dd>
				<dl>
					@foreach ($user->roles as $userRole)
						<span class="badge badge-info">{{$userRole->roleName}}</span>
					@endforeach
				</dl>
				<dd class="font-weight-bold">Created On: </dd>
				<dl>{{ $user->dateCreated}}</dl>
			</dt>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
