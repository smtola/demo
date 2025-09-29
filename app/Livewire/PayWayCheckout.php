<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\PayWayServices;

class PayWayCheckout extends Component
{
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $currency = 'USD';

    protected $payWayServices;

    public function boot(PayWayServices $payWayServices)
    {
        $this->payWayServices = $payWayServices;
    }

    protected function rules()
    {
        return [
            'firstName' => 'required|string|max:255',
            'lastName'  => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'required|string|max:20',
            'currency'  => 'required|string',
            'amount'    => 'required|numeric|min:0',
            'shipping'  => 'nullable|string|max:255',
            'payment_option' => 'required|string',
        ];
    }

    public function submit()
    {
        $this->validate();

        $items = [
            ['name' => "test 1", "qty" => '1', "amount" => '10.00'],
            ['name' => "test 2", "qty" => '2', "amount" => '20.00'],
        ];

        $itemsEncoded   = base64_encode(json_encode($items));
        $req_time       = time();
        $transactionId  = $req_time;
        $return_params  = "Hello World!";
        $type           = "purchase";
        $merchant_id    = config('payway.merchant_id');

        $hash = $this->payWayServices->getHash(
            $req_time . $merchant_id . $transactionId . $this->amount . $itemsEncoded .
            $this->shipping . $this->firstName . $this->lastName . $this->email . $this->phone .
            $type . $this->payment_option . $this->currency . $return_params
        );

        // Send data to checkout view (or emit event)
        return view('livewire.checkout', [
            'hash'          => $hash,
            'transactionId' => $transactionId,
            'amount'        => $this->amount,
            'items'         => $itemsEncoded,
            'firstName'     => $this->firstName,
            'lastName'      => $this->lastName,
            'phone'         => $this->phone,
            'type'          => $type,
            'payment_option'=> $this->payment_option,
            'email'         => $this->email,
            'return_params' => $return_params,
            'merchant_id'   => $merchant_id,
            'currency'      => $this->currency,
            'shipping'      => $this->shipping,
            'req_time'      => $req_time,
        ]);
    }

    public function render()
    {
        return view('livewire.pay-way-checkout');
    }
}
