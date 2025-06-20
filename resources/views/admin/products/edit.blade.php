@extends('layouts.admin')

@section('title', 'تعديل المنتج')
@section('page-title', 'تعديل المنتج')

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
        تعديل - {{ $product->name }}
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
    
    .edit-product-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .form-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        position: relative;
    }
    
    .form-header {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .form-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(-50%, -50%);
    }
    
    .form-title {
        font-size: 2rem;
        font-weight: 900;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        position: relative;
        z-index: 1;
    }
    
    .form-subtitle {
        margin: 1rem 0 0 0;
        opacity: 0.9;
        font-size: 1rem;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }
    
    .form-body {
        padding: 3rem;
    }
    
    .form-section {
        margin-bottom: 3rem;
    }
    
    .section-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        padding-bottom: 1rem;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #f59e0b, #d97706);
        border-radius: 2px;
    }
    
    .form-grid {
        display: grid;
        gap: 2rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    
    .form-row.single {
        grid-template-columns: 1fr;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-label {
        font-size: 1rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
    }
    
    .required {
        color: #ef4444;
        font-weight: 900;
    }
    
    .change-indicator {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        margin-right: 0.75rem;
        display: none;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
    }
    
    .form-input,
    .form-textarea,
    .form-select {
        padding: 1rem 1.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        font-size: 1rem;
        font-family: 'Cairo', sans-serif;
        transition: all 0.3s ease;
        background: white;
        font-weight: 500;
    }
    
    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        transform: translateY(-2px);
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 140px;
        line-height: 1.6;
    }
    
    .form-help {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.5rem;
        font-weight: 500;
    }
    
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 1rem;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .checkbox-group:hover {
        border-color: #f59e0b;
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
    }
    
    .checkbox-input {
        width: 24px;
        height: 24px;
        accent-color: #f59e0b;
    }
    
    .checkbox-label {
        font-size: 1rem;
        color: #374151;
        margin: 0;
        font-weight: 600;
    }
    
    .current-images {
        margin-bottom: 2rem;
    }
    
    .current-images-title {
        font-size: 1rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .current-images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.5rem;
    }
    
    .current-image-item {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .current-image-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #f59e0b;
    }
    
    .current-image {
        width: 100%;
        height: 140px;
        object-fit: cover;
    }
    
    .current-image-controls {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        display: flex;
        gap: 0.5rem;
    }
    
    .current-image-info {
        padding: 1rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        font-size: 0.85rem;
        color: #64748b;
        text-align: center;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }
    
    .primary-badge {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    }
    
    .delete-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .delete-checkbox input {
        width: 16px;
        height: 16px;
        accent-color: #ef4444;
    }
    
    .delete-checkbox label {
        font-size: 0.8rem;
        color: #ef4444;
        font-weight: 600;
        margin: 0;
    }
    
    .images-upload-area {
        border: 3px dashed #cbd5e1;
        border-radius: 1.5rem;
        padding: 3rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }
    
    .images-upload-area:hover {
        border-color: #f59e0b;
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        transform: scale(1.02);
    }
    
    .images-upload-area.dragover {
        border-color: #d97706;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        transform: scale(1.05);
        box-shadow: 0 20px 25px -5px rgba(245, 158, 11, 0.25);
    }
    
    .upload-icon {
        font-size: 4rem;
        color: #94a3b8;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .images-upload-area:hover .upload-icon {
        color: #f59e0b;
        transform: scale(1.1);
    }
    
    .upload-text {
        color: #374151;
        margin-bottom: 1rem;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .upload-hint {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
    }
    
    .new-images-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .new-image-preview-item {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .new-image-preview-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #f59e0b;
    }
    
    .new-preview-image {
        width: 100%;
        height: 140px;
        object-fit: cover;
    }
    
    .new-image-controls {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        display: flex;
        gap: 0.5rem;
    }
    
    .image-control-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        color: white;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .btn-remove-image {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    
    .btn-remove-image:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: scale(1.1);
    }
    
    .new-image-info {
        padding: 1rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        font-size: 0.85rem;
        color: #64748b;
        text-align: center;
        font-weight: 600;
    }
    
    .price-input-group {
        position: relative;
    }
    
    .price-currency {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .price-input {
        padding-right: 3rem;
    }
    
    .stock-input-group {
        position: relative;
    }
    
    .stock-unit {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 1rem;
        font-weight: 600;
    }
    
    .stock-input {
        padding-left: 4rem;
    }
    
    .form-actions {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 2rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        position: relative;
    }
    
    .form-actions::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #f59e0b, #d97706, #ea580c);
    }
    
    .btn-group {
        display: flex;
        gap: 1rem;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
    }
    
    .form-input.error,
    .form-textarea.error,
    .form-select.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        border: 2px solid transparent;
        border-radius: 50px;
        font-size: 1rem;
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
    
    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        box-shadow: 0 20px 25px -5p rgba(16, 185, 129, 0.6);
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
        margin-right: 1rem;
    }
    
    .form-label .fas {
        margin-left: 0;
        margin-right: 0.5rem;
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
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
    }
    
    @media (max-width: 768px) {
        .edit-product-container {
            margin: 0;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .current-images-grid,
        .new-images-preview {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        }
        
        .form-body {
            padding: 2rem;
        }
        
        .form-header {
            padding: 2rem;
        }
        
        .form-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="edit-product-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-edit"></i>
                تعديل المنتج
            </h1>
            <p class="form-subtitle">تحديث معلومات المنتج وإعداداته</p>
        </div>
        
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')
            
            <div class="form-body">
                <!-- Basic Information -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        المعلومات الأساسية
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    اسم المنتج
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="nameChangeIndicator">معدّل</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    class="form-input @error('name') error @enderror"
                                    value="{{ old('name', $product->name) }}"
                                    placeholder="أدخل اسم المنتج"
                                    required
                                    oninput="trackChanges('name')"
                                    data-original="{{ $product->name }}"
                                >
                                @error('name')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">اختر اسماً واضحاً ووصفياً للمنتج</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="category_id" class="form-label">
                                    التصنيف
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="category_idChangeIndicator">معدّل</span>
                                </label>
                                <select 
                                    id="category_id" 
                                    name="category_id" 
                                    class="form-select @error('category_id') error @enderror"
                                    required
                                    onchange="trackChanges('category_id')"
                                    data-original="{{ $product->category_id }}"
                                >
                                    <option value="">اختر التصنيف</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">اختر التصنيف الذي ينتمي إليه هذا المنتج</div>
                            </div>
                        </div>
                        
                        <div class="form-row single">
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    الوصف
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="descriptionChangeIndicator">معدّل</span>
                                </label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    class="form-textarea @error('description') error @enderror"
                                    placeholder="أدخل وصف المنتج"
                                    required
                                    oninput="trackChanges('description')"
                                    data-original="{{ $product->description }}"
                                >{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">قدم وصفاً مفصلاً لميزات المنتج وفوائده</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pricing & Inventory -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-dollar-sign"></i>
                        التسعير والمخزون
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="price" class="form-label">
                                    السعر
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="priceChangeIndicator">معدّل</span>
                                </label>
                                <div class="price-input-group">
                                    <span class="price-currency">$</span>
                                    <input 
                                        type="number" 
                                        id="price" 
                                        name="price" 
                                        class="form-input price-input @error('price') error @enderror"
                                        value="{{ old('price', $product->price) }}"
                                        placeholder="0.00"
                                        step="0.01"
                                        min="0"
                                        required
                                        oninput="trackChanges('price')"
                                        data-original="{{ $product->price }}"
                                    >
                                </div>
                                @error('price')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">حدد سعر البيع لهذا المنتج</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="stock" class="form-label">
                                    كمية المخزون
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="stockChangeIndicator">معدّل</span>
                                </label>
                                <div class="stock-input-group">
                                    <input 
                                        type="number" 
                                        id="stock" 
                                        name="stock" 
                                        class="form-input stock-input @error('stock') error @enderror"
                                        value="{{ old('stock', $product->stock) }}"
                                        placeholder="0"
                                        min="0"
                                        required
                                        oninput="trackChanges('stock')"
                                        data-original="{{ $product->stock }}"
                                    >
                                    <span class="stock-unit">قطعة</span>
                                </div>
                                @error('stock')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">الكمية المتوفرة في المخزون</div>
                            </div>
                        </div>
                        
                        <div class="form-row single">
                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input 
                                        type="checkbox" 
                                        id="is_active" 
                                        name="is_active" 
                                        class="checkbox-input"
                                        value="1"
                                        {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                        onchange="trackChanges('is_active')"
                                        data-original="{{ $product->is_active ? '1' : '0' }}"
                                    >
                                    <label for="is_active" class="checkbox-label">
                                        منتج نشط
                                    </label>
                                    <span class="change-indicator" id="is_activeChangeIndicator">معدّل</span>
                                </div>
                                <div class="form-help">ضع علامة في هذا المربع لجعل المنتج متاحاً للشراء</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product Images -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-images"></i>
                        صور المنتج
                    </h2>
                    
                    @if($product->images && $product->images->count() > 0)
                        <div class="current-images">
                            <div class="current-images-title">
                                <i class="fas fa-image"></i>
                                الصور الحالية
                            </div>
                            <div class="current-images-grid">
                                @foreach($product->images->sortBy('sort_order') as $image)
                                    <div class="current-image-item">
                                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $product->name }}" class="current-image">
                                        <div class="current-image-info">
                                            <div>
                                                @if($image->is_primary)
                                                    <span class="primary-badge">أساسية</span>
                                                @else
                                                    صورة {{ $loop->iteration }}
                                                @endif
                                            </div>
                                            <div class="delete-checkbox">
                                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" id="delete_{{ $image->id }}">
                                                <label for="delete_{{ $image->id }}">حذف</label>
                                            </div>
                                        </div>
                                        @if(!$image->is_primary)
                                            <div class="current-image-controls">
                                                <input type="radio" name="primary_image" value="{{ $image->id }}" id="primary_{{ $image->id }}" style="display: none;">
                                                <label for="primary_{{ $image->id }}" class="image-control-btn" style="background: linear-gradient(135deg, #3b82f6, #2563eb); cursor: pointer;" title="تعيين كصورة أساسية">
                                                    <i class="fas fa-star"></i>
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="form-grid">
                        <div class="form-row single">
                            <div class="form-group">
                                <label for="images" class="form-label">
                                    {{ $product->images && $product->images->count() > 0 ? 'إضافة صور أخرى' : 'رفع الصور' }}
                                    <span class="change-indicator" id="imagesChangeIndicator">معدّل</span>
                                </label>
                                <div class="images-upload-area" onclick="document.getElementById('images').click()">
                                    <input 
                                        type="file" 
                                        id="images" 
                                        name="images[]" 
                                        accept="image/*"
                                        multiple
                                        style="display: none;"
                                        onchange="previewNewImages(this); trackChanges('images')"
                                    >
                                    <div class="upload-content">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <div class="upload-text">
                                            {{ $product->images && $product->images->count() > 0 ? 'انقر لإضافة المزيد من الصور أو اسحب وأفلت' : 'انقر للرفع أو اسحب وأفلت' }}
                                        </div>
                                        <div class="upload-hint">PNG, JPG, GIF حتى 2 ميجابايت لكل صورة. يمكنك اختيار عدة صور.</div>
                                    </div>
                                </div>
                                @error('images')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                @error('images.*')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-help">
                                    {{ $product->images && $product->images->count() > 0 ? 'ارفع صور إضافية للمنتج.' : 'ارفع صور المنتج.' }}
                                    سيتم إضافة الصور الجديدة إلى الصور الموجودة.
                                </div>
                                
                                <div id="newImagesPreview" class="new-images-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <div>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للمنتجات
                    </a>
                </div>
                
                <div class="btn-group">
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i>
                        عرض المنتج
                    </a>
                    
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        إعادة تعيين
                    </button>
                    
                    <button type="submit" class="btn btn-success" id="updateBtn">
                        <i class="fas fa-save"></i>
                        تحديث المنتج
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let hasChanges = false;
    let selectedNewFiles = [];
    const originalValues = {};
    
    // Store original values on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-original]').forEach(field => {
            originalValues[field.id] = field.dataset.original;
        });
    });
    
    // Track changes in form fields
    function trackChanges(fieldName) {
        const field = document.getElementById(fieldName);
        const indicator = document.getElementById(fieldName + 'ChangeIndicator');
        let currentValue;
        
        if (field.type === 'checkbox') {
            currentValue = field.checked ? '1' : '0';
        } else {
            currentValue = field.value;
        }
        
        const originalValue = originalValues[fieldName] || '';
        
        if (currentValue !== originalValue) {
            if (indicator) indicator.style.display = 'inline-block';
            hasChanges = true;
        } else {
            if (indicator) indicator.style.display = 'none';
        }
        
        updateFormState();
    }
    
    // Update form state based on changes
    function updateFormState() {
        const updateBtn = document.getElementById('updateBtn');
        hasChanges = document.querySelector('.change-indicator[style*="inline-block"]') !== null;
        
        if (hasChanges) {
            updateBtn.classList.remove('btn-secondary');
            updateBtn.classList.add('btn-success');
        } else {
            updateBtn.classList.remove('btn-success');
            updateBtn.classList.add('btn-secondary');
        }
    }
    
    // Preview new images
    function previewNewImages(input) {
        const files = Array.from(input.files);
        selectedNewFiles = files;
        
        if (files.length === 0) {
            document.getElementById('newImagesPreview').innerHTML = '';
            return;
        }
        
        // Validate files
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            
            if (file.size > 2 * 1024 * 1024) {
                alert(`الملف ${file.name} كبير جداً. الحد الأقصى هو 2 ميجابايت.`);
                input.value = '';
                selectedNewFiles = [];
                document.getElementById('newImagesPreview').innerHTML = '';
                return;
            }
            
            if (!file.type.match('image.*')) {
                alert(`الملف ${file.name} ليس صورة.`);
                input.value = '';
                selectedNewFiles = [];
                document.getElementById('newImagesPreview').innerHTML = '';
                return;
            }
        }
        
        // Create preview
        const previewContainer = document.getElementById('newImagesPreview');
        previewContainer.innerHTML = '';
        
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'new-image-preview-item';
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="صورة جديدة ${index + 1}" class="new-preview-image">
                    <div class="new-image-controls">
                        <button type="button" class="image-control-btn btn-remove-image" 
                                onclick="removeNewImage(${index})" title="حذف الصورة">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="new-image-info">
                        صورة جديدة ${index + 1}
                    </div>
                `;
                previewContainer.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        });
    }
    
    // Remove new image
    function removeNewImage(index) {
        selectedNewFiles.splice(index, 1);
        
        // Update file input
        const dt = new DataTransfer();
        selectedNewFiles.forEach(file => {
            dt.items.add(file);
        });
        document.getElementById('images').files = dt.files;
        
        // Refresh preview
        previewNewImages(document.getElementById('images'));
        
        if (selectedNewFiles.length === 0) {
            const indicator = document.getElementById('imagesChangeIndicator');
            if (indicator) indicator.style.display = 'none';
            updateFormState();
        }
    }
    
    // Reset form to original values
    function resetForm() {
        if (confirm('هل أنت متأكد من إعادة تعيين النموذج؟ ستفقد جميع التغييرات.')) {
            // Reset text fields
            Object.keys(originalValues).forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = originalValues[fieldId] === '1';
                    } else {
                        field.value = originalValues[fieldId] || '';
                    }
                }
            });
            
            // Reset images
            document.getElementById('images').value = '';
            document.getElementById('newImagesPreview').innerHTML = '';
            selectedNewFiles = [];
            
            // Uncheck all delete checkboxes
            document.querySelectorAll('input[name="delete_images[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Uncheck primary image radios
            document.querySelectorAll('input[name="primary_image"]').forEach(radio => {
                radio.checked = false;
            });
            
            // Hide all change indicators
            document.querySelectorAll('.change-indicator').forEach(indicator => {
                indicator.style.display = 'none';
            });
            
            hasChanges = false;
            updateFormState();
        }
    }
    
    // Drag and drop functionality
    const imagesUploadArea = document.querySelector('.images-upload-area');
    
    imagesUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    imagesUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    imagesUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('images').files = files;
            previewNewImages(document.getElementById('images'));
            trackChanges('images');
        }
    });
    
    // Form validation and submission
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const price = document.getElementById('price').value;
        const stock = document.getElementById('stock').value;
        const category = document.getElementById('category_id').value;
        const description = document.getElementById('description').value.trim();
        
        // Basic validation
        if (!name) {
            e.preventDefault();
            alert('يرجى إدخال اسم المنتج');
            document.getElementById('name').focus();
            return;
        }
        
        if (!category) {
            e.preventDefault();
            alert('يرجى اختيار التصنيف');
            document.getElementById('category_id').focus();
            return;
        }
        
        if (!description) {
            e.preventDefault();
            alert('يرجى إدخال وصف المنتج');
            document.getElementById('description').focus();
            return;
        }
        
        if (!price || price <= 0) {
            e.preventDefault();
            alert('يرجى إدخال سعر صحيح');
            document.getElementById('price').focus();
            return;
        }
        
        if (!stock || stock < 0) {
            e.preventDefault();
            alert('يرجى إدخال كمية مخزون صحيحة');
            document.getElementById('stock').focus();
            return;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('updateBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ التحديث...';
        submitBtn.disabled = true;
        
        // Re-enable button after 10 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
    
    // Warn user about unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = 'لديك تغييرات غير محفوظة. هل أنت متأكد من الخروج؟';
            return e.returnValue;
        }
    });
    
    // Auto-format price input
    document.getElementById('price').addEventListener('blur', function() {
        let value = this.value;
        if (value && !isNaN(value)) {
            this.value = parseFloat(value).toFixed(2);
        }
    });
    
    // Auto-format stock input
    document.getElementById('stock').addEventListener('blur', function() {
        let value = this.value;
        if (value && !isNaN(value)) {
            this.value = Math.floor(parseFloat(value));
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
    
    // Enhanced form interactions
    document.querySelectorAll('.form-input, .form-textarea, .form-select').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = '';
        });
    });
</script>
@endpush