<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // فلتر البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // فلتر الدور
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // فلتر الحالة
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        // فلتر التاريخ
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        // الترتيب
        $sortField = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        $validSortFields = ['name', 'email', 'created_at', 'last_login_at'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'name';
        }

        if ($sortField === 'last_login_at') {
            $query->orderByRaw("last_login_at IS NULL, last_login_at {$sortOrder}");
        } else {
            $query->orderBy($sortField, $sortOrder);
        }

        $users = $query->withCount(['orders', 'testimonials'])
                      ->paginate(15)
                      ->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    /**
     * عرض نموذج إنشاء مستخدم جديد.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * حفظ مستخدم جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:customer,admin',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email_verified' => 'boolean',
            'send_welcome_email' => 'boolean',
        ], [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نص.',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.string' => 'البريد الإلكتروني يجب أن يكون نص.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صحيح.',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'phone.string' => 'رقم الهاتف يجب أن يكون نص.',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 20 حرف.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.string' => 'كلمة المرور يجب أن تكون نص.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'role.required' => 'دور المستخدم مطلوب.',
            'role.in' => 'دور المستخدم يجب أن يكون مستخدم أو مدير.',
            'avatar.image' => 'الصورة الشخصية يجب أن تكون صورة.',
            'avatar.mimes' => 'الصورة الشخصية يجب أن تكون من نوع: jpeg, png, jpg, gif.',
            'avatar.max' => 'حجم الصورة الشخصية يجب ألا يتجاوز 2 ميجابايت.',
        ]);

        // التعامل مع رفع الصورة الشخصية
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // إنشاء المستخدم
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'avatar' => $avatarPath,
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
        ]);

        // إرسال بريد ترحيب إذا تم طلبه
        if ($request->boolean('send_welcome_email')) {
            // يمكنك تنفيذ منطق إرسال البريد الإلكتروني هنا
            // Mail::to($user)->send(new WelcomeEmail($user));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * عرض المستخدم المحدد.
     */
    public function show(User $user)
    {
        $user->load(['orders', 'testimonials', 'wishlist']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * عرض نموذج تعديل المستخدم المحدد.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * تحديث المستخدم المحدد في قاعدة البيانات.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:customer,admin',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
            'email_verified' => 'boolean',
            'remove_avatar' => 'boolean',
        ], [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نص.',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.string' => 'البريد الإلكتروني يجب أن يكون نص.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صحيح.',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'phone.string' => 'رقم الهاتف يجب أن يكون نص.',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 20 حرف.',
            'role.required' => 'دور المستخدم مطلوب.',
            'role.in' => 'دور المستخدم يجب أن يكون مستخدم أو مدير.',
            'avatar.image' => 'الصورة الشخصية يجب أن تكون صورة.',
            'avatar.mimes' => 'الصورة الشخصية يجب أن تكون من نوع: jpeg, png, jpg, gif.',
            'avatar.max' => 'حجم الصورة الشخصية يجب ألا يتجاوز 2 ميجابايت.',
            'password.string' => 'كلمة المرور يجب أن تكون نص.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        // منع المستخدمين من تغيير دورهم الخاص
        if ($user->id === Auth::id()) {
            unset($validated['role']);
        }

        // التعامل مع رفع/إزالة الصورة الشخصية
        if ($request->boolean('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $validated['avatar'] = null;
        } elseif ($request->hasFile('avatar')) {
            // حذف الصورة الشخصية القديمة
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            // الاحتفاظ بالصورة الشخصية الموجودة
            unset($validated['avatar']);
        }

        // التعامل مع تحديث كلمة المرور
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // التعامل مع تأكيد البريد الإلكتروني
        if ($request->boolean('email_verified') && !$user->email_verified_at) {
            $validated['email_verified_at'] = now();
        } elseif (!$request->boolean('email_verified') && $user->email_verified_at) {
            $validated['email_verified_at'] = null;
        }

        // إزالة email_verified من البيانات المعتمدة حيث نتعامل معها بشكل منفصل
        unset($validated['email_verified'], $validated['remove_avatar']);

        // تحديث المستخدم
        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح.');
    }

    /**
     * حذف المستخدم المحدد من قاعدة البيانات.
     */
    public function destroy(User $user)
    {
        // منع المستخدمين من حذف أنفسهم
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }

        // حذف الصورة الشخصية إذا كانت موجودة
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // حذف المستخدم
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح.');
    }

    /**
     * تبديل حالة المستخدم (تفعيل/إلغاء تفعيل)
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك تغيير حالة حسابك الخاص.'
            ], 403);
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة المستخدم بنجاح.',
            'is_active' => $user->is_active
        ]);
    }

    /**
     * تأكيد بريد المستخدم الإلكتروني
     */
    public function verifyEmail(User $user)
    {
        if ($user->email_verified_at) {
            return redirect()->back()
                ->with('info', 'البريد الإلكتروني مؤكد بالفعل.');
        }

        $user->update([
            'email_verified_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'تم تأكيد البريد الإلكتروني بنجاح.');
    }

    /**
     * إرسال بريد إعادة تعيين كلمة المرور
     */
    public function sendPasswordReset(User $user)
    {
        // توليد رمز إعادة تعيين كلمة المرور وإرسال البريد الإلكتروني
        // هذا نموذج أولي - قم بتنفيذ منطق إعادة تعيين كلمة المرور الفعلي
        
        return redirect()->back()
            ->with('success', 'تم إرسال بريد إعادة تعيين كلمة المرور بنجاح.');
    }

    /**
     * الحصول على إحصائيات المستخدمين للوحة التحكم
     */
    public function getStatistics()
    {
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)
                                         ->whereYear('created_at', now()->year)
                                         ->count(),
            'active_users_last_30_days' => User::where('last_login_at', '>=', now()->subDays(30))->count(),
        ];

        return response()->json($stats);
    }

    /**
     * تصدير بيانات المستخدمين
     */
    public function export(Request $request)
    {
        // هذا نموذج أولي لوظيفة التصدير
        // يمكنك تنفيذ تصدير CSV أو Excel أو PDF هنا
        
        $users = User::all();
        
        // مثال: توليد CSV
        $filename = 'users_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            
            // عناوين CSV
            fputcsv($file, ['المعرف', 'الاسم', 'البريد الإلكتروني', 'الهاتف', 'الدور', 'البريد مؤكد', 'تاريخ الإنشاء', 'آخر دخول']);
            
            // بيانات CSV
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->role ?? 'customer',
                    $user->email_verified_at ? 'نعم' : 'لا',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'أبداً',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}