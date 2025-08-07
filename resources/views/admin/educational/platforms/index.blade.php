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
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -30%;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-item {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 5px solid #667eea;
}

.stat-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-label {
    color: #6c757d;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.stat-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.8;
}

.filter-section {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 2px solid #f8f9fa;
}

.filter-title {
    color: #495057;
    font-weight: 700;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.platform-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.platform-card:hover {
    border-left-color: #667eea;
    transform: translateX(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.platform-logo {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 900;
    font-size: 1.5rem;
    margin-left: 1rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.platform-info h5 {
    color: #495057;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.platform-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.platform-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-teacher {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    color: #1565c0;
}

.badge-subject {
    background: linear-gradient(135deg, #f3e5f5, #e1bee7);
    color: #7b1fa2;
}

.badge-generation {
    background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
    color: #2e7d32;
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

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    align-items: center;
    flex-wrap: wrap;
}

.table th {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 2px solid #dee2e6;
    font-weight: 700;
    color: #495057;
    padding: 1.25rem 1rem;
}

.table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
}

.table td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

.website-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.website-link:hover {
    color: #5a6fd8;
    text-decoration: underline;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 5rem;
    margin-bottom: 1.5rem;
    opacity: 0.5;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.empty-state h4 {
    color: #495057;
    margin-bottom: 1rem;
    font-weight: 700;
}

.form-input, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-input:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    outline: none;
    background: white;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    color: #212529;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d, #495057);
    color: white;
}

.modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 1.5rem 2rem;
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    border: none;
    padding: 1.5rem 2rem;
}

.search-highlight {
    background: yellow;
    padding: 0.125rem 0.25rem;
    border-radius: 4px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .platform-card {
        margin-bottom: 1rem;
    }
    
    .action-buttons {
        justify-content: center;
        gap: 0.25rem;
    }
    
    .filter-section {
        padding: 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- إحصائيات سريعة -->
    <div class="stats-card">
        <div style="position: relative; z-index: 2;">
            <h3 class="mb-3">
                <i class="fas fa-desktop"></i>
                نظام إدارة المنصات التعليمية
            </h3>
            <p class="mb-0 opacity-75">إدارة شاملة لجميع المنصات التعليمية والمعلمين</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-desktop text-primary"></i>
            </div>
            <div class="stat-number">{{ $platforms->total() }}</div>
            <div class="stat-label">إجمالي المنصات</div>
        </div>

        <div class="stat-item" style="border-left-color: #28a745;">
            <div class="stat-icon">
                <i class="fas fa-check-circle text-success"></i>
            </div>
            <div class="stat-number text-success">
                {{ $platforms->where('is_active', true)->count() }}
            </div>
            <div class="stat-label">منصات نشطة</div>
        </div>

        <div class="stat-item" style="border-left-color: #ffc107;">
            <div class="stat-icon">
                <i class="fas fa-user-tie text-warning"></i>
            </div>
            <div class="stat-number text-warning">{{ $teachers->count() }}</div>
            <div class="stat-label">المعلمين المتاحين</div>
        </div>

        <div class="stat-item" style="border-left-color: #17a2b8;">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap text-info"></i>
            </div>
            <div class="stat-number text-info">{{ $generations->count() }}</div>
            <div class="stat-label">الأجيال الدراسية</div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="filter-section">
        <h4 class="filter-title">
            <i class="fas fa-filter text-primary"></i>
            خيارات البحث والفلترة
        </h4>
        
        <form method="GET" action="{{ route('admin.educational.platforms.index') }}" id="filterForm">
            <div class="row align-items-end">
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <label class="form-label fw-bold">الجيل الدراسي</label>
                    <select name="generation_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">جميع الأجيال</option>
                        @foreach($generations as $generation)
                            <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                                {{ $generation->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <label class="form-label fw-bold">المادة الدراسية</label>
                    <select name="subject_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">جميع المواد</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} - {{ $subject->generation->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <label class="form-label fw-bold">المعلم</label>
                    <select name="teacher_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">جميع المعلمين</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} - {{ $teacher->subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <label class="form-label fw-bold">الحالة</label>
                    <select name="is_active" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">جميع الحالات</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <label class="form-label fw-bold">البحث</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-input" placeholder="اسم المنصة أو الوصف..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="col-lg-1 col-md-2 col-sm-12 mb-3">
                    <a href="{{ route('admin.educational.platforms.index') }}" class="btn btn-secondary w-100" title="إعادة تعيين">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- قائمة المنصات -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h3 class="card-title mb-0">
                    <i class="fas fa-desktop text-primary"></i>
                    قائمة المنصات التعليمية
                    @if(request()->hasAny(['generation_id', 'subject_id', 'teacher_id', 'is_active', 'search']))
                        <small class="text-muted">({{ $platforms->total() }} نتيجة)</small>
                    @endif
                </h3>
                
                <div class="d-flex gap-2 flex-wrap">
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

        <div class="card-body p-0">
            @if($platforms->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="80">الشعار</th>
                                <th>اسم المنصة</th>
                                <th>المعلم</th>
                                <th>المادة والجيل</th>
                                <th>الباقات</th>
                                <th>الموقع</th>
                                <th>الحالة</th>
                                <th width="200" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($platforms as $platform)
                                <tr class="platform-card border-0">
                                    <td>
                                        <div class="platform-logo">
                                            {{ strtoupper(substr($platform->name, 0, 2)) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="platform-info">
                                            <h5 class="mb-1">
                                                {{ $platform->name }}
                                                <div class="status-indicator {{ $platform->is_active ? 'status-active' : 'status-inactive' }}"></div>
                                            </h5>
                                            @if($platform->description)
                                                <small class="text-muted">{{ Str::limit($platform->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="platform-meta">
                                            <span class="platform-badge badge-teacher">
                                                <i class="fas fa-user-tie"></i>
                                                {{ $platform->teacher->name }}
                                            </span>
                                        </div>
                                        @if($platform->teacher->specialization)
                                            <small class="text-muted d-block">{{ $platform->teacher->specialization }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="platform-meta">
                                            <span class="platform-badge badge-subject">
                                                <i class="fas fa-book"></i>
                                                {{ $platform->teacher->subject->name }}
                                            </span>
                                        </div>
                                        <div class="platform-meta">
                                            <span class="platform-badge badge-generation">
                                                <i class="fas fa-graduation-cap"></i>
                                                {{ $platform->teacher->subject->generation->display_name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge badge-primary">
                                                {{ $platform->educational_packages_count }} باقة
                                            </span>
                                            <span class="badge badge-success">
                                                {{ $platform->active_educational_packages_count }} نشطة
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($platform->website_url)
                                            <a href="{{ $platform->formatted_website_url }}" target="_blank" class="website-link">
                                                <i class="fas fa-external-link-alt"></i>
                                                زيارة الموقع
                                            </a>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($platform->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center p-3">
                    {{ $platforms->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-desktop"></i>
                    <h4>لا توجد منصات تعليمية</h4>
                    <p>لم يتم العثور على أي منصات بالمعايير المحددة</p>
                    <a href="{{ route('admin.educational.platforms.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus"></i>
                        إضافة منصة تعليمية جديدة
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
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <h6>هل أنت متأكد من حذف هذه المنصة؟</h6>
                <p class="text-muted">سيتم حذف المنصة وجميع الباقات المرتبطة بها</p>
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

function deletePlatform(platformId) {
    document.getElementById('deleteForm').action = `/admin/educational/platforms/${platformId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    alertDiv.innerHTML = `
        <i class="fas fa-${icon}"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
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

    // تحسين تجربة البحث
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // يمكن إضافة بحث فوري هنا
            }, 500);
        });
    }

    // تحسين الفلاتر
    const filterSelects = document.querySelectorAll('.form-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // إضافة loading indicator
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'text-center py-3';
            loadingDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
            
            const tbody = document.querySelector('tbody');
            if (tbody) {
                tbody.innerHTML = loadingDiv.outerHTML;
            }
        });
    });

    // إضافة تأثيرات hover للأزرار
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // تحسين الجداول
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(102, 126, 234, 0.05)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>
@endpush