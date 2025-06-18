@extends('layouts.admin')

@section('title', __('Platform Details'))
@section('page-title', $platform->name)

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.platforms.index') }}" class="breadcrumb-link">{{ __('Platforms') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <span>{{ $platform->name }}</span>
    </div>
@endsection

@push('styles')
<style>
    .platform-header {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: var(--radius-lg);
        padding: var(--space-2xl);
        margin-bottom: var(--space-xl);
        position: relative;
        overflow: hidden;
    }
    
    .platform-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 200px;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><rect x="20" y="20" width="60" height="40" rx="5"/><circle cx="30" cy="70" r="8"/><circle cx="50" cy="70" r="8"/><circle cx="70" cy="70" r="8"/></svg>');
        background-size: cover;
    }
    
    .platform-info {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: var(--space-xl);
        align-items: center;
    }
    
    .platform-icon {
        width: 100px;
        height: 100px;
        border-radius: var(--radius-xl);
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 900;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }
    
    .platform-details h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: var(--space-sm);
    }
    
    .platform-percentage {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: var(--space-md);
    }
    
    .platform-meta {
        display: flex;
        gap: var(--space-lg);
        font-size: 0.875rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .platform-actions {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-xl);
    }
    
    .stat-card {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        text-align: center;
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-normal);
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-md);
        font-size: 1.5rem;
        color: white;
    }
    
    .stat-icon.primary {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
    }
    
    .stat-icon.success {
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
    }
    
    .stat-icon.warning {
        background: linear-gradient(135deg, var(--warning-500), var(--warning-600));
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 900;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .info-section {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        margin-bottom: var(--space-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    
    .section-header {
        background: var(--admin-secondary-50);
        border-bottom: 1px solid var(--admin-secondary-200);
        padding: var(--space-lg);
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .section-body {
        padding: var(--space-lg);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-lg);
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
    }
    
    .info-label {
        color: var(--admin-secondary-600);
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--space-xs);
    }
    
    .info-value {
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .info-value.empty {
        color: var(--admin-secondary-400);
        font-style: italic;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: var(--space-xs);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-badge.active {
        background: var(--success-100);
        color: var(--success-800);
    }
    
    .status-badge.inactive {
        background: var(--error-100);
        color: var(--error-800);
    }
    
    .price-calculator {
        background: var(--admin-primary-50);
        border: 1px solid var(--admin-primary-200);
        border-radius: var(--radius-md);
        padding: var(--space-lg);
    }
    
    .calc-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-sm) 0;
        border-bottom: 1px solid var(--admin-primary-200);
    }
    
    .calc-row:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 1rem;
        color: var(--admin-primary-700);
    }
    
    .calc-label {
        color: var(--admin-secondary-700);
    }
    
    .calc-value {
        font-weight: 500;
        color: var(--admin-secondary-900);
    }
    
    .dossiers-list {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .dossier-item {
        display: flex;
        align-items: center;
        justify-content: between;
        padding: var(--space-md);
        border-bottom: 1px solid var(--admin-secondary-100);
        transition: background var(--transition-fast);
    }
    
    .dossier-item:hover {
        background: var(--admin-secondary-50);
    }
    
    .dossier-item:last-child {
        border-bottom: none;
    }
    
    .dossier-info {
        flex: 1;
    }
    
    .dossier-name {
        font-weight: 500;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .dossier-meta {
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
    }
    
    .dossier-price {
        font-weight: 600;
        color: var(--admin-primary-600);
        margin-right: var(--space-md);
    }
</style>
@endpush

@section('content')
<!-- Platform Header -->
<div class="platform-header fade-in">
    <div class="platform-info">
        <div class="platform-icon">
            <i class="fas fa-desktop"></i>
        </div>
        
        <div class="platform-details">
            <h1>{{ $platform->name }}</h1>
            <div class="platform-percentage">
                <i class="fas fa-percentage"></i>
                {{ __('Price Increase') }}: {{ $platform->formatted_price_percentage }}
            </div>
            
            <div class="platform-meta">
                <div class="meta-item">
                    <i class="fas fa-calendar-plus"></i>
                    {{ __('Created') }}: {{ $platform->created_at->format('M d, Y') }}
                </div>
                
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    {{ __('Updated') }}: {{ $platform->updated_at->diffForHumans() }}
                </div>
                
                <div class="meta-item">
                    <span class="status-badge {{ $platform->is_active ? 'active' : 'inactive' }}">
                        <i class="fas {{ $platform->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $platform->status_text }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="platform-actions">
            <a href="{{ route('admin.platforms.edit', $platform) }}" class="btn btn-secondary">
                <i class="fas fa-edit"></i>
                {{ __('Edit Platform') }}
            </a>
            
            <form method="POST" action="{{ route('admin.platforms.toggle-status', $platform) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn {{ $platform->is_active ? 'btn-warning' : 'btn-success' }}">
                    <i class="fas {{ $platform->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                    {{ $platform->is_active ? __('Deactivate') : __('Activate') }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="stats-grid fade-in">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-number">{{ $stats['total_dossiers'] }}</div>
        <div class="stat-label">{{ __('Total Dossiers') }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-number">{{ $stats['active_dossiers'] }}</div>
        <div class="stat-label">{{ __('Active Dossiers') }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-number">{{ $stats['total_orders'] }}</div>
        <div class="stat-label">{{ __('Total Orders') }}</div>
    </div>
</div>

<!-- Platform Information -->
<div class="info-section fade-in">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-info-circle"></i>
            {{ __('Platform Information') }}
        </h3>
    </div>
    <div class="section-body">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">{{ __('Platform Name') }}</div>
                <div class="info-value">{{ $platform->name }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Website URL') }}</div>
                <div class="info-value {{ $platform->website_url ? '' : 'empty' }}">
                    @if($platform->website_url)
                        <a href="{{ $platform->website_url }}" target="_blank" style="color: var(--admin-primary-600);">
                            {{ $platform->website_url }}
                        </a>
                    @else
                        {{ __('Not provided') }}
                    @endif
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Price Percentage') }}</div>
                <div class="info-value">{{ $platform->formatted_price_percentage }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Display Order') }}</div>
                <div class="info-value">{{ $platform->order }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Status') }}</div>
                <div class="info-value">
                    <span class="status-badge {{ $platform->is_active ? 'active' : 'inactive' }}">
                        <i class="fas {{ $platform->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $platform->status_text }}
                    </span>
                </div>
            </div>
        </div>
        
        @if($platform->description)
            <div style="margin-top: var(--space-xl);">
                <div class="info-label">{{ __('Description') }}</div>
                <div class="info-value" style="margin-top: var(--space-sm); line-height: 1.6;">
                    {{ $platform->description }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Price Calculation -->
<div class="info-section fade-in">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-calculator"></i>
            {{ __('Price Calculation Examples') }}
        </h3>
    </div>
    <div class="section-body">
        <div class="price-calculator">
            <div style="font-weight: 600; margin-bottom: var(--space-md); color: var(--admin-primary-700);">
                {{ __('Example Calculations') }}
            </div>
            
            @php
                $examples = [5, 10, 15, 20, 25];
            @endphp
            
            @foreach($examples as $basePrice)
                <div class="calc-row">
                    <span class="calc-label">{{ $basePrice }}.00 JD {{ __('→') }}</span>
                    <span class="calc-value">{{ number_format($platform->calculatePrice($basePrice), 2) }} JD</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Platform's Dossiers -->
@if($platform->dossiers->count() > 0)
<div class="info-section fade-in">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-book"></i>
            {{ __('Dossiers') }} ({{ $platform->dossiers->count() }})
        </h3>
    </div>
    <div class="section-body">
        <div class="dossiers-list">
            @foreach($platform->dossiers as $dossier)
                <div class="dossier-item">
                    <div class="dossier-info">
                        <div class="dossier-name">{{ $dossier->name }}</div>
                        <div class="dossier-meta">
                            <i class="fas fa-layer-group"></i> {{ $dossier->generation->display_name }} •
                            <i class="fas fa-book"></i> {{ $dossier->subject->name }} •
                            <i class="fas fa-calendar"></i> {{ $dossier->semester_text }} •
                            <i class="fas fa-chalkboard-teacher"></i> {{ $dossier->teacher->name }}
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: var(--space-md);">
                        <div class="dossier-price">{{ $dossier->formatted_final_price }}</div>
                        
                        <span class="status-badge {{ $dossier->is_active ? 'active' : 'inactive' }}">
                            {{ $dossier->status_text }}
                        </span>
                        
                        <a href="{{ route('admin.dossiers.show', $dossier) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-eye"></i>
                            {{ __('View') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- System Information -->
<div class="info-section fade-in">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-cog"></i>
            {{ __('System Information') }}
        </h3>
    </div>
    <div class="section-body">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">{{ __('Platform ID') }}</div>
                <div class="info-value">#{{ $platform->id }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Created At') }}</div>
                <div class="info-value">{{ $platform->created_at->format('M d, Y H:i:s') }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Last Updated') }}</div>
                <div class="info-value">{{ $platform->updated_at->format('M d, Y H:i:s') }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Last Update') }}</div>
                <div class="info-value">{{ $platform->updated_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div style="display: flex; gap: var(--space-lg); justify-content: center; margin-top: var(--space-2xl);" class="fade-in">
    <a href="{{ route('admin.platforms.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        {{ __('Back to Platforms') }}
    </a>
    
    <a href="{{ route('admin.platforms.edit', $platform) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i>
        {{ __('Edit Platform') }}
    </a>
    
    @if($platform->dossiers()->count() === 0)
        <form method="POST" action="{{ route('admin.platforms.destroy', $platform) }}" 
              style="display: inline;" 
              onsubmit="return confirm('{{ __('Are you sure you want to delete this platform? This action cannot be undone.') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i>
                {{ __('Delete Platform') }}
            </button>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Platform details page loaded');
});
</script>
@endpush