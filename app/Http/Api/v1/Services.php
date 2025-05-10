<?php

namespace App\Http\Api\v1;

use App\Providers\CategoryProvider;
use App\Providers\Lib\Fundamental;
use Ions\Foundation\ApiController;
use Ions\Support\Request;
use JetBrains\PhpStorm\NoReturn;

class Services extends ApiController
{
    #[NoReturn]
    public function base(Request $request): void
    {
        $type = $request->get('type');
        $result = match ($type) {
            'upload' => Fundamental::uploadFiles($this->inputs)->toJson(),
            'notify' => Fundamental::notificationFcm($this->inputs)->toJson(),
            default => $this->notFoundResponse(['error' => 'Must use supported type']),
        };
        $this->display($result);
    }

    #[NoReturn]
    public function category(Request $request): void
    {
        $id = $request->get('id');
        $this->routeMethod('get',function () use ($id){
            if($id){
                $this->display(CategoryProvider::single($id)->toJson());
            }
            $this->display(CategoryProvider::show()->toJson());
        });

        $this->display($this->notFoundResponse(['error' => 'Must use supported method']));
    }
}