<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully');
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
        ]);
        
        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return redirect()->route('admin.profile.show')->with('success', 'Password changed successfully');
    }
}