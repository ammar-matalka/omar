@extends('layouts.admin')

@section('page-title', 'إدارة المواد التعليمية')

@section('content')
<div style="margin-bottom: var(--space-xl);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg);">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">إدارة المواد التعليمية والأسعار</h1>
            <p style="color: var(--gray-600);">إدارة المواد الدراسية وأسعارها لكل جيل</p>
        </div>
        <a href="{{ route('admin.educational-subjects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
        <a href="{{ route('admin.educational-subjects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            إضافة مادة جديدة
        </a>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: var(--space-xl);">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.educational-subjects.index') }}">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-md); align-items: end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">الجيل</label>
                        <select name="generation_id" class="form-input">
                            <option value="">جميع الأجيال</option>
                            @foreach($generations as $generation)
                                <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                                    {{ $generation->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-input">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">بحث بالاسم</label>
                        <input type="text" name="search" class="form-input" 
                               placeholder="اسم المادة" value="{{ request('search') }}">
                    </div>
                    
                    <div style="display: flex; gap: var(--space-sm);">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            بحث
                        </button>
                        <a href="{{ route('admin.educational-subjects.index') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-4" style="margin-bottom: var(--space-xl);">
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 2rem; color: var(--primary-500); margin-bottom: var(--space-sm);">
                    <i class="fas fa-book"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">
                    {{ $subjects->total() }}
                </div>
                <div style="color: var(--gray-600); font-size: 0.875rem;">إجمالي المواد</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 2rem; color: var(--success-500); margin-bottom: var(--space-sm);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">
                    {{ $subjects->where('is_active', true)->count() }}
                </div>
                <div style="color: var(--gray-600); font-size: 0.875rem;">المواد النشطة</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 2rem; color: var(--info-500); margin-bottom: var(--space-sm);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">
                    {{ number_format($subjects->avg('price'), 2) }} JD
                </div>
                <div style="color: var(--gray-600); font-size: 0.875rem;">متوسط السعر</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 2rem; color: var(--warning-500); margin-bottom: var(--space-sm);">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">
                    {{ $generations->count() }}
                </div>
                <div style="color: var(--gray-600); font-size: 0.875rem;">الأجيال المتاحة</div>
            </div>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i>
                قائمة المواد التعليمية
            </h3>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($subjects->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>الترتيب</th>
                            <th>اسم المادة</th>
                            <th>الجيل</th>
                            <th>السعر</th>
                            <th>المبيعات</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                            <tr>
                                <td>
                                    <span style="font-weight: 600; color: var(--primary-600);">#{{ $subject->order }}</span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; font-size: 1rem;">{{ $subject->name }}</div>
                                </td>
                                <td>
                                    <span style="background: var(--secondary-100); color: var(--secondary-700); padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 500;">
                                        {{ $subject->generation->display_name }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 1.125rem; font-weight: 700; color: var(--success-600);">
                                        {{ $subject->formatted_price }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $totalSales = $subject->orderItems()->sum('quantity');
                                        $totalRevenue = $subject->orderItems()->sum(\DB::raw('price * quantity'));
                                    @endphp
                                    <div>
                                        <div style="font-weight: 600;">{{ $totalSales }} نسخة</div>
                                        <div style="font-size: 0.75rem; color: var(--gray-500);">
                                            {{ number_format($totalRevenue, 2) }} JD
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($subject->is_active)
                                        <span class="badge badge-success">نشط</span>
                                    @else
                                        <span class="badge badge-danger">غير نشط</span>
                                    @endif
                                </td>
                                <td style="color: var(--gray-600); font-size: 0.875rem;">
                                    {{ $subject->created_at->format('Y-m-d') }}
                                </td>
                                <td>
                                    <div style="display: flex; gap: var(--space-xs);">
                                        <a href="{{ route('admin.educational-subjects.show', $subject) }}" 
                                           class="btn btn-sm btn-secondary" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.educational-subjects.edit', $subject) }}" 
                                           class="btn btn-sm btn-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.educational-subjects.toggle-status', $subject) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $subject->is_active ? 'btn-warning' : 'btn-success' }}" 
                                                    title="{{ $subject->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                <i class="fas {{ $subject->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                            </button>
                                        </form>
                                        @if($subject->orderItems()->count() == 0)
                                            <form action="{{ route('admin.educational-subjects.destroy', $subject) }}" 
                                                  method="POST" style="display: inline;"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
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
                    {{ $subjects->appends(request()->query())->links() }}
                </div>
            @else
                <div style="text-align: center; padding: var(--space-3xl); color: var(--gray-500);">
                    <div style="font-size: 3rem; margin-bottom: var(--space-lg);">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 style="margin-bottom: var(--space-sm);">
                        @if(request()->hasAny(['generation_id', 'status', 'search']))
                            لا توجد مواد تطابق معايير البحث
                        @else
                            لا توجد مواد تعليمية
                        @endif
                    </h3>
                    <p style="margin-bottom: var(--space-lg);">
                        @if(request()->hasAny(['generation_id', 'status', 'search']))
                            جرب تغيير معايير البحث
                        @else
                            ابدأ بإنشاء المادة الأولى
                        @endif
                    </p>
                    <div style="display: flex; gap: var(--space-md); justify-content: center;">
                        @if(request()->hasAny(['generation_id', 'status', 'search']))
                            <a href="{{ route('admin.educational-subjects.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i>
                                عرض جميع المواد
                            </a>
                        @endif
                        <a href="{{ route('admin.educational-subjects.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            إضافة مادة جديدة
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bulk Actions -->
    @if($subjects->count() > 0)
    <div class="card" style="margin-top: var(--space-lg);">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-tools"></i>
                إجراءات سريعة
            </h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-md);">
                <a href="{{ route('admin.educational-subjects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    إضافة مادة جديدة
                </a>
                <a href="{{ route('admin.generations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-graduation-cap"></i>
                    إدارة الأجيال
                </a>
                <a href="{{ route('admin.educational-card-orders.index') }}" class="btn btn-info">
                    <i class="fas fa-file-alt"></i>
                    عرض الطلبات
                </a>
                <button onclick="exportSubjects()" class="btn btn-success">
                    <i class="fas fa-download"></i>
                    تصدير البيانات
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function exportSubjects() {
    const params = new URLSearchParams(window.location.search);
    params.append('export', '1');
    
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '{{ route("admin.educational-subjects.index") }}';
    
    for (const [key, value] of params) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endsection