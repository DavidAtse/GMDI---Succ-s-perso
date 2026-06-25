<?php

return [
    'name'            => env('APP_NAME', 'GMDI Urbanisme SIG'),
    'env'             => env('APP_ENV', 'production'),
    'debug'           => (bool) env('APP_DEBUG', false),
    'url'             => env('APP_URL', 'http://localhost'),
    'timezone'        => 'Africa/Abidjan',
    'locale'          => 'fr',
    'fallback_locale' => 'fr',
    'faker_locale'    => 'fr_FR',
    'key'             => env('APP_KEY'),
    'cipher'          => 'AES-256-CBC',
    'aliases'         => [],
];
