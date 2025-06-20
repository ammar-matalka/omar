@extends('layouts.app')

@section('title', 'الرئيسية' . ' - ' . config('app.name'))

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-3xl) 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,100 1000,0 1000,100"/></svg>');
        background-size: cover;
        background-position: bottom;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
    
    .hero-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: var(--space-md);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: var(--space-2xl);
        opacity: 0.9;
    }
    
    .hero-buttons {
        display: flex;
        gap: var(--space-lg);
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .hero-btn {
        padding: var(--space-md) var(--space-2xl);
        font-size: 1.1rem;
        border-radius: var(--radius-xl);
        text-decoration: none;
        font-weight: 600;
        transition: all var(--transition-normal);
        box-shadow: var(--shadow-lg);
    }
    
    .hero-btn-primary {
        background: white;
        color: var(--primary-600);
    }
    
    .hero-btn-primary:hover {
        background: var(--gray-100);
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
    }
    
    .hero-btn-secondary {
        background: transparent;
        color: white;
        border: 2px solid white;
    }
    
    .hero-btn-secondary:hover {
        background: white;
        color: var(--primary-600);
        transform: translateY(-2px);
    }
    
    .features-section {
        padding: var(--space-3xl) 0;
        background: var(--surface);
    }
    
    .section-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: var(--space-lg);
        background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .section-subtitle {
        text-align: center;
        font-size: 1.125rem;
        color: var(--on-surface-variant);
        margin-bottom: var(--space-2xl);
        max-width: 600px;
        margin-right: auto;
        margin-left: auto;
    }
    
    .feature-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        text-align: center;
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
    }
    
    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
        transform: scaleX(0);
        transition: transform var(--transition-normal);
    }
    
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-200);
    }
    
    .feature-card:hover::before {
        transform: scaleX(1);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto var(--space-lg);
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--primary-600);
        transition: all var(--transition-normal);
    }
    
    .feature-card:hover .feature-icon {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        transform: scale(1.1);
    }
    
    .feature-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
    }
    
    .feature-description {
        color: var(--on-surface-variant);
        line-height: 1.7;
    }
    
    .stats-section {
        background: linear-gradient(135deg, var(--gray-900), var(--gray-800));
        color: white;
        padding: var(--space-2xl) 0;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        background: linear-gradient(135deg, var(--primary-400), var(--secondary-400));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-label {
        font-size: 1.125rem;
        color: var(--gray-300);
        font-weight: 500;
    }
    
    .cta-section {
        background: linear-gradient(135deg, var(--accent-500), var(--accent-600));
        color: white;
        padding: var(--space-3xl) 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><circle cx="50" cy="50" r="30"/><circle cx="20" cy="20" r="10"/><circle cx="80" cy="80" r="15"/></svg>');
        animation: float 20s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .cta-content {
        position: relative;
        z-index: 1;
    }
    
    .cta-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: var(--space-md);
    }
    
    .cta-description {
        font-size: 1.25rem;
        margin-bottom: var(--space-2xl);
        opacity: 0.9;
    }
    
    .cta-btn {
        background: white;
        color: var(--accent-600);
        padding: var(--space-lg) var(--space-2xl);
        border-radius: var(--radius-xl);
        text-decoration: none;
        font-weight: 700;
        font-size: 1.125rem;
        display: inline-block;
        transition: all var(--transition-normal);
        box-shadow: var(--shadow-lg);
    }
    
    .cta-btn:hover {
        background: var(--gray-100);
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .hero-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .cta-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content fade-in">
            <h1 class="hero-title">مرحباً بك في {{ config('app.name') }}</h1>
            <p class="hero-subtitle">اكتشف منتجات رائعة لتحسين تجربة التسوق الخاصة بك</p>
            
            <div class="hero-buttons">
                <a href="{{ route('products.index') }}" class="hero-btn hero-btn-primary">
                    <i class="fas fa-shopping-bag"></i>
                    تسوق المنتجات
                </a>
                @auth
                <a href="{{ route('user.conversations.create') }}" class="hero-btn hero-btn-secondary">
                    <i class="fas fa-headset"></i>
                    تواصل مع الدعم
                </a>
                @else
                <a href="{{ route('register') }}" class="hero-btn hero-btn-secondary">
                    <i class="fas fa-user-plus"></i>
                    انضم إلينا
                </a>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title">لماذا تختارنا؟</h2>
        <p class="section-subtitle">نحن نقدم منتجات عالية الجودة مع خدمة عملاء ممتازة</p>
        
        <div class="grid grid-cols-3">
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-award"></i>
                </div>
                <h3 class="feature-title">جودة عالية</h3>
                <p class="feature-description">جميع منتجاتنا مختارة بعناية لضمان أعلى معايير الجودة</p>
            </div>
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3 class="feature-title">توصيل سريع</h3>
                <p class="feature-description">خدمة توصيل سريعة وموثوقة لإيصال طلباتك في أسرع وقت ممكن</p>
            </div>
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="feature-title">دعم 24/7</h3>
                <p class="feature-description">فريق دعم العملاء جاهز دائماً لمساعدتك في أي استفسار</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="grid grid-cols-3">
            <div class="stat-item fade-in">
                <div class="stat-number">1000+</div>
                <div class="stat-label">عميل راضي</div>
            </div>
            
            <div class="stat-item fade-in">
                <div class="stat-number">500+</div>
                <div class="stat-label">منتج</div>
            </div>
            
            <div class="stat-item fade-in">
                <div class="stat-number">24/7</div>
                <div class="stat-label">دعم فني</div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">مستعد للبدء؟</h2>
            <p class="cta-description">انضم إلى آلاف العملاء الراضين وابدأ رحلة التسوق اليوم</p>
            
            @auth
                <a href="{{ route('products.index') }}" class="cta-btn">
                    <i class="fas fa-rocket"></i>
                    ابدأ التسوق
                </a>
            @else
                <a href="{{ route('register') }}" class="cta-btn">
                    <i class="fas fa-user-plus"></i>
                    انضم إلينا الآن
                </a>
            @endauth
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Add intersection observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all fade-in elements
    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });

    // Counter animation for stats
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current) + '+';
        }, 20);
    }

    // Animate stats when visible
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const number = entry.target.querySelector('.stat-number');
                const target = parseInt(number.textContent.replace('+', '').replace('/', ''));
                if (!isNaN(target) && target < 100) {
                    animateCounter(number, target);
                }
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-item').forEach(item => {
        statsObserver.observe(item);
    });

    console.log('🏠 تم تحميل الصفحة الرئيسية بنجاح');
</script>
@endpush