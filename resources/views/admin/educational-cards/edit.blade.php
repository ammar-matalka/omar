@extends('layouts.admin')

@section('title', 'تعديل البطاقة التعليمية #' . $id)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تعديل البطاقة التعليمية #{{ $id }}</h1>
        <div>
            <a href="{{ route('admin.educational-cards.show', $id) }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-eye"></i>
                </span>
                <span class="text">عرض</span>
            </a>
            <a href="{{ route('admin.educational-cards.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">العودة للقائمة</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تعديل بيانات البطاقة التعليمية</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.educational-cards.update', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label for="title">عنوان البطاقة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', 'بطاقة تعليمية نموذجية') }}" 
                                   placeholder="مثال: بطاقة تعليمية للرياضيات">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="وصف البطاقة التعليمية">{{ old('description', 'هذه بطاقة تعليمية نموذجية تحتوي على محتوى تعليمي مفيد للطلاب في مادة الرياضيات.') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category">الفئة <span class="text-danger">*</span></label>
                            <select class="form-control @error('category') is-invalid @enderror" 
                                    id="category" name="category">
                                <option value="">اختر الفئة</option>
                                <option value="math" {{ old('category', 'math') == 'math' ? 'selected' : '' }}>الرياضيات</option>
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
                                            <option value="{{ $i }}" {{ old('grade_level', 5) == $i ? 'selected' : '' }}>
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
                                        <option value="medium" {{ old('difficulty_level', 'medium') == 'medium' ? 'selected' : '' }}>متوسط</option>
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
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">الصيغ المدعومة: JPG, PNG, GIF. الحد الأقصى: 2MB</small>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <img src="https://via.placeholder.com/150x100/007bff/ffffff?text=الصورة+الحالية" 
                                             class="img-fluid rounded shadow" alt="الصورة الحالية">
                                        <small class="d-block text-muted mt-1">الصورة الحالية</small>
                                    </div>
                                </div>
                            </div>
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
                                <i class="fas fa-save"></i> حفظ التعديلات
                            </button>
                            <a href="{{ route('admin.educational-cards.show', $id) }}" class="btn btn-secondary">
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
                    <h6 class="m-0 font-weight-bold text-warning">تحذير</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><i class="fas fa-exclamation-triangle text-warning"></i> تعديل بيانات البطاقة قد يؤثر على:</p>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-dot-circle text-primary"></i> الطلاب الذين يستخدمون البطاقة</li>
                        <li><i class="fas fa-dot-circle text-primary"></i> إحصائيات البطاقة</li>
                        <li><i class="fas fa-dot-circle text-primary"></i> ظهور البطاقة في النتائج</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">إحصائيات سريعة</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="col-6 text-center">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">المشاهدات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">125</div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">التحميلات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">89</div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">التقييم</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">4.2/5</div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">معاينة البطاقة</h6>
                </div>
                <div class="card-body">
                    <div id="card-preview">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">الرياضيات</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">بطاقة تعليمية نموذجية</h5>
                                <p class="card-text text-muted">الصف 5 - مستوى متوسط</p>
                                <span class="badge badge-success">فعال</span>
                            </div>
                        </div>
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
    const gradeLevelSelect = document.getElementById('grade_level');
    const difficultySelect = document.getElementById('difficulty_level');
    const isActiveInput = document.getElementById('is_active');
    const previewDiv = document.getElementById('card-preview');
    
    function updatePreview() {
        const title = titleInput.value || 'بطاقة تعليمية نموذجية';
        const category = categorySelect.options[categorySelect.selectedIndex].text || 'الرياضيات';
        const gradeLevel = gradeLevelSelect.options[gradeLevelSelect.selectedIndex].text || 'الصف 5';
        const difficulty = difficultySelect.options[difficultySelect.selectedIndex].text || 'متوسط';
        const isActive = isActiveInput.checked;
        
        const statusBadge = isActive ? 
            '<span class="badge badge-success">فعال</span>' : 
            '<span class="badge badge-danger">غير فعال</span>';
        
        previewDiv.innerHTML = `
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">${category}</h6>
                </div>
                <div class="card-body">
                    <h5 class="card-title">${title}</h5>
                    <p class="card-text text-muted">${gradeLevel} - مستوى ${difficulty}</p>
                    ${statusBadge}
                </div>
            </div>
        `;
    }
    
    titleInput.addEventListener('input', updatePreview);
    categorySelect.addEventListener('change', updatePreview);
    gradeLevelSelect.addEventListener('change', updatePreview);
    difficultySelect.addEventListener('change', updatePreview);
    isActiveInput.addEventListener('change', updatePreview);
});
</script>
@endsection