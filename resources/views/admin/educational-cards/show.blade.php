@extends('layouts.admin')

@section('title', __('Educational Card Details'))
@section('page-title', __('Educational Card Details'))

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
        {{ $educationalCard->title }}
    </div>
@endsection

@push('styles')
<style>
    .card-show-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .card-header {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .card-hero {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: var(--space-xl);
        padding: var(--space-2xl);
    }
    
    .card-images-section {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
    }
    
    .main-image-container {
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
        box-shadow: var(--shadow-md);
    }
    
    .main-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
    
    .no-main-image {
        width: 100%;
        height: 300px;
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
    }
    
    .image-badge {
        position: absolute;
        top: var(--space-sm);
        left: var(--space-sm);
        background: var(--success-500);
        color: white;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .difficulty-badge {
        position: absolute;
        top: var(--space-sm);
        right: var(--space-sm);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .difficulty-easy {
        background: var(--success-500);
        color: white;
    }
    
    .difficulty-medium {
        background: var(--warning-500);
        color: white;
    }
    
    .difficulty-hard {
        background: var(--error-500);
        color: white;
    }
    
    .thumbnail-images {
        display: flex;
        gap: var(--space-sm);
        overflow-x: auto;
        padding: var(--space-xs);
    }
    
    .thumbnail-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: var(--radius-md);
        border: 2px solid transparent;
        cursor: pointer;
        transition: all var(--transition-fast);
        flex-shrink: 0;
    }
    
    .thumbnail-image:hover {
        border-color: var(--admin-primary-300);
        transform: scale(1.05);
    }
    
    .thumbnail-image.active {
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    
    .card-info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .card-main-info {
        flex: 1;
    }
    
    .card-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        line-height: 1.2;
    }
    
    .card-title-ar {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-md);
        direction: rtl;
        font-style: italic;
    }
    
    .card-hierarchy {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .hierarchy-item {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all var(--transition-fast);
    }
    
    .hierarchy-item:hover {
        background: var(--admin-primary-200);
        transform: translateY(-1px);
    }
    
    .hierarchy-separator {
        color: var(--admin-secondary-400);
        font-size: 1.25rem;
    }
    
    .card-description {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-md);
    }
    
    .card-description-ar {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-xl);
        direction: rtl;
        font-style: italic;
        border-top: 1px solid var(--admin-secondary-200);
        padding-top: var(--space-md);
    }
    
    .card-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-xl);
    }
    
    .stat-item {
        text-align: center;
        padding: var(--space-lg);
        background: var(--admin-secondary-50);
        border-radius: var(--radius-lg);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: var(--space-xs);
    }
    
    .stat-value.price {
        color: var(--admin-primary-600);
    }
    
    .stat-value.stock {
        color: var(--success-600);
    }
    
    .stat-value.stock.low {
        color: var(--warning-600);
    }
    
    .stat-value.stock.out {
        color: var(--error-600);
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }
    
    .status-indicators {
        display: flex;
        gap: var(--space-md);
        align-items: center;
        flex-wrap: wrap;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        gap: var(--space-sm);
    }
    
    .status-active {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .status-inactive {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .type-badge {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        border: 1px solid var(--admin-primary-200);
    }
    
    .stock-status {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .stock-status.low {
        background: var(--warning-100);
        color: var(--warning-700);
        border: 1px solid var(--warning-200);
    }
    
    .stock-status.out {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .card-actions {
        background: var(--admin-secondary-50);
        padding: var(--space-xl);
        border-top: 1px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-md);
    }
    
    .action-group {
        display: flex;
        gap: var(--space-md);
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--space-xl);
    }
    
    .main-content {
        display: flex;
        flex-direction: column;
        gap: var(--space-xl);
    }
    
    .sidebar-content {
        display: flex;
        flex-direction: column;
        gap: var(--space-xl);
    }
    
    .info-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .section-header {
        background: var(--admin-secondary-50);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .info-list {
        padding: var(--space-lg);
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-md) 0;
        border-bottom: 1px solid var(--admin-secondary-100);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 500;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
    }
    
    .info-value {
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
        text-align: right;
    }
    
    .images-gallery {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: var(--space-lg);
        padding: var(--space-lg);
    }
    
    .gallery-item {
        position: relative;
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
        transition: all var(--transition-fast);
        cursor: pointer;
    }
    
    .gallery-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .gallery-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    
    .gallery-info {
        padding: var(--space-sm);
        background: white;
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-align: center;
    }
    
    .primary-indicator {
        position: absolute;
        top: var(--space-xs);
        left: var(--space-xs);
        background: var(--success-500);
        color: white;
        padding: 2px 6px;
        border-radius: var(--radius-sm);
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .quick-actions {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .quick-actions-list {
        padding: var(--space-lg);
    }
    
    .quick-action {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-md);
        text-decoration: none;
        color: var(--admin-secondary-700);
        border-radius: var(--radius-md);
        transition: all var(--transition-fast);
        margin-bottom: var(--space-xs);
    }
    
    .quick-action:hover {
        background: var(--admin-secondary-50);
        color: var(--admin-secondary-900);
        transform: translateX(4px);
    }
    
    .quick-action:last-child {
        margin-bottom: 0;
    }
    
    .action-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        flex-shrink: 0;
    }
    
    .action-icon.edit {
        background: var(--warning-100);
        color: var(--warning-600);
    }
    
    .action-icon.duplicate {
        background: var(--admin-primary-100);
        color: var(--admin-primary-600);
    }
    
    .action-icon.delete {
        background: var(--error-100);
        color: var(--error-600);
    }
    
    .action-text {
        flex: 1;
    }
    
    .action-title {
        font-weight: 500;
        margin-bottom: 2px;
    }
    
    .action-subtitle {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .empty-gallery {
        text-align: center;
        padding: var(--space-2xl);
        color: var(--admin-secondary-500);
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: var(--space-md);
        opacity: 0.5;
    }
    
    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    
    .modal-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .modal-close {
        position: absolute;
        top: var(--space-md);
        right: var(--space-md);
        width: 40px;
        height: 40px;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition-fast);
    }
    
    .modal-close:hover {
        background: rgba(0, 0, 0, 0.8);
        transform: scale(1.1);
    }
    
    @media (max-width: 768px) {
        .card-show-container {
            margin: 0;
        }
        
        .card-hero {
            grid-template-columns: 1fr;
            text-align: center;
        }
        
        .content-grid {
            grid-template-columns: 1fr;
        }
        
        .card-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .action-group {
            justify-content: center;
        }
        
        .card-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .thumbnail-images {
            justify-content: center;
        }
        
        .status-indicators {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="card-show-container">
    <!-- Card Header -->
    <div class="card-header fade-in">
        <div class="card-hero">
            <div class="card-images-section">
                <div class="main-image-container">
                    @if($educationalCard->images && $educationalCard->images->count() > 0)
                        @php $primaryImage = $educationalCard->images->where('is_primary', true)->first() ?? $educationalCard->images->first(); @endphp
                        <img src="{{ Storage::url($primaryImage->image_path) }}" alt="{{ $educationalCard->title }}" class="main-image" id="mainImage">
                        <div class="image-badge">{{ __('Primary') }}</div>
                    @elseif($educationalCard->image)
                        <img src="{{ Storage::url($educationalCard->image) }}" alt="{{ $educationalCard->title }}" class="main-image" id="mainImage">
                        <div class="image-badge">{{ __('Main') }}</div>
                    @else
                        <div class="no-main-image" id="mainImage">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    @endif
                    
                    <div class="difficulty-badge difficulty-{{ $educationalCard->difficulty_level }}">
                        {{ ucfirst($educationalCard->difficulty_level) }}
                    </div>
                </div>
                
                @if($educationalCard->images && $educationalCard->images->count() > 1)
                    <div class="thumbnail-images">
                        @foreach($educationalCard->images->sortBy('sort_order') as $image)
                            <img src="{{ Storage::url($image->image_path) }}" 
                                 alt="{{ $educationalCard->title }}" 
                                 class="thumbnail-image {{ $loop->first ? 'active' : '' }}"
                                 onclick="changeMainImage('{{ Storage::url($image->image_path) }}', this)">
                        @endforeach
                    </div>
                @endif
            </div>
            
            <div class="card-info">
                <div class="card-main-info">
                    <h1 class="card-title">{{ $educationalCard->title }}</h1>
                    
                    @if($educationalCard->title_ar)
                        <div class="card-title-ar">{{ $educationalCard->title_ar }}</div>
                    @endif
                    
                    <div class="card-hierarchy">
                        <a href="{{ route('admin.platforms.show', $educationalCard->subject->grade->platform) }}" class="hierarchy-item">
                            <i class="fas fa-desktop"></i>
                            {{ $educationalCard->subject->grade->platform->name }}
                        </a>
                        
                        <span class="hierarchy-separator">›</span>
                        
                        <a href="{{ route('admin.grades.show', $educationalCard->subject->grade) }}" class="hierarchy-item">
                            <i class="fas fa-graduation-cap"></i>
                            {{ $educationalCard->subject->grade->name }}
                        </a>
                        
                        <span class="hierarchy-separator">›</span>
                        
                        <a href="{{ route('admin.subjects.show', $educationalCard->subject) }}" class="hierarchy-item">
                            <i class="fas fa-book"></i>
                            {{ $educationalCard->subject->name }}
                        </a>
                    </div>
                    
                    <div class="card-description">{{ $educationalCard->description }}</div>
                    
                    @if($educationalCard->description_ar)
                        <div class="card-description-ar">{{ $educationalCard->description_ar }}</div>
                    @endif
                    
                    <div class="card-stats">
                        <div class="stat-item">
                            <div class="stat-value price">${{ number_format($educationalCard->price, 2) }}</div>
                            <div class="stat-label">{{ __('Price') }}</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value stock {{ $educationalCard->stock <= 0 ? 'out' : ($educationalCard->stock <= 5 ? 'low' : '') }}">
                                {{ $educationalCard->stock }}
                            </div>
                            <div class="stat-label">{{ __('Stock') }}</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value">{{ $educationalCard->images ? $educationalCard->images->count() : ($educationalCard->image ? 1 : 0) }}</div>
                            <div class="stat-label">{{ __('Images') }}</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value">{{ ucfirst($educationalCard->card_type) }}</div>
                            <div class="stat-label">{{ __('Type') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="status-indicators">
                    <span class="status-badge {{ $educationalCard->is_active ? 'status-active' : 'status-inactive' }}">
                        <i class="fas fa-{{ $educationalCard->is_active ? 'check' : 'times' }}"></i>
                        {{ $educationalCard->is_active ? __('Active') : __('Inactive') }}
                    </span>
                    
                    <span class="status-badge type-badge">
                        <i class="fas fa-{{ $educationalCard->card_type == 'digital' ? 'laptop' : ($educationalCard->card_type == 'physical' ? 'box' : 'cubes') }}"></i>
                        {{ ucfirst($educationalCard->card_type) }}
                    </span>
                    
                    <span class="status-badge stock-status {{ $educationalCard->stock <= 0 ? 'out' : ($educationalCard->stock <= 5 ? 'low' : '') }}">
                        <i class="fas fa-{{ $educationalCard->stock <= 0 ? 'exclamation-triangle' : ($educationalCard->stock <= 5 ? 'exclamation' : 'check') }}"></i>
                        @if($educationalCard->stock <= 0)
                            {{ __('Out of Stock') }}
                        @elseif($educationalCard->stock <= 5)
                            {{ __('Low Stock') }}
                        @else
                            {{ __('In Stock') }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card-actions">
            <div>
                <a href="{{ route('admin.educational-cards.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Educational Cards') }}
                </a>
            </div>
            
            <div class="action-group">
                <a href="{{ route('admin.educational-cards.edit', $educationalCard) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit Card') }}
                </a>
                
                <button class="btn btn-primary" onclick="duplicateCard()">
                    <i class="fas fa-copy"></i>
                    {{ __('Duplicate') }}
                </button>
                
                <button class="btn btn-danger" onclick="deleteCard()">
                    <i class="fas fa-trash"></i>
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>
    
    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Card Images Gallery -->
            @if(($educationalCard->images && $educationalCard->images->count() > 0) || $educationalCard->image)
                <div class="images-gallery fade-in">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-images"></i>
                            {{ __('Card Gallery') }}
                        </h2>
                    </div>
                    
                    <div class="gallery-grid">
                        @if($educationalCard->images && $educationalCard->images->count() > 0)
                            @foreach($educationalCard->images->sortBy('sort_order') as $image)
                                <div class="gallery-item" onclick="openImageModal('{{ Storage::url($image->image_path) }}')">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $educationalCard->title }}" class="gallery-image">
                                    @if($image->is_primary)
                                        <div class="primary-indicator">{{ __('Primary') }}</div>
                                    @endif
                                    <div class="gallery-info">
                                        {{ __('Image') }} {{ $loop->iteration }}
                                        @if($image->is_primary) - {{ __('Primary') }} @endif
                                    </div>
                                </div>
                            @endforeach
                        @elseif($educationalCard->image)
                            <div class="gallery-item" onclick="openImageModal('{{ Storage::url($educationalCard->image) }}')">
                                <img src="{{ Storage::url($educationalCard->image) }}" alt="{{ $educationalCard->title }}" class="gallery-image">
                                <div class="primary-indicator">{{ __('Main') }}</div>
                                <div class="gallery-info">{{ __('Card Image') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="images-gallery fade-in">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-images"></i>
                            {{ __('Card Gallery') }}
                        </h2>
                    </div>
                    
                    <div class="empty-gallery">
                        <div class="empty-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <h3>{{ __('No Images Available') }}</h3>
                        <p>{{ __('This educational card doesn\'t have any images yet.') }}</p>
                        <a href="{{ route('admin.educational-cards.edit', $educationalCard) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            {{ __('Add Images') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="sidebar-content">
            <!-- Card Information -->
            <div class="info-card fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Card Information') }}
                    </h3>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">{{ __('Card ID') }}</span>
                        <span class="info-value">#{{ $educationalCard->id }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Platform') }}</span>
                        <span class="info-value">
                            <a href="{{ route('admin.platforms.show', $educationalCard->subject->grade->platform) }}" style="color: var(--admin-primary-600); text-decoration: none;">
                                {{ $educationalCard->subject->grade->platform->name }}
                            </a>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Grade') }}</span>
                        <span class="info-value">
                            <a href="{{ route('admin.grades.show', $educationalCard->subject->grade) }}" style="color: var(--admin-primary-600); text-decoration: none;">
                                {{ $educationalCard->subject->grade->name }}
                            </a>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Subject') }}</span>
                        <span class="info-value">
                            <a href="{{ route('admin.subjects.show', $educationalCard->subject) }}" style="color: var(--admin-primary-600); text-decoration: none;">
                                {{ $educationalCard->subject->name }}
                            </a>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Card Type') }}</span>
                        <span class="info-value">
                            <span class="badge badge-info">{{ ucfirst($educationalCard->card_type) }}</span>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Difficulty') }}</span>
                        <span class="info-value">
                            <span class="badge {{ $educationalCard->difficulty_level == 'easy' ? 'badge-success' : ($educationalCard->difficulty_level == 'medium' ? 'badge-warning' : 'badge-danger') }}">
                                {{ ucfirst($educationalCard->difficulty_level) }}
                            </span>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Status') }}</span>
                        <span class="info-value">
                            <span class="badge {{ $educationalCard->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $educationalCard->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Created') }}</span>
                        <span class="info-value">{{ $educationalCard->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Last Updated') }}</span>
                        <span class="info-value">{{ $educationalCard->updated_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Total Images') }}</span>
                        <span class="info-value">{{ $educationalCard->images ? $educationalCard->images->count() : ($educationalCard->image ? 1 : 0) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-bolt"></i>
                        {{ __('Quick Actions') }}
                    </h3>
                </div>
                
                <div class="quick-actions-list">
                    <a href="{{ route('admin.educational-cards.edit', $educationalCard) }}" class="quick-action">
                        <div class="action-icon edit">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Edit Card') }}</div>
                            <div class="action-subtitle">{{ __('Update card information') }}</div>
                        </div>
                    </a>
                    
                    <a href="#" onclick="duplicateCard(); return false;" class="quick-action">
                        <div class="action-icon duplicate">
                            <i class="fas fa-copy"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Duplicate Card') }}</div>
                            <div class="action-subtitle">{{ __('Create a copy of this card') }}</div>
                        </div>
                    </a>
                    
                    <a href="#" onclick="deleteCard(); return false;" class="quick-action">
                        <div class="action-icon delete">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Delete Card') }}</div>
                            <div class="action-subtitle">{{ __('Permanently remove this card') }}</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <img id="modalImage" class="modal-image" src="" alt="">
        <button class="modal-close" onclick="closeImageModal()">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-xl); max-width: 400px; width: 90%; text-align: center;">
        <div style="color: var(--error-500); font-size: 3rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Educational Card') }}</h3>
        <p style="margin-bottom: var(--space-md); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete') }} <strong>"{{ $educationalCard->title }}"</strong>?
        </p>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('This action cannot be undone. All card data and images will be permanently removed.') }}
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center;">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">{{ __('Cancel') }}</button>
            <button onclick="confirmDelete()" class="btn btn-danger">{{ __('Delete') }}</button>
        </div>
    </div>
</div>

<!-- Duplicate Confirmation Modal -->
<div id="duplicateModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-xl); max-width: 400px; width: 90%; text-align: center;">
        <div style="color: var(--admin-primary-500); font-size: 3rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-copy"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Duplicate Educational Card') }}</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('This will create a copy of') }} <strong>"{{ $educationalCard->title }}"</strong> {{ __('with all its information and images.') }}
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center;">
            <button onclick="closeDuplicateModal()" class="btn btn-secondary">{{ __('Cancel') }}</button>
            <button onclick="confirmDuplicate()" class="btn btn-primary">{{ __('Duplicate') }}</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Change main image
    function changeMainImage(imageSrc, thumbnail) {
        document.getElementById('mainImage').src = imageSrc;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail-image').forEach(img => {
            img.classList.remove('active');
        });
        thumbnail.classList.add('active');
    }
    
    // Image modal functions
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Delete card functions
    function deleteCard() {
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    function confirmDelete() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.educational-cards.destroy", $educationalCard) }}';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
    
    // Duplicate card functions
    function duplicateCard() {
        document.getElementById('duplicateModal').style.display = 'flex';
    }
    
    function closeDuplicateModal() {
        document.getElementById('duplicateModal').style.display = 'none';
    }
    
    function confirmDuplicate() {
        // Create a form to submit to create route with card data
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("admin.educational-cards.create") }}';
        
        const duplicateInput = document.createElement('input');
        duplicateInput.type = 'hidden';
        duplicateInput.name = 'duplicate';
        duplicateInput.value = '{{ $educationalCard->id }}';
        
        form.appendChild(duplicateInput);
        document.body.appendChild(form);
        form.submit();
    }
    
    // Close modals when clicking outside
    ['deleteModal', 'duplicateModal'].forEach(modalId => {
        document.getElementById(modalId).addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
            closeDeleteModal();
            closeDuplicateModal();
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