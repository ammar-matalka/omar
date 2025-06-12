@extends('layouts.admin')

@section('title', __('Order Details') . ' #' . $order->id)
@section('page-title', __('Order Details') . ' #' . $order->id)

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.orders.index') }}" class="breadcrumb-link">{{ __('Orders') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Order') }} #{{ $order->id }}
    </div>
@endsection

@push('styles')
<style>
    .order-header {
        background: white;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .order-title {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--space-lg);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .order-info {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .order-id {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .order-date {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .order-actions {
        display: flex;
        gap: var(--space-sm);
        align-items: flex-start;
        flex-wrap: wrap;
    }
    
    .status-section {
        background: var(--admin-secondary-50);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
    }
    
    .status-form {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        flex-wrap: wrap;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        gap: var(--space-sm);
        margin-right: var(--space-md);
    }
    
    .status-pending {
        background: var(--warning-100);
        color: var(--warning-700);
        border: 1px solid var(--warning-200);
    }
    
    .status-processing {
        background: var(--info-100);
        color: var(--info-700);
        border: 1px solid var(--info-200);
    }
    
    .status-shipped {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        border: 1px solid var(--admin-primary-200);
    }
    
    .status-delivered {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .status-cancelled {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .order-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .order-items-section {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--admin-secondary-200);
        overflow: hidden;
    }
    
    .section-header {
        background: var(--admin-secondary-50);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-200);
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin: 0;
    }
    
    .items-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .items-table th {
        background: var(--admin-secondary-50);
        padding: var(--space-md) var(--space-lg);
        text-align: left;
        font-weight: 600;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
        border-bottom: 1px solid var(--admin-secondary-200);
    }
    
    .items-table td {
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-100);
        vertical-align: middle;
    }
    
    .item-info {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .item-image {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-md);
        object-fit: cover;
        border: 1px solid var(--admin-secondary-200);
        flex-shrink: 0;
    }
    
    .item-placeholder {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-md);
        background: var(--admin-secondary-100);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-secondary-500);
        flex-shrink: 0;
    }
    
    .item-details {
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
    }
    
    .item-name {
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin: 0;
    }
    
    .item-category {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .price-cell {
        text-align: right;
        font-weight: 600;
        color: var(--admin-secondary-900);
    }
    
    .quantity-badge {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.875rem;
        text-align: center;
        min-width: 40px;
    }
    
    .order-summary {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--admin-secondary-200);
        height: fit-content;
    }
    
    .summary-content {
        padding: var(--space-lg);
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
        padding: var(--space-lg);
        background: var(--admin-secondary-50);
        border-radius: var(--radius-lg);
    }
    
    .customer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.125rem;
        flex-shrink: 0;
    }
    
    .customer-details {
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
    }
    
    .customer-name {
        font-weight: 600;
        color: var(--admin-secondary-900);
        font-size: 1rem;
    }
    
    .customer-email {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .customer-role {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-sm) 0;
        border-bottom: 1px solid var(--admin-secondary-100);
    }
    
    .summary-row:last-child {
        border-bottom: none;
        padding-top: var(--space-md);
        border-top: 2px solid var(--admin-secondary-200);
        margin-top: var(--space-md);
    }
    
    .summary-label {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .summary-value {
        font-weight: 600;
        color: var(--admin-secondary-900);
    }
    
    .total-row .summary-label,
    .total-row .summary-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--admin-primary-600);
    }
    
    .shipping-info {
        background: var(--admin-secondary-50);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-lg);
    }
    
    .shipping-title {
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .shipping-address {
        color: var(--admin-secondary-700);
        line-height: 1.6;
    }
    
    .payment-info {
        background: var(--info-50);
        border: 1px solid var(--info-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-lg);
    }
    
    .payment-method {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        font-weight: 600;
        color: var(--info-700);
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-lg);
        border: 1px solid transparent;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
        white-space: nowrap;
    }
    
    .btn-primary {
        background: var(--admin-primary-600);
        color: white;
        box-shadow: var(--shadow-sm);
    }
    
    .btn-primary:hover {
        background: var(--admin-primary-700);
        box-shadow: var(--shadow-md);
        transform: translateY(-1px);
    }
    
    .btn-secondary {
        background: white;
        color: var(--admin-secondary-700);
        border-color: var(--admin-secondary-300);
    }
    
    .btn-secondary:hover {
        background: var(--admin-secondary-50);
        border-color: var(--admin-secondary-400);
    }
    
    .btn-success {
        background: var(--success-500);
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
    }
    
    .btn-warning {
        background: var(--warning-500);
        color: white;
    }
    
    .btn-warning:hover {
        background: #d97706;
        transform: translateY(-1px);
    }
    
    .btn-danger {
        background: var(--error-500);
        color: white;
    }
    
    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }
    
    .form-select {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        background: white;
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
        min-width: 150px;
    }
    
    .form-select:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .timeline {
        margin-top: var(--space-xl);
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--admin-secondary-200);
        overflow: hidden;
    }
    
    .timeline-item {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-100);
        position: relative;
    }
    
    .timeline-item:last-child {
        border-bottom: none;
    }
    
    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    
    .timeline-content {
        flex: 1;
    }
    
    .timeline-title {
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .timeline-time {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .icon-pending {
        background: var(--warning-100);
        color: var(--warning-600);
    }
    
    .icon-processing {
        background: var(--info-100);
        color: var(--info-600);
    }
    
    .icon-shipped {
        background: var(--admin-primary-100);
        color: var(--admin-primary-600);
    }
    
    .icon-delivered {
        background: var(--success-100);
        color: var(--success-600);
    }
    
    .icon-cancelled {
        background: var(--error-100);
        color: var(--error-600);
    }
    
    @media (max-width: 1024px) {
        .order-grid {
            grid-template-columns: 1fr;
        }
        
        .order-title {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .order-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }
    
    @media (max-width: 768px) {
        .status-form {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .customer-info {
            flex-direction: column;
            text-align: center;
        }
        
        .items-table,
        .items-table thead,
        .items-table tbody,
        .items-table th,
        .items-table td,
        .items-table tr {
            display: block;
        }
        
        .items-table thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }
        
        .items-table tr {
            border: 1px solid var(--admin-secondary-200);
            margin-bottom: var(--space-md);
            border-radius: var(--radius-md);
            padding: var(--space-md);
        }
        
        .items-table td {
            border: none;
            position: relative;
            padding: var(--space-sm) 0;
        }
        
        .items-table td:before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 45%;
            padding-right: var(--space-sm);
            white-space: nowrap;
            font-weight: 600;
            color: var(--admin-secondary-700);
        }
        
        .item-info {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-sm);
        }
    }
    
    .alert {
        padding: var(--space-md);
        border-radius: var(--radius-md);
        border: 1px solid;
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .alert-success {
        background: var(--success-100);
        color: var(--success-700);
        border-color: var(--success-200);
    }
    
    .alert-error {
        background: var(--error-100);
        color: var(--error-700);
        border-color: var(--error-200);
    }
    
    .alert-warning {
        background: var(--warning-100);
        color: var(--warning-700);
        border-color: var(--warning-200);
    }
    
    .alert-info {
        background: var(--info-100);
        color: var(--info-700);
        border-color: var(--info-200);
    }
    
    /* Animation for smooth transitions */
    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease-out;
    }
    
    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@section('content')
<!-- Order Header -->
<div class="order-header fade-in">
    <div class="order-title">
        <div class="order-info">
            <h1 class="order-id">
                <i class="fas fa-receipt"></i>
                {{ __('Order') }} #{{ $order->id }}
            </h1>
            <div class="order-date">
                <i class="fas fa-calendar-alt"></i>
                {{ __('Placed on') }} {{ $order->created_at->format('F j, Y \a\t g:i A') }}
            </div>
        </div>
        
        <div class="order-actions">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to Orders') }}
            </a>
            
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i>
                {{ __('Print Order') }}
            </button>
            
            <button class="btn btn-danger" onclick="deleteOrder('{{ $order->id }}')">
                <i class="fas fa-trash"></i>
                {{ __('Delete Order') }}
            </button>
        </div>
    </div>
</div>

<!-- Order Status Update -->
<div class="status-section fade-in">
    <h3 class="section-title" style="margin-bottom: var(--space-md);">
        <i class="fas fa-sync-alt"></i>
        {{ __('Order Status') }}
    </h3>
    
    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="status-form">
        @csrf
        @method('PUT')
        
        <span class="status-badge status-{{ $order->status }}">
            <i class="fas fa-{{ $order->status == 'pending' ? 'clock' : ($order->status == 'processing' ? 'cog' : ($order->status == 'shipped' ? 'shipping-fast' : ($order->status == 'delivered' ? 'check' : 'times'))) }}"></i>
            {{ ucfirst($order->status) }}
        </span>
        
        <select name="status" class="form-select" required>
            <option value="">{{ __('Select Status') }}</option>
            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
        </select>
        
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i>
            {{ __('Update Status') }}
        </button>
    </form>
</div>

<!-- Order Content Grid -->
<div class="order-grid">
    <!-- Order Items -->
    <div class="order-items-section fade-in">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-shopping-bag"></i>
                {{ __('Order Items') }} ({{ $order->orderItems->count() }})
            </h3>
        </div>
        
        @if($order->orderItems->count() > 0)
            <table class="items-table">
                <thead>
                    <tr>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Quantity') }}</th>
                        <th>{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td data-label="{{ __('Product') }}">
                                <div class="item-info">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="item-image">
                                    @else
                                        <div class="item-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="item-details">
                                        <h4 class="item-name">{{ $item->product_name }}</h4>
                                        @if($item->product && $item->product->category)
                                            <div class="item-category">
                                                <i class="fas fa-tag"></i>
                                                {{ $item->product->category->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td data-label="{{ __('Price') }}" class="price-cell">
                                ${{ number_format($item->price, 2) }}
                            </td>
                            <td data-label="{{ __('Quantity') }}">
                                <span class="quantity-badge">{{ $item->quantity }}</span>
                            </td>
                            <td data-label="{{ __('Total') }}" class="price-cell">
                                ${{ number_format($item->price * $item->quantity, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding: var(--space-3xl); text-align: center; color: var(--admin-secondary-500);">
                <i class="fas fa-shopping-bag" style="font-size: 3rem; margin-bottom: var(--space-lg); opacity: 0.5;"></i>
                <h3 style="margin-bottom: var(--space-sm);">{{ __('No Items Found') }}</h3>
                <p>{{ __('This order has no items.') }}</p>
            </div>
        @endif
    </div>
    
    <!-- Order Summary -->
    <div class="order-summary fade-in">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-receipt"></i>
                {{ __('Order Summary') }}
            </h3>
        </div>
        
        <div class="summary-content">
            <!-- Customer Information -->
            <div class="customer-info">
                <div class="customer-avatar">
                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                </div>
                <div class="customer-details">
                    <div class="customer-name">{{ $order->user->name }}</div>
                    <div class="customer-email">{{ $order->user->email }}</div>
                    <div class="customer-role">{{ __('Customer') }}</div>
                </div>
            </div>
            
            <!-- Order Totals -->
            <div class="summary-row">
                <span class="summary-label">{{ __('Subtotal') }}</span>
                <span class="summary-value">${{ number_format($order->total_amount + $order->discount_amount, 2) }}</span>
            </div>
            
            @if($order->discount_amount > 0)
                <div class="summary-row">
                    <span class="summary-label">{{ __('Discount') }}</span>
                    <span class="summary-value" style="color: var(--success-600);">
                        -${{ number_format($order->discount_amount, 2) }}
                    </span>
                </div>
            @endif
            
            <div class="summary-row">
                <span class="summary-label">{{ __('Shipping') }}</span>
                <span class="summary-value">{{ __('Free') }}</span>
            </div>
            
            <div class="summary-row total-row">
                <span class="summary-label">{{ __('Total') }}</span>
                <span class="summary-value">${{ number_format($order->total_amount, 2) }}</span>
            </div>
            
            <!-- Shipping Information -->
            <div class="shipping-info">
                <h4 class="shipping-title">
                    <i class="fas fa-truck"></i>
                    {{ __('Shipping Address') }}
                </h4>
                <div class="shipping-address">
                    {{ $order->shipping_address ?? __('No shipping address provided') }}
                </div>
            </div>
            
            <!-- Payment Information -->
            <div class="payment-info">
                <div class="payment-method">
                    <i class="fas fa-credit-card"></i>
                    {{ ucfirst($order->payment_method) }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Timeline -->
<div class="timeline fade-in">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-history"></i>
            {{ __('Order Timeline') }}
        </h3>
    </div>
    
    <div class="timeline-item">
        <div class="timeline-icon icon-pending">
            <i class="fas fa-plus"></i>
        </div>
        <div class="timeline-content">
            <div class="timeline-title">{{ __('Order Placed') }}</div>
            <div class="timeline-time">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</div>
        </div>
    </div>
    
    @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
        <div class="timeline-item">
            <div class="timeline-icon icon-processing">
                <i class="fas fa-cog"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-title">{{ __('Order Processing') }}</div>
                <div class="timeline-time">{{ $order->updated_at->format('F j, Y \a\t g:i A') }}</div>
            </div>
        </div>
    @endif
    
    @if(in_array($order->status, ['shipped', 'delivered']))
        <div class="timeline-item">
            <div class="timeline-icon icon-shipped">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-title">{{ __('Order Shipped') }}</div>
                <div class="timeline-time">{{ $order->updated_at->format('F j, Y \a\t g:i A') }}</div>
            </div>
        </div>
    @endif
    
    @if($order->status == 'delivered')
        <div class="timeline-item">
            <div class="timeline-icon icon-delivered">
                <i class="fas fa-check"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-title">{{ __('Order Delivered') }}</div>
                <div class="timeline-time">{{ $order->updated_at->format('F j, Y \a\t g:i A') }}</div>
            </div>
        </div>
    @endif
    
    @if($order->status == 'cancelled')
        <div class="timeline-item">
            <div class="timeline-icon icon-cancelled">
                <i class="fas fa-times"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-title">{{ __('Order Cancelled') }}</div>
                <div class="timeline-time">{{ $order->updated_at->format('F j, Y \a\t g:i A') }}</div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Delete order function
    function deleteOrder(orderId) {
        if (!confirm('{{ __("Are you sure you want to delete this order? This action cannot be undone.") }}')) {
            return;
        }
        
        fetch('/admin/orders/' + orderId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(function(response) {
            if (response.ok) {
                showNotification('{{ __("Order deleted successfully") }}', 'success');
                setTimeout(function() {
                    window.location.href = '{{ route("admin.orders.index") }}';
                }, 1000);
            } else {
                showNotification('{{ __("Error deleting order") }}', 'error');
            }
        }).catch(function(error) {
            showNotification('{{ __("Error deleting order") }}', 'error');
        });
    }
    
    // Show notification
    function showNotification(message, type) {
        if (type === undefined) {
            type = 'info';
        }
        
        var notification = document.createElement('div');
        notification.className = 'alert alert-' + type;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.maxWidth = '300px';
        notification.style.animation = 'slideInRight 0.3s ease-out';
        
        var iconClass = 'info-circle';
        if (type === 'success') {
            iconClass = 'check-circle';
        } else if (type === 'error') {
            iconClass = 'exclamation-circle';
        } else if (type === 'warning') {
            iconClass = 'exclamation-triangle';
        }
        
        notification.innerHTML = '<i class="fas fa-' + iconClass + '"></i> ' + message;
        
        document.body.appendChild(notification);
        
        setTimeout(function() {
            notification.style.animation = 'slideOutRight 0.3s ease-out forwards';
            setTimeout(function() {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Initialize fade-in animations
    document.addEventListener('DOMContentLoaded', function() {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry, index) {
                if (entry.isIntersecting) {
                    setTimeout(function() {
                        entry.target.classList.add('visible');
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        var fadeElements = document.querySelectorAll('.fade-in');
        fadeElements.forEach(function(el) {
            observer.observe(el);
        });
    });
    
    // Print functionality enhancement
    window.addEventListener('beforeprint', function() {
        // Hide non-essential elements when printing
        var hideElements = document.querySelectorAll('.order-actions, .btn');
        hideElements.forEach(function(el) {
            el.style.display = 'none';
        });
    });
    
    window.addEventListener('afterprint', function() {
        // Show elements back after printing
        var showElements = document.querySelectorAll('.order-actions, .btn');
        showElements.forEach(function(el) {
            el.style.display = '';
        });
    });
    
    // Auto-refresh order status (every 30 seconds)
    setInterval(function() {
        var currentStatus = '{{ $order->status }}';
        
        fetch('/admin/orders/{{ $order->id }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            if (data.status && data.status !== currentStatus) {
                showNotification('{{ __("Order status has been updated") }}', 'info');
                setTimeout(function() {
                    location.reload();
                }, 2000);
            }
        }).catch(function(error) {
            // Silently fail - don't show error for auto-refresh
        });
    }, 30000);
    
    // Status form enhancement
    document.querySelector('select[name="status"]').addEventListener('change', function() {
        var form = this.closest('form');
        var submitBtn = form.querySelector('button[type="submit"]');
        
        if (this.value && this.value !== '{{ $order->status }}') {
            submitBtn.style.background = 'var(--warning-500)';
            submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> {{ __("Update Status") }}';
        } else {
            submitBtn.style.background = 'var(--success-500)';
            submitBtn.innerHTML = '<i class="fas fa-save"></i> {{ __("Update Status") }}';
        }
    });
    
    // Confirm status change for critical statuses
    document.querySelector('form').addEventListener('submit', function(e) {
        var newStatus = document.querySelector('select[name="status"]').value;
        var currentStatus = '{{ $order->status }}';
        
        if (newStatus === 'cancelled' && currentStatus !== 'cancelled') {
            e.preventDefault();
            if (confirm('{{ __("Are you sure you want to cancel this order? This action may affect inventory and customer notifications.") }}')) {
                this.submit();
            }
        } else if (newStatus === 'delivered' && currentStatus !== 'delivered') {
            e.preventDefault();
            if (confirm('{{ __("Are you sure you want to mark this order as delivered? This will finalize the order.") }}')) {
                this.submit();
            }
        }
    });
</script>

<style>
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    /* Print styles */
    @media print {
        .order-actions,
        .btn,
        .sidebar,
        .admin-header,
        .status-section form {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0 !important;
        }
        
        .order-header,
        .order-items-section,
        .order-summary,
        .timeline {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        .order-grid {
            grid-template-columns: 1fr !important;
        }
        
        body {
            font-size: 12px !important;
        }
        
        .order-id {
            font-size: 24px !important;
        }
        
        .section-title {
            font-size: 18px !important;
        }
    }
    
    /* Enhanced responsive design */
    @media (max-width: 480px) {
        .order-header {
            padding: var(--space-md);
        }
        
        .order-id {
            font-size: 1.5rem;
        }
        
        .customer-info {
            padding: var(--space-md);
        }
        
        .summary-content {
            padding: var(--space-md);
        }
        
        .shipping-info,
        .payment-info {
            padding: var(--space-md);
        }
        
        .btn {
            padding: var(--space-xs) var(--space-md);
            font-size: 0.75rem;
        }
        
        .status-form {
            gap: var(--space-sm);
        }
        
        .form-select {
            min-width: 120px;
            font-size: 0.75rem;
        }
    }
    
    /* Loading states */
    .loading {
        opacity: 0.6;
        pointer-events: none;
        position: relative;
    }
    
    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid var(--admin-primary-600);
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Accessibility improvements */
    .btn:focus,
    .form-select:focus {
        outline: 2px solid var(--admin-primary-500);
        outline-offset: 2px;
    }
    
    /* High contrast mode support */
    @media (prefers-contrast: high) {
        .status-badge {
            border-width: 2px;
        }
        
        .timeline-icon {
            border: 2px solid currentColor;
        }
    }
    
    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
        .fade-in,
        .btn,
        .form-select,
        .timeline-item {
            transition: none;
        }
        
        .fade-in {
            opacity: 1;
            transform: none;
        }
    }
</style>
@endpush