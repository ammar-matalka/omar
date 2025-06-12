@extends('layouts.admin')

@section('title', __('Edit User'))
@section('page-title', __('Edit User'))

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
        {{ __('Edit') }} - {{ $user->name }}
    </div>
@endsection

@push('styles')
<style>
    .edit-user-container {
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
        background: linear-gradient(135deg, var(--warning-500), var(--warning-600));
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
    
    .change-indicator {
        background: var(--warning-100);
        color: var(--warning-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        margin-left: var(--space-sm);
        display: none;
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
    
    .current-avatar {
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
    
    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--warning-500);
        margin: 0 auto var(--space-md);
        display: none;
    }
    
    .avatar-actions {
        display: flex;
        gap: var(--space-sm);
        justify-content: center;
        flex-wrap: wrap;
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
    
    .avatar-remove-btn {
        background: var(--error-500);
        color: white;
        border: none;
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-md);
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all var(--transition-fast);
    }
    
    .avatar-remove-btn:hover {
        background: var(--error-600);
        transform: translateY(-1px);
    }
    
    .password-change-section {
        background: var(--warning-50);
        border: 1px solid var(--warning-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .password-change-toggle {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
    }
    
    .password-fields {
        display: none;
    }
    
    .password-fields.active {
        display: block;
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
    
    .user-stats {
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--space-md);
    }
    
    .stat-item {
        text-align: center;
        padding: var(--space-md);
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-primary-600);
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
        .edit-user-container {
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
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .avatar-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="edit-user-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-user-edit"></i>
                {{ __('Edit User') }}
            </h1>
            <p class="form-subtitle">{{ __('Update user information and settings') }}</p>
        </div>
        
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" id="userForm">
            @csrf
            @method('PUT')
            
            <div class="form-body">
                <!-- User Statistics -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-chart-bar"></i>
                        {{ __('User Statistics') }}
                    </h2>
                    
                    <div class="user-stats">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->orders->count() ?? 0 }}</div>
                                <div class="stat-label">{{ __('Total Orders') }}</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value">${{ number_format($user->orders->sum('total') ?? 0, 2) }}</div>
                                <div class="stat-label">{{ __('Total Spent') }}</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->created_at->format('M d, Y') }}</div>
                                <div class="stat-label">{{ __('Member Since') }}</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value">
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        {{ __('Never') }}
                                    @endif
                                </div>
                                <div class="stat-label">{{ __('Last Login') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Avatar Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-camera"></i>
                        {{ __('Profile Picture') }}
                        <span class="change-indicator" id="avatarChangeIndicator">{{ __('Modified') }}</span>
                    </h2>
                    
                    <div class="avatar-upload-section">
                        @if($user->avatar)
                            <img id="currentAvatar" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="current-avatar">
                        @else
                            <div id="currentAvatar" class="avatar-placeholder">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                        
                        <img id="avatarPreview" class="avatar-preview" alt="Avatar Preview">
                        
                        <div class="avatar-actions">
                            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" onchange="previewAvatar(this)">
                            <button type="button" class="avatar-upload-btn" onclick="document.getElementById('avatar').click()">
                                <i class="fas fa-upload"></i>
                                {{ __('Change Photo') }}
                            </button>
                            
                            @if($user->avatar)
                                <button type="button" class="avatar-remove-btn" onclick="removeAvatar()">
                                    <i class="fas fa-trash"></i>
                                    {{ __('Remove Photo') }}
                                </button>
                                <input type="hidden" id="removeAvatar" name="remove_avatar" value="0">
                            @endif
                        </div>
                        
                        @error('avatar')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <div class="form-help">{{ __('Upload a new profile picture (optional). Max size: 2MB') }}</div>
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
                                    <span class="change-indicator" id="nameChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    class="form-input @error('name') error @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    placeholder="{{ __('Enter full name') }}"
                                    required
                                    oninput="trackChanges('name')"
                                    data-original="{{ $user->name }}"
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
                                    <span class="change-indicator" id="emailChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="form-input @error('email') error @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="{{ __('Enter email address') }}"
                                    required
                                    oninput="trackChanges('email')"
                                    data-original="{{ $user->email }}"
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
                                    <span class="change-indicator" id="phoneChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    class="form-input @error('phone') error @enderror"
                                    value="{{ old('phone', $user->phone) }}"
                                    placeholder="{{ __('Enter phone number') }}"
                                    oninput="trackChanges('phone')"
                                    data-original="{{ $user->phone }}"
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
                                    <span class="change-indicator" id="roleChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <select 
                                    id="role" 
                                    name="role" 
                                    class="form-select @error('role') error @enderror"
                                    required
                                    onchange="trackChanges('role')"
                                    data-original="{{ $user->role ?? 'user' }}"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                >
                                    <option value="user" {{ old('role', $user->role ?? 'user') == 'user' ? 'selected' : '' }}>{{ __('User') }}</option>
                                    <option value="admin" {{ old('role', $user->role ?? 'user') == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                </select>
                                @error('role')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if($user->id === auth()->id())
                                    <div class="form-help">{{ __('You cannot change your own role') }}</div>
                                @else
                                    <div class="form-help">{{ __('Select the user role and permissions') }}</div>
                                @endif
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
                    
                    <div class="password-change-section">
                        <div class="password-change-toggle">
                            <input 
                                type="checkbox" 
                                id="changePassword" 
                                class="checkbox-input"
                                onchange="togglePasswordFields(this)"
                            >
                            <label for="changePassword" class="checkbox-label">
                                {{ __('Change Password') }}
                            </label>
                        </div>
                        
                        <div id="passwordFields" class="password-fields">
                            <div class="form-grid">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="password" class="form-label">
                                            {{ __('New Password') }}
                                        </label>
                                        <input 
                                            type="password" 
                                            id="password" 
                                            name="password" 
                                            class="form-input @error('password') error @enderror"
                                            placeholder="{{ __('Enter new password') }}"
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
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">
                                            {{ __('Confirm New Password') }}
                                        </label>
                                        <input 
                                            type="password" 
                                            id="password_confirmation" 
                                            name="password_confirmation" 
                                            class="form-input"
                                            placeholder="{{ __('Confirm new password') }}"
                                        >
                                        <div class="form-help">{{ __('Re-enter the new password to confirm') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Account Settings -->
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
                                        {{ old('email_verified', $user->email_verified_at ? true : false) ? 'checked' : '' }}
                                        onchange="trackChanges('email_verified')"
                                        data-original="{{ $user->email_verified_at ? '1' : '0' }}"
                                    >
                                    <label for="email_verified" class="checkbox-label">
                                        {{ __('Email verified') }}
                                    </label>
                                    <span class="change-indicator" id="email_verifiedChangeIndicator">{{ __('Modified') }}</span>
                                </div>
                                <div class="form-help">
                                    @if($user->email_verified_at)
                                        {{ __('Email was verified on') }} {{ $user->email_verified_at->format('M d, Y \a\t g:i A') }}
                                    @else
                                        {{ __('User email is not verified yet') }}
                                    @endif
                                </div>
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
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i>
                        {{ __('View User') }}
                    </a>
                    
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        {{ __('Reset') }}
                    </button>
                    
                    <button type="submit" class="btn btn-warning" id="updateBtn">
                        <i class="fas fa-save"></i>
                        {{ __('Update User') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let hasChanges = false;
    const originalValues = {};
    
    // Store original values on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-original]').forEach(field => {
            originalValues[field.id] = field.dataset.original;
        });
    });
    
    // Track changes in form fields
    function trackChanges(fieldName) {
        const field = document.getElementById(fieldName);
        const indicator = document.getElementById(fieldName + 'ChangeIndicator');
        let currentValue;
        
        if (field.type === 'checkbox') {
            currentValue = field.checked ? '1' : '0';
        } else {
            currentValue = field.value;
        }
        
        const originalValue = originalValues[fieldName] || '';
        
        if (currentValue !== originalValue) {
            if (indicator) indicator.style.display = 'inline-block';
            hasChanges = true;
        } else {
            if (indicator) indicator.style.display = 'none';
        }
        
        updateFormState();
    }
    
    // Update form state based on changes
    function updateFormState() {
        const updateBtn = document.getElementById('updateBtn');
        hasChanges = document.querySelector('.change-indicator[style*="inline-block"]') !== null;
        
        if (hasChanges) {
            updateBtn.classList.remove('btn-secondary');
            updateBtn.classList.add('btn-warning');
        } else {
            updateBtn.classList.remove('btn-warning');
            updateBtn.classList.add('btn-secondary');
        }
    }
    
    // Preview avatar
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const currentAvatar = document.getElementById('currentAvatar');
                const preview = document.getElementById('avatarPreview');
                
                currentAvatar.style.display = 'none';
                preview.src = e.target.result;
                preview.style.display = 'block';
                
                document.getElementById('avatarChangeIndicator').style.display = 'inline-block';
                hasChanges = true;
                updateFormState();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Remove avatar
    function removeAvatar() {
        if (confirm('{{ __("Are you sure you want to remove the profile picture?") }}')) {
            const currentAvatar = document.getElementById('currentAvatar');
            const preview = document.getElementById('avatarPreview');
            const removeInput = document.getElementById('removeAvatar');
            
            if (removeInput) {
                removeInput.value = '1';
            }
            
            currentAvatar.style.display = 'none';
            preview.style.display = 'none';
            
            // Show placeholder
            const placeholder = document.createElement('div');
            placeholder.className = 'avatar-placeholder';
            placeholder.id = 'currentAvatar';
            placeholder.innerHTML = '{{ strtoupper(substr($user->name, 0, 2)) }}';
            currentAvatar.parentNode.insertBefore(placeholder, currentAvatar);
            
            document.getElementById('avatarChangeIndicator').style.display = 'inline-block';
            hasChanges = true;
            updateFormState();
        }
    }
    
    // Toggle password fields
    function togglePasswordFields(checkbox) {
        const passwordFields = document.getElementById('passwordFields');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        
        if (checkbox.checked) {
            passwordFields.classList.add('active');
            passwordInput.required = true;
            confirmInput.required = true;
        } else {
            passwordFields.classList.remove('active');
            passwordInput.required = false;
            passwordInput.value = '';
            confirmInput.required = false;
            confirmInput.value = '';
            document.getElementById('passwordStrength').style.display = 'none';
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
        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        
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
        if (confirm('{{ __("Are you sure you want to reset the form? All changes will be lost.") }}')) {
            location.reload();
        }
    }
    
    // Form validation
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const changePasswordCheckbox = document.getElementById('changePassword');
        
        if (changePasswordCheckbox.checked) {
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
        }
        
        // Show loading state
        const submitBtn = document.getElementById('updateBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Updating...") }}';
        submitBtn.disabled = true;
        
        // Re-enable button after 10 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
    
    // Warn user about unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '{{ __("You have unsaved changes. Are you sure you want to leave?") }}';
            return e.returnValue;
        }
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