@extends('adminlte::page')

@section('title', 'New Batch')

@section('content_header')
    <h1>New Batch</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-stopwatch"></i> New Batch
            </h3>
        </div>
        <div class="card-body">
			<form class="form-horizontal" action="{{ route('batch.store') }}" method="POST">
				@csrf
                <div class="form-group row {{ $errors->has('batchName') ? 'has-error' : '' }}">
                    <label for="batchName" class="col-sm-2 col-form-label">Batch Name: *</label>
					<div class="col-sm-10">
	                    <input type="text" name="batchName" class="form-control @if($errors->has('batchName')) is-invalid @endif" value="{{ old('batchName') }}" required>
	                    @if($errors->has('batchName'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('batchName') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row {{ $errors->has('startDate') ? 'has-error' : '' }}">
                    <label for="startDate" class="col-sm-2 col-form-label">Start Date: *</label>
					<div class="col-sm-10">
	                    <input type="date" name="startDate" class="form-control @if($errors->has('startDate')) is-invalid @endif" value="{{ old('startDate') }}" required>
	                    @if($errors->has('startDate'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('startDate') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row {{ $errors->has('endDate') ? 'has-error' : '' }}">
                    <label for="endDate" class="col-sm-2 col-form-label">End Date: *</label>
					<div class="col-sm-10">
	                    <input type="date" name="endDate" class="form-control @if($errors->has('endDate')) is-invalid @endif" value="{{ old('endDate') }}" required>
	                    @if($errors->has('endDate'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('endDate') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-10">
                    	<textarea name="description" class="form-control">{{ old('description') }}</textarea>
					</div>
                </div>
                <div>
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </form>
        </div>
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
