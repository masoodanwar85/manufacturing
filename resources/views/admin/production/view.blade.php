@extends('adminlte::page')

@section('title', 'Product')

@section('content_header')
    <h1>Production</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> {{ $production->serial }}
			</h3>
			@can('production_delete')
			<form action="{{ route('production.destroy', $production->productionID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');">
				<input type="hidden" name="_method" value="DELETE">
				@csrf
				<input type="submit" class="float-right btn btn-sm btn-danger" value="Delete">
			</form>
			@endcan
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Serial:</div>
					{{ $production->serial }}
				</div>
				<div class="col">
					<div class="font-weight-bold">Date Created:</div>
					{{ $production->dateCreated }}
				</div>
			</div>
			<div class="row">
				<div class="col">
					<table class="table table-hover">
						<tr>
							<th>Product</th>
							<th>Quantity</th>
							<th>Total</th>
						</tr>
						@foreach ($production->boms as $bom)
							<?php
								$productTotal = 0;
							?>
							<tr>
								<td>
									{{ $bom->product->productName }}<br />
									<table class="table table-sm">
										<tr>
											<th>Product Items</th>
											<th>Quantity</th>
											<th>Unit Price</th>
											<th>Sub Total</th>
										</tr>
										@foreach ($bom->items as $item)
											<?php
												$total = $item->quantity * $item->unitPrice;
												$productTotal += $total;
											?>
											<tr>
												<td>{{ $item->product->productName }}</td>
												<td>{{ $item->quantity }}</td>
												<td>{{ $item->unitPrice }}</td>
												<td>{{ $total }}</td>
											</tr>
										@endforeach
									</table>
								</td>
								<td>{{ $bom->quantity }}</td>
								<td>{{ $productTotal * $bom->quantity }}</td>
							</tr>
						@endforeach
					</table>
				</div>
			</div>
        </div>
    </div>
@stop
