@extends('layouts.admin')

@section('title', 'إضافة معلم جديد')
@section('page-title', 'إضافة معلم جديد')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">
    <a href="{{ route('admin.educational.teachers.index') }}" class="breadcrumb-link">المعلمين</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">إضافة معلم جديد</div>
@endsection

@push('styles')
<style>
.form-wizard {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}

.wizard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    text-align: center;
}

.form-section {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
    color: #495057;
}

.form-label i {
    margin-left: 8px;
    width: 20px;
}

.form-input, .form-textarea, .form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.form-input:focus, .form-textarea:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.image-upload-area {
    border: 3px dashed #dee2e6;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    background: #f8f9fa;
}

.image-upload-area:hover {
    border-color: #007bff;
    background: #e3f2fd;
}

.image-upload-area.dragover {
    border-color: #28a745;
    background: #d4edda;
}

.image-preview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 10px;
    margin: 1rem auto;
    display: none;
    border: 3px solid #e9ecef;
}

.upload-icon {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.form-checkbox {
    width: 18px;
    height: 18px;
    accent-color: #007bff;
    margin-left: 10px;
}

.info-box {
    background: linear-gradient(135deg, #e3f2fd, #f8f9fa);
    border: 1px solid #bbdefb;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.text-danger {
    color: #dc3545 !important;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.btn {
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.loading-content {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    text-align: center;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #007bff;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="form-wizard">
                <div class="wizard-header">
                    <i class="fas fa-user-plus fa-3x mb-3"></i>
                    <h2 class="mb-2">إضافة معلم جديد</h2>
                    <p class="mb-0 opacity-75">أدخل بيانات المعلم بعناية للحصول على أفضل النتائج</p>
                </div>

                <form action="{{ route('admin.educational.teachers.store') }}" method="POST" enctype="multipart/form-data" id="teacherForm">
                    @csrf
                    
                    <div class="form-section">
                        <!-- معلومات أساسية -->
                        <div class="row">
                            <div class="col-12">
                                <h4 class="mb-4 text-primary">
                                    <i class="fas fa-info-circle"></i>
                                    المعلومات الأساسية
                                </h4>
                            </div>

                            <!-- اختيار المادة -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id" class="form-label">
                                        <i class="fas fa-book text-primary"></i>
                                        المادة الدراسية *
                                    </label>
                                    <select name="subject_id" id="subject_id" class="form-select" required>
                                        <option value="">اختر المادة الدراسية</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }} - {{ $subject->generation->display_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- اسم المعلم -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user text-success"></i>
                                        اسم المعلم *
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-input" 
                                           value="{{ old('name') }}" 
                                           placeholder="أدخل اسم المعلم الكامل"
                                           required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- التخصص -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="specialization" class="form-label">
                                        <i class="fas fa-certificate text-warning"></i>
                                        التخصص (اختياري)
                                    </label>
                                    <input type="text" 
                                           name="specialization" 
                                           id="specialization" 
                                           class="form-input" 
                                           value="{{ old('specialization') }}" 
                                           placeholder="مثال: بكالوريوس رياضيات">
                                    @error('specialization')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- حالة التفعيل -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-toggle-on text-info"></i>
                                        حالة المعلم
                                    </label>
                                    <div class="mt-2">
                                        <label class="d-flex align-items-center">
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" 
                                                   name="is_active" 
                                                   value="1" 
                                                   {{ old('is_active', 1) ? 'checked' : '' }}
                                                   class="form-checkbox">
                                            <span>المعلم نشط ومتاح للطلاب</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- السيرة الذاتية -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4 class="mb-4 text-primary">
                                    <i class="fas fa-file-alt"></i>
                                    السيرة الذاتية
                                </h4>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="bio" class="form-label">
                                        <i class="fas fa-align-left text-secondary"></i>
                                        السيرة الذاتية (اختياري)
                                    </label>
                                    <textarea name="bio" 
                                              id="bio" 
                                              class="form-textarea" 
                                              rows="4" 
                                              placeholder="أدخل نبذة عن المعلم وخبراته التعليمية...">{{ old('bio') }}</textarea>
                                    @error('bio')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">الحد الأقصى 1000 حرف</small>
                                </div>
                            </div>
                        </div>

                        <!-- صورة المعلم -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4 class="mb-4 text-primary">
                                    <i class="fas fa-camera"></i>
                                    صورة المعلم
                                </h4>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image" class="form-label">
                                        <i class="fas fa-image text-primary"></i>
                                        صورة شخصية (اختياري)
                                    </label>
                                    
                                    <div class="image-upload-area" id="imageUploadArea">
                                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                        <h5>اسحب الصورة هنا أو انقر للاختيار</h5>
                                        <p class="text-muted mb-0">PNG, JPG, GIF حتى 2MB</p>
                                        <input type="file" 
                                               name="image" 
                                               id="image" 
                                               accept="image/*" 
                                               style="display: none;">
                                    </div>
                                    
                                    <img id="imagePreview" class="image-preview" alt="معاينة الصورة">
                                    
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- معلومات مساعدة -->
                        <div class="info-box mt-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-lightbulb"></i>
                                نصائح مهمة
                            </h5>
                            <ul class="mb-0">
                                <li>تأكد من اختيار المادة الصحيحة قبل إنشاء المعلم</li>
                                <li>اسم المعلم سيظهر للطلاب في جميع أنحاء النظام</li>
                                <li>السيرة الذاتية تساعد الطلاب في التعرف على خبرات المعلم</li>
                                <li>الصورة الشخصية تعزز ثقة الطلاب بالمعلم</li>
                                <li>يمكن تعديل جميع البيانات لاحقاً من صفحة التعديل</li>
                            </ul>
                        </div>
                    </div>

                    <!-- أزرار التحكم -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.educational.teachers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right"></i>
                                العودة للقائمة
                            </a>
                            
                            <div class="d-flex gap-2">
                                <button type="reset" class="btn btn-warning">
                                    <i class="fas fa-undo"></i>
                                    إعادة تعيين
                                </button>
                                
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i>
                                    حفظ المعلم
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner"></div>
        <h5>جاري حفظ بيانات المعلم...</h5>
        <p class="text-muted">يرجى الانتظار</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('teacherForm');
    const imageUploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const loadingOverlay = document.getElementById('loadingOverlay');

    // صورة المعلم - Drag & Drop
    imageUploadArea.addEventListener('click', () => imageInput.click());
    
    imageUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageUploadArea.classList.add('dragover');
    });
    
    imageUploadArea.addEventListener('dragleave', () => {
        imageUploadArea.classList.remove('dragover');
    });
    
    imageUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        imageUploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            previewImage(files[0]);
        }
    });
    
    imageInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            previewImage(e.target.files[0]);
        }
    });
    
    function previewImage(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
            
            // تحديث منطقة الرفع
            imageUploadArea.innerHTML = `
                <i class="fas fa-check-circle upload-icon text-success"></i>
                <h5 class="text-success">تم اختيار الصورة بنجاح</h5>
                <p class="text-muted mb-0">${file.name}</p>
            `;
        };
        reader.readAsDataURL(file);
    }
    
    // التحقق من صحة النموذج
    form.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const subjectId = document.getElementById('subject_id').value;
        
        if (!name || !subjectId) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
            return;
        }
        
        // عرض Loading
        loadingOverlay.style.display = 'flex';
        
        // تعطيل الزر
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    });
    
    // إعادة تعيين النموذج
    const resetBtn = form.querySelector('button[type="reset"]');
    resetBtn.addEventListener('click', function() {
        setTimeout(() => {
            imagePreview.style.display = 'none';
            imageUploadArea.innerHTML = `
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <h5>اسحب الصورة هنا أو انقر للاختيار</h5>
                <p class="text-muted mb-0">PNG, JPG, GIF حتى 2MB</p>
            `;
        }, 100);
    });
    
    // تحسين تجربة المستخدم
    const inputs = form.querySelectorAll('.form-input, .form-select, .form-textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    // عداد الأحرف للسيرة الذاتية
    const bioTextarea = document.getElementById('bio');
    const charCounter = document.createElement('small');
    charCounter.className = 'text-muted';
    charCounter.style.float = 'left';
    bioTextarea.parentElement.appendChild(charCounter);
    
    function updateCharCounter() {
        const currentLength = bioTextarea.value.length;
        const maxLength = 1000;
        charCounter.textContent = `${currentLength}/${maxLength} حرف`;
        
        if (currentLength > maxLength * 0.9) {
            charCounter.className = 'text-warning';
        } else if (currentLength >= maxLength) {
            charCounter.className = 'text-danger';
        } else {
            charCounter.className = 'text-muted';
        }
    }
    
    bioTextarea.addEventListener('input', updateCharCounter);
    updateCharCounter();
});
</script>
@endpush