@extends('layouts.admin')

@section('title', 'إنشاء كوبون')
@section('page-title', 'إنشاء كوبون')

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
        إنشاء
    </div>
@endsection

@section('content')
<div class="fade-in" style="direction: rtl;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
        <div>
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm); font-family: 'Cairo', sans-serif;">
                إنشاء كوبون جديد
            </h2>
            <p style="color: var(--admin-secondary-600);">إنشاء كوبون خصم لعملائك</p>
        </div>
        
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
            العودة للكوبونات
        </a>
    </div>

    <div class="grid grid-cols-3" style="gap: var(--space-xl);">
        <!-- Main Form -->
        <div style="grid-column: span 2;">
            <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponForm">
                @csrf
                
                <div class="card" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid var(--admin-secondary-200);">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600)); color: white; border-radius: 8px 8px 0 0;">
                        <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                            <i class="fas fa-ticket-alt"></i>
                            تفاصيل الكوبون
                        </h3>
                    </div>
                    
                    <div class="card-body" style="padding: var(--space-xl);">
                        <div class="grid grid-cols-2" style="gap: var(--space-lg);">
                            <!-- Coupon Code -->
                            <div class="form-group">
                                <label for="code" class="form-label" style="font-weight: 600; color: var(--admin-secondary-700); font-family: 'Cairo', sans-serif;">
                                    كود الكوبون
                                    <span style="color: var(--admin-secondary-400); font-size: 0.75rem;">(اختياري - سيتم إنشاؤه تلقائياً إذا ترك فارغاً)</span>
                                </label>
                                <div style="position: relative;">
                                    <input 
                                        type="text" 
                                        id="code" 
                                        name="code" 
                                        class="form-input" 
                                        value="{{ old('code') }}" 
                                        placeholder="مثال: SUMMER2024"
                                        style="text-transform: uppercase; font-family: monospace; font-weight: 600; padding-left: 100px; text-align: right;"
                                    >
                                    <button 
                                        type="button" 
                                        id="generateCode" 
                                        class="btn btn-sm btn-secondary"
                                        style="position: absolute; left: 4px; top: 4px; bottom: 4px; font-family: 'Cairo', sans-serif;"
                                    >
                                        <i class="fas fa-random" style="margin-left: 5px;"></i>
                                        إنشاء
                                    </button>
                                </div>
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
                                        value="{{ old('amount') }}" 
                                        placeholder="0.00"
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
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                        value="{{ old('min_purchase_amount') }}" 
                                        placeholder="0.00"
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

                            <!-- Valid Months -->
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="valid_months" class="form-label" style="font-weight: 600; color: var(--admin-secondary-700); font-family: 'Cairo', sans-serif;">
                                    صالح لمدة (بالأشهر) <span style="color: var(--error-500);">*</span>
                                </label>
                                <select id="valid_months" name="valid_months" class="form-input" required style="text-align: right; font-family: 'Cairo', sans-serif;">
                                    <option value="">اختر فترة الصلاحية</option>
                                    <option value="1" {{ old('valid_months') == '1' ? 'selected' : '' }}>شهر واحد</option>
                                    <option value="2" {{ old('valid_months') == '2' ? 'selected' : '' }}>شهران</option>
                                    <option value="3" {{ old('valid_months') == '3' ? 'selected' : '' }}>3 أشهر</option>
                                    <option value="6" {{ old('valid_months') == '6' ? 'selected' : '' }}>6 أشهر</option>
                                    <option value="12" {{ old('valid_months') == '12' ? 'selected' : '' }}>سنة واحدة</option>
                                </select>
                                @error('valid_months')
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
                        إنشاء الكوبون
                    </button>
                    
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary" style="font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-times" style="margin-left: 8px;"></i>
                        إلغاء
                    </a>
                </div>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div>
            <!-- Preview Card -->
            <div class="card" style="margin-bottom: var(--space-lg); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, var(--success-500), var(--success-600)); color: white;">
                    <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-eye"></i>
                        معاينة الكوبون
                    </h3>
                </div>
                <div class="card-body">
                    <div id="couponPreview" style="border: 2px dashed var(--admin-primary-300); border-radius: var(--radius-lg); padding: var(--space-lg); text-align: center; background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));">
                        <div style="background: white; border-radius: var(--radius-md); padding: var(--space-lg); box-shadow: var(--shadow-md);">
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 1px; font-family: 'Cairo', sans-serif;">
                                {{ config('app.name') }}
                            </div>
                            <div id="previewCode" style="font-size: 1.5rem; font-weight: 700; color: var(--admin-primary-600); margin-bottom: var(--space-sm); font-family: monospace;">
                                XXXXXXXX
                            </div>
                            <div id="previewAmount" style="font-size: 2rem; font-weight: 900; color: var(--success-600); margin-bottom: var(--space-sm);">
                                $0.00
                            </div>
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-600); font-family: 'Cairo', sans-serif;">
                                كوبون خصم
                            </div>
                            <div id="previewMinPurchase" style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-sm); font-family: 'Cairo', sans-serif;">
                                لا يوجد حد أدنى للشراء
                            </div>
                            <div id="previewValidity" style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs); font-family: 'Cairo', sans-serif;">
                                صالح لمدة: غير محدد
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, var(--warning-500), var(--warning-600)); color: white;">
                    <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-lightbulb"></i>
                        نصائح
                    </h3>
                </div>
                <div class="card-body">
                    <div>
                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md); align-items: flex-start;">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--info-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-info" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: var(--space-xs); font-family: 'Cairo', sans-serif;">أكواد الكوبونات</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600); line-height: 1.5;">
                                    استخدم أكواد سهلة التذكر والكتابة للعملاء
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md); align-items: flex-start;">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--success-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-dollar-sign" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: var(--space-xs); font-family: 'Cairo', sans-serif;">مبلغ الخصم</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600); line-height: 1.5;">
                                    ضع في اعتبارك هوامش الربح عند تحديد مبالغ الخصم
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md); align-items: flex-start;">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--warning-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-clock" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: var(--space-xs); font-family: 'Cairo', sans-serif;">فترة الصلاحية</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600); line-height: 1.5;">
                                    الفترات القصيرة تخلق شعوراً بالإلحاح وتزيد المبيعات
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
                                    الكوبونات العامة يمكن لأي شخص استخدامها، بينما كوبونات المستخدمين المحددين أكثر شخصية
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* إضافة دعم الخط العربي */
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap');

