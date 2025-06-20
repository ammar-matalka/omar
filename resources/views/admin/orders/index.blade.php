@extends('layouts.admin')

@section('title', 'إدارة الطلبات')
@section('page-title', 'إدارة الطلبات')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        الطلبات
    </div>
@endsection

@push('styles')
<style>
    * {
        direction: rtl;
        text-align: right;
    }
    
    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: var(--space-xl);
        border-radius: var(--radius-xl);
        color: white;
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
    }
    
    .orders-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: white;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        padding: var(--space-lg);
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border-radius: var(--radius-lg);
        border: 1px solid rgba(255,255,255,0.2);
        min-width: 120px;
        transition: all var(--transition-normal);
    }
    
    .stat-item:hover {
        transform: translateY(-5px);
        background: rgba(255,255,255,0.25);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 900;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: rgba(255,255,255,0.9);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-xs);
        font-weight: 600;
    }
    
    .filters-section {
        background: white;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border: 1px solid var(--admin-secondary-200);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }
    
    .filters-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: var(--space-lg);
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        border-bottom: 3px solid var(--admin-primary-500);
        padding-bottom: var(--space-sm);
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        font-weight: 600;
        color: var(--admin-secondary-700);
    }
    
    .filter-input {
        padding: var(--space-md) var(--space-lg);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        background: #ffffff;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        transform: translateY(-2px);
    }
    
    .filter-actions {
        display: flex;
        gap: var(--space-sm);
        justify-content: flex-start;
    }
    
    .btn {
        padding: var(--space-md) var(--space-xl);
        border: none;
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600));
        color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    .orders-table-container {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-100);
    }
    
    .table-header {
        background: linear-gradient(135deg, var(--admin-secondary-50), #f1f5f9);
        padding: var(--space-xl);
        border-bottom: 2px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .export-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: var(--space-md) var(--space-xl);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
        direction: rtl;
    }
    
    .orders-table th {
        background: linear-gradient(135deg, var(--admin-secondary-50), #f1f5f9);
        padding: var(--space-lg) var(--space-xl);
        text-align: right;
        font-weight: 700;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
        border-bottom: 2px solid var(--admin-secondary-200);
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .orders-table td {
        padding: var(--space-xl);
        border-bottom: 1px solid var(--admin-secondary-100);
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    .orders-table tbody tr {
        transition: all var(--transition-fast);
    }
    
    .orders-table tbody tr:hover {
        background: linear-gradient(135deg, var(--admin-secondary-25), #f8fafc);
        transform: scale(1.01);
    }
    
    .order-id {
        font-weight: 700;
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-decoration: none;
        transition: all var(--transition-fast);
        font-size: 1rem;
    }
    
    .order-id:hover {
        transform: scale(1.05);
        text-decoration: underline;
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .customer-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .customer-details {
        display: flex;
        flex-direction: column;
    }
    
    .customer-name {
        font-weight: 700;
        color: var(--admin-secondary-900);
        line-height: 1.2;
        font-size: 0.95rem;
    }
    
    .customer-email {
        color: var(--admin-secondary-600);
        font-size: 0.8rem;
        line-height: 1.2;
    }
    
    .order-amount {
        font-weight: 800;
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1.1rem;
    }
    
    .discount-info {
        background: linear-gradient(135deg, #10b981, #059669);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 0.75rem;
        margin-top: var(--space-xs);
        font-weight: 600;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-lg);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        gap: var(--space-xs);
        backdrop-filter: blur(10px);
    }
    
    .status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
    
    .status-processing {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    
    .status-shipped {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
    }
    
    .status-delivered {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    .status-cancelled {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-lg);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .order-date {
        color: var(--admin-secondary-600);
        white-space: nowrap;
        font-weight: 500;
    }
    
    .order-actions {
        display: flex;
        gap: var(--space-sm);
        align-items: center;
    }
    
    .action-btn {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 1rem;
        text-decoration: none;
    }
    
    .action-btn.view {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .action-btn.view:hover {
        transform: scale(1.1) translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }
    
    .action-btn.delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .action-btn.delete:hover {
        transform: scale(1.1) translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
    }
    
    .pagination-wrapper {
        padding: var(--space-xl);
        background: linear-gradient(135deg, var(--admin-secondary-50), #f1f5f9);
        border-top: 2px solid var(--admin-secondary-200);
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        gap: var(--space-sm);
        align-items: center;
        background: white;
        padding: var(--space-lg);
        border-radius: var(--radius-xl);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    
    .page-link {
        padding: var(--space-md) var(--space-lg);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        color: var(--admin-secondary-600);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        min-width: 45px;
        text-align: center;
    }
    
    .page-link:hover {
        background: var(--admin-primary-50);
        border-color: var(--admin-primary-300);
        color: var(--admin-primary-600);
        transform: translateY(-2px);
    }
    
    .page-link.active {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        border-color: var(--admin-primary-600);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .page-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    
    .empty-icon {
        font-size: 5rem;
        margin-bottom: var(--space-lg);
        opacity: 0.6;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .empty-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--admin-secondary-700);
    }
    
    .empty-text {
        margin-bottom: var(--space-xl);
        font-size: 1.125rem;
        color: var(--admin-secondary-600);
    }
    
    .bulk-actions {
        display: none;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-lg);
        background: linear-gradient(135deg, var(--admin-primary-50), #e0f2fe);
        border-bottom: 2px solid var(--admin-primary-200);
    }
    
    .bulk-actions.show {
        display: flex;
    }
    
    .bulk-select {
        font-weight: 600;
        color: var(--admin-primary-700);
        font-size: 1rem;
    }
    
    .bulk-btn {
        padding: var(--space-sm) var(--space-lg);
        border: 2px solid var(--admin-primary-300);
        background: white;
        color: var(--admin-primary-600);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-fast);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .bulk-btn:hover {
        background: var(--admin-primary-600);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
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

    .alert {
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .alert-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .alert-error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .alert-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .alert-info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }
    
    /* RTL Improvements */
    .fas {
        margin-left: var(--space-xs);
        margin-right: 0;
    }
    
    .breadcrumb-item .fas {
        margin: 0 var(--space-xs);
    }
    
    /* Animation Classes */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@section('content')
<!-- Orders Header -->
<div class="orders-header">
    <div>
        <h1 class="orders-title">
            <i class="fas fa-shopping-cart"></i>
            إدارة الطلبات
        </h1>
    </div>
    
    <div class="orders-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $orders->total() }}</div>
            <div class="stat-label">إجمالي الطلبات</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $orders->where('status', 'pending')->count() }}</div>
            <div class="stat-label">في الانتظار</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $orders->where('status', 'delivered')->count() }}</div>
            <div class="stat-label">تم التسليم</div>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="filters-section fade-in">
    <h2 class="filters-title">
        <i class="fas fa-filter"></i>
        تصفية الطلبات
    </h2>
    
    <form method="GET" action="{{ route('admin.orders.index') }}">
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">رقم الطلب</label>
                <input 
                    type="text" 
                    name="order_id" 
                    class="filter-input" 
                    placeholder="البحث برقم الطلب..."
                    value="{{ request('order_id') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">العميل</label>
                <input 
                    type="text" 
                    name="customer" 
                    class="filter-input" 
                    placeholder="البحث باسم العميل..."
                    value="{{ request('customer') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">الحالة</label>
                <select name="status" class="filter-input">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">التاريخ من</label>
                <input 
                    type="date" 
                    name="date_from" 
                    class="filter-input"
                    value="{{ request('date_from') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">التاريخ إلى</label>
                <input 
                    type="date" 
                    name="date_to" 
                    class="filter-input"
                    value="{{ request('date_to') }}"
                >
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                تصفية
            </button>
            
            @if(request()->hasAny(['order_id', 'customer', 'status', 'date_from', 'date_to']))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    مسح الفلاتر
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
            قائمة الطلبات
        </h3>
        
        <button class="export-btn" onclick="exportOrders()">
            <i class="fas fa-download"></i>
            تصدير CSV
        </button>
    </div>
    
    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActions">
        <span class="bulk-select">
            <span id="selectedCount">0</span> محدد
        </span>
        
        <button class="bulk-btn" onclick="bulkUpdateStatus('processing')">
            تحويل لقيد المعالجة
        </button>
        
        <button class="bulk-btn" onclick="bulkUpdateStatus('shipped')">
            تحويل لتم الشحن
        </button>
        
        <button class="bulk-btn" onclick="bulkUpdateStatus('delivered')">
            تحويل لتم التسليم
        </button>
        
        <button class="bulk-btn" onclick="bulkDelete()" style="border-color: var(--error-300); color: var(--error-600);">
            حذف المحدد
        </button>
    </div>
    
    @if($orders->count() > 0)
        <table class="orders-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                    </th>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                    <th>الدفع</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
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
                                    خصم: -${{ number_format($order->discount_amount, 2) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ $order->status }}">
                                <i class="fas fa-{{ $order->status == 'pending' ? 'clock' : ($order->status == 'processing' ? 'cog' : ($order->status == 'shipped' ? 'shipping-fast' : ($order->status == 'delivered' ? 'check' : 'times'))) }}"></i>
                                @switch($order->status)
                                    @case('pending') في الانتظار @break
                                    @case('processing') قيد المعالجة @break
                                    @case('shipped') تم الشحن @break
                                    @case('delivered') تم التسليم @break
                                    @case('cancelled') ملغي @break
                                    @default {{ ucfirst($order->status) }}
                                @endswitch
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-info">
                                @switch($order->payment_method)
                                    @case('credit_card') بطاقة ائتمان @break
                                    @case('paypal') باي بال @break
                                    @case('cash') نقداً @break
                                    @case('bank_transfer') تحويل بنكي @break
                                    @default {{ ucfirst($order->payment_method) }}
                                @endswitch
                            </span>
                        </td>
                        <td>
                            <div class="order-date">
                                {{ $order->created_at->format('d M, Y') }}
                                <br>
                                <small style="color: var(--admin-secondary-500);">
                                    {{ $order->created_at->format('H:i') }}
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="order-actions">
                                <a href="{{ route('admin.orders.show', $order) }}" class="action-btn view" title="عرض الطلب">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <button class="action-btn delete" onclick="deleteOrder('{{ $order->id }}')" title="حذف الطلب">
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
                        <span class="page-link disabled">السابق</span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="page-link">السابق</a>
                    @endif
                    
                    @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        @if($page == $orders->currentPage())
                            <span class="page-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="page-link">التالي</a>
                    @else
                        <span class="page-link disabled">التالي</span>
                    @endif
                </div>
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 class="empty-title">لا توجد طلبات</h3>
            <p class="empty-text">لا توجد طلبات تطابق معايير البحث الحالية. جرب تعديل معايير البحث.</p>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                عرض جميع الطلبات
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
        
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = selectAll.checked;
        });
        
        updateBulkActions();
    }
    
    // Update bulk actions
    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.order-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        
        selectedOrders = Array.from(checkboxes).map(function(cb) {
            return cb.value;
        });
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
    function bulkUpdateStatus(status) {
        if (selectedOrders.length === 0) {
            showNotification('يرجى تحديد طلبات للتحديث', 'warning');
            return;
        }
        
        const statusText = {
            'processing': 'قيد المعالجة',
            'shipped': 'تم الشحن',
            'delivered': 'تم التسليم'
        };
        
        if (!confirm('هل أنت متأكد من تحديث ' + selectedOrders.length + ' طلب إلى ' + statusText[status] + '؟')) {
            return;
        }
        
        var promises = selectedOrders.map(function(orderId) {
            return fetch('/admin/orders/' + orderId, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            });
        });
        
        Promise.all(promises).then(function() {
            showNotification('تم تحديث الطلبات بنجاح', 'success');
            setTimeout(function() {
                location.reload();
            }, 1000);
        }).catch(function(error) {
            showNotification('خطأ في تحديث الطلبات', 'error');
        });
    }
    
    // Bulk delete
    function bulkDelete() {
        if (selectedOrders.length === 0) {
            showNotification('يرجى تحديد طلبات للحذف', 'warning');
            return;
        }
        
        if (!confirm('هل أنت متأكد من حذف ' + selectedOrders.length + ' طلب؟ لا يمكن التراجع عن هذا الإجراء.')) {
            return;
        }
        
        var promises = selectedOrders.map(function(orderId) {
            return fetch('/admin/orders/' + orderId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        });
        
        Promise.all(promises).then(function() {
            showNotification('تم حذف الطلبات بنجاح', 'success');
            setTimeout(function() {
                location.reload();
            }, 1000);
        }).catch(function(error) {
            showNotification('خطأ في حذف الطلبات', 'error');
        });
    }
    
    // Delete single order
    function deleteOrder(orderId) {
        if (!confirm('هل أنت متأكد من حذف هذا الطلب؟ لا يمكن التراجع عن هذا الإجراء.')) {
            return;
        }
        
        fetch('/admin/orders/' + orderId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(function(response) {
            if (response.ok) {
                showNotification('تم حذف الطلب بنجاح', 'success');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                showNotification('خطأ في حذف الطلب', 'error');
            }
        }).catch(function(error) {
            showNotification('خطأ في حذف الطلب', 'error');
        });
    }
    
    // Export orders
    function exportOrders() {
        var params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');
        
        window.location.href = '{{ route("admin.orders.index") }}?' + params.toString();
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
        notification.style.left = '20px';
        notification.style.zIndex = '9999';
        notification.style.maxWidth = '350px';
        notification.style.transform = 'translateX(-100%)';
        notification.style.transition = 'transform 0.3s ease';
        
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
        
        // Animate in
        setTimeout(function() {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Animate out
        setTimeout(function() {
            notification.style.transform = 'translateX(-100%)';
            setTimeout(function() {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Initialize animations
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
        
        // Add hover effects to cards
        document.querySelectorAll('.orders-table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
        
        // Enhanced search functionality
        const searchInputs = document.querySelectorAll('.filter-input');
        searchInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 8px 20px rgba(59, 130, 246, 0.2)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
            });
        });
        
        // Add loading state to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.type === 'submit' || this.tagName === 'A') {
                    // Add loading spinner
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحميل...';
                    this.disabled = true;
                    
                    // Restore after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                }
            });
        });
    });
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        if (selectedOrders.length === 0) {
            location.reload();
        }
    }, 30000);
    
    // Add smooth scrolling to pagination
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
    
    // Real-time order status updates (if you have websockets)
    // You can implement this with Laravel Echo + Pusher
    /*
    Echo.channel('orders')
        .listen('OrderStatusUpdated', (e) => {
            showNotification('تم تحديث حالة الطلب #' + e.order.id, 'info');
            // Update the specific row or reload page
        });
    */
</script>
@endpush