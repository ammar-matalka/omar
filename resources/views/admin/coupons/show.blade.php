@extends('layouts.admin')

@section('title', 'تفاصيل الكوبون')
@section('page-title', 'تفاصيل الكوبون')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        <a href="{{ route('admin.coupons.index') }}" class="breadcrumb-link">الكوبونات</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        {{ $coupon->code }}
    </div>
@endsection

@section('content')
<div class="fade-in" style="direction: rtl;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
        <div>
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm); font-family: 'Cairo', sans-serif;">
                تفاصيل الكوبون
            </h2>
            <div style="display: flex; align-items: center; gap: var(--space-md);">
                <code style="background: var(--admin-primary-50); color: var(--admin-primary-700); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-md); font-weight: 700; font-size: 1.1rem;">
                    {{ $coupon->code }}
                </code>
                
                @if($coupon->is_used)
                    <span class="badge" style="background: linear-gradient(135deg, var(--info-500), var(--info-600)); color: white; padding: 6px 16px; border-radius: 20px; font-size: 0.75rem; font-family: 'Cairo', sans-serif; font-weight: 600;">
                        <i class="fas fa-check-circle" style="margin-left: 5px;"></i>
                        مُستخدم
                    </span>
                @elseif($coupon->valid_until < now())
                    <span class="badge" style="background: linear-gradient(135deg, var(--warning-500), var(--warning-600)); color: white; padding: 6px 16px; border-radius: 20px; font-size: 0.75rem; font-family: 'Cairo', sans-serif; font-weight: 600;">
                        <i class="fas fa-clock" style="margin-left: 5px;"></i>
                        منتهي الصلاحية
                    </span>
                @else
                    <span class="badge" style="background: linear-gradient(135deg, var(--success-500), var(--success-600)); color: white; padding: 6px 16px; border-radius: 20px; font-size: 0.75rem; font-family: 'Cairo', sans-serif; font-weight: 600;">
                        <i class="fas fa-star" style="margin-left: 5px;"></i>
                        نشط
                    </span>
                @endif
            </div>
        </div>
        
        <div style="display: flex; gap: var(--space-md);">
            @if(!$coupon->is_used)
                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning" style="font-family: 'Cairo', sans-serif;">
                    <i class="fas fa-edit" style="margin-left: 8px;"></i>
                    تعديل
                </a>
            @endif
            
            <button type="button" class="btn btn-info copy-btn" data-text="{{ $coupon->code }}" style="font-family: 'Cairo', sans-serif;">
                <i class="fas fa-copy" style="margin-left: 8px;"></i>
                نسخ الكود
            </button>
            
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary" style="font-family: 'Cairo', sans-serif;">
                <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                العودة للكوبونات
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3" style="gap: var(--space-xl);">
        <!-- Main Content -->
        <div style="grid-column: span 2;">
            <!-- Coupon Display Card -->
            <div class="card" style="margin-bottom: var(--space-xl); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); border: 1px solid var(--admin-secondary-200);">
                <div class="card-body" style="padding: var(--space-2xl);">
                    <div style="background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100)); border-radius: var(--radius-xl); padding: var(--space-2xl); text-align: center; position: relative; overflow: hidden;">
                        <!-- Background Pattern -->
                        <div style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"%23ffffff\" opacity=\"0.1\"/></svg>') repeat; animation: float 20s infinite linear;"></div>
                        
                        <div style="position: relative; z-index: 1;">
                            <div style="background: white; border-radius: var(--radius-lg); padding: var(--space-2xl); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); border: 3px dashed var(--admin-primary-300);">
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-500); margin-bottom: var(--space-md); text-transform: uppercase; letter-spacing: 2px; font-family: 'Cairo', sans-serif; font-weight: 600;">
                                    {{ config('app.name') }} - كوبون خصم
                                </div>
                                
                                <div style="font-size: 2.5rem; font-weight: 900; color: var(--admin-primary-600); margin-bottom: var(--space-lg); font-family: monospace; letter-spacing: 3px;">
                                    {{ $coupon->code }}
                                </div>
                                
                                <div style="font-size: 3rem; font-weight: 900; color: var(--success-600); margin-bottom: var(--space-lg);">
                                    ${{ number_format($coupon->amount, 2) }}
                                </div>
                                
                                <div style="font-size: 1rem; color: var(--admin-secondary-600); margin-bottom: var(--space-md); font-family: 'Cairo', sans-serif; font-weight: 600;">
                                    خصم فوري على مشترياتك
                                </div>
                                
                                @if($coupon->min_purchase_amount > 0)
                                <div style="font-size: 0.875rem; color: var(--warning-600); margin-bottom: var(--space-sm); font-family: 'Cairo', sans-serif;">
                                    <i class="fas fa-info-circle" style="margin-left: 5px;"></i>
                                    الحد الأدنى للشراء: ${{ number_format($coupon->min_purchase_amount, 2) }}
                                </div>
                                @endif
                                
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-500); font-family: 'Cairo', sans-serif;">
                                    <i class="fas fa-calendar-alt" style="margin-left: 5px;"></i>
                                    صالح حتى: {{ $coupon->valid_until->format('d M Y') }}
                                </div>
                                
                                @if($coupon->is_used)
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-20deg); background: var(--error-500); color: white; padding: 10px 30px; border-radius: 10px; font-weight: 700; font-size: 1.2rem; opacity: 0.9; font-family: 'Cairo', sans-serif;">
                                    مُستخدم
                                </div>
                                @elseif($coupon->valid_until < now())
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-20deg); background: var(--warning-500); color: white; padding: 10px 30px; border-radius: 10px; font-weight: 700; font-size: 1.2rem; opacity: 0.9; font-family: 'Cairo', sans-serif;">
                                    منتهي الصلاحية
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coupon Details -->
            <div class="card" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600)); color: white;">
                    <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-info-circle"></i>
                        معلومات تفصيلية
                    </h3>
                </div>
                
                <div class="card-body" style="padding: var(--space-xl);">
                    <div class="grid grid-cols-2" style="gap: var(--space-xl);">
                        <div>
                            <div style="margin-bottom: var(--space-lg);">
                                <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">كود الكوبون</label>
                                <div style="font-size: 1.125rem; font-weight: 600; color: var(--admin-secondary-900); font-family: monospace;">
                                    {{ $coupon->code }}
                                </div>
                            </div>
                            
                            <div style="margin-bottom: var(--space-lg);">
                                <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">مبلغ الخصم</label>
                                <div style="font-size: 1.125rem; font-weight: 700; color: var(--success-600);">
                                    ${{ number_format($coupon->amount, 2) }}
                                </div>
                            </div>
                            
                            <div style="margin-bottom: var(--space-lg);">
                                <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">الحد الأدنى للشراء</label>
                                <div style="font-size: 1.125rem; font-weight: 600; color: var(--admin-secondary-900);">
                                    @if($coupon->min_purchase_amount > 0)
                                        ${{ number_format($coupon->min_purchase_amount, 2) }}
                                    @else
                                        <span style="color: var(--admin-secondary-500); font-family: 'Cairo', sans-serif;">لا يوجد حد أدنى</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div style="margin-bottom: var(--space-lg);">
                                <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">المستخدم المخصص</label>
                                <div style="font-size: 1.125rem; font-weight: 600; color: var(--admin-secondary-900);">
                                    @if($coupon->user)
                                        <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--admin-primary-500); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                                {{ strtoupper(substr($coupon->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-family: 'Cairo', sans-serif;">{{ $coupon->user->name }}</div>
                                                <div style="font-size: 0.75rem; color: var(--admin-secondary-500);">{{ $coupon->user->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span style="color: var(--info-600); font-family: 'Cairo', sans-serif;">كوبون عام (أي مستخدم)</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div style="margin-bottom: var(--space-lg);">
                                <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">صالح حتى</label>
                                <div style="font-size: 1.125rem; font-weight: 600; color: var(--admin-secondary-900);">
                                    {{ $coupon->valid_until->format('d M Y, H:i') }}
                                    <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs); font-family: 'Cairo', sans-serif;">
                                        ({{ $coupon->valid_until->diffForHumans() }})
                                    </div>
                                </div>
                            </div>
                            
                            <div style="margin-bottom: var(--space-lg);">
                                <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">الحالة</label>
                                <div>
                                    @if($coupon->is_used)
                                        <span style="background: linear-gradient(135deg, var(--info-500), var(--info-600)); color: white; padding: 4px 12px; border-radius: 15px; font-size: 0.875rem; font-family: 'Cairo', sans-serif; font-weight: 600;">
                                            <i class="fas fa-check-circle" style="margin-left: 5px;"></i>
                                            مُستخدم
                                        </span>
                                    @elseif($coupon->valid_until < now())
                                        <span style="background: linear-gradient(135deg, var(--warning-500), var(--warning-600)); color: white; padding: 4px 12px; border-radius: 15px; font-size: 0.875rem; font-family: 'Cairo', sans-serif; font-weight: 600;">
                                            <i class="fas fa-clock" style="margin-left: 5px;"></i>
                                            منتهي الصلاحية
                                        </span>
                                    @else
                                        <span style="background: linear-gradient(135deg, var(--success-500), var(--success-600)); color: white; padding: 4px 12px; border-radius: 15px; font-size: 0.875rem; font-family: 'Cairo', sans-serif; font-weight: 600;">
                                            <i class="fas fa-star" style="margin-left: 5px;"></i>
                                            نشط
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Usage Information -->
            @if($coupon->is_used && $coupon->order)
            <div class="card" style="margin-bottom: var(--space-lg); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, var(--info-500), var(--info-600)); color: white;">
                    <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-shopping-cart"></i>
                        معلومات الاستخدام
                    </h3>
                </div>
                
                <div class="card-body">
                    <div style="margin-bottom: var(--space-md);">
                        <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">رقم الطلب</label>
                        <a href="{{ route('admin.orders.show', $coupon->order) }}" style="color: var(--admin-primary-600); text-decoration: none; font-weight: 600; font-size: 1.1rem;">
                            #{{ $coupon->order->id }}
                        </a>
                    </div>
                    
                    <div style="margin-bottom: var(--space-md);">
                        <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">تاريخ الاستخدام</label>
                        <div style="font-weight: 600;">{{ $coupon->updated_at->format('d M Y, H:i') }}</div>
                        <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs); font-family: 'Cairo', sans-serif;">
                            {{ $coupon->updated_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <div>
                        <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">مبلغ الطلب</label>
                        <div style="font-weight: 700; color: var(--success-600); font-size: 1.1rem;">
                            ${{ number_format($coupon->order->total, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="card" style="margin-bottom: var(--space-lg); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600)); color: white;">
                    <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-clock"></i>
                        التواريخ المهمة
                    </h3>
                </div>
                
                <div class="card-body">
                    <div style="margin-bottom: var(--space-md);">
                        <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">تاريخ الإنشاء</label>
                        <div style="font-weight: 600;">{{ $coupon->created_at->format('d M Y, H:i') }}</div>
                        <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs); font-family: 'Cairo', sans-serif;">
                            {{ $coupon->created_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <div style="margin-bottom: var(--space-md);">
                        <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">آخر تحديث</label>
                        <div style="font-weight: 600;">{{ $coupon->updated_at->format('d M Y, H:i') }}</div>
                        <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs); font-family: 'Cairo', sans-serif;">
                            {{ $coupon->updated_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <div>
                        <label style="font-size: 0.875rem; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: block; font-family: 'Cairo', sans-serif;">صالح حتى</label>
                        <div style="font-weight: 600; color: {{ $coupon->valid_until < now() ? 'var(--error-600)' : 'var(--success-600)' }};">
                            {{ $coupon->valid_until->format('d M Y, H:i') }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs); font-family: 'Cairo', sans-serif;">
                            {{ $coupon->valid_until->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, var(--warning-500), var(--warning-600)); color: white;">
                    <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-tools"></i>
                        الإجراءات
                    </h3>
                </div>
                
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                        @if(!$coupon->is_used)
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning" style="width: 100%; font-family: 'Cairo', sans-serif;">
                                <i class="fas fa-edit" style="margin-left: 8px;"></i>
                                تعديل الكوبون
                            </a>
                        @endif
                        
                        <button type="button" class="btn btn-info copy-btn" data-text="{{ $coupon->code }}" style="width: 100%; font-family: 'Cairo', sans-serif;">
                            <i class="fas fa-copy" style="margin-left: 8px;"></i>
                            نسخ كود الكوبون
                        </button>
                        
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="delete-form" data-confirm="هل أنت متأكد من حذف هذا الكوبون؟ هذا الإجراء لا يمكن التراجع عنه.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="width: 100%; font-family: 'Cairo', sans-serif;">
                                <i class="fas fa-trash" style="margin-left: 8px;"></i>
                                حذف الكوبون
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap');

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 12px;
    border: 1px solid var(--admin-secondary-200);
    overflow: hidden;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
}

.btn {
    transition: all 0.2s ease;
    font-weight: 600;
    border-radius: 8px;
    border: none;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
}

.btn-secondary {
    background: linear-gradient(135deg, var(--admin-secondary-400), var(--admin-secondary-500));
    color: white;
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning-500), var(--warning-600));
    color: white;
}

.btn-info {
    background: linear-gradient(135deg, var(--info-500), var(--info-600));
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, var(--error-500), var(--error-600));
    color: white;
}

.copy-btn:hover {
    background: linear-gradient(135deg, var(--info-600), var(--info-700)) !important;
}

@keyframes float {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.success-notification,
.error-notification {
    position: fixed;
    top: 20px;
    left: 20px;
    padding: 15px 20px;
    border-radius: 10px;
    color: white;
    z-index: 9999;
    animation: slideInLeft 0.3s ease-out;
    font-family: 'Cairo', sans-serif;
    direction: rtl;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

.success-notification {
    background: linear-gradient(135deg, var(--success-500), var(--success-600));
}

.error-notification {
    background: linear-gradient(135deg, var(--error-500), var(--error-600));
}

@keyframes slideInLeft {
    from {
        transform: translateX(-100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .grid-cols-3 {
        grid-template-columns: 1fr !important;
    }
    
    .grid-cols-2 {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy to clipboard functionality
    var copyButtons = document.querySelectorAll('.copy-btn');
    copyButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var text = this.getAttribute('data-text');
            
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    showNotification('تم نسخ كود الكوبون بنجاح!', 'success');
                    updateButtonState(button, true);
                }).catch(function(err) {
                    console.error('Could not copy text: ', err);
                    fallbackCopyTextToClipboard(text, button);
                });
            } else {
                fallbackCopyTextToClipboard(text, button);
            }
        });
    });

    // Fallback copy function for older browsers
    function fallbackCopyTextToClipboard(text, button) {
        var textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            var successful = document.execCommand('copy');
            if (successful) {
                showNotification('تم نسخ كود الكوبون بنجاح!', 'success');
                updateButtonState(button, true);
            } else {
                showNotification('فشل في نسخ كود الكوبون', 'error');
            }
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
            showNotification('فشل في نسخ كود الكوبون', 'error');
        }
        
        document.body.removeChild(textArea);
    }

    // Update button state after copy
    function updateButtonState(button, success) {
        var originalHTML = button.innerHTML;
        
        if (success) {
            button.innerHTML = '<i class="fas fa-check" style="margin-left: 8px;"></i>تم النسخ!';
            button.style.background = 'linear-gradient(135deg, var(--success-500), var(--success-600))';
        }
        
        setTimeout(function() {
            button.innerHTML = originalHTML;
            button.style.background = '';
        }, 2000);
    }

    // Delete form confirmation
    var deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var confirmMsg = this.getAttribute('data-confirm');
            if (!confirm(confirmMsg)) {
                e.preventDefault();
            }
        });
    });

    // Notification system
    function showNotification(message, type) {
        var notification = document.createElement('div');
        notification.className = type === 'success' ? 'success-notification' : 'error-notification';
        
        var iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
        
        notification.innerHTML = '<div style="display: flex; align-items: center; gap: var(--space-sm);"><i class="' + iconClass + '"></i><span>' + message + '</span><button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer; margin-right: auto;">×</button></div>';
        
        document.body.appendChild(notification);
        
        setTimeout(function() {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
});
</script>
@endsection