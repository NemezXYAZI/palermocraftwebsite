<?php

namespace App\Providers;

use App\Services\ServerStatus;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Статус сервера в шапці (кешується на 60 с)
        View::composer('partials.header', function ($view) {
            $view->with('serverStatus', app(ServerStatus::class)->get());
        });
    }
}
