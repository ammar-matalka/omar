<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    
    public function handle(Request $request, Closure $next)
    {
        // احصل على اللغة من الـ session
        $locale = Session::get('locale', config('app.locale', 'en'));
        
        // تأكد من أن اللغة صحيحة
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }
        
        // طبق اللغة على التطبيق
        App::setLocale($locale);
        
        // ضبط الاتجاه للغات RTL
        if ($locale === 'ar') {
            config(['app.direction' => 'rtl']);
        } else {
            config(['app.direction' => 'ltr']);
        }
        
        return $next($request);
    }
}