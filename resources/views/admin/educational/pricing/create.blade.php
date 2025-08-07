{{-- resources/views/admin/educational/pricing/create.blade.php --}}

@extends('layouts.admin')

@section('title', 'إضافة تسعير جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة تسعير تعليمي جديد</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.educational.pricing.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.educational.pricing.store') }}" method="POST" id="pricing-form">
                    @csrf
                    
                    <div class="card-body">
                        <div class="row">
                            {{-- الجيل الدراسي --}}
                            <div class="col-md-6 mb-3">
                                <label for="generation_id" class="form-label required">
                                    <span class="step-number">1</span>
                                    اختر الجيل الدراسي
                                </label>
                                <select name="generation_id" id="generation_id" class="form-control @error('generation_id') is-invalid @enderror" required>
                                    <option value="">اختر الجيل الدراسي...</option>
                                    @foreach($generations as $generation)
                                        <option value="{{ $generation->id }}" {{ old('generation_id') == $generation->id ? 'selected' : '' }}>
                                            {{ $generation->display_name }} ({{ $generation->birth_year }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('generation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">اختر السنة التي ولد فيها الطالب</small>
                            </div>

                            {{-- المادة --}}
                            <div class="col-md-6 mb-3">
                                <label for="subject_id" class="form-label required">
                                    <span class="step-number">2</span>
                                    اختر المادة
                                </label>
                                <select name="subject_id" id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" required disabled>
                                    <option value="">اختر المادة...</option>
                                </select>
                                @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">المواد المتاحة للجيل المختار</small>
                            </div>

                            {{-- المعلم --}}
                            <div class="col-md-6 mb-3">
                                <label for="teacher_id" class="form-label required">
                                    <span class="step-number">3</span>
                                    اختر المعلم
                                </label>
                                <select name="teacher_id" id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror" required disabled>
                                    <option value="">اختر المعلم...</option>
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">المعلمين المتخصصين في المادة</small>
                            </div>

                            {{-- المنصة --}}
                            <div class="col-md-6 mb-3">
                                <label for="platform_id" class="form-label required">
                                    <span class="step-number">4</span>
                                    اختر المنصة
                                </label>
                                <select name="platform_id" id="platform_id" class="form-control @error('platform_id') is-invalid @enderror" required disabled>
                                    <option value="">اختر المنصة...</option>
                                </select>
                                @error('platform_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">منصات المعلم المختار</small>
                            </div>

                            {{-- الباقة --}}
                            <div class="col-md-6 mb-3">
                                <label for="package_id" class="form-label required">
                                    <span class="step-number">5</span>
                                    اختر الباقة
                                </label>
                                <select name="package_id" id="package_id" class="form-control @error('package_id') is-invalid @enderror" required disabled>
                                    <option value="">اختر الباقة...</option>
                                </select>
                                @error('package_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">الباقات المتاحة في المنصة</small>
                            </div>

                            {{-- منطقة الشحن --}}
                            <div class="col-md-6 mb-3" id="region-container" style="display: none;">
                                <label for="region_id" class="form-label">
                                    <span class="step-number">6</span>
                                    منطقة الشحن
                                </label>
                                <select name="region_id" id="region_id" class="form-control @error('region_id') is-invalid @enderror">
                                    <option value="">اختر المنطقة...</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" data-shipping-cost="{{ $region->shipping_cost }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                            {{ $region->name }} ({{ number_format($region->shipping_cost, 2) }} دينار)
                                        </option>
                                    @endforeach
                                </select>
                                @error('region_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">مطلوب للباقات الورقية فقط</small>
                            </div>
                        </div>

                        {{-- السعر --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label required">السعر (دينار)</label>
                                <div class="input-group">
                                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
                                           step="0.01" min="0" max="9999.99" value="{{ old('price') }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">د.أ</span>
                                    </div>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="price_display" class="alert alert-info mt-2" style="display: none;"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label">الحالة</label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">نشط</label>
                                </div>
                                <small class="form-text text-muted">فقط التسعير النشط يظهر للعملاء</small>
                            </div>
                        </div>

                        {{-- معلومات إضافية --}}
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-info-circle"></i> ملاحظات مهمة:</h5>
                            <ul class="mb-0">
                                <li>يجب اختيار الخيارات بالترتيب المحدد</li>
                                <li>منطقة الشحن مطلوبة فقط للباقات الورقية</li>
                                <li>لا يمكن إنشاء تسعير مكرر للنفس الاختيارات</li>
                                <li>السعر يجب أن يكون بين 0 و 9999.99 دينار</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ التسعير
                                </button>
                                <a href="{{ route('admin.educational.pricing.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times"></i> إلغاء
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <small class="text-muted">* الحقول المطلوبة</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    font-size: 12px;
    font-weight: bold;
    margin-left: 8px;
}

.form-label.required::after {
    content: " *";
    color: #dc3545;
}

.form-control:disabled {
    background-color: #f8f9fa;
    opacity: 0.65;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

#region-container {
    transition: all 0.3s ease;
}

.input-group-text {
    background-color: #e9ecef;
    border-color: #ced4da;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-header .card-title {
    color: white;
    font-weight: 600;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: translateY(-1px);
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.loading-spinner {
    display: none;
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 8px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.select-loading {
    position: relative;
}

.select-loading::after {
    content: '';
    position: absolute;
    right: 30px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
</style>
@endpush

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
// Educational Pricing Form JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // عناصر النموذج
    const generationSelect = document.getElementById('generation_id');
    const subjectSelect = document.getElementById('subject_id');
    const teacherSelect = document.getElementById('teacher_id');
    const platformSelect = document.getElementById('platform_id');
    const packageSelect = document.getElementById('package_id');
    const regionSelect = document.getElementById('region_id');
    const regionContainer = document.getElementById('region-container');
    const priceInput = document.getElementById('price');
    const priceDisplay = document.getElementById('price_display');

    // URLs للـ API
    const apiUrls = {
        subjects: '/educational/api/subjects',
        teachers: '/educational/api/teachers',
        platforms: '/educational/api/platforms',
        packages: '/educational/api/packages',
        calculatePrice: '/educational/api/calculate-price'
    };

    // تفعيل/تعطيل العناصر
    function toggleSelect(selectElement, enabled) {
        selectElement.disabled = !enabled;
        if (!enabled) {
            selectElement.innerHTML = '<option value="">اختر...</option>';
            selectElement.classList.remove('select-loading');
        }
    }

    // إضافة حالة التحميل
    function addLoadingState(selectElement) {
        selectElement.classList.add('select-loading');
        selectElement.innerHTML = '<option value="">جاري التحميل...</option>';
        selectElement.disabled = true;
    }

    // إعادة تعيين العناصر التابعة
    function resetDependentSelects(fromIndex) {
        const selects = [subjectSelect, teacherSelect, platformSelect, packageSelect];
        for (let i = fromIndex; i < selects.length; i++) {
            toggleSelect(selects[i], false);
        }
        
        // إخفاء منطقة السعر والشحن
        if (priceDisplay) {
            priceDisplay.style.display = 'none';
        }
        if (regionContainer) {
            regionContainer.style.display = 'none';
        }
        regionSelect.required = false;
        priceInput.value = '';
    }

    // تحميل البيانات من API
    async function loadData(url, params = {}) {
        try {
            const queryString = new URLSearchParams(params).toString();
            const fullUrl = queryString ? `${url}?${queryString}` : url;
            
            const response = await fetch(fullUrl);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error loading data:', error);
            showError('حدث خطأ في تحميل البيانات');
            return [];
        }
    }

    // ملء dropdown بالبيانات
    function populateSelect(selectElement, data, valueField = 'id', textField = 'name') {
        selectElement.classList.remove('select-loading');
        selectElement.innerHTML = '<option value="">اختر...</option>';
        
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueField];
            option.textContent = item[textField];
            
            // إضافة معلومات إضافية للباقات
            if (item.requires_shipping !== undefined) {
                option.dataset.requiresShipping = item.requires_shipping;
            }
            
            selectElement.appendChild(option);
        });
        
        toggleSelect(selectElement, data.length > 0);
    }

    // عرض رسالة خطأ
    function showError(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        const container = document.querySelector('.card-body');
        container.insertBefore(alertDiv, container.firstChild);
        
        // إزالة الرسالة بعد 5 ثوان
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // تحديث السعر
    async function updatePrice() {
        if (!generationSelect.value || !subjectSelect.value || !teacherSelect.value || 
            !platformSelect.value || !packageSelect.value) {
            return;
        }

        const params = {
            generation_id: generationSelect.value,
            subject_id: subjectSelect.value,
            teacher_id: teacherSelect.value,
            platform_id: platformSelect.value,
            package_id: packageSelect.value,
            region_id: regionSelect.value || null
        };

        try {
            const response = await fetch(apiUrls.calculatePrice, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(params)
            });

            if (response.ok) {
                const data = await response.json();
                if (data.price !== undefined) {
                    priceInput.value = data.price;
                    if (priceDisplay) {
                        let message = `السعر المقترح: ${data.price} دينار`;
                        if (data.shipping_cost > 0) {
                            message += ` + ${data.shipping_cost} دينار شحن = ${data.total_cost} دينار إجمالي`;
                        }
                        if (data.exists) {
                            message += ' <br><small class="text-warning">⚠️ يوجد تسعير مماثل</small>';
                        }
                        priceDisplay.innerHTML = message;
                        priceDisplay.style.display = 'block';
                    }
                }
            }
        } catch (error) {
            console.error('Error calculating price:', error);
        }
    }

    // معالجات الأحداث

    // 1. تغيير الجيل
    generationSelect.addEventListener('change', async function() {
        resetDependentSelects(0);
        
        if (this.value) {
            addLoadingState(subjectSelect);
            const subjects = await loadData(apiUrls.subjects, { generation_id: this.value });
            populateSelect(subjectSelect, subjects);
        }
    });

    // 2. تغيير المادة
    subjectSelect.addEventListener('change', async function() {
        resetDependentSelects(1);
        
        if (this.value) {
            addLoadingState(teacherSelect);
            const teachers = await loadData(apiUrls.teachers, { subject_id: this.value });
            populateSelect(teacherSelect, teachers);
        }
    });

    // 3. تغيير المعلم
    teacherSelect.addEventListener('change', async function() {
        resetDependentSelects(2);
        
        if (this.value) {
            addLoadingState(platformSelect);
            const platforms = await loadData(apiUrls.platforms, { teacher_id: this.value });
            populateSelect(platformSelect, platforms);
        }
    });

    // 4. تغيير المنصة
    platformSelect.addEventListener('change', async function() {
        resetDependentSelects(3);
        
        if (this.value) {
            addLoadingState(packageSelect);
            const packages = await loadData(apiUrls.packages, { platform_id: this.value });
            populateSelect(packageSelect, packages);
        }
    });

    // 5. تغيير الباقة
    packageSelect.addEventListener('change', async function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const requiresShipping = selectedOption.dataset.requiresShipping === 'true';
            
            if (requiresShipping) {
                regionContainer.style.display = 'block';
                regionSelect.required = true;
            } else {
                regionContainer.style.display = 'none';
                regionSelect.required = false;
                regionSelect.value = '';
            }
            
            updatePrice();
        } else {
            regionContainer.style.display = 'none';
            regionSelect.required = false;
        }
    });

    // 6. تغيير المنطقة
    regionSelect.addEventListener('change', updatePrice);

    // التحقق من صحة النموذج قبل الإرسال
    const form = document.getElementById('pricing-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = [
                {element: generationSelect, name: 'الجيل الدراسي'},
                {element: subjectSelect, name: 'المادة'},
                {element: teacherSelect, name: 'المعلم'},
                {element: platformSelect, name: 'المنصة'},
                {element: packageSelect, name: 'الباقة'}
            ];
            
            for (let field of requiredFields) {
                if (!field.element.value) {
                    e.preventDefault();
                    showError(`يرجى اختيار ${field.name}`);
                    field.element.focus();
                    return false;
                }
            }

            // التحقق من منطقة الشحن للباقات الورقية
            const selectedPackageOption = packageSelect.options[packageSelect.selectedIndex];
            if (selectedPackageOption && selectedPackageOption.dataset.requiresShipping === 'true') {
                if (!regionSelect.value) {
                    e.preventDefault();
                    showError('يرجى اختيار منطقة الشحن للباقات الورقية');
                    regionSelect.focus();
                    return false;
                }
            }

            // التحقق من السعر
            if (!priceInput.value || priceInput.value <= 0) {
                e.preventDefault();
                showError('يرجى إدخال سعر صحيح');
                priceInput.focus();
                return false;
            }
        });
    }

    // إعداد أولي للنموذج
    resetDependentSelects(0);
    
    console.log('Educational Pricing Form JavaScript loaded successfully');
});
</script>
@endpush