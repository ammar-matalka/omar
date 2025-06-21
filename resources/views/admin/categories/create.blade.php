@extends('layouts.admin')

@section('title', 'إنشاء فئة جديدة')
@section('page-title', 'إنشاء فئة جديدة')

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
        إنشاء
    </div>
@endsection

@push('styles')
<style>
    * {
        direction: rtl;
        text-align: right;
    }
    
    .create-category-container {
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
        background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-400), var(--admin-primary-600));
    }
    
    .form-header {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-500));
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
        background: linear-gradient(135deg, var(--admin-primary-100), var(--admin-primary-200));
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
        border-bottom: 3px solid var(--admin-primary-200);
        padding-bottom: var(--space-md);
        font-family: 'Cairo', sans-serif;
    }
    
    .section-title i {
        color: var(--admin-primary-600);
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
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1), 0 4px 12px rgba(0, 0, 0, 0.1);
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
    
    .slug-preview {
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-md);
        margin-top: var(--space-sm);
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
        direction: ltr;
        text-align: left;
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
        border-color: var(--admin-primary-500);
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
        transform: scale(1.02);
    }
    
    .image-upload.dragover {
        border-color: var(--admin-primary-500);
        background: linear-gradient(135deg, var(--admin-primary-100), var(--admin-primary-200));
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
    }
    
    .upload-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--space-md);
    }
    
    .upload-icon {
        font-size: 4rem;
        color: var(--admin-primary-500);
        margin-bottom: var(--space-md);
        transition: all var(--transition-fast);
    }
    
    .image-upload:hover .upload-icon {
        transform: scale(1.1);
        color: var(--admin-primary-600);
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
        min-width: 120px;
        justify-content: center;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-500));
        color: white;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--admin-primary-700), var(--admin-primary-600));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
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
    
    /* تأثيرات تفاعلية إضافية */
    .form-group {
        transition: all var(--transition-fast);
    }
    
    .form-group:hover {
        transform: translateY(-1px);
    }
    
    .floating-label {
        position: absolute;
        top: var(--space-lg);
        right: var(--space-lg);
        background: white;
        padding: 0 var(--space-xs);
        color: var(--admin-secondary-500);
        transition: all var(--transition-fast);
        pointer-events: none;
        font-family: 'Cairo', sans-serif;
    }
    
    .form-input:focus + .floating-label,
    .form-input:not(:placeholder-shown) + .floating-label {
        top: -8px;
        font-size: 0.75rem;
        color: var(--admin-primary-600);
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .create-category-container {
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
    }
</style>
@endpush

@section('content')
<div class="create-category-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-plus-circle"></i>
                إنشاء فئة جديدة
            </h1>
            <p class="form-subtitle">أضف فئة جديدة لتنظيم منتجاتك بشكل أفضل</p>
        </div>
        
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
            @csrf
            
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
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-input @error('name') error @enderror"
                                value="{{ old('name') }}"
                                placeholder="أدخل اسم الفئة"
                                required
                                oninput="updateSlugPreview()"
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
                            </label>
                            <input 
                                type="text" 
                                id="slug" 
                                name="slug" 
                                class="form-input @error('slug') error @enderror"
                                value="{{ old('slug') }}"
                                placeholder="رابط-تلقائي"
                                readonly
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
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                class="form-textarea @error('description') error @enderror"
                                placeholder="أدخل وصف الفئة (اختياري)"
                                rows="4"
                            >{{ old('description') }}</textarea>
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
                    
                    <div class="form-row single">
                        <div class="form-group">
                            <label for="image" class="form-label">
                                رفع الصورة
                            </label>
                            <div class="image-upload" onclick="document.getElementById('image').click()">
                                <input 
                                    type="file" 
                                    id="image" 
                                    name="image" 
                                    accept="image/*"
                                    style="display: none;"
                                    onchange="previewImage(this)"
                                >
                                <div class="upload-content">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">انقر للرفع أو اسحب وأفلت</div>
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
                            <div class="form-help">ارفع صورة لتمثيل هذه الفئة. الحجم المُوصى به: 400×300 بكسل</div>
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
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        إعادة تعيين
                    </button>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        إنشاء الفئة
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // إنشاء الرابط من الاسم
    function updateSlugPreview() {
        const name = document.getElementById('name').value;
        const slug = name
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        
        document.getElementById('slug').value = slug;
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
    }
    
    // إعادة تعيين النموذج
    function resetForm() {
        if (confirm('هل أنت متأكد من رغبتك في إعادة تعيين النموذج؟ سيتم فقدان جميع البيانات المدخلة.')) {
            document.getElementById('categoryForm').reset();
            removeImage(new Event('click'));
            updateSlugPreview();
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
        }
    });
    
    // التحقق من صحة النموذج
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        
        if (!name) {
            e.preventDefault();
            alert('يرجى إدخال اسم الفئة');
            document.getElementById('name').focus();
            return;
        }
        
        // إظهار حالة التحميل
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإنشاء...';
        submitBtn.disabled = true;
        
        // إعادة تمكين الزر بعد 5 ثواني في حالة وجود خطأ
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
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