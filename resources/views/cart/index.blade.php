@extends('layouts.app')

@section('title', __('عربة التسوق') . ' - ' . config('app.name'))

@push('styles')
<style>
    /* RTL Direction */
    body {
        direction: rtl;
        text-align: right;
    }
    
    .cart-container {
        padding: var(--space-2xl) 0;
        min-height: 60vh;
    }
    
    .page-header {
        text-align: center;
        margin-bottom: var(--space-2xl);
    }
    
    .page-title {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .page-subtitle {
        color: var(--on-surface-variant);
        font-size: 1.125rem;
    }
    
    .cart-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--space-2xl);
        align-items: start;
    }
    
    .cart-items {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    
    .cart-header {
        background: var(--surface-variant);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--border-color);
    }
    
    .cart-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--on-surface);
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-direction: row-reverse;
    }
    
    .cart-list {
        padding: 0;
    }
    
    .cart-item {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--border-color);
        transition: background-color var(--transition-fast);
        flex-direction: row-reverse;
    }
    
    .cart-item:last-child {
        border-bottom: none;
    }
    
    .cart-item:hover {
        background: var(--surface-variant);
    }
    
    .item-image {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-lg);
        overflow: hidden;
        flex-shrink: 0;
        background: var(--gray-100);
    }
    
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .item-details {
        flex: 1;
        min-width: 0;
    }
    
    .item-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
        text-decoration: none;
        display: block;
        transition: color var(--transition-fast);
    }
    
    .item-title:hover {
        color: var(--primary-600);
    }
    
    .item-type {
        color: var(--primary-600);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: var(--space-xs);
    }
    
    .item-price {
        font-size: 1rem;
        font-weight: 600;
        color: var(--accent-600);
    }
    
    .item-controls {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        flex-shrink: 0;
        flex-direction: row-reverse;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
        padding: var(--space-xs);
        flex-direction: row-reverse;
    }
    
    .quantity-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: var(--surface);
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
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
        color: var(--on-surface);
        font-size: 0.875rem;
    }
    
    .item-subtotal {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--on-surface);
        min-width: 80px;
        text-align: left;
    }
    
    .remove-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: var(--error-50);
        color: var(--error-600);
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition-fast);
    }
    
    .remove-btn:hover {
        background: var(--error-500);
        color: white;
        transform: scale(1.1);
    }
    
    .cart-summary {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: var(--space-xl);
    }
    
    .summary-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-direction: row-reverse;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-md);
        font-size: 0.875rem;
    }
    
    .summary-label {
        color: var(--on-surface-variant);
    }
    
    .summary-value {
        font-weight: 600;
        color: var(--on-surface);
    }
    
    .summary-divider {
        height: 1px;
        background: var(--border-color);
        margin: var(--space-lg) 0;
    }
    
    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        font-size: 1.125rem;
        font-weight: 700;
    }
    
    .total-label {
        color: var(--on-surface);
    }
    
    .total-value {
        color: var(--primary-600);
        font-size: 1.5rem;
    }
    
    .checkout-btn {
        width: 100%;
        padding: var(--space-md) var(--space-lg);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-normal);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
        box-shadow: var(--shadow-md);
        flex-direction: row-reverse;
    }
    
    .checkout-btn:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .continue-shopping {
        width: 100%;
        padding: var(--space-sm) var(--space-lg);
        background: var(--surface);
        color: var(--on-surface-variant);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        flex-direction: row-reverse;
    }
    
    .continue-shopping:hover {
        background: var(--surface-variant);
        border-color: var(--primary-500);
        color: var(--primary-600);
        transform: translateY(-1px);
    }
    
    .empty-cart {
        text-align: center;
        padding: var(--space-3xl);
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
    }
    
    .empty-icon {
        font-size: 4rem;
        color: var(--on-surface-variant);
        opacity: 0.5;
        margin-bottom: var(--space-lg);
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: var(--space-sm);
    }
    
    .empty-text {
        color: var(--on-surface-variant);
        margin-bottom: var(--space-xl);
        font-size: 1rem;
    }
    
    .empty-actions {
        display: flex;
        gap: var(--space-md);
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .cart-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-lg);
        background: var(--surface-variant);
        border-top: 1px solid var(--border-color);
        flex-direction: row-reverse;
    }
    
    .items-count {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .clear-cart-btn {
        padding: var(--space-sm) var(--space-md);
        background: var(--error-50);
        color: var(--error-600);
        border: 1px solid var(--error-200);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        flex-direction: row-reverse;
    }
    
    .clear-cart-btn:hover {
        background: var(--error-500);
        color: white;
        border-color: var(--error-500);
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
        .cart-content {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .cart-item {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-md);
        }
        
        .item-details {
            width: 100%;
        }
        
        .item-controls {
            width: 100%;
            justify-content: space-between;
        }
        
        .cart-summary {
            position: static;
        }
        
        .page-title {
            font-size: 2rem;
        }
    }
    
    @media (max-width: 480px) {
        .cart-item {
            padding: var(--space-md);
        }
        
        .item-image {
            width: 60px;
            height: 60px;
        }
        
        .item-controls {
            flex-wrap: wrap;
            gap: var(--space-sm);
        }
        
        .empty-actions {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container cart-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">{{ __('عربة التسوق') }}</h1>
        <p class="page-subtitle">{{ __('راجع العناصر قبل الدفع') }}</p>
    </div>
    
    @if($cart && $cart->cartItems->count() > 0)
        <div class="cart-content">
            <!-- Cart Items -->
            <div class="cart-items fade-in">
                <div class="cart-header">
                    <h2>
                        <i class="fas fa-shopping-cart"></i>
                        {{ __('عناصر العربة') }} ({{ $cart->cartItems->count() }})
                    </h2>
                </div>
                
                <div class="cart-list">
                    @foreach($cart->cartItems as $item)
                        <div class="cart-item" data-item-id="{{ $item->id }}">
                            <!-- Item Image -->
                            <div class="item-image">
                                <img src="{{ $item->item_image }}" alt="{{ $item->item_name }}" loading="lazy">
                            </div>
                            
                            <!-- Item Details -->
                            <div class="item-details">
                                <div class="item-type">
                                    {{ $item->type === 'educational_card' ? __('بطاقة تعليمية') : __('منتج') }}
                                </div>
                                @if($item->type === 'educational_card')
                                    <a href="{{ route('educational-cards.show', $item->educationalCard) }}" class="item-title">
                                        {{ $item->item_name }}
                                    </a>
                                @else
                                    <a href="{{ route('products.show', $item->product) }}" class="item-title">
                                        {{ $item->item_name }}
                                    </a>
                                @endif
                                <div class="item-price">${{ number_format($item->item_price, 2) }}</div>
                            </div>
                            
                            <!-- Item Controls -->
                            <div class="item-controls">
                                <!-- Quantity Control -->
                                <div class="quantity-control">
                                    <button 
                                        class="quantity-btn" 
                                        onclick="updateQuantity('{{ $item->id }}', '{{ $item->quantity - 1 }}')"
                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                    >
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    
                                    <input 
                                        type="number" 
                                        class="quantity-input" 
                                        value="{{ $item->quantity }}" 
                                        min="1" 
                                        max="{{ $item->item->stock ?? 99 }}"
                                        onchange="updateQuantity('{{ $item->id }}', this.value)"
                                    >
                                    
                                    <button 
                                        class="quantity-btn" 
                                        onclick="updateQuantity('{{ $item->id }}', '{{ $item->quantity + 1 }}')"
                                        {{ $item->quantity >= ($item->item->stock ?? 99) ? 'disabled' : '' }}
                                    >
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                
                                <!-- Subtotal -->
                                <div class="item-subtotal">
                                    $<span class="subtotal-value">{{ number_format($item->subtotal(), 2) }}</span>
                                </div>
                                
                                <!-- Remove Button -->
                                <button 
                                    class="remove-btn" 
                                    onclick="removeItem('{{ $item->id }}')"
                                    title="{{ __('إزالة العنصر') }}"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Cart Actions -->
                <div class="cart-actions">
                    <div class="items-count">
                        {{ $cart->cartItems->count() }} {{ __('عناصر') }} • {{ $cart->cartItems->sum('quantity') }} {{ __('الكمية الإجمالية') }}
                    </div>
                    
                    <button class="clear-cart-btn" onclick="clearCart()">
                        <i class="fas fa-trash-alt"></i>
                        {{ __('تفريغ العربة') }}
                    </button>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="cart-summary fade-in">
                <h3 class="summary-title">
                    <i class="fas fa-receipt"></i>
                    {{ __('ملخص الطلب') }}
                </h3>
                
                <div class="summary-row">
                    <span class="summary-label">{{ __('المجموع الجزئي') }}</span>
                    <span class="summary-value" id="cartSubtotal">${{ number_format($cart->total(), 2) }}</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">{{ __('الشحن') }}</span>
                    <span class="summary-value">{{ __('مجاني') }}</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">{{ __('الضريبة') }}</span>
                    <span class="summary-value">{{ __('تحسب عند الدفع') }}</span>
                </div>
                
                <div class="summary-divider"></div>
                
                <div class="summary-total">
                    <span class="total-label">{{ __('الإجمالي') }}</span>
                    <span class="total-value" id="cartTotal">${{ number_format($cart->total(), 2) }}</span>
                </div>
                
                <a href="{{ route('checkout.index') }}" class="checkout-btn">
                    <i class="fas fa-credit-card"></i>
                    {{ __('الانتقال إلى الدفع') }}
                </a>
                
                <a href="{{ route('products.index') }}" class="continue-shopping">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('مواصلة التسوق') }}
                </a>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="empty-cart fade-in">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2 class="empty-title">{{ __('عربة التسوق فارغة') }}</h2>
            <p class="empty-text">{{ __('أضف بعض المنتجات إلى عربة التسوق للبدء') }}</p>
            
            <div class="empty-actions">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i>
                    {{ __('تصفح المنتجات') }}
                </a>
                
                <a href="{{ route('educational-cards.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-graduation-cap"></i>
                    {{ __('البطاقات التعليمية') }}
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Update item quantity
    async function updateQuantity(itemId, newQuantity) {
        if (newQuantity < 1) return;
        
        try {
            const response = await fetch(`/cart/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    quantity: newQuantity
                })
            });
            
            if (response.ok) {
                location.reload(); // Reload to update totals
            } else {
                const data = await response.json();
                showNotification(data.message || '{{ __("خطأ في تحديث الكمية") }}', 'error');
            }
        } catch (error) {
            showNotification('{{ __("خطأ في تحديث الكمية") }}', 'error');
        }
    }
    
    // Remove item from cart
    async function removeItem(itemId) {
        if (!confirm('{{ __("هل أنت متأكد من رغبتك في إزالة هذا العنصر؟") }}')) {
            return;
        }
        
        try {
            const response = await fetch(`/cart/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (response.ok) {
                // Animate removal
                const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
                itemElement.style.animation = 'slideOut 0.3s ease-out forwards';
                
                setTimeout(() => {
                    location.reload();
                }, 300);
            } else {
                showNotification('{{ __("خطأ في إزالة العنصر") }}', 'error');
            }
        } catch (error) {
            showNotification('{{ __("خطأ في إزالة العنصر") }}', 'error');
        }
    }
    
    // Clear entire cart
    async function clearCart() {
        if (!confirm('{{ __("هل أنت متأكد من رغبتك في تفريغ عربة التسوق بالكامل؟") }}')) {
            return;
        }
        
        try {
            const response = await fetch('{{ route("cart.clear") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (response.ok) {
                location.reload();
            } else {
                showNotification('{{ __("خطأ في تفريغ العربة") }}', 'error');
            }
        } catch (error) {
            showNotification('{{ __("خطأ في تفريغ العربة") }}', 'error');
        }
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.left = '20px'; /* Changed from right to left for RTL */
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
    
    // Initialize animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
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
</script>

<style>
    @keyframes slideOut {
        to {
            transform: translateX(100%);
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
        }
    }
</style>
@endpush