@extends('layouts.admin')

@section('title', 'إضافة منصة تعليمية جديدة')
@section('page-title', 'إضافة منصة تعليمية جديدة')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">
    <a href="{{ route('admin.educational.platforms.index') }}" class="breadcrumb-link">المنصات التعليمية</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">إضافة منصة جديدة</div>
@endsection

@push('styles')
<style>
.form-wizard {
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    overflow: hidden;
}

.wizard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2.5rem;
    text-align: center;
    position: relative;
}

.wizard-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -30%;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.wizard-header::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -30%;
    width: 150px;
    height: 150px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
}

.form-section {
    padding: 2.5rem;
}

.form-group {
    margin-bottom: 2rem;
}

.form-label {
    font-weight: 700;
    margin-bottom: 0.75rem;
    display: block;
    color: #495057;
    font-size: 0.95rem;
}

.form-label i {
    margin-left: 10px;
    width: 20px;
    color: inherit;
}

.form-input, .form-textarea, .form-select {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
    font-size: 1rem;
    background: #f8f9fa;
}

.form-input:focus, .form-textarea:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
    outline: none;
    background: white;
    transform: translateY(-2px);
}

.form-input:hover, .form-textarea:hover, .form-select:hover {
    border-color: #dee2e6;
    background: white;
}

