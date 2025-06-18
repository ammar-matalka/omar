@extends('layouts.app')

@section('title', __('My Educational Orders') . ' - ' . config('app.name'))

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        text-align: center;
        margin-bottom: var(--space-2xl);
        border-radius: var(--radius-lg);
    }
    
    .header-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: var(--space-sm);
    }
    
    .header-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }
    
    .orders-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .filters-section {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        box-shadow: var(--shadow-sm);
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        align-items: end;
    }
    
    .form-group {
        margin-bottom: 0;
    }
    
    .form-label {
        display: block;
        margin-bottom: var(--space-sm);
        font-weight: 500;
        color: var(--on-surface);
        font-size: 0.875rem;
    }
    
    .form-input {
        width: 100%;
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--focus-ring);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .orders-list {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .order-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-normal);
    }
    
    .order-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }
    
    .order-header {
        background: var(--surface-variant);
        border-bottom: 1px solid var(--border-color);
        padding: var(--space-lg);
        display: flex;
        justify-content: between;
        align-items: center;
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .order-info {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .order-id {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-600);
    }
    
    .order-date {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .order-status {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
    }
    
    .status-badge {
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: var(--space-xs);
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
    
    .order-total {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-600);
    }
    
    .order-body {
        padding: var(--space-lg);
    }
    
    .order-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
    }
    
    .detail-label {
        color: var(--on-surface-variant);
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--space-xs);
    }
    
    .detail-value {
        color: var(--on-surface);
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .order-items {
        border-top: 1px solid var(--border-color);
        padding-top: var(--space-lg);
    }
    
    .items-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: var(--space-md);
        color: var(--on-surface);
    }
    
    .item-list {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .order-item {
        display: flex;
        justify-content: between;
        align-items: center;
        padding: var(--space-sm);
        background: var(--surface-variant);
        border-radius: var(--radius-md);
    }
    
    .item-info {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .item-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-600);
        font-size: 1rem;
    }
    
    .item-details {
        display: flex;
        flex-direction: column;
    }
    
    .item-name {
        font-weight: 500;
        color: var(--on-surface);
        font-size: 0.875rem;
    }
    
    .item-meta {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
    }
    
    .item-pricing {
        text-align: right;
    }
    
    .item-quantity {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        margin-bottom: var(--space-xs);
    }
    
    .item-price {
        font-weight: 600;
        color: var(--primary-600);
        font-size: 0.875rem;
    }
    
    .order-actions {
        border-top: 1px solid var(--border-color);
        padding: var(--space-md) var(--space-lg);
        background: var(--surface-variant);
        display: flex;
        gap: var(--space-md);
        justify-content: flex-end;
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--on-surface-variant);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: var(--space-md);
        color: var(--on-surface);
    }
    
    .empty-message {
        margin-bottom: var(--space-xl);
        line-height: 1.6;
    }
    
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-xl);
    }
    
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        text-align: center;
        box-shadow: var(--shadow-sm);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 900;
        color: var(--primary-600);
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: var(--space-2xl);
    }
    
    @media (max-width: 768px) {
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .order-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .order-info {
            justify-content: space-between;
        }
        
        .order-details {
            grid-template-columns: 1fr;
        }
        
        .order-item {
            flex-direction: column;
            align-items: stretch;
            gap: var(--space-sm);
        }
        
        .item-pricing {
            text-align: left;
        }
        
        .stats-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1 class="header-title">{{ __('My Educational Orders') }}</h1>
        <p class="header-subtitle">{{ __('Track your educational cards and dossiers orders') }}</p>
    </div>
    
    <div class="orders-container">
        <!-- Statistics -->
        @if($orders->count() > 0)
        <div class="stats-cards fade-in">
            <div class="stat-card">
                <div class="stat-number">{{ $orders->total() }}</div>
                <div class="stat-label">{{ __('Total Orders') }}</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $orders->where('status', 'completed')->count() }}</div>
                <div class="stat-label">{{ __('Completed') }}</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $orders->where('status', 'pending')->count() + $orders->where('status', 'processing')->count() }}</div>
                <div class="stat-label">{{ __('In Progress') }}</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ number_format($orders->where('status', 'completed')->sum('total_amount'), 0) }} JD</div>
                <div class="stat-label">{{ __('Total Spent') }}</div>
            </div>
        </div>
        @endif
        
        <!-- Filters -->
        <div class="filters-section fade-in">
            <form method="GET" action="{{ route('educational-cards.my-orders') }}" id="filtersForm">
                <div class="filters-grid">
                    <div class="form-group">
                        <label class="form-label">{{ __('Search') }}</label>
                        <input type="text" name="search" class="form-input" 
                               placeholder="{{ __('Search orders...') }}" 
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select name="status" class="form-input">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('Order Type') }}</label>
                        <select name="order_type" class="form-input">
                            <option value="">{{ __('All Types') }}</option>
                            <option value="card" {{ request('order_type') === 'card' ? 'selected' : '' }}>{{ __('Educational Card') }}</option>
                            <option value="dossier" {{ request('order_type') === 'dossier' ? 'selected' : '' }}>{{ __('Dossier') }}</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('educational-cards.my-orders') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            {{ __('Clear') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Orders List -->
        @if($orders->count() > 0)
            <div class="orders-list fade-in">
                @foreach($orders as $order)
                    <div class="order-card">
                        <!-- Order Header -->
                        <div class="order-header">
                            <div class="order-info">
                                <div class="order-id">#{{ $order->id }}</div>
                                <div class="order-date">
                                    <i class="fas fa-calendar"></i>
                                    {{ $order->created_at->format('M d, Y H:i') }}
                                </div>
                            </div>
                            
                            <div class="order-status">
                                <span class="status-badge status-{{ $order->status }}">
                                    <i class="fas {{ $order->status === 'completed' ? 'fa-check-circle' : ($order->status === 'processing' ? 'fa-clock' : ($order->status === 'cancelled' ? 'fa-times-circle' : 'fa-hourglass-half')) }}"></i>
                                    {{ $order->status_text }}
                                </span>
                                <div class="order-total">{{ $order->formatted_total }}</div>
                            </div>
                        </div>
                        
                        <!-- Order Body -->
                        <div class="order-body">
                            <!-- Order Details -->
                            <div class="order-details">
                                <div class="detail-item">
                                    <div class="detail-label">{{ __('Student Name') }}</div>
                                    <div class="detail-value">{{ $order->student_name }}</div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="detail-label">{{ __('Generation') }}</div>
                                    <div class="detail-value">{{ $order->generation->display_name }}</div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="detail-label">{{ __('Order Type') }}</div>
                                    <div class="detail-value">{{ $order->order_type_text }}</div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="detail-label">{{ __('Semester') }}</div>
                                    <div class="detail-value">{{ $order->semester_text }}</div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="detail-label">{{ __('Quantity') }}</div>
                                    <div class="detail-value">{{ $order->quantity }}</div>
                                </div>
                                
                                @if($order->phone)
                                <div class="detail-item">
                                    <div class="detail-label">{{ __('Phone') }}</div>
                                    <div class="detail-value">{{ $order->phone }}</div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Order Items -->
                            @if($order->orderItems->count() > 0)
                            <div class="order-items">
                                <div class="items-title">{{ __('Items') }}</div>
                                <div class="item-list">
                                    @foreach($order->orderItems as $item)
                                        <div class="order-item">
                                            <div class="item-info">
                                                <div class="item-icon">
                                                    <i class="fas {{ $item->item_type === 'دوسية' ? 'fa-book' : 'fa-id-card' }}"></i>
                                                </div>
                                                <div class="item-details">
                                                    <div class="item-name">{{ $item->item_name }}</div>
                                                    <div class="item-meta">
                                                        {{ $item->item_type }}
                                                        @if($item->teacher_name)
                                                            • {{ $item->teacher_name }}
                                                        @endif
                                                        @if($item->platform_name)
                                                            • {{ $item->platform_name }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="item-pricing">
                                                <div class="item-quantity">{{ __('Qty') }}: {{ $item->quantity }}</div>
                                                <div class="item-price">{{ $item->formatted_subtotal }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            @if($order->notes)
                            <div style="margin-top: var(--space-lg); padding-top: var(--space-lg); border-top: 1px solid var(--border-color);">
                                <div class="detail-label">{{ __('Notes') }}</div>
                                <div class="detail-value" style="margin-top: var(--space-sm);">{{ $order->notes }}</div>
                            </div>
                            @endif
                            
                            @if($order->admin_notes)
                            <div style="margin-top: var(--space-lg); padding: var(--space-md); background: var(--info-50); border-radius: var(--radius-md); border-left: 4px solid var(--info-500);">
                                <div class="detail-label" style="color: var(--info-700);">{{ __('Admin Notes') }}</div>
                                <div class="detail-value" style="margin-top: var(--space-sm); color: var(--info-800);">{{ $order->admin_notes }}</div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Order Actions -->
                        <div class="order-actions">
                            <a href="{{ route('educational-cards.show-order', $order) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i>
                                {{ __('View Details') }}
                            </a>
                            
                            @if($order->status === 'pending')
                                <button class="btn btn-warning btn-sm" onclick="cancelOrder({{ $order->id }})">
                                    <i class="fas fa-times"></i>
                                    {{ __('Cancel Order') }}
                                </button>
                            @endif
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
                <i class="fas fa-shopping-cart empty-icon"></i>
                <h3 class="empty-title">
                    @if(request()->hasAny(['search', 'status', 'order_type']))
                        {{ __('No Orders Found') }}
                    @else
                        {{ __('No Orders Yet') }}
                    @endif
                </h3>
                <p class="empty-message">
                    @if(request()->hasAny(['search', 'status', 'order_type']))
                        {{ __('Try adjusting your filters to find what you\'re looking for.') }}
                    @else
                        {{ __('You haven\'t placed any educational orders yet. Start by exploring our educational system.') }}
                    @endif
                </p>
                
                @if(request()->hasAny(['search', 'status', 'order_type']))
                    <a href="{{ route('educational-cards.my-orders') }}" class="btn btn-secondary" style="margin-right: var(--space-md);">
                        <i class="fas fa-times"></i>
                        {{ __('Clear Filters') }}
                    </a>
                @endif
                
                <a href="{{ route('educational-cards.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Place Your First Order') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit filters on change (except search)
document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', function() {
        document.getElementById('filtersForm').submit();
    });
});

// Cancel order function
function cancelOrder(orderId) {
    if (!confirm('{{ __('Are you sure you want to cancel this order?') }}')) {
        return;
    }
    
    // Here you would typically make an AJAX request to cancel the order
    // For now, we'll just show a message
    alert('{{ __('Order cancellation functionality will be implemented soon.') }}');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('My orders page loaded');
});
</script>
@endpush