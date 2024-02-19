<?php

use Ions\Bundles\Route;
use Ions\Support\Request;

Route::get('/', 'Home::index');
Route::match(['get', 'post'], '/index', static function (Request $request) {
    appSetLocale('ar');
    render('re.html.twig', ['name' => 'ions', 'text' => $request->get('text')]);
    echo 'Hello world';
});

Route::prefix('/super','super\\')->group(function () {
    Route::get('', 'Index::index');
});
