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
			<table class="table table-hover table-sm">
                <thead>
                    <tr class="table-info">
                        <th>#</th>
                        <th>Book Type</th>
                        <th>Book Number</th>
                        <th>Start Page</th>
                        <th>End Page</th>
						<th>Invalidated</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$books->isEmpty())
                        @foreach ($books as $book)
    						<tr>
    							<td>{{ $loop->iteration }}</td>
    							<td>{{ $book->bookType }}</td>
                                <td>{{ $book->bookNumber }}</td>
                                <td>{{ $book->startPage }}</td>
                                <td>{{ $book->endPage }}</td>
                                <td>{{ $book->serials->count() }}</td>
    						</tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No Invoice Book Added</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app_.css">
@stop
