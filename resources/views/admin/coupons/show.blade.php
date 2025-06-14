@extends('layouts.admin')

@section('title', __('Generate Multiple Coupons'))
@section('page-title', __('Generate Multiple Coupons'))

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
        {{ __('Generate Multiple') }}
    </div>
@endsection

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
        <div>
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm);">
                {{ __('Generate Multiple Coupons') }}
            </h2>
            <p style="color: var(--admin-secondary-600);">{{ __('Create multiple discount coupons at once for selected users or general use') }}</p>
        </div>
        
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Coupons') }}
        </a>
    </div>

    <div class="grid grid-cols-3" style="gap: var(--space-xl);">
        <!-- Main Form -->
        <div style="grid-column: span 2;">
            <form action="{{ route('admin.coupons.storeMultiple') }}" method="POST" id="generateForm">
                @csrf
                
                <!-- Coupon Type Selection -->
                <div class="card" style="margin-bottom: var(--space-lg);">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-layer-group"></i>
                            {{ __('Coupon Type') }}
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                            <!-- Specific Users Option -->
                            <div class="option-card" data-option="specific" style="border: 2px solid var(--admin-secondary-300); border-radius: var(--radius-lg); padding: var(--space-lg); cursor: pointer; transition: all var(--transition-fast);">
                                <div style="text-align: center;">
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--admin-primary-100); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md);">
                                        <i class="fas fa-users" style="font-size: 1.5rem; color: var(--admin-primary-600);"></i>
                                    </div>
                                    <h4 style="font-weight: 600; margin-bottom: var(--space-sm);">{{ __('Specific Users') }}</h4>
                                    <p style="color: var(--admin-secondary-600); font-size: 0.875rem;">{{ __('Create coupons for selected users only') }}</p>
                                </div>
                                <input type="radio" name="coupon_type" value="specific" id="specific_users" style="display: none;" checked>
                            </div>

                            <!-- General Option -->
                            <div class="option-card" data-option="general" style="border: 2px solid var(--admin-secondary-300); border-radius: var(--radius-lg); padding: var(--space-lg); cursor: pointer; transition: all var(--transition-fast);">
                                <div style="text-align: center;">
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--success-100); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md);">
                                        <i class="fas fa-globe" style="font-size: 1.5rem; color: var(--success-600);"></i>
                                    </div>
                                    <h4 style="font-weight: 600; margin-bottom: var(--space-sm);">{{ __('General Coupons') }}</h4>
                                    <p style="color: var(--admin-secondary-600); font-size: 0.875rem;">{{ __('Create coupons that anyone can use') }}</p>
                                </div>
                                <input type="radio" name="coupon_type" value="general" id="general_coupons" style="display: none;">
                                <input type="hidden" name="generate_for_all" value="0" id="generate_for_all_input">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Selection (for specific users) -->
                <div class="card" id="userSelectionCard" style="margin-bottom: var(--space-lg);">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-check"></i>
                            {{ __('Select Users') }}
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div style="margin-bottom: var(--space-md);">
                            <div style="display: flex; gap: var(--space-md); margin-bottom: var(--space-md);">
                                <button type="button" id="selectAllUsers" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-check-square"></i>
                                    {{ __('Select All') }}
                                </button>
                                <button type="button" id="deselectAllUsers" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-square"></i>
                                    {{ __('Deselect All') }}
                                </button>
                                <span id="selectedCount" style="font-size: 0.875rem; color: var(--admin-secondary-600); line-height: 32px;">
                                    {{ __('0 users selected') }}
                                </span>
                            </div>
                        </div>

                        <div style="max-height: 300px; overflow-y: auto; border: 1px solid var(--admin-secondary-200); border-radius: var(--radius-md);">
                            @foreach($users as $user)
                            <label style="display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md); border-bottom: 1px solid var(--admin-secondary-100); cursor: pointer; transition: background-color var(--transition-fast);" 
                                   class="user-option" 
                                   onmouseover="this.style.backgroundColor='var(--admin-secondary-50)'" 
                                   onmouseout="this.style.backgroundColor='transparent'">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" style="margin: 0;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--admin-primary-500); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 500;">{{ $user->name }}</div>
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
                <div class="card" style="margin-bottom: var(--space-lg);">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-ticket-alt"></i>
                            {{ __('Coupon Details') }}
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div class="grid grid-cols-2" style="gap: var(--space-lg);">
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
                                        placeholder="10.00"
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
                                @error('min_purchase_amount')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Valid Months -->
                            <div class="form-group">
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

                            <!-- Quantity per User -->
                            <div class="form-group">
                                <label for="quantity_per_user" class="form-label">{{ __('Coupons per User') }} <span style="color: var(--error-500);">*</span></label>
                                <input 
                                    type="number" 
                                    id="quantity_per_user" 
                                    name="quantity_per_user" 
                                    class="form-input" 
                                    value="{{ old('quantity_per_user', 1) }}" 
                                    min="1"
                                    max="100"
                                    required
                                >
                                <div style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs);">
                                    <span id="quantity_description">{{ __('Number of coupons to create for each selected user') }}</span>
                                </div>
                                @error('quantity_per_user')
                                    <span style="color: var(--error-500); font-size: 0.875rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Generation Summary -->
                <div class="card" style="margin-bottom: var(--space-lg);">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calculator"></i>
                            {{ __('Generation Summary') }}
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div id="summaryContent" style="background: var(--admin-secondary-50); padding: var(--space-lg); border-radius: var(--radius-md); border: 1px solid var(--admin-secondary-200);">
                            <div style="text-align: center; color: var(--admin-secondary-500);">
                                {{ __('Configure the options above to see the generation summary') }}
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
                    
                    <button type="submit" class="btn btn-primary" id="generateBtn" disabled>
                        <i class="fas fa-magic"></i>
                        {{ __('Generate Coupons') }}
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
                                {{ __('Sample Coupon') }}
                            </div>
                            <div id="previewMinPurchase" style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-sm);">
                                {{ __('No minimum purchase') }}
                            </div>
                            <div id="previewValidity" style="font-size: 0.75rem; color: var(--admin-secondary-500); margin-top: var(--space-xs);">
                                {{ __('Valid for: Not set') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i>
                        {{ __('Current Statistics') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div style="space-y: var(--space-md);">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-md);">
                            <span style="color: var(--admin-secondary-600);">{{ __('Total Users') }}</span>
                            <span style="font-weight: 600;">{{ $users->count() }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-md);">
                            <span style="color: var(--admin-secondary-600);">{{ __('Active Coupons') }}</span>
                            <span style="font-weight: 600; color: var(--success-600);">
                                {{ \App\Models\Coupon::where('is_used', false)->where('valid_until', '>=', now())->count() }}
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-200); margin-bottom: var(--space-md);">
                            <span style="color: var(--admin-secondary-600);">{{ __('Used Coupons') }}</span>
                            <span style="font-weight: 600; color: var(--info-600);">
                                {{ \App\Models\Coupon::where('is_used', true)->count() }}
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0;">
                            <span style="color: var(--admin-secondary-600);">{{ __('Total Discounts') }}</span>
                            <span style="font-weight: 600; color: var(--success-600);">
                                ${{ number_format(\App\Models\Coupon::where('is_used', true)->sum('amount'), 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Example of fixed forms with proper confirm dialogs -->
    @if(isset($coupons))
        @foreach($coupons as $coupon)
            <div class="coupon-item" style="border: 1px solid var(--admin-secondary-200); padding: var(--space-md); margin-bottom: var(--space-md);">
                <!-- Coupon Status with proper conditional styling -->
                @php
                    $isExpired = $coupon->valid_until < now();
                    $statusColor = $isExpired ? 'var(--error-600)' : 'var(--success-600)';
                @endphp
                
                <div style="font-weight: 600; color: {{ $statusColor }};">
                    {{ $isExpired ? __('Expired') : __('Active') }}
                </div>
                
                <span style="font-weight: 600; color: {{ $statusColor }};">
                    {{ $coupon->valid_until->format('M d, Y') }}
                </span>

                <!-- Copy to clipboard button -->
                <button type="button" 
                        class="btn btn-sm btn-primary copy-btn" 
                        data-text="{{ $coupon->code }}"
                        data-success-msg="{{ __('Coupon code copied to clipboard!') }}"
                        data-error-msg="{{ __('Failed to copy coupon code') }}">
                    <i class="fas fa-copy"></i>
                    {{ __('Copy Code') }}
                </button>

                <!-- Delete form with proper confirmation -->
                <form action="{{ route('admin.coupons.destroy', $coupon) }}" 
                      method="POST" 
                      style="display: inline;" 
                      class="delete-form"
                      data-confirm-msg="{{ __('Are you sure you want to delete this coupon?') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        @endforeach
    @endif
</div>

<style>
.option-card.active {
    border-color: var(--admin-primary-500) !important;
    background: var(--admin-primary-50);
}

.user-option input[type="checkbox"]:checked + div {
    background: var(--admin-primary-500) !important;
}

#userSelectionCard.hidden {
    display: none;
}

.error-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: var(--error-500);
    color: white;
    padding: 15px 20px;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    animation: slideIn 0.3s ease-out;
}

.success-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: var(--success-500);
    color: white;
    padding: 15px 20px;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Define all translations and messages at the top
    var messages = {
        selectedUsersLabel: {!! json_encode(__('Selected Users:')) !!},
        couponsPerUserLabel: {!! json_encode(__('Coupons per User:')) !!},
        discountAmountLabel: {!! json_encode(__('Discount Amount:')) !!},
        validityPeriodLabel: {!! json_encode(__('Validity Period:')) !!},
        totalCouponsLabel: {!! json_encode(__('Total Coupons')) !!},
        totalPotentialDiscountLabel: {!! json_encode(__('Total Potential Discount')) !!},
        couponTypeLabel: {!! json_encode(__('Coupon Type:')) !!},
        generalText: {!! json_encode(__('General')) !!},
        specificUsersText: {!! json_encode(__('Specific Users')) !!},
        monthText: {!! json_encode(__('month')) !!},
        monthsText: {!! json_encode(__('months')) !!},
        minPurchaseText: {!! json_encode(__('Min purchase: $')) !!},
        noMinPurchaseText: {!! json_encode(__('No minimum purchase')) !!},
        validForText: {!! json_encode(__('Valid for:')) !!},
        validForNotSetText: {!! json_encode(__('Valid for: Not set')) !!},
        oneUserSelectedText: {!! json_encode(__('1 user selected')) !!},
        usersSelectedText: {!! json_encode(__('users selected')) !!},
        configureSummaryText: {!! json_encode(__('Configure the options above to see the generation summary')) !!},
        numberCouponsUserText: {!! json_encode(__('Number of coupons to create for each selected user')) !!},
        numberGeneralCouponsText: {!! json_encode(__('Number of general coupons to create')) !!},
        validDiscountAmountText: {!! json_encode(__('Please enter a valid discount amount')) !!},
        selectValidityPeriodText: {!! json_encode(__('Please select a validity period')) !!},
        validQuantityText: {!! json_encode(__('Please enter a valid quantity')) !!},
        selectUserText: {!! json_encode(__('Please select at least one user')) !!},
        generatingText: {!! json_encode(__('Generating...')) !!},
        copySuccessText: {!! json_encode(__('Coupon code copied to clipboard!')) !!},
        copyErrorText: {!! json_encode(__('Failed to copy coupon code')) !!}
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
                quantityDescription.textContent = messages.numberCouponsUserText;
            } else {
                generalRadio.checked = true;
                specificRadio.checked = false;
                generateForAllInput.value = '1';
                userSelectionCard.classList.add('hidden');
                quantityDescription.textContent = messages.numberGeneralCouponsText;
            }
            
            updateSummary();
            validateForm();
        });
    });

    // User selection functions
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
            selectedCount.textContent = count === 1 ? messages.oneUserSelectedText : count + ' ' + messages.usersSelectedText;
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
            previewAmount.textContent = '
         + amount.toFixed(2);
        }

        // Update min purchase
        var minPurchase = parseFloat(minPurchaseInput.value);
        if (previewMinPurchase) {
            if (minPurchase && minPurchase > 0) {
                previewMinPurchase.textContent = messages.minPurchaseText + minPurchase.toFixed(2);
            } else {
                previewMinPurchase.textContent = messages.noMinPurchaseText;
            }
        }

        // Update validity
        var validMonths = parseInt(validMonthsSelect.value);
        if (previewValidity) {
            if (validMonths) {
                var monthText = validMonths === 1 ? messages.monthText : messages.monthsText;
                previewValidity.textContent = messages.validForText + ' ' + validMonths + ' ' + monthText;
            } else {
                previewValidity.textContent = messages.validForNotSetText;
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
            var monthText = validMonths === 1 ? messages.monthText : messages.monthsText;
            
            var summaryHTML = '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-lg);">';
            summaryHTML += '<div style="text-align: center;">';
            summaryHTML += '<div style="font-size: 2rem; font-weight: 700; color: var(--admin-primary-600); margin-bottom: var(--space-sm);">';
            summaryHTML += totalCoupons;
            summaryHTML += '</div>';
            summaryHTML += '<div style="font-size: 0.875rem; color: var(--admin-secondary-600);">';
            summaryHTML += messages.totalCouponsLabel;
            summaryHTML += '</div>';
            summaryHTML += '</div>';
            
            summaryHTML += '<div style="text-align: center;">';
            summaryHTML += '<div style="font-size: 2rem; font-weight: 700; color: var(--success-600); margin-bottom: var(--space-sm);">';
            summaryHTML += '
         + totalValue.toFixed(2);
            summaryHTML += '</div>';
            summaryHTML += '<div style="font-size: 0.875rem; color: var(--admin-secondary-600);">';
            summaryHTML += messages.totalPotentialDiscountLabel;
            summaryHTML += '</div>';
            summaryHTML += '</div>';
            summaryHTML += '</div>';
            
            summaryHTML += '<hr style="margin: var(--space-lg) 0;">';
            
            summaryHTML += '<div style="space-y: var(--space-sm);">';
            summaryHTML += '<div style="display: flex; justify-content: space-between; font-size: 0.875rem;">';
            summaryHTML += '<span>' + messages.couponTypeLabel + '</span>';
            summaryHTML += '<span style="font-weight: 500;">';
            summaryHTML += generalRadio.checked ? messages.generalText : messages.specificUsersText;
            summaryHTML += '</span>';
            summaryHTML += '</div>';
            
            if (!generalRadio.checked) {
                summaryHTML += '<div style="display: flex; justify-content: space-between; font-size: 0.875rem;">';
                summaryHTML += '<span>' + messages.selectedUsersLabel + '</span>';
                summaryHTML += '<span style="font-weight: 500;">' + userCount + '</span>';
                summaryHTML += '</div>';
            }
            
            summaryHTML += '<div style="display: flex; justify-content: space-between; font-size: 0.875rem;">';
            summaryHTML += '<span>' + messages.couponsPerUserLabel + '</span>';
            summaryHTML += '<span style="font-weight: 500;">' + quantity + '</span>';
            summaryHTML += '</div>';
            
            summaryHTML += '<div style="display: flex; justify-content: space-between; font-size: 0.875rem;">';
            summaryHTML += '<span>' + messages.discountAmountLabel + '</span>';
            summaryHTML += '<span style="font-weight: 500;">
         + amount.toFixed(2) + '</span>';
            summaryHTML += '</div>';
            
            summaryHTML += '<div style="display: flex; justify-content: space-between; font-size: 0.875rem;">';
            summaryHTML += '<span>' + messages.validityPeriodLabel + '</span>';
            summaryHTML += '<span style="font-weight: 500;">' + validMonths + ' ' + monthText + '</span>';
            summaryHTML += '</div>';
            summaryHTML += '</div>';
            
            summaryContent.innerHTML = summaryHTML;
        } else if (summaryContent) {
            summaryContent.innerHTML = '<div style="text-align: center; color: var(--admin-secondary-500);">' + messages.configureSummaryText + '</div>';
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
                showNotification(messages.validDiscountAmountText, 'error');
                return;
            }

            if (!validMonths) {
                e.preventDefault();
                validMonthsSelect.focus();
                showNotification(messages.selectValidityPeriodText, 'error');
                return;
            }

            if (!quantity || quantity <= 0) {
                e.preventDefault();
                quantityInput.focus();
                showNotification(messages.validQuantityText, 'error');
                return;
            }

            if (specificRadio && specificRadio.checked) {
                var selectedUsers = document.querySelectorAll('input[name="user_ids[]"]:checked');
                if (selectedUsers.length === 0) {
                    e.preventDefault();
                    showNotification(messages.selectUserText, 'error');
                    return;
                }
            }

            // Show loading state
            if (generateBtn) {
                generateBtn.disabled = true;
                generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + messages.generatingText;
            }
        });
    }

    // Copy to clipboard functionality
    var copyButtons = document.querySelectorAll('.copy-btn');
    copyButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var text = this.getAttribute('data-text');
            var successMsg = this.getAttribute('data-success-msg');
            var errorMsg = this.getAttribute('data-error-msg');
            
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    showNotification(successMsg, 'success');
                }).catch(function(err) {
                    console.error('Could not copy text: ', err);
                    fallbackCopyTextToClipboard(text, successMsg, errorMsg);
                });
            } else {
                fallbackCopyTextToClipboard(text, successMsg, errorMsg);
            }
        });
    });

    // Fallback copy function for older browsers
    function fallbackCopyTextToClipboard(text, successMsg, errorMsg) {
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
                showNotification(successMsg, 'success');
            } else {
                showNotification(errorMsg, 'error');
            }
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
            showNotification(errorMsg, 'error');
        }
        
        document.body.removeChild(textArea);
    }

    // Delete form confirmation
    var deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var confirmMsg = this.getAttribute('data-confirm-msg');
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
        
        notification.innerHTML = '<div style="display: flex; align-items: center; gap: var(--space-sm);"><i class="' + iconClass + '"></i><span>' + message + '</span><button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer; margin-left: auto;">×</button></div>';
        
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