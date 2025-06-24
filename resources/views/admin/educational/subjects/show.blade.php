@extends('layouts.admin')

@section('title', 'تفاصيل المادة - ' . $subject->name)
@section('page-title', 'تفاصيل المادة')

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
    <a href="{{ route('admin.educational.subjects.index') }}" class="breadcrumb-link">إدارة المواد</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    <span>{{ $subject->name }}</span>
</div>
@endsection

@push('styles')
<style>
    .subject-header {
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-secondary-50));
        border: 2px solid var(--admin-primary-200);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        margin-bottom: var(--space-2xl);
        position: relative;
        overflow: hidden;
    }
    
    .subject-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-600));
    }
    
    .subject-main {
        display: flex;
        align-items: center;
        gap: var(--space-2xl);
        margin-bottom: var(--space-xl);
    }
    
    .subject-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: 900;
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
        border: 4px solid white;
        flex-shrink: 0;
    }
    
    .subject-details {
        flex: 1;
    }
    
    .subject-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-md);
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-secondary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .subject-meta {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .generation-badge {
        background: linear-gradient(135deg, var(--admin-primary-100), var(--admin-primary-200));
        color: var(--admin-primary-700);
        padding: var(--space-md) var(--space-lg);
        border-radius: var(--radius-lg);
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .code-badge {
        background: var(--admin-secondary-100);
        color: var(--admin-secondary-700);
        padding: var(--space-md) var(--space-lg);
        border-radius: var(--radius-lg);
        font-size: 1rem;
        font-weight: 600;
        font-family: monospace;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .subject-status {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        border-radius: var(--radius-lg);
        font-weight: 600;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-active {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border: 2px solid #22c55e;
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        color: #991b1b;
        border: 2px solid #ef4444;
    }
    
    .subject-actions {
        display: flex;
        gap: var(--space-md);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .stat-card {
        background: white;
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
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        line-height: 1;
    }
    
    .stat-label {
        color: var(--admin-secondary-600);
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .teachers-section {
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        overflow: hidden;
        margin-bottom: var(--space-2xl);
    }
    
    .section-header {
        padding: var(--space-xl);
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        border-bottom: 1px solid var(--admin-secondary-200);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .section-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
    }
    
    .teachers-grid {
        padding: var(--space-xl);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: var(--space-xl);
    }
    
    .teacher-card {
        background: linear-gradient(135deg, var(--admin-secondary-50), white);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        transition: all var(--transition-fast);
    }
    
    .teacher-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        border-color: var(--admin-primary-300);
    }
    
    .teacher-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
    }
    
    .teacher-avatar {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .teacher-info {
        flex: 1;
    }
    
    .teacher-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .teacher-specialization {
        color: var(--admin-secondary-600);
        font-size: 0.9rem;
        margin-bottom: var(--space-sm);
    }
    
    .teacher-status {
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .teacher-active {
        background: #dcfce7;
        color: #166534;
    }
    
    .teacher-inactive {
        background: #fef2f2;
        color: #991b1b;
    }
    
    .platforms-list {
        margin-top: var(--space-md);
    }
    
    .platforms-header {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .platform-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: var(--space-sm) var(--space-md);
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-md);
        margin-bottom: var(--space-sm);
        transition: all var(--transition-fast);
    }
    
    .platform-item:hover {
        background: var(--admin-primary-50);
        border-color: var(--admin-primary-300);
    }
    
    .platform-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--admin-secondary-800);
    }
    
    .platform-packages {
        font-size: 0.8rem;
        color: var(--admin-secondary-500);
    }
    
    .teacher-actions {
        display: flex;
        gap: var(--space-sm);
        margin-top: var(--space-lg);
        padding-top: var(--space-lg);
        border-top: 1px solid var(--admin-secondary-200);
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
    
    .generation-info {
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        overflow: hidden;
        margin-bottom: var(--space-2xl);
    }
    
    .generation-details {
        padding: var(--space-xl);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-xl);
    }
    
    .detail-item {
        text-align: center;
        padding: var(--space-lg);
        background: var(--admin-secondary-50);
        border-radius: var(--radius-lg);
    }
    
    .detail-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-primary-600);
        margin-bottom: var(--space-sm);
    }
    
    .detail-label {
        color: var(--admin-secondary-600);
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    @media (max-width: 768px) {
        .subject-main {
            flex-direction: column;
            text-align: center;
            gap: var(--space-lg);
        }
        
        .subject-icon {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }
        
        .subject-title {
            font-size: 2rem;
        }
        
        .subject-actions {
            flex-direction: column;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .teachers-grid {
            grid-template-columns: 1fr;
        }
        
        .section-header {
            flex-direction: column;
            gap: var(--space-md);
        }
        
        .subject-meta {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Subject Header -->
<div class="subject-header fade-in">
    <div class="subject-main">
        <div class="subject-icon">
            {{ strtoupper(substr($subject->name, 0, 1)) }}
        </div>
        <div class="subject-details">
            <h1 class="subject-title">{{ $subject->name }}</h1>
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
                <div class="subject-status {{ $subject->is_active ? 'status-active' : 'status-inactive' }}">
                    <i class="fas fa-{{ $subject->is_active ? 'check-circle' : 'times-circle' }}"></i>
                    {{ $subject->is_active ? 'نشط' : 'غير نشط' }}
                </div>
            </div>
        </div>
        <div class="subject-actions">
            <button onclick="toggleStatus({{ $subject->id }})" 
                    class="btn {{ $subject->is_active ? 'btn-warning' : 'btn-success' }}">
                <i class="fas fa-{{ $subject->is_active ? 'pause' : 'play' }}"></i>
                {{ $subject->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
            </button>
            <a href="{{ route('admin.educational.subjects.edit', $subject) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                تعديل
            </a>
            @if($subject->teachers->count() == 0)
                <button onclick="deleteSubject({{ $subject->id }})" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                    حذف
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="stats-grid fade-in">
    <div class="stat-card primary">
        <div class="stat-icon primary">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-number">{{ $subject->teachers->count() }}</div>
        <div class="stat-label">إجمالي المعلمين</div>
    </div>
    
    <div class="stat-card success">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-number">{{ $subject->teachers->where('is_active', true)->count() }}</div>
        <div class="stat-label">المعلمين النشطين</div>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-icon warning">
            <i class="fas fa-desktop"></i>
        </div>
        <div class="stat-number">{{ $subject->teachers->sum(function($teacher) { return $teacher->platforms->count(); }) }}</div>
        <div class="stat-label">المنصات</div>
    </div>
    
    <div class="stat-card info">
        <div class="stat-icon info">
            <i class="fas fa-calendar-plus"></i>
        </div>
        <div class="stat-number">{{ $subject->created_at->format('Y/m/d') }}</div>
        <div class="stat-label">تاريخ الإضافة</div>
    </div>
</div>

<!-- Generation Information -->
<div class="generation-info fade-in">
    <div class="section-header">
        <h2 class="section-title">
            <div class="section-icon">
                <i class="fas fa-users-class"></i>
            </div>
            معلومات الجيل الدراسي
        </h2>
    </div>
    
    <div class="generation-details">
        <div class="detail-item">
            <div class="detail-value">{{ $subject->generation->birth_year }}</div>
            <div class="detail-label">سنة الميلاد</div>
        </div>
        <div class="detail-item">
            <div class="detail-value">{{ $subject->generation->age_range }}</div>
            <div class="detail-label">العمر الحالي</div>
        </div>
        <div class="detail-item">
            <div class="detail-value">{{ $subject->generation->current_grade }}</div>
            <div class="detail-label">الصف الحالي</div>
        </div>
        <div class="detail-item">
            <div class="detail-value">{{ $subject->generation->birth_year + 18 }}</div>
            <div class="detail-label">سنة التخرج المتوقعة</div>
        </div>
    </div>
</div>

<!-- Teachers Section -->
<div class="teachers-section fade-in">
    <div class="section-header">
        <h2 class="section-title">
            <div class="section-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            المعلمين ({{ $subject->teachers->count() }})
        </h2>
        <a href="{{ route('admin.educational.teachers.create') }}?subject_id={{ $subject->id }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            إضافة معلم جديد
        </a>
    </div>
    
    @if($subject->teachers->count() > 0)
        <div class="teachers-grid">
            @foreach($subject->teachers as $teacher)
                <div class="teacher-card">
                    <div class="teacher-header">
                        <div class="teacher-avatar">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>
                        <div class="teacher-info">
                            <div class="teacher-name">{{ $teacher->name }}</div>
                            @if($teacher->specialization)
                                <div class="teacher-specialization">{{ $teacher->specialization }}</div>
                            @endif
                            <div class="teacher-status {{ $teacher->is_active ? 'teacher-active' : 'teacher-inactive' }}">
                                {{ $teacher->is_active ? 'نشط' : 'غير نشط' }}
                            </div>
                        </div>
                    </div>
                    
                    @if($teacher->platforms->count() > 0)
                        <div class="platforms-list">
                            <div class="platforms-header">
                                <i class="fas fa-desktop"></i>
                                المنصات ({{ $teacher->platforms->count() }})
                            </div>
                            @foreach($teacher->platforms->take(3) as $platform)
                                <div class="platform-item">
                                    <div class="platform-name">{{ $platform->name }}</div>
                                    <div class="platform-packages">
                                        {{ $platform->educationalPackages->count() ?? 0 }} باقة
                                    </div>
                                </div>
                            @endforeach
                            @if($teacher->platforms->count() > 3)
                                <div style="text-align: center; margin-top: var(--space-sm); color: var(--admin-secondary-500); font-size: 0.9rem;">
                                    +{{ $teacher->platforms->count() - 3 }} منصة أخرى
                                </div>
                            @endif
                        </div>
                    @else
                        <div style="text-align: center; padding: var(--space-md); color: var(--admin-secondary-500); font-size: 0.9rem; background: var(--admin-secondary-50); border-radius: var(--radius-md);">
                            لا توجد منصات حتى الآن
                        </div>
                    @endif
                    
                    <div class="teacher-actions">
                        <a href="{{ route('admin.educational.teachers.show', $teacher) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                            عرض
                        </a>
                        <a href="{{ route('admin.educational.teachers.edit', $teacher) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-edit"></i>
                            تعديل
                        </a>
                        @if($teacher->platforms->count() == 0)
                            <button onclick="deleteTeacher({{ $teacher->id }})" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                                حذف
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <h3 style="font-weight: 600; margin-bottom: var(--space-md); color: var(--admin-secondary-700);">
                لا يوجد معلمين لهذه المادة
            </h3>
            <p style="margin-bottom: var(--space-xl);">ابدأ بإضافة المعلمين لهذه المادة</p>
            <a href="{{ route('admin.educational.teachers.create') }}?subject_id={{ $subject->id }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                إضافة أول معلم
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Toggle subject status
async function toggleStatus(subjectId) {
    if (!confirm('هل أنت متأكد من تغيير حالة التفعيل؟')) {
        return;
    }
    
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

// Delete subject
async function deleteSubject(subjectId) {
    if (!confirm('هل أنت متأكد من حذف هذه المادة؟ لا يمكن التراجع عن هذا الإجراء.')) {
        return;
    }
    
    if (!confirm('تحذير: سيتم حذف جميع البيانات المرتبطة بهذه المادة. هل أنت متأكد تماماً؟')) {
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
            window.location.href = '{{ route("admin.educational.subjects.index") }}';
        } else {
            alert('حدث خطأ في حذف المادة');
        }
    } catch (error) {
        alert('حدث خطأ في الاتصال');
    }
}

// Delete teacher
async function deleteTeacher(teacherId) {
    if (!confirm('هل أنت متأكد من حذف هذا المعلم؟')) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/educational/teachers/${teacherId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            location.reload();
        } else {
            alert('حدث خطأ في حذف المعلم');
        }
    } catch (error) {
        alert('حدث خطأ في الاتصال');
    }
}

// Initialize animations
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
    
    // Add staggered animation to teacher cards
    setTimeout(() => {
        document.querySelectorAll('.teacher-card').forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 150);
        });
    }, 500);
    
    // Add hover effects to teacher cards
    document.querySelectorAll('.teacher-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-2px) scale(1)';
        });
    });
});
</script>
@endpush