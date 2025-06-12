@extends('layouts.admin')

@section('title', __('Subject Details'))
@section('page-title', __('Subject Details'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.subjects.index') }}" class="breadcrumb-link">{{ __('Subjects') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ $subject->name }}
    </div>
@endsection

@push('styles')
<style>
    .subject-show-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .subject-header {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .subject-hero {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: var(--space-xl);
        padding: var(--space-2xl);
        align-items: center;
    }
    
    .subject-image-section {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .subject-image-container {
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
        box-shadow: var(--shadow-md);
        width: 150px;
        height: 150px;
    }
    
    .subject-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .no-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
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
    
    .subject-info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .subject-main-info {
        flex: 1;
    }
    
    .subject-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        line-height: 1.2;
    }
    
    .subject-title-ar {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-md);
        direction: rtl;
        font-style: italic;
    }
    
    .subject-hierarchy {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .hierarchy-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        padding: var(--space-sm) var(--space-md);
        background: var(--admin-secondary-100);
        border-radius: var(--radius-lg);
        text-decoration: none;
        color: var(--admin-secondary-700);
        font-weight: 500;
        font-size: 0.875rem;
        transition: all var(--transition-fast);
    }
    
    .hierarchy-item:hover {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        transform: translateY(-1px);
    }
    
    .hierarchy-separator {
        color: var(--admin-secondary-400);
        font-size: 1.2rem;
    }
    
    .subject-description {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-md);
    }
    
    .subject-description-ar {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-xl);
        direction: rtl;
        font-style: italic;
        border-top: 1px solid var(--admin-secondary-200);
        padding-top: var(--space-md);
    }
    
    .subject-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-xl);
    }
    
    .stat-item {
        text-align: center;
        padding: var(--space-lg);
        background: var(--admin-secondary-50);
        border-radius: var(--radius-lg);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: var(--space-xs);
        color: var(--admin-primary-600);
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }
    
    .status-indicators {
        display: flex;
        gap: var(--space-md);
        align-items: center;
        flex-wrap: wrap;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        gap: var(--space-sm);
    }
    
    .status-badge.active {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .status-badge.inactive {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .subject-actions {
        background: var(--admin-secondary-50);
        padding: var(--space-xl);
        border-top: 1px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-md);
    }
    
    .action-group {
        display: flex;
        gap: var(--space-md);
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--space-xl);
    }
    
    .main-content {
        display: flex;
        flex-direction: column;
        gap: var(--space-xl);
    }
    
    .sidebar-content {
        display: flex;
        flex-direction: column;
        gap: var(--space-xl);
    }
    
    .info-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .section-header {
        background: var(--admin-secondary-50);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: var(--space-lg);
        padding: var(--space-lg);
    }
    
    .card-item {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        transition: all var(--transition-fast);
        text-decoration: none;
        color: inherit;
    }
    
    .card-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--admin-primary-300);
    }
    
    .card-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-md);
    }
    
    .card-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .card-name {
        font-weight: 600;
        color: var(--admin-secondary-900);
        flex: 1;
    }
    
    .card-name-ar {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
        direction: rtl;
        font-style: italic;
        margin-top: var(--space-xs);
    }
    
    .card-description {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: var(--space-md);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: var(--space-md);
        padding-top: var(--space-md);
        border-top: 1px solid var(--admin-secondary-200);
    }
    
    .card-price {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .price-badge {
        background: var(--success-100);
        color: var(--success-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .card-status {
        font-size: 0.75rem;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .card-status.active {
        background: var(--success-100);
        color: var(--success-700);
    }
    
    .card-status.inactive {
        background: var(--error-100);
        color: var(--error-700);
    }
    
    .info-list {
        padding: var(--space-lg);
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-md) 0;
        border-bottom: 1px solid var(--admin-secondary-100);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 500;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
    }
    
    .info-value {
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
        text-align: right;
    }
    
    .quick-actions {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .quick-actions-list {
        padding: var(--space-lg);
    }
    
    .quick-action {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-md);
        text-decoration: none;
        color: var(--admin-secondary-700);
        border-radius: var(--radius-md);
        transition: all var(--transition-fast);
        margin-bottom: var(--space-xs);
    }
    
    .quick-action:hover {
        background: var(--admin-secondary-50);
        color: var(--admin-secondary-900);
        transform: translateX(4px);
    }
    
    .quick-action:last-child {
        margin-bottom: 0;
    }
    
    .action-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        flex-shrink: 0;
    }
    
    .action-icon.edit {
        background: var(--warning-100);
        color: var(--warning-600);
    }
    
    .action-icon.add {
        background: var(--admin-primary-100);
        color: var(--admin-primary-600);
    }
    
    .action-icon.delete {
        background: var(--error-100);
        color: var(--error-600);
    }
    
    .action-text {
        flex: 1;
    }
    
    .action-title {
        font-weight: 500;
        margin-bottom: 2px;
    }
    
    .action-subtitle {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .empty-cards {
        text-align: center;
        padding: var(--space-2xl);
        color: var(--admin-secondary-500);
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: var(--space-md);
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .subject-show-container {
            margin: 0;
        }
        
        .subject-hero {
            grid-template-columns: 1fr;
            text-align: center;
            gap: var(--space-lg);
        }
        
        .content-grid {
            grid-template-columns: 1fr;
        }
        
        .subject-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .action-group {
            justify-content: center;
        }
        
        .subject-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .cards-grid {
            grid-template-columns: 1fr;
        }
        
        .status-indicators {
            justify-content: center;
        }
        
        .subject-hierarchy {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="subject-show-container">
    <!-- Subject Header -->
    <div class="subject-header fade-in">
        <div class="subject-hero">
            <div class="subject-image-section">
                <div class="subject-image-container">
                    @if($subject->image)
                        <img src="{{ Storage::url($subject->image) }}" alt="{{ $subject->name }}" class="subject-image">
                    @else
                        <div class="no-image">
                            <i class="fas fa-book"></i>
                        </div>
                    @endif
                    
                    <div class="subject-status status-{{ $subject->is_active ? 'active' : 'inactive' }}">
                        {{ $subject->is_active ? __('Active') : __('Inactive') }}
                    </div>
                </div>
            </div>
            
            <div class="subject-info">
                <div class="subject-main-info">
                    <h1 class="subject-title">{{ $subject->name }}</h1>
                    
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
                            {{ $subject->grade->name }} ({{ __('Grade') }} {{ $subject->grade->grade_number }})
                        </a>
                    </div>
                    
                    @if($subject->description)
                        <div class="subject-description">{{ $subject->description }}</div>
                    @else
                        <div class="subject-description" style="color: var(--admin-secondary-400); font-style: italic;">
                            {{ __('No description available') }}
                        </div>
                    @endif
                    
                    @if($subject->description_ar)
                        <div class="subject-description-ar">{{ $subject->description_ar }}</div>
                    @endif
                    
                    <div class="subject-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ $subject->educationalCards->count() }}</div>
                            <div class="stat-label">{{ __('Educational Cards') }}</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value">{{ $subject->educationalCards->where('is_active', true)->count() }}</div>
                            <div class="stat-label">{{ __('Active Cards') }}</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value">{{ $subject->educationalCards->sum('stock') }}</div>
                            <div class="stat-label">{{ __('Total Stock') }}</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value">{{ $subject->created_at->format('M Y') }}</div>
                            <div class="stat-label">{{ __('Created') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="status-indicators">
                    <span class="status-badge {{ $subject->is_active ? 'active' : 'inactive' }}">
                        <i class="fas fa-{{ $subject->is_active ? 'check' : 'times' }}"></i>
                        {{ $subject->is_active ? __('Active Subject') : __('Inactive Subject') }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="subject-actions">
            <div>
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Subjects') }}
                </a>
            </div>
            
            <div class="action-group">
                <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit Subject') }}
                </a>
                
                <a href="{{ route('admin.educational-cards.create') }}?subject={{ $subject->id }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Add Educational Card') }}
                </a>
                
                <button class="btn btn-danger" onclick="deleteSubject()">
                    <i class="fas fa-trash"></i>
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>
    
    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Educational Cards -->
            <div class="info-card fade-in">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-id-card"></i>
                        {{ __('Educational Cards') }}
                    </h2>
                    <a href="{{ route('admin.educational-cards.create') }}?subject={{ $subject->id }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i>
                        {{ __('Add Card') }}
                    </a>
                </div>
                
                @if($subject->educationalCards->count() > 0)
                    <div class="cards-grid">
                        @foreach($subject->educationalCards->sortBy('title') as $card)
                            <a href="{{ route('admin.educational-cards.show', $card) }}" class="card-item">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div class="card-info">
                                        <div class="card-name">{{ $card->title }}</div>
                                        @if($card->title_ar)
                                            <div class="card-name-ar">{{ $card->title_ar }}</div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($card->description)
                                    <div class="card-description">{{ $card->description }}</div>
                                @endif
                                
                                <div class="card-meta">
                                    <div class="card-price">
                                        <i class="fas fa-dollar-sign"></i>
                                        <span class="price-badge">${{ number_format($card->price, 2) }}</span>
                                    </div>
                                    <div class="card-status {{ $card->is_active ? 'active' : 'inactive' }}">
                                        {{ $card->is_active ? __('Active') : __('Inactive') }}
                                    </div>
                                </div>
                                
                                <div class="card-meta">
                                    <div class="card-price">
                                        <i class="fas fa-boxes"></i>
                                        <span class="price-badge">{{ $card->stock }} {{ __('in stock') }}</span>
                                    </div>
                                    <div class="card-status">
                                        {{ ucfirst($card->card_type) }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="empty-cards">
                        <div class="empty-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <h3>{{ __('No Educational Cards Yet') }}</h3>
                        <p>{{ __('This subject doesn\'t have any educational cards yet.') }}</p>
                        <a href="{{ route('admin.educational-cards.create') }}?subject={{ $subject->id }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            {{ __('Add First Card') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="sidebar-content">
            <!-- Subject Information -->
            <div class="info-card fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Subject Information') }}
                    </h3>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">{{ __('Subject ID') }}</span>
                        <span class="info-value">#{{ $subject->id }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Name') }}</span>
                        <span class="info-value">{{ $subject->name }}</span>
                    </div>
                    
                    @if($subject->name_ar)
                        <div class="info-item">
                            <span class="info-label">{{ __('Arabic Name') }}</span>
                            <span class="info-value" dir="rtl">{{ $subject->name_ar }}</span>
                        </div>
                    @endif
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Platform') }}</span>
                        <span class="info-value">
                            <a href="{{ route('admin.platforms.show', $subject->grade->platform) }}" style="color: var(--admin-primary-600); text-decoration: none;">
                                {{ $subject->grade->platform->name }}
                            </a>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Grade') }}</span>
                        <span class="info-value">
                            <a href="{{ route('admin.grades.show', $subject->grade) }}" style="color: var(--admin-primary-600); text-decoration: none;">
                                {{ $subject->grade->name }} ({{ __('Grade') }} {{ $subject->grade->grade_number }})
                            </a>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Status') }}</span>
                        <span class="info-value">
                            <span class="badge {{ $subject->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $subject->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Total Cards') }}</span>
                        <span class="info-value">{{ $subject->educationalCards->count() }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Active Cards') }}</span>
                        <span class="info-value">{{ $subject->educationalCards->where('is_active', true)->count() }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Total Value') }}</span>
                        <span class="info-value">${{ number_format($subject->educationalCards->sum('price'), 2) }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Total Stock') }}</span>
                        <span class="info-value">{{ $subject->educationalCards->sum('stock') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Created') }}</span>
                        <span class="info-value">{{ $subject->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Last Updated') }}</span>
                        <span class="info-value">{{ $subject->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-bolt"></i>
                        {{ __('Quick Actions') }}
                    </h3>
                </div>
                
                <div class="quick-actions-list">
                    <a href="{{ route('admin.subjects.edit', $subject) }}" class="quick-action">
                        <div class="action-icon edit">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Edit Subject') }}</div>
                            <div class="action-subtitle">{{ __('Update subject information') }}</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.educational-cards.create') }}?subject={{ $subject->id }}" class="quick-action">
                        <div class="action-icon add">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Add Educational Card') }}</div>
                            <div class="action-subtitle">{{ __('Create a new educational card') }}</div>
                        </div>
                    </a>
                    
                    <a href="#" onclick="deleteSubject(); return false;" class="quick-action">
                        <div class="action-icon delete">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Delete Subject') }}</div>
                            <div class="action-subtitle">{{ __('Permanently remove subject') }}</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-xl); max-width: 400px; width: 90%; text-align: center;">
        <div style="color: var(--error-500); font-size: 3rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Subject') }}</h3>
        <p style="margin-bottom: var(--space-md); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete') }} <strong>"{{ $subject->name }}"</strong>?
        </p>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('This action cannot be undone. All associated educational cards will be permanently removed.') }}
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
    // Delete subject functions
    function deleteSubject() {
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    function confirmDelete() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.subjects.destroy", $subject) }}';
        
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
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
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
                    }, index * 100);
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