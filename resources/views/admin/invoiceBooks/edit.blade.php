@extends('adminlte::page')

@section('title', 'Edit Invoice Book')

@section('content_header')
    <h1>Edit Invoice Book</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-male"></i> Edit Invoice Book
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('invoiceBooks.update',$invoiceBook->invoiceBookID) }}" method="POST">
				@csrf
                @method('PUT')
				<div class="form-group row {{ $errors->has('bookType') ? 'has-error' : '' }}">
					<label for="bookType" class="col-sm-2 col-form-label">Book Type: *</label>
					<div class="col-sm-10">
						<select name="bookType" class="form-control @if($errors->has('bookType')) is-invalid @endif" required>
                            <option value="BB" {{ old('bookType',$invoiceBook->bookType) == 'BB' ? 'selected' : '' }}>Bill Book</option>
                            <option value="CB" {{ old('bookType',$invoiceBook->bookType) == 'CB' ? 'selected' : '' }}>Cash Book</option>
                            <option value="RB" {{ old('bookType',$invoiceBook->bookType) == 'RB' ? 'selected' : '' }}>Receipt Book</option>
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
						<input type="number" name="bookNumber" class="form-control @if($errors->has('bookNumber')) is-invalid @endif" value="{{ old('bookNumber', $invoiceBook->bookNumber) }}" required>
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
						<input type="number" name="startPage" class="form-control @if($errors->has('startPage')) is-invalid @endif" value="{{ old('startPage',$invoiceBook->startPage) }}" required>
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
						<input type="number" name="endPage" class="form-control @if($errors->has('endPage')) is-invalid @endif" value="{{ old('endPage', $invoiceBook->endPage) }}" required>
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
