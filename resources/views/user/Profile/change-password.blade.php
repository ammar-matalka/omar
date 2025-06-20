@extends('layouts.app')

@section('title', __('تغيير كلمة المرور') . ' - ' . config('app.name'))

@push('styles')
<style>
    /* RTL Direction */
    body {
        direction: rtl;
        text-align: right;
    }

    .password-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .password-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,0 1000,0 1000,80 0,100"/></svg>');
        background-size: cover;
        background-position: bottom;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
        text-align: center;
    }
    
    .hero-title {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: var(--space-lg);
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        font-size: 0.875rem;
        opacity: 0.9;
        flex-wrap: wrap;
        flex-direction: row-reverse;
    }
    
    .breadcrumb-link {
        color: white;
        text-decoration: none;
        transition: opacity var(--transition-fast);
    }
    
    .breadcrumb-link:hover {
        opacity: 0.8;
        text-decoration: underline;
    }
    
    .breadcrumb-separator {
        opacity: 0.6;
    }
    
    .password-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
        min-height: 60vh;
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        background: var(--surface);
        color: var(--primary-600);
        border: 2px solid var(--primary-200);
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-weight: 600;
        transition: all var(--transition-fast);
        margin-bottom: var(--space-xl);
        flex-direction: row-reverse;
    }
    
    .back-button:hover {
        background: var(--primary-50);
        border-color: var(--primary-300);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .password-content {
        max-width: 600px;
        margin: 0 auto;
        display: grid;
        gap: var(--space-xl);
    }
    
    .password-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }
    
    .password-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
    }
    
    .security-section {
        background: linear-gradient(135deg, var(--warning-50), var(--warning-100));
        border: 2px solid var(--warning-200);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .security-title {
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: var(--space-md);
        color: var(--warning-700);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-direction: row-reverse;
    }
    
    .security-tips {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: var(--space-sm);
    }
    
    .security-tips li {
        display: flex;
        align-items: flex-start;
        gap: var(--space-sm);
        color: var(--warning-600);
        font-size: 0.875rem;
        line-height: 1.5;
        flex-direction: row-reverse;
    }
    
    .security-tips li::before {
        content: '✓';
        color: var(--warning-500);
        font-weight: bold;
        margin-top: 2px;
        flex-shrink: 0;
    }
    
    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-xl);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-direction: row-reverse;
    }
    
    .form-grid {
        display: grid;
        gap: var(--space-lg);
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .form-label {
        font-weight: 500;
        color: var(--on-surface);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-direction: row-reverse;
    }
    
    .form-label.required::after {
        content: '*';
        color: var(--error-500);
        margin-left: var(--space-xs);
    }
    
    .password-input-wrapper {
        position: relative;
    }
    
    .form-input {
        width: 100%;
        padding: var(--space-md);
        padding-left: var(--space-3xl);
        padding-right: var(--space-md);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 1rem;
        transition: all var(--transition-fast);
        font-family: inherit;
        text-align: right;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        background: var(--surface);
    }
    
    .form-input.error {
        border-color: var(--error-500);
    }
    
    .form-input.success {
        border-color: var(--success-500);
    }
    
    .toggle-password {
        position: absolute;
        left: var(--space-md);
        right: auto;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--on-surface-variant);
        cursor: pointer;
        font-size: 1rem;
        transition: color var(--transition-fast);
    }
    
    .toggle-password:hover {
        color: var(--primary-500);
    }
    
    .form-help {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        line-height: 1.4;
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        flex-direction: row-reverse;
    }
    
    .form-error {
        font-size: 0.75rem;
        color: var(--error-600);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        flex-direction: row-reverse;
    }
    
    .form-success {
        font-size: 0.75rem;
        color: var(--success-600);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        flex-direction: row-reverse;
    }
    
    .password-strength {
        margin-top: var(--space-sm);
    }
    
    .strength-meter {
        height: 6px;
        background: var(--surface-variant);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: var(--space-sm);
    }
    
    .strength-fill {
        height: 100%;
        width: 0%;
        border-radius: 3px;
        transition: all var(--transition-normal);
    }
    
    .strength-weak {
        background: var(--error-500);
        width: 25%;
    }
    
    .strength-fair {
        background: var(--warning-500);
        width: 50%;
    }
    
    .strength-good {
        background: var(--info-500);
        width: 75%;
    }
    
    .strength-strong {
        background: var(--success-500);
        width: 100%;
    }
    
    .strength-text {
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .strength-requirements {
        margin-top: var(--space-md);
        padding: var(--space-md);
        background: var(--surface-variant);
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
    }
    
    .requirements-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--on-surface-variant);
        margin-bottom: var(--space-sm);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .requirements-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: var(--space-xs);
    }
    
    .requirement-item {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        transition: color var(--transition-fast);
        flex-direction: row-reverse;
    }
    
    .requirement-item.met {
        color: var(--success-600);
    }
    
    .requirement-icon {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
        transition: all var(--transition-fast);
    }
    
    .requirement-item.met .requirement-icon {
        background: var(--success-500);
        border-color: var(--success-500);
        color: white;
    }
    
    .form-actions {
        display: flex;
        gap: var(--space-lg);
        justify-content: space-between;
        margin-top: var(--space-2xl);
        padding-top: var(--space-xl);
        border-top: 1px solid var(--border-color);
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-xl);
        border: 2px solid transparent;
        border-radius: var(--radius-lg);
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
        min-width: 120px;
        flex-direction: row-reverse;
    }
    
    .btn:focus {
        outline: 2px solid var(--primary-500);
        outline-offset: 2px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        box-shadow: var(--shadow-md);
    }
    
    .btn-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-secondary {
        background: var(--surface);
        color: var(--on-surface-variant);
        border-color: var(--border-color);
    }
    
    .btn-secondary:hover {
        background: var(--surface-variant);
        border-color: var(--border-hover);
        color: var(--on-surface);
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
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 1.5rem;
        }
        
        .password-content {
            padding: 0 var(--space-md);
        }
        
        .form-actions {
            flex-direction: column-reverse;
            gap: var(--space-md);
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
        
        .breadcrumb {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Password Hero -->
<section class="password-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    {{ __('الرئيسية') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('user.profile.show') }}" class="breadcrumb-link">
                    {{ __('الملف الشخصي') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ __('تغيير كلمة المرور') }}</span>
            </nav>
            
            <h1 class="hero-title">{{ __('تغيير كلمة المرور') }}</h1>
            <p class="hero-subtitle">{{ __('قم بتحديث كلمة المرور الخاصة بك للحفاظ على أمان حسابك') }}</p>
        </div>
    </div>
</section>

<!-- Password Container -->
<section class="password-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('user.profile.show') }}" class="back-button fade-in">
            <i class="fas fa-arrow-left"></i>
            {{ __('العودة إلى الملف الشخصي') }}
        </a>
        
        <div class="password-content">
            <!-- Security Tips -->
            <div class="security-section fade-in">
                <h3 class="security-title">
                    <i class="fas fa-shield-alt"></i>
                    {{ __('نصائح أمان كلمة المرور') }}
                </h3>
                <ul class="security-tips">
                    <li>{{ __('استخدم 8 أحرف على الأقل مع مزيج من الحروف والأرقام والرموز') }}</li>
                    <li>{{ __('تجنب استخدام المعلومات الشخصية مثل اسمك أو تاريخ ميلادك') }}</li>
                    <li>{{ __('لا تعيد استخدام كلمات المرور من حسابات أخرى') }}</li>
                    <li>{{ __('فكر في استخدام مدير كلمات المرور لأمان أفضل') }}</li>
                </ul>
            </div>
            
            <!-- Password Form -->
            <div class="password-card fade-in">
                <form action="{{ route('user.profile.update-password') }}" method="POST" id="password-form">
                    @csrf
                    @method('PUT')
                    
                    <h2 class="form-title">
                        <i class="fas fa-key"></i>
                        {{ __('تحديث كلمة المرور') }}
                    </h2>
                    
                    <div class="form-grid">
                        <!-- Current Password -->
                        <div class="form-group">
                            <label for="current_password" class="form-label required">
                                <i class="fas fa-lock"></i>
                                {{ __('كلمة المرور الحالية') }}
                            </label>
                            <div class="password-input-wrapper">
                                <input 
                                    type="password" 
                                    id="current_password" 
                                    name="current_password" 
                                    class="form-input {{ $errors->has('current_password') ? 'error' : '' }}"
                                    required
                                    autocomplete="current-password"
                                >
                                <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye" id="current_password_icon"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">{{ __('أدخل كلمة المرور الحالية للتحقق من هويتك') }}</div>
                        </div>
                        
                        <!-- New Password -->
                        <div class="form-group">
                            <label for="password" class="form-label required">
                                <i class="fas fa-key"></i>
                                {{ __('كلمة المرور الجديدة') }}
                            </label>
                            <div class="password-input-wrapper">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                                    required
                                    autocomplete="new-password"
                                    oninput="checkPasswordStrength(this.value)"
                                >
                                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password_icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            
                            <!-- Password Strength Meter -->
                            <div class="password-strength">
                                <div class="strength-meter">
                                    <div class="strength-fill" id="strength-fill"></div>
                                </div>
                                <div class="strength-text" id="strength-text">{{ __('أدخل كلمة مرور') }}</div>
                                
                                <!-- Password Requirements -->
                                <div class="strength-requirements">
                                    <div class="requirements-title">{{ __('يجب أن تحتوي كلمة المرور على:') }}</div>
                                    <ul class="requirements-list">
                                        <li class="requirement-item" id="req-length">
                                            <span class="requirement-icon"></span>
                                            {{ __('8 أحرف على الأقل') }}
                                        </li>
                                        <li class="requirement-item" id="req-uppercase">
                                            <span class="requirement-icon"></span>
                                            {{ __('حرف كبير واحد على الأقل') }}
                                        </li>
                                        <li class="requirement-item" id="req-lowercase">
                                            <span class="requirement-icon"></span>
                                            {{ __('حرف صغير واحد على الأقل') }}
                                        </li>
                                        <li class="requirement-item" id="req-number">
                                            <span class="requirement-icon"></span>
                                            {{ __('رقم واحد على الأقل') }}
                                        </li>
                                        <li class="requirement-item" id="req-special">
                                            <span class="requirement-icon"></span>
                                            {{ __('رمز خاص واحد على الأقل') }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label required">
                                <i class="fas fa-check-double"></i>
                                {{ __('تأكيد كلمة المرور الجديدة') }}
                            </label>
                            <div class="password-input-wrapper">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="form-input"
                                    required
                                    autocomplete="new-password"
                                    oninput="checkPasswordMatch()"
                                >
                                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                </button>
                            </div>
                            <div id="password-match-message"></div>
                            <div class="form-help">{{ __('أعد إدخال كلمة المرور الجديدة للتأكيد') }}</div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('user.profile.show') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            {{ __('إلغاء') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                            <i class="fas fa-save"></i>
                            {{ __('تحديث كلمة المرور') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Initialize animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });
    
    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '_icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }
    
    // Check password strength
    function checkPasswordStrength(password) {
        const strengthFill = document.getElementById('strength-fill');
        const strengthText = document.getElementById('strength-text');
        
        // Requirements
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };
        
        // Update requirement indicators
        Object.keys(requirements).forEach(req => {
            const element = document.getElementById('req-' + req);
            if (requirements[req]) {
                element.classList.add('met');
                element.querySelector('.requirement-icon').innerHTML = '✓';
            } else {
                element.classList.remove('met');
                element.querySelector('.requirement-icon').innerHTML = '';
            }
        });
        
        // Calculate strength
        const metRequirements = Object.values(requirements).filter(Boolean).length;
        let strength = 0;
        let strengthClass = '';
        let strengthLabel = '';
        
        if (password.length === 0) {
            strengthLabel = '{{ __("أدخل كلمة مرور") }}';
        } else if (metRequirements <= 2) {
            strength = 1;
            strengthClass = 'strength-weak';
            strengthLabel = '{{ __("ضعيفة") }}';
        } else if (metRequirements === 3) {
            strength = 2;
            strengthClass = 'strength-fair';
            strengthLabel = '{{ __("متوسطة") }}';
        } else if (metRequirements === 4) {
            strength = 3;
            strengthClass = 'strength-good';
            strengthLabel = '{{ __("جيدة") }}';
        } else if (metRequirements === 5) {
            strength = 4;
            strengthClass = 'strength-strong';
            strengthLabel = '{{ __("قوية") }}';
        }
        
        // Update UI
        strengthFill.className = 'strength-fill ' + strengthClass;
        strengthText.textContent = strengthLabel;
        strengthText.className = 'strength-text ' + strengthClass;
        
        // Update submit button state
        checkFormValidity();
    }
    
    // Check password match
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;
        const messageElement = document.getElementById('password-match-message');
        const confirmationInput = document.getElementById('password_confirmation');
        
        if (confirmation.length === 0) {
            messageElement.innerHTML = '';
            confirmationInput.classList.remove('error', 'success');
        } else if (password === confirmation) {
            messageElement.innerHTML = '<div class="form-success"><i class="fas fa-check-circle"></i> {{ __("كلمات المرور متطابقة") }}</div>';
            confirmationInput.classList.remove('error');
            confirmationInput.classList.add('success');
        } else {
            messageElement.innerHTML = '<div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ __("كلمات المرور غير متطابقة") }}</div>';
            confirmationInput.classList.remove('success');
            confirmationInput.classList.add('error');
        }
        
        checkFormValidity();
    }
    
    // Check overall form validity
    function checkFormValidity() {
        const currentPassword = document.getElementById('current_password').value;
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;
        const submitBtn = document.getElementById('submit-btn');
        
        // Check password requirements
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };
        
        const allRequirementsMet = Object.values(requirements).every(Boolean);
        const passwordsMatch = password === confirmation && confirmation.length > 0;
        const currentPasswordFilled = currentPassword.length > 0;
        
        if (currentPasswordFilled && allRequirementsMet && passwordsMatch) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
        } else {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
        }
    }
    
    // Form submission with loading state
    document.getElementById('password-form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submit-btn');
        
        // Show loading state
        submitBtn.innerHTML = '<div class="loading-spinner"></div> {{ __("جاري التحديث...") }}';
        submitBtn.disabled = true;
    });
    
    // Real-time validation
    document.getElementById('current_password').addEventListener('input', checkFormValidity);
    document.getElementById('password').addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });
    document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Submit with Ctrl+Enter
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            if (!document.getElementById('submit-btn').disabled) {
                document.getElementById('password-form').submit();
            }
        }
        
        // Cancel with Escape
        if (e.key === 'Escape') {
            if (confirm('{{ __("هل أنت متأكد أنك تريد الإلغاء؟ أي تغييرات غير محفوظة سيتم فقدانها.") }}')) {
                window.location.href = '{{ route("user.profile.show") }}';
            }
        }
    });
    
    // Warn about unsaved changes
    let formChanged = false;
    
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('input', () => {
            formChanged = true;
        });
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '{{ __("لديك تغييرات غير محفوظة. هل أنت متأكد أنك تريد المغادرة؟") }}';
            return e.returnValue;
        }
    });
    
    // Reset form changed flag on submit
    document.getElementById('password-form').addEventListener('submit', () => {
        formChanged = false;
    });
    
    // Password generation suggestion
    function generateStrongPassword() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
        let password = '';
        
        // Ensure at least one character from each required category
        password += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[Math.floor(Math.random() * 26)]; // Uppercase
        password += 'abcdefghijklmnopqrstuvwxyz'[Math.floor(Math.random() * 26)]; // Lowercase
        password += '0123456789'[Math.floor(Math.random() * 10)]; // Number
        password += '!@#$%^&*()'[Math.floor(Math.random() * 10)]; // Special
        
        // Fill remaining characters
        for (let i = 4; i < 12; i++) {
            password += chars[Math.floor(Math.random() * chars.length)];
        }
        
        // Shuffle the password
        return password.split('').sort(() => Math.random() - 0.5).join('');
    }
    
    // Add generate password button (optional feature)
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const generateBtn = document.createElement('button');
        generateBtn.type = 'button';
        generateBtn.className = 'btn btn-secondary';
        generateBtn.style.cssText = 'margin-top: var(--space-sm); font-size: 0.875rem; padding: var(--space-sm) var(--space-md);';
        generateBtn.innerHTML = '<i class="fas fa-magic"></i> {{ __("إنشاء كلمة مرور قوية") }}';
        generateBtn.onclick = function() {
            const newPassword = generateStrongPassword();
            passwordInput.value = newPassword;
            document.getElementById('password_confirmation').value = newPassword;
            checkPasswordStrength(newPassword);
            checkPasswordMatch();
            
            // Show the generated password temporarily
            passwordInput.type = 'text';
            document.getElementById('password_confirmation').type = 'text';
            document.getElementById('password_icon').className = 'fas fa-eye-slash';
            document.getElementById('password_confirmation_icon').className = 'fas fa-eye-slash';
            
            // Hide after 3 seconds
            setTimeout(() => {
                passwordInput.type = 'password';
                document.getElementById('password_confirmation').type = 'password';
                document.getElementById('password_icon').className = 'fas fa-eye';
                document.getElementById('password_confirmation_icon').className = 'fas fa-eye';
            }, 3000);
            
            showNotification('{{ __("تم إنشاء كلمة مرور قوية وملء الحقول") }}', 'success');
        };
        
        passwordInput.parentNode.parentNode.appendChild(generateBtn);
    });
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 20px;
            right: auto;
            z-index: 9999;
            max-width: 300px;
            box-shadow: var(--shadow-xl);
            animation: slideIn 0.3s ease-out;
        `;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out forwards';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
</script>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }
</style>
@endpush