@extends('layouts.admin')

@section('title', 'تعديل المعلم')
@section('page-title', 'تعديل المعلم')

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
<div class="breadcrumb-item">
    <a href="{{ route('admin.educational.teachers.show', $teacher) }}" class="breadcrumb-link">{{ $teacher->name }}</a>
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
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    text-align: center;
    position: relative;
}

.form-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.current-info-section {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    border: 2px solid #dee2e6;
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

.image-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
}

.current-image {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #e9ecef;
    margin-bottom: 1rem;
}

.current-image-placeholder {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: bold;
    color: white;
    margin: 0 auto 1rem;
    border: 5px solid #e9ecef;
}

.image-upload-area {
    border: 3px dashed #dee2e6;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    background: white;
    margin-top: 1rem;
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
    border-radius: 15px;
    margin: 1rem auto;
    display: none;
    border: 3px solid #e9ecef;
}

.upload-icon {
    font-size: 2rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.form-checkbox {
    width: 18px;
    height: 18px;
    accent-color: #007bff;
    margin-left: 10px;
}

.remove-image-section {
    background: #fff3cd;
    border: 2px solid #ffeaa7;
    border-radius: 10px;
    padding: 1rem;
    margin-top: 1rem;
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
    border-radius: 15px;
    text-align: center;
    max-width: 300px;
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

.platforms-summary {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 1rem;
    margin-top: 1rem;
}

.platform-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.platform-item:last-child {
    border-bottom: none;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="edit-form-wrapper">
                <div class="form-header">
                    <div style="position: relative; z-index: 2;">
                        <i class="fas fa-user-edit fa-3x mb-3"></i>
                        <h2 class="mb-2">تعديل بيانات المعلم</h2>
                        <p class="mb-0 opacity-75">{{ $teacher->name }}</p>
                    </div>
                </div>

                <form action="{{ route('admin.educational.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data" id="teacherEditForm">
                    @csrf
                    @method('PATCH')
                    
                    <div class="form-section">
                        <!-- معلومات حالية -->
                        <div class="current-info-section">
                            <h4 class="mb-3 text-primary">
                                <i class="fas fa-info-circle"></i>
                                المعلومات الحالية
                            </h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>المادة الحالية:</strong>
                                    <span class="badge badge-info ms-2">{{ $teacher->subject->name }}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>الجيل:</strong>
                                    <span class="badge badge-secondary ms-2">{{ $teacher->subject->generation->display_name }}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>عدد المنصات:</strong>
                                    <span class="badge badge-primary ms-2">{{ $teacher->platforms->count() }} منصة</span>
                                </div>
                            </div>
                            
                            @if($teacher->platforms->count() > 0)
                                <div class="platforms-summary">
                                    <h6 class="mb-2">المنصات المرتبطة:</h6>
                                    @foreach($teacher->platforms as $platform)
                                        <div class="platform-item">
                                            <span>{{ $platform->name }}</span>
                                            <span class="badge {{ $platform->is_active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $platform->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- تحذير -->
                        @if($teacher->platforms->count() > 0)
                            <div class="warning-box">
                                <h5 class="text-warning mb-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    تنبيه مهم
                                </h5>
                                <p class="mb-0">
                                    هذا المعلم لديه {{ $teacher->platforms->count() }} منصة مرتبطة. 
                                    تغيير المادة قد يؤثر على ترابط البيانات.
                                </p>
                            </div>
                        @endif

                        <!-- معلومات أساسية -->
                        <div class="row">
                            <div class="col-12">
                                <h4 class="mb-4 text-primary">
                                    <i class="fas fa-edit"></i>
                                    تعديل المعلومات الأساسية
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
                                            <option value="{{ $subject->id }}" 
                                                {{ old('subject_id', $teacher->subject_id) == $subject->id ? 'selected' : '' }}>
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
                                           value="{{ old('name', $teacher->name) }}" 
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
                                           value="{{ old('specialization', $teacher->specialization) }}" 
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
                                                   {{ old('is_active', $teacher->is_active) ? 'checked' : '' }}
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
                                              placeholder="أدخل نبذة عن المعلم وخبراته التعليمية...">{{ old('bio', $teacher->bio) }}</textarea>
                                    @error('bio')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="text-muted">الحد الأقصى 1000 حرف</small>
                                        <small id="bioCounter" class="text-muted"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- صورة المعلم -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4 class="mb-4 text-primary">
                                    <i class="fas fa-camera"></i>
                                    إدارة صورة المعلم
                                </h4>
                            </div>

                            <div class="col-12">
                                <div class="image-section">
                                    <h5 class="mb-3">الصورة الحالية</h5>
                                    
                                    @if($teacher->image)
                                        <img src="{{ $teacher->image_url }}" alt="{{ $teacher->name }}" class="current-image">
                                        
                                        <!-- خيار إزالة الصورة -->
                                        <div class="remove-image-section">
                                            <label class="d-flex align-items-center justify-content-center">
                                                <input type="checkbox" 
                                                       name="remove_image" 
                                                       value="1" 
                                                       class="form-checkbox">
                                                <span class="text-warning">
                                                    <i class="fas fa-trash"></i>
                                                    إزالة الصورة الحالية
                                                </span>
                                            </label>
                                        </div>
                                    @else
                                        <div class="current-image-placeholder">
                                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                        </div>
                                        <p class="text-muted">لا توجد صورة</p>
                                    @endif
                                    
                                    <!-- رفع صورة جديدة -->
                                    <div class="form-group">
                                        <label for="image" class="form-label">
                                            <i class="fas fa-image text-primary"></i>
                                            تحديث الصورة (اختياري)
                                        </label>
                                        
                                        <div class="image-upload-area" id="imageUploadArea">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <h6>اسحب الصورة الجديدة هنا أو انقر للاختيار</h6>
                                            <p class="text-muted mb-0 small">PNG, JPG, GIF حتى 2MB</p>
                                            <input type="file" 
                                                   name="image" 
                                                   id="image" 
                                                   accept="image/*" 
                                                   style="display: none;">
                                        </div>
                                        
                                        <img id="imagePreview" class="image-preview" alt="معاينة الصورة الجديدة">
                                        
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="bg-light p-4 rounded">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-chart-line"></i>
                                        إحصائيات المعلم
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>عدد المنصات:</strong>
                                            <span class="badge badge-primary">{{ $teacher->platforms->count() }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>المنصات النشطة:</strong>
                                            <span class="badge badge-success">{{ $teacher->platforms->where('is_active', true)->count() }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>تاريخ الإنشاء:</strong>
                                            {{ $teacher->created_at->format('Y-m-d') }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>آخر تحديث:</strong>
                                            {{ $teacher->updated_at->format('Y-m-d') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- أزرار التحكم -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.educational.teachers.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-right"></i>
                                    العودة للقائمة
                                </a>
                                
                                <a href="{{ route('admin.educational.teachers.show', $teacher) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                    عرض التفاصيل
                                </a>
                            </div>
                            
                            <div class="d-flex gap-2">
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد التعديل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-question-circle fa-3x text-warning"></i>
                </div>
                <h6 class="text-center">هل أنت متأكد من حفظ التعديلات؟</h6>
                <div id="changesPreview"></div>
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
    const form = document.getElementById('teacherEditForm');
    const imageUploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const bioTextarea = document.getElementById('bio');
    const bioCounter = document.getElementById('bioCounter');
    const removeImageCheckbox = document.querySelector('input[name="remove_image"]');

    // Store original values for comparison
    const originalValues = {
        subject_id: document.getElementById('subject_id').value,
        name: document.getElementById('name').value,
        specialization: document.getElementById('specialization').value,
        bio: bioTextarea.value,
        is_active: document.querySelector('input[name="is_active"]:checked') ? '1' : '0'
    };

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
                <h6 class="text-success">تم اختيار صورة جديدة</h6>
                <p class="text-muted mb-0 small">${file.name}</p>
            `;
        };
        reader.readAsDataURL(file);
    }

    // عداد الأحرف للسيرة الذاتية
    function updateBioCounter() {
        const currentLength = bioTextarea.value.length;
        const maxLength = 1000;
        bioCounter.textContent = `${currentLength}/${maxLength} حرف`;
        
        if (currentLength > maxLength * 0.9) {
            bioCounter.className = 'text-warning';
        } else if (currentLength >= maxLength) {
            bioCounter.className = 'text-danger';
        } else {
            bioCounter.className = 'text-muted';
        }
    }
    
    bioTextarea.addEventListener('input', updateBioCounter);
    updateBioCounter();

    // إدارة إزالة الصورة
    if (removeImageCheckbox) {
        removeImageCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // إخفاء الصورة الحالية وإظهار تحذير
                const currentImage = document.querySelector('.current-image');
                if (currentImage) {
                    currentImage.style.opacity = '0.3';
                }
                
                // تحذير
                showAlert('warning', 'سيتم حذف الصورة الحالية عند حفظ التعديلات');
            } else {
                // إعادة إظهار الصورة الحالية
                const currentImage = document.querySelector('.current-image');
                if (currentImage) {
                    currentImage.style.opacity = '1';
                }
            }
        });
    }

    // تتبع التغييرات
    function getChanges() {
        const changes = [];
        const currentValues = {
            subject_id: document.getElementById('subject_id').value,
            name: document.getElementById('name').value,
            specialization: document.getElementById('specialization').value,
            bio: bioTextarea.value,
            is_active: document.querySelector('input[name="is_active"]:checked') ? '1' : '0'
        };

        Object.keys(originalValues).forEach(key => {
            if (originalValues[key] !== currentValues[key]) {
                changes.push({
                    field: key,
                    old: originalValues[key],
                    new: currentValues[key]
                });
            }
        });

        // التحقق من الصورة الجديدة
        if (imageInput.files.length > 0) {
            changes.push({
                field: 'image',
                old: 'الصورة الحالية',
                new: 'صورة جديدة'
            });
        }

        // التحقق من إزالة الصورة
        if (removeImageCheckbox && removeImageCheckbox.checked) {
            changes.push({
                field: 'remove_image',
                old: 'الصورة الحالية',
                new: 'سيتم حذف الصورة'
            });
        }

        return changes;
    }

    // التحقق من صحة النموذج
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = document.getElementById('name').value.trim();
        const subjectId = document.getElementById('subject_id').value;
        
        if (!name || !subjectId) {
            showAlert('error', 'يرجى ملء جميع الحقول المطلوبة');
            return;
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
        
        let changesHtml = '<div class="mt-3"><h6>التعديلات المطلوبة:</h6><ul class="list-group">';
        
        changes.forEach(change => {
            const fieldNames = {
                'subject_id': 'المادة الدراسية',
                'name': 'اسم المعلم',
                'specialization': 'التخصص',
                'bio': 'السيرة الذاتية',
                'is_active': 'حالة التفعيل',
                'image': 'الصورة',
                'remove_image': 'إزالة الصورة'
            };
            
            changesHtml += `
                <li class="list-group-item d-flex justify-content-between">
                    <strong>${fieldNames[change.field]}:</strong>
                    <span class="text-muted">${change.old} → ${change.new}</span>
                </li>
            `;
        });
        
        changesHtml += '</ul></div>';
        changesPreview.innerHTML = changesHtml;
        
        modal.show();
    }

    // تأكيد الحفظ
    document.getElementById('confirmSave').addEventListener('click', function() {
        bootstrap.Modal.getInstance(document.getElementById('confirmationModal')).hide();
        
        // عرض Loading
        loadingOverlay.style.display = 'flex';
        
        // تعطيل الزر
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        
        // إرسال النموذج
        form.submit();
    });

    // إعادة تعيين النموذج
    const resetBtn = form.querySelector('button[type="reset"]');
    resetBtn.addEventListener('click', function() {
        setTimeout(() => {
            imagePreview.style.display = 'none';
            imageUploadArea.innerHTML = `
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <h6>اسحب الصورة الجديدة هنا أو انقر للاختيار</h6>
                <p class="text-muted mb-0 small">PNG, JPG, GIF حتى 2MB</p>
            `;
            
            // إعادة تعيين الصورة الحالية
            const currentImage = document.querySelector('.current-image');
            if (currentImage) {
                currentImage.style.opacity = '1';
            }
            
            updateBioCounter();
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

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 
                          type === 'info' ? 'alert-info' : 'alert-danger';
        const icon = type === 'success' ? 'check-circle' : 
                    type === 'warning' ? 'exclamation-triangle' : 
                    type === 'info' ? 'info-circle' : 'exclamation-circle';
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${icon}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        form.insertBefore(alertDiv, form.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
});
</script>
@endpush