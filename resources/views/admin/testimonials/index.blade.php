@extends('layouts.admin')

@section('title', 'إدارة الشهادات')
@section('page-title', 'إدارة الشهادات')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        الشهادات
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
    
    .testimonials-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 2rem;
        padding: 2rem;
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }
    
    .testimonials-title {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: flex;
        align-items: center;
        gap: 1rem;
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
        padding: 1.5rem;
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border-radius: 1rem;
        border: 2px solid #e5e7eb;
        min-width: 120px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-item::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.05));
        border-radius: 50%;
        transform: translate(25%, -25%);
    }
    
    .stat-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.25);
        border-color: #3b82f6;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        z-index: 1;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 0.5rem;
        font-weight: 600;
        position: relative;
        z-index: 1;
    }
    
    .testimonials-tabs {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .tabs-header {
        display: flex;
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        border-bottom: 1px solid #e5e7eb;
        position: relative;
    }
    
    .tabs-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
    }
    
    .tab-button {
        flex: 1;
        padding: 1.5rem 2rem;
        border: none;
        background: none;
        font-size: 1rem;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        font-family: 'Cairo', sans-serif;
    }
    
    .tab-button::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 100%;
        background: linear-gradient(135deg, transparent, rgba(59, 130, 246, 0.05));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .tab-button:hover::before {
        opacity: 1;
    }
    
    .tab-button.active {
        color: #3b82f6;
        background: white;
        box-shadow: 0 -5px 10px rgba(59, 130, 246, 0.1);
    }
    
    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        border-radius: 2px 2px 0 0;
    }
    
    .tab-count {
        background: linear-gradient(135deg, #e5e7eb, #d1d5db);
        color: #374151;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        min-width: 24px;
        text-align: center;
        font-weight: 700;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .tab-button.active .tab-count {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }
    
    .tab-content {
        display: none;
        padding: 2.5rem;
        min-height: 400px;
    }
    
    .tab-content.active {
        display: block;
        animation: fadeInUp 0.5s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 2rem;
    }
    
    .testimonial-card {
        background: white;
        border-radius: 1.5rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
        backdrop-filter: blur(10px);
    }
    
    .testimonial-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.05));
        border-radius: 50%;
        transform: translate(30%, -30%);
        transition: all 0.3s ease;
    }
    
    .testimonial-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.25);
        border-color: #3b82f6;
    }
    
    .testimonial-card:hover::before {
        transform: translate(30%, -30%) scale(1.2);
        opacity: 0.8;
    }
    
    .testimonial-header {
        padding: 2rem;
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        border-bottom: 1px solid #e5e7eb;
        position: relative;
        z-index: 1;
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .customer-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
        border: 3px solid white;
    }
    
    .customer-details {
        flex: 1;
    }
    
    .customer-name {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .customer-email {
        color: #6b7280;
        font-size: 0.9rem;
        direction: ltr;
        text-align: left;
    }
    
    .testimonial-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .star {
        color: #fbbf24;
        font-size: 1.2rem;
        transition: all 0.2s ease;
        filter: drop-shadow(0 2px 4px rgba(251, 191, 36, 0.3));
    }
    
    .star.empty {
        color: #d1d5db;
        filter: none;
    }
    
    .star:hover {
        transform: scale(1.1);
        filter: drop-shadow(0 4px 8px rgba(251, 191, 36, 0.5));
    }
    
    .rating-text {
        margin-right: 0.75rem;
        font-size: 0.9rem;
        color: #6b7280;
        font-weight: 600;
    }
    
    .testimonial-content {
        padding: 2rem;
        position: relative;
        z-index: 1;
    }
    
    .testimonial-text {
        color: #374151;
        line-height: 1.8;
        margin-bottom: 1.5rem;
        font-style: italic;
        position: relative;
        padding: 1rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 0.75rem;
        border-right: 3px solid #3b82f6;
        font-size: 0.95rem;
    }
    
    .testimonial-text::before {
        content: '"';
        font-size: 2.5rem;
        color: #cbd5e1;
        position: absolute;
        top: -5px;
        right: 0.5rem;
        font-family: 'Times New Roman', serif;
        opacity: 0.7;
    }
    
    .testimonial-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid #f3f4f6;
        font-size: 0.8rem;
        color: #9ca3af;
        font-weight: 500;
    }
    
    .order-link {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
    }
    
    .order-link:hover {
        color: #1d4ed8;
        background: rgba(59, 130, 246, 0.1);
        transform: translateX(3px);
    }
    
    .testimonial-actions {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        position: relative;
        z-index: 1;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: 2px solid transparent;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        font-family: 'Cairo', sans-serif;
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
    
    .btn-sm {
        padding: 0.6rem 1.25rem;
        font-size: 0.8rem;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        box-shadow: 0 8px 25px 0 rgba(16, 185, 129, 0.6);
        transform: translateY(-2px);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.39);
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        box-shadow: 0 8px 25px 0 rgba(239, 68, 68, 0.6);
        transform: translateY(-2px);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.39);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 8px 25px 0 rgba(59, 130, 246, 0.6);
        transform: translateY(-2px);
    }
    
    .status-badge {
        position: absolute;
        top: 1.5rem;
        left: 1.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        z-index: 2;
        backdrop-filter: blur(10px);
        border: 2px solid;
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
    
    .empty-state {
        text-align: center;
        padding: 4rem;
        color: #6b7280;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 1rem;
        border: 2px dashed #d1d5db;
    }
    
    .empty-icon {
        font-size: 5rem;
        margin-bottom: 2rem;
        opacity: 0.3;
        color: #9ca3af;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #374151;
    }
    
    .empty-text {
        margin-bottom: 1rem;
        color: #6b7280;
        font-size: 1.1rem;
    }
    
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.6;
        transform: none !important;
    }
    
    .btn:disabled:hover {
        transform: none !important;
        box-shadow: none !important;
    }
    
    .custom-notification {
        display: flex;
        align-items: center;
        font-size: 14px;
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 9999;
        max-width: 350px;
        min-width: 280px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-radius: 12px;
        padding: 16px 20px;
        font-weight: 600;
        font-family: 'Cairo', sans-serif;
        direction: rtl;
        text-align: right;
        backdrop-filter: blur(10px);
        border: 2px solid;
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
    
    @keyframes fa-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .fa-spin {
        animation: fa-spin 1s infinite linear;
    }
    
    /* RTL Adjustments */
    .breadcrumb-item i {
        transform: scaleX(-1);
    }
    
    .fas {
        margin-left: 0.5rem;
        margin-right: 0;
    }
    
    .btn .fas {
        margin-left: 0;
        margin-right: 0.5rem;
    }
    
    /* Enhanced animations */
    .testimonial-card {
        animation: slideInUp 0.6s ease-out;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Hover effects for better UX */
    .testimonial-card:hover .customer-avatar {
        transform: scale(1.1);
        box-shadow: 0 15px 30px -5px rgba(59, 130, 246, 0.5);
    }
    
    .testimonial-card:hover .testimonial-text {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border-right-color: #2563eb;
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
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
    }
    
    @media (max-width: 768px) {
        .testimonials-header {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.5rem;
        }
        
        .testimonials-stats {
            width: 100%;
            justify-content: space-between;
        }
        
        .stat-item {
            min-width: auto;
            flex: 1;
            padding: 1rem;
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
        
        .testimonials-title {
            font-size: 1.5rem;
        }
        
        .tab-content {
            padding: 1.5rem;
        }
        
        .testimonial-card {
            margin-bottom: 1rem;
        }
        
        .testimonial-actions {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    /* Loading state styles */
    .loading {
        position: relative;
        overflow: hidden;
    }
    
    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { left: -100%; }
        100% { left: 100%; }
    }
</style>
@endpush

@section('content')
<!-- Testimonials Header -->
<div class="testimonials-header">
    <div>
        <h1 class="testimonials-title">
            <i class="fas fa-star"></i>
            إدارة الشهادات
        </h1>
    </div>
    
    <div class="testimonials-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $pendingTestimonials->count() }}</div>
            <div class="stat-label">في الانتظار</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $approvedTestimonials->count() }}</div>
            <div class="stat-label">موافق عليها</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $rejectedTestimonials->count() }}</div>
            <div class="stat-label">مرفوضة</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $pendingTestimonials->count() + $approvedTestimonials->count() + $rejectedTestimonials->count() }}</div>
            <div class="stat-label">المجموع</div>
        </div>
    </div>
