<?php

use Ions\Bundles\MRoute as Route;
use Ions\Bundles\Path;

Route::get('/index', static function () {
    echo 'Hello world';
})->save();

Route::match(['get', 'post'], '/', 'Home::index');
Route::match(['get', 'post'], '/hello/{name}', 'Home::index', ['id' => 'taha'])
    ->name('test')
    ->where(['name' => '\d+'])
    ->save();

Route::prefix('/home')->group(static function () {
    Route::prefix('/index')->group(static function () {
        Route::get('/test', 'Home::index')->save();
    });
});

include_once Path::route('web/super.php');
