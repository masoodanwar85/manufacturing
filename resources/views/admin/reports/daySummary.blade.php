@extends('adminlte::page')

@section('title', 'Duplicates Report')

@section('content_header')
    <h1>Day Summary</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i> Day Summary
            </h3>
        </div>
        <div class="card-body">
            <form>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">Date:</label>
                        <input type="date" class="form-control" name="orderDate" value="{{ $orderDate }}" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">Sales Agent:</label>
                        <select name="salesAgentID" class="form-control">
                            <option value="">Please Select Sales Agent</option>
                            @foreach ($salesAgents as $salesAgent)
                                <option value="{{ $salesAgent->staffID }}" {!! $salesAgent->staffID == $salesAgentID ? 'selected' : '' !!}>{{ $salesAgent->staffName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <button style="margin-top:30px;" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>

            <h4>Sales Agent: {{ $salesAgentName }}</h4>
            <h4>Date: {{ $orderDate }}</h4>
            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr>
                        <th>Customers</th>
                        @foreach ($aryProducts as $product)
                            <td>{{ $product['productName'] }}</td>
                        @endforeach
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $grandTotal = 0;
                    ?>
                    @foreach ($finalOrders as $order)
                        <tr>
                            <th>{{ $order['customerName'] }}</th>
                            @foreach ($order['products'] as $key => $orderProduct)
                                <td>{{ $orderProduct['quantity'] }}</td>
                                <?php
                                    $aryProducts[$key]['sum'] += $orderProduct['quantity'];
                                ?>
                            @endforeach
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
                        <th>{{ $grandTotal }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@stop
