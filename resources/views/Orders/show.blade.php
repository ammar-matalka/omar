@extends('layouts.app')

@section('title', 'طلب #' . $order->id . ' - ' . config('app.name'))

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
    
    .order-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .order-hero::before {
        right: 0;
        left: auto;
    }
    
    .breadcrumb {
        flex-direction: row-reverse;
    }
    
    .order-header {
        flex-direction: row-reverse;
    }
    
    .order-meta {
        flex-direction: row-reverse;
    }
    
    .tracking-steps {
        flex-direction: row-reverse;
    }
    
    .tracking-steps::before {
        right: 20px;
        left: 20px;
    }
    
    .order-item {
        flex-direction: row-reverse;
    }
    
    .item-pricing {
        flex-direction: row-reverse;
    }
    
    .summary-row {
        flex-direction: row-reverse;
    }
    
    .info-row {
        flex-direction: row-reverse;
    }
    
    .info-value {
        text-align: left;
    }
    
    .actions-grid {
        direction: rtl;
    }
    
    @media (max-width: 768px) {
        .order-content {
            direction: rtl;
        }
        
        .order-item {
            flex-direction: column;
            text-align: right;
        }
        
        .item-details {
            align-items: flex-end;
        }
    }
</style>
@endpush

@section('content')
<!-- Order Hero -->
<section class="order-hero" dir="rtl">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    الرئيسية
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('orders.index') }}" class="breadcrumb-link">
                    طلباتي
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>طلب #{{ $order->id }}</span>
            </nav>
            
            <!-- Order Header -->
            <div class="order-header">
                <div>
                    <h1 class="order-title">طلب #{{ $order->id }}</h1>
                    <div class="order-meta">
                        <span class="order-date">{{ $order->created_at->format('M d, Y H:i') }}</span>
                        <span class="order-status">
                            @switch($order->status)
                                @case('pending') قيد الانتظار @break
                                @case('processing') قيد التجهيز @break
                                @case('shipped') تم الشحن @break
                                @case('delivered') تم التسليم @break
                                @case('cancelled') ملغي @break
                                @default {{ $order->status }}
                            @endswitch
                        </span>
                    </div>
                </div>
                
                <div class="order-amount">{{ number_format($order->total_amount, 2) }} ر.س</div>
            </div>
        </div>
    </div>
</section>

