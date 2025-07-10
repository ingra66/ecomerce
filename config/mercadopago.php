<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mercado Pago Configuration
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar las credenciales de Mercado Pago
    |
    */

    'public_key' => env('MERCADOPAGO_PUBLIC_KEY', 'TEST-9d789008-52a6-4517-8271-7c735b3806c6'),
    'access_token' => env('MERCADOPAGO_ACCESS_TOKEN', 'TEST-1209269489778646-010517-6ba0c3913960821f7b3b4f4b94973f92-1188129936'),
    'sandbox' => env('MERCADOPAGO_SANDBOX', true),
    
    // Configuraciones adicionales
    'notification_url' => env('MERCADOPAGO_NOTIFICATION_URL', 'https://tu-dominio.com/webhooks/mercadopago'),
    'back_urls' => [
        'success' => env('MERCADOPAGO_SUCCESS_URL', 'https://tu-dominio.com/payment/success'),
        'failure' => env('MERCADOPAGO_FAILURE_URL', 'https://tu-dominio.com/payment/failure'),
        'pending' => env('MERCADOPAGO_PENDING_URL', 'https://tu-dominio.com/payment/pending'),
    ],
    
    // Configuraciones de moneda
    'currency' => env('MERCADOPAGO_CURRENCY', 'ARS'),
    'currency_symbol' => env('MERCADOPAGO_CURRENCY_SYMBOL', '$'),
]; 