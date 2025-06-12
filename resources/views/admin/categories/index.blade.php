@extends('layouts.admin')

@section('title', __('Categories Management'))
@section('page-title', __('Categories Management'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Categories') }}
    </div>
@endsection

@push('styles')
<style>
    .categories-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .categories-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .categories-stats {
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
    
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .category-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: all var(--transition-normal);
        border: 1px solid var(--admin-secondary-200);
        position: relative;
    }
    
    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }
    
    .category-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, var(--admin-primary-100), var(--admin-primary-200));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-primary-600);
        font-size: 3rem;
    }
    
    .category-content {
        padding: var(--space-lg);
    }
    
    .category-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .category-slug {
        background: var(--admin-secondary-100);
        color: var(--admin-secondary-600);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-family: monospace;
        margin-bottom: var(--space-md);
    }
    
    .category-description {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: var(--space-lg);
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .category-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: var(--space-md);
        border-top: 1px solid var(--admin-secondary-200);
    }
    
    .products-count {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .category-actions {
        display: flex;
        gap: var(--space-xs);
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
    
    .action-btn.edit {
        background: var(--warning-100);
        color: var(--warning-600);
    }
    
    .action-btn.edit:hover {
        background: var(--warning-200);
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
    
    .search-section {
        background: white;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .search-form {
        display: flex;
        gap: var(--space-md);
        align-items: end;
    }
    
    .search-group {
        flex: 1;
    }
    
    .search-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-sm);
    }
    
    .search-input {
        width: 100%;
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        transition: border-color var(--transition-fast);
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    @media (max-width: 768px) {
        .categories-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .categories-stats {
            width: 100%;
            justify-content: space-between;
        }
        
        .categories-grid {
            grid-template-columns: 1fr;
        }
        
        .search-form {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endpush

@section('content')
<!-- Categories Header -->
<div class="categories-header">
    <div>
        <h1 class="categories-title">
            <i class="fas fa-tags"></i>
            {{ __('Categories Management') }}
        </h1>
    </div>
    
    <div class="categories-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $categories->total() }}</div>
            <div class="stat-label">{{ __('Total Categories') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $categories->where('products_count', '>', 0)->count() }}</div>
            <div class="stat-label">{{ __('With Products') }}</div>
        </div>
        
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('Add Category') }}
        </a>
    </div>
</div>

<!-- Search Section -->
<div class="search-section fade-in">
    <form method="GET" action="{{ route('admin.categories.index') }}" class="search-form">
        <div class="search-group">
            <label class="search-label">{{ __('Search Categories') }}</label>
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="{{ __('Search by name, slug, or description...') }}"
                value="{{ request('search') }}"
            >
        </div>
        
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
            {{ __('Search') }}
        </button>
        
        @if(request('search'))
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                {{ __('Clear') }}
            </a>
        @endif
    </form>
</div>

<!-- Categories Grid -->
@if($categories->count() > 0)
    <div class="categories-grid">
        @foreach($categories as $category)
            <div class="category-card fade-in">
                @if($category->image)
                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="category-image">
                @else
                    <div class="category-image">
                        <i class="fas fa-image"></i>
                    </div>
                @endif
                
                <div class="category-content">
                    <h3 class="category-name">
                        {{ $category->name }}
                    </h3>
                    
                    <div class="category-slug">{{ $category->slug }}</div>
                    
                    @if($category->description)
                        <p class="category-description">{{ $category->description }}</p>
                    @else
                        <p class="category-description" style="color: var(--admin-secondary-400); font-style: italic;">
                            {{ __('No description available') }}
                        </p>
                    @endif
                    
                    <div class="category-meta">
                        <div class="products-count">
                            <i class="fas fa-box"></i>
                            {{ $category->products_count ?? 0 }} {{ __('Products') }}
                        </div>
                        
                        <div class="category-actions">
                            <a href="{{ route('admin.categories.show', $category) }}" class="action-btn view" title="{{ __('View Category') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn edit" title="{{ __('Edit Category') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <button class="action-btn delete" onclick="deleteCategory('{{ $category->id }}')" title="{{ __('Delete Category') }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination">
                @if($categories->onFirstPage())
                    <span class="page-link disabled">{{ __('Previous') }}</span>
                @else
                    <a href="{{ $categories->previousPageUrl() }}" class="page-link">{{ __('Previous') }}</a>
                @endif
                
                @foreach($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                    @if($page == $categories->currentPage())
                        <span class="page-link active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($categories->hasMorePages())
                    <a href="{{ $categories->nextPageUrl() }}" class="page-link">{{ __('Next') }}</a>
                @else
                    <span class="page-link disabled">{{ __('Next') }}</span>
                @endif
            </div>
        </div>
    @endif
@else
    <div class="categories-grid">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-tags"></i>
            </div>
            <h3 class="empty-title">{{ __('No Categories Found') }}</h3>
            <p class="empty-text">
                @if(request('search'))
                    {{ __('No categories match your search criteria.') }}
                @else
                    {{ __('Start organizing your products by creating your first category.') }}
                @endif
            </p>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                {{ __('Create First Category') }}
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
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Category') }}</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete this category? This action cannot be undone.') }}
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
    let categoryToDelete = null;
    
    function deleteCategory(categoryId) {
        categoryToDelete = categoryId;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        categoryToDelete = null;
    }
    
    function confirmDelete() {
        if (categoryToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/categories/${categoryToDelete}`;
            
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