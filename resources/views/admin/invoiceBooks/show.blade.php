@extends('adminlte::page')

@section('title', 'Invoice Book')

@section('content_header')
    <h1>Invoice Book</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-invoice-dollar"></i> {{ $invoiceBook->bookType }}-{{ $invoiceBook->bookNumber }}-( {{ $invoiceBook->startPage }} to {{ $invoiceBook->endPage }} )
            </h3>
            @can('invoice_books_update')
            <a class="btn btn-primary btn-sm float-right" href="{{route('invoiceBooks.edit',$invoiceBook->invoiceBookID)}}">
                <i class="fas fa-edit"></i> Edit Invoice Book
            </a>
            @endcan
        </div>
        <div class="card-body">
			<table class="table table-hover" id="myTable">
				<thead>
					<tr>
                        <th>#</th>
						<th>Serial #</th>
						<th>Reason</th>
                        <th>Action</th>
					</tr>
				</thead>
                <tbody>
                    @if(!$invoiceBook->serials->isEmpty())
                        @foreach ($invoiceBook->serials as $serial)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $serial->serialNumber }}</td>
                            <td>{{ $serial->reason }}</td>
                            <td>
                                Edit / Delete
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Record Found</td>
                        </tr>
                    @endif
				</tbody>
			</table>
		</div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