<!-- Order Container -->
<section class="order-container" dir="rtl">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('orders.index') }}" class="back-button fade-in">
            <i class="fas fa-arrow-left"></i>
            العودة إلى الطلبات
        </a>
        
        <!-- Order Content -->
        <div class="order-content">
            <!-- Main Content -->
            <div class="order-main">
                <!-- Order Tracking -->
                <div class="section-card order-tracking fade-in">
                    <h2 class="section-title">
                        <i class="fas fa-truck"></i>
                        تتبع الطلب
                    </h2>
                    
                    <div class="tracking-steps">
                        <div class="tracking-step">
                            <div class="step-circle {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="step-label {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                تم الطلب
                            </div>
                        </div>
                        
                        <div class="tracking-step">
                            <div class="step-circle {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : ($order->status === 'pending' ? 'active' : '') }}">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="step-label {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : ($order->status === 'pending' ? 'active' : '') }}">
                                قيد التجهيز
                            </div>
                        </div>
                        
                        <div class="tracking-step">
                            <div class="step-circle {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : ($order->status === 'processing' ? 'active' : '') }}">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="step-label {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : ($order->status === 'processing' ? 'active' : '') }}">
                                تم الشحن
                            </div>
                        </div>
                        
                        <div class="tracking-step">
                            <div class="step-circle {{ $order->status === 'delivered' ? 'completed' : ($order->status === 'shipped' ? 'active' : '') }}">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="step-label {{ $order->status === 'delivered' ? 'completed' : ($order->status === 'shipped' ? 'active' : '') }}">
                                تم التسليم
                            </div>
                        </div>
                    </div>
                    
                    <div class="tracking-info">
                        <div class="tracking-current">
                            @switch($order->status)
                                @case('pending')
                                    تم استلام طلبك وهو بانتظار التأكيد.
                                    @break
                                @case('processing')
                                    يتم حالياً تجهيز طلبك وتحضيره للشحن.
                                    @break
                                @case('shipped')
                                    تم شحن طلبك وهو في طريقه إليك.
                                    @break
                                @case('delivered')
                                    تم تسليم طلبك بنجاح.
                                    @break
                                @case('cancelled')
                                    تم إلغاء هذا الطلب.
                                    @break
                                @default
                                    حالة الطلب: @switch($order->status)
                                        @case('pending') قيد الانتظار @break
                                        @case('processing') قيد التجهيز @break
                                        @case('shipped') تم الشحن @break
                                        @case('delivered') تم التسليم @break
                                        @default {{ $order->status }}
                                    @endswitch
                            @endswitch
                        </div>
                        <div class="tracking-details">
                            آخر تحديث: {{ $order->updated_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="section-card fade-in">
                    <h2 class="section-title">
                        <i class="fas fa-box"></i>
                        العناصر المطلوبة ({{ $order->orderItems->sum('quantity') }})
                    </h2>
                    
                    <div class="order-items-list">
                        @foreach($order->orderItems as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    @if($item->item && $item->item->main_image_url)
                                        <img src="{{ $item->item->main_image_url }}" alt="{{ $item->display_name }}">
                                    @else
                                        <i class="item-placeholder fas fa-{{ $item->type === 'educational_card' ? 'id-card' : 'box' }}"></i>
                                    @endif
                                </div>
                                
                                <div class="item-details">
                                    <h3 class="item-name">{{ $item->display_name }}</h3>
                                    
                                    <div class="item-meta">
                                        <span class="item-type">
                                            @switch($item->type)
                                                @case('educational_card') بطاقة تعليمية @break
                                                @case('product') منتج @break
                                                @default {{ $item->type }}
                                            @endswitch
                                        </span>
                                        @if($item->item)
                                            <span>رقم SKU: {{ $item->item->id }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="item-pricing">
                                        <div>
                                            <span class="item-quantity">الكمية: {{ $item->quantity }}</span>
                                            <span class="item-price"> × {{ number_format($item->price, 2) }} ر.س</span>
                                        </div>
                                        <div class="item-subtotal">{{ number_format($item->quantity * $item->price, 2) }} ر.س</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="order-sidebar">
                <!-- Order Summary -->
                <div class="section-card order-summary fade-in">
                    <h3 class="section-title">
                        <i class="fas fa-calculator"></i>
                        ملخص الطلب
                    </h3>
                    
                    <div class="summary-row">
                        <span class="summary-label">المجموع الجزئي</span>
                        <span class="summary-value">{{ number_format($order->total_amount + $order->discount_amount, 2) }} ر.س</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                        <div class="summary-row">
                            <span class="summary-label">الخصم</span>
                            <span class="summary-value discount-value">-{{ number_format($order->discount_amount, 2) }} ر.س</span>
                        </div>
                    @endif
                    
                    <div class="summary-row">
                        <span class="summary-label">الشحن</span>
                        <span class="summary-value">مجاني</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>المجموع الكلي</span>
                        <span>{{ number_format($order->total_amount, 2) }} ر.س</span>
                    </div>
                </div>
                
                <!-- Coupon Information -->
                @if($order->hasCoupon())
                    <div class="coupon-info fade-in">
                        <div class="coupon-header">
                            <i class="fas fa-ticket-alt"></i>
                            كوبون خصم مطبق
                        </div>
                        <div class="coupon-details">
                            الكود: <span class="coupon-code">{{ $order->coupon_code }}</span><br>
                            الخصم: {{ number_format($order->coupon_discount, 2) }} ر.س
                        </div>
                    </div>
                @endif
                
                <!-- Shipping Information -->
                <div class="section-card shipping-info fade-in">
                    <h3 class="section-title">
                        <i class="fas fa-shipping-fast"></i>
                        عنوان الشحن
                    </h3>
                    
                    <div class="info-row">
                        <span class="info-label">العنوان:</span>
                        <span class="info-value">{{ $order->shipping_address }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">الهاتف:</span>
                        <span class="info-value">{{ $order->phone_number }}</span>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="section-card payment-info fade-in">
                    <h3 class="section-title">
                        <i class="fas fa-credit-card"></i>
                        معلومات الدفع
                    </h3>
                    
                    <div class="info-row">
                        <span class="info-label">طريقة الدفع:</span>
                        <span class="info-value">
                            @switch($order->payment_method)
                                @case('credit_card') بطاقة ائتمان @break
                                @case('cash') نقداً عند الاستلام @break
                                @default {{ $order->payment_method }}
                            @endswitch
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">الحالة:</span>
                        <span class="info-value">
                            @if(in_array($order->status, ['delivered']))
                                <span style="color: var(--success-600);">تم الدفع</span>
                            @elseif($order->payment_method === 'cash')
                                <span style="color: var(--warning-600);">نقداً عند الاستلام</span>
                            @else
                                <span style="color: var(--info-600);">قيد الانتظار</span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">عنوان الفاتورة:</span>
                        <span class="info-value">{{ $order->billing_address }}</span>
                    </div>
                </div>
                
                <!-- Order Actions -->
                <div class="section-card order-actions fade-in">
                    <h3 class="section-title">
                        <i class="fas fa-cog"></i>
                        إجراءات الطلب
                    </h3>
                    
                    <div class="actions-grid">
                        @if($order->status === 'delivered')
                            <a href="{{ route('testimonials.create', ['order' => $order->id]) }}" class="action-btn btn-success-outline">
                                <i class="fas fa-star"></i>
                                كتابة تقييم
                            </a>
                        @endif
                        
                        @if(in_array($order->status, ['pending', 'processing']))
                            <button class="action-btn btn-warning-outline" onclick="cancelOrder('{{ $order->id }}')">
                                <i class="fas fa-times"></i>
                                إلغاء الطلب
                            </button>
                        @endif
                        
                        <button class="action-btn btn-primary-fill" onclick="reorder('{{ $order->id }}')">
                            <i class="fas fa-redo"></i>
                            إعادة الطلب
                        </button>
                        
                        <button class="action-btn btn-secondary-outline" onclick="downloadInvoice('{{ $order->id }}')">
                            <i class="fas fa-download"></i>
                            تحميل الفاتورة
                        </button>
                        
                        <a href="{{ route('user.conversations.create') }}?order={{ $order->id }}" class="action-btn btn-secondary-outline">
                            <i class="fas fa-headset"></i>
                            التواصل مع الدعم
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Cancel order functionality
    async function cancelOrder(orderId) {
        if (!confirm('هل أنت متأكد أنك تريد إلغاء هذا الطلب؟')) {
            return;
        }
        
        try {
            const response = await fetch(`/orders/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('تم إلغاء الطلب بنجاح', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message || 'حدث خطأ أثناء إلغاء الطلب', 'error');
            }
        } catch (error) {
            showNotification('حدث خطأ أثناء إلغاء الطلب', 'error');
        }
    }
    
    // Reorder functionality
    async function reorder(orderId) {
        try {
            const response = await fetch(`/orders/${orderId}/reorder`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('تمت إضافة العناصر إلى سلة التسوق بنجاح', 'success');
                
                // Update cart count if element exists
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                }
                
                // Redirect to cart after a delay
                setTimeout(() => {
                    window.location.href = '{{ route("cart.index") }}';
                }, 2000);
            } else {
                showNotification(data.message || 'حدث خطأ أثناء إضافة العناصر إلى السلة', 'error');
            }
        } catch (error) {
            showNotification('حدث خطأ أثناء إعادة الطلب', 'error');
        }
    }
    
    // Download invoice functionality
    async function downloadInvoice(orderId) {
        try {
            const response = await fetch(`/orders/${orderId}/invoice`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `invoice-${orderId}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                showNotification('تم تحميل الفاتورة بنجاح', 'success');
            } else {
                showNotification('حدث خطأ أثناء تحميل الفاتورة', 'error');
            }
        } catch (error) {
            showNotification('حدث خطأ أثناء تحميل الفاتورة', 'error');
        }
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
    
    // Real-time order tracking
    function initOrderTracking() {
        setInterval(async () => {
            try {
                const response = await fetch(`/orders/{{ $order->id }}/status`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (data.status !== '{{ $order->status }}') {
                    showNotification(`تم تحديث حالة الطلب إلى ${data.status}`, 'info');
                    setTimeout(() => location.reload(), 2000);
                }
            } catch (error) {
                console.error('Error checking order status:', error);
            }
        }, 30000);
    }
    
    // Initialize order tracking
    initOrderTracking();
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