@extends('layouts.admin')

@section('title', 'إنشاء كوبونات متعددة')
@section('page-title', 'إنشاء كوبونات متعددة')

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
        إنشاء متعدد
    </div>
@endsection

@section('content')
<div class="fade-in" style="direction: rtl;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
        <div>
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm); font-family: 'Cairo', sans-serif;">
                إنشاء كوبونات متعددة
            </h2>
            <p style="color: var(--admin-secondary-600); font-family: 'Cairo', sans-serif;">إنشاء عدة كوبونات خصم في نفس الوقت لمستخدمين محددين أو للاستخدام العام</p>
        </div>
        
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary" style="font-family: 'Cairo', sans-serif;">
            <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
            العودة للكوبونات
        </a>
    </div>

    <div class="grid grid-cols-3" style="gap: var(--space-xl);">
        <!-- Main Form -->
        <div style="grid-column: span 2;">
            <form action="{{ route('admin.coupons.storeMultiple') }}" method="POST" id="generateForm">
                @csrf
                
                <!-- Coupon Type Selection -->
                <div class="card" style="margin-bottom: var(--space-lg); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600)); color: white;">
                        <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                            <i class="fas fa-layer-group"></i>
                            نوع الكوبون
                        </h3>
                    </div>
                    
                    <div class="card-body" style="padding: var(--space-xl);">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                            <!-- Specific Users Option -->
                            <div class="option-card active" data-option="specific" style="border: 2px solid var(--admin-primary-500); border-radius: var(--radius-lg); padding: var(--space-lg); cursor: pointer; transition: all var(--transition-fast); background: var(--admin-primary-50);">
                                <div style="text-align: center;">
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600)); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md); box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
                                        <i class="fas fa-users" style="font-size: 1.5rem; color: white;"></i>
                                    </div>
                                    <h4 style="font-weight: 600; margin-bottom: var(--space-sm); font-family: 'Cairo', sans-serif;">مستخدمين محددين</h4>
                                    <p style="color: var(--admin-secondary-600); font-size: 0.875rem; font-family: 'Cairo', sans-serif;">إنشاء كوبونات لمستخدمين مختارين فقط</p>
                                </div>
                                <input type="radio" name="coupon_type" value="specific" id="specific_users" style="display: none;" checked>
                            </div>

                            <!-- General Option -->
                            <div class="option-card" data-option="general" style="border: 2px solid var(--admin-secondary-300); border-radius: var(--radius-lg); padding: var(--space-lg); cursor: pointer; transition: all var(--transition-fast);">
                                <div style="text-align: center;">
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--success-500), var(--success-600)); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md); box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);">
                                        <i class="fas fa-globe" style="font-size: 1.5rem; color: white;"></i>
                                    </div>
                                    <h4 style="font-weight: 600; margin-bottom: var(--space-sm); font-family: 'Cairo', sans-serif;">كوبونات عامة</h4>
                                    <p style="color: var(--admin-secondary-600); font-size: 0.875rem; font-family: 'Cairo', sans-serif;">إنشاء كوبونات يمكن لأي شخص استخدامها</p>
                                </div>
                                <input type="radio" name="coupon_type" value="general" id="general_coupons" style="display: none;">
                                <input type="hidden" name="generate_for_all" value="0" id="generate_for_all_input">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Selection -->
                <div class="card" id="userSelectionCard" style="margin-bottom: var(--space-lg); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--success-500), var(--success-600)); color: white;">
                        <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                            <i class="fas fa-user-check"></i>
                            اختيار المستخدمين
                        </h3>
                    </div>
                    
                    <div class="card-body" style="padding: var(--space-xl);">
                        <div style="margin-bottom: var(--space-md);">
                            <div style="display: flex; gap: var(--space-md); margin-bottom: var(--space-md);">
                                <button type="button" id="selectAllUsers" class="btn btn-sm btn-success" style="font-family: 'Cairo', sans-serif;">
                                    <i class="fas fa-check-square" style="margin-left: 5px;"></i>
                                    اختيار الكل
                                </button>
                                <button type="button" id="deselectAllUsers" class="btn btn-sm btn-warning" style="font-family: 'Cairo', sans-serif;">
                                    <i class="fas fa-square" style="margin-left: 5px;"></i>
                                    إلغاء الكل
                                </button>
                                <span id="selectedCount" style="font-size: 0.875rem; color: var(--admin-secondary-600); line-height: 32px; font-family: 'Cairo', sans-serif;">
                                    0 مستخدم مختار
                                </span>
                            </div>
                        </div>

                        <div style="max-height: 300px; overflow-y: auto; border: 1px solid var(--admin-secondary-200); border-radius: var(--radius-md);">
                            @foreach($users as $user)
                            <label style="display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md); border-bottom: 1px solid var(--admin-secondary-100); cursor: pointer; transition: background-color var(--transition-fast);" 
                                   class="user-option" 
                                   onmouseover="this.style.backgroundColor='var(--admin-primary-50)'" 
                                   onmouseout="this.style.backgroundColor='transparent'">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" style="margin: 0;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 500; font-family: 'Cairo', sans-serif;">{{ $user->name }}</div>
                                    <div style="font-size: 0.875rem; color: var(--admin-secondary-500);">{{ $user->email }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('user_ids')
                            <span style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-sm); display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Coupon Details -->
                <div class="card" style="margin-bottom: var(--space-lg); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--warning-500), var(--warning-600)); color: white;">
                        <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                            <i class="fas fa-ticket-alt"></i>
                            تفاصيل الكوبون
                        </h3>
                    </div>
                    
                    <div class="card-body" style="padding: var(--space-xl);">
                        <div class="grid grid-cols-2" style="gap: var(--space-lg);">
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
                                        placeholder="10.00"
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
                                @error('min_purchase_amount')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Valid Months -->
                            <div class="form-group">
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

                            <!-- Quantity per User -->
                            <div class="form-group">
                                <label for="quantity_per_user" class="form-label" style="font-weight: 600; color: var(--admin-secondary-700); font-family: 'Cairo', sans-serif;">
                                    كوبونات لكل مستخدم <span style="color: var(--error-500);">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="quantity_per_user" 
                                    name="quantity_per_user" 
                                    class="form-input" 
                                    value="{{ old('quantity_per_user', 1) }}" 
                                    min="1"
                                    max="100"
                                    required
                                    style="text-align: right;"
                                >
                                <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs);">
                                    <span id="quantity_description" style="font-family: 'Cairo', sans-serif;">عدد الكوبونات المراد إنشاؤها لكل مستخدم مختار</span>
                                </div>
                                @error('quantity_per_user')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Generation Summary -->
                <div class="card" style="margin-bottom: var(--space-lg); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--info-500), var(--info-600)); color: white;">
                        <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                            <i class="fas fa-calculator"></i>
                            ملخص الإنشاء
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div id="summaryContent" style="background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100)); padding: var(--space-lg); border-radius: var(--radius-md); border: 1px solid var(--admin-secondary-200);">
                            <div style="text-align: center; color: var(--admin-secondary-500); font-family: 'Cairo', sans-serif;">
                                قم بتكوين الخيارات أعلاه لرؤية ملخص الإنشاء
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div style="display: flex; justify-content: space-between; margin-top: var(--space-xl);">
                    <button type="submit" class="btn btn-primary" id="generateBtn" disabled style="font-family: 'Cairo', sans-serif; font-weight: 600;">
                        <i class="fas fa-magic" style="margin-left: 8px;"></i>
                        إنشاء الكوبونات
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
                    <div id="couponPreview" style="border: 2px dashed var(--admin-primary-300); border-radius: var(--radius-lg); padding: var(--space-lg); text-align: center; background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100)); position: relative; overflow: hidden;">
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
                                كوبون عينة
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

            <!-- Statistics -->
            <div class="card" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600)); color: white;">
                    <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; color: white; font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-chart-pie"></i>
                        الإحصائيات الحالية
                    </h3>
                </div>
                <div class="card-body">
                    <div style="space-y: var(--space-md);">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-md);">
                            <span style="color: var(--admin-secondary-600); font-family: 'Cairo', sans-serif;">إجمالي المستخدمين</span>
                            <span style="font-weight: 600;">{{ $users->count() }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-md);">
                            <span style="color: var(--admin-secondary-600); font-family: 'Cairo', sans-serif;">الكوبونات النشطة</span>
                            <span style="font-weight: 600; color: var(--success-600);">
                                {{ \App\Models\Coupon::where('is_used', false)->where('valid_until', '>=', now())->count() }}
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-md);">
                            <span style="color: var(--admin-secondary-600); font-family: 'Cairo', sans-serif;">الكوبونات المُستخدمة</span>
                            <span style="font-weight: 600; color: var(--info-600);">
                                {{ \App\Models\Coupon::where('is_used', true)->count() }}
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0;">
                            <span style="color: var(--admin-secondary-600); font-family: 'Cairo', sans-serif;">إجمالي الخصومات</span>
                            <span style="font-weight: 600; color: var(--success-600);">
                                ${{ number_format(\App\Models\Coupon::where('is_used', true)->sum('amount'), 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

.btn-success {
    background: linear-gradient(135deg, var(--success-500), var(--success-600));
    color: white;
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning-500), var(--warning-600));
    color: white;
}

.option-card {
    transition: all 0.3s ease;
    position: relative;
}

.option-card.active {
    border-color: var(--admin-primary-500) !important;
    background: var(--admin-primary-50);
    transform: scale(1.02);
}

.option-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
}

