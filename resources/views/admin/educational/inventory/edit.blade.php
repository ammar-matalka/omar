@extends('layouts.admin')

@section('title', 'تعديل المخزون')
@section('page-title', 'تعديل المخزون')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
        <i class="fas fa-chevron-left"></i>
    </div>
    <div class="breadcrumb-item">
        <a href="{{ route('admin.educational.inventory.index') }}" class="breadcrumb-link">المخزون التعليمي</a>
        <i class="fas fa-chevron-left"></i>
    </div>
    <div class="breadcrumb-item">
        <a href="{{ route('admin.educational.inventory.show', $inventory->id) }}" class="breadcrumb-link">تفاصيل المخزون</a>
        <i class="fas fa-chevron-left"></i>
    </div>
    <div class="breadcrumb-item">تعديل المخزون</div>
@endsection

@push('styles')
<style>
    .inventory-wizard {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
    }
    
    .wizard-header {
        background: linear-gradient(135deg, var(--warning-500), #d97706);
        color: white;
        padding: var(--space-2xl);
        text-align: center;
    }
    
    .wizard-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: var(--space-md);
    }
    
    .wizard-subtitle {
        opacity: 0.9;
        font-size: 0.95rem;
    }
    
    .current-product-info {
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
        border: 2px solid var(--admin-primary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .product-info-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    
    .info-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--admin-primary-700);
    }
    
    .product-chain-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
    }
    
    .chain-item {
        background: white;
        padding: var(--space-lg);
        border-radius: var(--radius-md);
        border: 1px solid var(--admin-primary-200);
        text-align: center;
    }
    
    .chain-label {
        font-size: 0.8rem;
        color: var(--admin-secondary-500);
        margin-bottom: var(--space-sm);
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .chain-value {
        font-weight: 700;
        color: var(--admin-secondary-900);
        font-size: 0.95rem;
    }
    
    .form-section {
        padding: var(--space-2xl);
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xl);
        padding-bottom: var(--space-md);
        border-bottom: 2px solid var(--warning-100);
    }
    
    .section-icon {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, var(--warning-500), #d97706);
        color: white;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .form-group {
        position: relative;
    }
    
    .form-label {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
        font-weight: 600;
        color: var(--admin-secondary-700);
        font-size: 0.95rem;
    }
    
    .label-icon {
        color: var(--warning-500);
        font-size: 0.9rem;
    }
    
    .required-mark {
        color: var(--error-500);
        margin-right: var(--space-xs);
    }
    
    .form-input, .form-select {
        width: 100%;
        padding: var(--space-md) var(--space-lg);
        border: 2px solid var(--admin-secondary-300);
        border-radius: var(--radius-lg);
        background: white;
        color: var(--admin-secondary-900);
        font-size: 0.9rem;
        transition: all var(--transition-fast);
        font-family: var(--font-family-sans);
    }
    
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--warning-500);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        transform: translateY(-1px);
    }
    
    .form-select:disabled {
        background: var(--admin-secondary-100);
        color: var(--admin-secondary-500);
        cursor: not-allowed;
    }
    
    .select-loading {
        position: relative;
    }
    
    .select-loading::after {
        content: '';
        position: absolute;
        left: var(--space-lg);
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        border: 2px solid var(--admin-secondary-300);
        border-top: 2px solid var(--warning-500);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    }
    
    .form-help {
        font-size: 0.85rem;
        color: var(--admin-secondary-500);
        margin-top: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .help-icon {
        color: var(--info-500);
    }
    
    .quantity-section {
        background: linear-gradient(135deg, var(--warning-50), var(--warning-100));
        border: 2px solid var(--warning-200);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .quantity-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
    }
    
    .quantity-title {
        font-weight: 700;
        color: var(--warning-700);
        font-size: 1.1rem;
    }
    
    .current-stock-display {
        background: white;
        border: 2px solid var(--warning-300);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-lg);
        text-align: center;
    }
    
    .current-stock-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--space-lg);
    }
    
    .stock-display-item {
        text-align: center;
    }
    
    .stock-number {
        font-size: 1.5rem;
        font-weight: 900;
        margin-bottom: var(--space-xs);
        background: linear-gradient(135deg, var(--warning-600), var(--warning-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stock-label {
        font-size: 0.8rem;
        color: var(--admin-secondary-600);
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .validation-section {
        background: linear-gradient(135deg, var(--info-50), var(--info-100));
        border: 2px solid var(--info-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-lg);
        display: none;
    }
    
    .validation-section.show {
        display: block;
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .validation-title {
        font-weight: 700;
        color: var(--info-700);
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .validation-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-md);
    }
    
    .validation-item {
        background: white;
        padding: var(--space-md);
        border-radius: var(--radius-md);
        border: 1px solid var(--info-200);
        text-align: center;
    }
    
    .validation-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .validation-label {
        font-size: 0.8rem;
        color: var(--admin-secondary-500);
        font-weight: 600;
    }
    
    .actions-section {
        background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        padding: var(--space-2xl);
        border-top: 1px solid var(--admin-secondary-200);
    }
    
    .action-buttons {
        display: flex;
        gap: var(--space-lg);
        justify-content: flex-end;
        align-items: center;
    }
    
    
    .btn-primary {
        background: linear-gradient(135deg, var(--warning-500), #d97706);
        color: white;
        box-shadow: 0 4px 14px 0 rgba(245, 158, 11, 0.39);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        box-shadow: 0 8px 25px 0 rgba(245, 158, 11, 0.6);
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background: white;
        color: var(--admin-secondary-700);
        border-color: var(--admin-secondary-300);
        box-shadow: var(--shadow-sm);
    }
    
    .btn-secondary:hover {
        background: var(--admin-secondary-50);
        border-color: var(--admin-secondary-400);
        transform: translateY(-1px);
    }
    
    .btn-lg {
        padding: var(--space-lg) var(--space-2xl);
        font-size: 1rem;
    }
    
    .error-message {
        color: var(--error-500);
        font-size: 0.85rem;
        margin-top: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .success-message {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border: 2px solid #22c55e;
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-weight: 500;
        box-shadow: var(--shadow-md);
    }
    
    .validation-errors {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        border: 2px solid #ef4444;
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
    }
    
    .validation-title {
        font-weight: 700;
        color: #991b1b;
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .validation-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .validation-item {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        color: #991b1b;
        margin-bottom: var(--space-sm);
    }
    
    @media (max-width: 768px) {
        .form-row, .product-chain-grid, .current-stock-grid, .validation-grid {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="inventory-wizard fade-in">
    <div class="wizard-header">
        <h1 class="wizard-title">
            <i class="fas fa-edit"></i>
            تعديل المخزون
        </h1>
        <p class="wizard-subtitle">قم بتعديل كميات المخزون وإعداداته</p>
    </div>

    @if($errors->any())
        <div class="form-section">
            <div class="validation-errors">
                <div class="validation-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    يرجى تصحيح الأخطاء التالية:
                </div>
                <ul class="validation-list">
                    @foreach($errors->all() as $error)
                        <li class="validation-item">
                            <i class="fas fa-times-circle"></i>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.educational.inventory.update', $inventory->id) }}" method="POST" id="inventoryForm">
        @csrf
        @method('PATCH')
        
        <!-- Current Product Info -->
        <div class="form-section">
            <div class="current-product-info">
                <div class="product-info-header">
                    <div class="info-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="info-title">المنتج الحالي</div>
                </div>
                
                <div class="product-chain-grid">
                    <div class="chain-item">
                        <div class="chain-label">الجيل</div>
                        <div class="chain-value">{{ $inventory->generation->display_name }}</div>
                    </div>
                    <div class="chain-item">
                        <div class="chain-label">المادة</div>
                        <div class="chain-value">{{ $inventory->subject->name }}</div>
                    </div>
                    <div class="chain-item">
                        <div class="chain-label">المعلم</div>
                        <div class="chain-value">{{ $inventory->teacher->name }}</div>
                    </div>
                    <div class="chain-item">
                        <div class="chain-label">المنصة</div>
                        <div class="chain-value">{{ $inventory->platform->name }}</div>
                    </div>
                    <div class="chain-item">
                        <div class="chain-label">الباقة</div>
                        <div class="chain-value">{{ $inventory->package->name }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Selection Section -->
        <div class="form-section">
            <h2 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                تحديث المنتج التعليمي
            </h2>

            <div class="form-row">
                <div class="form-group">
                    <label for="generation_id" class="form-label">
                        <i class="fas fa-users label-icon"></i>
                        الجيل الدراسي
                        <span class="required-mark">*</span>
                    </label>
                    <select name="generation_id" id="generation_id" class="form-select" required>
                        <option value="">اختر الجيل</option>
                        @foreach($generations as $generation)
                            <option value="{{ $generation->id }}" {{ old('generation_id', $inventory->generation_id) == $generation->id ? 'selected' : '' }}>
                                {{ $generation->display_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-help">
                        <i class="fas fa-info-circle help-icon"></i>
                        تغيير الجيل سيؤثر على باقي الخيارات
                    </div>
                    @error('generation_id')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="subject_id" class="form-label">
                        <i class="fas fa-book label-icon"></i>
                        المادة الدراسية
                        <span class="required-mark">*</span>
                    </label>
                    <select name="subject_id" id="subject_id" class="form-select" required>
                        <option value="">اختر المادة</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $inventory->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-help">
                        <i class="fas fa-info-circle help-icon"></i>
                        سيتم تحديث قائمة المعلمين بناءً على المادة
                    </div>
                    @error('subject_id')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="teacher_id" class="form-label">
                        <i class="fas fa-chalkboard-teacher label-icon"></i>
                        المعلم
                        <span class="required-mark">*</span>
                    </label>
                    <select name="teacher_id" id="teacher_id" class="form-select" required>
                        <option value="">اختر المعلم</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $inventory->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-help">
                        <i class="fas fa-info-circle help-icon"></i>
                        سيتم تحديث قائمة المنصات بناءً على المعلم
                    </div>
                    @error('teacher_id')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="platform_id" class="form-label">
                        <i class="fas fa-desktop label-icon"></i>
                        المنصة التعليمية
                        <span class="required-mark">*</span>
                    </label>
                    <select name="platform_id" id="platform_id" class="form-select" required>
                        <option value="">اختر المنصة</option>
                        @foreach($platforms as $platform)
                            <option value="{{ $platform->id }}" {{ old('platform_id', $inventory->platform_id) == $platform->id ? 'selected' : '' }}>
                                {{ $platform->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-help">
                        <i class="fas fa-info-circle help-icon"></i>
                        سيتم تحديث قائمة الباقات بناءً على المنصة
                    </div>
                    @error('platform_id')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="package_id" class="form-label">
                        <i class="fas fa-box label-icon"></i>
                        الباقة التعليمية
                        <span class="required-mark">*</span>
                    </label>
                    <select name="package_id" id="package_id" class="form-select" required>
                        <option value="">اختر الباقة</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_id', $inventory->package_id) == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - {{ $package->package_type }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-help">
                        <i class="fas fa-info-circle help-icon"></i>
                        اختر الباقة التعليمية المناسبة
                    </div>
                    @error('package_id')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Quantity Management Section -->
        <div class="form-section">
            <h2 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                إدارة الكميات
            </h2>

            <div class="quantity-section">
                <div class="quantity-header">
                    <i class="fas fa-chart-bar"></i>
                    <div class="quantity-title">الكميات الحالية</div>
                </div>

                <div class="current-stock-display">
                    <div class="current-stock-grid">
                        <div class="stock-display-item">
                            <div class="stock-number" id="currentAvailable">{{ number_format($inventory->quantity_available) }}</div>
                            <div class="stock-label">المتاح</div>
                        </div>
                        <div class="stock-display-item">
                            <div class="stock-number" id="currentReserved">{{ number_format($inventory->quantity_reserved) }}</div>
                            <div class="stock-label">المحجوز</div>
                        </div>
                        <div class="stock-display-item">
                            <div class="stock-number" id="currentTotal">{{ number_format($inventory->total_quantity) }}</div>
                            <div class="stock-label">الإجمالي</div>
                        </div>
                        <div class="stock-display-item">
                            <div class="stock-number" id="actualAvailable">{{ number_format($inventory->actual_available) }}</div>
                            <div class="stock-label">الفعلي المتاح</div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="quantity_available" class="form-label">
                            <i class="fas fa-boxes label-icon"></i>
                            الكمية المتاحة
                            <span class="required-mark">*</span>
                        </label>
                        <input type="number" name="quantity_available" id="quantity_available" class="form-input" 
                               value="{{ old('quantity_available', $inventory->quantity_available) }}" 
                               min="0" max="100000" required>
                        <div class="form-help">
                            <i class="fas fa-info-circle help-icon"></i>
                            إجمالي الكمية الموجودة في المخزون
                        </div>
                        @error('quantity_available')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="quantity_reserved" class="form-label">
                            <i class="fas fa-lock label-icon"></i>
                            الكمية المحجوزة
                        </label>
                        <input type="number" name="quantity_reserved" id="quantity_reserved" class="form-input" 
                               value="{{ old('quantity_reserved', $inventory->quantity_reserved) }}" 
                               min="0" max="100000">
                        <div class="form-help">
                            <i class="fas fa-info-circle help-icon"></i>
                            الكمية المحجوزة للطلبات المعلقة
                        </div>
                        @error('quantity_reserved')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Live Validation Display -->
                <div class="validation-section" id="validationSection">
                    <div class="validation-title">
                        <i class="fas fa-calculator"></i>
                        معاينة الكميات الجديدة
                    </div>
                    <div class="validation-grid">
                        <div class="validation-item">
                            <div class="validation-value" id="newTotal">0</div>
                            <div class="validation-label">الإجمالي الجديد</div>
                        </div>
                        <div class="validation-item">
                            <div class="validation-value" id="newActual">0</div>
                            <div class="validation-label">الفعلي المتاح الجديد</div>
                        </div>
                        <div class="validation-item">
                            <div class="validation-value" id="stockChange">0</div>
                            <div class="validation-label">التغيير</div>
                        </div>
                        <div class="validation-item">
                            <div class="validation-value" id="newStatus">-</div>
                            <div class="validation-label">الحالة الجديدة</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Section -->
        <div class="actions-section">
            <div class="action-buttons">
                <a href="{{ route('admin.educational.inventory.show', $inventory->id) }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i>
                    إلغاء
                </a>
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <i class="fas fa-save"></i>
                    حفظ التعديلات
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const generationSelect = document.getElementById('generation_id');
    const subjectSelect = document.getElementById('subject_id');
    const teacherSelect = document.getElementById('teacher_id');
    const platformSelect = document.getElementById('platform_id');
    const packageSelect = document.getElementById('package_id');
    const quantityAvailableInput = document.getElementById('quantity_available');
    const quantityReservedInput = document.getElementById('quantity_reserved');
    
    // Validation section
    const validationSection = document.getElementById('validationSection');
    const newTotal = document.getElementById('newTotal');
    const newActual = document.getElementById('newActual');
    const stockChange = document.getElementById('stockChange');
    const newStatus = document.getElementById('newStatus');
    
    // Current values
    const originalAvailable = {{ $inventory->quantity_available }};
    const originalReserved = {{ $inventory->quantity_reserved }};
    
    // Cascade loading functions
    function loadSubjects(generationId) {
        if (!generationId) {
            resetSelect(subjectSelect, 'اختر المادة');
            resetCascade(['teacher', 'platform', 'package']);
            return;
        }
        
        setLoading(subjectSelect, true);
        
        fetch(`/educational/api/subjects?generation_id=${generationId}`)
            .then(response => response.json())
            .then(data => {
                populateSelect(subjectSelect, data, 'اختر المادة', 'name');
                setLoading(subjectSelect, false);
                subjectSelect.disabled = false;
                resetCascade(['teacher', 'platform', 'package']);
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                setLoading(subjectSelect, false);
            });
    }
    
    function loadTeachers(subjectId) {
        if (!subjectId) {
            resetSelect(teacherSelect, 'اختر المعلم');
            resetCascade(['platform', 'package']);
            return;
        }
        
        setLoading(teacherSelect, true);
        
        fetch(`/educational/api/teachers?subject_id=${subjectId}`)
            .then(response => response.json())
            .then(data => {
                populateSelect(teacherSelect, data, 'اختر المعلم', 'name');
                setLoading(teacherSelect, false);
                teacherSelect.disabled = false;
                resetCascade(['platform', 'package']);
            })
            .catch(error => {
                console.error('Error loading teachers:', error);
                setLoading(teacherSelect, false);
            });
    }
    
    function loadPlatforms(teacherId) {
        if (!teacherId) {
            resetSelect(platformSelect, 'اختر المنصة');
            resetCascade(['package']);
            return;
        }
        
        setLoading(platformSelect, true);
        
        fetch(`/educational/api/platforms?teacher_id=${teacherId}`)
            .then(response => response.json())
            .then(data => {
                populateSelect(platformSelect, data, 'اختر المنصة', 'name');
                setLoading(platformSelect, false);
                platformSelect.disabled = false;
                resetCascade(['package']);
            })
            .catch(error => {
                console.error('Error loading platforms:', error);
                setLoading(platformSelect, false);
            });
    }
    
    function loadPackages(platformId) {
        if (!platformId) {
            resetSelect(packageSelect, 'اختر الباقة');
            return;
        }
        
        setLoading(packageSelect, true);
        
        fetch(`/educational/api/packages?platform_id=${platformId}`)
            .then(response => response.json())
            .then(data => {
                populateSelect(packageSelect, data, 'اختر الباقة', 'name', 'product_type');
                setLoading(packageSelect, false);
                packageSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading packages:', error);
                setLoading(packageSelect, false);
            });
    }
    
    // Helper functions
    function setLoading(element, loading) {
        if (loading) {
            element.classList.add('select-loading');
            element.disabled = true;
        } else {
            element.classList.remove('select-loading');
        }
    }
    
    function resetSelect(element, placeholder) {
        element.innerHTML = `<option value="">${placeholder}</option>`;
        element.disabled = true;
        element.classList.remove('select-loading');
    }
    
    function resetCascade(elements) {
        const placeholders = {
            'teacher': 'اختر المعلم',
            'platform': 'اختر المنصة',
            'package': 'اختر الباقة'
        };
        
        elements.forEach(elementName => {
            const element = document.getElementById(elementName + '_id');
            resetSelect(element, placeholders[elementName]);
        });
    }
    
    function populateSelect(element, data, placeholder, textField, extraField = null) {
        let html = `<option value="">${placeholder}</option>`;
        
        data.forEach(item => {
            const extraInfo = extraField && item[extraField] ? ` - ${item[extraField]}` : '';
            html += `<option value="${item.id}">${item[textField]}${extraInfo}</option>`;
        });
        
        element.innerHTML = html;
    }
    
    function updateValidation() {
        const available = parseInt(quantityAvailableInput.value) || 0;
        const reserved = parseInt(quantityReservedInput.value) || 0;
        
        const total = available;
        const actual = Math.max(0, available - reserved);
        const change = available - originalAvailable;
        
        let status = 'متوفر بكثرة';
        if (actual <= 0) {
            status = 'نفدت الكمية';
        } else if (actual <= 10) {
            status = 'كمية قليلة';
        } else if (actual <= 50) {
            status = 'متوفر';
        }
        
        newTotal.textContent = total.toLocaleString();
        newActual.textContent = actual.toLocaleString();
        stockChange.textContent = (change >= 0 ? '+' : '') + change.toLocaleString();
        newStatus.textContent = status;
        
        // Color coding
        stockChange.style.color = change > 0 ? 'var(--success-600)' : change < 0 ? 'var(--error-600)' : 'var(--admin-secondary-600)';
        newStatus.style.color = actual > 50 ? 'var(--success-600)' : actual > 10 ? 'var(--warning-600)' : actual > 0 ? 'var(--error-600)' : 'var(--error-700)';
        
        validationSection.classList.add('show');
        
        // Validate reserved quantity
        if (reserved > available) {
            quantityReservedInput.style.borderColor = 'var(--error-500)';
            quantityReservedInput.style.boxShadow = '0 0 0 4px rgba(239, 68, 68, 0.1)';
        } else {
            quantityReservedInput.style.borderColor = '';
            quantityReservedInput.style.boxShadow = '';
        }
    }
    
    // Event listeners
    generationSelect.addEventListener('change', function() {
        loadSubjects(this.value);
    });
    
    subjectSelect.addEventListener('change', function() {
        loadTeachers(this.value);
    });
    
    teacherSelect.addEventListener('change', function() {
        loadPlatforms(this.value);
    });
    
    platformSelect.addEventListener('change', function() {
        loadPackages(this.value);
    });
    
    quantityAvailableInput.addEventListener('input', updateValidation);
    quantityReservedInput.addEventListener('input', updateValidation);
    
    // Form submission
    document.getElementById('inventoryForm').addEventListener('submit', function(e) {
        const available = parseInt(quantityAvailableInput.value) || 0;
        const reserved = parseInt(quantityReservedInput.value) || 0;
        
        if (reserved > available) {
            e.preventDefault();
            alert('الكمية المحجوزة لا يمكن أن تتجاوز الكمية المتاحة');
            return false;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        submitBtn.disabled = true;
        
        // Re-enable button after 5 seconds (in case of slow response)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
    
    // Initialize validation display
    updateValidation();
    
    // Enhanced form interactions
    const inputs = document.querySelectorAll('.form-input, .form-select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    // Initialize fade-in animation
    const elements = document.querySelectorAll('.fade-in');
    elements.forEach(el => {
        el.classList.add('visible');
    });
});
</script>
@endpush