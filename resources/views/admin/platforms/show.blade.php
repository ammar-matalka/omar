@extends('layouts.admin')

@section('title', __('Platform Details'))
@section('page-title', __('Platform Details'))

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
        {{ $platform->name }}
    </div>
@endsection

@push('styles')
<style>
    .platform-show-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .platform-header {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .platform-hero {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: var(--space-xl);
        padding: var(--space-2xl);
    }
    
    .platform-image-section {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .platform-image-container {
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
        box-shadow: var(--shadow-md);
        width: 100%;
        height: 200px;
    }
    
    .platform-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .no-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }
    
    .platform-status {
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
    
    .platform-info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .platform-main-info {
        flex: 1;
    }
    
    .platform-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        line-height: 1.2;
    }
    
    .platform-title-ar {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-md);
        direction: rtl;
        font-style: italic;
    }
    
    .platform-description {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-md);
    }
    
    .platform-description-ar {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-xl);
        direction: rtl;
        font-style: italic;
        border-top: 1px solid var(--admin-secondary-200);
        padding-top: var(--space-md);
    }
    
    .platform-stats {
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
    
    .platform-actions {
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
    
    .grades-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: var(--space-lg);
        padding: var(--space-lg);
    }
    
    .grade-card {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        transition: all var(--transition-fast);
        text-decoration: none;
        color: inherit;
    }
    
    .grade-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--admin-primary-300);
    }
    
    .grade-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-md);
    }
    
    .grade-number {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
    }
    
    .grade-name {
        font-weight: 600;
        color: var(--admin-secondary-900);
        flex: 1;
    }
    
    .grade-name-ar {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
        direction: rtl;
        font-style: italic;
        margin-top: var(--space-xs);
    }
    
    .grade-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: var(--space-md);
        padding-top: var(--space-md);
        border-top: 1px solid var(--admin-secondary-200);
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
    
    .grade-status {
        font-size: 0.75rem;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .grade-status.active {
        background: var(--success-100);
        color: var(--success-700);
    }
    
    .grade-status.inactive {
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
    
    .empty-grades {
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
        .platform-show-container {
            margin: 0;
        }
        
        .platform-hero {
            grid-template-columns: 1fr;
            text-align: center;
        }
        
        .content-grid {
            grid-template-columns: 1fr;
        }
        
        .platform-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .action-group {
            justify-content: center;
        }
        
        .platform-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .grades-grid {
            grid-template-columns: 1fr;
        }
        
        .status-indicators {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="platform-show-container">
    <!-- Platform Header -->
    <div class="platform-header fade-in">
        <div class="platform-hero">
            <div class="platform-image-section">
                <div class="platform-image-container">
                    @if($platform->image)
                        <img src="{{ Storage::url($platform->image) }}" alt="{{ $platform->name }}" class="platform-image">
                    @else
                        <div class="no-image">
                            <i class="fas fa-desktop"></i>
                        </div>
                    @endif
                    
                    <div class="platform-status status-{{ $platform->is_active ? 'active' : 'inactive' }}">
                        {{ $platform->is_active ? __('Active') : __('Inactive') }}
                    </div>
                </div>
            </div>
            
            <div class="platform-info">
                <div class="platform-main-info">
                    <h1 class="platform-title">{{ $platform->name }}</h1>
                    
                    @if($platform->name_ar)
                        <div class="platform-title-ar">{{ $platform->name_ar }}</div>
                    @endif
                    
                    @if($platform->description)
                        <div class="platform-description">{{ $platform->description }}</div>
                    @else
                        <div class="platform-description" style="color: var(--admin-secondary-400); font-style: italic;">
                            {{ __('No description available') }}
                        </div>
                    @endif
                    
                    @if($platform->description_ar)
                        <div class="platform-description-ar">{{ $platform->description_ar }}</div>
                    @endif
                    
                    <div class="platform-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ $platform->grades->count() }}</div>
                            <div class="stat-label">{{ __('Grades') }}</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value">{{ $platform->grades->where('is_active', true)->count() }}</div>
                            <div class="stat-label">{{ __('Active Grades') }}</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value">{{ $platform->grades->sum(function($grade) { return $grade->subjects->count(); }) }}</div>
                            <div class="stat-label">{{ __('Total Subjects') }}</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value">{{ $platform->created_at->format('M Y') }}</div>
                            <div class="stat-label">{{ __('Created') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="status-indicators">
                    <span class="status-badge {{ $platform->is_active ? 'active' : 'inactive' }}">
                        <i class="fas fa-{{ $platform->is_active ? 'check' : 'times' }}"></i>
                        {{ $platform->is_active ? __('Active Platform') : __('Inactive Platform') }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="platform-actions">
            <div>
                <a href="{{ route('admin.platforms.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Platforms') }}
                </a>
            </div>
            
            <div class="action-group">
                <a href="{{ route('admin.platforms.edit', $platform) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit Platform') }}
                </a>
                
                <a href="{{ route('admin.grades.create') }}?platform={{ $platform->id }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Add Grade') }}
                </a>
                
                <button class="btn btn-danger" onclick="deletePlatform()">
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
            <!-- Platform Grades -->
            <div class="info-card fade-in">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-graduation-cap"></i>
                        {{ __('Platform Grades') }}
                    </h2>
                    <a href="{{ route('admin.grades.create') }}?platform={{ $platform->id }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i>
                        {{ __('Add Grade') }}
                    </a>
                </div>
                
                @if($platform->grades->count() > 0)
                    <div class="grades-grid">
                        @foreach($platform->grades->sortBy('grade_number') as $grade)
                            <a href="{{ route('admin.grades.show', $grade) }}" class="grade-card">
                                <div class="grade-header">
                                    <div class="grade-number">{{ $grade->grade_number }}</div>
                                    <div class="grade-info">
                                        <div class="grade-name">{{ $grade->name }}</div>
                                        @if($grade->name_ar)
                                            <div class="grade-name-ar">{{ $grade->name_ar }}</div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($grade->description)
                                    <div class="grade-description">{{ Str::limit($grade->description, 100) }}</div>
                                @endif
                                
                                <div class="grade-meta">
                                    <div class="subjects-count">
                                        <i class="fas fa-book"></i>
                                        <span class="count-badge">{{ $grade->subjects->count() }}</span>
                                        {{ __('Subjects') }}
                                    </div>
                                    <div class="grade-status {{ $grade->is_active ? 'active' : 'inactive' }}">
                                        {{ $grade->is_active ? __('Active') : __('Inactive') }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="empty-grades">
                        <div class="empty-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>{{ __('No Grades Yet') }}</h3>
                        <p>{{ __('This platform doesn\'t have any grades yet.') }}</p>
                        <a href="{{ route('admin.grades.create') }}?platform={{ $platform->id }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            {{ __('Add First Grade') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="sidebar-content">
            <!-- Platform Information -->
            <div class="info-card fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Platform Information') }}
                    </h3>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">{{ __('Platform ID') }}</span>
                        <span class="info-value">#{{ $platform->id }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Name') }}</span>
                        <span class="info-value">{{ $platform->name }}</span>
                    </div>
                    
                    @if($platform->name_ar)
                        <div class="info-item">
                            <span class="info-label">{{ __('Arabic Name') }}</span>
                            <span class="info-value" dir="rtl">{{ $platform->name_ar }}</span>
                        </div>
                    @endif
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Status') }}</span>
                        <span class="info-value">
                            <span class="badge {{ $platform->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $platform->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Total Grades') }}</span>
                        <span class="info-value">{{ $platform->grades->count() }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Active Grades') }}</span>
                        <span class="info-value">{{ $platform->grades->where('is_active', true)->count() }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Created') }}</span>
                        <span class="info-value">{{ $platform->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Last Updated') }}</span>
                        <span class="info-value">{{ $platform->updated_at->format('M d, Y') }}</span>
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
                    <a href="{{ route('admin.platforms.edit', $platform) }}" class="quick-action">
                        <div class="action-icon edit">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Edit Platform') }}</div>
                            <div class="action-subtitle">{{ __('Update platform information') }}</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.grades.create') }}?platform={{ $platform->id }}" class="quick-action">
                        <div class="action-icon add">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Add Grade') }}</div>
                            <div class="action-subtitle">{{ __('Create a new grade level') }}</div>
                        </div>
                    </a>
                    
                    <a href="#" onclick="deletePlatform(); return false;" class="quick-action">
                        <div class="action-icon delete">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Delete Platform') }}</div>
                            <div class="action-subtitle">{{ __('Permanently remove platform') }}</div>
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
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Platform') }}</h3>
        <p style="margin-bottom: var(--space-md); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete') }} <strong>"{{ $platform->name }}"</strong>?
        </p>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('This action cannot be undone. All associated grades and subjects will be permanently removed.') }}
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
    // Delete platform functions
    function deletePlatform() {
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    function confirmDelete() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.platforms.destroy", $platform) }}';
        
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