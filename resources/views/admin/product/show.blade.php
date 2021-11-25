@extends('adminlte::page')

@section('title', 'Product')

@section('content_header')
    <h1>Product</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> {{ $product->productName }}
			</h3>
			@can('product_update')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('product.edit',$product->productID) }}">
				<i class="fas fa-edit"></i> Edit Product
			</a>
			@endcan
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Product:</div>
					{{ $product->productName }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Category:</div>
					{{ $product->category->categoryName }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Product Unit:</div>
					{{$product->maximumUnit->unitName}} ({{$product->maximumUnit->symbol}}) - {{$product->maximumUnit->unitType}}
				</div>
				<div class="col">
					<div class="font-weight-bold">Units in Product:</div>
					{{ $product->unitsInProduct }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Alert Quantity:</div>
					{{ $product->thresholdUnit }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Date Created:</div>
					{{ $product->dateCreated }}
				</div>
			</div>
        </div>
    </div>

	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> {{ $product->productName }} History
			</h3>
		</div>
		<div class="card-body">
			<table class="table table-hover datatable datatable-history">
				<thead>
					<tr class="table-active">
						<th>Date</th>
						<th>Description</th>
						<th>Quantity</th>
						<th> {{ $product->maximumUnit->symbol == 'Qty' ? 'Item' : $product->maximumUnit->symbol}}(s)</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$totalQty = 0;
						$totalUnitQty = 0;
					?>
					@if ($productHistory)
						@foreach ($productHistory as $productInfo)
							<tr class="@if ($productInfo->productTrack == 'Purchase') table-primary @elseif ($productInfo->productTrack == 'Sold') table-success @elseif ($productInfo->productTrack == 'OpeningStock') table-info @endif">
								<td>{{ $productInfo->dateCreated }}</td>
								<td>
									@if ($productInfo->productTrack == 'Purchase')
										<a href="{{ route('purchase.show', $productInfo->poID) }}" target="_blank">
											Purchased from @if (strlen($productInfo->poSupplier)) {{ $productInfo->poSupplier }} (Supplier) @else {{ $productInfo->poCustomer }} (Customer) @endif
										</a>
										<?php
											$totalQty += $productInfo->quantity / $product->unitsInProduct;
											$totalUnitQty += $productInfo->quantity;
										?>
									@elseif ($productInfo->productTrack == 'Sold')
										<a href="{{ route('sales.show', $productInfo->soID) }}" target="_blank">
											Sold to {{ $productInfo->soCustomer }}
										</a>
										<?php
											$totalQty -= $productInfo->quantity / $product->unitsInProduct;
											$totalUnitQty -= $productInfo->quantity;
										?>
									@elseif ($productInfo->productTrack == 'OpeningStock')
										Opening Stock
										<?php
											$totalQty += $productInfo->quantity / $product->unitsInProduct;
											$totalUnitQty += $productInfo->quantity;
										?>
									@endif
								</td>
								<td>{{ $productInfo->quantity / $product->unitsInProduct }}</td>
								<td> {{ $productInfo->quantity }}</td>
							</tr>
						@endforeach
					@else
						<tr><td colspan="4">No Detail</td></tr>
					@endif
				</tbody>
				<tfoot>
					<tr style="font-weight:bold;">
						<td colspan="2" class="text-right">Current Stock:</td>
						<td colspan="">{{ $totalQty }}</td>
						<td colspan="">{{ $totalUnitQty }}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
@stop

@section('plugins.Datatables', true)

@section('js')
    <script>
        $(function () {
            $('.datatable-history').DataTable();
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
