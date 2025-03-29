<?php

use Ions\Bundles\Route;
use Ions\Support\Request;

Route::get(
    '/{_method}',
    'IndexController@index',
    ['_method' => 'index'],
    name: 'site.index',
    wheres: ['_method' => 'index'],
);

Route::match(['get', 'post'], '/index', static function (Request $request) {
    appSetLocale('ar');
    render('re.html.twig', ['name' => 'ions', 'text' => $request->get('text')]);
    echo 'Hello world';
});

Route::prefix('/super','super\\')->group(function () {
    Route::get('', 'Index::index');
});
