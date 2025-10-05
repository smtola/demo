<div id="receipt-content" class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-lg font-[Khmer OS Battambang] text-gray-800">
    
    {{-- Header --}}
    <div class="text-center border-b pb-4 mb-4">
        <h2 class="text-2xl font-bold text-gray-900">🧾 វិក្កយបត្រ (Invoice)</h2>
        <p class="text-sm text-gray-600">Ref: {{ $sale->reference }}</p>
        <p class="text-sm text-gray-600">ថ្ងៃខែឆ្នាំ: {{ $sale->sale_date }}</p>
        <p class="text-sm text-gray-600">អតិថិជន: {{ $sale->customer_info ?? 'N/A' }}</p>
    </div>

    {{-- Products Table --}}
    <div class="overflow-hidden border rounded-lg">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="py-2 px-3 text-left">ផលិតផល</th>
                    <th class="py-2 px-3 text-center">ចំនួន</th>
                    <th class="py-2 px-3 text-right">តម្លៃ</th>
                    <th class="py-2 px-3 text-right">សរុប</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-2 px-3">{{ $item->product->name }}</td>
                        <td class="py-2 px-3 text-center">{{ $item->quantity }}</td>
                        <td class="py-2 px-3 text-right">${{ number_format($item->price, 2) }}</td>
                        <td class="py-2 px-3 text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Summary --}}
    <div class="mt-4 space-y-1 text-sm">
        <div class="flex justify-between">
            <span>Subtotal:</span>
            <span>${{ number_format($sale->subtotal, 2) }}</span>
        </div>
        <div class="flex justify-between">
            <span>Discount:</span>
            <span class="text-red-600">-${{ number_format($sale->discount, 2) }}</span>
        </div>
        <div class="flex justify-between">
            <span>Tax:</span>
            <span>${{ number_format($sale->tax, 2) }}</span>
        </div>
        <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
            <span>Total:</span>
            <span class="text-green-600">${{ number_format($sale->total_amount, 2) }}</span>
        </div>
    </div>

    {{-- Payment & Footer --}}
    <div class="mt-4 text-center text-sm text-gray-600 border-t pt-3">
        <p>វិធីបង់ប្រាក់: <span class="font-semibold">{{ $sale->payment_method }}</span></p>
        <p class="mt-1">🙏 សូមអរគុណចំពោះការទិញទំនិញរបស់អ្នក</p>
    </div>

</div>
