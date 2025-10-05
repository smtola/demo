<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
        }
        
        td {
            font-size: 10px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .status-paid {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        
        .status-failed {
            color: #dc3545;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .summary-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i A') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>User</th>
                <th>Reference</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Total</th>
                <th>Payment Method</th>
                <th class="text-center">Status</th>
                <th>Sale Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                    <td>{{ $sale->user->name ?? 'N/A' }}</td>
                    <td>{{ $sale->reference ?? 'N/A' }}</td>
                    <td class="text-right">${{ number_format($sale->subtotal ?? 0, 2) }}</td>
                    <td class="text-right">${{ number_format($sale->discount ?? 0, 2) }}</td>
                    <td class="text-right">${{ number_format($sale->tax ?? 0, 2) }}</td>
                    <td class="text-right">${{ number_format($sale->total_amount, 2) }}</td>
                    <td>{{ $sale->payment_method ?? 'N/A' }}</td>
                    <td class="text-center">
                        <span class="status-{{ $sale->status }}">
                            {{ ucfirst($sale->status) }}
                        </span>
                    </td>
                    <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-row">
            <span>Total Sales:</span>
            <span>{{ $sales->count() }}</span>
        </div>
        <div class="summary-row">
            <span>Total Amount:</span>
            <span>${{ number_format($sales->sum('total_amount'), 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Average Sale:</span>
            <span>${{ number_format($sales->avg('total_amount'), 2) }}</span>
        </div>
        <div class="summary-row summary-total">
            <span>Paid Sales:</span>
            <span>{{ $sales->where('status', 'paid')->count() }} (${{ number_format($sales->where('status', 'paid')->sum('total_amount'), 2) }})</span>
        </div>
        <div class="summary-row">
            <span>Pending Sales:</span>
            <span>{{ $sales->where('status', 'pending')->count() }} (${{ number_format($sales->where('status', 'pending')->sum('total_amount'), 2) }})</span>
        </div>
        <div class="summary-row">
            <span>Failed Sales:</span>
            <span>{{ $sales->where('status', 'failed')->count() }} (${{ number_format($sales->where('status', 'failed')->sum('total_amount'), 2) }})</span>
        </div>
    </div>
    
    <div class="footer">
        <p>This report was generated automatically by the Book SMS System</p>
    </div>
</body>
</html>
