@extends('layouts.app')

@section('title', __('My Coupons'))

@section('content')
<div class="container" style="padding: var(--space-2xl) var(--space-md);">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: var(--space-2xl);">
        <h1 style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, var(--primary-500), var(--secondary-500)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: var(--space-md);">
            {{ __('My Coupons') }}
        </h1>
        <p style="font-size: 1.125rem; color: var(--on-surface-variant); max-width: 600px; margin: 0 auto;">
            {{ __('View and manage your discount coupons. Use them during checkout to save money on your purchases.') }}
        </p>
    </div>

    @if($coupons->count() > 0)
        <!-- Coupons Grid -->
        <div class="grid grid-cols-1" style="gap: var(--space-lg); max-width: 800px; margin: 0 auto;">
            @foreach($coupons as $coupon)
            <div class="coupon-card" style="border: 2px dashed {{ $coupon->is_used ? 'var(--gray-300)' : ($coupon->valid_until < now() ? 'var(--warning-400)' : 'var(--primary-400)') }}; border-radius: var(--radius-xl); padding: 0; background: {{ $coupon->is_used ? 'var(--gray-50)' : ($coupon->valid_until < now() ? 'var(--warning-50)' : 'linear-gradient(135deg, var(--primary-50), var(--primary-100))') }}; position: relative; overflow: hidden; transition: all var(--transition-normal);">
                
                <!-- Status Badge -->
                <div style="position: absolute; top: var(--space-md); right: var(--space-md); z-index: 10;">
                    @if($coupon->is_used)
                        <span class="badge" style="background: var(--info-100); color: var(--info-700); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-lg); font-weight: 600; font-size: 0.75rem;">
                            <i class="fas fa-check-circle"></i>
                            {{ __('Used') }}
                        </span>
                    @elseif($coupon->valid_until < now())
                        <span class="badge" style="background: var(--warning-100); color: var(--warning-700); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-lg); font-weight: 600; font-size: 0.75rem;">
                            <i class="fas fa-clock"></i>
                            {{ __('Expired') }}
                        </span>
                    @else
                        <span class="badge" style="background: var(--success-100); color: var(--success-700); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-lg); font-weight: 600; font-size: 0.75rem;">
                            <i class="fas fa-star"></i>
                            {{ __('Active') }}
                        </span>
                    @endif
                </div>

                <!-- Coupon Content -->
                <div style="display: flex; background: white; border-radius: var(--radius-lg); margin: var(--space-md); box-shadow: var(--shadow-lg); overflow: hidden; {{ $coupon->is_used || $coupon->valid_until < now() ? 'opacity: 0.7;' : '' }}">
                    
                    <!-- Left Side - Coupon Info -->
                    <div style="flex: 1; padding: var(--space-xl);">
                        <!-- Brand -->
                        <div style="font-size: 0.875rem; color: var(--gray-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                            {{ config('app.name') }}
                        </div>

                        <!-- Coupon Code -->
                        <div style="margin-bottom: var(--space-lg);">
                            <div style="font-size: 2rem; font-weight: 900; color: var(--primary-600); font-family: monospace; margin-bottom: var(--space-sm);">
                                {{ $coupon->code }}
                            </div>
                            <button onclick="copyToClipboard('{{ $coupon->code }}')" class="btn btn-sm btn-secondary" style="margin-top: var(--space-sm);">
                                <i class="fas fa-copy"></i>
                                {{ __('Copy Code') }}
                            </button>
                        </div>

                        <!-- Coupon Details -->
                        <div style="space-y: var(--space-sm);">
                            @if($coupon->min_purchase_amount > 0)
                            <div style="display: flex; align-items: center; gap: var(--space-sm); color: var(--gray-600); font-size: 0.875rem; margin-bottom: var(--space-sm);">
                                <i class="fas fa-shopping-cart" style="width: 16px;"></i>
                                <span>{{ __('Minimum purchase: $:amount', ['amount' => number_format($coupon->min_purchase_amount, 2)]) }}</span>
                            </div>
                            @endif

                            <div style="display: flex; align-items: center; gap: var(--space-sm); color: var(--gray-600); font-size: 0.875rem; margin-bottom: var(--space-sm);">
                                <i class="fas fa-calendar" style="width: 16px;"></i>
                                <span>
                                    @if($coupon->is_used)
                                        {{ __('Used on :date', ['date' => $coupon->updated_at->format('M d, Y')]) }}
                                    @elseif($coupon->valid_until < now())
                                        {{ __('Expired on :date', ['date' => $coupon->valid_until->format('M d, Y')]) }}
                                    @else
                                        {{ __('Valid until :date', ['date' => $coupon->valid_until->format('M d, Y')]) }}
                                    @endif
                                </span>
                            </div>

                            <div style="display: flex; align-items: center; gap: var(--space-sm); color: var(--gray-600); font-size: 0.875rem;">
                                <i class="fas fa-clock" style="width: 16px;"></i>
                                <span>
                                    @if($coupon->is_used)
                                        {{ __('Used :time', ['time' => $coupon->updated_at->diffForHumans()]) }}
                                    @elseif($coupon->valid_until < now())
                                        {{ __('Expired :time', ['time' => $coupon->valid_until->diffForHumans()]) }}
                                    @else
                                        {{ __('Expires :time', ['time' => $coupon->valid_until->diffForHumans()]) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div style="width: 2px; background: repeating-linear-gradient(to bottom, var(--gray-300) 0px, var(--gray-300) 10px, transparent 10px, transparent 20px); margin: var(--space-lg) 0;"></div>

                    <!-- Right Side - Discount Amount -->
                    <div style="width: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: var(--space-xl); text-align: center; background: linear-gradient(135deg, var(--primary-50), var(--primary-100));">
                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: var(--space-sm); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                            {{ __('Discount') }}
                        </div>
                        <div style="font-size: 3rem; font-weight: 900; color: var(--success-600); line-height: 1; margin-bottom: var(--space-sm);">
                            ${{ number_format($coupon->amount, 0) }}
                        </div>
                        @if($coupon->amount != floor($coupon->amount))
                        <div style="font-size: 1rem; color: var(--success-600); font-weight: 600;">
                            .{{ str_pad(($coupon->amount - floor($coupon->amount)) * 100, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        @endif
                        <div style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; letter-spacing: 1px;">
                            {{ __('Off') }}
                        </div>
                        
                        @if(!$coupon->is_used && $coupon->valid_until >= now())
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm" style="margin-top: var(--space-md); width: 100%;">
                            <i class="fas fa-shopping-bag"></i>
                            {{ __('Shop Now') }}
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Used/Expired Overlay -->
                @if($coupon->is_used || $coupon->valid_until < now())
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-15deg); z-index: 20; pointer-events: none;">
                    <div style="background: {{ $coupon->is_used ? 'var(--info-500)' : 'var(--warning-500)' }}; color: white; padding: var(--space-md) var(--space-xl); border-radius: var(--radius-lg); font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 1.5rem; box-shadow: var(--shadow-xl); opacity: 0.9;">
                        {{ $coupon->is_used ? __('USED') : __('EXPIRED') }}
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div style="padding: var(--space-md) var(--space-xl) var(--space-xl);">
                    <div style="display: flex; gap: var(--space-md); align-items: center;">
                        <a href="{{ route('coupons.show', $coupon) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-eye"></i>
                            {{ __('View Details') }}
                        </a>
                        
                        @if(!$coupon->is_used && $coupon->valid_until >= now())
                        <div style="flex: 1; text-align: right;">
                            <span style="font-size: 0.875rem; color: var(--success-600); font-weight: 600;">
                                <i class="fas fa-check-circle"></i>
                                {{ __('Ready to use') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($coupons->hasPages())
        <div style="margin-top: var(--space-2xl); display: flex; justify-content: center;">
            {{ $coupons->links() }}
        </div>
        @endif

    @else
        <!-- Empty State -->
        <div style="text-align: center; padding: var(--space-3xl) var(--space-md); max-width: 500px; margin: 0 auto;">
            <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-100), var(--primary-200)); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-xl);">
                <i class="fas fa-ticket-alt" style="font-size: 3rem; color: var(--primary-500);"></i>
            </div>
            
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--on-surface); margin-bottom: var(--space-md);">
                {{ __('No Coupons Yet') }}
            </h2>
            
            <p style="color: var(--on-surface-variant); margin-bottom: var(--space-xl); line-height: 1.6;">
                {{ __('You don\'t have any coupons at the moment. Coupons will appear here when they are assigned to your account or when you receive promotional offers.') }}
            </p>
            
            <div style="display: flex; gap: var(--space-md); justify-content: center;">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i>
                    {{ __('Browse Products') }}
                </a>
                
                <a href="{{ route('user.conversations.create') }}" class="btn btn-secondary">
                    <i class="fas fa-comments"></i>
                    {{ __('Contact Support') }}
                </a>
            </div>
        </div>
    @endif

    <!-- How to Use Section -->
    <div style="margin-top: var(--space-3xl); background: var(--surface-variant); padding: var(--space-2xl); border-radius: var(--radius-xl);">
        <h3 style="font-size: 1.25rem; font-weight: 700; text-align: center; margin-bottom: var(--space-xl); color: var(--on-surface);">
            {{ __('How to Use Your Coupons') }}
        </h3>
        
        <div class="grid grid-cols-3" style="gap: var(--space-xl);">
            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--primary-500); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md); color: white;">
                    <i class="fas fa-copy" style="font-size: 1.5rem;"></i>
                </div>
                <h4 style="font-weight: 600; margin-bottom: var(--space-sm);">{{ __('1. Copy Code') }}</h4>
                <p style="color: var(--on-surface-variant); font-size: 0.875rem;">{{ __('Copy the coupon code from your coupon card') }}</p>
            </div>
            
            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--secondary-500); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md); color: white;">
                    <i class="fas fa-shopping-cart" style="font-size: 1.5rem;"></i>
                </div>
                <h4 style="font-weight: 600; margin-bottom: var(--space-sm);">{{ __('2. Shop') }}</h4>
                <p style="color: var(--on-surface-variant); font-size: 0.875rem;">{{ __('Add products to your cart and proceed to checkout') }}</p>
            </div>
            
            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--accent-500); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md); color: white;">
                    <i class="fas fa-percent" style="font-size: 1.5rem;"></i>
                </div>
                <h4 style="font-weight: 600; margin-bottom: var(--space-sm);">{{ __('3. Apply & Save') }}</h4>
                <p style="color: var(--on-surface-variant); font-size: 0.875rem;">{{ __('Paste the code at checkout and enjoy your discount') }}</p>
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

// Add hover effects to coupon cards
document.addEventListener('DOMContentLoaded', function() {
    const couponCards = document.querySelectorAll('.coupon-card');
    
    couponCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (!this.style.opacity || this.style.opacity === '1') {
                this.style.transform = 'translateY(-4px)';
                this.style.boxShadow = 'var(--shadow-xl)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>

<style>
@media (max-width: 768px) {
    .coupon-card > div:first-child {
        flex-direction: column !important;
    }
    
    .coupon-card > div:first-child > div:last-child {
        width: 100% !important;
        border-radius: 0 0 var(--radius-lg) var(--radius-lg) !important;
    }
    
    .grid-cols-3 {
        grid-template-columns: 1fr !important;
    }
}

.coupon-card {
    transition: all var(--transition-normal);
}

.coupon-card:hover {
    transform: translateY(-2px);
}
</style>
@endsection