<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';
    public const HOME = '/';
    public const ADMIN = '/dashboard';
    public const STUDENT = '/dashboard';
    public const TEACHER = '/dashboard';

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware(['web','localize', 'localizationRedirect', 'localeViewPath' ])
        ->namespace($this->namespace)->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')->
        middleware(['api','localization','cors'])
        ->namespace($this->namespace.'\api')->group(base_path('routes/api.php'));
    }
}
