<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;

class PrintReceipt extends Component
{
    public $saleId;

    public function mount($saleId)
    {
        $this->saleId = $saleId;
    }

    // --------------------
    // Print receipt
    // --------------------
    public function printReceipt(): void
    {
        $sale = Sale::with('items.product')->findOrFail($this->saleId);
        $receiptHtml = view('exports.receipt', compact('sale'))->render();

        // ✅ Livewire v3 way
        $this->dispatch('print-receipt', html: $receiptHtml);
    }

    public function render()
    {
        $sale = Sale::with('items.product')->find($this->saleId);

        return view('livewire.print-receipt', [
            'sale' => $sale,
        ]);
    }
}
