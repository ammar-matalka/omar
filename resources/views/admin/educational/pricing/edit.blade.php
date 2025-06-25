@extends('layouts.admin')

@section('title', 'تعديل التسعير')

@push('styles')
<style>
.form-section {
    background: white;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    margin-bottom: 1.5rem;
}

.section-header {
    background: #f8fafc;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 0.75rem 0.75rem 0 0;
}

.section-content {
    padding: 1.5rem;
}

.current-info-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.chain-display {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.chain-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.chain-item:last-child {
    border-bottom: none;
}

.chain-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 1rem;
    color: white;
    font-size: 0.875rem;
}

.generation-icon { background: #8b5cf6; }
.subject-icon { background: #06b6d4; }
.teacher-icon { background: #f59e0b; }
.platform-icon { background: #10b981; }
.package-icon { background: #ef4444; }

.pricing-preview {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-radius: 0.75rem;
    padding: 2rem;
    text-align: center;
    margin-top: 1rem;
}

.price-display {
    font-size: 2.5rem;
    font-weight: 900;
    margin: 1rem 0;
}

.change-warning {
    background: #fef3c7;
    border: 1px solid #fbbf24;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
}

.quick-actions {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
}

.comparison-table {
    background: #f8fafc;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .current-info-card {
        text-align: center;
    }
    
    .chain-item {
        text-align: center;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .chain-icon {
        margin: 0;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="current-info-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-2">تعديل التسعير</h1>
                <p class="mb-1 opacity-75">{{ $pricing->package->name }}</p>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <span class="badge bg-light text-dark">{{ $pricing->formatted_price }}</span>
                    @if(!$pricing->is_digital && $pricing->region)
                        <span class="badge bg-light text-dark">{{ $pricing->region->name }}</span>
                    @endif
                    <span class="badge bg-{{ $pricing->is_active ? 'success' : 'danger' }}">
                        {{ $pricing->is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-white-50 justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white-50">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.educational.pricing.index') }}" class="text-white-50">التسعير</a></li>
                        <li class="breadcrumb-item active text-white">تعديل</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.educational.pricing.update', $pricing) }}" method="POST" id="pricingForm">
        @csrf
        @method('PUT')
        
        <!-- Educational Chain (Display Only) -->
        <div class="form-section">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-link me-2"></i>السلسلة التعليمية (عرض فقط)
                </h5>
            </div>
            <div class="section-content">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    لا يمكن تغيير السلسلة التعليمية بعد إنشاء التسعير. إذا كنت تحتاج لتغييرها، قم بإنشاء تسعير جديد.
                </div>

                <div class="chain-display">
                    <div class="chain-item">
                        <div class="chain-icon generation-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <strong>{{ $pricing->generation->display_name }}</strong>
                            <br><small class="text-muted">الجيل الدراسي</small>
                        </div>
                    </div>

                    <div class="chain-item">
                        <div class="chain-icon subject-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <strong>{{ $pricing->subject->name }}</strong>
                            <br><small class="text-muted">المادة</small>
                        </div>
                    </div>

                    <div class="chain-item">
                        <div class="chain-icon teacher-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <strong>{{ $pricing->teacher->name }}</strong>
                            <br><small class="text-muted">المعلم</small>
                        </div>
                    </div>

                    <div class="chain-item">
                        <div class="chain-icon platform-icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div>
                            <strong>{{ $pricing->platform->name }}</strong>
                            <br><small class="text-muted">المنصة التعليمية</small>
                        </div>
                    </div>

                    <div class="chain-item">
                        <div class="chain-icon package-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <strong>{{ $pricing->package->name }}</strong>
                            <br><small class="text-muted">{{ $pricing->package->package_type }}</small>
                        </div>
                    </div>

                    @if(!$pricing->is_digital && $pricing->region)
                        <div class="chain-item">
                            <div class="chain-icon" style="background: #6366f1;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <strong>{{ $pricing->region->name }}</strong>
                                <br><small class="text-muted">منطقة الشحن - {{ $pricing->region->formatted_shipping_cost }}</small>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Hidden fields for the chain -->
                <input type="hidden" name="generation_id" value="{{ $pricing->generation_id }}">
                <input type="hidden" name="subject_id" value="{{ $pricing->subject_id }}">
                <input type="hidden" name="teacher_id" value="{{ $pricing->teacher_id }}">
                <input type="hidden" name="platform_id" value="{{ $pricing->platform_id }}">
                <input type="hidden" name="package_id" value="{{ $pricing->package_id }}">
                <input type="hidden" name="region_id" value="{{ $pricing->region_id }}">
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="form-section">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-dollar-sign me-2"></i>تعديل السعر
                </h5>
            </div>
            <div class="section-content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group mb-4">
                            <label for="price" class="form-label">سعر المنتج <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="price" id="price" class="form-control" 
                                       value="{{ old('price', $pricing->price) }}" required min="0" max="9999.99" step="0.01"
                                       placeholder="0.00">
                                <span class="input-group-text">دينار أردني</span>
                            </div>
                            <div class="form-text">
                                السعر الحالي: <strong>{{ $pricing->formatted_price }}</strong>
                                @if(!$pricing->is_digital && $pricing->shipping_cost > 0)
                                    + {{ $pricing->formatted_shipping_cost }} شحن = <strong>{{ $pricing->formatted_total_cost }}</strong>
                                @endif
                            </div>
                            @error('price')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="is_active" class="form-label">حالة التسعير</label>
                            <select name="is_active" id="is_active" class="form-select">
                                <option value="1" {{ old('is_active', $pricing->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ old('is_active', $pricing->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                            <div class="form-text">
                                الأسعار النشطة فقط تظهر للطلاب في النظام
                            </div>
                        </div>

                        <!-- Quick Price Actions -->
                        <div class="quick-actions">
                            <h6><i class="fas fa-magic me-2"></i>إجراءات سريعة للسعر</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="adjustPrice(-1)">
                                        خصم 1 دينار
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-warning btn-sm w-100" onclick="adjustPrice(1)">
                                        زيادة 1 دينار
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-info btn-sm w-100" onclick="adjustPercentage(-10)">
                                        خصم 10%
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="adjustPercentage(10)">
                                        زيادة 10%
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">خصم/زيادة</span>
                                        <input type="number" id="customAmount" class="form-control" step="0.01" placeholder="0.00">
                                        <button type="button" class="btn btn-outline-secondary" onclick="adjustCustomAmount()">تطبيق</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">نسبة %</span>
                                        <input type="number" id="customPercentage" class="form-control" step="0.01" placeholder="0">
                                        <button type="button" class="btn btn-outline-secondary" onclick="adjustCustomPercentage()">تطبيق</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Price Comparison -->
                        @if($similarPricing->count() > 0)
                        <div class="comparison-table">
                            <h6><i class="fas fa-chart-line me-2"></i>مقارنة مع أسعار مشابهة</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>الباقة</th>
                                            @if(!$pricing->is_digital)
                                                <th>المنطقة</th>
                                            @endif
                                            <th>السعر</th>
                                            <th>الفرق</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($similarPricing as $similar)
                                            <tr class="{{ $similar->id == $pricing->id ? 'table-warning' : '' }}">
                                                <td>{{ $similar->package->name }}</td>
                                                @if(!$pricing->is_digital)
                                                    <td>{{ $similar->region ? $similar->region->name : 'غير محدد' }}</td>
                                                @endif
                                                <td>{{ $similar->formatted_price }}</td>
                                                <td>
                                                    @if($similar->id != $pricing->id)
                                                        @php $diff = $similar->price - $pricing->price; @endphp
                                                        <span class="text-{{ $diff > 0 ? 'success' : ($diff < 0 ? 'danger' : 'muted') }}">
                                                            {{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 2) }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">الحالي</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <!-- Pricing Preview -->
                        <div class="pricing-preview" id="pricing-preview">
                            <h6 class="mb-3">معاينة السعر الجديد</h6>
                            <div class="price-display" id="preview-price">{{ $pricing->formatted_price }}</div>
                            @if(!$pricing->is_digital && $pricing->shipping_cost > 0)
                                <div id="shipping-info">
                                    <small>+ رسوم الشحن: {{ $pricing->formatted_shipping_cost }}</small>
                                </div>
                                <hr style="border-color: rgba(255,255,255,0.3); margin: 1rem 0;">
                                <div>
                                    <strong>المجموع: <span id="total-price">{{ $pricing->formatted_total_cost }}</span></strong>
                                </div>
                            @endif
                            
                            <div class="mt-3" id="price-change-indicator" style="display: none;">
                                <small id="change-text"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-section">
            <div class="section-content">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('admin.educational.pricing.show', $pricing) }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-right me-2"></i>العودة للتفاصيل
                        </a>
                        <a href="{{ route('admin.educational.pricing.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>قائمة الأسعار
                        </a>
                    </div>
                    <div>
                        <button type="submit" name="action" value="save" class="btn btn-success me-2">
                            <i class="fas fa-save me-2"></i>حفظ التغييرات
                        </button>
                        <button type="submit" name="action" value="save_and_continue" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ وإضافة سعر آخر
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const originalPrice = {{ $pricing->price }};
const shippingCost = {{ $pricing->shipping_cost ?? 0 }};
const isDigital = {{ $pricing->is_digital ? 'true' : 'false' }};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updatePricing();
    
    // Price input event
    document.getElementById('price').addEventListener('input', updatePricing);
});

// Update pricing preview
function updatePricing() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const total = price + (isDigital ? 0 : shippingCost);
    
    document.getElementById('preview-price').textContent = price.toFixed(2) + ' د.أ';
    
    if (!isDigital && shippingCost > 0) {
        document.getElementById('total-price').textContent = total.toFixed(2) + ' د.أ';
    }
    
    // Show price change indicator
    const changeIndicator = document.getElementById('price-change-indicator');
    const changeText = document.getElementById('change-text');
    
    if (price !== originalPrice) {
        const diff = price - originalPrice;
        const percentage = ((diff / originalPrice) * 100).toFixed(1);
        
        changeIndicator.style.display = 'block';
        changeText.innerHTML = `
            <span class="text-${diff > 0 ? 'success' : 'danger'}">
                ${diff > 0 ? '↗' : '↘'} ${diff > 0 ? '+' : ''}${diff.toFixed(2)} د.أ (${diff > 0 ? '+' : ''}${percentage}%)
            </span>
        `;
    } else {
        changeIndicator.style.display = 'none';
    }
}

// Adjust price by amount
function adjustPrice(amount) {
    const currentPrice = parseFloat(document.getElementById('price').value) || 0;
    const newPrice = Math.max(0, currentPrice + amount);
    document.getElementById('price').value = newPrice.toFixed(2);
    updatePricing();
}

// Adjust price by percentage
function adjustPercentage(percentage) {
    const currentPrice = parseFloat(document.getElementById('price').value) || 0;
    const newPrice = Math.max(0, currentPrice * (1 + percentage / 100));
    document.getElementById('price').value = newPrice.toFixed(2);
    updatePricing();
}

// Adjust by custom amount
function adjustCustomAmount() {
    const customAmount = parseFloat(document.getElementById('customAmount').value) || 0;
    if (customAmount !== 0) {
        adjustPrice(customAmount);
        document.getElementById('customAmount').value = '';
    }
}

// Adjust by custom percentage
function adjustCustomPercentage() {
    const customPercentage = parseFloat(document.getElementById('customPercentage').value) || 0;
    if (customPercentage !== 0) {
        adjustPercentage(customPercentage);
        document.getElementById('customPercentage').value = '';
    }
}

// Form validation
document.getElementById('pricingForm').addEventListener('submit', function(e) {
    const price = parseFloat(document.getElementById('price').value);
    
    if (!price || price <= 0) {
        e.preventDefault();
        alert('يرجى إدخال سعر صحيح أكبر من الصفر');
        return;
    }
    
    if (price > 9999.99) {
        e.preventDefault();
        alert('السعر لا يمكن أن يتجاوز 9999.99 دينار');
        return;
    }
    
    // Confirm if price change is significant
    const priceDiff = Math.abs(price - originalPrice);
    const percentageChange = (priceDiff / originalPrice) * 100;
    
    if (percentageChange > 50) {
        if (!confirm(`تحذير: تغيير السعر بنسبة ${percentageChange.toFixed(1)}% قد يؤثر على المبيعات.\n\nهل أنت متأكد من المتابعة؟`)) {
            e.preventDefault();
            return;
        }
    }
});
</script>
@endpush