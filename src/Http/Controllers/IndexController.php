<?php

namespace App\Http\Controllers;

use Ions\Foundation\BaseController;
use Throwable;

class IndexController extends BaseController
{
    public function index(): void
    {
        try {
            $this->twig->display('index.html.twig');
        } catch (Throwable $e) {
            abort(404, $e->getMessage());
        }
    }
}