/* تحسينات RTL */
.form-input {
    direction: rtl !important;
    text-align: right !important;
}

.form-input::placeholder {
    text-align: right !important;
    opacity: 0.7;
}

/* تحسينات التصميم */
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
}

.btn {
    transition: all 0.2s ease;
    font-weight: 600;
    border-radius: 8px;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
    border: none;
}

.btn-secondary {
    background: linear-gradient(135deg, var(--admin-secondary-400), var(--admin-secondary-500));
    border: none;
    color: white;
}

/* تحسين شكل الإشعارات */
.error-notification {
    position: fixed;
    top: 20px;
    left: 20px; /* تغيير من right إلى left للعربية */
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

/* تحسين النماذج */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    line-height: 1.4;
}

/* تحسين معاينة الكوبون */
#couponPreview {
    position: relative;
    overflow: hidden;
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

/* تحسين التخطيط للجوال */
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
    var validMonthsSelect = document.getElementById('valid_months');
    var generateCodeBtn = document.getElementById('generateCode');
    
    var previewCode = document.getElementById('previewCode');
    var previewAmount = document.getElementById('previewAmount');
    var previewMinPurchase = document.getElementById('previewMinPurchase');
    var previewValidity = document.getElementById('previewValidity');

    // الترجمات العربية
    var translations = {
        noMinPurchase: 'لا يوجد حد أدنى للشراء',
        minPurchase: 'الحد الأدنى للشراء: $',
        validFor: 'صالح لمدة:',
        notSet: 'غير محدد',
        month: 'شهر',
        months: 'أشهر',
        year: 'سنة',
        validAmountError: 'يرجى إدخال مبلغ خصم صحيح',
        validPeriodError: 'يرجى اختيار فترة صلاحية',
        generating: 'جاري الإنشاء...'
    };

    function generateRandomCode() {
        var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var code = '';
        for (var i = 0; i < 8; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return code;
    }

    function updatePreview() {
        var code = codeInput.value || 'XXXXXXXX';
        previewCode.textContent = code;

        var amount = parseFloat(amountInput.value) || 0;
        previewAmount.textContent = '$' + amount.toFixed(2);

        var minPurchase = parseFloat(minPurchaseInput.value);
        if (minPurchase && minPurchase > 0) {
            previewMinPurchase.textContent = translations.minPurchase + minPurchase.toFixed(2);
        } else {
            previewMinPurchase.textContent = translations.noMinPurchase;
        }

        var validMonths = parseInt(validMonthsSelect.value);
        if (validMonths) {
            var monthText;
            if (validMonths === 1) {
                monthText = translations.month;
            } else if (validMonths === 12) {
                monthText = translations.year;
            } else {
                monthText = translations.months;
            }
            previewValidity.textContent = translations.validFor + ' ' + validMonths + ' ' + monthText;
        } else {
            previewValidity.textContent = translations.validFor + ' ' + translations.notSet;
        }
    }

    if (generateCodeBtn) {
        generateCodeBtn.addEventListener('click', function() {
            codeInput.value = generateRandomCode();
            updatePreview();
            
            // إضافة تأثير بصري
            generateCodeBtn.innerHTML = '<i class="fas fa-check" style="margin-left: 5px;"></i>تم الإنشاء';
            generateCodeBtn.style.background = 'var(--success-500)';
            
            setTimeout(function() {
                generateCodeBtn.innerHTML = '<i class="fas fa-random" style="margin-left: 5px;"></i>إنشاء';
                generateCodeBtn.style.background = '';
            }, 2000);
        });
    }

    if (codeInput) {
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            updatePreview();
        });
    }

    if (amountInput) {
        amountInput.addEventListener('input', updatePreview);
    }

    if (minPurchaseInput) {
        minPurchaseInput.addEventListener('input', updatePreview);
    }

    if (validMonthsSelect) {
        validMonthsSelect.addEventListener('change', updatePreview);
    }

    var form = document.getElementById('couponForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            var amount = parseFloat(amountInput.value);
            var validMonths = parseInt(validMonthsSelect.value);

            if (!amount || amount <= 0) {
                e.preventDefault();
                amountInput.focus();
                showError(translations.validAmountError);
                return;
            }

            if (!validMonths) {
                e.preventDefault();
                validMonthsSelect.focus();
                showError(translations.validPeriodError);
                return;
            }

            // إظهار حالة التحميل
            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-left: 8px;"></i>' + translations.generating;
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

    updatePreview();
});
</script>
@endsection