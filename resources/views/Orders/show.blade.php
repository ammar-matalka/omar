@extends('layouts.app')

@section('title', __('Order') . ' #' . $order->id . ' - ' . config('app.name'))

@push('styles')
<style>
    .order-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .order-hero::before {
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
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: var(--space-lg);
    }
    
    .order-title {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
    }
    
    .order-meta {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .order-date {
        opacity: 0.9;
        font-size: 1rem;
    }
    
    .order-status {
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-lg);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .order-amount {
        font-size: 1.5rem;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.2);
        padding: var(--space-md) var(--space-lg);
        border-radius: var(--radius-lg);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .order-container {
        padding: var(--space-3xl) 0;
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
    
    .order-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--space-2xl);
    }
    
    .order-main {
        display: flex;
        flex-direction: column;
        gap: var(--space-xl);
    }
    
    .order-sidebar {
        display: flex;
        flex-direction: column;
        gap: var(--space-xl);
    }
    
    .section-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        box-shadow: var(--shadow-sm);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: var(--space-lg);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .order-tracking {
        background: linear-gradient(135deg, var(--primary-50), var(--secondary-50));
        border: 2px solid var(--primary-200);
    }
    
    .tracking-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: var(--space-lg);
    }
    
    .tracking-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        height: 2px;
        background: var(--border-color);
        z-index: 1;
    }
    
    .tracking-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--space-sm);
        position: relative;
        z-index: 2;
        flex: 1;
        max-width: 120px;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--surface);
        border: 3px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: var(--on-surface-variant);
        transition: all var(--transition-normal);
    }
    
    .step-circle.active {
        background: var(--primary-500);
        border-color: var(--primary-500);
        color: white;
    }
    
    .step-circle.completed {
        background: var(--success-500);
        border-color: var(--success-500);
        color: white;
    }
    
    .step-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--on-surface-variant);
        text-align: center;
        line-height: 1.3;
    }
    
    .step-label.active {
        color: var(--primary-600);
        font-weight: 600;
    }
    
    .step-label.completed {
        color: var(--success-600);
        font-weight: 600;
    }
    
    .tracking-info {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        border: 1px solid var(--border-color);
    }
    
    .tracking-current {
        font-weight: 600;
        color: var(--primary-600);
        margin-bottom: var(--space-sm);
    }
    
    .tracking-details {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        line-height: 1.6;
    }
    
    .order-items-list {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .order-item {
        display: flex;
        gap: var(--space-lg);
        padding: var(--space-lg);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        transition: all var(--transition-fast);
    }
    
    .order-item:hover {
        background: var(--surface);
        border-color: var(--primary-200);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .item-image {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-lg);
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .item-placeholder {
        font-size: 2rem;
        color: var(--primary-500);
        opacity: 0.6;
    }
    
    .item-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .item-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
        line-height: 1.3;
    }
    
    .item-meta {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-sm);
        font-size: 0.875rem;
        color: var(--on-surface-variant);
    }
    
    .item-type {
        background: var(--primary-100);
        color: var(--primary-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-weight: 500;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .item-pricing {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .item-quantity {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
    }
    
    .item-price {
        font-weight: 600;
        color: var(--on-surface);
    }
    
    .item-subtotal {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-600);
    }
    
    .order-summary {
        background: linear-gradient(135deg, var(--surface), var(--surface-variant));
        border: 2px solid var(--primary-200);
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-md) 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .summary-row:last-child {
        border-bottom: none;
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-600);
        padding-top: var(--space-lg);
        margin-top: var(--space-sm);
        border-top: 2px solid var(--border-color);
    }
    
    .summary-label {
        color: var(--on-surface-variant);
        font-weight: 500;
    }
    
    .summary-value {
        font-weight: 600;
        color: var(--on-surface);
    }
    
    .discount-value {
        color: var(--success-600);
    }
    
    .shipping-info,
    .payment-info {
        background: var(--surface);
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--space-md);
        gap: var(--space-md);
    }
    
    .info-row:last-child {
        margin-bottom: 0;
    }
    
    .info-label {
        font-weight: 500;
        color: var(--on-surface-variant);
        min-width: 100px;
    }
    
    .info-value {
        color: var(--on-surface);
        text-align: right;
        line-height: 1.4;
    }
    
    .order-actions {
        background: var(--surface);
        border: 2px solid var(--border-color);
    }
    
    .actions-grid {
        display: grid;
        gap: var(--space-md);
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        border: 1px solid transparent;
        border-radius: var(--radius-lg);
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.875rem;
    }
    
    .btn-primary-fill {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        box-shadow: var(--shadow-sm);
    }
    
    .btn-primary-fill:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }
    
    .btn-success-outline {
        background: transparent;
        color: var(--success-600);
        border-color: var(--success-300);
    }
    
    .btn-success-outline:hover {
        background: var(--success-50);
        border-color: var(--success-400);
        transform: translateY(-1px);
    }
    
    .btn-warning-outline {
        background: transparent;
        color: var(--warning-600);
        border-color: var(--warning-300);
    }
    
    .btn-warning-outline:hover {
        background: var(--warning-50);
        border-color: var(--warning-400);
        transform: translateY(-1px);
    }
    
    .btn-secondary-outline {
        background: transparent;
        color: var(--on-surface-variant);
        border-color: var(--border-color);
    }
    
    .btn-secondary-outline:hover {
        background: var(--surface-variant);
        border-color: var(--border-hover);
        transform: translateY(-1px);
    }
    
    .coupon-info {
        background: linear-gradient(135deg, var(--success-50), var(--success-100));
        border: 2px solid var(--success-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .coupon-header {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-sm);
        color: var(--success-700);
        font-weight: 600;
    }
    
    .coupon-details {
        color: var(--success-600);
        font-size: 0.875rem;
        line-height: 1.4;
    }
    
    .coupon-code {
        background: var(--success-200);
        color: var(--success-800);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-family: monospace;
    }
    
    @media (max-width: 768px) {
        .order-content {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-md);
        }
        
        .order-title {
            font-size: 1.5rem;
        }
        
        .order-meta {
            width: 100%;
            justify-content: space-between;
        }
        
        .tracking-steps {
            flex-wrap: wrap;
            gap: var(--space-md);
        }
        
        .tracking-step {
            max-width: none;
            flex: 1;
            min-width: 80px;
        }
        
        .order-item {
            flex-direction: column;
            text-align: center;
        }
        
        .item-details {
            align-items: center;
        }
        
        .item-pricing {
            justify-content: center;
            gap: var(--space-lg);
        }
        
        .breadcrumb {
            justify-content: center;
        }
        
        .info-row {
            flex-direction: column;
            gap: var(--space-xs);
        }
        
        .info-value {
            text-align: left;
        }
    }
