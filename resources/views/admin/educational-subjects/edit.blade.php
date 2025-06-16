@extends('layouts.admin')

@section('page-title', 'تعديل المادة: ' . $educationalSubject->name)

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit"></i>
                تعديل المادة: {{ $educationalSubject->name }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.educational-subjects.update', $educationalSubject) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">الجيل التعليمي *</label>
                    <select name="generation_id" class="form-input" required>
                        <option value="">اختر الجيل</option>
                        @foreach($generations as $generation)
                            <option value="{{ $generation->id }}" 
                                {{ old('generation_id', $educationalSubject->generation_id) == $generation->id ? 'selected' : '' }}>
                                {{ $generation->display_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('generation_id')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">اسم المادة *</label>
                    <input type="text" name="name" class="form-input" 
                           value="{{ old('name', $educationalSubject->name) }}" required>
                    @error('name')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">سعر المادة (بالدينار الأردني) *</label>
                    <div style="position: relative;">
                        <input type="number" name="price" class="form-input" 
                               value="{{ old('price', $educationalSubject->price) }}" 
                               min="0" step="0.01" max="999999.99" required
                               style="padding-left: 3rem;">
                        <div style="position: absolute; left: var(--space-md); top: 50%; transform: translateY(-50%); color: var(--gray-500); font-weight: 500;">
                            JD
                        </div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: var(--space-xs);">
                        السعر الحالي: {{ $educationalSubject->formatted_price }}
                    </div>
                    @error('price')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">ترتيب العرض</label>
                    <input type="number" name="order" class="form-input" 
                           value="{{ old('order', $educationalSubject->order) }}" min="0">
                    @error('order')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: var(--space-sm); cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', $educationalSubject->is_active) ? 'checked' : '' }}>
                        <span>نشط (متاح للطلاب)</span>
                    </label>
                </div>

                <div style="display: flex; gap: var(--space-md); justify-content: flex-end; margin-top: var(--space-xl);">
                    <a href="{{ route('admin.educational-subjects.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
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

    <!-- Subject Statistics -->
    <div class="card" style="margin-top: var(--space-lg);">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-bar"></i>
                إحصائيات المادة
            </h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: var(--space-lg);">
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary-600);">
                        {{ $educationalSubject->orderItems()->count() }}
                    </div>
                    <div style="font-size: 0.875rem; color: var(--gray-600);">إجمالي الطلبات</div>
                </div>
                
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--success-600);">
                        {{ $educationalSubject->orderItems()->sum('quantity') }}
                    </div>
                    <div style="font-size: 0.875rem; color: var(--gray-600);">النسخ المباعة</div>
                </div>
                
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--warning-600);">
                        {{ number_format($educationalSubject->orderItems()->sum(\DB::raw('price * quantity')), 2) }} JD
                    </div>
                    <div style="font-size: 0.875rem; color: var(--gray-600);">إجمالي الإيرادات</div>
                </div>
                
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--info-600);">
                        {{ $educationalSubject->created_at->format('Y-m-d') }}
                    </div>
                    <div style="font-size: 0.875rem; color: var(--gray-600);">تاريخ الإنشاء</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Warning for Active Orders -->
    @if($educationalSubject->orderItems()->count() > 0)
    <div class="alert alert-warning" style="margin-top: var(--space-lg);">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>تنبيه:</strong> هذه المادة موجودة في {{ $educationalSubject->orderItems()->count() }} طلب. 
        تغيير السعر لن يؤثر على الطلبات الموجودة مسبقاً.
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="card" style="margin-top: var(--space-lg);">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-tools"></i>
                إجراءات سريعة
            </h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-md);">
                <a href="{{ route('admin.educational-subjects.show', $educationalSubject) }}" class="btn btn-secondary">
                    <i class="fas fa-eye"></i>
                    عرض تفاصيل المادة
                </a>
                
                <a href="{{ route('admin.educational-subjects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    إضافة مادة جديدة
                </a>
                
                <a href="{{ route('admin.generations.show', $educationalSubject->generation) }}" class="btn btn-info">
                    <i class="fas fa-graduation-cap"></i>
                    عرض جيل {{ $educationalSubject->generation->display_name }}
                </a>
                
                @if($educationalSubject->orderItems()->count() == 0)
                <form action="{{ route('admin.educational-subjects.destroy', $educationalSubject) }}" 
                      method="POST" style="display: inline;"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟ هذا الإجراء لا يمكن التراجع عنه.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        حذف المادة
                    </button>
                </form>
                @else
                <button class="btn btn-danger" disabled title="لا يمكن حذف المادة لأنها موجودة في طلبات">
                    <i class="fas fa-trash"></i>
                    حذف المادة (غير متاح)
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Auto-format price input
document.querySelector('input[name="price"]').addEventListener('input', function(e) {
    let value = e.target.value;
    
    // Remove any non-numeric characters except decimal point
    value = value.replace(/[^\d.]/g, '');
    
    // Ensure only one decimal point
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }
    
    // Limit decimal places to 2
    if (parts[1] && parts[1].length > 2) {
        value = parts[0] + '.' + parts[1].substring(0, 2);
    }
    
    e.target.value = value;
});

// Warn about price changes
const originalPrice = {{ $educationalSubject->price }};
document.querySelector('input[name="price"]').addEventListener('change', function(e) {
    const newPrice = parseFloat(e.target.value);
    const orderCount = {{ $educationalSubject->orderItems()->count() }};
    
    if (orderCount > 0 && newPrice !== originalPrice) {
        if (!confirm(`تنبيه: هذه المادة موجودة في ${orderCount} طلب. تغيير السعر لن يؤثر على الطلبات الموجودة. هل تريد المتابعة؟`)) {
            e.target.value = originalPrice;
        }
    }
});
</script>
@endsection