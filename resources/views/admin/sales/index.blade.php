@extends('adminlte::page')
@section('title', 'Sale Orders')

@section('content_header')
    <h1>Sale Orders</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-share"></i> Sale Orders
			</h3>
			@can('sales_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('sales.create') }}">
				<i class="fas fa-plus-circle"></i> Add Sales Order
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-salesOrder">
				<thead>
					<tr>
						<th>Customer</th>
						<th>Order Date</th>
						<th>Total Amount</th>
						<th>Paid Amount</th>
						<th>Remaining</th>
                        <th>Due Date</th>
						<th>Invoice #</th>
                        <th>Book Serial #</th>
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
                ajax: "{{ route('sales.index') }}",
                columns: [
					{ data: 'customerName', name: 'customerName' },
                    { data: 'orderDate', name: 'orderDate' },
                    { data: 'totalAmount', name: 'totalAmount' },
					{ data: 'totalPaid', name: 'totalPaid' },
					{ data: 'remaining', name: 'remaining' },
                    { data: 'paymentDueDate', name: 'paymentDueDate' },
					{ data: 'invoiceNumber', name: 'invoiceNumber' },
                    { data: 'bookSerial', name: 'bookSerial' },
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
