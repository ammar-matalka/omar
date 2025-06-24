@extends('layouts.app')

@section('title', 'النظام التعليمي')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/educational.css') }}">
@endpush

@section('content')
<div class="container" style="margin-top: var(--space-2xl); margin-bottom: var(--space-3xl);">
    <!-- Hero Section -->
    <div class="hero-section" style="text-align: center; margin-bottom: var(--space-3xl); padding: var(--space-3xl) var(--space-lg); background: linear-gradient(135deg, var(--primary-50), var(--secondary-50)); border-radius: var(--radius-2xl); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50%; left: -10%; width: 200px; height: 200px; background: linear-gradient(135deg, var(--primary-200), var(--secondary-200)); border-radius: 50%; opacity: 0.3; z-index: 1;"></div>
        <div style="position: absolute; bottom: -30%; right: -5%; width: 150px; height: 150px; background: linear-gradient(135deg, var(--accent-200), var(--primary-200)); border-radius: 50%; opacity: 0.3; z-index: 1;"></div>
        
        <div style="position: relative; z-index: 2;">
            <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: var(--space-lg); background: linear-gradient(135deg, var(--primary-600), var(--secondary-600)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                🎓 النظام التعليمي
            </h1>
            <p style="font-size: 1.25rem; color: var(--gray-600); margin-bottom: var(--space-2xl); max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.8;">
                اختر نوع المنتج التعليمي المناسب لك وابدأ رحلتك التعليمية معنا
            </p>
        </div>
    </div>

    <!-- Product Type Selection Cards -->
    <div class="product-types-section">
        <h2 style="font-size: 2rem; font-weight: 800; text-align: center; margin-bottom: var(--space-2xl); color: var(--gray-800);">
            اختر نوع المنتج التعليمي
        </h2>
        
        <div class="grid grid-cols-2" style="gap: var(--space-2xl); max-width: 800px; margin: 0 auto;">
            <!-- البطاقات الرقمية -->
            <div class="product-type-card digital-card" onclick="selectProductType('digital')" style="cursor: pointer; transition: all 0.3s ease;">
                <div class="card" style="height: 100%; border: 3px solid transparent; background: linear-gradient(135deg, #e0f2fe, #f0f9ff); position: relative; overflow: hidden;">
                    <!-- Background Pattern -->
                    <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: linear-gradient(135deg, var(--primary-200), var(--primary-300)); border-radius: 50%; opacity: 0.6;"></div>
                    <div style="position: absolute; bottom: -30px; left: -30px; width: 80px; height: 80px; background: linear-gradient(135deg, var(--secondary-200), var(--secondary-300)); border-radius: 50%; opacity: 0.6;"></div>
                    
                    <div class="card-body" style="text-align: center; position: relative; z-index: 2; padding: var(--space-2xl);">
                        <!-- Icon -->
                        <div style="font-size: 4rem; margin-bottom: var(--space-lg); background: linear-gradient(135deg, var(--primary-500), var(--primary-600)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            💳
                        </div>
                        
                        <!-- Title -->
                        <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-md); color: var(--gray-800);">
                            البطاقات التعليمية الرقمية
                        </h3>
                        
                        <!-- Description -->
                        <p style="color: var(--gray-600); margin-bottom: var(--space-lg); line-height: 1.6;">
                            بطاقات رقمية تحتوي على أكواد تفعيل للوصول إلى المحتوى التعليمي عبر المنصات الإلكترونية
                        </p>
                        
                        <!-- Features -->
                        <div style="margin-bottom: var(--space-lg);">
                            <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-sm); margin-bottom: var(--space-sm); color: var(--success-600);">
                                <i class="fas fa-check-circle"></i>
                                <span style="font-size: 0.9rem;">تسليم فوري</span>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-sm); margin-bottom: var(--space-sm); color: var(--success-600);">
                                <i class="fas fa-check-circle"></i>
                                <span style="font-size: 0.9rem;">وصول مدى الحياة</span>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-sm); color: var(--success-600);">
                                <i class="fas fa-check-circle"></i>
                                <span style="font-size: 0.9rem;">محتوى تفاعلي</span>
                            </div>
                        </div>
                        
                        <!-- Button -->
                        <button class="btn btn-primary btn-lg" style="width: 100%; font-weight: 600;">
                            <i class="fas fa-arrow-left" style="margin-left: var(--space-sm);"></i>
                            اختر البطاقات الرقمية
                        </button>
                    </div>
                </div>
            </div>

            <!-- الدوسيات الورقية -->
            <div class="product-type-card physical-card" onclick="selectProductType('physical')" style="cursor: pointer; transition: all 0.3s ease;">
                <div class="card" style="height: 100%; border: 3px solid transparent; background: linear-gradient(135deg, #fdf4ff, #fff7ed); position: relative; overflow: hidden;">
                    <!-- Background Pattern -->
                    <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: linear-gradient(135deg, var(--accent-200), var(--accent-300)); border-radius: 50%; opacity: 0.6;"></div>
                    <div style="position: absolute; bottom: -30px; left: -30px; width: 80px; height: 80px; background: linear-gradient(135deg, var(--secondary-200), var(--secondary-300)); border-radius: 50%; opacity: 0.6;"></div>
                    
                    <div class="card-body" style="text-align: center; position: relative; z-index: 2; padding: var(--space-2xl);">
                        <!-- Icon -->
                        <div style="font-size: 4rem; margin-bottom: var(--space-lg); background: linear-gradient(135deg, var(--accent-500), var(--accent-600)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            📚
                        </div>
                        
                        <!-- Title -->
                        <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-md); color: var(--gray-800);">
                            الدوسيات الورقية
                        </h3>
                        
                        <!-- Description -->
                        <p style="color: var(--gray-600); margin-bottom: var(--space-lg); line-height: 1.6;">
                            كتب ومذكرات ورقية مطبوعة تحتوي على المحتوى التعليمي مع إمكانية التوصيل لمنزلك
                        </p>
                        
                        <!-- Features -->
                        <div style="margin-bottom: var(--space-lg);">
                            <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-sm); margin-bottom: var(--space-sm); color: var(--info-600);">
                                <i class="fas fa-check-circle"></i>
                                <span style="font-size: 0.9rem;">طباعة عالية الجودة</span>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-sm); margin-bottom: var(--space-sm); color: var(--info-600);">
                                <i class="fas fa-check-circle"></i>
                                <span style="font-size: 0.9rem;">توصيل سريع</span>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-sm); color: var(--info-600);">
                                <i class="fas fa-check-circle"></i>
                                <span style="font-size: 0.9rem;">مراجعة سهلة</span>
                            </div>
                        </div>
                        
                        <!-- Button -->
                        <button class="btn btn-accent btn-lg" style="width: 100%; font-weight: 600;">
                            <i class="fas fa-arrow-left" style="margin-left: var(--space-sm);"></i>
                            اختر الدوسيات الورقية
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information Section -->
    <div style="margin-top: var(--space-3xl); text-align: center; padding: var(--space-2xl); background: var(--gray-50); border-radius: var(--radius-xl);">
        <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: var(--space-lg); color: var(--gray-800);">
            💡 كيف يعمل النظام؟
        </h3>
        
        <div class="grid grid-cols-3" style="gap: var(--space-xl); max-width: 900px; margin: 0 auto;">
            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-500), var(--primary-600)); border-radius: 50%; margin: 0 auto var(--space-md); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 700;">
                    1
                </div>
                <h4 style="font-weight: 600; margin-bottom: var(--space-sm); color: var(--gray-800);">اختر نوع المنتج</h4>
                <p style="font-size: 0.9rem; color: var(--gray-600);">بطاقات رقمية أو دوسيات ورقية</p>
            </div>
            
            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--secondary-500), var(--secondary-600)); border-radius: 50%; margin: 0 auto var(--space-md); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 700;">
                    2
                </div>
                <h4 style="font-weight: 600; margin-bottom: var(--space-sm); color: var(--gray-800);">اختر التفاصيل</h4>
                <p style="font-size: 0.9rem; color: var(--gray-600);">الجيل، المادة، المعلم والباقة</p>
            </div>
            
            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--accent-500), var(--accent-600)); border-radius: 50%; margin: 0 auto var(--space-md); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 700;">
                    3
                </div>
                <h4 style="font-weight: 600; margin-bottom: var(--space-sm); color: var(--gray-800);">اطلب واستلم</h4>
                <p style="font-size: 0.9rem; color: var(--gray-600);">أضف للسلة وأكمل الطلب</p>
            </div>
        </div>
    </div>

    @auth
    <!-- User Educational Cards Section -->
    <div style="margin-top: var(--space-3xl); padding: var(--space-2xl); background: linear-gradient(135deg, var(--success-50), var(--primary-50)); border-radius: var(--radius-xl); border: 2px solid var(--success-200);">
        <div style="text-align: center; margin-bottom: var(--space-lg);">
            <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--gray-800); margin-bottom: var(--space-sm);">
                <i class="fas fa-user-graduate" style="margin-left: var(--space-sm); color: var(--success-600);"></i>
                منطقة المستخدم
            </h3>
            <p style="color: var(--gray-600);">إدارة بطاقاتك التعليمية وتفعيل أكواد جديدة</p>
        </div>
        
        <div class="grid grid-cols-2" style="gap: var(--space-lg); max-width: 600px; margin: 0 auto;">
            <a href="{{ route('educational.my-cards') }}" class="btn btn-primary btn-lg" style="text-decoration: none;">
                <i class="fas fa-cards-blank" style="margin-left: var(--space-sm);"></i>
                بطاقاتي التعليمية
            </a>
            
            <a href="{{ route('educational.verify-card') }}" class="btn btn-secondary btn-lg" style="text-decoration: none;">
                <i class="fas fa-key" style="margin-left: var(--space-sm);"></i>
                تفعيل بطاقة جديدة
            </a>
        </div>
    </div>
    @endauth
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: var(--space-2xl); border-radius: var(--radius-xl); text-align: center; max-width: 300px;">
        <div style="font-size: 3rem; margin-bottom: var(--space-lg);">⏳</div>
        <h3 style="margin-bottom: var(--space-md); color: var(--gray-800);">جاري التحميل...</h3>
        <p style="color: var(--gray-600); font-size: 0.9rem;">يرجى الانتظار بينما نحضر لك الخيارات المتاحة</p>
        <div style="margin-top: var(--space-lg);">
            <div style="width: 200px; height: 4px; background: var(--gray-200); border-radius: 2px; overflow: hidden;">
                <div style="width: 0%; height: 100%; background: linear-gradient(135deg, var(--primary-500), var(--primary-600)); border-radius: 2px; animation: loadingProgress 2s ease-in-out infinite;"></div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes loadingProgress {
    0% { width: 0%; }
    50% { width: 70%; }
    100% { width: 100%; }
}

