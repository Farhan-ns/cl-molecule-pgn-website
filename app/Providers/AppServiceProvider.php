<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('send-whatsapp', function ($job) {
            return Limit::perMinutes(3, 1); // 1 job per 3 minutes
        });

        Response::macro('success', function ($data, int $httpCode = 200, string $message = '') {
            return Response::json([
                'success' => true,
                'code' => $httpCode,
                'message' => $message,
                'data' => $data,
            ], $httpCode);
        });

        Response::macro('error', function ($error, int $httpCode, string $message = '') {
            return Response::json([
                'success' => false,
                'code' => $httpCode,
                'message' => $message,
                'error' => $error,
            ], $httpCode);
        });
    }
}
