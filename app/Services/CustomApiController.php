<?php

namespace App\Services;

use Ions\Support\Request;
use JetBrains\PhpStorm\NoReturn;

class CustomApiController extends ModifiedApiController
{
    public function _initState(Request $request): void
    {
        $lang = 'en';
        if ($request->has('lang') && in_array($request->get('lang'), ['ar', 'en'])) {
            $lang = $request->get('lang');
        }
        if ($request->hasHeader('lang') && in_array($request->header('lang'), ['ar', 'en'])) {
            $lang = $request->header('lang');
        }
        appSetLocale($lang);

        /*$token = $request->header('token');
        $authTokenData = TokenService::verifyAccessToken($token);
        $customerId = $authTokenData['id'];*/
    }

    #[NoReturn]
    public function jsonResponse($status, $message, $data = null, $errors = null, $statusCode = 200, $meta = null): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');

        $arr = array_filter([
            'status' => $status,
            'status_code' => $statusCode,
            'message' => $message,
            'meta' => $meta,
            'data' => $data,
            'errors' => $errors,
        ], static function ($value) {
            return !is_null($value) && $value !== '';
        });

        $this->display(
            collect($arr)->toJson(),
        );
    }
}