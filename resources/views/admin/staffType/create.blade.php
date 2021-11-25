@extends('adminlte::page')

@section('title', 'New Staff Type')

@section('content_header')
    <h1>New Staff Type</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-users-cog"></i> New Staff Type
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('staffType.store') }}" method="POST">
				@csrf
				<div class="form-group row {{ $errors->has('staffType') ? 'has-error' : '' }}">
					<label for="staffType" class="col-sm-2 col-form-label">Staff Type: *</label>
					<div class="col-sm-10">
						<input type="text" name="staffType" class="form-control @if($errors->has('staffType')) is-invalid @endif" value="{{ old('staffType') }}" required>
						@if($errors->has('staffType'))
							<em class="invalid-feedback">
								{{ $errors->first('staffType') }}
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
@endsection
