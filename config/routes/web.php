<?php

use Ions\Bundles\MRoute;

MRoute::get('/', static function (){
   echo 'Hello world';
})->save();

MRoute::match(['get','post'],'/index', 'Controllers\\HomeController::index')->save();
