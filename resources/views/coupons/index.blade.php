@extends('layouts.app')

@section('title', __('My Coupons') . ' - ' . config('app.name'))

@push('styles')
<style>
    .coupons-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .coupons-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,0 1000,0 1000,80 0,100"/></svg>');
        background-size: cover;
        background-position: bottom;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
        text-align: center;
    }
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: var(--space-lg);
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        font-size: 0.875rem;
        opacity: 0.9;
        flex-wrap: wrap;
    }
    
    .breadcrumb-link {
        color: white;
        text-decoration: none;
        transition: opacity var(--transition-fast);
    }
    
    .breadcrumb-link:hover {
        opacity: 0.8;
        text-decoration: underline;
    }
    
    .breadcrumb-separator {
        opacity: 0.6;
    }
    
    .coupons-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
        min-height: 60vh;
    }
    
    .coupons-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-2xl);
        flex-wrap: wrap;
        gap: var(--space-lg);
    }
    
    .coupons-count {
        font-size: 1.125rem;
        color: var(--on-surface-variant);
        font-weight: 500;
    }
    
    .coupons-filters {
        display: flex;
        gap: var(--space-md);
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-select {
        padding: var(--space-sm) var(--space-md);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .filter-select:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .coupons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: var(--space-xl);
    }
    
    .coupon-card {
        background: var(--surface);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-normal);
        position: relative;
        display: flex;
        flex-direction: column;
    }
    
    .coupon-card:hover {
        border-color: var(--primary-200);
        box-shadow: var(--shadow-lg);
        transform: translateY(-4px);
    }
    
    .coupon-card.used {
        opacity: 0.7;
        border-color: var(--border-color);
        background: var(--surface-variant);
    }
    
    .coupon-card.expired {
        opacity: 0.5;
        border-color: var(--error-200);
        background: linear-gradient(135deg, var(--error-50), var(--surface));
    }
    
    .coupon-header {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-lg);
        position: relative;
        overflow: hidden;
    }
    
    .coupon-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><circle cx="20" cy="20" r="10"/><circle cx="80" cy="80" r="15"/><circle cx="50" cy="50" r="8"/></svg>');
        background-size: 50px 50px;
    }
    
    .coupon-header.used {
        background: linear-gradient(135deg, var(--on-surface-variant), var(--surface-variant));
    }
    
    .coupon-header.expired {
        background: linear-gradient(135deg, var(--error-500), var(--error-600));
    }
    
    .coupon-amount {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: var(--space-xs);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        position: relative;
        z-index: 1;
    }
    
    .coupon-type {
        font-size: 0.875rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
    }
    
    .coupon-content {
        padding: var(--space-lg);
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .coupon-code-section {
        margin-bottom: var(--space-lg);
    }
    
    .coupon-code-label {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--space-xs);
        font-weight: 600;
    }
    
    .coupon-code {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-md);
        background: var(--surface-variant);
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-md);
        font-family: 'Courier New', monospace;
        font-weight: 700;
        color: var(--primary-600);
        font-size: 1rem;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .coupon-code:hover {
        background: var(--primary-50);
        border-color: var(--primary-300);
    }
    
    .coupon-code.used {
        color: var(--on-surface-variant);
        background: var(--surface-variant);
        border-color: var(--border-color);
        cursor: not-allowed;
    }
    
    .copy-btn {
        background: none;
        border: none;
        color: var(--primary-600);
        cursor: pointer;
        font-size: 1rem;
        transition: all var(--transition-fast);
        padding: var(--space-xs);
        border-radius: var(--radius-sm);
    }
    
    .copy-btn:hover {
        background: var(--primary-100);
        transform: scale(1.1);
    }
    
    .copy-btn.used {
        color: var(--on-surface-variant);
        cursor: not-allowed;
    }
    
    .coupon-description {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: var(--space-lg);
        flex: 1;
    }
    
    .coupon-details {
        display: grid;
        gap: var(--space-sm);
        margin-bottom: var(--space-lg);
    }
    
    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        padding: var(--space-xs) 0;
    }
    
    .detail-label {
        color: var(--on-surface-variant);
        font-weight: 500;
    }
    
    .detail-value {
        color: var(--on-surface);
        font-weight: 600;
    }
    
    .coupon-status {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--space-lg);
    }
    
    .status-active {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .status-used {
        background: var(--on-surface-variant);
        color: white;
        border: 1px solid var(--on-surface-variant);
    }
    
    .status-expired {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .coupon-actions {
        display: flex;
        gap: var(--space-sm);
    }
    
    .coupon-btn {
        flex: 1;
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--on-surface-variant);
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-xs);
        cursor: pointer;
    }
    
    .coupon-btn:hover:not(:disabled) {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
        transform: translateY(-1px);
    }
    
    .coupon-btn.primary {
        background: var(--primary-500);
        color: white;
        border-color: var(--primary-500);
    }
    
    .coupon-btn.primary:hover {
        background: var(--primary-600);
        border-color: var(--primary-600);
    }
    
    .coupon-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    
    .coupon-badge {
        position: absolute;
        top: var(--space-md);
        right: var(--space-md);
        background: var(--warning-500);
        color: white;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transform: rotate(15deg);
        box-shadow: var(--shadow-md);
    }
    
    .badge-new {
        background: var(--success-500);
    }
    
    .badge-expiring {
        background: var(--warning-500);
    }
    
    .badge-used {
        background: var(--on-surface-variant);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl) var(--space-xl);
        color: var(--on-surface-variant);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
        color: var(--primary-300);
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-md);
        color: var(--on-surface);
    }
    
    .empty-text {
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-xl);
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .empty-cta {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-xl);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-weight: 600;
        transition: all var(--transition-fast);
        box-shadow: var(--shadow-md);
    }
    
    .empty-cta:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .pagination-wrapper {
        margin-top: var(--space-2xl);
        display: flex;
        justify-content: center;
    }
    
    .coupon-pattern {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 20px;
        background: repeating-linear-gradient(
            90deg,
            transparent,
            transparent 10px,
            rgba(255, 255, 255, 0.1) 10px,
            rgba(255, 255, 255, 0.1) 15px
        );
        transform: translateY(-50%);
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .coupons-header {
            flex-direction: column;
            align-items: stretch;
            gap: var(--space-md);
        }
        
        .coupons-filters {
            justify-content: center;
        }
        
        .coupons-grid {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .breadcrumb {
            justify-content: center;
        }
        
        .coupon-actions {
            flex-direction: column;
        }
        
        .coupon-amount {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Coupons Hero -->
<section class="coupons-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    {{ __('Home') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('user.profile.show') }}" class="breadcrumb-link">
                    {{ __('Profile') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ __('Coupons') }}</span>
            </nav>
            
            <h1 class="hero-title">{{ __('My Coupons') }}</h1>
            <p class="hero-subtitle">{{ __('Save money on your next purchase with exclusive discount coupons') }}</p>
        </div>
    </div>
</section>

<!-- Coupons Container -->
<section class="coupons-container">
    <div class="container">
        <!-- Coupons Header -->
        <div class="coupons-header fade-in">
            <div class="coupons-count">
                {{ __('Total Coupons') }}: <strong>{{ $coupons->total() }}</strong>
                @php
                    $activeCoupons = $coupons->where('is_used', false)->where('valid_until', '>=', now())->count();
                @endphp
                @if($activeCoupons > 0)
                    <span style="color: var(--success-600); margin-left: var(--space-sm);">
                        ({{ $activeCoupons }} {{ __('Active') }})
                    </span>
                @endif
            </div>
            
            <div class="coupons-filters">
                <select class="filter-select" onchange="filterCoupons(this.value)">
                    <option value="all">{{ __('All Coupons') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="used">{{ __('Used') }}</option>
                    <option value="expired">{{ __('Expired') }}</option>
                    <option value="expiring">{{ __('Expiring Soon') }}</option>
                </select>
                
                <select class="filter-select" onchange="sortCoupons(this.value)">
                    <option value="newest">{{ __('Newest First') }}</option>
                    <option value="oldest">{{ __('Oldest First') }}</option>
                    <option value="amount_high">{{ __('Highest Value') }}</option>
                    <option value="amount_low">{{ __('Lowest Value') }}</option>
                    <option value="expiry_soon">{{ __('Expiring Soon') }}</option>
                </select>
            </div>
        </div>
        
        <!-- Coupons Grid -->
        @if($coupons->count() > 0)
            <div class="coupons-grid">
                @foreach($coupons as $coupon)
                    @php
                        $isExpired = $coupon->valid_until < now();
                        $isExpiringSoon = !$isExpired && $coupon->valid_until <= now()->addDays(7);
                        $status = $coupon->is_used ? 'used' : ($isExpired ? 'expired' : 'active');
                    @endphp
                    
                    <div class="coupon-card fade-in {{ $status }}" data-status="{{ $status }}" data-amount="{{ $coupon->amount }}" data-created="{{ $coupon->created_at->timestamp }}">
                        <!-- Coupon Badge -->
                        @if($coupon->is_used)
                            <div class="coupon-badge badge-used">{{ __('Used') }}</div>
                        @elseif($isExpired)
                            <div class="coupon-badge badge-expired">{{ __('Expired') }}</div>
                        @elseif($isExpiringSoon)
                            <div class="coupon-badge badge-expiring">{{ __('Expiring Soon') }}</div>
                        @elseif($coupon->created_at->diffInDays() < 7)
                            <div class="coupon-badge badge-new">{{ __('New') }}</div>
                        @endif
                        
                        <!-- Coupon Header -->
                        <div class="coupon-header {{ $status }}">
                            <div class="coupon-pattern"></div>
                            <div class="coupon-amount">${{ number_format($coupon->amount, 0) }}</div>
                            <div class="coupon-type">{{ __('Discount Coupon') }}</div>
                        </div>
                        
                        <!-- Coupon Content -->
                        <div class="coupon-content">
                            <!-- Coupon Code -->
                            <div class="coupon-code-section">
                                <div class="coupon-code-label">{{ __('Coupon Code') }}</div>
                                <div class="coupon-code {{ $coupon->is_used ? 'used' : '' }}" onclick="copyCouponCode('{{ $coupon->code }}', this)">
                                    <span>{{ $coupon->code }}</span>
                                    <button class="copy-btn {{ $coupon->is_used ? 'used' : '' }}" {{ $coupon->is_used ? 'disabled' : '' }}>
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Coupon Description -->
                            @if($coupon->description)
                                <div class="coupon-description">{{ $coupon->description }}</div>
                            @endif
                            
                            <!-- Coupon Details -->
                            <div class="coupon-details">
                                <div class="detail-item">
                                    <span class="detail-label">{{ __('Valid Until') }}</span>
                                    <span class="detail-value">{{ $coupon->valid_until->format('M d, Y') }}</span>
                                </div>
                                
                                @if($coupon->minimum_amount)
                                    <div class="detail-item">
                                        <span class="detail-label">{{ __('Minimum Order') }}</span>
                                        <span class="detail-value">${{ number_format($coupon->minimum_amount, 2) }}</span>
                                    </div>
                                @endif
                                
                                <div class="detail-item">
                                    <span class="detail-label">{{ __('Created') }}</span>
                                    <span class="detail-value">{{ $coupon->created_at->format('M d, Y') }}</span>
                                </div>
                                
                                @if($coupon->is_used && $coupon->used_at)
                                    <div class="detail-item">
                                        <span class="detail-label">{{ __('Used On') }}</span>
                                        <span class="detail-value">{{ $coupon->used_at->format('M d, Y') }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Coupon Status -->
                            <div class="coupon-status status-{{ $status }}">
                                @if($coupon->is_used)
                                    <i class="fas fa-check-circle"></i>
                                    {{ __('Used') }}
                                @elseif($isExpired)
                                    <i class="fas fa-times-circle"></i>
                                    {{ __('Expired') }}
                                @else
                                    <i class="fas fa-clock"></i>
                                    {{ __('Active') }}
                                @endif
                            </div>
                            
                            <!-- Coupon Actions -->
                            <div class="coupon-actions">
                                @if(!$coupon->is_used && !$isExpired)
                                    <a href="{{ route('products.index', ['coupon' => $coupon->code]) }}" class="coupon-btn primary">
                                        <i class="fas fa-shopping-cart"></i>
                                        {{ __('Shop Now') }}
                                    </a>
                                    <button class="coupon-btn" onclick="copyCouponCode('{{ $coupon->code }}')">
                                        <i class="fas fa-copy"></i>
                                        {{ __('Copy Code') }}
                                    </button>
                                @else
                                    <button class="coupon-btn" disabled>
                                        <i class="fas fa-ban"></i>
                                        {{ $coupon->is_used ? __('Already Used') : __('Expired') }}
                                    </button>
                                    @if($coupon->is_used && $coupon->order_id)
                                        <a href="{{ route('orders.show', $coupon->order_id) }}" class="coupon-btn">
                                            <i class="fas fa-receipt"></i>
                                            {{ __('View Order') }}
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($coupons->hasPages())
                <div class="pagination-wrapper fade-in">
                    {{ $coupons->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state fade-in">
                <div class="empty-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h2 class="empty-title">{{ __('No Coupons Available') }}</h2>
                <p class="empty-text">
                    {{ __('You don\'t have any coupons yet. Complete orders and participate in promotions to earn discount coupons.') }}
                </p>
                <a href="{{ route('products.index') }}" class="empty-cta">
                    <i class="fas fa-shopping-bag"></i>
                    {{ __('Start Shopping') }}
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Initialize animations
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
    
    // Copy coupon code to clipboard
    function copyCouponCode(code, element = null) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(() => {
                showCopySuccess(element);
                showNotification(`{{ __("Coupon code") }} "${code}" {{ __("copied to clipboard") }}`, 'success');
            }).catch(() => {
                fallbackCopyTextToClipboard(code, element);
            });
        } else {
            fallbackCopyTextToClipboard(code, element);
        }
    }
    
    // Fallback copy method for older browsers
    function fallbackCopyTextToClipboard(text, element) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.top = '0';
        textArea.style.left = '0';
        textArea.style.width = '2em';
        textArea.style.height = '2em';
        textArea.style.padding = '0';
        textArea.style.border = 'none';
        textArea.style.outline = 'none';
        textArea.style.boxShadow = 'none';
        textArea.style.background = 'transparent';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            showCopySuccess(element);
            showNotification(`{{ __("Coupon code") }} "${text}" {{ __("copied to clipboard") }}`, 'success');
        } catch (err) {
            showNotification('{{ __("Failed to copy coupon code") }}', 'error');
        }
        
        document.body.removeChild(textArea);
    }
    
    // Show copy success animation
    function showCopySuccess(element) {
        if (element) {
            const copyBtn = element.querySelector('.copy-btn');
            if (copyBtn) {
                const originalIcon = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check"></i>';
                copyBtn.style.color = 'var(--success-600)';
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalIcon;
                    copyBtn.style.color = '';
                }, 2000);
            }
        }
    }
    
    // Filter coupons by status
    function filterCoupons(filter) {
        const couponCards = document.querySelectorAll('.coupon-card');
        
        couponCards.forEach(card => {
            let show = false;
            const status = card.dataset.status;
            
            switch (filter) {
                case 'all':
                    show = true;
                    break;
                case 'active':
                    show = status === 'active';
                    break;
                case 'used':
                    show = status === 'used';
                    break;
                case 'expired':
                    show = status === 'expired';
                    break;
                case 'expiring':
                    show = status === 'active' && card.querySelector('.badge-expiring');
                    break;
            }
            
            if (show) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
        
        // Update URL parameter
        const url = new URL(window.location);
        if (filter !== 'all') {
            url.searchParams.set('filter', filter);
        } else {
            url.searchParams.delete('filter');
        }
        window.history.pushState({}, '', url);
    }
    
    // Sort coupons
    function sortCoupons(sortBy) {
        const grid = document.querySelector('.coupons-grid');
        const cards = Array.from(grid.querySelectorAll('.coupon-card'));
        
        cards.sort((a, b) => {
            switch (sortBy) {
                case 'newest':
                    return parseInt(b.dataset.created) - parseInt(a.dataset.created);
                case 'oldest':
                    return parseInt(a.dataset.created) - parseInt(b.dataset.created);
                case 'amount_high':
                    return parseFloat(b.dataset.amount) - parseFloat(a.dataset.amount);
                case 'amount_low':
                    return parseFloat(a.dataset.amount) - parseFloat(b.dataset.amount);
                case 'expiry_soon':
                    // Prioritize expiring soon, then by expiry date
                    const aExpiring = a.querySelector('.badge-expiring') ? 1 : 0;
                    const bExpiring = b.querySelector('.badge-expiring') ? 1 : 0;
                    if (aExpiring !== bExpiring) {
                        return bExpiring - aExpiring;
                    }
                    return parseInt(a.dataset.created) - parseInt(b.dataset.created);
                default:
                    return 0;
            }
        });
        
        // Re-append sorted cards
        cards.forEach(card => {
            grid.appendChild(card);
        });
        
        // Animate cards
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        });
        
        // Update URL parameter
        const url = new URL(window.location);
        url.searchParams.set('sort', sortBy);
        window.history.pushState({}, '', url);
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
            box-shadow: var(--shadow-xl);
            animation: slideIn 0.3s ease-out;
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
    
    // Apply filters from URL parameters on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        const filter = urlParams.get('filter');
        if (filter) {
            const filterSelect = document.querySelector('.filter-select');
            filterSelect.value = filter;
            filterCoupons(filter);
        }
        
        const sort = urlParams.get('sort');
        if (sort) {
            const sortSelect = document.querySelectorAll('.filter-select')[1];
            sortSelect.value = sort;
        }
        
        // Add click event to all coupon codes
        document.querySelectorAll('.coupon-code:not(.used)').forEach(codeElement => {
            codeElement.addEventListener('click', function() {
                const code = this.querySelector('span').textContent;
                copyCouponCode(code, this);
            });
        });
        
        // Update expiring badges
        updateExpiringBadges();
    });
    
    // Update expiring badges based on current time
    function updateExpiringBadges() {
        const now = new Date();
        const sevenDaysFromNow = new Date(now.getTime() + (7 * 24 * 60 * 60 * 1000));
        
        document.querySelectorAll('.coupon-card').forEach(card => {
            const validUntilText = card.querySelector('.detail-value');
            if (validUntilText) {
                const validUntil = new Date(validUntilText.textContent);
                const badge = card.querySelector('.coupon-badge');
                
                if (validUntil <= now && !card.classList.contains('used') && !card.classList.contains('expired')) {
                    // Expired
                    card.classList.add('expired');
                    card.dataset.status = 'expired';
                    if (badge && !badge.classList.contains('badge-used')) {
                        badge.textContent = '{{ __("Expired") }}';
                        badge.className = 'coupon-badge badge-expired';
                    }
                } else if (validUntil <= sevenDaysFromNow && validUntil > now && !card.classList.contains('used')) {
                    // Expiring soon
                    if (badge && !badge.classList.contains('badge-used') && !badge.classList.contains('badge-expiring')) {
                        badge.textContent = '{{ __("Expiring Soon") }}';
                        badge.className = 'coupon-badge badge-expiring';
                    }
                }
            }
        });
    }
    
    // Auto-refresh expiring status every minute
    setInterval(updateExpiringBadges, 60000);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Copy first active coupon with Ctrl+C
        if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
            const firstActiveCoupon = document.querySelector('.coupon-card:not(.used):not(.expired) .coupon-code span');
            if (firstActiveCoupon) {
                e.preventDefault();
                copyCouponCode(firstActiveCoupon.textContent);
            }
        }
    });
    
    // Add hover effects to coupon cards
    document.querySelectorAll('.coupon-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (!this.classList.contains('used') && !this.classList.contains('expired')) {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Auto-apply coupon when redirecting to shop
    document.querySelectorAll('a[href*="coupon="]').forEach(link => {
        link.addEventListener('click', function(e) {
            const url = new URL(this.href);
            const couponCode = url.searchParams.get('coupon');
            
            if (couponCode) {
                // Store coupon in session storage for auto-apply
                sessionStorage.setItem('auto_apply_coupon', couponCode);
                showNotification(`{{ __("Coupon") }} "${couponCode}" {{ __("will be automatically applied at checkout") }}`, 'info');
            }
        });
    });
    
    // Countdown timer for expiring coupons
    function startCountdownTimers() {
        document.querySelectorAll('.coupon-card[data-status="active"]').forEach(card => {
            const expiryText = card.querySelector('.detail-value').textContent;
            const expiryDate = new Date(expiryText + ' 23:59:59');
            const badge = card.querySelector('.badge-expiring');
            
            if (badge && expiryDate > new Date()) {
                const updateCountdown = () => {
                    const now = new Date();
                    const timeLeft = expiryDate - now;
                    
                    if (timeLeft <= 0) {
                        card.classList.add('expired');
                        card.dataset.status = 'expired';
                        badge.textContent = '{{ __("Expired") }}';
                        badge.className = 'coupon-badge badge-expired';
                        return;
                    }
                    
                    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    
                    if (days > 0) {
                        badge.textContent = `${days}d ${hours}h`;
                    } else {
                        badge.textContent = `${hours}h`;
                    }
                };
                
                updateCountdown();
                setInterval(updateCountdown, 60000); // Update every minute
            }
        });
    }
    
    // Start countdown timers on page load
    document.addEventListener('DOMContentLoaded', startCountdownTimers);
</script>

<style>
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
    
    @keyframes slideOut {
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    /* Pulse animation for expiring coupons */
    .badge-expiring {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
        }
    }
    
    /* Copy animation */
    .coupon-code.copying {
        background: var(--success-50);
        border-color: var(--success-300);
        animation: copyFlash 0.5s ease-out;
    }
    
    @keyframes copyFlash {
        0% { background: var(--success-100); }
        50% { background: var(--success-200); }
        100% { background: var(--success-50); }
    }
</style>
@endpush