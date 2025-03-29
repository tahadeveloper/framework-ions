<?php

use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;

#[NoReturn]
function jsonResponse($status, $message, $data = null, $errors = null, $statusCode = 200, $meta = null): void
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

    display(
        collect($arr)->toJson(),
    );
}

function handleStatusCode(Throwable $e): int
{
    $code = $e->getCode();
    if (is_string($code)) {
        $code = 500;
    }
    return ($code > 0 && $code < 600) ? $code : 500;
}

function now($tz = null): Carbon
{
    return Carbon::now($tz);
}
