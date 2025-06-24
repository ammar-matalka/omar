@extends('layouts.admin')

@section('title', 'تفاصيل الجيل - ' . $generation->display_name)
@section('page-title', 'تفاصيل الجيل')

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
    <a href="{{ route('admin.educational.generations.index') }}" class="breadcrumb-link">إدارة الأجيال</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    <span>{{ $generation->display_name }}</span>
</div>
@endsection

@push('styles')
<style>
    .generation-header {
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-secondary-50));
        border: 2px solid var(--admin-primary-200);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        margin-bottom: var(--space-2xl);
        position: relative;
        overflow: hidden;
    }
    
    .generation-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-600));
    }
    
    .generation-main {
        display: flex;
        align-items: center;
        gap: var(--space-2xl);
        margin-bottom: var(--space-xl);
    }
    
    .generation-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 900;
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
        border: 4px solid white;
        flex-shrink: 0;
    }
    
    .generation-details {
        flex: 1;
    }
    
    .generation-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-md);
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-secondary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .generation-subtitle {
        font-size: 1.25rem;
        color: var(--admin-secondary-600);
        margin-bottom: var(--space-lg);
        font-weight: 500;
    }
    
    .generation-status {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        border-radius: var(--radius-lg);
        font-weight: 600;
        font-size: 0.9rem;
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
    
    .generation-actions {
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
    
    .subjects-section {
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
    
    .subjects-grid {
        padding: var(--space-xl);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-xl);
    }
    
    .subject-card {
        background: linear-gradient(135deg, var(--admin-secondary-50), white);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        transition: all var(--transition-fast);
    }
    
    .subject-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        border-color: var(--admin-primary-300);
    }
    
    .subject-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: var(--space-md);
    }
    
    .subject-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .subject-status {
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .subject-active {
        background: #dcfce7;
        color: #166534;
    }
    
    .subject-inactive {
        background: #fef2f2;
        color: #991b1b;
    }
    
    .subject-code {
        font-size: 0.8rem;
        color: var(--admin-secondary-500);
        font-weight: 500;
        margin-bottom: var(--space-md);
    }
    
    .teachers-list {
        margin-top: var(--space-md);
    }
    
    .teachers-header {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .teacher-item {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm);
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-md);
        margin-bottom: var(--space-sm);
        transition: all var(--transition-fast);
    }
    
    .teacher-item:hover {
        background: var(--admin-primary-50);
        border-color: var(--admin-primary-300);
    }
    
    .teacher-avatar {
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.8rem;
    }
    
    .teacher-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--admin-secondary-800);
    }
    
    .teacher-platforms {
        font-size: 0.8rem;
        color: var(--admin-secondary-500);
        margin-right: auto;
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
    
    .timeline-section {
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        overflow: hidden;
        margin-bottom: var(--space-2xl);
    }
    
    .timeline {
        padding: var(--space-xl);
    }
    
    .timeline-item {
        display: flex;
        gap: var(--space-lg);
        margin-bottom: var(--space-xl);
        position: relative;
    }
    
    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 19px;
        top: 40px;
        bottom: -20px;
        width: 2px;
        background: var(--admin-secondary-200);
    }
    
    .timeline-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .timeline-content {
        flex: 1;
    }
    
    .timeline-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .timeline-date {
        font-size: 0.9rem;
        color: var(--admin-secondary-500);
        margin-bottom: var(--space-sm);
    }
    
    .timeline-description {
        color: var(--admin-secondary-600);
        line-height: 1.6;
    }
    
    @media (max-width: 768px) {
        .generation-main {
            flex-direction: column;
            text-align: center;
            gap: var(--space-lg);
        }
        
        .generation-icon {
            width: 80px;
            height: 80px;
            font-size: 1.5rem;
        }
        
        .generation-title {
            font-size: 2rem;
        }
        
        .generation-actions {
            flex-direction: column;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .subjects-grid {
            grid-template-columns: 1fr;
        }
        
        .section-header {
            flex-direction: column;
            gap: var(--space-md);
        }
    }
</style>
@endpush

@section('content')
<!-- Generation Header -->
<div class="generation-header fade-in">
    <div class="generation-main">
        <div class="generation-icon">
            {{ $generation->birth_year }}
        </div>
        <div class="generation-details">
            <h1 class="generation-title">{{ $generation->display_name }}</h1>
            <p class="generation-subtitle">
                مواليد {{ $generation->birth_year }} • {{ $generation->current_grade }} • {{ $generation->age_range }}
            </p>
            <div class="generation-status {{ $generation->is_active ? 'status-active' : 'status-inactive' }}">
                <i class="fas fa-{{ $generation->is_active ? 'check-circle' : 'times-circle' }}"></i>
                {{ $generation->is_active ? 'نشط' : 'غير نشط' }}
            </div>
        </div>
        <div class="generation-actions">
            <button onclick="toggleStatus({{ $generation->id }})" 
                    class="btn {{ $generation->is_active ? 'btn-warning' : 'btn-success' }}">
                <i class="fas fa-{{ $generation->is_active ? 'pause' : 'play' }}"></i>
                {{ $generation->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
            </button>
            <a href="{{ route('admin.educational.generations.edit', $generation) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                تعديل
            </a>
            @if($generation->subjects->count() == 0)
                <button onclick="deleteGeneration({{ $generation->id }})" class="btn btn-danger">
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
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-number">{{ $generation->subjects->count() }}</div>
        <div class="stat-label">إجمالي المواد</div>
    </div>
    
    <div class="stat-card success">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-number">{{ $generation->subjects->where('is_active', true)->count() }}</div>
        <div class="stat-label">المواد النشطة</div>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-icon warning">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-number">{{ $generation->subjects->sum(function($subject) { return $subject->teachers->count(); }) }}</div>
        <div class="stat-label">المعلمين</div>
    </div>
    
    <div class="stat-card info">
        <div class="stat-icon info">
            <i class="fas fa-desktop"></i>
        </div>
        <div class="stat-number">{{ $generation->subjects->sum(function($subject) { return $subject->teachers->sum(function($teacher) { return $teacher->platforms->count(); }); }) }}</div>
        <div class="stat-label">المنصات</div>
    </div>
</div>

<!-- Subjects Section -->
<div class="subjects-section fade-in">
    <div class="section-header">
        <h2 class="section-title">
            <div class="section-icon">
                <i class="fas fa-book"></i>
            </div>
            المواد الدراسية ({{ $generation->subjects->count() }})
        </h2>
        <a href="{{ route('admin.educational.subjects.create') }}?generation_id={{ $generation->id }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            إضافة مادة جديدة
        </a>
    </div>
    
    @if($generation->subjects->count() > 0)
        <div class="subjects-grid">
            @foreach($generation->subjects as $subject)
                <div class="subject-card">
                    <div class="subject-header">
                        <div class="subject-name">
                            <i class="fas fa-book-open"></i>
                            {{ $subject->name }}
                        </div>
                        <div class="subject-status {{ $subject->is_active ? 'subject-active' : 'subject-inactive' }}">
                            {{ $subject->is_active ? 'نشط' : 'غير نشط' }}
                        </div>
                    </div>
                    
                    @if($subject->code)
                        <div class="subject-code">
                            <i class="fas fa-tag"></i>
                            كود المادة: {{ $subject->code }}
                        </div>
                    @endif
                    
                    <div class="teachers-list">
                        <div class="teachers-header">
                            <i class="fas fa-users"></i>
                            المعلمين ({{ $subject->teachers->count() }})
                        </div>
                        
                        @forelse($subject->teachers->take(3) as $teacher)
                            <div class="teacher-item">
                                <div class="teacher-avatar">
                                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                </div>
                                <div class="teacher-name">{{ $teacher->name }}</div>
                                <div class="teacher-platforms">
                                    {{ $teacher->platforms->count() }} منصة
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: var(--space-md); color: var(--admin-secondary-500); font-size: 0.9rem;">
                                لا يوجد معلمين حتى الآن
                            </div>
                        @endforelse
                        
                        @if($subject->teachers->count() > 3)
                            <div style="text-align: center; margin-top: var(--space-sm);">
                                <a href="{{ route('admin.educational.subjects.show', $subject) }}" class="btn btn-sm btn-secondary">
                                    عرض جميع المعلمين (+{{ $subject->teachers->count() - 3 }})
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <div style="display: flex; gap: var(--space-sm); margin-top: var(--space-lg); padding-top: var(--space-lg); border-top: 1px solid var(--admin-secondary-200);">
                        <a href="{{ route('admin.educational.subjects.show', $subject) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                            عرض
                        </a>
                        <a href="{{ route('admin.educational.subjects.edit', $subject) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-edit"></i>
                            تعديل
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-book"></i>
            </div>
            <h3 style="font-weight: 600; margin-bottom: var(--space-md); color: var(--admin-secondary-700);">
                لا توجد مواد دراسية لهذا الجيل
            </h3>
            <p style="margin-bottom: var(--space-xl);">ابدأ بإضافة المواد الدراسية لهذا الجيل</p>
            <a href="{{ route('admin.educational.subjects.create') }}?generation_id={{ $generation->id }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                إضافة أول مادة دراسية
            </a>
        </div>
    @endif
</div>

<!-- Timeline Section -->
<div class="timeline-section fade-in">
    <div class="section-header">
        <h2 class="section-title">
            <div class="section-icon">
                <i class="fas fa-clock"></i>
            </div>
            الجدول الزمني للجيل
        </h2>
    </div>
    
    <div class="timeline">
        <div class="timeline-item">
            <div class="timeline-icon">
                <i class="fas fa-baby"></i>
            </div>
            <div class="timeline-content">
                <h4 class="timeline-title">تاريخ الميلاد</h4>
                <p class="timeline-date">{{ $generation->birth_year }}</p>
                <p class="timeline-description">بداية هذا الجيل - مواليد عام {{ $generation->birth_year }}</p>
            </div>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-icon">
                <i class="fas fa-school"></i>
            </div>
            <div class="timeline-content">
                <h4 class="timeline-title">دخول المدرسة</h4>
                <p class="timeline-date">{{ $generation->birth_year + 6 }}</p>
                <p class="timeline-description">العمر المتوقع لدخول الصف الأول الابتدائي</p>
            </div>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="timeline-content">
                <h4 class="timeline-title">التخرج المتوقع</h4>
                <p class="timeline-date">{{ $generation->birth_year + 18 }}</p>
                <p class="timeline-description">التخرج من الثانوية العامة والانتقال للجامعة</p>
            </div>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-icon">
                <i class="fas fa-plus"></i>
            </div>
            <div class="timeline-content">
                <h4 class="timeline-title">تم إضافة الجيل للنظام</h4>
                <p class="timeline-date">{{ $generation->created_at->format('Y/m/d H:i') }}</p>
                <p class="timeline-description">تاريخ إضافة هذا الجيل إلى النظام التعليمي</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle generation status
async function toggleStatus(generationId) {
    if (!confirm('هل أنت متأكد من تغيير حالة التفعيل؟')) {
        return;
    }
    
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

// Delete generation
async function deleteGeneration(generationId) {
    if (!confirm('هل أنت متأكد من حذف هذا الجيل؟ لا يمكن التراجع عن هذا الإجراء.')) {
        return;
    }
    
    if (!confirm('تحذير: سيتم حذف جميع البيانات المرتبطة بهذا الجيل. هل أنت متأكد تماماً؟')) {
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
            window.location.href = '{{ route("admin.educational.generations.index") }}';
        } else {
            alert('حدث خطأ في حذف الجيل');
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
    
    // Add staggered animation to timeline items
    setTimeout(() => {
        document.querySelectorAll('.timeline-item').forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(30px)';
                item.style.transition = 'all 0.6s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, index * 200);
        });
    }, 500);
    
    // Add hover effects to subject cards
    document.querySelectorAll('.subject-card').forEach(card => {
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