@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <h1>Edit User</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user"></i> Edit User
            </h3>
        </div>
        <div class="card-body">
			<form class="form-horizontal" action="{{ route('user.update',$user->userID) }}" method="POST">
				@csrf
				@method('PUT')
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Active: *</label>
					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" name="statusID" id="yes" value="1" @if(old('statusID',$user->statusID) == '1') checked @endif > Yes
						</label>&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="statusID" id="no" value="0" @if(old('statusID',$user->statusID) == '0') checked @endif > No
						</label>
					</div>
                </div>
				<div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name" class="col-sm-2 col-form-label">Name: *</label>
					<div class="col-sm-10">
	                    <input type="text" name="name" class="form-control @if($errors->has('name')) is-invalid @endif" value="{{ old('name',$user->name) }}" required>
	                    @if($errors->has('name'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('name') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email" class="col-sm-2 col-form-label">Email: *</label>
					<div class="col-sm-10">
	                    <input type="email" name="email" class="form-control @if($errors->has('email')) is-invalid @endif" value="{{ old('email',$user->email) }}" required>
	                    @if($errors->has('email'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('email') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password:</label>
					<div class="col-sm-10">
                    	<input type="password" name="password" class="form-control @if($errors->has('password')) is-invalid @endif">
					</div>
                </div>
				<div class="form-group row {{ $errors->has('roleID') ? 'has-error' : '' }}">
                    <label for="name" class="col-sm-2 col-form-label">Roles: *</label>
					<div class="col-sm-10">
						@foreach ($roles as $role)
							<div class="checkbox @if($errors->has('roleID')) is-invalid @endif">
								<label>
									<input type="checkbox" name="roleID[]" value="{{$role->roleID}}"
									{{ (is_array(old('roleID',$userRoles)) && in_array($role->roleID, old('roleID',$userRoles))) ? ' checked' : '' }}
									> {{$role->roleName}}
								</label>
							</div>
						@endforeach
						@if($errors->has('roleID'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('roleID') }}
	                        </em>
	                    @endif
					</div>
                </div>
                <div>
                    <input class="btn btn-primary" type="submit" value="Update">
                </div>
            </form>
        </div>
    </div>
@stop
@section('plugins.bootstrapSwitch', true)
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('js')
<script>
    $(function () {
		// $("[name='statusID']").bootstrapSwitch();
		// $("[name='roleID']").bootstrapSwitch();
	});
</script>
@stop
