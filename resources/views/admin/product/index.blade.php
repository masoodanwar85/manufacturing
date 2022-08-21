@extends('adminlte::page')

@section('title', 'Products')

@section('content_header')
    <h1>Products</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> Products
			</h3>
			@can('product_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('product.create') }}">
				<i class="fas fa-plus-circle"></i> Add Product
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Product">
				<thead>
					<tr>
						<th>Name</th>
						<th>Category</th>
						<th>Unit</th>
						<th>Sale Price</th>
						<th>Purchase Price</th>
						<th>Alert Quantity</th>
						<th>Date Created</th>
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
                ajax: "{{ route('product.index') }}",
                columns: [
                    { data: 'productName', name: 'productName' },
                    { data: 'categoryName', name: 'categoryName' },
                    { data: 'maximumUnitSymbol', name: 'maximumUnitSymbol' },
                    { data: 'unitSalePrice', name: 'unitSalePrice' },
					{ data: 'unitPurchasePrice', name: 'unitPurchasePrice' },
					{ data: 'thresholdUnit', name: 'thresholdUnit' },
                    { data: 'dateCreated', name: 'dateCreated' },
                    { data: 'actions', name: 'Actions' }
                ],
                order: [[ 1, 'desc' ]],
                pageLength: 100,
            };

            $('.datatable-Product').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
