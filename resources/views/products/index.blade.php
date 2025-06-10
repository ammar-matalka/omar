@extends('layouts.app')

@section('title', __('Products') . ' - ' . config('app.name'))

@push('styles')
<style>
    .products-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .products-hero::before {
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
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-md);
    }
    
    .hero-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: var(--space-xl);
    }
    
    .products-container {
        padding: var(--space-2xl) 0;
    }
    
    .filters-section {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-2xl);
        box-shadow: var(--shadow-sm);
    }
    
    .filters-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: var(--space-lg);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .filters-form {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: var(--space-lg);
        align-items: end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .filter-label {
        font-weight: 500;
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .filter-input {
        padding: var(--space-sm) var(--space-md);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        background: var(--surface);
        color: var(--on-surface);
        transition: all var(--transition-fast);
    }
    
    .filter-input:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .filter-btn {
        padding: var(--space-sm) var(--space-lg);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .filter-btn:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .clear-filters {
        background: var(--surface);
        color: var(--on-surface-variant);
        border: 2px solid var(--border-color);
    }
    
    .clear-filters:hover {
        background: var(--surface-variant);
        border-color: var(--border-hover);
        transform: translateY(-1px);
    }
    
    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .products-count {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .products-count strong {
        color: var(--on-surface);
        font-weight: 600;
    }
    
    .sort-options {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .sort-label {
        font-weight: 500;
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .sort-select {
        padding: var(--space-sm) var(--space-md);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 0.875rem;
        cursor: pointer;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .product-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: all var(--transition-normal);
        position: relative;
        group: hover;
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-200);
    }
    
    .product-image {
        width: 100%;
        height: 200px;
        background: var(--gray-100);
        position: relative;
        overflow: hidden;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-slow);
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    .product-badge {
        position: absolute;
        top: var(--space-sm);
        left: var(--space-sm);
        background: linear-gradient(135deg, var(--accent-500), var(--accent-600));
        color: white;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .product-actions {
        position: absolute;
        top: var(--space-sm);
        right: var(--space-sm);
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
        opacity: 0;
        transform: translateX(20px);
        transition: all var(--transition-normal);
    }
    
    .product-card:hover .product-actions {
        opacity: 1;
        transform: translateX(0);
    }
    
    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: var(--surface);
        color: var(--on-surface-variant);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-fast);
        box-shadow: var(--shadow-md);
    }
    
    .action-btn:hover {
        background: var(--primary-500);
        color: white;
        transform: scale(1.1);
    }
    
    .action-btn.active {
        background: var(--error-500);
        color: white;
    }
    
    .product-info {
        padding: var(--space-lg);
    }
    
    .product-category {
        color: var(--primary-600);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: var(--space-xs);
    }
    
    .product-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: var(--space-sm);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-description {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: var(--space-md);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary-600);
        margin-bottom: var(--space-md);
    }
    
    .product-footer {
        display: flex;
        gap: var(--space-sm);
    }
    
    .add-to-cart-btn {
        flex: 1;
        padding: var(--space-sm) var(--space-md);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-xs);
    }
    
    .add-to-cart-btn:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .view-details-btn {
        padding: var(--space-sm) var(--space-md);
        background: var(--surface);
        color: var(--on-surface-variant);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-fast);
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .view-details-btn:hover {
        background: var(--surface-variant);
        border-color: var(--primary-500);
        color: var(--primary-600);
        transform: translateY(-1px);
    }
    
    .stock-indicator {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        margin-bottom: var(--space-sm);
        font-size: 0.75rem;
    }
    
    .stock-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    .stock-in {
        background: var(--success-500);
    }
    
    .stock-low {
        background: var(--warning-500);
    }
    
    .stock-out {
        background: var(--error-500);
    }
    
    .stock-text {
        font-weight: 500;
    }
    
    .stock-in + .stock-text {
        color: var(--success-600);
    }
    
    .stock-low + .stock-text {
        color: var(--warning-600);
    }
    
    .stock-out + .stock-text {
        color: var(--error-600);
    }
    
    .no-products {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--on-surface-variant);
    }
    
    .no-products-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        color: var(--on-surface-variant);
        opacity: 0.5;
    }
    
    .no-products-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
    }
    
    .no-products-text {
        font-size: 1rem;
        margin-bottom: var(--space-lg);
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
        .hero-title {
            font-size: 2rem;
        }
        
        .filters-form {
            grid-template-columns: 1fr;
            gap: var(--space-md);
        }
        
        .products-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .sort-options {
            justify-content: space-between;
        }
        
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: var(--space-lg);
        }
        
        .product-actions {
            opacity: 1;
            transform: translateX(0);
            flex-direction: row;
            bottom: var(--space-sm);
            top: auto;
            right: var(--space-sm);
            left: var(--space-sm);
            justify-content: flex-end;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="products-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">{{ __('Our Products') }}</h1>
            <p class="hero-subtitle">{{ __('Discover high-quality products for your learning journey') }}</p>
        </div>
    </div>
</section>

<!-- Products Container -->
<div class="container products-container">
    <!-- Filters Section -->
    <div class="filters-section fade-in">
        <h2 class="filters-title">
            <i class="fas fa-filter"></i>
            {{ __('Filter Products') }}
        </h2>
        
        <form method="GET" action="{{ route('products.index') }}" class="filters-form">
            <div class="filter-group">
                <label class="filter-label">{{ __('Search') }}</label>
                <input 
                    type="text" 
                    name="search" 
                    class="filter-input" 
                    placeholder="{{ __('Search products...') }}"
                    value="{{ request('search') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Category') }}</label>
                <select name="category" class="filter-input">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Price Range') }}</label>
                <select name="price_range" class="filter-input">
                    <option value="">{{ __('Any Price') }}</option>
                    <option value="0-50" {{ request('price_range') == '0-50' ? 'selected' : '' }}>{{ __('Under $50') }}</option>
                    <option value="50-100" {{ request('price_range') == '50-100' ? 'selected' : '' }}>$50 - $100</option>
                    <option value="100-200" {{ request('price_range') == '100-200' ? 'selected' : '' }}>$100 - $200</option>
                    <option value="200+" {{ request('price_range') == '200+' ? 'selected' : '' }}>{{ __('Over $200') }}</option>
                </select>
            </div>
            
            <div style="display: flex; gap: var(--space-sm);">
                <button type="submit" class="filter-btn">
                    <i class="fas fa-search"></i>
                    {{ __('Filter') }}
                </button>
                
                @if(request()->hasAny(['search', 'category', 'price_range']))
                    <a href="{{ route('products.index') }}" class="filter-btn clear-filters">
                        <i class="fas fa-times"></i>
                        {{ __('Clear') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Products Header -->
    <div class="products-header">
        <div class="products-count">
            {{ __('Showing') }} <strong>{{ $products->count() }}</strong> {{ __('of') }} <strong>{{ $products->total() }}</strong> {{ __('products') }}
        </div>
        
        <div class="sort-options">
            <label class="sort-label">{{ __('Sort by:') }}</label>
            <select class="sort-select" onchange="sortProducts(this.value)">
                <option value="newest">{{ __('Newest First') }}</option>
                <option value="oldest">{{ __('Oldest First') }}</option>
                <option value="price_low">{{ __('Price: Low to High') }}</option>
                <option value="price_high">{{ __('Price: High to Low') }}</option>
                <option value="name_asc">{{ __('Name: A to Z') }}</option>
                <option value="name_desc">{{ __('Name: Z to A') }}</option>
            </select>
        </div>
    </div>
    
    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
                <div class="product-card fade-in">
                    <!-- Product Image -->
                    <div class="product-image">
                        <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" loading="lazy">
                        
                        @if($product->featured)
                            <div class="product-badge">{{ __('Featured') }}</div>
                        @endif
                        
                        <!-- Product Actions -->
                        @auth
                            <div class="product-actions">
                                <button 
                                    class="action-btn wishlist-btn {{ Auth::user()->hasInWishlist($product->id) ? 'active' : '' }}" 
                                    onclick="toggleWishlist({{ $product->id }})"
                                    title="{{ __('Add to Wishlist') }}"
                                >
                                    <i class="fas fa-heart"></i>
                                </button>
                                
                                <button 
                                    class="action-btn quick-view-btn" 
                                    onclick="quickView({{ $product->id }})"
                                    title="{{ __('Quick View') }}"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        @endauth
                    </div>
                    
                    <!-- Product Info -->
                    <div class="product-info">
                        <div class="product-category">{{ $product->category->name }}</div>
                        
                        <h3 class="product-title">
                            <a href="{{ route('products.show', $product) }}" style="color: inherit; text-decoration: none;">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <p class="product-description">{{ $product->description }}</p>
                        
                        <!-- Stock Indicator -->
                        <div class="stock-indicator">
                            @if($product->stock > 10)
                                <div class="stock-dot stock-in"></div>
                                <span class="stock-text">{{ __('In Stock') }}</span>
                            @elseif($product->stock > 0)
                                <div class="stock-dot stock-low"></div>
                                <span class="stock-text">{{ __('Low Stock') }} ({{ $product->stock }})</span>
                            @else
                                <div class="stock-dot stock-out"></div>
                                <span class="stock-text">{{ __('Out of Stock') }}</span>
                            @endif
                        </div>
                        
                        <div class="product-price">${{ number_format($product->price, 2) }}</div>
                        
                        <!-- Product Footer -->
                        <div class="product-footer">
                            @if($product->stock > 0)
                                @auth
                                    <button 
                                        class="add-to-cart-btn" 
                                        onclick="addToCart({{ $product->id }})"
                                    >
                                        <i class="fas fa-shopping-cart"></i>
                                        {{ __('Add to Cart') }}
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="add-to-cart-btn">
                                        <i class="fas fa-sign-in-alt"></i>
                                        {{ __('Login to Buy') }}
                                    </a>
                                @endauth
                            @else
                                <button class="add-to-cart-btn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                    <i class="fas fa-times"></i>
                                    {{ __('Out of Stock') }}
                                </button>
                            @endif
                            
                            <a href="{{ route('products.show', $product) }}" class="view-details-btn">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->links() }}
            </div>
        @endif
    @else
        <!-- No Products Found -->
        <div class="no-products fade-in">
            <div class="no-products-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3 class="no-products-title">{{ __('No Products Found') }}</h3>
            <p class="no-products-text">{{ __('Try adjusting your search criteria or browse our categories.') }}</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="fas fa-refresh"></i>
                {{ __('View All Products') }}
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Sort products functionality
    function sortProducts(sortBy) {
        const url = new URL(window.location);
        url.searchParams.set('sort', sortBy);
        window.location.href = url.toString();
    }
    
    // Add to cart functionality
    async function addToCart(productId) {
        try {
            const response = await fetch('{{ route("cart.addItem") }}', {
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
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('{{ __("Product added to cart!") }}', 'success');
                updateCartCount();
            } else {
                showNotification(data.message || '{{ __("Error adding to cart") }}', 'error');
            }
        } catch (error) {
            showNotification('{{ __("Error adding to cart") }}', 'error');
        }
    }
    
    // Toggle wishlist functionality
    async function toggleWishlist(productId) {
        try {
            const response = await fetch('{{ route("wishlist.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                const btn = document.querySelector(`[onclick="toggleWishlist(${productId})"]`);
                btn.classList.toggle('active', data.in_wishlist);
                showNotification(data.message, 'success');
            }
        } catch (error) {
            showNotification('{{ __("Error updating wishlist") }}', 'error');
        }
    }
    
    // Quick view functionality
    function quickView(productId) {
        // Implement modal or redirect to product page
        window.location.href = `/products/${productId}`;
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
        // Create notification element
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
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out forwards';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
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
    
    // Set current sort value
    const urlParams = new URLSearchParams(window.location.search);
    const currentSort = urlParams.get('sort') || 'newest';
    const sortSelect = document.querySelector('.sort-select');
    if (sortSelect) {
        sortSelect.value = currentSort;
    }
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