.product-type-card:hover .card {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.digital-card:hover .card {
    border-color: var(--primary-400);
    background: linear-gradient(135deg, #dbeafe, #eff6ff);
}

.physical-card:hover .card {
    border-color: var(--accent-400);
    background: linear-gradient(135deg, #fae8ff, #fff7ed);
}

.product-type-card:hover .btn {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.grid-cols-2 {
    grid-template-columns: repeat(2, 1fr);
}

.grid-cols-3 {
    grid-template-columns: repeat(3, 1fr);
}

@media (max-width: 768px) {
    .grid-cols-2,
    .grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .hero-section h1 {
        font-size: 2rem !important;
    }
    
    .hero-section p {
        font-size: 1.1rem !important;
    }
    
    .product-type-card .card-body {
        padding: var(--space-lg) !important;
    }
}

@media (max-width: 480px) {
    .hero-section {
        padding: var(--space-2xl) var(--space-md) !important;
    }
    
    .hero-section h1 {
        font-size: 1.75rem !important;
    }
    
    .container {
        padding: 0 var(--space-sm) !important;
    }
}
</style>

@push('scripts')
<script src="{{ asset('js/educational-system.js') }}"></script>
<script>
function selectProductType(type) {
    // Show loading
    const overlay = document.getElementById('loadingOverlay');
    overlay.style.display = 'flex';
    
    // Simulate loading time for better UX
    setTimeout(() => {
        if (type === 'digital') {
            window.location.href = "{{ route('educational.form') }}?product_type=cards";
        } else if (type === 'physical') {
            window.location.href = "{{ route('educational.form') }}?product_type=booklets";
        }
    }, 800);
}

// Add smooth scroll animations
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.product-type-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush
@endsection