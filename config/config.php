<?php


return [
    'client_id' => env('BUSINESS_CENTRAL_CLIENT_ID'),
    'client_secret' => env('BUSINESS_CENTRAL_CLIENT_SECRET'),
    'scope' => env('BUSINESS_CENTRAL_SCOPE', 'https://api.businesscentral.dynamics.com/.default'),
    'tenant' => env('BUSINESS_CENTRAL_TENANT'),
    'environment' => env('BUSINESS_CENTRAL_ENVIRONMENT'),
    'company' => env('BUSINESS_CENTRAL_COMPANY'),
    'version' => env('BUSINESS_CENTRAL_VERSION', 'api/v2.0'),
    'timeout' => env('BUSINESS_CENTRAL_TIMEOUT', 30),
];