</div>

<!-- Testimonials Tabs -->
<div class="testimonials-tabs">
    <div class="tabs-header">
        <button class="tab-button active" data-tab="pending">
            <i class="fas fa-clock"></i>
            في الانتظار
            <span class="tab-count">{{ $pendingTestimonials->count() }}</span>
        </button>
        
        <button class="tab-button" data-tab="approved">
            <i class="fas fa-check-circle"></i>
            موافق عليها
            <span class="tab-count">{{ $approvedTestimonials->count() }}</span>
        </button>
        
        <button class="tab-button" data-tab="rejected">
            <i class="fas fa-times-circle"></i>
            مرفوضة
            <span class="tab-count">{{ $rejectedTestimonials->count() }}</span>
        </button>
    </div>
    
    <!-- Pending Testimonials Tab -->
    <div class="tab-content active" id="pending">
        @if($pendingTestimonials->count() > 0)
            <div class="testimonials-grid">
                @foreach($pendingTestimonials as $testimonial)
                    <div class="testimonial-card" data-testimonial-id="{{ $testimonial->id }}">
                        <div class="status-badge status-pending">في الانتظار</div>
                        
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
                                <span>{{ $testimonial->created_at->format('d M Y') }}</span>
                                @if($testimonial->order)
                                    <a href="{{ route('admin.orders.show', $testimonial->order) }}" class="order-link">
                                        الطلب #{{ $testimonial->order->id }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="testimonial-actions">
                            <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                                عرض
                            </a>
                            
                            <button class="btn btn-success btn-sm approve-btn" data-testimonial-id="{{ $testimonial->id }}">
                                <i class="fas fa-check"></i>
                                موافقة
                            </button>
                            
                            <button class="btn btn-danger btn-sm reject-btn" data-testimonial-id="{{ $testimonial->id }}">
                                <i class="fas fa-times"></i>
                                رفض
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
                <h3 class="empty-title">لا توجد شهادات في الانتظار</h3>
                <p class="empty-text">تم مراجعة جميع الشهادات.</p>
            </div>
        @endif
    </div>
    
    <!-- Approved Testimonials Tab -->
    <div class="tab-content" id="approved">
        @if($approvedTestimonials->count() > 0)
            <div class="testimonials-grid">
                @foreach($approvedTestimonials as $testimonial)
                    <div class="testimonial-card" data-testimonial-id="{{ $testimonial->id }}">
                        <div class="status-badge status-approved">موافق عليها</div>
                        
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
                                <span>{{ $testimonial->created_at->format('d M Y') }}</span>
                                @if($testimonial->order)
                                    <a href="{{ route('admin.orders.show', $testimonial->order) }}" class="order-link">
                                        الطلب #{{ $testimonial->order->id }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="testimonial-actions">
                            <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                                عرض
                            </a>
                            
                            <button class="btn btn-danger btn-sm reject-btn" data-testimonial-id="{{ $testimonial->id }}">
                                <i class="fas fa-times"></i>
                                رفض
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
                <h3 class="empty-title">لا توجد شهادات موافق عليها</h3>
                <p class="empty-text">لم يتم الموافقة على أي شهادات بعد.</p>
            </div>
        @endif
    </div>
    
    <!-- Rejected Testimonials Tab -->
    <div class="tab-content" id="rejected">
        @if($rejectedTestimonials->count() > 0)
            <div class="testimonials-grid">
                @foreach($rejectedTestimonials as $testimonial)
                    <div class="testimonial-card" data-testimonial-id="{{ $testimonial->id }}">
                        <div class="status-badge status-rejected">مرفوضة</div>
                        
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
                                <span>{{ $testimonial->created_at->format('d M Y') }}</span>
                                @if($testimonial->order)
                                    <a href="{{ route('admin.orders.show', $testimonial->order) }}" class="order-link">
                                        الطلب #{{ $testimonial->order->id }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="testimonial-actions">
                            <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                                عرض
                            </a>
                            
                            <button class="btn btn-success btn-sm approve-btn" data-testimonial-id="{{ $testimonial->id }}">
                                <i class="fas fa-check"></i>
                                موافقة
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
                <h3 class="empty-title">لا توجد شهادات مرفوضة</h3>
                <p class="empty-text">لم يتم رفض أي شهادات.</p>
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
    if (!confirm('هل أنت متأكد من الموافقة على هذه الشهادة؟')) {
        return;
    }
    
    // Show loading
    var originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ الموافقة...';
    button.disabled = true;
    button.classList.add('loading');
    
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
            showNotification('تم الموافقة على الشهادة بنجاح!', 'success');
            // Reload page to update the UI
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error('Network response was not ok');
        }
    })
    .catch(error => {
        showNotification('خطأ في الموافقة على الشهادة', 'error');
        button.innerHTML = originalHTML;
        button.disabled = false;
        button.classList.remove('loading');
    });
}

