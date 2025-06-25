@extends('layouts.admin')

@section('title', 'تفاصيل التسعير')

@push('styles')
<style>
.pricing-header {
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

.price-display {
    font-size: 3rem;
    font-weight: 900;
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-radius: 1rem;
    margin-bottom: 2rem;
}

.price-breakdown {
    background: #f8fafc;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
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

.chain-display {
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.chain-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    background: white;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
    border: 1px solid #e5e7eb;
}

.chain-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 1rem;
    color: white;
    font-weight: 600;
}

.generation-icon { background: #8b5cf6; }
.subject-icon { background: #06b6d4; }
.teacher-icon { background: #f59e0b; }
.platform-icon { background: #10b981; }
.package-icon { background: #ef4444; }

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

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.product-type-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.digital-badge {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.physical-badge {
    background: linear-gradient(135deg, #f093fb, #f5576c);
    color: white;
}

@media (max-width: 768px) {
    .pricing-header {
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
    
    .price-display {
        font-size: 2rem;
        padding: 1.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="pricing-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-3">
                    <span class="product-type-badge {{ $pricing->is_digital ? 'digital-badge' : 'physical-badge' }} me-3">
                        <i class="fas fa-{{ $pricing->is_digital ? 'laptop' : 'book' }} me-2"></i>
                        {{ $pricing->package->package_type }}
                    </span>
                    <span class="badge {{ $pricing->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $pricing->is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>
                <h1 class="h2 mb-2">تسعير: {{ $pricing->package->name }}</h1>
                <p class="mb-0 opacity-75">{{ $pricing->generation->display_name }} - {{ $pricing->subject->name }} - {{ $pricing->teacher->name }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="action-buttons">
                    <a href="{{ route('admin.educational.pricing.edit', $pricing) }}" class="btn btn-light">
                        <i class="fas fa-edit me-2"></i>تعديل
                    </a>
                    <button class="btn btn-light" onclick="togglePricingStatus({{ $pricing->id }})">
                        <i class="fas fa-toggle-{{ $pricing->is_active ? 'off' : 'on' }} me-2"></i>
                        {{ $pricing->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.educational.packages.show', $pricing->package) }}">
                                <i class="fas fa-box me-2"></i>عرض الباقة
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.educational.pricing.create') }}?package_id={{ $pricing->package_id }}">
                                <i class="fas fa-copy me-2"></i>نسخ التسعير
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button class="dropdown-item text-danger" onclick="deletePricing({{ $pricing->id }})">
                                <i class="fas fa-trash me-2"></i>حذف التسعير
                            </button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb text-white-50">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white-50">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.educational.pricing.index') }}" class="text-white-50">التسعير</a></li>
                <li class="breadcrumb-item active text-white">تفاصيل التسعير</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-md-8">
            <!-- Price Display -->
            <div class="price-display">
                <div>{{ $pricing->formatted_price }}</div>
                @if(!$pricing->is_digital && $pricing->shipping_cost > 0)
                    <div style="font-size: 1rem; opacity: 0.8;">+ {{ $pricing->formatted_shipping_cost }} شحن</div>
                    <hr style="border-color: rgba(255,255,255,0.3); margin: 1rem 0;">
                    <div style="font-size: 1.5rem;">المجموع: {{ $pricing->formatted_total_cost }}</div>
                @endif
            </div>

            <!-- Price Breakdown -->
            @if(!$pricing->is_digital)
            <div class="price-breakdown">
                <h5 class="mb-3">
                    <i class="fas fa-calculator me-2"></i>تفصيل السعر
                </h5>
                
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="border-end">
                            <h6 class="text-muted">سعر المنتج</h6>
                            <h4 class="text-primary">{{ $pricing->formatted_price }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border-end">
                            <h6 class="text-muted">رسوم الشحن</h6>
                            <h4 class="text-{{ $pricing->shipping_cost > 0 ? 'warning' : 'success' }}">
                                {{ $pricing->formatted_shipping_cost }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">المجموع النهائي</h6>
                        <h4 class="text-success">{{ $pricing->formatted_total_cost }}</h4>
                    </div>
                </div>
                
                @if($pricing->region)
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            منطقة الشحن: {{ $pricing->region->name }}
                        </small>
                    </div>
                @endif
            </div>
            @endif

            <!-- Educational Chain -->
            <div class="info-card">
                <h5 class="mb-4">
                    <i class="fas fa-link me-2"></i>السلسلة التعليمية
                </h5>
                
                <div class="chain-display">
                    <div class="chain-item">
                        <div class="chain-icon generation-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <strong>الجيل الدراسي</strong>
                            <br><span class="text-muted">{{ $pricing->generation->display_name }}</span>
                        </div>
                    </div>

                    <div class="chain-item">
                        <div class="chain-icon subject-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <strong>المادة</strong>
                            <br><span class="text-muted">{{ $pricing->subject->name }}</span>
                        </div>
                    </div>

                    <div class="chain-item">
                        <div class="chain-icon teacher-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <strong>المعلم</strong>
                            <br><span class="text-muted">{{ $pricing->teacher->name }}</span>
                        </div>
                    </div>

                    <div class="chain-item">
                        <div class="chain-icon platform-icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div>
                            <strong>المنصة التعليمية</strong>
                            <br><span class="text-muted">{{ $pricing->platform->name }}</span>
                        </div>
                    </div>

                    <div class="chain-item">
                        <div class="chain-icon package-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <strong>الباقة</strong>
                            <br><span class="text-muted">{{ $pricing->package->name }}</span>
                            <span class="badge bg-{{ $pricing->is_digital ? 'primary' : 'warning' }} ms-2">{{ $pricing->package->package_type }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Details -->
            <div class="info-card">
                <h5 class="mb-4">
                    <i class="fas fa-info-circle me-2"></i>تفاصيل الباقة
                </h5>
                
                <div class="detail-row">
                    <span class="detail-label">اسم الباقة</span>
                    <span class="detail-value">{{ $pricing->package->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">نوع المنتج</span>
                    <span class="detail-value">{{ $pricing->package->productType->name }}</span>
                </div>

                @if($pricing->package->description)
                    <div class="detail-row">
                        <span class="detail-label">وصف الباقة</span>
                        <span class="detail-value">{{ $pricing->package->description }}</span>
                    </div>
                @endif

                @if($pricing->is_digital)
                    @if($pricing->package->duration_days)
                        <div class="detail-row">
                            <span class="detail-label">مدة الصلاحية</span>
                            <span class="detail-value">{{ $pricing->package->duration_display }}</span>
                        </div>
                    @endif
                    @if($pricing->package->lessons_count)
                        <div class="detail-row">
                            <span class="detail-label">عدد الدروس</span>
                            <span class="detail-value">{{ $pricing->package->lessons_display }}</span>
                        </div>
                    @endif
                @else
                    @if($pricing->package->pages_count)
                        <div class="detail-row">
                            <span class="detail-label">عدد الصفحات</span>
                            <span class="detail-value">{{ $pricing->package->pages_display }}</span>
                        </div>
                    @endif
                    @if($pricing->package->weight_grams)
                        <div class="detail-row">
                            <span class="detail-label">الوزن</span>
                            <span class="detail-value">{{ $pricing->package->weight_display }}</span>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Pricing Information -->
            <div class="info-card">
                <h5 class="mb-4">
                    <i class="fas fa-dollar-sign me-2"></i>معلومات التسعير
                </h5>
                
                <div class="detail-row">
                    <span class="detail-label">السعر الأساسي</span>
                    <span class="detail-value">{{ $pricing->formatted_price }}</span>
                </div>
                
                @if(!$pricing->is_digital)
                    <div class="detail-row">
                        <span class="detail-label">منطقة الشحن</span>
                        <span class="detail-value">{{ $pricing->region ? $pricing->region->name : 'غير محدد' }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">تكلفة الشحن</span>
                        <span class="detail-value">{{ $pricing->formatted_shipping_cost }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">المجموع النهائي</span>
                        <span class="detail-value"><strong>{{ $pricing->formatted_total_cost }}</strong></span>
                    </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">حالة التسعير</span>
                    <span class="detail-value">
                        <span class="badge {{ $pricing->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $pricing->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">تاريخ الإنشاء</span>
                    <span class="detail-value">{{ $pricing->created_at->format('Y-m-d H:i') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">آخر تحديث</span>
                    <span class="detail-value">{{ $pricing->updated_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="fas fa-bolt me-2"></i>إجراءات سريعة
                </h6>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.educational.pricing.edit', $pricing) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>تعديل التسعير
                    </a>
                    
                    <button class="btn btn-outline-{{ $pricing->is_active ? 'warning' : 'success' }}" onclick="togglePricingStatus({{ $pricing->id }})">
                        <i class="fas fa-toggle-{{ $pricing->is_active ? 'off' : 'on' }} me-2"></i>
                        {{ $pricing->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                    </button>
                    
                    <a href="{{ route('admin.educational.pricing.create') }}?package_id={{ $pricing->package_id }}" class="btn btn-outline-secondary">
                        <i class="fas fa-copy me-2"></i>نسخ التسعير
                    </a>
                    
                    <hr>
                    
                    <button class="btn btn-outline-danger" onclick="deletePricing({{ $pricing->id }})">
                        <i class="fas fa-trash me-2"></i>حذف التسعير
                    </button>
                </div>
            </div>

            <!-- Related Information -->
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="fas fa-link me-2"></i>روابط ذات صلة
                </h6>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.educational.packages.show', $pricing->package) }}" class="btn btn-outline-primary">
                        <i class="fas fa-box me-2"></i>عرض الباقة
                    </a>
                    <a href="{{ route('admin.educational.platforms.show', $pricing->platform) }}" class="btn btn-outline-info">
                        <i class="fas fa-desktop me-2"></i>عرض المنصة
                    </a>
                    <a href="{{ route('admin.educational.teachers.show', $pricing->teacher) }}" class="btn btn-outline-warning">
                        <i class="fas fa-user-tie me-2"></i>عرض المعلم
                    </a>
                    @if(!$pricing->is_digital && $pricing->region)
                        <a href="{{ route('admin.educational.regions.show', $pricing->region) }}" class="btn btn-outline-success">
                            <i class="fas fa-map-marker-alt me-2"></i>عرض المنطقة
                        </a>
                    @endif
                </div>
            </div>

            <!-- Comparison with Other Prices -->
            @if($similarPricing->count() > 0)
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="fas fa-chart-line me-2"></i>أسعار مشابهة
                </h6>
                
                @foreach($similarPricing as $similar)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                        <div>
                            <small class="text-muted">{{ $similar->package->name }}</small>
                            @if($similar->region)
                                <br><small class="text-info">{{ $similar->region->name }}</small>
                            @endif
                        </div>
                        <strong class="text-{{ $similar->price < $pricing->price ? 'success' : ($similar->price > $pricing->price ? 'danger' : 'info') }}">
                            {{ $similar->formatted_price }}
                        </strong>
                    </div>
                @endforeach
                
                <div class="mt-3 text-center">
                    <a href="{{ route('admin.educational.pricing.index') }}?package_id={{ $pricing->package_id }}" class="btn btn-sm btn-outline-primary">
                        عرض جميع أسعار هذه الباقة
                    </a>
                </div>
            </div>
            @endif

            <!-- Navigation -->
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="fas fa-navigation me-2"></i>التنقل
                </h6>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.educational.pricing.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>جميع الأسعار
                    </a>
                    <a href="{{ route('admin.educational.pricing.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>إضافة سعر جديد
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle pricing status
function togglePricingStatus(pricingId) {
    if(confirm('هل أنت متأكد من تغيير حالة هذا التسعير؟')) {
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
                showAlert('error', 'حدث خطأ أثناء تغيير حالة التسعير');
            }
        })
        .catch(error => {
            showAlert('error', 'حدث خطأ في الاتصال');
        });
    }
}

// Delete pricing
function deletePricing(pricingId) {
    if(confirm('هل أنت متأكد من حذف هذا التسعير؟\n\nتحذير: سيؤثر هذا على إمكانية شراء هذا المنتج.\n\nلا يمكن التراجع عن هذا الإجراء.')) {
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