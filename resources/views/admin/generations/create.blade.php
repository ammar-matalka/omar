@extends('layouts.admin')

@section('title', 'إضافة جيل جديد')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إضافة جيل جديد</h1>
        <a href="{{ route('admin.generations.index') }}" class="btn btn-secondary btn-icon-split">
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
                    <h6 class="m-0 font-weight-bold text-primary">بيانات الجيل</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.generations.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">اسم الجيل <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="مثال: جيل 2024">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="year">السنة <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                   id="year" name="year" value="{{ old('year', date('Y')) }}" 
                                   min="2000" max="2050">
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="وصف الجيل (اختياري)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="order">ترتيب العرض</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', 0) }}" 
                                   min="0" placeholder="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الترتيب في صفحة عرض الأجيال (0 = الأول)</small>
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
                                <i class="fas fa-save"></i> حفظ الجيل
                            </button>
                            <a href="{{ route('admin.generations.index') }}" class="btn btn-secondary">
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
                        <li><i class="fas fa-info-circle text-info"></i> اسم الجيل مطلوب</li>
                        <li><i class="fas fa-info-circle text-info"></i> السنة يجب أن تكون بين 2000 و 2050</li>
                        <li><i class="fas fa-info-circle text-info"></i> يجب أن تكون السنة فريدة</li>
                        <li><i class="fas fa-info-circle text-info"></i> الترتيب يحدد ظهور الجيل في القائمة</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection