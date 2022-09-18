@extends('adminlte::page')

@section('title', 'Sales Report')

@section('content_header')
    <h1>Sales Report</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i> Sales Report
            </h3>
			<h4 class="card-title" style="float:right;">
				<i class="fas fa-fw fa-money-bill-alt"></i> <span id="totalAmount"></span>
			</h4>
        </div>
        <div class="card-body">
			<form>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">Month</label>
                        <input type="month" class="form-control" name="monthReport" value="{{ $monthReport }}" />
                    </div>
					<div class="form-group col-md-2">
                        <label for="inputFromDate">Cash Book</label>
                        <input type="checkbox" name="isSearchByCashBook" value="1" @if($isSearchByCashBook == 1) checked @endif />
                    </div>
					<div class="form-group col-md-2">
                        <label for="inputFromDate">Bill Book</label>
                        <input type="checkbox" name="isSearchByBillBook" value="1" @if($isSearchByBillBook == 1) checked @endif />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">Show Products</label>
                        <input type="checkbox" name="isShowProducts" value="1" @if($isShowProducts == 1) checked @endif />
                    </div>
                    <div class="form-group col-md-2">
                        <button style="margin-top:30px;" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="form-group col-md-2">
                        {{ count($allSalesOrder) }} Records Found
                    </div>
                </div>
            </form>
			<table class="table table-hover table-sm">
                <thead>
                    <tr class="table-info">
                        <th>Date</th>
                        <th>Book #</th>
						<th>Customer</th>
						<th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $currentBalance = 0;
						$currentAmount = 0;
                    ?>
                    @foreach ($allSalesOrder as $saleOrder)
						<tr>
							<td>{{ date('d-m-Y',strtotime($saleOrder->orderDate)) }}</td>
							<td><a href="/admin/sales/{{$saleOrder->salesOrderID}}" target="_blank">{{ $saleOrder->bookSerial }}</a></td>
							<td>
                                {{ $saleOrder->customer->customerName }} ({{ $saleOrder->customer->address }})
                                @if($isShowProducts == 1)
                                    <div class="row">
                                        @foreach($saleOrder->salesOrderDetails as $saleOrderDetail)
                                            <div class="col-2 text-secondary">
                                                {{ $saleOrderDetail->stockDetailStatus->stockDetail->product->productName }} (Qty: {{ $saleOrderDetail->stockDetailStatus->quantity }})
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
							<td>
								<?php
									$salesOrderTransactionDetails = $saleOrder->transactions[0]->transactionDetails;
									foreach ($salesOrderTransactionDetails as $transactionDetail) {
										if ($transactionDetail->isDebit == 0) {
											$currentAmount=$transactionDetail->amount;
											$currentBalance+=$currentAmount;
										}
									}
								?>
								<span style="color:green;">@money('$currentAmount','')</span>
							</td>
						</tr>
                    @endforeach
                </tbody>
				<?php
					$currentBalance = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $currentBalance);
				?>
                <tfoot>
                    <tr style="font-weight:bold;">
                        <td colspan="3" class="text-right">Total:</td>
                        <td>
							{{ $currentBalance }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@stop

@section('js')
	<script type="text/javascript">
		$('span#totalAmount').html("Rs. {{ $currentBalance }}");
	</script>
@stop
@section('css')
    <link rel="stylesheet" href="/css/app_.css">
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
