@extends('layouts.admin')

@section('title', 'تفاصيل المعلم')
@section('page-title', 'تفاصيل المعلم')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">
    <a href="{{ route('admin.educational.teachers.index') }}" class="breadcrumb-link">المعلمين</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">{{ $teacher->name }}</div>
@endsection

@push('styles')
<style>
.teacher-profile {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.teacher-profile::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.teacher-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 5px solid rgba(255, 255, 255, 0.3);
    object-fit: cover;
    margin-bottom: 1rem;
}

.teacher-avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 1rem;
    border: 5px solid rgba(255, 255, 255, 0.3);
}

.status-badge {
    position: absolute;
    top: 20px;
    left: 20px;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
}

.status-active {
    background: rgba(40, 167, 69, 0.9);
    color: white;
}

.status-inactive {
    background: rgba(220, 53, 69, 0.9);
    color: white;
}

.info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border-left: 4px solid #007bff;
    transition: transform 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
}

.info-card h6 {
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.info-card .value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #495057;
}

.platforms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.platform-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.platform-card:hover {
    transform: translateY(-5px);
    border-color: #007bff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.platform-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
}

.platform-status {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-left: 10px;
}

.platform-active {
    background: #28a745;
    animation: pulse 2s infinite;
}

.platform-inactive {
    background: #dc3545;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
}

