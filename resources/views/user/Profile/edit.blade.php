@extends('layouts.app')

@section('title', __('Edit Profile') . ' - ' . config('app.name'))

@push('styles')
<style>
    .edit-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .edit-hero::before {
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
    
    .edit-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
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
    }
    
    .back-button:hover {
        background: var(--primary-50);
        border-color: var(--primary-300);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .edit-content {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: var(--space-2xl);
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .edit-sidebar {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .edit-main {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        box-shadow: var(--shadow-sm);
    }
    
    .avatar-section {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        text-align: center;
        box-shadow: var(--shadow-sm);
    }
    
    .avatar-upload {
        position: relative;
        display: inline-block;
        margin-bottom: var(--space-lg);
    }
    
    .current-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        border: 4px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--primary-600);
        overflow: hidden;
        transition: all var(--transition-normal);
        cursor: pointer;
    }
    
    .current-avatar:hover {
        border-color: var(--primary-300);
        transform: scale(1.05);
    }
    
    .current-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity var(--transition-fast);
        cursor: pointer;
    }
    
    .current-avatar:hover .avatar-overlay {
        opacity: 1;
    }
    
    .avatar-overlay i {
        color: white;
        font-size: 1.5rem;
    }
    
    .avatar-input {
        display: none;
    }
    
    .avatar-actions {
        display: flex;
        gap: var(--space-sm);
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .avatar-btn {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .avatar-btn:hover {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
    }
    
    .avatar-btn.primary {
        background: var(--primary-500);
        color: white;
        border-color: var(--primary-500);
    }
    
    .avatar-btn.primary:hover {
        background: var(--primary-600);
        border-color: var(--primary-600);
    }
    
    .tips-section {
        background: linear-gradient(135deg, var(--info-50), var(--info-100));
        border: 2px solid var(--info-200);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
    }
    
    .tips-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: var(--space-md);
        color: var(--info-700);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .tips-list li {
        padding: var(--space-sm) 0;
        color: var(--info-600);
        font-size: 0.875rem;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: var(--space-sm);
    }
    
    .tips-list li::before {
        content: '•';
        color: var(--info-500);
        font-weight: bold;
        margin-top: 2px;
    }
    
    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-xl);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
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
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    
    .form-label {
        font-weight: 500;
        color: var(--on-surface);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .form-label.required::after {
        content: '*';
        color: var(--error-500);
        margin-left: var(--space-xs);
    }
    
    .form-input,
    .form-textarea {
        padding: var(--space-md);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 1rem;
        transition: all var(--transition-fast);
        font-family: inherit;
    }
    
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        background: var(--surface);
    }
    
    .form-input.error,
    .form-textarea.error {
        border-color: var(--error-500);
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 120px;
        line-height: 1.6;
    }
    
    .form-help {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        line-height: 1.4;
    }
    
    .form-error {
        font-size: 0.75rem;
        color: var(--error-600);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .input-icon {
        position: relative;
    }
    
    .input-icon .form-input {
        padding-left: var(--space-3xl);
    }
    
    .input-icon i {
        position: absolute;
        left: var(--space-md);
        top: 50%;
        transform: translateY(-50%);
        color: var(--on-surface-variant);
        font-size: 1rem;
    }
    
    .form-actions {
        display: flex;
        gap: var(--space-lg);
        justify-content: flex-end;
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
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
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
    
    .preview-section {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        box-shadow: var(--shadow-sm);
    }
    
    .preview-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: var(--space-lg);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .preview-card {
        background: var(--surface-variant);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .preview-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-600);
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .preview-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .preview-info {
        flex: 1;
    }
    
    .preview-name {
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
    }
    
    .preview-email {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 1.5rem;
        }
        
        .edit-content {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: var(--space-md);
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
        
        .preview-card {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Edit Hero -->
<section class="edit-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    {{ __('Home') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('user.profile.show') }}" class="breadcrumb-link">
                    {{ __('Profile') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ __('Edit') }}</span>
            </nav>
            
            <h1 class="hero-title">{{ __('Edit Profile') }}</h1>
            <p class="hero-subtitle">{{ __('Update your personal information and preferences') }}</p>
        </div>
    </div>
</section>

<!-- Edit Container -->
<section class="edit-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('user.profile.show') }}" class="back-button fade-in">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Profile') }}
        </a>
        
        <!-- Edit Content -->
        <div class="edit-content">
            <!-- Sidebar -->
            <div class="edit-sidebar">
                <!-- Avatar Section -->
                <div class="avatar-section fade-in">
                    <div class="avatar-upload">
                        <div class="current-avatar" onclick="document.getElementById('avatar-input').click()">
                            @if($user->profile_image)
                                <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" id="avatar-preview">
                            @else
                                <span id="avatar-initials">{{ $user->initials }}</span>
                            @endif
                            <div class="avatar-overlay">
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>
                        <input type="file" id="avatar-input" class="avatar-input" accept="image/*" onchange="previewAvatar(this)">
                    </div>
                    
                    <div class="avatar-actions">
                        <button type="button" class="avatar-btn primary" onclick="document.getElementById('avatar-input').click()">
                            <i class="fas fa-upload"></i>
                            {{ __('Upload') }}
                        </button>
                        @if($user->profile_image)
                            <button type="button" class="avatar-btn" onclick="removeAvatar()">
                                <i class="fas fa-trash"></i>
                                {{ __('Remove') }}
                            </button>
                        @endif
                    </div>
                </div>
                
                <!-- Tips Section -->
                <div class="tips-section fade-in">
                    <h3 class="tips-title">
                        <i class="fas fa-lightbulb"></i>
                        {{ __('Profile Tips') }}
                    </h3>
                    <ul class="tips-list">
                        <li>{{ __('Use a clear, high-quality photo for your profile picture') }}</li>
                        <li>{{ __('Keep your contact information up to date for order notifications') }}</li>
                        <li>{{ __('Add your address to speed up the checkout process') }}</li>
                        <li>{{ __('Verify your email address to receive important updates') }}</li>
                    </ul>
                </div>
                
                <!-- Preview Section -->
                <div class="preview-section fade-in">
                    <h3 class="preview-title">
                        <i class="fas fa-eye"></i>
                        {{ __('Profile Preview') }}
                    </h3>
                    <div class="preview-card">
                        <div class="preview-avatar">
                            @if($user->profile_image)
                                <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" id="preview-avatar-img">
                            @else
                                <span id="preview-initials">{{ $user->initials }}</span>
                            @endif
                        </div>
                        <div class="preview-info">
                            <div class="preview-name" id="preview-name">{{ $user->name }}</div>
                            <div class="preview-email" id="preview-email">{{ $user->email }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Form -->
            <div class="edit-main fade-in">
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
                    @csrf
                    @method('PUT')
                    
                    <h2 class="form-title">
                        <i class="fas fa-user-edit"></i>
                        {{ __('Personal Information') }}
                    </h2>
                    
                    <div class="form-grid">
                        <!-- Name and Email Row -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label required">
                                    <i class="fas fa-user"></i>
                                    {{ __('Full Name') }}
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-user"></i>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                                        value="{{ old('name', $user->name) }}"
                                        required
                                        oninput="updatePreview()"
                                    >
                                </div>
                                @error('name')
                                    <div class="form-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Your full name as you want it to appear on your profile') }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="form-label required">
                                    <i class="fas fa-envelope"></i>
                                    {{ __('Email Address') }}
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                                        value="{{ old('email', $user->email) }}"
                                        required
                                        oninput="updatePreview()"
                                    >
                                </div>
                                @error('email')
                                    <div class="form-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('We\'ll use this email for order confirmations and updates') }}</div>
                            </div>
                        </div>
                        
                        <!-- Phone -->
                        <div class="form-group">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone"></i>
                                {{ __('Phone Number') }}
                            </label>
                            <div class="input-icon">
                                <i class="fas fa-phone"></i>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    class="form-input {{ $errors->has('phone') ? 'error' : '' }}"
                                    value="{{ old('phone', $user->phone) }}"
                                    placeholder="+962 7X XXX XXXX"
                                >
                            </div>
                            @error('phone')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">{{ __('Optional: For order status updates via SMS') }}</div>
                        </div>
                        
                        <!-- Address -->
                        <div class="form-group full-width">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ __('Address') }}
                            </label>
                            <textarea 
                                id="address" 
                                name="address" 
                                class="form-textarea {{ $errors->has('address') ? 'error' : '' }}"
                                placeholder="{{ __('Enter your full address...') }}"
                            >{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">{{ __('Your address will be used as default shipping address') }}</div>
                        </div>
                        
                        <!-- Hidden file input for avatar -->
                        <input type="file" name="profile_image" id="profile-image-input" style="display: none;" accept="image/*">
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('user.profile.show') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <i class="fas fa-save"></i>
                            {{ __('Save Changes') }}
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
    
    // Avatar preview functionality
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Update main avatar
                const avatarPreview = document.getElementById('avatar-preview');
                const avatarInitials = document.getElementById('avatar-initials');
                
                if (avatarPreview) {
                    avatarPreview.src = e.target.result;
                } else {
                    // Create new img element if it doesn't exist
                    const currentAvatar = document.querySelector('.current-avatar');
                    currentAvatar.innerHTML = `<img src="${e.target.result}" alt="Avatar Preview" id="avatar-preview">`;
                }
                
                // Update preview section
                const previewImg = document.getElementById('preview-avatar-img');
                const previewInitials = document.getElementById('preview-initials');
                
                if (previewImg) {
                    previewImg.src = e.target.result;
                } else {
                    const previewAvatar = document.querySelector('.preview-avatar');
                    previewAvatar.innerHTML = `<img src="${e.target.result}" alt="Preview" id="preview-avatar-img">`;
                }
                
                // Copy file to hidden input for form submission
                const hiddenInput = document.getElementById('profile-image-input');
                hiddenInput.files = input.files;
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Remove avatar functionality
    function removeAvatar() {
        if (!confirm('{{ __("Are you sure you want to remove your profile picture?") }}')) {
            return;
        }
        
        // Reset to initials
        const userName = document.getElementById('name').value;
        const initials = getInitials(userName);
        
        // Update main avatar
        const currentAvatar = document.querySelector('.current-avatar');
        currentAvatar.innerHTML = `<span id="avatar-initials">${initials}</span><div class="avatar-overlay"><i class="fas fa-camera"></i></div>`;
        
        // Update preview
        const previewAvatar = document.querySelector('.preview-avatar');
        previewAvatar.innerHTML = `<span id="preview-initials">${initials}</span>`;
        
        // Clear file inputs
        document.getElementById('avatar-input').value = '';
        document.getElementById('profile-image-input').value = '';
        
        // Add hidden input to mark for removal
        const form = document.getElementById('profile-form');
        let removeInput = document.getElementById('remove-avatar');
        if (!removeInput) {
            removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_avatar';
            removeInput.id = 'remove-avatar';
            removeInput.value = '1';
            form.appendChild(removeInput);
        }
    }
    
    // Update preview in real-time
    function updatePreview() {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        
        // Update preview card
        document.getElementById('preview-name').textContent = name || '{{ $user->name }}';
        document.getElementById('preview-email').textContent = email || '{{ $user->email }}';
        
        // Update initials if no avatar
        if (!document.getElementById('avatar-preview')) {
            const initials = getInitials(name);
            const avatarInitials = document.getElementById('avatar-initials');
            const previewInitials = document.getElementById('preview-initials');
            
            if (avatarInitials) {
                avatarInitials.textContent = initials;
            }
            if (previewInitials) {
                previewInitials.textContent = initials;
            }
        }
    }
    
    // Get user initials from name
    function getInitials(name) {
        if (!name) return 'U';
        
        const words = name.trim().split(' ');
        let initials = '';
        
        for (let i = 0; i < Math.min(2, words.length); i++) {
            if (words[i].length > 0) {
                initials += words[i][0].toUpperCase();
            }
        }
        
        return initials || 'U';
    }
    
    // Form submission with loading state
    document.getElementById('profile-form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submit-btn');
        
        // Show loading state
        submitBtn.innerHTML = '<div class="loading-spinner"></div> {{ __("Saving...") }}';
        submitBtn.disabled = true;
        
        // Validate form before submission
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            submitBtn.innerHTML = '<i class="fas fa-save"></i> {{ __("Save Changes") }}';
            submitBtn.disabled = false;
            showNotification('{{ __("Please fill in all required fields") }}', 'error');
            return;
        }
    });
    
    // Phone number formatting
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Format Jordan phone numbers
        if (value.startsWith('962')) {
            // International format
            value = value.replace(/^962/, '+962 ');
            if (value.length > 5) {
                value = value.replace(/(\+962 )(\d{1})(\d{0,3})(\d{0,4})/, '$1$2$3 $4');
            }
        } else if (value.startsWith('07') || value.startsWith('7')) {
            // Local format
            if (!value.startsWith('07')) {
                value = '07' + value.substring(1);
            }
            value = value.replace(/(\d{2})(\d{0,4})(\d{0,4})/, '$1$2 $3');
        }
        
        e.target.value = value.trim();
    });
    
    // Real-time validation
    document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                validateField(this);
            }
        });
    });
    
    function validateField(field) {
        const value = field.value.trim();
        
        // Remove existing error state
        field.classList.remove('error');
        const existingError = field.parentNode.parentNode.querySelector('.form-error');
        if (existingError && !existingError.textContent.includes('{{ __("") }}')) {
            existingError.style.display = 'none';
        }
        
        // Validate required fields
        if (field.hasAttribute('required') && !value) {
            field.classList.add('error');
            return false;
        }
        
        // Validate email
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.classList.add('error');
                showFieldError(field, '{{ __("Please enter a valid email address") }}');
                return false;
            }
        }
        
        // Validate phone6
        if (field.name === 'phone' && value) {
            const phoneRegex = /^(\+962|07)\d{8,9}$/;
            if (!phoneRegex.test(value.replace(/\s/g, ''))) {
                field.classList.add('error');
                showFieldError(field, '{{ __("Please enter a valid phone number") }}');
                return false;
            }
        }
        
        return true;
    }
    
    function showFieldError(field, message) {
        const formGroup = field.closest('.form-group');
        let errorElement = formGroup.querySelector('.form-error.custom');
        
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'form-error custom';
            errorElement.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span></span>';
            
            const helpElement = formGroup.querySelector('.form-help');
            if (helpElement) {
                formGroup.insertBefore(errorElement, helpElement);
            } else {
                formGroup.appendChild(errorElement);
            }
        }
        
        errorElement.querySelector('span').textContent = message;
        errorElement.style.display = 'flex';
    }
    
    // Auto-save functionality (optional)
    let autoSaveTimeout;
    
    function autoSave() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            const formData = new FormData(document.getElementById('profile-form'));
            
            fetch('{{ route("user.profile.auto-save") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAutoSaveIndicator();
                }
            })
            .catch(error => {
                console.log('Auto-save failed:', error);
            });
        }, 3000);
    }
    
    function showAutoSaveIndicator() {
        const indicator = document.createElement('div');
        indicator.textContent = '{{ __("Draft saved") }}';
        indicator.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: var(--success-500);
            color: white;
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            z-index: 9999;
            animation: slideInLeft 0.3s ease-out;
        `;
        
        document.body.appendChild(indicator);
        
        setTimeout(() => {
            indicator.style.animation = 'slideOutLeft 0.3s ease-out forwards';
            setTimeout(() => {
                if (document.body.contains(indicator)) {
                    document.body.removeChild(indicator);
                }
            }, 300);
        }, 2000);
    }
    
    // Enable auto-save on form changes
    document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
        input.addEventListener('input', autoSave);
    });
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
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
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Save with Ctrl+S
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            document.getElementById('profile-form').submit();
        }
        
        // Cancel with Escape
        if (e.key === 'Escape') {
            if (confirm('{{ __("Are you sure you want to cancel? Any unsaved changes will be lost.") }}')) {
                window.location.href = '{{ route("user.profile.show") }}';
            }
        }
    });
    
    // Warn about unsaved changes
    let formChanged = false;
    
    document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
        input.addEventListener('input', () => {
            formChanged = true;
        });
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '{{ __("You have unsaved changes. Are you sure you want to leave?") }}';
            return e.returnValue;
        }
    });
    
    // Reset form changed flag on submit
    document.getElementById('profile-form').addEventListener('submit', () => {
        formChanged = false;
    });
</script>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    @keyframes slideInLeft {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutLeft {
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }
</style>
@endpush