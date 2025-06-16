@extends('layouts.app')

@section('title', 'تفاصيل الطلب #' . $order->id)

@section('content')
<div class="container" style="margin-top: var(--space-xl); margin-bottom: var(--space-3xl);">
    <!-- Breadcrumb -->
    <nav style="margin-bottom: var(--space-lg);">
        <div style="display: flex; align-items: center; gap: var(--space-sm); color: var(--gray-500); font-size: 0.875rem;">
            <a href="{{ route('home') }}" style="color: var(--primary-600); text-decoration: none;">الرئيسية</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <a href="{{ route('educational-cards.my-orders') }}" style="color: var(--primary-600); text-decoration: none;">طلباتي التعليمية</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <span>طلب #{{ $order->id }}</span>
        </div>
    </nav>

    <!-- Header -->
    <div style="text-align: center; margin-bottom: var(--space-2xl);">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: var(--space-sm);">
            تفاصيل الطلب #{{ $order->id }}
        </h1>
        <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-md);">
            <span class="badge badge-{{ $order->status_color }}" style="font-size: 0.875rem; padding: var(--space-sm) var(--space-md);">
                {{ $order->status_text }}
            </span>
            <span style="color: var(--gray-600);">{{ $order->created_at->format('Y-m-d H:i') }}</span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-xl);">
        <!-- Main Content -->
        <div>
            <!-- Order Information -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        معلومات الطلب
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg);">
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">اسم الطالب</label>
                            <div style="font-weight: 600; font-size: 1.125rem;">{{ $order->student_name }}</div>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">الجيل التعليمي</label>
                            <div style="font-weight: 600;">{{ $order->generation->display_name }}</div>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">الفصل الدراسي</label>
                            <div style="font-weight: 600;">{{ $order->semester_text }}</div>
                        </div>
                        
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">الكمية</label>
                            <div style="font-weight: 600;">{{ $order->quantity }} نسخة</div>
                        </div>
                    </div>
                    
                    @if($order->phone || $order->address)
                    <div style="border-top: 1px solid var(--border-color); margin-top: var(--space-lg); padding-top: var(--space-lg);">
                        <h4 style="margin-bottom: var(--space-md); color: var(--gray-700);">معلومات الاتصال</h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg);">
                            @if($order->phone)
                            <div>
                                <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">رقم الهاتف</label>
                                <div style="font-weight: 500;">
                                    <i class="fas fa-phone" style="color: var(--primary-500); margin-right: var(--space-xs);"></i>
                                    {{ $order->phone }}
                                </div>
                            </div>
                            @endif
                            
                            @if($order->address)
                            <div>
                                <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; display: block; margin-bottom: var(--space-xs);">العنوان</label>
                                <div style="font-weight: 500;">
                                    <i class="fas fa-map-marker-alt" style="color: var(--primary-500); margin-right: var(--space-xs);"></i>
                                    {{ $order->address }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    @if($order->notes)
                    <div style="border-top: 1px solid var(--border-color); margin-top: var(--space-lg); padding-top: var(--space-lg);">
                        <h4 style="margin-bottom: var(--space-sm); color: var(--gray-700);">ملاحظات إضافية</h4>
                        <div style="background: var(--gray-50); padding: var(--space-md); border-radius: var(--radius-md); border-left: 3px solid var(--primary-500);">
                            {{ $order->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i>
                        المواد المطلوبة
                    </h3>
                </div>
                <div class="card-body" style="padding: 0;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>المادة</th>
                                <th>السعر الوحدة</th>
                                <th>الكمية</th>
                                <th>المجموع الفرعي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;">{{ $item->subject_name }}</div>
                                        @if($item->subject)
                                            <div style="font-size: 0.75rem; color: var(--gray-500);">
                                                السعر الحالي: {{ $item->subject->formatted_price }}
                                            </div>
                                        @endif
                                    </td>
                                    <td style="font-weight: 500;">{{ $item->formatted_price }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td style="font-weight: 600; color: var(--success-600);">{{ $item->formatted_subtotal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: var(--gray-50); font-weight: 700;">
                                <td colspan="3" style="text-align: right;">المجموع النهائي:</td>
                                <td style="font-size: 1.125rem; color: var(--success-600);">{{ $order->formatted_total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Status Timeline -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock"></i>
                        حالة الطلب
                    </h3>
                </div>
                <div class="card-body">
                    <div style="position: relative;">
                        @php
                            $statuses = [
                                'pending' => ['text' => 'قيد الانتظار', 'icon' => 'fas fa-clock', 'color' => 'warning'],
                                'processing' => ['text' => 'قيد المعالجة', 'icon' => 'fas fa-cog', 'color' => 'info'], 
                                'completed' => ['text' => 'مكتمل', 'icon' => 'fas fa-check', 'color' => 'success'],
                            ];
                            $currentStatusIndex = array_search($order->status, array_keys($statuses));
                        @endphp
                        
                        @foreach($statuses as $status => $info)
                            @php
                                $statusIndex = array_search($status, array_keys($statuses));
                                $isActive = $statusIndex <= $currentStatusIndex;
                                $isCurrent = $status === $order->status;
                            @endphp
                            
                            <div style="display: flex; align-items: center; margin-bottom: var(--space-md); position: relative;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid; {{ $isActive ? 'background: var(--' . $info['color'] . '-500); border-color: var(--' . $info['color'] . '-500); color: white;' : 'border-color: var(--gray-300); color: var(--gray-400);' }}">
                                    <i class="{{ $info['icon'] }}"></i>
                                </div>
                                <div style="margin-right: var(--space-md); flex: 1;">
                                    <div style="font-weight: {{ $isCurrent ? '700' : '500' }}; {{ $isActive ? 'color: var(--' . $info['color'] . '-700);' : 'color: var(--gray-500);' }}">
                                        {{ $info['text'] }}
                                    </div>
                                    @if($isCurrent)
                                        <div style="font-size: 0.75rem; color: var(--gray-500);">الحالة الحالية</div>
                                    @endif
                                </div>
                            </div>
                            
                            @if(!$loop->last && $statusIndex < count($statuses) - 1)
                                <div style="width: 2px; height: 20px; background: {{ $statusIndex < $currentStatusIndex ? 'var(--' . $info['color'] . '-300)' : 'var(--gray-300)' }}; margin-left: 19px; margin-bottom: var(--space-sm);"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calculator"></i>
                        ملخص الطلب
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-sm);">
                        <span>عدد المواد:</span>
                        <span style="font-weight: 600;">{{ $order->orderItems->count() }} مادة</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-sm);">
                        <span>الكمية:</span>
                        <span style="font-weight: 600;">{{ $order->quantity }} نسخة</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-sm);">
                        <span>الفصل:</span>
                        <span style="font-weight: 600;">{{ $order->semester_text }}</span>
                    </div>
                    <hr style="margin: var(--space-md) 0;">
                    <div style="display: flex; justify-content: space-between; font-size: 1.125rem; font-weight: 700; color: var(--success-600);">
                        <span>المجموع:</span>
                        <span>{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tools"></i>
                        إجراءات
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
                        <a href="{{ route('educational-cards.my-orders') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            العودة للطلبات
                        </a>
                        
                        @if($order->status === 'pending')
                            <a href="{{ route('user.conversations.create') }}?order_id={{ $order->id }}" class="btn btn-primary">
                                <i class="fas fa-comments"></i>
                                التواصل مع الدعم
                            </a>
                        @endif
                        
                        <a href="{{ route('educational-cards.index') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i>
                            طلب دوسية جديدة
                        </a>
                        
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="fas fa-print"></i>
                            طباعة الطلب
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        .btn, .card:last-child {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
    }
    
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