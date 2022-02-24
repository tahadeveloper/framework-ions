<?php

namespace API\v1;

use App\Providers\Lib\Fundamental;
use Ions\Foundation\ApiController;
use Ions\Support\Request;
use JetBrains\PhpStorm\NoReturn;

class Api extends ApiController
{
    #[NoReturn] public function base(Request $request): void
    {
        $type = $request->get('type');
        $result = match ($type) {
            'upload' => Fundamental::uploadFiles($this->inputs)->toJson(),
            'notify' => Fundamental::notificationFcm($this->inputs)->toJson(),
            default => $this->notFoundResponse(['error' => 'Must use supported type']),
        };
        $this->display($result);
    }
}