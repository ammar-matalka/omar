@extends('layouts.admin')

@section('title', __('Create Educational Card'))
@section('page-title', __('Create Educational Card'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.educational-cards.index') }}" class="breadcrumb-link">{{ __('Educational Cards') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Create') }}
    </div>
@endsection

@push('styles')
<style>
    .create-card-container {
        max-width: 900px;
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
        background: linear-gradient(135deg, #6366f1, #4f46e5);
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
        grid-template-columns: 1fr 1fr 1fr;
        gap: var(--space-md);
        align-items: end;
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
    
    .images-preview {
        margin-top: var(--space-lg);
        display: none;
    }
    
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: var(--space-md);
    }
    
    .preview-item {
        position: relative;
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .preview-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    
    .remove-image {
        position: absolute;
        top: var(--space-xs);
        right: var(--space-xs);
        background: var(--error-500);
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }
    
    .remove-image:hover {
        background: var(--error-600);
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
        .create-card-container {
            margin: 0;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-row.triple {
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
<div class="create-card-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-id-card"></i>
                {{ __('Create New Educational Card') }}
            </h1>
            <p class="form-subtitle">{{ __('Add a new educational card to your library') }}</p>
        </div>
        
        <form action="{{ route('admin.educational-cards.store') }}" method="POST" enctype="multipart/form-data" id="cardForm">
            @csrf
            
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
                            {{ __('Select Platform, Grade & Subject') }}
                        </div>
                        <div class="hierarchy-row">
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
                                    onchange="loadGrades(this.value)"
                                >
                                    <option value="">{{ __('Select Platform') }}</option>
                                    @foreach($platforms as $platform)
                                        <option value="{{ $platform->id }}" {{ old('platform_id', request('platform')) == $platform->id ? 'selected' : '' }}>
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
                                </label>
                                <select 
                                    id="grade_id" 
                                    name="grade_id" 
                                    class="form-select @error('grade_id') error @enderror"
                                    required
                                    disabled
                                    onchange="loadSubjects(this.value)"
                                >
                                    <option value="">{{ __('Select Grade') }}</option>
                                </select>
                                @error('grade_id')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="subject_id" class="form-label">
                                    {{ __('Subject') }}
                                    <span class="required">*</span>
                                </label>
                                <select 
                                    id="subject_id" 
                                    name="subject_id" 
                                    class="form-select @error('subject_id') error @enderror"
                                    required
                                    disabled
                                >
                                    <option value="">{{ __('Select Subject') }}</option>
                                </select>
                                @error('subject_id')
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
                                <label for="title" class="form-label">
                                    {{ __('Card Title') }}
                                    <span class="required">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="title" 
                                    name="title" 
                                    class="form-input @error('title') error @enderror"
                                    value="{{ old('title') }}"
                                    placeholder="{{ __('Enter card title') }}"
                                    required
                                >
                                @error('title')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Choose a clear and descriptive title') }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="title_ar" class="form-label">
                                    {{ __('Card Title (Arabic)') }}
                                </label>
                                <input 
                                    type="text" 
                                    id="title_ar" 
                                    name="title_ar" 
                                    class="form-input @error('title_ar') error @enderror"
                                    value="{{ old('title_ar') }}"
                                    placeholder="{{ __('Enter Arabic title (optional)') }}"
                                    dir="rtl"
                                >
                                @error('title_ar')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Optional Arabic translation of the title') }}</div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    {{ __('Description') }}
                                    <span class="required">*</span>
                                </label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    class="form-textarea @error('description') error @enderror"
                                    placeholder="{{ __('Enter card description') }}"
                                    required
                                >{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Describe the educational content and objectives') }}</div>
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
                
                <!-- Card Details -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-tags"></i>
                        {{ __('Card Details') }}
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-row triple">
                            <div class="form-group">
                                <label for="price" class="form-label">
                                    {{ __('Price') }}
                                    <span class="required">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="price" 
                                    name="price" 
                                    class="form-input @error('price') error @enderror"
                                    value="{{ old('price') }}"
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                    required
                                >
                                @error('price')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Card price in USD') }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="stock" class="form-label">
                                    {{ __('Stock Quantity') }}
                                    <span class="required">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="stock" 
                                    name="stock" 
                                    class="form-input @error('stock') error @enderror"
                                    value="{{ old('stock') }}"
                                    placeholder="0"
                                    min="0"
                                    required
                                >
                                @error('stock')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Available quantity') }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="difficulty_level" class="form-label">
                                    {{ __('Difficulty Level') }}
                                    <span class="required">*</span>
                                </label>
                                <select 
                                    id="difficulty_level" 
                                    name="difficulty_level" 
                                    class="form-select @error('difficulty_level') error @enderror"
                                    required
                                >
                                    <option value="">{{ __('Select Difficulty') }}</option>
                                    <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>{{ __('Easy') }}</option>
                                    <option value="medium" {{ old('difficulty_level') == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                    <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>{{ __('Hard') }}</option>
                                </select>
                                @error('difficulty_level')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="card_type" class="form-label">
                                    {{ __('Card Type') }}
                                    <span class="required">*</span>
                                </label>
                                <select 
                                    id="card_type" 
                                    name="card_type" 
                                    class="form-select @error('card_type') error @enderror"
                                    required
                                >
                                    <option value="">{{ __('Select Type') }}</option>
                                    <option value="digital" {{ old('card_type') == 'digital' ? 'selected' : '' }}>{{ __('Digital') }}</option>
                                    <option value="physical" {{ old('card_type') == 'physical' ? 'selected' : '' }}>{{ __('Physical') }}</option>
                                    <option value="both" {{ old('card_type') == 'both' ? 'selected' : '' }}>{{ __('Both') }}</option>
                                </select>
                                @error('card_type')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Choose the format of the card') }}</div>
                            </div>
                            
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
                                        {{ __('Active Card') }}
                                    </label>
                                </div>
                                <div class="form-help">{{ __('Make the card available for purchase') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card Images -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-images"></i>
                        {{ __('Card Images') }}
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-row single">
                            <div class="form-group">
                                <label for="images" class="form-label">
                                    {{ __('Upload Images') }}
                                </label>
                                <div class="image-upload-area" onclick="document.getElementById('images').click()">
                                    <input 
                                        type="file" 
                                        id="images" 
                                        name="images[]" 
                                        accept="image/*"
                                        multiple
                                        style="display: none;"
                                        onchange="previewImages(this)"
                                    >
                                    <div class="upload-content">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <div class="upload-text">{{ __('Click to upload or drag and drop') }}</div>
                                        <div class="upload-hint">{{ __('PNG, JPG, GIF up to 2MB each. Multiple files allowed.') }}</div>
                                    </div>
                                </div>
                                @error('images')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                @error('images.*')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Upload multiple images for the card. First image will be the primary image.') }}</div>
                                
                                <div id="imagesPreview" class="images-preview">
                                    <div class="preview-grid" id="previewGrid">
                                        <!-- Preview images will be added here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <div>
                    <a href="{{ route('admin.educational-cards.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('Back to Cards') }}
                    </a>
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        {{ __('Reset') }}
                    </button>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        {{ __('Create Card') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedFiles = [];
    
    // Load grades based on selected platform
    function loadGrades(platformId) {
        const gradeSelect = document.getElementById('grade_id');
        const subjectSelect = document.getElementById('subject_id');
        
        // Clear and disable grade and subject selects
        gradeSelect.innerHTML = '<option value="">{{ __("Select Grade") }}</option>';
        gradeSelect.disabled = true;
        subjectSelect.innerHTML = '<option value="">{{ __("Select Subject") }}</option>';
        subjectSelect.disabled = true;
        
        if (platformId) {
            fetch(`/admin/platforms/${platformId}/grades`)
                .then(response => response.json())
                .then(grades => {
                    grades.forEach(grade => {
                        const option = document.createElement('option');
                        option.value = grade.id;
                        option.textContent = `${grade.name} (Grade ${grade.grade_number})`;
                        if ('{{ old("grade_id", request("grade")) }}' == grade.id) {
                            option.selected = true;
                        }
                        gradeSelect.appendChild(option);
                    });
                    gradeSelect.disabled = false;
                    
                    // If grade is pre-selected, load subjects
                    if (gradeSelect.value) {
                        loadSubjects(gradeSelect.value);
                    }
                })
                .catch(error => console.error('Error loading grades:', error));
        }
    }
    
    // Load subjects based on selected grade
    function loadSubjects(gradeId) {
        const subjectSelect = document.getElementById('subject_id');
        
        // Clear and disable subject select
        subjectSelect.innerHTML = '<option value="">{{ __("Select Subject") }}</option>';
        subjectSelect.disabled = true;
        
        if (gradeId) {
            fetch(`/admin/grades/${gradeId}/subjects`)
                .then(response => response.json())
                .then(subjects => {
                    subjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        if ('{{ old("subject_id", request("subject")) }}' == subject.id) {
                            option.selected = true;
                        }
                        subjectSelect.appendChild(option);
                    });
                    subjectSelect.disabled = false;
                })
                .catch(error => console.error('Error loading subjects:', error));
        }
    }
    
    // Preview uploaded images
    function previewImages(input) {
        if (input.files && input.files.length > 0) {
            selectedFiles = Array.from(input.files);
            const previewContainer = document.getElementById('imagesPreview');
            const previewGrid = document.getElementById('previewGrid');
            
            // Clear previous previews
            previewGrid.innerHTML = '';
            
            selectedFiles.forEach((file, index) => {
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert(`{{ __("File") }} "${file.name}" {{ __("is too large. Maximum size is 2MB.") }}`);
                    return;
                }
                
                // Validate file type
                if (!file.type.match('image.*')) {
                    alert(`{{ __("File") }} "${file.name}" {{ __("is not a valid image file.") }}`);
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" class="preview-image" alt="Preview ${index + 1}">
                        <button type="button" class="remove-image" onclick="removeImage(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    previewGrid.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            });
            
            previewContainer.style.display = 'block';
        }
    }
    
    // Remove image from preview
    function removeImage(index) {
        selectedFiles.splice(index, 1);
        
        // Update file input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        document.getElementById('images').files = dt.files;
        
        // Re-render previews
        if (selectedFiles.length > 0) {
            previewImages(document.getElementById('images'));
        } else {
            document.getElementById('imagesPreview').style.display = 'none';
        }
    }
    
    // Reset form
    function resetForm() {
        if (confirm('{{ __("Are you sure you want to reset the form? All entered data will be lost.") }}')) {
            document.getElementById('cardForm').reset();
            selectedFiles = [];
            document.getElementById('imagesPreview').style.display = 'none';
            
            // Reset hierarchy selects
            document.getElementById('grade_id').innerHTML = '<option value="">{{ __("Select Grade") }}</option>';
            document.getElementById('grade_id').disabled = true;
            document.getElementById('subject_id').innerHTML = '<option value="">{{ __("Select Subject") }}</option>';
            document.getElementById('subject_id').disabled = true;
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
            document.getElementById('images').files = files;
            previewImages(document.getElementById('images'));
        }
    });
    
    // Form validation and submission
    document.getElementById('cardForm').addEventListener('submit', function(e) {
        const platform = document.getElementById('platform_id').value;
        const grade = document.getElementById('grade_id').value;
        const subject = document.getElementById('subject_id').value;
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const price = document.getElementById('price').value;
        const stock = document.getElementById('stock').value;
        const cardType = document.getElementById('card_type').value;
        const difficultyLevel = document.getElementById('difficulty_level').value;
        
        if (!platform) {
            e.preventDefault();
            alert('{{ __("Please select a platform") }}');
            document.getElementById('platform_id').focus();
            return;
        }
        
        if (!grade) {
            e.preventDefault();
            alert('{{ __("Please select a grade") }}');
            document.getElementById('grade_id').focus();
            return;
        }
        
        if (!subject) {
            e.preventDefault();
            alert('{{ __("Please select a subject") }}');
            document.getElementById('subject_id').focus();
            return;
        }
        
        if (!title) {
            e.preventDefault();
            alert('{{ __("Please enter a card title") }}');
            document.getElementById('title').focus();
            return;
        }
        
        if (!description) {
            e.preventDefault();
            alert('{{ __("Please enter a description") }}');
            document.getElementById('description').focus();
            return;
        }
        
        if (!price || parseFloat(price) < 0) {
            e.preventDefault();
            alert('{{ __("Please enter a valid price") }}');
            document.getElementById('price').focus();
            return;
        }
        
        if (!stock || parseInt(stock) < 0) {
            e.preventDefault();
            alert('{{ __("Please enter a valid stock quantity") }}');
            document.getElementById('stock').focus();
            return;
        }
        
        if (!cardType) {
            e.preventDefault();
            alert('{{ __("Please select a card type") }}');
            document.getElementById('card_type').focus();
            return;
        }
        
        if (!difficultyLevel) {
            e.preventDefault();
            alert('{{ __("Please select a difficulty level") }}');
            document.getElementById('difficulty_level').focus();
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
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Load grades if platform is already selected
        const platformSelect = document.getElementById('platform_id');
        if (platformSelect.value) {
            loadGrades(platformSelect.value);
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