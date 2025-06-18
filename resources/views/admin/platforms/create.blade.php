@extends('layouts.admin')

@section('title', __('Add Platform'))
@section('page-title', __('Add Platform'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.platforms.index') }}" class="breadcrumb-link">{{ __('Platforms') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <span>{{ __('Add Platform') }}</span>
    </div>
@endsection

@push('styles')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-section {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: var(--shadow-sm);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-lg);
        padding-bottom: var(--space-md);
        border-bottom: 2px solid var(--admin-primary-500);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-lg);
    }
    
    .form-group {
        margin-bottom: var(--space-lg);
    }
    
    .form-label {
        display: block;
        margin-bottom: var(--space-sm);
        font-weight: 500;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
    }
    
    .form-label.required::after {
        content: ' *';
        color: var(--error-500);
    }
    
    .form-input {
        width: 100%;
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        background: white;
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-input.error {
        border-color: var(--error-500);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .form-error {
        color: var(--error-600);
        font-size: 0.75rem;
        margin-top: var(--space-xs);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .form-help {
        color: var(--admin-secondary-500);
        font-size: 0.75rem;
        margin-top: var(--space-xs);
    }
    
    .form-actions {
        display: flex;
        gap: var(--space-lg);
        justify-content: flex-end;
        padding-top: var(--space-xl);
        border-top: 1px solid var(--admin-secondary-200);
        margin-top: var(--space-xl);
    }
    
    .preview-card {
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        text-align: center;
    }
    
    .preview-icon {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-lg);
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 700;
        margin: 0 auto var(--space-md);
    }
    
    .status-toggle {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md);
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-md);
    }
    
    .toggle-switch {
        position: relative;
        width: 50px;
        height: 24px;
        background: var(--admin-secondary-300);
        border-radius: 12px;
        cursor: pointer;
        transition: background var(--transition-fast);
    }
    
    .toggle-switch.active {
        background: var(--success-500);
    }
    
    .toggle-switch::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        transition: transform var(--transition-fast);
    }
    
    .toggle-switch.active::before {
        transform: translateX(26px);
    }
    
    .percentage-input-group {
        position: relative;
    }
    
    .percentage-input-group::after {
        content: '%';
        position: absolute;
        right: var(--space-md);
        top: 50%;
        transform: translateY(-50%);
        color: var(--admin-secondary-500);
        font-weight: 500;
    }
    
    .percentage-input {
        padding-right: calc(var(--space-md) + 20px) !important;
    }
    
    .price-calculator {
        background: var(--admin-primary-50);
        border: 1px solid var(--admin-primary-200);
        border-radius: var(--radius-md);
        padding: var(--space-lg);
        margin-top: var(--space-md);
    }
    
    .calc-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-sm) 0;
        border-bottom: 1px solid var(--admin-primary-200);
    }
    
    .calc-row:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 1rem;
        color: var(--admin-primary-700);
    }
    
    .calc-label {
        color: var(--admin-secondary-700);
    }
    
    .calc-value {
        font-weight: 500;
        color: var(--admin-secondary-900);
    }
</style>
@endpush

