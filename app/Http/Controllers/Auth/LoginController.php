<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | وحدة تحكم تسجيل الدخول
    |--------------------------------------------------------------------------
    |
    | تتعامل هذه الوحدة مع مصادقة المستخدمين للتطبيق وإعادة توجيههم
    | إلى الشاشة الرئيسية. تستخدم الوحدة خاصية لتوفير هذه الوظائف
    | بشكل مريح لتطبيقاتك.
    |
    */

    use AuthenticatesUsers;

    /**
     * مكان إعادة توجيه المستخدمين بعد تسجيل الدخول.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * إنشاء مثيل جديد من الوحدة.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * الحصول على مسار إعادة التوجيه بعد تسجيل الدخول بناءً على دور المستخدم.
     */
    protected function redirectTo()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user && $user->isAdmin()) {
            return '/admin/dashboard';
        }
        
        return '/home';
    }

    /**
     * التعامل مع طلب تسجيل الدخول للتطبيق.
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // تم إزالة التحقق من البريد الإلكتروني كما طلبت
        
        // إذا كانت الفئة تستخدم خاصية ThrottlesLogins، يمكننا تلقائياً تقييد
        // محاولات تسجيل الدخول لهذا التطبيق. سنربط هذا بـ username و
        // عنوان IP للعميل الذي يقوم بهذه الطلبات في هذا التطبيق.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // إذا كانت محاولة تسجيل الدخول غير ناجحة، سنزيد عدد المحاولات
        // لتسجيل الدخول وإعادة توجيه المستخدم إلى نموذج تسجيل الدخول. بطبيعة الحال، عندما
        // يتجاوز هذا المستخدم العدد الأقصى للمحاولات سيتم قفله.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * تم مصادقة المستخدم.
     */
    protected function authenticated(Request $request, $user)
    {
        /** @var \App\Models\User $user */
        
        // إضافة أي منطق بعد تسجيل الدخول هنا
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'أهلاً بعودتك، أيها المدير!');
        }

        return redirect()->route('home')
            ->with('success', 'أهلاً بعودتك!');
    }

    /**
     * تسجيل خروج المستخدم من التطبيق.
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'تم تسجيل خروجك بنجاح.');
    }
}