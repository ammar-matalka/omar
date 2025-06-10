@extends('layouts.app')

@section('title', ($card->title_ar && app()->getLocale() === 'ar' ? $card->title_ar : $card->title) . ' - ' . __('Educational Cards') . ' - ' . config('app.name'))

@push('styles')
<style>
    .card-detail-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .card-detail-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,0 1000,0 1000,80 0,100"/></svg>');
        background-size: cover;
        background-position: bottom;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-lg);
        font-size: 0.875rem;
        opacity: 0.9;
        flex-wrap: wrap;
    }
    
    .breadcrumb-link {
        color: white;
        text-decoration: none;
        transition: opacity var(--transition-fast);
    }
    
    .breadcrumb-link:hover {
        opacity: 0.8;
        text-decoration: underline;
    }
    
    .breadcrumb-separator {
        opacity: 0.6;
    }
    
    .card-detail-container {
        padding: var(--space-2xl) 0;
        background: var(--background);
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        background: var(--surface);
        color: var(--primary-600);
        border: 2px solid var(--primary-200);
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-weight: 600;
        transition: all var(--transition-fast);
        margin-bottom: var(--space-xl);
    }
    
    .back-button:hover {
        background: var(--primary-50);
        border-color: var(--primary-300);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .card-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-2xl);
        margin-bottom: var(--space-2xl);
    }
    
    .card-gallery {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    
    .main-image {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        position: relative;
        overflow: hidden;
        cursor: zoom-in;
    }
    
    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-slow);
    }
    
    .main-image:hover img {
        transform: scale(1.05);
    }
    
    .image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: var(--primary-500);
        opacity: 0.3;
    }
    
    .thumbnail-gallery {
        display: flex;
        gap: var(--space-sm);
        padding: var(--space-md);
        overflow-x: auto;
    }
    
    .thumbnail {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-md);
        border: 2px solid transparent;
        overflow: hidden;
        cursor: pointer;
        transition: all var(--transition-fast);
        flex-shrink: 0;
    }
    
    .thumbnail.active {
        border-color: var(--primary-500);
        box-shadow: var(--shadow-md);
    }
    
    .thumbnail:hover {
        transform: scale(1.05);
        border-color: var(--primary-300);
    }
    
    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .card-info {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        box-shadow: var(--shadow-sm);
    }
    
    .card-badges {
        display: flex;
        gap: var(--space-sm);
        margin-bottom: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .card-badge {
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-difficulty {
        background: var(--warning-100);
        color: var(--warning-700);
        border: 1px solid var(--warning-200);
    }
    
    .badge-type {
        background: var(--info-100);
        color: var(--info-700);
        border: 1px solid var(--info-200);
    }
    
    .badge-new {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .card-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: var(--space-md);
        color: var(--on-surface);
        line-height: 1.3;
    }
    
    .card-description {
        color: var(--on-surface-variant);
        line-height: 1.7;
        margin-bottom: var(--space-xl);
        font-size: 1rem;
    }
    
    .card-meta {
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
    }
    
    .meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-sm) 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .meta-row:last-child {
        border-bottom: none;
    }
    
    .meta-label {
        color: var(--on-surface-variant);
        font-weight: 500;
    }
    
    .meta-value {
        color: var(--on-surface);
        font-weight: 600;
    }
    
    .price-section {
        background: linear-gradient(135deg, var(--primary-50), var(--secondary-50));
        border: 2px solid var(--primary-200);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        text-align: center;
        margin-bottom: var(--space-xl);
    }
    
    .price {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--primary-600);
        margin-bottom: var(--space-sm);
    }
    
    .price-label {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .stock-status {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        margin-top: var(--space-md);
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        font-weight: 600;
    }
    
    .stock-available {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .stock-low {
        background: var(--warning-100);
        color: var(--warning-700);
        border: 1px solid var(--warning-200);
    }
    
    .stock-out {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
    }
    
    .quantity-label {
        font-weight: 500;
        color: var(--on-surface);
    }
    
    .quantity-controls {
        display: flex;
        align-items: center;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        overflow: hidden;
    }
    
    .quantity-btn {
        background: var(--surface-variant);
        border: none;
        padding: var(--space-sm) var(--space-md);
        cursor: pointer;
        color: var(--on-surface-variant);
        transition: all var(--transition-fast);
    }
    
    .quantity-btn:hover {
        background: var(--primary-100);
        color: var(--primary-600);
    }
    
    .quantity-input {
        border: none;
        padding: var(--space-sm) var(--space-md);
        text-align: center;
        width: 60px;
        background: var(--surface);
        color: var(--on-surface);
    }
    
    .action-buttons {
        display: flex;
        gap: var(--space-md);
        flex-wrap: wrap;
    }
    
    .add-to-cart-btn {
        flex: 2;
        padding: var(--space-md) var(--space-lg);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
    }
    
    .add-to-cart-btn:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .add-to-cart-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .wishlist-btn {
        padding: var(--space-md);
        background: var(--surface);
        color: var(--on-surface-variant);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .wishlist-btn:hover {
        background: var(--error-50);
        color: var(--error-600);
        border-color: var(--error-200);
        transform: translateY(-2px);
    }
    
    .wishlist-btn.active {
        background: var(--error-100);
        color: var(--error-600);
        border-color: var(--error-300);
    }
    
    .card-details-section {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        margin-bottom: var(--space-2xl);
        box-shadow: var(--shadow-sm);
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-lg);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .feature-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-lg);
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-md);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
    }
    
    .feature-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }
    
    .feature-text {
        flex: 1;
    }
    
    .feature-title {
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
    }
    
    .feature-description {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .related-cards {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        box-shadow: var(--shadow-sm);
    }
    
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-lg);
    }
    
    .related-card {
        background: var(--surface-variant);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all var(--transition-normal);
        text-decoration: none;
        color: inherit;
    }
    
    .related-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-200);
    }
    
    .related-image {
        width: 100%;
        height: 150px;
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .related-content {
        padding: var(--space-lg);
    }
    
    .related-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
        line-height: 1.3;
    }
    
    .related-price {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-600);
    }
    
    @media (max-width: 768px) {
        .card-detail-grid {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .main-image {
            height: 300px;
        }
        
        .card-title {
            font-size: 1.5rem;
        }
        
        .price {
            font-size: 2rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .add-to-cart-btn {
            flex: none;
        }
        
        .quantity-selector {
            justify-content: space-between;
        }
        
        .breadcrumb {
            justify-content: center;
        }
        
        .related-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="card-detail-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('educational-cards.index') }}" class="breadcrumb-link">
                    <i class="fas fa-graduation-cap"></i>
                    {{ __('Educational Cards') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('educational-cards.grades', $card->subject->grade->platform) }}" class="breadcrumb-link">
                    {{ app()->getLocale() === 'ar' && $card->subject->grade->platform->name_ar ? $card->subject->grade->platform->name_ar : $card->subject->grade->platform->name }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('educational-cards.subjects', [$card->subject->grade->platform, $card->subject->grade]) }}" class="breadcrumb-link">
                    {{ app()->getLocale() === 'ar' && $card->subject->grade->name_ar ? $card->subject->grade->name_ar : $card->subject->grade->name }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('educational-cards.cards', [$card->subject->grade->platform, $card->subject->grade, $card->subject]) }}" class="breadcrumb-link">
                    {{ app()->getLocale() === 'ar' && $card->subject->name_ar ? $card->subject->name_ar : $card->subject->name }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ app()->getLocale() === 'ar' && $card->title_ar ? $card->title_ar : $card->title }}</span>
            </nav>
        </div>
    </div>
