@extends('layouts.admin')

@section('title', __('Edit Product'))
@section('page-title', __('Edit Product'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.products.index') }}" class="breadcrumb-link">{{ __('Products') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Edit') }} - {{ $product->name }}
    </div>
@endsection

@push('styles')
<style>
    .edit-product-container {
        max-width: 1000px;
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
    
    .change-indicator {
        background: var(--warning-100);
        color: var(--warning-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        margin-left: var(--space-sm);
        display: none;
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
    
    .current-images {
        margin-bottom: var(--space-lg);
    }
    
    .current-images-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-md);
    }
    
    .current-images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: var(--space-md);
    }
    
    .current-image-item {
        position: relative;
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
        background: white;
    }
    
    .current-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    
    .current-image-controls {
        position: absolute;
        top: var(--space-xs);
        right: var(--space-xs);
        display: flex;
        gap: var(--space-xs);
    }
    
    .current-image-info {
        padding: var(--space-sm);
        background: var(--admin-secondary-50);
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-align: center;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .primary-badge {
        background: var(--success-500);
        color: white;
        padding: 2px 6px;
        border-radius: var(--radius-sm);
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .delete-checkbox {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .images-upload-area {
        border: 2px dashed var(--admin-secondary-300);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        text-align: center;
        transition: all var(--transition-fast);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .images-upload-area:hover {
        border-color: var(--admin-primary-500);
        background: var(--admin-primary-50);
    }
    
    .images-upload-area.dragover {
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
    
    .new-images-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: var(--space-md);
        margin-top: var(--space-lg);
    }
    
    .new-image-preview-item {
        position: relative;
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
        background: white;
    }
    
    .new-preview-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    
    .new-image-controls {
        position: absolute;
        top: var(--space-xs);
        right: var(--space-xs);
        display: flex;
        gap: var(--space-xs);
    }
    
    .image-control-btn {
        width: 24px;
        height: 24px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition-fast);
        color: white;
    }
    
    .btn-remove-image {
        background: var(--error-500);
    }
    
    .btn-remove-image:hover {
        background: var(--error-600);
        transform: scale(1.1);
    }
    
    .new-image-info {
        padding: var(--space-sm);
        background: var(--admin-secondary-50);
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-align: center;
    }
    
    .price-input-group {
        position: relative;
    }
    
    .price-currency {
        position: absolute;
        left: var(--space-md);
        top: 50%;
        transform: translateY(-50%);
        color: var(--admin-secondary-500);
        font-weight: 600;
    }
    
    .price-input {
        padding-left: calc(var(--space-md) + 20px);
    }
    
    .stock-input-group {
        position: relative;
    }
    
    .stock-unit {
        position: absolute;
        right: var(--space-md);
        top: 50%;
        transform: translateY(-50%);
        color: var(--admin-secondary-500);
        font-size: 0.875rem;
    }
    
    .stock-input {
        padding-right: calc(var(--space-md) + 40px);
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
        .edit-product-container {
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
        
        .current-images-grid,
        .new-images-preview {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
    }
</style>
@endpush

@section('content')
<div class="edit-product-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-edit"></i>
                {{ __('Edit Product') }}
            </h1>
            <p class="form-subtitle">{{ __('Update product information and settings') }}</p>
        </div>
        
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')
            
            <div class="form-body">
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
                                    {{ __('Product Name') }}
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="nameChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    class="form-input @error('name') error @enderror"
                                    value="{{ old('name', $product->name) }}"
                                    placeholder="{{ __('Enter product name') }}"
                                    required
                                    oninput="trackChanges('name')"
                                    data-original="{{ $product->name }}"
                                >
                                @error('name')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Choose a clear, descriptive name for your product') }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="category_id" class="form-label">
                                    {{ __('Category') }}
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="category_idChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <select 
                                    id="category_id" 
                                    name="category_id" 
                                    class="form-select @error('category_id') error @enderror"
                                    required
                                    onchange="trackChanges('category_id')"
                                    data-original="{{ $product->category_id }}"
                                >
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Select the category this product belongs to') }}</div>
                            </div>
                        </div>
                        
                        <div class="form-row single">
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    {{ __('Description') }}
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="descriptionChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    class="form-textarea @error('description') error @enderror"
                                    placeholder="{{ __('Enter product description') }}"
                                    required
                                    oninput="trackChanges('description')"
                                    data-original="{{ $product->description }}"
                                >{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Provide a detailed description of the product features and benefits') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pricing & Inventory -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-dollar-sign"></i>
                        {{ __('Pricing & Inventory') }}
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="price" class="form-label">
                                    {{ __('Price') }}
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="priceChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <div class="price-input-group">
                                    <span class="price-currency">$</span>
                                    <input 
                                        type="number" 
                                        id="price" 
                                        name="price" 
                                        class="form-input price-input @error('price') error @enderror"
                                        value="{{ old('price', $product->price) }}"
                                        placeholder="0.00"
                                        step="0.01"
                                        min="0"
                                        required
                                        oninput="trackChanges('price')"
                                        data-original="{{ $product->price }}"
                                    >
                                </div>
                                @error('price')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Set the selling price for this product') }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="stock" class="form-label">
                                    {{ __('Stock Quantity') }}
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="stockChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <div class="stock-input-group">
                                    <input 
                                        type="number" 
                                        id="stock" 
                                        name="stock" 
                                        class="form-input stock-input @error('stock') error @enderror"
                                        value="{{ old('stock', $product->stock) }}"
                                        placeholder="0"
                                        min="0"
                                        required
                                        oninput="trackChanges('stock')"
                                        data-original="{{ $product->stock }}"
                                    >
                                    <span class="stock-unit">{{ __('units') }}</span>
                                </div>
                                @error('stock')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">{{ __('Available quantity in inventory') }}</div>
                            </div>
                        </div>
                        
                        <div class="form-row single">
                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input 
                                        type="checkbox" 
                                        id="is_active" 
                                        name="is_active" 
                                        class="checkbox-input"
                                        value="1"
                                        {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                        onchange="trackChanges('is_active')"
                                        data-original="{{ $product->is_active ? '1' : '0' }}"
                                    >
                                    <label for="is_active" class="checkbox-label">
                                        {{ __('Active Product') }}
                                    </label>
                                    <span class="change-indicator" id="is_activeChangeIndicator">{{ __('Modified') }}</span>
                                </div>
                                <div class="form-help">{{ __('Check this box to make the product available for purchase') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product Images -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-images"></i>
                        {{ __('Product Images') }}
                    </h2>
                    
                    @if($product->images && $product->images->count() > 0)
                        <div class="current-images">
                            <div class="current-images-title">{{ __('Current Images') }}</div>
                            <div class="current-images-grid">
                                @foreach($product->images->sortBy('sort_order') as $image)
                                    <div class="current-image-item">
                                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $product->name }}" class="current-image">
                                        <div class="current-image-info">
                                            <div>
                                                @if($image->is_primary)
                                                    <span class="primary-badge">{{ __('Primary') }}</span>
                                                @else
                                                    {{ __('Image') }} {{ $loop->iteration }}
                                                @endif
                                            </div>
                                            <div class="delete-checkbox">
                                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" id="delete_{{ $image->id }}">
                                                <label for="delete_{{ $image->id }}" style="font-size: 0.75rem; color: var(--error-600);">{{ __('Delete') }}</label>
                                            </div>
                                        </div>
                                        @if(!$image->is_primary)
                                            <div class="current-image-controls">
                                                <input type="radio" name="primary_image" value="{{ $image->id }}" id="primary_{{ $image->id }}" style="display: none;">
                                                <label for="primary_{{ $image->id }}" class="image-control-btn" style="background: var(--admin-primary-500); cursor: pointer;" title="{{ __('Set as primary') }}">
                                                    <i class="fas fa-star"></i>
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="form-grid">
                        <div class="form-row single">
                            <div class="form-group">
                                <label for="images" class="form-label">
                                    {{ $product->images && $product->images->count() > 0 ? __('Add More Images') : __('Upload Images') }}
                                    <span class="change-indicator" id="imagesChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <div class="images-upload-area" onclick="document.getElementById('images').click()">
                                    <input 
                                        type="file" 
                                        id="images" 
                                        name="images[]" 
                                        accept="image/*"
                                        multiple
                                        style="display: none;"
                                        onchange="previewNewImages(this); trackChanges('images')"
                                    >
                                    <div class="upload-content">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <div class="upload-text">
                                            {{ $product->images && $product->images->count() > 0 ? __('Click to add more images or drag and drop') : __('Click to upload or drag and drop') }}
                                        </div>
                                        <div class="upload-hint">{{ __('PNG, JPG, GIF up to 2MB each. You can select multiple images.') }}</div>
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
                                <div class="form-help">
                                    {{ $product->images && $product->images->count() > 0 ? __('Upload additional product images.') : __('Upload product images.') }}
                                    {{ __('New images will be added to existing ones.') }}
                                </div>
                                
                                <div id="newImagesPreview" class="new-images-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <div>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('Back to Products') }}
                    </a>
                </div>
                
                <div class="btn-group">
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i>
                        {{ __('View Product') }}
                    </a>
                    
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        {{ __('Reset') }}
                    </button>
                    
                    <button type="submit" class="btn btn-success" id="updateBtn">
                        <i class="fas fa-save"></i>
                        {{ __('Update Product') }}
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
    let selectedNewFiles = [];
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
            updateBtn.classList.add('btn-success');
        } else {
            updateBtn.classList.remove('btn-success');
            updateBtn.classList.add('btn-secondary');
        }
    }
    
    // Preview new images
    function previewNewImages(input) {
        const files = Array.from(input.files);
        selectedNewFiles = files;
        
        if (files.length === 0) {
            document.getElementById('newImagesPreview').innerHTML = '';
            return;
        }
        
        // Validate files
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            
            if (file.size > 2 * 1024 * 1024) {
                alert(`{{ __("File") }} ${file.name} {{ __("is too large. Maximum size is 2MB.") }}`);
                input.value = '';
                selectedNewFiles = [];
                document.getElementById('newImagesPreview').innerHTML = '';
                return;
            }
            
            if (!file.type.match('image.*')) {
                alert(`{{ __("File") }} ${file.name} {{ __("is not an image.") }}`);
                input.value = '';
                selectedNewFiles = [];
                document.getElementById('newImagesPreview').innerHTML = '';
                return;
            }
        }
        
        // Create preview
        const previewContainer = document.getElementById('newImagesPreview');
        previewContainer.innerHTML = '';
        
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'new-image-preview-item';
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="New Image ${index + 1}" class="new-preview-image">
                    <div class="new-image-controls">
                        <button type="button" class="image-control-btn btn-remove-image" 
                                onclick="removeNewImage(${index})" title="{{ __('Remove image') }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="new-image-info">
                        {{ __('New Image') }} ${index + 1}
                    </div>
                `;
                previewContainer.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        });
    }
    
    // Remove new image
    function removeNewImage(index) {
        selectedNewFiles.splice(index, 1);
        
        // Update file input
        const dt = new DataTransfer();
        selectedNewFiles.forEach(file => {
            dt.items.add(file);
        });
        document.getElementById('images').files = dt.files;
        
        // Refresh preview
        previewNewImages(document.getElementById('images'));
        
        if (selectedNewFiles.length === 0) {
            const indicator = document.getElementById('imagesChangeIndicator');
            if (indicator) indicator.style.display = 'none';
            updateFormState();
        }
    }
    
    // Reset form to original values
    function resetForm() {
        if (confirm('{{ __("Are you sure you want to reset the form? All changes will be lost.") }}')) {
            // Reset text fields
            Object.keys(originalValues).forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = originalValues[fieldId] === '1';
                    } else {
                        field.value = originalValues[fieldId] || '';
                    }
                }
            });
            
            // Reset images
            document.getElementById('images').value = '';
            document.getElementById('newImagesPreview').innerHTML = '';
            selectedNewFiles = [];
            
            // Uncheck all delete checkboxes
            document.querySelectorAll('input[name="delete_images[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Uncheck primary image radios
            document.querySelectorAll('input[name="primary_image"]').forEach(radio => {
                radio.checked = false;
            });
            
            // Hide all change indicators
            document.querySelectorAll('.change-indicator').forEach(indicator => {
                indicator.style.display = 'none';
            });
            
            hasChanges = false;
            updateFormState();
        }
    }
    
    // Drag and drop functionality
    const imagesUploadArea = document.querySelector('.images-upload-area');
    
    imagesUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    imagesUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    imagesUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('images').files = files;
            previewNewImages(document.getElementById('images'));
            trackChanges('images');
        }
    });
    
    // Form validation and submission
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const price = document.getElementById('price').value;
        const stock = document.getElementById('stock').value;
        const category = document.getElementById('category_id').value;
        const description = document.getElementById('description').value.trim();
        
        // Basic validation
        if (!name) {
            e.preventDefault();
            alert('{{ __("Please enter a product name") }}');
            document.getElementById('name').focus();
            return;
        }
        
        if (!category) {
            e.preventDefault();
            alert('{{ __("Please select a category") }}');
            document.getElementById('category_id').focus();
            return;
        }
        
        if (!description) {
            e.preventDefault();
            alert('{{ __("Please enter a product description") }}');
            document.getElementById('description').focus();
            return;
        }
        
        if (!price || price <= 0) {
            e.preventDefault();
            alert('{{ __("Please enter a valid price") }}');
            document.getElementById('price').focus();
            return;
        }
        
        if (!stock || stock < 0) {
            e.preventDefault();
            alert('{{ __("Please enter a valid stock quantity") }}');
            document.getElementById('stock').focus();
            return;
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
    
    // Auto-format price input
    document.getElementById('price').addEventListener('blur', function() {
        let value = this.value;
        if (value && !isNaN(value)) {
            this.value = parseFloat(value).toFixed(2);
        }
    });
    
    // Auto-format stock input
    document.getElementById('stock').addEventListener('blur', function() {
        let value = this.value;
        if (value && !isNaN(value)) {
            this.value = Math.floor(parseFloat(value));
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