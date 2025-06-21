@extends('layouts.admin')

@section('title', 'إدارة الفئات')
@section('page-title', 'إدارة الفئات')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        الفئات
    </div>
@endsection

@push('styles')
<style>
    * {
        direction: rtl;
        text-align: right;
    }
    
    .categories-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .categories-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        font-family: 'Cairo', sans-serif;
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
        padding: var(--space-lg);
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border-radius: var(--radius-xl);
        border: 2px solid var(--admin-secondary-100);
        min-width: 120px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all var(--transition-normal);
    }
    
    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.15);
        border-color: var(--admin-primary-300);
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
        color: var(--admin-secondary-600);
        font-weight: 600;
        font-family: 'Cairo', sans-serif;
    }
    
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .category-card {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all var(--transition-normal);
        border: 2px solid transparent;
        position: relative;
    }
    
    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-400));
    }
    
    .category-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: var(--admin-primary-200);
    }
    
    .category-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background: linear-gradient(135deg, var(--admin-primary-100), var(--admin-primary-200));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-primary-600);
        font-size: 3.5rem;
        position: relative;
    }
    
    .category-image::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40px;
        background: linear-gradient(to top, rgba(0,0,0,0.1), transparent);
    }
    
    .category-content {
        padding: var(--space-xl);
    }
    
    .category-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        font-family: 'Cairo', sans-serif;
    }
    
    .category-slug {
        background: linear-gradient(135deg, var(--admin-secondary-100), var(--admin-secondary-50));
        color: var(--admin-secondary-600);
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-family: 'Courier New', monospace;
        margin-bottom: var(--space-md);
        border: 1px solid var(--admin-secondary-200);
        direction: ltr;
        text-align: left;
    }
    
    .category-description {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        line-height: 1.6;
        margin-bottom: var(--space-lg);
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-family: 'Cairo', sans-serif;
    }
    
    .category-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: var(--space-md);
        border-top: 2px solid var(--admin-secondary-100);
    }
    
    .products-count {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        font-weight: 600;
        background: var(--admin-secondary-50);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-family: 'Cairo', sans-serif;
    }
    
    .category-actions {
        display: flex;
        gap: var(--space-sm);
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.875rem;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .action-btn.view {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-400));
        color: white;
    }
    
    .action-btn.view:hover {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-500));
        transform: scale(1.1);
    }
    
    .action-btn.edit {
        background: linear-gradient(135deg, var(--warning-500), var(--warning-400));
        color: white;
    }
    
    .action-btn.edit:hover {
        background: linear-gradient(135deg, var(--warning-600), var(--warning-500));
        transform: scale(1.1);
    }
    
    .action-btn.delete {
        background: linear-gradient(135deg, var(--error-500), var(--error-400));
        color: white;
    }
    
    .action-btn.delete:hover {
        background: linear-gradient(135deg, var(--error-600), var(--error-500));
        transform: scale(1.1);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
        grid-column: 1 / -1;
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border-radius: var(--radius-2xl);
        border: 2px dashed var(--admin-secondary-300);
    }
    
    .empty-icon {
        font-size: 5rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
        background: linear-gradient(135deg, var(--admin-primary-400), var(--admin-primary-500));
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .empty-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--admin-secondary-700);
        font-family: 'Cairo', sans-serif;
    }
    
    .empty-text {
        margin-bottom: var(--space-xl);
        font-size: 1.125rem;
        font-family: 'Cairo', sans-serif;
    }
    
    .search-section {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border-radius: var(--radius-2xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 2px solid var(--admin-secondary-100);
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
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-sm);
        font-family: 'Cairo', sans-serif;
    }
    
    .search-input {
        width: 100%;
        padding: var(--space-md) var(--space-lg);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        background: white;
        font-family: 'Cairo', sans-serif;
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .btn {
        font-family: 'Cairo', sans-serif;
        font-weight: 600;
        border-radius: var(--radius-xl);
        padding: var(--space-md) var(--space-xl);
        transition: all var(--transition-fast);
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-500));
        color: white;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--admin-primary-700), var(--admin-primary-600));
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, var(--admin-secondary-200), var(--admin-secondary-100));
        color: var(--admin-secondary-700);
    }
    
    .btn-secondary:hover {
        background: linear-gradient(135deg, var(--admin-secondary-300), var(--admin-secondary-200));
        transform: translateY(-2px);
    }
    
    .btn-lg {
        padding: var(--space-lg) var(--space-2xl);
        font-size: 1.125rem;
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
    }
    
    .page-link {
        padding: var(--space-sm) var(--space-md);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        color: var(--admin-secondary-600);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        background: white;
        font-family: 'Cairo', sans-serif;
    }
    
    .page-link:hover {
        background: var(--admin-primary-50);
        border-color: var(--admin-primary-300);
        color: var(--admin-primary-600);
        transform: translateY(-1px);
    }
    
    .page-link.active {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-500));
        border-color: var(--admin-primary-600);
        color: white;
    }
    
    .page-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* RTL Adjustments */
    .fas.fa-chevron-right {
        transform: rotate(180deg);
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
        
        .categories-title {
            font-size: 1.5rem;
        }
        
        .stat-item {
            min-width: 100px;
            padding: var(--space-md);
        }
        
        .stat-number {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- عنوان الفئات -->
<div class="categories-header">
    <div>
        <h1 class="categories-title">
            <i class="fas fa-tags"></i>
            إدارة الفئات
        </h1>
    </div>
    
    <div class="categories-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $categories->total() }}</div>
            <div class="stat-label">إجمالي الفئات</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $categories->where('products_count', '>', 0)->count() }}</div>
            <div class="stat-label">تحتوي على منتجات</div>
        </div>
        
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            إضافة فئة جديدة
        </a>
    </div>
