<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Sticker</title>
    <style>
        @page {
            size: 4in 6in;
            margin: 0.2in;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        .sticker {
            width: 100%;
            max-width: 3.6in;
            border: 2px solid #000;
            padding: 8px;
            box-sizing: border-box;
            background: white;
        }
        
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
        }
        
        .field {
            margin-bottom: 4px;
            display: flex;
        }
        
        .field-label {
            font-weight: bold;
            width: 80px;
            flex-shrink: 0;
        }
        
        .field-value {
            flex: 1;
            word-wrap: break-word;
        }
        
        .products {
            margin-top: 8px;
            border-top: 1px solid #000;
            padding-top: 4px;
        }
        
        .product-item {
            margin-bottom: 2px;
            font-size: 11px;
        }
        
        .total {
            margin-top: 8px;
            border-top: 1px solid #000;
            padding-top: 4px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        
        .footer {
            margin-top: 8px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 4px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .sticker {
                margin: 0;
                border: 2px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="sticker">
        <div class="header">
            SHIPPING LABEL
        </div>
        
        <div class="field">
            <div class="field-label">Customer:</div>
            <div class="field-value">{{ $customerName }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Phone:</div>
            <div class="field-value">{{ $customerPhone }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">From:</div>
            <div class="field-value">{{ $senderNumber }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Location:</div>
            <div class="field-value">{{ $location }}</div>
        </div>
        
        <div class="products">
            <div class="field-label" style="font-weight: bold; margin-bottom: 4px;">Products:</div>
            @if($sale && $sale->items)
                @foreach($sale->items as $item)
                    <div class="product-item">
                        {{ $item->product->name ?? 'N/A' }} x{{ $item->quantity }} - ${{ number_format($item->subtotal, 2) }}
                    </div>
                @endforeach
            @else
                <div class="product-item">{{ $products }}</div>
            @endif
        </div>
        
        <div class="total">
            Total: {{ $totalAmount }}
        </div>
        
        <div class="footer">
            Sale #{{ $sale->id ?? 'N/A' }} | {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
