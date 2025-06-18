@extends('layouts.admin')

@section('title', __('Dossier Details'))
@section('page-title', $dossier->name)

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.dossiers.index') }}" class="breadcrumb-link">{{ __('Dossiers') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <span>{{ $dossier->name }}</span>
    </div>
@endsection

@push('styles')
<style>
    .dossier-header {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: var(--radius-lg);
        padding: var(--space-2xl);
        margin-bottom: var(--space-xl);
        position: relative;
        overflow: hidden;
    }
    
    .dossier-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 250px;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><rect x="10" y="20" width="80" height="60" rx="5"/><line x1="20" y1="35" x2="80" y2="35" stroke="white" stroke-width="2"/><line x1="20" y1="45" x2="80" y2="45" stroke="white" stroke-width="2"/><line x1="20" y1="55" x2="60" y2="55" stroke="white" stroke-width="2"/></svg>');
        background-size: cover;
    }
    
    .dossier-info {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: var(--space-xl);
        align-items: center;
    }
    
    .dossier-icon {
        width: 120px;
        height: 120px;
        border-radius: var(--radius-xl);
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 900;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }
    
    .dossier-details h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: var(--space-sm);
        line-height: 1.2;
    }
    
    .dossier-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: var(--space-md);
    }
    
    .dossier-meta {
        display: flex;
        gap: var(--space-lg);
        font-size: 0.875rem;
        flex-wrap: wrap;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .dossier-actions {
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
    
    .stat-icon.info {
        background: linear-gradient(135deg, var(--info-500), var(--info-600));
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
    
    .price-breakdown {
        background: var(--admin-primary-50);
        border: 1px solid var(--admin-primary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-lg);
    }
    
    .price-title {
        font-weight: 600;
        margin-bottom: var(--space-md);
        color: var(--admin-primary-700);
        text-align: center;
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-sm) 0;
        border-bottom: 1px solid var(--admin-primary-200);
    }
    
    .price-row:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 1.125rem;
        color: var(--admin-primary-700);
        margin-top: var(--space-sm);
        padding-top: var(--space-md);
        border-top: 2px solid var(--admin-primary-300);
    }
    
    .price-label {
        color: var(--admin-secondary-700);
    }
    
    .price-value {
        font-weight: 500;
        color: var(--admin-secondary-900);
    }
    
    .semester-badge {
        background: var(--info-100);
        color: var(--info-800);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .educational-path {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-wrap: wrap;
        margin-bottom: var(--space-lg);
    }
    
    .path-item {
        background: var(--admin-secondary-100);
        color: var(--admin-secondary-700);
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .path-arrow {
        color: var(--admin-secondary-400);
        font-size: 0.875rem;
    }
    
    .orders-table {
        margin-top: var(--space-lg);
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }
    
    .table th,
    .table td {
        padding: var(--space-md);
        text-align: left;
        border-bottom: 1px solid var(--admin-secondary-200);
    }
    
    .table th {
        background: var(--admin-secondary-50);
        font-weight: 600;
        color: var(--admin-secondary-700);
    }
    
    .table tbody tr:hover {
        background: var(--admin-secondary-50);
    }
    
    .order-link {
        color: var(--admin-primary-600);
        text-decoration: none;
        font-weight: 500;
    }
    
    .order-link:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<!-- Dossier Header -->
<div class="dossier-header fade-in">
    <div class="dossier-info">
        <div class="dossier-icon">
            <i class="fas fa-book"></i>
        </div>
        
        <div class="dossier-details">
            <h1>{{ $dossier->name }}</h1>
            <div class="dossier-subtitle">
                {{ $dossier->generation->display_name }} • {{ $dossier->subject->name }}
            </div>
            
            <div class="dossier-meta">
                <div class="meta-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    {{ $dossier->teacher->name }}
                </div>
                
                <div class="meta-item">
                    <i class="fas fa-desktop"></i>
                    {{ $dossier->platform->name }}
                </div>
                
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    {{ $dossier->semester_text }}
                </div>
                
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    {{ __('Updated') }}: {{ $dossier->updated_at->diffForHumans() }}
                </div>
                
                <div class="meta-item">
                    <span class="status-badge {{ $dossier->is_active ? 'active' : 'inactive' }}">
                        <i class="fas {{ $dossier->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $dossier->status_text }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="dossier-actions">
            <a href="{{ route('admin.dossiers.edit', $dossier) }}" class="btn btn-secondary">
                <i class="fas fa-edit"></i>
                {{ __('Edit Dossier') }}
            </a>
            
            <form method="POST" action="{{ route('admin.dossiers.toggle-status', $dossier) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn {{ $dossier->is_active ? 'btn-warning' : 'btn-success' }}">
                    <i class="fas {{ $dossier->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                    {{ $dossier->is_active ? __('Deactivate') : __('Activate') }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="stats-grid fade-in">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-number">{{ $stats['total_orders'] }}</div>
        <div class="stat-label">{{ __('Total Orders') }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-boxes"></i>
        </div>
        <div class="stat-number">{{ $stats['total_quantity'] }}</div>
        <div class="stat-label">{{ __('Units Sold') }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-coins"></i>
        </div>
        <div class="stat-number">{{ number_format($stats['total_revenue'], 2) }}</div>
        <div class="stat-label">{{ __('Total Revenue') }} (JD)</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-number">{{ $stats['total_quantity'] > 0 ? number_format($stats['total_revenue'] / $stats['total_quantity'], 2) : '0.00' }}</div>
        <div class="stat-label">{{ __('Avg. Price per Unit') }} (JD)</div>
    </div>
</div>

<!-- Educational Path -->
<div class="info-section fade-in">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-route"></i>
            {{ __('Educational Path') }}
        </h3>
    </div>
    <div class="section-body">
        <div class="educational-path">
            <div class="path-item">{{ $dossier->generation->display_name }}</div>
            <div class="path-arrow"><i class="fas fa-chevron-right"></i></div>
            <div class="path-item">{{ $dossier->subject->name }}</div>
            <div class="path-arrow"><i class="fas fa-chevron-right"></i></div>
            <div class="path-item">{{ $dossier->teacher->name }}</div>
            <div class="path-arrow"><i class="fas fa-chevron-right"></i></div>
            <div class="path-item">{{ $dossier->platform->name }}</div>
            <div class="path-arrow"><i class="fas fa-chevron-right"></i></div>
            <span class="semester-badge">{{ $dossier->semester_text }}</span>
        </div>
    </div>
</div>

<!-- Dossier Information -->
<div class="info-section fade-in">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-info-circle"></i>
            {{ __('Dossier Information') }}
        </h3>
    </div>
    <div class="section-body">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">{{ __('Dossier Name') }}</div>
                <div class="info-value">{{ $dossier->name }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Generation') }}</div>
                <div class="info-value">{{ $dossier->generation->display_name }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Subject') }}</div>
                <div class="info-value">{{ $dossier->subject->name }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Teacher') }}</div>
                <div class="info-value">{{ $dossier->teacher->name }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Platform') }}</div>
                <div class="info-value">{{ $dossier->platform->name }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Semester') }}</div>
                <div class="info-value">{{ $dossier->semester_text }}</div>
            </div>
            
            @if($dossier->pages_count)
            <div class="info-item">
                <div class="info-label">{{ __('Pages Count') }}</div>
                <div class="info-value">{{ $dossier->pages_count }} {{ __('pages') }}</div>
            </div>
            @endif
            
            @if($dossier->file_size)
            <div class="info-item">
                <div class="info-label">{{ __('File Size') }}</div>
                <div class="info-value">{{ $dossier->file_size }}</div>
            </div>
            @endif
            
            <div class="info-item">
                <div class="info-label">{{ __('Display Order') }}</div>
                <div class="info-value">{{ $dossier->order }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Status') }}</div>
                <div class="info-value">
                    <span class="status-badge {{ $dossier->is_active ? 'active' : 'inactive' }}">
                        <i class="fas {{ $dossier->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $dossier->status_text }}
                    </span>
                </div>
            </div>
        </div>
        
        @if($dossier->description)
            <div style="margin-top: var(--space-xl);">
                <div class="info-label">{{ __('Description') }}</div>
                <div class="info-value" style="margin-top: var(--space-sm); line-height: 1.6;">
                    {{ $dossier->description }}
                </div>
            </div>
        @endif
        
        <!-- Price Breakdown -->
        <div class="price-breakdown">
            <div class="price-title">{{ __('Price Breakdown') }}</div>
            
            <div class="price-row">
                <span class="price-label">{{ __('Base Price') }}:</span>
                <span class="price-value">{{ $dossier->formatted_price }}</span>
            </div>
            
            <div class="price-row">
                <span class="price-label">{{ __('Platform Fee') }} ({{ $dossier->platform->formatted_price_percentage }}):</span>
                <span class="price-value">+{{ number_format($dossier->final_price - $dossier->price, 2) }} JD</span>
            </div>
            
            <div class="price-row">
                <span class="price-label">{{ __('Final Price') }}:</span>
                <span class="price-value">{{ $dossier->formatted_final_price }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
@if($dossier->orderItems()->count() > 0)
<div class="info-section fade-in">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-shopping-cart"></i>
            {{ __('Recent Orders') }} ({{ $dossier->orderItems()->count() }})
        </h3>
    </div>
    <div class="section-body">
        <div class="orders-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Order ID') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Student Name') }}</th>
                        <th>{{ __('Quantity') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dossier->orderItems()->with(['order.user'])->latest()->limit(10)->get() as $orderItem)
                        <tr>
                            <td>
                                <a href="{{ route('admin.educational-orders.show', $orderItem->order) }}" class="order-link">
                                    #{{ $orderItem->order->id }}
                                </a>
                            </td>
                            <td>{{ $orderItem->order->user->name ?? __('Guest') }}</td>
                            <td>{{ $orderItem->order->student_name }}</td>
                            <td>{{ $orderItem->quantity }}</td>
                            <td>{{ number_format($orderItem->price, 2) }} JD</td>
                            <td>{{ $orderItem->formatted_subtotal }}</td>
                            <td>
                                <span class="status-badge {{ $orderItem->order->status === 'completed' ? 'active' : 'inactive' }}">
                                    {{ $orderItem->order->status_text }}
                                </span>
                            </td>
                            <td>{{ $orderItem->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.educational-orders.show', $orderItem->order) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($dossier->orderItems()->count() > 10)
                <div style="text-align: center; margin-top: var(--space-lg);">
                    <a href="{{ route('admin.educational-orders.index', ['search' => $dossier->name]) }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i>
                        {{ __('View All Orders') }}
                    </a>
                </div>
            @endif
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
                <div class="info-label">{{ __('Dossier ID') }}</div>
                <div class="info-value">#{{ $dossier->id }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Created At') }}</div>
                <div class="info-value">{{ $dossier->created_at->format('M d, Y H:i:s') }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Last Updated') }}</div>
                <div class="info-value">{{ $dossier->updated_at->format('M d, Y H:i:s') }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">{{ __('Last Update') }}</div>
                <div class="info-value">{{ $dossier->updated_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div style="display: flex; gap: var(--space-lg); justify-content: center; margin-top: var(--space-2xl);" class="fade-in">
    <a href="{{ route('admin.dossiers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        {{ __('Back to Dossiers') }}
    </a>
    
    <a href="{{ route('admin.dossiers.edit', $dossier) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i>
        {{ __('Edit Dossier') }}
    </a>
    
    @if($dossier->orderItems()->count() === 0)
        <form method="POST" action="{{ route('admin.dossiers.destroy', $dossier) }}" 
              style="display: inline;" 
              onsubmit="return confirm('{{ __('Are you sure you want to delete this dossier? This action cannot be undone.') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i>
                {{ __('Delete Dossier') }}
            </button>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dossier details page loaded');
    
    // Auto-refresh statistics every 60 seconds
    setInterval(function() {
        // You can implement AJAX refresh here if needed
        console.log('Auto-refresh statistics');
    }, 60000);
});
</script>
@endpush