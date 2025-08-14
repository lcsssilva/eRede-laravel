<?php

return [

    /*
     *
     *
     */
    'sandbox_authorization' => 'https://sandbox-erede.useredecloud.com.br/v1/transactions',

    'production_authorization' => 'https://api.userede.com.br/erede/v1/transactions',

    /*
     *
     *
     */
    'sandbox_tokenization' => 'https://rl7-sandbox-api.useredecloud.com.br/token-service/v2/tokenization',

    'production_tokenization' => 'https://api.userede.com.br/redelabs/token-service/v2/tokenization',
    /*
    |--------------------------------------------------------------------------
    | eRede Filiation
    |--------------------------------------------------------------------------
    |
    | Sua filiation fornecido pela eRede
    |
    */
    'filiation' => env('EREDE_FILIATION'),

    /*
    |--------------------------------------------------------------------------
    | eRede Token
    |--------------------------------------------------------------------------
    |
    | Seu token fornecida pela eRede
    |
    */
    'api_token' => env('EREDE_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Sandbox Mode
    |--------------------------------------------------------------------------
    |
    | Define se está em modo sandbox (desenvolvimento) ou produção
    |
    */
    'sandbox' => env('EREDE_SANDBOX', true),

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | Timeout para requisições em segundos
    |
    */
    'timeout' => env('EREDE_TIMEOUT', 30),
];