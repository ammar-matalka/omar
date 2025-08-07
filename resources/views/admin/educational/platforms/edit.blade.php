@extends('layouts.admin')

@section('title', 'تعديل المنصة')
@section('page-title', 'تعديل المنصة')

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
<div class="breadcrumb-item">
    <a href="{{ route('admin.educational.platforms.show', $platform) }}" class="breadcrumb-link">{{ $platform->name }}</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">تعديل</div>
@endsection

@push('styles')
<style>
.edit-form-wrapper {
    background: white;
    border-radius: 25px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.form-header::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -30%;
    width: 250px;
    height: 250px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.form-header::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -20%;
    width: 180px;
    height: 180px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
}

.header-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    border: 4px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.current-info-section {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 2.5rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    border: 3px solid #dee2e6;
    position: relative;
    overflow: hidden;
}

.current-info-section::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    border-radius: 0 20px 0 100px;
}

.info-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    margin: 0.25rem;
}

.badge-primary {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    color: #1565c0;
}

.badge-secondary {
    background: linear-gradient(135deg, #f5f5f5, #e0e0e0);
    color: #424242;
}

.badge-success {
    background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
    color: #2e7d32;
}

.packages-summary {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 1.5rem;
    margin-top: 1.5rem;
}

.package-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 2px solid #f8f9fa;
    transition: all 0.3s ease;
    border-radius: 10px;
    margin-bottom: 0.5rem;
}

.package-item:hover {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    transform: translateX(-5px);
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
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.teacher-preview::before {
    content: '';
    position: absolute;
    top: -20%;
    right: -15%;
    width: 150px;
    height: 150px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
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
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 900;
    font-size: 2rem;
    flex-shrink: 0;
    border: 4px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.teacher-details h5 {
    margin: 0 0 0.5rem 0;
    font-weight: 700;
    font-size: 1.25rem;
}

.teacher-details p {
    margin: 0 0 0.25rem 0;
    opacity: 0.9;
    font-size: 0.95rem;
}

.form-checkbox {
    width: 24px;
    height: 24px;
    accent-color: #667eea;
    margin-left: 15px;
    cursor: pointer;
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

.btn-info {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
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

.changes-preview {
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    border-radius: 15px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.change-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    background: white;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.change-item:last-child {
    margin-bottom: 0;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    text-align: center;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.stat-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 900;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 600;
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
    
    .stats-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-11">
            <div class="edit-form-wrapper">
                <div class="form-header">
                    <div style="position: relative; z-index: 2;">
                        <div class="header-icon">
                            <i class="fas fa-edit fa-3x"></i>
                        </div>
                        <h2 class="mb-2">تعديل المنصة التعليمية</h2>
                        <p class="mb-0 opacity-75">{{ $platform->name }}</p>
                    </div>
                </div>

                <form action="{{ route('admin.educational.platforms.update', $platform) }}" method="POST" id="platformEditForm">
                    @csrf
                    @method('PATCH')
                    
                    <div class="form-section">
                        <!-- معلومات حالية -->
                        <div class="current-info-section">
                            <div style="position: relative; z-index: 2;">
                                <h4 class="mb-3 text-primary">
                                    <i class="fas fa-info-circle"></i>
                                    المعلومات الحالية للمنصة
                                </h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>المعلم الحالي:</strong>
                                        <div class="info-badge badge-primary">
                                            <i class="fas fa-user-tie"></i>
                                            {{ $platform->teacher->name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>المادة والجيل:</strong>
                                        <div class="info-badge badge-secondary">
                                            <i class="fas fa-book"></i>
                                            {{ $platform->teacher->subject->name }} - {{ $platform->teacher->subject->generation->display_name }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="stats-row">
                                    <div class="stat-card">
                                        <div class="stat-value">{{ $platform->educationalPackages->count() }}</div>
                                        <div class="stat-label">إجمالي الباقات</div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-value">{{ $platform->educationalPackages->where('is_active', true)->count() }}</div>
                                        <div class="stat-label">الباقات النشطة</div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-value">{{ $platform->created_at->format('Y-m-d') }}</div>
                                        <div class="stat-label">تاريخ الإنشاء</div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-value">{{ $platform->updated_at->format('Y-m-d') }}</div>
                                        <div class="stat-label">آخر تحديث</div>
                                    </div>
                                </div>
                                
                                @if($platform->educationalPackages->count() > 0)
                                    <div class="packages-summary">
                                        <h6 class="mb-3">
                                            <i class="fas fa-box"></i>
                                            الباقات المرتبطة بالمنصة:
                                        </h6>
                                        @foreach($platform->educationalPackages->take(5) as $package)
                                            <div class="package-item">
                                                <div>
                                                    <span class="fw-bold">{{ $package->name }}</span>
                                                    <small class="text-muted ms-2">({{ $package->package_type }})</small>
                                                </div>
                                                <span class="info-badge {{ $package->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $package->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                            </div>
                                        @endforeach
                                        @if($platform->educationalPackages->count() > 5)
                                            <div class="text-center mt-2">
                                                <small class="text-muted">
                                                    و {{ $platform->educationalPackages->count() - 5 }} باقات أخرى...
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- تحذير -->
                        @if($platform->educationalPackages->count() > 0)
                            <div class="warning-box">
                                <div style="position: relative; z-index: 2;">
                                    <h5 class="text-warning mb-3">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        تنبيه مهم عند التعديل
                                    </h5>
                                    <p class="mb-0">
                                        هذه المنصة لديها {{ $platform->educationalPackages->count() }} باقة مرتبطة. 
                                        تغيير المعلم المسؤول قد يؤثر على ترابط البيانات والتسعير المرتبط بالمنصة.
                                        تأكد من مراجعة جميع الباقات والأسعار بعد التعديل.
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- اختيار المعلم -->
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="section-title">
                                <h4>تعديل المعلم المسؤول</h4>
                                <p>يمكنك تغيير المعلم المسؤول عن هذه المنصة</p>
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
                                                {{ old('teacher_id', $platform->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }} - {{ $teacher->subject->name }} ({{ $teacher->subject->generation->display_name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- معاينة المعلم المختار -->
                                <div id="teacherPreview" class="teacher-preview">
                                    <div class="teacher-info">
                                        <div class="teacher-avatar" id="teacherAvatar">
                                            {{ strtoupper(substr($platform->teacher->name, 0, 1)) }}
                                        </div>
                                        <div class="teacher-details">
                                            <h5 id="teacherName">{{ $platform->teacher->name }}</h5>
                                            <p id="teacherSubject">{{ $platform->teacher->subject->name }} - {{ $platform->teacher->subject->generation->display_name }}</p>
                                            <p id="teacherSpecialization">{{ $platform->teacher->specialization ?: 'غير محدد' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات المنصة -->
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="section-title">
                                <h4>تعديل معلومات المنصة</h4>
                                <p>قم بتحديث البيانات الأساسية للمنصة</p>
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
                                           value="{{ old('name', $platform->name) }}" 
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
                                           value="{{ old('website_url', $platform->website_url) }}" 
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
                                              placeholder="أدخل وصفاً مختصراً عن المنصة وخدماتها التعليمية...">{{ old('description', $platform->description) }}</textarea>
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
                                                   {{ old('is_active', $platform->is_active) ? 'checked' : '' }}
                                                   class="form-checkbox">
                                            <span style="font-weight: 600;">المنصة نشطة ومتاحة للطلاب</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="info-box">
                            <div style="position: relative; z-index: 2;">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-info-circle"></i>
                                    نصائح مهمة عند التعديل
                                </h5>
                                <ul class="mb-0">
                                    <li class="mb-2">تأكد من مراجعة جميع الباقات المرتبطة بالمنصة بعد تغيير المعلم</li>
                                    <li class="mb-2">تحديث اسم المنصة قد يتطلب إعلام الطلاب المشتركين</li>
                                    <li class="mb-2">تغيير رابط الموقع يجب أن يتم بحذر لتجنب الروابط المعطلة</li>
                                    <li class="mb-2">يمكن إلغاء تفعيل المنصة مؤقتاً دون حذف البيانات</li>
                                    <li class="mb-0">جميع التعديلات سيتم حفظها في سجل النشاط</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- أزرار التحكم -->
                    <div class="card-footer bg-light" style="padding: 2rem;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="{{ route('admin.educational.platforms.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-right"></i>
                                    العودة للقائمة
                                </a>
                                
                                <a href="{{ route('admin.educational.platforms.show', $platform) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                    عرض التفاصيل
                                </a>
                            </div>
                            
                            <div class="d-flex gap-3 flex-wrap">
                                <button type="reset" class="btn btn-warning">
                                    <i class="fas fa-undo"></i>
                                    إعادة تعيين
                                </button>
                                
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i>
                                    حفظ التعديلات
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
        <h5>جاري حفظ التعديلات...</h5>
        <p class="text-muted">يرجى الانتظار</p>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-question-circle"></i>
                    تأكيد التعديلات
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-edit fa-3x text-warning"></i>
                </div>
                <h6 class="text-center">هل أنت متأكد من حفظ التعديلات؟</h6>
                <div id="changesPreview" class="changes-preview"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-success" id="confirmSave">
                    <i class="fas fa-save"></i>
                    تأكيد الحفظ
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('platformEditForm');
    const teacherSelect = document.getElementById('teacher_id');
    const teacherPreview = document.getElementById('teacherPreview');
    const websiteInput = document.getElementById('website_url');
    const urlPreview = document.getElementById('urlPreview');
    const descriptionTextarea = document.getElementById('description');
    const descCounter = document.getElementById('descCounter');
    const loadingOverlay = document.getElementById('loadingOverlay');

    // Store original values for comparison
    const originalValues = {
        teacher_id: teacherSelect.value,
        name: document.getElementById('name').value,
        website_url: websiteInput.value,
        description: descriptionTextarea.value,
        is_active: document.querySelector('input[name="is_active"]:checked') ? '1' : '0'
    };

    // Initialize teacher preview with current data
    updateTeacherPreview();

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
        } else {
            // إخفاء المعاينة
            teacherPreview.style.display = 'none';
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
                if (errorMsg && errorMsg.textContent.includes('رابط')) {
                    errorMsg.remove();
                }
            } catch (e) {
                urlPreview.textContent = 'رابط غير صحيح - سيتم تصحيحه تلقائياً';
                urlPreview.style.display = 'block';
                urlPreview.style.color = '#dc3545';
            }
        } else {
            urlPreview.style.display = 'none';
        }
    });

    // تشغيل معاينة الرابط عند التحميل
    if (websiteInput.value) {
        websiteInput.dispatchEvent(new Event('input'));
    }

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

    // تتبع التغييرات
    function getChanges() {
        const changes = [];
        const currentValues = {
            teacher_id: teacherSelect.value,
            name: document.getElementById('name').value,
            website_url: websiteInput.value,
            description: descriptionTextarea.value,
            is_active: document.querySelector('input[name="is_active"]:checked') ? '1' : '0'
        };

        Object.keys(originalValues).forEach(key => {
            if (originalValues[key] !== currentValues[key]) {
                let fieldName, oldValue, newValue;
                
                switch (key) {
                    case 'teacher_id':
                        fieldName = 'المعلم المسؤول';
                        oldValue = teacherSelect.querySelector(`option[value="${originalValues[key]}"]`)?.textContent || 'غير محدد';
                        newValue = teacherSelect.querySelector(`option[value="${currentValues[key]}"]`)?.textContent || 'غير محدد';
                        break;
                    case 'name':
                        fieldName = 'اسم المنصة';
                        oldValue = originalValues[key];
                        newValue = currentValues[key];
                        break;
                    case 'website_url':
                        fieldName = 'رابط الموقع';
                        oldValue = originalValues[key] || 'غير محدد';
                        newValue = currentValues[key] || 'غير محدد';
                        break;
                    case 'description':
                        fieldName = 'وصف المنصة';
                        oldValue = originalValues[key] || 'غير محدد';
                        newValue = currentValues[key] || 'غير محدد';
                        break;
                    case 'is_active':
                        fieldName = 'حالة التفعيل';
                        oldValue = originalValues[key] === '1' ? 'نشط' : 'غير نشط';
                        newValue = currentValues[key] === '1' ? 'نشط' : 'غير نشط';
                        break;
                }
                
                changes.push({ field: fieldName, old: oldValue, new: newValue });
            }
        });

        return changes;
    }

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

        // التحقق من التغييرات
        const changes = getChanges();
        
        if (changes.length === 0) {
            showAlert('info', 'لا توجد تعديلات للحفظ');
            return;
        }

        // عرض نافذة التأكيد
        showConfirmationModal(changes);
    });

    function showConfirmationModal(changes) {
        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        const changesPreview = document.getElementById('changesPreview');
        
        let changesHtml = '<h6 class="mb-3">التعديلات المطلوبة:</h6>';
        
        changes.forEach(change => {
            changesHtml += `
                <div class="change-item">
                    <div>
                        <strong>${change.field}:</strong>
                        <br>
                        <small class="text-muted">من: ${change.old}</small>
                        <br>
                        <small class="text-success">إلى: ${change.new}</small>
                    </div>
                </div>
            `;
        });
        
        changesPreview.innerHTML = changesHtml;
        modal.show();
    }

    // تأكيد الحفظ
    document.getElementById('confirmSave').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('confirmationModal')).hide();
        
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
            // إعادة تعيين القيم الأصلية
            Object.keys(originalValues).forEach(key => {
                if (key === 'teacher_id') {
                    teacherSelect.value = originalValues[key];
                } else if (key === 'is_active') {
                    document.querySelector('input[name="is_active"]').checked = originalValues[key] === '1';
                } else {
                    document.getElementById(key).value = originalValues[key];
                }
            });
            
            updateTeacherPreview();
            updateDescCounter();
            
            if (originalValues.website_url) {
                websiteInput.dispatchEvent(new Event('input'));
            } else {
                urlPreview.style.display = 'none';
            }
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
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'info' ? 'alert-info' : 'alert-danger';
        const icon = type === 'success' ? 'check-circle' : 
                    type === 'info' ? 'info-circle' : 'exclamation-circle';
        
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
        }, index * 100);
    });
});
</script>
@endpush