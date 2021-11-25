@extends('adminlte::page')

@section('title', 'Edit Godown')

@section('content_header')
    <h1>Edit Godown</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-warehouse"></i> New Godown
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('godown.update',$godown->godownID) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
					<label for="name" class="col-sm-2 col-form-label">Godown: *</label>
					<div class="col-sm-10">
						<input type="text" name="name" class="form-control @if($errors->has('name')) is-invalid @endif" value="{{ old('name', $godown->name) }}" required>
						@if($errors->has('name'))
							<em class="invalid-feedback">
								{{ $errors->first('name') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('address') ? 'has-error' : '' }}">
					<label for="address" class="col-sm-2 col-form-label">Address: *</label>
					<div class="col-sm-10">
						<input type="text" name="address" class="form-control @if($errors->has('address')) is-invalid @endif" value="{{ old('address', $godown->address) }}" required>
						@if($errors->has('address'))
							<em class="invalid-feedback">
								{{ $errors->first('address') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-10">
                    	<textarea name="description" class="form-control">{{ old('description', $godown->description) }}</textarea>
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
