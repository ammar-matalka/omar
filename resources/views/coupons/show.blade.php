@extends('layouts.app')

@section('title', __('Coupon Details'))

@section('content')
<div class="container" style="padding: var(--space-2xl) var(--space-md);">
    <!-- Breadcrumb -->
    <nav style="margin-bottom: var(--space-xl);">
        <div style="display: flex; align-items: center; gap: var(--space-sm); font-size: 0.875rem; color: var(--on-surface-variant);">
            <a href="{{ route('home') }}" style="color: var(--primary-600); text-decoration: none;">{{ __('Home') }}</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <a href="{{ route('coupons.index') }}" style="color: var(--primary-600); text-decoration: none;">{{ __('My Coupons') }}</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <span>{{ $coupon->code }}</span>
        </div>
    </nav>

    <!-- Header -->
    <div style="text-align: center; margin-bottom: var(--space-2xl);">
        <h1 style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, var(--primary-500), var(--secondary-500)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: var(--space-md);">
            {{ __('Coupon Details') }}
        </h1>
        
        <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <code style="background: var(--primary-50); color: var(--primary-700); padding: var(--space-sm) var(--space-lg); border-radius: var(--radius-lg); font-weight: 700; font-size: 1.5rem; font-family: monospace;">
                {{ $coupon->code }}
            </code>
            
            @if($coupon->is_used)
                <span class="badge" style="background: var(--info-100); color: var(--info-700); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-lg); font-weight: 600;">
                    <i class="fas fa-check-circle"></i>
                    {{ __('Used') }}
                </span>
            @elseif($coupon->valid_until < now())
                <span class="badge" style="background: var(--warning-100); color: var(--warning-700); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-lg); font-weight: 600;">
                    <i class="fas fa-clock"></i>
                    {{ __('Expired') }}
                </span>
            @else
                <span class="badge" style="background: var(--success-100); color: var(--success-700); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-lg); font-weight: 600;">
                    <i class="fas fa-star"></i>
                    {{ __('Active') }}
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-3" style="gap: var(--space-2xl); max-width: 1200px; margin: 0 auto;">
        <!-- Main Coupon Display -->
        <div style="grid-column: span 2;">
            <!-- Large Coupon Card -->
            <div class="card" style="border: 3px dashed {{ $coupon->is_used ? 'var(--gray-300)' : ($coupon->valid_until < now() ? 'var(--warning-400)' : 'var(--primary-400)') }}; background: {{ $coupon->is_used ? 'var(--gray-50)' : ($coupon->valid_until < now() ? 'var(--warning-50)' : 'linear-gradient(135deg, var(--primary-50), var(--primary-100))') }}; padding: 0; margin-bottom: var(--space-xl); position: relative; overflow: hidden;">
                
                <!-- Coupon Content -->
                <div style="background: white; margin: var(--space-lg); border-radius: var(--radius-xl); padding: var(--space-2xl); box-shadow: var(--shadow-xl); {{ $coupon->is_used || $coupon->valid_until < now() ? 'opacity: 0.8;' : '' }}">
                    
                    <!-- Brand Header -->
                    <div style="text-align: center; margin-bottom: var(--space-xl);">
                        <div style="font-size: 1rem; color: var(--gray-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 2px; font-weight: 700;">
                            {{ config('app.name') }}
                        </div>
                        <div style="width: 60px; height: 2px; background: linear-gradient(90deg, var(--primary-500), var(--secondary-500)); margin: 0 auto;"></div>
                    </div>

                    <!-- Coupon Code -->
                    <div style="text-align: center; margin-bottom: var(--space-2xl);">
                        <div style="font-size: 3rem; font-weight: 900; color: var(--primary-600); font-family: monospace; letter-spacing: 4px; margin-bottom: var(--space-md);">
                            {{ $coupon->code }}
                        </div>
                        <button onclick="copyToClipboard('{{ $coupon->code }}')" class="btn btn-primary">
                            <i class="fas fa-copy"></i>
                            {{ __('Copy Code') }}
                        </button>
                    </div>

                    <!-- Discount Amount -->
                    <div style="text-align: center; margin-bottom: var(--space-2xl);">
                        <div style="font-size: 1rem; color: var(--gray-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                            {{ __('Discount Amount') }}
                        </div>
                        <div style="font-size: 4rem; font-weight: 900; color: var(--success-600); line-height: 1;">
                            ${{ number_format($coupon->amount, 2) }}
                        </div>
                        <div style="font-size: 1.125rem; color: var(--success-600); font-weight: 600; margin-top: var(--space-sm);">
                            {{ __('OFF YOUR PURCHASE') }}
                        </div>
                    </div>

                    <!-- Terms -->
                    <div style="border-top: 2px dashed var(--gray-200); padding-top: var(--space-xl);">
                        <div class="grid grid-cols-2" style="gap: var(--space-xl);">
                            <div style="text-align: center;">
                                <div style="font-size: 0.875rem; color: var(--gray-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                    {{ __('Minimum Purchase') }}
                                </div>
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--on-surface);">
                                    @if($coupon->min_purchase_amount > 0)
                                        ${{ number_format($coupon->min_purchase_amount, 2) }}
                                    @else
                                        {{ __('No Minimum') }}
                                    @endif
                                </div>
                            </div>

                            <div style="text-align: center;">
                                <div style="font-size: 0.875rem; color: var(--gray-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                    {{ __('Valid Until') }}
                                </div>
                                <div style="font-size: 1.5rem; font-weight: 700; color: {{ $coupon->valid_until < now() ? 'var(--error-600)' : 'var(--on-surface)' }};">
                                    {{ $coupon->valid_until->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Used/Expired Overlay -->
                @if($coupon->is_used || $coupon->valid_until < now())
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-20deg); z-index: 10; pointer-events: none;">
                    <div style="background: {{ $coupon->is_used ? 'var(--info-500)' : 'var(--warning-500)' }}; color: white; padding: var(--space-lg) var(--space-2xl); border-radius: var(--radius-xl); font-weight: 900; text-transform: uppercase; letter-spacing: 3px; font-size: 2rem; box-shadow: var(--shadow-2xl); opacity: 0.9; border: 4px solid white;">
                        {{ $coupon->is_used ? __('USED') : __('EXPIRED') }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Usage Information -->
            @if($coupon->is_used && $coupon->order)
            <div class="card" style="margin-bottom: var(--space-xl);">
                <div class="card-header">
                    <h3 style="display: flex; align-items: center; gap: var(--space-sm); margin: 0; font-size: 1.125rem; font-weight: 600;">
                        <i class="fas fa-shopping-cart" style="color: var(--info-500);"></i>
                        {{ __('Usage Information') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div style="background: var(--info-50); padding: var(--space-lg); border-radius: var(--radius-lg); border: 1px solid var(--info-200);">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <div style="font-weight: 600; font-size: 1.125rem; color: var(--info-700); margin-bottom: var(--space-sm);">
                                    {{ __('Used in Order #:id', ['id' => $coupon->order->id]) }}
                                </div>
                                <div style="color: var(--gray-600); margin-bottom: var(--space-sm);">
                                    {{ __('Order Total: $:amount', ['amount' => number_format($coupon->order->total_amount, 2)]) }}
                                </div>
                                <div style="color: var(--gray-500); font-size: 0.875rem;">
                                    {{ __('Used on :date', ['date' => $coupon->updated_at->format('M d, Y h:i A')]) }}
                                </div>
                            </div>
                            <a href="{{ route('orders.show', $coupon->order) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i>
                                {{ __('View Order') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div style="display: flex; gap: var(--space-md); justify-content: center;">
                @if(!$coupon->is_used && $coupon->valid_until >= now())
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i>
                    {{ __('Start Shopping') }}
                </a>
                @endif
                
                <a href="{{ route('coupons.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Coupons') }}
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Quick Stats -->
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 style="display: flex; align-items: center; gap: var(--space-sm); margin: 0; font-size: 1.125rem; font-weight: 600;">
                        <i class="fas fa-chart-bar" style="color: var(--primary-500);"></i>
                        {{ __('Coupon Details') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div style="space-y: var(--space-md);">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--border-color); margin-bottom: var(--space-md);">
                            <span style="color: var(--on-surface-variant);">{{ __('Code') }}</span>
                            <code style="background: var(--surface-variant); padding: 2px 6px; border-radius: 4px; font-weight: 600;">{{ $coupon->code }}</code>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--border-color); margin-bottom: var(--space-md);">
                            <span style="color: var(--on-surface-variant);">{{ __('Discount') }}</span>
                            <span style="font-weight: 600; color: var(--success-600);">${{ number_format($coupon->amount, 2) }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--border-color); margin-bottom: var(--space-md);">
                            <span style="color: var(--on-surface-variant);">{{ __('Min. Purchase') }}</span>
                            <span style="font-weight: 600;">
                                @if($coupon->min_purchase_amount > 0)
                                    ${{ number_format($coupon->min_purchase_amount, 2) }}
                                @else
                                    {{ __('None') }}
                                @endif
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--border-color); margin-bottom: var(--space-md);">
                            <span style="color: var(--on-surface-variant);">{{ __('Created') }}</span>
                            <span style="font-weight: 600;">{{ $coupon->created_at->format('M d, Y') }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--border-color); margin-bottom: var(--space-md);">
                            <span style="color: var(--on-surface-variant);">{{ __('Expires') }}</span>
                            <span style="font-weight: 600; color: {{ $coupon->valid_until < now() ? 'var(--error-600)' : 'var(--on-surface)' }};">
                                {{ $coupon->valid_until->format('M d, Y') }}
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0;">
                            <span style="color: var(--on-surface-variant);">{{ __('Status') }}</span>
                            @if($coupon->is_used)
                                <span class="badge badge-info">{{ __('Used') }}</span>
                            @elseif($coupon->valid_until < now())
                                <span class="badge badge-warning">{{ __('Expired') }}</span>
                            @else
                                <span class="badge badge-success">{{ __('Active') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Remaining -->
            @if(!$coupon->is_used && $coupon->valid_until >= now())
            <div class="card" style="margin-bottom: var(--space-lg);">
                <div class="card-header">
                    <h3 style="display: flex; align-items: center; gap: var(--space-sm); margin: 0; font-size: 1.125rem; font-weight: 600;">
                        <i class="fas fa-clock" style="color: var(--warning-500);"></i>
                        {{ __('Time Remaining') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div style="text-align: center;">
                        <div id="countdown" style="font-size: 1.5rem; font-weight: 700; color: var(--warning-600); margin-bottom: var(--space-md);">
                            {{ $coupon->valid_until->diffForHumans() }}
                        </div>
                        <div style="font-size: 0.875rem; color: var(--on-surface-variant);">
                            {{ __('Until expiration') }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- How to Use -->
            <div class="card">
                <div class="card-header">
                    <h3 style="display: flex; align-items: center; gap: var(--space-sm); margin: 0; font-size: 1.125rem; font-weight: 600;">
                        <i class="fas fa-question-circle" style="color: var(--primary-500);"></i>
                        {{ __('How to Use') }}
                    </h3>
                </div>
                <div class="card-body">
                    <ol style="list-style: none; padding: 0; margin: 0; counter-reset: step-counter;">
                        <li style="counter-increment: step-counter; display: flex; align-items: flex-start; gap: var(--space-md); margin-bottom: var(--space-lg); position: relative;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-500); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.875rem; flex-shrink: 0;">
                                1
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: var(--space-xs);">{{ __('Copy the Code') }}</div>
                                <div style="color: var(--on-surface-variant); font-size: 0.875rem;">{{ __('Click the "Copy Code" button above') }}</div>
                            </div>
                        </li>
                        
                        <li style="counter-increment: step-counter; display: flex; align-items: flex-start; gap: var(--space-md); margin-bottom: var(--space-lg); position: relative;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--secondary-500); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.875rem; flex-shrink: 0;">
                                2
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: var(--space-xs);">{{ __('Shop Products') }}</div>
                                <div style="color: var(--on-surface-variant); font-size: 0.875rem;">{{ __('Add items to your cart') }}</div>
                            </div>
                        </li>
                        
                        <li style="counter-increment: step-counter; display: flex; align-items: flex-start; gap: var(--space-md); position: relative;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--accent-500); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.875rem; flex-shrink: 0;">
                                3
                            </div>
                            <div>
                                <div style="font-weight: 600; margin-bottom: var(--space-xs);">{{ __('Apply at Checkout') }}</div>
                                <div style="color: var(--on-surface-variant); font-size: 0.875rem;">{{ __('Paste the code during checkout') }}</div>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showSuccessNotification('{{ __('Coupon code copied to clipboard!') }}');
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        showErrorNotification('{{ __('Failed to copy coupon code') }}');
    });
}

// Update countdown every minute for active coupons
@if(!$coupon->is_used && $coupon->valid_until >= now())
setInterval(function() {
    const countdownElement = document.getElementById('countdown');
    if (countdownElement) {
        const now = new Date();
        const expiryDate = new Date('{{ $coupon->valid_until->toISOString() }}');
        const diff = expiryDate - now;
        
        if (diff <= 0) {
            countdownElement.textContent = '{{ __('Expired') }}';
            countdownElement.style.color = 'var(--error-600)';
            location.reload(); // Reload to update the page state
        } else {
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            if (days > 0) {
                countdownElement.textContent = `${days} {{ __('days') }}, ${hours} {{ __('hours') }}`;
            } else if (hours > 0) {
                countdownElement.textContent = `${hours} {{ __('hours') }}, ${minutes} {{ __('minutes') }}`;
            } else {
                countdownElement.textContent = `${minutes} {{ __('minutes') }}`;
                countdownElement.style.color = 'var(--error-600)';
            }
        }
    }
}, 60000); // Update every minute
@endif
</script>

<style>
@media (max-width: 768px) {
    .grid-cols-3 {
        grid-template-columns: 1fr !important;
    }
    
    .grid-cols-2 {
        grid-template-columns: 1fr !important;
    }
    
    .coupon-card {
        margin: var(--space-md) !important;
        padding: var(--space-lg) !important;
    }
}
</style>
@endsection