<?php

use Ions\Bundles\Route;

Route::prefix('/api')->group(function () {
    Route::match(['get', 'post'], '/v1/base/{type}', 'v1\Services::base', ['type' => 'upload']);
});
