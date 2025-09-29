<div class="container py-4">
    <h1 class="text-xl font-semibold">Purchase Receive</h1>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-12 gap-4">
        <section class="md:col-span-7 p-4 rounded-xl border bg-white dark:bg-gray-900">
            <div class="flex gap-2">
                <input wire:model.defer="search" wire:keydown.enter.prevent="searchProducts" type="text" placeholder="Scan barcode or search products" class="w-full rounded-lg border px-3 py-2 dark:bg-gray-100">
                <button wire:click="searchProducts" class="px-4 py-2 rounded-lg bg-primary-600 text-white cursor-pointer">Add</button>
            </div>

            <div class="mt-4 max-h-[50vh] overflow-auto">
                <table class="w-full text-sm">
                    <thead class="text-left text-gray-500 dark:text-gray-100">
                        <tr>
                            <th class="py-2">Item</th>
                            <th class="py-2 w-24">Qty</th>
                            <th class="py-2 w-28">Cost</th>
                            <th class="py-2 w-28">Subtotal</th>
                            <th class="py-2 w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lines as $index => $l)
                            <tr class="border-t dark:text-gray-100">
                                <td class="py-2">
                                    <div class="font-medium">{{ $l['name'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-100">{{ $l['sku'] }}</div>
                                </td>
                                <td>
                                    <input type="number" class="w-20 rounded border px-2 py-1" min="1" wire:change="updateQty({{ $index }}, $event.target.value)" value="{{ $l['qty'] }}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="w-24 rounded border px-2 py-1" wire:change="updateCost({{ $index }}, $event.target.value)" value="{{ $l['cost'] }}">
                                </td>
                                <td><span>{{ number_format($l['qty'] * $l['cost'], 2) }}</span></td>
                                <td>
                                    <button wire:click="remove({{ $index }})" class="text-red-600 hover:underline">Remove</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="md:col-span-5 p-4 rounded-xl border bg-white space-y-4">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span>Subtotal</span><span>{{ number_format($subtotal, 2) }}</span></div>
            </div>

            <div class="space-y-2">
                <label class="text-sm">Supplier</label>
                <input type="text" class="w-full rounded border px-3 py-2" placeholder="Supplier name" wire:model.defer="supplierName">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <button wire:click="clear" class="px-4 py-2 rounded-lg border">Clear</button>
                <button wire:click="receive" class="px-4 py-2 rounded-lg bg-emerald-600 text-white">Receive</button>
            </div>
        </aside>
    </div>
</div>


