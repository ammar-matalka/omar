@extends('layouts.admin')

@section('title', 'تفاصيل الفئة')
@section('page-title', 'تفاصيل الفئة')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        <a href="{{ route('admin.categories.index') }}" class="breadcrumb-link">الفئات</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        {{ $category->name }}
    </div>
@endsection

@push('styles')
<style>
    * {
        direction: rtl;
        text-align: right;
    }
    
    .category-show-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .category-header {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 2px solid var(--admin-secondary-100);
        position: relative;
    }
    
    .category-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-400), var(--success-500));
    }
    
    .category-hero {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: var(--space-2xl);
        padding: var(--space-2xl);
        background: linear-gradient(135deg, #ffffff, #f8fafc);
    }
    
    .category-image-container {
        position: relative;
    }
    
    .category-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: var(--radius-2xl);
        border: 3px solid var(--admin-secondary-200);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        transition: all var(--transition-normal);
    }
    
    .category-image:hover {
        transform: scale(1.03);
        box-shadow: 0 25px 35px -5px rgba(0, 0, 0, 0.15);
    }
    
    .no-image {
        width: 100%;
        height: 250px;
        background: linear-gradient(135deg, var(--admin-primary-100), var(--admin-primary-200));
        border-radius: var(--radius-2xl);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-primary-600);
        font-size: 4rem;
        border: 3px solid var(--admin-primary-300);
        position: relative;
        overflow: hidden;
    }
    
    .no-image::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
        50% { transform: translate(-50%, -50%) rotate(180deg); }
    }
    
    .category-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: var(--space-lg);
    }
    
    .category-name {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-family: 'Cairo', sans-serif;
        background: linear-gradient(135deg, var(--admin-secondary-900), var(--admin-primary-600));
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .category-slug {
        background: linear-gradient(135deg, var(--admin-secondary-100), var(--admin-secondary-200));
        color: var(--admin-secondary-600);
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-xl);
        font-size: 1rem;
        font-family: 'Courier New', monospace;
        margin-bottom: var(--space-lg);
        display: inline-block;
        width: fit-content;
        border: 2px solid var(--admin-secondary-300);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        direction: ltr;
        text-align: left;
    }
    
    .category-description {
        color: var(--admin-secondary-600);
        font-size: 1.125rem;
        line-height: 1.8;
        margin-bottom: var(--space-xl);
        font-family: 'Cairo', sans-serif;
        background: var(--admin-secondary-50);
        padding: var(--space-lg);
        border-radius: var(--radius-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .category-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-lg);
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: var(--space-lg);
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
        border-radius: var(--radius-xl);
        border: 2px solid var(--admin-primary-200);
        text-align: center;
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
    }
    
    .stat-item::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--admin-primary-200), var(--admin-primary-300));
        border-radius: 0 var(--radius-xl) 0 var(--radius-xl);
        opacity: 0.7;
    }
    
    .stat-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);
        border-color: var(--admin-primary-400);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-500));
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: var(--admin-primary-700);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-family: 'Cairo', sans-serif;
    }
    
    .category-actions {
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        padding: var(--space-2xl);
        border-top: 2px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-md);
    }
    
    .action-group {
        display: flex;
        gap: var(--space-md);
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--space-2xl);
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
    
    .products-section {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 2px solid var(--admin-secondary-100);
        position: relative;
    }
    
    .products-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--success-500), var(--success-400));
    }
    
    .section-header {
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        padding: var(--space-xl);
        border-bottom: 2px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-family: 'Cairo', sans-serif;
    }
    
    .section-title i {
        color: var(--success-600);
        font-size: 1.25rem;
    }
    
    .badge {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-400));
        color: white;
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        margin-right: var(--space-sm);
    }
    
    .section-action {
        color: var(--admin-primary-600);
        text-decoration: none;
        font-size: 1rem;
        font-weight: 600;
        transition: all var(--transition-fast);
        font-family: 'Cairo', sans-serif;
        background: var(--admin-primary-50);
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--admin-primary-200);
    }
    
    .section-action:hover {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.2);
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: var(--space-lg);
        padding: var(--space-xl);
    }
    
    .product-card {
        background: white;
        border: 2px solid var(--admin-secondary-100);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: all var(--transition-normal);
        position: relative;
    }
    
    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--admin-primary-400), var(--success-400));
        opacity: 0;
        transition: opacity var(--transition-fast);
    }
    
    .product-card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-4px);
        border-color: var(--admin-primary-300);
    }
    
    .product-card:hover::before {
        opacity: 1;
    }
    
    .product-image {
        width: 100%;
        height: 140px;
        object-fit: cover;
        background: linear-gradient(135deg, var(--admin-secondary-100), var(--admin-secondary-200));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-secondary-500);
        font-size: 2rem;
    }
    
    .product-info {
        padding: var(--space-lg);
    }
    
    .product-name {
        font-weight: 700;
        color: var(--admin-secondary-900);
        font-size: 1rem;
        margin-bottom: var(--space-sm);
        line-height: 1.4;
        font-family: 'Cairo', sans-serif;
    }
    
    .product-price {
        color: var(--success-600);
        font-weight: 800;
        font-size: 1.125rem;
        margin-bottom: var(--space-xs);
    }
    
    .product-stock {
        font-size: 0.875rem;
        color: var(--admin-secondary-500);
        font-family: 'Cairo', sans-serif;
        background: var(--admin-secondary-50);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        display: inline-block;
    }
    
    .empty-products {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
        background: linear-gradient(135deg, var(--admin-secondary-50), #ffffff);
        border-radius: var(--radius-xl);
        margin: var(--space-xl);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.6;
        background: linear-gradient(135deg, var(--admin-secondary-400), var(--admin-secondary-500));
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .info-card {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 2px solid var(--admin-secondary-100);
        position: relative;
    }
    
    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-400));
    }
    
    .info-list {
        padding: var(--space-xl);
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-lg) 0;
        border-bottom: 1px solid var(--admin-secondary-100);
        transition: all var(--transition-fast);
    }
    
    .info-item:hover {
        background: var(--admin-secondary-50);
        margin: 0 calc(-1 * var(--space-lg));
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: var(--admin-secondary-700);
        font-size: 1rem;
        font-family: 'Cairo', sans-serif;
    }
    
    .info-value {
        color: var(--admin-secondary-900);
        font-size: 1rem;
        font-weight: 500;
        font-family: 'Cairo', sans-serif;
    }
    
    .quick-actions {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 2px solid var(--admin-secondary-100);
        position: relative;
    }
    
    .quick-actions::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--warning-500), var(--warning-400));
    }
    
    .quick-actions-list {
        padding: var(--space-xl);
    }
    
    .quick-action {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        padding: var(--space-lg);
        text-decoration: none;
        color: var(--admin-secondary-700);
        border-radius: var(--radius-xl);
        transition: all var(--transition-normal);
        margin-bottom: var(--space-md);
        border: 1px solid transparent;
        background: linear-gradient(135deg, #ffffff, #f8fafc);
    }
    
    .quick-action:hover {
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        color: var(--admin-secondary-900);
        transform: translateX(8px);
        border-color: var(--admin-secondary-200);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .quick-action:last-child {
        margin-bottom: 0;
    }
    
    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
        flex-shrink: 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .action-icon.edit {
        background: linear-gradient(135deg, var(--warning-500), var(--warning-400));
        color: white;
    }
    
    .action-icon.products {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-400));
        color: white;
    }
    
    .action-icon.delete {
        background: linear-gradient(135deg, var(--error-500), var(--error-400));
        color: white;
    }
    
    .action-text {
        flex: 1;
    }
    
    .action-title {
        font-weight: 700;
        margin-bottom: var(--space-xs);
        font-size: 1.125rem;
        font-family: 'Cairo', sans-serif;
    }
    
    .action-subtitle {
        font-size: 0.875rem;
        color: var(--admin-secondary-500);
        font-family: 'Cairo', sans-serif;
        line-height: 1.4;
    }
    
    .btn {
        font-family: 'Cairo', sans-serif;
        font-weight: 600;
        border-radius: var(--radius-xl);
        padding: var(--space-md) var(--space-xl);
        transition: all var(--transition-normal);
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-size: 1rem;
        min-width: 130px;
        justify-content: center;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, var(--admin-secondary-200), var(--admin-secondary-100));
        color: var(--admin-secondary-700);
        border: 2px solid var(--admin-secondary-300);
    }
    
    .btn-secondary:hover {
        background: linear-gradient(135deg, var(--admin-secondary-300), var(--admin-secondary-200));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, var(--warning-600), var(--warning-500));
        color: white;
    }
    
    .btn-warning:hover {
        background: linear-gradient(135deg, var(--warning-700), var(--warning-600));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, var(--error-600), var(--error-500));
        color: white;
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, var(--error-700), var(--error-600));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-500));
        color: white;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--admin-primary-700), var(--admin-primary-600));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }
    
    /* نافذة تأكيد الحذف */
    .delete-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    
    .modal-content {
        background: white;
        padding: var(--space-2xl);
        border-radius: var(--radius-2xl);
        max-width: 450px;
        width: 90%;
        text-align: center;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 2px solid var(--admin-secondary-100);
        position: relative;
        overflow: hidden;
    }
    
    .modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--error-500), var(--error-400));
    }
    
    .modal-icon {
        color: var(--error-500);
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    
    .modal-title {
        margin-bottom: var(--space-md);
        color: var(--admin-secondary-900);
        font-family: 'Cairo', sans-serif;
        font-weight: 700;
        font-size: 1.5rem;
    }
    
    .modal-text {
        margin-bottom: var(--space-md);
        color: var(--admin-secondary-600);
        font-family: 'Cairo', sans-serif;
        line-height: 1.6;
        font-size: 1rem;
    }
    
    .warning-box {
        background: linear-gradient(135deg, var(--warning-100), var(--warning-50));
        border: 2px solid var(--warning-200);
        border-radius: var(--radius-xl);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        color: var(--warning-700);
        font-family: 'Cairo', sans-serif;
        line-height: 1.6;
    }
    
    .modal-actions {
        display: flex;
        gap: var(--space-md);
        justify-content: center;
        margin-top: var(--space-xl);
    }
    
    @media (max-width: 968px) {
        .category-show-container {
            margin: 0;
            padding: var(--space-md);
        }
        
        .category-hero {
            grid-template-columns: 1fr;
            text-align: center;
            gap: var(--space-xl);
        }
        
        .content-grid {
            grid-template-columns: 1fr;
        }
        
        .category-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .action-group {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .products-grid {
            grid-template-columns: 1fr;
        }
        
        .category-stats {
            grid-template-columns: 1fr;
        }
        
        .category-name {
            font-size: 2rem;
        }
        
        .modal-content {
            margin: var(--space-md);
            max-width: none;
        }
        
        .modal-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="category-show-container">
    <!-- رأس الفئة -->
    <div class="category-header fade-in">
        <div class="category-hero">
            <div class="category-image-container">
                @if($category->image)
                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="category-image">
                @else
                    <div class="no-image">
                        <i class="fas fa-image"></i>
                    </div>
                @endif
            </div>
            
            <div class="category-info">
                <h1 class="category-name">
                    <i class="fas fa-tag"></i>
                    {{ $category->name }}
                </h1>
                
                <div class="category-slug">{{ $category->slug }}</div>
                
                @if($category->description)
                    <p class="category-description">{{ $category->description }}</p>
                @else
                    <p class="category-description" style="color: var(--admin-secondary-400); font-style: italic;">
                        لم يتم توفير وصف لهذه الفئة.
                    </p>
                @endif
                
                <div class="category-stats">
                    <div class="stat-item">
                        <div class="stat-number">{{ $category->products()->count() }}</div>
                        <div class="stat-label">المنتجات</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-number">{{ $category->products()->where('is_active', true)->count() }}</div>
                        <div class="stat-label">النشطة</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-number">{{ $category->products()->sum('stock') }}</div>
                        <div class="stat-label">إجمالي المخزون</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="category-actions">
            <div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i>
                    العودة للفئات
                </a>
            </div>
            
            <div class="action-group">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    تعديل الفئة
                </a>
                
                <button class="btn btn-danger" onclick="deleteCategory()">
                    <i class="fas fa-trash"></i>
                    حذف الفئة
                </button>
            </div>
        </div>
    </div>
    
    <!-- شبكة المحتوى -->
    <div class="content-grid">
        <!-- المحتوى الرئيسي -->
        <div class="main-content">
            <!-- قسم المنتجات -->
            <div class="products-section fade-in">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-box"></i>
                        المنتجات في هذه الفئة
                        <span class="badge">{{ $category->products()->count() }}</span>
                    </h2>
                    
                    <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="section-action">
                        <i class="fas fa-plus"></i>
                        إضافة منتج
                    </a>
                </div>
                
                @if($category->products()->count() > 0)
                    <div class="products-grid">
                        @foreach($category->products()->latest()->take(8)->get() as $product)
                            <div class="product-card">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="product-image">
                                @else
                                    <div class="product-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                
                                <div class="product-info">
                                    <div class="product-name">{{ $product->name }}</div>
                                    <div class="product-price">${{ number_format($product->price, 2) }}</div>
                                    <div class="product-stock">
                                        المخزون: {{ $product->stock }}
                                        @if($product->stock <= 5)
                                            <span style="color: var(--error-500);">(منخفض)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($category->products()->count() > 8)
                        <div style="padding: var(--space-xl); text-align: center; border-top: 2px solid var(--admin-secondary-200); background: var(--admin-secondary-50);">
                            <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="btn btn-primary">
                                عرض جميع المنتجات ({{ $category->products()->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="empty-products">
                        <div class="empty-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-md); color: var(--admin-secondary-700); font-family: 'Cairo', sans-serif;">لا توجد منتجات بعد</h3>
                        <p style="margin-bottom: var(--space-xl); font-family: 'Cairo', sans-serif;">هذه الفئة لا تحتوي على أي منتجات حتى الآن.</p>
                        <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            إضافة أول منتج
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- الشريط الجانبي -->
        <div class="sidebar-content">
            <!-- معلومات الفئة -->
            <div class="info-card fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        معلومات الفئة
                    </h3>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">تاريخ الإنشاء</span>
                        <span class="info-value">{{ $category->created_at->format('d M, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">آخر تحديث</span>
                        <span class="info-value">{{ $category->updated_at->format('d M, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">رابط الصفحة</span>
                        <span class="info-value" style="font-family: 'Courier New', monospace; direction: ltr; text-align: left;">{{ $category->slug }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">إجمالي المنتجات</span>
                        <span class="info-value">{{ $category->products()->count() }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">المنتجات النشطة</span>
                        <span class="info-value">{{ $category->products()->where('is_active', true)->count() }}</span>
                    </div>
                </div>
            </div>
            
            <!-- الإجراءات السريعة -->
            <div class="quick-actions fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-bolt"></i>
                        الإجراءات السريعة
                    </h3>
                </div>
                
                <div class="quick-actions-list">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="quick-action">
                        <div class="action-icon edit">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">تعديل الفئة</div>
                            <div class="action-subtitle">تحديث الاسم والوصف والصورة</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="quick-action">
                        <div class="action-icon products">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">إدارة المنتجات</div>
                            <div class="action-subtitle">عرض وتعديل منتجات الفئة</div>
                        </div>
                    </a>
                    
                    <a href="#" onclick="deleteCategory(); return false;" class="quick-action">
                        <div class="action-icon delete">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">حذف الفئة</div>
                            <div class="action-subtitle">حذف هذه الفئة نهائياً</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- نافذة تأكيد الحذف -->
<div id="deleteModal" class="delete-modal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="modal-title">حذف الفئة</h3>
        <p class="modal-text">
            هل أنت متأكد من رغبتك في حذف <strong>"{{ $category->name }}"</strong>؟
        </p>
        @if($category->products()->count() > 0)
            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                هذه الفئة تحتوي على {{ $category->products()->count() }} منتج. لا يمكنك حذفها حتى يتم إزالة جميع المنتجات أو نقلها إلى فئة أخرى.
            </div>
        @else
            <p class="modal-text">
                لا يمكن التراجع عن هذا الإجراء.
            </p>
        @endif
        <div class="modal-actions">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">إلغاء</button>
            @if($category->products()->count() == 0)
                <button onclick="confirmDelete()" class="btn btn-danger">حذف</button>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function deleteCategory() {
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    function confirmDelete() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.categories.destroy", $category) }}';
        
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
    
    // إغلاق النافذة عند النقر خارجها
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // تهيئة الرسوم المتحركة
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
        
        // تأثيرات إضافية للبطاقات
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
        
        const quickActions = document.querySelectorAll('.quick-action');
        quickActions.forEach((action, index) => {
            action.style.animationDelay = `${index * 0.2}s`;
        });
    });
    
    // تأثيرات تفاعلية إضافية
    document.addEventListener('DOMContentLoaded', function() {
        // تأثير hover للإحصائيات
        const statItems = document.querySelectorAll('.stat-item');
        statItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.05)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
        
        // تأثير لمعان للبطاقات
        const cards = document.querySelectorAll('.product-card, .info-card, .quick-actions');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = '';
            });
        });
    });
</script>
@endpush