@section('content')
<div class="form-container fade-in">
    <form action="{{ route('admin.platforms.store') }}" method="POST" id="platformForm">
        @csrf
        
        <!-- Basic Information -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-desktop"></i>
                {{ __('Basic Information') }}
            </h3>
            
            <div class="form-grid">
                <div>
                    <div class="form-group">
                        <label for="name" class="form-label required">{{ __('Platform Name') }}</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-input @error('name') error @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="{{ __('Enter platform name') }}"
                               required
                               oninput="updatePreview()">
                        @error('name')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-help">{{ __('Name as it should appear to users') }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="website_url" class="form-label">{{ __('Website URL') }}</label>
                        <input type="url" 
                               id="website_url" 
                               name="website_url" 
                               class="form-input @error('website_url') error @enderror" 
                               value="{{ old('website_url') }}" 
                               placeholder="{{ __('https://example.com') }}">
                        @error('website_url')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-help">{{ __('Official website of the platform (optional)') }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="form-input @error('description') error @enderror" 
                                  rows="4" 
                                  placeholder="{{ __('Brief description about the platform (optional)') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-help">{{ __('Maximum 1000 characters') }}</div>
                    </div>
                </div>
                
                <div>
                    <div class="preview-card">
                        <div class="preview-icon" id="previewIcon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div style="font-weight: 600; margin-bottom: var(--space-xs);" id="previewName">
                            {{ old('name') ?: __('Platform Name') }}
                        </div>
                        <div style="color: var(--admin-secondary-600); font-size: 0.875rem;" id="previewPercentage">
                            +{{ old('price_percentage', 0) }}% {{ __('price increase') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pricing Configuration -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-calculator"></i>
                {{ __('Pricing Configuration') }}
            </h3>
            
            <div class="form-group">
                <label for="price_percentage" class="form-label required">{{ __('Price Increase Percentage') }}</label>
                <div class="percentage-input-group">
                    <input type="number" 
                           id="price_percentage" 
                           name="price_percentage" 
                           class="form-input percentage-input @error('price_percentage') error @enderror" 
                           value="{{ old('price_percentage', 0) }}" 
                           min="0" 
                           max="100" 
                           step="0.01"
                           placeholder="0"
                           required
                           oninput="updatePreview(); calculatePrice()">
                </div>
                @error('price_percentage')
                    <div class="form-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-help">{{ __('Percentage to add to base price (0-100%)') }}</div>
                
                <div class="price-calculator" id="priceCalculator">
                    <div style="font-weight: 600; margin-bottom: var(--space-md); color: var(--admin-primary-700);">
                        {{ __('Price Calculation Example') }}
                    </div>
                    
                    <div class="calc-row">
                        <span class="calc-label">{{ __('Base Price') }}:</span>
                        <span class="calc-value">10.00 JD</span>
                    </div>
                    
                    <div class="calc-row">
                        <span class="calc-label">{{ __('Platform Fee') }} (<span id="calcPercentage">0</span>%):</span>
                        <span class="calc-value">+<span id="calcFee">0.00</span> JD</span>
                    </div>
                    
                    <div class="calc-row">
                        <span class="calc-label">{{ __('Final Price') }}:</span>
                        <span class="calc-value"><span id="calcFinal">10.00</span> JD</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Display Settings -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-cog"></i>
                {{ __('Display Settings') }}
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="order" class="form-label">{{ __('Display Order') }}</label>
                    <input type="number" 
                           id="order" 
                           name="order" 
                           class="form-input @error('order') error @enderror" 
                           value="{{ old('order', 0) }}" 
                           min="0" 
                           placeholder="0">
                    @error('order')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-help">{{ __('Lower numbers appear first. 0 = automatic ordering.') }}</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">{{ __('Status') }}</label>
                    <div class="status-toggle">
                        <span style="color: var(--admin-secondary-600);">{{ __('Inactive') }}</span>
                        <div class="toggle-switch active" onclick="toggleStatus()" id="statusToggle">
                            <input type="hidden" name="is_active" value="1" id="statusInput">
                        </div>
                        <span style="color: var(--success-600); font-weight: 500;">{{ __('Active') }}</span>
                    </div>
                    <div class="form-help">{{ __('Only active platforms will be visible to users.') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('admin.platforms.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                {{ __('Cancel') }}
            </a>
            
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-save"></i>
                {{ __('Create Platform') }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Update preview in real-time
function updatePreview() {
    const name = document.getElementById('name').value || '{{ __('Platform Name') }}';
    const percentage = document.getElementById('price_percentage').value || '0';
    
    document.getElementById('previewName').textContent = name;
    document.getElementById('previewPercentage').textContent = `+${percentage}% {{ __('price increase') }}`;
}

// Calculate price example
function calculatePrice() {
    const percentage = parseFloat(document.getElementById('price_percentage').value) || 0;
    const basePrice = 10.00;
    const fee = basePrice * (percentage / 100);
    const finalPrice = basePrice + fee;
    
    document.getElementById('calcPercentage').textContent = percentage;
    document.getElementById('calcFee').textContent = fee.toFixed(2);
    document.getElementById('calcFinal').textContent = finalPrice.toFixed(2);
}

// Toggle status
function toggleStatus() {
    const toggle = document.getElementById('statusToggle');
    const input = document.getElementById('statusInput');
    
    if (toggle.classList.contains('active')) {
        toggle.classList.remove('active');
        input.value = '0';
    } else {
        toggle.classList.add('active');
        input.value = '1';
    }
}

// Form validation
document.getElementById('platformForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const name = document.getElementById('name').value.trim();
    const percentage = parseFloat(document.getElementById('price_percentage').value);
    
    if (!name) {
        e.preventDefault();
        alert('{{ __('Please enter platform name') }}');
        document.getElementById('name').focus();
        return;
    }
    
    if (isNaN(percentage) || percentage < 0 || percentage > 100) {
        e.preventDefault();
        alert('{{ __('Please enter a valid percentage between 0 and 100') }}');
        document.getElementById('price_percentage').focus();
        return;
    }
    
    // Prevent double submission
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Creating...') }}';
    
    // Re-enable after 5 seconds as fallback
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> {{ __('Create Platform') }}';
    }, 5000);
});

// Auto-resize textarea
document.getElementById('description').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 200) + 'px';
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    calculatePrice();
});
</script>
@endpush