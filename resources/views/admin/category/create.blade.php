@extends('adminlte::page')

@section('title', 'New Category')

@section('content_header')
    <h1>New Category</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-th-list"></i> New Category
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('category.store') }}" method="POST">
				@csrf
				<div class="form-group row {{ $errors->has('categoryName') ? 'has-error' : '' }}">
					<label for="categoryName" class="col-sm-2 col-form-label">Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="categoryName" class="form-control @if($errors->has('categoryName')) is-invalid @endif" value="{{ old('categoryName') }}" required>
						@if($errors->has('categoryName'))
							<em class="invalid-feedback">
								{{ $errors->first('categoryName') }}
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
