@extends('adminlte::print')

@section('title','Delivery Report')

@section('content')
<div class="delivery-report">
    <div class="report-item">
        <h2>Delivery Details</h2>
        <table cellspacing="5" cellpadding="5" width="100%">
            <tr>
                <th>Delivery Date</th>
                <th>Route</th>
                <th>Godown</th>
                <th>Transport</th>
            </tr>
            <tr>
                <td>{{$delivery->deliveryDate}}</td>
                <td>{{$delivery->route->route}}</td>
                <td>{{$delivery->godown->name}}</td>
                <td>{{$delivery->transport->name}}</td>
            </tr>
        </table>
    </div>
</div>

<div class="delivery-report">
    <div class="report-item">
        <table cellspacing="5" cellpadding="5" width="100%">
            <tr>
                <td width="45%" valign="top">
                    <h2>Products</h2>
                    <table cellspacing="5" cellpadding="5" width="100%">
                        <tr>
                            <th>Product</th>
                            <th>Quantity Units</th>
                        </tr>
                        @php
                            $previousProduct = null;
                            $totalQuantity = 0;
                        @endphp
                        @foreach($productsQuantities as $productQty)
                            <tr>
                                <td>{{ $productQty['product'] }}</td>
                                <td>{{ $productQty['qty'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
                <td width="45%" valign="top">
                    <h2>Invoices</h2>
                    <table cellspacing="5" cellpadding="5" width="100%">
                        <tr>
                            <th>Invoice Number</th>
                            <th>Customer</th>
                        </tr>
                        @foreach($delivery->salesOrders as $s)
                            <tr>
                                <td>{{$s->invoiceNumber}}</td>
                                <td>{{$s->customer->shopName}}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
@stop