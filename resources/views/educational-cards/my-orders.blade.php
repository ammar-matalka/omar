@extends('layouts.app')

@section('title', 'طلباتي من البطاقات التعليمية')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list"></i>
                        طلباتي من البطاقات التعليمية
                    </h2>
                    <p class="text-muted mb-0">متابعة حالة وتفاصيل طلباتك</p>
                </div>
                <div>
                    <a href="{{ route('educational-cards.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        طلب جديد
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('educational-cards.search-orders') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">حالة الطلب</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">جميع الحالات</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="generation_id">الجيل</label>
                            <select name="generation_id" id="generation_id" class="form-control">
                                <option value="">جميع الأجيال</option>
                                @if(isset($generations))
                                    @foreach($generations as $generation)
                                        <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                                            {{ $generation->display_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student_name">اسم الطالب</label>
                            <input type="text" name="student_name" id="student_name" class="form-control" 
                                   value="{{ request('student_name') }}" placeholder="البحث بـ اسم الطالب">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i>
                                    بحث
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100 order-card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold text-primary">
                                <i class="fas fa-hashtag"></i>
                                طلب رقم {{ $order->id }}
                            </h6>
                            <span class="badge badge-{{ $order->status_color }} badge-lg">
                                {{ $order->status_text }}
                            </span>
                        </div>
                        <div class="card-body">
                            <!-- Order Basic Info -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">اسم الطالب:</small>
                                    <p class="font-weight-bold mb-1">{{ $order->student_name }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">الجيل:</small>
                                    <p class="mb-1">
                                        <span class="badge badge-secondary">{{ $order->generation->display_name }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">الفصل:</small>
                                    <p class="mb-1">{{ $order->semester_text }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">الكمية:</small>
                                    <p class="mb-1 font-weight-bold">{{ $order->quantity }}</p>
                                </div>
                            </div>

                            <!-- Subjects -->
                            <div class="mb-3">
                                <small class="text-muted">المواد المطلوبة:</small>
                                <div class="mt-1">
                                    @foreach($order->orderItems as $item)
                                        <span class="badge badge-info mr-1 mb-1">{{ $item->subject_name }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Total Amount -->
                            <div class="mb-3 text-center">
                                <div class="bg-success text-white rounded p-2">
                                    <small>المبلغ الإجمالي</small>
                                    <h5 class="mb-0 font-weight-bold">{{ $order->formatted_total }}</h5>
                                </div>
                            </div>

                            <!-- Order Date -->
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i>
                                    تاريخ الطلب: {{ $order->created_at->format('Y-m-d H:i') }}
                                </small>
                            </div>

                            <!-- Admin Notes -->
                            @if($order->admin_notes)
                                <div class="alert alert-info alert-sm">
                                    <small>
                                        <strong>ملاحظة من الإدارة:</strong><br>
                                        {{ $order->admin_notes }}
                                    </small>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    آخر تحديث: {{ $order->updated_at->diffForHumans() }}
                                </small>
                                <a href="{{ route('educational-cards.show-order', $order) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                    عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="d-flex justify-content-center">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-clipboard-list fa-5x text-muted"></i>
            </div>
            <h4 class="text-muted">لا توجد طلبات</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['status', 'generation_id', 'student_name']))
                    لم يتم العثور على طلبات تطابق معايير البحث
                @else
                    لم تقم بطلب أي بطاقات تعليمية بعد
                @endif
            </p>
            <a href="{{ route('educational-cards.index') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                اطلب بطاقات تعليمية الآن
            </a>
        </div>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif
@endsection

@push('styles')
<style>
.order-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.bg-success {
    background: linear-gradient(45deg, #28a745, #20c997) !important;
}

.badge-pending { 
    background-color: #ffc107; 
    color: #212529; 
}

.badge-processing { 
    background-color: #17a2b8; 
    color: white;
}

.badge-completed { 
    background-color: #28a745; 
    color: white;
}

.badge-cancelled { 
    background-color: #dc3545; 
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

.badge-info {
    background-color: #17a2b8;
    color: white;
}

.alert-dismissible {
    animation: slideInRight 0.5s ease;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #6f42c1);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #5a2a9d);
    transform: translateY(-1px);
}

.btn-outline-primary:hover {
    background: linear-gradient(45deg, #007bff, #6f42c1);
    border-color: #007bff;
    transform: translateY(-1px);
}

.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.card-footer {
    border-radius: 0 0 10px 10px !important;
    border-top: 1px solid rgba(0,0,0,.125);
}

.text-primary {
    color: #007bff !important;
}

.text-muted {
    color: #6c757d !important;
}

@media (max-width: 768px) {
    .col-lg-6 {
        margin-bottom: 1rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: start !important;
    }
    
    .badge-lg {
        margin-top: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut();
    }, 5000);
    
    // Add loading animation to search button
    $('form').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        const originalHtml = submitBtn.html();
        
        submitBtn.html(`
            <i class="fas fa-spinner fa-spin"></i>
            جاري البحث...
        `).prop('disabled', true);
        
        // Re-enable button after 3 seconds in case of no redirect
        setTimeout(function() {
            submitBtn.html(originalHtml).prop('disabled', false);
        }, 3000);
    });
    
    // Smooth scroll to top when clicking pagination
    $('.pagination a').on('click', function(e) {
        $('html, body').animate({
            scrollTop: 0
        }, 500);
    });
    
    // Add hover effect to order cards
    $('.order-card').hover(
        function() {
            $(this).addClass('shadow-lg');
        },
        function() {
            $(this).removeClass('shadow-lg');
        }
    );
    
    // Initialize tooltips if available
    if (typeof $().tooltip === 'function') {
        $('[data-toggle="tooltip"]').tooltip();
    }
});
</script>
@endpush