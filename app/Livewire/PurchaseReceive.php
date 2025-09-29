<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseReceive extends Component
{
    public string $supplierName = '';
    public string $search = '';
    /** @var array<int, array{id:int,sku:string,name:string,cost:float,qty:int}> */
    public array $lines = [];

    public float $subtotal = 0.0;

    public function searchProducts(): void
    {
        $term = trim($this->search);
        if ($term === '') return;

        $query = Product::query()->select(['id','name','sku','unit_price as cost','barcode']);
        if (preg_match('/^\d{6,}$/', $term)) {
            $query->where('barcode', $term);
        } else {
            $query->where(function ($w) use ($term) {
                $w->where('name', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%")
                  ->orWhere('barcode', 'like', "%{$term}%");
            });
        }

        if ($p = $query->first()) {
            $this->addLine((int) $p->id, (string) $p->sku, (string) $p->name, (float) $p->cost ?: 0.0);
        }
    }

    public function addLine(int $id, string $sku, string $name, float $cost): void
    {
        foreach ($this->lines as &$l) {
            if ($l['id'] === $id) {
                $l['qty'] += 1;
                $this->recalc();
                return;
            }
        }
        $this->lines[] = [ 'id'=>$id, 'sku'=>$sku, 'name'=>$name, 'cost'=>$cost, 'qty'=>1 ];
        $this->recalc();
    }

    public function updateQty(int $index, int $qty): void
    {
        if (! array_key_exists($index, $this->lines)) return;
        $this->lines[$index]['qty'] = max(1, $qty);
        $this->recalc();
    }

    public function updateCost(int $index, float $cost): void
    {
        if (! array_key_exists($index, $this->lines)) return;
        $this->lines[$index]['cost'] = max(0, $cost);
        $this->recalc();
    }

    public function remove(int $index): void
    {
        if (! array_key_exists($index, $this->lines)) return;
        array_splice($this->lines, $index, 1);
        $this->recalc();
    }

    public function clear(): void
    {
        $this->lines = [];
        $this->subtotal = 0.0;
    }

    private function recalc(): void
    {
        $this->subtotal = collect($this->lines)->reduce(fn($s, $l) => $s + ((float)$l['cost'] * (int)$l['qty']), 0.0);
    }

    public function receive(): void
    {
        if (empty($this->lines)) {
            $this->dispatch('toast', message: 'No lines to receive');
            return;
        }

        DB::beginTransaction();
        try {
            $supplierId = null;
            $name = trim($this->supplierName);
            if ($name !== '') {
                $supplier = Supplier::firstOrCreate(['name' => $name]);
                $supplierId = $supplier->id;
            }

            $purchase = Purchase::create([
                'supplier_id' => $supplierId,
                'user_id' => Auth::id(),
                'reference' => 'P' . now()->format('YmdHis'),
                'total_amount' => 0,
                'status' => 'received',
                'purchase_date' => now(),
            ]);

            $total = 0.0;

            foreach ($this->lines as $l) {
                $qty = (int) $l['qty'];
                $cost = (float) $l['cost'];
                $lineTotal = $qty * $cost;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $l['id'],
                    'quantity' => $qty,
                    'cost_price' => $cost,
                    'subtotal' => $lineTotal,
                ]);

                // Stock in
                StockMovement::create([
                    'product_id' => $l['id'],
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity' => $qty,
                    'cost_price' => $cost,
                    'selling_price' => null,
                    'warehouse_id' => Product::find($l['id'])->warehouse_id,
                    'movement_date' => now(),
                    'note' => 'Purchase ' . $purchase->reference,
                ]);

                $total += $lineTotal;
            }

            $purchase->update(['total_amount' => $total]);

            DB::commit();
            $this->dispatch('toast', message: 'Purchase received');
            $this->clear();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('toast', message: 'Receive failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.purchase-receive');
    }
}


