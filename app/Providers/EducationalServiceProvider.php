<?php
// app/Providers/EducationalServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\OrderItem;
use App\Observers\OrderItemObserver;
use App\Services\EducationalService;

class EducationalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // تسجيل EducationalService
        $this->app->singleton(EducationalService::class, function ($app) {
            return new EducationalService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // تسجيل Observers
        OrderItem::observe(OrderItemObserver::class);

        // تسجيل الـ Routes إذا لم يتم تسجيلها في web.php
        if (file_exists(base_path('routes/educational.php'))) {
            $this->loadRoutesFrom(base_path('routes/educational.php'));
        }

        // تسجيل Views الخاصة بالنظام التعليمي
        $this->loadViewsFrom(resource_path('views/educational'), 'educational');

        // نشر الـ Assets إذا لزم الأمر
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../resources/views/educational' => resource_path('views/educational'),
            ], 'educational-views');

            $this->publishes([
                __DIR__.'/../../public/css/educational.css' => public_path('css/educational.css'),
                __DIR__.'/../../public/js/educational.js' => public_path('js/educational.js'),
            ], 'educational-assets');
        }
    }
}