</section>

<!-- Card Detail Container -->
<section class="card-detail-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('educational-cards.cards', [$card->subject->grade->platform, $card->subject->grade, $card->subject]) }}" class="back-button fade-in">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Cards') }}
        </a>
        
        <!-- Main Card Detail -->
        <div class="card-detail-grid fade-in">
            <!-- Gallery Section -->
            <div class="card-gallery">
                <div class="main-image" id="mainImage">
                    @if($card->main_image_url)
                        <img src="{{ $card->main_image_url }}" alt="{{ $card->title }}" id="mainImageImg">
                    @else
                        <div class="image-placeholder">
                            <i class="fas fa-id-card"></i>
                        </div>
                    @endif
                </div>
                
                @if($card->images->count() > 1)
                    <div class="thumbnail-gallery">
                        @foreach($card->images as $index => $image)
                            <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $card->title }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Card Information -->
            <div class="card-info">
                <!-- Badges -->
                <div class="card-badges">
                    <span class="card-badge badge-difficulty">{{ ucfirst($card->difficulty_level) }}</span>
                    <span class="card-badge badge-type">{{ ucfirst($card->card_type) }}</span>
                    @if($card->created_at->isAfter(now()->subDays(7)))
                        <span class="card-badge badge-new">{{ __('New') }}</span>
                    @endif
                </div>
                
                <!-- Title -->
                <h1 class="card-title">
                    {{ app()->getLocale() === 'ar' && $card->title_ar ? $card->title_ar : $card->title }}
                </h1>
                
                <!-- Description -->
                <p class="card-description">
                    {{ app()->getLocale() === 'ar' && $card->description_ar ? $card->description_ar : $card->description }}
                </p>
                
                <!-- Card Meta -->
                <div class="card-meta">
                    <div class="meta-row">
                        <span class="meta-label">{{ __('Subject') }}</span>
                        <span class="meta-value">{{ app()->getLocale() === 'ar' && $card->subject->name_ar ? $card->subject->name_ar : $card->subject->name }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">{{ __('Grade') }}</span>
                        <span class="meta-value">{{ app()->getLocale() === 'ar' && $card->subject->grade->name_ar ? $card->subject->grade->name_ar : $card->subject->grade->name }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">{{ __('Platform') }}</span>
                        <span class="meta-value">{{ app()->getLocale() === 'ar' && $card->subject->grade->platform->name_ar ? $card->subject->grade->platform->name_ar : $card->subject->grade->platform->name }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">{{ __('Difficulty') }}</span>
                        <span class="meta-value">{{ ucfirst($card->difficulty_level) }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">{{ __('Type') }}</span>
                        <span class="meta-value">{{ ucfirst($card->card_type) }}</span>
                    </div>
                </div>
                
                <!-- Price Section -->
                <div class="price-section">
                    <div class="price">${{ number_format($card->price, 2) }}</div>
                    <div class="price-label">{{ __('Per Card') }}</div>
                    
                    <!-- Stock Status -->
                    <div class="stock-status {{ $card->stock > 10 ? 'stock-available' : ($card->stock > 0 ? 'stock-low' : 'stock-out') }}">
                        @if($card->stock > 10)
                            <i class="fas fa-check-circle"></i>
                            {{ __('In Stock') }}
                        @elseif($card->stock > 0)
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ __('Low Stock') }} ({{ $card->stock }} {{ __('left') }})
                        @else
                            <i class="fas fa-times-circle"></i>
                            {{ __('Out of Stock') }}
                        @endif
                    </div>
                </div>
                
                @if($card->stock > 0)
                    <!-- Quantity Selector -->
                    <div class="quantity-selector">
                        <span class="quantity-label">{{ __('Quantity') }}:</span>
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" class="quantity-input" value="1" min="1" max="{{ $card->stock }}">
                            <button type="button" class="quantity-btn" onclick="changeQuantity(1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if($card->stock > 0)
                        @auth
                            <button class="add-to-cart-btn" onclick="addToCart()">
                                <i class="fas fa-cart-plus"></i>
                                {{ __('Add to Cart') }}
                            </button>
                            
                            <button class="wishlist-btn" onclick="toggleWishlist()" title="{{ __('Add to Wishlist') }}">
                                <i class="fas fa-heart"></i>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="add-to-cart-btn">
                                <i class="fas fa-sign-in-alt"></i>
                                {{ __('Login to Purchase') }}
                            </a>
                        @endauth
                    @else
                        <button class="add-to-cart-btn" disabled>
                            <i class="fas fa-times"></i>
                            {{ __('Out of Stock') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Card Details Section -->
        <div class="card-details-section fade-in">
            <h2 class="section-title">
                <i class="fas fa-info-circle"></i>
                {{ __('Card Features') }}
            </h2>
            
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="feature-text">
                        <div class="feature-title">{{ __('Educational Content') }}</div>
                        <div class="feature-description">{{ __('Carefully designed educational material') }}</div>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="feature-text">
                        <div class="feature-title">{{ __('Progressive Learning') }}</div>
                        <div class="feature-description">{{ __('Structured content for skill development') }}</div>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="feature-text">
                        <div class="feature-title">{{ __('Expert Reviewed') }}</div>
                        <div class="feature-description">{{ __('Content reviewed by education experts') }}</div>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="feature-text">
                        <div class="feature-title">{{ __('Digital Access') }}</div>
                        <div class="feature-description">{{ __('Available in digital format') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Cards -->
        @if($relatedCards && $relatedCards->count() > 0)
            <div class="related-cards fade-in">
                <h2 class="section-title">
                    <i class="fas fa-th-large"></i>
                    {{ __('Related Cards') }}
                </h2>
                
                <div class="related-grid">
                    @foreach($relatedCards as $relatedCard)
                        <a href="{{ route('educational-cards.show', $relatedCard) }}" class="related-card">
                            <div class="related-image">
                                @if($relatedCard->main_image_url)
                                    <img src="{{ $relatedCard->main_image_url }}" alt="{{ $relatedCard->title }}" loading="lazy">
                                @else
                                    <i class="fas fa-id-card" style="font-size: 2rem; color: var(--primary-500); opacity: 0.5;"></i>
                                @endif
                            </div>
                            
                            <div class="related-content">
                                <h3 class="related-title">
                                    {{ app()->getLocale() === 'ar' && $relatedCard->title_ar ? $relatedCard->title_ar : $relatedCard->title }}
                                </h3>
                                <div class="related-price">${{ number_format($relatedCard->price, 2) }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
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
</script>

<style>
    @keyframes slideOut {
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .slide-in {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endpush
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });
    
    // Gallery functionality
    function changeMainImage(imageSrc, thumbnailElement) {
        const mainImg = document.getElementById('mainImageImg');
        if (mainImg) {
            mainImg.src = imageSrc;
        }
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
        thumbnailElement.classList.add('active');
    }
    
    // Quantity controls
    function changeQuantity(delta) {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        const newValue = currentValue + delta;
        const max = parseInt(quantityInput.getAttribute('max'));
        const min = parseInt(quantityInput.getAttribute('min'));
        
        if (newValue >= min && newValue <= max) {
            quantityInput.value = newValue;
        }
    }
    
    // Add to cart functionality
    async function addToCart() {
        const quantity = document.getElementById('quantity').value;
        const cardId = {{ $card->id }};
        
        try {
            const response = await fetch('{{ route("cart.addItem") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    educational_card_id: cardId,
                    quantity: parseInt(quantity),
                    type: 'educational_card'
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('{{ __("Educational card added to cart!") }}', 'success');
                updateCartCount();
                
                // Add visual feedback
                const button = document.querySelector('.add-to-cart-btn');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i> {{ __("Added!") }}';
                button.style.background = 'linear-gradient(135deg, var(--success-500), var(--success-600))';
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.background = 'linear-gradient(135deg, var(--primary-500), var(--primary-600))';
                }, 2000);
            } else {
                showNotification(data.message || '{{ __("Error adding to cart") }}', 'error');
            }
        } catch (error) {
            showNotification('{{ __("Error adding to cart") }}', 'error');
        }
    }
    
    // Wishlist functionality
    async function toggleWishlist() {
        const cardId = {{ $card->id }};
        
        try {
            const response = await fetch('{{ route("wishlist.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: cardId
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                
                // Update wishlist button appearance
                const wishlistBtn = document.querySelector('.wishlist-btn');
                if (data.in_wishlist) {
                    wishlistBtn.classList.add('active');
                    wishlistBtn.innerHTML = '<i class="fas fa-heart"></i>';
                } else {
                    wishlistBtn.classList.remove('active');
                    wishlistBtn.innerHTML = '<i class="far fa-heart"></i>';
                }
                
                // Update wishlist count if element exists
                const wishlistCount = document.querySelector('.wishlist-count');
                if (wishlistCount) {
                    wishlistCount.textContent = data.count;
                }
            }
        } catch (error) {
            showNotification('{{ __("Error updating wishlist") }}', 'error');
        }
    }
    
    // Update cart count
    async function updateCartCount() {
        try {
            const response = await fetch('{{ route("cart.count") }}');
            const data = await response.json();
            
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.count;
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.maxWidth = '300px';
        notification.style.boxShadow = 'var(--shadow-xl)';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out forwards';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Image zoom functionality
    document.getElementById('mainImage').addEventListener('click', function() {
        const img = this.querySelector('img');
        if (img) {
            // Create zoom modal
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                cursor: zoom-out;
            `;
            
            const zoomedImg = document.createElement('img');
            zoomedImg.src = img.src;
            zoomedImg.style.cssText = `
                max-width: 90%;
                max-height: 90%;
                object-fit: contain;
                border-radius: var(--radius-lg);
                box-shadow: var(--shadow-2xl);
            `;
            
            modal.appendChild(zoomedImg);
            document.body.appendChild(modal);
            
            modal.addEventListener('click', () => {
                document.body.removeChild(modal);
            });
        }
    });
    
    // Smooth scrolling for related cards
    document.querySelectorAll('.related-card').forEach(card => {
        card.addEventListener('click', function(e) {
            const icon = this.querySelector('i');
            if (icon && !icon.classList.contains('fa-spinner')) {
                icon.classList.remove('fa-id-card');
                icon.classList.add('fa-spinner', 'fa-spin');
            }
        });
    });
    
    // Initialize quantity input validation
    document.getElementById('quantity').addEventListener('input', function() {
        const value = parseInt(this.value);
        const max = parseInt(this.getAttribute('max'));
        const min = parseInt(this.getAttribute('min'));
        
        if (value > max) this.value = max;
        if (value < min) this.value = min;
    });