.teacher-preview {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 2px solid #dee2e6;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}

.teacher-preview.active {
    border-color: #667eea;
    background: linear-gradient(135deg, #e3f2fd, #f8f9fa);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
}

.teacher-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.teacher-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.5rem;
    flex-shrink: 0;
    border: 3px solid white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.teacher-details h5 {
    margin: 0 0 0.25rem 0;
    color: #495057;
    font-weight: 700;
}

.teacher-details p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.form-checkbox {
    width: 20px;
    height: 20px;
    accent-color: #667eea;
    margin-left: 12px;
    cursor: pointer;
}

.info-box {
    background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
    border: 2px solid #c3e6cb;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.warning-box {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border: 2px solid #ffc107;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.text-danger {
    color: #dc3545 !important;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 500;
}

.btn {
    padding: 0.875rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8, #6c4298);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838, #1fa085);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d, #495057);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268, #3d4043);
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    color: #212529;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800, #e8690b);
}

.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.loading-content {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    text-align: center;
    max-width: 350px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin: 0 auto 1.5rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.url-preview {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-top: 0.5rem;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    color: #495057;
    word-break: break-all;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e9ecef;
}

.section-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.section-title {
    flex: 1;
}

.section-title h4 {
    margin: 0;
    color: #495057;
    font-weight: 700;
}

.section-title p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="form-wizard">
                <div class="wizard-header">
                    <div style="position: relative; z-index: 2;">
                        <i class="fas fa-desktop fa-4x mb-3" style="opacity: 0.9;"></i>
                        <h2 class="mb-2">إضافة منصة تعليمية جديدة</h2>
                        <p class="mb-0 opacity-75">قم بإنشاء منصة تعليمية جديدة لأحد المعلمين</p>
                    </div>
                </div>

                <form action="{{ route('admin.educational.platforms.store') }}" method="POST" id="platformForm">
                    @csrf
                    
                    <div class="form-section">
                        <!-- اختيار المعلم -->
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="section-title">
                                <h4>اختيار المعلم</h4>
                                <p>حدد المعلم الذي ستنتمي إليه هذه المنصة</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="teacher_id" class="form-label">
                                        <i class="fas fa-user-tie text-primary"></i>
                                        المعلم المسؤول *
                                    </label>
                                    <select name="teacher_id" id="teacher_id" class="form-select" required>
                                        <option value="">اختر المعلم</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" 
                                                data-teacher-name="{{ $teacher->name }}"
                                                data-teacher-subject="{{ $teacher->subject->name }}"
                                                data-teacher-generation="{{ $teacher->subject->generation->display_name }}"
                                                data-teacher-specialization="{{ $teacher->specialization }}"
                                                {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }} - {{ $teacher->subject->name }} ({{ $teacher->subject->generation->display_name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- معاينة المعلم المختار -->
                                <div id="teacherPreview" class="teacher-preview" style="display: none;">
                                    <div class="teacher-info">
                                        <div class="teacher-avatar" id="teacherAvatar">
                                            <!-- سيتم ملؤها بـ JavaScript -->
                                        </div>
                                        <div class="teacher-details">
                                            <h5 id="teacherName">اسم المعلم</h5>
                                            <p id="teacherSubject">المادة - الجيل</p>
                                            <p id="teacherSpecialization">التخصص</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات المنصة -->
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="section-title">
                                <h4>معلومات المنصة</h4>
                                <p>أدخل البيانات الأساسية للمنصة التعليمية</p>
                            </div>
                        </div>

                        <div class="row">
                            <!-- اسم المنصة -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-desktop text-success"></i>
                                        اسم المنصة *
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-input" 
                                           value="{{ old('name') }}" 
                                           placeholder="أدخل اسم المنصة التعليمية"
                                           required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- رابط الموقع -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website_url" class="form-label">
                                        <i class="fas fa-globe text-info"></i>
                                        رابط الموقع (اختياري)
                                    </label>
                                    <input type="url" 
                                           name="website_url" 
                                           id="website_url" 
                                           class="form-input" 
                                           value="{{ old('website_url') }}" 
                                           placeholder="https://example.com">
                                    @error('website_url')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div id="urlPreview" class="url-preview" style="display: none;"></div>
                                </div>
                            </div>

                            <!-- وصف المنصة -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left text-secondary"></i>
                                        وصف المنصة (اختياري)
                                    </label>
                                    <textarea name="description" 
                                              id="description" 
                                              class="form-textarea" 
                                              rows="4" 
                                              placeholder="أدخل وصفاً مختصراً عن المنصة وخدماتها التعليمية...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="text-muted">الحد الأقصى 1000 حرف</small>
                                        <small id="descCounter" class="text-muted"></small>
                                    </div>
                                </div>
                            </div>

                            <!-- حالة التفعيل -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-toggle-on text-warning"></i>
                                        حالة المنصة
                                    </label>
                                    <div class="mt-2">
                                        <label class="d-flex align-items-center cursor-pointer">
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" 
                                                   name="is_active" 
                                                   value="1" 
                                                   {{ old('is_active', 1) ? 'checked' : '' }}
                                                   class="form-checkbox">
                                            <span style="font-weight: 600;">المنصة نشطة ومتاحة للطلاب</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات مساعدة -->
                        <div class="info-box">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-lightbulb"></i>
                                نصائح لإنشاء منصة ناجحة
                            </h5>
                            <ul class="mb-0">
                                <li>اختر اسماً واضحاً ومميزاً للمنصة يعكس هويتها التعليمية</li>
                                <li>تأكد من صحة رابط الموقع الإلكتروني قبل الحفظ</li>
                                <li>اكتب وصفاً شاملاً يوضح خدمات ومميزات المنصة</li>
                                <li>يمكن تعديل جميع المعلومات لاحقاً من صفحة التعديل</li>
                                <li>ستتمكن من إضافة باقات تعليمية للمنصة بعد إنشائها</li>
                            </ul>
                        </div>

                        @if(request('teacher_id'))
                            <div class="warning-box">
                                <h5 class="text-warning mb-2">
                                    <i class="fas fa-info-circle"></i>
                                    ملاحظة
                                </h5>
                                <p class="mb-0">
                                    تم تحديد المعلم مسبقاً. يمكنك تغييره من القائمة أعلاه إذا لزم الأمر.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- أزرار التحكم -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.educational.platforms.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right"></i>
                                العودة للقائمة
                            </a>
                            
                            <div class="d-flex gap-3">
                                <button type="reset" class="btn btn-warning">
                                    <i class="fas fa-undo"></i>
                                    إعادة تعيين
                                </button>
                                
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i>
                                    حفظ المنصة
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
        <h5>جاري حفظ المنصة...</h5>
        <p class="text-muted">يرجى الانتظار</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('platformForm');
    const teacherSelect = document.getElementById('teacher_id');
    const teacherPreview = document.getElementById('teacherPreview');
    const websiteInput = document.getElementById('website_url');
    const urlPreview = document.getElementById('urlPreview');
    const descriptionTextarea = document.getElementById('description');
    const descCounter = document.getElementById('descCounter');
    const loadingOverlay = document.getElementById('loadingOverlay');

    // تحديد المعلم المختار مسبقاً
    @if(request('teacher_id'))
        teacherSelect.value = "{{ request('teacher_id') }}";
        updateTeacherPreview();
    @endif

    // التعامل مع اختيار المعلم
    teacherSelect.addEventListener('change', function() {
        updateTeacherPreview();
    });

    function updateTeacherPreview() {
        const selectedOption = teacherSelect.options[teacherSelect.selectedIndex];
        
        if (selectedOption.value) {
            const teacherName = selectedOption.dataset.teacherName;
            const teacherSubject = selectedOption.dataset.teacherSubject;
            const teacherGeneration = selectedOption.dataset.teacherGeneration;
            const teacherSpecialization = selectedOption.dataset.teacherSpecialization || 'غير محدد';
            
            // تحديث محتوى المعاينة
            document.getElementById('teacherAvatar').textContent = teacherName.charAt(0).toUpperCase();
            document.getElementById('teacherName').textContent = teacherName;
            document.getElementById('teacherSubject').textContent = `${teacherSubject} - ${teacherGeneration}`;
            document.getElementById('teacherSpecialization').textContent = teacherSpecialization;
            
            // إظهار المعاينة
            teacherPreview.style.display = 'block';
            teacherPreview.classList.add('active');
        } else {
            // إخفاء المعاينة
            teacherPreview.style.display = 'none';
            teacherPreview.classList.remove('active');
        }
    }

    // معاينة رابط الموقع
    websiteInput.addEventListener('input', function() {
        const url = this.value.trim();
        
        if (url) {
            try {
                // التحقق من صحة الرابط
                const urlObj = new URL(url.startsWith('http') ? url : 'https://' + url);
                urlPreview.textContent = urlObj.href;
                urlPreview.style.display = 'block';
                
                // إزالة رسالة الخطأ إن وجدت
                const errorMsg = this.parentElement.querySelector('.text-danger');
                if (errorMsg) {
                    errorMsg.remove();
                }
            } catch (e) {
                urlPreview.textContent = 'رابط غير صحيح';
                urlPreview.style.display = 'block';
                urlPreview.style.color = '#dc3545';
            }
        } else {
            urlPreview.style.display = 'none';
        }
    });

    // عداد الأحرف للوصف
    function updateDescCounter() {
        const currentLength = descriptionTextarea.value.length;
        const maxLength = 1000;
        descCounter.textContent = `${currentLength}/${maxLength} حرف`;
        
        if (currentLength > maxLength * 0.9) {
            descCounter.className = 'text-warning';
        } else if (currentLength >= maxLength) {
            descCounter.className = 'text-danger';
        } else {
            descCounter.className = 'text-muted';
        }
    }
    
    descriptionTextarea.addEventListener('input', updateDescCounter);
    updateDescCounter();

    // التحقق من صحة النموذج
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const teacherId = teacherSelect.value;
        const platformName = document.getElementById('name').value.trim();
        
        if (!teacherId) {
            showAlert('error', 'يرجى اختيار المعلم المسؤول عن المنصة');
            teacherSelect.focus();
            return;
        }
        
        if (!platformName) {
            showAlert('error', 'يرجى إدخال اسم المنصة');
            document.getElementById('name').focus();
            return;
        }

        // التحقق من رابط الموقع إذا تم إدخاله
        const websiteUrl = websiteInput.value.trim();
        if (websiteUrl) {
            try {
                new URL(websiteUrl.startsWith('http') ? websiteUrl : 'https://' + websiteUrl);
            } catch (e) {
                showAlert('error', 'رابط الموقع غير صحيح');
                websiteInput.focus();
                return;
            }
        }
        
        // عرض Loading
        loadingOverlay.style.display = 'flex';
        
        // تعطيل الأزرار
        const submitBtn = form.querySelector('button[type="submit"]');
        const resetBtn = form.querySelector('button[type="reset"]');
        submitBtn.disabled = true;
        resetBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        
        // إرسال النموذج
        form.submit();
    });

    // إعادة تعيين النموذج
    const resetBtn = form.querySelector('button[type="reset"]');
    resetBtn.addEventListener('click', function() {
        setTimeout(() => {
            teacherPreview.style.display = 'none';
            teacherPreview.classList.remove('active');
            urlPreview.style.display = 'none';
            updateDescCounter();
        }, 100);
    });

    // تحسين تجربة المستخدم
    const inputs = form.querySelectorAll('.form-input, .form-select, .form-textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });

    // إظهار التنبيهات
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        alertDiv.style.minWidth = '300px';
        alertDiv.innerHTML = `
            <i class="fas fa-${icon}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
            }
        }, 5000);
    }

    // تأثيرات بصرية محسنة
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach((group, index) => {
        group.style.opacity = '0';
        group.style.transform = 'translateY(30px)';
        setTimeout(() => {
            group.style.transition = 'all 0.6s ease';
            group.style.opacity = '1';
            group.style.transform = 'translateY(0)';
        }, index * 150);
    });
});
</script>
@endpush