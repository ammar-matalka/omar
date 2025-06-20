<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        return view('admin.profile.show', [
            'user' => Auth::user()
        ]);
    }

    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ], [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نص.',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.string' => 'البريد الإلكتروني يجب أن يكون نص.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون صحيح.',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.profile.show')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    public function changePassword()
    {
        return view('admin.profile.change-password');
    }
    
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة.',
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة.',
            'password.required' => 'كلمة المرور الجديدة مطلوبة.',
            'password.string' => 'كلمة المرور يجب أن تكون نص.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return redirect()->route('admin.profile.show')->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}