<?php

return [
    'api_key' => env('SHOPIFY_API_KEY'),
    'api_secret' => env('SHOPIFY_API_SECRET'),
    'scopes' => env('SHOPIFY_SCOPES', 'read_products,write_products'),
    'redirect_uri' => env('SHOPIFY_REDIRECT_URI'),
];

