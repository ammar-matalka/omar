@extends('layouts.admin')

@section('title', 'إضافة بطاقة تعليمية جديدة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إضافة بطاقة تعليمية جديدة</h1>
        <a href="{{ route('admin.educational-cards.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">العودة للقائمة</span>
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">بيانات البطاقة التعليمية</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.educational-cards.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label for="title">عنوان البطاقة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="مثال: بطاقة تعليمية للرياضيات">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="وصف البطاقة التعليمية">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category">الفئة <span class="text-danger">*</span></label>
                            <select class="form-control @error('category') is-invalid @enderror" 
                                    id="category" name="category">
                                <option value="">اختر الفئة</option>
                                <option value="math" {{ old('category') == 'math' ? 'selected' : '' }}>الرياضيات</option>
                                <option value="science" {{ old('category') == 'science' ? 'selected' : '' }}>العلوم</option>
                                <option value="arabic" {{ old('category') == 'arabic' ? 'selected' : '' }}>اللغة العربية</option>
                                <option value="english" {{ old('category') == 'english' ? 'selected' : '' }}>اللغة الإنجليزية</option>
                                <option value="history" {{ old('category') == 'history' ? 'selected' : '' }}>التاريخ</option>
                                <option value="geography" {{ old('category') == 'geography' ? 'selected' : '' }}>الجغرافيا</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grade_level">المستوى الدراسي</label>
                                    <select class="form-control @error('grade_level') is-invalid @enderror" 
                                            id="grade_level" name="grade_level">
                                        <option value="">اختر المستوى</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('grade_level') == $i ? 'selected' : '' }}>
                                                الصف {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('grade_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="difficulty_level">مستوى الصعوبة</label>
                                    <select class="form-control @error('difficulty_level') is-invalid @enderror" 
                                            id="difficulty_level" name="difficulty_level">
                                        <option value="">اختر مستوى الصعوبة</option>
                                        <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>سهل</option>
                                        <option value="medium" {{ old('difficulty_level') == 'medium' ? 'selected' : '' }}>متوسط</option>
                                        <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>صعب</option>
                                    </select>
                                    @error('difficulty_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="image">صورة البطاقة</label>
                            <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الصيغ المدعومة: JPG, PNG, GIF. الحد الأقصى: 2MB</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">فعال</label>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ البطاقة
                            </button>
                            <a href="{{ route('admin.educational-cards.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">ملاحظات</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-info-circle text-info"></i> عنوان البطاقة مطلوب</li>
                        <li><i class="fas fa-info-circle text-info"></i> اختيار الفئة مطلوب</li>
                        <li><i class="fas fa-info-circle text-info"></i> يفضل رفع صورة واضحة للبطاقة</li>
                        <li><i class="fas fa-info-circle text-info"></i> البطاقات غير الفعالة لن تظهر للطلاب</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">معاينة البطاقة</h6>
                </div>
                <div class="card-body">
                    <div id="card-preview" class="text-center text-muted">
                        <i class="fas fa-id-card fa-3x mb-3"></i>
                        <p>معاينة البطاقة ستظهر هنا بعد ملء البيانات</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview functionality
    const titleInput = document.getElementById('title');
    const categorySelect = document.getElementById('category');
    const previewDiv = document.getElementById('card-preview');
    
    function updatePreview() {
        const title = titleInput.value || 'عنوان البطاقة';
        const category = categorySelect.options[categorySelect.selectedIndex].text || 'الفئة';
        
        if (titleInput.value || categorySelect.value) {
            previewDiv.innerHTML = `
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">${category}</h6>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">${title}</h5>
                        <p class="card-text text-muted">معاينة البطاقة التعليمية</p>
                    </div>
                </div>
            `;
        }
    }
    
    titleInput.addEventListener('input', updatePreview);
    categorySelect.addEventListener('change', updatePreview);
});
</script>
@endsection