@extends('adminlte::page')

@section('title', 'New User')

@section('content_header')
    <h1>{{ __('adminlte::adminlte.new') }} {{ __('adminlte::adminlte.user') }}</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user"></i> {{ __('adminlte::adminlte.new') }} {{ __('adminlte::adminlte.user') }}
            </h3>
        </div>
        <div class="card-body">
			<form class="form-horizontal" action="{{ route('user.store') }}" method="POST">
				@csrf
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">{{ __('adminlte::adminlte.active') }}: *</label>
					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" name="statusID" value="1" @if(old('statusID') == null || (old('statusID') == '1')) checked @endif > {{ __('adminlte::adminlte.yes') }}
						</label>&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="statusID" value="0" @if(old('statusID') == '0') checked @endif> {{ __('adminlte::adminlte.no') }}
						</label>
					</div>
                </div>
				<div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name" class="col-sm-2 col-form-label">{{ __('adminlte::adminlte.name') }}: *</label>
					<div class="col-sm-10">
	                    <input type="text" name="name" class="form-control @if($errors->has('name')) is-invalid @endif" value="{{ old('name') }}" required>
	                    @if($errors->has('name'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('name') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email" class="col-sm-2 col-form-label">{{ __('adminlte::adminlte.email') }}: *</label>
					<div class="col-sm-10">
	                    <input type="email" name="email" class="form-control @if($errors->has('email')) is-invalid @endif" value="{{ old('email') }}" required>
	                    @if($errors->has('email'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('email') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password" class="col-sm-2 col-form-label">{{ __('adminlte::adminlte.password') }}: *</label>
					<div class="col-sm-10">
	                    <input type="password" name="password" class="form-control @if($errors->has('password') || $errors->has('confirmPassword')) is-invalid @endif" required>
	                    @if($errors->has('password'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('password') }}
	                        </em>
	                    @endif
						@if($errors->has('confirmPassword'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('confirmPassword') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">{{ __('adminlte::adminlte.confirm') }} {{ __('adminlte::adminlte.password') }}: *</label>
					<div class="col-sm-10">
                    	<input type="password" name="confirmPassword" class="form-control" required>
					</div>
                </div>
				<div class="form-group row {{ $errors->has('roleID') ? 'has-error' : '' }}">
                    <label for="name" class="col-sm-2 col-form-label">{{ __('adminlte::adminlte.roles') }}: *</label>
					<div class="col-sm-10">
						@foreach ($roles as $role)
							<div class="checkbox @if($errors->has('roleID')) is-invalid @endif">
								<label>
									<input type="checkbox" name="roleID[]" value="{{$role->roleID}}"
									{{ (is_array(old('roleID')) && in_array($role->roleID, old('roleID'))) ? ' checked' : '' }}
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
                <div class="form-group row">
					<label for="staffID" class="col-sm-2 col-form-label">Staff: </label>
					<div class="col-sm-10">
						<select name="staffID" class="form-control">
                            <option value="">Select Staff</option>
                            @foreach ($staffs as $staff)
                                <option value="{{ $staff->staffID }}">{{ $staff->staffName }} ({{ $staff->staffType->staffType }})</option>
                            @endforeach
                        </select>
					</div>
				</div>
                <div>
                    <input class="btn btn-primary" type="submit" value="{{ __('adminlte::adminlte.save') }}">
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
