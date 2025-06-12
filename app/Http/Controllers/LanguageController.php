<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        $language = $request->language;
        
        // تأكد من أن اللغة صحيحة
        if (!in_array($language, ['en', 'ar'])) {
            $language = 'en';
        }
        
        // احفظ في الـ session
        Session::put('locale', $language);
        Session::save();
        
        // طبق فوراً
        App::setLocale($language);
        
        return redirect()->back()->with('success', __('Language changed successfully'));
    }
    
    public function current()
    {
        return response()->json([
            'current_language' => App::getLocale(),
            'available_languages' => [
                'en' => 'English',
                'ar' => 'العربية'
            ]
        ]);
    }
}