</style>
@endpush

@section('content')
<!-- Order Hero -->
<section class="order-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    {{ __('Home') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('orders.index') }}" class="breadcrumb-link">
                    {{ __('My Orders') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ __('Order') }} #{{ $order->id }}</span>
            </nav>
            
            <!-- Order Header -->
            <div class="order-header">
                <div>
                    <h1 class="order-title">{{ __('Order') }} #{{ $order->id }}</h1>
                    <div class="order-meta">
                        <span class="order-date">{{ $order->created_at->format('M d, Y H:i') }}</span>
                        <span class="order-status">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
                
                <div class="order-amount">${{ number_format($order->total_amount, 2) }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Order Container -->
<section class="order-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('orders.index') }}" class="back-button fade-in">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Orders') }}
        </a>
        
        <!-- Order Content -->
        <div class="order-content">
            <!-- Main Content -->
            <div class="order-main">
                <!-- Order Tracking -->
                <div class="section-card order-tracking fade-in">
                    <h2 class="section-title">
                        <i class="fas fa-truck"></i>
                        {{ __('Order Tracking') }}
                    </h2>
                    
                    <div class="tracking-steps">
                        <div class="tracking-step">
                            <div class="step-circle {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="step-label {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                {{ __('Order Placed') }}
                            </div>
                        </div>
                        
                        <div class="tracking-step">
                            <div class="step-circle {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : ($order->status === 'pending' ? 'active' : '') }}">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="step-label {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : ($order->status === 'pending' ? 'active' : '') }}">
                                {{ __('Processing') }}
                            </div>
                        </div>
                        
                        <div class="tracking-step">
                            <div class="step-circle {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : ($order->status === 'processing' ? 'active' : '') }}">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="step-label {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : ($order->status === 'processing' ? 'active' : '') }}">
                                {{ __('Shipped') }}
                            </div>
                        </div>
                        
                        <div class="tracking-step">
                            <div class="step-circle {{ $order->status === 'delivered' ? 'completed' : ($order->status === 'shipped' ? 'active' : '') }}">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="step-label {{ $order->status === 'delivered' ? 'completed' : ($order->status === 'shipped' ? 'active' : '') }}">
                                {{ __('Delivered') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="tracking-info">
                        <div class="tracking-current">
                            @switch($order->status)
                                @case('pending')
                                    {{ __('Your order has been placed and is waiting for confirmation.') }}
                                    @break
                                @case('processing')
                                    {{ __('Your order is being processed and prepared for shipping.') }}
                                    @break
                                @case('shipped')
                                    {{ __('Your order has been shipped and is on its way to you.') }}
                                    @break
                                @case('delivered')
                                    {{ __('Your order has been delivered successfully.') }}
                                    @break
                                @case('cancelled')
                                    {{ __('This order has been cancelled.') }}
                                    @break
                                @default
                                    {{ __('Order status: ') . ucfirst($order->status) }}
                            @endswitch
                        </div>
                        <div class="tracking-details">
                            {{ __('Last updated: ') . $order->updated_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="section-card fade-in">
                    <h2 class="section-title">
                        <i class="fas fa-box"></i>
                        {{ __('Order Items') }} ({{ $order->orderItems->sum('quantity') }})
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
                                        <span class="item-type">{{ ucfirst(str_replace('_', ' ', $item->type)) }}</span>
                                        @if($item->item)
                                            <span>{{ __('SKU: ') . $item->item->id }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="item-pricing">
                                        <div>
                                            <span class="item-quantity">{{ __('Quantity: ') . $item->quantity }}</span>
                                            <span class="item-price"> × ${{ number_format($item->price, 2) }}</span>
                                        </div>
                                        <div class="item-subtotal">${{ number_format($item->quantity * $item->price, 2) }}</div>
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
                        {{ __('Order Summary') }}
                    </h3>
                    
                    <div class="summary-row">
                        <span class="summary-label">{{ __('Subtotal') }}</span>
                        <span class="summary-value">${{ number_format($order->total_amount + $order->discount_amount, 2) }}</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                        <div class="summary-row">
                            <span class="summary-label">{{ __('Discount') }}</span>
                            <span class="summary-value discount-value">-${{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    
                    <div class="summary-row">
                        <span class="summary-label">{{ __('Shipping') }}</span>
                        <span class="summary-value">{{ __('Free') }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>{{ __('Total') }}</span>
                        <span>${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
                
                <!-- Coupon Information -->
                @if($order->hasCoupon())
                    <div class="coupon-info fade-in">
                        <div class="coupon-header">
                            <i class="fas fa-ticket-alt"></i>
                            {{ __('Coupon Applied') }}
                        </div>
                        <div class="coupon-details">
                            {{ __('Code: ') }}<span class="coupon-code">{{ $order->coupon_code }}</span><br>
                            {{ __('Discount: ) . number_format($order->coupon_discount, 2) }}
                        </div>
                    </div>
                @endif
                
                <!-- Shipping Information -->
                <div class="section-card shipping-info fade-in">
                    <h3 class="section-title">
                        <i class="fas fa-shipping-fast"></i>
                        {{ __('Shipping Address') }}
                    </h3>
                    
                    <div class="info-row">
                        <span class="info-label">{{ __('Address:') }}</span>
                        <span class="info-value">{{ $order->shipping_address }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">{{ __('Phone:') }}</span>
                        <span class="info-value">{{ $order->phone_number }}</span>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="section-card payment-info fade-in">
                    <h3 class="section-title">
                        <i class="fas fa-credit-card"></i>
                        {{ __('Payment Information') }}
                    </h3>
                    
                    <div class="info-row">
                        <span class="info-label">{{ __('Method:') }}</span>
                        <span class="info-value">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">{{ __('Status:') }}</span>
                        <span class="info-value">
                            @if(in_array($order->status, ['delivered']))
                                <span style="color: var(--success-600);">{{ __('Paid') }}</span>
                            @elseif($order->payment_method === 'cash')
                                <span style="color: var(--warning-600);">{{ __('Cash on Delivery') }}</span>
                            @else
                                <span style="color: var(--info-600);">{{ __('Pending') }}</span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">{{ __('Billing:') }}</span>
                        <span class="info-value">{{ $order->billing_address }}</span>
                    </div>
                </div>
                
                <!-- Order Actions -->
                <div class="section-card order-actions fade-in">
                    <h3 class="section-title">
                        <i class="fas fa-cog"></i>
                        {{ __('Order Actions') }}
                    </h3>
                    
                    <div class="actions-grid">
                        @if($order->status === 'delivered')
                            <a href="{{ route('testimonials.create', ['order' => $order->id]) }}" class="action-btn btn-success-outline">
                                <i class="fas fa-star"></i>
                                {{ __('Write Review') }}
                            </a>
                        @endif
                        
                        @if(in_array($order->status, ['pending', 'processing']))
                            <button class="action-btn btn-warning-outline" onclick="cancelOrder('{{ $order->id }}')">
                                <i class="fas fa-times"></i>
                                {{ __('Cancel Order') }}
                            </button>
                        @endif
                        
                        <button class="action-btn btn-primary-fill" onclick="reorder('{{ $order->id }}')">
                            <i class="fas fa-redo"></i>
                            {{ __('Reorder') }}
                        </button>
                        
                        <button class="action-btn btn-secondary-outline" onclick="downloadInvoice('{{ $order->id }}')">
                            <i class="fas fa-download"></i>
                            {{ __('Download Invoice') }}
                        </button>
                        
                        <a href="{{ route('user.conversations.create') }}?order={{ $order->id }}" class="action-btn btn-secondary-outline">
                            <i class="fas fa-headset"></i>
                            {{ __('Contact Support') }}
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
    
    // Cancel order functionality
    async function cancelOrder(orderId) {
        if (!confirm('{{ __("Are you sure you want to cancel this order?") }}')) {
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
                showNotification('{{ __("Order cancelled successfully") }}', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message || '{{ __("Error cancelling order") }}', 'error');
            }
        } catch (error) {
            showNotification('{{ __("Error cancelling order") }}', 'error');
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
                showNotification('{{ __("Items added to cart successfully") }}', 'success');
                
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
                showNotification(data.message || '{{ __("Error adding items to cart") }}', 'error');
            }
        } catch (error) {
            showNotification('{{ __("Error reordering") }}', 'error');
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
                
                showNotification('{{ __("Invoice downloaded successfully") }}', 'success');
            } else {
                showNotification('{{ __("Error downloading invoice") }}', 'error');
            }
        } catch (error) {
            showNotification('{{ __("Error downloading invoice") }}', 'error');
        }
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
    
    // Real-time order tracking (WebSocket or polling)
    function initOrderTracking() {
        // Check for order status updates every 30 seconds
        setInterval(async () => {
            try {
                const response = await fetch(`/orders/{{ $order->id }}/status`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (data.status !== '{{ $order->status }}') {
                    showNotification(`{{ __("Order status updated to") }} ${data.status}`, 'info');
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