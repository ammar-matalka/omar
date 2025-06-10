@extends('layouts.app')

@section('title', __('Educational Cards') . ' - ' . config('app.name'))

@push('styles')
<style>
    .educational-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-3xl) 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .educational-hero::before {
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
        max-width: 800px;
        margin: 0 auto;
    }
    
    .hero-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-lg);
        font-size: 2rem;
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
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        font-size: 0.875rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .platforms-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
    }
    
    .section-header {
        text-align: center;
        margin-bottom: var(--space-2xl);
    }
    
    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: var(--space-md);
        background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .section-subtitle {
        font-size: 1.125rem;
        color: var(--on-surface-variant);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.7;
    }
    
    .platforms-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-2xl);
        margin-bottom: var(--space-2xl);
    }
    
    .platform-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-2xl);
        overflow: hidden;
        transition: all var(--transition-normal);
        position: relative;
        text-decoration: none;
        color: inherit;
        box-shadow: var(--shadow-md);
    }
    
    .platform-card::before {
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
    
    .platform-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-200);
    }
    
    .platform-card:hover::before {
        transform: scaleX(1);
    }
    
    .platform-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .platform-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-slow);
    }
    
    .platform-card:hover .platform-image img {
        transform: scale(1.05);
    }
    
    .platform-icon {
        font-size: 3rem;
        color: var(--primary-500);
        opacity: 0.3;
    }
    
    .platform-content {
        padding: var(--space-xl);
    }
    
    .platform-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
        line-height: 1.3;
    }
    
    .platform-description {
        color: var(--on-surface-variant);
        margin-bottom: var(--space-lg);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .platform-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: var(--space-lg);
        padding: var(--space-md);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
    }
    
    .platform-stat {
        text-align: center;
        flex: 1;
    }
    
    .platform-stat-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-600);
        display: block;
    }
    
    .platform-stat-label {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-xs);
    }
    
    .platform-cta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, var(--primary-50), var(--secondary-50));
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        transition: all var(--transition-fast);
    }
    
    .cta-text {
        font-weight: 600;
        color: var(--primary-700);
    }
    
    .cta-icon {
        color: var(--primary-600);
        font-size: 1.125rem;
        transition: transform var(--transition-fast);
    }
    
    .platform-card:hover .cta-icon {
        transform: translateX(4px);
    }
    
    .features-section {
        background: var(--surface);
        padding: var(--space-3xl) 0;
        border-top: 1px solid var(--border-color);
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-xl);
    }
    
    .feature-card {
        text-align: center;
        padding: var(--space-xl);
        border-radius: var(--radius-xl);
        background: var(--background);
        border: 1px solid var(--border-color);
        transition: all var(--transition-normal);
    }
    
    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-200);
    }
    
    .feature-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-lg);
        color: white;
        font-size: 1.5rem;
        transition: all var(--transition-normal);
    }
    
    .feature-card:hover .feature-icon {
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
        line-height: 1.6;
    }
    
    .search-section {
        background: linear-gradient(135deg, var(--gray-900), var(--gray-800));
        color: white;
        padding: var(--space-3xl) 0;
        text-align: center;
    }
    
    .search-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: var(--space-md);
    }
    
    .search-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: var(--space-xl);
    }
    
    .search-form {
        max-width: 600px;
        margin: 0 auto;
        display: flex;
        gap: var(--space-md);
        background: white;
        padding: var(--space-sm);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
    }
    
    .search-input {
        flex: 1;
        border: none;
        padding: var(--space-md) var(--space-lg);
        border-radius: var(--radius-lg);
        font-size: 1rem;
        color: var(--on-surface);
        background: transparent;
    }
    
    .search-input:focus {
        outline: none;
    }
    
    .search-button {
        padding: var(--space-md) var(--space-xl);
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .search-button:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--on-surface-variant);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
    }
    
    .empty-text {
        margin-bottom: var(--space-lg);
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-stats {
            gap: var(--space-lg);
        }
        
        .platforms-grid {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .features-grid {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .search-form {
            flex-direction: column;
            gap: var(--space-sm);
        }
        
        .search-button {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="educational-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <div class="hero-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1 class="hero-title">{{ __('Educational Cards') }}</h1>
            <p class="hero-subtitle">{{ __('Discover a world of learning with our comprehensive collection of educational cards designed to enhance your knowledge across different platforms and subjects.') }}</p>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ $platforms->count() }}+</div>
                    <div class="stat-label">{{ __('Platforms') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100+</div>
                    <div class="stat-label">{{ __('Subjects') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">{{ __('Cards') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Platforms Section -->
<section class="platforms-container">
    <div class="container">
        <div class="section-header fade-in">
            <h2 class="section-title">{{ __('Choose Your Learning Platform') }}</h2>
            <p class="section-subtitle">{{ __('Select from our diverse range of educational platforms, each offering specialized content tailored to different learning objectives and academic levels.') }}</p>
        </div>
        
        @if($platforms->count() > 0)
            <div class="platforms-grid">
                @foreach($platforms as $platform)
                    <a href="{{ route('educational-cards.grades', $platform) }}" class="platform-card fade-in">
                        <div class="platform-image">
                            @if($platform->image)
                                <img src="{{ $platform->image_url }}" alt="{{ $platform->name }}" loading="lazy">
                            @else
                                <i class="platform-icon fas fa-graduation-cap"></i>
                            @endif
                        </div>
                        
                        <div class="platform-content">
                            <h3 class="platform-name">
                                {{ app()->getLocale() === 'ar' && $platform->name_ar ? $platform->name_ar : $platform->name }}
                            </h3>
                            
                            <p class="platform-description">
                                {{ app()->getLocale() === 'ar' && $platform->description_ar ? $platform->description_ar : $platform->description }}
                            </p>
                            
                            <div class="platform-stats">
                                <div class="platform-stat">
                                    <span class="platform-stat-number">{{ $platform->grades->count() }}</span>
                                    <div class="platform-stat-label">{{ __('Grades') }}</div>
                                </div>
                                <div class="platform-stat">
                                    <span class="platform-stat-number">{{ $platform->grades->sum(function($grade) { return $grade->subjects->count(); }) }}</span>
                                    <div class="platform-stat-label">{{ __('Subjects') }}</div>
                                </div>
                                <div class="platform-stat">
                                    <span class="platform-stat-number">{{ $platform->grades->sum(function($grade) { return $grade->subjects->sum(function($subject) { return $subject->educationalCards->count(); }); }) }}</span>
                                    <div class="platform-stat-label">{{ __('Cards') }}</div>
                                </div>
                            </div>
                            
                            <div class="platform-cta">
                                <span class="cta-text">{{ __('Explore Grades') }}</span>
                                <i class="cta-icon fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state fade-in">
                <div class="empty-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="empty-title">{{ __('No Platforms Available') }}</h3>
                <p class="empty-text">{{ __('Educational platforms are being prepared. Please check back soon!') }}</p>
            </div>
        @endif
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header fade-in">
            <h2 class="section-title">{{ __('Why Choose Our Educational Cards?') }}</h2>
            <p class="section-subtitle">{{ __('Our educational cards are designed with modern learning principles to provide an engaging and effective study experience.') }}</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <h3 class="feature-title">{{ __('Interactive Learning') }}</h3>
                <p class="feature-description">{{ __('Engage with dynamic content that adapts to your learning style and pace for maximum retention.') }}</p>
            </div>
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">{{ __('Progress Tracking') }}</h3>
                <p class="feature-description">{{ __('Monitor your learning progress with detailed analytics and personalized recommendations.') }}</p>
            </div>
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="feature-title">{{ __('Expert Content') }}</h3>
                <p class="feature-description">{{ __('All content is created and reviewed by subject matter experts and experienced educators.') }}</p>
            </div>
            
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="feature-title">{{ __('Mobile Friendly') }}</h3>
                <p class="feature-description">{{ __('Access your educational cards anytime, anywhere with our responsive design.') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="search-section">
    <div class="container">
        <div class="fade-in">
            <h2 class="search-title">{{ __('Looking for Something Specific?') }}</h2>
            <p class="search-subtitle">{{ __('Search through our extensive library of educational cards') }}</p>
            
            <form action="{{ route('educational-cards.search') }}" method="GET" class="search-form">
                <input 
                    type="text" 
                    name="q" 
                    class="search-input" 
                    placeholder="{{ __('Search educational cards...') }}"
                    value="{{ request('q') }}"
                >
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                    {{ __('Search') }}
                </button>
            </form>
        </div>
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
    
    // Add hover effects to platform cards
    document.querySelectorAll('.platform-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Smooth scroll to platforms section when coming from navigation
    if (window.location.hash === '#platforms') {
        document.querySelector('.platforms-container').scrollIntoView({
            behavior: 'smooth'
        });
    }
</script>
@endpush