@extends('layouts.admin')

@section('title', __('Testimonials Management'))
@section('page-title', __('Testimonials Management'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Testimonials') }}
    </div>
@endsection

@push('styles')
<style>
    .testimonials-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .testimonials-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .testimonials-stats {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        background: white;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        min-width: 100px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3b82f6;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.25rem;
    }
    
    .testimonials-tabs {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .tabs-header {
        display: flex;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .tab-button {
        flex: 1;
        padding: 1rem 1.5rem;
        border: none;
        background: none;
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .tab-button.active {
        color: #3b82f6;
        background: white;
    }
    
    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #3b82f6;
    }
    
    .tab-count {
        background: #e5e7eb;
        color: #374151;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        min-width: 20px;
        text-align: center;
    }
    
    .tab-button.active .tab-count {
        background: #3b82f6;
        color: white;
    }
    
    .tab-content {
        display: none;
        padding: 2rem;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }
    
    .testimonial-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.2s;
        position: relative;
    }
    
    .testimonial-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .testimonial-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        border-bottom: 1px solid #e5e7eb;
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .customer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.125rem;
        flex-shrink: 0;
    }
    
    .customer-details {
        flex: 1;
    }
    
    .customer-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .customer-email {
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .testimonial-rating {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .star {
        color: #fbbf24;
        font-size: 1rem;
    }
    
    .star.empty {
        color: #d1d5db;
    }
    
    .rating-text {
        margin-left: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .testimonial-content {
        padding: 1.5rem;
    }
    
    .testimonial-text {
        color: #374151;
        line-height: 1.6;
        margin-bottom: 1rem;
        font-style: italic;
        position: relative;
    }
    
    .testimonial-text::before {
        content: '"';
        font-size: 3rem;
        color: #ddd6fe;
        position: absolute;
        top: -10px;
        left: -15px;
        font-family: serif;
    }
    
    .testimonial-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #f3f4f6;
        font-size: 0.75rem;
        color: #9ca3af;
    }
    
    .order-link {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    
    .order-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }
    
    .testimonial-actions {
        padding: 1rem 1.5rem;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
        padding: 0.5rem 1rem;
        border: 1px solid transparent;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
    }
    
    .btn-success {
        background: #10b981;
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
    }
    
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    
    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }
    
    .btn-primary {
        background: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }
    
    .status-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    
    .status-approved {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    
    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
        color: #9ca3af;
    }
    
    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #374151;
    }
    
    .empty-text {
        margin-bottom: 1rem;
        color: #6b7280;
    }
    
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .btn:disabled:hover {
        transform: none !important;
    }
    
    .custom-notification {
        display: flex;
        align-items: center;
        font-size: 14px;
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 300px;
        min-width: 250px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-radius: 8px;
        padding: 12px 16px;
        font-weight: 500;
    }
    
    .alert-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .alert-info {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    
    @keyframes fa-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .fa-spin {
        animation: fa-spin 1s infinite linear;
    }
    
    @media (max-width: 768px) {
        .testimonials-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .testimonials-stats {
            width: 100%;
            justify-content: space-between;
        }
        
        .testimonials-grid {
            grid-template-columns: 1fr;
        }
        
        .tabs-header {
            flex-direction: column;
        }
        
        .tab-button {
            justify-content: flex-start;
            padding: 1rem 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Testimonials Header -->
<div class="testimonials-header">
    <div>
        <h1 class="testimonials-title">
            <i class="fas fa-star"></i>
            {{ __('Testimonials Management') }}
        </h1>
    </div>
    
    <div class="testimonials-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $pendingTestimonials->count() }}</div>
            <div class="stat-label">{{ __('Pending') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $approvedTestimonials->count() }}</div>
            <div class="stat-label">{{ __('Approved') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $rejectedTestimonials->count() }}</div>
            <div class="stat-label">{{ __('Rejected') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $pendingTestimonials->count() + $approvedTestimonials->count() + $rejectedTestimonials->count() }}</div>
            <div class="stat-label">{{ __('Total') }}</div>
        </div>
    </div>
</div>

<!-- Testimonials Tabs -->
<div class="testimonials-tabs">
    <div class="tabs-header">
        <button class="tab-button active" data-tab="pending">
            <i class="fas fa-clock"></i>
            {{ __('Pending') }}
            <span class="tab-count">{{ $pendingTestimonials->count() }}</span>
        </button>
        
        <button class="tab-button" data-tab="approved">
            <i class="fas fa-check-circle"></i>
            {{ __('Approved') }}
            <span class="tab-count">{{ $approvedTestimonials->count() }}</span>
        </button>
        
        <button class="tab-button" data-tab="rejected">
            <i class="fas fa-times-circle"></i>
            {{ __('Rejected') }}
            <span class="tab-count">{{ $rejectedTestimonials->count() }}</span>
        </button>
    </div>
    
    <!-- Pending Testimonials Tab -->
    <div class="tab-content active" id="pending">
        @if($pendingTestimonials->count() > 0)
            <div class="testimonials-grid">
                @foreach($pendingTestimonials as $testimonial)
                    <div class="testimonial-card" data-testimonial-id="{{ $testimonial->id }}">
                        <div class="status-badge status-pending">{{ __('Pending') }}</div>
                        
                        <div class="testimonial-header">
                            <div class="customer-info">
                                <div class="customer-avatar">
                                    {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}
                                </div>
                                <div class="customer-details">
                                    <div class="customer-name">{{ $testimonial->user->name }}</div>
                                    <div class="customer-email">{{ $testimonial->user->email }}</div>
                                </div>
                            </div>
                            
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $testimonial->rating ? 'star' : 'star empty' }}"></i>
                                @endfor
                                <span class="rating-text">{{ $testimonial->rating }}/5</span>
                            </div>
                        </div>
                        
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                {{ $testimonial->comment }}
                            </div>
                            
                            <div class="testimonial-meta">
                                <span>{{ $testimonial->created_at->format('M d, Y') }}</span>
                                @if($testimonial->order)
                                    <a href="{{ route('admin.orders.show', $testimonial->order) }}" class="order-link">
                                        {{ __('Order') }} #{{ $testimonial->order->id }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="testimonial-actions">
                            <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                                {{ __('View') }}
                            </a>
                            
                            <button class="btn btn-success btn-sm approve-btn" data-testimonial-id="{{ $testimonial->id }}">
                                <i class="fas fa-check"></i>
                                {{ __('Approve') }}
                            </button>
                            
                            <button class="btn btn-danger btn-sm reject-btn" data-testimonial-id="{{ $testimonial->id }}">
                                <i class="fas fa-times"></i>
                                {{ __('Reject') }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="empty-title">{{ __('No Pending Testimonials') }}</h3>
                <p class="empty-text">{{ __('All testimonials have been reviewed.') }}</p>
            </div>
        @endif
    </div>
    
    <!-- Approved Testimonials Tab -->
    <div class="tab-content" id="approved">
        @if($approvedTestimonials->count() > 0)
            <div class="testimonials-grid">
                @foreach($approvedTestimonials as $testimonial)
                    <div class="testimonial-card" data-testimonial-id="{{ $testimonial->id }}">
                        <div class="status-badge status-approved">{{ __('Approved') }}</div>
                        
                        <div class="testimonial-header">
                            <div class="customer-info">
                                <div class="customer-avatar">
                                    {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}
                                </div>
                                <div class="customer-details">
                                    <div class="customer-name">{{ $testimonial->user->name }}</div>
                                    <div class="customer-email">{{ $testimonial->user->email }}</div>
                                </div>
                            </div>
                            
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $testimonial->rating ? 'star' : 'star empty' }}"></i>
                                @endfor
                                <span class="rating-text">{{ $testimonial->rating }}/5</span>
                            </div>
                        </div>
                        
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                {{ $testimonial->comment }}
                            </div>
                            
                            <div class="testimonial-meta">
                                <span>{{ $testimonial->created_at->format('M d, Y') }}</span>
                                @if($testimonial->order)
                                    <a href="{{ route('admin.orders.show', $testimonial->order) }}" class="order-link">
                                        {{ __('Order') }} #{{ $testimonial->order->id }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="testimonial-actions">
                            <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                                {{ __('View') }}
                            </a>
                            
                            <button class="btn btn-danger btn-sm reject-btn" data-testimonial-id="{{ $testimonial->id }}">
                                <i class="fas fa-times"></i>
                                {{ __('Reject') }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="empty-title">{{ __('No Approved Testimonials') }}</h3>
                <p class="empty-text">{{ __('No testimonials have been approved yet.') }}</p>
            </div>
        @endif
    </div>
    
    <!-- Rejected Testimonials Tab -->
    <div class="tab-content" id="rejected">
        @if($rejectedTestimonials->count() > 0)
            <div class="testimonials-grid">
                @foreach($rejectedTestimonials as $testimonial)
                    <div class="testimonial-card" data-testimonial-id="{{ $testimonial->id }}">
                        <div class="status-badge status-rejected">{{ __('Rejected') }}</div>
                        
                        <div class="testimonial-header">
                            <div class="customer-info">
                                <div class="customer-avatar">
                                    {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}
                                </div>
                                <div class="customer-details">
                                    <div class="customer-name">{{ $testimonial->user->name }}</div>
                                    <div class="customer-email">{{ $testimonial->user->email }}</div>
                                </div>
                            </div>
                            
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $testimonial->rating ? 'star' : 'star empty' }}"></i>
                                @endfor
                                <span class="rating-text">{{ $testimonial->rating }}/5</span>
                            </div>
                        </div>
                        
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                {{ $testimonial->comment }}
                            </div>
                            
                            <div class="testimonial-meta">
                                <span>{{ $testimonial->created_at->format('M d, Y') }}</span>
                                @if($testimonial->order)
                                    <a href="{{ route('admin.orders.show', $testimonial->order) }}" class="order-link">
                                        {{ __('Order') }} #{{ $testimonial->order->id }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="testimonial-actions">
                            <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                                {{ __('View') }}
                            </a>
                            
                            <button class="btn btn-success btn-sm approve-btn" data-testimonial-id="{{ $testimonial->id }}">
                                <i class="fas fa-check"></i>
                                {{ __('Approve') }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h3 class="empty-title">{{ __('No Rejected Testimonials') }}</h3>
                <p class="empty-text">{{ __('No testimonials have been rejected.') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    document.querySelectorAll('.tab-button').forEach(function(button) {
        button.addEventListener('click', function() {
            var targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and buttons
            document.querySelectorAll('.tab-button').forEach(function(btn) {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-content').forEach(function(content) {
                content.classList.remove('active');
            });
            
            // Add active class to clicked button and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });
    
    // Setup approve buttons
    document.querySelectorAll('.approve-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var testimonialId = this.getAttribute('data-testimonial-id');
            handleApprove(testimonialId, this);
        });
    });
    
    // Setup reject buttons
    document.querySelectorAll('.reject-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var testimonialId = this.getAttribute('data-testimonial-id');
            handleReject(testimonialId, this);
        });
    });
});

