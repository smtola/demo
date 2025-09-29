<div id="receipt-content" style="font-family: 'Khmer OS Battambang', sans-serif;">
    <h2 style="text-align: center;">វិក្កយបត្រ (Invoice)</h2>
    <p>Ref: {{ $sale->reference }}</p>
    <p>ថ្ងៃខែឆ្នាំ: {{ $sale->sale_date }}</p>
    <p>អតិថិជន: {{ $sale->customer_info ?? 'N/A' }}</p>
    <hr>

    <table width="100%" border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th>ផលិតផល</th>
                <th>ចំនួន</th>
                <th>តម្លៃ</th>
                <th>សរុប</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <p>Subtotal: ${{ number_format($sale->subtotal, 2) }}</p>
    <p>Discount: ${{ number_format($sale->discount, 2) }}</p>
    <p>Tax: ${{ number_format($sale->tax, 2) }}</p>
    <h3>Total: ${{ number_format($sale->total_amount, 2) }}</h3>
    <p>វិធីបង់ប្រាក់: {{ $sale->payment_method }}</p>
</div>
