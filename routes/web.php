<?php

use Ions\Bundles\MRoute;
use Ions\Bundles\Path;

MRoute::get('/index', static function (){
   echo 'Hello world';
})->save();

MRoute::match(['get','post'],'/', 'Home::index')->save();

include_once Path::route('web/super.php');
