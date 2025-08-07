{{-- Selection Step Component --}}
@props([
    'stepNumber' => 1,
    'title' => '',
    'description' => '',
    'icon' => 'fas fa-list',
    'theme' => 'primary', // primary, secondary, accent, success, warning, info
    'containerId' => '',
    'loadingId' => '',
    'gridCols' => 'auto-fit',
    'minCardWidth' => '200px',
    'isActive' => false
])

@php
$themeColors = [
    'primary' => ['bg' => 'var(--primary-50), var(--primary-100)', 'text' => 'var(--primary-700)', 'accent' => 'var(--primary-600)'],
    'secondary' => ['bg' => 'var(--secondary-50), var(--secondary-100)', 'text' => 'var(--secondary-700)', 'accent' => 'var(--secondary-600)'],
    'accent' => ['bg' => 'var(--accent-50), var(--accent-100)', 'text' => 'var(--accent-700)', 'accent' => 'var(--accent-600)'],
    'success' => ['bg' => 'var(--success-50), var(--success-100)', 'text' => 'var(--success-700)', 'accent' => 'var(--success-600)'],
    'warning' => ['bg' => 'var(--warning-50), var(--warning-100)', 'text' => 'var(--warning-700)', 'accent' => 'var(--warning-600)'],
    'info' => ['bg' => 'var(--info-50), var(--info-100)', 'text' => 'var(--info-700)', 'accent' => 'var(--info-600)']
];

$colors = $themeColors[$theme] ?? $themeColors['primary'];
@endphp

<div class="form-step {{ $isActive ? 'active' : '' }}" id="step{{ $stepNumber }}" style="margin-bottom: var(--space-2xl); {{ $isActive ? 'display: block;' : 'display: none;' }}">
    <div class="card selection-step-card" style="transition: all var(--transition-normal); position: relative; overflow: hidden;">
        <!-- Animated Border -->
        <div class="card-border-animation" style="position: absolute; top: 0; left: -100%; right: 0; height: 3px; background: linear-gradient(90deg, {{ $colors['accent'] }}, {{ $colors['text'] }}); transition: left 0.8s ease; z-index: 2;"></div>
        
        <div class="card-header" style="background: linear-gradient(135deg, {{ $colors['bg'] }}); position: relative;">
            <!-- Step Number Badge -->
            <div style="position: absolute; top: -10px; right: var(--space-lg); background: {{ $colors['accent'] }}; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; box-shadow: var(--shadow-md);">
                {{ $stepNumber }}
            </div>
            
            <h3 class="card-title" style="color: {{ $colors['text'] }}; display: flex; align-items: center; gap: var(--space-md); margin-top: var(--space-sm);">
                <div style="padding: var(--space-sm); background: rgba(255, 255, 255, 0.8); border-radius: var(--radius-md); color: {{ $colors['accent'] }};">
                    <i class="{{ $icon }}" style="font-size: 1.2rem;"></i>
                </div>
                <div>
                    <div style="font-size: 1.25rem; margin-bottom: var(--space-xs);">{{ $title }}</div>
                    @if($description)
                        <div style="font-size: 0.9rem; font-weight: 400; opacity: 0.8;">{{ $description }}</div>
                    @endif
                </div>
            </h3>
        </div>
        
        <div class="card-body" style="padding: var(--space-xl);">
            <!-- Loading State -->
            <div id="{{ $loadingId }}" class="loading-state" style="text-align: center; padding: var(--space-3xl);">
                <div class="loading-spinner" style="font-size: 2.5rem; margin-bottom: var(--space-lg); animation: spin 2s linear infinite;">
                    <i class="fas fa-spinner"></i>
                </div>
                <h4 style="color: var(--gray-700); margin-bottom: var(--space-sm);">جاري التحميل...</h4>
                <p style="color: var(--gray-500); font-size: 0.9rem;">يرجى الانتظار بينما نحضر لك الخيارات المتاحة</p>
                
                <!-- Loading Progress Bar -->
                <div style="width: 200px; height: 4px; background: var(--gray-200); border-radius: 2px; margin: var(--space-lg) auto 0; overflow: hidden;">
                    <div style="width: 0%; height: 100%; background: {{ $colors['accent'] }}; border-radius: 2px; animation: loadingProgress 3s ease-in-out infinite;"></div>
                </div>
            </div>

            <!-- Content Container -->
            <div 
                id="{{ $containerId }}" 
                class="options-grid" 
                style="display: none; grid-template-columns: repeat({{ $gridCols }}, minmax({{ $minCardWidth }}, 1fr)); gap: var(--space-lg); animation: fadeInUp 0.5s ease;"
            >
                <!-- Options will be populated by JavaScript -->
            </div>

            <!-- Empty State -->
            <div id="{{ $containerId }}_empty" class="empty-state" style="display: none; text-align: center; padding: var(--space-3xl);">
                <div style="font-size: 3rem; margin-bottom: var(--space-lg); opacity: 0.5;">📋</div>
                <h4 style="color: var(--gray-600); margin-bottom: var(--space-md);">لا توجد خيارات متاحة</h4>
                <p style="color: var(--gray-500); font-size: 0.9rem; margin-bottom: var(--space-lg);">
                    لم نتمكن من العثور على خيارات متاحة لهذا الاختيار
                </p>
                <button onclick="retryLoading('{{ $stepNumber }}')" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                    إعادة المحاولة
                </button>
            </div>

            <!-- Error State -->
            <div id="{{ $containerId }}_error" class="error-state" style="display: none; text-align: center; padding: var(--space-3xl);">
                <div style="font-size: 3rem; margin-bottom: var(--space-lg); color: var(--error-500);">⚠️</div>
                <h4 style="color: var(--error-600); margin-bottom: var(--space-md);">حدث خطأ في التحميل</h4>
                <p style="color: var(--error-500); font-size: 0.9rem; margin-bottom: var(--space-lg);">
                    نعتذر، حدث خطأ أثناء تحميل البيانات. يرجى المحاولة مرة أخرى.
                </p>
                <div style="display: flex; gap: var(--space-md); justify-content: center;">
                    <button onclick="retryLoading('{{ $stepNumber }}')" class="btn btn-primary">
                        <i class="fas fa-redo"></i>
                        إعادة المحاولة
                    </button>
                    <button onclick="reportError('{{ $stepNumber }}')" class="btn btn-secondary">
                        <i class="fas fa-bug"></i>
                        الإبلاغ عن المشكلة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Selection Step Animations */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes loadingProgress {
    0%, 100% { width: 0%; }
    50% { width: 100%; }
}

