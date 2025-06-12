@extends('layouts.admin')

@section('title', __('Grades Management'))
@section('page-title', __('Grades Management'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Grades') }}
    </div>
@endsection

@push('styles')
<style>
    .grades-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .grades-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .grades-stats {
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
    
    .grades-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .grade-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: all var(--transition-normal);
        border: 1px solid var(--admin-secondary-200);
        position: relative;
        cursor: pointer;
    }
    
    .grade-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }
    
    .grade-header {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        padding: var(--space-lg);
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
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
    }
    
    .grade-number-display {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        position: relative;
        z-index: 2;
    }
    
    .grade-number {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.5rem;
        color: white;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .grade-info {
        flex: 1;
    }
    
    .grade-name {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: var(--space-xs);
        color: white;
    }
    
    .grade-name-ar {
        font-size: 1rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
        direction: rtl;
        font-style: italic;
    }
    
    .grade-status {
        position: absolute;
        top: var(--space-sm);
        right: var(--space-sm);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        z-index: 3;
    }
    
    .status-active {
        background: var(--success-500);
        color: white;
    }
    
    .status-inactive {
        background: var(--error-500);
        color: white;
    }
    
    .grade-content {
        padding: var(--space-lg);
    }
    
    .grade-platform {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
        color: var(--admin-primary-600);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: color var(--transition-fast);
    }
    
    .grade-platform:hover {
        color: var(--admin-primary-700);
    }
    
    .platform-icon {
        width: 24px;
        height: 24px;
        border-radius: var(--radius-sm);
        background: var(--admin-primary-100);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        color: var(--admin-primary-600);
    }
    
    .grade-description {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: var(--space-lg);
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .grade-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: var(--space-md);
        border-top: 1px solid var(--admin-secondary-200);
        margin-bottom: var(--space-md);
    }
    
    .subjects-count {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .count-badge {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .grade-created {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .grade-actions {
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
    
    @media (max-width: 768px) {
        .grades-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .grades-stats {
            width: 100%;
            justify-content: space-between;
        }
        
        .grades-grid {
            grid-template-columns: 1fr;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .grade-number-display {
            flex-direction: column;
            text-align: center;
        }
        
        .grade-number {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Grades Header -->
<div class="grades-header">
    <div>
        <h1 class="grades-title">
            <i class="fas fa-graduation-cap"></i>
            {{ __('Grades Management') }}
        </h1>
    </div>
    
    <div class="grades-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $grades->total() }}</div>
            <div class="stat-label">{{ __('Total Grades') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $grades->where('is_active', true)->count() }}</div>
            <div class="stat-label">{{ __('Active') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $grades->sum(function($grade) { return $grade->subjects->count(); }) }}</div>
            <div class="stat-label">{{ __('Total Subjects') }}</div>
        </div>
        
        <a href="{{ route('admin.grades.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('Add Grade') }}
        </a>
    </div>
</div>

<!-- Filters Section -->
<div class="filters-section fade-in">
    <h2 class="filters-title">
        <i class="fas fa-filter"></i>
        {{ __('Filter Grades') }}
    </h2>
    
    <form method="GET" action="{{ route('admin.grades.index') }}">
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">{{ __('Search') }}</label>
                <input 
                    type="text" 
                    name="search" 
                    class="filter-input" 
                    placeholder="{{ __('Search by name or description...') }}"
                    value="{{ request('search') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">{{ __('Platform') }}</label>
                <select name="platform" class="filter-input">
                    <option value="">{{ __('All Platforms') }}</option>
                    @foreach(\App\Models\Platform::where('is_active', true)->get() as $platform)
                        <option value="{{ $platform->id }}" {{ request('platform') == $platform->id ? 'selected' : '' }}>
                            {{ $platform->name }}
                        </option>
                    @endforeach
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
                <label class="filter-label">{{ __('Sort By') }}</label>
                <select name="sort" class="filter-input">
                    <option value="grade_number" {{ request('sort') == 'grade_number' ? 'selected' : '' }}>{{ __('Grade Number') }}</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>{{ __('Name') }}</option>
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>{{ __('Date Created') }}</option>
                </select>
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                {{ __('Filter') }}
            </button>
            
            @if(request()->hasAny(['search', 'platform', 'status', 'sort']))
                <a href="{{ route('admin.grades.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('Clear Filters') }}
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Grades Grid -->
@if($grades->count() > 0)
    <div class="grades-grid">
        @foreach($grades as $grade)
            <div class="grade-card fade-in" onclick="window.location.href='{{ route('admin.grades.show', $grade) }}'">
                <div class="grade-header">
                    <div class="grade-number-display">
                        <div class="grade-number">{{ $grade->grade_number }}</div>
                        <div class="grade-info">
                            <div class="grade-name">{{ $grade->name }}</div>
                            @if($grade->name_ar)
                                <div class="grade-name-ar">{{ $grade->name_ar }}</div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grade-status status-{{ $grade->is_active ? 'active' : 'inactive' }}">
                        {{ $grade->is_active ? __('Active') : __('Inactive') }}
                    </div>
                </div>
                
                <div class="grade-content">
                    <a href="{{ route('admin.platforms.show', $grade->platform) }}" class="grade-platform" onclick="event.stopPropagation()">
                        <div class="platform-icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        {{ $grade->platform->name }}
                    </a>
                    
                    @if($grade->description)
                        <p class="grade-description">{{ $grade->description }}</p>
                    @else
                        <p class="grade-description" style="color: var(--admin-secondary-400); font-style: italic;">
                            {{ __('No description available') }}
                        </p>
                    @endif
                    
                    <div class="grade-meta">
                        <div class="subjects-count">
                            <i class="fas fa-book"></i>
                            <span class="count-badge">{{ $grade->subjects->count() }}</span>
                            {{ __('Subjects') }}
                        </div>
                        <div class="grade-created">
                            {{ $grade->created_at->format('M d, Y') }}
                        </div>
                    </div>
                    
                    <div class="grade-actions" onclick="event.stopPropagation()">
                        <a href="{{ route('admin.grades.show', $grade) }}" class="action-btn view">
                            <i class="fas fa-eye"></i>
                            {{ __('View') }}
                        </a>
                        
                        <a href="{{ route('admin.grades.edit', $grade) }}" class="action-btn edit">
                            <i class="fas fa-edit"></i>
                            {{ __('Edit') }}
                        </a>
                        
                        <button class="action-btn delete" onclick="deleteGrade('{{ $grade->id }}')">
                            <i class="fas fa-trash"></i>
                            {{ __('Delete') }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($grades->hasPages())
        <div class="pagination-wrapper">
            {{ $grades->appends(request()->query())->links() }}
        </div>
    @endif
@else
    <div class="grades-grid">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3 class="empty-title">{{ __('No Grades Found') }}</h3>
            <p class="empty-text">
                @if(request()->hasAny(['search', 'platform', 'status', 'sort']))
                    {{ __('No grades match your search criteria.') }}
                @else
                    {{ __('Start organizing your educational content by creating your first grade level.') }}
                @endif
            </p>
            <a href="{{ route('admin.grades.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                {{ __('Create First Grade') }}
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
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Grade') }}</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete this grade? This action cannot be undone and will affect all associated subjects.') }}
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
    let gradeToDelete = null;
    
    // Delete grade functions
    function deleteGrade(gradeId) {
        event.stopPropagation(); // Prevent card click
        gradeToDelete = gradeId;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        gradeToDelete = null;
    }
    
    function confirmDelete() {
        if (gradeToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/grades/${gradeToDelete}`;
            
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