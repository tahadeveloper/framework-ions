<?php

use Ions\Bundles\Route;
use Ions\Bundles\Path;
use Ions\Support\Request;

Route::get('/', 'Home::index');
Route::match(['get', 'post'], '/index', static function (Request $request) {
    appSetLocale('ar');
    render('re.html.twig', ['name' => 'ionzile', 'text' => $request->get('text')]);
    echo 'Hello world';
});

include_once Path::route('web/super.php');