function handleReject(testimonialId, button) {
    if (!confirm('هل أنت متأكد من رفض هذه الشهادة؟')) {
        return;
    }
    
    // Show loading
    var originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ الرفض...';
    button.disabled = true;
    button.classList.add('loading');
    
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
            showNotification('تم رفض الشهادة بنجاح!', 'success');
            // Reload page to update the UI
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error('Network response was not ok');
        }
    })
    .catch(error => {
        showNotification('خطأ في رفض الشهادة', 'error');
        button.innerHTML = originalHTML;
        button.disabled = false;
        button.classList.remove('loading');
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
    
    notification.innerHTML = '<i class="fas fa-' + iconClass + '" style="margin-left: 12px;"></i>' + message;
    
    // Add animation
    notification.style.animation = 'slideInLeft 0.3s ease-out';
    
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
    }, 4000);
}

// Add slide animations for notifications
var style = document.createElement('style');
style.textContent = `
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
`;
document.head.appendChild(style);

// Enhanced card animations on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry, index) {
        if (entry.isIntersecting) {
            setTimeout(function() {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }, index * 100);
        }
    });
}, observerOptions);

// Observe all testimonial cards
document.querySelectorAll('.testimonial-card').forEach(function(card, index) {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    card.style.transition = 'all 0.6s ease-out';
    observer.observe(card);
});
</script>
@endpush