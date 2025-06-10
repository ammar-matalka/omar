@extends('layouts.app')

@section('title', __('Search Results') . ' - ' . __('Educational Cards') . ' - ' . config('app.name'))

@push('styles')
<style>
    .search-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .search-hero::before {
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
    
    .search-title {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-md);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .search-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: var(--space-xl);
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }
    
    .search-form-hero {
        max-width: 600px;
        margin: 0 auto;
        display: flex;
        gap: var(--space-md);
        background: white;
        padding: var(--space-sm);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
    }
    
    .search-input-hero {
        flex: 1;
        border: none;
        padding: var(--space-md) var(--space-lg);
        border-radius: var(--radius-lg);
        font-size: 1rem;
        color: var(--on-surface);
        background: transparent;
    }
    
    .search-input-hero:focus {
        outline: none;
    }
    
    .search-button-hero {
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
    
    .search-button-hero:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
    }
    
    .search-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
    }
    
    .search-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-2xl);
        flex-wrap: wrap;
        gap: var(--space-lg);
    }
    
    .search-results-info {
        flex: 1;
    }
    
    .results-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
    }
    
    .results-meta {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .search-query-highlight {
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        color: var(--primary-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-weight: 600;
    }
    
    .search-filters {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-2xl);
        box-shadow: var(--shadow-sm);
    }
    
    .filters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-lg);
    }
    
    .filters-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .clear-filters {
        color: var(--primary-600);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: color var(--transition-fast);
    }
    
    .clear-filters:hover {
        color: var(--primary-700);
        text-decoration: underline;
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .filter-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--on-surface-variant);
    }
    
    .filter-select {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 0.875rem;
        transition: border-color var(--transition-fast);
    }
    
    .filter-select:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .filter-input {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 0.875rem;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .search-results {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .result-card {
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
    
    .result-card::before {
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
    
    .result-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-200);
    }
    
    .result-card:hover::before {
        transform: scaleX(1);
    }
    
    .result-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .result-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-slow);
    }
    
    .result-card:hover .result-image img {
        transform: scale(1.05);
    }
    
    .image-placeholder {
        font-size: 3rem;
        color: var(--primary-500);
        opacity: 0.6;
    }
    
    .result-badges {
        position: absolute;
        top: var(--space-md);
        left: var(--space-md);
        right: var(--space-md);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .result-badge {
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        backdrop-filter: blur(10px);
    }
    
    .badge-difficulty {
        background: rgba(255, 255, 255, 0.9);
        color: var(--on-surface);
    }
    
    .badge-type {
        background: rgba(14, 165, 233, 0.9);
        color: white;
    }
    
    .result-content {
        padding: var(--space-xl);
    }
    
    .result-title {
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .result-description {
        color: var(--on-surface-variant);
        margin-bottom: var(--space-lg);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .result-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-lg);
        font-size: 0.875rem;
    }
    
    .result-subject {
        color: var(--primary-600);
        font-weight: 500;
    }
    
    .result-grade {
        color: var(--on-surface-variant);
    }
    
    .result-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-md);
        background: var(--surface-variant);
        border-top: 1px solid var(--border-color);
    }
    
    .result-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-600);
    }
    
    .result-stock {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .stock-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    .stock-available {
        background: var(--success-500);
    }
    
    .stock-low {
        background: var(--warning-500);
    }
    
    .stock-out {
        background: var(--error-500);
    }
    
    .no-results {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--on-surface-variant);
    }
    
    .no-results-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
    }
    
    .no-results-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
    }
    
    .no-results-text {
        margin-bottom: var(--space-lg);
        line-height: 1.6;
    }
    
    .suggestions {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .suggestions-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: var(--space-md);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .suggestions-list {
        display: flex;
        flex-wrap: wrap;
        gap: var(--space-sm);
    }
    
    .suggestion-tag {
        background: var(--primary-50);
        color: var(--primary-700);
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-md);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all var(--transition-fast);
        border: 1px solid var(--primary-200);
    }
    
    .suggestion-tag:hover {
        background: var(--primary-100);
        color: var(--primary-800);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: var(--space-2xl);
    }
    
    .pagination {
        display: flex;
        gap: var(--space-xs);
        align-items: center;
    }
    
    .page-link {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        color: var(--on-surface-variant);
        text-decoration: none;
        font-weight: 500;
        transition: all var(--transition-fast);
    }
    
    .page-link:hover {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
    }
    
    .page-link.active {
        background: var(--primary-500);
        border-color: var(--primary-500);
        color: white;
    }
    
    .page-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    @media (max-width: 768px) {
        .search-form-hero {
            flex-direction: column;
            gap: var(--space-sm);
        }
        
        .search-button-hero {
            justify-content: center;
        }
        
        .search-title {
            font-size: 2rem;
        }
        
        .search-header {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-md);
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
            gap: var(--space-md);
        }
        
        .search-results {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .suggestions-list {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<!-- Search Hero -->
<section class="search-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <h1 class="search-title">{{ __('Search Educational Cards') }}</h1>
            <p class="search-subtitle">{{ __('Find the perfect educational cards to enhance your learning journey') }}</p>
            
            <form action="{{ route('educational-cards.search') }}" method="GET" class="search-form-hero">
                <input 
                    type="text" 
                    name="q" 
                    class="search-input-hero" 
                    placeholder="{{ __('Search for educational cards...') }}"
                    value="{{ $query }}"
                    autocomplete="off"
                >
                <button type="submit" class="search-button-hero">
                    <i class="fas fa-search"></i>
                    {{ __('Search') }}
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Search Container -->
<section class="search-container">
    <div class="container">
        <!-- Search Header -->
        <div class="search-header fade-in">
            <div class="search-results-info">
                <h2 class="results-title">
                    @if($query)
                        {{ __('Search Results') }}
                    @else
                        {{ __('All Educational Cards') }}
                    @endif
                </h2>
                <div class="results-meta">
                    @if($query)
                        {{ __('Showing') }} {{ $cards->count() }} {{ __('of') }} {{ $cards->total() }} {{ __('results for') }}
                        <span class="search-query-highlight">"{{ $query }}"</span>
                    @else
                        {{ __('Showing') }} {{ $cards->count() }} {{ __('of') }} {{ $cards->total() }} {{ __('educational cards') }}
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Search Filters -->
        <div class="search-filters fade-in">
            <div class="filters-header">
                <h3 class="filters-title">
                    <i class="fas fa-filter"></i>
                    {{ __('Filter Results') }}
                </h3>
                <a href="{{ route('educational-cards.search') }}{{ $query ? '?q=' . urlencode($query) : '' }}" class="clear-filters">
                    {{ __('Clear Filters') }}
                </a>
            </div>
            
            <form method="GET" class="filters-grid">
                <input type="hidden" name="q" value="{{ $query }}">
                
                <div class="filter-group">
                    <label class="filter-label">{{ __('Subject') }}</label>
                    <select name="subject" class="filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('All Subjects') }}</option>
                        <!-- Add dynamic subjects here -->
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">{{ __('Grade') }}</label>
                    <select name="grade" class="filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('All Grades') }}</option>
                        <!-- Add dynamic grades here -->
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">{{ __('Platform') }}</label>
                    <select name="platform" class="filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('All Platforms') }}</option>
                        <!-- Add dynamic platforms here -->
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">{{ __('Card Type') }}</label>
                    <select name="type" class="filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('All Types') }}</option>
                        <option value="digital" {{ request('type') == 'digital' ? 'selected' : '' }}>{{ __('Digital') }}</option>
                        <option value="physical" {{ request('type') == 'physical' ? 'selected' : '' }}>{{ __('Physical') }}</option>
                        <option value="both" {{ request('type') == 'both' ? 'selected' : '' }}>{{ __('Both') }}</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">{{ __('Difficulty') }}</label>
                    <select name="difficulty" class="filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('All Levels') }}</option>
                        <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>{{ __('Easy') }}</option>
                        <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                        <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>{{ __('Hard') }}</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">{{ __('Price Range') }}</label>
                    <input type="number" name="min_price" class="filter-input" placeholder="{{ __('Min Price') }}" value="{{ request('min_price') }}" step="0.01">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">&nbsp;</label>
                    <input type="number" name="max_price" class="filter-input" placeholder="{{ __('Max Price') }}" value="{{ request('max_price') }}" step="0.01">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">{{ __('Sort By') }}</label>
                    <select name="sort" class="filter-select" onchange="this.form.submit()">
                        <option value="relevance" {{ request('sort', 'relevance') == 'relevance' ? 'selected' : '' }}>{{ __('Relevance') }}</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>{{ __('Title A-Z') }}</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>{{ __('Title Z-A') }}</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('Price Low-High') }}</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('Price High-Low') }}</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                    </select>
                </div>
            </form>
        </div>
        
        <!-- Search Suggestions (for empty results) -->
        @if($cards->isEmpty() && $query)
            <div class="suggestions fade-in">
                <h3 class="suggestions-title">
                    <i class="fas fa-lightbulb"></i>
                    {{ __('Search Suggestions') }}
                </h3>
                <div class="suggestions-list">
                    <a href="{{ route('educational-cards.search', ['q' => 'math']) }}" class="suggestion-tag">{{ __('Mathematics') }}</a>
                    <a href="{{ route('educational-cards.search', ['q' => 'science']) }}" class="suggestion-tag">{{ __('Science') }}</a>
                    <a href="{{ route('educational-cards.search', ['q' => 'english']) }}" class="suggestion-tag">{{ __('English') }}</a>
                    <a href="{{ route('educational-cards.search', ['q' => 'arabic']) }}" class="suggestion-tag">{{ __('Arabic') }}</a>
                    <a href="{{ route('educational-cards.search', ['q' => 'history']) }}" class="suggestion-tag">{{ __('History') }}</a>
                    <a href="{{ route('educational-cards.search', ['q' => 'geography']) }}" class="suggestion-tag">{{ __('Geography') }}</a>
                </div>
            </div>
        @endif
        
        <!-- Search Results -->
        @if($cards->count() > 0)
            <div class="search-results">
                @foreach($cards as $card)
                    <a href="{{ route('educational-cards.show', $card) }}" class="result-card fade-in">
                        <div class="result-image">
                            @if($card->main_image_url)
                                <img src="{{ $card->main_image_url }}" alt="{{ $card->title }}" loading="lazy">
                            @else
                                <i class="image-placeholder fas fa-id-card"></i>
                            @endif
                            
                            <!-- Result Badges -->
                            <div class="result-badges">
                                <div class="result-badge badge-difficulty">
                                    {{ ucfirst($card->difficulty_level) }}
                                </div>
                                <div class="result-badge badge-type">
                                    {{ ucfirst($card->card_type) }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="result-content">
                            <h3 class="result-title">
                                {{ app()->getLocale() === 'ar' && $card->title_ar ? $card->title_ar : $card->title }}
                            </h3>
                            
                            <p class="result-description">
                                {{ app()->getLocale() === 'ar' && $card->description_ar ? $card->description_ar : $card->description }}
                            </p>
                            
                            <div class="result-meta">
                                <span class="result-subject">
                                    {{ app()->getLocale() === 'ar' && $card->subject->name_ar ? $card->subject->name_ar : $card->subject->name }}
                                </span>
                                <span class="result-grade">
                                    {{ app()->getLocale() === 'ar' && $card->subject->grade->name_ar ? $card->subject->grade->name_ar : $card->subject->grade->name }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="result-footer">
                            <div class="result-price">${{ number_format($card->price, 2) }}</div>
                            <div class="result-stock">
                                @if($card->stock > 10)
                                    <span class="stock-indicator stock-available"></span>
                                    {{ __('In Stock') }}
                                @elseif($card->stock > 0)
                                    <span class="stock-indicator stock-low"></span>
                                    {{ __('Low Stock') }}
                                @else
                                    <span class="stock-indicator stock-out"></span>
                                    {{ __('Out of Stock') }}
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($cards->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination">
                        @if($cards->onFirstPage())
                            <span class="page-link disabled">{{ __('Previous') }}</span>
                        @else
                            <a href="{{ $cards->appends(request()->query())->previousPageUrl() }}" class="page-link">{{ __('Previous') }}</a>
                        @endif
                        
                        @foreach($cards->appends(request()->query())->getUrlRange(1, $cards->lastPage()) as $page => $url)
                            @if($page == $cards->currentPage())
                                <span class="page-link active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                            @endif
                        @endforeach
                        
                        @if($cards->hasMorePages())
                            <a href="{{ $cards->appends(request()->query())->nextPageUrl() }}" class="page-link">{{ __('Next') }}</a>
                        @else
                            <span class="page-link disabled">{{ __('Next') }}</span>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="no-results fade-in">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="no-results-title">
                    @if($query)
                        {{ __('No Results Found') }}
                    @else
                        {{ __('No Educational Cards Available') }}
                    @endif
                </h3>
                <p class="no-results-text">
                    @if($query)
                        {{ __('Sorry, we couldn\'t find any educational cards matching your search for') }} "<strong>{{ $query }}</strong>".
                        {{ __('Try adjusting your search terms or filters.') }}
                    @else
                        {{ __('There are currently no educational cards available. Please check back later.') }}
                    @endif
                </p>
                
                @if($query)
                    <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
                        <a href="{{ route('educational-cards.search') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i>
                            {{ __('View All Cards') }}
                        </a>
                        <a href="{{ route('educational-cards.index') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i>
                            {{ __('Browse Categories') }}
                        </a>
                    </div>
                @else
                    <a href="{{ route('educational-cards.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('Back to Educational Cards') }}
                    </a>
                @endif
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
                }, index * 50);
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
    
    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.search-input-hero');
        const searchForm = document.querySelector('.search-form-hero');
        
        // Auto-submit search after typing stops
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length > 2 || this.value.length === 0) {
                    // Auto-search after 1 second of no typing
                    // searchForm.submit();
                }
            }, 1000);
        });
        
        // Search suggestions
        searchInput.addEventListener('focus', function() {
            // You can add search suggestions dropdown here
        });
        
        // Price range validation
        const minPriceInput = document.querySelector('input[name="min_price"]');
        const maxPriceInput = document.querySelector('input[name="max_price"]');
        
        if (minPriceInput && maxPriceInput) {
            minPriceInput.addEventListener('change', function() {
                const minValue = parseFloat(this.value);
                const maxValue = parseFloat(maxPriceInput.value);
                
                if (maxValue && minValue > maxValue) {
                    maxPriceInput.value = minValue;
                }
            });
            
            maxPriceInput.addEventListener('change', function() {
                const maxValue = parseFloat(this.value);
                const minValue = parseFloat(minPriceInput.value);
                
                if (minValue && maxValue < minValue) {
                    minPriceInput.value = maxValue;
                }
            });
        }
    });
    
    // Add loading animation for result cards
    document.querySelectorAll('.result-card').forEach(card => {
        card.addEventListener('click', function(e) {
            const placeholder = this.querySelector('.image-placeholder');
            if (placeholder) {
                placeholder.classList.remove('fa-id-card');
                placeholder.classList.add('fa-spinner', 'fa-spin');
            }
        });
    });
    
    // Highlight search terms in results
    function highlightSearchTerms() {
        const query = '{{ $query }}';
        if (!query) return;
        
        const terms = query.toLowerCase().split(' ').filter(term => term.length > 2);
        
        document.querySelectorAll('.result-title, .result-description').forEach(element => {
            let html = element.innerHTML;
            
            terms.forEach(term => {
                const regex = new RegExp(`(${term})`, 'gi');
                html = html.replace(regex, '<mark style="background: var(--primary-100); color: var(--primary-700); padding: 0 2px; border-radius: 2px;">$1</mark>');
            });
            
            element.innerHTML = html;
        });
    }
    
    // Call highlight function after page load
    window.addEventListener('load', highlightSearchTerms);
    
    // Add search analytics (optional)
    function trackSearch() {
        const query = '{{ $query }}';
        const resultsCount = {{ $cards->total() }};
        
        if (query && typeof gtag !== 'undefined') {
            gtag('event', 'search', {
                search_term: query,
                results_count: resultsCount
            });
        }
    }
    
    trackSearch();
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Focus search with Ctrl+K or Cmd+K
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.querySelector('.search-input-hero').focus();
        }
        
        // Clear search with Escape
        if (e.key === 'Escape') {
            const searchInput = document.querySelector('.search-input-hero');
            if (document.activeElement === searchInput) {
                searchInput.blur();
            }
        }
    });
    
    // Infinite scroll (optional - can be enabled if needed)
    function enableInfiniteScroll() {
        let loading = false;
        let currentPage = {{ $cards->currentPage() }};
        const lastPage = {{ $cards->lastPage() }};
        
        window.addEventListener('scroll', function() {
            if (loading || currentPage >= lastPage) return;
            
            const scrollHeight = document.documentElement.scrollHeight;
            const scrollTop = document.documentElement.scrollTop;
            const clientHeight = document.documentElement.clientHeight;
            
            if (scrollTop + clientHeight >= scrollHeight - 1000) {
                loading = true;
                currentPage++;
                
                // Load next page via AJAX
                // Implementation would go here
            }
        });
    }
    
    // Uncomment to enable infinite scroll
    // enableInfiniteScroll();
</script>
@endpush