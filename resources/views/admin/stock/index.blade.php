@extends('adminlte::page')
@section('title', 'Stock')

@section('content_header')
    <h1>Stock</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-cubes"></i> Stock
			</h3>
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-stock">
				<thead>
					<tr>
						<th>Products</th>
						<th>Purchased</th>
						<th>In Stock</th>
						<th>Sold</th>
						@if ($globalSettings['client_settings.is_show_damaged_units'] == 1)
						<th>Damaged</th>
						@endif
						<th>Good Returns</th>
						<th>Bad Returns</th>
                        <th>Sale Price</th>
                        <th>Stock Total Price</th>
{{--						<th>Last Purchase Price</th>--}}
{{--                        <th>Stock Total Price</th>--}}
						<th>Action</th>
					</tr>
				</thead>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-right font-weight-bold">Grand Total:</td>
                        <td class="font-weight-bold" id="stock-grand-total">Rs. </td>
                        <td></td>
                    </tr>
                </tfoot>
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
                ajax: "{{ route('stock.index') }}",
                columns: [
					{ data: 'product', name: 'product' },
                    { data: 'purchased', name: 'purchased' },
                    { data: 'inStock', name: 'inStock' },
					{ data: 'totalSold', name: 'totalSold' },
					@if ($globalSettings['client_settings.is_show_damaged_units'] == 1)
					{ data: 'totalDamaged', name: 'totalDamaged' },
					@endif
					{ data: 'totalGoodSalesReturn', name: 'totalGoodSalesReturn' },
					{ data: 'totalBadSalesReturn', name: 'totalBadSalesReturn' },
					{ data: 'salePrice', name: 'salePrice' },
					// { data: 'lastPurchasePrice', name: 'lastPurchasePrice' },
                    { data: 'totalPriceInStock', name: 'totalPriceInStock' },
                    { data: 'actions', name: 'Actions' }
                ],
                drawCallback: function(settings) {
                    $('#stock-grand-total').text('Rs. ' + settings.json.grandTotal);
                },
                pageLength: {{ $globalSettings['user_settings.records_per_page'] }}
            };

            $('.datatable-stock').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
