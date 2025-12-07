<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $sale->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
            color: #333;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .invoice-header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #666;
        }
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .invoice-info-left,
        .invoice-info-right {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }
        .invoice-info-right {
            text-align: right;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-section p {
            margin: 5px 0;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .items-table {
            margin-bottom: 30px;
        }
        .items-table td {
            text-align: left;
        }
        .items-table td:nth-child(3),
        .items-table td:nth-child(4),
        .items-table td:nth-child(5) {
            text-align: right;
        }
        .totals-section {
            margin-top: 20px;
        }
        .totals-table {
            width: 100%;
            max-width: 400px;
            margin-left: auto;
        }
        .totals-table td {
            padding: 8px;
        }
        .totals-table td:first-child {
            font-weight: bold;
            text-align: right;
            width: 60%;
        }
        .totals-table td:last-child {
            text-align: right;
            width: 40%;
        }
        .total-row {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #000;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>INVOICE</h1>
        <h2>Invoice #{{ $sale->invoice_number }}</h2>
    </div>

    <div class="invoice-info">
        <div class="invoice-info-left">
            <div class="info-section">
                <h3>Client Information</h3>
                <p><strong>Name:</strong> {{ optional($sale->client)->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ optional($sale->client)->email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ optional($sale->client)->phone ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ optional($sale->client)->address ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="invoice-info-right">
            <div class="info-section">
                <h3>Sale Information</h3>
                <p><strong>Date:</strong> {{ $sale->created_at->format('Y-m-d H:i:s') }}</p>
                <p><strong>Payment Type:</strong> {{ $sale->payment_type->label() }}</p>
                <p><strong>Warehouse:</strong> {{ optional($sale->warehouse)->name ?? 'N/A' }}</p>
                <p><strong>Payment Type:</strong> {{ optional($sale->safe)->name ?? 'N/A' }}</p>
                <p><strong>User:</strong> {{ optional(auth()->user())->full_name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="items-section">
        <h3>Items</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 35%">Item Name</th>
                    <th style="width: 15%">Unit Price</th>
                    <th style="width: 10%">Quantity</th>
                    <th style="width: 15%">Total Price</th>
                    <th style="width: 20%">Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sale->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">{{ number_format($item->pivot->unit_price, 2) }}</td>
                        <td class="text-right">{{ $item->pivot->quantity }}</td>
                        <td class="text-right">{{ number_format($item->pivot->total_price, 2) }}</td>
                        <td>{{ $item->pivot->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No items found for this sale.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td>{{ number_format($sale->total, 2) }}</td>
            </tr>
            @if($sale->discount > 0)
            <tr>
                <td>Discount ({{ $sale->discount_type->label() }}):</td>
                <td>-{{ number_format($sale->discount, 2) }}</td>
            </tr>
            @endif
            @if($sale->shipping_cost > 0)
            <tr>
                <td>Shipping Cost:</td>
                <td>{{ number_format($sale->shipping_cost, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Net Amount:</td>
                <td>{{ number_format($sale->net_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Paid Amount:</td>
                <td>{{ number_format($sale->paid_amount, 2) }}</td>
            </tr>
            @if($sale->remaining_amount > 0)
            <tr>
                <td>Remaining Amount:</td>
                <td>{{ number_format($sale->remaining_amount, 2) }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>

