@extends('layouts.admin')

@section('title', __('Products Management'))
@section('page-title', __('Products Management'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Products') }}
    </div>
@endsection

@push('styles')
<style>
    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .products-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
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
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .product-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: all var(--transition-normal);
        border: 1px solid var(--admin-secondary-200);
        position: relative;
    }
    
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }
    
    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, var(--admin-secondary-100), var(--admin-secondary-200));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-secondary-500);
        font-size: 3rem;
        position: relative;
    }
    
    .product-status {
        position: absolute;
        top: var(--space-sm);
        right: var(--space-sm);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-active {
        background: var(--success-500);
        color: white;
    }
    
    .status-inactive {
        background: var(--error-500);
        color: white;
    }
    
    .stock-badge {
        position: absolute;
        top: var(--space-sm);
        left: var(--space-sm);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .stock-high {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .stock-low {
        background: var(--warning-100);
        color: var(--warning-700);
        border: 1px solid var(--warning-200);
    }
    
    .stock-out {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .product-content {
        padding: var(--space-lg);
    }
    
    .product-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-category {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 500;
        margin-bottom: var(--space-md);
        display: inline-block;
    }
    
    .product-description {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        line-height: 1.5;
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
        padding-top: var(--space-md);
        border-top: 1px solid var(--admin-secondary-200);
        margin-bottom: var(--space-md);
    }
    
    .product-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--admin-primary-600);
    }
    
    .product-stock {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .product-actions {
        display: flex;
        gap: var(--space-xs);
        justify-content: center;
    }
    
    .action-btn {
        flex: 1;
        padding: var(--space-sm);
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.875rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-xs);
        font-weight: 500;
    }
    
    .action-btn.view {
        background: var(--admin-primary-100);
        color: var(--admin-primary-600);
    }
    
    .action-btn.view:hover {
        background: var(--admin-primary-200);
        transform: translateY(-1px);
    }
    
    .action-btn.edit {
        background: var(--warning-100);
        color: var(--warning-600);
    }
    
    .action-btn.edit:hover {
        background: var(--warning-200);
        transform: translateY(-1px);
    }
    
    .action-btn.delete {
        background: var(--error-100);
        color: var(--error-600);
    }
    
    .action-btn.delete:hover {
        background: var(--error-200);
        transform: translateY(-1px);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
        grid-column: 1 / -1;
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: var(--space-sm);
        color: var(--admin-secondary-700);
    }
    
    .empty-text {
        margin-bottom: var(--space-xl);
        font-size: 1.125rem;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: var(--space-xl);
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
    
    .view-toggle {
        display: flex;
        gap: var(--space-xs);
        background: var(--admin-secondary-100);
        padding: var(--space-xs);
        border-radius: var(--radius-md);
    }
    
    .view-btn {
        padding: var(--space-sm);
        border: none;
        background: transparent;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all var(--transition-fast);
        color: var(--admin-secondary-600);
    }
    
    .view-btn.active {
        background: white;
        color: var(--admin-primary-600);
        box-shadow: var(--shadow-sm);
    }
    
    /* Table View Styles */
    .products-table-container {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
        display: none;
    }
    
    .products-table-container.active {
        display: block;
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
    
    .products-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .products-table th {
        background: var(--admin-secondary-50);
        padding: var(--space-md) var(--space-lg);
        text-align: left;
        font-weight: 600;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
        border-bottom: 1px solid var(--admin-secondary-200);
        white-space: nowrap;
    }
    
    .products-table td {
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-100);
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    .products-table tbody tr {
        transition: background-color var(--transition-fast);
    }
    
    .products-table tbody tr:hover {
        background: var(--admin-secondary-50);
    }
    
    .product-info {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .product-thumb {
        width: 50px;
        height: 50px;
        border-radius: var(--radius-md);
        object-fit: cover;
        border: 1px solid var(--admin-secondary-200);
        flex-shrink: 0;
    }
    
    .product-details {
        min-width: 0;
        flex: 1;
    }
    
    .product-title {
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .product-category-small {
        color: var(--admin-secondary-500);
        font-size: 0.75rem;
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
    }
</style>
@endpush

@section('content')
<!-- Products Header -->
<div class="products-header">
    <div>
        <h1 class="products-title">
            <i class="fas fa-box"></i>
            {{ __('Products Management') }}
        </h1>
    </div>
    
    <div class="products-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $products->total() }}</div>
            <div class="stat-label">{{ __('Total Products') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $products->where('is_active', true)->count() }}</div>
            <div class="stat-label">{{ __('Active') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $products->where('stock', '<=', 5)->count() }}</div>
            <div class="stat-label">{{ __('Low Stock') }}</div>
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
            {{ __('Add Product') }}
        </a>
    </div>
</div>

<!-- Filters Section -->
<div class="filters-section fade-in">
    <h2 class="filters-title">
        <i class="fas fa-filter"></i>
        {{ __('Filter Products') }}
    </h2>
    
    <form method="GET" action="{{ route('admin.products.index') }}">
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">{{ __('Search') }}</label>
                <input 
                    type="text" 
                    name="search" 
                    class="filter-input" 
                    placeholder="{{ __('Search by name or description...') }}"
                    value="{{ request('search') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Category') }}</label>
                <select name="category" class="filter-input">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Status') }}</label>
                <select name="status" class="filter-input">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Stock Status') }}</label>
                <select name="stock_status" class="filter-input">
                    <option value="">{{ __('All Stock') }}</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>{{ __('In Stock') }}</option>
                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>{{ __('Low Stock') }}</option>
                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>{{ __('Out of Stock') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Price Range') }}</label>
                <input 
                    type="number" 
                    name="min_price" 
                    class="filter-input" 
                    placeholder="{{ __('Min Price') }}"
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
                    placeholder="{{ __('Max Price') }}"
                    value="{{ request('max_price') }}"
                    step="0.01"
                >
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                {{ __('Filter') }}
            </button>
            
            @if(request()->hasAny(['search', 'category', 'status', 'stock_status', 'min_price', 'max_price']))
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('Clear Filters') }}
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
                        {{ $product->is_active ? __('Active') : __('Inactive') }}
                    </div>
                    
                    <div class="stock-badge 
                        @if($product->stock <= 0) stock-out
                        @elseif($product->stock <= 5) stock-low
                        @else stock-high
                        @endif">
                        @if($product->stock <= 0)
                            {{ __('Out of Stock') }}
                        @elseif($product->stock <= 5)
                            {{ __('Low Stock') }}
                        @else
                            {{ __('In Stock') }}
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
                            {{ __('No description available') }}
                        </p>
                    @endif
                    
                    <div class="product-meta">
                        <div class="product-price">${{ number_format($product->price, 2) }}</div>
                        <div class="product-stock">
                            <i class="fas fa-boxes"></i>
                            {{ $product->stock }} {{ __('units') }}
                        </div>
                    </div>
                    
                    <div class="product-actions">
                        <a href="{{ route('admin.products.show', $product) }}" class="action-btn view">
                            <i class="fas fa-eye"></i>
                            {{ __('View') }}
                        </a>
                        
                        <a href="{{ route('admin.products.edit', $product) }}" class="action-btn edit">
                            <i class="fas fa-edit"></i>
                            {{ __('Edit') }}
                        </a>
                        
                        <button class="action-btn delete" onclick="deleteProduct('{{ $product->id }}')">
                            <i class="fas fa-trash"></i>
                            {{ __('Delete') }}
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
                {{ __('Products List') }}
            </h3>
        </div>
        
        <table class="products-table">
            <thead>
                <tr>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Stock') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Actions') }}</th>
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
                                    <div class="product-category-small">ID: {{ $product->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($product->category)
                                <span class="badge badge-info">{{ $product->category->name }}</span>
                            @else
                                <span class="badge badge-secondary">{{ __('No Category') }}</span>
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
                                {{ $product->stock }} {{ __('units') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $product->is_active ? __('Active') : __('Inactive') }}
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
                    <span class="page-link disabled">{{ __('Previous') }}</span>
                @else
                    <a href="{{ $products->appends(request()->query())->previousPageUrl() }}" class="page-link">{{ __('Previous') }}</a>
                @endif
                
                @foreach($products->appends(request()->query())->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if($page == $products->currentPage())
                        <span class="page-link active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($products->hasMorePages())
                    <a href="{{ $products->appends(request()->query())->nextPageUrl() }}" class="page-link">{{ __('Next') }}</a>
                @else
                    <span class="page-link disabled">{{ __('Next') }}</span>
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
            <h3 class="empty-title">{{ __('No Products Found') }}</h3>
            <p class="empty-text">
                @if(request()->hasAny(['search', 'category', 'status', 'stock_status', 'min_price', 'max_price']))
                    {{ __('No products match your search criteria.') }}
                @else
                    {{ __('Start building your inventory by adding your first product.') }}
                @endif
            </p>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                {{ __('Create First Product') }}
            </a>
        </div>
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-xl); max-width: 400px; width: 90%; text-align: center;">
        <div style="color: var(--error-500); font-size: 3rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Product') }}</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete this product? This action cannot be undone.') }}
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center;">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">{{ __('Cancel') }}</button>
            <button onclick="confirmDelete()" class="btn btn-danger">{{ __('Delete') }}</button>
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
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
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
    
    // Initialize view preference
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('productsView') || 'grid';
        switchView(savedView);
        
        // Initialize animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 50);
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
    
    // Search functionality enhancement
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit search after 1 second of inactivity
                // Uncomment if you want live search
                // this.form.submit();
            }, 1000);
        });
    }
    
    // Enhanced filtering
    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('change', function() {
            // Auto-submit on select changes
            if (this.tagName === 'SELECT') {
                // Uncomment if you want auto-submit on select changes
                // this.form.submit();
            }
        });
    });
</script>
@endpush