@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInFromRight {
    0% {
        opacity: 0;
        transform: translateX(50px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Form Step Transitions */
.form-step {
    opacity: 0;
    transform: translateX(30px);
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
}

.form-step.active {
    opacity: 1;
    transform: translateX(0);
    pointer-events: auto;
}

.form-step.active .card-border-animation {
    left: 0;
}

/* Selection Step Card Effects */
.selection-step-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.selection-step-card.active {
    border-color: {{ $colors['accent'] }};
    box-shadow: 0 0 0 2px {{ $colors['accent'] }}40;
}

/* Option Cards Base Styles */
.option-card {
    background: white;
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    cursor: pointer;
    transition: all var(--transition-normal);
    position: relative;
    overflow: hidden;
}

.option-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, {{ $colors['accent'] }}10, {{ $colors['accent'] }}05);
    opacity: 0;
    transition: opacity var(--transition-normal);
    z-index: 1;
}

.option-card:hover {
    border-color: {{ $colors['accent'] }};
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.option-card:hover::before {
    opacity: 1;
}

.option-card.selected {
    border-color: {{ $colors['accent'] }};
    background: linear-gradient(135deg, {{ $colors['bg'] }});
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
    position: relative;
}

.option-card.selected::after {
    content: '✓';
    position: absolute;
    top: var(--space-md);
    left: var(--space-md);
    background: {{ $colors['accent'] }};
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: bold;
    animation: checkmarkAppear 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    z-index: 3;
}

@keyframes checkmarkAppear {
    0% {
        opacity: 0;
        transform: scale(0) rotate(-180deg);
    }
    100% {
        opacity: 1;
        transform: scale(1) rotate(0deg);
    }
}

/* Loading State Enhancements */
.loading-state {
    opacity: 0.8;
    transition: opacity var(--transition-normal);
}

.loading-spinner {
    background: linear-gradient(135deg, {{ $colors['accent'] }}, {{ $colors['text'] }});
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Empty and Error States */
.empty-state,
.error-state {
    background: var(--gray-50);
    border-radius: var(--radius-xl);
    border: 2px dashed var(--gray-300);
    animation: fadeInUp 0.5s ease;
}

/* Special Card Types */
.teacher-card {
    display: flex;
    align-items: center;
    gap: var(--space-md);
    padding: var(--space-lg);
}

.teacher-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, {{ $colors['accent'] }}, {{ $colors['text'] }});
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    flex-shrink: 0;
    border: 3px solid white;
    box-shadow: var(--shadow-md);
    transition: all var(--transition-normal);
}

.teacher-card:hover .teacher-avatar {
    transform: scale(1.1);
    box-shadow: var(--shadow-lg);
}

.package-card {
    text-align: center;
    padding: var(--space-xl);
    min-height: 200px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.package-icon {
    font-size: 3rem;
    margin-bottom: var(--space-lg);
    transition: all var(--transition-normal);
}

.package-card:hover .package-icon {
    transform: scale(1.1) rotate(5deg);
}

.region-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-lg);
}