.user-option input[type="checkbox"]:checked + div {
    background: linear-gradient(135deg, var(--success-500), var(--success-600)) !important;
}

#userSelectionCard.hidden {
    display: none;
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

.error-notification,
.success-notification {
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
    // الترجمات العربية
    var translations = {
        selectedUsersLabel: 'المستخدمين المختارين:',
        couponsPerUserLabel: 'كوبونات لكل مستخدم:',
        discountAmountLabel: 'مبلغ الخصم:',
        validityPeriodLabel: 'فترة الصلاحية:',
        totalCouponsLabel: 'إجمالي الكوبونات',
        totalPotentialDiscountLabel: 'إجمالي الخصم المحتمل',
        couponTypeLabel: 'نوع الكوبون:',
        generalText: 'عام',
        specificUsersText: 'مستخدمين محددين',
        monthText: 'شهر',
        monthsText: 'أشهر',
        yearText: 'سنة',
        minPurchaseText: 'الحد الأدنى للشراء: ,
        noMinPurchaseText: 'لا يوجد حد أدنى للشراء',
        validForText: 'صالح لمدة:',
        validForNotSetText: 'صالح لمدة: غير محدد',
        oneUserSelectedText: 'مستخدم واحد مختار',
        usersSelectedText: 'مستخدم مختار',
        configureSummaryText: 'قم بتكوين الخيارات أعلاه لرؤية ملخص الإنشاء',
        numberCouponsUserText: 'عدد الكوبونات المراد إنشاؤها لكل مستخدم مختار',
        numberGeneralCouponsText: 'عدد الكوبونات العامة المراد إنشاؤها',
        validDiscountAmountText: 'يرجى إدخال مبلغ خصم صحيح',
        selectValidityPeriodText: 'يرجى اختيار فترة صلاحية',
        validQuantityText: 'يرجى إدخال كمية صحيحة',
        selectUserText: 'يرجى اختيار مستخدم واحد على الأقل',
        generatingText: 'جاري الإنشاء...'
    };

    // Elements
    var specificRadio = document.getElementById('specific_users');
    var generalRadio = document.getElementById('general_coupons');
    var generateForAllInput = document.getElementById('generate_for_all_input');
    var userSelectionCard = document.getElementById('userSelectionCard');
    var optionCards = document.querySelectorAll('.option-card');
    var userCheckboxes = document.querySelectorAll('input[name="user_ids[]"]');
    var selectAllBtn = document.getElementById('selectAllUsers');
    var deselectAllBtn = document.getElementById('deselectAllUsers');
    var selectedCount = document.getElementById('selectedCount');
    var generateBtn = document.getElementById('generateBtn');
    
    // Form inputs
    var amountInput = document.getElementById('amount');
    var minPurchaseInput = document.getElementById('min_purchase_amount');
    var validMonthsSelect = document.getElementById('valid_months');
    var quantityInput = document.getElementById('quantity_per_user');
    var quantityDescription = document.getElementById('quantity_description');
    
    // Preview elements
    var previewAmount = document.getElementById('previewAmount');
    var previewMinPurchase = document.getElementById('previewMinPurchase');
    var previewValidity = document.getElementById('previewValidity');
    var summaryContent = document.getElementById('summaryContent');

    // Option card selection
    optionCards.forEach(function(card) {
        card.addEventListener('click', function() {
            var option = this.getAttribute('data-option');
            
            // Remove active class from all cards
            optionCards.forEach(function(c) { c.classList.remove('active'); });
            
            // Add active class to clicked card
            this.classList.add('active');
            
            if (option === 'specific') {
                specificRadio.checked = true;
                generalRadio.checked = false;
                generateForAllInput.value = '0';
                userSelectionCard.classList.remove('hidden');
                quantityDescription.textContent = translations.numberCouponsUserText;
            } else {
                generalRadio.checked = true;
                specificRadio.checked = false;
                generateForAllInput.value = '1';
                userSelectionCard.classList.add('hidden');
                quantityDescription.textContent = translations.numberGeneralCouponsText;
            }
            
            updateSummary();
            validateForm();
        });
    });

    // User selection
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            userCheckboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
            updateSelectedCount();
            updateSummary();
            validateForm();
        });
    }

    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            userCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
            updateSelectedCount();
            updateSummary();
            validateForm();
        });
    }

    userCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            updateSummary();
            validateForm();
        });
    });

    function updateSelectedCount() {
        var checkedBoxes = document.querySelectorAll('input[name="user_ids[]"]:checked');
        var count = checkedBoxes.length;
        if (selectedCount) {
            selectedCount.textContent = count === 1 ? translations.oneUserSelectedText : count + ' ' + translations.usersSelectedText;
        }
    }

    // Form validation
    function validateForm() {
        var amount = parseFloat(amountInput.value);
        var validMonths = parseInt(validMonthsSelect.value);
        var quantity = parseInt(quantityInput.value);
        
        var isValid = true;
        
        // Check required fields
        if (!amount || amount <= 0) isValid = false;
        if (!validMonths) isValid = false;
        if (!quantity || quantity <= 0) isValid = false;
        
        // Check user selection for specific users
        if (specificRadio && specificRadio.checked) {
            var selectedUsers = document.querySelectorAll('input[name="user_ids[]"]:checked');
            if (selectedUsers.length === 0) isValid = false;
        }
        
        if (generateBtn) {
            generateBtn.disabled = !isValid;
            
            if (isValid) {
                generateBtn.classList.remove('btn-secondary');
                generateBtn.classList.add('btn-primary');
            } else {
                generateBtn.classList.remove('btn-primary');
                generateBtn.classList.add('btn-secondary');
            }
        }
    }

    // Update preview
    function updatePreview() {
        // Update amount
        var amount = parseFloat(amountInput.value) || 0;
        if (previewAmount) {
            previewAmount.textContent = ' + amount.toFixed(2);
        }

        // Update min purchase
        var minPurchase = parseFloat(minPurchaseInput.value);
        if (previewMinPurchase) {
            if (minPurchase && minPurchase > 0) {
                previewMinPurchase.textContent = translations.minPurchaseText + minPurchase.toFixed(2);
            } else {
                previewMinPurchase.textContent = translations.noMinPurchaseText;
            }
        }

        // Update validity
        var validMonths = parseInt(validMonthsSelect.value);
        if (previewValidity) {
            if (validMonths) {
                var monthText;
                if (validMonths === 1) {
                    monthText = translations.monthText;
                } else if (validMonths === 12) {
                    monthText = translations.yearText;
                } else {
                    monthText = translations.monthsText;
                }
                previewValidity.textContent = translations.validForText + ' ' + validMonths + ' ' + monthText;
            } else {
                previewValidity.textContent = translations.validForNotSetText;
            }
        }
    }

    // Update summary
    function updateSummary() {
        var amount = parseFloat(amountInput.value) || 0;
        var validMonths = parseInt(validMonthsSelect.value) || 0;
        var quantity = parseInt(quantityInput.value) || 1;
        
        var userCount = 0;
        var totalCoupons = 0;
        var totalValue = 0;
        
        if (generalRadio && generalRadio.checked) {
            userCount = 0;
            totalCoupons = quantity;
            totalValue = amount * quantity;
        } else {
            var selectedUsers = document.querySelectorAll('input[name="user_ids[]"]:checked');
            userCount = selectedUsers.length;
            totalCoupons = userCount * quantity;
            totalValue = amount * totalCoupons;
        }
        
        if (summaryContent && amount > 0 && validMonths > 0 && (generalRadio.checked || userCount > 0)) {
            var monthText;
            if (validMonths === 1) {
                monthText = translations.monthText;
            } else if (validMonths === 12) {
                monthText = translations.yearText;
            } else {
                monthText = translations.monthsText;
            }
            
            var summaryHTML = '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-lg);">';
            summaryHTML += '<div style="text-align: center;">';
            summaryHTML += '<div style="font-size: 2rem; font-weight: 700; color: var(--admin-primary-600); margin-bottom: var(--space-sm);">';
            summaryHTML += totalCoupons;
            summaryHTML += '</div>';
            summaryHTML += '<div style="font-size: 0.875rem; color: var(--admin-secondary-600); font-family: \'Cairo\', sans-serif;">';
            summaryHTML += translations.totalCouponsLabel;
            summaryHTML += '</div>';
            summaryHTML += '</div>';
            
            summaryHTML += '<div style="text-align: center;">';
            summaryHTML += '<div style="font-size: 2rem; font-weight: 700; color: var(--success-600); margin-bottom: var(--space-sm);">';
            summaryHTML += ' + totalValue.toFixed(2);
            summaryHTML += '</div>';
            summaryHTML += '<div style="font-size: 0.875rem; color: var(--admin-secondary-600); font-family: \'Cairo\', sans-serif;">';
            summaryHTML += translations.totalPotentialDiscountLabel;
            summaryHTML += '</div>';
            summaryHTML += '</div>';
            summaryHTML += '</div>';
            
            summaryHTML += '<hr style="margin: var(--space-lg) 0;">';
            
            summaryHTML += '<div style="space-y: var(--space-sm);">';
            summaryHTML += '<div style="display: flex; justify-content: space-between; font-size: 0.875rem; font-family: \'Cairo\', sans-serif;">';
            summaryHTML += '<span>' + translations.couponTypeLabel + '</span>';
            summaryHTML += '<span style="font-weight: 500;">';
            summaryHTML += generalRadio.checked ? translations.generalText : translations.specificUsersText;
            summaryHTML += '</span>';
            summaryHTML += '</div>';
            
            if (!generalRadio.checked) {
                summaryHTML += '<div style="display: flex; justify-content: space-between; font-size: 0.875rem; font-family: \'Cairo\', sans-serif;">';
                summaryHTML += '<span>' + translations.selectedUsersLabel + '</span>';
                summaryHTML += '<span style="font-weight: 500;">' + userCount + '</span>';
                summaryHTML += '</div>';
            }
            
            summaryHTML += '<div style="display: flex; justify-content: space-between; font-size: 0.875rem; font-family: \'Cairo\', sans-serif;">';
            summaryHTML += '<span>' + translations.couponsPerUserLabel + '</span>';
            summaryHTML += '<span style="font-weight: 500;">' + quantity + '</span>';
            summaryHTML += '</div>';
            
            summaryHTML += '<div style="display: flex; justify-content: space-between; font-size: 0.875rem; font-family: \'Cairo\', sans-serif;">';
            summaryHTML += '<span>' + translations.discountAmountLabel + '</span>';
            summaryHTML += '<span style="font-weight: 500;"> + amount.toFixed(2) + '</span>';
            summaryHTML += '</div>';
            
            summaryHTML += '<div style="display: flex; justify-content: space-between; font-size: 0.875rem; font-family: \'Cairo\', sans-serif;">';
            summaryHTML += '<span>' + translations.validityPeriodLabel + '</span>';
            summaryHTML += '<span style="font-weight: 500;">' + validMonths + ' ' + monthText + '</span>';
            summaryHTML += '</div>';
            summaryHTML += '</div>';
            
            summaryContent.innerHTML = summaryHTML;
        } else if (summaryContent) {
            summaryContent.innerHTML = '<div style="text-align: center; color: var(--admin-secondary-500); font-family: \'Cairo\', sans-serif;">' + translations.configureSummaryText + '</div>';
        }
    }

    // Event listeners
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            updatePreview();
            updateSummary();
            validateForm();
        });
    }

    if (minPurchaseInput) {
        minPurchaseInput.addEventListener('input', function() {
            updatePreview();
            updateSummary();
        });
    }

    if (validMonthsSelect) {
        validMonthsSelect.addEventListener('change', function() {
            updatePreview();
            updateSummary();
            validateForm();
        });
    }

    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            updateSummary();
            validateForm();
        });
    }

    // Form submission
    var form = document.getElementById('generateForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            var amount = parseFloat(amountInput.value);
            var validMonths = parseInt(validMonthsSelect.value);
            var quantity = parseInt(quantityInput.value);

            if (!amount || amount <= 0) {
                e.preventDefault();
                amountInput.focus();
                showNotification(translations.validDiscountAmountText, 'error');
                return;
            }

            if (!validMonths) {
                e.preventDefault();
                validMonthsSelect.focus();
                showNotification(translations.selectValidityPeriodText, 'error');
                return;
            }

            if (!quantity || quantity <= 0) {
                e.preventDefault();
                quantityInput.focus();
                showNotification(translations.validQuantityText, 'error');
                return;
            }

            if (specificRadio && specificRadio.checked) {
                var selectedUsers = document.querySelectorAll('input[name="user_ids[]"]:checked');
                if (selectedUsers.length === 0) {
                    e.preventDefault();
                    showNotification(translations.selectUserText, 'error');
                    return;
                }
            }

            // Show loading state
            if (generateBtn) {
                generateBtn.disabled = true;
                generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-left: 8px;"></i> ' + translations.generatingText;
            }
        });
    }

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

    // Initialize all functions
    updateSelectedCount();
    updatePreview();
    updateSummary();
    validateForm();
});
</script>
@endsection