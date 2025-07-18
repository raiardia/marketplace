<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Route default web
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Route default API
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Route admin
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));

            // Route client
            Route::middleware('web')
                ->group(base_path('routes/client.php'));

            // Route seller
            Route::middleware('web')
                ->group(base_path('routes/seller.php'));
        });
    }
}
