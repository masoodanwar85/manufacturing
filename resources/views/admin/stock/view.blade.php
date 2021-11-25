@extends('adminlte::page')

@section('title', 'Stock Details')

@section('content_header')
    <h1>Stock Details</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-cubes"></i> Stock Detail for <em>{{$stockInfo[0]->productName}}</em>
            </h3>
        </div>
        <div class="card-body">
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Product:</div>
					{{ $stockInfo[0]->productName }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Category:</div>
					{{ $product->category->categoryName }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Last Purchase Price:</div>
					{{ \App\Services\CurrencyService::getCurrencyFormatted($stockInfo[0]->lastPurchasePrice) }}
				</div>
			</div>
        </div>
    </div>

	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Godown Details
			</h3>
		</div>
		<div class="card-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Godown</th>
						<th>Total Purchased</th>
						<th>In Stock</th>
						<th>Sold</th>
						@if ($globalSettings['client_settings.is_show_damaged_units'] == 1)<th>Damaged</th>@endif
						<th>Good Sales Return</th>
						<th>Bad Sales Return</th>
                        <th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($stockInfo as $stockData)
						<tr>
							<td>{{ $stockData->godownName }}</td>
							<td>
								{{ $stockData->totalPurchasedQuantity }}
								@if ($stockData->totalPurchasedQuantity > 0)
									 Qty
									@if ($stockData->symbol == 'Qty')
										@if ($stockData->unitsInProduct > 1)
											: {{ $stockData->totalPurchasedUnits }} items
										@endif
									@else
										 : {{ $stockData->totalPurchasedUnits }} {{ $stockData->symbol }}(s)
									@endif
								@endif
							</td>
							<td>
								{{ $stockData->inStockQuantity }}
								@if ($stockData->inStockQuantity > 0)
								 	Qty
									@if ($stockData->symbol == 'Qty')
										@if ($stockData->unitsInProduct > 1)
											: {{ $stockData->inStockUnits }} items
										@endif
									@else
										 : {{ $stockData->inStockUnits }} {{ $stockData->symbol }}(s)
									@endif
								@endif
							</td>
							<td>
								{{ $stockData->totalSoldQuantity }}
								@if ($stockData->totalSoldQuantity > 0)
									 Qty
									@if ($stockData->symbol == 'Qty')
										@if ($stockData->unitsInProduct > 1)
											: {{ $stockData->totalSoldUnits }} items
										@endif
									@else
									 	: {{ $stockData->totalSoldUnits }} {{ $stockData->symbol }}(s)
									@endif
								@endif
							</td>
							@if ($globalSettings['client_settings.is_show_damaged_units'] == 1)
							<td>
								{{ $stockData->totalDamagedQuantity }}
								@if ($stockData->totalDamagedQuantity > 0)
									 Qty
									@if ($stockData->symbol == 'Qty')
										@if ($stockData->unitsInProduct > 1)
											: {{ $stockData->totalDamagedUnits }} items
										@endif
									@else
										 : {{ $stockData->totalDamagedUnits }} {{ $stockData->symbol }}(s)
									@endif
								@endif
							</td>
							@endif
							<td>
								{{ $stockData->totalGoodSalesReturnQuantity }}
								@if ($stockData->totalGoodSalesReturnQuantity > 0)
									 Qty
									@if ($stockData->symbol == 'Qty')
										@if ($stockData->unitsInProduct > 1)
											: {{ $stockData->totalGoodSalesReturnUnits }} items
										@endif
									@else
										 : {{ $stockData->totalGoodSalesReturnUnits }} {{ $stockData->symbol }}(s)
									@endif
								@endif
							</td>
							<td>
								{{ $stockData->totalBadSalesReturnQuantity }}
								@if ($stockData->totalBadSalesReturnQuantity > 0)
									 Qty
									@if ($stockData->symbol == 'Qty')
										@if ($stockData->unitsInProduct > 1)
											: {{ $stockData->totalBadSalesReturnUnits }} items
										@endif
									@else
										 : {{ $stockData->totalBadSalesReturnUnits }} {{ $stockData->symbol }}(s)
									@endif
								@endif
							</td>
                            <td>
                                @if ($stockData->inStockQuantity > 0)
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal"
										onClick="stockTransfer({{ $stockData->godownID }},{{ $stockData->inStockQuantity }},{{ $stockData->inStockUnits }},'{{ $stockData->godownName }}');"
										style="color:white;" title="Stock Transfer"> <i class="fas fa-dolly-flatbed"></i>
										Transfer
									</button>
                                @endif
                            </td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form name="frmStockTransfer" action="{{ route('stock.transfer') }}" method="post">
					@csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Stock Transfer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="prevGodown" class="col-sm-4 col-form-label">Previous Godown:</label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="prevGodown" value="">
								<input type="hidden" name="previousGodownID" id="previousGodownID" value="" />
                                <input type="hidden" name="productID" value="{{ $stockInfo[0]->productID }}" />
                            </div>
                        </div>
						<div class="form-group row">
							<label for="qty" class="col-sm-4 col-form-label">Quantity to move:</label>
							<div class="col-sm-8">
								<input type="number" class="form-control" id="quantityToMove" max="" name="quantityToMove" value="" />
							</div>
						</div>
                        <div class="form-group row">
							<label for="qty" class="col-sm-4 col-form-label">Units to move:</label>
							<div class="col-sm-8">
								<input type="number" readonly class="form-control" id="unitsToMove" max="" name="unitsToMove" value="" />
							</div>
						</div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-4 col-form-label">New Godown:</label>
                            <div class="col-sm-8">
                                <select class="select2 form-control" name="newGodownID">
                                    @foreach ($godowns as $godown)
                                        <option value="{{$godown->godownID}}">{{$godown->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Selling Details
			</h3>
		</div>
		<div class="card-body">
			<table class="table">
				<thead>
					<tr>
						<th>Customer</th>
						<th>Date</th>
						<th>Sold Units</th>
						<th>Good Sales Return</th>
						<th>Bad Sales Return</th>
						<th>Sale Price</th>
						<th>Total Amount (PKR)</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($saleOrders as $orderDetail)
						@foreach ($orderDetail->stockDetailStatuses as $stockDetailStatus)
							@if ($product->productID == $stockDetailStatus->stockDetail->productID)
								<tr>
									<td>{{$orderDetail->customer->customerName}}</td>
									<td>{{$orderDetail->orderDate}}</td>
									<td>
										@if ($stockDetailStatus->statusID == $soldStatusID)
											{{$stockDetailStatus->quantity}}
										@else
											0
										@endif
									</td>
									<td>
										@if ($stockDetailStatus->statusID == $goodSalesReturnStatusID)
											{{$stockDetailStatus->quantity}}
										@else
											0
										@endif
									</td>
									<td>
										@if ($stockDetailStatus->statusID == $badSalesReturnStatusID)
											{{$stockDetailStatus->quantity}}
										@else
											0
										@endif
									</td>
									<td>
										{{$stockDetailStatus->salePrice}}
									</td>
									<td>
										{{$stockDetailStatus->quantity * $stockDetailStatus->salePrice}}
									</td>
									<td>
                                        <a class="btn btn-sm btn-primary" href="{{route('sales.show', $orderDetail->salesOrderID)}}" title="View Order"> <i class="fas fa-file"></i> Order</a>
										<a class="btn btn-sm btn-warning" href="{{route('sales.invoice', $orderDetail->salesOrderID)}}" title="View Invoice"> <i class="fas fa-file-invoice"></i> Invoice</a>
									</td>
								</tr>
							@endif
						@endforeach
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('plugins.Select2', true)

@section('js')
    <script type="text/javascript">
		function stockTransfer(prevGodownID,totalQty,totalUnits,godownName) {
			$('#previousGodownID').val(prevGodownID);
			$('#quantityToMove').val(totalQty);
            $('#unitsToMove').val(totalUnits);
			$('#prevGodown').val(godownName);
			$('#quantityToMove').attr('max',totalQty);
            $('#unitsToMove').attr('max',totalUnits);
		}

        $('input#quantityToMove').bind('keydown mouseup keypress blur keyup change', function(e) {
            var quantity = $(e.target).val();
			$('#unitsToMove').val(quantity*{{$stockInfo[0]->unitsInProduct}});
        });
    </script>
@stop
