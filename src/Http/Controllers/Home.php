<?php

namespace App\Http\Controllers;

use Ions\Foundation\BaseController;
use Throwable;

class Home extends BaseController
{
    /**
     * @throws Throwable
     */
    public function index(): void
    {
        $this->twig->display('index.html.twig');
    }
}