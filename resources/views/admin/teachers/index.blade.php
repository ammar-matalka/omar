@extends('layouts.admin')

@section('title', __('Teachers Management'))
@section('page-title', __('Teachers'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <span>{{ __('Teachers') }}</span>
    </div>
@endsection

@push('styles')
<style>
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        align-items: end;
    }
    
    .teacher-card {
        transition: all var(--transition-normal);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .teacher-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        border-color: var(--admin-primary-300);
    }
    
    .teacher-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-md);
    }
    
    .teacher-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .teacher-specialization {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        margin-bottom: var(--space-sm);
    }
    
    .teacher-stats {
        display: flex;
        gap: var(--space-md);
        margin-bottom: var(--space-md);
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .teacher-actions {
        display: flex;
        gap: var(--space-sm);
        flex-wrap: wrap;
    }
    
    .status-badge {
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
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
    
    .bulk-actions {
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        display: none;
    }
    
    .bulk-actions.show {
        display: block;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="stats-cards fade-in">
    <div class="stat-card">
        <div class="stat-number">{{ $teachers->total() }}</div>
        <div class="stat-label">{{ __('Total Teachers') }}</div>
    </div>
    <div class="stat-card secondary">
        <div class="stat-number">{{ $teachers->where('is_active', true)->count() }}</div>
        <div class="stat-label">{{ __('Active Teachers') }}</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-number">{{ $teachers->where('is_active', false)->count() }}</div>
        <div class="stat-label">{{ __('Inactive Teachers') }}</div>
    </div>
</div>

<!-- Page Header -->
<div class="card fade-in">
    <div class="card-header">
        <div style="display: flex; justify-content: between; align-items: center;">
            <h2 class="card-title">
                <i class="fas fa-chalkboard-teacher"></i>
                {{ __('Teachers Management') }}
            </h2>
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('admin.teachers.export') }}" class="btn btn-secondary">
                    <i class="fas fa-download"></i>
                    {{ __('Export CSV') }}
                </a>
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Add Teacher') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filters-card fade-in">
    <form method="GET" action="{{ route('admin.teachers.index') }}" id="filtersForm">
        <div class="filters-grid">
            <div class="form-group">
                <label class="form-label">{{ __('Search') }}</label>
                <input type="text" name="search" class="form-input" 
                       placeholder="{{ __('Search by name or specialization...') }}" 
                       value="{{ request('search') }}">
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
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('Clear') }}
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div class="bulk-actions" id="bulkActions">
    <form method="POST" action="{{ route('admin.teachers.bulk-action') }}" id="bulkForm">
        @csrf
        <div style="display: flex; align-items: center; gap: var(--space-lg);">
            <span><strong id="selectedCount">0</strong> {{ __('teachers selected') }}</span>
            
            <select name="action" class="form-input" style="width: auto;">
                <option value="">{{ __('Choose Action') }}</option>
                <option value="activate">{{ __('Activate') }}</option>
                <option value="deactivate">{{ __('Deactivate') }}</option>
                <option value="delete">{{ __('Delete') }}</option>
            </select>
            
            <button type="submit" class="btn btn-warning" onclick="return confirm('{{ __('Are you sure?') }}')">
                <i class="fas fa-bolt"></i>
                {{ __('Apply') }}
            </button>
            
            <button type="button" class="btn btn-secondary" onclick="clearSelection()">
                <i class="fas fa-times"></i>
                {{ __('Cancel') }}
            </button>
        </div>
    </form>
</div>

