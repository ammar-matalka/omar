@extends('layouts.admin')

@section('title', 'إضافة مادة تعليمية جديدة')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إضافة مادة تعليمية جديدة</h1>
        <a href="{{ route('admin.educational-subjects.index') }}" class="btn btn-secondary btn-icon-split">
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
                    <h6 class="m-0 font-weight-bold text-primary">بيانات المادة</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.educational-subjects.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="generation_id">الجيل <span class="text-danger">*</span></label>
                            <select class="form-control @error('generation_id') is-invalid @enderror" 
                                    id="generation_id" name="generation_id">
                                <option value="">اختر الجيل</option>
                                @foreach($generations as $generation)
                                    <option value="{{ $generation->id }}" {{ old('generation_id') == $generation->id ? 'selected' : '' }}>
                                        {{ $generation->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('generation_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">اسم المادة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="مثال: الرياضيات">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price">السعر (JD) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="999999.99"
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" 
                                   placeholder="0.00">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="order">ترتيب العرض</label>
                            <input type="number" min="0" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', 0) }}" 
                                   placeholder="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الترتيب في صفحة عرض المواد (0 = الأول)</small>
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
                                <i class="fas fa-save"></i> حفظ المادة
                            </button>
                            <a href="{{ route('admin.educational-subjects.index') }}" class="btn btn-secondary">
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
                        <li><i class="fas fa-info-circle text-info"></i> اختيار الجيل مطلوب</li>
                        <li><i class="fas fa-info-circle text-info"></i> اسم المادة مطلوب</li>
                        <li><i class="fas fa-info-circle text-info"></i> السعر يجب أن يكون 0 أو أكثر</li>
                        <li><i class="fas fa-info-circle text-info"></i> الترتيب يحدد ظهور المادة في القائمة</li>
                        <li><i class="fas fa-info-circle text-info"></i> المواد غير الفعالة لن تظهر للطلاب</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">أمثلة أسماء المواد</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li>الرياضيات</li>
                        <li>العلوم</li>
                        <li>اللغة العربية</li>
                        <li>اللغة الإنجليزية</li>
                        <li>التاريخ</li>
                        <li>الجغرافيا</li>
                        <li>التربية الإسلامية</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection