{{-- Pricing Display Component --}}
@props([
    'type' => 'digital', // 'digital' or 'physical'
    'showQuantityControls' => true,
    'showFreeShippingIndicator' => true,
    'maxQuantity' => 10,
    'minQuantity' => 1,
    'currentQuantity' => 1,
    'theme' => 'primary' // primary, accent, success, warning
])

@php
$themeColors = [
    'primary' => ['bg' => 'var(--primary-50), var(--info-50)', 'border' => 'var(--primary-200)', 'accent' => 'var(--primary-600)'],
    'accent' => ['bg' => 'var(--accent-50), var(--warning-50)', 'border' => 'var(--accent-200)', 'accent' => 'var(--accent-600)'],
    'success' => ['bg' => 'var(--success-50), var(--primary-50)', 'border' => 'var(--success-200)', 'accent' => 'var(--success-600)'],
    'warning' => ['bg' => 'var(--warning-50), var(--accent-50)', 'border' => 'var(--warning-200)', 'accent' => 'var(--warning-600)']
];

$colors = $themeColors[$theme] ?? $themeColors['primary'];
$productName = $type === 'digital' ? 'البطاقة' : 'الدوسية';
$productNamePlural = $type === 'digital' ? 'البطاقات' : 'الدوسيات';
@endphp

