<?php

use Ions\Bundles\MRoute;

MRoute::get('/super', 'super\Index::index')->name('super_root')->save();

MRoute::prefix('/super/category')->group(function () {
    MRoute::get('', 'super\Category::index')->save();
    MRoute::post('-render', 'super\Category::render')->save();
    MRoute::match(['post', 'delete'], '/deleteMulti', 'super\Category::destroyAll')->save();
    MRoute::match(['post', 'delete'], '/delete', 'super\Category::destroy')->save();
});