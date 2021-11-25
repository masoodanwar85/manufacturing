@extends('adminlte::page')

@section('title', 'Edit Bank Account')

@section('content_header')
    <h1>Edit Bank Account</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-university"></i> New Bank Account
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('bankAccount.update',$bankAccount->bankAccountID) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('bankID') ? 'has-error' : '' }}">
					<label for="bankID" class="col-sm-2 col-form-label">Bank: *</label>
					<div class="col-sm-10">
						<select name="bankID" class="form-control select2 @if($errors->has('bankID')) is-invalid @endif" required>
							<option value="">Please Select Bank</option>
							@foreach($banks as $bank)
								<option value="{{ $bank->bankID }}" {{ old('bankID',$bankAccount->bankID) == $bank->bankID ? 'selected' : '' }}>{{ $bank->bankName }}</option>
							@endforeach
						</select>
						@if($errors->has('bankID'))
							<em class="invalid-feedback">
								{{ $errors->first('bankID') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('accountTitle') ? 'has-error' : '' }}">
					<label for="accountTitle" class="col-sm-2 col-form-label">Account Title: *</label>
					<div class="col-sm-10">
						<input type="text" name="accountTitle" class="form-control @if($errors->has('accountTitle')) is-invalid @endif" value="{{ old('accountTitle', $bankAccount->accountTitle) }}" required>
						@if($errors->has('accountTitle'))
							<em class="invalid-feedback">
								{{ $errors->first('accountTitle') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('accountNumber') ? 'has-error' : '' }}">
					<label for="accountNumber" class="col-sm-2 col-form-label">Account Number: *</label>
					<div class="col-sm-10">
						<input type="text" name="accountNumber" class="form-control @if($errors->has('accountNumber')) is-invalid @endif" value="{{ old('accountNumber', $bankAccount->accountNumber) }}" required>
						@if($errors->has('accountNumber'))
							<em class="invalid-feedback">
								{{ $errors->first('accountNumber') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('branchCode') ? 'has-error' : '' }}">
					<label for="branchCode" class="col-sm-2 col-form-label">Branch Code: *</label>
					<div class="col-sm-10">
						<input type="number" name="branchCode" class="form-control @if($errors->has('branchCode')) is-invalid @endif" value="{{ old('branchCode', $bankAccount->branchCode) }}" required>
						@if($errors->has('branchCode'))
							<em class="invalid-feedback">
								{{ $errors->first('branchCode') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('branchName') ? 'has-error' : '' }}">
					<label for="branchName" class="col-sm-2 col-form-label">Branch Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="branchName" class="form-control @if($errors->has('branchName')) is-invalid @endif" value="{{ old('branchName', $bankAccount->branchName) }}" required>
						@if($errors->has('branchName'))
							<em class="invalid-feedback">
								{{ $errors->first('branchName') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('branchLocation') ? 'has-error' : '' }}">
					<label for="branchLocation" class="col-sm-2 col-form-label">Branch Location: *</label>
					<div class="col-sm-10">
						<input type="text" name="branchLocation" class="form-control @if($errors->has('branchLocation')) is-invalid @endif" value="{{ old('branchLocation', $bankAccount->branchLocation) }}" required>
						@if($errors->has('branchLocation'))
							<em class="invalid-feedback">
								{{ $errors->first('branchLocation') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-10">
                    	<textarea name="description" class="form-control">{{ old('description', $bankAccount->description) }}</textarea>
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
