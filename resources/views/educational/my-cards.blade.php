@extends('layouts.app')

@section('title', 'بطاقاتي التعليمية')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/educational.css') }}">
@endpush

@section('content')
<div class="container" style="margin-top: var(--space-xl); margin-bottom: var(--space-3xl);">
    <!-- Header Section -->
    <div class="form-header" style="text-align: center; margin-bottom: var(--space-2xl); padding: var(--space-2xl); background: linear-gradient(135deg, var(--primary-50), var(--success-50)); border-radius: var(--radius-xl); position: relative; overflow: hidden;">
        <div style="position: relative; z-index: 2;">
            <!-- Breadcrumb -->
            <nav style="margin-bottom: var(--space-lg);">
                <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-sm); font-size: 0.9rem; color: var(--gray-600);">
                    <a href="{{ route('educational.index') }}" style="color: var(--primary-600); text-decoration: none; transition: color 0.2s;">النظام التعليمي</a>
                    <i class="fas fa-chevron-left" style="font-size: 0.7rem; color: var(--gray-400);"></i>
                    <span style="color: var(--gray-800); font-weight: 600;">بطاقاتي التعليمية</span>
                </div>
            </nav>
            
            <div style="font-size: 3rem; margin-bottom: var(--space-md);">🎓</div>
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: var(--space-md); color: var(--gray-800);">
                بطاقاتي التعليمية
            </h1>
            <p style="font-size: 1.1rem; color: var(--gray-600); max-width: 500px; margin: 0 auto;">
                إدارة وعرض جميع البطاقات التعليمية الخاصة بك
            </p>
        </div>
    </div>

    @if(isset($message))
        <div class="alert alert-info fade-in" style="text-align: center; margin-bottom: var(--space-2xl);">
            <i class="fas fa-info-circle"></i>
            {{ $message }}
            <div style="margin-top: var(--space-md);">
                <a href="{{ route('educational.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    احصل على بطاقات تعليمية
                </a>
            </div>
        </div>
    @else
        <!-- Quick Actions -->
        <div style="margin-bottom: var(--space-2xl); text-align: center;">
            <div class="grid grid-cols-3" style="max-width: 600px; margin: 0 auto; gap: var(--space-md);">
                <a href="{{ route('educational.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i>
                    شراء بطاقة جديدة
                </a>
                <a href="{{ route('educational.verify-card') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-key"></i>
                    تفعيل بطاقة
                </a>
                <button onclick="updateExpiredCards()" class="btn btn-accent btn-lg">
                    <i class="fas fa-sync"></i>
                    تحديث الحالة
                </button>
            </div>
        </div>

        <!-- Cards Statistics -->
        <div class="grid grid-cols-4" style="margin-bottom: var(--space-2xl);">
            <div class="card" style="text-align: center; padding: var(--space-xl); background: linear-gradient(135deg, var(--success-50), var(--success-100)); border: 2px solid var(--success-200);">
                <div style="font-size: 2rem; color: var(--success-600); margin-bottom: var(--space-sm);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--success-800); margin-bottom: var(--space-xs);">
                    {{ $usedCards->total() }}
                </h3>
                <p style="color: var(--success-600); font-weight: 500;">بطاقات مفعلة</p>
            </div>
            
            <div class="card" style="text-align: center; padding: var(--space-xl); background: linear-gradient(135deg, var(--primary-50), var(--primary-100)); border: 2px solid var(--primary-200);">
                <div style="font-size: 2rem; color: var(--primary-600); margin-bottom: var(--space-sm);">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--primary-800); margin-bottom: var(--space-xs);">
                    {{ $purchasedCards->total() }}
                </h3>
                <p style="color: var(--primary-600); font-weight: 500;">بطاقات جديدة</p>
            </div>
            
            <div class="card" style="text-align: center; padding: var(--space-xl); background: linear-gradient(135deg, var(--warning-50), var(--warning-100)); border: 2px solid var(--warning-200);">
                <div style="font-size: 2rem; color: var(--warning-600); margin-bottom: var(--space-sm);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--warning-800); margin-bottom: var(--space-xs);">
                    {{ $usedCards->where('expires_at', '!=', null)->where('expires_at', '<', now()->addDays(7))->count() }}
                </h3>
                <p style="color: var(--warning-600); font-weight: 500;">تنتهي قريباً</p>
            </div>
            
            <div class="card" style="text-align: center; padding: var(--space-xl); background: linear-gradient(135deg, var(--accent-50), var(--accent-100)); border: 2px solid var(--accent-200);">
                <div style="font-size: 2rem; color: var(--accent-600); margin-bottom: var(--space-sm);">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--accent-800); margin-bottom: var(--space-xs);">
                    {{ $usedCards->total() + $purchasedCards->total() }}
                </h3>
                <p style="color: var(--accent-600); font-weight: 500">إجمالي البطاقات</p>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div style="margin-bottom: var(--space-2xl);">
            <div style="display: flex; justify-content: center; gap: var(--space-md); border-bottom: 2px solid var(--gray-200); padding-bottom: var(--space-md);">
                <button class="tab-button active" data-tab="used-cards" style="padding: var(--space-md) var(--space-xl); border: none; background: none; font-weight: 600; color: var(--primary-600); border-bottom: 2px solid var(--primary-600); cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-check-circle" style="margin-left: var(--space-sm);"></i>
                    البطاقات المفعلة ({{ $usedCards->total() }})
                </button>
                <button class="tab-button" data-tab="purchased-cards" style="padding: var(--space-md) var(--space-xl); border: none; background: none; font-weight: 600; color: var(--gray-600); border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-clock" style="margin-left: var(--space-sm);"></i>
                    البطاقات الجديدة ({{ $purchasedCards->total() }})
                </button>
            </div>
        </div>

        <!-- Used Cards Section -->
        <div id="used-cards" class="tab-content active" style="display: block;">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-lg); color: var(--gray-800);">
                <i class="fas fa-check-circle" style="color: var(--success-600); margin-left: var(--space-sm);"></i>
                البطاقات المفعلة
            </h2>
            
            @if($usedCards->count() > 0)
                <div class="grid grid-cols-2" style="gap: var(--space-lg);">
                    @foreach($usedCards as $card)
                        <div class="card educational-card" style="transition: all 0.3s ease; position: relative; overflow: hidden;">
                            <!-- Card Status Indicator -->
                            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: {{ $card->is_expired ? 'var(--error-500)' : 'var(--success-500)' }};"></div>
                            
                            <div class="card-body" style="padding: var(--space-xl);">
                                <!-- Card Header -->
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-lg);">
                                    <div>
                                        <h3 style="font-weight: 700; color: var(--gray-800); margin-bottom: var(--space-sm);">
                                            {{ $card->package->name }}
                                        </h3>
                                        <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                                            <span style="background: {{ $card->is_expired ? 'var(--error-100)' : 'var(--success-100)' }}; color: {{ $card->is_expired ? 'var(--error-700)' : 'var(--success-700)' }}; padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600;">
                                                {{ $card->status_display }}
                                            </span>
                                            @if(!$card->is_expired && $card->expires_at)
                                                <span style="background: var(--warning-100); color: var(--warning-700); padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-size: 0.8rem;">
                                                    {{ $card->time_until_expiration }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div style="text-align: left;">
                                        <div style="font-size: 1.2rem; color: var(--primary-600);">💳</div>
                                    </div>
                                </div>

                                <!-- Card Details -->
                                <div style="margin-bottom: var(--space-lg);">
                                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-md); font-size: 0.9rem;">
                                        <div>
                                            <span style="color: var(--gray-600);">الجيل:</span>
                                            <span style="font-weight: 600; color: var(--gray-800);">{{ $card->generation->display_name }}</span>
                                        </div>
                                        <div>
                                            <span style="color: var(--gray-600);">المادة:</span>
                                            <span style="font-weight: 600; color: var(--gray-800);">{{ $card->subject->name }}</span>
                                        </div>
                                        <div>
                                            <span style="color: var(--gray-600);">المعلم:</span>
                                            <span style="font-weight: 600; color: var(--gray-800);">{{ $card->teacher->name }}</span>
                                        </div>
                                        <div>
                                            <span style="color: var(--gray-600);">المنصة:</span>
                                            <span style="font-weight: 600; color: var(--gray-800);">{{ $card->platform->name }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Codes (Blurred for security) -->
                                <div style="margin-bottom: var(--space-lg); padding: var(--space-md); background: var(--gray-50); border-radius: var(--radius-md); border: 1px solid var(--gray-200);">
                                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-md); font-size: 0.85rem;">
                                        <div>
                                            <div style="color: var(--gray-600); margin-bottom: var(--space-xs);">كود البطاقة:</div>
                                            <div class="card-code-blur" style="font-family: monospace; font-weight: 600; color: var(--gray-800); cursor: pointer; filter: blur(4px); transition: filter 0.3s ease;" onclick="toggleBlur(this)">
                                                {{ $card->card_code }}
                                            </div>
                                        </div>
                                        <div>
                                            <div style="color: var(--gray-600); margin-bottom: var(--space-xs);">الرقم السري:</div>
                                            <div class="pin-code-blur" style="font-family: monospace; font-weight: 600; color: var(--gray-800); cursor: pointer; filter: blur(4px); transition: filter 0.3s ease;" onclick="toggleBlur(this)">
                                                {{ $card->pin_code }}
                                            </div>
                                        </div>
                                    </div>
                                    <div style="margin-top: var(--space-sm); font-size: 0.8rem; color: var(--gray-500); text-align: center;">
                                        <i class="fas fa-eye"></i>
                                        اضغط لإظهار الأكواد
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div style="display: flex; gap: var(--space-sm);">
                                    <a href="{{ route('educational.cards.show', $card) }}" class="btn btn-primary btn-sm" style="flex: 1;">
                                        <i class="fas fa-eye"></i>
                                        عرض التفاصيل
                                    </a>
                                    @if($card->platform->website_url)
                                        <a href="{{ $card->platform->formatted_website_url }}" target="_blank" class="btn btn-secondary btn-sm" style="flex: 1;">
                                            <i class="fas fa-external-link-alt"></i>
                                            زيارة المنصة
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination for Used Cards -->
                @if($usedCards->hasPages())
                    <div style="margin-top: var(--space-2xl); text-align: center;">
                        {{ $usedCards->links() }}
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: var(--space-3xl); background: var(--gray-50); border-radius: var(--radius-xl); border: 2px dashed var(--gray-300);">
                    <div style="font-size: 4rem; margin-bottom: var(--space-lg); opacity: 0.5;">📚</div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--gray-600); margin-bottom: var(--space-md);">
                        لا توجد بطاقات مفعلة
                    </h3>
                    <p style="color: var(--gray-500); margin-bottom: var(--space-lg);">
                        لم تقم بتفعيل أي بطاقات تعليمية بعد
                    </p>
                    <a href="{{ route('educational.verify-card') }}" class="btn btn-primary">
                        <i class="fas fa-key"></i>
                        تفعيل بطاقة جديدة
                    </a>
                </div>
            @endif
        </div>

        <!-- Purchased Cards Section -->
        <div id="purchased-cards" class="tab-content" style="display: none;">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-lg); color: var(--gray-800);">
                <i class="fas fa-clock" style="color: var(--primary-600); margin-left: var(--space-sm);"></i>
                البطاقات الجديدة (غير مفعلة)
            </h2>
            
            @if($purchasedCards->count() > 0)
                <div class="grid grid-cols-2" style="gap: var(--space-lg);">
                    @foreach($purchasedCards as $card)
                        <div class="card educational-card" style="transition: all 0.3s ease; position: relative; overflow: hidden;">
                            <!-- New Card Indicator -->
                            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--primary-500);"></div>
                            <div style="position: absolute; top: var(--space-md); left: var(--space-md); background: var(--primary-500); color: white; padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600;">
                                جديدة
                            </div>
                            
                            <div class="card-body" style="padding: var(--space-xl);">
                                <!-- Card Header -->
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-lg);">
                                    <div>
                                        <h3 style="font-weight: 700; color: var(--gray-800); margin-bottom: var(--space-sm);">
                                            {{ $card->package->name }}
                                        </h3>
                                        <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                            <span style="background: var(--primary-100); color: var(--primary-700); padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600;">
                                                {{ $card->status_display }}
                                            </span>
                                        </div>
                                    </div>
                                    <div style="text-align: left;">
                                        <div style="font-size: 1.2rem; color: var(--primary-600);">🎁</div>
                                    </div>
                                </div>

                                <!-- Card Details -->
                                <div style="margin-bottom: var(--space-lg);">
                                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-md); font-size: 0.9rem;">
                                        <div>
                                            <span style="color: var(--gray-600);">الجيل:</span>
                                            <span style="font-weight: 600; color: var(--gray-800);">{{ $card->generation->display_name }}</span>
                                        </div>
                                        <div>
                                            <span style="color: var(--gray-600);">المادة:</span>
                                            <span style="font-weight: 600; color: var(--gray-800);">{{ $card->subject->name }}</span>
                                        </div>
                                        <div>
                                            <span style="color: var(--gray-600);">المعلم:</span>
                                            <span style="font-weight: 600; color: var(--gray-800);">{{ $card->teacher->name }}</span>
                                        </div>
                                        <div>
                                            <span style="color: var(--gray-600);">المنصة:</span>
                                            <span style="font-weight: 600; color: var(--gray-800);">{{ $card->platform->name }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Activation Instruction -->
                                <div style="margin-bottom: var(--space-lg); padding: var(--space-md); background: var(--info-50); border-radius: var(--radius-md); border: 1px solid var(--info-200);">
                                    <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                                        <i class="fas fa-info-circle" style="color: var(--info-600);"></i>
                                        <span style="font-weight: 600; color: var(--info-800);">تحتاج لتفعيل</span>
                                    </div>
                                    <p style="font-size: 0.9rem; color: var(--info-700);">
                                        هذه البطاقة جاهزة للاستخدام. قم بتفعيلها لبدء الوصول للمحتوى التعليمي.
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div style="display: flex; gap: var(--space-sm);">
                                    <a href="{{ route('educational.cards.show', $card) }}" class="btn btn-primary btn-sm" style="flex: 1;">
                                        <i class="fas fa-key"></i>
                                        تفعيل البطاقة
                                    </a>
                                    <a href="{{ route('educational.cards.show', $card) }}" class="btn btn-secondary btn-sm" style="flex: 1;">
                                        <i class="fas fa-eye"></i>
                                        عرض التفاصيل
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination for Purchased Cards -->
                @if($purchasedCards->hasPages())
                    <div style="margin-top: var(--space-2xl); text-align: center;">
                        {{ $purchasedCards->links() }}
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: var(--space-3xl); background: var(--gray-50); border-radius: var(--radius-xl); border: 2px dashed var(--gray-300);">
                    <div style="font-size: 4rem; margin-bottom: var(--space-lg); opacity: 0.5;">🛒</div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--gray-600); margin-bottom: var(--space-md);">
                        لا توجد بطاقات جديدة
                    </h3>
                    <p style="color: var(--gray-500); margin-bottom: var(--space-lg);">
                        جميع بطاقاتك مفعلة أو لم تقم بشراء بطاقات جديدة
                    </p>
                    <a href="{{ route('educational.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        شراء بطاقة جديدة
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>

