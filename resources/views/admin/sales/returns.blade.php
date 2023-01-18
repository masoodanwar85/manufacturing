@extends('adminlte::page')
@section('title', 'Sale Returns')

@section('content_header')
    <h1>Sale Returns</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-share"></i> Sale Returns
			</h3>
		</div>
		<div class="card-body">
            <form id="frmSearch">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">From Date</label>
                        <input type="date" class="form-control" name="fromDate" value="{{ $filters['fromDate'] }}" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputToDate">To Date</label>
                        <input type="date" class="form-control" name="toDate" value="{{ date('Y-m-d',strtotime($filters['toDate'])) }}" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="customerID">Customer</label>
                        <select name="customerID" class="form-control">
                            <option value=""></option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->customerID }}" {!! $filters['customerID'] == $customer->customerID ? 'selected' : '' !!} >{{ $customer->customerName }} ({{ $customer->shopName }}) ({{ $customer->address }})</option>
                            @endforeach
                        </select>
                    </div>
                    @if (!empty($salesAgents))
                        <div class="form-group col-md-2">
                            <label for="customerID">Sales Agent</label>
                            <select name="salesAgentID" class="form-control">
                                <option value=""></option>
                                @foreach ($salesAgents as $saleAgent)
                                    <option value="{{ $saleAgent->staffID }}" {!! $filters['salesAgentID'] == $saleAgent->staffID ? 'selected' : '' !!} >{{ $saleAgent->staffName }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="form-group col-md-1">
                        <button style="margin-top:30px;" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-salesOrder">
				<thead>
					<tr>
						<th>Customer</th>
                        <th>Sales Agent</th>
						<th>Order Date</th>
						<th>Invoice #</th>
                        <th>Return Amount</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('plugins.Datatables', true)
@section('js')
    <script>
        $(function () {
            let dtOverrideGlobals = {
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('sales.returns') }}?" + $('#frmSearch').serialize(),
                columns: [
					{ data: 'customerName', name: 'customerName' },
                    { data: 'salesAgent', name: 'salesAgent' },
                    { data: 'orderDate', name: 'orderDate' },
					{ data: 'invoiceNumber', name: 'invoiceNumber' },
                    { data: 'salesReturnAmount', name: 'salesReturnAmount'},
                    { data: 'actions', name: 'Actions' }
                ],
                pageLength: 100,
            };

            var dtObject = $('.datatable-salesOrder').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });

            @if (isset($_GET['bookSerial']) && strlen($_GET['bookSerial']))
            dtObject.columns(7).search('{{$_GET['bookSerial']}}').draw();
            @endif
        });
    </script>
@stop
