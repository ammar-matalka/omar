@extends('layouts.admin')

@section('title', __('Grade Details'))
@section('page-title', __('Grade Details'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.grades.index') }}" class="breadcrumb-link">{{ __('Grades') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ $grade->name }}
    </div>
@endsection

@push('styles')
<style>
    .grade-show-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .grade-header {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .grade-hero {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: var(--space-xl);
        padding: var(--space-2xl);
        align-items: center;
    }
    
    .grade-number-display {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 3rem;
        box-shadow: var(--shadow-lg);
        position: relative;
    }
    
    .grade-number-display::before {
        content: '';
        position: absolute;
        inset: -4px;
        background: linear-gradient(135deg, var(--admin-primary-400), var(--admin-primary-700));
        border-radius: 50%;
        z-index: -1;
        opacity: 0.3;
    }
    
    .grade-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .grade-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        line-height: 1.2;
    }
    
    .grade-title-ar {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-md);
        direction: rtl;
        font-style: italic;
    }
    
    .grade-platform-link {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-md);
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        margin-bottom: var(--space-lg);
        width: fit-content;
    }
    
    .grade-platform-link:hover {
        background: var(--admin-primary-200);
        transform: translateY(-1px);
    }
    
    .grade-description {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-md);
    }
    
    .grade-description-ar {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: var(--space-lg);
        direction: rtl;
        font-style: italic;
        border-top: 1px solid var(--admin-secondary-200);
        padding-top: var(--space-md);
    }
    
    .grade-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
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
    
    .grade-actions {
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
    
    .subjects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: var(--space-lg);
        padding: var(--space-lg);
    }
    
    .subject-card {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        transition: all var(--transition-fast);
        text-decoration: none;
        color: inherit;
    }
    
    .subject-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--admin-primary-300);
    }
    
    .subject-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-md);
    }
    
    .subject-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
        color: white;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .subject-name {
        font-weight: 600;
        color: var(--admin-secondary-900);
        flex: 1;
    }
    
    .subject-name-ar {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
        direction: rtl;
        font-style: italic;
        margin-top: var(--space-xs);
    }
    
    .subject-description {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: var(--space-md);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .subject-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: var(--space-md);
        padding-top: var(--space-md);
        border-top: 1px solid var(--admin-secondary-200);
    }
    
    .cards-count {
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
    
    .subject-status {
        font-size: 0.75rem;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .subject-status.active {
        background: var(--success-100);
        color: var(--success-700);
    }
    
    .subject-status.inactive {
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
    
    .empty-subjects {
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
        .grade-show-container {
            margin: 0;
        }
        
        .grade-hero {
            grid-template-columns: 1fr;
            text-align: center;
            gap: var(--space-lg);
        }
        
        .grade-number-display {
            width: 80px;
            height: 80px;
            font-size: 2rem;
            margin: 0 auto;
        }
        
        .content-grid {
            grid-template-columns: 1fr;
        }
        
        .grade-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .action-group {
            justify-content: center;
        }
        
        .grade-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .subjects-grid {
            grid-template-columns: 1fr;
        }
        
        .status-indicators {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="grade-show-container">
    <!-- Grade Header -->
    <div class="grade-header fade-in">
        <div class="grade-hero">
            <div class="grade-number-display">
                {{ $grade->grade_number }}
            </div>
            
            <div class="grade-info">
                <h1 class="grade-title">{{ $grade->name }}</h1>
                
                @if($grade->name_ar)
                    <div class="grade-title-ar">{{ $grade->name_ar }}</div>
                @endif
                
                <a href="{{ route('admin.platforms.show', $grade->platform) }}" class="grade-platform-link">
                    <i class="fas fa-desktop"></i>
                    {{ $grade->platform->name }}
                </a>
                
                @if($grade->description)
                    <div class="grade-description">{{ $grade->description }}</div>
                @else
                    <div class="grade-description" style="color: var(--admin-secondary-400); font-style: italic;">
                        {{ __('No description available') }}
                    </div>
                @endif
                
                @if($grade->description_ar)
                    <div class="grade-description-ar">{{ $grade->description_ar }}</div>
                @endif
                
                <div class="grade-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $grade->subjects->count() }}</div>
                        <div class="stat-label">{{ __('Subjects') }}</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-value">{{ $grade->subjects->where('is_active', true)->count() }}</div>
                        <div class="stat-label">{{ __('Active Subjects') }}</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-value">{{ $grade->subjects->sum(function($subject) { return $subject->educationalCards->count(); }) }}</div>
                        <div class="stat-label">{{ __('Total Cards') }}</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-value">{{ $grade->created_at->format('M Y') }}</div>
                        <div class="stat-label">{{ __('Created') }}</div>
                    </div>
                </div>
                
                <div class="status-indicators">
                    <span class="status-badge {{ $grade->is_active ? 'active' : 'inactive' }}">
                        <i class="fas fa-{{ $grade->is_active ? 'check' : 'times' }}"></i>
                        {{ $grade->is_active ? __('Active Grade') : __('Inactive Grade') }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="grade-actions">
            <div>
                <a href="{{ route('admin.grades.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Grades') }}
                </a>
            </div>
            
            <div class="action-group">
                <a href="{{ route('admin.grades.edit', $grade) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit Grade') }}
                </a>
                
                <a href="{{ route('admin.subjects.create') }}?grade={{ $grade->id }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Add Subject') }}
                </a>
                
                <button class="btn btn-danger" onclick="deleteGrade()">
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
            <!-- Grade Subjects -->
            <div class="info-card fade-in">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-book"></i>
                        {{ __('Grade Subjects') }}
                    </h2>
                    <a href="{{ route('admin.subjects.create') }}?grade={{ $grade->id }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i>
                        {{ __('Add Subject') }}
                    </a>
                </div>
                
                @if($grade->subjects->count() > 0)
                    <div class="subjects-grid">
                        @foreach($grade->subjects->sortBy('name') as $subject)
                            <a href="{{ route('admin.subjects.show', $subject) }}" class="subject-card">
                                <div class="subject-header">
                                    <div class="subject-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div class="subject-info">
                                        <div class="subject-name">{{ $subject->name }}</div>
                                        @if($subject->name_ar)
                                            <div class="subject-name-ar">{{ $subject->name_ar }}</div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($subject->description)
                                    <div class="subject-description">{{ $subject->description }}</div>
                                @endif
                                
                                <div class="subject-meta">
                                    <div class="cards-count">
                                        <i class="fas fa-id-card"></i>
                                        <span class="count-badge">{{ $subject->educationalCards->count() }}</span>
                                        {{ __('Cards') }}
                                    </div>
                                    <div class="subject-status {{ $subject->is_active ? 'active' : 'inactive' }}">
                                        {{ $subject->is_active ? __('Active') : __('Inactive') }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="empty-subjects">
                        <div class="empty-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3>{{ __('No Subjects Yet') }}</h3>
                        <p>{{ __('This grade doesn\'t have any subjects yet.') }}</p>
                        <a href="{{ route('admin.subjects.create') }}?grade={{ $grade->id }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            {{ __('Add First Subject') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="sidebar-content">
            <!-- Grade Information -->
            <div class="info-card fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Grade Information') }}
                    </h3>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">{{ __('Grade ID') }}</span>
                        <span class="info-value">#{{ $grade->id }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Grade Number') }}</span>
                        <span class="info-value">{{ $grade->grade_number }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Name') }}</span>
                        <span class="info-value">{{ $grade->name }}</span>
                    </div>
                    
                    @if($grade->name_ar)
                        <div class="info-item">
                            <span class="info-label">{{ __('Arabic Name') }}</span>
                            <span class="info-value" dir="rtl">{{ $grade->name_ar }}</span>
                        </div>
                    @endif
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Platform') }}</span>
                        <span class="info-value">
                            <a href="{{ route('admin.platforms.show', $grade->platform) }}" style="color: var(--admin-primary-600); text-decoration: none;">
                                {{ $grade->platform->name }}
                            </a>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Status') }}</span>
                        <span class="info-value">
                            <span class="badge {{ $grade->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $grade->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Total Subjects') }}</span>
                        <span class="info-value">{{ $grade->subjects->count() }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Active Subjects') }}</span>
                        <span class="info-value">{{ $grade->subjects->where('is_active', true)->count() }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Created') }}</span>
                        <span class="info-value">{{ $grade->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">{{ __('Last Updated') }}</span>
                        <span class="info-value">{{ $grade->updated_at->format('M d, Y') }}</span>
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
                    <a href="{{ route('admin.grades.edit', $grade) }}" class="quick-action">
                        <div class="action-icon edit">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Edit Grade') }}</div>
                            <div class="action-subtitle">{{ __('Update grade information') }}</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.subjects.create') }}?grade={{ $grade->id }}" class="quick-action">
                        <div class="action-icon add">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Add Subject') }}</div>
                            <div class="action-subtitle">{{ __('Create a new subject') }}</div>
                        </div>
                    </a>
                    
                    <a href="#" onclick="deleteGrade(); return false;" class="quick-action">
                        <div class="action-icon delete">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">{{ __('Delete Grade') }}</div>
                            <div class="action-subtitle">{{ __('Permanently remove grade') }}</div>
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
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Grade') }}</h3>
        <p style="margin-bottom: var(--space-md); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete') }} <strong>"{{ $grade->name }}"</strong>?
        </p>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('This action cannot be undone. All associated subjects will be permanently removed.') }}
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
    // Delete grade functions
    function deleteGrade() {
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    function confirmDelete() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.grades.destroy", $grade) }}';
        
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