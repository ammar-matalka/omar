@extends('layouts.admin')

@section('page-title', 'إضافة مادة تعليمية جديدة')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus"></i>
                إضافة مادة تعليمية جديدة
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.educational-subjects.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">الجيل التعليمي *</label>
                    <select name="generation_id" class="form-input" required>
                        <option value="">اختر الجيل</option>
                        @foreach($generations as $generation)
                            <option value="{{ $generation->id }}" {{ old('generation_id') == $generation->id ? 'selected' : '' }}>
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
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" 
                           placeholder="مثال: الرياضيات" required>
                    @error('name')
                        <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-xs);">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">سعر المادة (بالدينار الأردني) *</label>
                    <div style="position: relative;">
                        <input type="number" name="price" class="form-input" value="{{ old('price') }}" 
                               min="0" step="0.01" max="999999.99" placeholder="15.00" required
                               style="padding-left: 3rem;">
                        <div style="position: absolute; left: var(--space-md); top: 50%; transform: translateY(-50%); color: var(--gray-500); font-weight: 500;">
                            JD
                        </div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: var(--space-xs);">
                        أدخل السعر بالدينار الأردني (مثال: 15.50)
                    </div>
                    @error('price')
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
                        المواد ذات الترتيب الأقل تظهر أولاً (اتركه 0 للترتيب التلقائي)
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
                    <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: var(--space-xs);">
                        المواد النشطة فقط تظهر للطلاب في صفحة الطلبات
                    </div>
                </div>

                <div style="display: flex; gap: var(--space-md); justify-content: flex-end; margin-top: var(--space-xl);">
                    <a href="{{ route('admin.educational-subjects.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        حفظ المادة
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card" style="margin-top: var(--space-lg);">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-lightbulb"></i>
                نصائح سريعة
            </h3>
        </div>
        <div class="card-body">
            <div style="display: grid; gap: var(--space-md);">
                <div style="display: flex; align-items: start; gap: var(--space-sm);">
                    <i class="fas fa-info-circle" style="color: var(--info-500); margin-top: 2px;"></i>
                    <div>
                        <strong>اختيار الجيل:</strong>
                        <p style="margin: 0; color: var(--gray-600); font-size: 0.875rem;">
                            تأكد من اختيار الجيل الصحيح قبل إضافة المادة. لا يمكن تغيير الجيل بعد الحفظ.
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: start; gap: var(--space-sm);">
                    <i class="fas fa-dollar-sign" style="color: var(--success-500); margin-top: 2px;"></i>
                    <div>
                        <strong>تحديد السعر:</strong>
                        <p style="margin: 0; color: var(--gray-600); font-size: 0.875rem;">
                            أدخل السعر بالدينار الأردني. يمكن استخدام الأرقام العشرية (مثل: 12.50).
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: start; gap: var(--space-sm);">
                    <i class="fas fa-sort" style="color: var(--warning-500); margin-top: 2px;"></i>
                    <div>
                        <strong>ترتيب العرض:</strong>
                        <p style="margin: 0; color: var(--gray-600); font-size: 0.875rem;">
                            استخدم أرقام مختلفة لترتيب المواد (1، 2، 3...). المواد ذات الرقم الأقل تظهر أولاً.
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: start; gap: var(--space-sm);">
                    <i class="fas fa-eye" style="color: var(--primary-500); margin-top: 2px;"></i>
                    <div>
                        <strong>الحالة النشطة:</strong>
                        <p style="margin: 0; color: var(--gray-600); font-size: 0.875rem;">
                            المواد غير النشطة لن تظهر للطلاب لكن ستبقى محفوظة في النظام.
                        </p>
                    </div>
                </div>
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

// Auto-generate order number
document.querySelector('select[name="generation_id"]').addEventListener('change', function(e) {
    const generationId = e.target.value;
    if (generationId) {
        // Optional: Auto-fetch next order number via AJAX
        // fetch(`/admin/generations/${generationId}/next-subject-order`)
        //     .then(response => response.json())
        //     .then(data => {
        //         document.querySelector('input[name="order"]').value = data.next_order;
        //     });
    }
});
</script>
@endsection