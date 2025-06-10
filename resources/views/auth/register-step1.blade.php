@extends('layouts.app')

@section('title', __('Register') . ' - ' . config('app.name'))

@push('styles')
<style>
    .auth-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--space-2xl) 0;
        background: linear-gradient(135deg, var(--secondary-50), var(--accent-50));
    }
    
    .auth-card {
        background: var(--surface);
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-2xl);
        overflow: hidden;
        width: 100%;
        max-width: 450px;
        margin: 0 var(--space-md);
    }
    
    .auth-header {
        background: linear-gradient(135deg, var(--secondary-500), var(--accent-500));
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
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><polygon points="50,15 60,40 85,40 66,55 76,80 50,65 24,80 34,55 15,40 40,40"/></svg>');
        animation: float 20s ease-in-out infinite;
    }
    
    .step-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
        position: relative;
        z-index: 1;
    }
    
    .step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        position: relative;
    }
    
    .step.active {
        background: white;
        color: var(--secondary-600);
        box-shadow: 0 0 0 4px rgba(255,255,255,0.3);
    }
    
    .step.inactive {
        background: rgba(255,255,255,0.3);
        color: white;
    }
    
    .step-connector {
        width: 40px;
        height: 2px;
        background: rgba(255,255,255,0.3);
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
        font-size: 0.95rem;
    }
    
    .auth-body {
        padding: var(--space-2xl);
    }
    
    .info-box {
        background: linear-gradient(135deg, var(--info-50), var(--primary-50));
        border: 1px solid var(--info-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        display: flex;
        gap: var(--space-md);
        align-items: flex-start;
    }
    
    .info-icon {
        color: var(--info-600);
        font-size: 1.25rem;
        margin-top: 2px;
    }
    
    .info-content h4 {
        font-weight: 600;
        color: var(--info-800);
        margin-bottom: var(--space-xs);
        font-size: 0.95rem;
    }
    
    .info-content p {
        color: var(--info-700);
        font-size: 0.875rem;
        line-height: 1.5;
        margin: 0;
    }
    
    .auth-form {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .form-group {
        position: relative;
    }
    
    .form-label {
        display: block;
        margin-bottom: var(--space-sm);
        font-weight: 600;
        color: var(--on-surface);
        font-size: 0.875rem;
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
        border-color: var(--secondary-500);
        box-shadow: 0 0 0 4px rgba(217, 70, 239, 0.1);
        transform: translateY(-2px);
    }
    
    .form-input.error {
        border-color: var(--error-500);
        background: var(--error-50);
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
        color: var(--secondary-500);
    }
    
    .error-message {
        color: var(--error-600);
        font-size: 0.75rem;
        margin-top: var(--space-xs);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .auth-btn {
        width: 100%;
        padding: var(--space-md) var(--space-lg);
        background: linear-gradient(135deg, var(--secondary-500), var(--secondary-600));
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
    
    .auth-btn:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--secondary-600), var(--secondary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .auth-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .loading-spinner {
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    .login-prompt {
        text-align: center;
        padding: var(--space-lg);
        background: var(--surface-variant);
        border-radius: 0 0 var(--radius-2xl) var(--radius-2xl);
        margin: 0 -var(--space-2xl) -var(--space-2xl);
        margin-top: var(--space-lg);
    }
    
    .login-text {
        color: var(--on-surface-variant);
        margin-bottom: var(--space-sm);
        font-size: 0.875rem;
    }
    
    .login-btn {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-lg);
        background: var(--surface);
        color: var(--secondary-600);
        border: 2px solid var(--secondary-200);
        border-radius: var(--radius-md);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all var(--transition-fast);
    }
    
    .login-btn:hover {
        background: var(--secondary-50);
        border-color: var(--secondary-300);
        transform: translateY(-1px);
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(3deg); }
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
        
        .step-indicator {
            gap: var(--space-sm);
        }
        
        .step {
            width: 35px;
            height: 35px;
            font-size: 0.75rem;
        }
        
        .step-connector {
            width: 30px;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card fade-in">
        <!-- Header -->
        <div class="auth-header">
            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step active">1</div>
                <div class="step-connector"></div>
                <div class="step inactive">2</div>
            </div>
            
            <h1 class="auth-title">{{ __('Create Account') }}</h1>
            <p class="auth-subtitle">{{ __('Enter your email to get started') }}</p>
        </div>
        
        <!-- Body -->
        <div class="auth-body">
            <!-- Information Box -->
            <div class="info-box">
                <i class="info-icon fas fa-shield-alt"></i>
                <div class="info-content">
                    <h4>{{ __('Secure Registration') }}</h4>
                    <p>{{ __('We will send a verification code to your email address to ensure your account security.') }}</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('register.step1.process') }}" class="auth-form" id="registerForm">
                @csrf
                
                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input 
                        id="email" 
                        type="email" 
                        class="form-input @error('email') error @enderror" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email" 
                        autofocus
                        placeholder="{{ __('Enter your email address') }}"
                    >
                    <i class="form-icon fas fa-envelope"></i>
                    @error('email')
                        <div class="error-message">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="auth-btn" id="submitBtn">
                    <span class="btn-text">
                        <i class="fas fa-paper-plane"></i>
                        {{ __('Send Verification Code') }}
                    </span>
                    <div class="loading-spinner" id="loadingSpinner" style="display: none;"></div>
                </button>
            </form>
        </div>
        
        <!-- Login Prompt -->
        <div class="login-prompt">
            <p class="login-text">{{ __('Already have an account?') }}</p>
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-sign-in-alt"></i>
                {{ __('Sign In') }}
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form submission with loading state
    document.getElementById('registerForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = document.getElementById('loadingSpinner');
        
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        spinner.style.display = 'block';
    });
    
    // Email validation
    const emailInput = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    emailInput.addEventListener('input', function() {
        const email = this.value;
        const submitBtn = document.getElementById('submitBtn');
        
        if (email && emailRegex.test(email)) {
            this.classList.remove('error');
            submitBtn.disabled = false;
        } else if (email) {
            this.classList.add('error');
            submitBtn.disabled = true;
        } else {
            this.classList.remove('error');
            submitBtn.disabled = false;
        }
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