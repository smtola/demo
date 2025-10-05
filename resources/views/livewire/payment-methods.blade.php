<div>
    <!-- Payment Method Selection -->
    <div class="pb-2">
        {{-- <label class="block text-lg font-medium text-gray-700 mb-3">Total: ${{ $amount }}</label> --}}
        <div class="space-y-4">

            <!-- KHQR -->
            <button wire:click="preparePayWay('abapay')" type="button"
                class="w-full bg-white/40 backdrop-blur-sm border border-gray-200 rounded-xl flex items-center justify-between px-4 py-2 shadow hover:bg-gray-400 transition cursor-pointer {{ $selectedPaymentMethod === 'abapay' ? 'ring-2 ring-blue-500' : '' }}">
                <div class="flex items-center space-x-4">
                    <img src="{{ url('images/payment-images/ABA BANK.png') }}" alt="ABA KHQR" class="w-10 h-10 rounded" />
                    <div>
                        <div class="text-start font-semibold text-lg text-gray-800">ABA KHQR</div>
                        <div class="text-gray-500 text-sm">Scan to pay with any banking app</div>
                    </div>
                </div>
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                    class="text-gray-400" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>

            <!-- CARD -->
            <button wire:click="preparePayWay('cards')" type="button"
                class="w-full bg-white/40 backdrop-blur-sm border border-gray-200 rounded-xl flex items-center justify-between px-4 py-2 shadow hover:bg-gray-400 transition cursor-pointer {{ $selectedPaymentMethod === 'cards' ? 'ring-2 ring-blue-500' : '' }}">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-1">
                        <img src="{{ url('images/payment-images/cards_icons.png') }}" alt="cards" class="w-10 h-10" />
                    </div>
                    <div>
                        <div class="text-start font-semibold text-lg text-gray-800">Credit/Debit Card</div>
                        <div class="flex space-x-2">
                            <img src="{{ url('images/payment-images/Visa.svg') }}" alt="Visa" class="w-full h-4" />
                            <img src="{{ url('images/payment-images/Mastercard.svg') }}" alt="MC" class="w-full h-4" />
                            <img src="{{ url('images/payment-images/UPI.svg') }}" alt="UnionPay" class="w-full h-4" />
                            <img src="{{ url('images/payment-images/JCB.svg') }}" alt="JCB" class="w-full h-4" />
                        </div>
                    </div>
                </div>
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                    class="text-gray-400" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>

            <!-- ALIPAY -->
            <button wire:click="preparePayWay('ALIPAY')" type="button"
                class="w-full bg-white/40 backdrop-blur-sm border border-gray-200 rounded-xl flex items-center justify-between px-4 py-2 shadow hover:bg-gray-400 transition cursor-pointer {{ $selectedPaymentMethod === 'ALIPAY' ? 'ring-2 ring-blue-500' : '' }}">
                <div class="flex items-center space-x-4">
                    <img src="{{ url('images/payment-images/Alipay.png') }}" alt="Alipay" class="w-10 h-10 rounded" />
                    <div>
                        <div class="text-start font-semibold text-lg text-gray-800">Alipay</div>
                        <div class="text-gray-500 text-sm">Scan to pay with Alipay</div>
                    </div>
                </div>
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                    class="text-gray-400" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>

            <!-- WECHAT -->
            <button wire:click="preparePayWay('wechat')" type="button"
                class="w-full bg-white/40 backdrop-blur-sm border border-gray-200 rounded-xl flex items-center justify-between px-4 py-2 shadow hover:bg-gray-400 transition cursor-pointer {{ $selectedPaymentMethod === 'wechat' ? 'ring-2 ring-blue-500' : '' }}">
                <div class="flex items-center space-x-4">
                    <img src="{{ url('images/payment-images/WeChat.png') }}" alt="WeChat" class="w-10 h-10 rounded" />
                    <div>
                        <div class="text-start font-semibold text-lg text-gray-800">WeChat</div>
                        <div class="text-gray-500 text-sm">Scan to pay with WeChat</div>
                    </div>
                </div>
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                    class="text-gray-400" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>

            <!-- CASH -->
            <button wire:click="checkoutCash()" type="button"
                class="w-full bg-white/40 backdrop-blur-sm border border-gray-200 rounded-xl flex items-center justify-between px-4 py-2 shadow hover:bg-gray-400 transition cursor-pointer {{ $selectedPaymentMethod === 'CASH' ? 'ring-2 ring-blue-500' : '' }}">
                <div class="flex items-center space-x-4">
                    <span class="w-10 h-10 flex items-center justify-center rounded bg-yellow-100 text-yellow-600 text-2xl">💵</span>
                    <div>
                        <div class="text-start font-semibold text-lg text-gray-800">Cash</div>
                        <div class="text-gray-500 text-sm">Pay with cash</div>
                    </div>
                </div>
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                    class="text-gray-400" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>
        </div>
    </div>
</div>


<div id="aba_main_modal" class="aba-modal">
    <div class="aba-modal-content">
        @if($selectedPaymentMethod !== 'CASH')
            <form method="POST" target="aba_webservice" action="{{ config('payway.api_url') }}" id="aba_merchant_request" enctype='multipart/form-data'>
                <input type="hidden" name="hash" value="{{ $hash }}">
                <input type="hidden" name="tran_id" value="{{ $tran_id }}">
                <input type="hidden" name="items" value="{{ $items }}">
                <input type="hidden" name="amount" value="{{ $amount }}">
                <input type="hidden" name="firstname" value="{{ $firstname }}">
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="currency" value="{{ $currency }}">
                <input type="hidden" name="payment_option" value="{{ $payment_option }}">
                <input type="hidden" name="req_time" value="{{ $req_time }}">
                <input type="hidden" name="merchant_id" value="{{ $merchant_id }}">
                <input type="hidden" name="return_params" value="{{ $return_params }}">
                <input type="hidden" name="return_url" value="{{ $return_url }}">
                <input type="hidden" name="continue_success_url" value="{{ $continue_success_url }}">
            </form>
        @endif
        <input type="button" @disabled(!$isSelectedPaymentMethod) id="checkout_button" value="Pay Now" class="w-full space-y-4 px-6 py-4 rounded-xl bg-blue-700  text-white font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
    </div>
</div>

