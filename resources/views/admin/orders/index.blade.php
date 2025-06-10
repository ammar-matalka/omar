@extends('layouts.admin')

@section('title', __('Orders Management'))
@section('page-title', __('Orders Management'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Orders') }}
    </div>
@endsection

@push('styles')
<style>
    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .orders-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .orders-stats {
        display: flex;
        gap: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: var(--space-md);
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--admin-secondary-200);
        min-width: 100px;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-primary-600);
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-xs);
    }
    
    .filters-section {
        background: white;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .filters-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: var(--space-lg);
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .filter-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--admin-secondary-700);
    }
    
    .filter-input {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        transition: border-color var(--transition-fast);
    }
    
    .filter-input:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .filter-actions {
        display: flex;
        gap: var(--space-sm);
        justify-content: flex-end;
    }
    
    .orders-table-container {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .table-header {
        background: var(--admin-secondary-50);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .export-btn {
        background: var(--admin-primary-600);
        color: white;
        border: none;
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .export-btn:hover {
        background: var(--admin-primary-700);
        transform: translateY(-1px);
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .orders-table th {
        background: var(--admin-secondary-50);
        padding: var(--space-md) var(--space-lg);
        text-align: left;
        font-weight: 600;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
        border-bottom: 1px solid var(--admin-secondary-200);
        white-space: nowrap;
    }
    
    .orders-table td {
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-100);
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    .orders-table tbody tr {
        transition: background-color var(--transition-fast);
    }
    
    .orders-table tbody tr:hover {
        background: var(--admin-secondary-50);
    }
    
    .order-id {
        font-weight: 600;
        color: var(--admin-primary-600);
        text-decoration: none;
        transition: color var(--transition-fast);
    }
    
    .order-id:hover {
        color: var(--admin-primary-700);
        text-decoration: underline;
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .customer-avatar {
        width: 32px;
        height: 32px;
        background: var(--admin-primary-500);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
    
    .customer-details {
        display: flex;
        flex-direction: column;
    }
    
    .customer-name {
        font-weight: 600;
        color: var(--admin-secondary-900);
        line-height: 1.2;
    }
    
    .customer-email {
        color: var(--admin-secondary-600);
        font-size: 0.75rem;
        line-height: 1.2;
    }
    
    .order-amount {
        font-weight: 700;
        color: var(--admin-secondary-900);
        font-size: 1rem;
    }
    
    .discount-info {
        color: var(--success-600);
        font-size: 0.75rem;
        margin-top: var(--space-xs);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        gap: var(--space-xs);
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
    
    .order-date {
        color: var(--admin-secondary-600);
        white-space: nowrap;
    }
    
    .order-actions {
        display: flex;
        gap: var(--space-xs);
        align-items: center;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.875rem;
        text-decoration: none;
    }
    
    .action-btn.view {
        background: var(--admin-primary-100);
        color: var(--admin-primary-600);
    }
    
    .action-btn.view:hover {
        background: var(--admin-primary-200);
        transform: scale(1.1);
    }
    
    .action-btn.delete {
        background: var(--error-100);
        color: var(--error-600);
    }
    
    .action-btn.delete:hover {
        background: var(--error-200);
        transform: scale(1.1);
    }
    
    .pagination-wrapper {
        padding: var(--space-lg);
        background: var(--admin-secondary-50);
        border-top: 1px solid var(--admin-secondary-200);
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        gap: var(--space-xs);
        align-items: center;
    }
    
    .page-link {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        color: var(--admin-secondary-600);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all var(--transition-fast);
    }
    
    .page-link:hover {
        background: var(--admin-primary-50);
        border-color: var(--admin-primary-300);
        color: var(--admin-primary-600);
    }
    
    .page-link.active {
        background: var(--admin-primary-600);
        border-color: var(--admin-primary-600);
        color: white;
    }
    
    .page-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
    }
    
    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: var(--space-sm);
        color: var(--admin-secondary-700);
    }
    
    .empty-text {
        margin-bottom: var(--space-lg);
    }
    
    .bulk-actions {
        display: none;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-md);
        background: var(--admin-primary-50);
        border-bottom: 1px solid var(--admin-primary-200);
    }
    
    .bulk-actions.show {
        display: flex;
    }
    
    .bulk-select {
        font-weight: 500;
        color: var(--admin-primary-700);
    }
    
    .bulk-btn {
        padding: var(--space-xs) var(--space-md);
        border: 1px solid var(--admin-primary-300);
        background: white;
        color: var(--admin-primary-600);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .bulk-btn:hover {
        background: var(--admin-primary-600);
        color: white;
    }
    
    @media (max-width: 768px) {
        .orders-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .orders-stats {
            width: 100%;
            justify-content: space-between;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .orders-table-container {
            overflow-x: auto;
        }
        
        .orders-table {
            min-width: 800px;
        }
        
        .table-header {
            flex-direction: column;
            gap: var(--space-md);
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<!-- Orders Header -->
<div class="orders-header">
    <div>
        <h1 class="orders-title">
            <i class="fas fa-shopping-cart"></i>
            {{ __('Orders Management') }}
        </h1>
    </div>
    
    <div class="orders-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $orders->total() }}</div>
            <div class="stat-label">{{ __('Total Orders') }}            </div>
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                {{ __('Filter') }}
            </button>
            
            @if(request()->hasAny(['order_id', 'customer', 'status', 'date_from', 'date_to']))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('Clear Filters') }}
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="orders-table-container fade-in">
    <div class="table-header">
        <h3 class="table-title">
            <i class="fas fa-list"></i>
            {{ __('Orders List') }}
        </h3>
        
        <button class="export-btn" onclick="exportOrders()">
            <i class="fas fa-download"></i>
            {{ __('Export CSV') }}
        </button>
    </div>
    
    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActions">
        <span class="bulk-select">
            <span id="selectedCount">0</span> {{ __('selected') }}
        </span>
        
        <button class="bulk-btn" onclick="bulkUpdateStatus('processing')">
            {{ __('Mark as Processing') }}
        </button>
        
        <button class="bulk-btn" onclick="bulkUpdateStatus('shipped')">
            {{ __('Mark as Shipped') }}
        </button>
        
        <button class="bulk-btn" onclick="bulkUpdateStatus('delivered')">
            {{ __('Mark as Delivered') }}
        </button>
        
        <button class="bulk-btn" onclick="bulkDelete()" style="border-color: var(--error-300); color: var(--error-600);">
            {{ __('Delete Selected') }}
        </button>
    </div>
    
    @if($orders->count() > 0)
        <table class="orders-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                    </th>
                    <th>{{ __('Order ID') }}</th>
                    <th>{{ __('Customer') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Payment') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>
                            <input type="checkbox" class="order-checkbox" value="{{ $order->id }}" onchange="updateBulkActions()">
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="order-id">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-avatar">
                                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                </div>
                                <div class="customer-details">
                                    <div class="customer-name">{{ $order->user->name }}</div>
                                    <div class="customer-email">{{ $order->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="order-amount">${{ number_format($order->total_amount, 2) }}</div>
                            @if($order->discount_amount > 0)
                                <div class="discount-info">
                                    {{ __('Discount') }}: -${{ number_format($order->discount_amount, 2) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ $order->status }}">
                                <i class="fas fa-{{ $order->status == 'pending' ? 'clock' : ($order->status == 'processing' ? 'cog' : ($order->status == 'shipped' ? 'shipping-fast' : ($order->status == 'delivered' ? 'check' : 'times'))) }}"></i>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-info">
                                {{ ucfirst($order->payment_method) }}
                            </span>
                        </td>
                        <td>
                            <div class="order-date">
                                {{ $order->created_at->format('M d, Y') }}
                                <br>
                                <small style="color: var(--admin-secondary-500);">
                                    {{ $order->created_at->format('H:i') }}
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="order-actions">
                                <a href="{{ route('admin.orders.show', $order) }}" class="action-btn view" title="{{ __('View Order') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button class="action-btn delete" onclick="deleteOrder({{ $order->id }})" title="{{ __('Delete Order') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination">
                    @if($orders->onFirstPage())
                        <span class="page-link disabled">{{ __('Previous') }}</span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="page-link">{{ __('Previous') }}</a>
                    @endif
                    
                    @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        @if($page == $orders->currentPage())
                            <span class="page-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="page-link">{{ __('Next') }}</a>
                    @else
                        <span class="page-link disabled">{{ __('Next') }}</span>
                    @endif
                </div>
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 class="empty-title">{{ __('No Orders Found') }}</h3>
            <p class="empty-text">{{ __('No orders match your current filters. Try adjusting your search criteria.') }}</p>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                {{ __('View All Orders') }}
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    let selectedOrders = [];
    
    // Toggle select all
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.order-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        
        updateBulkActions();
    }
    
    // Update bulk actions
    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.order-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        
        selectedOrders = Array.from(checkboxes).map(cb => cb.value);
        selectedCount.textContent = selectedOrders.length;
        
        if (selectedOrders.length > 0) {
            bulkActions.classList.add('show');
        } else {
            bulkActions.classList.remove('show');
        }
        
        // Update "select all" checkbox
        const allCheckboxes = document.querySelectorAll('.order-checkbox');
        const selectAll = document.getElementById('selectAll');
        
        if (selectedOrders.length === allCheckboxes.length) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        } else if (selectedOrders.length > 0) {
            selectAll.checked = false;
            selectAll.indeterminate = true;
        } else {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }
    }
    
    // Bulk update status
    async function bulkUpdateStatus(status) {
        if (selectedOrders.length === 0) {
            showNotification('{{ __("Please select orders to update") }}', 'warning');
            return;
        }
        
        if (!confirm(`{{ __("Are you sure you want to update") }} ${selectedOrders.length} {{ __("orders to") }} ${status}?`)) {
            return;
        }
        
        try {
            const promises = selectedOrders.map(orderId => 
                fetch(`/admin/orders/${orderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: status })
                })
            );
            
            await Promise.all(promises);
            
            showNotification('{{ __("Orders updated successfully") }}', 'success');
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            showNotification('{{ __("Error updating orders") }}', 'error');
        }
    }
    
    // Bulk delete
    async function bulkDelete() {
        if (selectedOrders.length === 0) {
            showNotification('{{ __("Please select orders to delete") }}', 'warning');
            return;
        }
        
        if (!confirm(`{{ __("Are you sure you want to delete") }} ${selectedOrders.length} {{ __("orders? This action cannot be undone.") }}`)) {
            return;
        }
        
        try {
            const promises = selectedOrders.map(orderId => 
                fetch(`/admin/orders/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
            );
            
            await Promise.all(promises);
            
            showNotification('{{ __("Orders deleted successfully") }}', 'success');
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            showNotification('{{ __("Error deleting orders") }}', 'error');
        }
    }
    
    // Delete single order
    async function deleteOrder(orderId) {
        if (!confirm('{{ __("Are you sure you want to delete this order? This action cannot be undone.") }}')) {
            return;
        }
        
        try {
            const response = await fetch(`/admin/orders/${orderId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (response.ok) {
                showNotification('{{ __("Order deleted successfully") }}', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('{{ __("Error deleting order") }}', 'error');
            }
        } catch (error) {
            showNotification('{{ __("Error deleting order") }}', 'error');
        }
    }
    
    // Export orders
    function exportOrders() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');
        
        window.location.href = `{{ route('admin.orders.index') }}?${params.toString()}`;
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type}`;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.maxWidth = '300px';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
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
    document.addEventListener('DOMContentLoaded', function() {
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
    
    // Auto-refresh every 30 seconds
    setInterval(() => {
        if (selectedOrders.length === 0) {
            location.reload();
        }
    }, 30000);
</script>

<style>
    @keyframes slideOut {
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .order-checkbox:checked {
        accent-color: var(--admin-primary-500);
    }
    
    #selectAll:indeterminate {
        opacity: 0.5;
    }
</style>
@endpush
        <div class="stat-item">
            <div class="stat-number">{{ $orders->where('status', 'pending')->count() }}</div>
            <div class="stat-label">{{ __('Pending') }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $orders->where('status', 'delivered')->count() }}</div>
            <div class="stat-label">{{ __('Delivered') }}</div>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="filters-section fade-in">
    <h2 class="filters-title">
        <i class="fas fa-filter"></i>
        {{ __('Filter Orders') }}
    </h2>
    
    <form method="GET" action="{{ route('admin.orders.index') }}">
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">{{ __('Order ID') }}</label>
                <input 
                    type="text" 
                    name="order_id" 
                    class="filter-input" 
                    placeholder="{{ __('Search by order ID...') }}"
                    value="{{ request('order_id') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Customer') }}</label>
                <input 
                    type="text" 
                    name="customer" 
                    class="filter-input" 
                    placeholder="{{ __('Search by customer name...') }}"
                    value="{{ request('customer') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Status') }}</label>
                <select name="status" class="filter-input">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Date From') }}</label>
                <input 
                    type="date" 
                    name="date_from" 
                    class="filter-input"
                    value="{{ request('date_from') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Date To') }}</label>
                <input 
                    type="date" 
                    name="date_to" 
                    class="filter-input"
                    value="{{ request('date_to') }}"
                >