@extends('layouts.admin')

@section('title', 'إدارة الأجيال')
@section('page-title', 'إدارة الأجيال')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    <span>النظام التعليمي</span>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    <span>إدارة الأجيال</span>
</div>
@endsection

@push('styles')
<style>
    .generation-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .stat-card {
        background: linear-gradient(135deg, white, var(--admin-secondary-50));
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        text-align: center;
        transition: all var(--transition-fast);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-600));
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--admin-primary-300);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto var(--space-lg);
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 900;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
    }
    
    .stat-label {
        color: var(--admin-secondary-600);
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .generation-card {
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: all var(--transition-fast);
        margin-bottom: var(--space-xl);
    }
    
    .generation-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
        border-color: var(--admin-primary-300);
    }
    
    .generation-header {
        padding: var(--space-xl);
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        border-bottom: 1px solid var(--admin-secondary-200);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .generation-info {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
    }
    
    .generation-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .generation-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .generation-year {
        color: var(--admin-secondary-600);
        font-size: 0.9rem;
    }
    
    .generation-status {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .status-badge {
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-md);
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-active {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        color: #991b1b;
    }
    
    .generation-body {
        padding: var(--space-xl);
    }
    
    .generation-stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .stat-item {
        text-align: center;
        padding: var(--space-md);
        background: var(--admin-secondary-50);
        border-radius: var(--radius-lg);
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-primary-600);
        margin-bottom: var(--space-xs);
    }
    
    .stat-name {
        font-size: 0.8rem;
        color: var(--admin-secondary-600);
        font-weight: 500;
    }
    
    .generation-actions {
        display: flex;
        gap: var(--space-md);
        justify-content: flex-end;
        padding-top: var(--space-lg);
        border-top: 1px solid var(--admin-secondary-200);
        margin-top: var(--space-lg);
    }
    
    .filter-section {
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        align-items: end;
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .generation-stats {
            grid-template-columns: 1fr;
        }
        
        .generation-header {
            flex-direction: column;
            gap: var(--space-lg);
            text-align: center;
        }
        
        .generation-stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .generation-actions {
            flex-direction: column;
        }
        
        .filter-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="generation-stats fade-in">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users-class"></i>
        </div>
        <div class="stat-number">{{ $generations->total() }}</div>
        <div class="stat-label">إجمالي الأجيال</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-number">{{ $generations->where('is_active', true)->count() }}</div>
        <div class="stat-label">الأجيال النشطة</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-number">{{ $generations->sum('subjects_count') }}</div>
        <div class="stat-label">إجمالي المواد</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-number">{{ $generations->sum('active_subjects_count') }}</div>
        <div class="stat-label">المواد النشطة</div>
    </div>
</div>

<!-- Page Header -->
<div class="card fade-in">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-users-class"></i>
            إدارة الأجيال الدراسية
        </h2>
        <div style="display: flex; gap: var(--space-md);">
            <a href="{{ route('admin.educational.generations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                إضافة جيل جديد
            </a>
            <button onclick="exportGenerations()" class="btn btn-secondary">
                <i class="fas fa-download"></i>
                تصدير البيانات
            </button>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section fade-in">
    <form method="GET" action="{{ route('admin.educational.generations.index') }}">
        <div class="filter-row">
            <div class="form-group">
                <label class="form-label">حالة التفعيل</label>
                <select name="is_active" class="form-input">
                    <option value="">جميع الحالات</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">ترتيب حسب</label>
                <select name="sort" class="form-input">
                    <option value="birth_year" {{ request('sort') === 'birth_year' ? 'selected' : '' }}>سنة الميلاد</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>الاسم</option>
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>تاريخ الإنشاء</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">اتجاه الترتيب</label>
                <select name="order" class="form-input">
                    <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>تنازلي</option>
                    <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>تصاعدي</option>
                </select>
            </div>
            
            <div style="display: flex; gap: var(--space-md);">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i>
                    تطبيق الفلتر
                </button>
                <a href="{{ route('admin.educational.generations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    إعادة تعيين
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Generations List -->
<div class="fade-in">
    @forelse($generations as $generation)
        <div class="generation-card">
            <div class="generation-header">
                <div class="generation-info">
                    <div class="generation-icon">
                        {{ $generation->birth_year }}
                    </div>
                    <div>
                        <div class="generation-title">{{ $generation->display_name }}</div>
                        <div class="generation-year">
                            مواليد {{ $generation->birth_year }} - {{ $generation->current_grade }}
                        </div>
                    </div>
                </div>
                
                <div class="generation-status">
                    <div class="status-badge {{ $generation->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $generation->is_active ? 'نشط' : 'غير نشط' }}
                    </div>
                    
                    <div style="display: flex; gap: var(--space-sm);">
                        <button onclick="toggleStatus({{ $generation->id }})" 
                                class="btn btn-sm {{ $generation->is_active ? 'btn-warning' : 'btn-success' }}"
                                title="{{ $generation->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                            <i class="fas fa-{{ $generation->is_active ? 'pause' : 'play' }}"></i>
                        </button>
                        
                        <button onclick="showStatistics({{ $generation->id }})" 
                                class="btn btn-sm btn-info"
                                title="عرض الإحصائيات">
                            <i class="fas fa-chart-bar"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="generation-body">
                <div class="generation-stats-row">
                    <div class="stat-item">
                        <div class="stat-value">{{ $generation->subjects_count }}</div>
                        <div class="stat-name">إجمالي المواد</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $generation->active_subjects_count }}</div>
                        <div class="stat-name">المواد النشطة</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $generation->age_range }}</div>
                        <div class="stat-name">العمر الحالي</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $generation->created_at->format('Y/m/d') }}</div>
                        <div class="stat-name">تاريخ الإضافة</div>
                    </div>
                </div>
                
                @if($generation->subjects_count > 0)
                    <div style="margin-bottom: var(--space-lg);">
                        <h4 style="font-size: 1rem; font-weight: 600; color: var(--admin-secondary-700); margin-bottom: var(--space-md);">
                            <i class="fas fa-book" style="margin-left: var(--space-sm);"></i>
                            المواد المتاحة ({{ $generation->active_subjects_count }}/{{ $generation->subjects_count }})
                        </h4>
                        <div style="display: flex; flex-wrap: wrap; gap: var(--space-sm);">
                            @foreach($generation->subjects->take(5) as $subject)
                                <span class="badge {{ $subject->is_active ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $subject->name }}
                                </span>
                            @endforeach
                            @if($generation->subjects_count > 5)
                                <span class="badge badge-info">+{{ $generation->subjects_count - 5 }} مواد أخرى</span>
                            @endif
                        </div>
                    </div>
                @endif
                
                <div class="generation-actions">
                    <a href="{{ route('admin.educational.generations.show', $generation) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i>
                        عرض التفاصيل
                    </a>
                    <a href="{{ route('admin.educational.generations.edit', $generation) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                        تعديل
                    </a>
                    @if($generation->subjects_count == 0)
                        <button onclick="deleteGeneration({{ $generation->id }})" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            حذف
                        </button>
                    @else
                        <button class="btn btn-sm btn-secondary" disabled title="لا يمكن حذف جيل يحتوي على مواد">
                            <i class="fas fa-lock"></i>
                            محمي
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users-class"></i>
            </div>
            <h3 style="font-weight: 600; margin-bottom: var(--space-md); color: var(--admin-secondary-700);">
                لا توجد أجيال دراسية حتى الآن
            </h3>
            <p style="margin-bottom: var(--space-xl);">ابدأ بإضافة الأجيال الدراسية لتنظيم النظام التعليمي</p>
            <a href="{{ route('admin.educational.generations.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                إضافة أول جيل دراسي
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($generations->hasPages())
    <div class="fade-in" style="margin-top: var(--space-2xl);">
        {{ $generations->links() }}
    </div>
