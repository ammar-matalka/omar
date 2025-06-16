@extends('layouts.admin')

@section('page-title', 'تفاصيل المادة: ' . $educationalSubject->name)

@section('content')
<div style="margin-bottom: var(--space-xl);">
    <!-- Breadcrumb -->
    <nav style="margin-bottom: var(--space-lg);">
        <div style="display: flex; align-items: center; gap: var(--space-sm); color: var(--gray-500); font-size: 0.875rem;">
            <a href="{{ route('admin.dashboard') }}" style="color: var(--primary-600); text-decoration: none;">لوحة التحكم</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <a href="{{ route('admin.educational-subjects.index') }}" style="color: var(--primary-600); text-decoration: none;">المواد التعليمية</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <span>{{ $educationalSubject->name }}</span>
        </div>
    </nav>

    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: var(--space-xl);">
        <div>
            <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: var(--space-sm);">{{ $educationalSubject->name }}</h1>
            <div style="display: flex; align-items: center; gap: var(--space-md);">
                <span class="badge badge-{{ $educationalSubject->is_active ? 'success' : 'danger' }}">
                    {{ $educationalSubject->is_active ? 'نشط' : 'غير نشط' }}
                </span>
                <span style="color: var(--gray-600);">{{ $educationalSubject->generation->display_name }}</span>
            </div>
        </div>
        
        <div style="display: flex; gap: var(--space-sm);">
            <a href="{{ route('admin.educational-subjects.edit', $educationalSubject) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                تعديل
            </a>
            <form action="{{ route('admin.educational-subjects.toggle-status', $educationalSubject) }}" 
                  method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-{{ $educationalSubject->is_active ? 'warning' : 'success' }}">
                    <i class="fas {{ $educationalSubject->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                    {{ $educationalSubject->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                </button>
            </form>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-xl);">
        <!-- Main Content -->
        <div>
            <!-- Subject Information -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        معلومات المادة
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg);">
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">اسم المادة</label>
                            <div style="font-weight: 600; font-size: 1.125rem;">{{ $educationalSubject->name }}</div>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">الجيل التعليمي</label>
                            <div style="font-weight: 600;">
                                <a href="{{ route('admin.generations.show', $educationalSubject->generation) }}" 
                                   style="color: var(--primary-600); text-decoration: none;">
                                    {{ $educationalSubject->generation->display_name }}
                                </a>
                            </div>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">السعر الحالي</label>
                            <div style="font-weight: 700; font-size: 1.25rem; color: var(--success-600);">
                                {{ $educationalSubject->formatted_price }}
                            </div>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">ترتيب العرض</label>
                            <div style="font-weight: 600;">#{{ $educationalSubject->order }}</div>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">الحالة</label>
                            <span class="badge badge-{{ $educationalSubject->is_active ? 'success' : 'danger' }}">
                                {{ $educationalSubject->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">تاريخ الإنشاء</label>
                            <div style="font-weight: 500;">{{ $educationalSubject->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i>
                        تاريخ الطلبات
                    </h3>
                </div>
                <div class="card-body" style="padding: 0;">
                    @php
                        $recentOrders = $educationalSubject->orderItems()
                            ->with(['order.user', 'order.generation'])
                            ->latest()
                            ->limit(10)
                            ->get();
                    @endphp
                    
                    @if($recentOrders->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>اسم الطالب</th>
                                    <th>المستخدم</th>
                                    <th>الكمية</th>
                                    <th>السعر وقت الطلب</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.educational-card-orders.show', $item->order) }}" 
                                               style="color: var(--primary-600); text-decoration: none; font-weight: 600;">
                                                #{{ $item->order->id }}
                                            </a>
                                        </td>
                                        <td style="font-weight: 500;">{{ $item->order->student_name }}</td>
                                        <td>
                                            <div style="font-weight: 500;">{{ $item->order->user->name }}</div>
                                            <div style="font-size: 0.75rem; color: var(--gray-500);">{{ $item->order->user->email }}</div>
                                        </td>
                                        <td style="font-weight: 600;">{{ $item->quantity }}</td>
                                        <td style="font-weight: 600; color: var(--success-600);">{{ $item->formatted_price }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item->order->status_color }}">
                                                {{ $item->order->status_text }}
                                            </span>
                                        </td>
                                        <td style="color: var(--gray-600);">{{ $item->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.educational-card-orders.show', $item->order) }}" 
                                               class="btn btn-sm btn-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        @if($educationalSubject->orderItems()->count() > 10)
                        <div style="padding: var(--space-lg); text-align: center; border-top: 1px solid var(--border-color);">
                            <a href="{{ route('admin.educational-card-orders.index') }}?subject_id={{ $educationalSubject->id }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-list"></i>
                                عرض جميع الطلبات ({{ $educationalSubject->orderItems()->count() }})
                            </a>
                        </div>
                        @endif
                    @else
                        <div style="text-align: center; padding: var(--space-3xl); color: var(--gray-500);">
                            <div style="font-size: 3rem; margin-bottom: var(--space-lg);">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3 style="margin-bottom: var(--space-sm);">لا توجد طلبات لهذه المادة</h3>
                            <p>لم يطلب أي طالب هذه المادة بعد</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Statistics -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i>
                        إحصائيات المادة
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; gap: var(--space-lg);">
                        <div style="text-align: center; padding: var(--space-md); background: var(--primary-50); border-radius: var(--radius-md);">
                            <div style="font-size: 2rem; font-weight: 700; color: var(--primary-600);">
                                {{ $orderStats['total_orders'] }}
                            </div>
                            <div style="font-size: 0.875rem; color: var(--primary-700);">إجمالي الطلبات</div>
                        </div>
                        
                        <div style="text-align: center; padding: var(--space-md); background: var(--success-50); border-radius: var(--radius-md);">
                            <div style="font-size: 2rem; font-weight: 700; color: var(--success-600);">
                                {{ $orderStats['total_quantity'] }}
                            </div>
                            <div style="font-size: 0.875rem; color: var(--success-700);">النسخ المباعة</div>
                        </div>
                        
                        <div style="text-align: center; padding: var(--space-md); background: var(--warning-50); border-radius: var(--radius-md);">
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--warning-600);">
                                {{ number_format($orderStats['total_revenue'], 2) }} JD
                            </div>
                            <div style="font-size: 0.875rem; color: var(--warning-700);">إجمالي الإيرادات</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Subjects -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-books"></i>
                        مواد نفس الجيل
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $relatedSubjects = $educationalSubject->generation->subjects()
                            ->where('id', '!=', $educationalSubject->id)
                            ->ordered()
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($relatedSubjects->count() > 0)
                        <div style="display: grid; gap: var(--space-sm);">
                            @foreach($relatedSubjects as $subject)
                                <a href="{{ route('admin.educational-subjects.show', $subject) }}" 
                                   style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm); border: 1px solid var(--border-color); border-radius: var(--radius-sm); text-decoration: none; color: inherit; transition: all var(--transition-fast);"
                                   onmouseover="this.style.borderColor='var(--primary-500)'; this.style.background='var(--primary-50)';"
                                   onmouseout="this.style.borderColor='var(--border-color)'; this.style.background='white';">
                                    <span style="font-weight: 500;">{{ $subject->name }}</span>
                                    <span style="font-weight: 600; color: var(--success-600);">{{ $subject->formatted_price }}</span>
                                </a>
                            @endforeach
                        </div>
                        
                        @if($educationalSubject->generation->subjects()->count() > 6)
                        <div style="text-align: center; margin-top: var(--space-md);">
                            <a href="{{ route('admin.educational-subjects.index') }}?generation_id={{ $educationalSubject->generation_id }}" 
                               class="btn btn-sm btn-secondary">
                                عرض جميع مواد الجيل
                            </a>
                        </div>
                        @endif
                    @else
                        <div style="text-align: center; color: var(--gray-500);">
                            <p>لا توجد مواد أخرى لهذا الجيل</p>
                        </div>
                    @endif
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
                        <a href="{{ route('admin.educational-subjects.edit', $educationalSubject) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                            تعديل المادة
                        </a>
                        
                        <a href="{{ route('admin.educational-subjects.create') }}?generation_id={{ $educationalSubject->generation_id }}" class="btn btn-success">
                            <i class="fas fa-plus"></i>
                            إضافة مادة لنفس الجيل
                        </a>
                        
                        <a href="{{ route('admin.generations.show', $educationalSubject->generation) }}" class="btn btn-info">
                            <i class="fas fa-graduation-cap"></i>
                            عرض الجيل
                        </a>
                        
                        <a href="{{ route('admin.educational-card-orders.index') }}?subject_id={{ $educationalSubject->id }}" class="btn btn-secondary">
                            <i class="fas fa-file-alt"></i>
                            طلبات هذه المادة
                        </a>
                        
                        @if($educationalSubject->orderItems()->count() == 0)
                        <form action="{{ route('admin.educational-subjects.destroy', $educationalSubject) }}" 
                              method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟ هذا الإجراء لا يمكن التراجع عنه.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                                حذف المادة
                            </button>
                        </form>
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