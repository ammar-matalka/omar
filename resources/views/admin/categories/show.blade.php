@extends('layouts.admin')

@section('title', __('Category Details'))
@section('page-title', __('Category Details'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.categories.index') }}" class="breadcrumb-link">{{ __('Categories') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ $category->name }}
    </div>
@endsection

@push('styles')
<style>
    .category-show-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .category-header {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .category-hero {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: var(--space-xl);
        padding: var(--space-2xl);
    }
    
    .category-image-container {
        position: relative;
    }
    
    .category-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: var(--radius-lg);
        border: 1px solid var(--admin-secondary-200);
        box-shadow: var(--shadow-md);
    }
    
    .no-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, var(--admin-secondary-100), var(--admin-secondary-200));
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-secondary-500);
        font-size: 3rem;
        border: 1px solid var(--admin-secondary-300);
    }
    
    .category-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .category-name {
        font-size: 2rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .category-slug {
        background: var(--admin-secondary-100);
        color: var(--admin-secondary-600);
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-family: monospace;
        margin-bottom: var(--space-lg);
        display: inline-block;
        width: fit-content;
    }
    
    .category-description {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-xl);
    }
    
    .category-stats {
        display: flex;
        gap: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: var(--space-md);
        background: var(--admin-secondary-50);
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
    
    .category-actions {
        background: var(--admin-secondary-50);
        padding: var(--space-xl);
        border-top: 1px solid var(--admin-secondary-200);
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
        gap: var(--space-xl);
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
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .section-header {
        background: var(--admin-secondary-50);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .section-action {
        color: var(--admin-primary-600);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: color var(--transition-fast);
    }
    
    .section-action:hover {
        color: var(--admin-primary-700);
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: var(--space-lg);
        padding: var(--space-lg);
    }
    
    .product-card {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all var(--transition-fast);
    }
    
    .product-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    
    .product-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        background: var(--admin-secondary-100);
    }
    
    .product-info {
        padding: var(--space-md);
    }
    
    .product-name {
        font-weight: 600;
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
        margin-bottom: var(--space-xs);
        line-height: 1.3;
    }
    
    .product-price {
        color: var(--admin-primary-600);
        font-weight: 700;
        font-size: 1rem;
    }
    
    .product-stock {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
        margin-top: var(--space-xs);
    }
    
    .empty-products {
        text-align: center;
        padding: var(--space-2xl);
        color: var(--admin-secondary-500);
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: var(--space-md);
        opacity: 0.5;
    }
    
    .info-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .info-list {
        padding: var(--space-lg);
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-md) 0;
        border-bottom: 1px solid var(--admin-secondary-100);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 500;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
    }
    
    .info-value {
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
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
    }
    
    .status-active {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .status-inactive {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .quick-actions {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .quick-actions-list {
        padding: var(--space-lg);
    }
    
    .quick-action {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-md);
        text-decoration: none;
        color: var(--admin-secondary-700);
        border-radius: var(--radius-md);
        transition: all var(--transition-fast);
        margin-bottom: var(--space-xs);
    }
    
    .quick-action:hover {
        background: var(--admin-secondary-50);
        color: var(--admin-secondary-900);
        transform: translateX(4px);
    }
    
    .quick-action:last-child {
        margin-bottom: 0;
    }
    
    .action-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        flex-shrink: 0;
    }
    
    .action-icon.edit {
        background: var(--warning-100);
        color: var(--warning-600);
    }
    
    .action-icon.products {
        background: var(--admin-primary-100);
        color: var(--admin-primary-600);
    }
    
    .action-icon.delete {
        background: var(--error-100);
        color: var(--error-600);
    }
    
    .action-text {
        flex: 1;
    }
    
    .action-title {
        font-weight: 500;
        margin-bottom: 2px;
    }
    
    .action-subtitle {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    @media (max-width: 768px) {
        .category-show-container {
            margin: 0;
        }
        
        .category-hero {
            grid-template-columns: 1fr;
            text-align: center;
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
        }
        
        .products-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="category-show-container">
    <!-- Category Header -->
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
                        {{ __('No description provided for this category.') }}
                    </p>
                @endif
                
                <div class="category-stats">
                    <div class="stat-item">
                        <div class="stat-number">{{ $category->products()->count() }}</div>
                        <div class="stat-label">{{ __('Products') }}</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-number">{{ $category->products()->where('is_active', true)->count() }}</div>
                        <div class="stat-label">{{ __('Active') }}</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-number">{{ $category->products()->sum('stock') }}</div>
                        <div class="stat-label">{{ __('Total Stock') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="category-actions">
            <div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Categories') }}
                </a>
            </div>
            
            <div class="action-group">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit Category') }}
                </a>
                
                <button class="btn btn-danger" onclick="deleteCategory()">
                    <i class="fas fa-trash"></i>
                    {{ __('Delete Category') }}
                </button>
            </div>
        </div>
    </div>
    
    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Products Section -->
            <div class="products-section fade-in">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-box"></i>
                        {{ __('Products in this Category') }}
                        <span class="badge badge-secondary">{{ $category->products()->count() }}</span>
                    </h2>
                    
                    <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="section-action">
                        <i class="fas fa-plus"></i>
                        {{ __('Add Product') }}
                    </a>
                </div>
                
                @if($category->products()->count() > 0)
                    <div class="products-grid">
                        @foreach($category->products()->latest()->take(8)->get() as $product)
                            <div class="product-card">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="product-image">
                                @else
                                    <div class="product-image" style="display: flex; align-items: center; justify-content: center; color: var(--admin-secondary-400);">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                
                                <div class="product-info">
                                    <div class="product-name">{{ $product->name }}</div>
                                    <div class="product-price">${{ number_format($product->price, 2) }}</div>
                                    <div class="product-stock">
                                        {{ __('Stock') }}: {{ $product->stock }}
                                        @if($product->stock <= 5)
                                            <span style="color: var(--error-500);">({{ __('Low') }})</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($category->products()->count() > 8)
                        <div style="padding: var(--space-lg); text-align: center; border-top: 1px solid var(--admin-secondary-200);">
                            <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="btn btn-primary">
                                {{ __('View All Products') }} ({{ $category->products()->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="empty-products">
                        <div class="empty-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3>{{ __('No Products Yet') }}</h3>
                        <p>{{ __('This category doesn\'t have any products yet.') }}</p>
                        <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            {{ __('Add First Product') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="sidebar-content">
            <!-- Category Information -->
            <div class="info-card fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Category Information') }}
                    </h3>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">{{ __('Created') }}</span>
                        <span class="info-value">{{ $category->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Last Updated') }}</span>
                        <span class="info-value">{{ $category->updated_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('URL Slug') }}</span>
                        <span class="info-value" style="font-family: monospace;">{{ $category->slug }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Total Products') }}</span>
                        <span class="info-value">{{ $category->products()->count() }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Active Products') }}</span>
                        <span class="info-value">{{ $category->products()->where('is_active', true)->count() }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-bolt"></i>
                        {{ __('Quick Actions') }}
                    </h3>
                </div>
                
                <div class="quick-actions-list">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="quick-action">
                        <div class="action-icon edit">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Edit Category') }}</div>
                            <div class="action-subtitle">{{ __('Update name, description, and image') }}</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="quick-action">
                        <div class="action-icon products">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Manage Products') }}</div>
                            <div class="action-subtitle">{{ __('View and edit category products') }}</div>
                        </div>
                    </a>
                    
                    <a href="#" onclick="deleteCategory(); return false;" class="quick-action">
                        <div class="action-icon delete">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Delete Category') }}</div>
                            <div class="action-subtitle">{{ __('Permanently remove this category') }}</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-xl); max-width: 400px; width: 90%; text-align: center;">
        <div style="color: var(--error-500); font-size: 3rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Category') }}</h3>
        <p style="margin-bottom: var(--space-md); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete') }} <strong>"{{ $category->name }}"</strong>?
        </p>
        @if($category->products()->count() > 0)
            <div style="background: var(--warning-100); border: 1px solid var(--warning-200); border-radius: var(--radius-md); padding: var(--space-md); margin-bottom: var(--space-lg); color: var(--warning-700);">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __('This category has') }} {{ $category->products()->count() }} {{ __('products. You cannot delete it until all products are removed or moved to another category.') }}
            </div>
        @else
            <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
                {{ __('This action cannot be undone.') }}
            </p>
        @endif
        <div style="display: flex; gap: var(--space-md); justify-content: center;">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">{{ __('Cancel') }}</button>
            @if($category->products()->count() == 0)
                <button onclick="confirmDelete()" class="btn btn-danger">{{ __('Delete') }}</button>
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
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
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
</script>
@endpush    