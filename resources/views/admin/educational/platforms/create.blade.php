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
    border-radius: 25px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.wizard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.wizard-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -30%;
    width: 250px;
    height: 250px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.wizard-header::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -30%;
    width: 180px;
    height: 180px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
}

.wizard-icon {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    border: 4px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.form-section {
    padding: 3rem;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 3px solid #e9ecef;
    position: relative;
}

.section-header::after {
    content: '';
    position: absolute;
    bottom: -3px;
    right: 0;
    width: 100px;
    height: 3px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 2px;
}

.section-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.section-title h4 {
    margin: 0;
    color: #495057;
    font-weight: 800;
    font-size: 1.25rem;
}

.section-title p {
    margin: 0;
    color: #6c757d;
    font-size: 0.95rem;
}

.form-group {
    margin-bottom: 2.5rem;
}

.form-label {
    font-weight: 700;
    margin-bottom: 1rem;
    display: block;
    color: #495057;
    font-size: 1rem;
}

.form-label i {
    margin-left: 12px;
    width: 24px;
    color: inherit;
}

.form-input, .form-textarea, .form-select {
    width: 100%;
    padding: 1.25rem 1.5rem;
    border: 3px solid #e9ecef;
    border-radius: 15px;
    transition: all 0.4s ease;
    font-size: 1.05rem;
    background: #f8f9fa;
}

.form-input:focus, .form-textarea:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.3rem rgba(102, 126, 234, 0.15);
    outline: none;
    background: white;
    transform: translateY(-3px);
}

.form-input:hover, .form-textarea:hover, .form-select:hover {
    border-color: #dee2e6;
    background: white;
}

.teacher-preview {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 3px solid #dee2e6;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.teacher-preview::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    border-radius: 0 20px 0 100px;
}

.teacher-preview.active {
    border-color: #667eea;
    background: linear-gradient(135deg, #e3f2fd, #f8f9fa);
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
}

.teacher-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 2;
}

.teacher-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 900;
    font-size: 2rem;
    flex-shrink: 0;
    border: 4px solid white;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.teacher-details h5 {
    margin: 0 0 0.5rem 0;
    color: #495057;
    font-weight: 700;
    font-size: 1.25rem;
}

.teacher-details p {
    margin: 0 0 0.25rem 0;
    color: #6c757d;
    font-size: 0.95rem;
}

.form-checkbox {
    width: 24px;
    height: 24px;
    accent-color: #667eea;
    margin-left: 15px;
    cursor: pointer;
}

.info-box {
    background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
    border: 3px solid #c3e6cb;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
}

.info-box::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(195, 230, 203, 0.2));
    border-radius: 0 20px 0 80px;
}

.warning-box {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border: 3px solid #ffc107;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
}

.warning-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.2), rgba(255, 234, 167, 0.2));
    border-radius: 0 0 80px 20px;
}

.text-danger {
    color: #dc3545 !important;
    font-size: 0.9rem;
    margin-top: 0.75rem;
    font-weight: 600;
}

