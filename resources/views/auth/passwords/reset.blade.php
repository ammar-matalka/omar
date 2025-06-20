@extends('layouts.app')

@section('title', __('تعيين كلمة مرور جديدة') . ' - ' . config('app.name'))

@push('styles')
<style>
    /* نفس التعديلات السابقة مع إضافة RTL */
    body[dir="rtl"] .auth-container,
    body[dir="rtl"] .auth-card,
    body[dir="rtl"] .auth-header,
    body[dir="rtl"] .auth-body,
    body[dir="rtl"] .auth-form,
    body[dir="rtl"] .auth-links {
        direction: rtl;
        text-align: right;
    }

    body[dir="rtl"] .form-icon {
        right: var(--space-md);
        left: auto;
    }

    body[dir="rtl"] .form-input {
        padding-right: 3rem;
        padding-left: var(--space-lg);
    }

    body[dir="rtl"] .password-toggle {
        left: var(--space-md);
        right: auto;
    }

    body[dir="rtl"] .auth-link i {
        margin-right: 0;
        margin-left: var(--space-sm);
    }

    /* تعديلات النصوص في مؤشر قوة كلمة المرور */
    body[dir="rtl"] .strength-requirements {
        padding-right: 0;
        padding-left: 1.5rem;
    }

    body[dir="rtl"] .strength-requirement {
        flex-direction: row-reverse;
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card fade-in">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h1 class="auth-title">{{ __('تعيين كلمة مرور جديدة') }}</h1>
            <p class="auth-subtitle">{{ __('الرجاء إدخال كلمة المرور الجديدة أدناه') }}</p>
        </div>
        
        <!-- Body -->
        <div class="auth-body">
            <form method="POST" action="{{ route('password.update') }}" class="auth-form" id="resetForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <!-- Email -->
                <div class="form-group">
                    <input 
                        id="email" 
                        type="email" 
                        class="form-input @error('email') border-error-500 @enderror" 
                        name="email" 
                        value="{{ $email ?? old('email') }}" 
                        required 
                        autocomplete="email"
                        placeholder="{{ __('عنوان البريد الإلكتروني') }}"
                        readonly
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
                        autocomplete="new-password"
                        placeholder="{{ __('كلمة المرور الجديدة') }}"
                    >
                    <i class="form-icon fas fa-lock"></i>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="passwordToggleIcon"></i>
                    </button>
                    @error('password')
                        <span class="text-error-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                    
                    <!-- Password Strength Indicator -->
                    <div class="password-strength">
                        <div class="strength-title">{{ __('متطلبات كلمة المرور') }}</div>
                        <ul class="strength-requirements">
                            <li class="strength-requirement" id="length-req">
                                <i class="strength-icon fas fa-times"></i>
                                {{ __('8 أحرف على الأقل') }}
                            </li>
                            <li class="strength-requirement" id="uppercase-req">
                                <i class="strength-icon fas fa-times"></i>
                                {{ __('حرف كبير واحد على الأقل') }}
                            </li>
                            <li class="strength-requirement" id="lowercase-req">
                                <i class="strength-icon fas fa-times"></i>
                                {{ __('حرف صغير واحد على الأقل') }}
                            </li>
                            <li class="strength-requirement" id="number-req">
                                <i class="strength-icon fas fa-times"></i>
                                {{ __('رقم واحد على الأقل') }}
                            </li>
                            <li class="strength-requirement" id="special-req">
                                <i class="strength-icon fas fa-times"></i>
                                {{ __('رمز خاص واحد على الأقل') }}
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Confirm Password -->
                <div class="form-group">
                    <input 
                        id="password-confirm" 
                        type="password" 
                        class="form-input" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="{{ __('تأكيد كلمة المرور الجديدة') }}"
                    >
                    <i class="form-icon fas fa-lock"></i>
                    <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                        <i class="fas fa-eye" id="password-confirmToggleIcon"></i>
                    </button>
                    
                    <!-- Password Match Indicator -->
                    <div class="password-match" id="passwordMatch">
                        <span id="matchText"></span>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="auth-btn" id="submitBtn" disabled>
                    <span class="btn-text">{{ __('إعادة تعيين كلمة المرور') }}</span>
                    <div class="loading-spinner" id="loadingSpinner" style="display: none;"></div>
                </button>
                
                <!-- Links -->
                <div class="auth-links">
                    <a class="auth-link" href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('العودة لتسجيل الدخول') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // تعديل النصوص في الجافاسكريبت
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password-confirm').value;
        const matchIndicator = document.getElementById('passwordMatch');
        const matchText = document.getElementById('matchText');
        
        if (confirmPassword.length === 0) {
            matchIndicator.classList.remove('show');
            return false;
        }
        
        matchIndicator.classList.add('show');
        
        if (password === confirmPassword) {
            matchIndicator.classList.remove('no-match');
            matchIndicator.classList.add('match');
            matchText.textContent = '{{ __("كلمات المرور متطابقة") }}';
            return true;
        } else {
            matchIndicator.classList.remove('match');
            matchIndicator.classList.add('no-match');
            matchText.textContent = '{{ __("كلمات المرور غير متطابقة") }}';
            return false;
        }
    }
    
    // باقي الكود يبقى كما هو
    // ...
</script>
@endpush