<!DOCTYPE html>
<html>

<head>
    <title>Order Drink Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        h2 {
            text-align: center;
            color: #2d3748;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <h2>Order Drink Report</h2>
    <p style="text-align:center">Periode: {{ $start_date }} - {{ $end_date }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Order Date</th>
                <th>User</th>
                <th>Beverages</th>
                <th>Total Price</th>
            </tr>
        </thead>
        @php $grandTotal = 0; @endphp
        @foreach ($orders as $index => $order)
        @php
        $orderTotal = $order->orderdetails->sum('subtotal');
        $grandTotal += $orderTotal;
        @endphp

        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $order->created_at->format('Y-m-d') }}</td>
            <td>{{ $order->reservation->user->name ?? '-' }}</td>
            <td>
                <ul style="padding-left: 16px; margin: 0;">
                    @foreach ($order->orderdetails as $detail)
                    <li>{{ $detail->beverage->name }} (x{{ $detail->quantity }}) - Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</li>
                    @endforeach
                </ul>
            </td>
            <td>Rp{{ number_format($orderTotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>
    <p style="text-align: right; font-weight: bold; margin-top: 10px;">
        Grand Total: Rp{{ number_format($grandTotal, 0, ',', '.') }}
    </p>
</body>

</html>