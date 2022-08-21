	<style type="text/css">
	table {
		border-collapse: collapse;
	}
	.toppaddingmargin0 {
		margin-top: 0;
		padding-top: 0;
	}
</style>
<center><h2 class="toppaddingmargin0" style="padding-bottom: 5px;border-bottom: 1px solid grey;">Sales Invoice</h2></center>
<table style="width:100%">
	<tr>
		<td colspan="2"><h3 class="toppaddingmargin0"><i class="fas fa-globe"></i> Bin Yamin & Nasro Trading Company</h3></td>
		<td><span class="float-right">Date: {{ date('d/m/Y') }}</span></td>
	</tr>
	<tr>
		<td>
			From
			<address>
				<strong>{{ $client->firstName }} {{ $client->lastName}}</strong><br>
				{{ $client->address }}<br>
				{{ $client->city }}, {{ $client->state }}, Pakistan<br>
				Phone: {{ $client->phone }}
			</address>
		</td>
		<td>
			To
			<address>
				<strong>{{ $salesOrder->customer->customerName }}</strong><br>
				{{ $salesOrder->customer->shopName }}<br>
				{{ $salesOrder->customer->address }}<br>
				Phone: {{ $salesOrder->customer->phone }}
			</address>
		</td>
		<td>
			<b>Invoice #{{ $salesOrder->invoiceNumber }}</b><br>
			<br>
			<b>Order ID:</b> {{ $salesOrder->salesOrderID }}<br>
			<b>Discount:</b> {{ $salesOrder->discount }}<br>
			<b>Shipping Charges:</b> {{ $salesOrder->shippingCharges }}<br>
			<b>Payment Due:</b> {{ $salesOrder->paymentDueDate }}<br><br>
		</td>
	</tr>
</table>

<!-- /.row -->

<!-- Table row -->
<div class="row">
	<div class="col-12 table-responsive">
		<h3>Product Details</h3>
		<table class="table table-striped" style="width:100%;"  border="1">
			<thead>
				<tr>
					<th>Product</th>
					<th>Quantity</th>
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
						<td>{{$stockDetailStatus->quantity}}</td>
						<td>@money('$stockDetailStatus->salePrice')</td>
						<td>@money('$stockDetailStatus->quantity * $stockDetailStatus->salePrice')</td>
					</tr>
					<?php
						$total+= $stockDetailStatus->quantity * $stockDetailStatus->salePrice;
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
			<?php
				$grandTotal = $total + $salesOrder->shippingCharges - $salesOrder->discount;
			?>
		</table>
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->
<br />

<div class="row">
	<!-- accepted payments column -->
	<div class="col">
		<div class="table-responsive">
			<table class="table" width="100%" border="1">
				<tbody>
					<tr>
						<td rowspan="5" style="width:50%;"></td>
						<th>Subtotal:</th>
						<td>@money('$total')</td>
					</tr>
					<tr>
						<th>Discount</th>
						<td>{{ $salesOrder->discount }}</td>
					</tr>
					<tr>
						<th>Shipping:</th>
						<td>{{ $salesOrder->shippingCharges }}</td>
					</tr>
					<tr>
						<th>Total:</th>
						<td>@money('$grandTotal')</td>
					</tr>
					<tr>
						<th>Balance:</th>
						<td>@money('$grandTotal - $paid')</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