<div class="pricing-display-container" style="margin-bottom: var(--space-2xl);">
    <!-- Selection Summary -->
    <div id="selectionSummary" style="margin-bottom: var(--space-xl); padding: var(--space-xl); background: linear-gradient(135deg, var(--gray-50), var(--gray-100)); border-radius: var(--radius-xl); border: 2px solid var(--gray-200); position: relative; overflow: hidden;">
        <!-- Decorative Background -->
        <div style="position: absolute; top: -50px; right: -50px; width: 100px; height: 100px; background: linear-gradient(135deg, {{ $colors['accent'] }}20, {{ $colors['accent'] }}10); border-radius: 50%; opacity: 0.5;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 80px; height: 80px; background: linear-gradient(135deg, var(--success-200), var(--success-100)); border-radius: 50%; opacity: 0.3;"></div>
        
        <div style="position: relative; z-index: 2;">
            <h4 style="font-weight: 700; margin-bottom: var(--space-lg); color: var(--gray-800); display: flex; align-items: center; gap: var(--space-sm);">
                <i class="fas fa-check-circle" style="color: var(--success-600);"></i>
                ملخص اختيارك
            </h4>
            
            <div id="summaryContent" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-md);">
                <!-- Summary items will be populated by JavaScript -->
            </div>
        </div>
    </div>

    @if($showQuantityControls)
    <!-- Quantity Controls -->
    <div class="quantity-section" style="margin-bottom: var(--space-xl);">
        <label for="quantity" class="form-label" style="font-weight: 700; color: var(--gray-800); margin-bottom: var(--space-md); display: flex; align-items: center; gap: var(--space-sm);">
            <i class="fas fa-sort-numeric-up" style="color: {{ $colors['accent'] }};"></i>
            عدد {{ $productNamePlural }} المطلوبة
        </label>
        
        <div style="display: flex; align-items: center; gap: var(--space-lg); flex-wrap: wrap;">
            <!-- Quantity Controls -->
            <div style="display: flex; align-items: center; gap: var(--space-md);">
                <button type="button" id="decreaseQty" class="quantity-btn" style="width: 45px; height: 45px; border: 2px solid var(--gray-300); border-radius: var(--radius-lg); background: white; color: var(--gray-600); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all var(--transition-normal); font-size: 1.1rem;">
                    <i class="fas fa-minus"></i>
                </button>
                
                <input 
                    type="number" 
                    name="quantity" 
                    id="quantity" 
                    value="{{ $currentQuantity }}" 
                    min="{{ $minQuantity }}" 
                    max="{{ $maxQuantity }}" 
                    class="quantity-input" 
                    style="width: 80px; height: 45px; text-align: center; font-weight: 700; font-size: 1.2rem; border: 2px solid var(--gray-300); border-radius: var(--radius-lg); background: white; transition: all var(--transition-normal);"
                >
                
                <button type="button" id="increaseQty" class="quantity-btn" style="width: 45px; height: 45px; border: 2px solid var(--gray-300); border-radius: var(--radius-lg); background: white; color: var(--gray-600); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all var(--transition-normal); font-size: 1.1rem;">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            
            <!-- Quantity Info -->
            <div style="font-size: 0.9rem; color: var(--gray-600);">
                <span>الحد الأقصى: </span>
                <span id="maxQuantityDisplay" style="font-weight: 600; color: {{ $colors['accent'] }};">{{ $maxQuantity }}</span>
                <span> {{ $productNamePlural }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Stock Information (for physical products) -->
    @if($type === 'physical')
    <div id="stockInfo" style="margin-bottom: var(--space-lg); padding: var(--space-lg); border-radius: var(--radius-lg); display: none; transition: all var(--transition-normal);">
        <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
            <i class="fas fa-warehouse" style="color: var(--warning-600);"></i>
            <span style="font-weight: 700; color: var(--warning-800);">حالة المخزون</span>
        </div>
        <div id="stockStatus" style="font-size: 0.9rem;"></div>
    </div>
    @endif

    <!-- Price Display -->
    <div id="priceDisplay" style="padding: var(--space-2xl); background: linear-gradient(135deg, {{ $colors['bg'] }}); border-radius: var(--radius-2xl); border: 2px solid {{ $colors['border'] }}; text-align: center; position: relative; overflow: hidden;">
        <!-- Background Animation -->
        <div style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%); animation: priceShimmer 4s linear infinite; pointer-events: none;"></div>
        
        <div style="position: relative; z-index: 2;">
            <!-- Price Breakdown -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-xl);">
                <!-- Unit Price -->
                <div class="price-item" style="padding: var(--space-lg); background: rgba(255, 255, 255, 0.9); border-radius: var(--radius-lg); transition: all var(--transition-normal);">
                    <div style="font-size: 0.9rem; color: var(--gray-600); margin-bottom: var(--space-xs);">
                        سعر {{ $productName }} الواحدة
                    </div>
                    <div id="unitPrice" style="font-size: 1.3rem; font-weight: 800; color: var(--gray-800);">
                        -- دينار
                    </div>
                </div>
                
                <!-- Subtotal -->
                <div class="price-item" style="padding: var(--space-lg); background: rgba(255, 255, 255, 0.9); border-radius: var(--radius-lg); transition: all var(--transition-normal);">
                    <div style="font-size: 0.9rem; color: var(--gray-600); margin-bottom: var(--space-xs);">
                        المجموع الفرعي
                    </div>
                    <div id="subtotal" style="font-size: 1.3rem; font-weight: 800; color: var(--gray-800);">
                        -- دينار
                    </div>
                </div>
                
                <!-- Shipping Cost -->
                <div class="price-item" style="padding: var(--space-lg); background: rgba(255, 255, 255, 0.9); border-radius: var(--radius-lg); transition: all var(--transition-normal);">
                    <div style="font-size: 0.9rem; color: {{ $type === 'digital' ? 'var(--success-600)' : 'var(--warning-600)' }}; margin-bottom: var(--space-xs);">
                        {{ $type === 'digital' ? 'التوصيل' : 'رسوم الشحن' }}
                    </div>
                    <div id="shippingCost" style="font-size: 1.3rem; font-weight: 800; color: {{ $type === 'digital' ? 'var(--success-600)' : 'var(--warning-600)' }};">
                        {{ $type === 'digital' ? 'مجاني' : '-- دينار' }}
                    </div>
                </div>
            </div>
            
            <!-- Total Price -->
            <div style="border-top: 3px solid {{ $colors['border'] }}; padding-top: var(--space-xl);">
                <div style="font-size: 1.1rem; color: var(--gray-600); margin-bottom: var(--space-sm);">
                    السعر النهائي
                </div>
                <div id="totalPrice" style="font-size: 2.5rem; font-weight: 900; background: linear-gradient(135deg, {{ $colors['accent'] }}, var(--success-600)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; animation: pricePulse 3s ease-in-out infinite;">
                    -- دينار
                </div>
            </div>
            
            <!-- Loading State -->
            <div id="priceLoading" style="display: none; padding: var(--space-xl);">
                <div style="font-size: 2rem; margin-bottom: var(--space-md); animation: spin 1s linear infinite;">⏳</div>
                <p style="color: var(--gray-600);">جاري حساب السعر...</p>
            </div>
        </div>
    </div>

    @if($showFreeShippingIndicator && $type === 'physical')
    <!-- Free Shipping Indicator -->
    <div id="freeShippingIndicator" style="margin-top: var(--space-lg); padding: var(--space-lg); background: var(--success-50); border: 2px solid var(--success-200); border-radius: var(--radius-lg); display: none; animation: freeShippingAppear 0.5s ease;">
        <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-sm); color: var(--success-700);">
            <i class="fas fa-gift" style="animation: giftBounce 1s ease infinite;"></i>
            <span style="font-weight: 700;">🎉 مبروك! استحققت الشحن المجاني</span>
        </div>
    </div>
    @endif

    <!-- Price Comparison (Optional) -->
    <div id="priceComparison" style="margin-top: var(--space-lg); padding: var(--space-lg); background: var(--info-50); border-radius: var(--radius-lg); border: 1px solid var(--info-200); display: none;">
        <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
            <i class="fas fa-chart-line" style="color: var(--info-600);"></i>
            <span style="font-weight: 600; color: var(--info-800);">مقارنة الأسعار</span>
        </div>
        <div style="font-size: 0.9rem; color: var(--info-700);">
            <div id="savingsAmount" style="margin-bottom: var(--space-xs);"></div>
            <div id="pricePerUnit" style="opacity: 0.8;"></div>
        </div>
    </div>
</div>

<style>
/* Pricing Display Animations */
@keyframes priceShimmer {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes pricePulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes freeShippingAppear {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes giftBounce {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Quantity Controls */
.quantity-btn:hover {
    border-color: {{ $colors['accent'] }};
    background: {{ $colors['accent'] }}10;
    color: {{ $colors['accent'] }};
    transform: scale(1.05);
}

.quantity-btn:active {
    transform: scale(0.95);
}

.quantity-input:focus {
    outline: none;
    border-color: {{ $colors['accent'] }};
    box-shadow: 0 0 0 3px {{ $colors['accent'] }}20;
}

/* Price Items */
.price-item:hover {
    background: white !important;
    transform: scale(1.02);
    box-shadow: var(--shadow-md);
}

/* Stock Status Colors */
.stock-success {
    background: var(--success-50) !important;
    border: 2px solid var(--success-200) !important;
    color: var(--success-700) !important;
}

.stock-warning {
    background: var(--warning-50) !important;
    border: 2px solid var(--warning-200) !important;
    color: var(--warning-700) !important;
}

.stock-error {
    background: var(--error-50) !important;
    border: 2px solid var(--error-200) !important;
    color: var(--error-700) !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .pricing-display-container {
        margin-bottom: var(--space-lg);
    }
    
    #priceDisplay {
        padding: var(--space-lg) !important;
    }
    
    #priceDisplay > div:first-child {
        grid-template-columns: 1fr !important;
        gap: var(--space-md) !important;
    }
    
    #totalPrice {
        font-size: 2rem !important;
    }
    
    .quantity-section > div:first-child {
        flex-direction: column;
        align-items: stretch;
        gap: var(--space-md);
    }
    
    .quantity-btn,
    .quantity-input {
        width: 100% !important;
        max-width: 120px;
        margin: 0 auto;
    }
}

@media (max-width: 480px) {
    #selectionSummary {
        padding: var(--space-lg) !important;
    }
    
    #summaryContent {
        grid-template-columns: 1fr !important;
    }
    
    .price-item {
        padding: var(--space-md) !important;
    }
    
    #totalPrice {
        font-size: 1.75rem !important;
    }
}
</style>

<script>
// Pricing Display JavaScript Functions
document.addEventListener('DOMContentLoaded', function() {
    initializePricingDisplay();
});

function initializePricingDisplay() {
    setupQuantityControls();
    setupPriceFormatting();
    
    // Initialize with default values
    updatePriceDisplay({
        unit_price: 0,
        shipping_cost: 0,
        subtotal: 0,
        total_price: 0,
        formatted_unit_price: '-- دينار',
        formatted_shipping_cost: '{{ $type === "digital" ? "مجاني" : "-- دينار" }}',
        formatted_total_price: '-- دينار'
    });
}

function setupQuantityControls() {
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const quantityInput = document.getElementById('quantity');
    
    if (!quantityInput) return;
    
    const minQty = parseInt(quantityInput.min) || {{ $minQuantity }};
    const maxQty = parseInt(quantityInput.max) || {{ $maxQuantity }};
    
    // Decrease button
    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value) || minQty;
            const newValue = Math.max(minQty, currentValue - 1);
            quantityInput.value = newValue;
            handleQuantityChange(newValue);
            updateQuantityButtons();
        });
    }
    
    // Increase button
    if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value) || minQty;
            const newValue = Math.min(maxQty, currentValue + 1);
            quantityInput.value = newValue;
            handleQuantityChange(newValue);
            updateQuantityButtons();
        });
    }
    
    // Direct input
    quantityInput.addEventListener('input', function() {
        let value = parseInt(this.value) || minQty;
        
        if (value < minQty) {
            value = minQty;
            this.value = value;
        } else if (value > maxQty) {
            value = maxQty;
            this.value = value;
            if (window.showErrorNotification) {
                window.showErrorNotification(`الحد الأقصى للكمية هو ${maxQty} {{ $productNamePlural }}`);
            }
        }
        
        handleQuantityChange(value);
        updateQuantityButtons();
    });
    
    // Initialize button states
    updateQuantityButtons();
}

function updateQuantityButtons() {
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const quantityInput = document.getElementById('quantity');
    
    if (!quantityInput) return;
    
    const currentValue = parseInt(quantityInput.value) || {{ $minQuantity }};
    const minQty = parseInt(quantityInput.min) || {{ $minQuantity }};
    const maxQty = parseInt(quantityInput.max) || {{ $maxQuantity }};
    
    // Update decrease button
    if (decreaseBtn) {
        decreaseBtn.disabled = currentValue <= minQty;
        decreaseBtn.style.opacity = currentValue <= minQty ? '0.5' : '1';
        decreaseBtn.style.cursor = currentValue <= minQty ? 'not-allowed' : 'pointer';
    }
    
    // Update increase button
    if (increaseBtn) {
        increaseBtn.disabled = currentValue >= maxQty;
        increaseBtn.style.opacity = currentValue >= maxQty ? '0.5' : '1';
        increaseBtn.style.cursor = currentValue >= maxQty ? 'not-allowed' : 'pointer';
    }
}

function handleQuantityChange(quantity) {
    console.log('Quantity changed to:', quantity);
    
    // Trigger quantity change event
    const event = new CustomEvent('quantityChanged', {
        detail: { quantity: quantity }
    });
    document.dispatchEvent(event);
    
    // Update price comparison if enabled
    updatePriceComparison(quantity);
    
    // For physical products, check inventory
    @if($type === 'physical')
    if (window.checkInventory) {
        window.checkInventory();
    }
    @endif
    
    // Recalculate price
    if (window.calculatePrice) {
        window.calculatePrice();
    }
}

function setupPriceFormatting() {
    // Add number formatting for Arabic locale
    window.formatPrice = function(amount) {
        if (typeof amount !== 'number' || isNaN(amount)) {
            return '-- دينار';
        }
        
        return new Intl.NumberFormat('ar-JO', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount) + ' دينار';
    };
}

