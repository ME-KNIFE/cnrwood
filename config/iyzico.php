<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Iyzico Payment Gateway — Phase 11C
    |--------------------------------------------------------------------------
    |
    | Turkish credit/debit card payments via iyzico 3DS flow.
    | Sandbox:  https://sandbox-api.iyzipay.com
    | Live:     https://api.iyzipay.com
    |
    | Test cards: https://dev.iyzipay.com/en/test-cards
    */

    'api_key'    => env('IYZICO_API_KEY',    ''),
    'secret_key' => env('IYZICO_SECRET_KEY', ''),
    'base_url'   => env('IYZICO_BASE_URL',   'https://sandbox-api.iyzipay.com'),
];
