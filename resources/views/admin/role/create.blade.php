@extends('adminlte::page')

@section('title', 'New Role')

@section('content_header')
    <h1>New Role</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-tag"></i> New Role
            </h3>
        </div>
        <div class="card-body">
			<form class="form-horizontal" action="{{ route('role.store') }}" method="POST">
				@csrf
                <div class="form-group row {{ $errors->has('roleName') ? 'has-error' : '' }}">
                    <label for="roleName" class="col-sm-2 col-form-label">Role Name: *</label>
					<div class="col-sm-10">
	                    <input type="text" name="roleName" class="form-control @if($errors->has('roleName')) is-invalid @endif" value="{{ old('roleName') }}" required>
	                    @if($errors->has('roleName'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('roleName') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-10">
                    	<textarea name="description" class="form-control">{{ old('description') }}</textarea>
					</div>
                </div>
				<div class="form-group row">
					<div class="col-sm-12">
						<table class="table" @if($errors->has('privilegeID')) style="border:1px solid red;" @endif>
							<thead>
								<tr>
									<th>Modules</th>
									<th colspan="4">Privileges</th>
								</tr>
							</thead>
							<tbody>
								@php
									$moduleID = 0;
								@endphp
								@foreach($privileges as $privilege)
									@php
										if ($moduleID != $privilege->moduleID) {
											if ($moduleID != 0) {
												echo '</tr>';
											}
											echo '<tr><td><label>' . $privilege->module->moduleName . '</label></td>';
											$moduleID = $privilege->moduleID;
										}
									@endphp
									<td>
										<label class="form-check-label">
											<input type="checkbox" name="privilegeID[]" value="{{ $privilege->privilegeID }}" @if($errors->has('privilegeID')) is-invalid @endif
												{{ (is_array(old('privilegeID')) && in_array($privilege->privilegeID, old('privilegeID'))) ? ' checked' : '' }}
											/>
											&nbsp;&nbsp;{{ $privilege->privilegeName }}
										</label>
									</td>
								@endforeach
								</tr>
							</tbody>
						</table>
						@if($errors->has('privilegeID'))
							<em class="text-danger">
								{{ $errors->first('privilegeID') }}
							</em>
						@endif
					</div>
				</div>
                <div>
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </form>
        </div>
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
