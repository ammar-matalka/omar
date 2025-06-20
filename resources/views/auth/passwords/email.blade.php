@extends('layouts.app')

@section('title', __('إعادة تعيين كلمة المرور') . ' - ' . config('app.name'))

@push('styles')
<style>
    /* إضافة خاصية direction للعناصر الرئيسية */
    body[dir="rtl"] .auth-container,
    body[dir="rtl"] .auth-card,
    body[dir="rtl"] .auth-header,
    body[dir="rtl"] .auth-body,
    body[dir="rtl"] .auth-form,
    body[dir="rtl"] .auth-links {
        direction: rtl;
        text-align: right;
    }

    /* تعديل أماكن الأيقونات */
    body[dir="rtl"] .form-icon {
        right: var(--space-md);
        left: auto;
    }

    body[dir="rtl"] .form-input {
        padding-right: 3rem;
        padding-left: var(--space-lg);
    }

    /* تعديل مكان زر التبديل في كلمة المرور */
    body[dir="rtl"] .password-toggle {
        left: var(--space-md);
        right: auto;
    }

    /* تعديل مكان الروابط */
    body[dir="rtl"] .auth-link i {
        margin-right: 0;
        margin-left: var(--space-sm);
    }

    /* أي تعديلات أخرى لتحسين الشكل في الوضع العربي */
    .auth-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--space-2xl) 0;
        background: linear-gradient(135deg, var(--primary-50), var(--secondary-50));
    }
    
    /* باقي CSS الأصلي يبقى كما هو */
    /* ... */
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card fade-in">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fas fa-key"></i>
            </div>
            <h1 class="auth-title">{{ __('إعادة تعيين كلمة المرور') }}</h1>
            <p class="auth-subtitle">{{ __('أدخل عنوان بريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيين كلمة المرور') }}</p>
        </div>
        
        <!-- Body -->
        <div class="auth-body">
            <!-- Info Box -->
            <div class="info-box">
                <i class="info-icon fas fa-info-circle"></i>
                <div class="info-content">
                    <div class="info-title">{{ __('تعليمات إعادة تعيين كلمة المرور') }}</div>
                    <div class="info-text">{{ __('سنرسل لك بريدًا إلكترونيًا يحتوي على رابط آمن لإعادة تعيين كلمة المرور. سينتهي صلاحية الرابط بعد 60 دقيقة لأسباب أمنية.') }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="auth-form" id="resetForm">
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
                        placeholder="{{ __('عنوان البريد الإلكتروني') }}"
                    >
                    <i class="form-icon fas fa-envelope"></i>
                    @error('email')
                        <span class="text-error-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="auth-btn" id="submitBtn">
                    <span class="btn-text">{{ __('إرسال رابط إعادة التعيين') }}</span>
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
        
        <!-- Back to Register Prompt -->
        <div class="back-prompt">
            <p class="back-text">{{ __("ليس لديك حساب بعد؟") }}</p>
            <a href="{{ route('register') }}" class="back-btn">
                <i class="fas fa-user-plus"></i>
                {{ __('إنشاء حساب') }}
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // النصوص في الجافاسكريبت تبقى كما هي لأنها غير معروضة للمستخدم
    // Form submission with loading state
    document.getElementById('resetForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = document.getElementById('loadingSpinner');
        
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        spinner.style.display = 'block';
    });
    
    // باقي الكود يبقى كما هو
    // ...
</script>
@endpush