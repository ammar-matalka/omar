@extends('layouts.admin')

@section('title', __('Edit Subject'))
@section('page-title', __('Edit Subject'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.subjects.index') }}" class="breadcrumb-link">{{ __('Subjects') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Edit') }} - {{ $subject->name }}
    </div>
@endsection

@push('styles')
<style>
    .edit-subject-container {
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
    
    .hierarchy-selector {
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .hierarchy-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .hierarchy-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-md);
        align-items: end;
    }
    
    .current-image {
        margin-bottom: var(--space-lg);
        text-align: center;
    }
    
    .current-image-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-md);
    }
    
    .current-image-container {
        position: relative;
        display: inline-block;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .current-image-img {
        max-width: 300px;
        max-height: 200px;
        object-fit: cover;
    }
    
    .remove-current {
        display: block;
        margin: var(--space-sm) auto 0;
        color: var(--error-600);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
        text-decoration: underline;
    }
    
    .remove-current:hover {
        color: var(--error-700);
    }
    
    .image-upload-area {
        border: 2px dashed var(--admin-secondary-300);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        text-align: center;
        transition: all var(--transition-fast);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .image-upload-area:hover {
        border-color: var(--admin-primary-500);
        background: var(--admin-primary-50);
    }
    
    .image-upload-area.dragover {
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
        font-weight: 500;
    }
    
    .upload-hint {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .image-preview {
        margin-top: var(--space-lg);
        text-align: center;
        display: none;
    }
    
    .preview-image {
        max-width: 200px;
        max-height: 150px;
        border-radius: var(--radius-md);
        border: 1px solid var(--admin-secondary-200);
        object-fit: cover;
    }
    
    .remove-image {
        display: block;
        margin: var(--space-sm) auto 0;
        color: var(--error-600);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
        text-decoration: underline;
    }
    
    .remove-image:hover {
        color: var(--error-700);
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
        .edit-subject-container {
            margin: 0;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .hierarchy-row {
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
<div class="edit-subject-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-edit"></i>
                {{ __('Edit Subject') }}
            </h1>
            <p class="form-subtitle">{{ __('Update subject information and settings') }}</p>
        </div>
        
        <form action="{{ route('admin.subjects.update', $subject) }}" method="POST" enctype="multipart/form-data" id="subjectForm">
            @csrf
            @method('PUT')
            
            <div class="form-body">
                <!-- Hierarchy Selection -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-sitemap"></i>
                        {{ __('Educational Hierarchy') }}
                    </h2>
                    
                    <div class="hierarchy-selector">
                        <div class="hierarchy-title">
                            <i class="fas fa-layer-group"></i>
                            {{ __('Platform & Grade') }}
                        </div>
                        <div class="hierarchy-row">
                            <div class="form-group">
                                <label for="platform_id" class="form-label">
                                    {{ __('Platform') }}
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="platform_idChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <select 
                                    id="platform_id" 
                                    name="platform_id" 
                                    class="form-select @error('platform_id') error @enderror"
                                    required
                                    onchange="loadGrades(this.value); trackChanges('platform_id')"
                                    data-original="{{ $subject->grade->platform_id }}"
                                >
                                    <option value="">{{ __('Select Platform') }}</option>
                                    @foreach($platforms as $platform)
                                        <option value="{{ $platform->id }}" {{ old('platform_id', $subject->grade->platform_id) == $platform->id ? 'selected' : '' }}>
                                            {{ $platform->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('platform_id')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="grade_id" class="form-label">
                                    {{ __('Grade') }}
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="grade_idChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <select 
                                    id="grade_id" 
                                    name="grade_id" 
                                    class="form-select @error('grade_id') error @enderror"
                                    required
                                    onchange="trackChanges('grade_id')"
                                    data-original="{{ $subject->grade_id }}"
                                >
                                    <option value="">{{ __('Select Grade') }}</option>
                                    @foreach($subject->grade->platform->grades as $grade)
                                        <option value="{{ $grade->id }}" {{ old('grade_id', $subject->grade_id) == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name }} (Grade {{ $grade->grade_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('grade_id')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
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
                                    {{ __('Subject Name') }}
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="nameChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    class="form-input @error('name') error @enderror"
                                    value="{{ old('name', $subject->name) }}"
                                    placeholder="{{ __('Enter subject name') }}"
                                    required
                                    oninput="trackChanges('name')"
                                    data-original="{{ $subject->name }}"
                                >
                                @error('name')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Choose a clear name for the subject') }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="name_ar" class="form-label">
                                    {{ __('Subject Name (Arabic)') }}
                                    <span class="change-indicator" id="name_arChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name_ar" 
                                    name="name_ar" 
                                    class="form-input @error('name_ar') error @enderror"
                                    value="{{ old('name_ar', $subject->name_ar) }}"
                                    placeholder="{{ __('Enter Arabic name (optional)') }}"
                                    dir="rtl"
                                    oninput="trackChanges('name_ar')"
                                    data-original="{{ $subject->name_ar }}"
                                >
                                @error('name_ar')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Optional Arabic translation of the subject name') }}</div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    {{ __('Description') }}
                                    <span class="change-indicator" id="descriptionChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    class="form-textarea @error('description') error @enderror"
                                    placeholder="{{ __('Enter subject description') }}"
                                    oninput="trackChanges('description')"
                                    data-original="{{ $subject->description }}"
                                >{{ old('description', $subject->description) }}</textarea>
                                @error('description')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Describe the subject and its learning objectives') }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description_ar" class="form-label">
                                    {{ __('Description (Arabic)') }}
                                    <span class="change-indicator" id="description_arChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <textarea 
                                    id="description_ar" 
                                    name="description_ar" 
                                    class="form-textarea @error('description_ar') error @enderror"
                                    placeholder="{{ __('Enter Arabic description (optional)') }}"
                                    dir="rtl"
                                    oninput="trackChanges('description_ar')"
                                    data-original="{{ $subject->description_ar }}"
                                >{{ old('description_ar', $subject->description_ar) }}</textarea>
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
                
                <!-- Subject Image -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-image"></i>
                        {{ __('Subject Image') }}
                    </h2>
                    
                    @if($subject->image)
                        <div class="current-image">
                            <div class="current-image-title">{{ __('Current Image') }}</div>
                            <div class="current-image-container">
                                <img src="{{ Storage::url($subject->image) }}" alt="{{ $subject->name }}" class="current-image-img">
                            </div>
                            <button type="button" class="remove-current" onclick="removeSubjectImage()">
                                <i class="fas fa-trash"></i> {{ __('Remove Current Image') }}
                            </button>
                        </div>
                    @endif
                    
                    <div class="form-grid">
                        <div class="form-row single">
                            <div class="form-group">
                                <label for="image" class="form-label">
                                    {{ $subject->image ? __('Replace Image') : __('Upload Image') }}
                                    <span class="change-indicator" id="imageChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <div class="image-upload-area" onclick="document.getElementById('image').click()">
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
                                            {{ $subject->image ? __('Click to replace image or drag and drop') : __('Click to upload or drag and drop') }}
                                        </div>
                                        <div class="upload-hint">{{ __('PNG, JPG, GIF up to 2MB') }}</div>
                                    </div>
                                </div>
                                @error('image')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">
                                    {{ $subject->image ? __('Upload a new image to replace the current one') : __('Optional subject icon or representative image') }}
                                </div>
                                
                                <div id="imagePreview" class="image-preview">
                                    <img id="previewImg" class="preview-image" src="" alt="Preview">
                                    <button type="button" class="remove-image" onclick="removeImage()">
                                        <i class="fas fa-trash"></i> {{ __('Remove Image') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Subject Settings -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-cogs"></i>
                        {{ __('Subject Settings') }}
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
                                        {{ old('is_active', $subject->is_active) ? 'checked' : '' }}
                                        onchange="trackChanges('is_active')"
                                        data-original="{{ $subject->is_active ? '1' : '0' }}"
                                    >
                                    <label for="is_active" class="checkbox-label">
                                        {{ __('Active Subject') }}
                                    </label>
                                    <span class="change-indicator" id="is_activeChangeIndicator">{{ __('Modified') }}</span>
                                </div>
                                <div class="form-help">{{ __('Make the subject available for use') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <div>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('Back to Subjects') }}
                    </a>
                </div>
                
                <div class="btn-group">
                    <a href="{{ route('admin.subjects.show', $subject) }}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i>
                        {{ __('View Subject') }}
                    </a>
                    
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        {{ __('Reset') }}
                    </button>
                    
                    <button type="submit" class="btn btn-success" id="updateBtn">
                        <i class="fas fa-save"></i>
                        {{ __('Update Subject') }}
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
        } else if (field.type === 'file') {
            currentValue = field.files.length > 0 ? 'changed' : '';
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
            updateBtn.classList.add('btn-success');
        } else {
            updateBtn.classList.remove('btn-success');
            updateBtn.classList.add('btn-secondary');
        }
    }
    
    // Load grades based on selected platform
    function loadGrades(platformId) {
        const gradeSelect = document.getElementById('grade_id');
        
        if (platformId) {
            fetch(`/admin/platforms/${platformId}/grades`)
                .then(response => response.json())
                .then(grades => {
                    gradeSelect.innerHTML = '<option value="">{{ __("Select Grade") }}</option>';
                    
                    grades.forEach(grade => {
                        const option = document.createElement('option');
                        option.value = grade.id;
                        option.textContent = `${grade.name} (Grade ${grade.grade_number})`;
                        if (grade.id == originalValues['grade_id']) {
                            option.selected = true;
                        }
                        gradeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading grades:', error));
        }
    }
    
    // Preview uploaded image
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
                alert('{{ __("Please select a valid image file") }}');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Remove uploaded image
    function removeImage() {
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('previewImg').src = '';
        
        const indicator = document.getElementById('imageChangeIndicator');
        if (indicator) indicator.style.display = 'none';
        updateFormState();
    }
    
    // Remove subject image (add hidden input to mark for deletion)
    function removeSubjectImage() {
        if (confirm('{{ __("Are you sure you want to remove the current image?") }}')) {
            // Add hidden input to mark image for deletion
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'remove_current_image';
            deleteInput.value = '1';
            document.getElementById('subjectForm').appendChild(deleteInput);
            
            // Hide current image display
            document.querySelector('.current-image').style.display = 'none';
            
            trackChanges('image');
        }
    }
    
    // Reset form to original values
    function resetForm() {
        if (confirm('{{ __("Are you sure you want to reset the form? All changes will be lost.") }}')) {
            // Reset text fields
            Object.keys(originalValues).forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field && field.type !== 'file') {
                    if (field.type === 'checkbox') {
                        field.checked = originalValues[fieldId] === '1';
                    } else {
                        field.value = originalValues[fieldId] || '';
                    }
                }
            });
            
            // Reset image upload
            document.getElementById('image').value = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('previewImg').src = '';
            
            // Show current image if it was hidden
            const currentImage = document.querySelector('.current-image');
            if (currentImage) currentImage.style.display = 'block';
            
            // Remove any delete markers
            const deleteInput = document.querySelector('input[name="remove_current_image"]');
            if (deleteInput) deleteInput.remove();
            
            // Load grades for current platform
            loadGrades(originalValues['platform_id']);
            
            // Hide all change indicators
            document.querySelectorAll('.change-indicator').forEach(indicator => {
                indicator.style.display = 'none';
            });
            
            hasChanges = false;
            updateFormState();
        }
    }
    
    // Drag and drop functionality
    const imageUploadArea = document.querySelector('.image-upload-area');
    
    imageUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    imageUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    imageUploadArea.addEventListener('drop', function(e) {
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
    document.getElementById('subjectForm').addEventListener('submit', function(e) {
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