</div>

<!-- قسم البحث -->
<div class="search-section fade-in">
    <form method="GET" action="{{ route('admin.categories.index') }}" class="search-form">
        <div class="search-group">
            <label class="search-label">البحث في الفئات</label>
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="البحث بالاسم أو الرابط أو الوصف..."
                value="{{ request('search') }}"
            >
        </div>
        
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
            بحث
        </button>
        
        @if(request('search'))
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                مسح
            </a>
        @endif
    </form>
</div>

<!-- شبكة الفئات -->
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
                            لا يوجد وصف متاح
                        </p>
                    @endif
                    
                    <div class="category-meta">
                        <div class="products-count">
                            <i class="fas fa-box"></i>
                            {{ $category->products_count ?? 0 }} منتج
                        </div>
                        
                        <div class="category-actions">
                            <a href="{{ route('admin.categories.show', $category) }}" class="action-btn view" title="عرض الفئة">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn edit" title="تعديل الفئة">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <button class="action-btn delete" onclick="deleteCategory('{{ $category->id }}')" title="حذف الفئة">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- ترقيم الصفحات -->
    @if($categories->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination">
                @if($categories->onFirstPage())
                    <span class="page-link disabled">السابق</span>
                @else
                    <a href="{{ $categories->previousPageUrl() }}" class="page-link">السابق</a>
                @endif
                
                @foreach($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                    @if($page == $categories->currentPage())
                        <span class="page-link active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($categories->hasMorePages())
                    <a href="{{ $categories->nextPageUrl() }}" class="page-link">التالي</a>
                @else
                    <span class="page-link disabled">التالي</span>
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
            <h3 class="empty-title">لا توجد فئات</h3>
            <p class="empty-text">
                @if(request('search'))
                    لا توجد فئات تطابق معايير البحث.
                @else
                    ابدأ بتنظيم منتجاتك عن طريق إنشاء فئتك الأولى.
                @endif
            </p>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                إنشاء أول فئة
            </a>
        </div>
    </div>
@endif

<!-- نافذة تأكيد الحذف -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-2xl); max-width: 400px; width: 90%; text-align: center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        <div style="color: var(--error-500); font-size: 4rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900); font-family: 'Cairo', sans-serif; font-weight: 700;">حذف الفئة</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600); font-family: 'Cairo', sans-serif; line-height: 1.6;">
            هل أنت متأكد من رغبتك في حذف هذه الفئة؟ لا يمكن التراجع عن هذا الإجراء.
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center;">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">إلغاء</button>
            <button onclick="confirmDelete()" class="btn" style="background: linear-gradient(135deg, var(--error-600), var(--error-500)); color: white;">حذف</button>
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
    });
</script>
@endpush