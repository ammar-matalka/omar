<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
        $this->middleware('guest');
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
     * Get a validator for an incoming registration request.
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
                'regex:/[A-Z]/',
                'regex:/[a-z]/', 
                'regex:/[0-9]/', 
                'regex:/[@$!%*#?&]/', 
                'not_regex:/(123|abc|password|qwerty|admin|welcome)/i', 
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'password.min' => __('The password must be at least 8 characters long.'),
            'password.regex' => __('The password must contain uppercase and lowercase letters, numbers, and special characters.'),
            'password.not_regex' => __('The password is too weak and cannot be used.'),
            'password.confirmed' => __('Confirm password does not match.'),
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
            'email_verified_at' => now(), // Auto verify for now
        ]);
    }
}