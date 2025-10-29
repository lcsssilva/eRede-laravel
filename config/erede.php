<?php

return [

    /*
     *
     *
     */
    'api' => [
        'sandbox' => [
            'tokenization' => 'https://rl7-sandbox-api.useredecloud.com.br/token-service/v2/tokenization',
            'authorization' => 'https://sandbox-erede.useredecloud.com.br/v1/transactions'
        ],
        'production' =>  [
            'tokenization' => 'https://api.userede.com.br/redelabs/token-service/v2/tokenization',
            'authorization' => 'https://api.userede.com.br/erede/v1/transactions'
        ],
    ],

    /*
     *
     *
     */
    'oauth' => [
        'sandbox' => "https://rl7-sandbox-api.useredecloud.com.br/oauth2/token",
        'production' => 'https://api.userede.com.br/oauth2/token',
    ],
    /*
    |--------------------------------------------------------------------------
    | eRede Filiation
    |--------------------------------------------------------------------------
    |
    | Sua filiation fornecido pela eRede
    |
    */
    'clientId' => env('EREDE_FILIATION'),

    /*
    |--------------------------------------------------------------------------
    | eRede Token
    |--------------------------------------------------------------------------
    |
    | Seu token fornecida pela eRede
    |
    */
    'clientSecret' => env('EREDE_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Sandbox Mode
    |--------------------------------------------------------------------------
    |
    | Define se está em modo sandbox (desenvolvimento) ou produção
    |
    */
    'sandbox' => env('EREDE_SANDBOX', true),

    'oauth_type' => env('OAUTH', false),
    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | Timeout para requisições em segundos
    |
    */
    'timeout' => env('EREDE_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | OAuth2 Settings
    |--------------------------------------------------------------------------
    */
    'token_expiration_minutes' => env('EREDE_TOKEN_EXPIRATION', 24),

    'token_buffer_minutes' => 2, // Renovar 2 minutos antes de expirar

    'cache_prefix_key' => 'erede_oauth_token_',

    'cache_prefix_expiration' => 'erede_oauth_expires_'
];