@extends('adminlte::page')

@section('title', 'Edit Account Head')

@section('content_header')
    <h1>Edit Account Head</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-hand-holding-usd"></i> Edit Account Head
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('accountHead.update', $accountHead->headID) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('headName') ? 'has-error' : '' }}">
					<label for="headName" class="col-sm-2 col-form-label">Head Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="headName" class="form-control @if($errors->has('headName')) is-invalid @endif" value="{{ old('headName', $accountHead->headName) }}" required>
						@if($errors->has('headName'))
							<em class="invalid-feedback">
								{{ $errors->first('headName') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row">
					<label for="parentHeadID" class="col-sm-2 col-form-label">Parent Head: </label>
					<div class="col-sm-10">
						<select name="parentHeadID" class="form-control select2" required>
							<option value="-1"></option>
							@foreach($accountHeads as $head)
								<option value="{{ $head->headID }}" {{ old('parentHeadID', $accountHead->parentHeadID) == $head->headID ? 'selected' : '' }}>{{ $head->headName }}</option>
							@endforeach
						</select>
					</div>
				</div>
                <div class="form-group row">
                    <label fro="isShowForPurchaseOrderExpense" class="col-sm-2 col-form-label">Show on Purchase Order Expense?:</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
							<input type="radio" name="isShowForPurchaseOrderExpense" value="1" @if(old('isShowForPurchaseOrderExpense',$accountHead->isShowForPurchaseOrderExpense) == '1') checked @endif > {{ __('adminlte::adminlte.yes') }}
						</label>&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="isShowForPurchaseOrderExpense" value="0" @if(old('isShowForPurchaseOrderExpense',$accountHead->isShowForPurchaseOrderExpense) != '1') checked @endif> {{ __('adminlte::adminlte.no') }}
						</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label fro="isShowForPayment" class="col-sm-2 col-form-label">Show on Payment?:</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
							<input type="radio" name="isShowForPayment" value="1" @if(old('isShowForPayment',$accountHead->isShowForPayment) == '1') checked @endif > {{ __('adminlte::adminlte.yes') }}
						</label>&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="isShowForPayment" value="0" @if(old('isShowForPayment',$accountHead->isShowForPayment) != '1') checked @endif> {{ __('adminlte::adminlte.no') }}
						</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label fro="isShowForReceipt" class="col-sm-2 col-form-label">Show on Receipt?:</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
							<input type="radio" name="isShowForReceipt" value="1" @if(old('isShowForReceipt',$accountHead->isShowForReceipt) == '1') checked @endif > {{ __('adminlte::adminlte.yes') }}
						</label>&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="isShowForReceipt" value="0" @if(old('isShowForReceipt',$accountHead->isShowForReceipt) != '1') checked @endif> {{ __('adminlte::adminlte.no') }}
						</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label fro="isShowForOpeningBalance" class="col-sm-2 col-form-label">Show on Opening Balance?:</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
							<input type="radio" name="isShowForOpeningBalance" value="1" @if(old('isShowForOpeningBalance',$accountHead->isShowForOpeningBalance) == '1') checked @endif > {{ __('adminlte::adminlte.yes') }}
						</label>&nbsp;&nbsp;
						<label class="radio-inline">
							<input type="radio" name="isShowForOpeningBalance" value="0" @if(old('isShowForOpeningBalance',$accountHead->isShowForOpeningBalance) != '1') checked @endif> {{ __('adminlte::adminlte.no') }}
						</label>
                    </div>
                </div>
				<div>
                    <input class="btn btn-primary" type="submit" value="Update">
                </div>
            </form>
        </div>
    </div>
@endsection
