<div class="min-h-screen">
    <!-- Alert Messages -->
    @if($alertMessage)
        <div class="fixed top-4 right-4 z-50 max-w-md" 
             x-data="alertComponent()" 
             x-show="$wire.alertMessage" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform translate-x-full"
        >
            <div class="bg-white/50 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-4 flex items-center gap-3">
                @if($alertType === 'success')
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                @elseif($alertType === 'error')
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                @elseif($alertType === 'warning')
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                @else
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                @endif
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ $alertMessage }}</p>
                </div>
                <button 
                    wire:click="hideAlert" 
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="container mx-auto px-4 py-6">
        <!-- Header with Glass Effect -->
        <header class="glass-card mb-8 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Point of Sale
                    </h1>
                    <p class="text-gray-600 mt-1">Book SMS Management System</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">{{ now()->format('M d, Y') }}</div>
                    <div class="text-lg font-semibold text-gray-700">{{ now()->format('h:i A') }}</div>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <!-- Product Search & Cart Section -->
            <section class="xl:col-span-8">
                <!-- Barcode Scanner Input -->
                <div class="glass-card mb-6 p-6">
                    <div class="flex items-center gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                            <input 
                                wire:model.defer="search" 
                                wire:keydown.enter.prevent="addProducts" 
                                type="text" 
                                placeholder="Scan barcode or search by SKU/name..." 
                                class="w-full pl-12 pr-4 py-4 rounded-2xl border-0 bg-white/70 backdrop-blur-sm text-gray-700 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:bg-white/90 transition-all duration-200 shadow-lg"
                            >
                        </div>
                        <button 
                            wire:click="addProducts" 
                            class="px-6 py-4 rounded-2xl bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center gap-2"
                        >
                            Add
                        </button>
                    </div>
                </div>

                <!-- Product Cards -->
                <div class="glass-card mb-6 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Products</h2>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">
                                Showing {{ $this->products->count() }} of {{ $this->products->total() }} products
                            </span>
                        </div>
                    </div>

                    @if($this->products->count() > 0)
                        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
                            @foreach($this->products as $product)
                                <div class="bg-white/30 backdrop-blur-sm rounded-2xl p-4 border border-white/80 shadow-lg hover:shadow-xl transition-all duration-200 group">
                                    <!-- Product Image -->
                                    <div class="relative mb-4">
                                        @if(!empty($product->image_url))
                                            <img 
                                                src="{{ $product->image_url }}" 
                                                alt="{{ $product->name }}" 
                                                class="w-full h-32 rounded-md md:rounded-xl object-cover shadow-md group-hover:scale-105 transition-transform duration-200"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                            >
                                            <div class="w-full h-32 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 items-center justify-center hidden">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-full h-32 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Stock Badge -->
                                        <div class="absolute top-2 right-2">
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                {{ $product->quantity_available }} in stock
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="mb-4">
                                        <h3 class="font-semibold text-gray-800 text-sm mb-1 line-clamp-2">{{ $product->name }}</h3>
                                        <p class="text-xs text-gray-500 mb-2">{{ $product->sku }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-lg font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                        </div>
                                    </div>

                                    <!-- Add to Cart Button -->
                                    <button 
                                        wire:click="addToCart({{ $product->id }}, '{{ $product->sku }}', '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->image_url }}')"
                                        @disabled($isAddingToCart)
                                        class="w-full px-4 py-2 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold text-sm shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                    >
                                        @if($isAddingToCart)
                                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Adding...
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Add to Cart
                                        @endif
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($this->products->hasPages())
                            <div class="flex items-center justify-between">
                                <button 
                                    wire:click="previousPage" 
                                    @disabled($currentPage <= 1)
                                    class="px-4 py-2 rounded-xl bg-white/40 backdrop-blur-sm text-gray-700 font-semibold border border-gray-200 hover:bg-white/60 hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Previous
                                </button>
                                
                                <span class="text-sm text-gray-500">
                                    Page {{ $currentPage }} of {{ $this->products->lastPage() }}
                                </span>
                                
                                <button 
                                    wire:click="nextPage" 
                                    @disabled(!$this->products->hasMorePages())
                                    class="px-4 py-2 rounded-xl bg-white/40 backdrop-blur-sm text-gray-700 font-semibold border border-gray-200 hover:bg-white/60 hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                                >
                                    Next
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gray-400 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-500 mb-2">No products found</h3>
                            <p class="text-gray-400">Try adjusting your search or check back later</p>
                        </div>
                    @endif
                </div>

                <!-- Cart Items -->
                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Cart Items</h2>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ count($cart) }} items
                        </span>
                    </div>

                    @if(empty($cart))
                        <div class="text-center py-12">
                            <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gray-400 flex items-center justify-center">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="44"  height="44"  viewBox="0 0 24 24"  fill="none"  stroke="#fff"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17h-11v-14h-2" /><path d="M6 5l14 1l-1 7h-13" /></svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-500 mb-2">Your cart is empty</h3>
                            <p class="text-gray-400">Scan a barcode or search for products to get started</p>
                        </div>
                    @else
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($cart as $index => $item)
                                <div class="bg-white/30 backdrop-blur-sm rounded-2xl p-4 border border-white/80 shadow-lg hover:shadow-xl transition-all duration-200">
                                    <div class="flex items-center gap-4">
                                        <!-- Product Image -->
                                        <div class="relative">
                                            @if(!empty($item['image_url']))
                                                <img 
                                                    src="{{ $item['image_url'] }}" 
                                                    alt="{{ $item['name'] }}" 
                                                    class="w-16 h-16 rounded-xl object-cover shadow-md"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                >
                                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 items-center justify-center hidden">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-800 truncate">{{ $item['name'] }}</h3>
                                            <p class="text-sm text-gray-500">{{ $item['sku'] }}</p>
                                            <div class="flex items-center gap-4 mt-2">
                                                <span class="text-lg font-bold text-blue-600">${{ number_format($item['price'], 2) }}</span>
                                                <span class="text-sm text-gray-500">each</span>
                                            </div>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center gap-2">
                                            <button 
                                                wire:click="updateQty({{ $index }}, {{ $item['qty'] - 1 }})"
                                                class="w-8 h-8 rounded-full bg-gray-300 hover:bg-gray-400 flex items-center justify-center transition-colors duration-200"
                                            >
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input 
                                                type="number" 
                                                class="w-16 text-center font-semibold bg-white/70 rounded-lg border-0 focus:ring-2 focus:ring-blue-500" 
                                                min="1" 
                                                wire:change="updateQty({{ $index }}, $event.target.value)" 
                                                value="{{ $item['qty'] }}"
                                            >
                                            <button 
                                                wire:click="updateQty({{ $index }}, {{ $item['qty'] + 1 }})"
                                                @if($item['qty'] >= \App\Models\Product::find($item['id'])->quantity_available) disabled @endif
                                                class="w-8 h-8 rounded-full bg-gray-300 hover:bg-gray-400 flex items-center justify-center transition-colors duration-200"
                                            >
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Total & Remove -->
                                        <div class="text-right">
                                            <div class="text-xl font-bold text-gray-800">${{ number_format($item['qty'] * $item['price'], 2) }}</div>
                                            <button 
                                                wire:click="remove({{ $index }})" 
                                                class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors duration-200"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            <!-- Checkout Panel -->
            <aside class="xl:col-span-4">
                <div class="glass-card p-6 sticky top-6">
                    <!-- Order Summary -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount:</span>
                                <input type="number" wire:keydown="recalc"  wire:model="discount" class="w-24 text-right border rounded px-2 py-1">
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax:</span>
                                <span class="font-semibold">${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-blue-600">
                                <span>Total:</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    
                    <!-- Customer Info -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                        <input 
                            type="text" 
                            class="w-full px-4 py-3 rounded-xl border-0 bg-white/70 backdrop-blur-sm text-gray-700 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:bg-white/90 transition-all duration-200" 
                            placeholder="Walk-in customer" 
                            wire:model.defer="customer"
                            required
                        >
                    </div>

                    <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button 
                                wire:click="clearCart" 
                                class="w-full px-6 py-4 rounded-xl bg-white/40 backdrop-blur-sm text-gray-700 font-semibold border border-gray-200 hover:bg-white/60 hover:shadow-lg transition-all duration-200"
                            >
                                Clear Cart
                            </button>
                            <button 
                            wire:click="openPaymentModal()"
                            @disabled($isCheckingOut)
                            class="w-full px-6 py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        >
                            @if($isCheckingOut)
                                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Processing...
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Payment Methods
                            @endif
                        </button>
                    </div>
                </div>
            </aside>
        </div>
    </div>

      <div x-data="{ open: @entangle('showPaymentModal') }" x-show="open">
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-400/40 p-6 rounded-xl w-96">
                <h3 class="text-lg font-bold mb-4 text-[#fff]">Select Payment Method</h3>
                @include('livewire.payment-methods')
            </div>
        </div>
      </div>


    <!-- Glass Card Styles -->
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border-radius: 24px;
        }
        
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }
        
        /* Light text colors for better readability */
        .text-gray-800 {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .text-gray-700 {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        .text-gray-600 {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        
        .text-gray-500 {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        
        .text-gray-400 {
            color: rgba(255, 255, 255, 0.5) !important;
        }
        
        /* Custom scrollbar */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.5);
            border-radius: 3px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.7);
        }
        
        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <!-- Alpine.js for alert functionality -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('alertComponent', () => ({
                init() {
                    // Auto-hide alert after 5 seconds
                    this.$watch('$wire.alertMessage', (value) => {
                        if (value) {
                            setTimeout(() => {
                                this.$wire.hideAlert();
                            }, 5000);
                        }
                    });
                }
            }));
        });
    </script>
</div>


