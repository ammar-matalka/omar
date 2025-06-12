@extends('layouts.admin')

@section('title', __('Product Details'))
@section('page-title', __('Product Details'))

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
        {{ $product->name }}
    </div>
@endsection

@push('styles')
<style>
    .product-show-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .product-header {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .product-hero {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: var(--space-xl);
        padding: var(--space-2xl);
    }
    
    .product-images-section {
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
        background: linear-gradient(135deg, var(--admin-secondary-100), var(--admin-secondary-200));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-secondary-500);
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
    
    .product-info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .product-main-info {
        flex: 1;
    }
    
    .product-name {
        font-size: 2rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        line-height: 1.2;
    }
    
    .product-category-badge {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: var(--space-lg);
        display: inline-block;
        text-decoration: none;
        transition: all var(--transition-fast);
    }
    
    .product-category-badge:hover {
        background: var(--admin-primary-200);
        transform: translateY(-1px);
    }
    
    .product-description {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-xl);
    }
    
    .product-stats {
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
    
    .product-actions {
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
        .product-show-container {
            margin: 0;
        }
        
        .product-hero {
            grid-template-columns: 1fr;
            text-align: center;
        }
        
        .content-grid {
            grid-template-columns: 1fr;
        }
        
        .product-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .action-group {
            justify-content: center;
        }
        
        .product-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .thumbnail-images {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="product-show-container">
    <!-- Product Header -->
    <div class="product-header fade-in">
        <div class="product-hero">
            <div class="product-images-section">
                <div class="main-image-container">
                    @if($product->images && $product->images->count() > 0)
                        @php $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first(); @endphp
                        <img src="{{ Storage::url($primaryImage->image_path) }}" alt="{{ $product->name }}" class="main-image" id="mainImage">
                        <div class="image-badge">{{ __('Primary') }}</div>
                    @elseif($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="main-image" id="mainImage">
                        <div class="image-badge">{{ __('Main') }}</div>
                    @else
                        <div class="no-main-image" id="mainImage">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </div>
                
                @if($product->images && $product->images->count() > 1)
                    <div class="thumbnail-images">
                        @foreach($product->images->sortBy('sort_order') as $image)
                            <img src="{{ Storage::url($image->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="thumbnail-image {{ $loop->first ? 'active' : '' }}"
                                 onclick="changeMainImage('{{ Storage::url($image->image_path) }}', this)">
                        @endforeach
                    </div>
                @endif
            </div>
            
            <div class="product-info">
                <div class="product-main-info">
                    <h1 class="product-name">{{ $product->name }}</h1>
                    
                    @if($product->category)
                        <a href="{{ route('admin.categories.show', $product->category) }}" class="product-category-badge">
                            <i class="fas fa-tag"></i>
                            {{ $product->category->name }}
                        </a>
                    @endif
                    
                    <p class="product-description">{{ $product->description }}</p>
                    
                    <div class="product-stats">
                        <div class="stat-item">
                            <div class="stat-value price">${{ number_format($product->price, 2) }}</div>
                            <div class="stat-label">{{ __('Price') }}</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value stock {{ $product->stock <= 0 ? 'out' : ($product->stock <= 5 ? 'low' : '') }}">
                                {{ $product->stock }}
                            </div>
                            <div class="stat-label">{{ __('Stock') }}</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value">{{ $product->images ? $product->images->count() : ($product->image ? 1 : 0) }}</div>
                            <div class="stat-label">{{ __('Images') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="status-indicators">
                    <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                        <i class="fas fa-{{ $product->is_active ? 'check' : 'times' }}"></i>
                        {{ $product->is_active ? __('Active') : __('Inactive') }}
                    </span>
                    
                    <span class="status-badge stock-status {{ $product->stock <= 0 ? 'out' : ($product->stock <= 5 ? 'low' : '') }}">
                        <i class="fas fa-{{ $product->stock <= 0 ? 'exclamation-triangle' : ($product->stock <= 5 ? 'exclamation' : 'check') }}"></i>
                        @if($product->stock <= 0)
                            {{ __('Out of Stock') }}
                        @elseif($product->stock <= 5)
                            {{ __('Low Stock') }}
                        @else
                            {{ __('In Stock') }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
        
        <div class="product-actions">
            <div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Products') }}
                </a>
            </div>
            
            <div class="action-group">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit Product') }}
                </a>
                
                <button class="btn btn-primary" onclick="duplicateProduct()">
                    <i class="fas fa-copy"></i>
                    {{ __('Duplicate') }}
                </button>
                
                <button class="btn btn-danger" onclick="deleteProduct()">
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
            <!-- Product Images Gallery -->
            @if(($product->images && $product->images->count() > 0) || $product->image)
                <div class="images-gallery fade-in">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-images"></i>
                            {{ __('Product Gallery') }}
                        </h2>
                    </div>
                    
                    <div class="gallery-grid">
                        @if($product->images && $product->images->count() > 0)
                            @foreach($product->images->sortBy('sort_order') as $image)
                                <div class="gallery-item" onclick="openImageModal('{{ Storage::url($image->image_path) }}')">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $product->name }}" class="gallery-image">
                                    @if($image->is_primary)
                                        <div class="primary-indicator">{{ __('Primary') }}</div>
                                    @endif
                                    <div class="gallery-info">
                                        {{ __('Image') }} {{ $loop->iteration }}
                                        @if($image->is_primary) - {{ __('Primary') }} @endif
                                    </div>
                                </div>
                            @endforeach
                        @elseif($product->image)
                            <div class="gallery-item" onclick="openImageModal('{{ Storage::url($product->image) }}')">
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="gallery-image">
                                <div class="primary-indicator">{{ __('Main') }}</div>
                                <div class="gallery-info">{{ __('Product Image') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="images-gallery fade-in">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-images"></i>
                            {{ __('Product Gallery') }}
                        </h2>
                    </div>
                    
                    <div class="empty-gallery">
                        <div class="empty-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <h3>{{ __('No Images Available') }}</h3>
                        <p>{{ __('This product doesn\'t have any images yet.') }}</p>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            {{ __('Add Images') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="sidebar-content">
            <!-- Product Information -->
            <div class="info-card fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Product Information') }}
                    </h3>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">{{ __('Product ID') }}</span>
                        <span class="info-value">#{{ $product->id }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Category') }}</span>
                        <span class="info-value">
                            @if($product->category)
                                <a href="{{ route('admin.categories.show', $product->category) }}" style="color: var(--admin-primary-600); text-decoration: none;">
                                    {{ $product->category->name }}
                                </a>
                            @else
                                <span style="color: var(--admin-secondary-400);">{{ __('No Category') }}</span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Status') }}</span>
                        <span class="info-value">
                            <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $product->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Created') }}</span>
                        <span class="info-value">{{ $product->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Last Updated') }}</span>
                        <span class="info-value">{{ $product->updated_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Total Images') }}</span>
                        <span class="info-value">{{ $product->images ? $product->images->count() : ($product->image ? 1 : 0) }}</span>
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
                    <a href="{{ route('admin.products.edit', $product) }}" class="quick-action">
                        <div class="action-icon edit">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Edit Product') }}</div>
                            <div class="action-subtitle">{{ __('Update product information') }}</div>
                        </div>
                    </a>
                    
                    <a href="#" onclick="duplicateProduct(); return false;" class="quick-action">
                        <div class="action-icon duplicate">
                            <i class="fas fa-copy"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Duplicate Product') }}</div>
                            <div class="action-subtitle">{{ __('Create a copy of this product') }}</div>
                        </div>
                    </a>
                    
                    <a href="#" onclick="deleteProduct(); return false;" class="quick-action">
                        <div class="action-icon delete">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Delete Product') }}</div>
                            <div class="action-subtitle">{{ __('Permanently remove this product') }}</div>
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
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Product') }}</h3>
        <p style="margin-bottom: var(--space-md); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete') }} <strong>"{{ $product->name }}"</strong>?
        </p>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('This action cannot be undone. All product data and images will be permanently removed.') }}
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
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Duplicate Product') }}</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('This will create a copy of') }} <strong>"{{ $product->name }}"</strong> {{ __('with all its information and images.') }}
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
    
    // Delete product functions
    function deleteProduct() {
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    function confirmDelete() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.products.destroy", $product) }}';
        
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
    
    // Duplicate product functions
    function duplicateProduct() {
        document.getElementById('duplicateModal').style.display = 'flex';
    }
    
    function closeDuplicateModal() {
        document.getElementById('duplicateModal').style.display = 'none';
    }
    
    function confirmDuplicate() {
        // Create a form to submit to create route with product data
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("admin.products.create") }}';
        
        const duplicateInput = document.createElement('input');
        duplicateInput.type = 'hidden';
        duplicateInput.name = 'duplicate';
        duplicateInput.value = '{{ $product->id }}';
        
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