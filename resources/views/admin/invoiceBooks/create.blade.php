@extends('adminlte::page')

@section('title', 'New Invoice Book')

@section('content_header')
    <h1>New Invoice Book</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-male"></i> New Invoice Book
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('invoiceBooks.store') }}" method="POST">
				@csrf
				<div class="form-group row {{ $errors->has('bookType') ? 'has-error' : '' }}">
					<label for="bookType" class="col-sm-2 col-form-label">Book Type: *</label>
					<div class="col-sm-10">
						<select name="bookType" class="form-control @if($errors->has('bookType')) is-invalid @endif" required>
                            <option value="BB">Bill Book</option>
                            <option value="CB">Cash Book</option>
                            <option value="RB">Receipt Book</option>
							<option value="SR">Stock Receiving</option>
							<option value="TB">Transfer Book</option>
							<option value="MB">Manufacturing Book</option>
                        </select>
						@if($errors->has('bookType'))
							<em class="invalid-feedback">
								{{ $errors->first('bookType') }}
							</em>
						@endif
					</div>
				</div>
                <div class="form-group row {{ $errors->has('bookNumber') ? 'has-error' : '' }}">
					<label for="bookNumber" class="col-sm-2 col-form-label">Book Number: *</label>
					<div class="col-sm-10">
						<input type="number" name="bookNumber" class="form-control @if($errors->has('bookNumber')) is-invalid @endif" value="{{ old('bookNumber') }}" required>
						@if($errors->has('bookNumber'))
							<em class="invalid-feedback">
								{{ $errors->first('bookNumber') }}
							</em>
						@endif
					</div>
				</div>

                <div class="form-group row {{ $errors->has('startPage') ? 'has-error' : '' }}">
					<label for="startPage" class="col-sm-2 col-form-label">Start Page: *</label>
					<div class="col-sm-10">
						<input type="number" name="startPage" class="form-control @if($errors->has('startPage')) is-invalid @endif" value="{{ old('startPage') }}" required>
						@if($errors->has('startPage'))
							<em class="invalid-feedback">
								{{ $errors->first('startPage') }}
							</em>
						@endif
					</div>
				</div>

                <div class="form-group row {{ $errors->has('endPage') ? 'has-error' : '' }}">
					<label for="endPage" class="col-sm-2 col-form-label">End Page: *</label>
					<div class="col-sm-10">
						<input type="number" name="endPage" class="form-control @if($errors->has('endPage')) is-invalid @endif" value="{{ old('endPage') }}" required>
						@if($errors->has('endPage'))
							<em class="invalid-feedback">
								{{ $errors->first('endPage') }}
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

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
