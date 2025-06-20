@extends('layouts.app')

@section('title', __('تسجيل حساب جديد') . ' - ' . config('app.name'))

@push('styles')
<style>
    /* إضافة دعم RTL */
    :root {
        --direction: rtl;
    }
    
    body {
        direction: rtl;
        text-align: right;
    }
    
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
        right: -50%; /* تغيير من left إلى right */
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><polygon points="50,15 60,40 85,40 66,55 76,80 50,65 24,80 34,55 15,40 40,40"/></svg>');
        animation: float 20s ease-in-out infinite;
    }
    
    .auth-icon {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-lg);
        font-size: 1.5rem;
        position: relative;
        z-index: 1;
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
        padding-right: 3rem; /* تغيير من left إلى right */
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
        right: var(--space-md); /* تغيير من left إلى right */
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
        left: var(--space-md); /* تغيير من right إلى left */
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
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
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
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card fade-in">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1 class="auth-title">{{ __('إنشاء حساب جديد') }}</h1>
            <p class="auth-subtitle">{{ __('انضم إلينا اليوم وابدأ رحلتك') }}</p>
        </div>
        
        <!-- Body -->
        <div class="auth-body">
            <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm">
                @csrf
                
                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('الاسم الكامل') }}</label>
                    <input 
                        id="name" 
                        type="text" 
                        class="form-input @error('name') error @enderror" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autocomplete="name"
                        autofocus
                        placeholder="{{ __('أدخل اسمك الكامل') }}"
                    >
                    <i class="form-icon fas fa-user"></i>
                    @error('name')
                        <div class="error-message">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('البريد الإلكتروني') }}</label>
                    <input 
                        id="email" 
                        type="email" 
                        class="form-input @error('email') error @enderror" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email"
                        placeholder="{{ __('أدخل عنوان بريدك الإلكتروني') }}"
                    >
                    <i class="form-icon fas fa-envelope"></i>
                    @error('email')
                        <div class="error-message">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Password Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('كلمة المرور') }}</label>
                        <input 
                            id="password" 
                            type="password" 
                            class="form-input @error('password') error @enderror" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="{{ __('إنشاء كلمة مرور') }}"
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
                        <label for="password_confirmation" class="form-label">{{ __('تأكيد كلمة المرور') }}</label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            class="form-input" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="{{ __('تأكيد كلمة المرور') }}"
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
                        <label for="phone" class="form-label">{{ __('رقم الهاتف') }} <span style="color: var(--on-surface-variant);">({{ __('اختياري') }})</span></label>
                        <input 
                            id="phone" 
                            type="tel" 
                            class="form-input" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            autocomplete="tel"
                            placeholder="{{ __('أدخل رقم الهاتف') }}"
                        >
                        <i class="form-icon fas fa-phone"></i>
                    </div>
                    
                    <div class="form-group">
                        <label for="address" class="form-label">{{ __('العنوان') }} <span style="color: var(--on-surface-variant);">({{ __('اختياري') }})</span></label>
                        <input 
                            id="address" 
                            type="text" 
                            class="form-input" 
                            name="address" 
                            value="{{ old('address') }}" 
                            autocomplete="address"
                            placeholder="{{ __('أدخل عنوانك') }}"
                        >
                        <i class="form-icon fas fa-map-marker-alt"></i>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="auth-btn" id="submitBtn">
                    <span class="btn-text">
                        <i class="fas fa-user-plus"></i>
                        {{ __('إنشاء حساب') }}
                    </span>
                    <div class="loading-spinner" id="loadingSpinner" style="display: none;"></div>
                </button>
            </form>
        </div>
        
        <!-- Login Prompt -->
        <div class="login-prompt">
            <p class="login-text">{{ __('لديك حساب بالفعل؟') }}</p>
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-sign-in-alt"></i>
                {{ __('تسجيل الدخول') }}
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
    
    // Password strength checker
    function checkPasswordStrength() {
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
        else feedback.push('{{ __("8 أحرف على الأقل") }}');
        
        if (/[A-Z]/.test(password)) score++;
        else feedback.push('{{ __("حرف كبير") }}');
        
        if (/[a-z]/.test(password)) score++;
        else feedback.push('{{ __("حرف صغير") }}');
        
        if (/[0-9]/.test(password)) score++;
        else feedback.push('{{ __("رقم") }}');
        
        if (/[@$!%*#?&]/.test(password)) score++;
        else feedback.push('{{ __("حرف خاص") }}');
        
        if (score < 3) {
            strengthDiv.className = 'password-strength weak';
            strengthDiv.innerHTML = '{{ __("كلمة مرور ضعيفة") }} - {{ __("أضف") }}: ' + feedback.join('، ');
        } else if (score < 5) {
            strengthDiv.className = 'password-strength medium';
            strengthDiv.innerHTML = '{{ __("كلمة مرور متوسطة") }} - {{ __("أضف") }}: ' + feedback.join('، ');
        } else {
            strengthDiv.className = 'password-strength strong';
            strengthDiv.innerHTML = '{{ __("كلمة مرور قوية") }} ✓';
        }
    }
    
    // Form submission with loading state
    document.getElementById('registerForm').addEventListener('submit', function() {
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