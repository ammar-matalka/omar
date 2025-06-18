@extends('layouts.app')

@section('title', __('Order Details') . ' #' . $order->id . ' - ' . config('app.name'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        text-align: center;
        margin-bottom: var(--space-2xl);
        border-radius: var(--radius-lg);
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 200px;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><rect x="20" y="30" width="60" height="40" rx="5"/><circle cx="30" cy="60" r="3"/><circle cx="50" cy="60" r="3"/><circle cx="70" cy="60" r="3"/></svg>');
        background-size: cover;
    }
    
    .header-content {
        position: relative;
        z-index: 1;
    }
    
    .header-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: var(--space-sm);
    }
    
    .header-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: var(--space-lg);
    }
    
    .order-status-large {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-lg);
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-lg);
        font-size: 1.125rem;
        font-weight: 600;
    }
    
    .order-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .order-section {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        margin-bottom: var(--space-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    
    .section-header {
        background: var(--surface-variant);
        border-bottom: 1px solid var(--border-color);
        padding: var(--space-lg);
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--on-surface);
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .section-body {
        padding: var(--space-lg);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
    }
    
    .info-label {
        color: var(--on-surface-variant);
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--space-xs);
    }
    
    .info-value {
        color: var(--on-surface);
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .info-value.large {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-600);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: var(--space-xs);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending {
        background: var(--warning-100);
        color: var(--warning-800);
    }
    
    .status-processing {
        background: var(--info-100);
        color: var(--info-800);
    }
    
    .status-completed {
        background: var(--success-100);
        color: var(--success-800);
    }
    
    .status-cancelled {
        background: var(--error-100);
        color: var(--error-800);
    }
    
    .order-timeline {
        position: relative;
        padding-left: var(--space-2xl);
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: var(--space-lg);
        border-left: 2px solid var(--border-color);
        padding-left: var(--space-lg);
    }
    
    .timeline-item:last-child {
        border-left-color: transparent;
    }
    
    .timeline-item.active {
        border-left-color: var(--primary-500);
    }
    
    .timeline-item.completed {
        border-left-color: var(--success-500);
    }
    
    .timeline-dot {
        position: absolute;
        left: -6px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--border-color);
        border: 2px solid var(--surface);
    }
    
    .timeline-item.active .timeline-dot {
        background: var(--primary-500);
    }
    
    .timeline-item.completed .timeline-dot {
        background: var(--success-500);
    }
    
    .timeline-content {
        padding-top: 0;
    }
    
    .timeline-title {
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
    }
    
    .timeline-time {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        margin-bottom: var(--space-sm);
    }
    
    .timeline-description {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
        line-height: 1.5;
    }
    
    .items-list {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
    }
    
    .order-item {
        display: flex;
        align-items: center;
        padding: var(--space-md);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
    }
    
    .item-icon {
        width: 50px;
        height: 50px;
        border-radius: var(--radius-lg);
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-600);
        font-size: 1.25rem;
        margin-right: var(--space-md);
        flex-shrink: 0;
    }
    
    .item-details {
        flex: 1;
    }
    
    .item-name {
        font-size: 1rem;
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
    }
    
    .item-meta {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
        margin-bottom: var(--space-xs);
    }
    
    .item-type {
        display: inline-flex;
        align-items: center;
        gap: var(--space-xs);
        padding: var(--space-xs) var(--space-sm);
        background: var(--primary-100);
        color: var(--primary-800);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .item-pricing {
        text-align: right;
        flex-shrink: 0;
    }
    
    .item-quantity {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        margin-bottom: var(--space-xs);
    }
    
    .item-price {
        font-size: 1rem;
        font-weight: 700;
        color: var(--primary-600);
    }
    
    .price-summary {
        background: var(--primary-50);
        border: 1px solid var(--primary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
    }
    
    .price-row {
        display: flex;
        justify-content: between;
        align-items: center;
        padding: var(--space-sm) 0;
        border-bottom: 1px solid var(--primary-200);
    }
    
    .price-row:last-child {
        border-bottom: none;
        font-weight: 700;
        font-size: 1.125rem;
        color: var(--primary-700);
        margin-top: var(--space-sm);
        padding-top: var(--space-md);
        border-top: 2px solid var(--primary-300);
    }
    
    .price-label {
        color: var(--on-surface);
    }
    
    .price-value {
        font-weight: 500;
        color: var(--on-surface);
    }
    
    .actions-section {
        display: flex;
        gap: var(--space-md);
        justify-content: center;
        margin-top: var(--space-2xl);
        padding: var(--space-xl);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
    }
    
    .notes-section {
        padding: var(--space-md);
        background: var(--surface-variant);
        border-radius: var(--radius-md);
        border-left: 4px solid var(--primary-500);
    }
    
    .admin-notes {
        padding: var(--space-md);
        background: var(--info-50);
        border-radius: var(--radius-md);
        border-left: 4px solid var(--info-500);
    }
    
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .order-item {
            flex-direction: column;
            text-align: center;
        }
        
        .item-icon {
            margin-right: 0;
            margin-bottom: var(--space-md);
        }
        
        .item-pricing {
            text-align: center;
            margin-top: var(--space-md);
        }
        
        .actions-section {
            flex-direction: column;
        }
        
        .order-timeline {
            padding-left: var(--space-lg);
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <div class="header-content">
            <h1 class="header-title">{{ __('Order') }} #{{ $order->id }}</h1>
            <p class="header-subtitle">{{ __('Placed on') }} {{ $order->created_at->format('F d, Y \a\t H:i') }}</p>
            <div class="order-status-large status-{{ $order->status }}">
                <i class="fas {{ $order->status === 'completed' ? 'fa-check-circle' : ($order->status === 'processing' ? 'fa-clock' : ($order->status === 'cancelled' ? 'fa-times-circle' : 'fa-hourglass-half')) }}"></i>
                {{ $order->status_text }}
            </div>
        </div>
    </div>
    
    <div class="order-container">
        <!-- Order Information -->
        <div class="order-section fade-in">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    {{ __('Order Information') }}
                </h2>
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">{{ __('Order ID') }}</div>
                        <div class="info-value">#{{ $order->id }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Student Name') }}</div>
                        <div class="info-value">{{ $order->student_name }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Generation') }}</div>
                        <div class="info-value">{{ $order->generation->display_name }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Order Type') }}</div>
                        <div class="info-value">{{ $order->order_type_text }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Semester') }}</div>
                        <div class="info-value">{{ $order->semester_text }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Status') }}</div>
                        <div class="info-value">
                            <span class="status-badge status-{{ $order->status }}">
                                <i class="fas {{ $order->status === 'completed' ? 'fa-check-circle' : ($order->status === 'processing' ? 'fa-clock' : ($order->status === 'cancelled' ? 'fa-times-circle' : 'fa-hourglass-half')) }}"></i>
                                {{ $order->status_text }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Total Amount') }}</div>
                        <div class="info-value large">{{ $order->formatted_total }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">{{ __('Order Date') }}</div>
                        <div class="info-value">{{ $order->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    
                    @if($order->phone)
                    <div class="info-item">
                        <div class="info-label">{{ __('Phone') }}</div>
                        <div class="info-value">{{ $order->phone }}</div>
                    </div>
                    @endif
                    
                    @if($order->address)
                    <div class="info-item">
                        <div class="info-label">{{ __('Address') }}</div>
                        <div class="info-value">{{ $order->address }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="order-section fade-in">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-shopping-cart"></i>
                    {{ __('Order Items') }}
                </h2>
            </div>
            <div class="section-body">
                <div class="items-list">
                    @foreach($order->orderItems as $item)
                        <div class="order-item">
                            <div class="item-icon">
                                <i class="fas {{ $item->item_type === 'دوسية' ? 'fa-book' : 'fa-id-card' }}"></i>
                            </div>
                            
                            <div class="item-details">
                                <div class="item-name">{{ $item->item_name }}</div>
                                <div class="item-meta">
                                    @if($item->subject_name)
                                        {{ $item->subject_name }}
                                    @endif
                                    @if($item->teacher_name)
                                        • {{ $item->teacher_name }}
                                    @endif
                                    @if($item->platform_name)
                                        • {{ $item->platform_name }}
                                    @endif
                                </div>
                                <div class="item-type">
                                    <i class="fas {{ $item->item_type === 'دوسية' ? 'fa-book' : 'fa-id-card' }}"></i>
                                    {{ $item->item_type }}
                                </div>
                            </div>
                            
                            <div class="item-pricing">
                                <div class="item-quantity">{{ __('Qty') }}: {{ $item->quantity }}</div>
                                <div class="item-price">{{ $item->formatted_subtotal }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Price Summary -->
                <div class="price-summary" style="margin-top: var(--space-xl);">
                    <div class="price-row">
                        <span class="price-label">{{ __('Quantity') }}:</span>
                        <span class="price-value">{{ $order->quantity }}</span>
                    </div>
                    
                    <div class="price-row">
                        <span class="price-label">{{ __('Total Amount') }}:</span>
                        <span class="price-value">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Timeline -->
        <div class="order-section fade-in">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-history"></i>
                    {{ __('Order Timeline') }}
                </h2>
            </div>
            <div class="section-body">
                <div class="order-timeline">
                    <div class="timeline-item completed">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">{{ __('Order Placed') }}</div>
                            <div class="timeline-time">{{ $order->created_at->format('M d, Y H:i') }}</div>
                            <div class="timeline-description">{{ __('Your order has been successfully placed and is awaiting processing.') }}</div>
                        </div>
                    </div>
                    
                    @if($order->status !== 'pending')
                    <div class="timeline-item {{ $order->status === 'processing' ? 'active' : 'completed' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">{{ __('Processing') }}</div>
                            <div class="timeline-time">{{ $order->updated_at->format('M d, Y H:i') }}</div>
                            <div class="timeline-description">{{ __('Your order is being processed and prepared.') }}</div>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->status === 'completed')
                    <div class="timeline-item completed">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">{{ __('Completed') }}</div>
                            <div class="timeline-time">{{ $order->updated_at->format('M d, Y H:i') }}</div>
                            <div class="timeline-description">{{ __('Your order has been completed successfully.') }}</div>
                        </div>
                    </div>
                    @elseif($order->status === 'cancelled')
                    <div class="timeline-item" style="border-left-color: var(--error-500);">
                        <div class="timeline-dot" style="background: var(--error-500);"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">{{ __('Cancelled') }}</div>
                            <div class="timeline-time">{{ $order->updated_at->format('M d, Y H:i') }}</div>
                            <div class="timeline-description">{{ __('This order has been cancelled.') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        @if($order->notes || $order->admin_notes)
        <div class="order-section fade-in">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-sticky-note"></i>
                    {{ __('Notes') }}
                </h2>
            </div>
            <div class="section-body">
                @if($order->notes)
                <div class="notes-section">
                    <div class="info-label">{{ __('Your Notes') }}</div>
                    <div class="info-value" style="margin-top: var(--space-sm);">{{ $order->notes }}</div>
                </div>
                @endif
                
                @if($order->admin_notes)
                <div class="admin-notes" style="{{ $order->notes ? 'margin-top: var(--space-md);' : '' }}">
                    <div class="info-label" style="color: var(--info-700);">{{ __('Admin Notes') }}</div>
                    <div class="info-value" style="margin-top: var(--space-sm); color: var(--info-800);">{{ $order->admin_notes }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Actions -->
        <div class="actions-section fade-in">
            <a href="{{ route('educational-cards.my-orders') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to My Orders') }}
            </a>
            
            @if($order->status === 'pending')
                <button class="btn btn-warning" onclick="cancelOrder()">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel Order') }}
                </button>
            @endif
            
            @if($order->status === 'completed')
                <button class="btn btn-success" onclick="downloadReceipt()">
                    <i class="fas fa-download"></i>
                    {{ __('Download Receipt') }}
                </button>
            @endif
            
            <a href="{{ route('educational-cards.index') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                {{ __('Place New Order') }}
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Cancel order function
function cancelOrder() {
    if (!confirm('{{ __('Are you sure you want to cancel this order? This action cannot be undone.') }}')) {
        return;
    }
    
    // Here you would typically make an AJAX request to cancel the order
    // For now, we'll just show a message
    alert('{{ __('Order cancellation functionality will be implemented soon.') }}');
}

// Download receipt function
function downloadReceipt() {
    // Here you would typically generate and download a PDF receipt
    alert('{{ __('Receipt download functionality will be implemented soon.') }}');
}

// Auto-refresh order status every 30 seconds for pending/processing orders
@if(in_array($order->status, ['pending', 'processing']))
setInterval(function() {
    // You can implement AJAX refresh here if needed
    console.log('Checking order status...');
}, 30000);
@endif

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Order details page loaded for order #{{ $order->id }}');
});
</script>
@endpush