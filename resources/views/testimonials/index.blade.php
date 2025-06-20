@extends('layouts.app')

@section('title', __('تقييمات العملاء') . ' - ' . config('app.name'))

@push('styles')
<style>
    /* RTL Direction */
    html[dir="rtl"] {
        direction: rtl;
        text-align: right;
    }

    .testimonials-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-3xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .testimonials-hero::before {
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
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: var(--space-md);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: var(--space-xl);
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }
    
    .hero-stats {
        display: flex;
        justify-content: center;
        gap: var(--space-2xl);
        flex-wrap: wrap;
    }
    
    .stat-item {
        text-align: center;
        background: rgba(255, 255, 255, 0.1);
        padding: var(--space-lg);
        border-radius: var(--radius-xl);
        border: 1px solid rgba(255, 255, 255, 0.2);
        min-width: 120px;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 900;
        display: block;
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        font-size: 0.875rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .testimonials-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
    }
    
    .testimonials-header {
        text-align: center;
        margin-bottom: var(--space-3xl);
    }
    
    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: var(--space-lg);
        background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .section-subtitle {
        font-size: 1.125rem;
        color: var(--on-surface-variant);
        max-width: 600px;
        margin: 0 auto var(--space-xl);
        line-height: 1.6;
    }
    
    .filter-tabs {
        display: flex;
        justify-content: center;
        gap: var(--space-md);
        margin-bottom: var(--space-2xl);
        flex-wrap: wrap;
    }
    
    .filter-tab {
        padding: var(--space-md) var(--space-xl);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-xl);
        background: var(--surface);
        color: var(--on-surface-variant);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        text-decoration: none;
    }
    
    .filter-tab:hover {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
        transform: translateY(-2px);
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border-color: var(--primary-500);
        box-shadow: var(--shadow-md);
    }
    
    .sort-controls {
        display: flex;
        justify-content: center;
        gap: var(--space-md);
        margin-bottom: var(--space-2xl);
        flex-wrap: wrap;
    }
    
    .sort-select {
        padding: var(--space-sm) var(--space-md);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .sort-select:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .testimonial-card {
        background: var(--surface);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
    }
    
    .testimonial-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
        transform: scaleX(0);
        transition: transform var(--transition-normal);
    }
    
    .testimonial-card:hover {
        border-color: var(--primary-200);
        box-shadow: var(--shadow-lg);
        transform: translateY(-8px);
    }
    
    .testimonial-card:hover::before {
        transform: scaleX(1);
    }
    
    .testimonial-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
    }
    
    .customer-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-600);
        overflow: hidden;
        flex-shrink: 0;
        border: 3px solid var(--border-color);
    }
    
    .customer-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .customer-info {
        flex: 1;
    }
    
    .customer-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
    }
    
    .review-meta {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        color: var(--on-surface-variant);
        font-size: 0.875rem;
        flex-wrap: wrap;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .rating-display {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .stars {
        display: flex;
        gap: var(--space-xs);
    }
    
    .star {
        color: var(--warning-500);
        font-size: 1rem;
    }
    
    .star.empty {
        color: var(--border-color);
    }
    
    .rating-number {
        font-weight: 600;
        color: var(--on-surface);
    }
    
    .testimonial-content {
        margin-bottom: var(--space-lg);
    }
    
    .testimonial-text {
        font-size: 1rem;
        line-height: 1.7;
        color: var(--on-surface);
        margin-bottom: var(--space-md);
        position: relative;
    }
    
    .testimonial-text::before {
        content: '"';
        position: absolute;
        top: -10px;
        right: -15px;
        left: auto;
        font-size: 3rem;
        color: var(--primary-200);
        font-family: serif;
        line-height: 1;
    }
    
    .testimonial-text::after {
        content: '"';
        position: absolute;
        bottom: -20px;
        left: -5px;
        right: auto;
        font-size: 3rem;
        color: var(--primary-200);
        font-family: serif;
        line-height: 1;
    }
    
    .testimonial-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: var(--space-md);
        border-top: 1px solid var(--border-color);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .order-info {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        color: var(--on-surface-variant);
        font-size: 0.75rem;
    }
    
    .verified-badge {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        background: var(--success-100);
        color: var(--success-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid var(--success-200);
    }
    
    .helpful-actions {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .helpful-btn {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        background: none;
        border: 1px solid var(--border-color);
        color: var(--on-surface-variant);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .helpful-btn:hover {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
    }
    
    .helpful-btn.active {
        background: var(--primary-500);
        color: white;
        border-color: var(--primary-500);
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
    
    .load-more-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
        padding: var(--space-md) var(--space-xl);
        background: var(--surface);
        color: var(--primary-600);
        border: 2px solid var(--primary-200);
        border-radius: var(--radius-lg);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .load-more-btn:hover {
        background: var(--primary-50);
        border-color: var(--primary-300);
        transform: translateY(-2px);
    }
    
    .testimonial-badge {
        position: absolute;
        top: var(--space-md);
        left: var(--space-md);
        right: auto;
        background: var(--warning-500);
        color: white;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-featured {
        background: var(--secondary-500);
    }
    
    .badge-recent {
        background: var(--success-500);
    }
    
    .floating-action {
        position: fixed;
        bottom: var(--space-xl);
        left: var(--space-xl);
        right: auto;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border: none;
        border-radius: 50%;
        box-shadow: var(--shadow-lg);
        cursor: pointer;
        transition: all var(--transition-fast);
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .floating-action:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: scale(1.1);
        box-shadow: var(--shadow-xl);
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-stats {
            gap: var(--space-lg);
        }
        
        .stat-item {
            min-width: 100px;
            padding: var(--space-md);
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .filter-tabs {
            gap: var(--space-sm);
        }
        
        .filter-tab {
            padding: var(--space-sm) var(--space-md);
            font-size: 0.875rem;
        }
        
        .testimonials-grid {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .testimonial-header {
            flex-direction: column;
            text-align: center;
            gap: var(--space-sm);
        }
        
        .customer-avatar {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
        
        .review-meta {
            justify-content: center;
        }
        
        .testimonial-footer {
            flex-direction: column;
            gap: var(--space-sm);
        }
        
        .floating-action {
            bottom: var(--space-lg);
            left: var(--space-lg);
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Testimonials Hero -->
<section class="testimonials-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <h1 class="hero-title">{{ __('تقييمات العملاء') }}</h1>
            <p class="hero-subtitle">
                {{ __('اكتشف ما يقوله عملاؤنا عن تجربة التسوق معنا. تقييمات حقيقية من عملاء حقيقيين.') }}
            </p>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $totalReviews ?? 0 }}</span>
                    <div class="stat-label">{{ __('إجمالي التقييمات') }}</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ number_format($averageRating ?? 0, 1) }}</span>
                    <div class="stat-label">{{ __('متوسط التقييم') }}</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $satisfiedCustomers ?? 0 }}%</span>
                    <div class="stat-label">{{ __('عملاء راضون') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Container -->
<section class="testimonials-container">
    <div class="container">
        <!-- Section Header -->
        <div class="testimonials-header fade-in">
            <h2 class="section-title">{{ __('ما يقوله عملاؤنا') }}</h2>
            <p class="section-subtitle">
                {{ __('تصفح التقييمات والتجارب الحقيقية التي شاركها عملاؤنا الكرام') }}
            </p>
        </div>
        
        <!-- Filter Tabs -->
        <div class="filter-tabs fade-in">
            <a href="?rating=all" class="filter-tab {{ request('rating', 'all') === 'all' ? 'active' : '' }}">
                <i class="fas fa-star"></i>
                {{ __('كل التقييمات') }}
            </a>
            <a href="?rating=5" class="filter-tab {{ request('rating') === '5' ? 'active' : '' }}">
                <i class="fas fa-star"></i>
                {{ __('5 نجوم') }}
            </a>
            <a href="?rating=4" class="filter-tab {{ request('rating') === '4' ? 'active' : '' }}">
                <i class="fas fa-star"></i>
                {{ __('4 نجوم') }}
            </a>
            <a href="?rating=3" class="filter-tab {{ request('rating') === '3' ? 'active' : '' }}">
                <i class="fas fa-star"></i>
                {{ __('3 نجوم') }}
            </a>
            <a href="?rating=recent" class="filter-tab {{ request('rating') === 'recent' ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                {{ __('الأحدث') }}
            </a>
        </div>
        
        <!-- Sort Controls -->
        <div class="sort-controls fade-in">
            <select class="sort-select" onchange="window.location.href = updateUrlParameter('sort', this.value)">
                <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>
                    {{ __('الأحدث أولاً') }}
                </option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                    {{ __('الأقدم أولاً') }}
                </option>
                <option value="highest_rating" {{ request('sort') === 'highest_rating' ? 'selected' : '' }}>
                    {{ __('أعلى تقييم') }}
                </option>
                <option value="lowest_rating" {{ request('sort') === 'lowest_rating' ? 'selected' : '' }}>
                    {{ __('أقل تقييم') }}
                </option>
                <option value="most_helpful" {{ request('sort') === 'most_helpful' ? 'selected' : '' }}>
                    {{ __('الأكثر فائدة') }}
                </option>
            </select>
        </div>
        
        <!-- Testimonials Grid -->
        @if(isset($testimonials) && $testimonials->count() > 0)
            <div class="testimonials-grid" id="testimonials-grid">
                @foreach($testimonials as $testimonial)
                    <div class="testimonial-card fade-in" data-rating="{{ $testimonial->rating }}">
                        <!-- Testimonial Badge -->
                        @if($testimonial->created_at->diffInDays() < 7)
                            <div class="testimonial-badge badge-recent">{{ __('جديد') }}</div>
                        @elseif($testimonial->rating === 5)
                            <div class="testimonial-badge badge-featured">{{ __('مميز') }}</div>
                        @endif
                        
                        <!-- Testimonial Header -->
                        <div class="testimonial-header">
                            <div class="customer-avatar">
                                @if($testimonial->user->profile_image)
                                    <img src="{{ $testimonial->user->profile_image_url }}" alt="{{ $testimonial->user->name }}">
                                @else
                                    {{ $testimonial->user->initials }}
                                @endif
                            </div>
                            
                            <div class="customer-info">
                                <div class="customer-name">{{ $testimonial->user->name }}</div>
                                <div class="review-meta">
                                    <div class="meta-item rating-display">
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star star {{ $i <= $testimonial->rating ? '' : 'empty' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-number">{{ $testimonial->rating }}/5</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $testimonial->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Testimonial Content -->
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                {{ $testimonial->comment }}
                            </div>
                        </div>
                        
                        <!-- Testimonial Footer -->
                        <div class="testimonial-footer">
                            <div class="order-info">
                                <i class="fas fa-receipt"></i>
                                {{ __('طلب') }} #{{ $testimonial->order_id }}
                                <span>•</span>
                                <i class="fas fa-check-circle"></i>
                                {{ __('شراء موثوق') }}
                            </div>
                            
                            <div class="verified-badge">
                                <i class="fas fa-shield-check"></i>
                                {{ __('تقييم موثوق') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Load More Button -->
            @if($testimonials->hasMorePages())
                <button class="load-more-btn fade-in" onclick="loadMoreTestimonials()">
                    <i class="fas fa-plus"></i>
                    {{ __('تحميل المزيد') }}
                </button>
            @endif
            
            <!-- Pagination (alternative to load more) -->
            @if($testimonials->hasPages())
                <div class="pagination-wrapper fade-in" style="display: none;">
                    {{ $testimonials->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state fade-in">
                <div class="empty-icon">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <h3 class="empty-title">{{ __('لا توجد تقييمات بعد') }}</h3>
                <p class="empty-text">
                    {{ __('كن أول من يشارك تجربته! تقييمك يساعد العملاء الآخرين على اتخاذ قرارات مستنيرة.') }}
                </p>
                <a href="{{ route('products.index') }}" class="empty-cta">
                    <i class="fas fa-shopping-bag"></i>
                    {{ __('ابدأ التسوق') }}
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Floating Action Button -->
@auth
    <button class="floating-action" onclick="scrollToTop()" title="{{ __('العودة للأعلى') }}">
        <i class="fas fa-arrow-up"></i>
    </button>
@endauth
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
    
    // Update URL parameter function
    function updateUrlParameter(param, value) {
        const url = new URL(window.location);
        url.searchParams.set(param, value);
        return url.toString();
    }
    
    // Load more testimonials
    let loadMorePage = 2;
    let isLoading = false;
    
    function loadMoreTestimonials() {
        if (isLoading) return;
        
        isLoading = true;
        const loadMoreBtn = document.querySelector('.load-more-btn');
        const originalText = loadMoreBtn.innerHTML;
        
        loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("جاري التحميل...") }}';
        loadMoreBtn.disabled = true;
        
        const url = new URL(window.location);
        url.searchParams.set('page', loadMorePage);
        
        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                const grid = document.getElementById('testimonials-grid');
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                
                // Add new testimonials with animation
                Array.from(tempDiv.children).forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    grid.appendChild(card);
                    
                    setTimeout(() => {
                        card.style.transition = 'all 0.6s ease-out';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
                
                loadMorePage++;
                
                if (!data.hasMore) {
                    loadMoreBtn.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error loading more testimonials:', error);
            showNotification('{{ __("فشل تحميل المزيد من التقييمات") }}', 'error');
        })
        .finally(() => {
            isLoading = false;
            loadMoreBtn.innerHTML = originalText;
            loadMoreBtn.disabled = false;
        });
    }
    
    // Scroll to top function
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    
    // Show/hide floating action button based on scroll
    window.addEventListener('scroll', function() {
        const floatingAction = document.querySelector('.floating-action');
        if (floatingAction) {
            if (window.scrollY > 300) {
                floatingAction.style.opacity = '1';
                floatingAction.style.transform = 'scale(1)';
            } else {
                floatingAction.style.opacity = '0';
                floatingAction.style.transform = 'scale(0.8)';
            }
        }
    });
    
    // Filter testimonials by rating (client-side)
    function filterTestimonials(rating) {
        const cards = document.querySelectorAll('.testimonial-card');
        
        cards.forEach(card => {
            const cardRating = parseInt(card.dataset.rating);
            let show = false;
            
            if (rating === 'all') {
                show = true;
            } else if (rating === 'recent') {
                // Show cards created in last 7 days (you might need to add data attribute)
                show = true; // Simplified for demo
            } else {
                show = cardRating === parseInt(rating);
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
    }
    
    // Infinite scroll (alternative to load more button)
    function enableInfiniteScroll() {
        window.addEventListener('scroll', function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
                if (document.querySelector('.load-more-btn') && !isLoading) {
                    loadMoreTestimonials();
                }
            }
        });
    }
    
    // Initialize infinite scroll (optional)
    // enableInfiniteScroll();
    
    // Animate statistics on scroll
    function animateStats() {
        const stats = document.querySelectorAll('.stat-number');
        
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const finalValue = target.textContent;
                    const numericValue = parseFloat(finalValue.replace(/[^0-9.]/g, ''));
                    
                    if (!isNaN(numericValue)) {
                        animateNumber(target, 0, numericValue, finalValue);
                    }
                    
                    statsObserver.unobserve(target);
                }
            });
        }, { threshold: 0.5 });
        
        stats.forEach(stat => {
            statsObserver.observe(stat);
        });
    }
    
    function animateNumber(element, start, end, finalText) {
        const duration = 2000;
        const increment = (end - start) / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            
            if (current >= end) {
                current = end;
                clearInterval(timer);
                element.textContent = finalText;
            } else {
                if (finalText.includes('.')) {
                    element.textContent = current.toFixed(1);
                } else if (finalText.includes('%')) {
                    element.textContent = Math.round(current) + '%';
                } else {
                    element.textContent = Math.round(current);
                }
            }
        }, 16);
    }
    
    // Initialize stats animation
    animateStats();
    
    // Add hover effects to testimonial cards
    document.querySelectorAll('.testimonial-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.borderColor = 'var(--primary-300)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.borderColor = 'var(--border-color)';
        });
    });
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 20px;
            right: auto;
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
    
    // Search testimonials (if search functionality is added)
    function searchTestimonials(query) {
        const cards = document.querySelectorAll('.testimonial-card');
        const searchTerm = query.toLowerCase();
        
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            
            if (text.includes(searchTerm)) {
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
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Home key to scroll to top
        if (e.key === 'Home') {
            e.preventDefault();
            scrollToTop();
        }
        
        // End key to scroll to bottom
        if (e.key === 'End') {
            e.preventDefault();
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });
        }
    });
    
    // Share testimonial (social sharing)
    function shareTestimonial(testimonialId) {
        if (navigator.share) {
            navigator.share({
                title: '{{ config("app.name") }} - تقييم العميل',
                text: 'اطلع على هذا التقييم',
                url: window.location.href + '#testimonial-' + testimonialId
            });
        } else {
            // Fallback: copy URL to clipboard
            const url = window.location.href + '#testimonial-' + testimonialId;
            navigator.clipboard.writeText(url).then(() => {
                showNotification('{{ __("تم نسخ رابط التقييم") }}', 'success');
            });
        }
    }
    
    // Initialize floating action button
    document.addEventListener('DOMContentLoaded', function() {
        const floatingAction = document.querySelector('.floating-action');
        if (floatingAction) {
            floatingAction.style.opacity = '0';
            floatingAction.style.transform = 'scale(0.8)';
            floatingAction.style.transition = 'all 0.3s ease-out';
        }
    });
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
    
    /* Loading skeleton for testimonials */
    .testimonial-skeleton {
        background: var(--surface);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        animation: pulse 1.5s ease-in-out infinite alternate;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        100% { opacity: 0.5; }
    }
    
    /* Smooth scrolling for browsers that support it */
    @supports (scroll-behavior: smooth) {
        html {
            scroll-behavior: smooth;
        }
    }
</style>
@endpush