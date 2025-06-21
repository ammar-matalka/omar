@extends('layouts.admin')

@section('title', 'تعديل الكوبون')
@section('page-title', 'تعديل الكوبون')

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
        <a href="{{ route('admin.coupons.show', $coupon) }}" class="breadcrumb-link">{{ $coupon->code }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        تعديل
    </div>
@endsection

@section('content')
<div class="fade-in" style="direction: rtl;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
        <div>
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm); font-family: 'Cairo', sans-serif;">
                تعديل الكوبون
            </h2>
            <div style="display: flex; align-items: center; gap: var(--space-md);">
                <code style="background: var(--admin-primary-50); color: var(--admin-primary-700); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-md); font-weight: 600; font-size: 1.1rem;">
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
            <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-info" style="font-family: 'Cairo', sans-serif;">
                <i class="fas fa-eye" style="margin-left: 8px;"></i>
                عرض التفاصيل
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary" style="font-family: 'Cairo', sans-serif;">
                <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                العودة للكوبونات
            </a>
        </div>
    </div>

    @if($coupon->is_used)
    <div class="alert alert-warning" style="background: linear-gradient(135deg, var(--warning-50), var(--warning-100)); border: 1px solid var(--warning-300); border-radius: 12px; padding: var(--space-lg); margin-bottom: var(--space-xl); font-family: 'Cairo', sans-serif; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"%23f59e0b\" opacity=\"0.1\"/></svg>') repeat;"></div>
        <div style="position: relative; z-index: 1;">
            <i class="fas fa-exclamation-triangle" style="color: var(--warning-600); margin-left: 10px; font-size: 1.2rem;"></i>
            <strong>تنبيه:</strong> هذا الكوبون تم استخدامه ولا يمكن تعديله. يمكنك فقط عرض تفاصيله أو حذفه.
        </div>
    </div>
    @else

    <div class="grid grid-cols-3" style="gap: var(--space-xl);">
        <!-- Main Form -->
        <div style="grid-column: span 2;">
            <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" id="couponForm">
                @csrf
                @method('PUT')
                
                <div class="card" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid var(--admin-secondary-200);">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600)); color: white; border-radius: 12px 12px 0 0;">
                        <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                            <i class="fas fa-edit"></i>
                            تعديل تفاصيل الكوبون
                        </h3>
                    </div>
                    
                    <div class="card-body" style="padding: var(--space-xl);">
                        <div class="grid grid-cols-2" style="gap: var(--space-lg);">
                            <!-- Coupon Code -->
                            <div class="form-group">
                                <label for="code" class="form-label" style="font-weight: 600; color: var(--admin-secondary-700); font-family: 'Cairo', sans-serif;">
                                    كود الكوبون <span style="color: var(--error-500);">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="code" 
                                    name="code" 
                                    class="form-input" 
                                    value="{{ old('code', $coupon->code) }}" 
                                    required
                                    style="text-transform: uppercase; font-family: monospace; font-weight: 600; text-align: right;"
                                >
                                @error('code')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div class="form-group">
                                <label for="amount" class="form-label" style="font-weight: 600; color: var(--admin-secondary-700); font-family: 'Cairo', sans-serif;">
                                    مبلغ الخصم <span style="color: var(--error-500);">*</span>
                                </label>
                                <div style="position: relative;">
                                    <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--admin-secondary-500); font-weight: 600;">$</span>
                                    <input 
                                        type="number" 
                                        id="amount" 
                                        name="amount" 
                                        class="form-input" 
                                        value="{{ old('amount', $coupon->amount) }}" 
                                        step="0.01"
                                        min="0.01"
                                        required
                                        style="padding-right: 32px; font-weight: 600; text-align: right;"
                                    >
                                </div>
                                @error('amount')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- User Selection -->
                            <div class="form-group">
                                <label for="user_id" class="form-label" style="font-weight: 600; color: var(--admin-secondary-700); font-family: 'Cairo', sans-serif;">
                                    المستخدم المخصص له
                                </label>
                                <select id="user_id" name="user_id" class="form-input" style="text-align: right; font-family: 'Cairo', sans-serif;">
                                    <option value="">كوبون عام (أي مستخدم)</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $coupon->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs);">
                                    اتركه فارغاً لإنشاء كوبون عام يمكن لأي مستخدم استخدامه
                                </div>
                                @error('user_id')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Minimum Purchase Amount -->
                            <div class="form-group">
                                <label for="min_purchase_amount" class="form-label" style="font-weight: 600; color: var(--admin-secondary-700); font-family: 'Cairo', sans-serif;">
                                    الحد الأدنى للشراء
                                </label>
                                <div style="position: relative;">
                                    <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--admin-secondary-500); font-weight: 600;">$</span>
                                    <input 
                                        type="number" 
                                        id="min_purchase_amount" 
                                        name="min_purchase_amount" 
                                        class="form-input" 
                                        value="{{ old('min_purchase_amount', $coupon->min_purchase_amount) }}" 
                                        step="0.01"
                                        min="0"
                                        style="padding-right: 32px; text-align: right;"
                                    >
                                </div>
                                <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs);">
                                    اتركه فارغاً لعدم وجود حد أدنى للشراء
                                </div>
                                @error('min_purchase_amount')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Valid Until -->
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="valid_until" class="form-label" style="font-weight: 600; color: var(--admin-secondary-700); font-family: 'Cairo', sans-serif;">
                                    صالح حتى <span style="color: var(--error-500);">*</span>
                                </label>
                                <input 
                                    type="datetime-local" 
                                    id="valid_until" 
                                    name="valid_until" 
                                    class="form-input" 
                                    value="{{ old('valid_until', $coupon->valid_until->format('Y-m-d\TH:i')) }}" 
                                    min="{{ now()->format('Y-m-d\TH:i') }}"
                                    required
                                    style="text-align: right;"
                                >
                                <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs);">
                                    تاريخ الانتهاء الحالي: <strong>{{ $coupon->valid_until->format('d M, Y h:i A') }}</strong>
                                </div>
                                @error('valid_until')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div style="display: flex; justify-content: space-between; margin-top: var(--space-xl);">
                    <button type="submit" class="btn btn-primary" style="font-family: 'Cairo', sans-serif; font-weight: 600;">
                        <i class="fas fa-save" style="margin-left: 8px;"></i>
                        تحديث الكوبون
                    </button>
                    
                    <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-secondary" style="font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-times" style="margin-left: 8px;"></i>
                        إلغاء
                    </a>
                </div>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div>
            <!-- Original Coupon Preview -->
            <div class="card" style="margin-bottom: var(--space-lg); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600)); color: white;">
                    <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-history"></i>
                        القيم الأصلية
                    </h3>
                </div>
                <div class="card-body">
                    <div style="border: 2px dashed var(--admin-secondary-300); border-radius: var(--radius-lg); padding: var(--space-lg); text-align: center; background: var(--admin-secondary-50); margin-bottom: var(--space-md);">
                        <div style="background: white; border-radius: var(--radius-md); padding: var(--space-lg); box-shadow: var(--shadow-sm);">
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 1px; font-family: 'Cairo', sans-serif;">
                                {{ config('app.name') }} - النسخة الأصلية
                            </div>
                            <div style="font-size: 1.25rem; font-weight: 700; color: var(--admin-secondary-600); margin-bottom: var(--space-sm); font-family: monospace;">
                                {{ $coupon->code }}
                            </div>
                            <div style="font-size: 1.5rem; font-weight: 900; color: var(--admin-secondary-600); margin-bottom: var(--space-sm);">
                                ${{ number_format($coupon->amount, 2) }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-500); font-family: 'Cairo', sans-serif;">
                                القيم قبل التعديل
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div id="couponPreview" style="border: 2px dashed var(--admin-primary-300); border-radius: var(--radius-lg); padding: var(--space-lg); text-align: center; background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100)); position: relative; overflow: hidden;">
                        <div style="background: white; border-radius: var(--radius-md); padding: var(--space-lg); box-shadow: var(--shadow-md);">
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 1px; font-family: 'Cairo', sans-serif;">
                                {{ config('app.name') }} - المعاينة المباشرة
                            </div>
                            <div id="previewCode" style="font-size: 1.25rem; font-weight: 700; color: var(--admin-primary-600); margin-bottom: var(--space-sm); font-family: monospace;">
                                {{ $coupon->code }}
                            </div>
                            <div id="previewAmount" style="font-size: 1.5rem; font-weight: 900; color: var(--success-600); margin-bottom: var(--space-sm);">
                                ${{ number_format($coupon->amount, 2) }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-600); font-family: 'Cairo', sans-serif;">
                                التحديث المباشر
                            </div>
                            <div id="previewMinPurchase" style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-sm); font-family: 'Cairo', sans-serif;">
                                @if($coupon->min_purchase_amount > 0)
                                    الحد الأدنى للشراء: ${{ number_format($coupon->min_purchase_amount, 2) }}
                                @else
                                    لا يوجد حد أدنى للشراء
                                @endif
                            </div>
                            <div id="previewValidity" style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs); font-family: 'Cairo', sans-serif;">
                                صالح حتى: {{ $coupon->valid_until->format('d M, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Original Details -->
            <div class="card" style="margin-bottom: var(--space-lg); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, var(--info-500), var(--info-600)); color: white;">
                    <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-info-circle"></i>
                        معلومات إضافية
                    </h3>
                </div>
                <div class="card-body">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-sm);">
                            <span style="color: var(--admin-secondary-600); font-size: 0.875rem; font-family: 'Cairo', sans-serif;">تاريخ الإنشاء</span>
                            <span style="font-size: 0.875rem; font-weight: 500;">{{ $coupon->created_at->format('d M, Y') }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-sm);">
                            <span style="color: var(--admin-secondary-600); font-size: 0.875rem; font-family: 'Cairo', sans-serif;">الكود الأصلي</span>
                            <code style="font-size: 0.875rem; background: var(--admin-secondary-100); padding: 4px 8px; border-radius: 6px;">{{ $coupon->code }}</code>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-sm);">
                            <span style="color: var(--admin-secondary-600); font-size: 0.875rem; font-family: 'Cairo', sans-serif;">المبلغ الأصلي</span>
                            <span style="font-size: 0.875rem; font-weight: 500;">${{ number_format($coupon->amount, 2) }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-sm);">
                            <span style="color: var(--admin-secondary-600); font-size: 0.875rem; font-family: 'Cairo', sans-serif;">المستخدم المخصص</span>
                            <span style="font-size: 0.875rem; font-weight: 500; font-family: 'Cairo', sans-serif;">
                                @if($coupon->user)
                                    {{ $coupon->user->name }}
                                @else
                                    كوبون عام
                                @endif
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0;">
                            <span style="color: var(--admin-secondary-600); font-size: 0.875rem; font-family: 'Cairo', sans-serif;">الحالة الحالية</span>
                            @if($coupon->is_used)
                                <span style="background: linear-gradient(135deg, var(--info-500), var(--info-600)); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-family: 'Cairo', sans-serif;">مُستخدم</span>
                            @elseif($coupon->valid_until < now())
                                <span style="background: linear-gradient(135deg, var(--warning-500), var(--warning-600)); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-family: 'Cairo', sans-serif;">منتهي الصلاحية</span>
                            @else
                                <span style="background: linear-gradient(135deg, var(--success-500), var(--success-600)); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-family: 'Cairo', sans-serif;">نشط</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Tips -->
            <div class="card" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, var(--warning-500), var(--warning-600)); color: white;">
                    <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-lightbulb"></i>
                        نصائح التعديل
                    </h3>
                </div>
                <div class="card-body">
                    <div>
                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md); align-items: flex-start;">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--warning-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-exclamation" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: var(--space-xs); font-family: 'Cairo', sans-serif;">الكوبونات المُستخدمة</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600); line-height: 1.5;">
                                    الكوبونات المُستخدمة لا يمكن تعديلها للحفاظ على سجلات المبيعات.
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md); align-items: flex-start;">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--info-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-clock" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: var(--space-xs); font-family: 'Cairo', sans-serif;">تاريخ الانتهاء</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600); line-height: 1.5;">
                                    يمكنك تمديد فترة الصلاحية فقط، ولا يمكن تقصيرها لتكون قبل الوقت الحالي.
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md); align-items: flex-start;">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--success-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-edit" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: var(--space-xs); font-family: 'Cairo', sans-serif;">تغيير الكود</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600); line-height: 1.5;">
                                    تغيير كود الكوبون سيؤدي إلى إنشاء معرف فريد جديد بدلاً من الكود القديم.
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: var(--space-sm); align-items: flex-start;">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--admin-primary-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-users" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: var(--space-xs); font-family: 'Cairo', sans-serif;">تخصيص المستخدم</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600); line-height: 1.5;">
                                    يمكنك تغيير تخصيص المستخدم للكوبونات غير المُستخدمة بأمان.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap');

