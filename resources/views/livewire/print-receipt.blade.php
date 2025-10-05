<div class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 text-center space-y-4">

        {{-- Success Icon --}}
        <div class="flex justify-center">
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-green-100">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        {{-- Title --}}
        <h1 class="text-2xl font-bold text-gray-800">Payment Successful 🎉</h1>

        {{-- Transaction Info --}}
        <div class="text-gray-600 space-y-1 ">
            <p><span class="font-semibold">Transaction:</span> {{ $sale->reference }}</p>
            <p><span class="font-semibold">Status:</span> 
                <span class="px-2 py-1 text-xs font-medium rounded-full 
                    {{ $sale->status === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                    {{ ucfirst($sale->status) }}
                </span>
            </p>
            <p><span class="font-semibold">Total:</span> ${{ number_format($sale->total_amount, 2) }}</p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-center gap-3 pt-4">
            <button wire:click="printReceipt"
                class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 shadow transition">
                🖨 Print Receipt
            </button>

            <a href="{{ url('/pos') }}"
                class="px-5 py-2 rounded-lg bg-gray-200 dark:bg-gray-400 text-gray-700 hover:bg-gray-300 dark:hover:hover:bg-gray-600 shadow transition">
                ⬅ Back to POS
            </a>
        </div>
    </div>
</div>


<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('print-receipt', (data) => {
            const win = window.open('', '_blank');
            win.document.write(data.html);
            win.document.close();
            win.print();
        });
    });
</script>

