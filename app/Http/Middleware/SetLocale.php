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
        // احصل على اللغة من الـ session (افتراضياً العربية)
        $locale = Session::get('locale', config('app.locale', 'ar'));
        
        // تأكد من أن اللغة صحيحة (العربية افتراضياً)
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }
        
        // طبق اللغة على التطبيق
        App::setLocale($locale);
        
        // ضبط الاتجاه للغات RTL (العربية دائماً RTL)
        if ($locale === 'ar') {
            config(['app.direction' => 'rtl']);
        } else {
            config(['app.direction' => 'ltr']);
        }
        
        // ضبط المنطقة الزمنية حسب اللغة
        if ($locale === 'ar') {
            config(['app.timezone' => 'Asia/Amman']); // أو أي منطقة زمنية عربية أخرى
        }
        
        // مشاركة البيانات مع جميع العروض
        view()->share([
            'currentLocale' => $locale,
            'direction' => config('app.direction'),
            'isRtl' => $locale === 'ar',
            'supportedLocales' => config('app.supported_locales', ['ar' => 'العربية', 'en' => 'English'])
        ]);
        
        return $next($request);
    }
}