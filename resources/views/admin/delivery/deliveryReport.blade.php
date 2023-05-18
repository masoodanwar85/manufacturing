<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice and Delivery Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .delivery-report {
            margin-top: 40px;
        }

        .report-item {
            margin-bottom: 20px;
        }

        .report-item h2 {
            margin-bottom: 10px;
        }

        .report-item table {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<h1>Invoice and Delivery Report</h1>


<div class="delivery-report">
    <div class="report-item">
        <h2>Delivery Details</h2>
        <table>
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
        <table>
            <tr>
                <td>
                    <h2>Product</h2>
                    <table>
                        <tr>
                            <th>Product</th>
                            <th>Quantity Units</th>
                        </tr>
                        @php
                            $totalQuantity = 0;
                        @endphp
                        @foreach($delivery->salesOrders as $s)
                            @foreach($s->stockDetailStatuses as $stockDetailStatus)
                                @php
                                 $totalQuantity += $stockDetailStatus->quantity;
                                @endphp
                            @endforeach
                        @endforeach
                        <tr>
                            <td>{{$delivery->salesOrders[0]->stockDetailStatuses[0]->stockDetail->product->productName}}</td>
                            <td>{{$totalQuantity}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <h2>Invoice</h2>
                    <table>
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
</body>
</html>