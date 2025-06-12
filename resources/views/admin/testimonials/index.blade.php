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
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .testimonials-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .testimonials-stats {
        display: flex;
        gap: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: var(--space-md);
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--admin-secondary-200);
        min-width: 100px;
        box-shadow: var(--shadow-sm);
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-primary-600);
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-xs);
    }
    
    .testimonials-tabs {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--admin-secondary-200);
        overflow: hidden;
        margin-bottom: var(--space-xl);
    }
    
    .tabs-header {
        display: flex;
        background: var(--admin-secondary-50);
        border-bottom: 1px solid var(--admin-secondary-200);
    }
    
    .tab-button {
        flex: 1;
        padding: var(--space-lg) var(--space-xl);
        border: none;
        background: none;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--admin-secondary-600);
        cursor: pointer;
        transition: all var(--transition-fast);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
    }
    
    .tab-button.active {
        color: var(--admin-primary-600);
        background: white;
    }
    
    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--admin-primary-600);
    }
    
    .tab-count {
        background: var(--admin-secondary-200);
        color: var(--admin-secondary-700);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        min-width: 20px;
        text-align: center;
    }
    
    .tab-button.active .tab-count {
        background: var(--admin-primary-600);
        color: white;
    }
    
    .tab-content {
        display: none;
        padding: var(--space-xl);
    }
    
    .tab-content.active {
        display: block;
    }
    
    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: var(--space-xl);
    }
    
    .testimonial-card {
        background: white;
        border-radius: var(--radius-xl);
        border: 1px solid var(--admin-secondary-200);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        transition: all var(--transition-fast);
        position: relative;
    }
    
    .testimonial-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .testimonial-header {
        padding: var(--space-lg);
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        border-bottom: 1px solid var(--admin-secondary-200);
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-md);
    }
    
    .customer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
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
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .customer-email {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .testimonial-rating {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .star {
        color: #fbbf24;
        font-size: 1rem;
    }
    
    .star.empty {
        color: var(--admin-secondary-300);
    }
    
    .rating-text {
        margin-left: var(--space-sm);
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
    }
    
    .testimonial-content {
        padding: var(--space-lg);
    }
    
    .testimonial-text {
        color: var(--admin-secondary-700);
        line-height: 1.6;
        margin-bottom: var(--space-md);
        font-style: italic;
        position: relative;
    }
    
    .testimonial-text::before {
        content: '"';
        font-size: 3rem;
        color: var(--admin-primary-200);
        position: absolute;
        top: -10px;
        left: -15px;
        font-family: serif;
    }
    
    .testimonial-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: var(--space-md);
        border-top: 1px solid var(--admin-secondary-100);
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .order-link {
        color: var(--admin-primary-600);
        text-decoration: none;
        font-weight: 500;
        transition: color var(--transition-fast);
    }
    
    .order-link:hover {
        color: var(--admin-primary-700);
        text-decoration: underline;
    }
    
    .testimonial-actions {
        padding: var(--space-md) var(--space-lg);
        background: var(--admin-secondary-50);
        border-top: 1px solid var(--admin-secondary-200);
        display: flex;
        gap: var(--space-sm);
        justify-content: flex-end;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-xs);
        padding: var(--space-xs) var(--space-md);
        border: 1px solid transparent;
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
        white-space: nowrap;
    }
    
    .btn-sm {
        padding: var(--space-xs) var(--space-sm);
        font-size: 0.75rem;
    }
    
    .btn-success {
        background: var(--success-500);
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
    }
    
    .btn-danger {
        background: var(--error-500);
        color: white;
    }
    
    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }
    
    .btn-primary {
        background: var(--admin-primary-600);
        color: white;
    }
    
    .btn-primary:hover {
        background: var(--admin-primary-700);
        transform: translateY(-1px);
    }
    
    .btn-secondary {
        background: white;
        color: var(--admin-secondary-700);
        border-color: var(--admin-secondary-300);
    }
    
    .btn-secondary:hover {
        background: var(--admin-secondary-50);
        border-color: var(--admin-secondary-400);
    }
    
    .status-badge {
        position: absolute;
        top: var(--space-md);
        right: var(--space-md);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending {
        background: var(--warning-100);
        color: var(--warning-700);
        border: 1px solid var(--warning-200);
    }
    
    .status-approved {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .status-rejected {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
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
        color: var(--admin-secondary-400);
    }
    
    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: var(--space-sm);
        color: var(--admin-secondary-700);
    }
    
    .empty-text {
        margin-bottom: var(--space-lg);
        color: var(--admin-secondary-500);
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
            padding: var(--space-md) var(--space-lg);
        }
    }
    
    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease-out;
    }
    
    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    .testimonial-card.fade-in {
        transform: translateY(30px) scale(0.95);
    }
    
    .testimonial-card.fade-in.visible {
        transform: translateY(0) scale(1);
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
            <div class="stat-number">5</div>
            <div class="stat-label">{{ __('Pending') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">12</div>
            <div class="stat-label">{{ __('Approved') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">3</div>
            <div class="stat-label">{{ __('Rejected') }}</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">20</div>
            <div class="stat-label">{{ __('Total') }}</div>
        </div>
    </div>
</div>

<!-- Testimonials Tabs -->
<div class="testimonials-tabs fade-in">
    <div class="tabs-header">
        <button class="tab-button active" data-tab="pending">
            <i class="fas fa-clock"></i>
            {{ __('Pending') }}
            <span class="tab-count">5</span>
        </button>
        
        <button class="tab-button" data-tab="approved">
            <i class="fas fa-check-circle"></i>
            {{ __('Approved') }}
            <span class="tab-count">12</span>
        </button>
        
        <button class="tab-button" data-tab="rejected">
            <i class="fas fa-times-circle"></i>
            {{ __('Rejected') }}
            <span class="tab-count">3</span>
        </button>
    </div>
    
    <!-- Pending Testimonials Tab -->
    <div class="tab-content active" id="pending">
        <div class="testimonials-grid">
            <!-- Sample Testimonial Card -->
            <div class="testimonial-card fade-in">
                <div class="status-badge status-pending">{{ __('Pending') }}</div>
                
                <div class="testimonial-header">
                    <div class="customer-info">
                        <div class="customer-avatar">
                            J
                        </div>
                        <div class="customer-details">
                            <div class="customer-name">John Doe</div>
                            <div class="customer-email">john@example.com</div>
                        </div>
                        <input type="checkbox" class="testimonial-checkbox" value="1">
                    </div>
                    
                    <div class="testimonial-rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <span class="rating-text">5/5</span>
                    </div>
                </div>
                
                <div class="testimonial-content">
                    <div class="testimonial-text">
                        Amazing product! The quality exceeded my expectations and the customer service was outstanding. I would definitely recommend this to anyone looking for a great solution.
                    </div>
                    
                    <div class="testimonial-meta">
                        <span>Mar 15, 2024</span>
                        <a href="#" class="order-link">
                            {{ __('Order') }} #1234
                        </a>
                    </div>
                </div>
                
                <div class="testimonial-actions">
                    <a href="#" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i>
                        {{ __('View') }}
                    </a>
                    
                    <button class="btn btn-success btn-sm">
                        <i class="fas fa-check"></i>
                        {{ __('Approve') }}
                    </button>
                    
                    <button class="btn btn-danger btn-sm">
                        <i class="fas fa-times"></i>
                        {{ __('Reject') }}
                    </button>
                </div>
            </div>
            
            <!-- Add more sample cards here -->
        </div>
    </div>
    
    <!-- Approved Testimonials Tab -->
    <div class="tab-content" id="approved">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="empty-title">{{ __('No Approved Testimonials') }}</h3>
            <p class="empty-text">{{ __('No testimonials have been approved yet.') }}</p>
        </div>
    </div>
    
    <!-- Rejected Testimonials Tab -->
    <div class="tab-content" id="rejected">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h3 class="empty-title">{{ __('No Rejected Testimonials') }}</h3>
            <p class="empty-text">{{ __('No testimonials have been rejected.') }}</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
    
    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry, index) {
                if (entry.isIntersecting) {
                    setTimeout(function() {
                        entry.target.classList.add('visible');
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        var fadeElements = document.querySelectorAll('.fade-in');
        fadeElements.forEach(function(el) {
            observer.observe(el);
        });
    });
</script>
@endpush