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
        
        .btn:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .btn:disabled:hover {
            transform: none !important;
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
        
        @keyframes fa-spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        
        .fa-spin {
            animation: fa-spin 1s infinite linear;
        }
        
        .custom-notification {
            display: flex;
            align-items: center;
            font-size: 14px;
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
    <div class="testimonials-tabs fade-in">
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
                        <div class="testimonial-card fade-in" data-testimonial-id="{{ $testimonial->id }}">
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
                        <div class="testimonial-card fade-in" data-testimonial-id="{{ $testimonial->id }}">
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
                        <div class="testimonial-card fade-in" data-testimonial-id="{{ $testimonial->id }}">
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
                
                // Trigger animations for testimonial cards
                setTimeout(function() {
                    var cards = document.querySelectorAll('#' + targetTab + ' .testimonial-card');
                    cards.forEach(function(card, index) {
                        setTimeout(function() {
                            card.classList.add('visible');
                        }, index * 100);
                    });
                }, 100);
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
        
        // Initialize animations
        initAnimations();
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
        .then(response => response.json())
        .then(data => {
            showNotification('Testimonial approved successfully!', 'success');
            
            // Move card to approved tab
            moveTestimonialCard(testimonialId, 'approved');
            
            // Update counters
            updateCounters();
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
        .then(response => response.json())
        .then(data => {
            showNotification('Testimonial rejected successfully!', 'success');
            
            // Move card to rejected tab
            moveTestimonialCard(testimonialId, 'rejected');
            
            // Update counters
            updateCounters();
        })
        .catch(error => {
            showNotification('Error rejecting testimonial', 'error');
            button.innerHTML = '<i class="fas fa-times"></i> Reject';
            button.disabled = false;
        });
    }

    function moveTestimonialCard(testimonialId, newStatus) {
        var card = document.querySelector('[data-testimonial-id="' + testimonialId + '"]');
        if (!card) return;
        
        // Update status badge
        var statusBadge = card.querySelector('.status-badge');
        statusBadge.className = 'status-badge status-' + newStatus;
        statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
        
        // Update buttons
        var actions = card.querySelector('.testimonial-actions');
        var viewBtn = actions.querySelector('a');
        
        if (newStatus === 'approved') {
            actions.innerHTML = viewBtn.outerHTML + 
                '<button class="btn btn-danger btn-sm reject-btn" data-testimonial-id="' + testimonialId + '">' +
                '<i class="fas fa-times"></i> Reject</button>';
        } else if (newStatus === 'rejected') {
            actions.innerHTML = viewBtn.outerHTML + 
                '<button class="btn btn-success btn-sm approve-btn" data-testimonial-id="' + testimonialId + '">' +
                '<i class="fas fa-check"></i> Approve</button>';
        }
        
        // Re-attach event listeners to new buttons
        var newApproveBtn = actions.querySelector('.approve-btn');
        var newRejectBtn = actions.querySelector('.reject-btn');
        
        if (newApproveBtn) {
            newApproveBtn.addEventListener('click', function() {
                handleApprove(testimonialId, this);
            });
        }
        
        if (newRejectBtn) {
            newRejectBtn.addEventListener('click', function() {
                handleReject(testimonialId, this);
            });
        }
        
        // Animate card removal from current tab
        card.style.transform = 'scale(0.8)';
        card.style.opacity = '0.5';
        
        setTimeout(function() {
            // Clone and move to appropriate tab
            var targetTab = document.getElementById(newStatus);
            var targetGrid = targetTab.querySelector('.testimonials-grid');
            
            if (!targetGrid) {
                // Create grid if empty state exists
                var emptyState = targetTab.querySelector('.empty-state');
                if (emptyState) {
                    emptyState.style.display = 'none';
                }
                targetGrid = document.createElement('div');
                targetGrid.className = 'testimonials-grid';
                targetTab.appendChild(targetGrid);
            }
            
            // Reset card styles and move
            card.style.transform = '';
            card.style.opacity = '';
            targetGrid.appendChild(card);
            
            // Show success animation
            card.style.transform = 'scale(1.05)';
            setTimeout(function() {
                card.style.transform = '';
            }, 200);
            
        }, 300);
    }

    function updateCounters() {
        // Update tab counters
        var pendingCount = document.querySelectorAll('#pending .testimonial-card').length;
        var approvedCount = document.querySelectorAll('#approved .testimonial-card').length;
        var rejectedCount = document.querySelectorAll('#rejected .testimonial-card').length;
        var totalCount = pendingCount + approvedCount + rejectedCount;
        
        // Update tab counts
        document.querySelector('[data-tab="pending"] .tab-count').textContent = pendingCount;
        document.querySelector('[data-tab="approved"] .tab-count').textContent = approvedCount;
        document.querySelector('[data-tab="rejected"] .tab-count').textContent = rejectedCount;
        
        // Update header stats
        var stats = document.querySelectorAll('.stat-number');
        if (stats.length >= 4) {
            stats[0].textContent = pendingCount; // Pending
            stats[1].textContent = approvedCount; // Approved  
            stats[2].textContent = rejectedCount; // Rejected
            stats[3].textContent = totalCount; // Total
        }
        
        // Show/hide empty states
        updateEmptyStates();
    }

    function updateEmptyStates() {
        ['pending', 'approved', 'rejected'].forEach(function(tab) {
            var tabElement = document.getElementById(tab);
            var grid = tabElement.querySelector('.testimonials-grid');
            var emptyState = tabElement.querySelector('.empty-state');
            var hasCards = grid && grid.children.length > 0;
            
            if (hasCards) {
                if (emptyState) emptyState.style.display = 'none';
                if (grid) grid.style.display = 'grid';
            } else {
                if (grid) grid.style.display = 'none';
                if (emptyState) emptyState.style.display = 'block';
            }
        });
    }

    function showNotification(message, type) {
        // Remove existing notifications
        document.querySelectorAll('.custom-notification').forEach(function(notif) {
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
        
        var iconClass = type === 'success' ? 'check-circle' : 
                    type === 'error' ? 'exclamation-circle' : 'info-circle';
        
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
        
        // Initialize first tab animations
        setTimeout(function() {
            var activeTabCards = document.querySelectorAll('.tab-content.active .testimonial-card');
            activeTabCards.forEach(function(card, index) {
                setTimeout(function() {
                    card.classList.add('visible');
                }, index * 100);
            });
        }, 500);
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Tab navigation with keyboard
        if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
            var activeButton = document.querySelector('.tab-button.active');
            var tabButtons = Array.from(document.querySelectorAll('.tab-button'));
            var currentIndex = tabButtons.indexOf(activeButton);
            var newIndex;
            
            if (e.key === 'ArrowLeft') {
                newIndex = currentIndex > 0 ? currentIndex - 1 : tabButtons.length - 1;
            } else {
                newIndex = currentIndex < tabButtons.length - 1 ? currentIndex + 1 : 0;
            }
            
            tabButtons[newIndex].click();
        }
    });

    // Enhanced card interactions
    document.querySelectorAll('.testimonial-card').forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Auto-refresh every 60 seconds for new testimonials
    setInterval(function() {
        fetch('/admin/testimonials', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            if (data.newTestimonials && data.newTestimonials > 0) {
                showNotification('New testimonials received!', 'info');
            }
        }).catch(function(error) {
            // Silently fail for auto-refresh
        });
    }, 60000);

    // Search functionality
    function searchTestimonials(query) {
        var allCards = document.querySelectorAll('.testimonial-card');
        
        allCards.forEach(function(card) {
            var customerName = card.querySelector('.customer-name').textContent.toLowerCase();
            var testimonialText = card.querySelector('.testimonial-text').textContent.toLowerCase();
            var customerEmail = card.querySelector('.customer-email').textContent.toLowerCase();
            
            var searchQuery = query.toLowerCase();
            
            if (customerName.includes(searchQuery) || 
                testimonialText.includes(searchQuery) || 
                customerEmail.includes(searchQuery)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Export functionality
    function exportTestimonials(status) {
        var data = [];
        var cards = document.querySelectorAll('#' + status + ' .testimonial-card');
        
        cards.forEach(function(card) {
            var testimonialData = {
                customer: card.querySelector('.customer-name').textContent,
                email: card.querySelector('.customer-email').textContent,
                rating: card.querySelector('.rating-text').textContent,
                comment: card.querySelector('.testimonial-text').textContent,
                date: card.querySelector('.testimonial-meta span').textContent
            };
            data.push(testimonialData);
        });
        
        // Convert to CSV
        var csv = 'Customer,Email,Rating,Comment,Date\n';
        data.forEach(function(row) {
            csv += [row.customer, row.email, row.rating, row.comment, row.date].join(',') + '\n';
        });
        
        // Download CSV
        var blob = new Blob([csv], { type: 'text/csv' });
        var url = window.URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = status + '_testimonials.csv';
        a.click();
        window.URL.revokeObjectURL(url);
    }

    // Bulk actions
    function bulkApprove(testimonialIds) {
        if (testimonialIds.length === 0) return;
        
        var promises = testimonialIds.map(function(id) {
            return fetch('/admin/testimonials/' + id + '/approve', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
        });
        
        Promise.all(promises).then(function() {
            showNotification('All selected testimonials approved!', 'success');
            testimonialIds.forEach(function(id) {
                moveTestimonialCard(id, 'approved');
            });
            updateCounters();
        }).catch(function(error) {
            showNotification('Error approving testimonials', 'error');
        });
    }

    function bulkReject(testimonialIds) {
        if (testimonialIds.length === 0) return;
        
        var promises = testimonialIds.map(function(id) {
            return fetch('/admin/testimonials/' + id + '/reject', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
        });
        
        Promise.all(promises).then(function() {
            showNotification('All selected testimonials rejected!', 'success');
            testimonialIds.forEach(function(id) {
                moveTestimonialCard(id, 'rejected');
            });
            updateCounters();
        }).catch(function(error) {
            showNotification('Error rejecting testimonials', 'error');
        });
    }

    // Performance optimization - Debounce function
    function debounce(func, wait) {
        var timeout;
        return function executedFunction() {
            var context = this;
            var args = arguments;
            var later = function() {
                clearTimeout(timeout);
                func.apply(context, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Accessibility improvements
    function announceToScreenReader(message) {
        var announcement = document.createElement('div');
        announcement.textContent = message;
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.style.position = 'absolute';
        announcement.style.left = '-10000px';
        announcement.style.width = '1px';
        announcement.style.height = '1px';
        announcement.style.overflow = 'hidden';
        
        document.body.appendChild(announcement);
        
        setTimeout(function() {
            document.body.removeChild(announcement);
        }, 1000);
    }

    // Initialize everything when DOM is fully loaded
    console.log('Testimonials management page loaded successfully');
    updateEmptyStates();
    </script>
    @endpush