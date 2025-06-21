@extends('layouts.admin')

@section('title', 'إدارة الكوبونات')
@section('page-title', 'الكوبونات')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    الكوبونات
</div>
@endsection

@section('content')
<div class="fade-in" style="direction: rtl;">
    <!-- Header Actions -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl); gap: var(--space-md);">
        <div>
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm); font-family: 'Cairo', sans-serif;">
                إدارة الكوبونات
            </h2>
            <p style="color: var(--admin-secondary-600); font-family: 'Cairo', sans-serif;">إدارة كوبونات الخصم لعملائك</p>
        </div>

        <div style="display: flex; gap: var(--space-md);">
            <a href="{{ route('admin.coupons.generate') }}" class="btn btn-accent" style="font-family: 'Cairo', sans-serif;">
                <i class="fas fa-magic" style="margin-left: 8px;"></i>
                إنشاء متعدد
            </a>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary" style="font-family: 'Cairo', sans-serif;">
                <i class="fas fa-plus" style="margin-left: 8px;"></i>
                إنشاء كوبون
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-4" style="margin-bottom: var(--space-xl);">
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="background: linear-gradient(135deg, var(--success-500), var(--success-600)); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md);">
                    <i class="fas fa-ticket-alt" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <h3 style="font-size: 2rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-xs);">
                    {{ $activeCoupons->count() }}
                </h3>
                <p style="color: var(--admin-secondary-600); font-weight: 500; font-family: 'Cairo', sans-serif;">الكوبونات النشطة</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600)); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md);">
                    <i class="fas fa-check-circle" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <h3 style="font-size: 2rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-xs);">
                    {{ $usedCoupons->count() }}
                </h3>
                <p style="color: var(--admin-secondary-600); font-weight: 500; font-family: 'Cairo', sans-serif;">الكوبونات المُستخدمة</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="background: linear-gradient(135deg, var(--warning-500), var(--warning-600)); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md);">
                    <i class="fas fa-clock" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <h3 style="font-size: 2rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-xs);">
                    {{ $expiredCoupons->count() }}
                </h3>
                <p style="color: var(--admin-secondary-600); font-weight: 500; font-family: 'Cairo', sans-serif;">الكوبونات المنتهية</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600)); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md);">
                    <i class="fas fa-dollar-sign" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <h3 style="font-size: 2rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-xs);">
                    ${{ number_format($usedCoupons->sum('amount'), 2) }}
                </h3>
                <p style="color: var(--admin-secondary-600); font-weight: 500; font-family: 'Cairo', sans-serif;">إجمالي الخصومات</p>
            </div>
        </div>
    </div>

    <!-- Coupons Tabs -->
    <div class="card">
        <div class="card-header">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <h3 class="card-title" style="font-family: 'Cairo', sans-serif;">
                    <i class="fas fa-ticket-alt" style="margin-left: 8px;"></i>
                    جميع الكوبونات
                </h3>

                <!-- Tab Navigation -->
                <div style="display: flex; gap: var(--space-sm);">
                    <button class="btn btn-sm tab-btn active" data-tab="active" style="font-family: 'Cairo', sans-serif;">
                        النشطة
                        <span class="badge badge-success" style="margin-right: var(--space-xs);">{{ $activeCoupons->count() }}</span>
                    </button>
                    <button class="btn btn-sm tab-btn" data-tab="used" style="font-family: 'Cairo', sans-serif;">
                        المُستخدمة
                        <span class="badge badge-info" style="margin-right: var(--space-xs);">{{ $usedCoupons->count() }}</span>
                    </button>
                    <button class="btn btn-sm tab-btn" data-tab="expired" style="font-family: 'Cairo', sans-serif;">
                        المنتهية
                        <span class="badge badge-warning" style="margin-right: var(--space-xs);">{{ $expiredCoupons->count() }}</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Active Coupons Tab -->
            <div class="tab-content active" id="tab-active">
                @if($activeCoupons->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-family: 'Cairo', sans-serif;">الكود</th>
                                <th style="font-family: 'Cairo', sans-serif;">المبلغ</th>
                                <th style="font-family: 'Cairo', sans-serif;">المستخدم</th>
                                <th style="font-family: 'Cairo', sans-serif;">الحد الأدنى</th>
                                <th style="font-family: 'Cairo', sans-serif;">صالح حتى</th>
                                <th style="font-family: 'Cairo', sans-serif;">تاريخ الإنشاء</th>
                                <th style="font-family: 'Cairo', sans-serif;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeCoupons as $coupon)
                            <tr>
                                <td>
                                    <code style="background: var(--admin-primary-50); color: var(--admin-primary-700); padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-weight: 600;">
                                        {{ $coupon->code }}
                                    </code>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: var(--success-600);">
                                        ${{ number_format($coupon->amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @if($coupon->user)
                                    <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--admin-primary-500); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 600;">
                                            {{ strtoupper(substr($coupon->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 500; font-family: 'Cairo', sans-serif;">{{ $coupon->user->name }}</div>
                                            <div style="font-size: 0.75rem; color: var(--admin-secondary-500);">{{ $coupon->user->email }}</div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="badge badge-info" style="font-family: 'Cairo', sans-serif;">عام</span>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->min_purchase_amount > 0)
                                    ${{ number_format($coupon->min_purchase_amount, 2) }}
                                    @else
                                    <span style="color: var(--admin-secondary-400); font-family: 'Cairo', sans-serif;">لا يوجد حد</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem;">
                                        {{ $coupon->valid_until->format('d M, Y') }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--admin-secondary-500); font-family: 'Cairo', sans-serif;">
                                        {{ $coupon->valid_until->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem;">
                                        {{ $coupon->created_at->format('d M, Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: var(--space-xs);">
                                        <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" style="display: inline;" class="delete-form" data-confirm="هل أنت متأكد من حذف هذا الكوبون؟">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div style="text-align: center; padding: var(--space-2xl); color: var(--admin-secondary-500);">
                    <i class="fas fa-ticket-alt" style="font-size: 3rem; margin-bottom: var(--space-lg); opacity: 0.3;"></i>
                    <h3 style="margin-bottom: var(--space-sm); font-family: 'Cairo', sans-serif;">لا توجد كوبونات نشطة</h3>
                    <p style="font-family: 'Cairo', sans-serif;">أنشئ أول كوبون لبدء تقديم خصومات للعملاء.</p>
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary" style="margin-top: var(--space-md); font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-plus" style="margin-left: 8px;"></i>
                        إنشاء كوبون
                    </a>
                </div>
                @endif
            </div>

            <!-- Used Coupons Tab -->
            <div class="tab-content" id="tab-used">
                @if($usedCoupons->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-family: 'Cairo', sans-serif;">الكود</th>
                                <th style="font-family: 'Cairo', sans-serif;">المبلغ</th>
                                <th style="font-family: 'Cairo', sans-serif;">المستخدم</th>
                                <th style="font-family: 'Cairo', sans-serif;">الطلب</th>
                                <th style="font-family: 'Cairo', sans-serif;">تاريخ الاستخدام</th>
                                <th style="font-family: 'Cairo', sans-serif;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usedCoupons as $coupon)
                            <tr>
                                <td>
                                    <code style="background: var(--admin-secondary-100); color: var(--admin-secondary-700); padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-weight: 600;">
                                        {{ $coupon->code }}
                                    </code>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: var(--success-600);">
                                        ${{ number_format($coupon->amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @if($coupon->user)
                                    <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--admin-primary-500); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 600;">
                                            {{ strtoupper(substr($coupon->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 500; font-family: 'Cairo', sans-serif;">{{ $coupon->user->name }}</div>
                                            <div style="font-size: 0.75rem; color: var(--admin-secondary-500);">{{ $coupon->user->email }}</div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="badge badge-info" style="font-family: 'Cairo', sans-serif;">عام</span>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->order)
                                    <a href="{{ route('admin.orders.show', $coupon->order) }}" style="color: var(--admin-primary-600); text-decoration: none; font-weight: 500;">
                                        #{{ $coupon->order->id }}
                                    </a>
                                    @else
                                    <span style="color: var(--admin-secondary-400); font-family: 'Cairo', sans-serif;">غير متوفر</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem;">
                                        {{ $coupon->updated_at->format('d M, Y H:i') }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--admin-secondary-500); font-family: 'Cairo', sans-serif;">
                                        {{ $coupon->updated_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div style="text-align: center; padding: var(--space-2xl); color: var(--admin-secondary-500);">
                    <i class="fas fa-check-circle" style="font-size: 3rem; margin-bottom: var(--space-lg); opacity: 0.3;"></i>
                    <h3 style="margin-bottom: var(--space-sm); font-family: 'Cairo', sans-serif;">لا توجد كوبونات مُستخدمة</h3>
                    <p style="font-family: 'Cairo', sans-serif;">ستظهر الكوبونات المُستخدمة هنا عندما يبدأ العملاء في استردادها.</p>
                </div>
                @endif
            </div>

            <!-- Expired Coupons Tab -->
            <div class="tab-content" id="tab-expired">
                @if($expiredCoupons->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-family: 'Cairo', sans-serif;">الكود</th>
                                <th style="font-family: 'Cairo', sans-serif;">المبلغ</th>
                                <th style="font-family: 'Cairo', sans-serif;">المستخدم</th>
                                <th style="font-family: 'Cairo', sans-serif;">تاريخ الانتهاء</th>
                                <th style="font-family: 'Cairo', sans-serif;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expiredCoupons as $coupon)
                            <tr style="opacity: 0.7;">
                                <td>
                                    <code style="background: var(--warning-50); color: var(--warning-700); padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-weight: 600;">
                                        {{ $coupon->code }}
                                    </code>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: var(--admin-secondary-600);">
                                        ${{ number_format($coupon->amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @if($coupon->user)
                                    <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--admin-secondary-400); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 600;">
                                            {{ strtoupper(substr($coupon->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 500; font-family: 'Cairo', sans-serif;">{{ $coupon->user->name }}</div>
                                            <div style="font-size: 0.75rem; color: var(--admin-secondary-500);">{{ $coupon->user->email }}</div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="badge badge-secondary" style="font-family: 'Cairo', sans-serif;">عام</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem; color: var(--warning-600);">
                                        {{ $coupon->valid_until->format('d M, Y') }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--admin-secondary-500); font-family: 'Cairo', sans-serif;">
                                        {{ $coupon->valid_until->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: var(--space-xs);">
                                        <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" style="display: inline;" class="delete-form" data-confirm="هل أنت متأكد من حذف هذا الكوبون المنتهي الصلاحية؟">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div style="text-align: center; padding: var(--space-2xl); color: var(--admin-secondary-500);">
                    <i class="fas fa-clock" style="font-size: 3rem; margin-bottom: var(--space-lg); opacity: 0.3;"></i>
                    <h3 style="margin-bottom: var(--space-sm); font-family: 'Cairo', sans-serif;">لا توجد كوبونات منتهية الصلاحية</h3>
                    <p style="font-family: 'Cairo', sans-serif;">ستظهر الكوبونات منتهية الصلاحية هنا عندما تتجاوز تاريخ انتهاء صلاحيتها.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap');

.tab-btn {
    border: 1px solid var(--admin-secondary-300);
    background: white;
    color: var(--admin-secondary-700);
}

.tab-btn.active {
    background: var(--admin-primary-600);
    color: white;
    border-color: var(--admin-primary-600);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.table-responsive {
    overflow-x: auto;
}

.btn {
    transition: all 0.2s ease;
    border-radius: 6px;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
    border: none;
}

.btn-accent {
    background: linear-gradient(135deg, var(--warning-500), var(--warning-600));
    border: none;
    color: white;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 10px;
    border: 1px solid var(--admin-secondary-200);
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .grid-cols-4 {
        grid-template-columns: repeat(2, 1fr);
    }

    .table {
        font-size: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to clicked button and corresponding content
            this.classList.add('active');
            document.getElementById(`tab-${targetTab}`).classList.add('active');
        });
    });

    // Delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection