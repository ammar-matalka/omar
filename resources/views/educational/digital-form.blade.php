@extends('layouts.app')

@section('title', 'البطاقات التعليمية الرقمية')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/educational.css') }}">
@endpush

@section('content')
<div class="container" style="margin-top: var(--space-xl); margin-bottom: var(--space-3xl);">
    <!-- Header Section -->
    <div class="form-header" style="text-align: center; margin-bottom: var(--space-2xl); padding: var(--space-2xl); background: linear-gradient(135deg, var(--primary-50), var(--info-50)); border-radius: var(--radius-xl); position: relative; overflow: hidden;">
        <!-- Background Pattern -->
        <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: linear-gradient(135deg, var(--primary-200), var(--primary-300)); border-radius: 50%; opacity: 0.4;"></div>
        <div style="position: absolute; bottom: -40px; left: -40px; width: 100px; height: 100px; background: linear-gradient(135deg, var(--info-200), var(--info-300)); border-radius: 50%; opacity: 0.4;"></div>
        
        <div style="position: relative; z-index: 2;">
            <!-- Breadcrumb -->
            <nav style="margin-bottom: var(--space-lg);">
                <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-sm); font-size: 0.9rem; color: var(--gray-600);">
                    <a href="{{ route('educational.index') }}" style="color: var(--primary-600); text-decoration: none; transition: color 0.2s;">النظام التعليمي</a>
                    <i class="fas fa-chevron-left" style="font-size: 0.7rem; color: var(--gray-400);"></i>
                    <span style="color: var(--gray-800); font-weight: 600;">البطاقات الرقمية</span>
                </div>
            </nav>
            
            <div style="font-size: 3rem; margin-bottom: var(--space-md);">💳</div>
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: var(--space-md); color: var(--gray-800);">
                البطاقات التعليمية الرقمية
            </h1>
            <p style="font-size: 1.1rem; color: var(--gray-600); max-width: 500px; margin: 0 auto;">
                اختر الجيل والمادة والمعلم للحصول على بطاقتك التعليمية الرقمية
            </p>
        </div>
    </div>

    <!-- Main Form -->
    <div class="educational-form-container" style="max-width: 800px; margin: 0 auto;">
        <form id="digitalEducationalForm" method="POST" action="{{ route('educational.cart.add') }}">
            @csrf
            <input type="hidden" name="product_type" value="digital">
            
            <!-- Progress Indicator -->
            <div class="progress-container" style="margin-bottom: var(--space-2xl);">
                <div style="display: flex; justify-content: space-between; align-items: center; position: relative;">
                    <!-- Progress Line -->
                    <div style="position: absolute; top: 15px; left: 10%; right: 10%; height: 3px; background: var(--gray-200); border-radius: 2px; z-index: 1;">
                        <div id="progressLine" style="height: 100%; background: linear-gradient(90deg, var(--primary-500), var(--primary-600)); border-radius: 2px; width: 0%; transition: width 0.5s ease;"></div>
                    </div>
                    
                    <!-- Steps -->
                    <div class="progress-step active" data-step="1" style="position: relative; z-index: 2; text-align: center;">
                        <div class="step-circle" style="width: 30px; height: 30px; border-radius: 50%; background: var(--primary-500); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; margin: 0 auto var(--space-xs);">1</div>
                        <span style="font-size: 0.8rem; color: var(--gray-600);">الجيل</span>
                    </div>
                    
                    <div class="progress-step" data-step="2" style="position: relative; z-index: 2; text-align: center;">
                        <div class="step-circle" style="width: 30px; height: 30px; border-radius: 50%; background: var(--gray-300); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; margin: 0 auto var(--space-xs);">2</div>
                        <span style="font-size: 0.8rem; color: var(--gray-600);">المادة</span>
                    </div>
                    
                    <div class="progress-step" data-step="3" style="position: relative; z-index: 2; text-align: center;">
                        <div class="step-circle" style="width: 30px; height: 30px; border-radius: 50%; background: var(--gray-300); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; margin: 0 auto var(--space-xs);">3</div>
                        <span style="font-size: 0.8rem; color: var(--gray-600);">المعلم</span>
                    </div>
                    
                    <div class="progress-step" data-step="4" style="position: relative; z-index: 2; text-align: center;">
                        <div class="step-circle" style="width: 30px; height: 30px; border-radius: 50%; background: var(--gray-300); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; margin: 0 auto var(--space-xs);">4</div>
                        <span style="font-size: 0.8rem; color: var(--gray-600);">المنصة</span>
                    </div>
                    
                    <div class="progress-step" data-step="5" style="position: relative; z-index: 2; text-align: center;">
                        <div class="step-circle" style="width: 30px; height: 30px; border-radius: 50%; background: var(--gray-300); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; margin: 0 auto var(--space-xs);">5</div>
                        <span style="font-size: 0.8rem; color: var(--gray-600);">الباقة</span>
                    </div>
                </div>
            </div>

            <!-- Step 1: Generation Selection -->
            <div class="form-step active" id="step1" style="margin-bottom: var(--space-2xl);">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--primary-50), var(--primary-100));">
                        <h3 class="card-title" style="color: var(--primary-700);">
                            <i class="fas fa-graduation-cap"></i>
                            اختر الجيل الدراسي
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="generationsContainer" class="options-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-md);">
                            <!-- Will be populated by JavaScript -->
                        </div>
                        <div id="generationsLoading" class="loading-state" style="text-align: center; padding: var(--space-2xl);">
                            <div style="font-size: 2rem; margin-bottom: var(--space-md); opacity: 0.6;">📚</div>
                            <p style="color: var(--gray-600);">جاري تحميل الأجيال المتاحة...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Subject Selection -->
            <div class="form-step" id="step2" style="margin-bottom: var(--space-2xl); display: none;">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--secondary-50), var(--secondary-100));">
                        <h3 class="card-title" style="color: var(--secondary-700);">
                            <i class="fas fa-book"></i>
                            اختر المادة الدراسية
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="subjectsContainer" class="options-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-md);">
                            <!-- Will be populated by JavaScript -->
                        </div>
                        <div id="subjectsLoading" class="loading-state" style="text-align: center; padding: var(--space-2xl); display: none;">
                            <div style="font-size: 2rem; margin-bottom: var(--space-md); opacity: 0.6;">📖</div>
                            <p style="color: var(--gray-600);">جاري تحميل المواد المتاحة...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Teacher Selection -->
            <div class="form-step" id="step3" style="margin-bottom: var(--space-2xl); display: none;">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--accent-50), var(--accent-100));">
                        <h3 class="card-title" style="color: var(--accent-700);">
                            <i class="fas fa-user-tie"></i>
                            اختر المعلم
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="teachersContainer" class="options-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-md);">
                            <!-- Will be populated by JavaScript -->
                        </div>
                        <div id="teachersLoading" class="loading-state" style="text-align: center; padding: var(--space-2xl); display: none;">
                            <div style="font-size: 2rem; margin-bottom: var(--space-md); opacity: 0.6;">👨‍🏫</div>
                            <p style="color: var(--gray-600);">جاري تحميل المعلمين المتاحين...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Platform Selection -->
            <div class="form-step" id="step4" style="margin-bottom: var(--space-2xl); display: none;">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--info-50), var(--info-100));">
                        <h3 class="card-title" style="color: var(--info-700);">
                            <i class="fas fa-desktop"></i>
                            اختر المنصة التعليمية
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="platformsContainer" class="options-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-md);">
                            <!-- Will be populated by JavaScript -->
                        </div>
                        <div id="platformsLoading" class="loading-state" style="text-align: center; padding: var(--space-2xl); display: none;">
                            <div style="font-size: 2rem; margin-bottom: var(--space-md); opacity: 0.6;">💻</div>
                            <p style="color: var(--gray-600);">جاري تحميل المنصات المتاحة...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5: Package Selection -->
            <div class="form-step" id="step5" style="margin-bottom: var(--space-2xl); display: none;">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--success-50), var(--success-100));">
                        <h3 class="card-title" style="color: var(--success-700);">
                            <i class="fas fa-box"></i>
                            اختر الباقة التعليمية
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="packagesContainer" class="options-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--space-md);">
                            <!-- Will be populated by JavaScript -->
                        </div>
                        <div id="packagesLoading" class="loading-state" style="text-align: center; padding: var(--space-2xl); display: none;">
                            <div style="font-size: 2rem; margin-bottom: var(--space-md); opacity: 0.6;">📦</div>
                            <p style="color: var(--gray-600);">جاري تحميل الباقات المتاحة...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quantity and Price Section -->
            <div class="form-step" id="quantityStep" style="margin-bottom: var(--space-2xl); display: none;">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--warning-50), var(--warning-100));">
                        <h3 class="card-title" style="color: var(--warning-700);">
                            <i class="fas fa-shopping-cart"></i>
                            الكمية والسعر النهائي
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Selected Items Summary -->
                        <div id="selectionSummary" style="margin-bottom: var(--space-xl); padding: var(--space-lg); background: var(--gray-50); border-radius: var(--radius-lg); border: 2px solid var(--gray-200);">
                            <h4 style="font-weight: 600; margin-bottom: var(--space-md); color: var(--gray-800);">
                                <i class="fas fa-check-circle" style="color: var(--success-600); margin-left: var(--space-sm);"></i>
                                ملخص اختيارك
                            </h4>
                            <div id="summaryContent" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-md); font-size: 0.9rem;">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>

                        <!-- Quantity Selection -->
                        <div style="margin-bottom: var(--space-xl);">
                            <label for="quantity" class="form-label" style="font-weight: 600; color: var(--gray-800);">
                                <i class="fas fa-sort-numeric-up" style="margin-left: var(--space-sm); color: var(--primary-600);"></i>
                                عدد البطاقات المطلوبة
                            </label>
                            <div style="display: flex; align-items: center; gap: var(--space-md); margin-top: var(--space-sm);">
                                <button type="button" id="decreaseQty" class="btn btn-secondary" style="padding: var(--space-sm) var(--space-md);">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" class="form-input" style="width: 80px; text-align: center; font-weight: 600; font-size: 1.1rem;">
                                <button type="button" id="increaseQty" class="btn btn-secondary" style="padding: var(--space-sm) var(--space-md);">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <span style="color: var(--gray-600); font-size: 0.9rem; margin-right: var(--space-md);">الحد الأقصى: 10 بطاقات</span>
                            </div>
                        </div>

                        <!-- Price Display -->
                        <div id="priceDisplay" style="padding: var(--space-xl); background: linear-gradient(135deg, var(--success-50), var(--primary-50)); border-radius: var(--radius-lg); border: 2px solid var(--success-200); text-align: center;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-lg);">
                                <div>
                                    <div style="font-size: 0.9rem; color: var(--gray-600); margin-bottom: var(--space-xs);">سعر البطاقة الواحدة</div>
                                    <div id="unitPrice" style="font-size: 1.2rem; font-weight: 700; color: var(--gray-800);">-- دينار</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.9rem; color: var(--gray-600); margin-bottom: var(--space-xs);">المجموع الفرعي</div>
                                    <div id="subtotal" style="font-size: 1.2rem; font-weight: 700; color: var(--gray-800);">-- دينار</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.9rem; color: var(--success-600); margin-bottom: var(--space-xs);">الشحن</div>
                                    <div id="shippingCost" style="font-size: 1.2rem; font-weight: 700; color: var(--success-600);">مجاني</div>
                                </div>
                            </div>
                            <div style="border-top: 2px solid var(--success-300); padding-top: var(--space-lg);">
                                <div style="font-size: 1rem; color: var(--gray-600); margin-bottom: var(--space-sm);">السعر النهائي</div>
                                <div id="totalPrice" style="font-size: 2rem; font-weight: 900; background: linear-gradient(135deg, var(--success-600), var(--primary-600)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                    -- دينار
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="form-navigation" style="display: flex; justify-content: space-between; align-items: center; margin-top: var(--space-2xl); padding-top: var(--space-xl); border-top: 2px solid var(--gray-200);">
                <button type="button" id="backBtn" class="btn btn-secondary btn-lg" style="display: none;">
                    <i class="fas fa-arrow-right" style="margin-left: var(--space-sm);"></i>
                    السابق
                </button>
                
                <div style="flex: 1;"></div>
                
                <button type="button" id="nextBtn" class="btn btn-primary btn-lg" style="display: none;">
                    التالي
                    <i class="fas fa-arrow-left" style="margin-right: var(--space-sm);"></i>
                </button>
                
                <button type="submit" id="addToCartBtn" class="btn btn-success btn-lg" style="display: none; padding: var(--space-md) var(--space-2xl); font-size: 1.1rem; font-weight: 700;">
                    <i class="fas fa-cart-plus" style="margin-left: var(--space-sm);"></i>
                    إضافة إلى السلة
                </button>
            </div>

            <!-- Hidden Form Fields -->
            <input type="hidden" name="generation_id" id="generation_id">
            <input type="hidden" name="subject_id" id="subject_id">
            <input type="hidden" name="teacher_id" id="teacher_id">
            <input type="hidden" name="platform_id" id="platform_id">
            <input type="hidden" name="package_id" id="package_id">
        </form>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: var(--space-2xl); border-radius: var(--radius-xl); text-align: center; max-width: 300px;">
        <div style="font-size: 3rem; margin-bottom: var(--space-lg);">⏳</div>
        <h3 style="margin-bottom: var(--space-md); color: var(--gray-800);">جاري المعالجة...</h3>
        <p style="color: var(--gray-600); font-size: 0.9rem;">يرجى الانتظار</p>
    </div>
</div>

<style>
.form-step {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.form-step:not(.active) {
    opacity: 0;
    transform: translateX(20px);
    pointer-events: none;
}

.option-card {
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.option-card:hover {
    border-color: var(--primary-400);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.option-card.selected {
    border-color: var(--primary-500);
    background: linear-gradient(135deg, var(--primary-50), var(--primary-100));
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.teacher-card {
    display: flex;
    align-items: center;
    gap: var(--space-md);
}

.teacher-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.progress-step.active .step-circle {
    background: var(--primary-500) !important;
}

.progress-step.completed .step-circle {
    background: var(--success-500) !important;
}

.loading-state {
    opacity: 0.7;
}

@media (max-width: 768px) {
    .options-grid {
        grid-template-columns: 1fr !important;
    }
    
    .form-navigation {
        flex-direction: column;
        gap: var(--space-md);
    }
    
    .form-navigation > div {
        display: none;
    }
    
    #priceDisplay {
        text-align: center;
    }
    
    #priceDisplay > div {
        grid-template-columns: 1fr !important;
    }
}
</style>

@push('scripts')
<script src="{{ asset('js/educational-system.js') }}"></script>
<script>
// Initialize Digital Educational Form
document.addEventListener('DOMContentLoaded', function() {
    initializeDigitalForm();
});
</script>
@endpush
@endsection