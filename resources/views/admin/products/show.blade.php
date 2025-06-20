@extends('layouts.admin')

@section('title', 'تفاصيل المنتج')
@section('page-title', 'تفاصيل المنتج')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        <a href="{{ route('admin.products.index') }}" class="breadcrumb-link">المنتجات</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        {{ $product->name }}
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
    }
    
    .product-show-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .product-header {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
        position: relative;
    }
    
    .product-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 200px;
        height: 200px;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.05));
        border-radius: 50%;
        transform: translate(-50%, -50%);
    }
    
    .product-hero {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 3rem;
        padding: 3rem;
        position: relative;
        z-index: 1;
    }
    
    .product-images-section {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .main-image-container {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .main-image-container:hover {
        transform: scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .main-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
    
    .no-main-image {
        width: 100%;
        height: 300px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 4rem;
    }
    
    .image-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);
    }
    
    .thumbnail-images {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        padding: 0.5rem;
        scrollbar-width: thin;
        scrollbar-color: #3b82f6 #f1f5f9;
    }
    
    .thumbnail-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.75rem;
        border: 3px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .thumbnail-image:hover {
        border-color: #93c5fd;
        transform: scale(1.1);
    }
    
    .thumbnail-image.active {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
    }
    
    .product-info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .product-main-info {
        flex: 1;
    }
    
    .product-name {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #0f172a, #334155);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    
    .product-category-badge {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1d4ed8;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 2rem;
        display: inline-block;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid #3b82f6;
    }
    
    .product-category-badge:hover {
        background: linear-gradient(135deg, #bfdbfe, #93c5fd);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
    }
    
    .product-description {
        color: #64748b;
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 1.5rem;
        border-radius: 1rem;
        border-right: 4px solid #3b82f6;
    }
    
    .product-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-item {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
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
    
    .stat-value {
        font-size: 1.8rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }
    
    .stat-value.price {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-value.stock {
        background: linear-gradient(135deg, #10b981, #059669);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-value.stock.low {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-value.stock.out {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        position: relative;
        z-index: 1;
    }
    
    .status-indicators {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        gap: 0.5rem;
        backdrop-filter: blur(10px);
        border: 2px solid;
    }
    
    .status-active {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border-color: #10b981;
        box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.3);
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-color: #ef4444;
        box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.3);
    }
    
    .stock-status {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border-color: #10b981;
        box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.3);
    }
    
    .stock-status.low {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border-color: #f59e0b;
        box-shadow: 0 4px 14px 0 rgba(245, 158, 11, 0.3);
    }
    
    .stock-status.out {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-color: #ef4444;
        box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.3);
    }
    
    .product-actions {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 2rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        position: relative;
    }
    
    .product-actions::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
    }
    
    .action-group {
        display: flex;
        gap: 1rem;
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    
    .main-content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .sidebar-content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .info-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.35);
    }
    
    .section-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }
    
    .section-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .info-list {
        padding: 1.5rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
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
        font-weight: 600;
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .info-value {
        color: #0f172a;
        font-size: 0.9rem;
        text-align: left;
        direction: ltr;
        font-weight: 700;
    }
    
    .images-gallery {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .images-gallery:hover {
        transform: translateY(-5px);
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.35);
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1.5rem;
        padding: 1.5rem;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        cursor: pointer;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .gallery-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    
    .gallery-info {
        padding: 0.75rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        font-size: 0.8rem;
        color: #64748b;
        text-align: center;
        font-weight: 600;
    }
    
    .primary-indicator {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 4px 8px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    }
    
    .quick-actions {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .quick-actions:hover {
        transform: translateY(-5px);
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.35);
    }
    
    .quick-actions-list {
        padding: 1.5rem;
    }
    
    .quick-action {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        text-decoration: none;
        color: #374151;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        margin-bottom: 0.5rem;
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
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.05));
        border-radius: 50%;
        transform: translate(30%, -30%);
        transition: all 0.3s ease;
        opacity: 0;
    }
    
    .quick-action:hover {
        background: linear-gradient(135deg, #f8fafc, rgba(59, 130, 246, 0.05));
        color: #0f172a;
        transform: translateX(-8px);
        padding-right: 1.5rem;
    }
    
    .quick-action:hover::before {
        opacity: 1;
        transform: translate(30%, -30%) scale(1.2);
    }
    
    .quick-action:last-child {
        margin-bottom: 0;
    }
    
    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }
    
    .action-icon.edit {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
    }
    
    .action-icon.duplicate {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1d4ed8;
    }
    
    .action-icon.delete {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }
    
    .action-text {
        flex: 1;
        position: relative;
        z-index: 1;
    }
    
    .action-title {
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 1rem;
    }
    
    .action-subtitle {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }
    
    .empty-gallery {
        text-align: center;
        padding: 3rem;
        color: #64748b;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 1rem;
        border: 2px dashed #d1d5db;
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        border: 2px solid transparent;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        text-align: center;
        position: relative;
        overflow: hidden;
        font-family: 'Cairo', sans-serif;
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
    
    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.4);
    }
    
    .btn-warning:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        box-shadow: 0 20px 25px -5px rgba(245, 158, 11, 0.6);
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
    
    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }
    
    .modal-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    
    .modal-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .modal-close {
        position: absolute;
        top: 1rem;
        left: 1rem;
        width: 50px;
        height: 50px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .modal-close:hover {
        background: rgba(0, 0, 0, 0.9);
        transform: scale(1.1);
    }
    
    /* RTL Adjustments */
    .fas {
        margin-left: 0.5rem;
        margin-right: 0;
    }
    
    .btn .fas {
        margin-left: 0;
        margin-right: 0.75rem;
    }
    
    .section-title .fas {
        margin-left: 0;
        margin-right: 0.75rem;
    }
    
    .product-category-badge .fas {
        margin-left: 0;
        margin-right: 0.5rem;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
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
    
    /* Animations */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease-out;
    }
    
    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    @media (max-width: 1024px) {
        .product-hero {
            grid-template-columns: 1fr;
            text-align: center;
        }
        
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .product-show-container {
            margin: 0;
        }
        
        .product-hero {
            padding: 2rem;
        }
        
        .product-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .action-group {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .product-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .thumbnail-images {
            justify-content: center;
        }
        
        .product-name {
            font-size: 2rem;
        }
        
        .status-indicators {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="product-show-container">
    <!-- Product Header -->
    <div class="product-header fade-in">
        <div class="product-hero">
            <div class="product-images-section">
                <div class="main-image-container">
                    @if($product->images && $product->images->count() > 0)
                        @php $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first(); @endphp
                        <img src="{{ Storage::url($primaryImage->image_path) }}" alt="{{ $product->name }}" class="main-image" id="mainImage">
                        <div class="image-badge">أساسية</div>
                    @elseif($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="main-image" id="mainImage">
                        <div class="image-badge">رئيسية</div>
                    @else
                        <div class="no-main-image" id="mainImage">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </div>
                
                @if($product->images && $product->images->count() > 1)
                    <div class="thumbnail-images">
                        @foreach($product->images->sortBy('sort_order') as $image)
                            <img src="{{ Storage::url($image->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="thumbnail-image {{ $loop->first ? 'active' : '' }}"
                                 onclick="changeMainImage('{{ Storage::url($image->image_path) }}', this)">
                        @endforeach
                    </div>
                @endif
            </div>
            
            <div class="product-info">
                <div class="product-main-info">
                    <h1 class="product-name">{{ $product->name }}</h1>
                    
                    @if($product->category)
                        <a href="{{ route('admin.categories.show', $product->category) }}" class="product-category-badge">
                            <i class="fas fa-tag"></i>
                            {{ $product->category->name }}
                        </a>
                    @endif
                    
                    <p class="product-description">{{ $product->description }}</p>
                    
                    <div class="product-stats">
                        <div class="stat-item">
                            <div class="stat-value price">${{ number_format($product->price, 2) }}</div>
                            <div class="stat-label">السعر</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value stock {{ $product->stock <= 0 ? 'out' : ($product->stock <= 5 ? 'low' : '') }}">
                                {{ $product->stock }}
                            </div>
                            <div class="stat-label">المخزون</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value">{{ $product->images ? $product->images->count() : ($product->image ? 1 : 0) }}</div>
                            <div class="stat-label">الصور</div>
                        </div>
                    </div>
                </div>
                
                <div class="status-indicators">
                    <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                        <i class="fas fa-{{ $product->is_active ? 'check' : 'times' }}"></i>
                        {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                    
                    <span class="status-badge stock-status {{ $product->stock <= 0 ? 'out' : ($product->stock <= 5 ? 'low' : '') }}">
                        <i class="fas fa-{{ $product->stock <= 0 ? 'exclamation-triangle' : ($product->stock <= 5 ? 'exclamation' : 'check') }}"></i>
                        @if($product->stock <= 0)
                            نفد المخزون
                        @elseif($product->stock <= 5)
                            مخزون منخفض
                        @else
                            متوفر
                        @endif
                    </span>
                </div>
            </div>
        </div>
        
        <div class="product-actions">
            <div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i>
                    العودة للمنتجات
                </a>
            </div>
            
            <div class="action-group">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    تعديل المنتج
                </a>
                
                <button class="btn btn-primary" onclick="duplicateProduct()">
                    <i class="fas fa-copy"></i>
                    نسخ
                </button>
                
                <button class="btn btn-danger" onclick="deleteProduct()">
                    <i class="fas fa-trash"></i>
                    حذف
                </button>
            </div>
        </div>
    </div>
    
    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Product Images Gallery -->
            @if(($product->images && $product->images->count() > 0) || $product->image)
                <div class="images-gallery fade-in">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-images"></i>
                            معرض صور المنتج
                        </h2>
                    </div>
                    
                    <div class="gallery-grid">
                        @if($product->images && $product->images->count() > 0)
                            @foreach($product->images->sortBy('sort_order') as $image)
                                <div class="gallery-item" onclick="openImageModal('{{ Storage::url($image->image_path) }}')">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $product->name }}" class="gallery-image">
                                    @if($image->is_primary)
                                        <div class="primary-indicator">أساسية</div>
                                    @endif
                                    <div class="gallery-info">
                                        صورة {{ $loop->iteration }}
                                        @if($image->is_primary) - أساسية @endif
                                    </div>
                                </div>
                            @endforeach
                        @elseif($product->image)
                            <div class="gallery-item" onclick="openImageModal('{{ Storage::url($product->image) }}')">
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="gallery-image">
                                <div class="primary-indicator">رئيسية</div>
                                <div class="gallery-info">صورة المنتج</div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="images-gallery fade-in">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-images"></i>
                            معرض صور المنتج
                        </h2>
                    </div>
                    
                    <div class="empty-gallery">
                        <div class="empty-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <h3>لا توجد صور متاحة</h3>
                        <p>هذا المنتج لا يحتوي على أي صور بعد.</p>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            إضافة صور
                        </a>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="sidebar-content">
            <!-- Product Information -->
            <div class="info-card fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        معلومات المنتج
                    </h3>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">معرف المنتج</span>
                        <span class="info-value">#{{ $product->id }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">التصنيف</span>
                        <span class="info-value">
                            @if($product->category)
                                <a href="{{ route('admin.categories.show', $product->category) }}" style="color: #3b82f6; text-decoration: none;">
                                    {{ $product->category->name }}
                                </a>
                            @else
                                <span style="color: #9ca3af;">بدون تصنيف</span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">الحالة</span>
                        <span class="info-value">
                            <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">تاريخ الإنشاء</span>
                        <span class="info-value">{{ $product->created_at->format('d M Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">آخر تحديث</span>
                        <span class="info-value">{{ $product->updated_at->format('d M Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">إجمالي الصور</span>
                        <span class="info-value">{{ $product->images ? $product->images->count() : ($product->image ? 1 : 0) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions fade-in">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-bolt"></i>
                        إجراءات سريعة
                    </h3>
                </div>
                
                <div class="quick-actions-list">
                    <a href="{{ route('admin.products.edit', $product) }}" class="quick-action">
                        <div class="action-icon edit">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">تعديل المنتج</div>
                            <div class="action-subtitle">تحديث معلومات المنتج</div>
                        </div>
                    </a>
                    
                    <a href="#" onclick="duplicateProduct(); return false;" class="quick-action">
                        <div class="action-icon duplicate">
                            <i class="fas fa-copy"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">نسخ المنتج</div>
                            <div class="action-subtitle">إنشاء نسخة من هذا المنتج</div>
                        </div>
                    </a>
                    
                    <a href="#" onclick="deleteProduct(); return false;" class="quick-action">
                        <div class="action-icon delete">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="action-text">
                            <div class="action-title">حذف المنتج</div>
                            <div class="action-subtitle">إزالة هذا المنتج نهائياً</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <img id="modalImage" class="modal-image" src="" alt="">
        <button class="modal-close" onclick="closeImageModal()">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 1.5rem; max-width: 400px; width: 90%; text-align: center; font-family: 'Cairo', sans-serif; direction: rtl;">
        <div style="color: #ef4444; font-size: 3rem; margin-bottom: 1.5rem;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: 1rem; color: #0f172a;">حذف المنتج</h3>
        <p style="margin-bottom: 1rem; color: #64748b;">
            هل أنت متأكد من حذف <strong>"{{ $product->name }}"</strong>؟
        </p>
        <p style="margin-bottom: 2rem; color: #64748b;">
            لا يمكن التراجع عن هذا الإجراء. سيتم حذف جميع بيانات المنتج والصور نهائياً.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">إلغاء</button>
            <button onclick="confirmDelete()" class="btn btn-danger">حذف</button>
        </div>
    </div>
</div>

<!-- Duplicate Confirmation Modal -->
<div id="duplicateModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 1.5rem; max-width: 400px; width: 90%; text-align: center; font-family: 'Cairo', sans-serif; direction: rtl;">
        <div style="color: #3b82f6; font-size: 3rem; margin-bottom: 1.5rem;">
            <i class="fas fa-copy"></i>
        </div>
        <h3 style="margin-bottom: 1rem; color: #0f172a;">نسخ المنتج</h3>
        <p style="margin-bottom: 2rem; color: #64748b;">
            سيتم إنشاء نسخة من <strong>"{{ $product->name }}"</strong> مع جميع معلوماته وصوره.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button onclick="closeDuplicateModal()" class="btn btn-secondary">إلغاء</button>
            <button onclick="confirmDuplicate()" class="btn btn-primary">نسخ</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Change main image
    function changeMainImage(imageSrc, thumbnail) {
        document.getElementById('mainImage').src = imageSrc;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail-image').forEach(img => {
            img.classList.remove('active');
        });
        thumbnail.classList.add('active');
    }
    
    // Image modal functions
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Delete product functions
    function deleteProduct() {
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    function confirmDelete() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.products.destroy", $product) }}';
        
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
    
    // Duplicate product functions
    function duplicateProduct() {
        document.getElementById('duplicateModal').style.display = 'flex';
    }
    
    function closeDuplicateModal() {
        document.getElementById('duplicateModal').style.display = 'none';
    }
    
    function confirmDuplicate() {
        // Create a form to submit to create route with product data
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("admin.products.create") }}';
        
        const duplicateInput = document.createElement('input');
        duplicateInput.type = 'hidden';
        duplicateInput.name = 'duplicate';
        duplicateInput.value = '{{ $product->id }}';
        
        form.appendChild(duplicateInput);
        document.body.appendChild(form);
        form.submit();
    }
    
    // Close modals when clicking outside
    ['deleteModal', 'duplicateModal'].forEach(modalId => {
        document.getElementById(modalId).addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
            closeDeleteModal();
            closeDuplicateModal();
        }
    });
    
    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 150);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
    });
</script>
@endpush