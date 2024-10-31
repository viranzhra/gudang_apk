<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Blade;

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
        Schema::defaultStringLength(150);

        Http::macro('api', function () {
            return Http::baseUrl(config('app.api_url'));
        });

        Blade::directive('ifcan', function ($permission) {
            return "<?php if (in_array({$permission}, session('permissions', []))): ?>";
        });
    
        Blade::directive('endifcan', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('can', function ($permission) {
            return "<?php if (in_array({$permission}, session('permissions', []))): ?>";
        });
    
        Blade::directive('endcan', function () {
            return "<?php endif; ?>";
        });    

        Blade::directive('canany', function ($permissions) {
            return "<?php if (!empty(array_intersect($permissions, session('permissions', [])))): ?>";
        });

        Blade::directive('endcanany', function () {
            return "<?php endif; ?>";
        }); 

        Blade::directive('canall', function ($permissions) {
            return "<?php if (empty(array_diff($permissions, session('permissions', [])))): ?>";
        });

        Blade::directive('endcanall', function () {
            return "<?php endif; ?>";
        });    
    }
}