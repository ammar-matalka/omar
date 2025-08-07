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
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        parent::boot();

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // ====================================
        // CUSTOM ROUTE MODEL BINDING للنظام التعليمي
        // ====================================

        // Custom route model binding للبطاقات الرقمية (Admin)
        Route::bind('digitalCard', function ($value) {
            $package = \App\Models\EducationalPackage::with('productType')->find($value);
            
            if (!$package) {
                abort(404, 'البطاقة الرقمية غير موجودة');
            }
            
            // التأكد من أن هذا منتج رقمي
            if (!$package->productType || !$package->productType->is_digital) {
                abort(404, 'المعرف المطلوب ليس بطاقة رقمية');
            }
            
            return $package;
        });

        // Custom route model binding للدوسيات الورقية (Admin)
        Route::bind('physicalBooklet', function ($value) {
            $package = \App\Models\EducationalPackage::with('productType')->find($value);
            
            if (!$package) {
                abort(404, 'الدوسية الورقية غير موجودة');
            }
            
            // التأكد من أن هذا منتج ورقي
            if (!$package->productType || $package->productType->is_digital) {
                abort(404, 'المعرف المطلوب ليس دوسية ورقية');
            }
            
            return $package;
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}