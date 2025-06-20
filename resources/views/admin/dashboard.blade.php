@extends('layouts.admin')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <i class="fas fa-home"></i>
        لوحة التحكم
    </div>
@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
    * {
        direction: rtl;
        text-align: right;
    }
    
    body {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }
    
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        border-radius: 1.5rem;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.25), 0 10px 10px -5px rgba(99, 102, 241, 0.1);
        backdrop-filter: blur(10px);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(-30px, -30px);
    }
    
    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), transparent);
        border-radius: 50%;
        transform: translate(30px, 30px);
    }
    
    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 35px 60px -12px rgba(99, 102, 241, 0.4);
    }
    
    .stat-card.orders {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.25), 0 10px 10px -5px rgba(16, 185, 129, 0.1);
    }
    
    .stat-card.orders:hover {
        box-shadow: 0 35px 60px -12px rgba(16, 185, 129, 0.4);
    }
    
    .stat-card.users {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 20px 25px -5px rgba(245, 158, 11, 0.25), 0 10px 10px -5px rgba(245, 158, 11, 0.1);
    }
    
    .stat-card.users:hover {
        box-shadow: 0 35px 60px -12px rgba(245, 158, 11, 0.4);
    }
    
    .stat-card.revenue {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        box-shadow: 0 20px 25px -5px rgba(139, 92, 246, 0.25), 0 10px 10px -5px rgba(139, 92, 246, 0.1);
    }
    
    .stat-card.revenue:hover {
        box-shadow: 0 35px 60px -12px rgba(139, 92, 246, 0.4);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }
    
    .stat-info h3 {
        font-size: 1rem;
        font-weight: 600;
        opacity: 0.9;
        margin: 0 0 0.75rem 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 900;
        margin: 0;
        line-height: 1;
        background: linear-gradient(45deg, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0.8));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .stat-footer {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
        font-weight: 500;
    }
    
    .stat-change {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        font-weight: 700;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .stat-change.positive {
        background: rgba(34, 197, 94, 0.3);
        border-color: rgba(34, 197, 94, 0.5);
    }
    
    .stat-change.negative {
        background: rgba(239, 68, 68, 0.3);
        border-color: rgba(239, 68, 68, 0.5);
    }
    
    .content-sections {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    
    .chart-section {
        display: grid;
        gap: 2rem;
    }
    
    .chart-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    
    .chart-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.35);
    }
    
    .chart-header {
        padding: 2rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        position: relative;
    }
    
    .chart-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
    }
    
    .chart-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .chart-body {
        padding: 2rem;
        height: 350px;
        position: relative;
    }
    
    .chart-canvas {
        width: 100% !important;
        height: 100% !important;
    }
    
    .activity-section {
        display: grid;
        gap: 2rem;
        align-content: start;
    }
    
    .activity-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    
    .activity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.35);
    }
    
    .activity-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }
    
    .activity-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
    }
    
    .activity-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .view-all-link {
        color: #6366f1;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.2s ease;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        background: rgba(99, 102, 241, 0.1);
    }
    
    .view-all-link:hover {
        color: #4f46e5;
        background: rgba(99, 102, 241, 0.2);
        transform: translateX(-3px);
    }
    
    .activity-list {
        max-height: 350px;
        overflow-y: auto;
    }
    
    .activity-item {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }
    
    .activity-item:hover {
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.05));
        padding-right: 2.5rem;
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
        gap: 0.5rem;
    }
    
    .order-id {
        font-weight: 700;
        color: #0f172a;
        font-size: 1rem;
    }
    
    .order-customer {
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .order-amount {
        font-weight: 800;
        color: #6366f1;
        font-size: 1.1rem;
        direction: ltr;
        text-align: left;
    }
    
    .testimonial-item {
        display: flex;
        gap: 1rem;
    }
    
    .testimonial-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
        border: 3px solid white;
    }
    
    .testimonial-content {
        flex: 1;
        min-width: 0;
    }
    
    .testimonial-text {
        color: #374151;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-style: italic;
    }
    
    .testimonial-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }
    
    .testimonial-rating {
        display: flex;
        gap: 3px;
    }
    
    .star {
        color: #fbbf24;
        filter: drop-shadow(0 1px 2px rgba(251, 191, 36, 0.3));
    }
    
    .conversation-item {
        display: flex;
        gap: 1rem;
    }
    
    .conversation-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.4);
        border: 3px solid white;
    }
    
    .conversation-content {
        flex: 1;
        min-width: 0;
    }
    
    .conversation-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .conversation-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }
    
    .unread-badge {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 50px;
        font-weight: 700;
        box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.39);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #64748b;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 1rem;
        border: 2px dashed #d1d5db;
    }
    
    .empty-icon {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    
    .quick-action {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 2rem;
        text-align: center;
        text-decoration: none;
        color: #374151;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }
    
    .quick-action::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.05));
        border-radius: 50%;
        transform: translate(30%, -30%);
        transition: all 0.3s ease;
    }
    
    .quick-action:hover {
        border-color: #6366f1;
        background: linear-gradient(135deg, #f8fafc, rgba(99, 102, 241, 0.05));
        color: #4f46e5;
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.25);
    }
    
    .quick-action:hover::before {
        transform: translate(30%, -30%) scale(1.2);
        opacity: 0.8;
    }
    
    .quick-action-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }
    
    .quick-action:hover .quick-action-icon {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
    }
    
    .quick-action-text {
        font-weight: 700;
        font-size: 1rem;
        position: relative;
        z-index: 1;
    }

    .badge {
        display: inline-block;
        padding: 0.4em 0.8em;
        font-size: 70%;
        font-weight: 800;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 50px;
        margin-right: 0.75rem;
        box-shadow: 0 4px 14px 0 rgba(245, 158, 11, 0.39);
    }

    .badge-warning {
        color: #92400e;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
    }
    
    /* Fade-in animation */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease-out;
    }
    
    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* RTL adjustments */
    .fas {
        margin-left: 0.5rem;
        margin-right: 0;
    }
    
    .activity-title .fas {
        margin-left: 0;
        margin-right: 0.75rem;
    }
    
    .chart-title .fas {
        margin-left: 0;
        margin-right: 0.75rem;
    }
    
    .view-all-link:hover {
        transform: translateX(3px);
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
    }
    
    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .content-sections {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
        }
        
        .chart-body {
            height: 300px;
            padding: 1.5rem;
        }
        
        .quick-actions {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }
        
        .stat-card {
            padding: 1.5rem;
        }
        
        .activity-item {
            padding: 1rem 1.5rem;
        }
        
        .chart-header {
            padding: 1.5rem;
        }
        
        .activity-header {
            padding: 1rem 1.5rem;
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
                <h3>إجمالي المنتجات</h3>
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
            <span>مقارنة بالشهر الماضي</span>
        </div>
    </div>
    
    <div class="stat-card orders fade-in">
        <div class="stat-header">
            <div class="stat-info">
                <h3>إجمالي الطلبات</h3>
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
            <span>مقارنة بالشهر الماضي</span>
        </div>
    </div>
    
    <div class="stat-card users fade-in">
        <div class="stat-header">
            <div class="stat-info">
                <h3>إجمالي المستخدمين</h3>
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
            <span>مقارنة بالشهر الماضي</span>
        </div>
    </div>
    
    <div class="stat-card revenue fade-in">
        <div class="stat-header">
            <div class="stat-info">
                <h3>إجمالي الإيرادات</h3>
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
            <span>مقارنة بالشهر الماضي</span>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="{{ route('admin.products.create') }}" class="quick-action fade-in">
        <div class="quick-action-icon">
            <i class="fas fa-plus"></i>
        </div>
        <span class="quick-action-text">إضافة منتج</span>
    </a>
    
    <a href="{{ route('admin.categories.create') }}" class="quick-action fade-in">
        <div class="quick-action-icon">
            <i class="fas fa-tags"></i>
        </div>
        <span class="quick-action-text">إضافة تصنيف</span>
    </a>
    
    <a href="{{ route('admin.orders.index') }}" class="quick-action fade-in">
        <div class="quick-action-icon">
            <i class="fas fa-list"></i>
        </div>
        <span class="quick-action-text">عرض الطلبات</span>
    </a>
    
    <a href="{{ route('admin.users.create') }}" class="quick-action fade-in">
        <div class="quick-action-icon">
            <i class="fas fa-user-plus"></i>
        </div>
        <span class="quick-action-text">إضافة مستخدم</span>
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
                    الإيرادات الشهرية
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
                    المبيعات حسب التصنيف
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
                    الطلبات الحديثة
                </h3>
                <a href="{{ route('admin.orders.index') }}" class="view-all-link">
                    عرض الكل
                </a>
            </div>
            <div class="activity-list">
                @forelse($recentOrders as $order)
                    <div class="activity-item">
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-id">طلب #{{ $order->id }}</div>
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
                        <p>لا توجد طلبات حديثة</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Testimonials -->
        <div class="activity-card fade-in">
            <div class="activity-header">
                <h3 class="activity-title">
                    <i class="fas fa-star"></i>
                    الشهادات الحديثة
                    @if($pendingTestimonialsCount > 0)
                        <span class="badge badge-warning">{{ $pendingTestimonialsCount }}</span>
                    @endif
                </h3>
                <a href="{{ route('admin.testimonials.index') }}" class="view-all-link">
                    عرض الكل
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
                                <div class="testimonial-text">{{ $testimonial->comment ?? $testimonial->review }}</div>
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
                        <p>لا توجد شهادات حديثة</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Conversations -->
        <div class="activity-card fade-in">
            <div class="activity-header">
                <h3 class="activity-title">
                    <i class="fas fa-comments"></i>
                    المحادثات الحديثة
                    @if($unreadConversationsCount > 0)
                        <span class="badge badge-warning">{{ $unreadConversationsCount }}</span>
                    @endif
                </h3>
                <a href="{{ route('admin.conversations.index') }}" class="view-all-link">
                    عرض الكل
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
                                        <span class="unread-badge">جديد</span>
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
                        <p>لا توجد محادثات حديثة</p>
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
        // Initialize fade-in animations
        initAnimations();
        
        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: 'الإيرادات',
                    data: @json($monthlyData),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 8,
                    pointHoverRadius: 12,
                    pointHoverBackgroundColor: '#4f46e5',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        cornerRadius: 12,
                        displayColors: false,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return 'الإيرادات:  + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(99, 102, 241, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return ' + value.toLocaleString();
                            },
                            color: '#64748b',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        hoverBackgroundColor: '#4f46e5'
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
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
                        '#06b6d4',
                        '#ec4899',
                        '#84cc16'
                    ],
                    borderWidth: 0,
                    hoverOffset: 8,
                    hoverBorderWidth: 4,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 25,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 13,
                                weight: '600',
                                family: 'Cairo'
                            },
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        cornerRadius: 12,
                        displayColors: true,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%',
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });
        
        // Add loading animations for charts
        setTimeout(() => {
            revenueChart.update('active');
            categoryChart.update('active');
        }, 500);
    });
    
    function initAnimations() {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry, index) {
                if (entry.isIntersecting) {
                    setTimeout(function() {
                        entry.target.classList.add('visible');
                    }, index * 150);
                    observer.unobserve(entry.target);
                }
            });
        }, { 
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        var fadeElements = document.querySelectorAll('.fade-in');
        fadeElements.forEach(function(el, index) {
            observer.observe(el);
        });
    }
    
    // Add hover effects for stat cards
    document.querySelectorAll('.stat-card').forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
    
    // Add click effects for quick actions
    document.querySelectorAll('.quick-action').forEach(function(action) {
        action.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(-3px) scale(0.98)';
        });
        
        action.addEventListener('mouseup', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        action.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
    
    // Smooth scrolling for view all links
    document.querySelectorAll('.view-all-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Add a subtle loading effect
            this.style.transform = 'translateX(5px)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // Add dynamic counting effect for numbers
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const currentValue = Math.floor(progress * (end - start) + start);
            element.innerHTML = currentValue.toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }
    
    // Animate stat numbers when they come into view
    const statObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const numberElement = entry.target.querySelector('.stat-number');
                if (numberElement && !numberElement.classList.contains('animated')) {
                    numberElement.classList.add('animated');
                    const finalValue = parseInt(numberElement.textContent.replace(/[^0-9]/g, ''));
                    numberElement.textContent = '0';
                    setTimeout(() => {
                        animateValue(numberElement, 0, finalValue, 2000);
                    }, 500);
                }
                statObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    document.querySelectorAll('.stat-card').forEach(function(card) {
        statObserver.observe(card);
    });
</script>
@endpush