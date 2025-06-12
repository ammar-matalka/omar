@extends('layouts.admin')

@section('title', __('Edit Category'))
@section('page-title', __('Edit Category'))

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
        {{ __('Edit') }} - {{ $category->name }}
    </div>
@endsection

@push('styles')
<style>
    .edit-category-container {
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
    
    .current-image {
        margin-bottom: var(--space-lg);
        text-align: center;
    }
    
    .current-image img {
        max-width: 200px;
        height: 150px;
        object-fit: cover;
        border-radius: var(--radius-lg);
        border: 1px solid var(--admin-secondary-200);
        box-shadow: var(--shadow-sm);
    }
    
    .current-image-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-sm);
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
    
    .change-indicator {
        background: var(--warning-100);
        color: var(--warning-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        margin-left: var(--space-sm);
        display: none;
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
        .edit-category-container {
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
<div class="edit-category-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-edit"></i>
                {{ __('Edit Category') }}
            </h1>
            <p class="form-subtitle">{{ __('Update category information and settings') }}</p>
        </div>
        
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" id="categoryForm">
            @csrf
            @method('PUT')
            
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
                                <span class="change-indicator" id="nameChangeIndicator">{{ __('Modified') }}</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-input @error('name') error @enderror"
                                value="{{ old('name', $category->name) }}"
                                placeholder="{{ __('Enter category name') }}"
                                required
                                oninput="updateSlugPreview(); trackChanges('name')"
                                data-original="{{ $category->name }}"
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
                                <span class="change-indicator" id="slugChangeIndicator">{{ __('Modified') }}</span>
                            </label>
                            <input 
                                type="text" 
                                id="slug" 
                                name="slug" 
                                class="form-input @error('slug') error @enderror"
                                value="{{ old('slug', $category->slug) }}"
                                placeholder="{{ __('auto-generated-slug') }}"
                                readonly
                                data-original="{{ $category->slug }}"
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
                                <span class="change-indicator" id="descriptionChangeIndicator">{{ __('Modified') }}</span>
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                class="form-textarea @error('description') error @enderror"
                                placeholder="{{ __('Enter category description (optional)') }}"
                                rows="4"
                                oninput="trackChanges('description')"
                                data-original="{{ $category->description }}"
                            >{{ old('description', $category->description) }}</textarea>
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
                    
                    @if($category->image)
                        <div class="current-image">
                            <label class="current-image-label">{{ __('Current Image') }}</label>
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}">
                        </div>
                    @endif
                    
                    <div class="form-row single">
                        <div class="form-group">
                            <label for="image" class="form-label">
                                {{ $category->image ? __('Replace Image') : __('Upload Image') }}
                                <span class="change-indicator" id="imageChangeIndicator">{{ __('Modified') }}</span>
                            </label>
                            <div class="image-upload" onclick="document.getElementById('image').click()">
                                <input 
                                    type="file" 
                                    id="image" 
                                    name="image" 
                                    accept="image/*"
                                    style="display: none;"
                                    onchange="previewImage(this); trackChanges('image')"
                                >
                                <div class="upload-content">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">
                                        {{ $category->image ? __('Click to replace image or drag and drop') : __('Click to upload or drag and drop') }}
                                    </div>
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
                            <div class="form-help">
                                {{ $category->image ? __('Upload a new image to replace the current one.') : __('Upload an image to represent this category.') }}
                                {{ __('Recommended size: 400x300 pixels') }}
                            </div>
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
                    <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i>
                        {{ __('View Category') }}
                    </a>
                    
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        {{ __('Reset') }}
                    </button>
                    
                    <button type="submit" class="btn btn-success" id="updateBtn">
                        <i class="fas fa-save"></i>
                        {{ __('Update Category') }}
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
        const currentValue = field.value;
        const originalValue = originalValues[fieldName] || '';
        
        if (currentValue !== originalValue) {
            indicator.style.display = 'inline-block';
            hasChanges = true;
        } else {
            indicator.style.display = 'none';
        }
        
        // Check if any field has changes
        updateFormState();
    }
    
    // Update form state based on changes
    function updateFormState() {
        const updateBtn = document.getElementById('updateBtn');
        hasChanges = document.querySelector('.change-indicator[style*="inline-block"]') !== null;
        
        if (hasChanges) {
            updateBtn.classList.remove('btn-secondary');
            updateBtn.classList.add('btn-success');
        } else {
            updateBtn.classList.remove('btn-success');
            updateBtn.classList.add('btn-secondary');
        }
    }
    
    // Generate slug from name
    function updateSlugPreview() {
        const name = document.getElementById('name').value;
        const slug = name
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        
        const slugField = document.getElementById('slug');
        slugField.value = slug;
        
        // Track slug changes
        const indicator = document.getElementById('slugChangeIndicator');
        if (slug !== originalValues['slug']) {
            indicator.style.display = 'inline-block';
            hasChanges = true;
        } else {
            indicator.style.display = 'none';
        }
        
        updateFormState();
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
        
        // Hide image change indicator
        document.getElementById('imageChangeIndicator').style.display = 'none';
        updateFormState();
    }
    
    // Reset form to original values
    function resetForm() {
        if (confirm('{{ __("Are you sure you want to reset the form? All changes will be lost.") }}')) {
            // Reset text fields
            document.getElementById('name').value = originalValues['name'] || '';
            document.getElementById('slug').value = originalValues['slug'] || '';
            document.getElementById('description').value = originalValues['description'] || '';
            
            // Reset image
            document.getElementById('image').value = '';
            removeImage(new Event('click'));
            
            // Hide all change indicators
            document.querySelectorAll('.change-indicator').forEach(indicator => {
                indicator.style.display = 'none';
            });
            
            hasChanges = false;
            updateFormState();
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
            trackChanges('image');
        }
    });
    
    // Form validation and submission
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        
        if (!name) {
            e.preventDefault();
            alert('{{ __("Please enter a category name") }}');
            document.getElementById('name').focus();
            return;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('updateBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Updating...") }}';
        submitBtn.disabled = true;
        
        // Re-enable button after 5 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
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