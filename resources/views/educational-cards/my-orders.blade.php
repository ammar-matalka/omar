@extends('layouts.app')

@section('title', 'طلباتي التعليمية')

@section('content')
<div class="container" style="margin-top: var(--space-xl); margin-bottom: var(--space-3xl);">
    <!-- Header Section -->
    <div style="text-align: center; margin-bottom: var(--space-2xl);">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: var(--space-sm);">طلباتي التعليمية</h1>
        <p style="color: var(--gray-600);">متابعة حالة طلبات الدوسيات التعليمية</p>
    </div>

    <!-- Filter Section -->
    <div class="card" style="margin-bottom: var(--space-xl);">
        <div class="card-body">
            <form action="{{ route('educational-cards.search-orders') }}" method="GET">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-md); align-items: end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">البحث بالاسم</label>
                        <input type="text" name="student_name" class="form-input" 
                               placeholder="اسم الطالب" value="{{ request('student_name') }}">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-input">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغى</option>
                        </select>
                    </div>
                    
                    @if(isset($generations))
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
                    @endif
                    
                    <div style="display: flex; gap: var(--space-sm);">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            بحث
                        </button>
                        <a href="{{ route('educational-cards.my-orders') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div style="display: grid; gap: var(--space-lg);">
            @foreach($orders as $order)
                <div class="card" style="border-left: 4px solid var(--{{ $order->status_color }}-500);">
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr auto; gap: var(--space-lg); align-items: start;">
                            <!-- Order Info -->
                            <div>
                                <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-sm);">
                                    <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0;">
                                        طلب #{{ $order->id }} - {{ $order->student_name }}
                                    </h3>
                                    <span class="badge badge-{{ $order->status_color }}">
                                        {{ $order->status_text }}
                                    </span>
                                </div>
                                
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: var(--space-md); margin-bottom: var(--space-md);">
                                    <div>
                                        <span style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">الجيل</span>
                                        <div style="font-weight: 500;">{{ $order->generation->display_name }}</div>
                                    </div>
                                    <div>
                                        <span style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">الفصل</span>
                                        <div style="font-weight: 500;">{{ $order->semester_text }}</div>
                                    </div>
                                    <div>
                                        <span style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">الكمية</span>
                                        <div style="font-weight: 500;">{{ $order->quantity }}</div>
                                    </div>
                                    <div>
                                        <span style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">التاريخ</span>
                                        <div style="font-weight: 500;">{{ $order->created_at->format('Y-m-d') }}</div>
                                    </div>
                                </div>
                                
                                <!-- Subjects -->
                                <div style="margin-bottom: var(--space-md);">
                                    <span style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">المواد المطلوبة</span>
                                    <div style="display: flex; flex-wrap: wrap; gap: var(--space-xs); margin-top: var(--space-xs);">
                                        @foreach($order->orderItems as $item)
                                            <span style="background: var(--primary-100); color: var(--primary-700); padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-size: 0.75rem;">
                                                {{ $item->subject_name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                
                                @if($order->notes)
                                <div>
                                    <span style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">ملاحظات</span>
                                    <div style="color: var(--gray-600); font-size: 0.875rem;">{{ $order->notes }}</div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Price & Actions -->
                            <div style="text-align: right;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--success-600); margin-bottom: var(--space-md);">
                                    {{ $order->formatted_total }}
                                </div>
                                
                                <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
                                    <a href="{{ route('educational-cards.show-order', $order) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                        عرض التفاصيل
                                    </a>
                                    
                                    @if($order->status === 'pending')
                                        <a href="{{ route('user.conversations.create') }}?order_id={{ $order->id }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-comments"></i>
                                            التواصل
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="margin-top: var(--space-xl); display: flex; justify-content: center;">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body" style="text-align: center; padding: var(--space-3xl);">
                <div style="color: var(--gray-400); font-size: 4rem; margin-bottom: var(--space-lg);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 style="color: var(--gray-600); margin-bottom: var(--space-sm);">لا توجد طلبات تعليمية</h3>
                <p style="color: var(--gray-500); margin-bottom: var(--space-lg);">
                    @if(request()->hasAny(['student_name', 'status', 'generation_id']))
                        لم يتم العثور على طلبات تطابق معايير البحث
                    @else
                        لم تقم بإنشاء أي طلبات تعليمية بعد
                    @endif
                </p>
                
                <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
                    @if(request()->hasAny(['student_name', 'status', 'generation_id']))
                        <a href="{{ route('educational-cards.my-orders') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i>
                            عرض جميع الطلبات
                        </a>
                    @endif
                    <a href="{{ route('educational-cards.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        إنشاء طلب جديد
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="card" style="margin-top: var(--space-xl);">
        <div class="card-body">
            <h4 style="margin-bottom: var(--space-md);">إجراءات سريعة</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-md);">
                <a href="{{ route('educational-cards.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    طلب دوسية جديدة
                </a>
                <a href="{{ route('user.conversations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-comments"></i>
                    الدعم الفني
                </a>
                <a href="{{ route('user.profile.show') }}" class="btn btn-secondary">
                    <i class="fas fa-user"></i>
                    الملف الشخصي
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .card-body > div:first-child {
            grid-template-columns: 1fr;
            gap: var(--space-md);
        }
        
        .card-body > div:first-child > div:last-child {
            text-align: left;
        }
        
        .form-group {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection