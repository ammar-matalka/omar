@extends('layouts.admin')

@section('title', 'إضافة باقة تعليمية جديدة')

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

.form-group {
    margin-bottom: 1.5rem;
}

.conditional-fields {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
}

.field-description {
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

.preview-card {
    background: #f8fafc;
    border: 2px dashed #d1d5db;
    border-radius: 0.75rem;
    padding: 2rem;
    text-align: center;
    margin-top: 1rem;
}

.package-type-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.type-option {
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.type-option:hover {
    border-color: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.type-option.selected {
    border-color: #3b82f6;
    background: #eff6ff;
}

.type-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .package-type-selector {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">إضافة باقة تعليمية جديدة</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.educational.packages.index') }}">الباقات التعليمية</a></li>
                    <li class="breadcrumb-item active">إضافة باقة جديدة</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.educational.packages.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>

    <form action="{{ route('admin.educational.packages.store') }}" method="POST" id="packageForm">
        @csrf
        
        <!-- Product Type Selection -->
        <div class="form-section">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-tag me-2"></i>نوع المنتج
                </h5>
            </div>
            <div class="section-content">
                <div class="package-type-selector">
                    @foreach($productTypes as $type)
                        <div class="type-option" data-type-id="{{ $type->id }}" data-is-digital="{{ $type->is_digital ? 'true' : 'false' }}">
                            <div class="type-icon text-{{ $type->is_digital ? 'primary' : 'warning' }}">
                                <i class="fas fa-{{ $type->is_digital ? 'laptop' : 'book' }}"></i>
                            </div>
                            <h6>{{ $type->name }}</h6>
                            <small class="text-muted">{{ $type->is_digital ? 'بطاقات رقمية' : 'دوسيات ورقية' }}</small>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="product_type_id" id="product_type_id" required>
                @error('product_type_id')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Platform Selection -->
        <div class="form-section">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-desktop me-2"></i>اختيار المنصة التعليمية
                </h5>
            </div>
            <div class="section-content">
                <div class="form-group">
                    <label for="platform_id" class="form-label">المنصة التعليمية <span class="text-danger">*</span></label>
                    <select name="platform_id" id="platform_id" class="form-select" required>
                        <option value="">اختر المنصة...</option>
                        @foreach($platforms as $platform)
                            <option value="{{ $platform->id }}" {{ old('platform_id') == $platform->id ? 'selected' : '' }}>
                                {{ $platform->teaching_chain }}
                            </option>
                        @endforeach
                    </select>
                    <div class="field-description">
                        اختر المنصة التي ستحتوي على هذه الباقة (الجيل - المادة - المعلم - المنصة)
                    </div>
                    @error('platform_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="form-section">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>المعلومات الأساسية
                </h5>
            </div>
            <div class="section-content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name" class="form-label">اسم الباقة <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="{{ old('name') }}" required maxlength="255"
                                   placeholder="أدخل اسم الباقة التعليمية">
                            <div class="field-description">
                                اسم واضح ومميز للباقة يساعد الطلاب على فهم محتواها
                            </div>
                            @error('name')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="is_active" class="form-label">حالة الباقة</label>
                            <select name="is_active" id="is_active" class="form-select">
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>نشطة</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>غير نشطة</option>
                            </select>
                            <div class="field-description">
                                الباقات النشطة فقط تظهر للطلاب
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">وصف الباقة</label>
                    <textarea name="description" id="description" class="form-control" rows="4" 
                              maxlength="1000" placeholder="وصف تفصيلي للباقة ومحتواها">{{ old('description') }}</textarea>
                    <div class="field-description">
                        وصف يساعد الطلاب على فهم ما تحتويه الباقة (اختياري - حد أقصى 1000 حرف)
                    </div>
                    @error('description')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Digital Package Fields -->
        <div class="form-section" id="digital-fields" style="display: none;">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-laptop me-2 text-primary"></i>إعدادات البطاقة الرقمية
                </h5>
            </div>
            <div class="section-content">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    هذه الإعدادات خاصة بالبطاقات الرقمية التي يحصل عليها الطلاب إلكترونياً
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="duration_days" class="form-label">مدة صلاحية البطاقة (بالأيام)</label>
                            <input type="number" name="duration_days" id="duration_days" class="form-control" 
                                   value="{{ old('duration_days') }}" min="1" max="3650"
                                   placeholder="مثال: 365">
                            <div class="field-description">
                                عدد الأيام التي تبقى فيها البطاقة صالحة للاستخدام (اتركه فارغاً لصلاحية غير محدودة)
                            </div>
                            @error('duration_days')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lessons_count" class="form-label">عدد الدروس</label>
                            <input type="number" name="lessons_count" id="lessons_count" class="form-control" 
                                   value="{{ old('lessons_count') }}" min="1" max="1000"
                                   placeholder="مثال: 50">
                            <div class="field-description">
                                عدد الدروس المتاحة في هذه الباقة (اتركه فارغاً للدروس غير محدودة)
                            </div>
                            @error('lessons_count')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="conditional-fields">
                    <h6><i class="fas fa-clock me-2"></i>خيارات الصلاحية السريعة</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setDuration(30)">
                                شهر واحد
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setDuration(90)">
                                3 أشهر
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setDuration(180)">
                                6 أشهر
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setDuration(365)">
                                سنة كاملة
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Physical Package Fields -->
        <div class="form-section" id="physical-fields" style="display: none;">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-book me-2 text-warning"></i>إعدادات الدوسية الورقية
                </h5>
            </div>
            <div class="section-content">
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    هذه الإعدادات خاصة بالدوسيات الورقية التي يتم طباعتها وشحنها للطلاب
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pages_count" class="form-label">عدد الصفحات</label>
                            <input type="number" name="pages_count" id="pages_count" class="form-control" 
                                   value="{{ old('pages_count') }}" min="1" max="1000"
                                   placeholder="مثال: 120">
                            <div class="field-description">
                                العدد الإجمالي لصفحات الدوسية
                            </div>
                            @error('pages_count')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="weight_grams" class="form-label">الوزن (بالجرام)</label>
                            <input type="number" name="weight_grams" id="weight_grams" class="form-control" 
                                   value="{{ old('weight_grams') }}" min="1" max="10000"
                                   placeholder="مثال: 500">
                            <div class="field-description">
                                وزن الدوسية لحساب تكلفة الشحن (اختياري)
                            </div>
                            @error('weight_grams')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="conditional-fields">
                    <h6><i class="fas fa-calculator me-2"></i>حاسبة الوزن التقريبي</h6>
                    <p class="text-muted small">استخدم هذه المعادلة لتقدير وزن الدوسية:</p>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">عدد الصفحات</label>
                            <input type="number" id="calc_pages" class="form-control" placeholder="120">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">نوع الورق</label>
                            <select id="paper_type" class="form-select">
                                <option value="5">ورق عادي (5 جرام/صفحة)</option>
                                <option value="7">ورق متوسط (7 جرام/صفحة)</option>
                                <option value="10">ورق سميك (10 جرام/صفحة)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">الوزن المقدر</label>
                            <div class="input-group">
                                <input type="text" id="estimated_weight" class="form-control" readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="calculateWeight()">احسب</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="form-section">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-eye me-2"></i>معاينة الباقة
                </h5>
            </div>
            <div class="section-content">
                <div class="preview-card" id="package-preview">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">معاينة الباقة</h5>
                    <p class="text-muted">اختر نوع المنتج والمنصة لرؤية معاينة الباقة</p>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-section">
            <div class="section-content">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.educational.packages.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </a>
                    <div>
                        <button type="submit" name="action" value="save" class="btn btn-success me-2">
                            <i class="fas fa-save me-2"></i>حفظ الباقة
                        </button>
                        <button type="submit" name="action" value="save_and_continue" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ والمتابعة للتسعير
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
let selectedProductType = null;
let selectedPlatform = null;

// Product type selection
document.querySelectorAll('.type-option').forEach(option => {
    option.addEventListener('click', function() {
        // Remove previous selection
        document.querySelectorAll('.type-option').forEach(opt => opt.classList.remove('selected'));
        
        // Select current option
        this.classList.add('selected');
        selectedProductType = this.dataset.typeId;
        document.getElementById('product_type_id').value = selectedProductType;
        
        // Show/hide conditional fields
        const isDigital = this.dataset.isDigital === 'true';
        document.getElementById('digital-fields').style.display = isDigital ? 'block' : 'none';
        document.getElementById('physical-fields').style.display = isDigital ? 'none' : 'block';
        
        updatePreview();
    });
});

// Platform selection
document.getElementById('platform_id').addEventListener('change', function() {
    selectedPlatform = this.value;
    updatePreview();
});

// Form fields for preview update
document.getElementById('name').addEventListener('input', updatePreview);
document.getElementById('description').addEventListener('input', updatePreview);
document.getElementById('duration_days').addEventListener('input', updatePreview);
document.getElementById('lessons_count').addEventListener('input', updatePreview);
document.getElementById('pages_count').addEventListener('input', updatePreview);
document.getElementById('weight_grams').addEventListener('input', updatePreview);

// Set duration helper function
function setDuration(days) {
    document.getElementById('duration_days').value = days;
    updatePreview();
}

// Calculate weight function
function calculateWeight() {
    const pages = parseInt(document.getElementById('calc_pages').value) || 0;
    const paperWeight = parseInt(document.getElementById('paper_type').value) || 5;
    const totalWeight = pages * paperWeight;
    
    document.getElementById('estimated_weight').value = totalWeight + ' جرام';
    document.getElementById('weight_grams').value = totalWeight;
    updatePreview();
}

// Update preview function
function updatePreview() {
    const preview = document.getElementById('package-preview');
    const name = document.getElementById('name').value;
    const description = document.getElementById('description').value;
    const platformSelect = document.getElementById('platform_id');
    const platformText = platformSelect.options[platformSelect.selectedIndex]?.text;
    
    if (!selectedProductType || !selectedPlatform || !name) {
        preview.innerHTML = `
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">معاينة الباقة</h5>
            <p class="text-muted">اختر نوع المنتج والمنصة وأدخل اسم الباقة لرؤية المعاينة</p>
        `;
        return;
    }
    
    const isDigital = document.querySelector('.type-option.selected')?.dataset.isDigital === 'true';
    const duration = document.getElementById('duration_days').value;
    const lessons = document.getElementById('lessons_count').value;
    const pages = document.getElementById('pages_count').value;
    const weight = document.getElementById('weight_grams').value;
    
    let detailsHtml = '';
    if (isDigital) {
        if (duration) detailsHtml += `<small class="d-block text-muted">مدة الصلاحية: ${duration} يوم</small>`;
        if (lessons) detailsHtml += `<small class="d-block text-muted">عدد الدروس: ${lessons} درس</small>`;
    } else {
        if (pages) detailsHtml += `<small class="d-block text-muted">عدد الصفحات: ${pages} صفحة</small>`;
        if (weight) detailsHtml += `<small class="d-block text-muted">الوزن: ${weight} جرام</small>`;
    }
    
    preview.innerHTML = `
        <div class="text-start">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="mb-1">${name}</h5>
                    <span class="badge bg-${isDigital ? 'primary' : 'warning'} mb-2">
                        ${isDigital ? 'بطاقة رقمية' : 'دوسية ورقية'}
                    </span>
                    <div class="text-muted small">${platformText}</div>
                </div>
                <i class="fas fa-${isDigital ? 'laptop' : 'book'} fa-2x text-${isDigital ? 'primary' : 'warning'}"></i>
            </div>
            ${description ? `<p class="text-muted small">${description}</p>` : ''}
            ${detailsHtml}
        </div>
    `;
}

// Form validation
document.getElementById('packageForm').addEventListener('submit', function(e) {
    if (!selectedProductType) {
        e.preventDefault();
        alert('يرجى اختيار نوع المنتج');
        return;
    }
    
    if (!selectedPlatform) {
        e.preventDefault();
        alert('يرجى اختيار المنصة التعليمية');
        return;
    }
});
</script>
@endpush