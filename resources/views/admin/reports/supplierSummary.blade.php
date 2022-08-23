@extends('adminlte::page')

@section('title', 'Supplier Summary Report')

@section('content_header')
    <h1>Supplier Summary</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i> Supplier Summary
            </h3>
        </div>
        <div class="card-body">
            <form>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">From Date:</label>
                        <input type="date" class="form-control" name="startDate" value="{{ $startDate ? $startDate->toDateString() : '' }}" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">To Date:</label>
                        <input type="date" class="form-control" name="endDate" value="{{ $endDate ? $endDate->toDateString() : '' }}" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">Supplier Name:</label>
                        <select name="supplierID" class="form-control">
                            <option value="">Please Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->supplierID }}" {!! $supplier->supplierID == $supplierID ? 'selected' : '' !!}>{{ $supplier->supplierName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <button style="margin-top:30px;" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>

            <h4>Supplier Agent: {{ $supplierName }}</h4>
            <h4>Date Range: {{ $startDate ? $startDate->toDateString() : '' }} to {{ $endDate ? $endDate->toDateString() : '' }}</h4>
            <table class="table table-bordered table-hover table-sm">
                <thead>
                <tr>
                    <th>Date</th>
                    @foreach ($aryProducts as $product)
                        <td>{{ $product['productName'] }}</td>
                    @endforeach
                    <th>Total Cotton</th>
                    <th>Total Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $grandTotal = 0;
                $grandTotalCotton = 0;
                ?>
                @foreach ($finalOrders as $idx => $order)
                    <?php
                    $dateTotal = 0;
                    ?>
                    <tr>
                        <th>{{ $idx }}</th>
                        @foreach ($order['products'] as $key => $orderProduct)
                            <td>{{ $orderProduct['quantity'] }}</td>
                            <?php
                            $aryProducts[$key]['sum'] += $orderProduct['quantity'];
                            $dateTotal += $orderProduct['quantity'];
                            $grandTotalCotton += $orderProduct['quantity'];
                            ?>
                        @endforeach
                        <th>{{ $dateTotal }}</th>
                        <th>{{ number_format($order['totalAmount']) }}</th>
                        <?php
                        $grandTotal += $order['totalAmount'];
                        ?>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    @foreach ($aryProducts as $product)
                        <th>{{ $product['sum'] }}</th>
                    @endforeach
                    <th>{{ $grandTotalCotton }}</th>
                    <th>{{ $grandTotal }}</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@stop