<style>
.educational-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.tab-button.active {
    color: var(--primary-600) !important;
    border-bottom-color: var(--primary-600) !important;
}

.tab-button:hover {
    color: var(--primary-500) !important;
}

.tab-content {
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card-code-blur:hover,
.pin-code-blur:hover {
    filter: none !important;
}

@media (max-width: 768px) {
    .grid-cols-2 {
        grid-template-columns: 1fr !important;
    }
    
    .grid-cols-3,
    .grid-cols-4 {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 480px) {
    .grid-cols-3,
    .grid-cols-4 {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => {
                content.classList.remove('active');
                content.style.display = 'none';
            });
            
            // Add active class to clicked button and corresponding content
            this.classList.add('active');
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.add('active');
                targetContent.style.display = 'block';
            }
        });
    });
});

// Toggle blur for card codes
function toggleBlur(element) {
    if (element.style.filter === 'none') {
        element.style.filter = 'blur(4px)';
    } else {
        element.style.filter = 'none';
    }
}

// Update expired cards
function updateExpiredCards() {
    showLoadingOverlay();
    
    fetch('{{ route("educational.update-expired-cards") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessNotification('تم تحديث حالة البطاقات بنجاح');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showErrorNotification('فشل في تحديث حالة البطاقات');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorNotification('حدث خطأ أثناء تحديث البطاقات');
    })
    .finally(() => {
        hideLoadingOverlay();
    });
}

// Loading overlay functions
function showLoadingOverlay() {
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.innerHTML = `
            <div style="background: white; padding: var(--space-2xl); border-radius: var(--radius-xl); text-align: center; max-width: 300px;">
                <div style="font-size: 3rem; margin-bottom: var(--space-lg);">⏳</div>
                <h3 style="margin-bottom: var(--space-md); color: var(--gray-800);">جاري التحديث...</h3>
                <p style="color: var(--gray-600); font-size: 0.9rem;">يرجى الانتظار</p>
            </div>
        `;
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        `;
        document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
}

function hideLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}
</script>

@push('scripts')
<script src="{{ asset('js/educational-system.js') }}"></script>
@endpush
@endsection