<?php

use Ions\Foundation\RegisterDB;
use Ions\Foundation\Kernel;

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here, so we don't need to manually load our classes.
|
*/

require __DIR__ . '/../vendor/autoload.php';

Kernel::boot();
Kernel::make('web', 'App\\Http\\');