function updatePriceDisplay(priceData) {
    console.log('Updating price display:', priceData);
    
    const elements = {
        unitPrice: document.getElementById('unitPrice'),
        subtotal: document.getElementById('subtotal'),
        shippingCost: document.getElementById('shippingCost'),
        totalPrice: document.getElementById('totalPrice'),
        priceLoading: document.getElementById('priceLoading'),
        priceDisplay: document.getElementById('priceDisplay')
    };
    
    // Hide loading state
    if (elements.priceLoading) {
        elements.priceLoading.style.display = 'none';
    }
    
    // Update price elements
    if (elements.unitPrice) {
        elements.unitPrice.textContent = priceData.formatted_unit_price || window.formatPrice(priceData.unit_price);
    }
    
    if (elements.subtotal) {
        elements.subtotal.textContent = priceData.formatted_subtotal || window.formatPrice(priceData.subtotal);
    }
    
    if (elements.shippingCost) {
        elements.shippingCost.textContent = priceData.formatted_shipping_cost || 
            (priceData.shipping_cost === 0 ? 'مجاني' : window.formatPrice(priceData.shipping_cost));
    }
    
    if (elements.totalPrice) {
        elements.totalPrice.textContent = priceData.formatted_total_price || window.formatPrice(priceData.total_price);
        
        // Add animation effect
        elements.totalPrice.style.animation = 'none';
        setTimeout(() => {
            elements.totalPrice.style.animation = 'pricePulse 0.5s ease';
        }, 10);
    }
    
    // Update free shipping indicator
    @if($showFreeShippingIndicator && $type === 'physical')
    updateFreeShippingIndicator(priceData.shipping_cost === 0);
    @endif
    
    // Update stock info if available
    if (priceData.stock_info) {
        updateStockDisplay(priceData.stock_info);
    }
    
    // Update price comparison
    const quantity = parseInt(document.getElementById('quantity')?.value) || 1;
    updatePriceComparison(quantity, priceData);
}

function showPriceLoading() {
    const priceLoading = document.getElementById('priceLoading');
    const priceDisplay = document.getElementById('priceDisplay');
    
    if (priceLoading) {
        priceLoading.style.display = 'block';
    }
    
    // Optionally dim the price display
    if (priceDisplay) {
        priceDisplay.style.opacity = '0.7';
    }
}

function hidePriceLoading() {
    const priceLoading = document.getElementById('priceLoading');
    const priceDisplay = document.getElementById('priceDisplay');
    
    if (priceLoading) {
        priceLoading.style.display = 'none';
    }
    
    if (priceDisplay) {
        priceDisplay.style.opacity = '1';
    }
}

@if($showFreeShippingIndicator && $type === 'physical')
function updateFreeShippingIndicator(isFreeShipping) {
    const indicator = document.getElementById('freeShippingIndicator');
    if (!indicator) return;
    
    if (isFreeShipping) {
        indicator.style.display = 'block';
        indicator.style.animation = 'freeShippingAppear 0.5s ease';
    } else {
        indicator.style.display = 'none';
    }
}
@endif

@if($type === 'physical')
function updateStockDisplay(stockInfo) {
    const stockInfoElement = document.getElementById('stockInfo');
    const stockStatus = document.getElementById('stockStatus');
    
    if (!stockInfoElement || !stockStatus) return;
    
    stockInfoElement.style.display = 'block';
    
    let statusClass = 'stock-success';
    let statusIcon = 'fas fa-check-circle';
    
    if (stockInfo.available_quantity <= 10) {
        statusClass = 'stock-warning';
        statusIcon = 'fas fa-exclamation-triangle';
    }
    
    if (stockInfo.available_quantity <= 0) {
        statusClass = 'stock-error';
        statusIcon = 'fas fa-times-circle';
    }
    
    // Remove existing status classes
    stockInfoElement.classList.remove('stock-success', 'stock-warning', 'stock-error');
    stockInfoElement.classList.add(statusClass);
    
    stockStatus.innerHTML = `
        <div style="display: flex; align-items: center; gap: var(--space-sm);">
            <i class="${statusIcon}"></i>
            <span>${stockInfo.stock_status} - متوفر: ${stockInfo.available_quantity} قطعة</span>
        </div>
    `;
    
    // Update max quantity
    const maxQuantityDisplay = document.getElementById('maxQuantityDisplay');
    const quantityInput = document.getElementById('quantity');
    
    if (maxQuantityDisplay && stockInfo.available_quantity < {{ $maxQuantity }}) {
        maxQuantityDisplay.textContent = stockInfo.available_quantity;
    }
    
    if (quantityInput && stockInfo.available_quantity < {{ $maxQuantity }}) {
        quantityInput.max = stockInfo.available_quantity;
        
        // Adjust current quantity if it exceeds available
        const currentQty = parseInt(quantityInput.value);
        if (currentQty > stockInfo.available_quantity) {
            quantityInput.value = stockInfo.available_quantity;
            handleQuantityChange(stockInfo.available_quantity);
        }
    }
}
@endif

