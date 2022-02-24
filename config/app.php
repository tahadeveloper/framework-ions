<?php

use Ions\Bundles\Path;

return [
    /*
     |--------------------------------------------------------------------------
     | Application app URL ,app id , app debug
     |--------------------------------------------------------------------------
    */
    'app_url' => env('APP_URL'),
    'app_id' => env('APP_ID'),
    'app_debug' => env('APP_DEBUG', false),
    /*
    |--------------------------------------------------------------------------
    | database project engine support ['db', 'redbean']
    |--------------------------------------------------------------------------
    */
    'database_engine' => [],
    /*
    |--------------------------------------------------------------------------
    | templates project support ['twig','smarty']
    |--------------------------------------------------------------------------
    */
    'templates' => ['twig'],
    /*
    |--------------------------------------------------------------------------
    | Twig folder and cache
    |--------------------------------------------------------------------------
    */
    'twig' => [
        'theme' => $twig_theme = 'default',
        'source' => Path::views($twig_theme),
        'cache' => Path::cache('twig'),
        'paths' => [
            'admin'
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | Smarty folder and cache
    |--------------------------------------------------------------------------
    */
    'smarty' => [
        'theme' => $smarty_theme = 'default',
        'source' => Path::views($smarty_theme),
        'compile' => Path::cache('smarty/compile'),
        'cache' => Path::cache('smarty/cache'),
        'config' => Path::config('smarty.php'),
        'paths' => [
            'admin'
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | default language and locales
    |--------------------------------------------------------------------------
    */
    'localization' => [
        'locale' => 'en'
    ],
    /*
     |--------------------------------------------------------------------------
     | where mysql dump path
     |--------------------------------------------------------------------------
    */
    'dump' => ['dump_binary_path' => '/Applications/MAMP/Library/bin/'],
    /*
     |--------------------------------------------------------------------------
     | migration table in database
     |--------------------------------------------------------------------------
    */
    'database' => ['migrations' => 'migrations'],
    /*
     |--------------------------------------------------------------------------
     | include libraries or classes - include once
     | path will be in src folder, only load if it in src
     |--------------------------------------------------------------------------
    */
    'preloads' => [],
];