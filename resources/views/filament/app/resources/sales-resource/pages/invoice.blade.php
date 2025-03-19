
<x-filament-panels::page>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .invoice-container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        .user-info {
            font-size: 14px;
            text-align: right;
        }
        .invoice-details {
            margin-top: 20px;
            font-size: 16px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">POS</div>
            <div class="user-info">
                <strong>Sold By:</strong> Cashier-{{ $invoice->user->id }} <br>
                <strong>Invoice #:</strong> {{ $invoice->invoice_number }} <br>
                <strong>Date:</strong> {{ $invoice->created_at->format('d M Y, H:i A') }}
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <strong>Items Purchased:</strong>
        </div>

        <!-- Sale Items Table -->
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->saleItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price_at_sale, 2) }}</td>
                    <td>${{ number_format($item->quantity * $item->price_at_sale, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Price -->
        <div class="total">
            Grand Total: ${{ number_format($invoice->final_amount, 2) }}
        </div>

        <!-- Footer -->
        <div class="footer">
            Thank you for shopping with us!
        </div>
    </div>

</body>
</html>
</x-filament-panels::page>


