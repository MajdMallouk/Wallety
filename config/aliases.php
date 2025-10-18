<?php
// config/aliases.php

return [

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. Feel free to add your own as needed.
    |
    */

    'App'       => Illuminate\Support\Facades\App::class,
    'Artisan'   => Illuminate\Support\Facades\Artisan::class,
    // … all the defaults from the framework …
    'View'      => Illuminate\Support\Facades\View::class,

    // Your added facade alias:
    'QrCode'    => SimpleSoftwareIO\QrCode\Facades\QrCode::class,
];
