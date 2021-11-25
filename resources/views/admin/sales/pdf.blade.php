@extends('adminlte::pdf')
@section('content')
<style>
	table {
		font-family: Arial,sans-serif;
	}
	table#product-details, table#product-details td, table#product-details th {
		border: 1px solid gray;
	}
	table#product-details {
		border-collapse: collapse;
		font-size: 14px;
	}
	table#product-details thead tr {
		background: #d3d3d3;
	}

	table#product-details tfoot th:nth-child(2) {
		background: #d3d3d3;
	}

	table#product-details tbody tr:nth-child(odd) {
		background: #ececec;
	}
</style>
<table width="100%" border="0">
	<tr>
		<td>
			<h2>Book Serial # {{ $salesOrder->bookSerial }}</h2>
			<h2>Invoice # {{ $salesOrder->invoiceNumber }}</h2>
		</td>
		<td>
			<p>
				Invoice Date: {{ $salesOrder->dateCreated }}<br />
				Printed On: {{ date('d/m/Y') }}<br />
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<h3>Invoiced To:</h3>
			<p>
				{{ $salesOrder->customer->customerName }}<br>
				{{ $salesOrder->customer->shopName }}<br>
				{{ $salesOrder->customer->address }}<br>
				Phone: {{ $salesOrder->customer->phone }}
			</p>
		</td>
		<td>
			<h3>Pay To:</h3>
			<p>
				<strong>{{ $client->firstName }} {{ $client->lastName}}</strong><br>
				{{ $client->address }}<br>
				{{ $client->city }}, {{ $client->state }}, Pakistan<br>
				Phone: {{ $client->phone }}
			</p>
		</td>
	</tr>
</table>
<hr />
<h3>Product Details</h3>
<table id="product-details" width="100%" border="1" cellpadding="5">
	<thead>
		<tr>
			<th>Product</th>
			<th>Quantity</th>
			<th>Units</th>
			<th>Sale Price</th>
			<th>Sub Total</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$total = 0;
			$paid = 0;
			$grandTotal = 0;
		?>
		@foreach($salesOrder->stockDetailStatuses as $stockDetailStatus)
			<tr>
				<td>{{$stockDetailStatus->stockDetail->product->productName}} ({{$stockDetailStatus->stockDetail->product->category->categoryName}})</td>
				<td align="center">{{$stockDetailStatus->quantity}}</td>
				<td align="center">
					{{$stockDetailStatus->quantityUnits}}
					{{ $stockDetailStatus->stockDetail->product->maximumUnit->symbol == 'Qty' ? 'Items' : $stockDetailStatus->stockDetail->product->maximumUnit->symbol }}
				</td>
				<td align="center">@money('$stockDetailStatus->salePrice')</td>
				<td align="center">@money('$stockDetailStatus->quantityUnits * $stockDetailStatus->salePrice')</td>
			</tr>
			<?php
				$total+= $stockDetailStatus->quantityUnits * $stockDetailStatus->salePrice;
			?>
		@endforeach
		<tr><td colspan="5">&nbsp;</td></tr>
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
	<?php
		$grandTotal = $total + $salesOrder->shippingCharges - $salesOrder->discount;
	?>
	<tfoot>
		<tr>
			<th align="right" colspan="4">Subtotal:</th>
			<th>@money('$total')</th>
		</tr>
		<tr>
			<th align="right" colspan="4">Discount</th>
			<th>{{ $salesOrder->discount }}</th>
		</tr>
		<tr>
			<th align="right" colspan="4">Shipping:</th>
			<th>{{ $salesOrder->shippingCharges }}</th>
		</tr>
		<tr>
			<th align="right" colspan="4">Total:</th>
			<th>@money('$grandTotal')</th>
		</tr>
		<tr>
			<th align="right" colspan="4">Balance:</th>
			<th>@money('$grandTotal - $paid')</th>
		</tr>
	</tfoot>
</table>
@stop
