@extends('adminlte::page')

@section('title', 'Edit Customer')

@section('content_header')
    <h1>Edit Customer</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-male"></i> Edit Customer
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('customer.update', $customer->customerID) }}" method="POST">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('customerName') ? 'has-error' : '' }}">
					<label for="customerName" class="col-sm-2 col-form-label">Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="customerName" class="form-control @if($errors->has('customerName')) is-invalid @endif" value="{{ old('customerName', $customer->customerName) }}" required>
						@if($errors->has('customerName'))
							<em class="invalid-feedback">
								{{ $errors->first('customerName') }}
							</em>
						@endif
					</div>
				</div>
				<div class="form-group row {{ $errors->has('shopName') ? 'has-error' : '' }}">
					<label for="shopName" class="col-sm-2 col-form-label">Shop Name: *</label>
					<div class="col-sm-10">
						<input type="text" name="shopName" class="form-control @if($errors->has('shopName')) is-invalid @endif" value="{{ old('shopName', $customer->shopName) }}" required>
						@if($errors->has('shopName'))
							<em class="invalid-feedback">
								{{ $errors->first('shopName') }}
							</em>
						@endif
					</div>
				</div>

                <div class="form-group row">
					<label for="saleAgent" class="col-sm-2 col-form-label">Sales Agent: </label>
					<div class="col-sm-10">
						<select name="salesAgentID" class="form-control">
                            <option value="">Select Sales Agent</option>
                            @foreach ($saleAgents as $saleAgent)
                                <option value="{{ $saleAgent->staffID }}" {!! old('salesAgentID', $customer->salesAgentID) == $saleAgent->staffID ? 'selected' : '' !!}>{{ $saleAgent->staffName }}</option>
                            @endforeach
                        </select>
					</div>
				</div>

				<div class="form-group row">
					<label for="phone" class="col-sm-2 col-form-label">Phone: </label>
					<div class="col-sm-10">
						<input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
					</div>
				</div>

				<div class="form-group row">
					<label for="address" class="col-sm-2 col-form-label">Address: </label>
					<div class="col-sm-10">
						<input type="text" name="address" class="form-control" value="{{ old('address', $customer->address) }}">
					</div>
				</div>

				<div class="form-group row">
					<label for="description" class="col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-10">
						<textarea name="description" class="form-control">{{ old('description', $customer->description) }}</textarea>
					</div>
				</div>

				<hr />

				<div class="form-group row">
					<label for="equipments" class="col-sm-2 col-form-label"></label>
					<div class="col-sm-10">

						<table class="table">
							<tr>
								<th>#</th>
								<th>Type</th>
								<th>Serial</th>
							</tr>
							<?php
								$records_already_exists = $customerEquipments->count();
							?>
							@foreach($customerEquipments as $customerEquipment)
							<tr>
								<td>{{ $loop->iteration }}.</td>
								<td>
									<select name="equipmentType[]" class="form-control">
										<option value=""></option>
										<option value="VC Cooler" {!! ($customerEquipment->equipmentType == 'VC Cooler') ? 'selected' : '' !!}>VC Cooler</option>
										<option value="Deep Freezer" {!! ($customerEquipment->equipmentType == 'Deep Freezer') ? 'selected' : '' !!}>Deep Freezer</option>
									</select>
								</td>
								<td>
									<input type="text" name="equipmentSerial[]" class="form-control" value="{{ $customerEquipment->equipmentSerial }}" />
								</td>
							</tr>
							@endforeach
							@for($ctr = $records_already_exists; $ctr < 5; $ctr++)
							<tr>
								<td>{{ $ctr + 1 }}.</td>
								<td>
									<select name="equipmentType[]" class="form-control">
										<option value=""></option>
										<option value="VC Cooler">VC Cooler</option>
										<option value="Deep Freezer">Deep Freezer</option>
									</select>
								</td>
								<td>
									<input type="text" name="equipmentSerial[]" class="form-control" />
								</td>
							</tr>
							@endfor
							
						</table>
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