@endif

<!-- Statistics Modal -->
<div id="statisticsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: var(--radius-xl); max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div style="padding: var(--space-xl); border-bottom: 1px solid var(--admin-secondary-200);">
            <h3 style="font-weight: 700; color: var(--admin-secondary-900); margin: 0;">إحصائيات الجيل</h3>
        </div>
        <div id="statisticsContent" style="padding: var(--space-xl);">
            <!-- Statistics content will be loaded here -->
        </div>
        <div style="padding: var(--space-xl); border-top: 1px solid var(--admin-secondary-200); text-align: center;">
            <button onclick="closeModal()" class="btn btn-secondary">إغلاق</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle generation status
async function toggleStatus(generationId) {
    try {
        const response = await fetch(`/admin/educational/generations/${generationId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert('حدث خطأ في تحديث الحالة');
        }
    } catch (error) {
        alert('حدث خطأ في الاتصال');
    }
}

// Show generation statistics
async function showStatistics(generationId) {
    try {
        const response = await fetch(`/admin/educational/generations/${generationId}/statistics`);
        const data = await response.json();
        
        if (data.success) {
            const content = `
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: var(--space-lg);">
                    <div class="stat-item">
                        <div class="stat-value">${data.data.subjects_count}</div>
                        <div class="stat-name">إجمالي المواد</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${data.data.active_subjects_count}</div>
                        <div class="stat-name">المواد النشطة</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${data.data.teachers_count}</div>
                        <div class="stat-name">المعلمين</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${data.data.platforms_count}</div>
                        <div class="stat-name">المنصات</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${data.data.cards_count}</div>
                        <div class="stat-name">البطاقات</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${data.data.active_cards_count}</div>
                        <div class="stat-name">البطاقات النشطة</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${data.data.orders_count}</div>
                        <div class="stat-name">الطلبات</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${data.data.total_revenue} د.أ</div>
                        <div class="stat-name">إجمالي الإيرادات</div>
                    </div>
                </div>
            `;
            
            document.getElementById('statisticsContent').innerHTML = content;
            document.getElementById('statisticsModal').style.display = 'flex';
        }
    } catch (error) {
        alert('حدث خطأ في تحميل الإحصائيات');
    }
}

// Delete generation
async function deleteGeneration(generationId) {
    if (!confirm('هل أنت متأكد من حذف هذا الجيل؟ لا يمكن التراجع عن هذا الإجراء.')) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/educational/generations/${generationId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            location.reload();
        } else {
            alert('حدث خطأ في حذف الجيل');
        }
    } catch (error) {
        alert('حدث خطأ في الاتصال');
    }
}

// Export generations
function exportGenerations() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = `/admin/educational/generations/export?${params.toString()}`;
}

// Close modal
function closeModal() {
    document.getElementById('statisticsModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('statisticsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Initialize animations on page load
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endpush