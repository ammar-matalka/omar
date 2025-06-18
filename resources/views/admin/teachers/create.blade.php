@extends('layouts.admin')

@section('title', __('Add Teacher'))
@section('page-title', __('Add Teacher'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.teachers.index') }}" class="breadcrumb-link">{{ __('Teachers') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <span>{{ __('Add Teacher') }}</span>
    </div>
@endsection

@push('styles')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-section {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: var(--shadow-sm);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-lg);
        padding-bottom: var(--space-md);
        border-bottom: 2px solid var(--admin-primary-500);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-lg);
    }
    
    .form-group {
        margin-bottom: var(--space-lg);
    }
    
    .form-label {
        display: block;
        margin-bottom: var(--space-sm);
        font-weight: 500;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
    }
    
    .form-label.required::after {
        content: ' *';
        color: var(--error-500);
    }
    
    .form-input {
        width: 100%;
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        background: white;
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-input.error {
        border-color: var(--error-500);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .form-error {
        color: var(--error-600);
        font-size: 0.75rem;
        margin-top: var(--space-xs);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .form-help {
        color: var(--admin-secondary-500);
        font-size: 0.75rem;
        margin-top: var(--space-xs);
    }
    
    .form-actions {
        display: flex;
        gap: var(--space-lg);
        justify-content: flex-end;
        padding-top: var(--space-xl);
        border-top: 1px solid var(--admin-secondary-200);
        margin-top: var(--space-xl);
    }
    
    .preview-card {
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        text-align: center;
    }
    
    .preview-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 700;
        margin: 0 auto var(--space-md);
    }
    
    .status-toggle {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md);
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-md);
    }
    
    .toggle-switch {
        position: relative;
        width: 50px;
        height: 24px;
        background: var(--admin-secondary-300);
        border-radius: 12px;
        cursor: pointer;
        transition: background var(--transition-fast);
    }
    
    .toggle-switch.active {
        background: var(--success-500);
    }
    
    .toggle-switch::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        transition: transform var(--transition-fast);
    }
    
    .toggle-switch.active::before {
        transform: translateX(26px);
    }
</style>
@endpush

@section('content')
<div class="form-container fade-in">
    <form action="{{ route('admin.teachers.store') }}" method="POST" id="teacherForm">
        @csrf
        
        <!-- Basic Information -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-user"></i>
                {{ __('Basic Information') }}
            </h3>
            
            <div class="form-grid">
                <div>
                    <div class="form-group">
                        <label for="name" class="form-label required">{{ __('Teacher Name') }}</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-input @error('name') error @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="{{ __('Enter teacher full name') }}"
                               required
                               oninput="updatePreview()">
                        @error('name')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-help">{{ __('Full name as it should appear in the system') }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="specialization" class="form-label">{{ __('Specialization') }}</label>
                        <input type="text" 
                               id="specialization" 
                               name="specialization" 
                               class="form-input @error('specialization') error @enderror" 
                               value="{{ old('specialization') }}" 
                               placeholder="{{ __('e.g., Mathematics, Physics, Chemistry') }}"
                               oninput="updatePreview()">
                        @error('specialization')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="form-input @error('description') error @enderror" 
                                  rows="4" 
                                  placeholder="{{ __('Brief description about the teacher (optional)') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-help">{{ __('Maximum 1000 characters') }}</div>
                    </div>
                </div>
                
                <div>
                    <div class="preview-card">
                        <div class="preview-avatar" id="previewAvatar">
                            {{ old('name') ? strtoupper(substr(old('name'), 0, 2)) : '??' }}
                        </div>
                        <div style="font-weight: 600; margin-bottom: var(--space-xs);" id="previewName">
                            {{ old('name') ?: __('Teacher Name') }}
                        </div>
                        <div style="color: var(--admin-secondary-600); font-size: 0.875rem;" id="previewSpecialization">
                            {{ old('specialization') ?: __('Specialization') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-address-book"></i>
                {{ __('Contact Information') }}
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           class="form-input @error('phone') error @enderror" 
                           value="{{ old('phone') }}" 
                           placeholder="{{ __('e.g., +962 7X XXX XXXX') }}">
                    @error('phone')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-input @error('email') error @enderror" 
                           value="{{ old('email') }}" 
                           placeholder="{{ __('teacher@example.com') }}">
                    @error('email')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Display Settings -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-cog"></i>
                {{ __('Display Settings') }}
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="order" class="form-label">{{ __('Display Order') }}</label>
                    <input type="number" 
                           id="order" 
                           name="order" 
                           class="form-input @error('order') error @enderror" 
                           value="{{ old('order', 0) }}" 
                           min="0" 
                           placeholder="0">
                    @error('order')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-help">{{ __('Lower numbers appear first. 0 = automatic ordering.') }}</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">{{ __('Status') }}</label>
                    <div class="status-toggle">
                        <span style="color: var(--admin-secondary-600);">{{ __('Inactive') }}</span>
                        <div class="toggle-switch active" onclick="toggleStatus()" id="statusToggle">
                            <input type="hidden" name="is_active" value="1" id="statusInput">
                        </div>
                        <span style="color: var(--success-600); font-weight: 500;">{{ __('Active') }}</span>
                    </div>
                    <div class="form-help">{{ __('Only active teachers will be visible to users.') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                {{ __('Cancel') }}
            </a>
            
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-save"></i>
                {{ __('Create Teacher') }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Update preview in real-time
function updatePreview() {
    const name = document.getElementById('name').value || '{{ __('Teacher Name') }}';
    const specialization = document.getElementById('specialization').value || '{{ __('Specialization') }}';
    
    document.getElementById('previewName').textContent = name;
    document.getElementById('previewSpecialization').textContent = specialization;
    document.getElementById('previewAvatar').textContent = name.substring(0, 2).toUpperCase() || '??';
}

// Toggle status
function toggleStatus() {
    const toggle = document.getElementById('statusToggle');
    const input = document.getElementById('statusInput');
    
    if (toggle.classList.contains('active')) {
        toggle.classList.remove('active');
        input.value = '0';
    } else {
        toggle.classList.add('active');
        input.value = '1';
    }
}

// Form validation
document.getElementById('teacherForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const name = document.getElementById('name').value.trim();
    
    if (!name) {
        e.preventDefault();
        alert('{{ __('Please enter teacher name') }}');
        document.getElementById('name').focus();
        return;
    }
    
    // Prevent double submission
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Creating...') }}';
    
    // Re-enable after 5 seconds as fallback
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> {{ __('Create Teacher') }}';
    }, 5000);
});

// Auto-resize textarea
document.getElementById('description').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 200) + 'px';
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
@endpush