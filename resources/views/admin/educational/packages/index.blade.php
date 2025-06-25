@extends('layouts.admin')

@section('title', 'إدارة الباقات التعليمية')

@push('styles')
<style>
.package-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    background: white;
}

.package-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.package-type-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.digital-badge {
    background-color: #dbeafe;
    color: #1e40af;
}

.physical-badge {
    background-color: #fef3c7;
    color: #d97706;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    text-align: center;
}

.filters-section {
    background: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    margin-bottom: 1.5rem;
}

.package-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.package-details {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
    font-size: 0.875rem;
    color: #6b7280;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .package-details {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .action-buttons {
        flex-direction: column;
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">إدارة الباقات التعليمية</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">النظام التعليمي</a></li>
                    <li class="breadcrumb-item active">الباقات التعليمية</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.educational.packages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>إضافة باقة جديدة
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">إجمالي الباقات</h6>
                    <h3 class="mb-0 text-primary">{{ number_format($packages->total()) }}</h3>
                </div>
                <div class="text-primary">
                    <i class="fas fa-box-open fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">باقات نشطة</h6>
                    <h3 class="mb-0 text-success">{{ $packages->where('is_active', true)->count() }}</h3>
                </div>
                <div class="text-success">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">باقات رقمية</h6>
                    <h3 class="mb-0 text-info">{{ $packages->filter(function($p) { return $p->productType->is_digital; })->count() }}</h3>
                </div>
                <div class="text-info">
                    <i class="fas fa-laptop fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">باقات ورقية</h6>
                    <h3 class="mb-0 text-warning">{{ $packages->filter(function($p) { return !$p->productType->is_digital; })->count() }}</h3>
                </div>
                <div class="text-warning">
                    <i class="fas fa-book fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.educational.packages.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">نوع المنتج</label>
                    <select name="product_type_id" class="form-select">
                        <option value="">جميع الأنواع</option>
                        @foreach($productTypes as $type)
                            <option value="{{ $type->id }}" {{ request('product_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">الجيل</label>
                    <select name="generation_id" class="form-select">
                        <option value="">جميع الأجيال</option>
                        @foreach($generations as $generation)
                            <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                                {{ $generation->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">المادة</label>
                    <select name="subject_id" class="form-select">
                        <option value="">جميع المواد</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">المعلم</label>
                    <select name="teacher_id" class="form-select">
                        <option value="">جميع المعلمين</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">الحالة</label>
                    <select name="is_active" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="البحث في أسماء الباقات..." value="{{ request('search') }}">
                </div>
                <div class="col-md-8 d-flex gap-2">
                    <a href="{{ route('admin.educational.packages.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>مسح الفلاتر
                    </a>
                    <a href="{{ route('admin.educational.packages.export', request()->query()) }}" class="btn btn-outline-success">
                        <i class="fas fa-file-excel me-1"></i>تصدير Excel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Packages List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة الباقات ({{ $packages->total() }} باقة)</h5>
            <div class="d-flex gap-2">
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="view" id="grid-view" checked>
                    <label class="btn btn-outline-secondary" for="grid-view">
                        <i class="fas fa-th"></i>
                    </label>
                    <input type="radio" class="btn-check" name="view" id="list-view">
                    <label class="btn btn-outline-secondary" for="list-view">
                        <i class="fas fa-list"></i>
                    </label>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($packages->count() > 0)
                <!-- Grid View -->
                <div id="grid-container" class="row g-3">
                    @foreach($packages as $package)
                        <div class="col-md-6 col-lg-4">
                            <div class="package-card p-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="package-info flex-grow-1">
                                        <h6 class="mb-1">{{ $package->name }}</h6>
                                        <div class="d-flex gap-2 mb-2">
                                            <span class="package-type-badge {{ $package->is_digital ? 'digital-badge' : 'physical-badge' }}">
                                                {{ $package->package_type }}
                                            </span>
                                            <span class="badge {{ $package->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $package->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </div>
                                        <div class="package-details">
                                            <span><i class="fas fa-graduation-cap text-muted me-1"></i>{{ $package->platform->teacher->subject->generation->display_name }}</span>
                                            <span><i class="fas fa-book text-muted me-1"></i>{{ $package->platform->teacher->subject->name }}</span>
                                            <span><i class="fas fa-user-tie text-muted me-1"></i>{{ $package->platform->teacher->name }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('admin.educational.packages.show', $package) }}">
                                                <i class="fas fa-eye me-2"></i>عرض التفاصيل
                                            </a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin.educational.packages.edit', $package) }}">
                                                <i class="fas fa-edit me-2"></i>تعديل
                                            </a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin.educational.packages.statistics', $package) }}">
                                                <i class="fas fa-chart-bar me-2"></i>الإحصائيات
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><button class="dropdown-item text-primary" onclick="togglePackageStatus({{ $package->id }})">
                                                <i class="fas fa-toggle-{{ $package->is_active ? 'off' : 'on' }} me-2"></i>
                                                {{ $package->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                                            </button></li>
                                            <li><button class="dropdown-item text-danger" onclick="deletePackage({{ $package->id }})">
                                                <i class="fas fa-trash me-2"></i>حذف
                                            </button></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="row text-center border-top pt-2">
                                    @if($package->is_digital)
                                        @if($package->duration_days)
                                            <div class="col-6">
                                                <small class="text-muted d-block">مدة الصلاحية</small>
                                                <strong>{{ $package->duration_display }}</strong>
                                            </div>
                                        @endif
                                        @if($package->lessons_count)
                                            <div class="col-6">
                                                <small class="text-muted d-block">عدد الدروس</small>
                                                <strong>{{ $package->lessons_display }}</strong>
                                            </div>
                                        @endif
                                    @else
                                        @if($package->pages_count)
                                            <div class="col-6">
                                                <small class="text-muted d-block">عدد الصفحات</small>
                                                <strong>{{ $package->pages_display }}</strong>
                                            </div>
                                        @endif
                                        @if($package->weight_grams)
                                            <div class="col-6">
                                                <small class="text-muted d-block">الوزن</small>
                                                <strong>{{ $package->weight_display }}</strong>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- List View (Hidden by default) -->
                <div id="list-container" class="table-responsive" style="display: none;">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>اسم الباقة</th>
                                <th>النوع</th>
                                <th>المعلم</th>
                                <th>المادة</th>
                                <th>الجيل</th>
                                <th>المعلومات</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packages as $package)
                                <tr>
                                    <td>
                                        <strong>{{ $package->name }}</strong>
                                        @if($package->description)
                                            <br><small class="text-muted">{{ Str::limit($package->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="package-type-badge {{ $package->is_digital ? 'digital-badge' : 'physical-badge' }}">
                                            {{ $package->package_type }}
                                        </span>
                                    </td>
                                    <td>{{ $package->platform->teacher->name }}</td>
                                    <td>{{ $package->platform->teacher->subject->name }}</td>
                                    <td>{{ $package->platform->teacher->subject->generation->display_name }}</td>
                                    <td>
                                        @if($package->is_digital)
                                            @if($package->duration_days)
                                                <small class="d-block">{{ $package->duration_display }}</small>
                                            @endif
                                            @if($package->lessons_count)
                                                <small class="d-block">{{ $package->lessons_display }}</small>
                                            @endif
                                        @else
                                            @if($package->pages_count)
                                                <small class="d-block">{{ $package->pages_display }}</small>
                                            @endif
                                            @if($package->weight_grams)
                                                <small class="d-block">{{ $package->weight_display }}</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $package->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $package->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.educational.packages.show', $package) }}" class="btn btn-sm btn-outline-info" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.educational.packages.edit', $package) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-{{ $package->is_active ? 'warning' : 'success' }}" 
                                                    onclick="togglePackageStatus({{ $package->id }})" 
                                                    title="{{ $package->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                <i class="fas fa-toggle-{{ $package->is_active ? 'off' : 'on' }}"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deletePackage({{ $package->id }})" title="حذف">
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
                <div class="d-flex justify-content-center mt-4">
                    {{ $packages->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد باقات تعليمية</h5>
                    <p class="text-muted">لم يتم العثور على أي باقات تطابق المعايير المحددة</p>
                    <a href="{{ route('admin.educational.packages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>إضافة باقة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@include('admin.educational.packages.modals.clone')
@endsection

@push('scripts')
<script>
// Toggle between grid and list view
document.getElementById('grid-view').addEventListener('change', function() {
    if(this.checked) {
        document.getElementById('grid-container').style.display = 'block';
        document.getElementById('list-container').style.display = 'none';
    }
});

document.getElementById('list-view').addEventListener('change', function() {
    if(this.checked) {
        document.getElementById('grid-container').style.display = 'none';
        document.getElementById('list-container').style.display = 'block';
    }
});

// Toggle package status
function togglePackageStatus(packageId) {
    if(confirm('هل أنت متأكد من تغيير حالة هذه الباقة؟')) {
        fetch(`/admin/educational/packages/${packageId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('error', 'حدث خطأ أثناء تغيير حالة الباقة');
            }
        })
        .catch(error => {
            showAlert('error', 'حدث خطأ في الاتصال');
        });
    }
}

// Delete package
function deletePackage(packageId) {
    if(confirm('هل أنت متأكد من حذف هذه الباقة؟ لا يمكن التراجع عن هذا الإجراء.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/educational/packages/${packageId}`;
        form.innerHTML = `
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Show alert messages
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endpush