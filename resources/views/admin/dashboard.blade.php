@extends('layouts.admin')

@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <i class="fas fa-home"></i>
        {{ __('Dashboard') }}
    </div>
@endsection

@push('styles')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .stat-card {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        position: relative;
        overflow: hidden;
        transition: all var(--transition-normal);
        border: none;
        box-shadow: var(--shadow-lg);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }
    
    .stat-card.orders {
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
    }
    
    .stat-card.users {
        background: linear-gradient(135deg, var(--warning-500), var(--warning-600));
    }
    
    .stat-card.revenue {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--space-lg);
        position: relative;
        z-index: 1;
    }
    
    .stat-info h3 {
        font-size: 0.875rem;
        font-weight: 500;
        opacity: 0.9;
        margin: 0 0 var(--space-xs) 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 900;
        margin: 0;
        line-height: 1;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-footer {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        font-size: 0.875rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    
    .stat-change {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        padding: var(--space-xs) var(--space-sm);
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-sm);
        font-weight: 600;
    }
    
    .stat-change.positive {
        background: rgba(34, 197, 94, 0.2);
    }
    
    .stat-change.negative {
        background: rgba(239, 68, 68, 0.2);
    }
    
    .content-sections {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--space-2xl);
        margin-bottom: var(--space-2xl);
    }
    
    .chart-section {
        display: grid;
        gap: var(--space-xl);
    }
    
    .chart-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }
    
    .chart-header {
        padding: var(--space-xl);
        border-bottom: 1px solid var(--admin-secondary-200);
        background: var(--admin-secondary-50);
    }
    
    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .chart-body {
        padding: var(--space-xl);
        height: 300px;
        position: relative;
    }
    
    .chart-canvas {
        width: 100% !important;
        height: 100% !important;
    }
    
    .activity-section {
        display: grid;
        gap: var(--space-xl);
        align-content: start;
    }
    
    .activity-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }
    
    .activity-header {
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-200);
        background: var(--admin-secondary-50);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .activity-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .view-all-link {
        color: var(--admin-primary-600);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: color var(--transition-fast);
    }
    
    .view-all-link:hover {
        color: var(--admin-primary-700);
    }
    
    .activity-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .activity-item {
        padding: var(--space-md) var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-100);
        transition: background-color var(--transition-fast);
    }
    
    .activity-item:hover {
        background: var(--admin-secondary-50);
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .order-info {
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
    }
    
    .order-id {
        font-weight: 600;
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
    }
    
    .order-customer {
        color: var(--admin-secondary-600);
        font-size: 0.75rem;
    }
    
    .order-amount {
        font-weight: 700;
        color: var(--admin-primary-600);
        font-size: 0.875rem;
    }
    
    .testimonial-item {
        display: flex;
        gap: var(--space-md);
    }
    
    .testimonial-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        flex-shrink: 0;
    }
    
    .testimonial-content {
        flex: 1;
        min-width: 0;
    }
    
    .testimonial-text {
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
        line-height: 1.4;
        margin-bottom: var(--space-xs);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .testimonial-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .testimonial-rating {
        display: flex;
        gap: 2px;
    }
    
    .star {
        color: #fbbf24;
    }
    
    .conversation-item {
        display: flex;
        gap: var(--space-md);
    }
    
    .conversation-avatar {
        width: 36px;
        height: 36px;
        background: var(--warning-500);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
    
    .conversation-content {
        flex: 1;
        min-width: 0;
    }
    
    .conversation-title {
        font-weight: 600;
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
        margin-bottom: var(--space-xs);
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .conversation-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
    }
    
    .unread-badge {
        background: var(--error-500);
        color: white;
        font-size: 0.625rem;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 600;
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-2xl);
        color: var(--admin-secondary-500);
    }
    
    .empty-icon {
        font-size: 2rem;
        margin-bottom: var(--space-md);
        opacity: 0.5;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-2xl);
    }
    
    .quick-action {
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        text-align: center;
        text-decoration: none;
        color: var(--admin-secondary-700);
        transition: all var(--transition-fast);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .quick-action:hover {
        border-color: var(--admin-primary-500);
        background: var(--admin-primary-50);
        color: var(--admin-primary-700);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .quick-action-icon {
        width: 48px;
        height: 48px;
        background: var(--admin-secondary-100);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: all var(--transition-fast);
    }
    
    .quick-action:hover .quick-action-icon {
        background: var(--admin-primary-500);
        color: white;
    }
    
    .quick-action-text {
        font-weight: 600;
        font-size: 0.875rem;
    }

    .badge {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.375rem;
        margin-left: 0.5rem;
    }

    .badge-warning {
        color: #212529;
        background-color: #ffc107;
    }
    
    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
        
        .content-sections {
            grid-template-columns: 1fr;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .chart-body {
            height: 250px;
        }
        
        .quick-actions {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
    }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="dashboard-grid">
    <div class="stat-card fade-in">
        <div class="stat-header">
            <div class="stat-info">
                <h3>{{ __('Total Products') }}</h3>
                <p class="stat-number">{{ number_format($totalProducts) }}</p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +12%
            </div>
            <span>{{ __('vs last month') }}</span>
        </div>
    </div>
    
    <div class="stat-card orders fade-in">
        <div class="stat-header">
            <div class="stat-info">
                <h3>{{ __('Total Orders') }}</h3>
                <p class="stat-number">{{ number_format($totalOrders) }}</p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +8%
            </div>
            <span>{{ __('vs last month') }}</span>
        </div>
    </div>
    
    <div class="stat-card users fade-in">
        <div class="stat-header">
            <div class="stat-info">
                <h3>{{ __('Total Users') }}</h3>
                <p class="stat-number">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +15%
            </div>
            <span>{{ __('vs last month') }}</span>
        </div>
    </div>
    
    <div class="stat-card revenue fade-in">
        <div class="stat-header">
            <div class="stat-info">
                <h3>{{ __('Total Revenue') }}</h3>
                <p class="stat-number">${{ number_format($totalRevenue, 0) }}</p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <div class="stat-footer">
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +23%
            </div>
            <span>{{ __('vs last month') }}</span>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="{{ route('admin.products.create') }}" class="quick-action fade-in">
        <div class="quick-action-icon">
            <i class="fas fa-plus"></i>
        </div>
        <span class="quick-action-text">{{ __('Add Product') }}</span>
    </a>
    
    <a href="{{ route('admin.educational-cards.create') }}" class="quick-action fade-in">
        <div class="quick-action-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <span class="quick-action-text">{{ __('Add Educational Card') }}</span>
    </a>
    
    <a href="{{ route('admin.orders.index') }}" class="quick-action fade-in">
        <div class="quick-action-icon">
            <i class="fas fa-list"></i>
        </div>
        <span class="quick-action-text">{{ __('View Orders') }}</span>
    </a>
    
    <a href="{{ route('admin.users.create') }}" class="quick-action fade-in">
        <div class="quick-action-icon">
            <i class="fas fa-user-plus"></i>
        </div>
        <span class="quick-action-text">{{ __('Add User') }}</span>
    </a>
</div>

<!-- Content Sections -->
<div class="content-sections">
    <!-- Charts Section -->
    <div class="chart-section">
        <!-- Monthly Revenue Chart -->
        <div class="chart-card fade-in">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="fas fa-chart-line"></i>
                    {{ __('Monthly Revenue') }}
                </h3>
            </div>
            <div class="chart-body">
                <canvas id="revenueChart" class="chart-canvas"></canvas>
            </div>
        </div>
        
        <!-- Sales by Category Chart -->
        <div class="chart-card fade-in">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    {{ __('Sales by Category') }}
                </h3>
            </div>
            <div class="chart-body">
                <canvas id="categoryChart" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Activity Section -->
    <div class="activity-section">
        <!-- Recent Orders -->
        <div class="activity-card fade-in">
            <div class="activity-header">
                <h3 class="activity-title">
                    <i class="fas fa-shopping-cart"></i>
                    {{ __('Recent Orders') }}
                </h3>
                <a href="{{ route('admin.orders.index') }}" class="view-all-link">
                    {{ __('View All') }}
                </a>
            </div>
            <div class="activity-list">
                @forelse($recentOrders as $order)
                    <div class="activity-item">
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-id">#{{ $order->id }}</div>
                                <div class="order-customer">{{ $order->user->name }}</div>
                            </div>
                            <div class="order-amount">${{ number_format($order->total_amount, 2) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <p>{{ __('No recent orders') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Testimonials -->
        <div class="activity-card fade-in">
            <div class="activity-header">
                <h3 class="activity-title">
                    <i class="fas fa-star"></i>
                    {{ __('Recent Testimonials') }}
                    @if($pendingTestimonialsCount > 0)
                        <span class="badge badge-warning">{{ $pendingTestimonialsCount }}</span>
                    @endif
                </h3>
                <a href="{{ route('admin.testimonials.index') }}" class="view-all-link">
                    {{ __('View All') }}
                </a>
            </div>
            <div class="activity-list">
                @forelse($recentTestimonials as $testimonial)
                    <div class="activity-item">
                        <div class="testimonial-item">
                            <div class="testimonial-avatar">
                                {{ substr($testimonial->user->name, 0, 1) }}
                            </div>
                            <div class="testimonial-content">
                                <div class="testimonial-text">{{ $testimonial->review }}</div>
                                <div class="testimonial-meta">
                                    <span>{{ $testimonial->user->name }}</span>
                                    <div class="testimonial-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $testimonial->rating ? 'star' : '' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <p>{{ __('No recent testimonials') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Conversations -->
        <div class="activity-card fade-in">
            <div class="activity-header">
                <h3 class="activity-title">
                    <i class="fas fa-comments"></i>
                    {{ __('Recent Conversations') }}
                    @if($unreadConversationsCount > 0)
                        <span class="badge badge-warning">{{ $unreadConversationsCount }}</span>
                    @endif
                </h3>
                <a href="{{ route('admin.conversations.index') }}" class="view-all-link">
                    {{ __('View All') }}
                </a>
            </div>
            <div class="activity-list">
                @forelse($recentConversations as $conversation)
                    <div class="activity-item">
                        <div class="conversation-item">
                            <div class="conversation-avatar">
                                {{ substr($conversation->user->name, 0, 1) }}
                            </div>
                            <div class="conversation-content">
                                <div class="conversation-title">{{ $conversation->subject }}</div>
                                <div class="conversation-meta">
                                    <span>{{ $conversation->user->name }}</span>
                                    @if(!$conversation->is_read_by_admin)
                                        <span class="unread-badge">{{ __('New') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <p>{{ __('No recent conversations') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: '{{ __("Revenue") }}',
                    data: @json($monthlyData),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                elements: {
                    point: {
                        hoverBackgroundColor: '#6366f1'
                    }
                }
            }
        });

        // Sales by Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryData),
                    backgroundColor: [
                        '#6366f1',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#06b6d4'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '60%'
            }
        });
    });
</script>
@endpush