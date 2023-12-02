@extends('adminlte::page')

@section('title', 'Multi Stock Transfer')

@section('content_header')
<h1>Multi Stock Transfer</h1>
@stop

@section('content')
<div class="card card-default color-palette-box">
	<div class="card-header">
		<h3 class="card-title">
			<i class="fas fa-share"></i> Multi Stock Transfer
		</h3>
	</div>
	<div class="card-body">
		<form class="form-horizontal" id="frmSales" action="{{ route('stock.multiTransfer') }}" method="POST">
			@csrf
			<div class="form-group row">
				<label for="transferFrom" class="col-sm-2 col-form-label">Transfer From: *</label>
				<div class="col-sm-3">
					<select name="transferFrom" id="transferFrom" class="form-control" onchange="filterProducts();"
						required>
						<option value="">Transfer From</option>
						@foreach ($godowns as $godown)
						<option value="{{ $godown->godownID }}">{{ $godown->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="transferTo" class="col-sm-2 col-form-label">Transfer To: *</label>
				<div class="col-sm-3">
					<select name="transferTo" id="transferTo" class="form-control" required>
						<option value="">Transfer To</option>
						@foreach ($godowns as $godown)
						<option value="{{ $godown->godownID }}">{{ $godown->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="bookSerial" class="col-sm-2 col-form-label">Book Serial: *</label>
				<div class="col-sm-3">
					<div class="row">
						<div class="col-sm-4">
							<select name="bookType" id="bookType" class="form-control" required>
								<option value=""></option>
								<option value="TB">TB</option>
								<option value="RB">RB</option>
							</select>
						</div>
						<div class="col-sm-6">
							<input type="text" readonly placeholder="Book Serial #" id="bookSerial" name="bookSerial" class="form-control" value="{{ old('bookSerial')}}" required />
						</div>
						<div class="col-sm-2">
							@can('invoice_books_create')
							<button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
								data-target="#exampleModal" style="color:white;" title="Void Bill">
								<i class="fas fa-times"></i>
							</button>
							@endcan
						</div>
					</div>
				</div>
			</div>


			<div class="form-group row {{ $errors->has('transferDate') ? 'has-error' : '' }}">
				<label for="transferDate" class="col-sm-2 col-form-label">Date: *</label>
				<div class="col-sm-3">
					<input type="date" name="transferDate"
						class="form-control @if($errors->has('transferDate')) is-invalid @endif"
						value="{{ old('transferDate',$now) }}" required>
					@if($errors->has('transferDate'))
					<em class="invalid-feedback">
						{{ $errors->first('transferDate') }}
					</em>
					@endif
				</div>
			</div>

			<div class="row">
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h3>Products</h3>
							<div class="clearfix"></div>
						</div>
						<?php $colspanValue = 3; ?>
						<div class="x_content">
							<table class="table" id="myTable">
								<thead>
									<tr class="text-center">
										<th style="text-align:center;width:25%;">Product Name</th>
										<th style="text-align:center;width:10%;">Quantity Available</th>
										<th style="text-align:center;width:8%;">Quantity To Move</th>
										<th style="text-align:center;width:5%;">Action</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
								<tfoot>
									<tr id="actionRow">
										<td colspan="{{ $colspanValue }}"></td>
										<td>
											<button class="btn btn-primary btn-sm pull-right " onclick="addSORow()"
												type="button" title="Add New Item">
												<i class="nav-icon fas fa-fw fa-plus"></i>
											</button>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<hr>
			</div>
			<div>
				<input class="btn btn-primary" type="submit" value="Transfer">
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form name="frm" id="frmVoidSerial" action="" method="post">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Void Serial</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label for="prevGodown" class="col-sm-4 col-form-label">Invoice Book #:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="invoiceBookNum" readonly disabled value="" />
							<input type="hidden" id="invoiceBookNumber" name="invoiceBookNumber" value="" />
						</div>
					</div>
					<div class="form-group row">
						<label for="reason" class="col-sm-4 col-form-label">Reason:</label>
						<div class="col-sm-8">
							<textarea class="form-control" id="reason" name="reason" required></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" onclick="voidThisSerial();" data-dismiss="modal"
						class="btn btn-primary">Void</button>
				</div>
			</form>
		</div>
	</div>
</div>

@include('admin.stock.dynamicFields')
@endsection

@section('js')
@include('admin.stock.formJS', ['isNew' => true])
@stop