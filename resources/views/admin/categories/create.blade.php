@extends('layouts.admin')

@section('title', __('Create Category'))
@section('page-title', __('Create Category'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.categories.index') }}" class="breadcrumb-link">{{ __('Categories') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Create') }}
    </div>
@endsection

@push('styles')
<style>
    .create-category-container {
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
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
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
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
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
    .form-textarea {
        padding: var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-family: inherit;
        transition: all var(--transition-fast);
        background: white;
    }
    
    .form-input:focus,
    .form-textarea:focus {
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
    
    .slug-preview {
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-md);
        padding: var(--space-md);
        margin-top: var(--space-sm);
        font-family: monospace;
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
    }
    
    .image-upload {
        border: 2px dashed var(--admin-secondary-300);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        text-align: center;
        transition: all var(--transition-fast);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .image-upload:hover {
        border-color: var(--admin-primary-500);
        background: var(--admin-primary-50);
    }
    
    .image-upload.dragover {
        border-color: var(--admin-primary-500);
        background: var(--admin-primary-50);
        transform: scale(1.02);
    }
    
    .upload-icon {
        font-size: 3rem;
        color: var(--admin-secondary-400);
        margin-bottom: var(--space-md);
    }
    
    .upload-text {
        color: var(--admin-secondary-600);
        margin-bottom: var(--space-sm);
    }
    
    .upload-hint {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .image-preview {
        position: relative;
        max-width: 200px;
        margin: var(--space-md) auto 0;
    }
    
    .preview-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: var(--radius-md);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .remove-image {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 24px;
        height: 24px;
        background: var(--error-500);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition-fast);
    }
    
    .remove-image:hover {
        background: var(--error-600);
        transform: scale(1.1);
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
    .form-textarea.error {
        border-color: var(--error-500);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    @media (max-width: 768px) {
        .create-category-container {
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
<div class="create-category-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-plus"></i>
                {{ __('Create New Category') }}
            </h1>
            <p class="form-subtitle">{{ __('Add a new category to organize your products') }}</p>
        </div>
        
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
            @csrf
            
            <div class="form-body">
                <!-- Basic Information -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Basic Information') }}
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                {{ __('Category Name') }}
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-input @error('name') error @enderror"
                                value="{{ old('name') }}"
                                placeholder="{{ __('Enter category name') }}"
                                required
                                oninput="updateSlugPreview()"
                            >
                            @error('name')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">{{ __('Choose a clear, descriptive name for your category') }}</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug" class="form-label">
                                {{ __('URL Slug') }}
                            </label>
                            <input 
                                type="text" 
                                id="slug" 
                                name="slug" 
                                class="form-input @error('slug') error @enderror"
                                value="{{ old('slug') }}"
                                placeholder="{{ __('auto-generated-slug') }}"
                                readonly
                            >
                            @error('slug')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">{{ __('URL-friendly version of the name (auto-generated)') }}</div>
                        </div>
                    </div>
                    
                    <div class="form-row single">
                        <div class="form-group">
                            <label for="description" class="form-label">
                                {{ __('Description') }}
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                class="form-textarea @error('description') error @enderror"
                                placeholder="{{ __('Enter category description (optional)') }}"
                                rows="4"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">{{ __('Provide a brief description of what products this category contains') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Category Image -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-image"></i>
                        {{ __('Category Image') }}
                    </h2>
                    
                    <div class="form-row single">
                        <div class="form-group">
                            <label for="image" class="form-label">
                                {{ __('Upload Image') }}
                            </label>
                            <div class="image-upload" onclick="document.getElementById('image').click()">
                                <input 
                                    type="file" 
                                    id="image" 
                                    name="image" 
                                    accept="image/*"
                                    style="display: none;"
                                    onchange="previewImage(this)"
                                >
                                <div class="upload-content">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">{{ __('Click to upload or drag and drop') }}</div>
                                    <div class="upload-hint">{{ __('PNG, JPG, GIF up to 2MB') }}</div>
                                </div>
                                <div id="imagePreview" class="image-preview" style="display: none;">
                                    <img id="previewImg" class="preview-image" src="" alt="Preview">
                                    <button type="button" class="remove-image" onclick="removeImage(event)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @error('image')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">{{ __('Upload an image to represent this category. Recommended size: 400x300 pixels') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <div>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('Back to Categories') }}
                    </a>
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        {{ __('Reset') }}
                    </button>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        {{ __('Create Category') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Generate slug from name
    function updateSlugPreview() {
        const name = document.getElementById('name').value;
        const slug = name
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        
        document.getElementById('slug').value = slug;
    }
    
    // Image preview functionality
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('{{ __("File size must be less than 2MB") }}');
                input.value = '';
                return;
            }
            
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('{{ __("Please select an image file") }}');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
                document.querySelector('.upload-content').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Remove image preview
    function removeImage(event) {
        event.stopPropagation();
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').style.display = 'none';
        document.querySelector('.upload-content').style.display = 'block';
    }
    
    // Reset form
    function resetForm() {
        if (confirm('{{ __("Are you sure you want to reset the form? All entered data will be lost.") }}')) {
            document.getElementById('categoryForm').reset();
            removeImage(new Event('click'));
            updateSlugPreview();
        }
    }
    
    // Drag and drop functionality
    const imageUpload = document.querySelector('.image-upload');
    
    imageUpload.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    imageUpload.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    imageUpload.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('image').files = files;
            previewImage(document.getElementById('image'));
        }
    });
    
    // Form validation
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        
        if (!name) {
            e.preventDefault();
            alert('{{ __("Please enter a category name") }}');
            document.getElementById('name').focus();
            return;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Creating...") }}';
        submitBtn.disabled = true;
        
        // Re-enable button after 5 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
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