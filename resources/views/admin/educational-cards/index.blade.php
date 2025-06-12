@extends('layouts.admin')

@section('title', __('Educational Cards Management'))
@section('page-title', __('Educational Cards Management'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Educational Cards') }}
    </div>
@endsection

@push('styles')
<style>
    .cards-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .cards-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .cards-stats {
        display: flex;
        gap: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: var(--space-md);
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--admin-secondary-200);
        min-width: 100px;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-primary-600);
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-xs);
    }
    
    .filters-section {
        background: white;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .filters-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: var(--space-lg);
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .filter-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--admin-secondary-700);
    }
    
    .filter-input {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        transition: border-color var(--transition-fast);
    }
    
    .filter-input:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .filter-actions {
        display: flex;
        gap: var(--space-sm);
        justify-content: flex-end;
    }
    
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .card-item {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: all var(--transition-normal);
        border: 1px solid var(--admin-secondary-200);
        position: relative;
    }
    
    .card-item:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }
    
    .card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, var(--admin-primary-100), var(--admin-primary-200));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-primary-600);
        font-size: 3rem;
        position: relative;
    }
    
    .card-status {
        position: absolute;
        top: var(--space-sm);
        right: var(--space-sm);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-active {
        background: var(--success-500);
        color: white;
    }
    
    .status-inactive {
        background: var(--error-500);
        color: white;
    }
    
    .difficulty-badge {
        position: absolute;
        top: var(--space-sm);
        left: var(--space-sm);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
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
    
    .card-type-badge {
        position: absolute;
        bottom: var(--space-sm);
        right: var(--space-sm);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        background: rgba(255, 255, 255, 0.9);
        color: var(--admin-secondary-700);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .card-content {
        padding: var(--space-lg);
    }
    
    .card-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-hierarchy {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        margin-bottom: var(--space-md);
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
    }
    
    .hierarchy-item {
        background: var(--admin-secondary-100);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
    }
    
    .hierarchy-separator {
        color: var(--admin-secondary-400);
    }
    
    .card-description {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: var(--space-lg);
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: var(--space-md);
        border-top: 1px solid var(--admin-secondary-200);
        margin-bottom: var(--space-md);
    }
    
    .card-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--admin-primary-600);
    }
    
    .card-stock {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .card-actions {
        display: flex;
        gap: var(--space-xs);
        justify-content: center;
    }
    
    .action-btn {
        flex: 1;
        padding: var(--space-sm);
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.875rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-xs);
        font-weight: 500;
    }
    
    .action-btn.view {
        background: var(--admin-primary-100);
        color: var(--admin-primary-600);
    }
    
    .action-btn.view:hover {
        background: var(--admin-primary-200);
        transform: translateY(-1px);
    }
    
    .action-btn.edit {
        background: var(--warning-100);
        color: var(--warning-600);
    }
    
    .action-btn.edit:hover {
        background: var(--warning-200);
        transform: translateY(-1px);
    }
    
    .action-btn.delete {
        background: var(--error-100);
        color: var(--error-600);
    }
    
    .action-btn.delete:hover {
        background: var(--error-200);
        transform: translateY(-1px);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
        grid-column: 1 / -1;
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: var(--space-sm);
        color: var(--admin-secondary-700);
    }
    
    .empty-text {
        margin-bottom: var(--space-xl);
        font-size: 1.125rem;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: var(--space-xl);
    }
    
    .pagination {
        display: flex;
        gap: var(--space-xs);
        align-items: center;
    }
    
    .page-link {
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        color: var(--admin-secondary-600);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all var(--transition-fast);
    }
    
    .page-link:hover {
        background: var(--admin-primary-50);
        border-color: var(--admin-primary-300);
        color: var(--admin-primary-600);
    }
    
    .page-link.active {
        background: var(--admin-primary-600);
        border-color: var(--admin-primary-600);
        color: white;
    }
    
    .page-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    @media (max-width: 768px) {
        .cards-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .cards-stats {
            width: 100%;
            justify-content: space-between;
        }
        
        .cards-grid {
            grid-template-columns: 1fr;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Cards Header -->
<div class="cards-header">
    <div>
        <h1 class="cards-title">
            <i class="fas fa-graduation-cap"></i>
            {{ __('Educational Cards Management') }}
        </h1>
    </div>
    
    <div class="cards-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $cards->total() }}</div>
            <div class="stat-label">{{ __('Total Cards') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $cards->where('is_active', true)->count() }}</div>
            <div class="stat-label">{{ __('Active') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $cards->where('stock', '<=', 5)->count() }}</div>
            <div class="stat-label">{{ __('Low Stock') }}</div>
        </div>
        
        <a href="{{ route('admin.educational-cards.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('Add Educational Card') }}
        </a>
    </div>
</div>

<!-- Filters Section -->
<div class="filters-section fade-in">
    <h2 class="filters-title">
        <i class="fas fa-filter"></i>
        {{ __('Filter Educational Cards') }}
    </h2>
    
    <form method="GET" action="{{ route('admin.educational-cards.index') }}">
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">{{ __('Search') }}</label>
                <input 
                    type="text" 
                    name="search" 
                    class="filter-input" 
                    placeholder="{{ __('Search by title or description...') }}"
                    value="{{ request('search') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Platform') }}</label>
                <select name="platform" class="filter-input" onchange="loadGrades(this.value)">
                    <option value="">{{ __('All Platforms') }}</option>
                    @foreach(\App\Models\Platform::where('is_active', true)->get() as $platform)
                        <option value="{{ $platform->id }}" {{ request('platform') == $platform->id ? 'selected' : '' }}>
                            {{ $platform->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Grade') }}</label>
                <select name="grade" class="filter-input" id="gradeSelect" onchange="loadSubjects(this.value)">
                    <option value="">{{ __('All Grades') }}</option>
                    @if(request('platform'))
                        @foreach(\App\Models\Grade::where('platform_id', request('platform'))->where('is_active', true)->get() as $grade)
                            <option value="{{ $grade->id }}" {{ request('grade') == $grade->id ? 'selected' : '' }}>
                                {{ $grade->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Subject') }}</label>
                <select name="subject" class="filter-input" id="subjectSelect">
                    <option value="">{{ __('All Subjects') }}</option>
                    @if(request('grade'))
                        @foreach(\App\Models\Subject::where('grade_id', request('grade'))->where('is_active', true)->get() as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Card Type') }}</label>
                <select name="card_type" class="filter-input">
                    <option value="">{{ __('All Types') }}</option>
                    <option value="digital" {{ request('card_type') == 'digital' ? 'selected' : '' }}>{{ __('Digital') }}</option>
                    <option value="physical" {{ request('card_type') == 'physical' ? 'selected' : '' }}>{{ __('Physical') }}</option>
                    <option value="both" {{ request('card_type') == 'both' ? 'selected' : '' }}>{{ __('Both') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Difficulty') }}</label>
                <select name="difficulty" class="filter-input">
                    <option value="">{{ __('All Levels') }}</option>
                    <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>{{ __('Easy') }}</option>
                    <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                    <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>{{ __('Hard') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Status') }}</label>
                <select name="status" class="filter-input">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Price Range') }}</label>
                <input 
                    type="number" 
                    name="min_price" 
                    class="filter-input" 
                    placeholder="{{ __('Min Price') }}"
                    value="{{ request('min_price') }}"
                    step="0.01"
                >
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                {{ __('Filter') }}
            </button>
            
            @if(request()->hasAny(['search', 'platform', 'grade', 'subject', 'card_type', 'difficulty', 'status', 'min_price']))
                <a href="{{ route('admin.educational-cards.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('Clear Filters') }}
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Educational Cards Grid -->
@if($cards->count() > 0)
    <div class="cards-grid">
        @foreach($cards as $card)
            <div class="card-item fade-in">
                <div class="card-image">
                    @if($card->image || ($card->images && $card->images->first()))
                        <img src="{{ Storage::url($card->image ?? $card->images->first()->image_path) }}" alt="{{ $card->title }}">
                    @else
                        <i class="fas fa-graduation-cap"></i>
                    @endif
                    
                    <div class="card-status status-{{ $card->is_active ? 'active' : 'inactive' }}">
                        {{ $card->is_active ? __('Active') : __('Inactive') }}
                    </div>
                    
                    <div class="difficulty-badge difficulty-{{ $card->difficulty_level }}">
                        {{ ucfirst($card->difficulty_level) }}
                    </div>
                    
                    <div class="card-type-badge">
                        {{ ucfirst($card->card_type) }}
                    </div>
                </div>
                
                <div class="card-content">
                    <h3 class="card-title">{{ $card->title }}</h3>
                    
                    @if($card->subject)
                        <div class="card-hierarchy">
                            <span class="hierarchy-item">{{ $card->subject->grade->platform->name }}</span>
                            <span class="hierarchy-separator">›</span>
                            <span class="hierarchy-item">{{ $card->subject->grade->name }}</span>
                            <span class="hierarchy-separator">›</span>
                            <span class="hierarchy-item">{{ $card->subject->name }}</span>
                        </div>
                    @endif
                    
                    @if($card->description)
                        <p class="card-description">{{ $card->description }}</p>
                    @else
                        <p class="card-description" style="color: var(--admin-secondary-400); font-style: italic;">
                            {{ __('No description available') }}
                        </p>
                    @endif
                    
                    <div class="card-meta">
                        <div class="card-price">${{ number_format($card->price, 2) }}</div>
                        <div class="card-stock">
                            <i class="fas fa-boxes"></i>
                            {{ $card->stock }} {{ __('units') }}
                        </div>
                    </div>
                    
                    <div class="card-actions">
                        <a href="{{ route('admin.educational-cards.show', $card) }}" class="action-btn view">
                            <i class="fas fa-eye"></i>
                            {{ __('View') }}
                        </a>
                        
                        <a href="{{ route('admin.educational-cards.edit', $card) }}" class="action-btn edit">
                            <i class="fas fa-edit"></i>
                            {{ __('Edit') }}
                        </a>
                        
                        <button class="action-btn delete" onclick="deleteCard('{{ $card->id }}')">
                            <i class="fas fa-trash"></i>
                            {{ __('Delete') }}
                        </button>
                    </div>
                </div>
            </div>
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
    <div class="cards-grid">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3 class="empty-title">{{ __('No Educational Cards Found') }}</h3>
            <p class="empty-text">
                @if(request()->hasAny(['search', 'platform', 'grade', 'subject', 'card_type', 'difficulty', 'status', 'min_price']))
                    {{ __('No educational cards match your search criteria.') }}
                @else
                    {{ __('Start building your educational content by creating your first educational card.') }}
                @endif
            </p>
            <a href="{{ route('admin.educational-cards.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                {{ __('Create First Educational Card') }}
            </a>
        </div>
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-xl); max-width: 400px; width: 90%; text-align: center;">
        <div style="color: var(--error-500); font-size: 3rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Educational Card') }}</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete this educational card? This action cannot be undone.') }}
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center;">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">{{ __('Cancel') }}</button>
            <button onclick="confirmDelete()" class="btn btn-danger">{{ __('Delete') }}</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cardToDelete = null;
    
    // Load grades based on selected platform
    function loadGrades(platformId) {
        const gradeSelect = document.getElementById('gradeSelect');
        const subjectSelect = document.getElementById('subjectSelect');
        
        // Clear grade and subject selects
        gradeSelect.innerHTML = '<option value="">{{ __("All Grades") }}</option>';
        subjectSelect.innerHTML = '<option value="">{{ __("All Subjects") }}</option>';
        
        if (platformId) {
            fetch(`/admin/platforms/${platformId}/grades`)
                .then(response => response.json())
                .then(grades => {
                    grades.forEach(grade => {
                        const option = document.createElement('option');
                        option.value = grade.id;
                        option.textContent = grade.name;
                        gradeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading grades:', error));
        }
    }
    
    // Load subjects based on selected grade
    function loadSubjects(gradeId) {
        const subjectSelect = document.getElementById('subjectSelect');
        
        // Clear subject select
        subjectSelect.innerHTML = '<option value="">{{ __("All Subjects") }}</option>';
        
        if (gradeId) {
            fetch(`/admin/grades/${gradeId}/subjects`)
                .then(response => response.json())
                .then(subjects => {
                    subjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        subjectSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading subjects:', error));
        }
    }
    
    // Delete card functions
    function deleteCard(cardId) {
        cardToDelete = cardId;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        cardToDelete = null;
    }
    
    function confirmDelete() {
        if (cardToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/educational-cards/${cardToDelete}`;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endpush