@extends('layouts.app')

@section('title', __('My Orders') . ' - ' . config('app.name'))

@push('styles')
<style>
    .orders-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .orders-hero::before {
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
    
    .orders-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
        min-height: 50vh;
    }
    
    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-2xl);
        flex-wrap: wrap;
        gap: var(--space-lg);
    }
    
    .orders-count {
        font-size: 1.125rem;
        color: var(--on-surface-variant);
        font-weight: 500;
    }
    
    .orders-filters {
        display: flex;
        gap: var(--space-md);
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-select {
        padding: var(--space-sm) var(--space-md);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .filter-select:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .orders-grid {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .order-card {
        background: var(--surface);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
    }
    
    .order-card::before {
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
    
    .order-card:hover {
        border-color: var(--primary-200);
        box-shadow: var(--shadow-lg);
        transform: translateY(-4px);
    }
    
    .order-card:hover::before {
        transform: scaleX(1);
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--space-lg);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .order-info {
        flex: 1;
    }
    
    .order-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .order-meta {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        flex-wrap: wrap;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .order-status {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: var(--space-sm);
    }
    
    .status-badge {
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-xl);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 2px solid transparent;
    }
    
    .status-pending {
        background: var(--warning-100);
        color: var(--warning-700);
        border-color: var(--warning-200);
    }
    
    .status-processing {
        background: var(--info-100);
        color: var(--info-700);
        border-color: var(--info-200);
    }
    
    .status-shipped {
        background: var(--primary-100);
        color: var(--primary-700);
        border-color: var(--primary-200);
    }
    
    .status-delivered {
        background: var(--success-100);
        color: var(--success-700);
        border-color: var(--success-200);
    }
    
    .status-cancelled {
        background: var(--error-100);
        color: var(--error-700);
        border-color: var(--error-200);
    }
    
    .order-total {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--on-surface);
    }
    
    .order-items {
        margin-bottom: var(--space-lg);
    }
    
    .items-summary {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        margin-bottom: var(--space-md);
    }
    
    .item-previews {
        display: flex;
        gap: var(--space-sm);
        flex-wrap: wrap;
    }
    
    .item-preview {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-md);
        background: var(--surface-variant);
        border: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        overflow: hidden;
        position: relative;
    }
    
    .item-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .item-preview .quantity-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: var(--primary-500);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
        border: 2px solid var(--surface);
    }
    
    .more-items {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
        font-weight: 600;
    }
    
    .order-actions {
        display: flex;
        gap: var(--space-md);
        justify-content: flex-end;
        flex-wrap: wrap;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-lg);
        border: 2px solid transparent;
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .action-btn:focus {
        outline: 2px solid var(--primary-500);
        outline-offset: 2px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        box-shadow: var(--shadow-sm);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
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
    }
    
    .btn-outline {
        background: transparent;
        color: var(--primary-600);
        border-color: var(--primary-200);
    }
    
    .btn-outline:hover {
        background: var(--primary-50);
        border-color: var(--primary-300);
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
    
    .pagination-wrapper {
        margin-top: var(--space-2xl);
        display: flex;
        justify-content: center;
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .orders-header {
            flex-direction: column;
            align-items: stretch;
            gap: var(--space-md);
        }
        
        .orders-filters {
            justify-content: center;
        }
        
        .order-header {
            flex-direction: column;
            gap: var(--space-sm);
        }
        
        .order-status {
            align-items: flex-start;
            flex-direction: row;
            justify-content: space-between;
        }
        
        .order-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-sm);
        }
        
        .order-actions {
            justify-content: stretch;
        }
        
        .action-btn {
            flex: 1;
            justify-content: center;
        }
        
        .item-previews {
            justify-content: center;
        }
        
        .breadcrumb {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Orders Hero -->
<section class="orders-hero">
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
                <span>{{ __('Orders') }}</span>
            </nav>
            
            <h1 class="hero-title">{{ __('My Orders') }}</h1>
            <p class="hero-subtitle">{{ __('Track and manage all your orders in one place') }}</p>
        </div>
    </div>
</section>

<!-- Orders Container -->
<section class="orders-container">
    <div class="container">
        <!-- Orders Header -->
        <div class="orders-header fade-in">
            <div class="orders-count">
                {{ __('Total Orders') }}: <strong>{{ $orders->total() }}</strong>
            </div>
            
            <div class="orders-filters">
                <select class="filter-select" onchange="filterOrders(this.value)">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="processing">{{ __('Processing') }}</option>
                    <option value="shipped">{{ __('Shipped') }}</option>
                    <option value="delivered">{{ __('Delivered') }}</option>
                    <option value="cancelled">{{ __('Cancelled') }}</option>
                </select>
                
                <select class="filter-select" onchange="sortOrders(this.value)">
                    <option value="newest">{{ __('Newest First') }}</option>
                    <option value="oldest">{{ __('Oldest First') }}</option>
                    <option value="amount_high">{{ __('Highest Amount') }}</option>
                    <option value="amount_low">{{ __('Lowest Amount') }}</option>
                </select>
            </div>
        </div>
        
        <!-- Orders Grid -->
        @if($orders->count() > 0)
            <div class="orders-grid">
                @foreach($orders as $order)
                    <div class="order-card fade-in" data-status="{{ $order->status }}">
                        <!-- Order Header -->
                        <div class="order-header">
                            <div class="order-info">
                                <div class="order-number">
                                    <i class="fas fa-receipt"></i>
                                    {{ __('Order') }} #{{ $order->id }}
                                </div>
                                <div class="order-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $order->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        {{ $order->created_at->format('h:i A') }}
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-credit-card"></i>
                                        {{ ucfirst($order->payment_method) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="order-status">
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <div class="order-total">
                                    ${{ number_format($order->total_amount, 2) }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Items -->
                        <div class="order-items">
                            <div class="items-summary">
                                <i class="fas fa-box"></i>
                                {{ $order->orderItems->sum('quantity') }} {{ __('items') }}
                                @if($order->discount_amount > 0)
                                    <span style="color: var(--success-600);">
                                        <i class="fas fa-tag"></i>
                                        {{ __('Discount Applied') }}: ${{ number_format($order->discount_amount, 2) }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="item-previews">
                                @php
                                    $displayItems = $order->orderItems->take(4);
                                    $remainingItems = $order->orderItems->count() - 4;
                                @endphp
                                
                                @foreach($displayItems as $item)
                                    <div class="item-preview" title="{{ $item->item_name }}">
                                        @if($item->product && $item->product->images->first())
                                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->item_name }}">
                                        @elseif($item->educationalCard && $item->educationalCard->images->first())
                                            <img src="{{ asset('storage/' . $item->educationalCard->images->first()->image_path) }}" alt="{{ $item->item_name }}">
                                        @else
                                            <i class="fas fa-box"></i>
                                        @endif
                                        
                                        @if($item->quantity > 1)
                                            <span class="quantity-badge">{{ $item->quantity }}</span>
                                        @endif
                                    </div>
                                @endforeach
                                
                                @if($remainingItems > 0)
                                    <div class="item-preview more-items">
                                        +{{ $remainingItems }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Order Actions -->
                        <div class="order-actions">
                            <a href="{{ route('orders.show', $order) }}" class="action-btn btn-primary">
                                <i class="fas fa-eye"></i>
                                {{ __('View Details') }}
                            </a>
                            
                            @if($order->status === 'delivered')
                                <a href="{{ route('testimonials.create', $order) }}" class="action-btn btn-outline">
                                    <i class="fas fa-star"></i>
                                    {{ __('Review') }}
                                </a>
                            @endif
                            
                            @if(in_array($order->status, ['pending', 'processing']))
                                <button class="action-btn btn-secondary" onclick="cancelOrder('{{ $order->id }}')">
                                    <i class="fas fa-times"></i>
                                    {{ __('Cancel') }}
                                </button>
                            @endif
                            
                            <button class="action-btn btn-secondary" onclick="trackOrder('{{ $order->id }}')">
                                <i class="fas fa-truck"></i>
                                {{ __('Track') }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="pagination-wrapper fade-in">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state fade-in">
                <div class="empty-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h2 class="empty-title">{{ __('No Orders Yet') }}</h2>
                <p class="empty-text">
                    {{ __('You haven\'t placed any orders yet. Start shopping to see your orders here.') }}
                </p>
                <a href="{{ route('products.index') }}" class="empty-cta">
                    <i class="fas fa-shopping-cart"></i>
                    {{ __('Start Shopping') }}
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
    
    // Filter orders by status
    function filterOrders(status) {
        const orderCards = document.querySelectorAll('.order-card');
        
        orderCards.forEach(card => {
            if (!status || card.dataset.status === status) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
        
        // Update URL parameter
        const url = new URL(window.location);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.history.pushState({}, '', url);
    }
    
    // Sort orders
    function sortOrders(sortBy) {
        const ordersGrid = document.querySelector('.orders-grid');
        const orderCards = Array.from(document.querySelectorAll('.order-card'));
        
        orderCards.sort((a, b) => {
            switch (sortBy) {
                case 'newest':
                    return new Date(b.querySelector('.order-meta .meta-item').textContent) - 
                           new Date(a.querySelector('.order-meta .meta-item').textContent);
                case 'oldest':
                    return new Date(a.querySelector('.order-meta .meta-item').textContent) - 
                           new Date(b.querySelector('.order-meta .meta-item').textContent);
                case 'amount_high':
                    return parseFloat(b.querySelector('.order-total').textContent.replace(/[^0-9.]/g, '')) - 
                           parseFloat(a.querySelector('.order-total').textContent.replace(/[^0-9.]/g, ''));
                case 'amount_low':
                    return parseFloat(a.querySelector('.order-total').textContent.replace(/[^0-9.]/g, '')) - 
                           parseFloat(b.querySelector('.order-total').textContent.replace(/[^0-9.]/g, ''));
                default:
                    return 0;
            }
        });
        
        // Re-append sorted cards
        orderCards.forEach(card => {
            ordersGrid.appendChild(card);
        });
        
        // Update URL parameter
        const url = new URL(window.location);
        url.searchParams.set('sort', sortBy);
        window.history.pushState({}, '', url);
    }
    
    // Cancel order function
    function cancelOrder(orderId) {
        if (!confirm('{{ __("Are you sure you want to cancel this order?") }}')) {
            return;
        }
        
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('{{ __("Order cancelled successfully") }}', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || '{{ __("Failed to cancel order") }}', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('{{ __("An error occurred") }}', 'error');
        });
    }
    
    // Track order function
    function trackOrder(orderId) {
        // Implement order tracking functionality
        showNotification('{{ __("Opening order tracking...") }}', 'info');
        
        // For now, just redirect to order details
        setTimeout(() => {
            window.location.href = `/orders/${orderId}`;
        }, 500);
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
    
    // Apply filters from URL parameters on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        const status = urlParams.get('status');
        if (status) {
            const statusSelect = document.querySelector('.filter-select');
            statusSelect.value = status;
            filterOrders(status);
        }
        
        const sort = urlParams.get('sort');
        if (sort) {
            const sortSelect = document.querySelectorAll('.filter-select')[1];
            sortSelect.value = sort;
        }
    });
    
    // Add item preview hover effect
    document.querySelectorAll('.item-preview').forEach(preview => {
        preview.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.zIndex = '10';
        });
        
        preview.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.zIndex = '1';
        });
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