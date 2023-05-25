<?php

return [
    'access_key' => env('UNSPLASH_ACCESS_KEY'),
    'secret_key' => env('UNSPLASH_SECRET_KEY'),
    'store_in_database' => env('UNSPLASH_STORE_IN_DATABASE', false),
    'disk' => env('UNSPLASH_STORAGE_DISK', 'local'),
];
