<?php

use Ions\Bundles\MRoute;
use Ions\Bundles\Path;

MRoute::get('/', static function (){
   echo 'Hello world';
})->save();

MRoute::match(['get','post'],'/index', 'Controllers\\HomeController::index')->save();

include_once Path::route('web/super.php');