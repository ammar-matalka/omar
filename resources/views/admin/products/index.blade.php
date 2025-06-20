@extends('layouts.admin')

@section('title', 'إدارة المنتجات')
@section('page-title', 'إدارة المنتجات')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        المنتجات
    </div>
@endsection

@push('styles')
<style>
    * {
        direction: rtl;
        text-align: right;
    }
    
    .products-header {
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
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }
    
    .products-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: white;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .products-stats {
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
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .product-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all var(--transition-normal);
        border: 1px solid var(--admin-secondary-100);
        position: relative;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }
    
    .product-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .product-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background: linear-gradient(135deg, var(--admin-secondary-100), var(--admin-secondary-200));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-secondary-500);
        font-size: 3rem;
        position: relative;
        overflow: hidden;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-normal);
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.1);
    }
    
    .product-status {
        position: absolute;
        top: var(--space-sm);
        left: var(--space-sm);
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-lg);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
    }
    
    .status-active {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }
    
    .stock-badge {
        position: absolute;
        top: var(--space-sm);
        right: var(--space-sm);
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-lg);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
    }
    
    .stock-high {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    .stock-low {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
    
    .stock-out {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }
    
    .product-content {
        padding: var(--space-xl);
    }
    
    .product-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }
    
    .product-category {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-lg);
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: var(--space-lg);
        display: inline-block;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .product-description {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        line-height: 1.6;
        margin-bottom: var(--space-lg);
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: var(--space-lg);
        border-top: 2px solid var(--admin-secondary-100);
        margin-bottom: var(--space-lg);
    }
    
    .product-price {
        font-size: 1.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .product-stock {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .product-actions {
        display: flex;
        gap: var(--space-sm);
        justify-content: center;
    }
    
    .action-btn {
        flex: 1;
        padding: var(--space-md);
        border: none;
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.875rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-xs);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .action-btn.view {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .action-btn.view:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }
    
    .action-btn.edit {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .action-btn.edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
    }
    
    .action-btn.delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .action-btn.delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
        grid-column: 1 / -1;
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
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: var(--space-xl);
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
    
    .view-toggle {
        display: flex;
        gap: var(--space-xs);
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        padding: var(--space-xs);
        border-radius: var(--radius-lg);
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .view-btn {
        padding: var(--space-md);
        border: none;
        background: transparent;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        color: rgba(255,255,255,0.8);
        font-size: 1.1rem;
    }
    
    .view-btn.active {
        background: rgba(255,255,255,0.25);
        color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: scale(1.1);
    }
    
    /* Table View Styles */
    .products-table-container {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-100);
        display: none;
    }
    
    .products-table-container.active {
        display: block;
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
    
    .products-table {
        width: 100%;
        border-collapse: collapse;
        direction: rtl;
    }
    
    .products-table th {
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
    
    .products-table td {
        padding: var(--space-xl);
        border-bottom: 1px solid var(--admin-secondary-100);
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    .products-table tbody tr {
        transition: all var(--transition-fast);
    }
    
    .products-table tbody tr:hover {
        background: linear-gradient(135deg, var(--admin-secondary-25), #f8fafc);
        transform: scale(1.01);
    }
    
    .product-info {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .product-thumb {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-lg);
        object-fit: cover;
        border: 2px solid var(--admin-secondary-200);
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .product-details {
        min-width: 0;
        flex: 1;
    }
    
    .product-title {
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .product-category-small {
        color: var(--admin-secondary-500);
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .btn {
        border-radius: var(--radius-lg);
        font-weight: 600;
        transition: all var(--transition-fast);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600));
        border: none;
        color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border: none;
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .badge {
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .badge-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .badge-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    .badge-info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }
    
    .badge-secondary {
        background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600));
        color: white;
    }
    
    @media (max-width: 768px) {
        .products-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .products-stats {
            width: 100%;
            justify-content: space-between;
        }
        
        .products-grid {
            grid-template-columns: 1fr;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .products-table-container {
            overflow-x: auto;
        }
        
        .products-table {
            min-width: 800px;
        }
        
        .product-actions {
            flex-direction: column;
        }
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
<!-- Products Header -->
<div class="products-header">
    <div>
        <h1 class="products-title">
            <i class="fas fa-box"></i>
            إدارة المنتجات
        </h1>
    </div>
    
    <div class="products-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $products->total() }}</div>
            <div class="stat-label">إجمالي المنتجات</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $products->where('is_active', true)->count() }}</div>
            <div class="stat-label">نشط</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $products->where('stock', '<=', 5)->count() }}</div>
            <div class="stat-label">مخزون منخفض</div>
        </div>
        
        <div class="view-toggle">
            <button class="view-btn active" id="gridViewBtn" onclick="switchView('grid')">
                <i class="fas fa-th"></i>
            </button>
            <button class="view-btn" id="tableViewBtn" onclick="switchView('table')">
                <i class="fas fa-list"></i>
            </button>
        </div>
        
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            إضافة منتج
        </a>
    </div>
</div>

<!-- Filters Section -->
<div class="filters-section fade-in">
    <h2 class="filters-title">
        <i class="fas fa-filter"></i>
        تصفية المنتجات
    </h2>
    
    <form method="GET" action="{{ route('admin.products.index') }}">
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">البحث</label>
                <input 
                    type="text" 
                    name="search" 
                    class="filter-input" 
                    placeholder="البحث بالاسم أو الوصف..."
                    value="{{ request('search') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">الفئة</label>
                <select name="category" class="filter-input">
                    <option value="">جميع الفئات</option>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">الحالة</label>
                <select name="status" class="filter-input">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">حالة المخزون</label>
                <select name="stock_status" class="filter-input">
                    <option value="">جميع المخزون</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>متوفر</option>
                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>مخزون منخفض</option>
                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>نفد المخزون</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">نطاق السعر</label>
                <input 
                    type="number" 
                    name="min_price" 
                    class="filter-input" 
                    placeholder="أقل سعر"
                    value="{{ request('min_price') }}"
                    step="0.01"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">&nbsp;</label>
                <input 
                    type="number" 
                    name="max_price" 
                    class="filter-input" 
                    placeholder="أعلى سعر"
                    value="{{ request('max_price') }}"
                    step="0.01"
                >
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                تصفية
            </button>
            
            @if(request()->hasAny(['search', 'category', 'status', 'stock_status', 'min_price', 'max_price']))
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    مسح الفلاتر
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Grid View -->
@if($products->count() > 0)
    <div class="products-grid" id="gridView">
        @foreach($products as $product)
            <div class="product-card fade-in">
                <div class="product-image">
                    @if($product->image || ($product->images && $product->images->first()))
                        <img src="{{ Storage::url($product->image ?? $product->images->first()->image_path) }}" alt="{{ $product->name }}">
                    @else
                        <i class="fas fa-image"></i>
                    @endif
                    
                    <div class="product-status status-{{ $product->is_active ? 'active' : 'inactive' }}">
                        {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                    </div>
                    
                    <div class="stock-badge 
                        @if($product->stock <= 0) stock-out
                        @elseif($product->stock <= 5) stock-low
                        @else stock-high
                        @endif">
                        @if($product->stock <= 0)
                            نفد المخزون
                        @elseif($product->stock <= 5)
                            مخزون منخفض
                        @else
                            متوفر
                        @endif
                    </div>
                </div>
                
                <div class="product-content">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    
                    @if($product->category)
                        <div class="product-category">{{ $product->category->name }}</div>
                    @endif
                    
                    @if($product->description)
                        <p class="product-description">{{ $product->description }}</p>
                    @else
                        <p class="product-description" style="color: var(--admin-secondary-400); font-style: italic;">
                            لا يوجد وصف متاح
                        </p>
                    @endif
                    
                    <div class="product-meta">
                        <div class="product-price">${{ number_format($product->price, 2) }}</div>
                        <div class="product-stock">
                            <i class="fas fa-boxes"></i>
                            {{ $product->stock }} وحدة
                        </div>
                    </div>
                    
                    <div class="product-actions">
                        <a href="{{ route('admin.products.show', $product) }}" class="action-btn view">
                            <i class="fas fa-eye"></i>
                            عرض
                        </a>
                        
                        <a href="{{ route('admin.products.edit', $product) }}" class="action-btn edit">
                            <i class="fas fa-edit"></i>
                            تعديل
                        </a>
                        
                        <button class="action-btn delete" onclick="deleteProduct('{{ $product->id }}')">
                            <i class="fas fa-trash"></i>
                            حذف
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Table View -->
    <div class="products-table-container" id="tableView">
        <div class="table-header">
            <h3 class="table-title">
                <i class="fas fa-list"></i>
                قائمة المنتجات
            </h3>
        </div>
        
        <table class="products-table">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>الفئة</th>
                    <th>السعر</th>
                    <th>المخزون</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="product-info">
                                @if($product->image || ($product->images && $product->images->first()))
                                    <img src="{{ Storage::url($product->image ?? $product->images->first()->image_path) }}" 
                                         alt="{{ $product->name }}" class="product-thumb">
                                @else
                                    <div class="product-thumb" style="background: var(--admin-secondary-100); display: flex; align-items: center; justify-content: center; color: var(--admin-secondary-400);">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <div class="product-details">
                                    <div class="product-title">{{ $product->name }}</div>
                                    <div class="product-category-small">الرقم: {{ $product->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($product->category)
                                <span class="badge badge-info">{{ $product->category->name }}</span>
                            @else
                                <span class="badge badge-secondary">لا توجد فئة</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight: 600; color: var(--admin-primary-600);">
                                ${{ number_format($product->price, 2) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge 
                                @if($product->stock <= 0) badge-danger
                                @elseif($product->stock <= 5) badge-warning
                                @else badge-success
                                @endif">
                                {{ $product->stock }} وحدة
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: var(--space-xs);">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="deleteProduct('{{ $product->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($products->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination">
                @if($products->onFirstPage())
                    <span class="page-link disabled">السابق</span>
                @else
                    <a href="{{ $products->appends(request()->query())->previousPageUrl() }}" class="page-link">السابق</a>
                @endif
                
                @foreach($products->appends(request()->query())->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if($page == $products->currentPage())
                        <span class="page-link active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($products->hasMorePages())
                    <a href="{{ $products->appends(request()->query())->nextPageUrl() }}" class="page-link">التالي</a>
                @else
                    <span class="page-link disabled">التالي</span>
                @endif
            </div>
        </div>
    @endif
@else
    <div class="products-grid" id="gridView">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 class="empty-title">لا توجد منتجات</h3>
            <p class="empty-text">
                @if(request()->hasAny(['search', 'category', 'status', 'stock_status', 'min_price', 'max_price']))
                    لا توجد منتجات تطابق معايير البحث الخاصة بك.
                @else
                    ابدأ في بناء مخزونك عن طريق إضافة منتجك الأول.
                @endif
            </p>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                إنشاء أول منتج
            </a>
        </div>
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-xl); max-width: 450px; width: 90%; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div style="color: var(--error-500); font-size: 4rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900); font-size: 1.5rem; font-weight: 700;">حذف المنتج</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600); font-size: 1.1rem; line-height: 1.6;">
            هل أنت متأكد من أنك تريد حذف هذا المنتج؟ لا يمكن التراجع عن هذا الإجراء.
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center;">
            <button onclick="closeDeleteModal()" class="btn btn-secondary" style="padding: var(--space-md) var(--space-xl);">إلغاء</button>
            <button onclick="confirmDelete()" class="btn btn-danger" style="padding: var(--space-md) var(--space-xl);">حذف</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let productToDelete = null;
    let currentView = 'grid';
    
    // Switch between grid and table view
    function switchView(view) {
        currentView = view;
        
        const gridView = document.getElementById('gridView');
        const tableView = document.getElementById('tableView');
        const gridBtn = document.getElementById('gridViewBtn');
        const tableBtn = document.getElementById('tableViewBtn');
        
        if (view === 'grid') {
            gridView.style.display = 'grid';
            tableView.classList.remove('active');
            gridBtn.classList.add('active');
            tableBtn.classList.remove('active');
        } else {
            gridView.style.display = 'none';
            tableView.classList.add('active');
            gridBtn.classList.remove('active');
            tableBtn.classList.add('active');
        }
        
        // Store preference in localStorage
        localStorage.setItem('productsView', view);
    }
    
    // Delete product functions
    function deleteProduct(productId) {
        productToDelete = productId;
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
        productToDelete = null;
    }
    
    function confirmDelete() {
        if (productToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/products/${productToDelete}`;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
    
    // Initialize view preference
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('productsView') || 'grid';
        switchView(savedView);
        
        // Enhanced animations with staggered effect
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 100); // Stagger animation by 100ms
                    observer.unobserve(entry.target);
                }
            });
        }, { 
            threshold: 0.1,
            rootMargin: '50px'
        });
        
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
        
        // Add hover effects to cards
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
    
    // Enhanced search functionality
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        
        // Add search icon animation
        searchInput.addEventListener('focus', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 20px rgba(59, 130, 246, 0.2)';
        });
        
        searchInput.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
        });
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            // Visual feedback for typing
            this.style.borderColor = 'var(--admin-primary-400)';
            
            searchTimeout = setTimeout(() => {
                this.style.borderColor = 'var(--admin-secondary-300)';
                // Uncomment for live search
                // this.form.submit();
            }, 1000);
        });
    }
    
    // Enhanced filtering with smooth transitions
    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'translateY(-2px)';
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
            this.parentElement.style.transform = 'scale(1)';
        });
        
        input.addEventListener('change', function() {
            if (this.tagName === 'SELECT') {
                // Visual feedback for selection
                this.style.background = 'linear-gradient(135deg, #f0f9ff, #e0f2fe)';
                
                setTimeout(() => {
                    this.style.background = '#ffffff';
                }, 300);
                
                // Uncomment for auto-submit
                // this.form.submit();
            }
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
                
                // Restore after 2 seconds (adjust based on your needs)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 2000);
            }
        });
    });
    
    // Add smooth scrolling to pagination
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            // Smooth scroll to top when changing pages
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
    
    // Toast notification system (optional - add to your layout)
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #ef4444, #dc2626)'};
            color: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            font-weight: 600;
        `;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Animate out
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
    
    // Example usage: showToast('تم حذف المنتج بنجاح', 'success');
</script>
@endpush