@extends('layouts.admin')

@section('title', __('Edit Coupon'))
@section('page-title', __('Edit Coupon'))

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
        <a href="{{ route('admin.coupons.show', $coupon) }}" class="breadcrumb-link">{{ $coupon->code }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Edit') }}
    </div>
@endsection

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
        <div>
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm);">
                {{ __('Edit Coupon') }}
            </h2>
            <div style="display: flex; align-items: center; gap: var(--space-md);">
                <code style="background: var(--admin-primary-50); color: var(--admin-primary-700); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-md); font-weight: 600;">
                    {{ $coupon->code }}
                </code>
                
                @if($coupon->is_used)
                    <span class="badge badge-info">{{ __('Used') }}</span>
                @elseif($coupon->valid_until < now())
                    <span class="badge badge-warning">{{ __('Expired') }}</span>
                @else
                    <span class="badge badge-success">{{ __('Active') }}</span>
                @endif
            </div>
        </div>
        
        <div style="display: flex; gap: var(--space-md);">
            <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-secondary">
                <i class="fas fa-eye"></i>
                {{ __('View Details') }}
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to Coupons') }}
            </a>
        </div>
    </div>

    @if($coupon->is_used)
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        {{ __('This coupon has been used and cannot be edited. You can only view its details or delete it.') }}
    </div>
    @else

    <div class="grid grid-cols-3" style="gap: var(--space-xl);">
        <!-- Main Form -->
        <div style="grid-column: span 2;">
            <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" id="couponForm">
                @csrf
                @method('PUT')
                
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
                                    {{ __('Coupon Code') }} <span style="color: var(--error-500);">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="code" 
                                    name="code" 
                                    class="form-input" 
                                    value="{{ old('code', $coupon->code) }}" 
                                    required
                                    style="text-transform: uppercase; font-family: monospace; font-weight: 600;"
                                >
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
                                        value="{{ old('amount', $coupon->amount) }}" 
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
                                        <option value="{{ $user->id }}" {{ old('user_id', $coupon->user_id) == $user->id ? 'selected' : '' }}>
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
                                        value="{{ old('min_purchase_amount', $coupon->min_purchase_amount) }}" 
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

                            <!-- Valid Until -->
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="valid_until" class="form-label">{{ __('Valid Until') }} <span style="color: var(--error-500);">*</span></label>
                                <input 
                                    type="datetime-local" 
                                    id="valid_until" 
                                    name="valid_until" 
                                    class="form-input" 
                                    value="{{ old('valid_until', $coupon->valid_until->format('Y-m-d\TH:i')) }}" 
                                    min="{{ now()->format('Y-m-d\TH:i') }}"
                                    required
                                >
                                <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs);">
                                    {{ __('Current expiry: :date', ['date' => $coupon->valid_until->format('M d, Y h:i A')]) }}
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
                    <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        {{ __('Cancel') }}
                    </a>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        {{ __('Update Coupon') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div>
            <!-- Current Coupon Preview -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i>
                        {{ __('Current Values') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div style="border: 2px dashed var(--admin-secondary-300); border-radius: var(--radius-lg); padding: var(--space-lg); text-align: center; background: var(--admin-secondary-50); margin-bottom: var(--space-md);">
                        <div style="background: white; border-radius: var(--radius-md); padding: var(--space-lg); box-shadow: var(--shadow-sm);">
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 1px;">
                                {{ config('app.name') }}
                            </div>
                            <div style="font-size: 1.25rem; font-weight: 700; color: var(--admin-secondary-600); margin-bottom: var(--space-sm); font-family: monospace;">
                                {{ $coupon->code }}
                            </div>
                            <div style="font-size: 1.5rem; font-weight: 900; color: var(--admin-secondary-600); margin-bottom: var(--space-sm);">
                                ${{ number_format($coupon->amount, 2) }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-500);">
                                {{ __('Original Values') }}
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div id="couponPreview" style="border: 2px dashed var(--admin-primary-300); border-radius: var(--radius-lg); padding: var(--space-lg); text-align: center; background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));">
                        <div style="background: white; border-radius: var(--radius-md); padding: var(--space-lg); box-shadow: var(--shadow-md);">
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 1px;">
                                {{ config('app.name') }}
                            </div>
                            <div id="previewCode" style="font-size: 1.25rem; font-weight: 700; color: var(--admin-primary-600); margin-bottom: var(--space-sm); font-family: monospace;">
                                {{ $coupon->code }}
                            </div>
                            <div id="previewAmount" style="font-size: 1.5rem; font-weight: 900; color: var(--success-600); margin-bottom: var(--space-sm);">
                                ${{ number_format($coupon->amount, 2) }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--admin-secondary-600);">
                                {{ __('Live Preview') }}
                            </div>
                            <div id="previewMinPurchase" style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-sm);">
                                @if($coupon->min_purchase_amount > 0)
                                    Min purchase: ${{ number_format($coupon->min_purchase_amount, 2) }}
                                @else
                                    No minimum purchase
                                @endif
                            </div>
                            <div id="previewValidity" style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs);">
                                Valid until: {{ $coupon->valid_until->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Summary -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Original Details') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-sm);">
                            <span style="color: var(--admin-secondary-600); font-size: 0.875rem;">{{ __('Created') }}</span>
                            <span style="font-size: 0.875rem; font-weight: 500;">{{ $coupon->created_at->format('M d, Y') }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-sm);">
                            <span style="color: var(--admin-secondary-600); font-size: 0.875rem;">{{ __('Original Code') }}</span>
                            <code style="font-size: 0.875rem; background: var(--admin-secondary-100); padding: 2px 6px; border-radius: 4px;">{{ $coupon->code }}</code>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-sm);">
                            <span style="color: var(--admin-secondary-600); font-size: 0.875rem;">{{ __('Original Amount') }}</span>
                            <span style="font-size: 0.875rem; font-weight: 500;">${{ number_format($coupon->amount, 2) }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0;">
                            <span style="color: var(--admin-secondary-600); font-size: 0.875rem;">{{ __('Status') }}</span>
                            @if($coupon->is_used)
                                <span class="badge badge-info" style="font-size: 0.75rem;">{{ __('Used') }}</span>
                            @elseif($coupon->valid_until < now())
                                <span class="badge badge-warning" style="font-size: 0.75rem;">{{ __('Expired') }}</span>
                            @else
                                <span class="badge badge-success" style="font-size: 0.75rem;">{{ __('Active') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb"></i>
                        {{ __('Edit Tips') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div>
                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md);">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--warning-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-exclamation" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; margin-bottom: var(--space-xs);">{{ __('Used Coupons') }}</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600);">
                                    {{ __('Used coupons cannot be edited. You can only view their details.') }}
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md);">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--info-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-clock" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; margin-bottom: var(--space-xs);">{{ __('Expiry Date') }}</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600);">
                                    {{ __('You can extend the validity period, but not shorten it below the current time.') }}
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: var(--space-sm); margin-bottom: var(--space-md);">
                            <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--success-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-edit" style="color: white; font-size: 0.625rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; margin-bottom: var(--space-xs);">{{ __('Code Changes') }}</div>
                                <div style="font-size: 0.875rem; color: var(--admin-secondary-600);">
                                    {{ __('Changing the coupon code will create a new unique identifier.') }}
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
                                    {{ __('You can change user assignment for unused coupons.') }}
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

    function updatePreview() {
        var code = codeInput.value || '{{ $coupon->code }}';
        previewCode.textContent = code;

        var amount = parseFloat(amountInput.value) || '{{ $coupon->amount }}';
        previewAmount.textContent = '$' + amount.toFixed(2);

        var minPurchase = parseFloat(minPurchaseInput.value);
        if (minPurchase && minPurchase > 0) {
            previewMinPurchase.textContent = 'Min purchase: $' + minPurchase.toFixed(2);
        } else {
            previewMinPurchase.textContent = 'No minimum purchase';
        }

        var validUntil = validUntilInput.value;
        if (validUntil) {
            var date = new Date(validUntil);
            var options = { year: 'numeric', month: 'short', day: 'numeric' };
            previewValidity.textContent = 'Valid until: ' + date.toLocaleDateString('en-US', options);
} else {
    previewValidity.textContent = 'Valid until: {{ $coupon->valid_until->format("M d, Y") }}';
}
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

    if (validUntilInput) {
        validUntilInput.addEventListener('change', updatePreview);
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
                showError('Please enter a valid discount amount');
                return;
            }

            if (validUntil <= now) {
                e.preventDefault();
                validUntilInput.focus();
                showError('Expiry date must be in the future');
                return;
            }

            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
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