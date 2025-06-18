@extends('layouts.admin')

@section('title', __('Dossiers Management'))
@section('page-title', __('Dossiers'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <span>{{ __('Dossiers') }}</span>
    </div>
@endsection

@push('styles')
<style>
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-2xl);
    }
    
    .stat-card {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
    }
    
    .stat-card.secondary {
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
    }
    
    .stat-card.warning {
        background: linear-gradient(135deg, var(--warning-500), var(--warning-600));
    }
    
    .stat-card.info {
        background: linear-gradient(135deg, var(--info-500), var(--info-600));
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
    }
    
    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }
    
    .filters-card {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        box-shadow: var(--shadow-sm);
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: var(--space-lg);
        align-items: end;
    }
    
    .dossier-card {
        transition: all var(--transition-normal);
        border: 1px solid var(--admin-secondary-200);
        position: relative;
    }
    
    .dossier-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        border-color: var(--admin-primary-300);
    }
    
    .dossier-header {
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-secondary-50));
        border-bottom: 1px solid var(--admin-secondary-200);
        padding: var(--space-md);
        position: relative;
    }
    
    .dossier-badges {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--space-sm);
    }
    
    .dossier-name {
        font-size: 1rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
        line-height: 1.3;
    }
    
    .dossier-meta {
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .dossier-body {
        padding: var(--space-md);
    }
    
    .dossier-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
        font-size: 0.75rem;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        color: var(--admin-secondary-600);
    }
    
    .dossier-pricing {
        background: var(--admin-primary-50);
        border: 1px solid var(--admin-primary-200);
        border-radius: var(--radius-md);
        padding: var(--space-sm);
        margin-bottom: var(--space-md);
        text-align: center;
    }
    
    .base-price {
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-decoration: line-through;
    }
    
    .final-price {
        font-size: 1rem;
        font-weight: 700;
        color: var(--admin-primary-700);
    }
    
    .platform-fee {
        font-size: 0.625rem;
        color: var(--admin-secondary-500);
    }
    
    .dossier-actions {
        display: flex;
        gap: var(--space-xs);
        flex-wrap: wrap;
    }
    
    .status-badge {
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-active {
        background: var(--success-100);
        color: var(--success-800);
    }
    
    .status-inactive {
        background: var(--error-100);
        color: var(--error-800);
    }
    
    .semester-badge {
        background: var(--info-100);
        color: var(--info-800);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.625rem;
        font-weight: 600;
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        opacity: 0.5;
    }
    
    .filter-count {
        background: var(--admin-primary-100);
        color: var(--admin-primary-800);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: var(--space-sm);
    }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="stats-cards fade-in">
    <div class="stat-card">
        <div class="stat-number">{{ $dossiers->total() }}</div>
        <div class="stat-label">{{ __('Total Dossiers') }}</div>
    </div>
    <div class="stat-card secondary">
        <div class="stat-number">{{ $dossiers->where('is_active', true)->count() }}</div>
        <div class="stat-label">{{ __('Active Dossiers') }}</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-number">{{ $dossiers->where('is_active', false)->count() }}</div>
        <div class="stat-label">{{ __('Inactive Dossiers') }}</div>
    </div>
    <div class="stat-card info">
        <div class="stat-number">{{ $dossiers->where('semester', 'both')->count() }}</div>
        <div class="stat-label">{{ __('Full Year Dossiers') }}</div>
    </div>
</div>

<!-- Page Header -->
<div class="card fade-in">
    <div class="card-header">
        <div style="display: flex; justify-content: between; align-items: center;">
            <h2 class="card-title">
                <i class="fas fa-book"></i>
                {{ __('Dossiers Management') }}
                @if(request()->hasAny(['generation_id', 'subject_id', 'teacher_id', 'platform_id', 'semester', 'status', 'search']))
                    <span class="filter-count">{{ $dossiers->total() }} {{ __('filtered') }}</span>
                @endif
            </h2>
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('admin.dossiers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Add Dossier') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filters-card fade-in">
    <form method="GET" action="{{ route('admin.dossiers.index') }}" id="filtersForm">
        <div class="filters-grid">
            <div class="form-group">
                <label class="form-label">{{ __('Search') }}</label>
                <input type="text" name="search" class="form-input" 
                       placeholder="{{ __('Search by name...') }}" 
                       value="{{ request('search') }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">{{ __('Generation') }}</label>
                <select name="generation_id" class="form-input" onchange="loadSubjects()">
                    <option value="">{{ __('All Generations') }}</option>
                    @foreach($generations as $generation)
                        <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                            {{ $generation->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">{{ __('Subject') }}</label>
                <select name="subject_id" class="form-input" id="subjectSelect" onchange="loadTeachers()">
                    <option value="">{{ __('All Subjects') }}</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">{{ __('Teacher') }}</label>
                <select name="teacher_id" class="form-input" id="teacherSelect">
                    <option value="">{{ __('All Teachers') }}</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">{{ __('Platform') }}</label>
                <select name="platform_id" class="form-input">
                    <option value="">{{ __('All Platforms') }}</option>
                    @foreach($platforms as $platform)
                        <option value="{{ $platform->id }}" {{ request('platform_id') == $platform->id ? 'selected' : '' }}>
                            {{ $platform->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">{{ __('Semester') }}</label>
                <select name="semester" class="form-input">
                    <option value="">{{ __('All Semesters') }}</option>
                    <option value="first" {{ request('semester') === 'first' ? 'selected' : '' }}>{{ __('First Semester') }}</option>
                    <option value="second" {{ request('semester') === 'second' ? 'selected' : '' }}>{{ __('Second Semester') }}</option>
                    <option value="both" {{ request('semester') === 'both' ? 'selected' : '' }}>{{ __('Both Semesters') }}</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">{{ __('Status') }}</label>
                <select name="status" class="form-input">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    {{ __('Filter') }}
                </button>
                <a href="{{ route('admin.dossiers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('Clear') }}
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Dossiers Grid -->
<div class="grid grid-cols-3 fade-in">
    @forelse($dossiers as $dossier)
        <div class="card dossier-card">
            <!-- Header -->
            <div class="dossier-header">
                <div class="dossier-badges">
                    <span class="status-badge {{ $dossier->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $dossier->status_text }}
                    </span>
                    <span class="semester-badge">
                        {{ $dossier->semester_text }}
                    </span>
                </div>
                
                <div class="dossier-name">{{ $dossier->name }}</div>
                
                <div class="dossier-meta">
                    <span><i class="fas fa-layer-group"></i> {{ $dossier->generation->display_name }}</span>
                    <span><i class="fas fa-book"></i> {{ $dossier->subject->name }}</span>
                </div>
            </div>
            
            <!-- Body -->
            <div class="dossier-body">
                <div class="dossier-details">
                    <div class="detail-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        {{ $dossier->teacher->name }}
                    </div>
                    
                    <div class="detail-item">
                        <i class="fas fa-desktop"></i>
                        {{ $dossier->platform->name }}
                    </div>
                    
                    @if($dossier->pages_count)
                        <div class="detail-item">
                            <i class="fas fa-file-alt"></i>
                            {{ $dossier->pages_count }} {{ __('pages') }}
                        </div>
                    @endif
                    
                    @if($dossier->file_size)
                        <div class="detail-item">
                            <i class="fas fa-hdd"></i>
                            {{ $dossier->file_size }}
                        </div>
                    @endif
                </div>
                
                <div class="dossier-pricing">
                    @if($dossier->platform->price_percentage > 0)
                        <div class="base-price">{{ $dossier->formatted_price }}</div>
                    @endif
                    <div class="final-price">{{ $dossier->formatted_final_price }}</div>
                    @if($dossier->platform->price_percentage > 0)
                        <div class="platform-fee">+{{ $dossier->platform->formatted_price_percentage }} {{ __('platform fee') }}</div>
                    @endif
                </div>
                
                @if($dossier->description)
                    <div style="font-size: 0.75rem; color: var(--admin-secondary-600); margin-bottom: var(--space-md); line-height: 1.4;">
                        {{ Str::limit($dossier->description, 80) }}
                    </div>
                @endif
                
                <div class="dossier-actions">
                    <a href="{{ route('admin.dossiers.show', $dossier) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-eye"></i>
                        {{ __('View') }}
                    </a>
                    
                    <a href="{{ route('admin.dossiers.edit', $dossier) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                        {{ __('Edit') }}
                    </a>
                    
                    <form method="POST" action="{{ route('admin.dossiers.toggle-status', $dossier) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $dossier->is_active ? 'btn-warning' : 'btn-success' }}">
                            <i class="fas {{ $dossier->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                        </button>
                    </form>
                    
                    @if($dossier->orderItems()->count() === 0)
                        <form method="POST" action="{{ route('admin.dossiers.destroy', $dossier) }}" 
                              style="display: inline;" 
                              onsubmit="return confirm('{{ __('Are you sure you want to delete this dossier?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1;">
            <div class="card">
                <div class="card-body empty-state">
                    <i class="fas fa-book empty-icon"></i>
                    <h3 style="color: var(--admin-secondary-600); margin-bottom: var(--space-md);">
                        @if(request()->hasAny(['generation_id', 'subject_id', 'teacher_id', 'platform_id', 'semester', 'status', 'search']))
                            {{ __('No Dossiers Found') }}
                        @else
                            {{ __('No Dossiers Yet') }}
                        @endif
                    </h3>
                    <p style="color: var(--admin-secondary-500); margin-bottom: var(--space-lg);">
                        @if(request()->hasAny(['generation_id', 'subject_id', 'teacher_id', 'platform_id', 'semester', 'status', 'search']))
                            {{ __('Try adjusting your filters to find what you\'re looking for.') }}
                        @else
                            {{ __('Start by adding your first dossier to the system.') }}
                        @endif
                    </p>
                    
                    @if(request()->hasAny(['generation_id', 'subject_id', 'teacher_id', 'platform_id', 'semester', 'status', 'search']))
                        <a href="{{ route('admin.dossiers.index') }}" class="btn btn-secondary" style="margin-right: var(--space-md);">
                            <i class="fas fa-times"></i>
                            {{ __('Clear Filters') }}
                        </a>
                    @endif
                    
                    <a href="{{ route('admin.dossiers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        {{ __('Add First Dossier') }}
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($dossiers->hasPages())
    <div style="margin-top: var(--space-2xl);" class="fade-in">
        {{ $dossiers->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
// Load subjects based on generation
function loadSubjects() {
    const generationId = document.querySelector('select[name="generation_id"]').value;
    const subjectSelect = document.getElementById('subjectSelect');
    const teacherSelect = document.getElementById('teacherSelect');
    
    // Reset dependent selects
    subjectSelect.innerHTML = '<option value="">{{ __('All Subjects') }}</option>';
    teacherSelect.innerHTML = '<option value="">{{ __('All Teachers') }}</option>';
    
    if (!generationId) return;
    
    // Load subjects for selected generation
    fetch(`/admin/educational-subjects/generation/${generationId}`)
        .then(response => response.json())
        .then(subjects => {
            subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                if (subject.id == '{{ request('subject_id') }}') {
                    option.selected = true;
                }
                subjectSelect.appendChild(option);
            });
            
            if ('{{ request('subject_id') }}') {
                loadTeachers();
            }
        })
        .catch(error => console.error('Error loading subjects:', error));
}

// Load teachers based on generation and subject
function loadTeachers() {
    const generationId = document.querySelector('select[name="generation_id"]').value;
    const subjectId = document.getElementById('subjectSelect').value;
    const teacherSelect = document.getElementById('teacherSelect');
    
    teacherSelect.innerHTML = '<option value="">{{ __('All Teachers') }}</option>';
    
    if (!generationId || !subjectId) return;
    
    fetch(`/admin/teachers/generation/${generationId}/subject/${subjectId}`)
        .then(response => response.json())
        .then(teachers => {
            teachers.forEach(teacher => {
                const option = document.createElement('option');
                option.value = teacher.id;
                option.textContent = teacher.name;
                if (teacher.id == '{{ request('teacher_id') }}') {
                    option.selected = true;
                }
                teacherSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading teachers:', error));
}

// Auto-submit on filter change (except search)
document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', function() {
        if (this.name !== 'search') {
            document.getElementById('filtersForm').submit();
        }
    });
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // If generation is selected, load its subjects
    if ('{{ request('generation_id') }}') {
        loadSubjects();
    }
});
</script>
@endpush