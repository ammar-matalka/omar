@extends('layouts.app')

@section('title', (app()->getLocale() === 'ar' && $subject->name_ar ? $subject->name_ar : $subject->name) . ' - ' . __('Educational Cards') . ' - ' . config('app.name'))

@push('styles')
<style>
    .cards-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .cards-hero::before {
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
    
    .subject-info {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .subject-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }
    
    .subject-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: var(--radius-xl);
    }
    
    .subject-details h1 {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .subject-meta {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-size: 1rem;
        opacity: 0.9;
        flex-wrap: wrap;
    }
    
    .meta-tag {
        background: rgba(255, 255, 255, 0.2);
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .cards-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
    }
    
    .section-header {
        text-align: center;
        margin-bottom: var(--space-2xl);
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: var(--space-md);
        background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .section-subtitle {
        font-size: 1rem;
        color: var(--on-surface-variant);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.7;
    }
    
    .filters-section {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-2xl);
        box-shadow: var(--shadow-sm);
    }
    
    .filters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-lg);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .filters-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .filters-count {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--space-lg);
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .filter-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--on-surface-variant);
    }
    
    .filter-select {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 0.875rem;
        transition: border-color var(--transition-fast);
    }
    
    .filter-select:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .card-item {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: all var(--transition-normal);
        position: relative;
        box-shadow: var(--shadow-sm);
    }
    
    .card-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
        transform: scaleX(0);
        transition: transform var(--transition-normal);
    }
    
    .card-item:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-200);
    }
    
    .card-item:hover::before {
        transform: scaleX(1);
    }
    
    .card-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-slow);
    }
    
    .card-item:hover .card-image img {
        transform: scale(1.05);
    }
    
    .card-placeholder {
        font-size: 3rem;
        color: var(--primary-500);
        opacity: 0.6;
    }
    
    .card-badges {
        position: absolute;
        top: var(--space-md);
        left: var(--space-md);
        right: var(--space-md);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .card-badge {
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        backdrop-filter: blur(10px);
    }
    
    .badge-difficulty {
        background: rgba(255, 255, 255, 0.9);
        color: var(--on-surface);
    }
    
    .badge-type {
        background: rgba(14, 165, 233, 0.9);
        color: white;
    }
    
    .badge-new {
        background: rgba(34, 197, 94, 0.9);
        color: white;
    }
    
    .card-actions {
        position: absolute;
        top: var(--space-md);
        right: var(--space-md);
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
        opacity: 0;
        transform: translateX(20px);
        transition: all var(--transition-normal);
    }
    
    .card-item:hover .card-actions {
        opacity: 1;
        transform: translateX(0);
    }
    
    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.9);
        color: var(--on-surface-variant);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-fast);
        box-shadow: var(--shadow-md);
        backdrop-filter: blur(10px);
    }
    
    .action-btn:hover {
        background: var(--primary-500);
        color: white;
        transform: scale(1.1);
    }
    
    .card-content {
        padding: var(--space-xl);
    }
    
    .card-title {
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-description {
        color: var(--on-surface-variant);
        margin-bottom: var(--space-lg);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-lg);
        padding: var(--space-md);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
    }
    
    .card-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-600);
    }
    
    .card-stock {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .stock-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    .stock-available {
        background: var(--success-500);
    }
    
    .stock-low {
        background: var(--warning-500);
    }
    
    .stock-out {
        background: var(--error-500);
    }
    
    .card-footer {
        display: flex;
        gap: var(--space-sm);
    }
    
    .view-btn {
        flex: 1;
        padding: var(--space-sm) var(--space-md);
        background: var(--surface);
        color: var(--on-surface-variant);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-xs);
    }
    
    .view-btn:hover {
        background: var(--surface-variant);
        border-color: var(--primary-500);
        color: var(--primary-600);
        transform: translateY(-1px);
    }
    
    .add-to-cart-btn {
        flex: 2;
        padding: var(--space-sm) var(--space-md);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-xs);
    }
    
    .add-to-cart-btn:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .add-to-cart-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
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
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--on-surface-variant);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
    }
    
    .empty-text {
        margin-bottom: var(--space-lg);
        line-height: 1.6;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: var(--space-2xl);
    }
    
    .pagination {
        display: flex;
        gap: var(--space-xs);
        align-items: center;
    }
    
    .page-link {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        color: var(--on-surface-variant);
        text-decoration: none;
        font-weight: 500;
        transition: all var(--transition-fast);
    }
    
    .page-link:hover {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
    }
    
    .page-link.active {
        background: var(--primary-500);
        border-color: var(--primary-500);
        color: white;
    }
    
    .page-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    @media (max-width: 768px) {
        .subject-info {
            flex-direction: column;
            text-align: center;
            gap: var(--space-md);
        }
        
        .subject-details h1 {
            font-size: 2rem;
        }
        
        .subject-meta {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .cards-grid {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
            gap: var(--space-md);
        }
        
        .card-actions {
            opacity: 1;
            transform: translateX(0);
            flex-direction: row;
            bottom: var(--space-md);
            top: auto;
            right: var(--space-md);
            left: var(--space-md);
            justify-content: flex-end;
        }
        
        .breadcrumb {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="cards-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('educational-cards.index') }}" class="breadcrumb-link">
                    <i class="fas fa-graduation-cap"></i>
                    {{ __('Educational Cards') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('educational-cards.grades', $platform) }}" class="breadcrumb-link">
                    {{ app()->getLocale() === 'ar' && $platform->name_ar ? $platform->name_ar : $platform->name }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('educational-cards.subjects', [$platform, $grade]) }}" class="breadcrumb-link">
                    {{ app()->getLocale() === 'ar' && $grade->name_ar ? $grade->name_ar : $grade->name }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ app()->getLocale() === 'ar' && $subject->name_ar ? $subject->name_ar : $subject->name }}</span>
            </nav>
            
            <!-- Subject Info -->
            <div class="subject-info">
                <div class="subject-icon">
                    @if($subject->image)
                        <img src="{{ $subject->image_url }}" alt="{{ $subject->name }}">
                    @else
                        <i class="fas fa-book"></i>
                    @endif
                </div>
                
                <div class="subject-details">
                    <h1>{{ app()->getLocale() === 'ar' && $subject->name_ar ? $subject->name_ar : $subject->name }}</h1>
                    <div class="subject-meta">
                        <span class="meta-tag">
                            {{ app()->getLocale() === 'ar' && $platform->name_ar ? $platform->name_ar : $platform->name }}
                        </span>
                        <span class="meta-tag">
                            {{ app()->getLocale() === 'ar' && $grade->name_ar ? $grade->name_ar : $grade->name }}
                        </span>
                        <span class="meta-tag">
                            {{ $cards->total() }} {{ __('Cards') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cards Container -->
<section class="cards-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('educational-cards.subjects', [$platform, $grade]) }}" class="back-button fade-in">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Subjects') }}
        </a>
        
        <!-- Filters Section -->
        <div class="filters-section fade-in">
            <div class="filters-header">
                <h3 class="filters-title">
                    <i class="fas fa-filter"></i>
                    {{ __('Filter Cards') }}
                </h3>
                <div class="filters-count">
                    {{ __('Showing') }} {{ $cards->count() }} {{ __('of') }} {{ $cards->total() }} {{ __('cards') }}
                </div>
            </div>
            
            <form method="GET" class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">{{ __('Card Type') }}</label>
                    <select name="type" class="filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('All Types') }}</option>
                        <option value="digital" {{ request('type') == 'digital' ? 'selected' : '' }}>{{ __('Digital') }}</option>
                        <option value="physical" {{ request('type') == 'physical' ? 'selected' : '' }}>{{ __('Physical') }}</option>
                        <option value="both" {{ request('type') == 'both' ? 'selected' : '' }}>{{ __('Both') }}</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">{{ __('Difficulty') }}</label>
                    <select name="difficulty" class="filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('All Levels') }}</option>
                        <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>{{ __('Easy') }}</option>
                        <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                        <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>{{ __('Hard') }}</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">{{ __('Availability') }}</label>
                    <select name="availability" class="filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('All Cards') }}</option>
                        <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>{{ __('In Stock') }}</option>
                        <option value="out_of_stock" {{ request('availability') == 'out_of_stock' ? 'selected' : '' }}>{{ __('Out of Stock') }}</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">{{ __('Sort By') }}</label>
                    <select name="sort" class="filter-select" onchange="this.form.submit()">
                        <option value="title" {{ request('sort', 'title') == 'title' ? 'selected' : '' }}>{{ __('Title A-Z') }}</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>{{ __('Title Z-A') }}</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('Price Low-High') }}</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('Price High-Low') }}</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                    </select>
                </div>
            </form>
        </div>
        
        <!-- Section Header -->
        <div class="section-header fade-in">
            <h2 class="section-title">{{ __('Educational Cards') }}</h2>
            <p class="section-subtitle">
                @if($subject->description || $subject->description_ar)
                    {{ app()->getLocale() === 'ar' && $subject->description_ar ? $subject->description_ar : $subject->description }}
                @else
                    {{ __('Explore our collection of educational cards designed to enhance your learning experience.') }}
                @endif
            </p>
        </div>
        
        <!-- Cards Grid -->
        @if($cards->count() > 0)
            <div class="cards-grid">
                @foreach($cards as $card)
                    <div class="card-item fade-in">
                        <div class="card-image">
                            @if($card->main_image_url)
                                <img src="{{ $card->main_image_url }}" alt="{{ $card->title }}" loading="lazy">
                            @else
                                <i class="card-placeholder fas fa-id-card"></i>
                            @endif
                            
                            <!-- Card Badges -->
                            <div class="card-badges">
                                <div class="badge-difficulty card-badge">
                                    {{ ucfirst($card->difficulty_level) }}
                                </div>
                                @if($card->created_at->isAfter(now()->subDays(7)))
                                    <div class="badge-new card-badge">{{ __('New') }}</div>
                                @endif
                            </div>
                            
                            <!-- Card Actions -->
                            @auth
                                <div class="card-actions">
                                    <button class="action-btn" onclick="addToWishlist('{{ $card->id }}')" title="{{ __('Add to Wishlist') }}">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <button class="action-btn" onclick="quickView('{{ $card->id }}')" title="{{ __('Quick View') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            @endauth
                        </div>
                        
                        <div class="card-content">
                            <h3 class="card-title">
                                {{ app()->getLocale() === 'ar' && $card->title_ar ? $card->title_ar : $card->title }}
                            </h3>
                            
                            <p class="card-description">
                                {{ app()->getLocale() === 'ar' && $card->description_ar ? $card->description_ar : $card->description }}
                            </p>
                            
                            <div class="card-info">
                                <div class="card-price">${{ number_format($card->price, 2) }}</div>
                                <div class="card-stock">
                                    @if($card->stock > 10)
                                        <span class="stock-indicator stock-available"></span>
                                        {{ __('In Stock') }}
                                    @elseif($card->stock > 0)
                                        <span class="stock-indicator stock-low"></span>
                                        {{ __('Low Stock') }} ({{ $card->stock }})
                                    @else
                                        <span class="stock-indicator stock-out"></span>
                                        {{ __('Out of Stock') }}
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <a href="{{ route('educational-cards.show', $card) }}" class="view-btn">
                                    <i class="fas fa-eye"></i>
                                    {{ __('Details') }}
                                </a>
                                
                                @if($card->stock > 0)
                                    @auth
                                        <button class="add-to-cart-btn" onclick="addToCart('{{ $card->id }}')">
                                            <i class="fas fa-cart-plus"></i>
                                            {{ __('Add to Cart') }}
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="add-to-cart-btn">
                                            <i class="fas fa-sign-in-alt"></i>
                                            {{ __('Login to Buy') }}
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
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($cards->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination">
                        @if($cards->onFirstPage())
                            <span class="page-link disabled">{{ __('Previous') }}</span>
                        @else
                            <a href="{{ $cards->previousPageUrl() }}" class="page-link">{{ __('Previous') }}</a>
                        @endif
                        
                        @foreach($cards->getUrlRange(1, $cards->lastPage()) as $page => $url)
                            @if($page == $cards->currentPage())
                                <span class="page-link active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                            @endif
                        @endforeach
                        
                        @if($cards->hasMorePages())
                            <a href="{{ $cards->nextPageUrl() }}" class="page-link">{{ __('Next') }}</a>
                        @else
                            <span class="page-link disabled">{{ __('Next') }}</span>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state fade-in">
                <div class="empty-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <h3 class="empty-title">{{ __('No Cards Found') }}</h3>
                <p class="empty-text">{{ __('No educational cards match your current filters. Try adjusting your search criteria or explore other subjects.') }}</p>
                <a href="{{ route('educational-cards.subjects', [$platform, $grade]) }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Subjects') }}
                </a>
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
                }, index * 50);
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
    
    // Add to cart functionality
    async function addToCart(cardId) {
        try {
            const response = await fetch('{{ route("cart.addItem") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    educational_card_id: cardId,
                    quantity: 1,
                    type: 'educational_card'
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('{{ __("Educational card added to cart!") }}', 'success');
                updateCartCount();
            } else {
                showNotification(data.message || '{{ __("Error adding to cart") }}', 'error');
            }
        } catch (error) {
            showNotification('{{ __("Error adding to cart") }}', 'error');
        }
    }
    
    // Add to wishlist functionality
    async function addToWishlist(cardId) {
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
            }
        } catch (error) {
            showNotification('{{ __("Error updating wishlist") }}', 'error');
        }
    }
    
    // Quick view functionality
    function quickView(cardId) {
        window.location.href = `/educational-cards/card/${cardId}`;
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
    
    // Add loading animation for buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!this.disabled) {
                const icon = this.querySelector('i');
                if (icon && !icon.classList.contains('fa-sign-in-alt')) {
                    icon.classList.remove('fa-cart-plus');
                    icon.classList.add('fa-spinner', 'fa-spin');
                    
                    setTimeout(() => {
                        icon.classList.remove('fa-spinner', 'fa-spin');
                        icon.classList.add('fa-cart-plus');
                    }, 1000);
                }
            }
        });
    });
</script>

<style>
    @keyframes slideOut {
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
@endpush