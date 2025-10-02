<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PurchaseReceive;
use App\Livewire\QuickExpense;
use App\Models\Product;
use App\Http\Controllers\PaymentController;

Route::view('/', 'pos');

Route::get('/api/products', function(){
    $q = request('q');
    $barcode = request('barcode');

    $query = Product::query()->select(['id','name','sku','barcode','unit_price as price']);
    if($barcode){
        $query->where('barcode', $barcode);
    } elseif($q){
        $query->where(function($w) use ($q){
            $w->where('name', 'like', "%{$q}%")
              ->orWhere('sku', 'like', "%{$q}%")
              ->orWhere('barcode', 'like', "%{$q}%");
        });
    }

    return response()->json($query->limit(20)->get());
});

Route::get('/purchase/receive', PurchaseReceive::class);
Route::get('/expense/quick', QuickExpense::class);

Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/notify', [PaymentController::class, 'notify'])
    ->name('payment.notify')
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);