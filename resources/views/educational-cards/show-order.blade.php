@extends('layouts.app')

@section('title', 'تفاصيل الطلب #' . $order->id)

@section('content')
<div class="container">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="font-weight-bold text-primary">
                        <i class="fas fa-file-alt"></i>
                        تفاصيل الطلب #{{ $order->id }}
                    </h2>
                    <p class="text-muted mb-0">عرض جميع تفاصيل طلبك</p>
                </div>
                <div>
                    <a href="{{ route('educational-cards.my-orders') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للطلبات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Details -->
        <div class="col-lg-8">
            <!-- Order Status -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-{{ $order->status_color }} text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        حالة الطلب
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="text-{{ $order->status_color }} mb-2">
                                <i class="fas fa-{{ $order->status == 'completed' ? 'check-circle' : ($order->status == 'processing' ? 'cog fa-spin' : ($order->status == 'cancelled' ? 'times-circle' : 'clock')) }}"></i>
                                {{ $order->status_text }}
                            </h4>
                            <p class="text-muted mb-0">
                                @switch($order->status)
                                    @case('pending')
                                        طلبك قيد المراجعة وسيتم الرد عليك قريباً
                                        @break
                                    @case('processing')
                                        جاري تحضير طلبك
                                        @break
                                    @case('completed')
                                        تم تسليم طلبك بنجاح
                                        @break
                                    @case('cancelled')
                                        تم إلغاء طلبك
                                        @break
                                    @default
                                        حالة غير محددة
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            @if($order->status == 'completed')
                                <div class="text-success">
                                    <i class="fas fa-check-circle fa-4x"></i>
                                </div>
                            @elseif($order->status == 'processing')
                                <div class="text-info">
                                    <i class="fas fa-cog fa-4x fa-spin"></i>
                                </div>
                            @elseif($order->status == 'cancelled')
                                <div class="text-danger">
                                    <i class="fas fa-times-circle fa-4x"></i>
                                </div>
                            @else
                                <div class="text-warning">
                                    <i class="fas fa-clock fa-4x"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($order->admin_notes)
                        <hr>
                        <div class="alert alert-info">
                            <strong><i class="fas fa-comment"></i> ملاحظة من الإدارة:</strong><br>
                            {{ $order->admin_notes }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info"></i>
                        معلومات الطلب
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">رقم الطلب:</td>
                                    <td>{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">اسم الطالب:</td>
                                    <td>{{ $order->student_name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">الجيل:</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $order->generation->display_name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">الفصل:</td>
                                    <td>{{ $order->semester_text }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">الكمية:</td>
                                    <td>{{ $order->quantity }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">رقم الهاتف:</td>
                                    <td>{{ $order->phone ?: 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">تاريخ الطلب:</td>
                                    <td>{{ $order->created_at->format('Y/m/d H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">آخر تحديث:</td>
                                    <td>{{ $order->updated_at->format('Y/m/d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($order->address)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="font-weight-bold">العنوان:</h6>
                                <p class="text-muted">{{ $order->address }}</p>
                            </div>
                        </div>
                    @endif

                    @if($order->notes)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="font-weight-bold">ملاحظاتك:</h6>
                                <div class="alert alert-light">
                                    {{ $order->notes }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        المواد المطلوبة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>اسم المادة</th>
                                    <th>سعر الوحدة</th>
                                    <th>الكمية</th>
                                    <th>المجموع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold">{{ $item->subject_name }}</span>
                                        </td>
                                        <td>{{ $item->formatted_price }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="font-weight-bold text-success">{{ $item->formatted_subtotal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th colspan="3" class="text-right">المجموع الكلي:</th>
                                    <th class="text-success h5">{{ $order->formatted_total }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator"></i>
                        ملخص الطلب
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <span class="text-muted">عدد المواد:</span>
                        <h4 class="text-primary">{{ $order->orderItems->count() }}</h4>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">الكمية الإجمالية:</span>
                        <h4 class="text-info">{{ $order->quantity }}</h4>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">المبلغ الإجمالي:</span>
                        <h3 class="text-success font-weight-bold">{{ $order->formatted_total }}</h3>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i>
                        تاريخ الطلب
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">تم إنشاء الطلب</h6>
                                <p class="timeline-text">{{ $order->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                        </div>

                        @if($order->status != 'pending')
                            <div class="timeline-item {{ $order->status == 'processing' || $order->status == 'completed' ? 'active' : '' }}">
                                <div class="timeline-marker {{ $order->status == 'processing' || $order->status == 'completed' ? 'bg-info' : 'bg-secondary' }}"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">
                                        @if($order->status == 'cancelled')
                                            تم إلغاء الطلب
                                        @else
                                            بدء المعالجة
                                        @endif
                                    </h6>
                                    <p class="timeline-text">{{ $order->updated_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($order->status == 'completed')
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">تم إكمال الطلب</h6>
                                    <p class="timeline-text">{{ $order->updated_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs"></i>
                        إجراءات
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button onclick="window.print()" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-print"></i>
                            طباعة تفاصيل الطلب
                        </button>
                        
                        @if($order->status == 'pending')
                            <button type="button" class="btn btn-outline-warning btn-block" 
                                    data-toggle="modal" data-target="#contactModal">
                                <i class="fas fa-envelope"></i>
                                تواصل مع الإدارة
                            </button>
                        @endif

                        <a href="{{ route('educational-cards.index') }}" class="btn btn-outline-success btn-block">
                            <i class="fas fa-plus"></i>
                            طلب جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-envelope"></i>
                    تواصل مع الإدارة
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>للاستفسار حول طلبك #{{ $order->id }}، يمكنك التواصل معنا عبر:</p>
                <div class="list-group">
                    <a href="mailto:support@example.com" class="list-group-item list-group-item-action">
                        <i class="fas fa-envelope text-primary"></i>
                        البريد الإلكتروني: support@example.com
                    </a>
                    <a href="tel:+962123456789" class="list-group-item list-group-item-action">
                        <i class="fas fa-phone text-success"></i>
                        الهاتف: +962 12 345 6789
                    </a>
                    @if(Route::has('user.conversations.create'))
                        <a href="{{ route('user.conversations.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-comments text-info"></i>
                            إرسال رسالة عبر النظام
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -23px;
    top: 20px;
    height: calc(100% + 10px);
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item.active:not(:last-child)::before {
    background-color: #28a745;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item.active .timeline-marker {
    box-shadow: 0 0 0 2px #28a745;
}

.timeline-title {
    font-size: 0.9rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0;
}

@media print {
    .btn, .modal, .card-header {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    body {
        background: white !important;
    }
}

.badge-secondary {
    background-color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Smooth scroll for timeline
    $('.timeline-item').each(function(index) {
        $(this).delay(index * 200).animate({
            opacity: 1
        }, 500);
    });
});
</script>
@endpush