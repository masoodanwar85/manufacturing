@extends('adminlte::page')

@section('title', 'Edit Sales Order')

@section('content_header')
    <h1>Edit Sales Order</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-share"></i> Edit Sales Order
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" id="frmSales" action="{{ route('sales.update', $salesOrder->salesOrderID) }}" method="POST" onSubmit="return formCheck()">
				@csrf
				@method('PUT')
				<div class="form-group row {{ $errors->has('customerID') ? 'has-error' : '' }}">
					<label for="customerID" class="col-sm-2 col-form-label">Customer: *</label>
					<div class="col-sm-5">
						<select name="customerID" class="form-control select2 @if($errors->has('customerID')) is-invalid @endif" onChange="getCustomerData(this.value);" required>
							<option value="">Please Select Customer</option>
							@foreach($customers as $customer)
								<option value="{{ $customer->customerID }}" salesAgentID="{{ $customer->salesAgent ? $customer->salesAgent->staffID : null }}" {{ old('customerID', $salesOrder->customerID) == $customer->customerID ? 'selected' : '' }}>{{ $customer->customerName }} ({{ $customer->shopName }}) ({{ $customer->address }})</option>
							@endforeach
						</select>
						@if($errors->has('customerID'))
							<em class="invalid-feedback">
								{{ $errors->first('customerID') }}
							</em>
						@endif
					</div>
					<div class="col-sm-2">
						<span style="color:red;font-weight:bold;" id="customerBalance">Balance: {{ \App\Services\CurrencyService::getCurrencyFormatted($totalPayable) }}</span>
					</div>
                    <div class="col-sm-3">
                        <input type="text" readonly placeholder="Bill Book Serial #" name="bookSerial" class="form-control" value="{{ old('bookSerial', $salesOrder->bookSerial) }}" />
                    </div>
				</div>
                <div class="form-group row {{ $errors->has('orderDate') ? 'has-error' : '' }}">
                    <label for="orderDate" class="col-sm-2 col-form-label">Date: *</label>
					<div class="col-sm-3">
	                    <input type="date" name="orderDate" class="form-control @if($errors->has('orderDate')) is-invalid @endif" value="{{ old('orderDate', $salesOrder->orderDate) }}" required>
	                    @if($errors->has('orderDate'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('orderDate') }}
	                        </em>
	                    @endif
					</div>
					<label for="discount" class="offset-sm-2 col-sm-2 col-form-label">Discount: *</label>
					<div class="col-sm-3">
	                    <input type="number" name="discount" class="form-control @if($errors->has('discount')) is-invalid @endif" value="{{ old('discount',$salesOrder->discount) }}" required>
	                    @if($errors->has('discount'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('discount') }}
	                        </em>
	                    @endif
					</div>
                </div>
				<div class="form-group row {{ $errors->has('paymentDueDate') ? 'has-error' : '' }}">
                    <label for="orderDate" class="col-sm-2 col-form-label">Payment Due Date: *</label>
					<div class="col-sm-3">
	                    <input type="date" name="paymentDueDate" class="form-control @if($errors->has('paymentDueDate')) is-invalid @endif" value="{{ old('paymentDueDate',$salesOrder->paymentDueDate) }}" required>
	                    @if($errors->has('paymentDueDate'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('paymentDueDate') }}
	                        </em>
	                    @endif
					</div>
					<label for="shippingCharges" class="offset-sm-2 col-sm-2 col-form-label">Shipping Charges: *</label>
					<div class="col-sm-3">
	                    <input type="number"  onkeyup="calculateGrandTotal();" name="shippingCharges" class="form-control @if($errors->has('shippingCharges')) is-invalid @endif" value="{{ old('shippingCharges',$salesOrder->shippingCharges) }}" required>
	                    @if($errors->has('shippingCharges'))
	                        <em class="invalid-feedback">
	                            {{ $errors->first('shippingCharges') }}
	                        </em>
	                    @endif
					</div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Sales Agent: </label>
					<div class="col-sm-3">
                        @if (auth()->user()->staffID == null)
                            <select name="salesAgentID" class="form-control select2">
                                <option value="">Please Select Sales Agent</option>
                                @foreach($saleAgents as $saleAgent)
                                    <option value="{{ $saleAgent->staffID }}" {{ old('salesAgentID', $salesOrder->salesAgentID) == $saleAgent->staffID ? 'selected' : '' }}>{{ $saleAgent->staffName }}</option>
                                @endforeach
                            </select>
                        @else
                            {!! auth()->user()->staff ? auth()->user()->staff->staffName: '' !!}
                            <input type="hidden" name="salesAgentID" value="{{ auth()->user()->staff->staffID }}" />
                        @endif
					</div>
                    <label for="description" class="offset-sm-2 col-sm-2 col-form-label">Description: </label>
					<div class="col-sm-3">
                    	<textarea name="description" class="form-control">{{ old('description', $salesOrder->description) }}</textarea>
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
								<table class="table" id="myTable">
									<thead>
										<tr class="text-center">
                                            <th style="text-align:center;width:25%;">Product Name</th>
											<th style="text-align:center;width:10%;">Total Units Available</th>
											<th style="text-align:center;width:15%;">Godown</th>
											<th style="text-align:center;width:10%;">Per Unit Price</th>
											<th style="text-align:center;width:8%;">Quantity</th>
											<th style="text-align:center;width:10%;">Sale Price</th>
                                            @if ($globalSettings['client_settings.is_show_discount_per_product'] == 1)
                                                <th style="text-align:center;width:10%;">Discount</th>
                                                <?php $colspanValue++; ?>
                                            @endif
											<th style="text-align:center;width:15%;">Sub Total</th>
											<th style="text-align:center;width:5%;">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$total = 0;
                                            $discount = 0;
											$paid = 0;
										?>
										@foreach($salesOrder->stockDetailStatuses as $stockDetailStatus)
											<?php
												$aryProduct = \App\Models\Stock::getProducts($stockDetailStatus->stockDetail->productID);
												$unitsAvailability = 0;
												if (count($aryProduct)) {
													$unitsAvailability = $aryProduct[0]->quantityAvailable;
												}
											?>
											<tr>
												<td>
													<div class="form-group">
												        <div class="col-sm-12">
															{{ $stockDetailStatus->stockDetail->product->productName }} ({{ $stockDetailStatus->stockDetail->product->category->categoryName }})
															<input type="hidden" name="productID[]" value="{{ $stockDetailStatus->stockDetail->productID }}" />
														</div>
													</div>
												</td>
												<td>
													<div class="form-group">
												        <div class="col-sm-12">
															<input type="hidden" name="unitsInProduct[]" value="{{$stockDetailStatus->stockDetail->product->unitsInProduct}}" />
												            <span class="totalUnitsAvailable">
												            	<?php
																	$unitsAvailable = 0;
																	if (count($flattenedStockProducts) && !empty($flattenedStockProducts['product_' . $stockDetailStatus->stockDetail->productID])) {
																		$unitsAvailable = $flattenedStockProducts['product_' . $stockDetailStatus->stockDetail->productID]->quantityAvailable;
																	}
																	//$unitsAvailable+=$stockDetailStatus->quantity;
                                                                    $totalUnitsAvailableText = "0";
                                                                    if ($unitsAvailable > 0 && $stockDetailStatus->stockDetail->product->unitsInProduct > 0) {
                                                                        $totalUnitsAvailableText = $unitsAvailable / $stockDetailStatus->stockDetail->product->unitsInProduct;
                                                                    }
																	$totalUnitsAvailable = $unitsAvailable;
																	if ($stockDetailStatus->stockDetail->product->unitsInProduct > 1) {
																		$totalUnitsAvailableText .= " Qty -- ${totalUnitsAvailable}";
																		$totalUnitsAvailableText .= ($stockDetailStatus->stockDetail->product->maximumUnit->symbol == 'Qty') ? " Items" : " " . $stockDetailStatus->stockDetail->product->maximumUnit->symbol;
																	}
																?>
																{{ $totalUnitsAvailableText }}
												            </span>
															<input type="hidden" name="unitsAvailable[]" value="{{$unitsAvailable+$stockDetailStatus->quantity}}" />
												        </div>
												    </div>
												</td>
												<td>
													<div class="form-group">
														<div class="col-sm-12 godown text-center">
															{{ $stockDetailStatus->godown->name }}
															<input type="hidden" name="godownID[]" value="{{$stockDetailStatus->godownID}}" />
														</div>
													</div>
												</td>
												<td>
													<div class="form-group">
														<div class="col-sm-12">
												            <input type="text" disabled name="purchasePrice[]" value="@money('$stockDetailStatus->stockDetail->purchasePrice','')" class="form-control" min="1" />
												        </div>
													</div>
												</td>
												<td>
													<div class="form-group">
												        <div class="col-sm-12">
												            <input type="number" name="quantity[]" readonly value="{{$stockDetailStatus->quantity}}" class="form-control" min="1" max="{{$stockDetailStatus->quantity + $unitsAvailability}}" placeholder="Quantity" required>
												        </div>
												    </div>
												</td>
                                                <td>
													<div class="form-group">
														<div class="col-sm-12">
											            	<input type="text" readonly onkeyUp="calculateProductRowTotal(this);" name="salePrice[]" value="@money('$stockDetailStatus->salePrice','')" class="form-control" min="1" placeholder="Sale Price" required>
											        	</div>
													</div>
												</td>
                                                @if ($globalSettings['client_settings.is_show_discount_per_product'] == 1)
                                                    <td>
    													<div class="form-group">
    														<div class="col-sm-12">
    											            	<input type="text" readonly onkeyUp="calculateProductRowTotal(this);" name="product_discount[]" value="@money('$stockDetailStatus->discount','')" class="form-control" min="0" placeholder="Discount" required>
    											        	</div>
    													</div>
    												</td>
                                                @else
                                                    <input type="hidden" name="product_discount[]" value="0" />
                                                @endif
												<td>
													<div class="form-group">
												        <div class="col-sm-12">
												            <input type="text" disabled name="total[]" value="@money('($stockDetailStatus->quantityUnits * $stockDetailStatus->salePrice)-($stockDetailStatus->quantityUnits * $stockDetailStatus->discount)','')" class="form-control" />
												        </div>
												    </div>
												</td>
												<td>
													<button class="btn btn-danger btn-sm pull-right removeSORow" type="button" title="Delete Sales Item">
												        <i class="nav-icon fas fa-fw fa-trash"></i>
												    </button>
												</td>
											</tr>
											<?php
												$total+= ($stockDetailStatus->quantity * $stockDetailStatus->salePrice)-($stockDetailStatus->quantity * $stockDetailStatus->discount);
											?>
										@endforeach
									</tbody>
									@foreach ($salesOrder->transactions as $transaction)
										@foreach ($transaction->transactionDetails as $transactionDetail)
											@if ($transactionDetail->headID == $cashHeadID)
												<?php
													$paid+= $transactionDetail->amount;
												?>
											@endif
										@endforeach
									@endforeach
									<tfoot>
										<tr id="actionRow">
											<td colspan="{{ $colspanValue }}"></td>
											<td>
												<button class="btn btn-primary btn-sm pull-right " onclick="addSORow()" type="button" title="Add New Sale Item">
													<i class="nav-icon fas fa-fw fa-plus"></i>
												</button>
											</td>
										</tr>
										<tr>
											<td colspan="{{ $colspanValue-1 }}" class="font-weight-bold text-right">Sub Total:</td>
											<td colspan="2" id="gSubTotal" class="font-weight-bold"> &nbsp;&nbsp;&nbsp;&nbsp;{{ $total }} </td>
										</tr>
										<tr>
											<td colspan="{{ $colspanValue-1 }}" class="font-weight-bold text-right">Discount:</td>
											<td colspan="2" id="discountTotal" class="font-weight-bold">&nbsp;&nbsp;&nbsp;&nbsp;{{$salesOrder->discount}}</td>
										</tr>
										<tr>
											<td colspan="{{ $colspanValue-1 }}" class="font-weight-bold text-right">Shipping:</td>
											<td colspan="2" id="shippingChargesTotal" class="font-weight-bold"> &nbsp;&nbsp;&nbsp;&nbsp;{{ $salesOrder->shippingCharges }} </td>
										</tr>
										<tr>
											<td colspan="{{ $colspanValue-1 }}" class="font-weight-bold text-right">Grand Total:</td>
											<td colspan="2" id="gTotal" class="font-weight-bold">&nbsp;&nbsp;&nbsp;&nbsp;{{ $total - $salesOrder->discount + $salesOrder->shippingCharges }}</td>
										</tr>
										<tr>
											<td colspan="{{ $colspanValue-1 }}" class="font-weight-bold text-right">Paid:</td>
											<td colspan="2">
												<input type="text" onKeyUp="calculateBalance();" name="amountPaid" id="amountPaid" value="{{$paid}}" class="form-control" />
											</td>
										</tr>
										<tr>
											<td colspan="{{ $colspanValue-2 }}" class="font-weight-bold text-right font-urdu" id="balanceInUrdu"></td>
											<td class="font-weight-bold text-right">Balance:</td>
											<td colspan="2" id="balance" class="font-weight-bold">&nbsp;&nbsp;&nbsp;&nbsp;{{ $total - $salesOrder->discount + $salesOrder->shippingCharges - $paid}}</td>
										</tr>

									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<hr>
				</div>

				<div>
                    <input class="btn btn-primary" type="submit" value="Update">
                </div>
            </form>
        </div>
    </div>

    @include('admin.sales.dynamicFields')
@endsection

@section('css')
    <link rel="stylesheet" href="/vendor/adminlte/dist/css/adminlte.css">
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

@section('plugins.daterangepicker', true)
@section('js')
	<script src="/js/utils.js"></script>
	@include('admin.sales.formJS', ['isNew' => false])
@stop
