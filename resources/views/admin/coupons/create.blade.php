@extends('layouts.admin')

@section('title', __('Create Coupon'))
@section('page-title', __('Create Coupon'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.coupons.index') }}" class="breadcrumb-link">{{ __('Coupons') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Create') }}
    </div>
@endsection

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
        <div>
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm);">
                {{ __('Create New Coupon') }}
            </h2>
            <p style="color: var(--admin-secondary-600);">{{ __('Create a discount coupon for your customers') }}</p>
        </div>
        
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Coupons') }}
        </a>
    </div>

    <div class="grid grid-cols-3" style="gap: var(--space-xl);">
        <!-- Main Form -->
        <div style="grid-column: span 2;">
            <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponForm">
                @csrf
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-ticket-alt"></i>
                            {{ __('Coupon Details') }}
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div class="grid grid-cols-2" style="gap: var(--space-lg);">
                            <!-- Coupon Code -->
                            <div class="form-group">
                                <label for="code" class="form-label">
                                    {{ __('Coupon Code') }}
                                    <span style="color: var(--admin-secondary-400); font-size: 0.75rem;">({{ __('Optional - Auto-generated if empty') }})</span>
                                </label>
                                <div style="position: relative;">
                                    <input 
                                        type="text" 
                                        id="code" 
                                        name="code" 
                                        class="form-input" 
                                        value="{{ old('code') }}" 
                                        placeholder="{{ __('e.g., SUMMER2024') }}"
                                        style="text-transform: uppercase; font-family: monospace; font-weight: 600; padding-right: 100px;"
                                    >
                                    <button 
                                        type="button" 
                                        id="generateCode" 
                                        class="btn btn-sm btn-secondary"
                                        style="position: absolute; right: 4px; top: 4px; bottom: 4px;"
                                    >
                                        <i class="fas fa-random"></i>
                                        {{ __('Generate') }}
                                    </button>
                                </div>
                                @error('code')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div class="form-group">
                                <label for="amount" class="form-label">{{ __('Discount Amount') }} <span style="color: var(--error-500);">*</span></label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--admin-secondary-500); font-weight: 600;">$</span>
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
                                        style="padding-left: 32px; font-weight: 600;"
                                    >
                                </div>
                                @error('amount')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- User Selection -->
                            <div class="form-group">
                                <label for="user_id" class="form-label">{{ __('Assigned User') }}</label>
                                <select id="user_id" name="user_id" class="form-input">
                                    <option value="">{{ __('General Coupon (Any User)') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs);">
                                    {{ __('Leave empty to create a general coupon that any user can use') }}
                                </div>
                                @error('user_id')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Minimum Purchase Amount -->
                            <div class="form-group">
                                <label for="min_purchase_amount" class="form-label">{{ __('Minimum Purchase Amount') }}</label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--admin-secondary-500); font-weight: 600;">$</span>
                                    <input 
                                        type="number" 
                                        id="min_purchase_amount" 
                                        name="min_purchase_amount" 
                                        class="form-input" 
                                        value="{{ old('min_purchase_amount') }}" 
                                        placeholder="0.00"
                                        step="0.01"
                                        min="0"
                                        style="padding-left: 32px;"
                                    >
                                </div>
                                <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs);">
                                    {{ __('Leave empty for no minimum purchase requirement') }}
                                </div>
                                @error('min_purchase_amount')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Valid Months -->
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="valid_months" class="form-label">{{ __('Valid for (Months)') }} <span style="color: var(--error-500);">*</span></label>
                                <select id="valid_months" name="valid_months" class="form-input" required>
                                    <option value="">{{ __('Select validity period') }}</option>
                                    <option value="1" {{ old('valid_months') == '1' ? 'selected' : '' }}>{{ __('1 Month') }}</option>
                                    <option value="2" {{ old('valid_months') == '2' ? 'selected' : '' }}>{{ __('2 Months') }}</option>
                                    <option value="3" {{ old('valid_months') == '3' ? 'selected' : '' }}>{{ __('3 Months') }}</option>
                                    <option value="6" {{ old('valid_months') == '6' ? 'selected' : '' }}>{{ __('6 Months') }}</option>
                                    <option value="12" {{ old('valid_months') == '12' ? 'selected' : '' }}>{{ __('1 Year') }}</option>
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
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        {{ __('Cancel') }}
                    </a>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        {{ __('Create Coupon') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div>
            <!-- Preview Card -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye"></i>
                        {{ __('Coupon Preview') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div id="couponPreview" style="border: 2px dashed var(--admin-primary-300); border-radius: var(--radius-lg); padding: var(--space-lg); text-align: center; background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));">
                        <div style="background: white; border-radius: var(--radius-md); padding: var(--space-lg); box-shadow: var(--shadow-md);">
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 1px;">
                                {{ config('app.name') }}
                            </div>
                            <div id="previewCode" style="font-size: 1.5rem; font-weight: 700; color: var(--admin-primary-600); margin-bottom: var(--space-sm); font-family: monospace;">
                                XXXXXXXX
                            </div>
                            <div id="previewAmount" style="font-size: 2rem; font-weight: 900; color: var(--success-600); margin-bottom: var(--space-sm);">
                                $0.00
                            </div>
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-600);">
                                {{ __('Discount Coupon') }}
                            </div>
                            <div id="previewMinPurchase" style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-sm);">
                                No minimum purchase
                            </div>
                            <div id="previewValidity" style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs);">
                                Valid for: Not set
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb"></i>
                        {{ __('Tips') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div>
                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md);">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--info-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-info" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; margin-bottom: var(--space-xs);">{{ __('Coupon Codes') }}</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600);">
                                    {{ __('Use memorable codes that are easy for customers to type and remember.') }}
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md);">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--success-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-dollar-sign" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; margin-bottom: var(--space-xs);">{{ __('Discount Amount') }}</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600);">
                                    {{ __('Consider your profit margins when setting discount amounts.') }}
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md);">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--warning-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-clock" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; margin-bottom: var(--space-xs);">{{ __('Validity Period') }}</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600);">
                                    {{ __('Shorter validity periods create urgency and boost conversions.') }}
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: var(--space-sm);">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--admin-primary-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-users" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; margin-bottom: var(--space-xs);">{{ __('User Assignment') }}</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600);">
                                    {{ __('General coupons can be used by anyone, while specific user coupons are more personal.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            previewMinPurchase.textContent = 'Min purchase: $' + minPurchase.toFixed(2);
        } else {
            previewMinPurchase.textContent = 'No minimum purchase';
        }

        var validMonths = parseInt(validMonthsSelect.value);
        if (validMonths) {
            var monthText = validMonths === 1 ? 'month' : 'months';
            previewValidity.textContent = 'Valid for: ' + validMonths + ' ' + monthText;
        } else {
            previewValidity.textContent = 'Valid for: Not set';
        }
    }

    if (generateCodeBtn) {
        generateCodeBtn.addEventListener('click', function() {
            codeInput.value = generateRandomCode();
            updatePreview();
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
                showError('Please enter a valid discount amount');
                return;
            }

            if (!validMonths) {
                e.preventDefault();
                validMonthsSelect.focus();
                showError('Please select a validity period');
                return;
            }
        });
    }

    function showError(message) {
        var notification = document.createElement('div');
        notification.className = 'error-notification';
        notification.innerHTML = '<div style="display: flex; align-items: center; gap: var(--space-sm);"><i class="fas fa-exclamation-triangle"></i><span>' + message + '</span><button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer; margin-left: auto;">&times;</button></div>';
        
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