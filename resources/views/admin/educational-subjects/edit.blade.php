@extends('layouts.admin')

@section('title', 'تعديل المادة: ' . $educationalSubject->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تعديل المادة: {{ $educationalSubject->name }}</h1>
        <div>
            <a href="{{ route('admin.educational-subjects.show', $educationalSubject) }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-eye"></i>
                </span>
                <span class="text">عرض</span>
            </a>
            <a href="{{ route('admin.educational-subjects.index') }}" class="btn btn-secondary btn-icon-split">
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
                    <h6 class="m-0 font-weight-bold text-primary">تعديل بيانات المادة</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.educational-subjects.update', $educationalSubject) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label for="order">ترتيب العرض</label>
                            <input type="number" min="0" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', $educationalSubject->order) }}" 
                                   placeholder="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الترتيب في صفحة عرض المواد (0 = الأول)</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', $educationalSubject->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">فعال</label>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ التعديلات
                            </button>
                            <a href="{{ route('admin.educational-subjects.show', $educationalSubject) }}" class="btn btn-secondary">
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
                    <p class="mb-2"><i class="fas fa-exclamation-triangle text-warning"></i> تعديل بيانات المادة قد يؤثر على:</p>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-dot-circle text-primary"></i> الطلبات الموجودة</li>
                        <li><i class="fas fa-dot-circle text-primary"></i> أسعار المادة في النظام</li>
                        <li><i class="fas fa-dot-circle text-primary"></i> عرض المادة للطلاب</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">إحصائيات سريعة</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">الطلبات</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $educationalSubject->orderItems()->count() }}</div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">الإيرادات</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($educationalSubject->orderItems()->sum(\DB::raw('price * quantity')), 2) }} JD</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsectiongroup">
                            <label for="generation_id">الجيل <span class="text-danger">*</span></label>
                            <select class="form-control @error('generation_id') is-invalid @enderror" 
                                    id="generation_id" name="generation_id">
                                <option value="">اختر الجيل</option>
                                @foreach($generations as $generation)
                                    <option value="{{ $generation->id }}" 
                                            {{ old('generation_id', $educationalSubject->generation_id) == $generation->id ? 'selected' : '' }}>
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
                                   id="name" name="name" value="{{ old('name', $educationalSubject->name) }}" 
                                   placeholder="مثال: الرياضيات">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price">السعر (JD) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="999999.99"
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', $educationalSubject->price) }}" 
                                   placeholder="0.00">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-