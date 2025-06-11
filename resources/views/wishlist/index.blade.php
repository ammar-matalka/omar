@extends('layouts.app')

@section('title', __('My Wishlist') . ' - ' . config('app.name'))

@push('styles')
<style>
    .wishlist-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .wishlist-hero::before {
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
        text-align: center;
    }
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: var(--space-lg);
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
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
    
    .wishlist-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
        min-height: 60vh;
    }
    
    .wishlist-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-2xl);
        flex-wrap: wrap;
        gap: var(--space-lg);
    }
    
    .wishlist-count {
        font-size: 1.125rem;
        color: var(--on-surface-variant);
        font-weight: 500;
    }
    
    .wishlist-actions {
        display: flex;
        gap: var(--space-md);
        align-items: center;
        flex-wrap: wrap;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        border: 2px solid transparent;
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        box-shadow: var(--shadow-md);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .btn-secondary {
        background: var(--surface);
        color: var(--on-surface-variant);
        border-color: var(--border-color);
    }
    
    .btn-secondary:hover {
        background: var(--surface-variant);
        border-color: var(--border-hover);
        color: var(--on-surface);
        transform: translateY(-1px);
    }
    
    .btn-danger {
        background: var(--error-500);
        color: white;
        border-color: var(--error-500);
    }
    
    .btn-danger:hover {
        background: var(--error-600);
        border-color: var(--error-600);
        transform: translateY(-1px);
    }
    
    .sort-dropdown {
        position: relative;
    }
    
    .sort-select {
        padding: var(--space-sm) var(--space-md);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-fast);
        appearance: none;
        padding-right: var(--space-xl);
    }
    
    .sort-select:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .sort-dropdown::after {
        content: '';
        position: absolute;
        right: var(--space-md);
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid var(--on-surface-variant);
        pointer-events: none;
    }
    
    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: var(--space-xl);
    }
    
    .wishlist-item {
        background: var(--surface);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-normal);
        position: relative;
        display: flex;
        flex-direction: column;
    }
    
    .wishlist-item:hover {
        border-color: var(--primary-200);
        box-shadow: var(--shadow-lg);
        transform: translateY(-4px);
    }
    
    .item-image {
        position: relative;
        aspect-ratio: 4/3;
        overflow: hidden;
        background: var(--surface-variant);
    }
    
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-normal);
    }
    
    .wishlist-item:hover .item-image img {
        transform: scale(1.05);
    }
    
    .item-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        opacity: 0;
        transition: opacity var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-md);
    }
    
    .wishlist-item:hover .item-overlay {
        opacity: 1;
    }
    
    .overlay-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: none;
        color: var(--primary-600);
        font-size: 1rem;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-md);
    }
    
    .overlay-btn:hover {
        background: var(--primary-500);
        color: white;
        transform: scale(1.1);
    }
    
    .overlay-btn.remove {
        background: var(--error-500);
        color: white;
    }
    
    .overlay-btn.remove:hover {
        background: var(--error-600);
        transform: scale(1.1);
    }
    
    .item-badge {
        position: absolute;
        top: var(--space-md);
        left: var(--space-md);
        background: var(--primary-500);
        color: white;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
    }
    
    .badge-sale {
        background: var(--error-500);
    }
    
    .badge-new {
        background: var(--success-500);
    }
    
    .badge-out-of-stock {
        background: var(--warning-500);
    }
    
    .item-content {
        padding: var(--space-lg);
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .item-category {
        font-size: 0.75rem;
        color: var(--primary-600);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--space-xs);
    }
    
    .item-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: var(--space-sm);
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .item-description {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
        line-height: 1.5;
        margin-bottom: var(--space-md);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    
    .item-price {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
    }
    
    .current-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-600);
    }
    
    .original-price {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
        text-decoration: line-through;
    }
    
    .discount-badge {
        background: var(--error-500);
        color: white;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .item-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        margin-bottom: var(--space-md);
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .stock-status {
        font-weight: 500;
    }
    
    .in-stock {
        color: var(--success-600);
    }
    
    .out-of-stock {
        color: var(--error-600);
    }
    
    .low-stock {
        color: var(--warning-600);
    }
    
    .item-actions {
        display: flex;
        gap: var(--space-sm);
    }
    
    .item-btn {
        flex: 1;
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--on-surface-variant);
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        text-align: center;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-xs);
    }
    
    .item-btn:hover {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
        transform: translateY(-1px);
    }
    
    .item-btn.primary {
        background: var(--primary-500);
        color: white;
        border-color: var(--primary-500);
    }
    
    .item-btn.primary:hover {
        background: var(--primary-600);
        border-color: var(--primary-600);
    }
    
    .item-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    
    .remove-btn {
        position: absolute;
        top: var(--space-md);
        right: var(--space-md);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        color: var(--error-500);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0.8);
        z-index: 2;
    }
    
    .wishlist-item:hover .remove-btn {
        opacity: 1;
        transform: scale(1);
    }
    
    .remove-btn:hover {
        background: var(--error-500);
        color: white;
        transform: scale(1.1);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl) var(--space-xl);
        color: var(--on-surface-variant);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
        color: var(--primary-300);
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-md);
        color: var(--on-surface);
    }
    
    .empty-text {
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-xl);
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .empty-cta {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-xl);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-weight: 600;
        transition: all var(--transition-fast);
        box-shadow: var(--shadow-md);
    }
    
    .empty-cta:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .bulk-actions {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        display: none;
    }
    
    .bulk-actions.active {
        display: block;
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .bulk-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-md);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .bulk-title {
        font-weight: 600;
        color: var(--on-surface);
    }
    
    .bulk-buttons {
        display: flex;
        gap: var(--space-sm);
        flex-wrap: wrap;
    }
    
    .loading-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .wishlist-header {
            flex-direction: column;
            align-items: stretch;
            gap: var(--space-md);
        }
        
        .wishlist-actions {
            justify-content: center;
        }
        
        .wishlist-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: var(--space-lg);
        }
        
        .breadcrumb {
            justify-content: center;
        }
        
        .bulk-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .bulk-buttons {
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .wishlist-grid {
            grid-template-columns: 1fr;
        }
        
        .item-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<!-- Wishlist Hero -->
<section class="wishlist-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    {{ __('Home') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('user.profile.show') }}" class="breadcrumb-link">
                    {{ __('Profile') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ __('Wishlist') }}</span>
            </nav>
            
            <h1 class="hero-title">{{ __('My Wishlist') }}</h1>
            <p class="hero-subtitle">{{ __('Keep track of your favorite products and never miss a deal') }}</p>
        </div>
    </div>
</section>

<!-- Wishlist Container -->
<section class="wishlist-container">
    <div class="container">
        <!-- Wishlist Header -->
        <div class="wishlist-header fade-in">
            <div class="wishlist-count">
                {{ __('Wishlist Items') }}: <strong>{{ $wishlistItems->count() }}</strong>
            </div>
            
            <div class="wishlist-actions">
                @if($wishlistItems->count() > 0)
                    <button class="action-btn btn-secondary" onclick="toggleBulkActions()">
                        <i class="fas fa-check-square"></i>
                        {{ __('Select Multiple') }}
                    </button>
                    
                    <div class="sort-dropdown">
                        <select class="sort-select" onchange="sortWishlist(this.value)">
                            <option value="newest">{{ __('Newest First') }}</option>
                            <option value="oldest">{{ __('Oldest First') }}</option>
                            <option value="name_asc">{{ __('Name A-Z') }}</option>
                            <option value="name_desc">{{ __('Name Z-A') }}</option>
                            <option value="price_low">{{ __('Price Low to High') }}</option>
                            <option value="price_high">{{ __('Price High to Low') }}</option>
                        </select>
                    </div>
                    
                    <button class="action-btn btn-danger" onclick="clearWishlist()">
                        <i class="fas fa-trash"></i>
                        {{ __('Clear All') }}
                    </button>
                @endif
                
                <a href="{{ route('products.index') }}" class="action-btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Add More Items') }}
                </a>
            </div>
        </div>
        
        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulk-actions">
            <div class="bulk-header">
                <div class="bulk-title">
                    <span id="selected-count">0</span> {{ __('items selected') }}
                </div>
                <div class="bulk-buttons">
                    <button class="action-btn btn-primary" onclick="addSelectedToCart()">
                        <i class="fas fa-shopping-cart"></i>
                        {{ __('Add to Cart') }}
                    </button>
                    <button class="action-btn btn-danger" onclick="removeSelected()">
                        <i class="fas fa-trash"></i>
                        {{ __('Remove Selected') }}
                    </button>
                    <button class="action-btn btn-secondary" onclick="cancelBulkActions()">
                        <i class="fas fa-times"></i>
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Wishlist Grid -->
        @if($wishlistItems->count() > 0)
            <div class="wishlist-grid" id="wishlist-grid">
                @foreach($wishlistItems as $item)
                    <div class="wishlist-item fade-in" data-id="{{ $item->id }}" data-name="{{ strtolower($item->product->name) }}" data-price="{{ $item->product->price }}">
                        <!-- Remove Button -->
                        <button class="remove-btn" onclick="removeFromWishlist('{{ $item->product->id }}')">
                            <i class="fas fa-times"></i>
                        </button>
                        
                        <!-- Bulk Selection Checkbox -->
                        <input type="checkbox" class="bulk-checkbox" style="display: none; position: absolute; top: var(--space-md); left: var(--space-md); z-index: 3;" onchange="updateBulkSelection()">
                        
                        <!-- Item Image -->
                        <div class="item-image">
                            @if($item->product->images->first())
                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}">
                            @else
                                <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: var(--surface-variant); color: var(--on-surface-variant);">
                                    <i class="fas fa-image" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                            
                            <!-- Badges -->
                            @if($item->product->stock <= 0)
                                <div class="item-badge badge-out-of-stock">{{ __('Out of Stock') }}</div>
                            @elseif($item->product->created_at->diffInDays() < 30)
                                <div class="item-badge badge-new">{{ __('New') }}</div>
                            @endif
                            
                            <!-- Overlay Actions -->
                            <div class="item-overlay">
                                <button class="overlay-btn" onclick="quickView('{{ $item->product->id }}')" title="{{ __('Quick View') }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if($item->product->stock > 0)
                                    <button class="overlay-btn" onclick="addToCart('{{ $item->product->id }}')" title="{{ __('Add to Cart') }}">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                @endif
                                
                                <button class="overlay-btn remove" onclick="removeFromWishlist('{{ $item->product->id }}')" title="{{ __('Remove from Wishlist') }}">
                                    <i class="fas fa-heart-broken"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Item Content -->
                        <div class="item-content">
                            <div class="item-category">{{ $item->product->category->name }}</div>
                            
                            <h3 class="item-title">
                                <a href="{{ route('products.show', $item->product) }}" style="color: inherit; text-decoration: none;">
                                    {{ $item->product->name }}
                                </a>
                            </h3>
                            
                            <p class="item-description">{{ $item->product->description }}</p>
                            
                            <div class="item-price">
                                <span class="current-price">${{ number_format($item->product->price, 2) }}</span>
                                @if($item->product->original_price && $item->product->original_price > $item->product->price)
                                    <span class="original-price">${{ number_format($item->product->original_price, 2) }}</span>
                                    @php
                                        $discount = round((($item->product->original_price - $item->product->price) / $item->product->original_price) * 100);
                                    @endphp
                                    <span class="discount-badge">-{{ $discount }}%</span>
                                @endif
                            </div>
                            
                            <div class="item-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ __('Added') }} {{ $item->created_at->diffForHumans() }}
                                </div>
                                <div class="meta-item stock-status {{ $item->product->stock <= 0 ? 'out-of-stock' : ($item->product->stock < 10 ? 'low-stock' : 'in-stock') }}">
                                    <i class="fas fa-box"></i>
                                    @if($item->product->stock <= 0)
                                        {{ __('Out of Stock') }}
                                    @elseif($item->product->stock < 10)
                                        {{ __('Low Stock') }} ({{ $item->product->stock }})
                                    @else
                                        {{ __('In Stock') }} ({{ $item->product->stock }})
                                    @endif
                                </div>
                            </div>
                            
                            <div class="item-actions">
                                <a href="{{ route('products.show', $item->product) }}" class="item-btn">
                                    <i class="fas fa-eye"></i>
                                    {{ __('View') }}
                                </a>
                                
                                @if($item->product->stock > 0)
                                    <button class="item-btn primary" onclick="addToCart('{{ $item->product->id }}')">
                                        <i class="fas fa-shopping-cart"></i>
                                        {{ __('Add to Cart') }}
                                    </button>
                                @else
                                    <button class="item-btn" disabled>
                                        <i class="fas fa-ban"></i>
                                        {{ __('Out of Stock') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state fade-in">
                <div class="empty-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h2 class="empty-title">{{ __('Your Wishlist is Empty') }}</h2>
                <p class="empty-text">
                    {{ __('Start adding products to your wishlist to keep track of items you love and want to purchase later.') }}
                </p>
                <a href="{{ route('products.index') }}" class="empty-cta">
                    <i class="fas fa-shopping-bag"></i>
                    {{ __('Browse Products') }}
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    let bulkMode = false;
    
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
    
    // Toggle bulk actions mode
    function toggleBulkActions() {
        bulkMode = !bulkMode;
        const bulkActions = document.getElementById('bulk-actions');
        const checkboxes = document.querySelectorAll('.bulk-checkbox');
        
        if (bulkMode) {
            bulkActions.classList.add('active');
            checkboxes.forEach(checkbox => {
                checkbox.style.display = 'block';
            });
        } else {
            bulkActions.classList.remove('active');
            checkboxes.forEach(checkbox => {
                checkbox.style.display = 'none';
                checkbox.checked = false;
            });
        }
        
        updateBulkSelection();
    }
    
    // Cancel bulk actions
    function cancelBulkActions() {
        toggleBulkActions();
    }
    
    // Update bulk selection count
    function updateBulkSelection() {
        const checkedBoxes = document.querySelectorAll('.bulk-checkbox:checked');
        const selectedCount = document.getElementById('selected-count');
        selectedCount.textContent = checkedBoxes.length;
    }
    
    // Add selected items to cart
    function addSelectedToCart() {
        const checkedBoxes = document.querySelectorAll('.bulk-checkbox:checked');
        const productIds = Array.from(checkedBoxes).map(checkbox => {
            return checkbox.closest('.wishlist-item').dataset.id;
        });
        
        if (productIds.length === 0) {
            showNotification('{{ __("Please select items to add to cart") }}', 'warning');
            return;
        }
        
        // Add each product to cart
        let completed = 0;
        productIds.forEach(productId => {
            const item = document.querySelector(`[data-id="${productId}"]`);
            const productElement = item.querySelector('.item-btn.primary');
            if (productElement) {
                const productRealId = productElement.onclick.toString().match(/\d+/)[0];
                addToCart(productRealId).then(() => {
                    completed++;
                    if (completed === productIds.length) {
                        showNotification(`{{ __("Added") }} ${productIds.length} {{ __("items to cart") }}`, 'success');
                        cancelBulkActions();
                    }
                });
            }
        });
    }
    
    // Remove selected items
    function removeSelected() {
        const checkedBoxes = document.querySelectorAll('.bulk-checkbox:checked');
        const items = Array.from(checkedBoxes).map(checkbox => checkbox.closest('.wishlist-item'));
        
        if (items.length === 0) {
            showNotification('{{ __("Please select items to remove") }}', 'warning');
            return;
        }
        
        if (!confirm(`{{ __("Remove") }} ${items.length} {{ __("items from wishlist?") }}`)) {
            return;
        }
        
        items.forEach(item => {
            const removeBtn = item.querySelector('.remove-btn');
            if (removeBtn) {
                removeBtn.click();
            }
        });
        
        cancelBulkActions();
    }
    
    // Sort wishlist
    function sortWishlist(sortBy) {
        const grid = document.getElementById('wishlist-grid');
        const items = Array.from(grid.querySelectorAll('.wishlist-item'));
        
        items.sort((a, b) => {
            switch (sortBy) {
                case 'newest':
                    return new Date(b.querySelector('.meta-item').textContent) - new Date(a.querySelector('.meta-item').textContent);
                case 'oldest':
                    return new Date(a.querySelector('.meta-item').textContent) - new Date(b.querySelector('.meta-item').textContent);
                case 'name_asc':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'name_desc':
                    return b.dataset.name.localeCompare(a.dataset.name);
                case 'price_low':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price_high':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                default:
                    return 0;
            }
        });
        
        // Re-append sorted items
        items.forEach(item => {
            grid.appendChild(item);
        });
        
        // Animate items
        items.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }
    
    // Clear entire wishlist
    function clearWishlist() {
        if (!confirm('{{ __("Are you sure you want to clear your entire wishlist?") }}')) {
            return;
        }
        
        fetch('/wishlist/clear', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('{{ __("Wishlist cleared successfully") }}', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || '{{ __("Failed to clear wishlist") }}', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('{{ __("An error occurred") }}', 'error');
        });
    }
    
    // Remove item from wishlist
    function removeFromWishlist(productId) {
        if (!confirm('{{ __("Remove this item from your wishlist?") }}')) {
            return;
        }
        
        fetch(`/wishlist/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = document.querySelector(`[data-id] [onclick*="${productId}"]`).closest('.wishlist-item');
                if (item) {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.remove();
                        updateWishlistCount();
                    }, 300);
                }
                showNotification('{{ __("Item removed from wishlist") }}', 'success');
            } else {
                showNotification(data.message || '{{ __("Failed to remove item") }}', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('{{ __("An error occurred") }}', 'error');
        });
    }
    
    // Add item to cart
    function addToCart(productId) {
        return fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1,
                type: 'product'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('{{ __("Item added to cart") }}', 'success');
                updateCartCount();
            } else {
                showNotification(data.message || '{{ __("Failed to add item to cart") }}', 'error');
            }
            return data;
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('{{ __("An error occurred") }}', 'error');
            throw error;
        });
    }
    
    // Quick view (placeholder)
    function quickView(productId) {
        // Implement quick view modal or redirect to product page
        window.open(`/products/${productId}`, '_blank');
    }
    
    // Update wishlist count in header
    function updateWishlistCount() {
        const countElement = document.querySelector('.wishlist-count strong');
        if (countElement) {
            const currentCount = parseInt(countElement.textContent);
            countElement.textContent = Math.max(0, currentCount - 1);
        }
        
        // Check if wishlist is empty
        const items = document.querySelectorAll('.wishlist-item');
        if (items.length === 0) {
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }
    }
    
    // Update cart count in header (if exists)
    function updateCartCount() {
        fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.querySelector('.cart-count, .cart-badge');
            if (cartBadge) {
                cartBadge.textContent = data.count;
            }
        })
        .catch(error => {
            console.log('Failed to update cart count:', error);
        });
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
            box-shadow: var(--shadow-xl);
            animation: slideIn 0.3s ease-out;
        `;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
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
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Select all with Ctrl+A
        if ((e.ctrlKey || e.metaKey) && e.key === 'a' && bulkMode) {
            e.preventDefault();
            const checkboxes = document.querySelectorAll('.bulk-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateBulkSelection();
        }
        
        // Delete selected with Delete key
        if (e.key === 'Delete' && bulkMode) {
            e.preventDefault();
            removeSelected();
        }
        
        // Escape to cancel bulk mode
        if (e.key === 'Escape' && bulkMode) {
            cancelBulkActions();
        }
    });
</script>

<style>
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
    
    @keyframes slideOut {
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
@endpush