@extends('layouts.admin')

@section('title', 'تفاصيل الشهادة')
@section('page-title', 'تفاصيل الشهادة')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        <a href="{{ route('admin.testimonials.index') }}" class="breadcrumb-link">الشهادات</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        تفاصيل الشهادة
    </div>
@endsection

@push('styles')
<style>
    * {
        direction: rtl;
        text-align: right;
    }
    
    body {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .testimonial-header {
        background: linear-gradient(135deg, #ffffff, #f8feff);
        border-radius: 1.5rem;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        position: relative;
        overflow: hidden;
    }
    
    .testimonial-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.05));
        border-radius: 50%;
        transform: translate(50%, -50%);
    }
    
    .testimonial-title {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
        z-index: 1;
    }
    
    .testimonial-info {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .testimonial-id {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .testimonial-date {
        color: #64748b;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }
    
    .testimonial-actions {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        flex-wrap: wrap;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        gap: 0.5rem;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
        border: 2px solid;
        position: relative;
        z-index: 1;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border-color: #f59e0b;
        box-shadow: 0 4px 14px 0 rgba(245, 158, 11, 0.3);
    }
    
    .status-approved {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border-color: #10b981;
        box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.3);
    }
    
    .status-rejected {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-color: #ef4444;
        box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.3);
    }
    
    .testimonial-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .testimonial-content-section {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .testimonial-content-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.35);
    }
    
    .section-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 2rem;
        border-bottom: 1px solid #e2e8f0;
        position: relative;
    }
    
    .section-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }
    
    .content-body {
        padding: 2.5rem;
    }
    
    .testimonial-text {
        font-size: 1.2rem;
        line-height: 2;
        color: #334155;
        font-style: italic;
        position: relative;
        margin: 2rem 0;
        padding: 2.5rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 1rem;
        border-right: 5px solid #3b82f6;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .testimonial-text::before {
        content: '"';
        font-size: 5rem;
        color: #93c5fd;
        position: absolute;
        top: -10px;
        right: 1rem;
        font-family: 'Times New Roman', serif;
        line-height: 1;
        opacity: 0.7;
    }
    
    .testimonial-text::after {
        content: '"';
        font-size: 5rem;
        color: #93c5fd;
        position: absolute;
        bottom: -30px;
        left: 1rem;
        font-family: 'Times New Roman', serif;
        line-height: 1;
        opacity: 0.7;
    }
    
    .rating-section {
        background: linear-gradient(135deg, #f8fafc, #eff6ff);
        border-radius: 1rem;
        padding: 2rem;
        margin: 2rem 0;
        text-align: center;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e7ff;
    }
    
    .rating-stars {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .star {
        font-size: 2.5rem;
        color: #fbbf24;
        transition: all 0.3s ease;
        filter: drop-shadow(0 2px 4px rgba(251, 191, 36, 0.5));
    }
    
    .star.empty {
        color: #cbd5e1;
        filter: none;
    }
    
    .star:hover {
        transform: scale(1.2) rotate(15deg);
        filter: drop-shadow(0 4px 8px rgba(251, 191, 36, 0.7));
    }
    
    .rating-text {
        font-size: 1.4rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    
    .rating-subtitle {
        font-size: 0.95rem;
        color: #64748b;
        font-weight: 500;
    }
    
    .testimonial-sidebar {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid #f1f5f9;
        height: fit-content;
        transition: all 0.3s ease;
    }
    
    .testimonial-sidebar:hover {
        transform: translateY(-5px);
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.35);
    }
    
    .sidebar-content {
        padding: 2rem;
    }
    
    .customer-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 2.5rem;
        background: linear-gradient(135deg, #f8fafc, #eff6ff);
        border-radius: 1rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .customer-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.05));
        border-radius: 50%;
        transform: translate(30%, -30%);
    }
    
    .customer-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 4px solid white;
        position: relative;
        z-index: 1;
    }
    
    .customer-name {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }
    
    .customer-email {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }
    
    .customer-role {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1d4ed8;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        z-index: 1;
        border: 2px solid #3b82f6;
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
        padding: 1.5rem 0;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }
    
    .info-item:hover {
        padding-right: 0.5rem;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.05));
        border-radius: 0.5rem;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        color: #64748b;
        font-size: 0.95rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-value {
        font-weight: 700;
        color: #0f172a;
        text-align: left;
    }
    
    .order-link {
        color: #2563eb;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        border-radius: 0.5rem;
    }
    
    .order-link:hover {
        color: #1d4ed8;
        background: rgba(37, 99, 235, 0.1);
        transform: translateX(-5px);
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1.25rem 2rem;
        border: 2px solid transparent;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn:hover::before {
        left: 100%;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.6);
        transform: translateY(-3px);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.6);
        transform: translateY(-3px);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4);
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        box-shadow: 0 20px 25px -5px rgba(239, 68, 68, 0.6);
        transform: translateY(-3px);
    }
    
    .btn-secondary {
        background: white;
        color: #374151;
        border-color: #d1d5db;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        transform: translateY(-2px);
    }
    
    .timeline-section {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        margin-top: 2rem;
    }
    
    .timeline-item {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 2rem;
        border-bottom: 1px solid #f1f5f9;
        position: relative;
        transition: all 0.2s ease;
    }
    
    .timeline-item:hover {
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.05));
    }
    
    .timeline-item:last-child {
        border-bottom: none;
    }
    
    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 35px;
        bottom: -1px;
        width: 2px;
        height: 20px;
        background: #e2e8f0;
    }
    
    .timeline-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 3px solid white;
    }
    
    .timeline-content {
        flex: 1;
    }
    
    .timeline-title {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .timeline-time {
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .icon-submitted {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #3b82f6;
    }
    
    .icon-approved {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #059669;
    }
    
    .icon-rejected {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
    }
    
    .related-products {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 1rem;
        padding: 2rem;
        margin-top: 2rem;
        border: 1px solid #e2e8f0;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .product-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .product-placeholder {
        width: 100%;
        height: 120px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        margin-bottom: 1rem;
        font-size: 2rem;
    }
    
    .product-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }
    
    .product-price {
        color: #3b82f6;
        font-weight: 800;
        font-size: 1.1rem;
    }
    
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease-out;
    }
    
    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* RTL Adjustments */
    .breadcrumb-item i {
        transform: scaleX(-1);
    }
    
    .info-value {
        text-align: left;
        direction: ltr;
    }
    
    .order-link:hover {
        transform: translateX(5px);
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
            padding: 2rem;
        }
        
        .customer-avatar {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }
        
        .testimonial-text {
            font-size: 1.1rem;
            padding: 2rem;
        }
        
        .testimonial-text::before,
        .testimonial-text::after {
            font-size: 4rem;
        }
        
        .star {
            font-size: 2rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .products-grid {
            grid-template-columns: 1fr;
        }
        
        .testimonial-header {
            padding: 2rem;
        }
        
        .content-body {
            padding: 2rem;
        }
    }
