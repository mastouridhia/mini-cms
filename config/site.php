<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Read-only mode
    |--------------------------------------------------------------------------
    |
    | When true, the "writable" middleware refuses every write request
    | (POST, PUT, PATCH, DELETE) with a 503 response.
    |
    */

    'read_only' => (bool) env('SITE_READ_ONLY', false),

];
