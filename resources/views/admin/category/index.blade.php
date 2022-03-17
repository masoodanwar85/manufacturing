@extends('adminlte::page')
@section('title', 'Product Categories')

@section('content_header')
    <h1>Product Categories</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-user"></i> Product Categories
			</h3>
			@can('product_category_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('category.create') }}">
				<i class="fas fa-plus-circle"></i> Add Category
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Category">
				<thead>
					<tr>
						<th>Name</th>
						<th>No. of Products</th>
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
                ajax: "{{ route('category.index') }}",
                columns: [
                    { data: 'categoryName', name: 'categoryName' },
                    { data: 'products_count', name: 'products_count' },
                    { data: 'dateCreated', name: 'dateCreated' },
                    { data: 'actions', name: 'Actions' }
                ],
                order: [[ 1, 'desc' ]],
                pageLength: 100,
            };

            $('.datatable-Category').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
