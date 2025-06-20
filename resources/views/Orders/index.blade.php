@extends('layouts.app')

@section('title', 'طلباتي - ' . config('app.name'))

@push('styles')
<style>
    /* RTL CSS Adjustments */
    :root {
        --text-direction: rtl;
    }
    
    body[dir="rtl"] {
        direction: rtl;
        text-align: right;
    }
    
    .orders-hero::before {
        right: 0;
        left: auto;
    }
    
    .breadcrumb {
        flex-direction: row-reverse;
    }
    
    .orders-header {
        flex-direction: row-reverse;
    }
    
    .orders-filters {
        flex-direction: row-reverse;
    }
    
    .order-header {
        flex-direction: row-reverse;
    }
    
    .order-status {
        align-items: flex-start;
    }
    
    .order-meta {
        flex-direction: row-reverse;
    }
    
    .item-previews {
        flex-direction: row-reverse;
    }
    
    .order-actions {
        flex-direction: row-reverse;
    }
    
    .empty-cta {
        flex-direction: row-reverse;
    }
    
    @media (max-width: 768px) {
        .orders-header {
            flex-direction: column;
        }
        
        .orders-filters {
            justify-content: flex-end;
        }
        
        .order-header {
            flex-direction: column;
        }
        
        .order-actions {
            flex-direction: row-reverse;
        }
    }
</style>
@endpush

@section('content')
<!-- Orders Hero -->
<section class="orders-hero" dir="rtl">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    الرئيسية
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('user.profile.show') }}" class="breadcrumb-link">
                    الملف الشخصي
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>طلباتي</span>
            </nav>
            
            <h1 class="hero-title">طلباتي</h1>
            <p class="hero-subtitle">تابع وادار جميع طلباتك في مكان واحد</p>
        </div>
    </div>
</section>

<!-- Orders Container -->
<section class="orders-container" dir="rtl">
    <div class="container">
        <!-- Orders Header -->
        <div class="orders-header fade-in">
            <div class="orders-count">
                إجمالي الطلبات: <strong>{{ $orders->total() }}</strong>
            </div>
            
            <div class="orders-filters">
                <select class="filter-select" onchange="filterOrders(this.value)">
                    <option value="">جميع الحالات</option>
                    <option value="pending">قيد الانتظار</option>
                    <option value="processing">قيد التجهيز</option>
                    <option value="shipped">تم الشحن</option>
                    <option value="delivered">تم التسليم</option>
                    <option value="cancelled">ملغية</option>
                </select>
                
                <select class="filter-select" onchange="sortOrders(this.value)">
                    <option value="newest">الأحدث أولاً</option>
                    <option value="oldest">الأقدم أولاً</option>
                    <option value="amount_high">الأعلى سعراً</option>
                    <option value="amount_low">الأقل سعراً</option>
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
                                    طلب #{{ $order->id }}
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
                                        @switch($order->payment_method)
                                            @case('credit_card') بطاقة ائتمان @break
                                            @case('cash') نقداً عند الاستلام @break
                                            @default {{ $order->payment_method }}
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                            
                            <div class="order-status">
                                <span class="status-badge status-{{ $order->status }}">
                                    @switch($order->status)
                                        @case('pending') قيد الانتظار @break
                                        @case('processing') قيد التجهيز @break
                                        @case('shipped') تم الشحن @break
                                        @case('delivered') تم التسليم @break
                                        @case('cancelled') ملغي @break
                                        @default {{ $order->status }}
                                    @endswitch
                                </span>
                                <div class="order-total">
                                    {{ number_format($order->total_amount, 2) }} ر.س
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Items -->
                        <div class="order-items">
                            <div class="items-summary">
                                <i class="fas fa-box"></i>
                                {{ $order->orderItems->sum('quantity') }} عناصر
                                @if($order->discount_amount > 0)
                                    <span style="color: var(--success-600);">
                                        <i class="fas fa-tag"></i>
                                        خصم مطبق: {{ number_format($order->discount_amount, 2) }} ر.س
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
                                عرض التفاصيل
                            </a>
                            
                            @if($order->status === 'delivered')
                                <a href="{{ route('testimonials.create', $order) }}" class="action-btn btn-outline">
                                    <i class="fas fa-star"></i>
                                    تقييم
                                </a>
                            @endif
                            
                            @if(in_array($order->status, ['pending', 'processing']))
                                <button class="action-btn btn-secondary" onclick="cancelOrder('{{ $order->id }}')">
                                    <i class="fas fa-times"></i>
                                    إلغاء
                                </button>
                            @endif
                            
                            <button class="action-btn btn-secondary" onclick="trackOrder('{{ $order->id }}')">
                                <i class="fas fa-truck"></i>
                                تتبع
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
                <h2 class="empty-title">لا توجد طلبات</h2>
                <p class="empty-text">
                    لم تقم بتقديم أي طلبات حتى الآن. ابدأ بالتسوق لرؤية طلباتك هنا.
                </p>
                <a href="{{ route('products.index') }}" class="empty-cta">
                    <i class="fas fa-shopping-cart"></i>
                    ابدأ التسوق
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
        if (!confirm('هل أنت متأكد أنك تريد إلغاء هذا الطلب؟')) {
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
                showNotification('تم إلغاء الطلب بنجاح', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'فشل في إلغاء الطلب', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('حدث خطأ', 'error');
        });
    }
    
    // Track order function
    function trackOrder(orderId) {
        showNotification('جاري فتح صفحة تتبع الطلب...', 'info');
        
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
            left: 20px;
            right: auto;
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
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }
</style>
@endpush