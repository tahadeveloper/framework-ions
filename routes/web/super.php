<?php

use Ions\Bundles\Route;

Route::get('/super', 'super\Index::index', name: 'super_root');

Route::prefix('/super/category')->group(function () {
    Route::get('', 'super\Category::index');
    Route::post('-render', 'super\Category::render');
    Route::match(['post', 'delete'], '/deleteMulti', 'super\Category::destroyAll');
    Route::match(['post', 'delete'], '/delete', 'super\Category::destroy');
});