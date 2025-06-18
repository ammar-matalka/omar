@extends('layouts.admin')

@section('title', __('Teacher Details'))
@section('page-title', $teacher->name)

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.teachers.index') }}" class="breadcrumb-link">{{ __('Teachers') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <span>{{ $teacher->name }}</span>
    </div>
@endsection

@push('styles')
<style>
    .teacher-header {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: var(--radius-lg);
        padding: var(--space-2xl);
        margin-bottom: var(--space-xl);
        position: relative;
        overflow: hidden;
    }
    
    .teacher-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 200px;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><circle cx="50" cy="50" r="30"/><circle cx="20" cy="20" r="10"/><circle cx="80" cy="80" r="15"/></svg>');
        background-size: cover;
    }
    
    .teacher-info {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: var(--space-xl);
        align-items: center;
    }
    
    .teacher-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 900;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }
    
    .teacher-details h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: var(--space-sm);
    }
    
    .teacher-specialization {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: var(--space-md);
    }
    
    .teacher-meta {
        display: flex;
        gap: var(--space-lg);
        font-size: 0.875rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .teacher-actions {
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
    }
</style>
@endpush

@section('content')
<!-- Teacher Header -->
<div class="teacher-header fade-in">
    <div class="teacher-info">
        <div class="teacher-actions">
            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-secondary">
                <i class="fas fa-edit"></i>
                {{ __('Edit Teacher') }}
            </a>
            
            <form method="POST" action="{{ route('admin.teachers.toggle-status', $teacher) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn {{ $teacher->is_active ? 'btn-warning' : 'btn-success' }}">
                    <i class="fas {{ $teacher->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                    {{ $teacher->is_active ? __('Deactivate') : __('Activate') }}
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
    
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-coins"></i>
        </div>
        <div class="stat-number">{{ number_format($stats['total_revenue'], 2) }} JD</div>
        <div class="stat-label">{{ __('Total Revenue') }}</div>
    </div>
</div>

<!-- Teacher Information -->
<div class="info-section fade-in">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-info-circle"></i>
            {{ __('Teacher Information') }}
        </h3>
    </div>
    <div class="section-body">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">{{ __('Full Name') }}</div>
                <div class="info-value">{{ $teacher->name }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Specialization') }}</div>
                <div class="info-value {{ $teacher->specialization ? '' : 'empty' }}">
                    {{ $teacher->specialization ?: __('Not specified') }}
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Phone Number') }}</div>
                <div class="info-value {{ $teacher->phone ? '' : 'empty' }}">
                    {{ $teacher->phone ?: __('Not provided') }}
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Email Address') }}</div>
                <div class="info-value {{ $teacher->email ? '' : 'empty' }}">
                    {{ $teacher->email ?: __('Not provided') }}
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Display Order') }}</div>
                <div class="info-value">{{ $teacher->order }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Status') }}</div>
                <div class="info-value">
                    <span class="status-badge {{ $teacher->is_active ? 'active' : 'inactive' }}">
                        <i class="fas {{ $teacher->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $teacher->status_text }}
                    </span>
                </div>
            </div>
        </div>
        
        @if($teacher->description)
            <div style="margin-top: var(--space-xl);">
                <div class="info-label">{{ __('Description') }}</div>
                <div class="info-value" style="margin-top: var(--space-sm); line-height: 1.6;">
                    {{ $teacher->description }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Teacher's Dossiers -->
@if($teacher->dossiers->count() > 0)
<div class="info-section fade-in">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-book"></i>
            {{ __('Dossiers') }} ({{ $teacher->dossiers->count() }})
        </h3>
    </div>
    <div class="section-body">
        <div class="dossiers-list">
            @foreach($teacher->dossiers as $dossier)
                <div class="dossier-item">
                    <div class="dossier-info">
                        <div class="dossier-name">{{ $dossier->name }}</div>
                        <div class="dossier-meta">
                            <i class="fas fa-layer-group"></i> {{ $dossier->generation->display_name }} •
                            <i class="fas fa-book"></i> {{ $dossier->subject->name }} •
                            <i class="fas fa-calendar"></i> {{ $dossier->semester_text }} •
                            <i class="fas fa-desktop"></i> {{ $dossier->platform->name }}
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
                <div class="info-label">{{ __('Teacher ID') }}</div>
                <div class="info-value">#{{ $teacher->id }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Created At') }}</div>
                <div class="info-value">{{ $teacher->created_at->format('M d, Y H:i:s') }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Last Updated') }}</div>
                <div class="info-value">{{ $teacher->updated_at->format('M d, Y H:i:s') }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Last Update') }}</div>
                <div class="info-value">{{ $teacher->updated_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div style="display: flex; gap: var(--space-lg); justify-content: center; margin-top: var(--space-2xl);" class="fade-in">
    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        {{ __('Back to Teachers') }}
    </a>
    
    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i>
        {{ __('Edit Teacher') }}
    </a>
    
    @if($teacher->dossiers()->count() === 0 && $teacher->orderItems()->count() === 0)
        <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" 
              style="display: inline;" 
              onsubmit="return confirm('{{ __('Are you sure you want to delete this teacher? This action cannot be undone.') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i>
                {{ __('Delete Teacher') }}
            </button>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh statistics every 30 seconds
setInterval(function() {
    // You can implement AJAX refresh here if needed
    console.log('Auto-refresh statistics');
}, 30000);

// Initialize tooltips or additional functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Teacher details page loaded');
});
</script>
@endpush="teacher-avatar">
            {{ strtoupper(substr($teacher->name, 0, 2)) }}
        </div>
        
        <div class="teacher-details">
            <h1>{{ $teacher->name }}</h1>
            @if($teacher->specialization)
                <div class="teacher-specialization">
                    <i class="fas fa-graduation-cap"></i>
                    {{ $teacher->specialization }}
                </div>
            @endif
            
            <div class="teacher-meta">
                <div class="meta-item">
                    <i class="fas fa-calendar-plus"></i>
                    {{ __('Joined') }}: {{ $teacher->created_at->format('M d, Y') }}
                </div>
                
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    {{ __('Updated') }}: {{ $teacher->updated_at->diffForHumans() }}
                </div>
                
                <div class="meta-item">
                    <span class="status-badge {{ $teacher->is_active ? 'active' : 'inactive' }}">
                        <i class="fas {{ $teacher->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $teacher->status_text }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class