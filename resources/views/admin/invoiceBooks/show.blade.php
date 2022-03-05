@extends('adminlte::page')

@section('title', 'Book Serials Voids')

@section('content_header')
    <h1>Book Serials Voids</h1>
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
            <form class="form-horizontal" action="{{ route('invoiceBooks.updateBookSerials') }}" method="POST">
                <input type="hidden" name="invoiceBookID" value="{{$invoiceBook->invoiceBookID}}" />
                @csrf
    			<table class="table table-hover" id="myTable">
    				<thead>
    					<tr>
                            <th width="10%">Serial #</th>
    						<th>Reason</th>
                            <th width="5%">Action</th>
    					</tr>
    				</thead>
                    <tbody>
                        @if(!$invoiceBook->serials->isEmpty())
                            @foreach ($invoiceBook->serials as $serial)
                            <tr>
                                <td>
                                    <input type="number" name="serialNumber[]" min="1" value="{{ $serial->serialNumber }}" class="form-control" required />
                                </td>
                                <td><input type="text" name="reason[]" value="{{ $serial->reason }}" class="form-control" required></td>
                                <td>
                                    <button type="button" class="btn btn-danger removeSerialRow"><i class="nav-icon fas fa-fw fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
    				</tbody>
                    <tfoot>
                        <tr id="actionRow">
                            <td colspan="2"></td>
                            <td>
                                <button class="btn btn-primary btn-sm pull-right " onclick="addSerialRow()" type="button" title="Add New Item">
                                    <i class="nav-icon fas fa-fw fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
    			</table>
                <div>
					<input class="btn btn-primary btn-block" type="submit" value="Save">
				</div>
            </form>
		</div>
    </div>

    <div id="serial-row" style="display:none;">
		<div class="form-group">
			<div class="col-sm-12">
				<input type="number" name="serialNumber[]" min="1" value="" class="form-control" required />
			</div>
		</div>
		<span class="separator"></span>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="text" name="reason[]" value="" class="form-control" required>
			</div>
		</div>
		<span class="separator"></span>
		<button class="btn btn-danger btn-sm pull-right removeSerialRow" type="button" title="Delete Item">
			<i class="nav-icon fas fa-fw fa-trash"></i>
		</button>
	</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
	<script type="text/javascript">
		$(function() {
            addSerialRow();
			bindRemoveClick();
		});

		function bindRemoveClick() {
			$('button.removeSerialRow').bind('click', function() {
				$(this).parent().parent().remove();
			});
		}

		function addSerialRow() {
			var strSerialRowHTML = $('#serial-row').html();
			strSerialRowHTML = strSerialRowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
			$('table#myTable tbody').append('<tr><td>' + strSerialRowHTML + '</td></tr>');
			bindRemoveClick();
		}
	</script>
@stop