function handleApprove(testimonialId, button) {
    if (!confirm('Are you sure you want to approve this testimonial?')) {
        return;
    }
    
    // Show loading
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approving...';
    button.disabled = true;
    
    // Make AJAX request
    fetch('/admin/testimonials/' + testimonialId + '/approve', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            showNotification('Testimonial approved successfully!', 'success');
            // Reload page to update the UI
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error('Network response was not ok');
        }
    })
    .catch(error => {
        showNotification('Error approving testimonial', 'error');
        button.innerHTML = '<i class="fas fa-check"></i> Approve';
        button.disabled = false;
    });
}

function handleReject(testimonialId, button) {
    if (!confirm('Are you sure you want to reject this testimonial?')) {
        return;
    }
    
    // Show loading
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rejecting...';
    button.disabled = true;
    
    // Make AJAX request
    fetch('/admin/testimonials/' + testimonialId + '/reject', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            showNotification('Testimonial rejected successfully!', 'success');
            // Reload page to update the UI
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error('Network response was not ok');
        }
    })
    .catch(error => {
        showNotification('Error rejecting testimonial', 'error');
        button.innerHTML = '<i class="fas fa-times"></i> Reject';
        button.disabled = false;
    });
}

function showNotification(message, type) {
    // Remove existing notifications
    document.querySelectorAll('.custom-notification').forEach(function(notif) {
        notif.remove();
    });
    
    var notification = document.createElement('div');
    notification.className = 'custom-notification alert alert-' + type;
    
    var iconClass = type === 'success' ? 'check-circle' : 
                type === 'error' ? 'exclamation-circle' : 'info-circle';
    
    notification.innerHTML = '<i class="fas fa-' + iconClass + '" style="margin-right: 8px;"></i>' + message;
    
    document.body.appendChild(notification);
    
    setTimeout(function() {
        if (document.body.contains(notification)) {
            notification.style.opacity = '0';
            setTimeout(function() {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }
    }, 3000);
}
</script>
@endpush