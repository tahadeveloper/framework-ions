<?php

use Ions\Bundles\Path;

return [
    'default' => 'stack',
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
        ],
        'single' => [
            'driver' => 'single',
            'path' => Path::logs('app.log'),
            'level' => 'debug',
        ],
    ],
];