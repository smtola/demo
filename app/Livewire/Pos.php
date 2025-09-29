<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Services\PayWayServices;
class Pos extends Component
{
    public string $search = '';
    public string $customer = '';
    /** @var array<int, array{id:int,sku:string,name:string,price:float,image_url:string,qty:int}> */
    public array $cart = [];
    public int $productsPerPage = 12;
    public int $currentPage = 1;

    public float $subtotal = 0;
    public float $discount = 0;
    public float $tax = 0;
    public float $total = 0;

    public bool $isAddingToCart = false;
    public bool $isCheckingOut = false;
    public string $alertMessage = '';
    public string $alertType = 'success';

    public bool $showPaymentModal = false;
    public string $selectedPaymentMethod = '';
    public bool $isSelectedPaymentMethod = false;
    public ContentTypeModel $model;

    // PayWay params
    public $hash, $tran_id, $amount, $items, $firstname, $type, $currency, $return_url, $req_time, $merchant_id, $payment_option, $return_params, $continue_success_url;

    protected PayWayServices $payWayServices;

    public function boot(PayWayServices $payWayServices)
    {
        $this->payWayServices = $payWayServices;
    }

    // --------------------
    // Select Payment
    // --------------------
    public function selectPaymentMethod(string $method): void
    {
        $this->selectedPaymentMethod = $method;

        if ($method === 'CASH') {
            $this->checkoutCash();
        } else {
            $this->preparePayWay($method);
            $this->dispatchBrowserEvent('open-payway-form');
        }
    }

    // -------------------
    // Open Payment Methods
    // -------------------
    public function openPaymentModal()
    {
        if (empty($this->cart)) {
            $this->showAlert('Cart is empty', 'warning');
            return;
        }

        if (trim($this->customer) === '') {
            $this->showAlert('Customer name is required', 'warning');
            return;
        }

        $this->showPaymentModal = true;
        $this->isCheckingOut = true;
    }

    // --------------------
    // Product search & pagination
    // --------------------
    public function updatedSearch(): void
    {
        $this->currentPage = 1;
    }

    public function nextPage(): void
    {
        $this->currentPage++;
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function getProductsProperty()
    {
        $query = Product::query()
            ->select(['id','name','sku','barcode','unit_price as price','image_url','quantity_available'])
            ->where('quantity_available', '>', 0);

        if (!empty($this->search)) {
            $term = trim($this->search);
            $query->where(fn($q) =>
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%")
                  ->orWhere('barcode', 'like', "%{$term}%")
            );
        }

        return $query->paginate($this->productsPerPage, ['*'], 'page', $this->currentPage);
    }

    // --------------------
    // Alerts
    // --------------------
    public function showAlert(string $message, string $type = 'success'): void
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->dispatch('alert-timeout');
    }

    public function hideAlert(): void
    {
        $this->alertMessage = '';
    }

    // --------------------
    // Cart management
    // --------------------
    public function updatedCart(): void
    {
        $this->recalc();
    }

    public function addProducts(): void
    {
        $term = trim($this->search);
        if ($term === '') return;

        $query = Product::query()->select(['id','name','sku','barcode','unit_price as price','image_url']);

        if (preg_match('/^\d{6,}$/', $term)) {
            $query->where('barcode', $term);
        } elseif (preg_match('/^[A-Za-z0-9\-_]+$/', $term)) {
            $query->where('sku', $term);
        } else {
            $query->where('name', 'like', "%{$term}%");
        }

        $p = $query->first();
        if ($p) {
            $this->addToCart($p->id, $p->sku, $p->name, $p->price, $p->image_url ?? '');
            $this->search = '';
        } else {
            $this->showAlert('Product not found', 'warning');
        }
    }

    public function addToCart(int $id, string $sku, string $name, float $price, string $imageUrl = ''): void
    {
        $this->isAddingToCart = true;
        try {
            foreach ($this->cart as &$item) {
                if ($item['id'] === $id) {
                    $item['qty'] += 1;
                    $this->recalc();
                    $this->showAlert("Updated quantity for {$name}", 'success');
                    return;
                }
            }

            $this->cart[] = [
                'id' => $id,
                'sku' => $sku,
                'name' => $name,
                'price' => $price,
                'image_url' => $imageUrl,
                'qty' => 1,
            ];

            $this->recalc();
            $this->showAlert("Added {$name} to cart", 'success');
        } catch (\Exception $e) {
            $this->showAlert("Failed to add {$name} to cart", 'error');
        } finally {
            $this->isAddingToCart = false;
        }
    }

