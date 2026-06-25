<?php
// config/cors.php
return [
    'paths'                => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods'      => ['*'],
    'allowed_origins'      => ['http://localhost:4200','http://127.0.0.1:4200','https://gmdi.mairie.ci','http://192.168.0.100','http://192.168.0.100:4200'],
    'allowed_origins_patterns' => [],
    'allowed_headers'      => ['*'],
    'exposed_headers'      => [],
    'max_age'              => 0,
    'supports_credentials' => false,
];