.btn {
    padding: 1rem 2.5rem;
    border-radius: 15px;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.4s ease;
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d, #495057);
    color: white;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    color: #212529;
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
    padding: 3rem;
    border-radius: 25px;
    text-align: center;
    max-width: 400px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #667eea;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1s linear infinite;
    margin: 0 auto 2rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.url-preview {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 2px solid #dee2e6;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-top: 0.75rem;
    font-family: 'Courier New', monospace;
    font-size: 0.95rem;
    color: #495057;
    word-break: break-all;
}

.char-counter {
    display: flex;
    justify-content: space-between;
    margin-top: 0.75rem;
}

.char-counter small {
    font-size: 0.85rem;
}

.progress-indicator {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: #e9ecef;
    z-index: 1000;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    width: 0%;
    transition: width 0.3s ease;
}

.floating-help {
    position: fixed;
    bottom: 30px;
    left: 30px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 1rem;
    border-radius: 50%;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 1000;
}

.floating-help:hover {
    transform: scale(1.1);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
}

@media (max-width: 768px) {
    .form-section {
        padding: 2rem 1.5rem;
    }
    
    .teacher-info {
        flex-direction: column;
        text-align: center;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .floating-help {
        bottom: 20px;
        left: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="progress-indicator">
    <div class="progress-bar" id="progressBar"></div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="form-wizard">
                <div class="wizard-header">
                    <div style="position: relative; z-index: 2;">
                        <div class="wizard-icon">
                            <i class="fas fa-desktop fa-3x"></i>
                        </div>
                        <h2 class="mb-2">إضافة منصة تعليمية جديدة</h2>
                        <p class="mb-0 opacity-75">قم بإنشاء منصة تعليمية جديدة لأحد المعلمين المتاحين</p>
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
                                <h4>اختيار المعلم المسؤول</h4>
                                <p>حدد المعلم الذي ستنتمي إليه هذه المنصة التعليمية</p>
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
                                        <option value="">اختر المعلم من القائمة</option>
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
                                    <div style="position: relative; z-index: 2;">
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
                        </div>

                        <!-- معلومات المنصة -->
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="section-title">
                                <h4>معلومات المنصة التعليمية</h4>
                                <p>أدخل البيانات الأساسية والتفاصيل الخاصة بالمنصة</p>
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
                                           placeholder="أدخل اسم المنصة التعليمية (مثل: منصة الرياضيات المتقدمة)"
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
                                        رابط الموقع الإلكتروني (اختياري)
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
                                              maxlength="1000"
                                              placeholder="أدخل وصفاً مختصراً عن المنصة وخدماتها التعليمية والمميزات التي تقدمها للطلاب...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="char-counter">
                                        <small class="text-muted">وصف تفصيلي يساعد الطلاب على فهم محتوى المنصة</small>
                                        <small id="descCounter" class="text-muted">0/1000 حرف</small>
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
                                            <span style="font-weight: 600;">المنصة نشطة ومتاحة للطلاب والعمليات التعليمية</span>
                                        </label>
                                        <small class="text-muted d-block mt-2">
                                            يمكن تغيير حالة التفعيل لاحقاً من صفحة إدارة المنصات
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات مساعدة -->
                        <div class="info-box">
                            <div style="position: relative; z-index: 2;">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-lightbulb"></i>
                                    نصائح لإنشاء منصة تعليمية ناجحة
                                </h5>
                                <ul class="mb-0">
                                    <li class="mb-2">اختر اسماً واضحاً ومميزاً للمنصة يعكس هويتها التعليمية ومحتواها</li>
                                    <li class="mb-2">تأكد من صحة رابط الموقع الإلكتروني وأنه يعمل بشكل صحيح</li>
                                    <li class="mb-2">اكتب وصفاً شاملاً وواضحاً يوضح خدمات ومميزات المنصة للطلاب</li>
                                    <li class="mb-2">يمكن تعديل جميع المعلومات لاحقاً من صفحة إدارة المنصات</li>
                                    <li class="mb-0">ستتمكن من إضافة باقات تعليمية متنوعة للمنصة بعد إنشائها</li>
                                </ul>
                            </div>
                        </div>

                        @if(request('teacher_id'))
                            <div class="warning-box">
                                <div style="position: relative; z-index: 2;">
                                    <h5 class="text-warning mb-2">
                                        <i class="fas fa-info-circle"></i>
                                        ملاحظة
                                    </h5>
                                    <p class="mb-0">
                                        تم تحديد المعلم مسبقاً من الرابط. يمكنك تغييره من القائمة أعلاه إذا لزم الأمر.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- أزرار التحكم -->
                    <div class="card-footer bg-light" style="padding: 2rem;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <a href="{{ route('admin.educational.platforms.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right"></i>
                                العودة للقائمة
                            </a>
                            
                            <div class="d-flex gap-3 flex-wrap">
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
        <p class="text-muted">يرجى الانتظار قليلاً</p>
    </div>
</div>

<!-- Floating Help Button -->
<div class="floating-help" onclick="showHelp()">
    <i class="fas fa-question fa-lg"></i>
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
    const progressBar = document.getElementById('progressBar');

    // تحديد المعلم المختار مسبقاً
    @if(request('teacher_id'))
        teacherSelect.value = "{{ request('teacher_id') }}";
        updateTeacherPreview();
        updateProgress(25);
    @endif

    // التعامل مع اختيار المعلم
    teacherSelect.addEventListener('change', function() {
        updateTeacherPreview();
        updateProgress();
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
            
            // إظهار المعاينة مع تأثير
            teacherPreview.style.display = 'block';
            setTimeout(() => {
                teacherPreview.classList.add('active');
            }, 100);
        } else {
            // إخفاء المعاينة
            teacherPreview.classList.remove('active');
            setTimeout(() => {
                teacherPreview.style.display = 'none';
            }, 300);
        }
    }

    // معاينة رابط الموقع
    websiteInput.addEventListener('input', function() {
        const url = this.value.trim();
        
        if (url) {
            try {
                // التحقق من صحة الرابط
                const urlObj = new URL(url.startsWith('http') ? url : 'https://' + url);
                urlPreview.textContent = `معاينة: ${urlObj.href}`;
                urlPreview.style.display = 'block';
                urlPreview.style.color = '#495057';
                
                // إزالة رسالة الخطأ إن وجدت
                const errorMsg = this.parentElement.querySelector('.text-danger');
                if (errorMsg) {
                    errorMsg.remove();
                }
            } catch (e) {
                urlPreview.textContent = 'رابط غير صحيح - يرجى التحقق من صحة الرابط';
                urlPreview.style.display = 'block';
                urlPreview.style.color = '#dc3545';
            }
        } else {
            urlPreview.style.display = 'none';
        }
        updateProgress();
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
        updateProgress();
    }
    
    descriptionTextarea.addEventListener('input', updateDescCounter);
    updateDescCounter();

    // تحديث شريط التقدم
    function updateProgress(customValue = null) {
        if (customValue !== null) {
            progressBar.style.width = customValue + '%';
            return;
        }

        let progress = 0;
        
        // اختيار المعلم (40%)
        if (teacherSelect.value) progress += 40;
        
        // اسم المنصة (30%)
        if (document.getElementById('name').value.trim()) progress += 30;
        
        // رابط الموقع (15%)
        if (websiteInput.value.trim()) progress += 15;
        
        // الوصف (15%)
        if (descriptionTextarea.value.trim()) progress += 15;
        
        progressBar.style.width = progress + '%';
    }

    // إضافة مستمعين للحقول لتحديث التقدم
    document.getElementById('name').addEventListener('input', updateProgress);
    
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
        progressBar.style.width = '100%';
        
        // تعطيل الأزرار
        const submitBtn = form.querySelector('button[type="submit"]');
        const resetBtn = form.querySelector('button[type="reset"]');
        submitBtn.disabled = true;
        resetBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        
        // إرسال النموذج
        setTimeout(() => {
            form.submit();
        }, 1000);
    });

    // إعادة تعيين النموذج
    const resetBtn = form.querySelector('button[type="reset"]');
    resetBtn.addEventListener('click', function() {
        setTimeout(() => {
            teacherPreview.style.display = 'none';
            teacherPreview.classList.remove('active');
            urlPreview.style.display = 'none';
            updateDescCounter();
            updateProgress(0);
        }, 100);
    });

    // تحسين تجربة المستخدم
    const inputs = form.querySelectorAll('.form-input, .form-select, .form-textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.style.transition = 'transform 0.3s ease';
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
        alertDiv.style.borderRadius = '15px';
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

    // تحديث التقدم الأولي
    updateProgress();
});

// وظيفة المساعدة
function showHelp() {
    const helpModal = `
        <div class="modal fade" id="helpModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-question-circle"></i>
                            مساعدة إنشاء منصة تعليمية
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="accordion" id="helpAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#help1">
                                        كيفية اختيار المعلم المناسب؟
                                    </button>
                                </h2>
                                <div id="help1" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>اختر المعلم حسب المادة والجيل المطلوب</li>
                                            <li>تأكد من أن المعلم نشط ومتاح</li>
                                            <li>يمكن للمعلم الواحد أن يملك عدة منصات</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#help2">
                                        نصائح لاختيار اسم المنصة
                                    </button>
                                </h2>
                                <div id="help2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>اختر اسماً واضحاً يعكس محتوى المنصة</li>
                                            <li>تجنب الأسماء المعقدة أو الطويلة</li>
                                            <li>يمكن تضمين اسم المعلم أو المادة</li>
                                            <li>مثال: "منصة الرياضيات المتقدمة - أ.محمد"</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#help3">
                                        أهمية رابط الموقع والوصف
                                    </button>
                                </h2>
                                <div id="help3" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>رابط الموقع يساعد الطلاب للوصول للمحتوى</li>
                                            <li>الوصف يوضح ما تقدمه المنصة من خدمات</li>
                                            <li>كلاهما اختياري لكن ينصح بتعبئتهما</li>
                                            <li>يمكن تعديلهما لاحقاً</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">فهمت</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // إضافة المودال للصفحة إذا لم يكن موجوداً
    if (!document.getElementById('helpModal')) {
        document.body.insertAdjacentHTML('beforeend', helpModal);
    }
    
    // إظهار المودال
    new bootstrap.Modal(document.getElementById('helpModal')).show();
}
</script>
@endpush