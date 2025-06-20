<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | وحدة تحكم التسجيل
    |--------------------------------------------------------------------------
    |
    | تتعامل هذه الوحدة مع تسجيل المستخدمين الجدد بالإضافة إلى
    | التحقق من صحتهم وإنشاءهم. افتراضياً تستخدم هذه الوحدة خاصية
    | لتوفير هذه الوظائف دون الحاجة لأي كود إضافي.
    |
    */

    use RegistersUsers;

    /**
     * مكان إعادة توجيه المستخدمين بعد التسجيل.
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
        $this->middleware('guest');
    }

    /**
     * عرض نموذج تسجيل التطبيق.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * التعامل مع طلب تسجيل للتطبيق.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        Auth::login($user);

        return redirect($this->redirectPath())
            ->with('success', 'تم إكمال التسجيل بنجاح! أهلاً بك في منصتنا.');
    }

    /**
     * الحصول على مُدقق لطلب تسجيل وارد.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // يجب أن تحتوي على حرف كبير
                'regex:/[a-z]/',      // يجب أن تحتوي على حرف صغير
                'regex:/[0-9]/',      // يجب أن تحتوي على رقم
                'regex:/[@$!%*#?&]/', // يجب أن تحتوي على رمز خاص
                'not_regex:/(123|abc|password|qwerty|admin|welcome)/i', // كلمات مرور ضعيفة
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'الاسم الكامل مطلوب.',
            'name.max' => 'الاسم الكامل لا يمكن أن يتجاوز 255 حرف.',
            'email.required' => 'عنوان البريد الإلكتروني مطلوب.',
            'email.email' => 'يرجى إدخال عنوان بريد إلكتروني صحيح.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل بالفعل. يرجى استخدام بريد إلكتروني مختلف أو محاولة تسجيل الدخول.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف.',
            'password.regex' => 'كلمة المرور يجب أن تحتوي على أحرف كبيرة وصغيرة وأرقام ورموز خاصة.',
            'password.not_regex' => 'كلمة المرور ضعيفة جداً ولا يمكن استخدامها.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'phone.max' => 'رقم الهاتف لا يمكن أن يتجاوز 20 حرف.',
            'address.max' => 'العنوان لا يمكن أن يتجاوز 500 حرف.',
        ]);
    }

    /**
     * إنشاء مثيل مستخدم جديد بعد تسجيل صحيح.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => 'customer',
            'email_verified_at' => now(), // تأكيد البريد الإلكتروني تلقائياً (تم إلغاء نظام التحقق)
        ]);
    }
}