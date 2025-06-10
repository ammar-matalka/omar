<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the locale from session, URL parameter, or default to 'en'
        $locale = Session::get('locale', config('app.locale', 'en'));
        
        // Check if locale is passed as URL parameter
        if ($request->has('lang')) {
            $requestedLocale = $request->get('lang');
            if (in_array($requestedLocale, ['en', 'ar'])) {
                $locale = $requestedLocale;
                Session::put('locale', $locale);
            }
        }
        
        // Validate locale
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }
        
        // Set the application locale
        App::setLocale($locale);
        
        // Set direction for RTL languages
        if ($locale === 'ar') {
            config(['app.direction' => 'rtl']);
        } else {
            config(['app.direction' => 'ltr']);
        }
        
        return $next($request);
    }
}