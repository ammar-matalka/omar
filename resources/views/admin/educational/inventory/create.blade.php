@extends('layouts.admin')

@section('title', 'إدارة المنصات التعليمية')
@section('page-title', 'إدارة المنصات التعليمية')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">إدارة المنصات التعليمية</div>
@endsection

@push('styles')
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.stat-value {
    font-size: 2rem;
    font-weight: 900;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.filter-section {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    border: 2px solid #dee2e6;
}

.platform-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    border-radius: 10px;
}

.platform-card:hover {
    border-left-color: #007bff;
    transform: translateX(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.platform-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.platform-logo {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.platform-info h5 {
    margin: 0;
    color: #495057;
    font-weight: 700;
}

.platform-info p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
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

.platform-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.platform-stat {
    text-align: center;
}

.platform-stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #495057;
    display: block;
}

.platform-stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

.website-link {
    color: #007bff;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.website-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 5rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.platforms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.quick-actions {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 2px solid #e9ecef;
}

.table th {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 2px solid #dee2e6;
    font-weight: 700;
    color: #495057;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    border-radius: 50px;
    font-weight: 600;
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

.dropdown-menu {
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    border: none;
}

.dropdown-item {
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #e3f2fd, #f8f9fa);
    transform: translateX(-5px);
    padding-right: 1.5rem;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- إحصائيات سريعة -->
    <div class="stats-grid">
        <div class="stat-card">
            <div style="position: relative; z-index: 2;">
                <div class="stat-value">{{ $platforms->total() }}</div>
                <div class="stat-label">
                    <i class="fas fa-desktop"></i>
                    إجمالي المنصات
                </div>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
            <div style="position: relative; z-index: 2;">
                <div class="stat-value">{{ $platforms->where('is_active', true)->count() }}</div>
                <div class="stat-label">
                    <i class="fas fa-check-circle"></i>
                    منصات نشطة
                </div>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
            <div style="position: relative; z-index: 2;">
                <div class="stat-value">{{ $generations->count() }}</div>
                <div class="stat-label">
                    <i class="fas fa-graduation-cap"></i>
                    الأجيال المتاحة
                </div>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #6f42c1, #e83e8c);">
            <div style="position: relative; z-index: 2;">
                <div class="stat-value">{{ $teachers->count() }}</div>
                <div class="stat-label">
                    <i class="fas fa-user-tie"></i>
                    المعلمين المتاحين
                </div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.educational.platforms.index') }}" id="filterForm">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-graduation-cap text-primary"></i>
                        الجيل الدراسي
                    </label>
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
                    <label class="form-label">
                        <i class="fas fa-book text-success"></i>
                        المادة الدراسية
                    </label>
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
                    <label class="form-label">
                        <i class="fas fa-user-tie text-warning"></i>
                        المعلم
                    </label>
                    <select name="teacher_id" class="form-input" onchange="document.getElementById('filterForm').submit()">
                        <option value="">جميع المعلمين</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">
                        <i class="fas fa-toggle-on text-info"></i>
                        الحالة
                    </label>
                    <select name="is_active" class="form-input" onchange="document.getElementById('filterForm').submit()">
                        <option value="">جميع الحالات</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.educational.platforms.index') }}" class="btn btn-secondary flex-fill">
                            <i class="fas fa-refresh"></i>
                        </a>
                        <button type="button" class="btn btn-info flex-fill" onclick="toggleView()">
                            <i class="fas fa-th" id="viewToggleIcon"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-search text-secondary"></i>
                        البحث
                    </label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-input" placeholder="اسم المنصة أو الوصف..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            بحث
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- الإجراءات السريعة -->
    <div class="quick-actions">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-desktop text-primary"></i>
                المنصات التعليمية
            </h4>
            
            <div class="d-flex gap-2">
                <a href="{{ route('admin.educational.platforms.export') }}" class="btn btn-success">
                    <i class="fas fa-download"></i>
                    تصدير Excel
                </a>
                
                <a href="{{ route('admin.educational.platforms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    إضافة منصة جديدة
                </a>
            </div>
        </div>
    </div>

    <!-- عرض المنصات -->
    <div id="platformsContent">
        @if($platforms->count() > 0)
            <!-- عرض الكروت (افتراضي) -->
            <div class="platforms-grid" id="cardsView">
                @foreach($platforms as $platform)
                    <div class="card platform-card">
                        <div class="card-body">
                            <div class="platform-header">
                                <div class="platform-logo">
                                    {{ strtoupper(substr($platform->name, 0, 2)) }}
                                </div>
                                <div class="platform-info flex-grow-1">
                                    <h5>
                                        <div class="status-indicator {{ $platform->is_active ? 'status-active' : 'status-inactive' }}"></div>
                                        {{ $platform->name }}
                                    </h5>
                                    <p>{{ $platform->teacher->name }} - {{ $platform->teacher->subject->name }}</p>
                                </div>
                                
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" 
                                            type="button" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.educational.platforms.show', $platform) }}">
                                                <i class="fas fa-eye text-info"></i>
                                                عرض التفاصيل
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.educational.platforms.edit', $platform) }}">
                                                <i class="fas fa-edit text-warning"></i>
                                                تعديل
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item" onclick="toggleStatus({{ $platform->id }})">
                                                <i class="fas fa-{{ $platform->is_active ? 'pause' : 'play' }} text-{{ $platform->is_active ? 'secondary' : 'success' }}"></i>
                                                {{ $platform->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-danger" onclick="deletePlatform({{ $platform->id }})">
                                                <i class="fas fa-trash"></i>
                                                حذف
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @if($platform->description)
                                <p class="text-muted mb-3">{{ Str::limit($platform->description, 100) }}</p>
                            @endif

                            @if($platform->website_url)
                                <div class="mb-3">
                                    <a href="{{ $platform->formatted_website_url }}" 
                                       target="_blank" 
                                       class="website-link">
                                        <i class="fas fa-external-link-alt"></i>
                                        زيارة الموقع
                                    </a>
                                </div>
                            @endif

                            <div class="platform-stats">
                                <div class="platform-stat">
                                    <span class="platform-stat-value text-primary">{{ $platform->educational_packages_count ?? 0 }}</span>
                                    <span class="platform-stat-label">الباقات</span>
                                </div>
                                <div class="platform-stat">
                                    <span class="platform-stat-value text-success">{{ $platform->active_educational_packages_count ?? 0 }}</span>
                                    <span class="platform-stat-label">النشطة</span>
                                </div>
                                <div class="platform-stat">
                                    <span class="platform-stat-value text-info">{{ $platform->teacher->subject->generation->display_name }}</span>
                                    <span class="platform-stat-label">الجيل</span>
                                </div>
                            </div>

                            <div class="mt-3">
                                <span class="badge {{ $platform->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $platform->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- عرض الجدول (مخفي افتراضياً) -->
            <div class="card" id="tableView" style="display: none;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>المنصة</th>
                                    <th>المعلم</th>
                                    <th>المادة</th>
                                    <th>الجيل</th>
                                    <th>الباقات</th>
                                    <th>الموقع</th>
                                    <th>الحالة</th>
                                    <th width="150" class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($platforms as $platform)
                                    <tr class="platform-card">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="platform-logo me-2" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                                    {{ strtoupper(substr($platform->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $platform->name }}</strong>
                                                    <div class="status-indicator {{ $platform->is_active ? 'status-active' : 'status-inactive' }}"></div>
                                                    @if($platform->description)
                                                        <small class="text-muted d-block">{{ Str::limit($platform->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $platform->teacher->name }}</strong>
                                            @if($platform->teacher->specialization)
                                                <small class="text-muted d-block">{{ $platform->teacher->specialization }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $platform->teacher->subject->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $platform->teacher->subject->generation->display_name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $platform->educational_packages_count ?? 0 }} باقة</span>
                                            <span class="badge badge-success">{{ $platform->active_educational_packages_count ?? 0 }} نشطة</span>
                                        </td>
                                        <td>
                                            @if($platform->website_url)
                                                <a href="{{ $platform->formatted_website_url }}" target="_blank" class="website-link">
                                                    <i class="fas fa-external-link-alt"></i>
                                                    زيارة
                                                </a>
                                            @else
                                                <span class="text-muted">لا يوجد</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $platform->is_active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $platform->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.educational.platforms.show', $platform) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <a href="{{ route('admin.educational.platforms.edit', $platform) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <button type="button" 
                                                        class="btn btn-sm {{ $platform->is_active ? 'btn-secondary' : 'btn-success' }}" 
                                                        onclick="toggleStatus({{ $platform->id }})"
                                                        title="{{ $platform->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                    <i class="fas fa-{{ $platform->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                                
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="deletePlatform({{ $platform->id }})"
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
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $platforms->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-desktop"></i>
                <h4>لا توجد منصات تعليمية</h4>
                <p>لم يتم العثور على أي منصات بالمعايير المحددة</p>
                <a href="{{ route('admin.educational.platforms.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i>
                    إضافة منصة جديدة
                </a>
            </div>
        @endif
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
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <h6 class="text-center">هل أنت متأكد من حذف هذه المنصة؟</h6>
                <p class="text-center text-muted">سيتم حذف المنصة وجميع الباقات المرتبطة بها</p>
                <div class="alert alert-danger">
                    <strong>تحذير:</strong> هذا الإجراء لا يمكن التراجع عنه!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
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
// تبديل حالة التفعيل
function toggleStatus(platformId) {
    fetch(`/admin/educational/platforms/${platformId}/toggle-status`, {
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

// حذف منصة
function deletePlatform(platformId) {
    document.getElementById('deleteForm').action = `/admin/educational/platforms/${platformId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// تبديل طريقة العرض
function toggleView() {
    const cardsView = document.getElementById('cardsView');
    const tableView = document.getElementById('tableView');
    const toggleIcon = document.getElementById('viewToggleIcon');
    
    if (cardsView.style.display === 'none') {
        // Switch to cards view
        cardsView.style.display = 'grid';
        tableView.style.display = 'none';
        toggleIcon.className = 'fas fa-th';
    } else {
        // Switch to table view
        cardsView.style.display = 'none';
        tableView.style.display = 'block';
        toggleIcon.className = 'fas fa-list';
    }
}

// عرض التنبيهات
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

// تحسين تجربة المستخدم
document.addEventListener('DOMContentLoaded', function() {
    // إضافة animation للكروت
    const cards = document.querySelectorAll('.platform-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // تحسين Dropdowns
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
});
</script>
@endpush