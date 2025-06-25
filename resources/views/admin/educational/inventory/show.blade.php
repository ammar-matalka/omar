@extends('layouts.admin')

@section('title', 'تفاصيل المخزون')
@section('page-title', 'تفاصيل المخزون')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
        <i class="fas fa-chevron-left"></i>
    </div>
    <div class="breadcrumb-item">
        <a href="{{ route('admin.educational.inventory.index') }}" class="breadcrumb-link">المخزون التعليمي</a>
        <i class="fas fa-chevron-left"></i>
    </div>
    <div class="breadcrumb-item">تفاصيل المخزون</div>
@endsection

@push('styles')
<style>
    .inventory-header {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        color: white;
        padding: var(--space-2xl);
        border-radius: var(--radius-xl) var(--radius-xl) 0 0;
        margin: -var(--space-2xl) -var(--space-2xl) var(--space-2xl) -var(--space-2xl);
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: var(--space-xl);
    }
    
    .header-info {
        flex: 1;
    }
    
    .inventory-title {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .title-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .inventory-meta {
        opacity: 0.9;
        font-size: 1rem;
        line-height: 1.6;
    }
    
    .header-actions {
        display: flex;
        gap: var(--space-md);
        flex-shrink: 0;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        border: 2px solid transparent;
        border-radius: var(--radius-lg);
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
        white-space: nowrap;
        font-family: var(--font-family-sans);
    }
    
    .btn-white {
        background: white;
        color: var(--admin-primary-700);
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .btn-white:hover {
        background: rgba(255, 255, 255, 0.95);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--space-2xl);
        margin-top: var(--space-xl);
    }
    
    .main-content {
        display: flex;
        flex-direction: column;
        gap: var(--space-xl);
    }
    
    .sidebar-content {
        display: flex;
        flex-direction: column;
        gap: var(--space-xl);
    }
    
    .info-card {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: all var(--transition-fast);
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
    }
    
    .card-header {
        padding: var(--space-xl);
        border-bottom: 1px solid var(--admin-secondary-200);
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
    }
    
    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .card-icon {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .card-body {
        padding: var(--space-xl);
    }
    
    .product-chain {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .chain-item {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        padding: var(--space-lg);
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        border-radius: var(--radius-lg);
        border: 2px solid var(--admin-secondary-200);
        transition: all var(--transition-fast);
    }
    
    .chain-item:hover {
        border-color: var(--admin-primary-300);
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
    }
    
    .chain-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    
    .chain-details {
        flex: 1;
    }
    
    .chain-label {
        font-size: 0.85rem;
        color: var(--admin-secondary-500);
        margin-bottom: var(--space-xs);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .chain-value {
        font-weight: 700;
        color: var(--admin-secondary-900);
        font-size: 1rem;
    }
    
    .stock-overview {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-lg);
    }
    
    .stock-item {
        text-align: center;
        padding: var(--space-lg);
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        border-radius: var(--radius-lg);
        border: 2px solid var(--admin-secondary-200);
        transition: all var(--transition-fast);
    }
    
    .stock-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .stock-number {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stock-label {
        font-size: 0.9rem;
        color: var(--admin-secondary-600);
        font-weight: 600;
    }
    
    .stock-status {
        margin-top: var(--space-lg);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        border-radius: var(--radius-lg);
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-available {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border: 2px solid #22c55e;
    }
    
    .status-low {
        background: linear-gradient(135deg, #fffbeb, #fde68a);
        color: #92400e;
        border: 2px solid #f59e0b;
    }
    
    .status-out {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        color: #991b1b;
        border: 2px solid #ef4444;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--space-md);
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-md);
        padding: var(--space-lg);
        border: 2px solid var(--admin-secondary-300);
        border-radius: var(--radius-lg);
        background: white;
        color: var(--admin-secondary-700);
        text-decoration: none;
        font-weight: 600;
        transition: all var(--transition-fast);
    }
    
    .action-btn:hover {
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
        border-color: var(--admin-primary-300);
        color: var(--admin-primary-700);
        transform: translateY(-1px);
    }
    
    .action-btn.edit {
        border-color: var(--admin-primary-300);
        color: var(--admin-primary-700);
    }
    
    .action-btn.edit:hover {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        color: white;
    }
    
    .action-btn.add-stock {
        border-color: var(--success-300);
        color: var(--success-700);
    }
    
    .action-btn.add-stock:hover {
        background: linear-gradient(135deg, var(--success-500), #059669);
        color: white;
    }
    
    .package-details {
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
        border: 2px solid var(--admin-primary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
    }
    
    .package-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
    }
    
    .package-type-badge {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-md);
        font-size: 0.85rem;
        font-weight: 600;
        background: linear-gradient(135deg, var(--warning-100), var(--warning-200));
        color: var(--warning-800);
        border: 2px solid var(--warning-300);
    }
    
    .package-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-md);
    }
    
    .package-info-item {
        background: white;
        padding: var(--space-lg);
        border-radius: var(--radius-md);
        border: 1px solid var(--admin-primary-200);
    }
    
    .info-label {
        font-size: 0.8rem;
        color: var(--admin-secondary-500);
        margin-bottom: var(--space-xs);
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .info-value {
        font-weight: 700;
        color: var(--admin-secondary-900);
        font-size: 0.95rem;
    }
    
    .activity-timeline {
        margin-top: var(--space-xl);
    }
    
    .timeline-item {
        display: flex;
        gap: var(--space-lg);
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        border: 2px solid var(--admin-secondary-200);
        margin-bottom: var(--space-md);
        transition: all var(--transition-fast);
    }
    
    .timeline-item:hover {
        border-color: var(--admin-primary-300);
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
    }
    
    .timeline-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--success-500), #059669);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .timeline-content {
        flex: 1;
    }
    
    .timeline-title {
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .timeline-meta {
        font-size: 0.85rem;
        color: var(--admin-secondary-500);
    }
    
    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
            gap: var(--space-xl);
        }
        
        .header-content {
            flex-direction: column;
            gap: var(--space-lg);
        }
        
        .header-actions {
            align-self: stretch;
        }
        
        .btn {
            flex: 1;
            justify-content: center;
        }
    }
    
    @media (max-width: 768px) {
        .stock-overview {
            grid-template-columns: 1fr;
        }
        
        .package-info-grid {
            grid-template-columns: 1fr;
        }
        
        .inventory-header {
            margin: -var(--space-lg) -var(--space-lg) var(--space-lg) -var(--space-lg);
            padding: var(--space-lg);
        }
        
        .inventory-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="info-card fade-in">
    <div class="inventory-header">
        <div class="header-content">
            <div class="header-info">
                <h1 class="inventory-title">
                    <div class="title-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    مخزون: {{ $inventory->package->name }}
                </h1>
                <div class="inventory-meta">
                    {{ $inventory->generation->display_name }} - {{ $inventory->subject->name }} - {{ $inventory->teacher->name }}
                    <br>
                    منصة: {{ $inventory->platform->name }}
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.educational.inventory.edit', $inventory->id) }}" class="btn btn-white">
                    <i class="fas fa-edit"></i>
                    تعديل
                </a>
                <a href="{{ route('admin.educational.inventory.index') }}" class="btn btn-white">
                    <i class="fas fa-arrow-right"></i>
                    العودة
                </a>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Product Chain -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <div class="card-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        السلسلة التعليمية
                    </h2>
                </div>
                <div class="card-body">
                    <div class="product-chain">
                        <div class="chain-item">
                            <div class="chain-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="chain-details">
                                <div class="chain-label">الجيل الدراسي</div>
                                <div class="chain-value">{{ $inventory->generation->display_name }}</div>
                            </div>
                        </div>

                        <div class="chain-item">
                            <div class="chain-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="chain-details">
                                <div class="chain-label">المادة الدراسية</div>
                                <div class="chain-value">{{ $inventory->subject->name }}</div>
                            </div>
                        </div>

                        <div class="chain-item">
                            <div class="chain-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="chain-details">
                                <div class="chain-label">المعلم</div>
                                <div class="chain-value">{{ $inventory->teacher->name }}</div>
                            </div>
                        </div>

                        <div class="chain-item">
                            <div class="chain-icon">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <div class="chain-details">
                                <div class="chain-label">المنصة التعليمية</div>
                                <div class="chain-value">{{ $inventory->platform->name }}</div>
                            </div>
                        </div>

                        <div class="chain-item">
                            <div class="chain-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="chain-details">
                                <div class="chain-label">الباقة التعليمية</div>
                                <div class="chain-value">{{ $inventory->package->name }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Details -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <div class="card-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        تفاصيل الباقة
                    </h2>
                </div>
                <div class="card-body">
                    <div class="package-details">
                        <div class="package-header">
                            <div class="package-type-badge">
                                <i class="fas {{ $inventory->package->is_digital ? 'fa-cloud' : 'fa-book' }}"></i>
                                {{ $inventory->package->package_type }}
                            </div>
                        </div>

                        <div class="package-info-grid">
                            <div class="package-info-item">
                                <div class="info-label">نوع المنتج</div>
                                <div class="info-value">{{ $inventory->package->productType->name }}</div>
                            </div>

                            @if($inventory->package->description)
                                <div class="package-info-item">
                                    <div class="info-label">الوصف</div>
                                    <div class="info-value">{{ $inventory->package->description }}</div>
                                </div>
                            @endif

                            @if($inventory->package->is_digital)
                                @if($inventory->package->duration_days)
                                    <div class="package-info-item">
                                        <div class="info-label">مدة الصلاحية</div>
                                        <div class="info-value">{{ $inventory->package->duration_display }}</div>
                                    </div>
                                @endif

                                @if($inventory->package->lessons_count)
                                    <div class="package-info-item">
                                        <div class="info-label">عدد الدروس</div>
                                        <div class="info-value">{{ $inventory->package->lessons_display }}</div>
                                    </div>
                                @endif
                            @else
                                @if($inventory->package->pages_count)
                                    <div class="package-info-item">
                                        <div class="info-label">عدد الصفحات</div>
                                        <div class="info-value">{{ $inventory->package->pages_display }}</div>
                                    </div>
                                @endif

                                @if($inventory->package->weight_grams)
                                    <div class="package-info-item">
                                        <div class="info-label">الوزن</div>
                                        <div class="info-value">{{ $inventory->package->weight_display }}</div>
                                    </div>
                                @endif
                            @endif

                            <div class="package-info-item">
                                <div class="info-label">تاريخ الإنشاء</div>
                                <div class="info-value">{{ $inventory->created_at->format('Y-m-d H:i') }}</div>
                            </div>

                            <div class="package-info-item">
                                <div class="info-label">آخر تحديث</div>
                                <div class="info-value">{{ $inventory->updated_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <div class="card-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        النشاط الأخير
                    </h2>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">إنشاء المخزون</div>
                                <div class="timeline-meta">
                                    تم إنشاء المخزون بتاريخ {{ $inventory->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>
                        </div>

                        @if($inventory->updated_at != $inventory->created_at)
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">تحديث المخزون</div>
                                    <div class="timeline-meta">
                                        آخر تحديث بتاريخ {{ $inventory->updated_at->format('Y-m-d H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Stock Overview -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <div class="card-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        نظرة عامة على المخزون
                    </h2>
                </div>
                <div class="card-body">
                    <div class="stock-overview">
                        <div class="stock-item">
                            <div class="stock-number">{{ number_format($inventory->quantity_available) }}</div>
                            <div class="stock-label">الكمية المتاحة</div>
                        </div>

                        <div class="stock-item">
                            <div class="stock-number">{{ number_format($inventory->quantity_reserved) }}</div>
                            <div class="stock-label">الكمية المحجوزة</div>
                        </div>

                        <div class="stock-item">
                            <div class="stock-number">{{ number_format($inventory->total_quantity) }}</div>
                            <div class="stock-label">إجمالي الكمية</div>
                        </div>

                        <div class="stock-item">
                            <div class="stock-number">{{ number_format($inventory->actual_available) }}</div>
                            <div class="stock-label">المتاح الفعلي</div>
                        </div>
                    </div>

                    <div class="stock-status">
                        <div class="status-badge status-{{ $inventory->stock_status_class }}">
                            <i class="fas {{ $inventory->actual_available > 50 ? 'fa-check-circle' : ($inventory->actual_available > 10 ? 'fa-exclamation-triangle' : ($inventory->actual_available > 0 ? 'fa-exclamation-circle' : 'fa-times-circle')) }}"></i>
                            {{ $inventory->stock_status }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <div class="card-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        إجراءات سريعة
                    </h2>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="{{ route('admin.educational.inventory.edit', $inventory->id) }}" class="action-btn edit">
                            <i class="fas fa-edit"></i>
                            تعديل المخزون
                        </a>

                        <button type="button" class="action-btn add-stock" onclick="showAddStockModal()">
                            <i class="fas fa-plus"></i>
                            إضافة كمية
                        </button>

                        <button type="button" class="action-btn" onclick="showAdjustReservedModal()">
                            <i class="fas fa-lock"></i>
                            تعديل المحجوز
                        </button>

                        <a href="{{ route('admin.educational.inventory.index') }}" class="action-btn">
                            <i class="fas fa-list"></i>
                            عرض جميع المخزون
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stock Statistics -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <div class="card-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        إحصائيات المخزون
                    </h2>
                </div>
                <div class="card-body">
                    <div class="package-info-grid">
                        <div class="package-info-item">
                            <div class="info-label">نسبة التوفر</div>
                            <div class="info-value">
                                @php
                                    $percentage = $inventory->total_quantity > 0 ? 
                                        round(($inventory->actual_available / $inventory->total_quantity) * 100, 1) : 0;
                                @endphp
                                {{ $percentage }}%
                            </div>
                        </div>

                        <div class="package-info-item">
                            <div class="info-label">نسبة الحجز</div>
                            <div class="info-value">
                                @php
                                    $reservedPercentage = $inventory->quantity_available > 0 ? 
                                        round(($inventory->quantity_reserved / $inventory->quantity_available) * 100, 1) : 0;
                                @endphp
                                {{ $reservedPercentage }}%
                            </div>
                        </div>

                        @if($inventory->hasLowStock())
                            <div class="package-info-item">
                                <div class="info-label">تحذير</div>
                                <div class="info-value" style="color: var(--warning-600);">
                                    مخزون منخفض
                                </div>
                            </div>
                        @endif

                        @if($inventory->isOutOfStock())
                            <div class="package-info-item">
                                <div class="info-label">تنبيه</div>
                                <div class="info-value" style="color: var(--error-600);">
                                    نفد المخزون
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Stock Modal -->
<div id="addStockModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>إضافة كمية للمخزون</h3>
            <button type="button" class="modal-close" onclick="hideAddStockModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="addStockForm" action="{{ route('admin.educational.inventory.add-stock', $inventory->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="addQuantity" class="form-label">
                        <i class="fas fa-plus label-icon"></i>
                        الكمية المراد إضافتها
                        <span class="required-mark">*</span>
                    </label>
                    <input type="number" name="quantity" id="addQuantity" class="form-input" 
                           min="1" max="10000" required placeholder="أدخل الكمية">
                    <div class="form-help">
                        <i class="fas fa-info-circle help-icon"></i>
                        ستتم إضافة هذه الكمية إلى المخزون الحالي
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="hideAddStockModal()">
                <i class="fas fa-times"></i>
                إلغاء
            </button>
            <button type="submit" form="addStockForm" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                إضافة
            </button>
        </div>
    </div>
</div>

<!-- Adjust Reserved Modal -->
<div id="adjustReservedModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>تعديل الكمية المحجوزة</h3>
            <button type="button" class="modal-close" onclick="hideAdjustReservedModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="adjustReservedForm" action="{{ route('admin.educational.inventory.adjust-reserved', $inventory->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="reservedQuantity" class="form-label">
                        <i class="fas fa-lock label-icon"></i>
                        الكمية المحجوزة الجديدة
                        <span class="required-mark">*</span>
                    </label>
                    <input type="number" name="quantity" id="reservedQuantity" class="form-input" 
                           min="0" max="{{ $inventory->quantity_available }}" 
                           value="{{ $inventory->quantity_reserved }}" required>
                    <div class="form-help">
                        <i class="fas fa-info-circle help-icon"></i>
                        الحد الأقصى: {{ number_format($inventory->quantity_available) }} قطعة
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="hideAdjustReservedModal()">
                <i class="fas fa-times"></i>
                إلغاء
            </button>
            <button type="submit" form="adjustReservedForm" class="btn btn-primary">
                <i class="fas fa-save"></i>
                حفظ
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Modal Styles */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: var(--z-modal);
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }
    
    .modal-content {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-xl);
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .modal-header {
        padding: var(--space-xl);
        border-bottom: 1px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
    }
    
    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--admin-secondary-500);
        cursor: pointer;
        padding: var(--space-sm);
        border-radius: var(--radius-md);
        transition: all var(--transition-fast);
    }
    
    .modal-close:hover {
        background: var(--admin-secondary-200);
        color: var(--admin-secondary-700);
    }
    
    .modal-body {
        padding: var(--space-xl);
    }
    
    .modal-footer {
        padding: var(--space-xl);
        border-top: 1px solid var(--admin-secondary-200);
        display: flex;
        gap: var(--space-md);
        justify-content: flex-end;
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
    }
    
    .form-group {
        margin-bottom: var(--space-lg);
    }
    
    .form-label {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
        font-weight: 600;
        color: var(--admin-secondary-700);
        font-size: 0.95rem;
    }
    
    .label-icon {
        color: var(--admin-primary-500);
        font-size: 0.9rem;
    }
    
    .required-mark {
        color: var(--error-500);
        margin-right: var(--space-xs);
    }
    
    .form-input {
        width: 100%;
        padding: var(--space-md) var(--space-lg);
        border: 2px solid var(--admin-secondary-300);
        border-radius: var(--radius-lg);
        background: white;
        color: var(--admin-secondary-900);
        font-size: 0.9rem;
        transition: all var(--transition-fast);
        font-family: var(--font-family-sans);
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }
    
    .form-help {
        font-size: 0.85rem;
        color: var(--admin-secondary-500);
        margin-top: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .help-icon {
        color: var(--info-500);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize fade-in animation
    const elements = document.querySelectorAll('.fade-in');
    elements.forEach(el => {
        el.classList.add('visible');
    });
    
    // Modal functions
    window.showAddStockModal = function() {
        document.getElementById('addStockModal').style.display = 'flex';
        document.getElementById('addQuantity').focus();
    };
    
    window.hideAddStockModal = function() {
        document.getElementById('addStockModal').style.display = 'none';
        document.getElementById('addStockForm').reset();
    };
    
    window.showAdjustReservedModal = function() {
        document.getElementById('adjustReservedModal').style.display = 'flex';
        document.getElementById('reservedQuantity').focus();
    };
    
    window.hideAdjustReservedModal = function() {
        document.getElementById('adjustReservedModal').style.display = 'none';
    };
    
    // Close modals when clicking outside
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.style.display = 'none';
            });
        }
    });
    
    // Form submissions with loading states
    document.getElementById('addStockForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
        submitBtn.disabled = true;
        
        // Re-enable button after 5 seconds (in case of slow response)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
    
    document.getElementById('adjustReservedForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        submitBtn.disabled = true;
        
        // Re-enable button after 5 seconds (in case of slow response)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
    
    // Enhanced hover effects
    document.querySelectorAll('.chain-item, .stock-item, .timeline-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush