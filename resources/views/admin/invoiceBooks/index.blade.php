@extends('adminlte::page')

@section('title', 'Invoice Books')

@section('content_header')
    <h1>Invoice Books</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i> Invoice Books
            </h3>
            @can('invoice_books_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('invoiceBooks.create') }}">
				<i class="fas fa-plus-circle"></i> Add Invoice Book
			</a>
			@endcan
        </div>
        <div class="card-body">
			<table class="table table-hover table-sm dt-invoice-books">
                <thead>
                    <tr class="table-info">
                        <th>Book Type</th>
                        <th>Book Number</th>
                        <th>Start Page</th>
                        <th>End Page</th>
						<th>Invalidated</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app_.css">
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
                ajax: "{{ route('invoiceBooks.index') }}",
                columns: [
                    { data: 'bookType', name: 'bookType' },
					{ data: 'bookNumber', name: 'bookNumber' },
                    { data: 'bookStartPage', name: 'bookStartPage' },
					{ data: 'bookEndPage', name: 'bookEndPage' },
					{ data: 'invalidatedSerials', name: 'invalidatedSerials' },
                    { data: 'dateCreated', name: 'dateCreated' },
                    { data: 'actions', name: 'Actions' }
                ],
                pageLength: 100,
            };

            $('.dt-invoice-books').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
