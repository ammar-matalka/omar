<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class StepRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show step 1: Email verification
     */
    public function showStep1()
    {
        return view('auth.register-step1');
    }

    /**
     * Process step 1: Send verification code to email
     */
    public function processStep1(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        // Generate verification code
        $verificationCode = rand(100000, 999999);
        
        // Store email and code in session
        Session::put('registration_email', $request->email);
        Session::put('verification_code', $verificationCode);
        Session::put('code_sent_at', now());

        // Send verification email
        try {
            Mail::send('emails.verification-code', [
                'code' => $verificationCode,
                'email' => $request->email
            ], function ($message) use ($request) {
                $message->to($request->email)
                       ->subject(__('Email Verification Code'));
            });

            return redirect()->route('register.step2')
                ->with('success', __('Verification code sent to your email.'));
                
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Failed to send verification email. Please try again.'
            ]);
        }
    }

    /**
     * Show step 2: Verify code and complete registration
     */
    public function showStep2()
    {
        if (!Session::has('registration_email')) {
            return redirect()->route('register.step1')
                ->with('error', __('Please start the registration process.'));
        }

        return view('auth.register-step2', [
            'email' => Session::get('registration_email')
        ]);
    }

    /**
     * Process step 2: Verify code and create user
     */
    public function processStep2(Request $request)
    {
        if (!Session::has('registration_email') || !Session::has('verification_code')) {
            return redirect()->route('register.step1')
                ->with('error', __('Please start the registration process.'));
        }

        // Check if code is expired (10 minutes)
        $codeSentAt = Session::get('code_sent_at');
        if ($codeSentAt && now()->diffInMinutes($codeSentAt) > 10) {
            Session::forget(['registration_email', 'verification_code', 'code_sent_at']);
            return redirect()->route('register.step1')
                ->with('error', __('Verification code expired. Please try again.'));
        }

        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|digits:6',
            'name' => 'required|string|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
                'not_regex:/(123|abc|password|qwerty|admin|welcome)/i',
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'password.min' => __('The password must be at least 8 characters long.'),
            'password.regex' => __('The password must contain uppercase and lowercase letters, numbers, and special characters.'),
            'password.not_regex' => __('The password is too weak and cannot be used.'),
            'password.confirmed' => __('Confirm password does not match.'),
            'verification_code.digits' => __('Verification code must be 6 digits.'),
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verify the code
        $storedCode = Session::get('verification_code');
        if ($request->verification_code != $storedCode) {
            return back()->withErrors([
                'verification_code' => __('Invalid verification code.')
            ])->withInput();
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => Session::get('registration_email'),
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer',
            'email_verified_at' => now(), // Mark as verified since they used the code
        ]);

        // Clear session data
        Session::forget(['registration_email', 'verification_code', 'code_sent_at']);

        // Fire registered event
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        return redirect()->route('home')
            ->with('success', __('Registration completed successfully! Welcome!'));
    }

    /**
     * Resend verification code
     */
    public function resendCode(Request $request)
    {
        if (!Session::has('registration_email')) {
            return redirect()->route('register.step1')
                ->with('error', __('Please start the registration process.'));
        }

        // Check if we can resend (limit to once per 2 minutes)
        $lastSentAt = Session::get('code_sent_at');
        if ($lastSentAt && now()->diffInMinutes($lastSentAt) < 2) {
            return back()->with('error', __('Please wait before requesting another code.'));
        }

        // Generate new verification code
        $verificationCode = rand(100000, 999999);
        $email = Session::get('registration_email');
        
        // Update session
        Session::put('verification_code', $verificationCode);
        Session::put('code_sent_at', now());

        // Send verification email
        try {
            Mail::send('emails.verification-code', [
                'code' => $verificationCode,
                'email' => $email
            ], function ($message) use ($email) {
                $message->to($email)
                       ->subject(__('Email Verification Code'));
            });

            return back()->with('success', __('New verification code sent to your email.'));
                
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Failed to send verification email. Please try again.'
            ]);
        }
    }

    /**
     * Go back to step 1 (change email)
     */
    public function backToStep1()
    {
        Session::forget(['registration_email', 'verification_code', 'code_sent_at']);
        return redirect()->route('register.step1');
    }
}