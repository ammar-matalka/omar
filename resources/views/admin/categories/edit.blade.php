@extends('layouts.admin')

@section('title', 'تعديل الفئة')
@section('page-title', 'تعديل الفئة')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        <a href="{{ route('admin.categories.index') }}" class="breadcrumb-link">الفئات</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        تعديل - {{ $category->name }}
    </div>
@endsection

@push('styles')
<style>
    * {
        direction: rtl;
        text-align: right;
    }
    
    .edit-category-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .form-card {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        border: 2px solid var(--admin-secondary-100);
        position: relative;
    }
    
    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--warning-500), var(--warning-400), var(--warning-600));
    }
    
    .form-header {
        background: linear-gradient(135deg, var(--warning-600), var(--warning-500));
        color: white;
        padding: var(--space-2xl);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .form-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
        50% { transform: translate(-50%, -50%) rotate(180deg); }
    }
    
    .form-title {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-md);
        font-family: 'Cairo', sans-serif;
        position: relative;
        z-index: 1;
    }
    
    .form-subtitle {
        margin: var(--space-md) 0 0 0;
        opacity: 0.9;
        font-size: 1rem;
        font-family: 'Cairo', sans-serif;
        position: relative;
        z-index: 1;
    }
    
    .form-body {
        padding: var(--space-2xl);
    }
    
    .form-section {
        margin-bottom: var(--space-2xl);
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        border: 2px solid var(--admin-secondary-100);
        position: relative;
    }
    
    .form-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--warning-100), var(--warning-200));
        border-radius: 0 var(--radius-xl) 0 var(--radius-2xl);
        opacity: 0.7;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-md);
        border-bottom: 3px solid var(--warning-200);
        padding-bottom: var(--space-md);
        font-family: 'Cairo', sans-serif;
    }
    
    .section-title i {
        color: var(--warning-600);
        font-size: 1.25rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-xl);
        margin-bottom: var(--space-lg);
    }
    
    .form-row.single {
        grid-template-columns: 1fr;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        position: relative;
    }
    
    .form-label {
        font-size: 1rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        font-family: 'Cairo', sans-serif;
    }
    
    .required {
        color: var(--error-500);
        font-size: 1.2rem;
    }
    
    .change-indicator {
        background: linear-gradient(135deg, var(--warning-100), var(--warning-200));
        color: var(--warning-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        margin-right: var(--space-sm);
        display: none;
        font-weight: 600;
        border: 1px solid var(--warning-300);
        box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .form-input,
    .form-textarea {
        padding: var(--space-lg);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        font-size: 1rem;
        font-family: 'Cairo', sans-serif;
        transition: all var(--transition-normal);
        background: white;
        position: relative;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--warning-500);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1), 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 120px;
        line-height: 1.6;
    }
    
    .form-help {
        font-size: 0.875rem;
        color: var(--admin-secondary-500);
        margin-top: var(--space-sm);
        font-family: 'Cairo', sans-serif;
        line-height: 1.5;
    }
    
    .current-image {
        margin-bottom: var(--space-lg);
        text-align: center;
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        border-radius: var(--radius-xl);
        padding: var(--space-lg);
        border: 2px solid var(--admin-secondary-200);
    }
    
    .current-image img {
        max-width: 250px;
        height: 180px;
        object-fit: cover;
        border-radius: var(--radius-xl);
        border: 3px solid var(--admin-secondary-200);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transition: all var(--transition-normal);
    }
    
    .current-image img:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
    }
    
    .current-image-label {
        display: block;
        font-size: 1rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-md);
        font-family: 'Cairo', sans-serif;
    }
    
    .image-upload {
        border: 3px dashed var(--admin-secondary-300);
        border-radius: var(--radius-2xl);
        padding: var(--space-2xl);
        text-align: center;
        transition: all var(--transition-normal);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .image-upload:hover {
        border-color: var(--warning-500);
        background: linear-gradient(135deg, var(--warning-50), var(--warning-100));
        transform: scale(1.02);
    }
    
    .image-upload.dragover {
        border-color: var(--warning-500);
        background: linear-gradient(135deg, var(--warning-100), var(--warning-200));
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
    }
    
    .upload-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--space-md);
    }
    
    .upload-icon {
        font-size: 4rem;
        color: var(--warning-500);
        margin-bottom: var(--space-md);
        transition: all var(--transition-fast);
    }
    
    .image-upload:hover .upload-icon {
        transform: scale(1.1);
        color: var(--warning-600);
    }
    
    .upload-text {
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-sm);
        font-size: 1.125rem;
        font-weight: 600;
        font-family: 'Cairo', sans-serif;
    }
    
    .upload-hint {
        font-size: 0.875rem;
        color: var(--admin-secondary-500);
        font-family: 'Cairo', sans-serif;
    }
    
    .image-preview {
        position: relative;
        max-width: 250px;
        margin: var(--space-md) auto 0;
    }
    
    .preview-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: var(--radius-xl);
        border: 3px solid var(--admin-secondary-200);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    
    .remove-image {
        position: absolute;
        top: -10px;
        left: -10px;
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--error-600), var(--error-500));
        color: white;
        border: 3px solid white;
        border-radius: 50%;
        cursor: pointer;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition-fast);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .remove-image:hover {
        background: linear-gradient(135deg, var(--error-700), var(--error-600));
        transform: scale(1.2);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }
    
    .form-actions {
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        padding: var(--space-2xl);
        border-top: 2px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-md);
    }
    
    .btn-group {
        display: flex;
        gap: var(--space-md);
    }
    
    .btn {
        font-family: 'Cairo', sans-serif;
        font-weight: 600;
        border-radius: var(--radius-xl);
        padding: var(--space-md) var(--space-xl);
        transition: all var(--transition-normal);
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-size: 1rem;
        min-width: 130px;
        justify-content: center;
    }
    
    .btn-success {
        background: linear-gradient(135deg, var(--success-600), var(--success-500));
        color: white;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, var(--success-700), var(--success-600));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, var(--admin-secondary-200), var(--admin-secondary-100));
        color: var(--admin-secondary-700);
        border: 2px solid var(--admin-secondary-300);
    }
    
    .btn-secondary:hover {
        background: linear-gradient(135deg, var(--admin-secondary-300), var(--admin-secondary-200));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    .error-message {
        color: var(--error-500);
        font-size: 0.875rem;
        margin-top: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        font-family: 'Cairo', sans-serif;
        background: var(--error-50);
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-lg);
        border: 1px solid var(--error-200);
    }
    
    .form-input.error,
    .form-textarea.error {
        border-color: var(--error-500);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        background: var(--error-50);
    }
    
    /* تأثيرات إضافية للتغييرات */
    .form-input[data-changed="true"],
    .form-textarea[data-changed="true"] {
        border-color: var(--warning-400);
        background: linear-gradient(135deg, var(--warning-50), #ffffff);
    }
    
    .unsaved-changes-warning {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: linear-gradient(135deg, var(--warning-500), var(--warning-400));
        color: white;
        padding: var(--space-md) var(--space-lg);
        border-radius: var(--radius-xl);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        z-index: 1000;
        display: none;
        font-family: 'Cairo', sans-serif;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .edit-category-container {
            margin: 0;
            padding: var(--space-md);
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .form-title {
            font-size: 1.5rem;
        }
        
        .section-title {
            font-size: 1.25rem;
        }
        
        .upload-icon {
            font-size: 3rem;
        }
        
        .current-image img {
            max-width: 200px;
            height: 150px;
        }
        
        .unsaved-changes-warning {
            right: 10px;
            left: 10px;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="edit-category-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-edit"></i>
                تعديل الفئة
            </h1>
            <p class="form-subtitle">قم بتحديث معلومات وإعدادات الفئة</p>
        </div>
        
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" id="categoryForm">
            @csrf
            @method('PUT')
            
            <div class="form-body">
                <!-- المعلومات الأساسية -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        المعلومات الأساسية
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                اسم الفئة
                                <span class="required">*</span>
                                <span class="change-indicator" id="nameChangeIndicator">تم التعديل</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-input @error('name') error @enderror"
                                value="{{ old('name', $category->name) }}"
                                placeholder="أدخل اسم الفئة"
                                required
                                oninput="updateSlugPreview(); trackChanges('name')"
                                data-original="{{ $category->name }}"
                            >
                            @error('name')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">اختر اسماً واضحاً ووصفياً لفئتك</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug" class="form-label">
                                رابط الصفحة (URL)
                                <span class="change-indicator" id="slugChangeIndicator">تم التعديل</span>
                            </label>
                            <input 
                                type="text" 
                                id="slug" 
                                name="slug" 
                                class="form-input @error('slug') error @enderror"
                                value="{{ old('slug', $category->slug) }}"
                                placeholder="رابط-تلقائي"
                                readonly
                                data-original="{{ $category->slug }}"
                            >
                            @error('slug')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">نسخة مناسبة للرابط من الاسم (يتم إنشاؤها تلقائياً)</div>
                        </div>
                    </div>
                    
                    <div class="form-row single">
                        <div class="form-group">
                            <label for="description" class="form-label">
                                الوصف
                                <span class="change-indicator" id="descriptionChangeIndicator">تم التعديل</span>
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                class="form-textarea @error('description') error @enderror"
                                placeholder="أدخل وصف الفئة (اختياري)"
                                rows="4"
                                oninput="trackChanges('description')"
                                data-original="{{ $category->description }}"
                            >{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">قدم وصفاً مختصراً عن المنتجات التي تحتويها هذه الفئة</div>
                        </div>
                    </div>
                </div>
                
                <!-- صورة الفئة -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-image"></i>
                        صورة الفئة
                    </h2>
                    
                    @if($category->image)
                        <div class="current-image">
                            <label class="current-image-label">الصورة الحالية</label>
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}">
                        </div>
                    @endif
                    
                    <div class="form-row single">
                        <div class="form-group">
                            <label for="image" class="form-label">
                                {{ $category->image ? 'استبدال الصورة' : 'رفع الصورة' }}
                                <span class="change-indicator" id="imageChangeIndicator">تم التعديل</span>
                            </label>
                            <div class="image-upload" onclick="document.getElementById('image').click()">
                                <input 
                                    type="file" 
                                    id="image" 
                                    name="image" 
                                    accept="image/*"
                                    style="display: none;"
                                    onchange="previewImage(this); trackChanges('image')"
                                >
                                <div class="upload-content">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">
                                        {{ $category->image ? 'انقر لاستبدال الصورة أو اسحب وأفلت' : 'انقر للرفع أو اسحب وأفلت' }}
                                    </div>
                                    <div class="upload-hint">PNG, JPG, GIF حتى 2 ميجابايت</div>
                                </div>
                                <div id="imagePreview" class="image-preview" style="display: none;">
                                    <img id="previewImg" class="preview-image" src="" alt="معاينة">
                                    <button type="button" class="remove-image" onclick="removeImage(event)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @error('image')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">
                                {{ $category->image ? 'ارفع صورة جديدة لاستبدال الصورة الحالية.' : 'ارفع صورة لتمثيل هذه الفئة.' }}
                                الحجم المُوصى به: 400×300 بكسل
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <div>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للفئات
                    </a>
                </div>
                
                <div class="btn-group">
                    <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i>
                        عرض الفئة
                    </a>
                    
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        إعادة تعيين
                    </button>
                    
                    <button type="submit" class="btn btn-success" id="updateBtn">
                        <i class="fas fa-save"></i>
                        حفظ التغييرات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- تحذير التغييرات غير المحفوظة -->
<div id="unsavedWarning" class="unsaved-changes-warning">
    <i class="fas fa-exclamation-triangle"></i>
    لديك تغييرات غير محفوظة
</div>
@endsection

@push('scripts')
<script>
    let hasChanges = false;
    const originalValues = {};
    
    // تخزين القيم الأصلية عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-original]').forEach(field => {
            originalValues[field.id] = field.dataset.original;
        });
    });
    
    // تتبع التغييرات في حقول النموذج
    function trackChanges(fieldName) {
        const field = document.getElementById(fieldName);
        const indicator = document.getElementById(fieldName + 'ChangeIndicator');
        const currentValue = field.value;
        const originalValue = originalValues[fieldName] || '';
        
        if (currentValue !== originalValue) {
            indicator.style.display = 'inline-block';
            field.setAttribute('data-changed', 'true');
            hasChanges = true;
        } else {
            indicator.style.display = 'none';
            field.removeAttribute('data-changed');
        }
        
        // فحص إذا كان هناك أي حقل يحتوي على تغييرات
        updateFormState();
    }
    
    // تحديث حالة النموذج بناءً على التغييرات
    function updateFormState() {
        const updateBtn = document.getElementById('updateBtn');
        const warningDiv = document.getElementById('unsavedWarning');
        hasChanges = document.querySelector('.change-indicator[style*="inline-block"]') !== null;
        
        if (hasChanges) {
            updateBtn.classList.remove('btn-secondary');
            updateBtn.classList.add('btn-success');
            warningDiv.style.display = 'block';
        } else {
            updateBtn.classList.remove('btn-success');
            updateBtn.classList.add('btn-secondary');
            warningDiv.style.display = 'none';
        }
    }
    
    // إنشاء الرابط من الاسم
    function updateSlugPreview() {
        const name = document.getElementById('name').value;
        const slug = name
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        
        const slugField = document.getElementById('slug');
        slugField.value = slug;
        
        // تتبع تغييرات الرابط
        const indicator = document.getElementById('slugChangeIndicator');
        if (slug !== originalValues['slug']) {
            indicator.style.display = 'inline-block';
            slugField.setAttribute('data-changed', 'true');
            hasChanges = true;
        } else {
            indicator.style.display = 'none';
            slugField.removeAttribute('data-changed');
        }
        
        updateFormState();
    }
    
    // وظيفة معاينة الصورة
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // التحقق من حجم الملف (2 ميجابايت كحد أقصى)
            if (file.size > 2 * 1024 * 1024) {
                alert('يجب أن يكون حجم الملف أقل من 2 ميجابايت');
                input.value = '';
                return;
            }
            
            // التحقق من نوع الملف
            if (!file.type.match('image.*')) {
                alert('يرجى اختيار ملف صورة');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
                document.querySelector('.upload-content').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }
    
    // إزالة معاينة الصورة
    function removeImage(event) {
        event.stopPropagation();
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').style.display = 'none';
        document.querySelector('.upload-content').style.display = 'flex';
        
        // إخفاء مؤشر تغيير الصورة
        document.getElementById('imageChangeIndicator').style.display = 'none';
        updateFormState();
    }
    
    // إعادة تعيين النموذج للقيم الأصلية
    function resetForm() {
        if (confirm('هل أنت متأكد من رغبتك في إعادة تعيين النموذج؟ سيتم فقدان جميع التغييرات.')) {
            // إعادة تعيين الحقول النصية
            document.getElementById('name').value = originalValues['name'] || '';
            document.getElementById('slug').value = originalValues['slug'] || '';
            document.getElementById('description').value = originalValues['description'] || '';
            
            // إعادة تعيين الصورة
            document.getElementById('image').value = '';
            removeImage(new Event('click'));
            
            // إخفاء جميع مؤشرات التغيير
            document.querySelectorAll('.change-indicator').forEach(indicator => {
                indicator.style.display = 'none';
            });
            
            // إزالة سمات التغيير
            document.querySelectorAll('[data-changed]').forEach(field => {
                field.removeAttribute('data-changed');
            });
            
            hasChanges = false;
            updateFormState();
        }
    }
    
    // وظيفة السحب والإفلات
    const imageUpload = document.querySelector('.image-upload');
    
    imageUpload.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    imageUpload.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    imageUpload.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('image').files = files;
            previewImage(document.getElementById('image'));
            trackChanges('image');
        }
    });
    
    // التحقق من صحة النموذج والإرسال
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        
        if (!name) {
            e.preventDefault();
            alert('يرجى إدخال اسم الفئة');
            document.getElementById('name').focus();
            return;
        }
        
        // إظهار حالة التحميل
        const submitBtn = document.getElementById('updateBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        submitBtn.disabled = true;
        
        // إعادة تمكين الزر بعد 5 ثواني في حالة وجود خطأ
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
    
    // تحذير المستخدم حول التغييرات غير المحفوظة
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = 'لديك تغييرات غير محفوظة. هل أنت متأكد من رغبتك في المغادرة؟';
            return e.returnValue;
        }
    });
    
    // تتبع التغييرات في الحقول تلقائياً
    document.addEventListener('DOMContentLoaded', function() {
        // إضافة مستمعي الأحداث للحقول
        document.getElementById('name').addEventListener('input', () => trackChanges('name'));
        document.getElementById('description').addEventListener('input', () => trackChanges('description'));
        
        // إخفاء تحذير التغييرات غير المحفوظة عند النقر عليه
        document.getElementById('unsavedWarning').addEventListener('click', function() {
            this.style.display = 'none';
        });
    });
    
    // تهيئة الرسوم المتحركة
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    });
</script>
@endpush