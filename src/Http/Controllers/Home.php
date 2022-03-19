<?php

namespace App\Http\Controllers;

use Ions\Foundation\BaseController;

class Home extends BaseController
{
    public function index(): void
    {
        $this->twig->display('index.html.twig');
    }
}