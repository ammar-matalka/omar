@extends('layouts.admin')

@section('title', __('Create User'))
@section('page-title', __('Create User'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.users.index') }}" class="breadcrumb-link">{{ __('Users') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Create') }}
    </div>
@endsection

@push('styles')
<style>
    .create-user-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .form-header {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: var(--space-xl);
        text-align: center;
    }
    
    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
    }
    
    .form-subtitle {
        margin: var(--space-sm) 0 0 0;
        opacity: 0.9;
        font-size: 0.875rem;
    }
    
    .form-body {
        padding: var(--space-2xl);
    }
    
    .form-section {
        margin-bottom: var(--space-2xl);
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        border-bottom: 2px solid var(--admin-secondary-200);
        padding-bottom: var(--space-sm);
    }
    
    .form-grid {
        display: grid;
        gap: var(--space-lg);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-lg);
    }
    
    .form-row.single {
        grid-template-columns: 1fr;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .required {
        color: var(--error-500);
    }
    
    .form-input,
    .form-textarea,
    .form-select {
        padding: var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-family: inherit;
        transition: all var(--transition-fast);
        background: white;
    }
    
    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .form-help {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
        margin-top: var(--space-xs);
    }
    
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-top: var(--space-sm);
    }
    
    .checkbox-input {
        width: 18px;
        height: 18px;
        accent-color: var(--admin-primary-500);
    }
    
    .checkbox-label {
        font-size: 0.875rem;
        color: var(--admin-secondary-700);
        margin: 0;
    }
    
    .avatar-upload-section {
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        text-align: center;
    }
    
    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--admin-secondary-200);
        margin: 0 auto var(--space-md);
        display: block;
    }
    
    .avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: var(--admin-primary-100);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-primary-600);
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 auto var(--space-md);
        border: 4px solid var(--admin-secondary-200);
    }
    
    .avatar-upload-btn {
        background: var(--admin-primary-500);
        color: white;
        border: none;
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-md);
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all var(--transition-fast);
    }
    
    .avatar-upload-btn:hover {
        background: var(--admin-primary-600);
        transform: translateY(-1px);
    }
    
    .password-strength {
        margin-top: var(--space-sm);
        padding: var(--space-sm);
        border-radius: var(--radius-md);
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        display: none;
    }
    
    .strength-bar {
        width: 100%;
        height: 4px;
        background: var(--admin-secondary-200);
        border-radius: var(--radius-sm);
        overflow: hidden;
        margin-bottom: var(--space-sm);
    }
    
    .strength-fill {
        height: 100%;
        transition: all var(--transition-fast);
        border-radius: var(--radius-sm);
    }
    
    .strength-weak .strength-fill {
        width: 25%;
        background: var(--error-500);
    }
    
    .strength-fair .strength-fill {
        width: 50%;
        background: var(--warning-500);
    }
    
    .strength-good .strength-fill {
        width: 75%;
        background: var(--success-400);
    }
    
    .strength-strong .strength-fill {
        width: 100%;
        background: var(--success-500);
    }
    
    .strength-text {
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .strength-requirements {
        margin-top: var(--space-sm);
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
    }
    
    .requirement {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        margin-bottom: var(--space-xs);
    }
    
    .requirement-icon {
        width: 16px;
        color: var(--admin-secondary-400);
    }
    
    .requirement.met .requirement-icon {
        color: var(--success-500);
    }
    
    .form-actions {
        background: var(--admin-secondary-50);
        padding: var(--space-xl);
        border-top: 1px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-md);
    }
    
    .btn-group {
        display: flex;
        gap: var(--space-md);
    }
    
    .error-message {
        color: var(--error-500);
        font-size: 0.75rem;
        margin-top: var(--space-xs);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .form-input.error,
    .form-textarea.error,
    .form-select.error {
        border-color: var(--error-500);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    @media (max-width: 768px) {
        .create-user-container {
            margin: 0;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-group {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="create-user-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-user-plus"></i>
                {{ __('Create New User') }}
            </h1>
            <p class="form-subtitle">{{ __('Add a new user to the system') }}</p>
        </div>
        
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" id="userForm">
            @csrf
            
            <div class="form-body">
                <!-- Avatar Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-camera"></i>
                        {{ __('Profile Picture') }}
                    </h2>
                    
                    <div class="avatar-upload-section">
                        <div id="avatarPreview" class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                        
                        <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" onchange="previewAvatar(this)">
                        <button type="button" class="avatar-upload-btn" onclick="document.getElementById('avatar').click()">
                            <i class="fas fa-upload"></i>
                            {{ __('Choose Photo') }}
                        </button>
                        
                        @error('avatar')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <div class="form-help">{{ __('Upload a profile picture (optional). Max size: 2MB') }}</div>
                    </div>
                </div>
                
                <!-- Basic Information -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Basic Information') }}
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    {{ __('Full Name') }}
                                    <span class="required">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    class="form-input @error('name') error @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="{{ __('Enter full name') }}"
                                    required
                                >
                                @error('name')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    {{ __('Email Address') }}
                                    <span class="required">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="form-input @error('email') error @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="{{ __('Enter email address') }}"
                                    required
                                >
                                @error('email')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    {{ __('Phone Number') }}
                                </label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    class="form-input @error('phone') error @enderror"
                                    value="{{ old('phone') }}"
                                    placeholder="{{ __('Enter phone number') }}"
                                >
                                @error('phone')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="role" class="form-label">
                                    {{ __('User Role') }}
                                    <span class="required">*</span>
                                </label>
                                <select 
                                    id="role" 
                                    name="role" 
                                    class="form-select @error('role') error @enderror"
                                    required
                                >
                                    <option value="">{{ __('Select Role') }}</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>{{ __('User') }}</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                </select>
                                @error('role')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Select the user role and permissions') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Password Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-lock"></i>
                        {{ __('Password') }}
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    {{ __('Password') }}
                                    <span class="required">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input @error('password') error @enderror"
                                    placeholder="{{ __('Enter password') }}"
                                    required
                                    oninput="checkPasswordStrength(this.value)"
                                >
                                @error('password')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                
                                <div id="passwordStrength" class="password-strength">
                                    <div class="strength-bar">
                                        <div class="strength-fill"></div>
                                    </div>
                                    <div class="strength-text"></div>
                                    <div class="strength-requirements">
                                        <div class="requirement" id="req-length">
                                            <i class="fas fa-times requirement-icon"></i>
                                            {{ __('At least 8 characters') }}
                                        </div>
                                        <div class="requirement" id="req-uppercase">
                                            <i class="fas fa-times requirement-icon"></i>
                                            {{ __('One uppercase letter') }}
                                        </div>
                                        <div class="requirement" id="req-lowercase">
                                            <i class="fas fa-times requirement-icon"></i>
                                            {{ __('One lowercase letter') }}
                                        </div>
                                        <div class="requirement" id="req-number">
                                            <i class="fas fa-times requirement-icon"></i>
                                            {{ __('One number') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">
                                    {{ __('Confirm Password') }}
                                    <span class="required">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="form-input"
                                    placeholder="{{ __('Confirm password') }}"
                                    required
                                >
                                <div class="form-help">{{ __('Re-enter the password to confirm') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Settings -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-cog"></i>
                        {{ __('Account Settings') }}
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-row single">
                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input 
                                        type="checkbox" 
                                        id="email_verified" 
                                        name="email_verified" 
                                        class="checkbox-input"
                                        value="1"
                                        {{ old('email_verified') ? 'checked' : '' }}
                                    >
                                    <label for="email_verified" class="checkbox-label">
                                        {{ __('Mark email as verified') }}
                                    </label>
                                </div>
                                <div class="form-help">{{ __('Skip email verification for this user') }}</div>
                            </div>
                        </div>
                        
                        <div class="form-row single">
                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input 
                                        type="checkbox" 
                                        id="send_welcome_email" 
                                        name="send_welcome_email" 
                                        class="checkbox-input"
                                        value="1"
                                        {{ old('send_welcome_email', true) ? 'checked' : '' }}
                                    >
                                    <label for="send_welcome_email" class="checkbox-label">
                                        {{ __('Send welcome email') }}
                                    </label>
                                </div>
                                <div class="form-help">{{ __('Send account creation notification to the user') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('Back to Users') }}
                    </a>
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        {{ __('Reset') }}
                    </button>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-plus"></i>
                        {{ __('Create User') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview avatar
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                preview.innerHTML = `<img src="${e.target.result}" class="avatar-preview" alt="Avatar Preview">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Password strength checker
    function checkPasswordStrength(password) {
        const strengthDiv = document.getElementById('passwordStrength');
        const strengthBar = strengthDiv.querySelector('.strength-fill');
        const strengthText = strengthDiv.querySelector('.strength-text');
        
        if (password.length === 0) {
            strengthDiv.style.display = 'none';
            return;
        }
        
        strengthDiv.style.display = 'block';
        
        let score = 0;
        let strength = '';
        
        // Check requirements
        const requirements = {
            'req-length': password.length >= 8,
            'req-uppercase': /[A-Z]/.test(password),
            'req-lowercase': /[a-z]/.test(password),
            'req-number': /[0-9]/.test(password)
        };
        
        // Update requirement indicators
        Object.keys(requirements).forEach(reqId => {
            const req = document.getElementById(reqId);
            const icon = req.querySelector('.requirement-icon');
            
            if (requirements[reqId]) {
                req.classList.add('met');
                icon.className = 'fas fa-check requirement-icon';
                score++;
            } else {
                req.classList.remove('met');
                icon.className = 'fas fa-times requirement-icon';
            }
        });
        
        // Determine strength
        strengthDiv.className = 'password-strength';
        
        if (score === 0) {
            strength = '{{ __("Very Weak") }}';
            strengthDiv.classList.add('strength-weak');
        } else if (score === 1) {
            strength = '{{ __("Weak") }}';
            strengthDiv.classList.add('strength-weak');
        } else if (score === 2) {
            strength = '{{ __("Fair") }}';
            strengthDiv.classList.add('strength-fair');
        } else if (score === 3) {
            strength = '{{ __("Good") }}';
            strengthDiv.classList.add('strength-good');
        } else if (score === 4) {
            strength = '{{ __("Strong") }}';
            strengthDiv.classList.add('strength-strong');
        }
        
        strengthText.textContent = strength;
    }
    
    // Reset form
    function resetForm() {
        if (confirm('{{ __("Are you sure you want to reset the form? All entered data will be lost.") }}')) {
            document.getElementById('userForm').reset();
            document.getElementById('avatarPreview').innerHTML = '<i class="fas fa-user"></i>';
            document.getElementById('passwordStrength').style.display = 'none';
        }
    }
    
    // Form validation
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('{{ __("Passwords do not match") }}');
            document.getElementById('password_confirmation').focus();
            return;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('{{ __("Password must be at least 8 characters long") }}');
            document.getElementById('password').focus();
            return;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Creating...") }}';
        submitBtn.disabled = true;
        
        // Re-enable button after 10 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
    
    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endpush