<?php

namespace App\Services;

class PayWayServices
{
/**
 *
 *
 *       @return string;
 *
 */

    public function getApiUrl() {
        return config('payway.api_url');
    }
    /**
    *    @param string $str;
    *    @return string;
    */
    public function getHash($str) {
        $key = config('payway.api_key');
        return base64_encode(hash_hmac('sha512', $str,$key,true));
    }
}


