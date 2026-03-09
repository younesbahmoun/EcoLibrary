<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Resources\Json\JsonResource;

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
        JsonResource::withoutWrapping();
        // 2 requests per minute per user or IP address
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // For login/register — limit brute force (5/min)
        // RateLimiter::for('auth', function (Request $request) {
        //     return Limit::perMinute(5)
        //         ->by($request->input('email') . '|' . $request->ip())
        //         ->response(function () {
        //             return response()->json([
        //                 'message' => 'Trop de tentatives. Réessayez dans 1 minute.',
        //             ], 429);
        //         });
        // });
    }
}
