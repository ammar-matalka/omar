@extends('layouts.admin')

@section('title', 'إضافة تسعير جديد')

@push('styles')
<style>
.form-section {
    background: white;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    margin-bottom: 1.5rem;
}

.section-header {
    background: #f8fafc;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 0.75rem 0.75rem 0 0;
}

.section-content {
    padding: 1.5rem;
}

.chain-selector {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.chain-step {
    margin-bottom: 1rem;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    background: white;
}

.chain-step.completed {
    border-color: #10b981;
    background: #ecfdf5;
}

.chain-step.active {
    border-color: #3b82f6;
    background: #eff6ff;
}

.step-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    margin-left: 1rem;
}

.step-number.completed {
    background: #10b981;
    color: white;
}

.step-number.active {
    background: #3b82f6;
    color: white;
}

.pricing-preview {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.75rem;
    padding: 2rem;
    text-align: center;
}

.price-display {
    font-size: 2.5rem;
    font-weight: 900;
    margin: 1rem 0;
}

.shipping-toggle {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
}

.validation-feedback {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
    color: #dc2626;
}

@media (max-width: 768px) {
    .chain-step {
        text-align: center;
    }
    
    .step-number {
        margin: 0 auto 0.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">إضافة تسعير جديد</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.educational.pricing.index') }}">التسعير</a></li>
                    <li class="breadcrumb-item active">إضافة تسعير جديد</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.educational.pricing.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>

    <form action="{{ route('admin.educational.pricing.store') }}" method="POST" id="pricingForm">
        @csrf
        
        <!-- Educational Chain Selection -->
        <div class="form-section">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-link me-2"></i>السلسلة التعليمية
                </h5>
            </div>
            <div class="section-content">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    اختر الجيل والمادة والمعلم والمنصة والباقة لإنشاء تسعير محدد
                </div>

                <div class="chain-selector">
                    <!-- Step 1: Generation -->
                    <div class="chain-step active" id="step-generation">
                        <div class="d-flex align-items-center">
                            <div class="step-number active">1</div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">اختر الجيل الدراسي</h6>
                                <select name="generation_id" id="generation_id" class="form-select" required>
                                    <option value="">اختر الجيل...</option>
                                    @foreach($generations as $generation)
                                        <option value="{{ $generation->id }}" {{ old('generation_id') == $generation->id ? 'selected' : '' }}>
                                            {{ $generation->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('generation_id')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Subject -->
                    <div class="chain-step" id="step-subject">
                        <div class="d-flex align-items-center">
                            <div class="step-number">2</div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">اختر المادة</h6>
                                <select name="subject_id" id="subject_id" class="form-select" required disabled>
                                    <option value="">اختر الجيل أولاً...</option>
                                </select>
                                @error('subject_id')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Teacher -->
                    <div class="chain-step" id="step-teacher">
                        <div class="d-flex align-items-center">
                            <div class="step-number">3</div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">اختر المعلم</h6>
                                <select name="teacher_id" id="teacher_id" class="form-select" required disabled>
                                    <option value="">اختر المادة أولاً...</option>
                                </select>
                                @error('teacher_id')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Platform -->
                    <div class="chain-step" id="step-platform">
                        <div class="d-flex align-items-center">
                            <div class="step-number">4</div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">اختر المنصة</h6>
                                <select name="platform_id" id="platform_id" class="form-select" required disabled>
                                    <option value="">اختر المعلم أولاً...</option>
                                </select>
                                @error('platform_id')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Package -->
                    <div class="chain-step" id="step-package">
                        <div class="d-flex align-items-center">
                            <div class="step-number">5</div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">اختر الباقة</h6>
                                <select name="package_id" id="package_id" class="form-select" required disabled>
                                    <option value="">اختر المنصة أولاً...</option>
                                </select>
                                @error('package_id')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pre-fill from URL parameters -->
                @if(request('package_id'))
                    <div class="alert alert-success">
                        <i class="fas fa-link me-2"></i>
                        تم تحديد الباقة مسبقاً من الرابط. ستتم تعبئة الحقول تلقائياً.
                    </div>
                @endif
            </div>
        </div>

        <!-- Package Type & Shipping -->
        <div class="form-section" id="package-type-section" style="display: none;">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>نوع الباقة والشحن
                </h5>
            </div>
            <div class="section-content">
                <div id="package-info">
                    <!-- Will be populated by JavaScript -->
                </div>

                <!-- Region Selection (for physical packages) -->
                <div id="region-selection" style="display: none;">
                    <h6 class="mt-4 mb-3">منطقة الشحن</h6>
                    <select name="region_id" id="region_id" class="form-select">
                        <option value="">اختر منطقة الشحن...</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" data-cost="{{ $region->shipping_cost }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                {{ $region->name }} - {{ $region->formatted_shipping_cost }}
                            </option>
                        @endforeach
                    </select>
                    @error('region_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>