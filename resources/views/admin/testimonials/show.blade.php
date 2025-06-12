@extends('layouts.admin')

@section('title', __('Testimonial Details'))
@section('page-title', __('Testimonial Details'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.testimonials.index') }}" class="breadcrumb-link">{{ __('Testimonials') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ __('Testimonial Details') }}
    </div>
@endsection

@push('styles')
<style>
    .testimonial-header {
        background: white;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .testimonial-title {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--space-lg);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .testimonial-info {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .testimonial-id {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .testimonial-date {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .testimonial-actions {
        display: flex;
        gap: var(--space-sm);
        align-items: flex-start;
        flex-wrap: wrap;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
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
    
    .testimonial-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .testimonial-content-section {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--admin-secondary-200);
        overflow: hidden;
    }
    
    .section-header {
        background: var(--admin-secondary-50);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-200);
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin: 0;
    }
    
    .content-body {
        padding: var(--space-xl);
    }
    
    .testimonial-text {
        font-size: 1.125rem;
        line-height: 1.8;
        color: var(--admin-secondary-700);
        font-style: italic;
        position: relative;
        margin: var(--space-xl) 0;
        padding: var(--space-xl);
        background: var(--admin-secondary-50);
        border-radius: var(--radius-lg);
        border-left: 4px solid var(--admin-primary-500);
    }
    
    .testimonial-text::before {
        content: '"';
        font-size: 4rem;
        color: var(--admin-primary-300);
        position: absolute;
        top: -10px;
        left: var(--space-md);
        font-family: serif;
        line-height: 1;
    }
    
    .testimonial-text::after {
        content: '"';
        font-size: 4rem;
        color: var(--admin-primary-300);
        position: absolute;
        bottom: -30px;
        right: var(--space-md);
        font-family: serif;
        line-height: 1;
    }
    
    .rating-section {
        background: var(--admin-secondary-50);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin: var(--space-lg) 0;
        text-align: center;
    }
    
    .rating-stars {
        display: flex;
        justify-content: center;
        gap: var(--space-xs);
        margin-bottom: var(--space-md);
    }
    
    .star {
        font-size: 2rem;
        color: #fbbf24;
        transition: all var(--transition-fast);
    }
    
    .star.empty {
        color: var(--admin-secondary-300);
    }
    
    .star:hover {
        transform: scale(1.1);
    }
    
    .rating-text {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
    }
    
    .rating-subtitle {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
        margin-top: var(--space-xs);
    }
    
    .testimonial-sidebar {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--admin-secondary-200);
        height: fit-content;
    }
    
    .sidebar-content {
        padding: var(--space-lg);
    }
    
    .customer-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: var(--space-xl);
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        border-radius: var(--radius-lg);
        margin-bottom: var(--space-lg);
    }
    
    .customer-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 2rem;
        margin-bottom: var(--space-md);
        box-shadow: var(--shadow-md);
    }
    
    .customer-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .customer-email {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        margin-bottom: var(--space-sm);
    }
    
    .customer-role {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
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
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .info-value {
        font-weight: 600;
        color: var(--admin-secondary-900);
        text-align: right;
    }
    
    .order-link {
        color: var(--admin-primary-600);
        text-decoration: none;
        font-weight: 600;
        transition: color var(--transition-fast);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .order-link:hover {
        color: var(--admin-primary-700);
        text-decoration: underline;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
        margin-top: var(--space-lg);
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        border: 1px solid transparent;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
        white-space: nowrap;
        text-align: center;
    }
    
    .btn-primary {
        background: var(--admin-primary-600);
        color: white;
        box-shadow: var(--shadow-sm);
    }
    
    .btn-primary:hover {
        background: var(--admin-primary-700);
        box-shadow: var(--shadow-md);
        transform: translateY(-1px);
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
    
    .btn-secondary {
        background: white;
        color: var(--admin-secondary-700);
        border-color: var(--admin-secondary-300);
    }
    
    .btn-secondary:hover {
        background: var(--admin-secondary-50);
        border-color: var(--admin-secondary-400);
    }
    
    .timeline-section {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--admin-secondary-200);
        overflow: hidden;
        margin-top: var(--space-xl);
    }
    
    .timeline-item {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-100);
        position: relative;
    }
    
    .timeline-item:last-child {
        border-bottom: none;
    }
    
    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    
    .timeline-content {
        flex: 1;
    }
    
    .timeline-title {
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .timeline-time {
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .icon-submitted {
        background: var(--info-100);
        color: var(--info-600);
    }
    
    .icon-approved {
        background: var(--success-100);
        color: var(--success-600);
    }
    
    .icon-rejected {
        background: var(--error-100);
        color: var(--error-600);
    }
    
    .related-products {
        background: var(--admin-secondary-50);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-lg);
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-md);
        margin-top: var(--space-md);
    }
    
    .product-card {
        background: white;
        border-radius: var(--radius-md);
        padding: var(--space-md);
        border: 1px solid var(--admin-secondary-200);
        transition: all var(--transition-fast);
    }
    
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .product-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: var(--radius-md);
        margin-bottom: var(--space-sm);
    }
    
    .product-placeholder {
        width: 100%;
        height: 120px;
        background: var(--admin-secondary-100);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-secondary-500);
        margin-bottom: var(--space-sm);
    }
    
    .product-name {
        font-weight: 600;
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
        margin-bottom: var(--space-xs);
    }
    
    .product-price {
        color: var(--admin-primary-600);
        font-weight: 700;
        font-size: 1rem;
    }
    
    @media (max-width: 1024px) {
        .testimonial-grid {
            grid-template-columns: 1fr;
        }
        
        .testimonial-title {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .testimonial-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }
    
    @media (max-width: 768px) {
        .customer-card {
            padding: var(--space-lg);
        }
        
        .customer-avatar {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .testimonial-text {
            font-size: 1rem;
            padding: var(--space-lg);
        }
        
        .testimonial-text::before,
        .testimonial-text::after {
            font-size: 3rem;
        }
        
        .star {
            font-size: 1.5rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .products-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .alert {
        padding: var(--space-md);
        border-radius: var(--radius-md);
        border: 1px solid;
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .alert-success {
        background: var(--success-100);
        color: var(--success-700);
        border-color: var(--success-200);
    }
    
    .alert-error {
        background: var(--error-100);
        color: var(--error-700);
        border-color: var(--error-200);
    }
    
    .alert-warning {
        background: var(--warning-100);
        color: var(--warning-700);
        border-color: var(--warning-200);
    }
    
    .alert-info {
        background: var(--info-100);
        color: var(--info-700);
        border-color: var(--info-200);
    }
    
    /* Animation classes */
    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease-out;
    }
    
    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Print styles */
    @media print {
        .testimonial-actions,
        .action-buttons,
        .btn {
            display: none !important;
        }
        
        .testimonial-grid {
            grid-template-columns: 1fr !important;
        }
        
        .testimonial-header,
        .testimonial-content-section,
        .testimonial-sidebar,
        .timeline-section {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        body {
            font-size: 12px !important;
        }
        
        .testimonial-id {
            font-size: 24px !important;
        }
        
        .section-title {
            font-size: 18px !important;
        }
    }
    
    /* Loading state */
    .loading {
        opacity: 0.6;
        pointer-events: none;
        position: relative;
    }
    
    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid var(--admin-primary-600);
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Accessibility improvements */
    .btn:focus,
    .order-link:focus {
        outline: 2px solid var(--admin-primary-500);
        outline-offset: 2px;
    }
    
    /* High contrast mode support */
    @media (prefers-contrast: high) {
        .status-badge {
            border-width: 2px;
        }
        
        .timeline-icon {
            border: 2px solid currentColor;
        }
        
        .star {
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
        }
    }
    
    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
        .fade-in,
        .btn,
        .product-card,
        .star {
            transition: none;
        }
        
        .fade-in {
            opacity: 1;
            transform: none;
        }
        
        .product-card:hover,
        .star:hover {
            transform: none;
        }
    }
</style>
@endpush

@section('content')
<!-- Testimonial Header -->
<div class="testimonial-header fade-in">
    <div class="testimonial-title">
        <div class="testimonial-info">
            <h1 class="testimonial-id">
                <i class="fas fa-star"></i>
                {{ __('Testimonial Details') }}
            </h1>
            <div class="testimonial-date">
                <i class="fas fa-calendar-alt"></i>
                {{ __('Submitted on') }} March 15, 2024 at 2:30 PM
            </div>
        </div>
        
        <div class="testimonial-actions">
            <a href="#" onclick="goBack()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to Testimonials') }}
            </a>
            
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i>
                {{ __('Print') }}
            </button>
        </div>
    </div>
    
    <span class="status-badge status-pending">
        <i class="fas fa-clock"></i>
        Pending
    </span>
</div>

<!-- Testimonial Content Grid -->
<div class="testimonial-grid">
    <!-- Main Content -->
    <div class="testimonial-content-section fade-in">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-comment-alt"></i>
                {{ __('Customer Testimonial') }}
            </h3>
        </div>
        
        <div class="content-body">
            <!-- Rating Section -->
            <div class="rating-section">
                <div class="rating-stars">
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                </div>
                <div class="rating-text">5 {{ __('out of 5 stars') }}</div>
                <div class="rating-subtitle">{{ __('Customer Rating') }}</div>
            </div>
            
            <!-- Testimonial Text -->
            <div class="testimonial-text">
                Amazing product! The quality exceeded my expectations and the customer service was outstanding. I would definitely recommend this to anyone looking for a great solution. The delivery was fast and the packaging was excellent. Very satisfied with my purchase!
            </div>
            
            <!-- Related Products -->
            <div class="related-products">
                <h4 style="margin: 0 0 var(--space-md) 0; color: var(--admin-secondary-900); font-weight: 600;">
                    <i class="fas fa-shopping-bag"></i>
                    {{ __('Products from this order') }}
                </h4>
                
                <div class="products-grid">
                    <div class="product-card">
                        <div class="product-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="product-name">Premium Laptop</div>
                        <div class="product-price">$1,299.99</div>
                    </div>
                    
                    <div class="product-card">
                        <div class="product-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="product-name">Wireless Mouse</div>
                        <div class="product-price">$49.99</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="testimonial-sidebar fade-in">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-user"></i>
                {{ __('Customer Information') }}
            </h3>
        </div>
        
        <div class="sidebar-content">
            <!-- Customer Card -->
            <div class="customer-card">
                <div class="customer-avatar">
                    J
                </div>
                <div class="customer-name">John Doe</div>
                <div class="customer-email">john.doe@example.com</div>
                <div class="customer-role">{{ __('Customer') }}</div>
            </div>
            
            <!-- Information List -->
            <ul class="info-list">
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-envelope"></i>
                        {{ __('Email') }}
                    </span>
                    <span class="info-value">john.doe@example.com</span>
                </li>
                
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-calendar-plus"></i>
                        {{ __('Joined') }}
                    </span>
                    <span class="info-value">Jan 2024</span>
                </li>
                
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-shopping-cart"></i>
                        {{ __('Order') }}
                    </span>
                    <span class="info-value">
                        <a href="#" class="order-link" onclick="showOrderDetails()">
                            #1234
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </span>
                </li>
                
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-dollar-sign"></i>
                        {{ __('Order Value') }}
                    </span>
                    <span class="info-value">$1,349.98</span>
                </li>
                
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-truck"></i>
                        {{ __('Order Status') }}
                    </span>
                    <span class="info-value">Delivered</span>
                </li>
                
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-clock"></i>
                        {{ __('Submitted') }}
                    </span>
                    <span class="info-value">2 days ago</span>
                </li>
            </ul>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="button" class="btn btn-success" id="approveBtn">
                    <i class="fas fa-check"></i>
                    {{ __('Approve Testimonial') }}
                </button>
                
                <button type="button" class="btn btn-danger" id="rejectBtn">
                    <i class="fas fa-times"></i>
                    {{ __('Reject Testimonial') }}
                </button>
            </div>
            
            <!-- Delete Button -->
            <div class="action-buttons" style="margin-top: var(--space-md);">
                <button type="button" class="btn btn-danger" id="deleteBtn">
                    <i class="fas fa-trash"></i>
                    {{ __('Delete Testimonial') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Timeline -->
<div class="timeline-section fade-in">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-history"></i>
            {{ __('Testimonial Timeline') }}
        </h3>
    </div>
    
    <div class="timeline-item">
        <div class="timeline-icon icon-submitted">
            <i class="fas fa-plus"></i>
        </div>
        <div class="timeline-content">
            <div class="timeline-title">{{ __('Testimonial Submitted') }}</div>
            <div class="timeline-time">March 15, 2024 at 2:30 PM</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to buttons
        var approveBtn = document.getElementById('approveBtn');
        var rejectBtn = document.getElementById('rejectBtn');
        var deleteBtn = document.getElementById('deleteBtn');
        
        if (approveBtn) {
            approveBtn.addEventListener('click', function() {
                approveTestimonial();
            });
        }
        
        if (rejectBtn) {
            rejectBtn.addEventListener('click', function() {
                rejectTestimonial();
            });
        }
        
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                deleteTestimonial();
            });
        }
        
        // Initialize animations
        initAnimations();
    });
    
    // Navigation functions
    function goBack() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '/admin/testimonials';
        }
    }
    
    function showOrderDetails() {
        showNotification('Order details would open here', 'info');
        // In real implementation: window.location.href = orderUrl;
    }
    
    // Action functions
    function approveTestimonial() {
        if (confirm('Are you sure you want to approve this testimonial?')) {
            var button = document.getElementById('approveBtn');
            if (!button) return;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approving...';
            button.disabled = true;
            button.style.opacity = '0.7';
            
            // Simulate API call
            setTimeout(function() {
                showNotification('Testimonial approved successfully!', 'success');
                updateTestimonialStatus('approved');
                
                // Update button
                button.innerHTML = '<i class="fas fa-check"></i> Approved';
                button.classList.remove('btn-success');
                button.classList.add('btn-secondary');
                button.disabled = true;
                
                // Hide reject button
                var rejectBtn = document.getElementById('rejectBtn');
                if (rejectBtn) {
                    rejectBtn.style.display = 'none';
                }
            }, 1500);
        }
    }
    
    function rejectTestimonial() {
        if (confirm('Are you sure you want to reject this testimonial?')) {
            var button = document.getElementById('rejectBtn');
            if (!button) return;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rejecting...';
            button.disabled = true;
            button.style.opacity = '0.7';
            
            setTimeout(function() {
                showNotification('Testimonial rejected successfully!', 'success');
                updateTestimonialStatus('rejected');
                
                // Update button
                button.innerHTML = '<i class="fas fa-times"></i> Rejected';
                button.classList.remove('btn-danger');
                button.classList.add('btn-secondary');
                button.disabled = true;
                
                // Hide approve button
                var approveBtn = document.getElementById('approveBtn');
                if (approveBtn) {
                    approveBtn.style.display = 'none';
                }
            }, 1500);
        }
    }
    
    function deleteTestimonial() {
        if (confirm('Are you sure you want to delete this testimonial? This action cannot be undone.')) {
            var button = document.getElementById('deleteBtn');
            if (!button) return;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
            button.disabled = true;
            button.style.opacity = '0.7';
            
            setTimeout(function() {
                showNotification('Testimonial deleted successfully!', 'success');
                setTimeout(function() {
                    goBack();
                }, 1000);
            }, 1500);
        }
    }
    
    // Update testimonial status
    function updateTestimonialStatus(newStatus) {
        var statusBadge = document.querySelector('.status-badge');
        if (!statusBadge) return;
        
        // Remove old classes
        statusBadge.classList.remove('status-pending', 'status-approved', 'status-rejected');
        
        // Add new classes and content
        statusBadge.classList.add('status-' + newStatus);
        
        if (newStatus === 'approved') {
            statusBadge.innerHTML = '<i class="fas fa-check"></i> Approved';
        } else if (newStatus === 'rejected') {
            statusBadge.innerHTML = '<i class="fas fa-times"></i> Rejected';
        }
        
        // Add animation
        statusBadge.style.transform = 'scale(1.1)';
        setTimeout(function() {
            statusBadge.style.transform = 'scale(1)';
        }, 200);
        
        // Update timeline
        addTimelineItem(newStatus);
    }
    
    // Add timeline item
    function addTimelineItem(status) {
        var timelineSection = document.querySelector('.timeline-section');
        if (!timelineSection) return;
        
        var newItem = document.createElement('div');
        newItem.className = 'timeline-item';
        newItem.style.opacity = '0';
        newItem.style.transform = 'translateX(-20px)';
        
        var iconClass = status === 'approved' ? 'icon-approved' : 'icon-rejected';
        var iconName = status === 'approved' ? 'check' : 'times';
        var title = status === 'approved' ? 'Testimonial Approved' : 'Testimonial Rejected';
        var currentTime = new Date().toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
        
        newItem.innerHTML = `
            <div class="timeline-icon ${iconClass}">
                <i class="fas fa-${iconName}"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-title">${title}</div>
                <div class="timeline-time">${currentTime}</div>
            </div>
        `;
        
        timelineSection.appendChild(newItem);
        
        // Animate in
        setTimeout(function() {
            newItem.style.transition = 'all 0.5s ease-out';
            newItem.style.opacity = '1';
            newItem.style.transform = 'translateX(0)';
        }, 100);
    }
    
    // Show notification
    function showNotification(message, type) {
        if (type === undefined) {
            type = 'info';
        }
        
        // Remove existing notifications
        var existingNotifications = document.querySelectorAll('.custom-notification');
        existingNotifications.forEach(function(notif) {
            notif.remove();
        });
        
        var notification = document.createElement('div');
        notification.className = 'custom-notification alert alert-' + type;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
            min-width: 250px;
            animation: slideInRight 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 500;
        `;
        
        var iconClass = 'info-circle';
        if (type === 'success') {
            iconClass = 'check-circle';
        } else if (type === 'error') {
            iconClass = 'exclamation-circle';
        } else if (type === 'warning') {
            iconClass = 'exclamation-triangle';
        }
        
        notification.innerHTML = '<i class="fas fa-' + iconClass + '" style="margin-right: 8px;"></i>' + message;
        
        document.body.appendChild(notification);
        
        setTimeout(function() {
            if (document.body.contains(notification)) {
                notification.style.animation = 'slideOutRight 0.3s ease-out forwards';
                setTimeout(function() {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }
        }, 3000);
    }
    
    // Initialize animations
    function initAnimations() {
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
    }
    
    // Print functionality enhancement
    window.addEventListener('beforeprint', function() {
        var hideElements = document.querySelectorAll('.testimonial-actions, .action-buttons, .btn');
        hideElements.forEach(function(el) {
            el.style.display = 'none';
        });
    });
    
    window.addEventListener('afterprint', function() {
        var showElements = document.querySelectorAll('.testimonial-actions, .action-buttons, .btn');
        showElements.forEach(function(el) {
            el.style.display = '';
        });
    });
    
    // Enhanced star interactions
    document.querySelectorAll('.star').forEach(function(star, index) {
        star.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.filter = 'brightness(1.2)';
        });
        
        star.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.filter = '';
        });
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + P for print
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
        
        // Escape to go back
        if (e.key === 'Escape') {
            goBack();
        }
    });
</script>

<style>
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    /* Button states */
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .btn:disabled:hover {
        transform: none !important;
    }
    
    /* Notification styles */
    .custom-notification {
        display: flex;
        align-items: center;
        font-size: 14px;
    }
    
    /* Timeline animation */
    .timeline-item {
        transition: all 0.3s ease-out;
    }
    
    .timeline-item:hover {
        background: var(--admin-secondary-50);
        border-radius: var(--radius-md);
    }
</style>
@endpush
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
    
    // Print functionality enhancement
    window.addEventListener('beforeprint', function() {
        // Hide non-essential elements when printing
        var hideElements = document.querySelectorAll('.testimonial-actions, .action-buttons, .btn');
        hideElements.forEach(function(el) {
            el.style.display = 'none';
        });
    });
    
    window.addEventListener('afterprint', function() {
        // Show elements back after printing
        var showElements = document.querySelectorAll('.testimonial-actions, .action-buttons, .btn');
        showElements.forEach(function(el) {
            el.style.display = '';
        });
    });
    
    // Enhanced star interactions
    document.querySelectorAll('.star').forEach(function(star, index) {
        star.addEventListener('mouseenter', function() {
            // Highlight stars up to current one
            var stars = this.parentElement.querySelectorAll('.star');
            stars.forEach(function(s, i) {
                if (i <= index) {
                    s.style.transform = 'scale(1.1)';
                    s.style.filter = 'brightness(1.2)';
                }
            });
        });
        
        star.addEventListener('mouseleave', function() {
            // Reset all stars
            var stars = this.parentElement.querySelectorAll('.star');
            stars.forEach(function(s) {
                s.style.transform = '';
                s.style.filter = '';
            });
        });
    });
    
    // Smooth scroll to sections
    function scrollToSection(sectionId) {
        document.getElementById(sectionId).scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + P for print
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
        
        // Escape to go back
        if (e.key === 'Escape') {
            window.location.href = '{{ route("admin.testimonials.index") }}';
        }
    });
    
    // Auto-refresh testimonial status (every 30 seconds)
    setInterval(function() {
        fetch('{{ route("admin.testimonials.show", $testimonial) }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            if (data.status && data.status !== '{{ $testimonial->status }}') {
                showNotification('{{ __("Testimonial status has been updated") }}', 'info');
                setTimeout(function() {
                    location.reload();
                }, 2000);
            }
        }).catch(function(error) {
            // Silently fail - don't show error for auto-refresh
        });
    }, 30000);
    
    // Show notification
    function showNotification(message, type) {
        if (type === undefined) {
            type = 'info';
        }
        
        var notification = document.createElement('div');
        notification.className = 'alert alert-' + type;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.maxWidth = '300px';
        notification.style.animation = 'slideInRight 0.3s ease-out';
        
        var iconClass = 'info-circle';
        if (type === 'success') {
            iconClass = 'check-circle';
        } else if (type === 'error') {
            iconClass = 'exclamation-circle';
        } else if (type === 'warning') {
            iconClass = 'exclamation-triangle';
        }
        
        notification.innerHTML = '<i class="fas fa-' + iconClass + '"></i> ' + message;
        
        document.body.appendChild(notification);
        
        setTimeout(function() {
            notification.style.animation = 'slideOutRight 0.3s ease-out forwards';
            setTimeout(function() {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
</script>

<style>
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
@endpush