@extends('adminlte::page')

@section('title', 'Edit Production')

@section('content_header')
    <h1>Edit Production</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Edit Production
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('production.changeUpdate', $production->productionID) }}" method="POST">
				@csrf
				<div class="form-group row {{ $errors->has('bookSerial') ? 'has-error' : '' }}">
                    <label for="bookSerial" class="col-sm-2 col-form-label">Book Serial#: *</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="hidden" name="mb" value="MB">MB -
                                </div>
                            </div>
                            <input type="text" name="bookSerial" class="form-control @if($errors->has('bookSerial')) is-invalid @endif" value="{{ old('bookSerial', Str::replace('MB-', '', $production->serial)) }}" required>
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
	                    <input type="date" name="productionDate" class="form-control @if($errors->has('productionDate')) is-invalid @endif" value="{{ old('productionDate',$production->productionDate) }}" required>
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
										@foreach ($production->boms as $BOM)
											<?php 
												$this_uuid = Str::uuid()->toString();
											?>
											<tr>
												<td>
													<div class="form-group">
														<div class="col-sm-12">
															<select name="productIDs[]" class="form-control" onchange="BOMProductChanged(this);" required>
																<option value=""></option>
																@foreach($BOMProducts as $idx => $product)
																	<option value="{{ $product->productID }}_{{ $this_uuid }}" {!! $product->productID == $BOM->productID ? ' selected' : '' !!}>{{ $product->productName }}</option>
																@endforeach
															</select>
															<a href="javascript:void(0);" style="display:block;text-align:center;" onclick="$('#sub-row_{{ $this_uuid }}').toggle();">Show/Hide Items</a>
														</div>
														<div class="col-sm-12" id="sub-row_{{ $this_uuid }}" style="display:none;">
															<br />
															<table class="table table-striped table-bordered table-sm">
																<thead>
																	<tr>
																		<th width="50%">Item</th>
																		<th width="15%">Quantity Per Unit</th>
																		<th width="15%">Unit Price</th>
																		<th width="15%">Total</th>
																	</tr>
																</thead>
																<tbody class="product-bom-items">
																	@foreach ($BOM->items as $item)
																		<tr>
																			<td><div class="form-group"><div class="col-sm-12">{{ $item->product->productName }}</div><input type="hidden" name="productItemIDs_{{ $this_uuid }}[]" value="{{ $item->productID }}" /></div></td>
																			<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" onchange="calculateProductRowTotal();" name="productItemQuantitys_{{ $this_uuid }}[]" value="{{ $item->quantity }}" /></div></div></td>
																			<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" name="productItemPrices_{{ $this_uuid }}[]" onchange="calculateProductRowTotal();" value="{{ $item->unitPrice }}" /></div></div></td>
																			<td><div class="form-group"><div class="col-sm-12 row-total-{{ $this_uuid }}">{{$item->quantity * $item->unitPrice}}</div></div></td>
																		</tr>
																	@endforeach
																</tbody>
																<tfoot>
																	<tr>
																		<td colspan="3" class="text-right font-weight-bold">Total:</td>
																		<td class="font-weight-bold product-bom-item-total"></td>
																	</tr>
																</tfoot>
															</table>
															<table class="table table-striped table-bordered table-sm">
																<thead>
																	<tr>
																		<th width="50%">Expense</th>
																		<th width="15%">Quantity Per Unit</th>
																		<th width="15%">Amount</th>
																		<th width="15%">Total</th>
																	</tr>
																</thead>
																<tbody class="product-bom-expenses">
																	@foreach ($BOM->expenses as $expense)
																		<tr>
																			<td><div class="form-group"><div class="col-sm-12">{{ $expense->head->headName }} </div><input type="hidden" name="expenseHeadIDs_{{ $this_uuid }}[]" value="{{ $expense->headID }}" /></div></td>
																			<td><div class="form-group"><div class="col-sm-12"><input type="hidden" class="form-control" value="1" name="baseExpenseQtys_{{ $this_uuid }}[]" /><input type="number" class="form-control" value="1" name="expenseQuantity[]" /></div></div></td>
																			<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" name="expenseAmounts_{{ $this_uuid }}[]" onchange="calculateProductRowTotal();" value="{{ $expense->amount }}" /></div></div></td>
																			<td><div class="form-group"><div class="col-sm-12"></div></div></td>
																		</tr>
																	@endforeach
																</tbody>
																<tfoot>
																	<tr>
																		<td colspan="3" class="text-right font-weight-bold">Total:</td>
																		<td class="font-weight-bold product-bom-expense-total"></td>
																	</tr>
																	<tr>
																		<td colspan="3" class="text-right font-weight-bold">Grand Total:</td>
																		<td class="font-weight-bold product-bom-item-expense-grand-total"></td>
																	</tr>
																</tfoot>
															</table>
														</div>
													</div>
												</td>
												<td>
													<div class="form-group">
														<div class="col-sm-12">
															<input type="number" name="quantity[]" value="{{ $BOM->quantity }}" onchange="calculateProductRowTotal();" class="form-control" min="1" placeholder="Quantity" required>
														</div>
													</div>
												</td>
												<td>
													<div class="form-group">
														<div class="col-sm-12">
															<input type="number" name="total[]" value="0" class="form-control" disabled>
														</div>
													</div>
												</td>
												<td>
													<button class="btn btn-danger btn-sm float-right removeProductRow" type="button" title="Delete Product">
														<i class="nav-icon fas fa-fw fa-trash"></i>
													</button>
												</td>
											</tr>
										@endforeach
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
    @include('admin.production.formJS', ['isNew' => false])
@stop
