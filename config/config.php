<?php

use ReeceM\StaticForm\Http\Middleware\Authorize;
use ReeceM\StaticForm\Http\Middleware\ValidStaticSiteKey;

return [
    /*
    |--------------------------------------------------------------------------
    | The header that contains the token or hash
    |--------------------------------------------------------------------------
    |
    | This is the header that has the token that is sent with the request.
    |
    */
    'header' => env('STATIC_FROM_HEADER', 'X-STATIC-FORM'),

    /*
    |--------------------------------------------------------------------------
    | This is the base URL path for requests.
    |--------------------------------------------------------------------------
    |
    | All requests that have the route group attached will be under here.
    |
    */
    'path' => env('STATIC_FROM_PATH', 'static-form'),

    /*
    |--------------------------------------------------------------------------
    | Storage handler for the static site token
    |--------------------------------------------------------------------------
    |
    | This is the type of storage to use to keep the token. Defaults to file
    |
    | Supported Disks: "local", "ftp", "sftp", "s3",
    |
    */
    'storage' => [
        'disk' => env('STATIC_FROM_KEY_DISC', 'local'),
        'path' => env('STATIC_FORM_KEY_PATH', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | This is the middleware that is applied to the routes for the forms.
    |
    */
    'middleware' => [

        /**
         * This is the middleware for the Forms, the public facing side of things.
         */
        'forms' => [
            ValidStaticSiteKey::class,
            'throttle:30,3',
        ],

        'api' => [
            'web',
            Authorize::class,
        ],
    ],
];
