@extends('layouts.admin')

@section('title', 'تعديل المادة')
@section('page-title', 'تعديل المادة')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">
    <a href="{{ route('admin.educational.subjects.index') }}" class="breadcrumb-link">المواد الدراسية</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">تعديل المادة</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i>
                        تعديل بيانات المادة: {{ $subject->name }}
                    </h3>
                </div>
                
                <form action="{{ route('admin.educational.subjects.update', $subject) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="card-body">
                        <div class="row">
                            <!-- اختيار الجيل -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="generation_id" class="form-label">
                                        <i class="fas fa-graduation-cap text-primary"></i>
                                        الجيل الدراسي *
                                    </label>
                                    <select name="generation_id" id="generation_id" class="form-input" required>
                                        <option value="">اختر الجيل الدراسي</option>
                                        @foreach($generations as $generation)
                                            <option value="{{ $generation->id }}" 
                                                {{ old('generation_id', $subject->generation_id) == $generation->id ? 'selected' : '' }}>
                                                {{ $generation->display_name }} ({{ $generation->birth_year }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('generation_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- اسم المادة -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-book text-success"></i>
                                        اسم المادة *
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-input" 
                                           value="{{ old('name', $subject->name) }}" 
                                           placeholder="أدخل اسم المادة"
                                           required>
                                    @error('name')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- كود المادة -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="form-label">
                                        <i class="fas fa-code text-info"></i>
                                        كود المادة (اختياري)
                                    </label>
                                    <input type="text" 
                                           name="code" 
                                           id="code" 
                                           class="form-input" 
                                           value="{{ old('code', $subject->code) }}" 
                                           placeholder="مثال: MATH101">
                                    @error('code')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">كود فريد للمادة (اختياري)</small>
                                </div>
                            </div>

                            <!-- حالة التفعيل -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-toggle-on text-warning"></i>
                                        حالة المادة
                                    </label>
                                    <div class="mt-2">
                                        <label class="inline-flex items-center">
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" 
                                                   name="is_active" 
                                                   value="1" 
                                                   {{ old('is_active', $subject->is_active) ? 'checked' : '' }}
                                                   class="form-checkbox">
                                            <span class="mr-2">المادة نشطة ومتاحة</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="bg-light p-4 rounded">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-info-circle"></i>
                                        معلومات المادة الحالية
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>عدد المعلمين:</strong>
                                            <span class="badge badge-info">{{ $subject->teachers_count ?? 0 }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>المعلمين النشطين:</strong>
                                            <span class="badge badge-success">{{ $subject->active_teachers_count ?? 0 }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>تاريخ الإنشاء:</strong>
                                            {{ $subject->created_at->format('Y-m-d') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin.educational.subjects.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-right"></i>
                                    العودة للقائمة
                                </a>
                                
                                <a href="{{ route('admin.educational.subjects.show', $subject) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                    عرض التفاصيل
                                </a>
                            </div>
                            
                            <div>
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

<style>
.form-checkbox {
    width: 18px;
    height: 18px;
    accent-color: #28a745;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
}

.form-label i {
    margin-left: 8px;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.form-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.card {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 0.75rem;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 2px solid #dee2e6;
    border-radius: 0.75rem 0.75rem 0 0;
}

.btn {
    padding: 0.5rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.badge {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.text-danger {
    color: #dc3545 !important;
    font-size: 0.875rem;
    font-weight: 500;
}

.text-muted {
    color: #6c757d !important;
    font-size: 0.875rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحسين تجربة المستخدم
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    });
    
    // تفعيل الـ tooltips
    const tooltips = document.querySelectorAll('[data-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });
});
</script>
@endsection