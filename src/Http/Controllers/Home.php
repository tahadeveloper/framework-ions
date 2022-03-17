<?php

namespace App\Http\Controllers;

use Ions\Foundation\BaseController;
use Ions\Support\Route;
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

    #[Route('/test')]
    public function test(): void
    {
       //abort(403,'asdasd');
        echo 'test';
    }
}