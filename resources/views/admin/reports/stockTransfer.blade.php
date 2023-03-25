@extends('adminlte::page')
@section('title', 'Stock Transfer Report')

@section('content_header')
    <h1>Stock Transfer Report</h1>
@stop
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-exchange-alt"></i> Stock Transfer Report
            </h3>
        </div>
        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Customer">
                <thead>
                <tr>
                    <th>Book Serial</th>
                    <th>Transferred Date</th>
                    <th>Transferred From</th>
                    <th>Transferred To</th>
                    <th>Action</td>
                </tr>
                </thead>
                <tbody>
                @foreach($stockTransfers as $transfer)
                    <tr>
                        <td>{{ $transfer->bookSerial }}</td>
                        <td>{{ $transfer->transferDate }}</td>
                        <td>{{ $transfer->prevGodownName }}</td>
                        <td>{{ $transfer->newGodownName }}</td>
                        <td><a class="btn btn-sm btn-primary" href="javascript:void(0);" onclick="fetchStockTransferInfo('{{$transfer->bookSerial}}');">Detail</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Transfer Detail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="prevGodown" class="col-sm-4 col-form-label">Book Serial #:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="bookSerial" readonly value="" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="prevGodown" class="col-sm-4 col-form-label">Transfer Date:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="transferDate" readonly value="" />
                            </div>
                        </div>
						<div class="form-group row">
                            <label for="prevGodown" class="col-sm-4 col-form-label">Previous Godown:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="previousGodown" readonly value="" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="prevGodown" class="col-sm-4 col-form-label">New Godown:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="newGodown" readonly value="" />
                            </div>
                        </div>
                        <table class="table table-stripped" id="productsTransferred">
                            <thead>
                                <tr>
                                    <th>Product</td>
                                    <th class="text-center">Quantity Moved</td>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
            </div>
        </div>
    </div>

@stop


@section('js')
    <script>
        function fetchStockTransferInfo(bookSerial) {
            $.ajax({
                url: '/admin/ajax/getStockTransferDetail',
                type: 'POST',
				data: {
                    bookSerial: bookSerial,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#transferDate').val(response[0].transferDate);
                    $('#bookSerial').val(response[0].bookSerial);
                    $('#newGodown').val(response[0].newGodownName);
                    $('#previousGodown').val(response[0].prevGodownName);

                    var strProductsHTML = '';
                    response.forEach((v,i) => {
                        strProductsHTML+='<tr>';
                        strProductsHTML+=`<td>${v.productName}</td><td align="center">${v.quantity}</td>`;
                        strProductsHTML+='</tr>';
                    });

                    $('#productsTransferred tbody').html(strProductsHTML);

                    $('#transferModal').modal('show');
                }
            });
        }
    </script>
@stop