@extends('layouts.admin')

@section('title', 'إدارة المعلمين')
@section('page-title', 'إدارة المعلمين')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">إدارة المعلمين</div>
@endsection

@push('styles')
<style>
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-item {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
}

.teacher-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e9ecef;
}

.teacher-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.teacher-card:hover {
    border-left-color: #007bff;
    transform: translateX(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.filter-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    margin-left: 8px;
}

.status-active {
    background-color: #28a745;
    animation: pulse 2s infinite;
}

.status-inactive {
    background-color: #dc3545;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
}

.table th {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 2px solid #dee2e6;
    font-weight: 700;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- إحصائيات سريعة -->
    <div class="stats-grid">
        <div class="stat-item">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-0 text-primary">{{ $teachers->total() }}</h3>
                    <p class="text-muted mb-0">إجمالي المعلمين</p>
                </div>
                <i class="fas fa-user-tie fa-2x text-primary"></i>
            </div>
        </div>

        <div class="stat-item">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-0 text-success">{{ $teachers->where('is_active', true)->count() }}</h3>
                    <p class="text-muted mb-0">معلمين نشطين</p>
                </div>
                <i class="fas fa-check-circle fa-2x text-success"></i>
            </div>
        </div>

        <div class="stat-item">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-0 text-info">{{ $generations->count() }}</h3>
                    <p class="text-muted mb-0">الأجيال المتاحة</p>
                </div>
                <i class="fas fa-graduation-cap fa-2x text-info"></i>
            </div>
        </div>

        <div class="stat-item">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-0 text-warning">{{ $subjects->count() }}</h3>
                    <p class="text-muted mb-0">المواد الدراسية</p>
                </div>
                <i class="fas fa-book fa-2x text-warning"></i>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.educational.teachers.index') }}" id="filterForm">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">الجيل الدراسي</label>
                    <select name="generation_id" class="form-input" onchange="document.getElementById('filterForm').submit()">
                        <option value="">جميع الأجيال</option>
                        @foreach($generations as $generation)
                            <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                                {{ $generation->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">المادة الدراسية</label>
                    <select name="subject_id" class="form-input" onchange="document.getElementById('filterForm').submit()">
                        <option value="">جميع المواد</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} - {{ $subject->generation->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">الحالة</label>
                    <select name="is_active" class="form-input" onchange="document.getElementById('filterForm').submit()">
                        <option value="">جميع الحالات</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">البحث</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-input" placeholder="اسم المعلم أو التخصص..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-1">
                    <a href="{{ route('admin.educational.teachers.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- قائمة المعلمين -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-user-tie"></i>
                    قائمة المعلمين
                </h3>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.educational.teachers.export') }}" class="btn btn-success">
                        <i class="fas fa-download"></i>
                        تصدير Excel
                    </a>
                    
                    <a href="{{ route('admin.educational.teachers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        إضافة معلم جديد
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($teachers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="80">الصورة</th>
                                <th>المعلم</th>
                                <th>المادة</th>
                                <th>الجيل</th>
                                <th>التخصص</th>
                                <th>المنصات</th>
                                <th>الحالة</th>
                                <th width="200" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                                <tr class="teacher-card">
                                    <td>
                                        @if($teacher->image)
                                            <img src="{{ $teacher->image_url }}" alt="{{ $teacher->name }}" class="teacher-avatar">
                                        @else
                                            <div class="teacher-avatar bg-primary text-white d-flex align-items-center justify-content-center">
                                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $teacher->name }}</strong>
                                            <div class="status-indicator {{ $teacher->is_active ? 'status-active' : 'status-inactive' }}"></div>
                                        </div>
                                        <small class="text-muted">معرف: #{{ $teacher->id }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $teacher->subject->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $teacher->subject->generation->display_name }}</span>
                                    </td>
                                    <td>
                                        {{ $teacher->specialization ?: 'غير محدد' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $teacher->platforms_count }} منصة</span>
                                        <span class="badge badge-success">{{ $teacher->active_platforms_count }} نشطة</span>
                                    </td>
                                    <td>
                                        @if($teacher->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.educational.teachers.show', $teacher) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('admin.educational.teachers.edit', $teacher) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <button type="button" 
                                                    class="btn btn-sm {{ $teacher->is_active ? 'btn-secondary' : 'btn-success' }}" 
                                                    onclick="toggleStatus({{ $teacher->id }})"
                                                    title="{{ $teacher->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                <i class="fas fa-{{ $teacher->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="deleteTeacher({{ $teacher->id }})"
                                                    title="حذف">
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
                <div class="d-flex justify-content-center p-3">
                    {{ $teachers->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-tie fa-5x text-muted mb-3"></i>
                    <h4 class="text-muted">لا يوجد معلمين</h4>
                    <p class="text-muted">لم يتم العثور على أي معلمين بالمعايير المحددة</p>
                    <a href="{{ route('admin.educational.teachers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        إضافة معلم جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal للحذف -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذا المعلم؟</p>
                <p class="text-danger"><strong>تحذير:</strong> سيتم حذف جميع البيانات المرتبطة بهذا المعلم.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
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
            location.reload();
        } else {
            alert('حدث خطأ أثناء تحديث الحالة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تحديث الحالة');
    });
}

function deleteTeacher(teacherId) {
    document.getElementById('deleteForm').action = `/admin/educational/teachers/${teacherId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// تحسين تجربة المستخدم
document.addEventListener('DOMContentLoaded', function() {
    // إضافة animation للكروت
    const cards = document.querySelectorAll('.teacher-card');
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
@endpush