@extends('layouts.admin')

@section('title', 'تفاصيل الباقة: ' . $package->name)

@push('styles')
<style>
.package-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 1rem;
    color: white;
    padding: 2rem;
    margin-bottom: 2rem;
}

.info-card {
    background: white;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-item {
    text-align: center;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    background: white;
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.package-type-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
}

.digital-badge {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.physical-badge {
    background: linear-gradient(135deg, #f093fb, #f5576c);
    color: white;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #374151;
}

.detail-value {
    color: #6b7280;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.related-section {
    background: #f8fafc;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.related-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: white;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    margin-bottom: 0.5rem;
}

.timeline-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding: 1rem;
    background: white;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}

.timeline-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 1rem;
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .package-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .action-buttons {
        justify-content: center;
    }
    
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="package-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-3">
                    <span class="package-type-badge {{ $package->is_digital ? 'digital-badge' : 'physical-badge' }} me-3">
                        <i class="fas fa-{{ $package->is_digital ? 'laptop' : 'book' }} me-2"></i>
                        {{ $package->package_type }}
                    </span>
                    <span class="badge {{ $package->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $package->is_active ? 'نشطة' : 'غير نشطة' }}
                    </span>
                </div>
                <h1 class="h2 mb-2">{{ $package->name }}</h1>
                <p class="mb-0 opacity-75">{{ $package->platform->teaching_chain }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="action-buttons">
                    <a href="{{ route('admin.educational.packages.edit', $package) }}" class="btn btn-light">
                        <i class="fas fa-edit me-2"></i>تعديل
                    </a>
                    <button class="btn btn-light" onclick="togglePackageStatus({{ $package->id }})">
                        <i class="fas fa-toggle-{{ $package->is_active ? 'off' : 'on' }} me-2"></i>
                        {{ $package->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.educational.packages.statistics', $package) }}">
                                <i class="fas fa-chart-bar me-2"></i>الإحصائيات
                            </a></li>
                            <li><button class="dropdown-item" onclick="clonePackage({{ $package->id }})">
                                <i class="fas fa-copy me-2"></i>نسخ الباقة
                            </button></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button class="dropdown-item text-danger" onclick="deletePackage({{ $package->id }})">
                                <i class="fas fa-trash me-2"></i>حذف الباقة
                            </button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb text-white-50">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white-50">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.educational.packages.index') }}" class="text-white-50">الباقات التعليمية</a></li>
                <li class="breadcrumb-item active text-white">{{ $package->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Basic Information -->
        <div class="col-md-8">
            <div class="info-card">
                <h5 class="mb-4">
                    <i class="fas fa-info-circle me-2 text-primary"></i>المعلومات الأساسية
                </h5>
                
                <div class="detail-row">
                    <span class="detail-label">اسم الباقة</span>
                    <span class="detail-value">{{ $package->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">نوع المنتج</span>
                    <span class="detail-value">{{ $package->productType->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">المنصة التعليمية</span>
                    <span class="detail-value">{{ $package->platform->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">المعلم</span>
                    <span class="detail-value">{{ $package->platform->teacher->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">المادة</span>
                    <span class="detail-value">{{ $package->platform->teacher->subject->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">الجيل</span>
                    <span class="detail-value">{{ $package->platform->teacher->subject->generation->display_name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">الحالة</span>
                    <span class="detail-value">
                        <span class="badge {{ $package->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $package->is_active ? 'نشطة' : 'غير نشطة' }}
                        </span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">تاريخ الإنشاء</span>
                    <span class="detail-value">{{ $package->created_at->format('Y-m-d H:i') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">آخر تحديث</span>
                    <span class="detail-value">{{ $package->updated_at->format('Y-m-d H:i') }}</span>
                </div>

                @if($package->description)
                    <div class="mt-4">
                        <h6 class="detail-label">وصف الباقة</h6>
                        <p class="detail-value mt-2">{{ $package->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Package Specifications -->
            <div class="info-card">
                <h5 class="mb-4">
                    <i class="fas fa-{{ $package->is_digital ? 'laptop' : 'book' }} me-2 text-{{ $package->is_digital ? 'primary' : 'warning' }}"></i>
                    مواصفات {{ $package->package_type }}
                </h5>

                @if($package->is_digital)
                    <!-- Digital Package Specs -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-row">
                                <span class="detail-label">مدة الصلاحية</span>
                                <span class="detail-value">
                                    {{ $package->duration_display ?: 'غير محدودة' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <span class="detail-label">عدد الدروس</span>
                                <span class="detail-value">
                                    {{ $package->lessons_display ?: 'غير محدود' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($package->duration_days)
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-clock me-2"></i>
                            البطاقات المُصدرة من هذه الباقة تنتهي صلاحيتها خلال {{ $package->duration_days }} يوم من تاريخ الإصدار
                        </div>
                    @endif
                @else
                    <!-- Physical Package Specs -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-row">
                                <span class="detail-label">عدد الصفحات</span>
                                <span class="detail-value">
                                    {{ $package->pages_display ?: 'غير محدد' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <span class="detail-label">الوزن</span>
                                <span class="detail-value">
                                    {{ $package->weight_display ?: 'غير محدد' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($package->weight_grams)
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-shipping-fast me-2"></i>
                            وزن الدوسية {{ $package->weight_display }} - يؤثر على تكلفة الشحن
                        </div>
                    @endif
                @endif
            </div>

            <!-- Related Pricing -->
            @if($package->educationalPricing && $package->educationalPricing->count() > 0)
                <div class="info-card">
                    <h5 class="mb-4">
                        <i class="fas fa-dollar-sign me-2 text-success"></i>التسعير المتاح
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>السعر</th>
                                    @if(!$package->is_digital)
                                        <th>منطقة الشحن</th>
                                        <th>تكلفة الشحن</th>
                                    @endif
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($package->educationalPricing as $pricing)
                                    <tr>
                                        <td><strong>{{ $pricing->formatted_price }}</strong></td>
                                        @if(!$package->is_digital)
                                            <td>{{ $pricing->region ? $pricing->region->name : 'غير محدد' }}</td>
                                            <td>{{ $pricing->formatted_shipping_cost }}</td>
                                        @endif
                                        <td>
                                            <span class="badge {{ $pricing->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $pricing->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.educational.pricing.show', $pricing) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.educational.pricing.create') }}?package_id={{ $package->id }}" class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i>إضافة تسعير جديد
                        </a>
                    </div>
                </div>
            @else
                <div class="info-card text-center">
                    <i class="fas fa-dollar-sign fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا يوجد تسعير</h5>
                    <p class="text-muted">لم يتم إعداد أي تسعير لهذه الباقة بعد</p>
                    <a href="{{ route('admin.educational.pricing.create') }}?package_id={{ $package->id }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>إضافة تسعير
                    </a>
                </div>
            @endif

            <!-- Inventory Information (Physical packages only) -->
            @if(!$package->is_digital)
                @if($package->educationalInventory && $package->educationalInventory->count() > 0)
                    <div class="info-card">
                        <h5 class="mb-4">
                            <i class="fas fa-warehouse me-2 text-info"></i>معلومات المخزون
                        </h5>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>الكمية المتاحة</th>
                                        <th>الكمية المحجوزة</th>
                                        <th>الكمية الفعلية</th>
                                        <th>حالة المخزون</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($package->educationalInventory as $inventory)
                                        <tr>
                                            <td><strong>{{ number_format($inventory->quantity_available) }}</strong></td>
                                            <td>{{ number_format($inventory->quantity_reserved) }}</td>
                                            <td>{{ number_format($inventory->actual_available) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $inventory->stock_status_class }}">
                                                    {{ $inventory->stock_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.educational.inventory.show', $inventory) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('admin.educational.inventory.create') }}?package_id={{ $package->id }}" class="btn btn-outline-info">
                                <i class="fas fa-plus me-2"></i>إضافة مخزون جديد
                            </a>
                        </div>
                    </div>
                @else
                    <div class="info-card text-center">
                        <i class="fas fa-warehouse fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا يوجد مخزون</h5>
                        <p class="text-muted">لم يتم إعداد أي مخزون لهذه الدوسية بعد</p>
                        <a href="{{ route('admin.educational.inventory.create') }}?package_id={{ $package->id }}" class="btn btn-info">
                            <i class="fas fa-plus me-2"></i>إضافة مخزون
                        </a>
                    </div>
                @endif
            @endif
        </div>

        <!-- Statistics and Quick Actions -->
        <div class="col-md-4">
            <!-- Quick Stats -->
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="fas fa-chart-line me-2"></i>إحصائيات سريعة
                </h6>
                
                <div class="row g-2">
                    <div class="col-6">
                        <div class="stat-item">
                            <div class="stat-number text-primary">{{ $package->educationalPricing->count() }}</div>
                            <small class="text-muted">أسعار متاحة</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item">
                            <div class="stat-number text-success">{{ $package->educationalPricing->where('is_active', true)->count() }}</div>
                            <small class="text-muted">أسعار نشطة</small>
                        </div>
                    </div>
                    @if($package->is_digital)
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-info">{{ $package->educationalCards ? $package->educationalCards->count() : 0 }}</div>
                                <small class="text-muted">بطاقات مُصدرة</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-warning">{{ $package->educationalCards ? $package->educationalCards->where('status', 'active')->count() : 0 }}</div>
                                <small class="text-muted">بطاقات نشطة</small>
                            </div>
                        </div>
                    @else
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-info">{{ $package->educationalInventory->sum('quantity_available') }}</div>
                                <small class="text-muted">إجمالي المخزون</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number text-warning">{{ $package->educationalInventory->sum('quantity_reserved') }}</div>
                                <small class="text-muted">مخزون محجوز</small>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="mt-3 text-center">
                    <a href="{{ route('admin.educational.packages.statistics', $package) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-chart-bar me-2"></i>عرض التفاصيل
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="fas fa-bolt me-2"></i>إجراءات سريعة
                </h6>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.educational.packages.edit', $package) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>تعديل الباقة
                    </a>
                    
                    @if($package->educationalPricing->count() == 0)
                        <a href="{{ route('admin.educational.pricing.create') }}?package_id={{ $package->id }}" class="btn btn-outline-success">
                            <i class="fas fa-dollar-sign me-2"></i>إضافة تسعير
                        </a>
                    @endif
                    
                    @if(!$package->is_digital && $package->educationalInventory->count() == 0)
                        <a href="{{ route('admin.educational.inventory.create') }}?package_id={{ $package->id }}" class="btn btn-outline-info">
                            <i class="fas fa-warehouse me-2"></i>إضافة مخزون
                        </a>
                    @endif
                    
                    <button class="btn btn-outline-secondary" onclick="clonePackage({{ $package->id }})">
                        <i class="fas fa-copy me-2"></i>نسخ الباقة
                    </button>
                    
                    <button class="btn btn-outline-{{ $package->is_active ? 'warning' : 'success' }}" onclick="togglePackageStatus({{ $package->id }})">
                        <i class="fas fa-toggle-{{ $package->is_active ? 'off' : 'on' }} me-2"></i>
                        {{ $package->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                    </button>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="fas fa-history me-2"></i>النشاط الأخير
                </h6>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon bg-primary">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <div>
                            <strong>تم إنشاء الباقة</strong>
                            <br><small class="text-muted">{{ $package->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    
                    @if($package->created_at != $package->updated_at)
                        <div class="timeline-item">
                            <div class="timeline-icon bg-info">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <div>
                                <strong>تم تحديث البيانات</strong>
                                <br><small class="text-muted">{{ $package->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endif
                    
                    @if($package->educationalPricing->count() > 0)
                        <div class="timeline-item">
                            <div class="timeline-icon bg-success">
                                <i class="fas fa-dollar-sign text-white"></i>
                            </div>
                            <div>
                                <strong>تم إضافة تسعير</strong>
                                <br><small class="text-muted">{{ $package->educationalPricing->first()->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Navigation -->
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="fas fa-link me-2"></i>روابط سريعة
                </h6>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.educational.packages.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>جميع الباقات
                    </a>
                    <a href="{{ route('admin.educational.platforms.show', $package->platform) }}" class="btn btn-outline-primary">
                        <i class="fas fa-desktop me-2"></i>المنصة التعليمية
                    </a>
                    <a href="{{ route('admin.educational.teachers.show', $package->platform->teacher) }}" class="btn btn-outline-info">
                        <i class="fas fa-user-tie me-2"></i>المعلم
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clone Modal -->
<div class="modal fade" id="cloneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">نسخ الباقة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cloneForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>اختر المنصة التي تريد نسخ الباقة إليها:</p>
                    <select name="target_platform_id" class="form-select" required>
                        <option value="">اختر المنصة...</option>
                        @foreach($platforms as $platform)
                            @if($platform->id != $package->platform_id)
                                <option value="{{ $platform->id }}">{{ $platform->teaching_chain }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">نسخ الباقة</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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

// Clone package
function clonePackage(packageId) {
    document.getElementById('cloneForm').action = `/admin/educational/packages/${packageId}/clone`;
    new bootstrap.Modal(document.getElementById('cloneModal')).show();
}

// Delete package
function deletePackage(packageId) {
    if(confirm('هل أنت متأكد من حذف هذه الباقة؟\n\nتحذير: سيتم حذف جميع البيانات المرتبطة بها (التسعير، المخزون، إلخ)\n\nلا يمكن التراجع عن هذا الإجراء.')) {
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