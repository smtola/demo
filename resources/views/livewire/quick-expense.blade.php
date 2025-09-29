<div class="container py-4">
    <h1 class="text-xl font-semibold">Quick Expense</h1>

    <div class="mt-4 space-y-3 max-w-md">
        <div>
            <label class="text-sm">Title</label>
            <input type="text" class="w-full rounded border px-3 py-2" wire:model.defer="title">
            @error('title') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="text-sm">Amount</label>
            <input type="number" step="0.01" class="w-full rounded border px-3 py-2" wire:model.defer="amount">
            @error('amount') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="text-sm">Note</label>
            <textarea class="w-full rounded border px-3 py-2" rows="3" wire:model.defer="note"></textarea>
        </div>
        <div>
            <button wire:click="save" class="px-4 py-2 rounded-lg bg-primary-600 text-white">Save Expense</button>
        </div>
    </div>
</div>


