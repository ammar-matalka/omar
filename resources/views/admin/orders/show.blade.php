@extends('layouts.admin')

@section('title', 'تفاصيل الطلب #' . $order->id)
@section('page-title', 'تفاصيل الطلب #' . $order->id)

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        <a href="{{ route('admin.orders.index') }}" class="breadcrumb-link">الطلبات</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        طلب #{{ $order->id }}
    </div>
@endsection

@push('styles')
<style>
    :root {
        /* Modern Color Palette */
        --primary-50: #eff6ff;
        --primary-100: #dbeafe;
        --primary-200: #bfdbfe;
        --primary-300: #93c5fd;
        --primary-400: #60a5fa;
        --primary-500: #3b82f6;
        --primary-600: #2563eb;
        --primary-700: #1d4ed8;
        --primary-800: #1e40af;
        --primary-900: #1e3a8a;
        
        --admin-secondary-25: #fafafa;
        --admin-secondary-50: #f8fafc;
        --admin-secondary-100: #f1f5f9;
        --admin-secondary-200: #e2e8f0;
        --admin-secondary-300: #cbd5e1;
        --admin-secondary-400: #94a3b8;
        --admin-secondary-500: #64748b;
        --admin-secondary-600: #475569;
        --admin-secondary-700: #334155;
        --admin-secondary-800: #1e293b;
        --admin-secondary-900: #0f172a;
        
        --admin-primary-500: var(--primary-500);
        --admin-primary-600: var(--primary-600);
        --admin-primary-700: var(--primary-700);
        
        --success-500: #10b981;
        --success-600: #059669;
        --warning-500: #f59e0b;
        --warning-600: #d97706;
        --error-500: #ef4444;
        --error-600: #dc2626;
        --purple-500: #8b5cf6;
        --purple-600: #7c3aed;
        
        /* Spacing System */
        --space-xs: 0.25rem;
        --space-sm: 0.5rem;
        --space-md: 0.75rem;
        --space-lg: 1rem;
        --space-xl: 1.5rem;
        --space-2xl: 2rem;
        --space-3xl: 3rem;
        
        /* Border Radius */
        --radius-sm: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
        --radius-2xl: 1.5rem;
        
        /* Transitions */
        --transition-fast: all 0.15s ease;
        --transition-normal: all 0.3s ease;
        --transition-slow: all 0.5s ease;
        
        /* Shadows */
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    * {
        direction: rtl;
        text-align: right;
    }
    
    body {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
    }

    /* Order Header */
    .order-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: var(--radius-2xl);
        padding: var(--space-2xl);
        margin-bottom: var(--space-2xl);
        color: white;
        box-shadow: var(--shadow-2xl);
        position: relative;
        overflow: hidden;
    }

    .order-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        z-index: 0;
    }

    .order-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: var(--space-xl);
    }

    .order-title-section {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
    }

    .order-title {
        font-size: clamp(1.5rem, 4vw, 2.5rem);
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin: 0;
    }

    .order-title i {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .order-meta {
        display: flex;
        gap: var(--space-lg);
        flex-wrap: wrap;
        font-size: 1rem;
        opacity: 0.9;
    }

    .order-actions {
        display: flex;
        gap: var(--space-md);
        flex-wrap: wrap;
    }

    .btn {
        padding: var(--space-lg) var(--space-2xl);
        border: none;
        border-radius: var(--radius-xl);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-fast);
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: var(--transition-normal);
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
    }

    .btn-primary:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(16, 185, 129, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--warning-500), var(--warning-600));
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .btn-warning:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(245, 158, 11, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--error-500), var(--error-600));
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .btn-danger:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(239, 68, 68, 0.4);
    }

    /* Main Content Grid */
    .order-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--space-2xl);
        margin-bottom: var(--space-2xl);
    }

    /* Order Details Card */
    .order-details-card {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-100);
    }

    .card-header {
        background: linear-gradient(135deg, var(--admin-secondary-50), #f1f5f9);
        padding: var(--space-2xl);
        border-bottom: 2px solid var(--admin-secondary-200);
        position: relative;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--purple-500));
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin: 0;
    }

    .card-body {
        padding: var(--space-2xl);
    }

    /* Order Items Table */
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: var(--space-xl);
    }

    .items-table th {
        background: var(--admin-secondary-50);
        padding: var(--space-lg);
        text-align: right;
        font-weight: 700;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
        border-bottom: 2px solid var(--admin-secondary-200);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .items-table td {
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-100);
        vertical-align: middle;
    }

    .items-table tbody tr:hover {
        background: var(--admin-secondary-25);
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }

    .product-image {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: var(--shadow-md);
    }

    .product-details h4 {
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin: 0 0 var(--space-xs) 0;
        font-size: 0.95rem;
    }

    .product-details p {
        color: var(--admin-secondary-600);
        font-size: 0.8rem;
        margin: 0;
    }

    .price-cell {
        font-weight: 700;
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1rem;
    }

    /* Order Summary */
    .order-summary {
        background: var(--admin-secondary-50);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-top: var(--space-xl);
        border: 2px solid var(--admin-secondary-200);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-md) 0;
        border-bottom: 1px solid var(--admin-secondary-200);
    }

    .summary-row:last-child {
        border-bottom: none;
        font-weight: 700;
        font-size: 1.1rem;
        padding-top: var(--space-lg);
        margin-top: var(--space-lg);
        border-top: 2px solid var(--admin-secondary-300);
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Customer Info Card */
    .customer-card {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-100);
        margin-bottom: var(--space-xl);
    }

    .customer-profile {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        margin-bottom: var(--space-xl);
    }

    .customer-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 2rem;
        flex-shrink: 0;
        box-shadow: var(--shadow-lg);
    }

    .customer-details h3 {
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin: 0 0 var(--space-sm) 0;
        font-size: 1.25rem;
    }

    .customer-details p {
        color: var(--admin-secondary-600);
        margin: 0;
        font-size: 0.95rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-lg) 0;
        border-bottom: 1px solid var(--admin-secondary-100);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: var(--admin-secondary-600);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }

    .info-value {
        font-weight: 500;
        color: var(--admin-secondary-900);
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: var(--space-md) var(--space-xl);
        border-radius: var(--radius-xl);
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        gap: var(--space-sm);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .status-pending {
        background: linear-gradient(135deg, var(--warning-500), var(--warning-600));
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .status-processing {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .status-shipped {
        background: linear-gradient(135deg, var(--purple-500), var(--purple-600));
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .status-delivered {
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .status-cancelled {
        background: linear-gradient(135deg, var(--error-500), var(--error-600));
        color: white;
        box-shadow: var(--shadow-lg);
    }

    /* Payment Info */
    .payment-card {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-100);
        margin-bottom: var(--space-xl);
    }

    .payment-method {
        display: inline-flex;
        align-items: center;
        padding: var(--space-md) var(--space-xl);
        border-radius: var(--radius-xl);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        box-shadow: var(--shadow-md);
        gap: var(--space-sm);
    }

    /* Shipping Info */
    .shipping-card {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-100);
    }

    .address-box {
        background: var(--admin-secondary-25);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        margin-top: var(--space-lg);
        border: 1px solid var(--admin-secondary-200);
    }

    /* Order Timeline */
    .timeline-card {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-100);
        margin-top: var(--space-xl);
    }

    .timeline {
        position: relative;
        padding: var(--space-xl);
    }

    .timeline-item {
        position: relative;
        padding-left: var(--space-3xl);
        margin-bottom: var(--space-xl);
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 12px;
        top: 0;
        bottom: -var(--space-xl);
        width: 2px;
        background: var(--admin-secondary-200);
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        color: white;
        font-weight: 700;
        box-shadow: var(--shadow-md);
    }

    .timeline-marker.completed {
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
    }

    .timeline-marker.current {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        animation: pulse 2s infinite;
    }

    .timeline-marker.pending {
        background: var(--admin-secondary-300);
    }

    .timeline-content h4 {
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin: 0 0 var(--space-sm) 0;
        font-size: 1rem;
    }

    .timeline-content p {
        color: var(--admin-secondary-600);
        margin: 0;
        font-size: 0.875rem;
    }

    .timeline-date {
        color: var(--admin-secondary-500);
        font-size: 0.8rem;
        margin-top: var(--space-xs);
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

    /* Responsive Design */
    @media (max-width: 1024px) {
        .order-content {
            grid-template-columns: 1fr;
            gap: var(--space-xl);
        }
    }

    @media (max-width: 768px) {
        .order-header {
            padding: var(--space-xl);
        }
        
        .order-header-content {
            flex-direction: column;
            text-align: center;
            gap: var(--space-lg);
        }
        
        .order-actions {
            width: 100%;
            justify-content: center;
        }
        
        .btn {
            flex: 1;
            justify-content: center;
            min-width: 0;
        }
        
        .customer-profile {
            flex-direction: column;
            text-align: center;
        }
        
        .customer-avatar {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .items-table {
            font-size: 0.8rem;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            font-size: 1rem;
        }
        
        .timeline-item {
            padding-left: var(--space-2xl);
        }
    }

    @media (max-width: 480px) {
        .order-header {
            padding: var(--space-lg);
            margin: 0 -var(--space-lg) var(--space-xl) -var(--space-lg);
            border-radius: var(--radius-xl);
        }
        
        .order-meta {
            flex-direction: column;
            gap: var(--space-sm);
            text-align: center;
        }
        
        .card-header,
        .card-body {
            padding: var(--space-xl);
        }
        
        .order-details-card,
        .customer-card,
        .payment-card,
        .shipping-card,
        .timeline-card {
            margin: 0 -var(--space-lg) var(--space-xl) -var(--space-lg);
            border-radius: var(--radius-xl);
        }
        
        .items-table th,
        .items-table td {
            padding: var(--space-md);
        }
        
        .product-info {
            flex-direction: column;
            text-align: center;
            gap: var(--space-sm);
        }
        
        .info-row {
            flex-direction: column;
            gap: var(--space-sm);
            text-align: center;
        }
    }

    /* Print Styles */
    @media print {
        .order-actions,
        .btn {
            display: none !important;
        }
        
        .order-header {
            background: none !important;
            color: #000 !important;
            box-shadow: none !important;
        }
        
        .order-details-card,
        .customer-card,
        .payment-card,
        .shipping-card {
            box-shadow: none !important;
            border: 1px solid #000;
        }
    }
</style>
@endpush

@section('content')
<!-- Order Header -->
<div class="order-header">
    <div class="order-header-content">
        <div class="order-title-section">
            <h1 class="order-title">
                <i class="fas fa-receipt"></i>
                طلب #{{ $order->id }}
            </h1>
            
            <div class="order-meta">
                <span><i class="fas fa-calendar"></i> {{ $order->created_at->format('d M, Y H:i') }}</span>
                <span><i class="fas fa-user"></i> {{ $order->user->name }}</span>
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
            </div>
        </div>
        
        <div class="order-actions">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-right"></i>
                العودة للطلبات
            </a>
            
            @if($order->status == 'pending')
                <button class="btn btn-success" onclick="updateOrderStatus('processing')">
                    <i class="fas fa-play"></i>
                    بدء المعالجة
                </button>
            @elseif($order->status == 'processing')
                <button class="btn btn-warning" onclick="updateOrderStatus('shipped')">
                    <i class="fas fa-shipping-fast"></i>
                    شحن الطلب
                </button>
            @elseif($order->status == 'shipped')
                <button class="btn btn-success" onclick="updateOrderStatus('delivered')">
                    <i class="fas fa-check"></i>
                    تأكيد التسليم
                </button>
            @endif
            
            @if($order->status != 'cancelled' && $order->status != 'delivered')
                <button class="btn btn-danger" onclick="updateOrderStatus('cancelled')">
                    <i class="fas fa-times"></i>
                    إلغاء الطلب
                </button>
            @endif
            
            <button class="btn btn-primary" onclick="printOrder()">
                <i class="fas fa-print"></i>
                طباعة
            </button>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="order-content">
    <!-- Order Details -->
    <div>
        <!-- Order Items -->
        <div class="order-details-card fade-in">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-shopping-bag"></i>
                    تفاصيل الطلب
                </h2>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items ?? [] as $item)
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <div class="product-image">
                                                {{ strtoupper(substr($item->product->name ?? 'منتج', 0, 1)) }}
                                            </div>
                                            <div class="product-details">
                                                <h4>{{ $item->product->name ?? 'منتج غير محدد' }}</h4>
                                                <p>{{ $item->product->description ?? 'وصف المنتج' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->quantity ?? 1 }}</td>
                                    <td class="price-cell">${{ number_format($item->price ?? 0, 2) }}</td>
                                    <td class="price-cell">${{ number_format(($item->quantity ?? 1) * ($item->price ?? 0), 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: var(--space-3xl); color: var(--admin-secondary-500);">
                                        <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: var(--space-lg); opacity: 0.5;"></i>
                                        <br>
                                        لا توجد منتجات في هذا الطلب
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Order Summary -->
                @if(($order->items ?? collect())->count() > 0)
                <div class="order-summary">
                    <div class="summary-row">
                        <span>المجموع الفرعي:</span>
                        <span>${{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                    </div>
                    
                    @if(($order->discount_amount ?? 0) > 0)
                        <div class="summary-row">
                            <span>الخصم:</span>
                            <span style="color: var(--success-600);">-${{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    
                    @if(($order->tax_amount ?? 0) > 0)
                        <div class="summary-row">
                            <span>الضريبة:</span>
                            <span>${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                    @endif
                    
                    @if(($order->shipping_amount ?? 0) > 0)
                        <div class="summary-row">
                            <span>رسوم الشحن:</span>
                            <span>${{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                    @endif
                    
                    <div class="summary-row">
                        <span>المجموع النهائي:</span>
                        <span>${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="timeline-card fade-in">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-history"></i>
                    تاريخ الطلب
                </h2>
            </div>
            
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker completed">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="timeline-content">
                        <h4>تم إنشاء الطلب</h4>
                        <p>تم إنشاء الطلب بنجاح وإرسال تأكيد للعميل</p>
                        <div class="timeline-date">{{ $order->created_at->format('d M, Y H:i') }}</div>
                    </div>
                </div>
                
                @if($order->status != 'pending')
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $order->status == 'processing' ? 'current' : 'completed' }}">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>قيد المعالجة</h4>
                            <p>بدأت معالجة الطلب وتحضير المنتجات</p>
                            <div class="timeline-date">{{ $order->updated_at->format('d M, Y H:i') }}</div>
                        </div>
                    </div>
                @endif
                
                @if(in_array($order->status, ['shipped', 'delivered']))
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $order->status == 'shipped' ? 'current' : 'completed' }}">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>تم الشحن</h4>
                            <p>تم شحن الطلب وهو في الطريق للعميل</p>
                            <div class="timeline-date">{{ $order->updated_at->format('d M, Y H:i') }}</div>
                        </div>
                    </div>
                @endif
                
                @if($order->status == 'delivered')
                    <div class="timeline-item">
                        <div class="timeline-marker current">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>تم التسليم</h4>
                            <p>تم تسليم الطلب بنجاح للعميل</p>
                            <div class="timeline-date">{{ $order->updated_at->format('d M, Y H:i') }}</div>
                        </div>
                    </div>
                @endif
                
                @if($order->status == 'cancelled')
                    <div class="timeline-item">
                        <div class="timeline-marker" style="background: linear-gradient(135deg, var(--error-500), var(--error-600));">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>تم الإلغاء</h4>
                            <p>تم إلغاء الطلب</p>
                            <div class="timeline-date">{{ $order->updated_at->format('d M, Y H:i') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div>
        <!-- Customer Info -->
        <div class="customer-card fade-in">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-user"></i>
                    معلومات العميل
                </h2>
            </div>
            
            <div class="card-body">
                <div class="customer-profile">
                    <div class="customer-avatar">
                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                    </div>
                    <div class="customer-details">
                        <h3>{{ $order->user->name }}</h3>
                        <p>{{ $order->user->email }}</p>
                    </div>
                </div>
                
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-envelope"></i>
                        البريد الإلكتروني
                    </span>
                    <span class="info-value">{{ $order->user->email }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-phone"></i>
                        رقم الهاتف
                    </span>
                    <span class="info-value">{{ $order->user->phone ?? 'غير محدد' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-calendar-plus"></i>
                        تاريخ التسجيل
                    </span>
                    <span class="info-value">{{ $order->user->created_at->format('d M, Y') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-shopping-cart"></i>
                        إجمالي الطلبات
                    </span>
                    <span class="info-value">{{ $order->user->orders()->count() ?? 0 }} طلب</span>
                </div>
            </div>
        </div>
        
        <!-- Payment Info -->
        <div class="payment-card fade-in">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-credit-card"></i>
                    معلومات الدفع
                </h2>
            </div>
            
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-money-bill"></i>
                        طريقة الدفع
                    </span>
                    <span class="payment-method">
                        <i class="fas fa-{{ $order->payment_method == 'credit_card' ? 'credit-card' : ($order->payment_method == 'paypal' ? 'paypal' : ($order->payment_method == 'cash' ? 'money-bill' : 'university')) }}"></i>
                        @switch($order->payment_method)
                            @case('credit_card') بطاقة ائتمان @break
                            @case('paypal') باي بال @break
                            @case('cash') نقداً @break
                            @case('bank_transfer') تحويل بنكي @break
                            @default {{ ucfirst($order->payment_method) }}
                        @endswitch
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-check-circle"></i>
                        حالة الدفع
                    </span>
                    <span class="info-value">
                        <span class="status-badge {{ $order->payment_status == 'paid' ? 'status-delivered' : 'status-pending' }}">
                            <i class="fas fa-{{ $order->payment_status == 'paid' ? 'check' : 'clock' }}"></i>
                            {{ $order->payment_status == 'paid' ? 'مدفوع' : 'في الانتظار' }}
                        </span>
                    </span>
                </div>
                
                @if(($order->payment_date ?? null))
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-calendar-check"></i>
                            تاريخ الدفع
                        </span>
                        <span class="info-value">{{ $order->payment_date->format('d M, Y H:i') }}</span>
                    </div>
                @endif
                
                @if($order->transaction_id ?? null)
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-hashtag"></i>
                            رقم المعاملة
                        </span>
                        <span class="info-value">{{ $order->transaction_id }}</span>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Shipping Info -->
        <div class="shipping-card fade-in">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-truck"></i>
                    معلومات الشحن
                </h2>
            </div>
            
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-shipping-fast"></i>
                        طريقة الشحن
                    </span>
                    <span class="info-value">{{ $order->shipping_method ?? 'شحن عادي' }}</span>
                </div>
                
                @if($order->tracking_number ?? null)
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-barcode"></i>
                            رقم التتبع
                        </span>
                        <span class="info-value">{{ $order->tracking_number }}</span>
                    </div>
                @endif
                
                @if(($order->estimated_delivery_date ?? null))
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-calendar-alt"></i>
                            تاريخ التسليم المتوقع
                        </span>
                        <span class="info-value">{{ $order->estimated_delivery_date->format('d M, Y') }}</span>
                    </div>
                @endif
                
                <div class="address-box">
                    <h4 style="margin: 0 0 var(--space-md) 0; color: var(--admin-secondary-700); font-size: 0.9rem;">
                        <i class="fas fa-map-marker-alt"></i>
                        عنوان التسليم
                    </h4>
                    <p style="margin: 0; color: var(--admin-secondary-600); line-height: 1.6;">
                        {{ $order->shipping_address ?? $order->user->address ?? 'لا يوجد عنوان محدد' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update order status
    function updateOrderStatus(status) {
        const statusText = {
            'processing': 'قيد المعالجة',
            'shipped': 'تم الشحن',
            'delivered': 'تم التسليم',
            'cancelled': 'ملغي'
        };
        
        if (!confirm('هل أنت متأكد من تحديث حالة الطلب إلى "' + statusText[status] + '"؟')) {
            return;
        }
        
        // Show loading state
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(btn => {
            if (btn.onclick && btn.onclick.toString().includes('updateOrderStatus')) {
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
                btn.disabled = true;
            }
        });
        
        fetch('{{ route("admin.orders.update", $order) }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        }).then(response => {
            if (response.ok) {
                showNotification('تم تحديث حالة الطلب بنجاح', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification('خطأ في تحديث حالة الطلب', 'error');
                // Restore buttons
                buttons.forEach(btn => {
                    btn.disabled = false;
                    // You would need to restore original text here
                });
            }
        }).catch(error => {
            showNotification('خطأ في تحديث حالة الطلب', 'error');
            // Restore buttons
            buttons.forEach(btn => {
                btn.disabled = false;
            });
        });
    }
    
    // Print order
    function printOrder() {
        window.print();
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
            padding: 1rem 1.5rem;
            border-radius: 1rem;
            color: white;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transform: translateX(-120%);
            transition: transform 0.3s ease;
            backdrop-filter: blur(20px);
            max-width: 350px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        `;
        
        const colors = {
            success: 'linear-gradient(135deg, #10b981, #059669)',
            error: 'linear-gradient(135deg, #ef4444, #dc2626)',
            warning: 'linear-gradient(135deg, #f59e0b, #d97706)',
            info: 'linear-gradient(135deg, #3b82f6, #2563eb)'
        };
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        notification.style.background = colors[type];
        notification.innerHTML = `<i class="fas ${icons[type]}"></i> ${message}`;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(-120%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
    
    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
        
        // Add loading states to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.onclick && this.onclick.toString().includes('updateOrderStatus')) {
                    // Skip - handled in updateOrderStatus function
                    return;
                }
                
                if (this.type === 'submit' || this.tagName === 'A') {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحميل...';
                    this.disabled = true;
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                }
            });
        });
        
        // Enhanced hover effects
        document.querySelectorAll('.customer-card, .payment-card, .shipping-card, .timeline-card, .order-details-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 25px 50px -12px rgba(0, 0, 0, 0.25)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
            });
        });
    });
</script>
@endpush