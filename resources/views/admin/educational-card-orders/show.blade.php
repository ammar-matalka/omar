@extends('layouts.admin')

@section('page-title', 'تفاصيل الجيل: ' . $generation->display_name)

@section('content')
<div style="margin-bottom: var(--space-xl);">
    <!-- Breadcrumb -->
    <nav style="margin-bottom: var(--space-lg);">
        <div style="display: flex; align-items: center; gap: var(--space-sm); color: var(--gray-500); font-size: 0.875rem;">
            <a href="{{ route('admin.dashboard') }}" style="color: var(--primary-600); text-decoration: none;">لوحة التحكم</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <a href="{{ route('admin.generations.index') }}" style="color: var(--primary-600); text-decoration: none;">الأجيال التعليمية</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <span>{{ $generation->display_name }}</span>
        </div>
    </nav>

    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: var(--space-xl);">
        <div>
            <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: var(--space-sm);">{{ $generation->display_name }}</h1>
            <div style="display: flex; align-items: center; gap: var(--space-md);">
                <span class="badge badge-{{ $generation->is_active ? 'success' : 'danger' }}">
                    {{ $generation->is_active ? 'نشط' : 'غير نشط' }}
                </span>
                <span style="color: var(--gray-600);">سنة {{ $generation->year }}</span>
            </div>
            @if($generation->description)
                <p style="color: var(--gray-600); margin-top: var(--space-sm); max-width: 600px;">
                    {{ $generation->description }}
                </p>
            @endif
        </div>
        
        <div style="display: flex; gap: var(--space-sm);">
            <a href="{{ route('admin.generations.edit', $generation) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                تعديل
            </a>
            <form action="{{ route('admin.generations.toggle-status', $generation) }}" 
                  method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-{{ $generation->is_active ? 'warning' : 'success' }}">
                    <i class="fas {{ $generation->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                    {{ $generation->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-4" style="margin-bottom: var(--space-xl);">
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 2rem; color: var(--primary-500); margin-bottom: var(--space-sm);">
                    <i class="fas fa-book"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">
                    {{ $generation->subjects->count() }}
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
                    {{ $generation->subjects->where('is_active', true)->count() }}
                </div>
                <div style="color: var(--gray-600); font-size: 0.875rem;">المواد النشطة</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 2rem; color: var(--info-500); margin-bottom: var(--space-sm);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-xs);">
                    {{ $orderStats['total_orders'] }}
                </div>
                <div style="color: var(--gray-600); font-size: 0.875rem;">إجمالي الطلبات</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 2rem; color: var(--warning-500); margin-bottom: var(--space-sm);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div style="font-size: 1.25rem; font-weight: 700; margin-bottom: var(--space-xs);">
                    {{ number_format($orderStats['total_revenue'], 2) }} JD
                </div>
                <div style="color: var(--gray-600); font-size: 0.875rem;">إجمالي الإيرادات</div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-xl);">
        <!-- Main Content -->
        <div>
            <!-- Subjects List -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i>
                            مواد الجيل ({{ $generation->subjects->count() }})
                        </h3>
                        <a href="{{ route('admin.educational-subjects.create') }}?generation_id={{ $generation->id }}" 
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i>
                            إضافة مادة جديدة
                        </a>
                    </div>
                </div>
                <div class="card-body" style="padding: 0;">
                    @if($generation->subjects->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>الترتيب</th>
                                    <th>اسم المادة</th>
                                    <th>السعر</th>
                                    <th>المبيعات</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($generation->subjects as $subject)
                                    <tr>
                                        <td>
                                            <span style="font-weight: 600; color: var(--primary-600);">#{{ $subject->order }}</span>
                                        </td>
                                        <td>
                                            <div style="font-weight: 600;">{{ $subject->name }}</div>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; color: var(--success-600);">
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
                                            <span class="badge badge-{{ $subject->is_active ? 'success' : 'danger' }}">
                                                {{ $subject->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
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
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="text-align: center; padding: var(--space-3xl); color: var(--gray-500);">
                            <div style="font-size: 3rem; margin-bottom: var(--space-lg);">
                                <i class="fas fa-book"></i>
                            </div>
                            <h3 style="margin-bottom: var(--space-sm);">لا توجد مواد لهذا الجيل</h3>
                            <p style="margin-bottom: var(--space-lg);">ابدأ بإضافة المادة الأولى</p>
                            <a href="{{ route('admin.educational-subjects.create') }}?generation_id={{ $generation->id }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                إضافة مادة جديدة
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="card-title">
                            <i class="fas fa-history"></i>
                            آخر الطلبات ({{ $orderStats['total_orders'] }})
                        </h3>
                        <a href="{{ route('admin.educational-card-orders.index') }}?generation_id={{ $generation->id }}" 
                           class="btn btn-sm btn-secondary">
                            <i class="fas fa-list"></i>
                            عرض جميع الطلبات
                        </a>
                    </div>
                </div>
                <div class="card-body" style="padding: 0;">
                    @php
                        $recentOrders = $generation->orders()->with(['user', 'orderItems.subject'])->latest()->limit(10)->get();
                    @endphp
                    
                    @if($recentOrders->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>اسم الطالب</th>
                                    <th>المستخدم</th>
                                    <th>عدد المواد</th>
                                    <th>المجموع</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.educational-card-orders.show', $order) }}" 
                                               style="color: var(--primary-600); text-decoration: none; font-weight: 600;">
                                                #{{ $order->id }}
                                            </a>
                                        </td>
                                        <td style="font-weight: 500;">{{ $order->student_name }}</td>
                                        <td>
                                            <div style="font-weight: 500;">{{ $order->user->name }}</div>
                                            <div style="font-size: 0.75rem; color: var(--gray-500);">{{ $order->user->email }}</div>
                                        </td>
                                        <td>{{ $order->orderItems->count() }} مادة</td>
                                        <td style="font-weight: 600; color: var(--success-600);">{{ $order->formatted_total }}</td>
                                        <td>
                                            <span class="badge badge-{{ $order->status_color }}">
                                                {{ $order->status_text }}
                                            </span>
                                        </td>
                                        <td style="color: var(--gray-600);">{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.educational-card-orders.show', $order) }}" 
                                               class="btn btn-sm btn-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="text-align: center; padding: var(--space-3xl); color: var(--gray-500);">
                            <div style="font-size: 3rem; margin-bottom: var(--space-lg);">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3 style="margin-bottom: var(--space-sm);">لا توجد طلبات لهذا الجيل</h3>
                            <p>لم يطلب أي طالب مواد من هذا الجيل بعد</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Generation Info -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        معلومات الجيل
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; gap: var(--space-md);">
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">اسم الجيل</label>
                            <div style="font-weight: 600;">{{ $generation->name }}</div>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">السنة</label>
                            <div style="font-weight: 600; color: var(--primary-600);">{{ $generation->year }}</div>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">ترتيب العرض</label>
                            <div style="font-weight: 600;">#{{ $generation->order }}</div>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">الحالة</label>
                            <span class="badge badge-{{ $generation->is_active ? 'success' : 'danger' }}">
                                {{ $generation->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">تاريخ الإنشاء</label>
                            <div style="font-weight: 500;">{{ $generation->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        
                        @if($generation->description)
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">الوصف</label>
                            <div style="color: var(--gray-700);">{{ $generation->description }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Statistics -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i>
                        إحصائيات الطلبات
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; gap: var(--space-md);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>إجمالي الطلبات:</span>
                            <span style="font-weight: 600; color: var(--primary-600);">{{ $orderStats['total_orders'] }}</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>قيد الانتظار:</span>
                            <span style="font-weight: 600; color: var(--warning-600);">{{ $orderStats['pending_orders'] }}</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>مكتملة:</span>
                            <span style="font-weight: 600; color: var(--success-600);">{{ $orderStats['completed_orders'] }}</span>
                        </div>
                        
                        <hr style="margin: var(--space-sm) 0;">
                        
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>إجمالي الإيرادات:</span>
                            <span style="font-weight: 700; color: var(--success-600); font-size: 1.125rem;">
                                {{ number_format($orderStats['total_revenue'], 2) }} JD
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tools"></i>
                        إجراءات سريعة
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
                        <a href="{{ route('admin.generations.edit', $generation) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                            تعديل الجيل
                        </a>
                        
                        <a href="{{ route('admin.educational-subjects.create') }}?generation_id={{ $generation->id }}" class="btn btn-success">
                            <i class="fas fa-plus"></i>
                            إضافة مادة جديدة
                        </a>
                        
                        <a href="{{ route('admin.educational-subjects.index') }}?generation_id={{ $generation->id }}" class="btn btn-info">
                            <i class="fas fa-list"></i>
                            عرض مواد الجيل
                        </a>
                        
                        <a href="{{ route('admin.educational-card-orders.index') }}?generation_id={{ $generation->id }}" class="btn btn-secondary">
                            <i class="fas fa-file-alt"></i>
                            طلبات الجيل
                        </a>
                        
                        @if($orderStats['total_orders'] == 0)
                        <form action="{{ route('admin.generations.destroy', $generation) }}" 
                              method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الجيل؟ سيتم حذف جميع المواد المرتبطة به أيضاً.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                                حذف الجيل
                            </button>
                        </form>
                        @else
                        <button class="btn btn-danger" disabled title="لا يمكن حذف الجيل لأنه يحتوي على طلبات">
                            <i class="fas fa-trash"></i>
                            حذف الجيل (غير متاح)
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .container > div:last-child {
            grid-template-columns: 1fr;
        }
        
        .table {
            font-size: 0.875rem;
        }
        
        .table th, .table td {
            padding: var(--space-sm);
        }
    }
</style>
@endsection