    public function remove(int $index): void
    {
        if (! array_key_exists($index, $this->cart)) return;
        array_splice($this->cart, $index, 1);
        $this->recalc();
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->recalc();
    }

    public function updateQty(int $index, int $qty): void
    {
        if (!array_key_exists($index, $this->cart)) return;

        $cartItem = $this->cart[$index];
        $product = Product::find($cartItem['id']);

        if (!$product) {
            $this->showAlert("Product not found.", 'error');
            return;
        }

        $newQty = max(1, $qty);

        if ($newQty > $product->quantity_available) {
            $this->showAlert("Cannot add more. Only {$product->quantity_available} in stock.", 'warning');
            $newQty = $product->quantity_available;
        }

        $this->cart[$index]['qty'] = $newQty;
        $this->recalc();
    }

    public function recalc(): void
    {
        $this->subtotal = collect($this->cart)->sum(fn($i) => $i['price'] * $i['qty']);
        $this->tax = $this->subtotal * 0.1; // 10% tax
        $this->total = $this->subtotal - $this->discount + $this->tax;
    }

    // --------------------
    // Checkout
    // --------------------
    public function checkoutCash(): void
    {
        try {
            DB::transaction(function () {
                $userId = Auth::id();
                $reference = 'INV-' . now()->format('YmdHis');

                $customer = Customer::firstOrCreate(['name' => trim($this->customer)]);
                
                $sale = Sale::create([
                    'customer_id' => $customer->id,
                    'user_id' => $userId,
                    'reference' => $reference,
                    'subtotal' => $this->subtotal,
                    'discount' => $this->discount,
                    'tax' => $this->tax,
                    'total_amount' => $this->total,
                    'payment_method' => 'CASH',
                    'status' => 'paid',
                    'sale_date' => now(),
                    'customer_info' => $customer->name,
                ]);

                foreach ($this->cart as $cartItem) {
                    $product = Product::lockForUpdate()->find($cartItem['id']);
                    if (!$product) throw new \RuntimeException('Product not found.');

                    $qty = $cartItem['qty'];
                    if ($qty > $product->quantity_available) {
                        throw new \RuntimeException("Insufficient stock for {$product->name}.");
                    }

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'selling_price' => $cartItem['price'],
                        'subtotal' => $cartItem['price'] * $qty,
                        'image_url' => $product->image_url,
                    ]);

                    $product->decrement('quantity_available', $qty);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => $userId,
                        'type' => 'out',
                        'quantity' => $qty,
                        'selling_price' => $cartItem['price'],
                        'movement_date' => now(),
                        'note' => 'POS Sale ' . $reference,
                        'warehouse_id' => $product->warehouse_id,
                        'cost_price' => $product->cost_price,
                    ]);
                }
                $this->showPaymentModal = false;
                $this->showAlert("Checkout successful! Total: $ {$this->total}", 'success');
                $this->clearCart();
            });
        } catch (\Throwable $e) {
            $this->showAlert("Checkout failed: {$e->getMessage()}", 'error');
        }
    }


    // --------------------
    // ABA PayWay checkout
    // --------------------
    public function preparePayWay(string $method): void
    {
        $this->selectedPaymentMethod = $method;
        $this->isSelectedPaymentMethod = true;

        try {
            $items = [];
            foreach ($this->cart as $c) {
                $items[] = [
                    'name'   => $c['name'],
                    'qty'    => (string) $c['qty'],
                    'amount' => number_format($c['price'], 2, '.', ''),
                ];
            }

            $items         = base64_encode(json_encode($items));
            $req_time      = time();
            $tran_id       = $req_time . rand(1000, 9999); // unique ID
            $amount        = number_format($this->total, 2, '.', '');
            $firstname     = $this->customer ?: 'Guest';
            $return_params = "POS Order";
            $type          = "purchase";
            $view_type     = "popup";
            $merchant_id   = config('payway.merchant_id');
            $currency      = 'USD';
            $return_url    = route('payment.success', ['tran_id' => $tran_id]);
            $continue_success_url = route('payment.success', ['tran_id' => $tran_id]);
            $payment_option = $method;

            $raw_string = $req_time . $merchant_id . $tran_id . $amount . $items .
                        $firstname . $type . $payment_option . $return_url . $continue_success_url . $currency . $return_params;

            $hash = $this->payWayServices->getHash($raw_string);

            // ✅ Save values for Blade form
            $this->hash           = $hash;
            $this->tran_id        = $tran_id;
            $this->items          = $items;
            $this->amount         = $amount;
            $this->firstname      = $firstname;
            $this->type           = $type;
            $this->currency       = $currency;
            $this->payment_option = $payment_option;
            $this->req_time       = $req_time;
            $this->merchant_id    = $merchant_id;
            $this->return_params  = $return_params;
            $this->return_url     = $return_url;
            $this->continue_success_url = $continue_success_url;

            // ✅ Create Sale & related records in DB
            DB::transaction(function () use ($tran_id, $method) {
                $userId = Auth::id();
                $customer = Customer::firstOrCreate(['name' => trim($this->customer)]);

                $sale = Sale::create([
                    'customer_id'   => $customer->id,
                    'user_id'       => $userId,
                    'reference'     => $tran_id,  // link with PayWay tran_id
                    'subtotal'      => $this->subtotal,
                    'discount'      => $this->discount,
                    'tax'           => $this->tax,
                    'total_amount'  => $this->total,
                    'payment_method'=> strtoupper($method),
                    'status'        => 'pending', // will update after PayWay notify
                    'sale_date'     => now(),
                    'customer_info' => $customer->name ?? 'Guest',
                ]);

                foreach (
                    collect($this->cart)
                        ->groupBy('id') // prevent duplicate products
                        ->map(function ($group) {
                            $first = $group->first();
                            $first['qty'] = $group->sum('qty'); // merge quantities
                            return $first;
                        })
                        ->values() as $cartItem
                ) {
                    $product = Product::lockForUpdate()->find($cartItem['id']);
                    if (!$product) {
                        throw new \RuntimeException('Product not found.');
                    }
                
                    $qty = $cartItem['qty'];
                    if ($qty > $product->quantity_available) {
                        throw new \RuntimeException("Insufficient stock for {$product->name}.");
                    }
                
                    // create sale item
                    SaleItem::create([
                        'sale_id'       => $sale->id,
                        'product_id'    => $product->id,
                        'quantity'      => $qty,
                        'selling_price' => $cartItem['price'],
                        'subtotal'      => $cartItem['price'] * $qty,
                        'image_url'     => $product->image_url,
                    ]);
                
                    // decrement stock only ONCE with the merged quantity
                    $product->decrement('quantity_available', $qty);
                
                    // track stock movement
                    StockMovement::create([
                        'product_id'    => $product->id,
                        'user_id'       => $userId,
                        'type'          => 'out',
                        'quantity'      => $qty,
                        'selling_price' => $cartItem['price'],
                        'movement_date' => now(),
                        'note'          => 'POS Sale ' . $tran_id,
                        'warehouse_id'  => $product->warehouse_id,
                        'cost_price'    => $product->cost_price,
                    ]);
                }
                
                $this->showAlert("Sale created (pending). Waiting for payment confirmation...", 'info');
            });

        } catch (\Throwable $e) {
            $this->showAlert("Checkout failed: {$e->getMessage()}", 'error');
        }
    }

    // --------------------
    // Print receipt
    // --------------------
    public function printReceipt($saleId): void
    {
        $sale = Sale::with('items.product')->findOrFail($saleId);
        $receiptHtml = view('exports.receipt', compact('sale'))->render();
        $this->dispatchBrowserEvent('print-receipt', ['html' => $receiptHtml]);
    }

    public function render()
    {
        return view('livewire.pos', [
            'products' => $this->products,
        ]);
    }
}
