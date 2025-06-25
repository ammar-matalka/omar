@extends('layouts.admin')

@section('title', 'تعديل الباقة التعليمية')
@section('page-title', 'تعديل الباقة التعليمية')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
        <i class="fas fa-chevron-left"></i>
    </div>
    <div class="breadcrumb-item">
        <a href="{{ route('admin.educational.packages.index') }}" class="breadcrumb-link">الباقات التعليمية</a>
        <i class="fas fa-chevron-left"></i>
    </div>
    <div class="breadcrumb-item">تعديل الباقة</div>
@endsection

@push('styles')
<style>
    .form-wizard {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
    }
    
    .wizard-header {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        color: white;
        padding: var(--space-2xl);
        text-align: center;
    }
    
    .wizard-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: var(--space-md);
    }
    
    .wizard-subtitle {
        opacity: 0.9;
        font-size: 0.95rem;
    }
    
    .form-section {
        padding: var(--space-2xl);
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xl);
        padding-bottom: var(--space-md);
        border-bottom: 2px solid var(--admin-primary-100);
    }
    
    .section-icon {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .form-group {
        position: relative;
    }
    
    .form-label {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
        font-weight: 600;
        color: var(--admin-secondary-700);
        font-size: 0.95rem;
    }
    
    .label-icon {
        color: var(--admin-primary-500);
        font-size: 0.9rem;
    }
    
    .required-mark {
        color: var(--error-500);
        margin-right: var(--space-xs);
    }
    
    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: var(--space-md) var(--space-lg);
        border: 2px solid var(--admin-secondary-300);
        border-radius: var(--radius-lg);
        background: white;
        color: var(--admin-secondary-900);
        font-size: 0.9rem;
        transition: all var(--transition-fast);
        font-family: var(--font-family-sans);
        resize: vertical;
    }
    
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }
    
    .form-help {
        font-size: 0.85rem;
        color: var(--admin-secondary-500);
        margin-top: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .help-icon {
        color: var(--info-500);
    }
    
    .package-type-info {
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
        border: 2px solid var(--admin-primary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
    }
    
    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-md);
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: var(--space-md);
    }
    
    .digital-badge {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
    }
    
    .physical-badge {
        background: linear-gradient(135deg, #fffbeb, #fde68a);
        color: #92400e;
    }
    
    .conditional-fields {
        border: 2px dashed var(--admin-secondary-300);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        margin-top: var(--space-lg);
        background: var(--admin-secondary-50);
    }
    
    .conditional-title {
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .actions-section {
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        padding: var(--space-2xl);
        border-top: 1px solid var(--admin-secondary-200);
    }
    
    .action-buttons {
        display: flex;
        gap: var(--space-lg);
        justify-content: flex-end;
        align-items: center;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-xl);
        border: 2px solid transparent;
        border-radius: var(--radius-lg);
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
        white-space: nowrap;
        font-family: var(--font-family-sans);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        color: white;
        box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.39);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--admin-primary-700), var(--admin-primary-800));
        box-shadow: 0 8px 25px 0 rgba(59, 130, 246, 0.6);
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background: white;
        color: var(--admin-secondary-700);
        border-color: var(--admin-secondary-300);
        box-shadow: var(--shadow-sm);
    }
    
    .btn-secondary:hover {
        background: var(--admin-secondary-50);
        border-color: var(--admin-secondary-400);
        transform: translateY(-1px);
    }
    
    .btn-lg {
        padding: var(--space-lg) var(--space-2xl);
        font-size: 1rem;
    }
    
    .error-message {
        color: var(--error-500);
        font-size: 0.85rem;
        margin-top: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .success-message {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border: 2px solid #22c55e;
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-weight: 500;
        box-shadow: var(--shadow-md);
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="form-wizard fade-in">
    <div class="wizard-header">
        <h1 class="wizard-title">
            <i class="fas fa-edit"></i>
            تعديل الباقة التعليمية
        </h1>
        <p class="wizard-subtitle">قم بتعديل بيانات الباقة التعليمية وإعداداتها</p>
    </div>

    <form action="{{ route('admin.educational.packages.update', $package->id) }}" method="POST" id="packageForm">
        @csrf
        @method('PATCH')
        
        <!-- Basic Information Section -->
        <div class="form-section">
            <h2 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                المعلومات الأساسية
            </h2>

            <!-- Package Type Information -->
            <div class="package-type-info">
                <div class="type-badge {{ $package->is_digital ? 'digital-badge' : 'physical-badge' }}">
                    <i class="fas {{ $package->is_digital ? 'fa-cloud' : 'fa-book' }}"></i>
                    {{ $package->package_type }}
                </div>
                <p class="form-help">
                    <i class="fas fa-info-circle help-icon"></i>
                    نوع المنتج: {{ $package->productType->name }} - {{ $package->productType->display_type }}
                </p>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="product_type_id" class="form-label">
                        <i class="fas fa-layer-group label-icon"></i>
                        نوع المنتج
                        <span class="required-mark">*</span>
                    </label>
                    <select name="product_type_id" id="product_type_id" class="form-select" required>
                        <option value="">اختر نوع المنتج</option>
                        @foreach($productTypes as $type)
                            <option value="{{ $type->id }}" 
                                    {{ old('product_type_id', $package->product_type_id) == $type->id ? 'selected' : '' }}
                                    data-is-digital="{{ $type->is_digital ? 'true' : 'false' }}">
                                {{ $type->name }} - {{ $type->display_type }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_type_id')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="platform_id" class="form-label">
                        <i class="fas fa-chalkboard-teacher label-icon"></i>
                        المنصة التعليمية
                        <span class="required-mark">*</span>
                    </label>
                    <select name="platform_id" id="platform_id" class="form-select" required>
                        <option value="">اختر المنصة</option>
                        @foreach($platforms as $platform)
                            <option value="{{ $platform->id }}" 
                                    {{ old('platform_id', $package->platform_id) == $platform->id ? 'selected' : '' }}>
                                {{ $platform->teaching_chain }}
                            </option>
                        @endforeach
                    </select>
                    @error('platform_id')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag label-icon"></i>
                        اسم الباقة
                        <span class="required-mark">*</span>
                    </label>
                    <input type="text" name="name" id="name" class="form-input" 
                           value="{{ old('name', $package->name) }}" required maxlength="255">
                    <div class="form-help">
                        <i class="fas fa-lightbulb help-icon"></i>
                        اختر اسماً وصفياً ومفهوماً للباقة
                    </div>
                    @error('name')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="is_active" class="form-label">
                        <i class="fas fa-toggle-on label-icon"></i>
                        حالة الباقة
                    </label>
                    <select name="is_active" id="is_active" class="form-select">
                        <option value="1" {{ old('is_active', $package->is_active) == '1' ? 'selected' : '' }}>
                            نشطة
                        </option>
                        <option value="0" {{ old('is_active', $package->is_active) == '0' ? 'selected' : '' }}>
                            غير نشطة
                        </option>
                    </select>
                    @error('is_active')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left label-icon"></i>
                    وصف الباقة
                </label>
                <textarea name="description" id="description" class="form-textarea" 
                          rows="4" maxlength="1000">{{ old('description', $package->description) }}</textarea>
                <div class="form-help">
                    <i class="fas fa-info-circle help-icon"></i>
                    وصف مفصل للباقة ومحتوياتها (اختياري)
                </div>
                @error('description')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Digital Package Fields -->
        <div class="form-section" id="digitalFields" style="display: {{ $package->is_digital ? 'block' : 'none' }};">
            <h2 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-cloud"></i>
                </div>
                إعدادات البطاقة الرقمية
            </h2>

            <div class="conditional-fields">
                <div class="conditional-title">
                    <i class="fas fa-digital-tachograph"></i>
                    خصائص البطاقة الرقمية
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="duration_days" class="form-label">
                            <i class="fas fa-calendar-alt label-icon"></i>
                            مدة الصلاحية (بالأيام)
                        </label>
                        <input type="number" name="duration_days" id="duration_days" class="form-input" 
                               value="{{ old('duration_days', $package->duration_days) }}" 
                               min="1" max="3650" placeholder="مثال: 365">
                        <div class="form-help">
                            <i class="fas fa-info-circle help-icon"></i>
                            اتركه فارغاً للحصول على صلاحية غير محدودة
                        </div>
                        @error('duration_days')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="lessons_count" class="form-label">
                            <i class="fas fa-play-circle label-icon"></i>
                            عدد الدروس
                        </label>
                        <input type="number" name="lessons_count" id="lessons_count" class="form-input" 
                               value="{{ old('lessons_count', $package->lessons_count) }}" 
                               min="1" max="1000" placeholder="مثال: 50">
                        <div class="form-help">
                            <i class="fas fa-info-circle help-icon"></i>
                            اتركه فارغاً للحصول على دروس غير محدودة
                        </div>
                        @error('lessons_count')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Physical Package Fields -->
        <div class="form-section" id="physicalFields" style="display: {{ $package->is_digital ? 'none' : 'block' }};">
            <h2 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-book"></i>
                </div>
                إعدادات الدوسية الورقية
            </h2>

            <div class="conditional-fields">
                <div class="conditional-title">
                    <i class="fas fa-book-open"></i>
                    خصائص الدوسية الورقية
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="pages_count" class="form-label">
                            <i class="fas fa-file-alt label-icon"></i>
                            عدد الصفحات
                        </label>
                        <input type="number" name="pages_count" id="pages_count" class="form-input" 
                               value="{{ old('pages_count', $package->pages_count) }}" 
                               min="1" max="1000" placeholder="مثال: 120">
                        <div class="form-help">
                            <i class="fas fa-info-circle help-icon"></i>
                            إجمالي عدد صفحات الدوسية
                        </div>
                        @error('pages_count')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="weight_grams" class="form-label">
                            <i class="fas fa-weight label-icon"></i>
                            الوزن (بالجرام)
                        </label>
                        <input type="number" name="weight_grams" id="weight_grams" class="form-input" 
                               value="{{ old('weight_grams', $package->weight_grams) }}" 
                               min="1" max="10000" placeholder="مثال: 250">
                        <div class="form-help">
                            <i class="fas fa-info-circle help-icon"></i>
                            وزن الدوسية لحساب تكلفة الشحن
                        </div>
                        @error('weight_grams')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Section -->
        <div class="actions-section">
            <div class="action-buttons">
                <a href="{{ route('admin.educational.packages.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i>
                    إلغاء
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i>
                    حفظ التعديلات
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productTypeSelect = document.getElementById('product_type_id');
    const digitalFields = document.getElementById('digitalFields');
    const physicalFields = document.getElementById('physicalFields');
    
    // Handle product type change
    function toggleFields() {
        const selectedOption = productTypeSelect.options[productTypeSelect.selectedIndex];
        const isDigital = selectedOption.getAttribute('data-is-digital') === 'true';
        
        if (isDigital) {
            digitalFields.style.display = 'block';
            physicalFields.style.display = 'none';
            
            // Clear physical fields
            document.getElementById('pages_count').value = '';
            document.getElementById('weight_grams').value = '';
        } else {
            digitalFields.style.display = 'none';
            physicalFields.style.display = 'block';
            
            // Clear digital fields
            document.getElementById('duration_days').value = '';
            document.getElementById('lessons_count').value = '';
        }
    }
    
    productTypeSelect.addEventListener('change', toggleFields);
    
    // Form validation
    document.getElementById('packageForm').addEventListener('submit', function(e) {
        const productType = document.getElementById('product_type_id').value;
        const platform = document.getElementById('platform_id').value;
        const name = document.getElementById('name').value.trim();
        
        if (!productType || !platform || !name) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
            return false;
        }
        
        // Show loading state
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        submitBtn.disabled = true;
        
        // Re-enable button after 5 seconds (in case of slow response)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
    
    // Enhanced form interactions
    const inputs = document.querySelectorAll('.form-input, .form-select, .form-textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    // Initialize fade-in animation
    const elements = document.querySelectorAll('.fade-in');
    elements.forEach(el => {
        el.classList.add('visible');
    });
});
</script>
@endpush