.form-input {
    direction: rtl !important;
    text-align: right !important;
    border-radius: 8px;
    border: 1px solid var(--admin-secondary-300);
    padding: 12px;
    transition: all 0.2s ease;
}

.form-input:focus {
    border-color: var(--admin-primary-500);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input::placeholder {
    text-align: right !important;
    opacity: 0.7;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 12px;
    border: 1px solid var(--admin-secondary-200);
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
    padding: 12px 20px;
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

.btn-info {
    background: linear-gradient(135deg, var(--info-500), var(--info-600));
    color: white;
}

#couponPreview::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    transform: rotate(45deg);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
    100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
}

.error-notification {
    position: fixed;
    top: 20px;
    left: 20px;
    background: linear-gradient(135deg, var(--error-500), var(--error-600));
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(239, 68, 68, 0.3);
    z-index: 9999;
    animation: slideInLeft 0.3s ease-out;
    font-family: 'Cairo', sans-serif;
    direction: rtl;
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

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    line-height: 1.4;
}

@media (max-width: 768px) {
    .grid-cols-3 {
        grid-template-columns: 1fr !important;
    }
    
    .grid-cols-2 {
        grid-template-columns: 1fr !important;
    }
    
    .card-body {
        padding: var(--space-lg) !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var codeInput = document.getElementById('code');
    var amountInput = document.getElementById('amount');
    var minPurchaseInput = document.getElementById('min_purchase_amount');
    var validUntilInput = document.getElementById('valid_until');
    
    var previewCode = document.getElementById('previewCode');
    var previewAmount = document.getElementById('previewAmount');
    var previewMinPurchase = document.getElementById('previewMinPurchase');
    var previewValidity = document.getElementById('previewValidity');

    // الترجمات العربية
    var translations = {
        noMinPurchase: 'لا يوجد حد أدنى للشراء',
        minPurchase: 'الحد الأدنى للشراء: ,
        validUntil: 'صالح حتى:',
        validAmountError: 'يرجى إدخال مبلغ خصم صحيح',
        futureExpiryError: 'تاريخ الانتهاء يجب أن يكون في المستقبل',
        updating: 'جاري التحديث...',
        updated: 'تم التحديث!'
    };

    function updatePreview() {
        var code = codeInput.value || '{{ $coupon->code }}';
        previewCode.textContent = code;

        var amount = parseFloat(amountInput.value) || {{ $coupon->amount }};
        previewAmount.textContent = ' + amount.toFixed(2);

        var minPurchase = parseFloat(minPurchaseInput.value);
        if (minPurchase && minPurchase > 0) {
            previewMinPurchase.textContent = translations.minPurchase + minPurchase.toFixed(2);
        } else {
            previewMinPurchase.textContent = translations.noMinPurchase;
        }

        var validUntil = validUntilInput.value;
        if (validUntil) {
            var date = new Date(validUntil);
            var options = { year: 'numeric', month: 'short', day: 'numeric' };
            previewValidity.textContent = translations.validUntil + ' ' + date.toLocaleDateString('ar-EG', options);
        } else {
            previewValidity.textContent = translations.validUntil + ' {{ $coupon->valid_until->format("d M, Y") }}';
        }
    }

    // Input event listeners with visual feedback
    if (codeInput) {
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            updatePreview();
            
            // Visual feedback for code changes
            if (this.value !== '{{ $coupon->code }}') {
                this.style.borderColor = 'var(--warning-500)';
                this.style.backgroundColor = 'var(--warning-50)';
            } else {
                this.style.borderColor = '';
                this.style.backgroundColor = '';
            }
        });
    }

    if (amountInput) {
        amountInput.addEventListener('input', function() {
            updatePreview();
            
            // Visual feedback for amount changes
            var originalAmount = {{ $coupon->amount }};
            var currentAmount = parseFloat(this.value) || 0;
            
            if (currentAmount !== originalAmount) {
                this.style.borderColor = 'var(--warning-500)';
                this.style.backgroundColor = 'var(--warning-50)';
            } else {
                this.style.borderColor = '';
                this.style.backgroundColor = '';
            }
        });
    }

    if (minPurchaseInput) {
        minPurchaseInput.addEventListener('input', function() {
            updatePreview();
            
            // Visual feedback for min purchase changes
            var originalMinPurchase = {{ $coupon->min_purchase_amount ?? 0 }};
            var currentMinPurchase = parseFloat(this.value) || 0;
            
            if (currentMinPurchase !== originalMinPurchase) {
                this.style.borderColor = 'var(--warning-500)';
                this.style.backgroundColor = 'var(--warning-50)';
            } else {
                this.style.borderColor = '';
                this.style.backgroundColor = '';
            }
        });
    }

    if (validUntilInput) {
        validUntilInput.addEventListener('change', function() {
            updatePreview();
            
            // Visual feedback for date changes
            var originalDate = '{{ $coupon->valid_until->format("Y-m-d\TH:i") }}';
            if (this.value !== originalDate) {
                this.style.borderColor = 'var(--warning-500)';
                this.style.backgroundColor = 'var(--warning-50)';
            } else {
                this.style.borderColor = '';
                this.style.backgroundColor = '';
            }
        });
    }

    var form = document.getElementById('couponForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            var amount = parseFloat(amountInput.value);
            var validUntil = new Date(validUntilInput.value);
            var now = new Date();

            if (!amount || amount <= 0) {
                e.preventDefault();
                amountInput.focus();
                amountInput.style.borderColor = 'var(--error-500)';
                showError(translations.validAmountError);
                return;
            }

            if (validUntil <= now) {
                e.preventDefault();
                validUntilInput.focus();
                validUntilInput.style.borderColor = 'var(--error-500)';
                showError(translations.futureExpiryError);
                return;
            }

            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-left: 8px;"></i>' + translations.updating;
                
                // Change button style to indicate processing
                submitBtn.style.background = 'linear-gradient(135deg, var(--warning-500), var(--warning-600))';
            }
        });
    }

    function showError(message) {
        var notification = document.createElement('div');
        notification.className = 'error-notification';
        notification.innerHTML = '<div style="display: flex; align-items: center; gap: var(--space-sm);"><i class="fas fa-exclamation-triangle"></i><span>' + message + '</span><button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer; margin-right: auto;">&times;</button></div>';
        
        document.body.appendChild(notification);
        
        setTimeout(function() {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Detect changes and show save reminder
    var hasChanges = false;
    var inputs = [codeInput, amountInput, minPurchaseInput, validUntilInput];
    var originalValues = {
        code: '{{ $coupon->code }}',
        amount: {{ $coupon->amount }},
        min_purchase_amount: {{ $coupon->min_purchase_amount ?? 0 }},
        valid_until: '{{ $coupon->valid_until->format("Y-m-d\TH:i") }}'
    };

    inputs.forEach(function(input) {
        if (input) {
            input.addEventListener('input', function() {
                checkForChanges();
            });
            
            input.addEventListener('change', function() {
                checkForChanges();
            });
        }
    });

    function checkForChanges() {
        var currentValues = {
            code: codeInput ? codeInput.value : originalValues.code,
            amount: amountInput ? parseFloat(amountInput.value) || 0 : originalValues.amount,
            min_purchase_amount: minPurchaseInput ? parseFloat(minPurchaseInput.value) || 0 : originalValues.min_purchase_amount,
            valid_until: validUntilInput ? validUntilInput.value : originalValues.valid_until
        };

        var changed = Object.keys(originalValues).some(function(key) {
            return currentValues[key] !== originalValues[key];
        });

        if (changed && !hasChanges) {
            hasChanges = true;
            showChangeIndicator();
        } else if (!changed && hasChanges) {
            hasChanges = false;
            hideChangeIndicator();
        }
    }

    function showChangeIndicator() {
        var submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn && !submitBtn.querySelector('.pulse-dot')) {
            var pulseDot = document.createElement('span');
            pulseDot.className = 'pulse-dot';
            pulseDot.style.cssText = 'position: absolute; top: -5px; right: -5px; width: 10px; height: 10px; background: var(--error-500); border-radius: 50%; animation: pulse 1.5s infinite;';
            submitBtn.style.position = 'relative';
            submitBtn.appendChild(pulseDot);
            
            // Add pulse animation
            var style = document.createElement('style');
            style.textContent = '@keyframes pulse { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }';
            document.head.appendChild(style);
        }
    }

    function hideChangeIndicator() {
        var submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            var pulseDot = submitBtn.querySelector('.pulse-dot');
            if (pulseDot) {
                pulseDot.remove();
            }
        }
    }

    // Initialize preview
    updatePreview();
});
</script>
@endsection