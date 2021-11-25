@extends('adminlte::page')

@section('title', 'Edit Category')

@section('content_header')
    <h1>Edit Category</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-th-list"></i> Edit Category
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('category.update',$category->categoryID) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('categoryName') ? 'has-error' : '' }}">
					<label for="categoryName" class="col-sm-2 col-form-label">Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="categoryName" class="form-control @if($errors->has('categoryName')) is-invalid @endif" value="{{ old('categoryName',$category->categoryName) }}" required>
						@if($errors->has('categoryName'))
							<em class="invalid-feedback">
								{{ $errors->first('categoryName') }}
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
