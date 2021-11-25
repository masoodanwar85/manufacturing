@extends('adminlte::page')

@section('title', 'Edit Transport')

@section('content_header')
    <h1>Edit Transport</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-truck"></i> New Transport
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('transport.update',$transport->transportID) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
					<label for="name" class="col-sm-2 col-form-label">Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="name" class="form-control @if($errors->has('name')) is-invalid @endif" value="{{ old('name', $transport->name) }}" required>
						@if($errors->has('name'))
							<em class="invalid-feedback">
								{{ $errors->first('name') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('owner') ? 'has-error' : '' }}">
					<label for="owner" class="col-sm-2 col-form-label">Owner: *</label>
					<div class="col-sm-10">
						<input type="text" name="owner" class="form-control @if($errors->has('owner')) is-invalid @endif" value="{{ old('owner', $transport->owner) }}" required>
						@if($errors->has('owner'))
							<em class="invalid-feedback">
								{{ $errors->first('owner') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('vehicleNumber') ? 'has-error' : '' }}">
					<label for="vehicleNumber" class="col-sm-2 col-form-label">Vehicle Number: *</label>
					<div class="col-sm-10">
						<input type="text" name="vehicleNumber" class="form-control @if($errors->has('vehicleNumber')) is-invalid @endif" value="{{ old('vehicleNumber', $transport->vehicleNumber) }}" required>
						@if($errors->has('vehicleNumber'))
							<em class="invalid-feedback">
								{{ $errors->first('vehicleNumber') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-10">
                    	<textarea name="description" class="form-control">{{ old('description', $transport->description) }}</textarea>
					</div>
                </div>
				<div>
					<input class="btn btn-primary" type="submit" value="Update">
				</div>
            </form>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
