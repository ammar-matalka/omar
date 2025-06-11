@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@push('styles')
<style>
    .product-container {
        padding: var(--space-2xl) 0;
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-xl);
        font-size: 0.875rem;
        color: var(--on-surface-variant);
    }
    
    .breadcrumb-link {
        color: var(--primary-600);
        text-decoration: none;
        transition: color var(--transition-fast);
    }
    
    .breadcrumb-link:hover {
        color: var(--primary-700);
    }
    
    .breadcrumb-separator {
        color: var(--on-surface-variant);
    }
    
    .product-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-2xl);
        margin-bottom: var(--space-3xl);
    }
    
    .product-gallery {
        position: relative;
    }
    
    .main-image {
        width: 100%;
        height: 500px;
        border-radius: var(--radius-xl);
        overflow: hidden;
        background: var(--surface);
        border: 1px solid var(--border-color);
        margin-bottom: var(--space-lg);
        position: relative;
    }
    
    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-slow);
    }
    
    .image-zoom {
        position: absolute;
        top: var(--space-md);
        right: var(--space-md);
        width: 40px;
        height: 40px;
        background: var(--surface);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: var(--shadow-md);
        transition: all var(--transition-fast);
        color: var(--on-surface-variant);
    }
    
    .image-zoom:hover {
        background: var(--primary-500);
        color: white;
        transform: scale(1.1);
    }
    
    .thumbnail-gallery {
        display: flex;
        gap: var(--space-sm);
        overflow-x: auto;
        padding: var(--space-xs) 0;
    }
    
    .thumbnail {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all var(--transition-fast);
        flex-shrink: 0;
    }
    
    .thumbnail.active {
        border-color: var(--primary-500);
        transform: scale(1.05);
    }
    
    .thumbnail:hover {
        border-color: var(--primary-300);
        transform: scale(1.02);
    }
    
    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-info {
        padding: var(--space-lg) 0;
    }
    
    .product-category {
        color: var(--primary-600);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: var(--space-sm);
    }
    
    .product-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--on-surface);
        margin-bottom: var(--space-md);
        line-height: 1.3;
    }
    
    .product-rating {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-lg);
    }
    
    .stars {
        display: flex;
        gap: 2px;
    }
    
    .star {
        color: #fbbf24;
        font-size: 1rem;
    }
    
    .star.empty {
        color: var(--gray-300);
    }
    
    .rating-text {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .product-price {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--primary-600);
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: baseline;
        gap: var(--space-md);
    }
    
    .original-price {
        font-size: 1.5rem;
        color: var(--on-surface-variant);
        text-decoration: line-through;
        font-weight: 500;
    }
    
    .discount-badge {
        background: linear-gradient(135deg, var(--error-500), var(--error-600));
        color: white;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .stock-status {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-lg);
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        font-weight: 500;
    }
    
    .stock-in {
        background: var(--success-50);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .stock-low {
        background: var(--warning-50);
        color: var(--warning-700);
        border: 1px solid var(--warning-200);
    }
    
    .stock-out {
        background: var(--error-50);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .stock-icon {
        font-size: 1rem;
    }
    
    .product-description {
        color: var(--on-surface-variant);
        line-height: 1.7;
        margin-bottom: var(--space-xl);
        font-size: 1rem;
    }
    
    .product-actions {
        background: var(--surface-variant);
        padding: var(--space-xl);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border-color);
    }
    
    .quantity-selector {
        margin-bottom: var(--space-lg);
    }
    
    .quantity-label {
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-sm);
        display: block;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: var(--space-sm);
        border: 1px solid var(--border-color);
        width: fit-content;
    }
    
    .quantity-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: var(--surface-variant);
        color: var(--on-surface-variant);
        border-radius: var(--radius-md);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition-fast);
        font-weight: 600;
    }
    
    .quantity-btn:hover:not(:disabled) {
        background: var(--primary-500);
        color: white;
        transform: scale(1.1);
    }
    
    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .quantity-input {
        width: 60px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
        color: var(--on-surface);
        font-size: 1rem;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
    }
    
    .add-to-cart-btn {
        width: 100%;
        padding: var(--space-lg) var(--space-xl);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-size: 1.125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-normal);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        box-shadow: var(--shadow-md);
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
    
    .secondary-actions {
        display: flex;
        gap: var(--space-sm);
    }
    
    .wishlist-btn,
    .share-btn {
        flex: 1;
        padding: var(--space-md) var(--space-lg);
        background: var(--surface);
        color: var(--on-surface-variant);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        text-decoration: none;
    }
    
    .wishlist-btn:hover,
    .share-btn:hover {
        background: var(--surface-variant);
        border-color: var(--primary-500);
        color: var(--primary-600);
        transform: translateY(-1px);
    }
    
    .wishlist-btn.active {
        background: var(--error-50);
        border-color: var(--error-200);
        color: var(--error-600);
    }
    
    .product-features {
        margin-top: var(--space-xl);
        padding: var(--space-lg);
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
    }
    
    .features-title {
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .features-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .feature-icon {
        color: var(--success-500);
        font-size: 0.875rem;
    }
    
    .related-products {
        margin-top: var(--space-3xl);
    }
    
    .section-header {
        text-align: center;
        margin-bottom: var(--space-2xl);
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: var(--space-sm);
        background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .section-subtitle {
        color: var(--on-surface-variant);
        font-size: 1rem;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-xl);
    }
    
    .product-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: all var(--transition-normal);
        position: relative;
    }
    
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-200);
    }
    
    .card-image {
        width: 100%;
        height: 200px;
        background: var(--gray-100);
        position: relative;
        overflow: hidden;
    }
    
    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-slow);
    }
    
    .product-card:hover .card-image img {
        transform: scale(1.05);
    }
    
    .card-info {
        padding: var(--space-lg);
    }
    
    .card-title {
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-sm);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-price {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-600);
        margin-bottom: var(--space-md);
    }
    
    .card-btn {
        width: 100%;
        padding: var(--space-sm) var(--space-md);
        background: var(--primary-500);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .card-btn:hover {
        background: var(--primary-600);
        transform: translateY(-1px);
    }
    
    .loading-spinner {
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .product-detail {
            grid-template-columns: 1fr;
            gap: var(--space-xl);
        }
        
        .product-title {
            font-size: 1.5rem;
        }
        
        .product-price {
            font-size: 2rem;
        }
        
        .main-image {
            height: 300px;
        }
        
        .secondary-actions {
            flex-direction: column;
        }
        
        .thumbnail-gallery {
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .product-container {
            padding: var(--space-lg) 0;
        }
        
        .product-info {
            padding: 0;
        }
        
        .product-actions {
            padding: var(--space-lg);
        }
        
        .main-image {
            height: 250px;
        }
        
        .products-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--space-lg);
        }
    }
