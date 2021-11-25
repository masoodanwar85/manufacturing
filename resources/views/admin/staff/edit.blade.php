@extends('adminlte::page')

@section('title', 'Edit Staff')

@section('content_header')
    <h1>Edit Staff</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-users"></i> Edit Staff
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('staff.update',$staff->staffID) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('staffName') ? 'has-error' : '' }}">
					<label for="staffName" class="col-sm-2 col-form-label">Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="staffName" class="form-control @if($errors->has('staffName')) is-invalid @endif" value="{{ old('staffName',$staff->staffName) }}" required>
						@if($errors->has('staffName'))
							<em class="invalid-feedback">
								{{ $errors->first('staffName') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('staffTypeID') ? 'has-error' : '' }}">
					<label for="categoryID" class="col-sm-2 col-form-label">Staff Type: *</label>
					<div class="col-sm-10">
						<select name="staffTypeID" class="form-control select2 @if($errors->has('staffTypeID')) is-invalid @endif" required>
							<option value="">Please Select Staff Type</option>
							@foreach($staffTypes as $staffType)
								<option value="{{ $staffType->staffTypeID }}" {{ old('staffTypeID',$staff->staffTypeID) == $staffType->staffTypeID ? 'selected' : '' }}>{{ $staffType->staffType }}</option>
							@endforeach
						</select>
						@if($errors->has('staffTypeID'))
							<em class="invalid-feedback">
								{{ $errors->first('staffTypeID') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('dateJoined') ? 'has-error' : '' }}">
                    <label for="dateJoined" class="col-sm-2 col-form-label">Date Joined: *</label>
					<div class="col-sm-10">
	                    <input type="date" name="dateJoined" class="form-control @if($errors->has('dateJoined')) is-invalid @endif" value="{{ old('dateJoined', $staff->dateJoined) }}" required>
	                    @if($errors->has('dateJoined'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('dateJoined') }}
	                        </em>
	                    @endif
					</div>
                </div>
                <div class="form-group row {{ $errors->has('paymentFrequencyID') ? 'has-error' : '' }}">
					<label for="paymentFrequencyID" class="col-sm-2 col-form-label">Payment Frequency: *</label>
					<div class="col-sm-4">
						<select name="paymentFrequencyID" class="form-control select2 @if($errors->has('paymentFrequencyID')) is-invalid @endif" required>
							<option value="">Please Select Payment Frequency</option>
                            <option value="2" {{ old('paymentFrequencyID',$staff->paymentFrequencyID) == 2 ? 'selected' : '' }}>Daily</option>
                            <option value="1" {{ old('paymentFrequencyID',$staff->paymentFrequencyID) == 1 ? 'selected' : '' }}>Monthly</option>
						</select>
						@if($errors->has('paymentFrequencyID'))
							<em class="invalid-feedback">
								{{ $errors->first('paymentFrequencyID') }}
							</em>
						@endif
					</div>
					<label for="paymentAmount" class="col-sm-2 col-form-label">Payment Amount: *</label>
					<div class="col-sm-4">
						<input type="number" name="paymentAmount" class="form-control" value="{{ old('paymentAmount',$staff->paymentAmount) }}" required>
					</div>
				</div>
				<div>
					<input class="btn btn-primary" type="submit" value="Update">
				</div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
