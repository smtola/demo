<form method="POST" action="https://checkout.payway.com.kh/api/payment-gateway/v1/payments">
    <input type="hidden" name="hash" value="{{ $hash }}">
    <input type="hidden" name="transactionId" value="{{ $transactionId }}">
    <input type="hidden" name="amount" value="{{ $amount }}">
    <input type="hidden" name="items" value="{{ $items }}">
    <input type="hidden" name="firstName" value="{{ $firstName }}">
    <input type="hidden" name="lastName" value="{{ $lastName }}">
    <input type="hidden" name="phone" value="{{ $phone }}">
    <input type="hidden" name="email" value="{{ $email }}">
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="payment_option" value="{{ $payment_option }}">
    <input type="hidden" name="currency" value="{{ $currency }}">
    <input type="hidden" name="shipping" value="{{ $shipping }}">
    <input type="hidden" name="req_time" value="{{ $req_time }}">
    <input type="hidden" name="merchant_id" value="{{ $merchant_id }}">
    <input type="hidden" name="return_params" value="{{ $return_params }}">

    <button type="submit">Proceed to PayWay</button>
</form>
