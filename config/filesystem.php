<?php

use Ions\Bundles\Path;

return [
    'disks' => [
        'default' => env('FILESYSTEM_DISK', 'local'),
        'local' => [
            'driver' => 'local',
            'root' => Path::files(''),
        ],
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'base_path' => env('AWS_BASE_PATH', 'app'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],
    ],
];