</style>
@endpush

@section('content')
<div class="container product-container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb fade-in">
        <a href="{{ route('home') }}" class="breadcrumb-link">{{ __('Home') }}</a>
        <span class="breadcrumb-separator">/</span>
        <a href="{{ route('products.index') }}" class="breadcrumb-link">{{ __('Products') }}</a>
        <span class="breadcrumb-separator">/</span>
        <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="breadcrumb-link">{{ $product->category->name }}</a>
        <span class="breadcrumb-separator">/</span>
        <span>{{ $product->name }}</span>
    </nav>
    
    <!-- Product Detail -->
    <div class="product-detail">
        <!-- Product Gallery -->
        <div class="product-gallery fade-in">
            <div class="main-image">
                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" id="mainImage">
                <button class="image-zoom" onclick="openImageModal()">
                    <i class="fas fa-search-plus"></i>
                </button>
            </div>
            
            @if($product->images->count() > 1)
                <div class="thumbnail-gallery">
                    @foreach($product->images as $index => $image)
                        <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeMainImage(this.getAttribute('data-image-src'), this)">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <!-- Product Info -->
        <div class="product-info fade-in">
            <div class="product-category">{{ $product->category->name }}</div>
            
            <h1 class="product-title">{{ $product->name }}</h1>
            
            <!-- Product Rating -->
            <div class="product-rating">
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star star {{ $i <= 4 ? '' : 'empty' }}"></i>
                    @endfor
                </div>
                <span class="rating-text">(4.0) • 23 {{ __('reviews') }}</span>
            </div>
            
            <!-- Product Price -->
            <div class="product-price">
                ${{ number_format($product->price, 2) }}
                @if(rand(0, 1))
                    <span class="original-price">${{ number_format($product->price * 1.3, 2) }}</span>
                    <span class="discount-badge">23% {{ __('OFF') }}</span>
                @endif
            </div>
            
            <!-- Stock Status -->
            <div class="stock-status {{ $product->stock > 10 ? 'stock-in' : ($product->stock > 0 ? 'stock-low' : 'stock-out') }}">
                @if($product->stock > 10)
                    <i class="stock-icon fas fa-check-circle"></i>
                    <span>{{ __('In Stock') }} ({{ $product->stock }} {{ __('available') }})</span>
                @elseif($product->stock > 0)
                    <i class="stock-icon fas fa-exclamation-triangle"></i>
                    <span>{{ __('Low Stock') }} ({{ __('Only') }} {{ $product->stock }} {{ __('left') }})</span>
                @else
                    <i class="stock-icon fas fa-times-circle"></i>
                    <span>{{ __('Out of Stock') }}</span>
                @endif
            </div>
            
            <!-- Product Description -->
            <div class="product-description">
                {{ $product->description }}
            </div>
            
            <!-- Product Actions -->
            <div class="product-actions">
                @if($product->stock > 0)
                    <div class="quantity-selector">
                        <label class="quantity-label">{{ __('Quantity') }}</label>
                        <div class="quantity-control">
                            <button class="quantity-btn" onclick="changeQuantity(-1)" id="decreaseBtn">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="quantity-input" value="1" min="1" max="{{ $product->stock }}" id="quantityInput">
                            <button class="quantity-btn" onclick="changeQuantity(1)" id="increaseBtn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        @auth
                            <button class="add-to-cart-btn" onclick="addToCart()" id="addToCartBtn">
                                <i class="fas fa-shopping-cart"></i>
                                <span id="cartBtnText">{{ __('Add to Cart') }}</span>
                                <div class="loading-spinner" id="cartSpinner" style="display: none;"></div>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="add-to-cart-btn">
                                <i class="fas fa-sign-in-alt"></i>
                                {{ __('Login to Purchase') }}
                            </a>
                        @endauth
                        
                        <div class="secondary-actions">
                            @auth
                                <button class="wishlist-btn {{ Auth::user()->hasInWishlist($product->id) ? 'active' : '' }}" onclick="toggleWishlist()">
                                    <i class="fas fa-heart"></i>
                                    <span id="wishlistText">
                                        {{ Auth::user()->hasInWishlist($product->id) ? __('Remove from Wishlist') : __('Add to Wishlist') }}
                                    </span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="wishlist-btn">
                                    <i class="fas fa-heart"></i>
                                    {{ __('Add to Wishlist') }}
                                </a>
                            @endauth
                            
                            <button class="share-btn" onclick="shareProduct()">
                                <i class="fas fa-share-alt"></i>
                                {{ __('Share') }}
                            </button>
                        </div>
                    </div>
                @else
                    <div class="action-buttons">
                        <button class="add-to-cart-btn" disabled>
                            <i class="fas fa-times"></i>
                            {{ __('Out of Stock') }}
                        </button>
                        
                        <button class="wishlist-btn" onclick="notifyWhenAvailable()">
                            <i class="fas fa-bell"></i>
                            {{ __('Notify When Available') }}
                        </button>
                    </div>
                @endif
            </div>
            
            <!-- Product Features -->
            <div class="product-features">
                <h3 class="features-title">
                    <i class="fas fa-check-circle"></i>
                    {{ __('Product Features') }}
                </h3>
                <ul class="features-list">
                    <li class="feature-item">
                        <i class="feature-icon fas fa-shipping-fast"></i>
                        {{ __('Free shipping on orders over $50') }}
                    </li>
                    <li class="feature-item">
                        <i class="feature-icon fas fa-undo"></i>
                        {{ __('30-day return policy') }}
                    </li>
                    <li class="feature-item">
                        <i class="feature-icon fas fa-shield-alt"></i>
                        {{ __('1-year warranty') }}
                    </li>
                    <li class="feature-item">
                        <i class="feature-icon fas fa-headset"></i>
                        {{ __('24/7 customer support') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <section class="related-products">
            <div class="section-header">
                <h2 class="section-title">{{ __('Related Products') }}</h2>
                <p class="section-subtitle">{{ __('You might also like these products') }}</p>
            </div>
            
            <div class="products-grid">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="product-card fade-in">
                        <div class="card-image">
                            <img src="{{ $relatedProduct->main_image_url }}" alt="{{ $relatedProduct->name }}" loading="lazy">
                        </div>
                        <div class="card-info">
                            <h3 class="card-title">
                                <a href="{{ route('products.show', $relatedProduct) }}" style="color: inherit; text-decoration: none;">
                                    {{ $relatedProduct->name }}
                                </a>
                            </h3>
                            <div class="card-price">${{ number_format($relatedProduct->price, 2) }}</div>
                            <a href="{{ route('products.show', $relatedProduct) }}" class="card-btn">
                                {{ __('View Details') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
    let currentQuantity = 1;
    const maxQuantity = '{{ $product->stock }}';
    
    // Quantity control
    function changeQuantity(change) {
        const input = document.getElementById('quantityInput');
        const newValue = parseInt(input.value) + change;
        
        if (newValue >= 1 && newValue <= maxQuantity) {
            input.value = newValue;
            currentQuantity = newValue;
            updateQuantityButtons();
        }
    }
    
    function updateQuantityButtons() {
        const decreaseBtn = document.getElementById('decreaseBtn');
        const increaseBtn = document.getElementById('increaseBtn');
        
        decreaseBtn.disabled = currentQuantity <= 1;
        increaseBtn.disabled = currentQuantity >= maxQuantity;
    }
    
    // Input validation
    document.getElementById('quantityInput').addEventListener('change', function() {
        let value = parseInt(this.value);
        if (isNaN(value) || value < 1) value = 1;
        if (value > maxQuantity) value = maxQuantity;
        
        this.value = value;
        currentQuantity = value;
        updateQuantityButtons();
    });
    
    // Gallery functions
    function changeMainImage(src, thumbnail) {
        document.getElementById('mainImage').src = src;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        thumbnail.classList.add('active');
    }
    
    function openImageModal() {
        // Implement image modal/lightbox
        const mainImage = document.getElementById('mainImage');
        window.open(mainImage.src, '_blank');
    }
    
    // Add to cart
    async function addToCart() {
        const btn = document.getElementById('addToCartBtn');
        const btnText = document.getElementById('cartBtnText');
        const spinner = document.getElementById('cartSpinner');
        
        // Show loading state
        btn.disabled = true;
        btnText.style.display = 'none';
        spinner.style.display = 'block';
        
        try {
            const response = await fetch('{{ route("cart.addItem") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: '{{ $product->id }}',
                    quantity: currentQuantity,
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
        } finally {
            // Reset button state
            btn.disabled = false;
            btnText.style.display = 'inline';
            spinner.style.display = 'none';
        }
    }
    
    // Toggle wishlist
    async function toggleWishlist() {
        try {
            const response = await fetch('{{ route("wishlist.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: '{{ $product->id }}'
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                const btn = document.querySelector('.wishlist-btn');
                const text = document.getElementById('wishlistText');
                
                btn.classList.toggle('active', data.in_wishlist);
                text.textContent = data.in_wishlist ? 
                    '{{ __("Remove from Wishlist") }}' : 
                    '{{ __("Add to Wishlist") }}';
                
                showNotification(data.message, 'success');
            }
        } catch (error) {
            showNotification('{{ __("Error updating wishlist") }}', 'error');
        }
    }
    
    // Share product
    function shareProduct() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $product->name }}',
                text: '{{ $product->description }}',
                url: window.location.href
            });
        } else {
            // Fallback - copy to clipboard
            navigator.clipboard.writeText(window.location.href).then(() => {
                showNotification('{{ __("Product link copied to clipboard!") }}', 'success');
            }).catch(() => {
                showNotification('{{ __("Unable to share product") }}', 'error');
            });
        }
    }
    
    // Notify when available (for out of stock products)
    function notifyWhenAvailable() {
        showNotification('{{ __("You will be notified when this product is back in stock") }}', 'info');
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
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateQuantityButtons();
        
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

<style>
    @keyframes slideOut {
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
@endpush