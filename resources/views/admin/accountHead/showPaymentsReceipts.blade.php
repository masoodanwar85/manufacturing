@extends('adminlte::page')

@section('title', 'Receipts/Payments List')

@section('content_header')
    <h1>Receipts/Payments List</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-hand-holding-usd"></i> Receipts/Payments List
			</h3>
			@can('account_head_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('accountHead.payment') }}">
				<i class="fas fa-plus-circle"></i> Add Payment
			</a>

			<a class="btn btn-primary btn-sm float-right" style="margin-right:10px;" href="{{ route('accountHead.receipt') }}">
				<i class="fas fa-plus-circle"></i> Add Receipt
			</a>
			@endcan

		</div>
		<div class="card-body">
            <form>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">From Date</label>
                        <input type="date" class="form-control" name="fromDate" value="{{ $filters['fromDate'] }}" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputToDate">To Date</label>
                        <input type="date" class="form-control" name="toDate" value="{{ date('Y-m-d',strtotime($filters['toDate'])) }}" />
                    </div>
                    <div class="form-group col-md-1">
                        <label for="inputFromDate">Ignore Dates?</label>
                        <br />&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="isIgnoreDates" value="1" {!! $filters['isIgnoreDates'] == 1 ? 'checked' : '' !!} />
                    </div>
					<div class="form-group col-md-2">
                        <label for="selectHeadFilter">Head</label>
                        <select name="headID" class="form-control">
                            <option value=""></option>
                            @foreach ($filterHeads as $head)
                                <option value="{{ $head->headID }}" {!! $filters['headID'] == $head->headID ? 'selected' : '' !!} >{{ $head->headName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputTransactionTypeNumber">Bill #</label>
                        <input type="text" name="transactionTypeNumber" class="form-control" value="{{ $filters['transactionTypeNumber'] }}" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputTransactionTypeNumber">Customer</label>
                        <select name="customerID" class="form-control">
                            <option value=""></option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->customerID }}" {!! $filters['customerID'] == $customer->customerID ? 'selected' : '' !!} >{{ $customer->customerName }} ({{ $customer->address }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <button style="margin-top:30px;" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="form-group col-md-1">
                        {{ count($transactions) }} Records Found
                    </div>
                </div>
            </form>
			<table class="table table-sm table-hover">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Bill #</th>
                    <th>Account</th>
                    <th>Head</th>
                    <th>Amount</th>
                    <th>Creation Date</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                @if (count($transactions))
                    @foreach ($transactions as $transaction)
                        <?php
                            $amount = 0;
                            $head = "";
                            $subHead = "";
                            $description = "";
                        ?>
                        @foreach ($transaction->transactionDetails as $transactionDetail)
                            <?php
                                if ($transactionDetail->isDebit == 1) {
                                    $amount += $transactionDetail->amount;
                                }
                                $head .= ($head != $transactionDetail->head->headName) ? ($head != "" ? " <i class='fa fa-exchange-alt'></i> " : "" ) . $transactionDetail->head->headName : "";
                                $subHead .= ($subHead != $transactionDetail->subHead->headName) ? $transactionDetail->subHead->headName : "";
                                if ($description == "") {
                                    $description .= $transactionDetail->description;
                                }
                            ?>
                            @if ($loop->last)
                                <tr>
                                    <td>{{ $loop->parent->iteration }}</td>
                                    <td>{{ date('Y-m-d',strtotime($transaction->transactionDate)) }}</td>
                                    <td>{{ $transaction->transactionTypeNumber }}</td>
                                    <td>{{ $subHead }}</td>
                                    <td>{!! $head !!}</td>
                                    <td>{{ $amount }}</td>
                                    <td>{{ $transaction->dateCreated }}</td>
                                    <td>{{ $description }}</td>
                                    <td>
                                        @can('transaction_delete')
                                            <form action="{{ route('accountHead.transactionDestroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="transactionID" value="{{ $transaction->transactionID }}" />
                                                <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
					<tfoot>
						<td colspan="5" class="text-right font-weight-bolder">Total:</td>
						<td colspan="4" id="totals" class="font-weight-bolder"></td>
					</tfoot>
                @else
                    <tr><td colspan="9">No Record Found.</td></tr>
                @endif
            </table>
        </div>
    </div>
@endsection
@section('js')
	<script>
        $(function () {
			var total = $('table tbody tr td:nth-child(6)').toArray().reduce((partial_sum, a) => partial_sum + parseInt(a.innerHTML),0);
			$('#totals').html(total);
        });
    </script>
@stop
