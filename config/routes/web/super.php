<?php

use Ions\Bundles\MRoute;

MRoute::get('/super', 'super\\IndexController::index')->name('super_root')->save();


MRoute::prefix('/super/category')->group(function () {
    MRoute::get('', 'super\\CategoryController::index')->save();
    MRoute::post('-render', 'super\\CategoryController::render')->save();
    MRoute::post('/deleteMulti','super\\CategoryController::destroyMulti')->save();
    MRoute::post('/delete','super\\CategoryController::destroy')->save();
});