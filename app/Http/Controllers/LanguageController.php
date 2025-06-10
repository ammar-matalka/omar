<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch language and redirect back
     */
    public function switch(Request $request)
    {
        $language = $request->language;
        
        // Validate language
        if (!in_array($language, ['en', 'ar'])) {
            $language = 'en'; // Default to English
        }
        
        // Set the application locale
        App::setLocale($language);
        
        // Store language preference in session
        Session::put('locale', $language);
        
        return redirect()->back()->with('success', __('Language changed successfully'));
    }
    
    /**
     * Get current language
     */
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