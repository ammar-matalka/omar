@extends('layouts.app')

@section('title', __('Login') . ' - ' . config('app.name'))

@push('styles')
<style>
    .auth-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--space-2xl) 0;
        background: linear-gradient(135deg, var(--primary-50), var(--secondary-50));
    }
    
    .auth-card {
        background: var(--surface);
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-2xl);
        overflow: hidden;
        width: 100%;
        max-width: 400px;
        margin: 0 var(--space-md);
    }
    
    .auth-header {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .auth-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><circle cx="20" cy="20" r="15"/><circle cx="80" cy="80" r="20"/><circle cx="60" cy="30" r="10"/></svg>');
        animation: float 15s ease-in-out infinite;
    }
    
    .auth-title {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: var(--space-sm);
        position: relative;
        z-index: 1;
    }
    
    .auth-subtitle {
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    
    .auth-body {
        padding: var(--space-2xl);
    }
    
    .auth-form {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .form-group {
        position: relative;
    }
    
    .form-input {
        width: 100%;
        padding: var(--space-md) var(--space-lg);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 1rem;
        transition: all var(--transition-fast);
        padding-left: 3rem;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
        transform: translateY(-2px);
    }
    
    .form-icon {
        position: absolute;
        left: var(--space-md);
        top: 50%;
        transform: translateY(-50%);
        color: var(--on-surface-variant);
        font-size: 1.125rem;
        transition: color var(--transition-fast);
    }
    
    .form-input:focus + .form-icon {
        color: var(--primary-500);
    }
    
    .password-toggle {
        position: absolute;
        right: var(--space-md);
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--on-surface-variant);
        cursor: pointer;
        font-size: 1.125rem;
        transition: color var(--transition-fast);
    }
    
    .password-toggle:hover {
        color: var(--primary-500);
    }
    
    .form-checkbox {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .checkbox-input {
        width: 1.125rem;
        height: 1.125rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--surface);
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .checkbox-input:checked {
        background: var(--primary-500);
        border-color: var(--primary-500);
    }
    
    .checkbox-label {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        cursor: pointer;
    }
    
    .auth-btn {
        width: 100%;
        padding: var(--space-md) var(--space-lg);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-normal);
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
    }
    
    .auth-btn:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .auth-btn:active {
        transform: translateY(0);
    }
    
    .auth-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .auth-links {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
        text-align: center;
        margin-top: var(--space-lg);
    }
    
    .auth-link {
        color: var(--primary-600);
        text-decoration: none;
        font-weight: 500;
        transition: color var(--transition-fast);
        font-size: 0.875rem;
    }
    
    .auth-link:hover {
        color: var(--primary-700);
        text-decoration: underline;
    }
    
    .divider {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin: var(--space-lg) 0;
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border-color);
    }
    
    .register-prompt {
        text-align: center;
        padding: var(--space-lg);
        background: var(--surface-variant);
        border-radius: 0 0 var(--radius-2xl) var(--radius-2xl);
        margin: 0 -var(--space-2xl) -var(--space-2xl);
        margin-top: var(--space-lg);
    }
    
    .register-text {
        color: var(--on-surface-variant);
        margin-bottom: var(--space-sm);
        font-size: 0.875rem;
    }
    
    .register-btn {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-lg);
        background: var(--surface);
        color: var(--primary-600);
        border: 2px solid var(--primary-200);
        border-radius: var(--radius-md);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all var(--transition-fast);
    }
    
    .register-btn:hover {
        background: var(--primary-50);
        border-color: var(--primary-300);
        transform: translateY(-1px);
    }
    
    .loading-spinner {
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(5deg); }
    }
    
    @media (max-width: 480px) {
        .auth-card {
            margin: 0 var(--space-sm);
            border-radius: var(--radius-lg);
        }
        
        .auth-header,
        .auth-body {
            padding: var(--space-lg);
        }
        
        .auth-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card fade-in">
        <!-- Header -->
        <div class="auth-header">
            <h1 class="auth-title">{{ __('Welcome Back') }}</h1>
            <p class="auth-subtitle">{{ __('Sign in to your account') }}</p>
        </div>
        
        <!-- Body -->
        <div class="auth-body">
            <form method="POST" action="{{ route('login') }}" class="auth-form" id="loginForm">
                @csrf
                
                <!-- Email -->
                <div class="form-group">
                    <input 
                        id="email" 
                        type="email" 
                        class="form-input @error('email') border-error-500 @enderror" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email" 
                        autofocus
                        placeholder="{{ __('Email Address') }}"
                    >
                    <i class="form-icon fas fa-envelope"></i>
                    @error('email')
                        <span class="text-error-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="form-group">
                    <input 
                        id="password" 
                        type="password" 
                        class="form-input @error('password') border-error-500 @enderror" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="{{ __('Password') }}"
                    >
                    <i class="form-icon fas fa-lock"></i>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="passwordToggleIcon"></i>
                    </button>
                    @error('password')
                        <span class="text-error-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Remember Me -->
                <div class="form-checkbox">
                    <input 
                        class="checkbox-input" 
                        type="checkbox" 
                        name="remember" 
                        id="remember" 
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label class="checkbox-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="auth-btn" id="submitBtn">
                    <span class="btn-text">{{ __('Sign In') }}</span>
                    <div class="loading-spinner" id="loadingSpinner" style="display: none;"></div>
                </button>
                
                <!-- Links -->
                <div class="auth-links">
                    @if (Route::has('password.request'))
                        <a class="auth-link" href="{{ route('password.request') }}">
                            <i class="fas fa-key"></i>
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
        
        <!-- Register Prompt -->
        <div class="register-prompt">
            <p class="register-text">{{ __("Don't have an account?") }}</p>
            <a href="{{ route('register') }}" class="register-btn">
                <i class="fas fa-user-plus"></i>
                {{ __('Create Account') }}
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Password toggle functionality
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + 'ToggleIcon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    // Form submission with loading state
    document.getElementById('loginForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = document.getElementById('loadingSpinner');
        
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        spinner.style.display = 'block';
    });
    
    // Input focus effects
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    // Intersection observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });
</script>
@endpush