@extends('layouts.admin')

@section('title', __('Platforms Management'))
@section('page-title', __('Platforms'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <span>{{ __('Platforms') }}</span>
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
    
    .platform-card {
        transition: all var(--transition-normal);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .platform-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        border-color: var(--admin-primary-300);
    }
    
    .platform-icon {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-lg);
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-md);
    }
    
    .platform-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .platform-percentage {
        color: var(--admin-primary-600);
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
    }
    
    .platform-url {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        margin-bottom: var(--space-sm);
        word-break: break-all;
    }
    
    .platform-stats {
        display: flex;
        gap: var(--space-md);
        margin-bottom: var(--space-md);
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .platform-actions {
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
    
    .percentage-badge {
        background: var(--admin-primary-100);
        color: var(--admin-primary-800);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="stats-cards fade-in">
    <div class="stat-card">
        <div class="stat-number">{{ $platforms->total() }}</div>
        <div class="stat-label">{{ __('Total Platforms') }}</div>
    </div>
    <div class="stat-card secondary">
        <div class="stat-number">{{ $platforms->where('is_active', true)->count() }}</div>
        <div class="stat-label">{{ __('Active Platforms') }}</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-number">{{ $platforms->where('is_active', false)->count() }}</div>
        <div class="stat-label">{{ __('Inactive Platforms') }}</div>
    </div>
</div>

<!-- Page Header -->
<div class="card fade-in">
    <div class="card-header">
        <div style="display: flex; justify-content: between; align-items: center;">
            <h2 class="card-title">
                <i class="fas fa-desktop"></i>
                {{ __('Platforms Management') }}
            </h2>
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('admin.platforms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Add Platform') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filters-card fade-in">
    <form method="GET" action="{{ route('admin.platforms.index') }}" id="filtersForm">
        <div class="filters-grid">
            <div class="form-group">
                <label class="form-label">{{ __('Search') }}</label>
                <input type="text" name="search" class="form-input" 
                       placeholder="{{ __('Search by platform name...') }}" 
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
                <a href="{{ route('admin.platforms.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('Clear') }}
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Platforms Grid -->
<div class="grid grid-cols-3 fade-in">
    @forelse($platforms as $platform)
        <div class="card platform-card">
            <div class="card-body">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: var(--space-md);">
                    <span class="status-badge {{ $platform->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $platform->status_text }}
                    </span>
                    
                    <span class="percentage-badge">
                        +{{ $platform->price_percentage }}%
                    </span>
                </div>
                
                <div class="platform-icon">
                    <i class="fas fa-desktop"></i>
                </div>
                
                <div class="platform-name">{{ $platform->name }}</div>
                
                <div class="platform-percentage">
                    {{ __('Price Increase') }}: {{ $platform->formatted_price_percentage }}
                </div>
                
                @if($platform->website_url)
                    <div class="platform-url">
                        <i class="fas fa-link"></i>
                        <a href="{{ $platform->website_url }}" target="_blank" style="color: inherit;">
                            {{ Str::limit($platform->website_url, 30) }}
                        </a>
                    </div>
                @endif
                
                <div class="platform-stats">
                    <span>
                        <i class="fas fa-book"></i>
                        {{ $platform->dossiers_count ?? 0 }} {{ __('dossiers') }}
                    </span>
                    <span>
                        <i class="fas fa-shopping-cart"></i>
                        {{ $platform->orders_count ?? 0 }} {{ __('orders') }}
                    </span>
                </div>
                
                @if($platform->description)
                    <div style="margin-bottom: var(--space-md); font-size: 0.875rem; color: var(--admin-secondary-600); line-height: 1.4;">
                        {{ Str::limit($platform->description, 100) }}
                    </div>
                @endif
                
                <div class="platform-actions">
                    <a href="{{ route('admin.platforms.show', $platform) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-eye"></i>
                        {{ __('View') }}
                    </a>
                    
                    <a href="{{ route('admin.platforms.edit', $platform) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                        {{ __('Edit') }}
                    </a>
                    
                    <form method="POST" action="{{ route('admin.platforms.toggle-status', $platform) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $platform->is_active ? 'btn-warning' : 'btn-success' }}">
                            <i class="fas {{ $platform->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                            {{ $platform->is_active ? __('Deactivate') : __('Activate') }}
                        </button>
                    </form>
                    
                    @if($platform->dossiers()->count() === 0)
                        <form method="POST" action="{{ route('admin.platforms.destroy', $platform) }}" 
                              style="display: inline;" 
                              onsubmit="return confirm('{{ __('Are you sure you want to delete this platform?') }}')">
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
                    <i class="fas fa-desktop" style="font-size: 3rem; color: var(--admin-secondary-400); margin-bottom: var(--space-lg);"></i>
                    <h3 style="color: var(--admin-secondary-600); margin-bottom: var(--space-md);">{{ __('No Platforms Found') }}</h3>
                    <p style="color: var(--admin-secondary-500); margin-bottom: var(--space-lg);">
                        {{ __('Start by adding your first platform to the system.') }}
                    </p>
                    <a href="{{ route('admin.platforms.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        {{ __('Add First Platform') }}
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($platforms->hasPages())
    <div style="margin-top: var(--space-2xl);" class="fade-in">
        {{ $platforms->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
// Auto-submit filters on change
document.querySelector('select[name="status"]').addEventListener('change', function() {
    document.getElementById('filtersForm').submit();
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Platforms management page loaded');
});
</script>
@endpush