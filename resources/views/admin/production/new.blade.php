@extends('adminlte::page')

@section('title', 'New Production')

@section('content_header')
    <h1>New Production</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> New Production
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('production.save') }}" method="POST">
				@csrf
				<div class="form-group row {{ $errors->has('bookSerial') ? 'has-error' : '' }}">
                    <label for="bookSerial" class="col-sm-2 col-form-label">Book Serial#: *</label>
                    <div class="col-sm-10">

						<div class="input-group">
                            <input type="text" readonly name="bookSerial" id="bookSerial" class="form-control @if($errors->has('bookSerial')) is-invalid @endif" value="{{ old('bookSerial', '') }}" required>
							<div class="col-sm-2">
								@can('invoice_books_create')
								<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#exampleModal" style="color:white;" title="Void Bill">
									<i class="fas fa-times"></i>
								</button>
								@endcan
							</div>
                        </div>
                        @if($errors->has('bookSerial'))
                            <em class="invalid-feedback">
                                {{ $errors->first('bookSerial') }}
                            </em>
                        @endif
                    </div>
                </div>

				<div class="form-group row {{ $errors->has('productionDate') ? 'has-error' : '' }}">
                    <label for="productionDate" class="col-sm-2 col-form-label">Date: *</label>
					<div class="col-sm-3">
	                    <input type="date" name="productionDate" class="form-control @if($errors->has('productionDate')) is-invalid @endif" value="{{ old('productionDate',$now) }}" required>
	                    @if($errors->has('productionDate'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('productionDate') }}
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
                            <?php $colspanValue = 7; ?>
							<div class="x_content">
								<table class="table" id="main">
									<thead>
										<tr class="text-center">
											<th style="text-align:center;width:50%;">Product Name</th>
											<th style="text-align:center;width:10%;">Quantity</th>
											<th style="text-align:center;width:15%;">Sub Total</th>
											<th style="text-align:center;width:5%;">Action</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
									<tfoot>
										<tr id="actionRow">
											<td colspan="3"></td>
											<td align="right">
												<button class="btn btn-primary btn-sm pull-right " onclick="addProductRow()" type="button" title="Add New Product">
													<i class="nav-icon fas fa-fw fa-plus"></i>
												</button>
											</td>
										</tr>
										<tr>
											<td colspan="4" class="font-weight-bold text-right">Sub Total:</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<hr>
				</div>

				<div class="mt-3 offset-2">
					<input class="btn btn-primary" type="submit" value="Save">
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

    @include('admin.production.dynamicFields')
@endsection

@section('css')
    <style>
        @font-face {
            font-family: '_pdms_jauhar_regular';
            src: url('/fonts/_pdms_jauhar_regular.ttf');
            font-weight: bold;
        }

        .font-urdu {
            font-family: _pdms_jauhar_regular;
            font-size: 25px;
            line-height: 1.5;
            letter-spacing: 3px;
        }
    </style>
@stop

@section('js')
    <script src="/js/utils.js"></script>
    @include('admin.production.formJS', ['isNew' => true])
@stop
