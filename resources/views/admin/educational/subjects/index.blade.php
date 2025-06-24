@extends('layouts.admin')

@section('title', 'إدارة المواد الدراسية')
@section('page-title', 'إدارة المواد الدراسية')

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
    <span>إدارة المواد</span>
</div>
@endsection

@push('styles')
<style>
    .subjects-stats {
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
    }
    
    .stat-card.primary::before {
        background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-600));
    }
    
    .stat-card.success::before {
        background: linear-gradient(90deg, var(--success-500), #059669);
    }
    
    .stat-card.warning::before {
        background: linear-gradient(90deg, var(--warning-500), #d97706);
    }
    
    .stat-card.info::before {
        background: linear-gradient(90deg, var(--info-500), #0284c7);
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
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .stat-icon.primary {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
    }
    
    .stat-icon.success {
        background: linear-gradient(135deg, var(--success-500), #059669);
    }
    
    .stat-icon.warning {
        background: linear-gradient(135deg, var(--warning-500), #d97706);
    }
    
    .stat-icon.info {
        background: linear-gradient(135deg, var(--info-500), #0284c7);
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
    
    .subject-card {
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: all var(--transition-fast);
        margin-bottom: var(--space-xl);
    }
    
    .subject-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
        border-color: var(--admin-primary-300);
    }
    
    .subject-header {
        padding: var(--space-xl);
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        border-bottom: 1px solid var(--admin-secondary-200);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .subject-info {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
    }
    
    .subject-icon {
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
    
    .subject-details {
        flex: 1;
    }
    
    .subject-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .subject-meta {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        color: var(--admin-secondary-600);
        font-size: 0.9rem;
    }
    
    .generation-badge {
        background: linear-gradient(135deg, var(--admin-primary-100), var(--admin-primary-200));
        color: var(--admin-primary-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .code-badge {
        background: var(--admin-secondary-100);
        color: var(--admin-secondary-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        font-weight: 500;
        font-family: monospace;
    }
    
    .subject-status {
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
    
    .subject-body {
        padding: var(--space-xl);
    }
    
    .subject-stats {
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
    
    .teachers-section {
        margin-bottom: var(--space-lg);
    }
    
    .teachers-header {
        font-size: 1rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .teachers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-md);
    }
    
    .teacher-item {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md);
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        transition: all var(--transition-fast);
    }
    
    .teacher-item:hover {
        background: var(--admin-primary-50);
        border-color: var(--admin-primary-300);
        transform: translateY(-2px);
    }
    
    .teacher-avatar {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
    }
    
    .teacher-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--admin-secondary-800);
        flex: 1;
    }
    
    .teacher-platforms {
        font-size: 0.8rem;
        color: var(--admin-secondary-500);
    }
    
    .subject-actions {
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
        .subjects-stats {
            grid-template-columns: 1fr;
        }
        
        .subject-header {
            flex-direction: column;
            gap: var(--space-lg);
            text-align: center;
        }
        
        .subject-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .teachers-grid {
            grid-template-columns: 1fr;
        }
        
        .subject-actions {
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
<div class="subjects-stats fade-in">
    <div class="stat-card primary">
        <div class="stat-icon primary">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-number">{{ $subjects->total() }}</div>
        <div class="stat-label">إجمالي المواد</div>
    </div>
    
    <div class="stat-card success">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-number">{{ $subjects->where('is_active', true)->count() }}</div>
        <div class="stat-label">المواد النشطة</div>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-icon warning">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-number">{{ $subjects->sum('teachers_count') }}</div>
        <div class="stat-label">إجمالي المعلمين</div>
    </div>
    
    <div class="stat-card info">
        <div class="stat-icon info">
            <i class="fas fa-users-class"></i>
        </div>
        <div class="stat-number">{{ $generations->count() }}</div>
        <div class="stat-label">الأجيال المتاحة</div>
    </div>
</div>

<!-- Page Header -->
<div class="card fade-in">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-book"></i>
            إدارة المواد الدراسية
        </h2>
        <div style="display: flex; gap: var(--space-md);">
            <a href="{{ route('admin.educational.subjects.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                إضافة مادة جديدة
            </a>
            <button onclick="exportSubjects()" class="btn btn-secondary">
                <i class="fas fa-download"></i>
                تصدير البيانات
            </button>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section fade-in">
    <form method="GET" action="{{ route('admin.educational.subjects.index') }}">
        <div class="filter-row">
            <div class="form-group">
                <label class="form-label">الجيل الدراسي</label>
                <select name="generation_id" class="form-input">
                    <option value="">جميع الأجيال</option>
                    @foreach($generations as $generation)
                        <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                            {{ $generation->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">حالة التفعيل</label>
                <select name="is_active" class="form-input">
                    <option value="">جميع الحالات</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">البحث</label>
                <input type="text" name="search" class="form-input" 
                       placeholder="ابحث في أسماء المواد أو الأكواد..." 
                       value="{{ request('search') }}">
            </div>
            
            <div style="display: flex; gap: var(--space-md);">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i>
                    تطبيق الفلتر
                </button>
                <a href="{{ route('admin.educational.subjects.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    إعادة تعيين
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Subjects List -->
<div class="fade-in">
    @forelse($subjects as $subject)
        <div class="subject-card">
            <div class="subject-header">
                <div class="subject-info">
                    <div class="subject-icon">
                        {{ strtoupper(substr($subject->name, 0, 1)) }}
                    </div>
                    <div class="subject-details">
                        <div class="subject-title">{{ $subject->name }}</div>
                        <div class="subject-meta">
                            <div class="generation-badge">
                                <i class="fas fa-users-class"></i>
                                {{ $subject->generation->display_name }}
                            </div>
                            @if($subject->code)
                                <div class="code-badge">
                                    <i class="fas fa-tag"></i>
                                    {{ $subject->code }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="subject-status">
                    <div class="status-badge {{ $subject->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $subject->is_active ? 'نشط' : 'غير نشط' }}
                    </div>
                    
                    <div style="display: flex; gap: var(--space-sm);">
                        <button onclick="toggleStatus({{ $subject->id }})" 
                                class="btn btn-sm {{ $subject->is_active ? 'btn-warning' : 'btn-success' }}"
                                title="{{ $subject->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                            <i class="fas fa-{{ $subject->is_active ? 'pause' : 'play' }}"></i>
                        </button>
                        
                        <button onclick="showStatistics({{ $subject->id }})" 
                                class="btn btn-sm btn-info"
                                title="عرض الإحصائيات">
                            <i class="fas fa-chart-bar"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="subject-body">
                <div class="subject-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $subject->teachers_count }}</div>
                        <div class="stat-name">المعلمين</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $subject->active_teachers_count }}</div>
                        <div class="stat-name">المعلمين النشطين</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $subject->created_at->format('Y/m/d') }}</div>
                        <div class="stat-name">تاريخ الإضافة</div>
                    </div>
                </div>
                
                @if($subject->teachers_count > 0)
                    <div class="teachers-section">
                        <div class="teachers-header">
                            <i class="fas fa-chalkboard-teacher"></i>
                            المعلمين ({{ $subject->active_teachers_count }}/{{ $subject->teachers_count }})
                        </div>
                        <div class="teachers-grid">
                            @foreach($subject->teachers->take(4) as $teacher)
                                <div class="teacher-item">
                                    <div class="teacher-avatar">
                                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                    </div>
                                    <div class="teacher-name">{{ $teacher->name }}</div>
                                    <div class="teacher-platforms">
                                        {{ $teacher->platforms_count ?? 0 }} منصة
                                    </div>
                                </div>
                            @endforeach
                            @if($subject->teachers_count > 4)
                                <div style="display: flex; align-items: center; justify-content: center; padding: var(--space-md); color: var(--admin-secondary-500); font-size: 0.9rem; border: 2px dashed var(--admin-secondary-300); border-radius: var(--radius-lg);">
                                    +{{ $subject->teachers_count - 4 }} معلم آخر
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div style="text-align: center; padding: var(--space-lg); background: var(--admin-secondary-50); border-radius: var(--radius-lg); color: var(--admin-secondary-500);">
                        <i class="fas fa-user-plus" style="font-size: 2rem; margin-bottom: var(--space-md); opacity: 0.5;"></i>
                        <p>لا يوجد معلمين لهذه المادة حتى الآن</p>
                    </div>
                @endif
                
                <div class="subject-actions">
                    <a href="{{ route('admin.educational.subjects.show', $subject) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i>
                        عرض التفاصيل
                    </a>
                    <a href="{{ route('admin.educational.subjects.edit', $subject) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                        تعديل
                    </a>
                    @if($subject->teachers_count == 0)
                        <button onclick="deleteSubject({{ $subject->id }})" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            حذف
                        </button>
                    @else
                        <button class="btn btn-sm btn-secondary" disabled title="لا يمكن حذف مادة تحتوي على معلمين">
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
                <i class="fas fa-book"></i>
            </div>
            <h3 style="font-weight: 600; margin-bottom: var(--space-md); color: var(--admin-secondary-700);">
                لا توجد مواد دراسية حتى الآن
            </h3>
            <p style="margin-bottom: var(--space-xl);">ابدأ بإضافة المواد الدراسية لتنظيم النظام التعليمي</p>
            <a href="{{ route('admin.educational.subjects.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                إضافة أول مادة دراسية
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($subjects->hasPages())
    <div class="fade-in" style="margin-top: var(--space-2xl);">
        {{ $subjects->links() }}
    </div>
@endif

<!-- Statistics Modal -->
<div id="statisticsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: var(--radius-xl); max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div style="padding: var(--space-xl); border-bottom: 1px solid var(--admin-secondary-200);">
            <h3 style="font-weight: 700; color: var(--admin-secondary-900); margin: 0;">إحصائيات المادة</h3>
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
// Toggle subject status
async function toggleStatus(subjectId) {
    try {
        const response = await fetch(`/admin/educational/subjects/${subjectId}/toggle-status`, {
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

// Show subject statistics
async function showStatistics(subjectId) {
    try {
        const response = await fetch(`/admin/educational/subjects/${subjectId}/statistics`);
        const data = await response.json();
        
        if (data.success) {
            const content = `
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: var(--space-lg);">
                    <div class="stat-item">
                        <div class="stat-value">${data.data.teachers_count}</div>
                        <div class="stat-name">إجمالي المعلمين</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${data.data.active_teachers_count}</div>
                        <div class="stat-name">المعلمين النشطين</div>
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

// Delete subject
async function deleteSubject(subjectId) {
    if (!confirm('هل أنت متأكد من حذف هذه المادة؟ لا يمكن التراجع عن هذا الإجراء.')) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/educational/subjects/${subjectId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            location.reload();
        } else {
            alert('حدث خطأ في حذف المادة');
        }
    } catch (error) {
        alert('حدث خطأ في الاتصال');
    }
}

// Export subjects
function exportSubjects() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = `/admin/educational/subjects/export?${params.toString()}`;
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