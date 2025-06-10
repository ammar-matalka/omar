<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Platform;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        // Set pagination views
        Paginator::defaultView('custom.pagination');
        Paginator::defaultSimpleView('custom.simple-pagination');

        // Set locale from session
        $locale = Session::get('locale', config('app.locale', 'en'));
        App::setLocale($locale);
        
        // Set direction for RTL languages
        if ($locale === 'ar') {
            config(['app.direction' => 'rtl']);
        } else {
            config(['app.direction' => 'ltr']);
        }

        // Set default string length for MySQL
        Schema::defaultStringLength(191);

        // Share common data with all views
        View::composer('*', function ($view) {
            // Share categories
            $categories = Category::orderBy('name')->get();
            
            // Share platforms for educational cards
            $platforms = Platform::where('is_active', true)->orderBy('name')->get();
            
            // Share current language and direction
            $currentLocale = App::getLocale();
            $isRTL = $currentLocale === 'ar';
            
            // Share cart count if user is authenticated
            $cartCount = 0;
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $cart = $user->cart;
                $cartCount = $cart ? $cart->cartItems->sum('quantity') : 0;
            }
            
            // Share wishlist count if user is authenticated
            $wishlistCount = 0;
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $wishlistCount = $user->wishlist()->count();
            }
            
            $view->with([
                'categories' => $categories,
                'platforms' => $platforms,
                'currentLocale' => $currentLocale,
                'isRTL' => $isRTL,
                'cartCount' => $cartCount,
                'wishlistCount' => $wishlistCount,
                'availableLocales' => [
                    'en' => 'English',
                    'ar' => 'العربية'
                ]
            ]);
        });
    }
}