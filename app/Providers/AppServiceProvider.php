<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ViewService;
use App\Services\ComponentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 서비스들을 싱글톤으로 등록
        $this->app->singleton(ViewService::class, function ($app) {
            return new ViewService();
        });

        $this->app->singleton(ComponentService::class, function ($app) {
            return new ComponentService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