.activity-timeline {
    position: relative;
    padding-left: 2rem;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
    background: white;
    padding: 1rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -1.75rem;
    top: 1rem;
    width: 12px;
    height: 12px;
    background: #007bff;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #007bff;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.stats-overview {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.bio-section {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.bio-content {
    line-height: 1.8;
    color: #495057;
    font-size: 1rem;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Teacher Profile Header -->
    <div class="teacher-profile">
        <div class="status-badge {{ $teacher->is_active ? 'status-active' : 'status-inactive' }}">
            {{ $teacher->is_active ? 'نشط' : 'غير نشط' }}
        </div>
        
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                @if($teacher->image)
                    <img src="{{ $teacher->image_url }}" alt="{{ $teacher->name }}" class="teacher-avatar">
                @else
                    <div class="action-buttons">
                    <a href="{{ route('admin.educational.teachers.edit', $teacher) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                        تعديل البيانات
                    </a>
                    
                    <button type="button" 
                            class="btn {{ $teacher->is_active ? 'btn-secondary' : 'btn-success' }}" 
                            onclick="toggleStatus({{ $teacher->id }})">
                        <i class="fas fa-{{ $teacher->is_active ? 'pause' : 'play' }}"></i>
                        {{ $teacher->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                    </button>
                    
                    <button type="button" class="btn btn-info" onclick="getStatistics({{ $teacher->id }})">
                        <i class="fas fa-chart-bar"></i>
                        الإحصائيات
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="info-cards">
        <div class="info-card">
            <h6><i class="fas fa-desktop"></i> عدد المنصات</h6>
            <div class="value">{{ $teacher->platforms_count ?? 0 }}</div>
        </div>
        
        <div class="info-card">
            <h6><i class="fas fa-check-circle"></i> المنصات النشطة</h6>
            <div class="value text-success">{{ $teacher->active_platforms_count ?? 0 }}</div>
        </div>
        
        <div class="info-card">
            <h6><i class="fas fa-calendar-alt"></i> تاريخ الإنضمام</h6>
            <div class="value">{{ $teacher->created_at->format('Y-m-d') }}</div>
        </div>
        
        <div class="info-card">
            <h6><i class="fas fa-clock"></i> آخر تحديث</h6>
            <div class="value">{{ $teacher->updated_at->format('Y-m-d') }}</div>
        </div>
    </div>

    <div class="row">
        <!-- Bio Section -->
        @if($teacher->bio)
        <div class="col-12 mb-4">
            <div class="bio-section">
                <h4 class="mb-3 text-primary">
                    <i class="fas fa-user-circle"></i>
                    السيرة الذاتية
                </h4>
                <div class="bio-content">
                    {{ $teacher->bio }}
                </div>
            </div>
        </div>
        @endif

        <!-- Platforms Section -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-desktop"></i>
                            منصات المعلم
                        </h4>
                        
                        <a href="{{ route('admin.educational.platforms.create') }}?teacher_id={{ $teacher->id }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            إضافة منصة جديدة
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($teacher->platforms->count() > 0)
                        <div class="platforms-grid">
                            @foreach($teacher->platforms as $platform)
                                <div class="platform-card">
                                    <div class="platform-header">
                                        <h5 class="mb-0">
                                            <div class="platform-status {{ $platform->is_active ? 'platform-active' : 'platform-inactive' }}"></div>
                                            {{ $platform->name }}
                                        </h5>
                                        
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-secondary dropdown-toggle" 
                                                    type="button" 
                                                    data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('admin.educational.platforms.show', $platform) }}">
                                                        <i class="fas fa-eye"></i>
                                                        عرض التفاصيل
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('admin.educational.platforms.edit', $platform) }}">
                                                        <i class="fas fa-edit"></i>
                                                        تعديل
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    @if($platform->description)
                                        <p class="text-muted mb-3">{{ $platform->description }}</p>
                                    @endif
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">الباقات:</small>
                                            <div class="font-weight-bold">
                                                {{ $platform->educationalPackages->count() }} باقة
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">الحالة:</small>
                                            <div>
                                                <span class="badge {{ $platform->is_active ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $platform->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($platform->website_url)
                                        <div class="mt-3">
                                            <a href="{{ $platform->formatted_website_url }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt"></i>
                                                زيارة الموقع
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-desktop"></i>
                            <h5>لا توجد منصات</h5>
                            <p>لم يتم إنشاء أي منصات لهذا المعلم بعد</p>
                            <a href="{{ route('admin.educational.platforms.create') }}?teacher_id={{ $teacher->id }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                إضافة منصة جديدة
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-line"></i>
                        الإحصائيات التفصيلية
                    </h4>
                </div>
                <div class="card-body">
                    <div id="statisticsContent">
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <p class="text-muted">انقر على زر "الإحصائيات" أعلاه لعرض البيانات التفصيلية</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Modal -->
<div class="modal fade" id="statisticsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إحصائيات المعلم: {{ $teacher->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="statisticsModalContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="mt-3">جاري تحميل الإحصائيات...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clone Teacher Modal -->
<div class="modal fade" id="cloneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">نسخ المعلم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.educational.teachers.clone', $teacher) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="target_subject_id" class="form-label">المادة المستهدفة</label>
                        <select name="target_subject_id" id="target_subject_id" class="form-select" required>
                            <option value="">اختر المادة</option>
                            @foreach($allSubjects ?? [] as $subject)
                                @if($subject->id !== $teacher->subject_id)
                                    <option value="{{ $subject->id }}">
                                        {{ $subject->name }} - {{ $subject->generation->display_name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <p class="text-muted mt-3">
                        <i class="fas fa-info-circle"></i>
                        سيتم نسخ المعلم مع جميع بياناته إلى المادة المختارة
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">نسخ المعلم</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <h6 class="text-center">هل أنت متأكد من حذف هذا المعلم؟</h6>
                <p class="text-center text-muted">
                    سيتم حذف المعلم "{{ $teacher->name }}" وجميع البيانات المرتبطة به
                </p>
                <div class="alert alert-danger">
                    <strong>تحذير:</strong> هذا الإجراء لا يمكن التراجع عنه!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form action="{{ route('admin.educational.teachers.destroy', $teacher) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        حذف نهائياً
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(teacherId) {
    fetch(`/admin/educational/teachers/${teacherId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', 'حدث خطأ أثناء تحديث الحالة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'حدث خطأ أثناء تحديث الحالة');
    });
}

function getStatistics(teacherId) {
    const modal = new bootstrap.Modal(document.getElementById('statisticsModal'));
    modal.show();
    
    fetch(`/admin/educational/teachers/${teacherId}/statistics`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayStatistics(data.data);
            } else {
                document.getElementById('statisticsModalContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        حدث خطأ في تحميل الإحصائيات
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('statisticsModalContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    حدث خطأ في الاتصال بالخادم
                </div>
            `;
        });
}

function displayStatistics(stats) {
    const content = `
        <div class="row">
            <div class="col-md-6">
                <div class="stat-item text-center p-3 border rounded">
                    <h4 class="text-primary">${stats.platforms_count || 0}</h4>
                    <p class="mb-0">إجمالي المنصات</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-item text-center p-3 border rounded">
                    <h4 class="text-success">${stats.active_platforms_count || 0}</h4>
                    <p class="mb-0">المنصات النشطة</p>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="stat-item text-center p-3 border rounded">
                    <h4 class="text-info">${stats.packages_count || 0}</h4>
                    <p class="mb-0">إجمالي الباقات</p>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="stat-item text-center p-3 border rounded">
                    <h4 class="text-warning">${stats.cards_count || 0}</h4>
                    <p class="mb-0">البطاقات المُصدرة</p>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="stat-item text-center p-3 border rounded">
                    <h4 class="text-success">${stats.orders_count || 0}</h4>
                    <p class="mb-0">إجمالي الطلبات</p>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="stat-item text-center p-3 border rounded">
                    <h4 class="text-primary">${stats.total_revenue || 0} دينار</h4>
                    <p class="mb-0">إجمالي الإيرادات</p>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('statisticsModalContent').innerHTML = content;
    
    // Update main statistics section
    document.getElementById('statisticsContent').innerHTML = content;
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${icon}"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.container-fluid').firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Animation on page load
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.info-card, .platform-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush class="teacher-avatar-placeholder">
                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            
            <div class="col-md-9">
                <h2 class="mb-2">{{ $teacher->name }}</h2>
                <h5 class="mb-3 opacity-75">
                    <i class="fas fa-book"></i>
                    {{ $teacher->subject->name }} - {{ $teacher->subject->generation->display_name }}
                </h5>
                
                @if($teacher->specialization)
                    <p class="mb-3">
                        <i class="fas fa-certificate"></i>
                        {{ $teacher->specialization }}
                    </p>
                @endif
            