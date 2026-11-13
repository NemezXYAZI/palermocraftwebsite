<?php

namespace App\Providers;

use App\Services\ServerStatus;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Один екземпляр на запит: статус сервера питаємо лише раз, навіть якщо він на сторінці кілька разів
        $this->app->singleton(ServerStatus::class);
    }

    public function boot(): void
    {
        // Мову (і Carbon) виставляє middleware SetLocale для кожного запиту
        Carbon::setLocale(app()->getLocale());

        Paginator::defaultView('vendor.pagination.palermo');
        Paginator::defaultSimpleView('vendor.pagination.palermo-simple');
    }
}
