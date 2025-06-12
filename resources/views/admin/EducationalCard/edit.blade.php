@extends('layouts.admin')

@section('title', __('Edit Educational Card'))
@section('page-title', __('Edit Educational Card'))

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
        {{ __('Edit') }} - {{ $card->title }}
    </div>
@endsection

@push('styles')
<style>
    .edit-card-container {
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
        margin-bottom: var(--space-md);
    }
    
    .current-image-item {
        position: relative;
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .current-image-item.primary {
        border-color: var(--admin-primary-500);
        border-width: 2px;
    }
    
    .current-image-img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    
    .image-actions {
        position: absolute;
        top: var(--space-xs);
        right: var(--space-xs);
        display: flex;
        gap: var(--space-xs);
    }
    
    .image-action-btn {
        width: 24px;
        height: 24px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        color: white;
    }
    
    .set-primary {
        background: var(--admin-primary-500);
    }
    
    .set-primary:hover {
        background: var(--admin-primary-600);
    }
    
    .delete-image {
        background: var(--error-500);
    }
    
    .delete-image:hover {
        background: var(--error-600);
    }
    
    .primary-badge {
        position: absolute;
        bottom: var(--space-xs);
        left: var(--space-xs);
        background: var(--admin-primary-500);
        color: white;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
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
    
    .new-images-preview {
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
        .edit-card-container {
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
        
        .current-images-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="edit-card-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-edit"></i>
                {{ __('Edit Educational Card') }}
            </h1>
            <p class="form-subtitle">{{ __('Update card information and settings') }}</p>
        </div>
        
        <form action="{{ route('admin.educational-cards.update', $card) }}" method="POST" enctype="multipart/form-data" id="cardForm">
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
                            {{ __('Platform, Grade & Subject') }}
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
                                    data-original="{{ $card->subject->grade->platform_id }}"
                                >
                                    <option value="">{{ __('Select Platform') }}</option>
                                    @foreach($platforms as $platform)
                                        <option value="{{ $platform->id }}" {{ old('platform_id', $card->subject->grade->platform_id) == $platform->id ? 'selected' : '' }}>
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
                                    onchange="loadSubjects(this.value); trackChanges('grade_id')"
                                    data-original="{{ $card->subject->grade_id }}"
                                >
                                    <option value="">{{ __('Select Grade') }}</option>
                                    @foreach($card->subject->grade->platform->grades as $grade)
                                        <option value="{{ $grade->id }}" {{ old('grade_id', $card->subject->grade_id) == $grade->id ? 'selected' : '' }}>
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
                            
                            <div class="form-group">
                                <label for="subject_id" class="form-label">
                                    {{ __('Subject') }}
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="subject_idChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <select 
                                    id="subject_id" 
                                    name="subject_id" 
                                    class="form-select @error('subject_id') error @enderror"
                                    required
                                    onchange="trackChanges('subject_id')"
                                    data-original="{{ $card->subject_id }}"
                                >
                                    <option value="">{{ __('Select Subject') }}</option>
                                    @foreach($card->subject->grade->subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id', $card->subject_id) == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
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
                                    <span class="change-indicator" id="titleChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="title" 
                                    name="title" 
                                    class="form-input @error('title') error @enderror"
                                    value="{{ old('title', $card->title) }}"
                                    placeholder="{{ __('Enter card title') }}"
                                    required
                                    oninput="trackChanges('title')"
                                    data-original="{{ $card->title }}"
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
                                    <span class="change-indicator" id="title_arChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="title_ar" 
                                    name="title_ar" 
                                    class="form-input @error('title_ar') error @enderror"
                                    value="{{ old('title_ar', $card->title_ar) }}"
                                    placeholder="{{ __('Enter Arabic title (optional)') }}"
                                    dir="rtl"
                                    oninput="trackChanges('title_ar')"
                                    data-original="{{ $card->title_ar }}"
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
                                    <span class="change-indicator" id="descriptionChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    class="form-textarea @error('description') error @enderror"
                                    placeholder="{{ __('Enter card description') }}"
                                    required
                                    oninput="trackChanges('description')"
                                    data-original="{{ $card->description }}"
                                >{{ old('description', $card->description) }}</textarea>
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
                                    <span class="change-indicator" id="description_arChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <textarea 
                                    id="description_ar" 
                                    name="description_ar" 
                                    class="form-textarea @error('description_ar') error @enderror"
                                    placeholder="{{ __('Enter Arabic description (optional)') }}"
                                    dir="rtl"
                                    oninput="trackChanges('description_ar')"
                                    data-original="{{ $card->description_ar }}"
                                >{{ old('description_ar', $card->description_ar) }}</textarea>
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
                                    <span class="change-indicator" id="priceChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="price" 
                                    name="price" 
                                    class="form-input @error('price') error @enderror"
                                    value="{{ old('price', $card->price) }}"
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                    required
                                    oninput="trackChanges('price')"
                                    data-original="{{ $card->price }}"
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
                                    <span class="change-indicator" id="stockChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="stock" 
                                    name="stock" 
                                    class="form-input @error('stock') error @enderror"
                                    value="{{ old('stock', $card->stock) }}"
                                    placeholder="0"
                                    min="0"
                                    required
                                    oninput="trackChanges('stock')"
                                    data-original="{{ $card->stock }}"
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
                                    <span class="change-indicator" id="difficulty_levelChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <select 
                                    id="difficulty_level" 
                                    name="difficulty_level" 
                                    class="form-select @error('difficulty_level') error @enderror"
                                    required
                                    onchange="trackChanges('difficulty_level')"
                                    data-original="{{ $card->difficulty_level }}"
                                >
                                    <option value="">{{ __('Select Difficulty') }}</option>
                                    <option value="easy" {{ old('difficulty_level', $card->difficulty_level) == 'easy' ? 'selected' : '' }}>{{ __('Easy') }}</option>
                                    <option value="medium" {{ old('difficulty_level', $card->difficulty_level) == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                    <option value="hard" {{ old('difficulty_level', $card->difficulty_level) == 'hard' ? 'selected' : '' }}>{{ __('Hard') }}</option>
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
                                    <span class="change-indicator" id="card_typeChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <select 
                                    id="card_type" 
                                    name="card_type" 
                                    class="form-select @error('card_type') error @enderror"
                                    required
                                    onchange="trackChanges('card_type')"
                                    data-original="{{ $card->card_type }}"
                                >
                                    <option value="">{{ __('Select Type') }}</option>
                                    <option value="digital" {{ old('card_type', $card->card_type) == 'digital' ? 'selected' : '' }}>{{ __('Digital') }}</option>
                                    <option value="physical" {{ old('card_type', $card->card_type) == 'physical' ? 'selected' : '' }}>{{ __('Physical') }}</option>
                                    <option value="both" {{ old('card_type', $card->card_type) == 'both' ? 'selected' : '' }}>{{ __('Both') }}</option>
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
                                        {{ old('is_active', $card->is_active) ? 'checked' : '' }}
                                        onchange="trackChanges('is_active')"
                                        data-original="{{ $card->is_active ? '1' : '0' }}"
                                    >
                                    <label for="is_active" class="checkbox-label">
                                        {{ __('Active Card') }}
                                    </label>
                                    <span class="change-indicator" id="is_activeChangeIndicator">{{ __('Modified') }}</span>
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
                    
                    @if($card->images && $card->images->count() > 0)
                        <div class="current-images">
                            <div class="current-images-title">{{ __('Current Images') }}</div>
                            <div class="current-images-grid">
                                @foreach($card->images->sortBy('sort_order') as $image)
                                    <div class="current-image-item {{ $image->is_primary ? 'primary' : '' }}" data-image-id="{{ $image->id }}">
                                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $card->title }}" class="current-image-img">
                                        <div class="image-actions">
                                            @if(!$image->is_primary)
                                                <button type="button" class="image-action-btn set-primary" onclick="setPrimaryImage('{{ $image->id }}')" title="{{ __('Set as Primary') }}">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="image-action-btn delete-image" onclick="deleteCurrentImage('{{ $image->id }}')" title="{{ __('Delete Image') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        @if($image->is_primary)
                                            <div class="primary-badge">{{ __('Primary') }}</div>
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
                                    {{ $card->images && $card->images->count() > 0 ? __('Add More Images') : __('Upload Images') }}
                                    <span class="change-indicator" id="imagesChangeIndicator">{{ __('Modified') }}</span>
                                </label>
                                <div class="image-upload-area" onclick="document.getElementById('images').click()">
                                    <input 
                                        type="file" 
                                        id="images" 
                                        name="images[]" 
                                        accept="image/*"
                                        multiple
                                        style="display: none;"
                                        onchange="previewImages(this); trackChanges('images')"
                                    >
                                    <div class="upload-content">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <div class="upload-text">
                                            {{ $card->images && $card->images->count() > 0 ? __('Click to add more images or drag and drop') : __('Click to upload or drag and drop') }}
                                        </div>
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
                                <div class="form-help">
                                    {{ $card->images && $card->images->count() > 0 ? __('Upload additional images for the card.') : __('Upload multiple images for the card. First image will be the primary image.') }}
                                </div>
                                
                                <div id="newImagesPreview" class="new-images-preview">
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
                    <a href="{{ route('admin.educational-cards.show', $card) }}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i>
                        {{ __('View Card') }}
                    </a>
                    
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        {{ __('Reset') }}
                    </button>
                    
                    <button type="submit" class="btn btn-success" id="updateBtn">
                        <i class="fas fa-save"></i>
                        {{ __('Update Card') }}
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
    let selectedFiles = [];
    let deletedImages = [];
    let primaryImageId = null;
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
        hasChanges = document.querySelector('.change-indicator[style*="inline-block"]') !== null || deletedImages.length > 0 || primaryImageId !== null;
        
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
        const subjectSelect = document.getElementById('subject_id');
        
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
        
        if (gradeId) {
            fetch(`/admin/grades/${gradeId}/subjects`)
                .then(response => response.json())
                .then(subjects => {
                    subjectSelect.innerHTML = '<option value="">{{ __("Select Subject") }}</option>';
                    
                    subjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        if (subject.id == originalValues['subject_id']) {
                            option.selected = true;
                        }
                        subjectSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading subjects:', error));
        }
    }
    
    // Set primary image
    function setPrimaryImage(imageId) {
        // Remove primary status from all current images
        document.querySelectorAll('.current-image-item').forEach(item => {
            item.classList.remove('primary');
            const badge = item.querySelector('.primary-badge');
            if (badge) badge.remove();
            
            // Show set primary button for non-primary images
            const setPrimaryBtn = item.querySelector('.set-primary');
            if (setPrimaryBtn) setPrimaryBtn.style.display = 'flex';
        });
        
        // Set new primary image
        const imageItem = document.querySelector(`[data-image-id="${imageId}"]`);
        if (imageItem) {
            imageItem.classList.add('primary');
            
            // Add primary badge
            const badge = document.createElement('div');
            badge.className = 'primary-badge';
            badge.textContent = '{{ __("Primary") }}';
            imageItem.appendChild(badge);
            
            // Hide set primary button
            const setPrimaryBtn = imageItem.querySelector('.set-primary');
            if (setPrimaryBtn) setPrimaryBtn.style.display = 'none';
        }
        
        primaryImageId = imageId;
        updateFormState();
    }
    
    // Delete current image
    function deleteCurrentImage(imageId) {
        if (confirm('{{ __("Are you sure you want to delete this image?") }}')) {
            const imageItem = document.querySelector(`[data-image-id="${imageId}"]`);
            if (imageItem) {
                imageItem.style.display = 'none';
                deletedImages.push(imageId);
                updateFormState();
            }
        }
    }
    
    // Preview new uploaded images
    function previewImages(input) {
        if (input.files && input.files.length > 0) {
            selectedFiles = Array.from(input.files);
            const previewContainer = document.getElementById('newImagesPreview');
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
                        <button type="button" class="remove-image" onclick="removeNewImage(${index})">
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
    
    // Remove new image from preview
    function removeNewImage(index) {
        selectedFiles.splice(index, 1);
        
        // Update file input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        document.getElementById('images').files = dt.files;
        
        // Re-render previews
        if (selectedFiles.length > 0) {
            previewImages(document.getElementById('images'));
        } else {
            document.getElementById('newImagesPreview').style.display = 'none';
        }
        
        trackChanges('images');
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
            
            // Reset images
            document.getElementById('images').value = '';
            document.getElementById('newImagesPreview').style.display = 'none';
            selectedFiles = [];
            deletedImages = [];
            primaryImageId = null;
            
            // Show all current images
            document.querySelectorAll('.current-image-item').forEach(item => {
                item.style.display = 'block';
            });
            
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
            document.getElementById('images').files = files;
            previewImages(document.getElementById('images'));
            trackChanges('images');
        }
    });
    
    // Form submission
    document.getElementById('cardForm').addEventListener('submit', function(e) {
        // Add deleted images as hidden inputs
        deletedImages.forEach(imageId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = imageId;
            this.appendChild(input);
        });
        
        // Add primary image as hidden input
        if (primaryImageId) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'primary_image';
            input.value = primaryImageId;
            this.appendChild(input);
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