<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout']);
    }

    /**
     * Show the registration form (redirect to step registration)
     */
    public function showRegistrationForm()
    {
        return redirect()->route('register.step1');
    }

    /**
     * Handle a registration request (redirect to step registration)
     */
    public function register(Request $request)
    {
        return redirect()->route('register.step1');
    }

    /**
     * Show step 1 registration form (email input)
     */
    public function showStep1()
    {
        return view('auth.register.step1');
    }

    /**
     * Process step 1 - validate email and send verification code
     */
    public function processStep1(Request $request)
    {
        // Validate email
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
        ], [
            'email.unique' => __('This email is already registered. Please use a different email or try logging in.'),
            'email.required' => __('Email address is required.'),
            'email.email' => __('Please enter a valid email address.'),
        ]);

        $email = $request->email;

        // Generate 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store email and code in session
        Session::put([
            'registration_email' => $email,
            'verification_code' => $verificationCode,
            'code_sent_at' => now(),
            'code_expires_at' => now()->addMinutes(10), // Code expires in 10 minutes
        ]);

        // Send verification email
        try {
            $this->sendVerificationEmail($email, $verificationCode);
            
            return redirect()->route('register.step2', ['email' => $email])
                ->with('success', __('Verification code has been sent to your email.'));
                
        } catch (\Exception $e) {
            Log::error('Failed to send verification email: ' . $e->getMessage());
            
            return back()->withErrors([
                'email' => __('Failed to send verification email. Please try again.')
            ])->withInput();
        }
    }

    /**
     * Show step 2 registration form (verification + user details)
     */
    public function showStep2(Request $request)
    {
        $email = $request->get('email') ?? Session::get('registration_email');
        
        if (!$email || !Session::has('verification_code')) {
            return redirect()->route('register.step1')
                ->withErrors(['email' => __('Please start the registration process again.')]);
        }

        // Check if code is expired
        if (Session::get('code_expires_at') && now()->isAfter(Session::get('code_expires_at'))) {
            Session::forget(['verification_code', 'code_sent_at', 'code_expires_at']);
            return redirect()->route('register.step1')
                ->withErrors(['email' => __('Verification code has expired. Please start again.')]);
        }

        return view('auth.register.step2', compact('email'));
    }

    /**
     * Process step 2 - verify code and create user
     */
    public function processStep2(Request $request)
    {
        // Check if session data exists
        if (!Session::has('verification_code') || !Session::has('registration_email')) {
            return redirect()->route('register.step1')
                ->withErrors(['error' => __('Session expired. Please start registration again.')]);
        }

        // Check if code is expired
        if (Session::get('code_expires_at') && now()->isAfter(Session::get('code_expires_at'))) {
            Session::forget(['verification_code', 'code_sent_at', 'code_expires_at']);
            return redirect()->route('register.step1')
                ->withErrors(['error' => __('Verification code has expired. Please start again.')]);
        }

        // Validate input
        $validator = $this->validator($request->all());
        
        // Add verification code validation
        $validator->after(function ($validator) use ($request) {
            $inputCode = $request->verification_code;
            $sessionCode = Session::get('verification_code');
            
            if ($inputCode !== $sessionCode) {
                $validator->errors()->add('verification_code', __('Invalid verification code. Please check and try again.'));
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Create user
            $userData = $request->all();
            $userData['email'] = Session::get('registration_email');
            
            $user = $this->create($userData);

            // Clear session data
            Session::forget([
                'registration_email',
                'verification_code', 
                'code_sent_at',
                'code_expires_at'
            ]);

            // Log the user in
            Auth::login($user);

            return redirect($this->redirectTo)
                ->with('success', __('Registration completed successfully! Welcome to our platform.'));

        } catch (\Exception $e) {
            Log::error('Failed to create user: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => __('Failed to create account. Please try again.')
            ])->withInput();
        }
    }

    /**
     * Resend verification code
     */
    public function resendCode(Request $request)
    {
        if (!Session::has('registration_email')) {
            return response()->json([
                'success' => false,
                'message' => __('Session expired. Please start registration again.')
            ], 400);
        }

        $email = Session::get('registration_email');
        
        // Check rate limiting (prevent spam)
        $lastSent = Session::get('code_sent_at');
        if ($lastSent && now()->diffInSeconds($lastSent) < 60) {
            return response()->json([
                'success' => false,
                'message' => __('Please wait before requesting another code.')
            ], 429);
        }

        // Generate new code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update session
        Session::put([
            'verification_code' => $verificationCode,
            'code_sent_at' => now(),
            'code_expires_at' => now()->addMinutes(10),
        ]);

        try {
            $this->sendVerificationEmail($email, $verificationCode);
            
            return response()->json([
                'success' => true,
                'message' => __('Verification code has been resent to your email.')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to resend verification email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => __('Failed to send verification email. Please try again.')
            ], 500);
        }
    }

    /**
     * Go back to step 1 (change email)
     */
    public function back()
    {
        // Clear session data
        Session::forget([
            'registration_email',
            'verification_code',
            'code_sent_at',
            'code_expires_at'
        ]);

        return redirect()->route('register.step1');
    }

    /**
     * Send verification email
     *
     * @param string $email
     * @param string $code
     * @return void
     */
    private function sendVerificationEmail($email, $code)
    {
        $appName = config('app.name');
        
        $subject = __('Email Verification Code - :app', ['app' => $appName]);
        
        $message = __('Hello!') . "\n\n" .
                  __('Your verification code for :app is:', ['app' => $appName]) . "\n\n" .
                  "**{$code}**\n\n" .
                  __('This code will expire in 10 minutes.') . "\n\n" .
                  __('If you did not request this code, please ignore this email.') . "\n\n" .
                  __('Thank you,') . "\n" .
                  $appName;

        Mail::raw($message, function ($mail) use ($email, $subject) {
            $mail->to($email)
                 ->subject($subject);
        });

        Log::info("Verification code sent to: {$email}");
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'verification_code' => ['required', 'string', 'size:6'],
            'name' => ['required', 'string', 'max:255'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // Must contain uppercase
                'regex:/[a-z]/',      // Must contain lowercase  
                'regex:/[0-9]/',      // Must contain number
                'regex:/[@$!%*#?&]/', // Must contain special character
                'not_regex:/(123|abc|password|qwerty|admin|welcome)/i', // Weak passwords
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'verification_code.required' => __('Verification code is required.'),
            'verification_code.size' => __('Verification code must be 6 digits.'),
            'name.required' => __('Full name is required.'),
            'name.max' => __('Full name cannot exceed 255 characters.'),
            'password.min' => __('Password must be at least 8 characters long.'),
            'password.regex' => __('Password must contain uppercase and lowercase letters, numbers, and special characters.'),
            'password.not_regex' => __('Password is too weak and cannot be used.'),
            'password.confirmed' => __('Password confirmation does not match.'),
            'phone.max' => __('Phone number cannot exceed 20 characters.'),
            'address.max' => __('Address cannot exceed 500 characters.'),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
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
            'email_verified_at' => now(), // Auto verify since they verified email with code
        ]);
    }
}