<!-- Teachers Grid -->
<div class="grid grid-cols-3 fade-in">
    @forelse($teachers as $teacher)
        <div class="card teacher-card">
            <div class="card-body">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: var(--space-md);">
                    <input type="checkbox" class="teacher-checkbox" value="{{ $teacher->id }}" 
                           onchange="updateSelection()" style="margin-top: var(--space-xs);">
                    
                    <span class="status-badge {{ $teacher->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $teacher->status_text }}
                    </span>
                </div>
                
                <div class="teacher-avatar">
                    {{ strtoupper(substr($teacher->name, 0, 2)) }}
                </div>
                
                <div class="teacher-name">{{ $teacher->name }}</div>
                
                @if($teacher->specialization)
                    <div class="teacher-specialization">
                        <i class="fas fa-graduation-cap"></i>
                        {{ $teacher->specialization }}
                    </div>
                @endif
                
                <div class="teacher-stats">
                    <span>
                        <i class="fas fa-book"></i>
                        {{ $teacher->dossiers_count ?? 0 }} {{ __('dossiers') }}
                    </span>
                    <span>
                        <i class="fas fa-shopping-cart"></i>
                        {{ $teacher->orders_count ?? 0 }} {{ __('orders') }}
                    </span>
                </div>
                
                @if($teacher->phone || $teacher->email)
                    <div style="margin-bottom: var(--space-md); font-size: 0.875rem; color: var(--admin-secondary-600);">
                        @if($teacher->phone)
                            <div><i class="fas fa-phone"></i> {{ $teacher->phone }}</div>
                        @endif
                        @if($teacher->email)
                            <div><i class="fas fa-envelope"></i> {{ $teacher->email }}</div>
                        @endif
                    </div>
                @endif
                
                <div class="teacher-actions">
                    <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-eye"></i>
                        {{ __('View') }}
                    </a>
                    
                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                        {{ __('Edit') }}
                    </a>
                    
                    <form method="POST" action="{{ route('admin.teachers.toggle-status', $teacher) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $teacher->is_active ? 'btn-warning' : 'btn-success' }}">
                            <i class="fas {{ $teacher->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                            {{ $teacher->is_active ? __('Deactivate') : __('Activate') }}
                        </button>
                    </form>
                    
                    @if($teacher->dossiers()->count() === 0 && $teacher->orderItems()->count() === 0)
                        <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" 
                              style="display: inline;" 
                              onsubmit="return confirm('{{ __('Are you sure you want to delete this teacher?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                                {{ __('Delete') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1;">
            <div class="card">
                <div class="card-body" style="text-align: center; padding: var(--space-2xl);">
                    <i class="fas fa-chalkboard-teacher" style="font-size: 3rem; color: var(--admin-secondary-400); margin-bottom: var(--space-lg);"></i>
                    <h3 style="color: var(--admin-secondary-600); margin-bottom: var(--space-md);">{{ __('No Teachers Found') }}</h3>
                    <p style="color: var(--admin-secondary-500); margin-bottom: var(--space-lg);">
                        {{ __('Start by adding your first teacher to the system.') }}
                    </p>
                    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        {{ __('Add First Teacher') }}
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($teachers->hasPages())
    <div style="margin-top: var(--space-2xl);" class="fade-in">
        {{ $teachers->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
// Bulk selection functionality
function updateSelection() {
    const checkboxes = document.querySelectorAll('.teacher-checkbox:checked');
    const count = checkboxes.length;
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    selectedCount.textContent = count;
    
    if (count > 0) {
        bulkActions.classList.add('show');
        updateBulkFormData();
    } else {
        bulkActions.classList.remove('show');
    }
}

function updateBulkFormData() {
    const checkboxes = document.querySelectorAll('.teacher-checkbox:checked');
    const bulkForm = document.getElementById('bulkForm');
    
    // Remove existing hidden inputs
    bulkForm.querySelectorAll('input[name="teachers[]"]').forEach(input => input.remove());
    
    // Add selected teacher IDs
    checkboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'teachers[]';
        input.value = checkbox.value;
        bulkForm.appendChild(input);
    });
}

function clearSelection() {
    document.querySelectorAll('.teacher-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelection();
}

// Auto-submit filters on change
document.querySelector('select[name="status"]').addEventListener('change', function() {
    document.getElementById('filtersForm').submit();
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateSelection();
});
</script>
@endpush