function updatePriceComparison(quantity, priceData = null) {
    const comparisonElement = document.getElementById('priceComparison');
    if (!comparisonElement || !priceData) return;
    
    // Only show comparison for quantities > 1
    if (quantity <= 1) {
        comparisonElement.style.display = 'none';
        return;
    }
    
    const singlePrice = priceData.unit_price;
    const totalPrice = priceData.total_price;
    const pricePerUnit = totalPrice / quantity;
    
    // Calculate savings (if any bulk discount exists)
    const savings = (singlePrice * quantity) - totalPrice;
    
    const savingsAmount = document.getElementById('savingsAmount');
    const pricePerUnitElement = document.getElementById('pricePerUnit');
    
    if (savingsAmount && savings > 0) {
        savingsAmount.innerHTML = `
            <span style="color: var(--success-600); font-weight: 600;">
                <i class="fas fa-tag"></i>
                توفير: ${window.formatPrice(savings)}
            </span>
        `;
    } else if (savingsAmount) {
        savingsAmount.innerHTML = '';
    }
    
    if (pricePerUnitElement) {
        pricePerUnitElement.textContent = `السعر للقطعة الواحدة: ${window.formatPrice(pricePerUnit)}`;
    }
    
    comparisonElement.style.display = 'block';
}

function updateSelectionSummary(summaryData) {
    const summaryContent = document.getElementById('summaryContent');
    if (!summaryContent || !summaryData) return;
    
    summaryContent.innerHTML = '';
    
    Object.entries(summaryData).forEach(([label, value]) => {
        if (value) {
            const summaryItem = document.createElement('div');
            summaryItem.style.cssText = `
                background: white; 
                padding: var(--space-md); 
                border-radius: var(--radius-md); 
                border: 1px solid var(--gray-200); 
                transition: all var(--transition-normal);
            `;
            
            summaryItem.innerHTML = `
                <div style="font-weight: 600; color: var(--gray-700); font-size: 0.85rem; margin-bottom: var(--space-xs);">
                    ${label}
                </div>
                <div style="color: var(--gray-800); font-weight: 500;">
                    ${value}
                </div>
            `;
            
            // Add hover effect
            summaryItem.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = 'var(--shadow-md)';
            });
            
            summaryItem.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
            
            summaryContent.appendChild(summaryItem);
        }
    });
}

// Export functions for global access
window.PricingDisplay = {
    updatePriceDisplay: updatePriceDisplay,
    showPriceLoading: showPriceLoading,
    hidePriceLoading: hidePriceLoading,
    updateSelectionSummary: updateSelectionSummary,
    handleQuantityChange: handleQuantityChange,
    @if($type === 'physical')
    updateStockDisplay: updateStockDisplay,
    @endif
    @if($showFreeShippingIndicator && $type === 'physical')
    updateFreeShippingIndicator: updateFreeShippingIndicator,
    @endif
    updatePriceComparison: updatePriceComparison
};

// Keyboard shortcuts for quantity
document.addEventListener('keydown', function(e) {
    if (e.target.id === 'quantity') return; // Don't interfere with direct input
    
    const quantityInput = document.getElementById('quantity');
    if (!quantityInput) return;
    
    if ((e.ctrlKey || e.metaKey) && e.key === '+') {
        e.preventDefault();
        document.getElementById('increaseQty')?.click();
    } else if ((e.ctrlKey || e.metaKey) && e.key === '-') {
        e.preventDefault();
        document.getElementById('decreaseQty')?.click();
    }
});
</script>