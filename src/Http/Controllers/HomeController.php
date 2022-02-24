<?php

namespace App\Http\Controllers;

use Ions\Foundation\BaseController;
use Ions\Support\Request;

class HomeController extends BaseController
{
    public function index(Request $request): void
    {
        $this->twig->display('index.html.twig');
    }
}