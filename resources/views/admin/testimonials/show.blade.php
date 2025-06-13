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
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }
    
    .testimonial-title {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .testimonial-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .testimonial-id {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .testimonial-date {
        color: #475569;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .testimonial-actions {
        display: flex;
        gap: 0.5rem;
        align-items: flex-start;
        flex-wrap: wrap;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1.5rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        gap: 0.5rem;
        margin-bottom: 1rem;
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
    
    .testimonial-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .testimonial-content-section {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .section-header {
        background: #f8fafc;
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }
    
    .content-body {
        padding: 2rem;
    }
    
    .testimonial-text {
        font-size: 1.125rem;
        line-height: 1.8;
        color: #334155;
        font-style: italic;
        position: relative;
        margin: 2rem 0;
        padding: 2rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        border-left: 4px solid #3b82f6;
    }
    
    .testimonial-text::before {
        content: '"';
        font-size: 4rem;
        color: #93c5fd;
        position: absolute;
        top: -10px;
        left: 1rem;
        font-family: serif;
        line-height: 1;
    }
    
    .testimonial-text::after {
        content: '"';
        font-size: 4rem;
        color: #93c5fd;
        position: absolute;
        bottom: -30px;
        right: 1rem;
        font-family: serif;
        line-height: 1;
    }
    
    .rating-section {
        background: #f8fafc;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin: 1.5rem 0;
        text-align: center;
    }
    
    .rating-stars {
        display: flex;
        justify-content: center;
        gap: 0.25rem;
        margin-bottom: 1rem;
    }
    
    .star {
        font-size: 2rem;
        color: #fbbf24;
        transition: all 0.15s ease;
    }
    
    .star.empty {
        color: #cbd5e1;
    }
    
    .star:hover {
        transform: scale(1.1);
    }
    
    .rating-text {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f172a;
    }
    
    .rating-subtitle {
        font-size: 0.875rem;
        color: #475569;
        margin-top: 0.25rem;
    }
    
    .testimonial-sidebar {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        height: fit-content;
    }
    
    .sidebar-content {
        padding: 1.5rem;
    }
    
    .customer-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .customer-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 2rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .customer-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    
    .customer-email {
        color: #475569;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
    
    .customer-role {
        background: #dbeafe;
        color: #1d4ed8;
        padding: 0.25rem 1rem;
        border-radius: 0.375rem;
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
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        color: #475569;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .info-value {
        font-weight: 600;
        color: #0f172a;
        text-align: right;
    }
    
    .order-link {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.15s ease;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .order-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-top: 1.5rem;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        border: 1px solid transparent;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
        text-align: center;
    }
    
    .btn-primary {
        background: #2563eb;
        color: white;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .btn-primary:hover {
        background: #1d4ed8;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
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
    
    .btn-secondary {
        background: white;
        color: #334155;
        border-color: #cbd5e1;
    }
    
    .btn-secondary:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }
    
    .timeline-section {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-top: 2rem;
    }
    
    .timeline-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
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
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    
    .timeline-time {
        color: #475569;
        font-size: 0.875rem;
    }
    
    .icon-submitted {
        background: #eff6ff;
        color: #2563eb;
    }
    
    .icon-approved {
        background: #dcfce7;
        color: #059669;
    }
    
    .icon-rejected {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .related-products {
        background: #f8fafc;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .product-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease;
    }
    
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .product-placeholder {
        width: 100%;
        height: 120px;
        background: #f1f5f9;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        margin-bottom: 0.5rem;
    }
    
    .product-name {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .product-price {
        color: #2563eb;
        font-weight: 700;
        font-size: 1rem;
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
            padding: 1.5rem;
        }
        
        .customer-avatar {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .testimonial-text {
            font-size: 1rem;
            padding: 1.5rem;
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
</style>
@endpush

@section('content')
<!-- Testimonial Header -->
<div class="testimonial-header fade-in">
    <div class="testimonial-title">
        <div class="testimonial-info">
            <h1 class="testimonial-id">
                <i class="fas fa-star"></i>
                {{ __('Testimonial') }} #{{ $testimonial->id }}
            </h1>
            <div class="testimonial-date">
                <i class="fas fa-calendar-alt"></i>
                {{ __('Submitted on') }} {{ $testimonial->created_at->format('F d, Y \a\t g:i A') }}
            </div>
        </div>
        
        <div class="testimonial-actions">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to Testimonials') }}
            </a>
            
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i>
                {{ __('Print') }}
            </button>
        </div>
    </div>
    
    <span class="status-badge status-{{ $testimonial->status }}">
        <i class="fas fa-{{ $testimonial->status === 'pending' ? 'clock' : ($testimonial->status === 'approved' ? 'check' : 'times') }}"></i>
        {{ ucfirst($testimonial->status) }}
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
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $testimonial->rating ? 'star' : 'star empty' }}"></i>
                    @endfor
                </div>
                <div class="rating-text">{{ $testimonial->rating }} {{ __('out of 5 stars') }}</div>
                <div class="rating-subtitle">{{ __('Customer Rating') }}</div>
            </div>
            
            <!-- Testimonial Text -->
            <div class="testimonial-text">
                {{ $testimonial->comment }}
            </div>
            
            <!-- Related Products -->
            @if($testimonial->order && $testimonial->order->orderItems->count() > 0)
                <div class="related-products">
                    <h4 style="margin: 0 0 1rem 0; color: #0f172a; font-weight: 600;">
                        <i class="fas fa-shopping-bag"></i>
                        {{ __('Products from this order') }}
                    </h4>
                    
                    <div class="products-grid">
                        @foreach($testimonial->order->orderItems->take(4) as $orderItem)
                            <div class="product-card">
                                @if($orderItem->product && $orderItem->product->image)
                                    <img src="{{ asset('storage/' . $orderItem->product->image) }}" alt="{{ $orderItem->product->name }}" class="product-image">
                                @else
                                    <div class="product-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <div class="product-name">{{ $orderItem->product->name ?? 'Product' }}</div>
                                <div class="product-price">${{ number_format($orderItem->price, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
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
                    {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}
                </div>
                <div class="customer-name">{{ $testimonial->user->name }}</div>
                <div class="customer-email">{{ $testimonial->user->email }}</div>
                <div class="customer-role">{{ __('Customer') }}</div>
            </div>
            
            <!-- Information List -->
            <ul class="info-list">
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-envelope"></i>
                        {{ __('Email') }}
                    </span>
                    <span class="info-value">{{ $testimonial->user->email }}</span>
                </li>
                
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-calendar-plus"></i>
                        {{ __('Joined') }}
                    </span>
                    <span class="info-value">{{ $testimonial->user->created_at->format('M Y') }}</span>
                </li>
                
                @if($testimonial->order)
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-shopping-cart"></i>
                            {{ __('Order') }}
                        </span>
                        <span class="info-value">
                            <a href="{{ route('admin.orders.show', $testimonial->order) }}" class="order-link">
                                #{{ $testimonial->order->id }}
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </span>
                    </li>
                    
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-dollar-sign"></i>
                            {{ __('Order Value') }}
                        </span>
                        <span class="info-value">${{ number_format($testimonial->order->total_amount, 2) }}</span>
                    </li>
                    
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-truck"></i>
                            {{ __('Order Status') }}
                        </span>
                        <span class="info-value">{{ ucfirst($testimonial->order->status) }}</span>
                    </li>
                @endif
                
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-clock"></i>
                        {{ __('Submitted') }}
                    </span>
                    <span class="info-value">{{ $testimonial->created_at->diffForHumans() }}</span>
                </li>
            </ul>
            
            <!-- Action Buttons -->
            @if($testimonial->status === 'pending')
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
            @endif
            
            <!-- Delete Button -->
            <div class="action-buttons" style="margin-top: 1rem;">
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
            <div class="timeline-time">{{ $testimonial->created_at->format('F d, Y \a\t g:i A') }}</div>
        </div>
    </div>
    
    @if($testimonial->status !== 'pending')
        <div class="timeline-item">
            <div class="timeline-icon icon-{{ $testimonial->status }}">
                <i class="fas fa-{{ $testimonial->status === 'approved' ? 'check' : 'times' }}"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-title">{{ __('Testimonial') }} {{ ucfirst($testimonial->status) }}</div>
                <div class="timeline-time">{{ $testimonial->updated_at->format('F d, Y \a\t g:i A') }}</div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
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
    
    // Action functions
    function approveTestimonial() {
        if (confirm('Are you sure you want to approve this testimonial?')) {
            var button = document.getElementById('approveBtn');
            if (!button) return;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approving...';
            button.disabled = true;
            
            fetch('{{ route("admin.testimonials.approve", $testimonial) }}', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    showNotification('Testimonial approved successfully!', 'success');
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
    }
    
    function rejectTestimonial() {
        if (confirm('Are you sure you want to reject this testimonial?')) {
            var button = document.getElementById('rejectBtn');
            if (!button) return;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rejecting...';
            button.disabled = true;
            
            fetch('{{ route("admin.testimonials.reject", $testimonial) }}', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    showNotification('Testimonial rejected successfully!', 'success');
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
    }
    
    function deleteTestimonial() {
        if (confirm('Are you sure you want to delete this testimonial? This action cannot be undone.')) {
            var button = document.getElementById('deleteBtn');
            if (!button) return;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
            button.disabled = true;
            
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.testimonials.destroy", $testimonial) }}';
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    function showNotification(message, type) {
        var notification = document.createElement('div');
        notification.className = 'alert alert-' + type;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
            animation: slideInRight 0.3s ease-out;
            padding: 12px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        `;
        
        var iconClass = type === 'success' ? 'check-circle' : 
                       type === 'error' ? 'exclamation-circle' : 'info-circle';
        
        notification.innerHTML = '<i class="fas fa-' + iconClass + '"></i>' + message;
        
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
    }
    
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
            window.location.href = '{{ route("admin.testimonials.index") }}';
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
    
    @keyframes fa-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .fa-spin {
        animation: fa-spin 1s infinite linear;
    }
    
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .btn:disabled:hover {
        transform: none !important;
    }
    
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .alert-success {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }
    
    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border-color: #fecaca;
    }
    
    .alert-info {
        background: #eff6ff;
        color: #1e40af;
        border-color: #bfdbfe;
    }
    
    @media print {
        .testimonial-actions,
        .action-buttons,
        .btn {
            display: none !important;
        }
        
        .testimonial-grid {
            grid-template-columns: 1fr !important;
        }
        
        body {
            font-size: 12px !important;
        }
    }
</style>
@endpush