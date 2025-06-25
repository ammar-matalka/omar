@extends('layouts.admin')

@section('title', 'إدارة التسعير التعليمي')

@push('styles')
<style>
.pricing-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    background: white;
}

.pricing-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.price-badge {
    font-size: 1.25rem;
    font-weight: 700;
    padding: 0.5rem 1rem;
    border-radius: 50px;
}

.digital-price {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.physical-price {
    background: linear-gradient(135deg, #f093fb, #f5576c);
    color: white;
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

.pricing-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.pricing-details {
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

.bulk-actions {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: none;
}

.product-chain {
    font-size: 0.8rem;
    color: #6b7280;
    line-height: 1.2;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .pricing-details {
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
            <h1 class="h3 mb-0 text-gray-800">إدارة التسعير التعليمي</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">النظام التعليمي</a></li>
                    <li class="breadcrumb-item active">التسعير</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.educational.pricing.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>إضافة سعر جديد
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">إجمالي الأسعار</h6>
                    <h3 class="mb-0 text-primary">{{ number_format($pricing->total()) }}</h3>
                </div>
                <div class="text-primary">
                    <i class="fas fa-dollar-sign fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">أسعار نشطة</h6>
                    <h3 class="mb-0 text-success">{{ $pricing->where('is_active', true)->count() }}</h3>
                </div>
                <div class="text-success">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">متوسط السعر</h6>
                    <h3 class="mb-0 text-info">{{ number_format($stats['average_price'] ?? 0, 2) }} د.أ</h3>
                </div>
                <div class="text-info">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">أسعار رقمية</h6>
                    <h3 class="mb-0 text-warning">{{ $pricing->whereNull('region_id')->count() }}</h3>
                </div>
                <div class="text-warning">
                    <i class="fas fa-laptop fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.educational.pricing.index') }}">
            <div class="row g-3">
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
                    <label class="form-label">الباقة</label>
                    <select name="package_id" class="form-select">
                        <option value="">جميع الباقات</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">المنطقة</label>
                    <select name="region_id" class="form-select">
                        <option value="">جميع المناطق</option>
                        <option value="digital" {{ request('region_id') == 'digital' ? 'selected' : '' }}>رقمي فقط</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                                {{ $region->name }}
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
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <label class="form-label">نطاق السعر</label>
                    <div class="input-group">
                        <input type="number" name="price_min" class="form-control" placeholder="من" 
                               value="{{ request('price_min') }}" step="0.01">
                        <span class="input-group-text">إلى</span>
                        <input type="number" name="price_max" class="form-control" placeholder="إلى" 
                               value="{{ request('price_max') }}" step="0.01">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">الترتيب</label>
                    <select name="sort" class="form-select">
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>السعر</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>تاريخ الإنشاء</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">الاتجاه</label>
                    <select name="order" class="form-select">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i>فلترة
                        </button>
                        <a href="{{ route('admin.educational.pricing.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>مسح
                        </a>
                        <a href="{{ route('admin.educational.pricing.export', request()->query()) }}" class="btn btn-outline-success">
                            <i class="fas fa-file-excel me-2"></i>تصدير
                        </a>
                        <button type="button" class="btn btn-outline-warning" onclick="toggleBulkActions()">
                            <i class="fas fa-edit me-2"></i>تحديث جماعي
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActionsPanel">
        <form method="POST" action="{{ route('admin.educational.pricing.bulk-update') }}" id="bulkForm">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">العملية</label>
                    <select name="action" id="bulkAction" class="form-select" required>
                        <option value="">اختر العملية...</option>
                        <option value="update_price">تحديث السعر</option>
                        <option value="add_percentage">إضافة نسبة مئوية</option>
                        <option value="set_status">تغيير الحالة</option>
                    </select>
                </div>
                <div class="col-md-2" id="priceField" style="display: none;">
                    <label class="form-label">السعر الجديد</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0">
                </div>
                <div class="col-md-2" id="percentageField" style="display: none;">
                    <label class="form-label">النسبة المئوية</label>
                    <input type="number" name="percentage" class="form-control" step="0.01">
                </div>
                <div class="col-md-2" id="statusField" style="display: none;">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>تطبيق على المحدد
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="toggleBulkActions()">
                        إلغاء
                    </button>
                </div>
            </div>
            <div class="mt-2">
                <small class="text-muted">
                    <span id="selectedCount">0</span> عنصر محدد
                </small>
            </div>
        </form>
    </div>

    <!-- Pricing List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة الأسعار ({{ $pricing->total() }} سعر)</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll">
                <label class="form-check-label" for="selectAll">
                    تحديد الكل
                </label>
            </div>
        </div>

        <div class="card-body">
            @if($pricing->count() > 0)
                <div class="row g-3">
                    @foreach($pricing as $price)
                        <div class="col-md-6 col-lg-4">
                            <div class="pricing-card p-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input pricing-checkbox" type="checkbox" 
                                               value="{{ $price->id }}" name="pricing_ids[]">
                                    </div>
                                    
                                    <div class="pricing-info flex-grow-1 mx-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="price-badge {{ $price->is_digital ? 'digital-price' : 'physical-price' }}">
                                                {{ $price->formatted_price }}
                                            </span>
                                            <span class="badge {{ $price->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $price->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </div>
                                        
                                        <h6 class="mb-1">{{ $price->package->name }}</h6>
                                        <div class="product-chain">
                                            <div>{{ $price->generation->display_name }} - {{ $price->subject->name }}</div>
                                            <div>{{ $price->teacher->name }} - {{ $price->platform->name }}</div>
                                            @if($price->region)
                                                <div class="text-info">📍 {{ $price->region->name }} - {{ $price->formatted_shipping_cost }}</div>
                                            @else
                                                <div class="text-primary">💻 رقمي - شحن مجاني</div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('admin.educational.pricing.show', $price) }}">
                                                <i class="fas fa-eye me-2"></i>عرض التفاصيل
                                            </a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin.educational.pricing.edit', $price) }}">
                                                <i class="fas fa-edit me-2"></i>تعديل
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><button class="dropdown-item text-primary" onclick="togglePricingStatus({{ $price->id }})">
                                                <i class="fas fa-toggle-{{ $price->is_active ? 'off' : 'on' }} me-2"></i>
                                                {{ $price->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                                            </button></li>
                                            <li><button class="dropdown-item text-danger" onclick="deletePricing({{ $price->id }})">
                                                <i class="fas fa-trash me-2"></i>حذف
                                            </button></li>
                                        </ul>
                                    </div>
                                </div>

                                @if($price->total_cost != $price->price)
                                    <div class="border-top pt-2">
                                        <div class="row text-center text-sm">
                                            <div class="col-6">
                                                <small class="text-muted d-block">سعر المنتج</small>
                                                <strong>{{ $price->formatted_price }}</strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">الإجمالي مع الشحن</small>
                                                <strong class="text-success">{{ $price->formatted_total_cost }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $pricing->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-dollar-sign fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا يوجد تسعير</h5>
                    <p class="text-muted">لم يتم العثور على أي أسعار تطابق المعايير المحددة</p>
                    <a href="{{ route('admin.educational.pricing.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>إضافة سعر جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Bulk actions toggle
function toggleBulkActions() {
    const panel = document.getElementById('bulkActionsPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    updateSelectedCount();
}

// Bulk action type change
document.getElementById('bulkAction').addEventListener('change', function() {
    const action = this.value;
    document.getElementById('priceField').style.display = action === 'update_price' ? 'block' : 'none';
    document.getElementById('percentageField').style.display = action === 'add_percentage' ? 'block' : 'none';
    document.getElementById('statusField').style.display = action === 'set_status' ? 'block' : 'none';
});

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.pricing-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
});

// Individual checkbox change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('pricing-checkbox')) {
        updateSelectedCount();
        
        // Update select all checkbox
        const allCheckboxes = document.querySelectorAll('.pricing-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.pricing-checkbox:checked');
        const selectAllCheckbox = document.getElementById('selectAll');
        
        if (checkedCheckboxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedCheckboxes.length === allCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }
});

// Update selected count
function updateSelectedCount() {
    const checkedBoxes = document.querySelectorAll('.pricing-checkbox:checked');
    document.getElementById('selectedCount').textContent = checkedBoxes.length;
}

// Bulk form submission
document.getElementById('bulkForm').addEventListener('submit', function(e) {
    const checkedBoxes = document.querySelectorAll('.pricing-checkbox:checked');
    if (checkedBoxes.length === 0) {
        e.preventDefault();
        alert('يرجى تحديد عنصر واحد على الأقل');
        return;
    }
    
    const action = document.getElementById('bulkAction').value;
    if (!action) {
        e.preventDefault();
        alert('يرجى اختيار نوع العملية');
        return;
    }
    
    // Add selected IDs to form
    checkedBoxes.forEach(checkbox => {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'pricing_ids[]';
        hiddenInput.value = checkbox.value;
        this.appendChild(hiddenInput);
    });
    
    if (!confirm(`هل أنت متأكد من تطبيق هذه العملية على ${checkedBoxes.length} عنصر؟`)) {
        e.preventDefault();
    }
});

// Toggle pricing status
function togglePricingStatus(pricingId) {
    if(confirm('هل أنت متأكد من تغيير حالة هذا السعر؟')) {
        fetch(`/admin/educational/pricing/${pricingId}/toggle-status`, {
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
                showAlert('error', 'حدث خطأ أثناء تغيير حالة السعر');
            }
        })
        .catch(error => {
            showAlert('error', 'حدث خطأ في الاتصال');
        });
    }
}

// Delete pricing
function deletePricing(pricingId) {
    if(confirm('هل أنت متأكد من حذف هذا السعر؟ لا يمكن التراجع عن هذا الإجراء.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/educational/pricing/${pricingId}`;
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