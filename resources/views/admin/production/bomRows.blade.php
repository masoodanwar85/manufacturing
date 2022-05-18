<div id="bom-product" class="row" @if (isset($product) && $product->isBOM == 1) @else style="display:none;" @endif>
	<div class="col-sm-2"><h3>BOM Details</h3></div>
	<div class="col-sm-10">
		<hr />
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h3>Product Items</h3>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<table class="table" id="myTable">
							<thead>
								<tr class="text-center">
									<th style="text-align:center;width:25%;">Product Name</th>
									<th style="text-align:center;width:10%;">Quantity</th>
									<th style="text-align:center;width:15%;">Per Unit Price</th>
									<th style="text-align:center;width:15%;">Total</th>
									<th style="text-align:center;width:5%;">Action</th>
								</tr>
							</thead>
							<tbody>
								@if (isset($product) && $product->isBOM == 1)
									<input type="hidden" name="productBOMID" value="{{ $product->BOMs->productBOMID }}" />
									@foreach ($product->BOMs->items as $productBOMItem)
										<tr>
											<td>
												<div class="form-group">
													<div class="col-sm-12">
														<select name="productID[]" class="form-control" onChange="productChanged(this);" required>
															<option value=""></option>
															@foreach ($products as $objProduct)
																<option value="{{$objProduct->productID}}" {{ $productBOMItem->productID == $objProduct->productID ? 'selected' : '' }} >{{$objProduct->productName}} ( {{$objProduct->category->categoryName}} )</option>
															@endforeach
														</select>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group">
													<div class="col-sm-12">
														<input type="number" name="quantity[]" value="{{ $productBOMItem->quantity }}" class="form-control" min="1" placeholder="Quantity" required>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group">
													<div class="col-sm-12">
														<input type="text" onkeyUp="calculateProductRowTotal(this);" name="perUnitPrice[]" value="{{ $productBOMItem->product->unitPurchasePrice }}" class="form-control" min="1" placeholder="Unit Price" readonly>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group">
													<div class="col-sm-12">
														<input type="text" disabled name="total[]" value="{{ $productBOMItem->quantity * $productBOMItem->product->unitPurchasePrice }}" class="form-control" />
													</div>
												</div>
											</td>
											<td>
												<button class="btn btn-danger btn-sm pull-right removeBOMRow" type="button" title="Delete BOM Item">
													<i class="nav-icon fas fa-fw fa-trash"></i>
												</button>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2" class="font-weight-bold text-right font-urdu" id="moneyInUrdu"></td>
									<td class="font-weight-bold text-right">Total:</td>
									<td id="gTotal" class="font-weight-bold"></td>
									<td id="gTotalInPKR" class="font-weight-bold"></td>
								</tr>
								<tr id="actionRow">
									<td colspan="4"></td>
									<td>
										<button class="btn btn-primary btn-sm pull-right " onclick="addBOMRow()" type="button" title="Add New BOM Item">
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
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h3>Expenses</h3>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<table class="table" id="expenseTable">
							<thead>
								<tr class="text-center">
									<th style="text-align:center;">Expense</th>
									<th style="text-align:center;">Amount</th>
									<th style="text-align:center;">Action</th>
								</tr>
							</thead>
							<tbody>
								@if (isset($product) && $product->isBOM == 1)
									@foreach ($product->BOMs->expenses as $productBOMExpense)
										<tr>
											<td>
												<div class="form-group">
													<div class="col-sm-12">
														<select name="headID[]" class="form-control" required>
															<option value=""></option>
															@foreach ($BOMExpense as $key => $value)
																<option value="{{$value['headID']}}" {{ $productBOMExpense->expenseHeadID == $value['headID'] ? 'selected' : '' }}>{{$value['headName']}}</option>
															@endforeach
														</select>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group">
													<div class="col-sm-12">
														<input type="text" onkeyUp="calculateRowPerUnitExpense(this);" name="amount[]" value="{{ $productBOMExpense->amount }}" min="1" class="form-control" placeholder="Expense Amount" required />
													</div>
												</div>
											</td>
											<td>
												<button class="btn btn-danger btn-sm pull-right removeBOMExpenseRow" type="button" title="Delete BOM Expense Item">
													<i class="nav-icon fas fa-fw fa-trash"></i>
												</button>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
							<tfoot>
								<tr>
									<td class="font-weight-bold text-right font-urdu" id="expenseInUrdu"></td>
									<td class="font-weight-bold text-right">Total:</td>
									<td id="expenseGTotalInPKR" class="font-weight-bold"></td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<td>
										<button class="btn btn-primary btn-sm pull-right " onclick="addBOMExpenseRow()" type="button" title="Add New BOM Expense">
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
	</div>
</div>
