<?php

namespace App;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Ions\Foundation\Singleton;

class Booting extends Singleton
{
    public static function boot(): void
    {
        // config changes
        // preload options
    }

    #=> call api http
    public static function httpClient(): PendingRequest
    {
        return Http::withOptions([
            'debug' => env('API_CLIENT_DEBUG', false),
            'base_uri' => config('app_url') . '/api/',
            'timeout' => 50,
        ])->withHeaders([
            'Authorization' => env('JWT_KEY')
        ]);
    }
}
