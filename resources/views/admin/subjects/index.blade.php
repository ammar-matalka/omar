@extends('layouts.admin')

@section('title', __('Subjects Management'))
@section('page-title', __('Subjects Management'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Subjects') }}
    </div>
@endsection

@push('styles')
<style>
    .subjects-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .subjects-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .subjects-stats {
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
    
    .subjects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .subject-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: all var(--transition-normal);
        border: 1px solid var(--admin-secondary-200);
        position: relative;
    }
    
    .subject-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }
    
    .subject-image {
        width: 100%;
        height: 160px;
        object-fit: cover;
        background: linear-gradient(135deg, var(--admin-primary-100), var(--admin-primary-200));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-primary-600);
        font-size: 2.5rem;
        position: relative;
    }
    
    .subject-status {
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
    
    .subject-content {
        padding: var(--space-lg);
    }
    
    .subject-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .subject-title-ar {
        font-size: 1rem;
        font-weight: 600;
        color: var(--admin-secondary-600);
        margin-bottom: var(--space-md);
        direction: rtl;
        font-style: italic;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .subject-hierarchy {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        margin-bottom: var(--space-md);
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        flex-wrap: wrap;
    }
    
    .hierarchy-item {
        background: var(--admin-secondary-100);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        text-decoration: none;
        color: inherit;
        transition: all var(--transition-fast);
    }
    
    .hierarchy-item:hover {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
    }
    
    .hierarchy-separator {
        color: var(--admin-secondary-400);
    }
    
    .subject-description {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: var(--space-lg);
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .subject-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: var(--space-md);
        border-top: 1px solid var(--admin-secondary-200);
        margin-bottom: var(--space-md);
    }
    
    .subject-cards {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .cards-count {
        font-weight: 600;
        color: var(--admin-primary-600);
        background: var(--admin-primary-100);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
    }
    
    .subject-created {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .subject-actions {
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
        .subjects-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .subjects-stats {
            width: 100%;
            justify-content: space-between;
        }
        
        .subjects-grid {
            grid-template-columns: 1fr;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .subject-hierarchy {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<!-- Subjects Header -->
<div class="subjects-header">
    <div>
        <h1 class="subjects-title">
            <i class="fas fa-book"></i>
            {{ __('Subjects Management') }}
        </h1>
    </div>
    
    <div class="subjects-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $subjects->total() }}</div>
            <div class="stat-label">{{ __('Total Subjects') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $subjects->where('is_active', true)->count() }}</div>
            <div class="stat-label">{{ __('Active') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $subjects->sum(function($subject) { return $subject->educationalCards->count(); }) }}</div>
            <div class="stat-label">{{ __('Total Cards') }}</div>
        </div>
        
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('Add Subject') }}
        </a>
    </div>
</div>

<!-- Filters Section -->
<div class="filters-section fade-in">
    <h2 class="filters-title">
        <i class="fas fa-filter"></i>
        {{ __('Filter Subjects') }}
    </h2>
    
    <form method="GET" action="{{ route('admin.subjects.index') }}">
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
                <select name="grade" class="filter-input" id="gradeSelect">
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
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>{{ __('Name') }}</option>
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>{{ __('Date Created') }}</option>
                    <option value="cards_count" {{ request('sort') == 'cards_count' ? 'selected' : '' }}>{{ __('Number of Cards') }}</option>
                </select>
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                {{ __('Filter') }}
            </button>
            
            @if(request()->hasAny(['search', 'platform', 'grade', 'status', 'sort']))
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('Clear Filters') }}
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Subjects Grid -->
@if($subjects->count() > 0)
    <div class="subjects-grid">
        @foreach($subjects as $subject)
            <div class="subject-card fade-in">
                <div class="subject-image">
                    @if($subject->image)
                        <img src="{{ Storage::url($subject->image) }}" alt="{{ $subject->name }}">
                    @else
                        <i class="fas fa-book"></i>
                    @endif
                    
                    <div class="subject-status status-{{ $subject->is_active ? 'active' : 'inactive' }}">
                        {{ $subject->is_active ? __('Active') : __('Inactive') }}
                    </div>
                </div>
                
                <div class="subject-content">
                    <h3 class="subject-title">{{ $subject->name }}</h3>
                    
                    @if($subject->name_ar)
                        <div class="subject-title-ar">{{ $subject->name_ar }}</div>
                    @endif
                    
                    <div class="subject-hierarchy">
                        <a href="{{ route('admin.platforms.show', $subject->grade->platform) }}" class="hierarchy-item">
                            <i class="fas fa-desktop"></i>
                            {{ $subject->grade->platform->name }}
                        </a>
                        
                        <span class="hierarchy-separator">›</span>
                        
                        <a href="{{ route('admin.grades.show', $subject->grade) }}" class="hierarchy-item">
                            <i class="fas fa-graduation-cap"></i>
                            {{ $subject->grade->name }}
                        </a>
                    </div>
                    
                    @if($subject->description)
                        <p class="subject-description">{{ $subject->description }}</p>
                    @else
                        <p class="subject-description" style="color: var(--admin-secondary-400); font-style: italic;">
                            {{ __('No description available') }}
                        </p>
                    @endif
                    
                    <div class="subject-meta">
                        <div class="subject-cards">
                            <i class="fas fa-id-card"></i>
                            <span class="cards-count">{{ $subject->educationalCards->count() }}</span>
                            {{ __('Cards') }}
                        </div>
                        <div class="subject-created">
                            {{ $subject->created_at->format('M d, Y') }}
                        </div>
                    </div>
                    
                    <div class="subject-actions">
                        <a href="{{ route('admin.subjects.show', $subject) }}" class="action-btn view">
                            <i class="fas fa-eye"></i>
                            {{ __('View') }}
                        </a>
                        
                        <a href="{{ route('admin.subjects.edit', $subject) }}" class="action-btn edit">
                            <i class="fas fa-edit"></i>
                            {{ __('Edit') }}
                        </a>
                        
                        <button class="action-btn delete" onclick="deleteSubject('{{ $subject->id }}')">
                            <i class="fas fa-trash"></i>
                            {{ __('Delete') }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($subjects->hasPages())
        <div class="pagination-wrapper">
            {{ $subjects->appends(request()->query())->links() }}
        </div>
    @endif
@else
    <div class="subjects-grid">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-book"></i>
            </div>
            <h3 class="empty-title">{{ __('No Subjects Found') }}</h3>
            <p class="empty-text">
                @if(request()->hasAny(['search', 'platform', 'grade', 'status', 'sort']))
                    {{ __('No subjects match your search criteria.') }}
                @else
                    {{ __('Start organizing your educational content by creating your first subject.') }}
                @endif
            </p>
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                {{ __('Create First Subject') }}
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
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Subject') }}</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete this subject? This action cannot be undone and will affect all associated educational cards.') }}
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
    let subjectToDelete = null;
    
    // Load grades based on selected platform
    function loadGrades(platformId) {
        const gradeSelect = document.getElementById('gradeSelect');
        
        // Clear grade select
        gradeSelect.innerHTML = '<option value="">{{ __("All Grades") }}</option>';
        
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
    
    // Delete subject functions
    function deleteSubject(subjectId) {
        subjectToDelete = subjectId;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        subjectToDelete = null;
    }
    
    function confirmDelete() {
        if (subjectToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/subjects/${subjectToDelete}`;
            
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