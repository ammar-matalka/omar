@extends('layouts.app')

@section('title', __('Complete Registration') . ' - ' . config('app.name'))

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
        max-width: 500px;
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
        animation: float 25s ease-in-out infinite;
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
        transition: all var(--transition-normal);
    }
    
    .step.completed {
        background: white;
        color: var(--success-600);
        box-shadow: 0 0 0 4px rgba(255,255,255,0.3);
    }
    
    .step.active {
        background: white;
        color: var(--secondary-600);
        box-shadow: 0 0 0 4px rgba(255,255,255,0.3);
        animation: pulse 2s infinite;
    }
    
    .step-connector {
        width: 40px;
        height: 2px;
        background: white;
        position: relative;
        overflow: hidden;
    }
    
    .step-connector::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        background: var(--success-400);
        animation: progress 0.5s ease-out;
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
    
    .email-display {
        background: rgba(255,255,255,0.2);
        border-radius: var(--radius-md);
        padding: var(--space-sm) var(--space-md);
        margin-top: var(--space-sm);
        font-weight: 600;
        position: relative;
        z-index: 1;
    }
    
    .auth-body {
        padding: var(--space-2xl);
    }
    
    .verification-section {
        background: linear-gradient(135deg, var(--warning-50), var(--accent-50));
        border: 1px solid var(--warning-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        text-align: center;
    }
    
    .verification-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--warning-400), var(--accent-400));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-md);
        color: white;
        font-size: 1.5rem;
    }
    
    .verification-text {
        color: var(--warning-800);
        font-weight: 500;
        margin-bottom: var(--space-md);
    }
    
    .code-input-group {
        display: flex;
        gap: var(--space-sm);
        justify-content: center;
        margin-bottom: var(--space-lg);
    }
    
    .code-input {
        width: 50px;
        height: 50px;
        text-align: center;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: 1.25rem;
        font-weight: 600;
        transition: all var(--transition-fast);
    }
    
    .code-input:focus {
        outline: none;
        border-color: var(--secondary-500);
        box-shadow: 0 0 0 4px rgba(217, 70, 239, 0.1);
        transform: scale(1.05);
    }
    
    .resend-section {
        text-align: center;
        margin-bottom: var(--space-lg);
    }
    
    .resend-text {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        margin-bottom: var(--space-sm);
    }
    
    .resend-btn {
        background: none;
        border: none;
        color: var(--secondary-600);
        font-weight: 600;
        text-decoration: underline;
        cursor: pointer;
        font-size: 0.875rem;
        transition: color var(--transition-fast);
    }
    
    .resend-btn:hover:not(:disabled) {
        color: var(--secondary-700);
    }
    
    .resend-btn:disabled {
        color: var(--on-surface-variant);
        cursor: not-allowed;
        text-decoration: none;
    }
    
    .countdown {
        color: var(--warning-600);
        font-weight: 600;
    }
    
    .auth-form {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-md);
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
        top: calc(50% + 10px);
        transform: translateY(-50%);
        color: var(--on-surface-variant);
        font-size: 1.125rem;
        transition: color var(--transition-fast);
    }
    
    .form-input:focus + .form-icon {
        color: var(--secondary-500);
    }
    
    .password-toggle {
        position: absolute;
        right: var(--space-md);
        top: calc(50% + 10px);
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--on-surface-variant);
        cursor: pointer;
        font-size: 1.125rem;
        transition: color var(--transition-fast);
    }
    
    .password-toggle:hover {
        color: var(--secondary-500);
    }
    
    .password-strength {
        margin-top: var(--space-sm);
        padding: var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        transition: all var(--transition-fast);
    }
    
    .password-strength.weak {
        background: var(--error-50);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .password-strength.medium {
        background: var(--warning-50);
        color: var(--warning-700);
        border: 1px solid var(--warning-200);
    }
    
    .password-strength.strong {
        background: var(--success-50);
        color: var(--success-700);
        border: 1px solid var(--success-200);
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
        text-decoration: none;
    }
    
    .auth-btn:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--secondary-600), var(--secondary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        color: white;
    }
    
    .auth-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .back-btn {
        background: var(--surface);
        color: var(--on-surface-variant);
        border: 2px solid var(--border-color);
        margin-top: var(--space-md);
    }
    
    .back-btn:hover:not(:disabled) {
        background: var(--surface-variant);
        border-color: var(--border-hover);
        transform: translateY(-1px);
        color: var(--on-surface-variant);
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
        50% { transform: translateY(-10px) rotate(3deg); }
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    @keyframes progress {
        from { width: 0%; }
        to { width: 100%; }
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .code-input-group {
            gap: var(--space-xs);
        }
        
        .code-input {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
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
                <div class="step completed">
                    <i class="fas fa-check"></i>
                </div>
                <div class="step-connector"></div>
                <div class="step active">2</div>
            </div>
            
            <h1 class="auth-title">{{ __('Complete Registration') }}</h1>
            <p class="auth-subtitle">{{ __('Verify your email and create your account') }}</p>
            <div class="email-display">{{ $email }}</div>
        </div>
        
        <!-- Body -->
        <div class="auth-body">
            <!-- Verification Section -->
            <div class="verification-section">
                <div class="verification-icon">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <p class="verification-text">{{ __('Enter the 6-digit verification code sent to your email') }}</p>
                
                <!-- Code Input -->
                <div class="code-input-group">
                    <input type="text" class="code-input" maxlength="1" id="code1">
                    <input type="text" class="code-input" maxlength="1" id="code2">
                    <input type="text" class="code-input" maxlength="1" id="code3">
                    <input type="text" class="code-input" maxlength="1" id="code4">
                    <input type="text" class="code-input" maxlength="1" id="code5">
                    <input type="text" class="code-input" maxlength="1" id="code6">
                </div>
                
                <!-- Resend Section -->
                <div class="resend-section">
                    <p class="resend-text">{{ __("Didn't receive the code?") }}</p>
                    <button type="button" class="resend-btn" id="resendBtn" onclick="resendCode()">
                        {{ __('Resend Code') }}
                    </button>
                    <span class="countdown" id="countdown" style="display: none;"></span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('register.step2.process') }}" class="auth-form" id="registerForm">
                @csrf
                
                <!-- Hidden verification code input -->
                <input type="hidden" name="verification_code" id="verificationCode">
                
                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Full Name') }}</label>
                    <input 
                        id="name" 
                        type="text" 
                        class="form-input @error('name') error @enderror" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autocomplete="name"
                        placeholder="{{ __('Enter your full name') }}"
                    >
                    <i class="form-icon fas fa-user"></i>
                    @error('name')
                        <div class="error-message">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Password Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input 
                            id="password" 
                            type="password" 
                            class="form-input @error('password') error @enderror" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="{{ __('Create password') }}"
                            onkeyup="checkPasswordStrength()"
                        >
                        <i class="form-icon fas fa-lock"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="passwordToggleIcon"></i>
                        </button>
                        <div class="password-strength" id="passwordStrength" style="display: none;"></div>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            class="form-input" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="{{ __('Confirm password') }}"
                        >
                        <i class="form-icon fas fa-lock"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye" id="password_confirmationToggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Optional Fields Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone" class="form-label">{{ __('Phone Number') }} <span style="color: var(--on-surface-variant);">({{ __('Optional') }})</span></label>
                        <input 
                            id="phone" 
                            type="tel" 
                            class="form-input" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            autocomplete="tel"
                            placeholder="{{ __('Enter phone number') }}"
                        >
                        <i class="form-icon fas fa-phone"></i>
                    </div>
                    
                    <div class="form-group">
                        <label for="address" class="form-label">{{ __('Address') }} <span style="color: var(--on-surface-variant);">({{ __('Optional') }})</span></label>
                        <input 
                            id="address" 
                            type="text" 
                            class="form-input" 
                            name="address" 
                            value="{{ old('address') }}" 
                            autocomplete="address"
                            placeholder="{{ __('Enter your address') }}"
                        >
                        <i class="form-icon fas fa-map-marker-alt"></i>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="auth-btn" id="submitBtn" disabled>
                    <span class="btn-text">
                        <i class="fas fa-user-plus"></i>
                        {{ __('Create Account') }}
                    </span>
                    <div class="loading-spinner" id="loadingSpinner" style="display: none;"></div>
                </button>
                
                <!-- Back Button -->
                <a href="{{ route('register.back') }}" class="auth-btn back-btn">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Change Email') }}
                </a>
            </form>
@section('content')
<div class="auth-container" data-code-sent="{{ session('code_sent_at') ? '1' : '0' }}">
    <div class="auth-card fade-in">
        <!-- Header -->
        <div class="auth-header">
            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step completed">
                    <i class="fas fa-check"></i>
                </div>
                <div class="step-connector"></div>
                <div class="step active">2</div>
            </div>
            
            <h1 class="auth-title">{{ __('Complete Registration') }}</h1>
            <p class="auth-subtitle">{{ __('Verify your email and create your account') }}</p>
            <div class="email-display">{{ $email }}</div>
        </div>
        
        <!-- Body -->
        <div class="auth-body">
            <!-- Verification Section -->
            <div class="verification-section">
                <div class="verification-icon">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <p class="verification-text">{{ __('Enter the 6-digit verification code sent to your email') }}</p>
                
                <!-- Code Input -->
                <div class="code-input-group">
                    <input type="text" class="code-input" maxlength="1" id="code1">
                    <input type="text" class="code-input" maxlength="1" id="code2">
                    <input type="text" class="code-input" maxlength="1" id="code3">
                    <input type="text" class="code-input" maxlength="1" id="code4">
                    <input type="text" class="code-input" maxlength="1" id="code5">
                    <input type="text" class="code-input" maxlength="1" id="code6">
                </div>
                
                <!-- Resend Section -->
                <div class="resend-section">
                    <p class="resend-text">{{ __("Didn't receive the code?") }}</p>
                    <button type="button" class="resend-btn" id="resendBtn" onclick="resendCode()">
                        {{ __('Resend Code') }}
                    </button>
                    <span class="countdown" id="countdown" style="display: none;"></span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('register.step2.process') }}" class="auth-form" id="registerForm">
                @csrf
                
                <!-- Hidden verification code input -->
                <input type="hidden" name="verification_code" id="verificationCode">
                
                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Full Name') }}</label>
                    <input 
                        id="name" 
                        type="text" 
                        class="form-input @error('name') error @enderror" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autocomplete="name"
                        placeholder="{{ __('Enter your full name') }}"
                    >
                    <i class="form-icon fas fa-user"></i>
                    @error('name')
                        <div class="error-message">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Password Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input 
                            id="password" 
                            type="password" 
                            class="form-input @error('password') error @enderror" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="{{ __('Create password') }}"
                            onkeyup="checkPasswordStrength()"
                        >
                        <i class="form-icon fas fa-lock"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="passwordToggleIcon"></i>
                        </button>
                        <div class="password-strength" id="passwordStrength" style="display: none;"></div>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            class="form-input" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="{{ __('Confirm password') }}"
                        >
                        <i class="form-icon fas fa-lock"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye" id="password_confirmationToggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Optional Fields Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone" class="form-label">{{ __('Phone Number') }} <span style="color: var(--on-surface-variant);">({{ __('Optional') }})</span></label>
                        <input 
                            id="phone" 
                            type="tel" 
                            class="form-input" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            autocomplete="tel"
                            placeholder="{{ __('Enter phone number') }}"
                        >
                        <i class="form-icon fas fa-phone"></i>
                    </div>
                    
                    <div class="form-group">
                        <label for="address" class="form-label">{{ __('Address') }} <span style="color: var(--on-surface-variant);">({{ __('Optional') }})</span></label>
                        <input 
                            id="address" 
                            type="text" 
                            class="form-input" 
                            name="address" 
                            value="{{ old('address') }}" 
                            autocomplete="address"
                            placeholder="{{ __('Enter your address') }}"
                        >
                        <i class="form-icon fas fa-map-marker-alt"></i>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="auth-btn" id="submitBtn" disabled>
                    <span class="btn-text">
                        <i class="fas fa-user-plus"></i>
                        {{ __('Create Account') }}
                    </span>
                    <div class="loading-spinner" id="loadingSpinner" style="display: none;"></div>
                </button>
                
                <!-- Back Button -->
                <a href="{{ route('register.back') }}" class="auth-btn back-btn">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Change Email') }}
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let resendCountdown = 0;
        var codeSentAt = document.querySelector('.auth-container').getAttribute('data-code-sent') === '1';
        
        // Code input handling
        document.querySelectorAll('.code-input').forEach(function(input, index) {
            input.addEventListener('input', function(e) {
                if (this.value.length === 1) {
                    if (index < 5) {
                        document.getElementById('code' + (index + 2)).focus();
                    }
                }
                updateVerificationCode();
            });
            
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    document.getElementById('code' + index).focus();
                }
            });
            
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = e.clipboardData.getData('text');
                if (paste.length === 6 && /^\d+$/.test(paste)) {
                    for (let i = 0; i < 6; i++) {
                        document.getElementById('code' + (i + 1)).value = paste[i];
                    }
                    updateVerificationCode();
                }
            });
        });
        
        function updateVerificationCode() {
            let code = '';
            for (let i = 1; i <= 6; i++) {
                code += document.getElementById('code' + i).value;
            }
            document.getElementById('verificationCode').value = code;
            
            const submitBtn = document.getElementById('submitBtn');
            const name = document.getElementById('name').value;
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            
            if (code.length === 6 && name && password && passwordConfirm && password === passwordConfirm) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }
        
        // Password toggle functionality
        window.togglePassword = function(inputId) {
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
        };
        
        // Password strength checker
        window.checkPasswordStrength = function() {
            const password = document.getElementById('password').value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthDiv.style.display = 'none';
                return;
            }
            
            strengthDiv.style.display = 'block';
            
            let score = 0;
            let feedback = [];
            
            if (password.length >= 8) score++;
            else feedback.push('{{ __("At least 8 characters") }}');
            
            if (/[A-Z]/.test(password)) score++;
            else feedback.push('{{ __("Uppercase letter") }}');
            
            if (/[a-z]/.test(password)) score++;
            else feedback.push('{{ __("Lowercase letter") }}');
            
            if (/[0-9]/.test(password)) score++;
            else feedback.push('{{ __("Number") }}');
            
            if (/[@$!%*#?&]/.test(password)) score++;
            else feedback.push('{{ __("Special character") }}');
            
            if (score < 3) {
                strengthDiv.className = 'password-strength weak';
                strengthDiv.innerHTML = '{{ __("Weak password") }} - {{ __("Add") }}: ' + feedback.join(', ');
            } else if (score < 5) {
                strengthDiv.className = 'password-strength medium';
                strengthDiv.innerHTML = '{{ __("Medium password") }} - {{ __("Add") }}: ' + feedback.join(', ');
            } else {
                strengthDiv.className = 'password-strength strong';
                strengthDiv.innerHTML = '{{ __("Strong password") }} ✓';
            }
            
            updateVerificationCode();
        };
        
        // Resend code functionality
        window.resendCode = function() {
            const resendBtn = document.getElementById('resendBtn');
            const countdown = document.getElementById('countdown');
            
            fetch('{{ route("register.resend") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    startResendCountdown();
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
            });
        };
        
        function startResendCountdown() {
            const resendBtn = document.getElementById('resendBtn');
            const countdown = document.getElementById('countdown');
            
            resendCountdown = 120; // 2 minutes
            resendBtn.disabled = true;
            resendBtn.style.display = 'none';
            countdown.style.display = 'inline';
            
            const timer = setInterval(function() {
                const minutes = Math.floor(resendCountdown / 60);
                const seconds = resendCountdown % 60;
                countdown.textContent = '{{ __("Resend in") }} ' + minutes + ':' + seconds.toString().padStart(2, '0');
                
                resendCountdown--;
                
                if (resendCountdown < 0) {
                    clearInterval(timer);
                    resendBtn.disabled = false;
                    resendBtn.style.display = 'inline';
                    countdown.style.display = 'none';
                }
            }, 1000);
        }
        
        // Form validation
        document.querySelectorAll('#name, #password, #password_confirmation').forEach(function(input) {
            input.addEventListener('input', updateVerificationCode);
        });
        
        // Form submission
        document.getElementById('registerForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = document.getElementById('loadingSpinner');
            
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            spinner.style.display = 'block';
        });
        
        // Initialize countdown if needed
        if (codeSentAt) {
            startResendCountdown();
        }
        
        // Intersection observer for animations
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.fade-in').forEach(function(el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    });
</script>
@endpush