.shipping-cost {
    font-weight: 700;
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-sm);
    font-size: 0.9rem;
    transition: all var(--transition-normal);
}

.shipping-free {
    background: var(--success-100);
    color: var(--success-700);
    border: 1px solid var(--success-300);
}

.shipping-paid {
    background: var(--warning-100);
    color: var(--warning-700);
    border: 1px solid var(--warning-300);
}

/* Responsive Design */
@media (max-width: 768px) {
    .options-grid {
        grid-template-columns: 1fr !important;
    }
    
    .teacher-card {
        flex-direction: column;
        text-align: center;
        gap: var(--space-sm);
    }
    
    .teacher-avatar {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .package-card {
        padding: var(--space-lg);
        min-height: 180px;
    }
    
    .region-card {
        flex-direction: column;
        gap: var(--space-sm);
        text-align: center;
    }
}

@media (max-width: 480px) {
    .card-body {
        padding: var(--space-lg) !important;
    }
    
    .option-card {
        padding: var(--space-md) !important;
    }
    
    .loading-state,
    .empty-state,
    .error-state {
        padding: var(--space-xl) !important;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .option-card,
    .form-step,
    .loading-spinner,
    .teacher-avatar,
    .package-icon {
        transition: none !important;
        animation: none !important;
    }
}

/* High Contrast Mode */
@media (prefers-contrast: high) {
    .option-card {
        border-width: 3px;
    }
    
    .option-card.selected {
        border-width: 4px;
        box-shadow: 0 0 0 3px currentColor;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .option-card {
        background: var(--gray-800);
        border-color: var(--gray-600);
        color: var(--gray-100);
    }
    
    .option-card:hover {
        background: var(--gray-700);
    }
    
    .empty-state,
    .error-state {
        background: var(--gray-800);
        border-color: var(--gray-600);
        color: var(--gray-100);
    }
}
</style>

<script>
// Selection Step JavaScript Functions
document.addEventListener('DOMContentLoaded', function() {
    initializeSelectionStep{{ $stepNumber }}();
});

function initializeSelectionStep{{ $stepNumber }}() {
    const container = document.getElementById('{{ $containerId }}');
    const loadingState = document.getElementById('{{ $loadingId }}');
    
    if (container && loadingState) {
        // Add event listeners for option selection
        setupOptionSelection('{{ $containerId }}');
        
        // Add keyboard navigation
        setupKeyboardNavigation('{{ $containerId }}');
        
        // Add accessibility features
        setupAccessibility('{{ $containerId }}');
    }
}

function setupOptionSelection(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    // Delegate click events to option cards
    container.addEventListener('click', function(e) {
        const optionCard = e.target.closest('.option-card');
        if (!optionCard) return;
        
        // Remove selection from other cards
        container.querySelectorAll('.option-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selection to clicked card
        optionCard.classList.add('selected');
        
        // Trigger selection event
        const optionId = optionCard.dataset.id;
        if (optionId) {
            triggerOptionSelection({{ $stepNumber }}, optionId, optionCard);
        }
    });
}

function setupKeyboardNavigation(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    container.addEventListener('keydown', function(e) {
        const focusedCard = document.activeElement;
        if (!focusedCard.classList.contains('option-card')) return;
        
        let targetCard = null;
        
        switch(e.key) {
            case 'ArrowRight':
                targetCard = focusedCard.nextElementSibling;
                break;
            case 'ArrowLeft':
                targetCard = focusedCard.previousElementSibling;
                break;
            case 'ArrowDown':
                // Find card in next row
                const currentIndex = Array.from(container.children).indexOf(focusedCard);
                const gridCols = getComputedStyle(container).gridTemplateColumns.split(' ').length;
                targetCard = container.children[currentIndex + gridCols];
                break;
            case 'ArrowUp':
                // Find card in previous row
                const currentIdx = Array.from(container.children).indexOf(focusedCard);
                const cols = getComputedStyle(container).gridTemplateColumns.split(' ').length;
                targetCard = container.children[currentIdx - cols];
                break;
            case 'Enter':
            case ' ':
                e.preventDefault();
                focusedCard.click();
                return;
        }
        
        if (targetCard) {
            e.preventDefault();
            targetCard.focus();
        }
    });
}

function setupAccessibility(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    // Add ARIA attributes and make cards focusable
    container.querySelectorAll('.option-card').forEach((card, index) => {
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        card.setAttribute('aria-label', `الخيار ${index + 1}`);
        
        // Add focus styles
        card.addEventListener('focus', function() {
            this.style.outline = '2px solid {{ $colors["accent"] }}';
            this.style.outlineOffset = '2px';
        });
        
        card.addEventListener('blur', function() {
            this.style.outline = 'none';
        });
    });
}

function triggerOptionSelection(stepNumber, optionId, optionElement) {
    // Create custom event for option selection
    const event = new CustomEvent('optionSelected', {
        detail: {
            step: stepNumber,
            optionId: optionId,
            optionElement: optionElement,
            optionText: optionElement.querySelector('h4, h3')?.textContent || 'Unknown'
        }
    });
    
    document.dispatchEvent(event);
    
    // Add visual feedback
    optionElement.style.transform = 'scale(0.98)';
    setTimeout(() => {
        optionElement.style.transform = '';
    }, 150);
    
    // Log selection for debugging
    console.log(`Step ${stepNumber}: Selected option ${optionId}`);
}

function showLoadingState(containerId, loadingId) {
    const container = document.getElementById(containerId);
    const loading = document.getElementById(loadingId);
    const emptyState = document.getElementById(containerId + '_empty');
    const errorState = document.getElementById(containerId + '_error');
    
    if (container) container.style.display = 'none';
    if (emptyState) emptyState.style.display = 'none';
    if (errorState) errorState.style.display = 'none';
    if (loading) loading.style.display = 'block';
}

function showContentState(containerId, loadingId) {
    const container = document.getElementById(containerId);
    const loading = document.getElementById(loadingId);
    const emptyState = document.getElementById(containerId + '_empty');
    const errorState = document.getElementById(containerId + '_error');
    
    if (loading) loading.style.display = 'none';
    if (emptyState) emptyState.style.display = 'none';
    if (errorState) errorState.style.display = 'none';
    if (container) {
        container.style.display = 'grid';
        container.style.animation = 'fadeInUp 0.5s ease';
    }
}

function showEmptyState(containerId, loadingId) {
    const container = document.getElementById(containerId);
    const loading = document.getElementById(loadingId);
    const emptyState = document.getElementById(containerId + '_empty');
    const errorState = document.getElementById(containerId + '_error');
    
    if (container) container.style.display = 'none';
    if (loading) loading.style.display = 'none';
    if (errorState) errorState.style.display = 'none';
    if (emptyState) emptyState.style.display = 'block';
}

function showErrorState(containerId, loadingId) {
    const container = document.getElementById(containerId);
    const loading = document.getElementById(loadingId);
    const emptyState = document.getElementById(containerId + '_empty');
    const errorState = document.getElementById(containerId + '_error');
    
    if (container) container.style.display = 'none';
    if (loading) loading.style.display = 'none';
    if (emptyState) emptyState.style.display = 'none';
    if (errorState) errorState.style.display = 'block';
}

function retryLoading(stepNumber) {
    console.log(`Retrying loading for step ${stepNumber}`);
    
    // Trigger retry event
    const event = new CustomEvent('retryLoading', {
        detail: { step: stepNumber }
    });
    document.dispatchEvent(event);
    
    // Show loading state
    showLoadingState('{{ $containerId }}', '{{ $loadingId }}');
}

function reportError(stepNumber) {
    console.log(`Reporting error for step ${stepNumber}`);
    
    // You can implement error reporting here
    if (window.showErrorNotification) {
        window.showErrorNotification('تم الإبلاغ عن المشكلة. سيتم التواصل معك قريباً.');
    }
}

// Export functions for global access
window.SelectionStep{{ $stepNumber }} = {
    showLoadingState: () => showLoadingState('{{ $containerId }}', '{{ $loadingId }}'),
    showContentState: () => showContentState('{{ $containerId }}', '{{ $loadingId }}'),
    showEmptyState: () => showEmptyState('{{ $containerId }}', '{{ $loadingId }}'),
    showErrorState: () => showErrorState('{{ $containerId }}', '{{ $loadingId }}'),
    retryLoading: () => retryLoading({{ $stepNumber }}),
    containerId: '{{ $containerId }}',
    loadingId: '{{ $loadingId }}'
};
</script>