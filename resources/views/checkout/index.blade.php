@extends('layouts.app')

@section('title', __('الدفع') . ' - ' . config('app.name'))

@push('styles')
<style>
    /* RTL Direction */
    body {
        direction: rtl;
        text-align: right;
    }
    
    .checkout-container {
        padding: var(--space-2xl) 0;
        background: var(--surface-variant);
        min-height: 80vh;
    }
    
    .checkout-header {
        text-align: center;
        margin-bottom: var(--space-2xl);
    }
    
    .checkout-title {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .checkout-subtitle {
        color: var(--on-surface-variant);
        font-size: 1.125rem;
    }
    
    .checkout-steps {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: var(--space-lg);
        margin-bottom: var(--space-2xl);
    }
    
    .step {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-lg);
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 2px solid var(--border-color);
        color: var(--on-surface-variant);
        font-weight: 500;
        transition: all var(--transition-fast);
        flex-direction: row-reverse; /* Reverse for RTL */
    }
    
    .step.active {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border-color: var(--primary-500);
        box-shadow: var(--shadow-md);
    }
    
    .step.completed {
        background: var(--success-50);
        color: var(--success-700);
        border-color: var(--success-200);
    }
    
    .step-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: currentColor;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        color: var(--surface);
        font-weight: 600;
    }
    
    .step.active .step-icon {
        background: white;
        color: var(--primary-600);
    }
    
    .checkout-content {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: var(--space-2xl);
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .checkout-form {
        background: var(--surface);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }
    
    .form-section {
        padding: var(--space-xl);
        border-bottom: 1px solid var(--border-color);
    }
    
    .form-section:last-child {
        border-bottom: none;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-direction: row-reverse; /* Reverse for RTL */
    }
    
    .section-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-600);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
    }
    
    .form-group {
        margin-bottom: var(--space-lg);
    }
    
    .form-label {
        display: block;
        margin-bottom: var(--space-sm);
        font-weight: 600;
        color: var(--on-surface);
        font-size: 0.875rem;
    }
    
    .required {
        color: var(--error-500);
    }
    
    .form-input {
        width: 100%;
        padding: var(--space-md) var(--space-lg);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 1rem;
        transition: all var(--transition-fast);
        text-align: right; /* RTL text alignment */
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        transform: translateY(-2px);
    }
    
    .form-input.error {
        border-color: var(--error-500);
        background: var(--error-50);
    }
    
    .error-message {
        color: var(--error-600);
        font-size: 0.75rem;
        margin-top: var(--space-xs);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        flex-direction: row-reverse; /* Reverse for RTL */
        justify-content: flex-end; /* Align to right */
    }
    
    .coupon-section {
        background: var(--accent-50);
        border: 1px solid var(--accent-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .coupon-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: var(--space-md);
        flex-direction: row-reverse; /* Reverse for RTL */
    }
    
    .coupon-title {
        font-weight: 600;
        color: var(--accent-700);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-direction: row-reverse; /* Reverse for RTL */
    }
    
    .coupon-toggle {
        background: none;
        border: none;
        color: var(--accent-600);
        cursor: pointer;
        font-weight: 500;
        text-decoration: underline;
    }
    
    .coupon-form {
        display: flex;
        gap: var(--space-sm);
        flex-direction: row-reverse; /* Reverse for RTL */
    }
    
    .coupon-input {
        flex: 1;
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--accent-300);
        border-radius: var(--radius-md);
        background: var(--surface);
        text-align: right; /* RTL text alignment */
    }
    
    .coupon-btn {
        padding: var(--space-sm) var(--space-lg);
        background: var(--accent-500);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .coupon-btn:hover {
        background: var(--accent-600);
        transform: translateY(-1px);
    }
    
    .applied-coupon {
        background: var(--success-50);
        border: 1px solid var(--success-200);
        border-radius: var(--radius-md);
        padding: var(--space-md);
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: var(--space-md);
        flex-direction: row-reverse; /* Reverse for RTL */
    }
    
    .coupon-info {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        color: var(--success-700);
        font-weight: 500;
        flex-direction: row-reverse; /* Reverse for RTL */
    }
    
    .remove-coupon {
        background: none;
        border: none;
        color: var(--error-600);
        cursor: pointer;
        font-size: 0.875rem;
        padding: var(--space-xs);
    }
    
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
    }
    
    .payment-option {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-lg);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: all var(--transition-fast);
        flex-direction: row-reverse; /* Reverse for RTL */
    }
    
    .payment-option:hover {
        border-color: var(--primary-300);
        background: var(--primary-50);
    }
    
    .payment-option.selected {
        border-color: var(--primary-500);
        background: var(--primary-50);
    }
    
    .payment-radio {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-color);
        border-radius: 50%;
        position: relative;
        transition: all var(--transition-fast);
    }
    
    .payment-option.selected .payment-radio {
        border-color: var(--primary-500);
    }
    
    .payment-option.selected .payment-radio::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        background: var(--primary-500);
        border-radius: 50%;
    }
    
    .payment-info {
        flex: 1;
    }
    
    .payment-title {
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
    }
    
    .payment-description {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .payment-icon {
        color: var(--primary-600);
        font-size: 1.5rem;
    }
    
    .place-order-btn {
        width: 100%;
        padding: var(--space-lg) var(--space-xl);
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-size: 1.125rem;
        font-weight: 700;
        cursor: pointer;
        transition: all var(--transition-normal);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        box-shadow: var(--shadow-lg);
        margin-top: var(--space-xl);
        flex-direction: row-reverse; /* Reverse for RTL */
    }
    
    .place-order-btn:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--success-600), var(--success-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
    }
    
    .place-order-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .order-summary {
        background: var(--surface);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        position: sticky;
        top: var(--space-xl);
        height: fit-content;
    }
    
    .summary-header {
        padding: var(--space-xl);
        border-bottom: 1px solid var(--border-color);
    }
    
    .summary-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-direction: row-reverse; /* Reverse for RTL */
    }
    
    .order-items {
        padding: var(--space-lg);
        border-bottom: 1px solid var(--border-color);
        max-height: 300px;
        overflow-y: auto;
    }
    
    .order-item {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
        padding-bottom: var(--space-lg);
        border-bottom: 1px solid var(--border-color);
        flex-direction: row-reverse; /* Reverse for RTL */
    }
    
    .order-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .item-image {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-md);
        overflow: hidden;
        background: var(--gray-100);
        flex-shrink: 0;
    }
    
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .item-details {
        flex: 1;
        min-width: 0;
    }
    
    .item-name {
        font-weight: 600;
        color: var(--on-surface);
        font-size: 0.875rem;
        margin-bottom: var(--space-xs);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .item-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        color: var(--on-surface-variant);
    }
    
    .item-quantity {
        background: var(--surface-variant);
        padding: 2px 6px;
        border-radius: var(--radius-sm);
        font-weight: 500;
    }
    
    .item-price {
        font-weight: 600;
        color: var(--primary-600);
    }
    
    .summary-calculations {
        padding: var(--space-xl);
    }
    
    .calc-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-md);
        font-size: 0.875rem;
    }
    
    .calc-label {
        color: var(--on-surface-variant);
    }
    
    .calc-value {
        font-weight: 600;
        color: var(--on-surface);
    }
    
    .discount-row {
        color: var(--success-600);
    }
    
    .total-row {
        padding-top: var(--space-md);
        border-top: 2px solid var(--border-color);
        margin-top: var(--space-md);
        font-size: 1.125rem;
        font-weight: 700;
    }
    
    .total-label {
        color: var(--on-surface);
    }
    
    .total-value {
        color: var(--primary-600);
        font-size: 1.5rem;
    }
    
    .loading-spinner {
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }
    
    .coupon-suggestion:hover {
        background: var(--primary-50) !important;
        border-color: var(--primary-300) !important;
        color: var(--primary-600) !important;
    }
    
    @media (max-width: 768px) {
        .checkout-content {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .checkout-steps {
            flex-direction: column;
            gap: var(--space-md);
        }
        
        .step {
            width: 100%;
            justify-content: center;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .checkout-title {
            font-size: 2rem;
        }
        
        .order-summary {
            position: static;
        }
    }
    
    @media (max-width: 480px) {
        .checkout-container {
            padding: var(--space-lg) 0;
        }
        
        .form-section {
            padding: var(--space-lg);
        }
        
        .coupon-form {
            flex-direction: column;
        }
        
        .order-items {
            padding: var(--space-md);
        }
        
        .summary-calculations {
            padding: var(--space-lg);
        }
    }
</style>
@endpush

@section('content')
<div class="container checkout-container">
    <!-- Checkout Header -->
    <div class="checkout-header">
        <h1 class="checkout-title">{{ __('الدفع') }}</h1>
        <p class="checkout-subtitle">{{ __('أكمل طلبك') }}</p>
    </div>
    
    <!-- Checkout Steps -->
    <div class="checkout-steps fade-in">
        <div class="step completed">
            <div class="step-icon">
                <i class="fas fa-check"></i>
            </div>
            <span>{{ __('مراجعة السلة') }}</span>
        </div>
        
        <div class="step active">
            <div class="step-icon">2</div>
            <span>{{ __('الشحن والدفع') }}</span>
        </div>
        
        <div class="step">
            <div class="step-icon">3</div>
            <span>{{ __('اكتمال الطلب') }}</span>
        </div>
    </div>
    
    <div class="checkout-content">
        <!-- Checkout Form -->
        <div class="checkout-form fade-in">
            <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm">
                @csrf
                
                <!-- Shipping Information -->
                <div class="form-section">
                    <h2 class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        {{ __('معلومات الشحن') }}
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone_number" class="form-label">
                                {{ __('رقم الهاتف') }} <span class="required">*</span>
                            </label>
                            <input 
                                type="tel" 
                                id="phone_number" 
                                name="phone_number" 
                                class="form-input @error('phone_number') error @enderror"
                                value="{{ old('phone_number', Auth::user()->phone) }}"
                                required
                                placeholder="{{ __('أدخل رقم هاتفك') }}"
                            >
                            @error('phone_number')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="shipping_address" class="form-label">
                            {{ __('عنوان الشحن') }} <span class="required">*</span>
                        </label>
                        <textarea 
                            id="shipping_address" 
                            name="shipping_address" 
                            class="form-input @error('shipping_address') error @enderror"
                            rows="3"
                            required
                            placeholder="{{ __('أدخل عنوان الشحن الكامل') }}"
                        >{{ old('shipping_address', Auth::user()->address) }}</textarea>
                        @error('shipping_address')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="billing_address" class="form-label">
                            {{ __('عنوان الفاتورة') }} <span class="required">*</span>
                        </label>
                        <textarea 
                            id="billing_address" 
                            name="billing_address" 
                            class="form-input @error('billing_address') error @enderror"
                            rows="3"
                            required
                            placeholder="{{ __('أدخل عنوان الفاتورة') }}"
                        >{{ old('billing_address', Auth::user()->address) }}</textarea>
                        @error('billing_address')
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <div style="margin-top: var(--space-sm);">
                            <label style="display: flex; align-items: center; gap: var(--space-sm); font-size: 0.875rem; color: var(--on-surface-variant);">
                                <input type="checkbox" id="sameAsShipping" onchange="copyShippingAddress()">
                                {{ __('نفس عنوان الشحن') }}
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Coupon Section -->
                @if($availableCoupons->count() > 0 || !$appliedCoupon)
                    <div class="form-section">
                        <div class="coupon-section">
                            <div class="coupon-header">
                                <h3 class="coupon-title">
                                    <i class="fas fa-ticket-alt"></i>
                                    {{ __('كوبون خصم') }}
                                </h3>
                                <button type="button" class="coupon-toggle" onclick="toggleCouponForm()">
                                    {{ __('لديك كوبون؟') }}
                                </button>
                            </div>
                            
                            @if($appliedCoupon)
                                <div class="applied-coupon">
                                    <div class="coupon-info">
                                        <i class="fas fa-check-circle"></i>
                                        {{ __('تم تطبيق الكوبون') }}: {{ $appliedCoupon->code }}
                                        (-${{ number_format($discountAmount, 2) }})
                                    </div>
                                    <form method="POST" action="{{ route('checkout.removeCoupon') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="remove-coupon">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="coupon-form" id="couponForm" style="display: none;">
                                    <input 
                                        type="text" 
                                        class="coupon-input" 
                                        name="coupon_code" 
                                        placeholder="{{ __('أدخل كود الكوبون') }}"
                                        id="couponInput"
                                    >
                                    <button type="button" class="coupon-btn" onclick="applyCoupon()">
                                        {{ __('تطبيق') }}
                                    </button>
                                </div>
                                
                                @if($availableCoupons->count() > 0)
                                    <div style="margin-top: var(--space-md);">
                                        <p style="font-size: 0.875rem; color: var(--on-surface-variant); margin-bottom: var(--space-sm);">
                                            {{ __('الكوبونات المتاحة:') }}
                                        </p>
                                        @foreach($availableCoupons as $coupon)
                                            <button 
                                                type="button" 
                                                class="coupon-suggestion" 
                                                onclick="useCoupon('{{ $coupon->code }}')"
                                                style="background: var(--surface); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: var(--space-xs) var(--space-sm); margin-right: var(--space-sm); margin-bottom: var(--space-xs); font-size: 0.75rem; cursor: pointer;"
                                            >
                                                {{ $coupon->code }} (-${{ number_format($coupon->amount, 2) }})
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
                
                <!-- Payment Method -->
                <div class="form-section">
                    <h2 class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        {{ __('طريقة الدفع') }}
                    </h2>
                    
                    <div class="payment-methods">
                        <div class="payment-option selected" onclick="selectPayment(this)" data-method="cash">
                            <div class="payment-radio"></div>
                            <div class="payment-info">
                                <div class="payment-title">{{ __('الدفع عند الاستلام') }}</div>
                                <div class="payment-description">{{ __('ادفع عند استلام طلبك') }}</div>
                            </div>
                            <i class="payment-icon fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    
                    <input type="hidden" name="payment_method" value="cash" id="paymentMethod">
                </div>
                
                <!-- Place Order Button -->
                <div class="form-section">
                    <button type="submit" class="place-order-btn" id="placeOrderBtn">
                        <i class="fas fa-lock"></i>
                        <span id="orderBtnText">{{ __('تأكيد الطلب') }}</span>
                        <div class="loading-spinner" id="orderSpinner" style="display: none;"></div>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Order Summary -->
        <div class="order-summary fade-in">
            <div class="summary-header">
                <h3 class="summary-title">
                    <i class="fas fa-receipt"></i>
                    {{ __('ملخص الطلب') }}
                </h3>
            </div>
            
            <div class="order-items">
                @foreach($cart->cartItems as $item)
                    <div class="order-item">
                        <div class="item-image">
                            <img src="{{ $item->item_image }}" alt="{{ $item->item_name }}" loading="lazy">
                        </div>
                        <div class="item-details">
                            <div class="item-name">{{ $item->item_name }}</div>
                            <div class="item-info">
                                <span class="item-quantity">{{ __('الكمية') }}: {{ $item->quantity }}</span>
                                <span class="item-price">${{ number_format($item->item_price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="summary-calculations">
                <div class="calc-row">
                    <span class="calc-label">{{ __('المجموع الجزئي') }}</span>
                    <span class="calc-value">${{ number_format($cartTotal, 2) }}</span>
                </div>
                
                <div class="calc-row">
                    <span class="calc-label">{{ __('الشحن') }}</span>
                    <span class="calc-value">{{ __('مجاني') }}</span>
                </div>
               @if($appliedCoupon && $discountAmount > 0)
                    <div class="calc-row discount-row">
                        <span class="calc-label">{{ __('الخصم') }} ({{ $appliedCoupon->code }})</span>
                        <span class="calc-value">-${{ number_format($discountAmount, 2) }}</span>
                    </div>
                @endif
                
                <div class="calc-row">
                    <span class="calc-label">{{ __('الضريبة') }}</span>
                    <span class="calc-value">{{ __('مشمولة') }}</span>
                </div>
                
                <div class="calc-row total-row">
                    <span class="total-label">{{ __('الإجمالي') }}</span>
                    <span class="total-value">${{ number_format($cartTotal - $discountAmount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Copy shipping address to billing address
    function copyShippingAddress() {
        const checkbox = document.getElementById('sameAsShipping');
        const shippingAddress = document.getElementById('shipping_address').value;
        const billingAddress = document.getElementById('billing_address');
        
        if (checkbox.checked) {
            billingAddress.value = shippingAddress;
            billingAddress.disabled = true;
        } else {
            billingAddress.disabled = false;
        }
    }
    
    // Toggle coupon form
    function toggleCouponForm() {
        const form = document.getElementById('couponForm');
        const isHidden = form.style.display === 'none';
        
        form.style.display = isHidden ? 'flex' : 'none';
        
        if (isHidden) {
            document.getElementById('couponInput').focus();
        }
    }
    
    // Use suggested coupon
    function useCoupon(code) {
        document.getElementById('couponInput').value = code;
        document.getElementById('couponForm').style.display = 'flex';
    }
    
    // Apply coupon - FIXED للتوافق مع الـ route الجديد
    async function applyCoupon() {
        const input = document.getElementById('couponInput');
        const code = input.value.trim();
        
        if (!code) {
            showNotification('{{ __("الرجاء إدخال كود الكوبون") }}', 'error');
            return;
        }
        
        try {
            const response = await fetch('{{ route("checkout.applyCoupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    coupon_code: code
                })
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                showNotification(data.message, 'success');
                // Reload after short delay to show applied coupon
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification(data.message || '{{ __("كود الكوبون غير صالح") }}', 'error');
            }
        } catch (error) {
            console.error('Coupon application error:', error);
            showNotification('{{ __("خطأ في تطبيق الكوبون") }}', 'error');
        }
    }
    
    // Select payment method
    function selectPayment(element) {
        // Remove selected class from all options
        document.querySelectorAll('.payment-option').forEach(option => {
            option.classList.remove('selected');
        });
        
        // Add selected class to clicked option
        element.classList.add('selected');
        
        // Update hidden input
        const method = element.dataset.method;
        document.getElementById('paymentMethod').value = method;
    }
    
    // Form submission
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('placeOrderBtn');
        const btnText = document.getElementById('orderBtnText');
        const spinner = document.getElementById('orderSpinner');
        
        // Show loading state
        btn.disabled = true;
        btnText.style.display = 'none';
        spinner.style.display = 'block';
        
        // Validate required fields
        const requiredFields = ['phone_number', 'shipping_address', 'billing_address'];
        let isValid = true;
        
        requiredFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            btn.disabled = false;
            btnText.style.display = 'inline';
            spinner.style.display = 'none';
            showNotification('{{ __("الرجاء ملء جميع الحقول المطلوبة") }}', 'error');
            return;
        }
    });
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
            max-width: 300px;
            padding: 12px 16px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease-out;
            background: ${type === 'success' ? '#10B981' : type === 'error' ? '#EF4444' : '#3B82F6'};
        `;
        
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out forwards';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Input validation
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('error') && this.value.trim()) {
                this.classList.remove('error');
            }
        });
    });
    
    // Sync shipping address to billing when typing
    document.getElementById('shipping_address').addEventListener('input', function() {
        const checkbox = document.getElementById('sameAsShipping');
        if (checkbox.checked) {
            document.getElementById('billing_address').value = this.value;
        }
    });
    
    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    });
    
    // Coupon input enter key
    document.addEventListener('DOMContentLoaded', function() {
        const couponInput = document.getElementById('couponInput');
        if (couponInput) {
            couponInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyCoupon();
                }
            });
        }
    });
</script>
@endpush