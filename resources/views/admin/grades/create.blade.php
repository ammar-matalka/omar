@extends('layouts.admin')

@section('title', __('Create Grade'))
@section('page-title', __('Create Grade'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.grades.index') }}" class="breadcrumb-link">{{ __('Grades') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Create') }}
    </div>
@endsection

@push('styles')
<style>
    .create-grade-container {
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
    
    .form-row.triple {
        grid-template-columns: 1fr 1fr 1fr;
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
        min-height: 120px;
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
    
    .platform-selector {
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .platform-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .grade-number-input {
        position: relative;
    }
    
    .grade-number-preview {
        position: absolute;
        right: var(--space-md);
        top: 50%;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        opacity: 0;
        transition: all var(--transition-fast);
    }
    
    .grade-number-preview.show {
        opacity: 1;
    }
    
    .grade-input {
        padding-right: calc(var(--space-md) + 50px);
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
        .create-grade-container {
            margin: 0;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-row.triple {
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
<div class="create-grade-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-graduation-cap"></i>
                {{ __('Create New Grade') }}
            </h1>
            <p class="form-subtitle">{{ __('Add a new grade level to organize your educational content') }}</p>
        </div>
        
        <form action="{{ route('admin.grades.store') }}" method="POST" id="gradeForm">
            @csrf
            
            <div class="form-body">
                <!-- Platform Selection -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-desktop"></i>
                        {{ __('Platform Assignment') }}
                    </h2>
                    
                    <div class="platform-selector">
                        <div class="platform-title">
                            <i class="fas fa-layer-group"></i>
                            {{ __('Select Educational Platform') }}
                        </div>
                        <div class="form-group">
                            <label for="platform_id" class="form-label">
                                {{ __('Platform') }}
                                <span class="required">*</span>
                            </label>
                            <select 
                                id="platform_id" 
                                name="platform_id" 
                                class="form-select @error('platform_id') error @enderror"
                                required
                            >
                                <option value="">{{ __('Select a platform') }}</option>
                                @foreach($platforms as $platform)
                                    <option value="{{ $platform->id }}" {{ old('platform_id') == $platform->id ? 'selected' : '' }}>
                                        {{ $platform->name }}
                                        @if($platform->name_ar) - {{ $platform->name_ar }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('platform_id')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">{{ __('Choose the platform this grade belongs to') }}</div>
                        </div>
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
                                    {{ __('Grade Name') }}
                                    <span class="required">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    class="form-input @error('name') error @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="{{ __('Enter grade name') }}"
                                    required
                                >
                                @error('name')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Choose a clear name for the grade level') }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="name_ar" class="form-label">
                                    {{ __('Grade Name (Arabic)') }}
                                </label>
                                <input 
                                    type="text" 
                                    id="name_ar" 
                                    name="name_ar" 
                                    class="form-input @error('name_ar') error @enderror"
                                    value="{{ old('name_ar') }}"
                                    placeholder="{{ __('Enter Arabic name (optional)') }}"
                                    dir="rtl"
                                >
                                @error('name_ar')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Optional Arabic translation of the grade name') }}</div>
                            </div>
                        </div>
                        
                        <div class="form-row single">
                            <div class="form-group">
                                <label for="grade_number" class="form-label">
                                    {{ __('Grade Number') }}
                                    <span class="required">*</span>
                                </label>
                                <div class="grade-number-input">
                                    <input 
                                        type="number" 
                                        id="grade_number" 
                                        name="grade_number" 
                                        class="form-input grade-input @error('grade_number') error @enderror"
                                        value="{{ old('grade_number') }}"
                                        placeholder="{{ __('Enter grade number (1-12)') }}"
                                        min="1"
                                        max="12"
                                        required
                                        oninput="updateGradePreview(this.value)"
                                    >
                                    <div class="grade-number-preview" id="gradePreview"></div>
                                </div>
                                @error('grade_number')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Numerical order of the grade (1-12)') }}</div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    {{ __('Description') }}
                                </label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    class="form-textarea @error('description') error @enderror"
                                    placeholder="{{ __('Enter grade description') }}"
                                >{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Describe the grade level and its educational objectives') }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description_ar" class="form-label">
                                    {{ __('Description (Arabic)') }}
                                </label>
                                <textarea 
                                    id="description_ar" 
                                    name="description_ar" 
                                    class="form-textarea @error('description_ar') error @enderror"
                                    placeholder="{{ __('Enter Arabic description (optional)') }}"
                                    dir="rtl"
                                >{{ old('description_ar') }}</textarea>
                                @error('description_ar')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Optional Arabic translation of the description') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grade Settings -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-cogs"></i>
                        {{ __('Grade Settings') }}
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-row single">
                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input 
                                        type="checkbox" 
                                        id="is_active" 
                                        name="is_active" 
                                        class="checkbox-input"
                                        value="1"
                                        {{ old('is_active', true) ? 'checked' : '' }}
                                    >
                                    <label for="is_active" class="checkbox-label">
                                        {{ __('Active Grade') }}
                                    </label>
                                </div>
                                <div class="form-help">{{ __('Make the grade available for use') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <div>
                    <a href="{{ route('admin.grades.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('Back to Grades') }}
                    </a>
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        {{ __('Reset') }}
                    </button>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        {{ __('Create Grade') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update grade number preview
    function updateGradePreview(value) {
        const preview = document.getElementById('gradePreview');
        if (value && value >= 1 && value <= 12) {
            preview.textContent = value;
            preview.classList.add('show');
        } else {
            preview.classList.remove('show');
        }
    }
    
    // Reset form
    function resetForm() {
        if (confirm('{{ __("Are you sure you want to reset the form? All entered data will be lost.") }}')) {
            document.getElementById('gradeForm').reset();
            document.getElementById('gradePreview').classList.remove('show');
        }
    }
    
    // Form validation and submission
    document.getElementById('gradeForm').addEventListener('submit', function(e) {
        const platform = document.getElementById('platform_id').value;
        const name = document.getElementById('name').value.trim();
        const gradeNumber = document.getElementById('grade_number').value;
        
        if (!platform) {
            e.preventDefault();
            alert('{{ __("Please select a platform") }}');
            document.getElementById('platform_id').focus();
            return;
        }
        
        if (!name) {
            e.preventDefault();
            alert('{{ __("Please enter a grade name") }}');
            document.getElementById('name').focus();
            return;
        }
        
        if (!gradeNumber || gradeNumber < 1 || gradeNumber > 12) {
            e.preventDefault();
            alert('{{ __("Please enter a valid grade number (1-12)") }}');
            document.getElementById('grade_number').focus();
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
    
    // Initialize grade preview if value exists
    document.addEventListener('DOMContentLoaded', function() {
        const gradeNumberInput = document.getElementById('grade_number');
        if (gradeNumberInput.value) {
            updateGradePreview(gradeNumberInput.value);
        }
        
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
    });
</script>
@endpush