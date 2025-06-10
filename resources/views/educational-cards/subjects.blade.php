@extends('layouts.app')

@section('title', (app()->getLocale() === 'ar' && $grade->name_ar ? $grade->name_ar : $grade->name) . ' - ' . __('Subjects') . ' - ' . config('app.name'))

@push('styles')
<style>
    .subjects-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .subjects-hero::before {
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
    
    .grade-info {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .grade-badge {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 900;
        flex-shrink: 0;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }
    
    .grade-details h1 {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .grade-meta {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-size: 1rem;
        opacity: 0.9;
    }
    
    .platform-tag {
        background: rgba(255, 255, 255, 0.2);
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .subjects-container {
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
    
    .subjects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .subject-card {
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
    
    .subject-card::before {
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
    
    .subject-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-200);
    }
    
    .subject-card:hover::before {
        transform: scaleX(1);
    }
    
    .subject-image {
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .subject-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-slow);
    }
    
    .subject-card:hover .subject-image img {
        transform: scale(1.05);
    }
    
    .subject-icon {
        font-size: 3rem;
        color: var(--primary-500);
        opacity: 0.6;
    }
    
    .subject-content {
        padding: var(--space-xl);
    }
    
    .subject-name {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
        line-height: 1.3;
    }
    
    .subject-description {
        color: var(--on-surface-variant);
        margin-bottom: var(--space-lg);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .subject-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: var(--space-lg);
        padding: var(--space-md);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
    }
    
    .subject-stat {
        text-align: center;
        flex: 1;
    }
    
    .subject-stat-number {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-600);
        display: block;
    }
    
    .subject-stat-label {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-xs);
    }
    
    .subject-features {
        display: flex;
        flex-wrap: wrap;
        gap: var(--space-sm);
        margin-bottom: var(--space-lg);
    }
    
    .feature-tag {
        background: var(--primary-50);
        color: var(--primary-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid var(--primary-200);
    }
    
    .subject-cta {
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
    
    .subject-card:hover .cta-icon {
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
    
    .grade-summary {
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
    
    .summary-description {
        color: var(--on-surface-variant);
        line-height: 1.6;
        margin-bottom: var(--space-lg);
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
    
    .subject-difficulty {
        position: absolute;
        top: var(--space-md);
        right: var(--space-md);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .difficulty-easy {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .difficulty-medium {
        background: var(--warning-100);
        color: var(--warning-700);
        border: 1px solid var(--warning-200);
    }
    
    .difficulty-hard {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    @media (max-width: 768px) {
        .grade-info {
            flex-direction: column;
            text-align: center;
            gap: var(--space-md);
        }
        
        .grade-details h1 {
            font-size: 2rem;
        }
        
        .grade-meta {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .subjects-grid {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .summary-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-md);
        }
        
        .breadcrumb {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="subjects-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('educational-cards.index') }}" class="breadcrumb-link">
                    <i class="fas fa-graduation-cap"></i>
                    {{ __('Educational Cards') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('educational-cards.grades', $platform) }}" class="breadcrumb-link">
                    {{ app()->getLocale() === 'ar' && $platform->name_ar ? $platform->name_ar : $platform->name }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ app()->getLocale() === 'ar' && $grade->name_ar ? $grade->name_ar : $grade->name }}</span>
            </nav>
            
            <!-- Grade Info -->
            <div class="grade-info">
                <div class="grade-badge">
                    {{ $grade->grade_number }}
                </div>
                
                <div class="grade-details">
                    <h1>{{ app()->getLocale() === 'ar' && $grade->name_ar ? $grade->name_ar : $grade->name }}</h1>
                    <div class="grade-meta">
                        <span class="platform-tag">
                            {{ app()->getLocale() === 'ar' && $platform->name_ar ? $platform->name_ar : $platform->name }}
                        </span>
                        <span>{{ __('Grade') }} {{ $grade->grade_number }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Subjects Container -->
<section class="subjects-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('educational-cards.grades', $platform) }}" class="back-button fade-in">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Grades') }}
        </a>
        
        <!-- Grade Summary -->
        <div class="grade-summary fade-in">
            <h3 class="summary-title">
                <i class="fas fa-info-circle"></i>
                {{ __('Grade Overview') }}
            </h3>
            @if($grade->description || $grade->description_ar)
                <p class="summary-description">
                    {{ app()->getLocale() === 'ar' && $grade->description_ar ? $grade->description_ar : $grade->description }}
                </p>
            @endif
            <div class="summary-stats">
                <div class="summary-stat">
                    <span class="summary-stat-number">{{ $subjects->count() }}</span>
                    <div class="summary-stat-label">{{ __('Available Subjects') }}</div>
                </div>
                <div class="summary-stat">
                    <span class="summary-stat-number">{{ $subjects->sum(function($subject) { return $subject->educationalCards->count(); }) }}</span>
                    <div class="summary-stat-label">{{ __('Total Cards') }}</div>
                </div>
                <div class="summary-stat">
                    <span class="summary-stat-number">{{ $subjects->where('is_active', true)->count() }}</span>
                    <div class="summary-stat-label">{{ __('Active Subjects') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Section Header -->
        <div class="section-header fade-in">
            <h2 class="section-title">{{ __('Choose Your Subject') }}</h2>
            <p class="section-subtitle">{{ __('Select a subject to explore educational cards designed specifically for your grade level and learning objectives.') }}</p>
        </div>
        
        <!-- Subjects Grid -->
        @if($subjects->count() > 0)
            <div class="subjects-grid">
                @foreach($subjects as $subject)
                    <a href="{{ route('educational-cards.cards', [$platform, $grade, $subject]) }}" class="subject-card fade-in">
                        <!-- Difficulty Badge -->
                        @if($subject->educationalCards->isNotEmpty())
                            @php
                                $difficulties = $subject->educationalCards->pluck('difficulty_level')->unique();
                                $mainDifficulty = $difficulties->first();
                            @endphp
                            <div class="subject-difficulty difficulty-{{ $mainDifficulty }}">
                                {{ ucfirst($mainDifficulty) }}
                            </div>
                        @endif
                        
                        <div class="subject-image">
                            @if($subject->image)
                                <img src="{{ $subject->image_url }}" alt="{{ $subject->name }}" loading="lazy">
                            @else
                                <i class="subject-icon fas fa-book"></i>
                            @endif
                        </div>
                        
                        <div class="subject-content">
                            <h3 class="subject-name">
                                {{ app()->getLocale() === 'ar' && $subject->name_ar ? $subject->name_ar : $subject->name }}
                            </h3>
                            
                            @if($subject->description || $subject->description_ar)
                                <p class="subject-description">
                                    {{ app()->getLocale() === 'ar' && $subject->description_ar ? $subject->description_ar : $subject->description }}
                                </p>
                            @endif
                            
                            <div class="subject-stats">
                                <div class="subject-stat">
                                    <span class="subject-stat-number">{{ $subject->educationalCards->count() }}</span>
                                    <div class="subject-stat-label">{{ __('Cards') }}</div>
                                </div>
                                <div class="subject-stat">
                                    <span class="subject-stat-number">{{ $subject->educationalCards->where('is_active', true)->count() }}</span>
                                    <div class="subject-stat-label">{{ __('Active') }}</div>
                                </div>
                                <div class="subject-stat">
                                    <span class="subject-stat-number">{{ $subject->educationalCards->where('stock', '>', 0)->count() }}</span>
                                    <div class="subject-stat-label">{{ __('Available') }}</div>
                                </div>
                            </div>
                            
                            <!-- Subject Features -->
                            @if($subject->educationalCards->isNotEmpty())
                                <div class="subject-features">
                                    @php
                                        $cardTypes = $subject->educationalCards->pluck('card_type')->unique();
                                    @endphp
                                    @foreach($cardTypes as $type)
                                        <span class="feature-tag">{{ ucfirst($type) }}</span>
                                    @endforeach
                                    
                                    @if($subject->educationalCards->where('stock', '>', 0)->count() > 0)
                                        <span class="feature-tag">{{ __('In Stock') }}</span>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="subject-cta">
                                <span class="cta-text">{{ __('View Cards') }}</span>
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
                <h3 class="empty-title">{{ __('No Subjects Available') }}</h3>
                <p class="empty-text">{{ __('Subjects for this grade are being prepared. Please check back soon or explore other grades.') }}</p>
                <a href="{{ route('educational-cards.grades', $platform) }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Grades') }}
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
    
    // Add hover effects to subject cards
    document.querySelectorAll('.subject-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            const subjectIcon = this.querySelector('.subject-icon');
            if (subjectIcon) {
                subjectIcon.style.transform = 'scale(1.1) rotate(5deg)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const subjectIcon = this.querySelector('.subject-icon');
            if (subjectIcon) {
                subjectIcon.style.transform = 'scale(1) rotate(0deg)';
            }
        });
    });
    
    // Add loading animation for navigation
    document.querySelectorAll('.subject-card').forEach(card => {
        card.addEventListener('click', function(e) {
            const ctaIcon = this.querySelector('.cta-icon');
            if (ctaIcon) {
                ctaIcon.classList.remove('fa-arrow-right');
                ctaIcon.classList.add('fa-spinner', 'fa-spin');
            }
        });
    });
    
    // Add interactive effects to feature tags
    document.querySelectorAll('.feature-tag').forEach(tag => {
        tag.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        tag.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
</script>
@endpush