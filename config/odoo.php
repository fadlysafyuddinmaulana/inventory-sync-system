<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Odoo Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for connecting to Odoo 19 API
    |
    */

    'url' => env('ODOO_URL', 'http://localhost:8069'),

    'database' => env('ODOO_DATABASE', 'odoo_db'),

    'username' => env('ODOO_USERNAME', 'admin'),

    'password' => env('ODOO_API_KEY', 'admin'),

    'timeout' => env('ODOO_TIMEOUT', 30),

    'cache' => [
        'enabled' => env('ODOO_CACHE_ENABLED', true),
        'ttl' => env('ODOO_CACHE_TTL', 3600), // 1 hour
    ],

    'pagination' => [
        'per_page' => env('ODOO_PRODUCTS_PER_PAGE', 20),
        'max_limit' => env('ODOO_PRODUCTS_MAX_LIMIT', 500),
    ],

    'models' => [
        'product' => 'product.product',
        'template' => 'product.template',
    ],

    'image' => [
        'fallback' => '/images/no-image.png',
        'sizes' => [
            'thumbnail' => 128,
            'small' => 256,
            'medium' => 512,
            'large' => 1024,
            'original' => 1920,
        ],
        'cache_control' => 'max-age=86400, public', // 24 hours
    ],

    'log_channel' => env('ODOO_LOG_CHANNEL', 'single'),

    'sync' => [
        'batch_size' => 100,
        'retry_attempts' => 3,
        'retry_delay' => 1000, // milliseconds
    ],
];
