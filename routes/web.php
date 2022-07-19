<?php

use Ions\Bundles\Route;
use Ions\Bundles\Path;

Route::get('/', 'Home::index');
Route::match(['get', 'post'], '/index', static function () {
    echo 'Hello world';
});

include_once Path::route('web/super.php');
