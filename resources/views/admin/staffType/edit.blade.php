@extends('adminlte::page')

@section('title', 'Edit Staff Type')

@section('content_header')
    <h1>Edit Staff Type</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-users-cog"></i> Edit Staff Type
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('staffType.update',$staffType->staffTypeID) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('staffType') ? 'has-error' : '' }}">
					<label for="staffType" class="col-sm-2 col-form-label">Staff Type: *</label>
					<div class="col-sm-10">
						<input type="text" name="staffType" class="form-control @if($errors->has('staffType')) is-invalid @endif" value="{{ old('staffType',$staffType->staffType) }}" required>
						@if($errors->has('staffType'))
							<em class="invalid-feedback">
								{{ $errors->first('staffType') }}
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
@endsection
