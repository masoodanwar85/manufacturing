@extends('adminlte::page')

@section('title', 'Book Serial')

@section('content_header')
    <h1>Book Serial</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-balance-scale"></i> Book Serial
            </h3>
        </div>
        <div class="card-body">
			<form class="form-horizontal" action="{{ route('accountHead.updateBookSerials') }}" method="POST">
                @if (!empty($bookInfo))
                    <input type="hidden" name="bookType" value="{{ $bookInfo['bookType'] }}" />
                    <input type="hidden" name="bookNumber" value="{{ $bookInfo['bookNumber'] }}" />
                    <h3 class="text-center">Book Serial: {{ $bookInfo['bookType'] }}-{{ $bookInfo['bookNumber'] }}</h3>
                @else

                    <div class="form-group row">
                        <label for="bookType" class="col-sm-2 col-form-label">Book Type: *</label>
                        <div class="col-sm-10">
                            <select name="bookType" class="form-control">
                                <option value="BB">Bill Book</option>
                                <option value="CB">Cash Book</option>
                                <option value="RB">Receiving Book</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="bookNumber" class="col-sm-2 col-form-label">Book Serial: *</label>
                        <div class="col-sm-10">
                            <input type="number" name="bookNumber" class="form-control" min="1" />
                        </div>
                    </div>
                @endif


				@csrf
				<table class="table" id="myTable">
					<thead>
						<tr class="text-center">
							<th style="text-align:center;width:15%;">Serial</th>
                            <th style="text-align:center;width:75%;">Reason</th>
							<th style="text-align:center;width:5%;">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($bookSerial as $subSerial)
							<tr>
								<td>
                                    <div class="form-group">
                            			<div class="col-sm-12">
                                            <input type="number" name="serialNumber[]" value="{{ $subSerial->serialNumber }}" min="1" class="form-control" required />
                                        </div>
                                    </div>
								</td>
                                <td>
                                    <div class="form-group">
                            			<div class="col-sm-12">
                                            <input type="text" name="reason[]" value="{{ $subSerial->reason }}" class="form-control" required />
                                        </div>
                                    </div>
                                </td>
								<td>
									<button class="btn btn-danger btn-sm pull-right removeSerialRow" type="button" title="Delete Item">
										<i class="nav-icon fas fa-fw fa-trash"></i>
									</button>
								</td>
							</tr>
						@endforeach
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
					<input class="btn btn-primary" type="submit" value="Save">
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


@section('js')
	<script type="text/javascript">
		$(function() {
			@if ($isNew == 1)
			addSerialRow();
			@endif
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
