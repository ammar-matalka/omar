{{-- admin/generations/index.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'إدارة الأجيال التعليمية')

@section('content')
<div style="margin-bottom: var(--space-xl);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg);">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">إدارة الأجيال التعليمية</h1>
            <p style="color: var(--gray-600);">إدارة أجيال الطلاب والمناهج التعليمية</p>
        </div>
        <a href="{{ route('admin.generations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            إضافة جيل جديد
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-4" style="margin-bottom: var(--space-xl);">
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 2rem; color: var(--primary-500); margin-bottom: var(--space-sm);">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">
                    {{ $generations->total() }}
                </div>
                <div style="color: var(--gray-600); font-size: 0.875rem;">إجمالي الأجيال</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 2rem; color: var(--success-500); margin-bottom: var(--space-sm);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">
                    {{ $generations->where('is_active', true)->count() }}
                </div>
                <div style="color: var(--gray-600); font-size: 0.875rem;">الأجيال النشطة</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 2rem; color: var(--info-500); margin-bottom: var(--space-sm);">
                    <i class="fas fa-book"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">
                    {{ $generations->sum('subjects_count') }}
                </div>
                <div style="color: var(--gray-600); font-size: 0.875rem;">إجمالي المواد</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 2rem; color: var(--warning-500); margin-bottom: var(--space-sm);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">
                    {{ $generations->sum('orders_count') }}
                </div>
                <div style="color: var(--gray-600); font-size: 0.875rem;">إجمالي الطلبات</div>
            </div>
        </div>
    </div>

    <!-- Generations Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i>
                قائمة الأجيال
            </h3>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($generations->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>الترتيب</th>
                            <th>اسم الجيل</th>
                            <th>السنة</th>
                            <th>عدد المواد</th>
                            <th>عدد الطلبات</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($generations as $generation)
                            <tr>
                                <td>
                                    <span style="font-weight: 600; color: var(--primary-600);">#{{ $generation->order }}</span>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ $generation->display_name }}</div>
                                    @if($generation->description)
                                        <div style="font-size: 0.75rem; color: var(--gray-500);">
                                            {{ Str::limit($generation->description, 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 1.125rem; font-weight: 700; color: var(--secondary-600);">
                                        {{ $generation->year }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $generation->subjects_count }} مادة</span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $generation->orders_count }} طلب</span>
                                </td>
                                <td>
                                    @if($generation->is_active)
                                        <span class="badge badge-success">نشط</span>
                                    @else
                                        <span class="badge badge-danger">غير نشط</span>
                                    @endif
                                </td>
                                <td style="color: var(--gray-600); font-size: 0.875rem;">
                                    {{ $generation->created_at->format('Y-m-d') }}
                                </td>
                                <td>
                                    <div style="display: flex; gap: var(--space-xs);">
                                        <a href="{{ route('admin.generations.show', $generation) }}" 
                                           class="btn btn-sm btn-secondary" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.generations.edit', $generation) }}" 
                                           class="btn btn-sm btn-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.generations.toggle-status', $generation) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $generation->is_active ? 'btn-warning' : 'btn-success' }}" 
                                                    title="{{ $generation->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                <i class="fas {{ $generation->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                            </button>
                                        </form>
                                        @if($generation->orders_count == 0)
                                            <form action="{{ route('admin.generations.destroy', $generation) }}" 
                                                  method="POST" style="display: inline;"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الجيل؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div style="padding: var(--space-lg);">
                    {{ $generations->links() }}
                </div>
            @else
                <div style="text-align: center; padding: var(--space-3xl); color: var(--gray-500);">
                    <div style="font-size: 3rem; margin-bottom: var(--space-lg);">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 style="margin-bottom: var(--space-sm);">لا توجد أجيال تعليمية</h3>
                    <p style="margin-bottom: var(--space-lg);">ابدأ بإنشاء الجيل الأول</p>
                    <a href="{{ route('admin.generations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        إضافة جيل جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

{{-- admin/generations/create.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'إضافة جيل جديد')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus"></i>
                إضافة جيل تعليمي جديد
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.generations.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">اسم الجيل *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" 
                           placeholder="مثال: جيل 2007" required>
                    @error('name')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">سنة الجيل *</label>
                    <input type="number" name="year" class="form-input" value="{{ old('year') }}" 
                           min="2000" max="2050" placeholder="2007" required>
                    @error('year')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-input" rows="3" 
                              placeholder="وصف مختصر عن الجيل...">{{ old('description') }}</textarea>
                    @error('description')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">ترتيب العرض</label>
                    <input type="number" name="order" class="form-input" value="{{ old('order', 0) }}" 
                           min="0" placeholder="0">
                    <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: var(--space-xs);">
                        الأجيال ذات الترتيب الأقل تظهر أولاً
                    </div>
                    @error('order')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: var(--space-sm); cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>نشط (متاح للطلاب)</span>
                    </label>
                </div>

                <div style="display: flex; gap: var(--space-md); justify-content: flex-end;">
                    <a href="{{ route('admin.generations.index') }}" class="btn btn-secondary">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        حفظ الجيل
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- admin/generations/edit.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'تعديل الجيل: ' . $generation->display_name)

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit"></i>
                تعديل الجيل: {{ $generation->display_name }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.generations.update', $generation) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">اسم الجيل *</label>
                    <input type="text" name="name" class="form-input" 
                           value="{{ old('name', $generation->name) }}" required>
                    @error('name')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">سنة الجيل *</label>
                    <input type="number" name="year" class="form-input" 
                           value="{{ old('year', $generation->year) }}" min="2000" max="2050" required>
                    @error('year')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-input" rows="3">{{ old('description', $generation->description) }}</textarea>
                    @error('description')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">ترتيب العرض</label>
                    <input type="number" name="order" class="form-input" 
                           value="{{ old('order', $generation->order) }}" min="0">
                    @error('order')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: var(--space-sm); cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', $generation->is_active) ? 'checked' : '' }}>
                        <span>نشط (متاح للطلاب)</span>
                    </label>
                </div>

                <div style="display: flex; gap: var(--space-md); justify-content: flex-end;">
                    <a href="{{ route('admin.generations.index') }}" class="btn btn-secondary">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection