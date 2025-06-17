@extends('layouts.app')

@section('title', 'البطاقات التعليمية')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 font-weight-bold text-primary mb-3">البطاقات التعليمية</h1>
            <p class="lead text-muted">اختر الجيل ثم نوع الطلب المطلوب</p>
        </div>
    </div>

    <!-- Step 1: Generations Selection -->
    <div id="step1_generations" class="step-container">
        <div class="row justify-content-center mb-4">
            <div class="col-md-8 text-center">
                <h3 class="section-title mb-4">
                    <i class="fas fa-graduation-cap"></i>
                    الخطوة الأولى: اختر الجيل
                </h3>
            </div>
        </div>

        <div class="row">
            @forelse($generations as $generation)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="generation-card card h-100" 
                         data-generation-id="{{ $generation->id }}"
                         data-generation-name="{{ $generation->display_name }}">
                        <div class="card-header text-center">
                            <div class="generation-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h4 class="generation-year mb-0">{{ $generation->year }}</h4>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $generation->name }}</h5>
                            
                            @if($generation->description)
                                <p class="card-text text-muted mb-3">{{ Str::limit($generation->description, 80) }}</p>
                            @endif
                            
                            <div class="subjects-count mb-3">
                                <span class="badge badge-pill badge-primary">
                                    <i class="fas fa-book"></i>
                                    {{ $generation->subjects_count }} مادة
                                </span>
                            </div>
                            
                            <button type="button" class="btn btn-primary btn-block select-generation-btn"
                                    data-generation-id="{{ $generation->id }}"
                                    data-generation-name="{{ $generation->display_name }}">
                                <i class="fas fa-arrow-left"></i>
                                اختيار هذا الجيل
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">لا توجد أجيال متاحة حالياً</h4>
                        <p class="text-muted">سيتم إضافة الأجيال قريباً</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Step 2: Order Type Selection -->
    <div id="step2_order_type" class="step-container" style="display: none;">
        <div class="row justify-content-center mb-4">
            <div class="col-md-10">
                <div class="text-center mb-4">
                    <button type="button" class="btn btn-outline-secondary back-to-generations-btn">
                        <i class="fas fa-arrow-right"></i>
                        العودة لاختيار الجيل
                    </button>
                </div>
                
                <div class="alert alert-primary text-center">
                    <h5 class="mb-2">
                        <i class="fas fa-check-circle"></i>
                        الجيل المختار: <span id="selected_generation_display" class="font-weight-bold"></span>
                    </h5>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-md-8 text-center">
                <h3 class="section-title mb-4">
                    <i class="fas fa-clipboard-list"></i>
                    الخطوة الثانية: اختر نوع الطلب
                </h3>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-5 mb-4">
                <div class="order-type-card card h-100" data-order-type="card">
                    <div class="card-header bg-success text-white text-center">
                        <div class="order-type-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <h4 class="mb-0">بطاقة تعليمية</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text mb-4">بطاقات تعليمية للمواد الدراسية مع معلم ومنصة محددة</p>
                        <ul class="list-unstyled text-left mb-4">
                            <li><i class="fas fa-check text-success"></i> اختيار المادة</li>
                            <li><i class="fas fa-check text-success"></i> اختيار المعلم</li>
                            <li><i class="fas fa-check text-success"></i> اختيار الفصل</li>
                            <li><i class="fas fa-check text-success"></i> اختيار المنصة</li>
                        </ul>
                        <button type="button" class="btn btn-success btn-block select-order-type-btn"
                                data-order-type="card">
                            <i class="fas fa-arrow-left"></i>
                            اختيار بطاقة تعليمية
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-5 mb-4">
                <div class="order-type-card card h-100" data-order-type="dossier">
                    <div class="card-header bg-info text-white text-center">
                        <div class="order-type-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h4 class="mb-0">دوسية</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text mb-4">دوسيات جاهزة للمواد مع المعلم والمنصة</p>
                        <ul class="list-unstyled text-left mb-4">
                            <li><i class="fas fa-check text-info"></i> اختيار المادة</li>
                            <li><i class="fas fa-check text-info"></i> اختيار المعلم</li>
                            <li><i class="fas fa-check text-info"></i> اختيار الفصل</li>
                            <li><i class="fas fa-check text-info"></i> اختيار المنصة</li>
                            <li><i class="fas fa-check text-info"></i> اختيار الدوسية</li>
                        </ul>
                        <button type="button" class="btn btn-info btn-block select-order-type-btn"
                                data-order-type="dossier">
                            <i class="fas fa-arrow-left"></i>
                            اختيار دوسية
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 3: Order Form -->
    <div id="step3_order_form" class="step-container" style="display: none;">
        <div class="row justify-content-center mb-4">
            <div class="col-md-10">
                <div class="text-center mb-4">
                    <button type="button" class="btn btn-outline-secondary back-to-order-type-btn">
                        <i class="fas fa-arrow-right"></i>
                        العودة لاختيار نوع الطلب
                    </button>
                </div>
                
                <div class="alert alert-primary text-center">
                    <h5 class="mb-2">
                        <i class="fas fa-check-circle"></i>
                        الجيل: <span id="selected_generation_display2" class="font-weight-bold"></span>
                        | النوع: <span id="selected_order_type_display" class="font-weight-bold"></span>
                    </h5>
                </div>
            </div>
        </div>

        @auth
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header text-center py-4">
                        <h4 class="mb-0 text-white">
                            <i class="fas fa-shopping-cart"></i>
                            <span id="form_title">طلب البطاقات التعليمية</span>
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form id="orderForm" action="{{ route('educational-cards.submit-order') }}" method="POST">
                            @csrf
                            <input type="hidden" name="generation_id" id="selected_generation_id">
                            <input type="hidden" name="order_type" id="selected_order_type">
                            
                            <!-- Student Info -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="student_name" class="form-label">
                                            <i class="fas fa-user text-primary"></i>
                                            اسم الطالب <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('student_name') is-invalid @enderror" 
                                               id="student_name" name="student_name" value="{{ old('student_name') }}" 
                                               placeholder="أدخل اسم الطالب" required>
                                        @error('student_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="semester" class="form-label">
                                            <i class="fas fa-calendar text-primary"></i>
                                            الفصل الدراسي <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control @error('semester') is-invalid @enderror" 
                                                id="semester" name="semester" required>
                                            <option value="">اختر الفصل</option>
                                            <option value="first" {{ old('semester') == 'first' ? 'selected' : '' }}>الفصل الأول</option>
                                            <option value="second" {{ old('semester') == 'second' ? 'selected' : '' }}>الفصل الثاني</option>
                                            <option value="both" {{ old('semester') == 'both' ? 'selected' : '' }}>كلا الفصلين</option>
                                        </select>
                                        @error('semester')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Selection -->
                            <div class="form-group mb-4">
                                <label class="form-label mb-3">
                                    <i class="fas fa-book text-primary"></i>
                                    المادة <span class="text-danger">*</span>
                                </label>
                                <div id="subjects_container" class="selection-container">
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">جاري التحميل...</span>
                                        </div>
                                        <p class="mt-3 text-muted">جاري تحميل المواد...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Teacher Selection -->
                            <div class="form-group mb-4" id="teacher_selection" style="display: none;">
                                <label class="form-label mb-3">
                                    <i class="fas fa-chalkboard-teacher text-primary"></i>
                                    المعلم <span class="text-danger">*</span>
                                </label>
                                <div id="teachers_container" class="selection-container">
                                    <div class="text-center py-3">
                                        <p class="text-muted">اختر المادة أولاً</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Platform Selection -->
                            <div class="form-group mb-4" id="platform_selection" style="display: none;">
                                <label class="form-label mb-3">
                                    <i class="fas fa-desktop text-primary"></i>
                                    المنصة <span class="text-danger">*</span>
                                </label>
                                <div id="platforms_container" class="selection-container">
                                    <div class="text-center py-3">
                                        <p class="text-muted">اختر المعلم أولاً</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Dossier Selection (for dossiers only) -->
                            <div class="form-group mb-4" id="dossier_selection" style="display: none;">
                                <label class="form-label mb-3">
                                    <i class="fas fa-folder-open text-primary"></i>
                                    الدوسية <span class="text-danger">*</span>
                                </label>
                                <div id="dossiers_container" class="selection-container">
                                    <div class="text-center py-3">
                                        <p class="text-muted">اختر المنصة أولاً</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity and Contact -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="quantity" class="form-label">
                                            <i class="fas fa-sort-numeric-up text-primary"></i>
                                            الكمية <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                               id="quantity" name="quantity" value="{{ old('quantity', 1) }}" 
                                               min="1" max="10" required>
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">
                                            <i class="fas fa-phone text-primary"></i>
                                            رقم الهاتف
                                        </label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                               placeholder="مثال: 079xxxxxxx">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="form-group mb-4">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                    العنوان
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2" 
                                          placeholder="العنوان التفصيلي للتوصيل (اختياري)">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="form-group mb-4">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-sticky-note text-primary"></i>
                                    ملاحظات إضافية
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="أي ملاحظات خاصة بالطلب (اختياري)">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Order Summary -->
                            <div id="order_summary" class="order-summary" style="display: none;">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-calculator"></i>
                                            ملخص الطلب
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="summary_content"></div>
                                        <hr>
                                        <p class="mb-0 text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            سيتم التواصل معك قريباً لتأكيد الطلب
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-5" id="submit_order_btn" disabled>
                                    <i class="fas fa-paper-plane"></i>
                                    إرسال الطلب
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg text-center">
                    <div class="card-body p-5">
                        <i class="fas fa-user-lock fa-4x text-primary mb-4"></i>
                        <h4 class="text-primary mb-3">تسجيل الدخول مطلوب</h4>
                        <p class="text-muted mb-4">يجب تسجيل الدخول أولاً لطلب البطاقات التعليمية</p>
                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-sign-in-alt"></i>
                                    تسجيل الدخول
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-user-plus"></i>
                                    إنشاء حساب
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endauth
    </div>

    <!-- My Orders Section -->
    @auth
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list"></i>
                        طلباتي السابقة
                    </h5>
                </div>
                <div class="card-body text-center">
                    <a href="{{ route('educational-cards.my-orders') }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye"></i>
                        عرض جميع طلباتي
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endauth
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif
@endsection

@push('styles')
<style>
/* Steps styling */
.step-container {
    animation: fadeIn 0.6s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Generation Cards */
.generation-card {
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.generation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.generation-card .card-header {
    background: linear-gradient(135deg, #007bff, #6f42c1);
    color: white;
    border: none;
    padding: 2rem 1rem 1rem;
}

.generation-icon,
.order-type-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
}

.generation-year {
    font-size: 2rem;
    font-weight: bold;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

/* Order Type Cards */
.order-type-card {
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.order-type-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.order-type-card.selected {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    border: 2px solid #28a745;
}

/* Selection containers */
.selection-container {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1.5rem;
    min-height: 120px;
}

.selection-item {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
    cursor: pointer;
    margin-bottom: 1rem;
    padding: 1rem;
}

.selection-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #007bff;
}

.selection-item.selected {
    border-color: #28a745;
    background: linear-gradient(135deg, #d4edda, #f8fff9);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
}

.selection-checkbox {
    transform: scale(1.2);
}

/* Form styling */
.card-header {
    background: linear-gradient(135deg, #007bff, #6f42c1);
    border: none;
}

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Buttons */
.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #1e7e34, #155724);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8, #138496);
    border: none;
}

.btn-info:hover {
    background: linear-gradient(135deg, #138496, #0f6674);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.125rem;
    border-radius: 12px;
}

/* Order Summary */
.order-summary {
    animation: slideDown 0.5s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Section Title */
.section-title {
    color: #333;
    font-weight: 700;
    margin-bottom: 2rem;
}

/* Badge styling */
.badge-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
}

/* Responsive */
@media (max-width: 768px) {
    .generation-card, .order-type-card {
        margin-bottom: 1.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .generation-year {
        font-size: 1.5rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
    
    .order-type-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
}

/* Loading States */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Alert positioning */
.alert.position-fixed {
    top: 20px;
    right: 20px;
    max-width: 400px;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let selectedGeneration = null;
    let selectedOrderType = null;
    let availableSubjects = [];
    let availableTeachers = [];
    let availablePlatforms = [];
    let availableDossiers = [];
    let selectedItems = [];

    console.log('New Educational Cards System loaded');

    // Step 1: Generation Selection
    $(document).on('click', '.select-generation-btn', function(e) {
        e.preventDefault();
        
        const generationId = $(this).data('generation-id');
        const generationName = $(this).data('generation-name');
        
        selectedGeneration = generationId;
        
        $('#selected_generation_id').val(generationId);
        $('#selected_generation_display, #selected_generation_display2').text(generationName);
        
        $('#step1_generations').fadeOut(400, function() {
            $('#step2_order_type').fadeIn(400);
            loadSubjects(generationId);
        });
    });

    // Step 2: Order Type Selection
    $(document).on('click', '.select-order-type-btn', function(e) {
        e.preventDefault();
        
        const orderType = $(this).data('order-type');
        selectedOrderType = orderType;
        
        $('#selected_order_type').val(orderType);
        
        const typeText = orderType === 'card' ? 'بطاقة تعليمية' : 'دوسية';
        $('#selected_order_type_display').text(typeText);
        $('#form_title').text('طلب ' + typeText);
        
        // Show/hide dossier selection based on order type
        if (orderType === 'dossier') {
            $('#dossier_selection').show();
        } else {
            $('#dossier_selection').hide();
        }
        
        $('#step2_order_type').fadeOut(400, function() {
            $('#step3_order_form').fadeIn(400);
        });
    });

    // Back buttons
    $(document).on('click', '.back-to-generations-btn', function(e) {
        e.preventDefault();
        $('#step2_order_type, #step3_order_form').fadeOut(400, function() {
            $('#step1_generations').fadeIn(400);
            resetAll();
        });
    });

    $(document).on('click', '.back-to-order-type-btn', function(e) {
        e.preventDefault();
        $('#step3_order_form').fadeOut(400, function() {
            $('#step2_order_type').fadeIn(400);
            resetForm();
        });
    });

    // Load subjects
    function loadSubjects(generationId) {
        $.get(`/educational-cards/subjects/${generationId}`)
            .done(function(response) {
                availableSubjects = response.subjects;
                renderSubjects();
            })
            .fail(function() {
                $('#subjects_container').html('<div class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> خطأ في تحميل المواد</div>');
            });
    }

    // Render subjects
    function renderSubjects() {
        if (availableSubjects.length === 0) {
            $('#subjects_container').html('<div class="text-center text-muted"><i class="fas fa-info-circle"></i> لا توجد مواد متاحة</div>');
            return;
        }

        let html = '<div class="row">';
        availableSubjects.forEach(function(subject) {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="selection-item" data-type="subject" data-id="${subject.id}">
                        <div class="form-check">
                            <input class="form-check-input selection-checkbox" type="checkbox" 
                                   name="subjects[]" value="${subject.id}" id="subject_${subject.id}">
                            <label class="form-check-label w-100" for="subject_${subject.id}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 font-weight-bold">${subject.name}</h6>
                                        <small class="text-muted">مادة دراسية</small>
                                    </div>
                                    <div>
                                        <span class="badge badge-primary">${subject.formatted_price}</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        $('#subjects_container').html(html);
        bindSelectionEvents();
    }

    // Bind selection events
    function bindSelectionEvents() {
        $(document).on('change', 'input[name="subjects[]"]', function() {
            updateSelections();
            if (selectedItems.subjects && selectedItems.subjects.length > 0) {
                loadTeachers();
            } else {
                clearTeachers();
            }
        });

        $(document).on('change', 'input[name="teachers"]', function() {
            updateSelections();
            if (selectedItems.teacher) {
                loadPlatforms();
            } else {
                clearPlatforms();
            }
        });

        $(document).on('change', 'input[name="platforms"]', function() {
            updateSelections();
            if (selectedItems.platform && selectedOrderType === 'dossier') {
                loadDossiers();
            } else if (selectedItems.platform) {
                updateOrderSummary();
            }
        });

        $(document).on('change', 'input[name="dossiers[]"]', function() {
            updateSelections();
            updateOrderSummary();
        });

        $(document).on('click', '.selection-item', function(e) {
            if (e.target.type !== 'checkbox' && e.target.type !== 'radio') {
                const checkbox = $(this).find('input[type="checkbox"], input[type="radio"]');
                if (checkbox.attr('type') === 'radio') {
                    checkbox.prop('checked', true).trigger('change');
                } else {
                    checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
                }
            }
        });
    }

    // Update selections
    function updateSelections() {
        selectedItems = {
            subjects: [],
            teacher: null,
            platform: null,
            dossiers: []
        };

        $('input[name="subjects[]"]:checked').each(function() {
            selectedItems.subjects.push(parseInt($(this).val()));
        });

        const teacherRadio = $('input[name="teachers"]:checked');
        if (teacherRadio.length) {
            selectedItems.teacher = parseInt(teacherRadio.val());
        }

        const platformRadio = $('input[name="platforms"]:checked');
        if (platformRadio.length) {
            selectedItems.platform = parseInt(platformRadio.val());
        }

        $('input[name="dossiers[]"]:checked').each(function() {
            selectedItems.dossiers.push(parseInt($(this).val()));
        });

        // Update visual selection
        $('.selection-item').removeClass('selected');
        $('input:checked').closest('.selection-item').addClass('selected');
    }

    // Load teachers, platforms, dossiers functions...
    function loadTeachers() {
        if (!selectedItems.subjects || selectedItems.subjects.length === 0) return;
        
        const subjectId = selectedItems.subjects[0]; // Use first selected subject
        
        $('#teacher_selection').show();
        $('#teachers_container').html('<div class="text-center"><div class="spinner-border text-primary"></div><p class="mt-2">جاري تحميل المعلمين...</p></div>');
        
        $.get(`/educational-cards/teachers/${selectedGeneration}/${subjectId}`)
            .done(function(response) {
                renderTeachers(response.teachers);
            })
            .fail(function() {
                $('#teachers_container').html('<div class="text-center text-danger">خطأ في تحميل المعلمين</div>');
            });
    }

    function renderTeachers(teachers) {
        if (teachers.length === 0) {
            $('#teachers_container').html('<div class="text-center text-muted">لا يوجد معلمين متاحين</div>');
            return;
        }

        let html = '<div class="row">';
        teachers.forEach(function(teacher) {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="selection-item" data-type="teacher" data-id="${teacher.id}">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" 
                                   name="teachers" value="${teacher.id}" id="teacher_${teacher.id}">
                            <label class="form-check-label w-100" for="teacher_${teacher.id}">
                                <h6 class="mb-1 font-weight-bold">${teacher.name}</h6>
                                ${teacher.specialization ? `<small class="text-muted">${teacher.specialization}</small>` : ''}
                            </label>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        $('#teachers_container').html(html);
    }

    function loadPlatforms() {
        if (!selectedItems.teacher || !selectedItems.subjects || selectedItems.subjects.length === 0) return;
        
        const subjectId = selectedItems.subjects[0];
        
        $('#platform_selection').show();
        $('#platforms_container').html('<div class="text-center"><div class="spinner-border text-primary"></div><p class="mt-2">جاري تحميل المنصات...</p></div>');
        
        $.get(`/educational-cards/platforms/${selectedGeneration}/${subjectId}/${selectedItems.teacher}`)
            .done(function(response) {
                renderPlatforms(response.platforms);
            })
            .fail(function() {
                $('#platforms_container').html('<div class="text-center text-danger">خطأ في تحميل المنصات</div>');
            });
    }

    function renderPlatforms(platforms) {
        if (platforms.length === 0) {
            $('#platforms_container').html('<div class="text-center text-muted">لا توجد منصات متاحة</div>');
            return;
        }

        let html = '<div class="row">';
        platforms.forEach(function(platform) {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="selection-item" data-type="platform" data-id="${platform.id}">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" 
                                   name="platforms" value="${platform.id}" id="platform_${platform.id}">
                            <label class="form-check-label w-100" for="platform_${platform.id}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 font-weight-bold">${platform.name}</h6>
                                        <small class="text-muted">منصة تعليمية</small>
                                    </div>
                                    <div>
                                        <span class="badge badge-info">+${platform.price_percentage}%</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        $('#platforms_container').html(html);
    }

    function loadDossiers() {
        if (!selectedItems.platform || !selectedItems.subjects || selectedItems.subjects.length === 0 || !selectedItems.teacher) return;
        
        const subjectId = selectedItems.subjects[0];
        const semester = $('#semester').val() || 'first';
        
        $('#dossiers_container').html('<div class="text-center"><div class="spinner-border text-primary"></div><p class="mt-2">جاري تحميل الدوسيات...</p></div>');
        
        $.get(`/educational-cards/dossiers/${selectedGeneration}/${subjectId}/${selectedItems.teacher}/${selectedItems.platform}/${semester}`)
            .done(function(response) {
                renderDossiers(response.dossiers);
            })
            .fail(function() {
                $('#dossiers_container').html('<div class="text-center text-danger">خطأ في تحميل الدوسيات</div>');
            });
    }

    function renderDossiers(dossiers) {
        if (dossiers.length === 0) {
            $('#dossiers_container').html('<div class="text-center text-muted">لا توجد دوسيات متاحة</div>');
            return;
        }

        let html = '<div class="row">';
        dossiers.forEach(function(dossier) {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="selection-item" data-type="dossier" data-id="${dossier.id}">
                        <div class="form-check">
                            <input class="form-check-input selection-checkbox" type="checkbox" 
                                   name="dossiers[]" value="${dossier.id}" id="dossier_${dossier.id}">
                            <label class="form-check-label w-100" for="dossier_${dossier.id}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 font-weight-bold">${dossier.name}</h6>
                                        <small class="text-muted">${dossier.pages_count ? dossier.pages_count + ' صفحة' : 'دوسية'}</small>
                                    </div>
                                    <div>
                                        <span class="badge badge-success">${dossier.formatted_final_price}</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        $('#dossiers_container').html(html);
    }

    // Clear functions
    function clearTeachers() {
        $('#teacher_selection').hide();
        $('#teachers_container').html('<div class="text-center py-3"><p class="text-muted">اختر المادة أولاً</p></div>');
        clearPlatforms();
    }

    function clearPlatforms() {
        $('#platform_selection').hide();
        $('#platforms_container').html('<div class="text-center py-3"><p class="text-muted">اختر المعلم أولاً</p></div>');
        clearDossiers();
    }

    function clearDossiers() {
        $('#dossiers_container').html('<div class="text-center py-3"><p class="text-muted">اختر المنصة أولاً</p></div>');
    }

    // Update order summary
    function updateOrderSummary() {
        const data = {
            order_type: selectedOrderType,
            quantity: parseInt($('#quantity').val()) || 1
        };

        if (selectedOrderType === 'card') {
            data.subjects = selectedItems.subjects;
            data.platform_id = selectedItems.platform;
        } else {
            data.dossiers = selectedItems.dossiers;
        }

        if ((selectedOrderType === 'card' && selectedItems.subjects.length > 0 && selectedItems.platform) ||
            (selectedOrderType === 'dossier' && selectedItems.dossiers.length > 0)) {
            
            $.post('/educational-cards/calculate-price', data)
                .done(function(response) {
                    if (response.success) {
                        displayOrderSummary(response);
                        $('#submit_order_btn').prop('disabled', false);
                    }
                })
                .fail(function() {
                    hideOrderSummary();
                });
        } else {
            hideOrderSummary();
        }
    }

    function displayOrderSummary(data) {
        let itemsHtml = '<ul class="mb-2">';
        data.items.forEach(function(item) {
            itemsHtml += `<li>${item.name} - ${item.formatted_price}</li>`;
        });
        itemsHtml += '</ul>';

        const summaryHtml = `
            <div class="row">
                <div class="col-md-8">
                    <strong>العناصر المختارة (${data.items.length}):</strong><br>
                    ${itemsHtml}
                </div>
                <div class="col-md-4 text-right">
                    <strong>الكمية:</strong> ${data.quantity}<br>
                    <strong>السعر للوحدة:</strong> ${data.total_price.toFixed(2)} JD<br>
                    <strong class="text-success h5">الإجمالي: ${data.formatted_final_total}</strong>
                </div>
            </div>
        `;
        
        $('#summary_content').html(summaryHtml);
        $('#order_summary').slideDown();
    }

    function hideOrderSummary() {
        $('#order_summary').slideUp();
        $('#submit_order_btn').prop('disabled', true);
    }

    // Form submission
    $('#orderForm').on('submit', function(e) {
        const hasSelections = (selectedOrderType === 'card' && selectedItems.subjects.length > 0 && selectedItems.platform) ||
                             (selectedOrderType === 'dossier' && selectedItems.dossiers.length > 0);

        if (!hasSelections) {
            e.preventDefault();
            alert('يرجى إكمال جميع الاختيارات المطلوبة');
            return false;
        }

        $('#submit_order_btn').html('<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...').prop('disabled', true);
    });

    // Event listeners for quantity change and semester change
    $('#quantity').on('input', function() {
        updateOrderSummary();
    });

    $('#semester').on('change', function() {
        if (selectedOrderType === 'dossier' && selectedItems.platform) {
            loadDossiers();
        }
    });

    // Reset functions
    function resetForm() {
        $('#orderForm')[0].reset();
        selectedItems = [];
        $('#teacher_selection, #platform_selection').hide();
        $('#order_summary').hide();
        $('#submit_order_btn').prop('disabled', true).html('<i class="fas fa-paper-plane"></i> إرسال الطلب');
    }

    function resetAll() {
        resetForm();
        selectedGeneration = null;
        selectedOrderType = null;
        availableSubjects = [];
    }

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert-dismissible').fadeOut();
    }, 5000);
});
</script>
@endpush