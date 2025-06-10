@extends('layouts.app')

@section('title', (app()->getLocale() === 'ar' && $platform->name_ar ? $platform->name_ar : $platform->name) . ' - ' . __('Grades') . ' - ' . config('app.name'))

@push('styles')
<style>
    .grades-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .grades-hero::before {
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
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-lg);
        font-size: 0.875rem;
        opacity: 0.9;
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
    
    .platform-info {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .platform-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
    }
    
    .platform-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: var(--radius-xl);
    }
    
    .platform-details h1 {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .platform-description {
        font-size: 1.125rem;
        opacity: 0.9;
        line-height: 1.6;
    }
    
    .grades-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
    }
    
    .section-header {
        text-align: center;
        margin-bottom: var(--space-2xl);
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: var(--space-md);
        background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .section-subtitle {
        font-size: 1rem;
        color: var(--on-surface-variant);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.7;
    }
    
    .grades-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .grade-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: all var(--transition-normal);
        position: relative;
        text-decoration: none;
        color: inherit;
        box-shadow: var(--shadow-sm);
    }
    
    .grade-card::before {
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
    
    .grade-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-200);
    }
    
    .grade-card:hover::before {
        transform: scaleX(1);
    }
    
    .grade-header {
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        padding: var(--space-xl);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .grade-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><circle cx="30" cy="30" r="20"/><circle cx="70" cy="70" r="15"/></svg>');
        animation: float 20s ease-in-out infinite;
    }
    
    .grade-number {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-md);
        font-size: 1.5rem;
        font-weight: 900;
        position: relative;
        z-index: 1;
        box-shadow: var(--shadow-md);
    }
    
    .grade-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-700);
        position: relative;
        z-index: 1;
    }
    
    .grade-content {
        padding: var(--space-xl);
    }
    
    .grade-description {
        color: var(--on-surface-variant);
        margin-bottom: var(--space-lg);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .grade-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: var(--space-lg);
        padding: var(--space-md);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
    }
    
    .grade-stat {
        text-align: center;
        flex: 1;
    }
    
    .grade-stat-number {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-600);
        display: block;
    }
    
    .grade-stat-label {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-xs);
    }
    
    .grade-cta {
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
    
    .grade-card:hover .cta-icon {
        transform: translateX(4px);
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
        line-height: 1.6;
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        background: var(--surface);
        color: var(--primary-600);
        border: 2px solid var(--primary-200);
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-weight: 600;
        transition: all var(--transition-fast);
        margin-bottom: var(--space-xl);
    }
    
    .back-button:hover {
        background: var(--primary-50);
        border-color: var(--primary-300);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .platform-summary {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-2xl);
        box-shadow: var(--shadow-sm);
    }
    
    .summary-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: var(--space-md);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .summary-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: var(--space-lg);
    }
    
    .summary-stat {
        text-align: center;
        padding: var(--space-md);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
    }
    
    .summary-stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-600);
        display: block;
        margin-bottom: var(--space-xs);
    }
    
    .summary-stat-label {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
        font-weight: 500;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(5deg); }
    }
    
    @media (max-width: 768px) {
        .platform-info {
            flex-direction: column;
            text-align: center;
            gap: var(--space-md);
        }
        
        .platform-details h1 {
            font-size: 2rem;
        }
        
        .grades-grid {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .summary-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-md);
        }
        
        .breadcrumb {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="grades-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('educational-cards.index') }}" class="breadcrumb-link">
                    <i class="fas fa-graduation-cap"></i>
                    {{ __('Educational Cards') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ app()->getLocale() === 'ar' && $platform->name_ar ? $platform->name_ar : $platform->name }}</span>
            </nav>
            
            <!-- Platform Info -->
            <div class="platform-info">
                <div class="platform-icon">
                    @if($platform->image)
                        <img src="{{ $platform->image_url }}" alt="{{ $platform->name }}">
                    @else
                        <i class="fas fa-graduation-cap"></i>
                    @endif
                </div>
                
                <div class="platform-details">
                    <h1>{{ app()->getLocale() === 'ar' && $platform->name_ar ? $platform->name_ar : $platform->name }}</h1>
                    @if($platform->description || $platform->description_ar)
                        <p class="platform-description">
                            {{ app()->getLocale() === 'ar' && $platform->description_ar ? $platform->description_ar : $platform->description }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Grades Container -->
<section class="grades-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('educational-cards.index') }}" class="back-button fade-in">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Platforms') }}
        </a>
        
        <!-- Platform Summary -->
        <div class="platform-summary fade-in">
            <h3 class="summary-title">
                <i class="fas fa-chart-bar"></i>
                {{ __('Platform Overview') }}
            </h3>
            <div class="summary-stats">
                <div class="summary-stat">
                    <span class="summary-stat-number">{{ $grades->count() }}</span>
                    <div class="summary-stat-label">{{ __('Available Grades') }}</div>
                </div>
                <div class="summary-stat">
                    <span class="summary-stat-number">{{ $grades->sum(function($grade) { return $grade->subjects->count(); }) }}</span>
                    <div class="summary-stat-label">{{ __('Total Subjects') }}</div>
                </div>
                <div class="summary-stat">
                    <span class="summary-stat-number">{{ $grades->sum(function($grade) { return $grade->subjects->sum(function($subject) { return $subject->educationalCards->count(); }); }) }}</span>
                    <div class="summary-stat-label">{{ __('Total Cards') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Section Header -->
        <div class="section-header fade-in">
            <h2 class="section-title">{{ __('Select Your Grade Level') }}</h2>
            <p class="section-subtitle">{{ __('Choose your academic grade to access relevant subjects and educational content designed for your level.') }}</p>
        </div>
        
        <!-- Grades Grid -->
        @if($grades->count() > 0)
            <div class="grades-grid">
                @foreach($grades as $grade)
                    <a href="{{ route('educational-cards.subjects', [$platform, $grade]) }}" class="grade-card fade-in">
                        <div class="grade-header">
                            <div class="grade-number">{{ $grade->grade_number }}</div>
                            <h3 class="grade-name">
                                {{ app()->getLocale() === 'ar' && $grade->name_ar ? $grade->name_ar : $grade->name }}
                            </h3>
                        </div>
                        
                        <div class="grade-content">
                            @if($grade->description || $grade->description_ar)
                                <p class="grade-description">
                                    {{ app()->getLocale() === 'ar' && $grade->description_ar ? $grade->description_ar : $grade->description }}
                                </p>
                            @endif
                            
                            <div class="grade-stats">
                                <div class="grade-stat">
                                    <span class="grade-stat-number">{{ $grade->subjects->count() }}</span>
                                    <div class="grade-stat-label">{{ __('Subjects') }}</div>
                                </div>
                                <div class="grade-stat">
                                    <span class="grade-stat-number">{{ $grade->subjects->sum(function($subject) { return $subject->educationalCards->count(); }) }}</span>
                                    <div class="grade-stat-label">{{ __('Cards') }}</div>
                                </div>
                            </div>
                            
                            <div class="grade-cta">
                                <span class="cta-text">{{ __('Explore Subjects') }}</span>
                                <i class="cta-icon fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state fade-in">
                <div class="empty-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 class="empty-title">{{ __('No Grades Available') }}</h3>
                <p class="empty-text">{{ __('Grades for this platform are being prepared. Please check back soon or explore other platforms.') }}</p>
                <a href="{{ route('educational-cards.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Platforms') }}
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
    
    // Add hover effects to grade cards
    document.querySelectorAll('.grade-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            const gradeNumber = this.querySelector('.grade-number');
            if (gradeNumber) {
                gradeNumber.style.transform = 'scale(1.1) rotate(5deg)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const gradeNumber = this.querySelector('.grade-number');
            if (gradeNumber) {
                gradeNumber.style.transform = 'scale(1) rotate(0deg)';
            }
        });
    });
    
    // Add loading animation for navigation
    document.querySelectorAll('.grade-card').forEach(card => {
        card.addEventListener('click', function(e) {
            const ctaIcon = this.querySelector('.cta-icon');
            if (ctaIcon) {
                ctaIcon.classList.remove('fa-arrow-right');
                ctaIcon.classList.add('fa-spinner', 'fa-spin');
            }
        });
    });
</script>
@endpush