<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Jalur rute "home" aplikasi kamu.
     */
    public const HOME = '/dashboard';

    /**
     * Mendaftarkan semua route aplikasi.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // Route untuk API
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Route untuk tampilan web
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