</style>

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
@endpush
@endpush

@section('content')
<!-- Testimonial Header -->
<div class="testimonial-header fade-in">
    <div class="testimonial-title">
        <div class="testimonial-info">
            <h1 class="testimonial-id">
                <i class="fas fa-star"></i>
                الشهادة رقم #{{ $testimonial->id }}
            </h1>
            <div class="testimonial-date">
                <i class="fas fa-calendar-alt"></i>
                تم إرسالها في {{ $testimonial->created_at->format('d F Y \ا\ل\س\ا\ع\ة g:i A') }}
            </div>
        </div>
        
        <div class="testimonial-actions">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i>
                العودة للشهادات
            </a>
            
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i>
                طباعة
            </button>
        </div>
    </div>
    
    <span class="status-badge status-{{ $testimonial->status }}">
        <i class="fas fa-{{ $testimonial->status === 'pending' ? 'clock' : ($testimonial->status === 'approved' ? 'check' : 'times') }}"></i>
        {{ $testimonial->status === 'pending' ? 'في الانتظار' : ($testimonial->status === 'approved' ? 'مُوافق عليها' : 'مرفوضة') }}
    </span>
</div>

<!-- Testimonial Content Grid -->
<div class="testimonial-grid">
    <!-- Main Content -->
    <div class="testimonial-content-section fade-in">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-comment-alt"></i>
                شهادة العميل
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
                <div class="rating-text">{{ $testimonial->rating }} من أصل 5 نجوم</div>
                <div class="rating-subtitle">تقييم العميل</div>
            </div>
            
            <!-- Testimonial Text -->
            <div class="testimonial-text">
                {{ $testimonial->comment }}
            </div>
            
            <!-- Related Products -->
            @if($testimonial->order && $testimonial->order->orderItems->count() > 0)
                <div class="related-products">
                    <h4 style="margin: 0 0 1rem 0; color: #0f172a; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-shopping-bag"></i>
                        المنتجات من هذا الطلب
                    </h4>
                    
                    <div class="products-grid">
                        @foreach($testimonial->order->orderItems->take(4) as $orderItem)
                            <div class="product-card">
                                @if($orderItem->product && $orderItem->product->image)
                                    <img src="{{ asset('storage/' . $orderItem->product->image) }}" alt="{{ $orderItem->product->name }}" class="product-image" style="width: 100%; height: 120px; object-fit: cover; border-radius: 0.75rem; margin-bottom: 1rem;">
                                @else
                                    <div class="product-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <div class="product-name">{{ $orderItem->product->name ?? 'منتج' }}</div>
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
                معلومات العميل
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
                <div class="customer-role">عميل</div>
            </div>
            
            <!-- Information List -->
            <ul class="info-list">
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-envelope"></i>
                        البريد الإلكتروني
                    </span>
                    <span class="info-value">{{ $testimonial->user->email }}</span>
                </li>
                
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-calendar-plus"></i>
                        تاريخ التسجيل
                    </span>
                    <span class="info-value">{{ $testimonial->user->created_at->format('M Y') }}</span>
                </li>
                
                @if($testimonial->order)
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-shopping-cart"></i>
                            الطلب
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
                            قيمة الطلب
                        </span>
                        <span class="info-value">${{ number_format($testimonial->order->total_amount, 2) }}</span>
                    </li>
                    
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-truck"></i>
                            حالة الطلب
                        </span>
                        <span class="info-value">{{ $testimonial->order->status === 'pending' ? 'في الانتظار' : ($testimonial->order->status === 'completed' ? 'مكتمل' : ($testimonial->order->status === 'cancelled' ? 'ملغي' : ucfirst($testimonial->order->status))) }}</span>
                    </li>
                @endif
                
                <li class="info-item">
                    <span class="info-label">
                        <i class="fas fa-clock"></i>
                        تم الإرسال
                    </span>
                    <span class="info-value">{{ $testimonial->created_at->diffForHumans() }}</span>
                </li>
            </ul>
            
            <!-- Action Buttons -->
            @if($testimonial->status === 'pending')
                <div class="action-buttons">
                    <button type="button" class="btn btn-success" id="approveBtn">
                        <i class="fas fa-check"></i>
                        الموافقة على الشهادة
                    </button>
                    
                    <button type="button" class="btn btn-danger" id="rejectBtn">
                        <i class="fas fa-times"></i>
                        رفض الشهادة
                    </button>
                </div>
            @endif
            
            <!-- Delete Button -->
            <div class="action-buttons" style="margin-top: 1rem;">
                <button type="button" class="btn btn-danger" id="deleteBtn">
                    <i class="fas fa-trash"></i>
                    حذف الشهادة
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
            تاريخ الشهادة
        </h3>
    </div>
    
    <div class="timeline-item">
        <div class="timeline-icon icon-submitted">
            <i class="fas fa-plus"></i>
        </div>
        <div class="timeline-content">
            <div class="timeline-title">تم إرسال الشهادة</div>
            <div class="timeline-time">{{ $testimonial->created_at->format('d F Y \ا\ل\س\ا\ع\ة g:i A') }}</div>
        </div>
    </div>
    
    @if($testimonial->status !== 'pending')
        <div class="timeline-item">
            <div class="timeline-icon icon-{{ $testimonial->status }}">
                <i class="fas fa-{{ $testimonial->status === 'approved' ? 'check' : 'times' }}"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-title">تم {{ $testimonial->status === 'approved' ? 'الموافقة على' : 'رفض' }} الشهادة</div>
                <div class="timeline-time">{{ $testimonial->updated_at->format('d F Y \ا\ل\س\ا\ع\ة g:i A') }}</div>
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
        if (confirm('هل أنت متأكد من الموافقة على هذه الشهادة؟')) {
            var button = document.getElementById('approveBtn');
            if (!button) return;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ الموافقة...';
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
                    showNotification('تم الموافقة على الشهادة بنجاح!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                showNotification('خطأ في الموافقة على الشهادة', 'error');
                button.innerHTML = '<i class="fas fa-check"></i> الموافقة';
                button.disabled = false;
            });
        }
    }
    
    function rejectTestimonial() {
        if (confirm('هل أنت متأكد من رفض هذه الشهادة؟')) {
            var button = document.getElementById('rejectBtn');
            if (!button) return;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ الرفض...';
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
                    showNotification('تم رفض الشهادة بنجاح!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                showNotification('خطأ في رفض الشهادة', 'error');
                button.innerHTML = '<i class="fas fa-times"></i> رفض';
                button.disabled = false;
            });
        }
    }
    
    function deleteTestimonial() {
        if (confirm('هل أنت متأكد من حذف هذه الشهادة؟ لا يمكن التراجع عن هذا الإجراء.')) {
            var button = document.getElementById('deleteBtn');
            if (!button) return;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ الحذف...';
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
            left: 20px;
            z-index: 9999;
            max-width: 300px;
            animation: slideInLeft 0.3s ease-out;
            padding: 16px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            text-align: right;
        `;
        
        var iconClass = type === 'success' ? 'check-circle' : 
                       type === 'error' ? 'exclamation-circle' : 'info-circle';
        
        notification.innerHTML = '<i class="fas fa-' + iconClass + '"></i>' + message;
        
        document.body.appendChild(notification);
        
        setTimeout(function() {
            if (document.body.contains(notification)) {
                notification.style.animation = 'slideOutLeft 0.3s ease-out forwards';
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
                    }, index * 150);
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
            this.style.transform = 'scale(1.2) rotate(15deg)';
            this.style.filter = 'drop-shadow(0 4px 8px rgba(251, 191, 36, 0.7))';
        });
        
        star.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.filter = this.classList.contains('empty') ? 'none' : 'drop-shadow(0 2px 4px rgba(251, 191, 36, 0.5))';
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
    @keyframes slideInLeft {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutLeft {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(-100%);
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
        padding: 16px 20px;
        border-radius: 12px;
        border: 2px solid;
        display: flex;
        align-items: center;
        gap: 12px;
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .alert-success {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border-color: #22c55e;
    }
    
    .alert-error {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        color: #991b1b;
        border-color: #ef4444;
    }
    
    .alert-info {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #1e40af;
        border-color: #3b82f6;
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
            direction: rtl;
        }
        
        * {
            box-shadow: none !important;
            background: white !important;
        }
    }
    
    /* Additional RTL improvements */
    .fas {
        margin-left: 0.5rem;
        margin-right: 0;
    }
    
    .btn .fas {
        margin-left: 0;
        margin-right: 0.5rem;
    }
    
    /* Enhanced hover effects */
    .testimonial-card:hover,
    .testimonial-content-section:hover,
    .testimonial-sidebar:hover {
        box-shadow: 0 35px 60px -12px rgba(59, 130, 246, 0.25);
    }
    
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* Custom scrollbar for RTL */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
    }
</style>
@endpush