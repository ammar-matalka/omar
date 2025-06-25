@extends('layouts.admin')

@section('title', 'إضافة مخزون جديد')

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

.inventory-preview {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-radius: 0.75rem;
    padding: 2rem;
    text-align: center;
}

.quantity-display {
    font-size: 3rem;
    font-weight: 900;
    margin: 1rem 0;
}

.stock-calculation {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
}

.existing-inventory-warning {
    background: #fef3c7;
    border: 1px solid #fbbf24;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
}

.quantity-suggestions {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .chain-step {
        text-align: center;
    }
    
    .step-number {
        margin: 0 auto 0.5rem;
    }
    
    .quantity-display {
        font-size: 2rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">إضافة مخزون جديد</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.educational.inventory.index') }}">المخزون</a></li>
                    <li class="breadcrumb-item active">إضافة مخزون جديد</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.educational.inventory.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>

    <form action="{{ route('admin.educational.inventory.store') }}" method="POST" id="inventoryForm">
        @csrf
        
        <!-- Educational Chain Selection -->
        <div class="form-section">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-link me-2"></i>اختيار المنتج
                </h5>
            </div>
            <div class="section-content">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    اختر السلسلة التعليمية والباقة لإنشاء مخزون للدوسيات الورقية فقط
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
                                <h6 class="mb-2">اختر الباقة (الدوسيات الورقية فقط)</h6>
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

        <!-- Package Information -->
        <div class="form-section" id="package-info-section" style="display: none;">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>معلومات الباقة
                </h5>
            </div>
            <div class="section-content">
                <div id="package-info">
                    <!-- Will be populated by JavaScript -->
                </div>
                
                <div id="existing-inventory-warning" style="display: none;">
                    <!-- Will show if inventory already exists -->
                </div>
            </div>
        </div>

        <!-- Inventory Quantities -->
        <div class="form-section" id="quantities-section" style="display: none;">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-warehouse me-2"></i>كميات المخزون
                </h5>
            </div>
            <div class="section-content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group mb-4">
                            <label for="quantity_available" class="form-label">الكمية المتاحة <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="quantity_available" id="quantity_available" class="form-control" 
                                       value="{{ old('quantity_available', 0) }}" required min="0" max="100000"
                                       placeholder="0">
                                <span class="input-group-text">قطعة</span>
                            </div>
                            <div class="form-text">
                                العدد الإجمالي للدوسيات المتاحة للبيع
                            </div>
                            @error('quantity_available')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="quantity_reserved" class="form-label">الكمية المحجوزة</label>
                            <div class="input-group">
                                <input type="number" name="quantity_reserved" id="quantity_reserved" class="form-control" 
                                       value="{{ old('quantity_reserved', 0) }}" min="0" max="100000"
                                       placeholder="0">
                                <span class="input-group-text">قطعة</span>
                            </div>
                            <div class="form-text">
                                عدد القطع المحجوزة للطلبات المؤكدة (اختياري)
                            </div>
                            @error('quantity_reserved')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quick Quantity Suggestions -->
                        <div class="quantity-suggestions">
                            <h6><i class="fas fa-lightbulb me-2"></i>اقتراحات كميات سريعة</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuantity(50)">
                                        50 قطعة
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuantity(100)">
                                        100 قطعة
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuantity(200)">
                                        200 قطعة
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuantity(500)">
                                        500 قطعة
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Calculation -->
                        <div class="stock-calculation">
                            <h6><i class="fas fa-calculator me-2"></i>حساب المخزون</h6>
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <h6 class="text-muted">إجمالي المخزون</h6>
                                        <h4 class="text-primary" id="total-stock">0</h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <h6 class="text-muted">المحجوز</h6>
                                        <h4 class="text-warning" id="reserved-stock">0</h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted">المتاح الفعلي</h6>
                                    <h4 class="text-success" id="actual-available">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Inventory Preview -->
                        <div class="inventory-preview" id="inventory-preview">
                            <h6 class="mb-3">معاينة المخزون</h6>
                            <div class="quantity-display" id="preview-quantity">0</div>
                            <div>قطعة متاحة</div>
                            
                            <div class="mt-3" id="stock-status-preview">
                                <small id="status-text">لا يوجد مخزون</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="form-section" id="summary-section" style="display: none;">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>ملخص المخزون الجديد
                </h5>
            </div>
            <div class="section-content">
                <div class="row" id="inventory-summary">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-section">
            <div class="section-content">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.educational.inventory.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </a>
                    <div>
                        <button type="submit" name="action" value="save" class="btn btn-success me-2">
                            <i class="fas fa-save me-2"></i>حفظ المخزون
                        </button>
                        <button type="submit" name="action" value="save_and_add" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>حفظ وإضافة آخر
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let selectedChain = {
    generation: null,
    subject: null,
    teacher: null,
    platform: null,
    package: null,
    packageData: null
};

// Initialize from URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const packageId = urlParams.get('package_id');
    
    if (packageId) {
        // Auto-select package if provided in URL
        fetchPackageDetails(packageId);
    }
    
    // Initialize event listeners
    initializeEventListeners();
});

function initializeEventListeners() {
    // Chain selection events
    document.getElementById('generation_id').addEventListener('change', handleGenerationChange);
    document.getElementById('subject_id').addEventListener('change', handleSubjectChange);
    document.getElementById('teacher_id').addEventListener('change', handleTeacherChange);
    document.getElementById('platform_id').addEventListener('change', handlePlatformChange);
    document.getElementById('package_id').addEventListener('change', handlePackageChange);
    
    // Quantity input events
    document.getElementById('quantity_available').addEventListener('input', updateInventoryPreview);
    document.getElementById('quantity_reserved').addEventListener('input', updateInventoryPreview);
}

// Chain handlers (similar to pricing create)
function handleGenerationChange() {
    const generationId = this.value;
    selectedChain.generation = generationId;
    
    if (generationId) {
        updateStepStatus('generation', 'completed');
        updateStepStatus('subject', 'active');
        loadSubjects(generationId);
    } else {
        resetFromStep('generation');
    }
}

function handleSubjectChange() {
    const subjectId = this.value;
    selectedChain.subject = subjectId;
    
    if (subjectId) {
        updateStepStatus('subject', 'completed');
        updateStepStatus('teacher', 'active');
        loadTeachers(subjectId);
    } else {
        resetFromStep('subject');
    }
}

function handleTeacherChange() {
    const teacherId = this.value;
    selectedChain.teacher = teacherId;
    
    if (teacherId) {
        updateStepStatus('teacher', 'completed');
        updateStepStatus('platform', 'active');
        loadPlatforms(teacherId);
    } else {
        resetFromStep('teacher');
    }
}

function handlePlatformChange() {
    const platformId = this.value;
    selectedChain.platform = platformId;
    
    if (platformId) {
        updateStepStatus('platform', 'completed');
        updateStepStatus('package', 'active');
        loadPackages(platformId);
    } else {
        resetFromStep('platform');
    }
}

function handlePackageChange() {
    const packageId = this.value;
    selectedChain.package = packageId;
    
    if (packageId) {
        updateStepStatus('package', 'completed');
        fetchPackageDetails(packageId);
    } else {
        resetFromStep('package');
    }
}

// Load chain data functions (similar to pricing create but filter for physical packages only)
function loadSubjects(generationId) {
    fetch(`/admin/educational/api/subjects?generation_id=${generationId}`)
        .then(response => response.json())
        .then(data => {
            const subjectSelect = document.getElementById('subject_id');
            subjectSelect.innerHTML = '<option value="">اختر المادة...</option>';
            
            data.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                subjectSelect.appendChild(option);
            });
            
            subjectSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading subjects:', error);
            showAlert('error', 'خطأ في تحميل المواد');
        });
}

function loadTeachers(subjectId) {
    fetch(`/admin/educational/api/teachers?subject_id=${subjectId}`)
        .then(response => response.json())
        .then(data => {
            const teacherSelect = document.getElementById('teacher_id');
            teacherSelect.innerHTML = '<option value="">اختر المعلم...</option>';
            
            data.forEach(teacher => {
                const option = document.createElement('option');
                option.value = teacher.id;
                option.textContent = teacher.name;
                teacherSelect.appendChild(option);
            });
            
            teacherSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading teachers:', error);
            showAlert('error', 'خطأ في تحميل المعلمين');
        });
}

function loadPlatforms(teacherId) {
    fetch(`/admin/educational/api/platforms?teacher_id=${teacherId}`)
        .then(response => response.json())
        .then(data => {
            const platformSelect = document.getElementById('platform_id');
            platformSelect.innerHTML = '<option value="">اختر المنصة...</option>';
            
            data.forEach(platform => {
                const option = document.createElement('option');
                option.value = platform.id;
                option.textContent = platform.name;
                platformSelect.appendChild(option);
            });
            
            platformSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading platforms:', error);
            showAlert('error', 'خطأ في تحميل المنصات');
        });
}

function loadPackages(platformId) {
    // Only load physical packages for inventory
    fetch(`/admin/educational/api/packages?platform_id=${platformId}&type=physical`)
        .then(response => response.json())
        .then(data => {
            const packageSelect = document.getElementById('package_id');
            packageSelect.innerHTML = '<option value="">اختر الباقة...</option>';
            
            if (data.length === 0) {
                packageSelect.innerHTML = '<option value="">لا توجد دوسيات ورقية في هذه المنصة</option>';
                packageSelect.disabled = true;
                return;
            }
            
            data.forEach(pkg => {
                const option = document.createElement('option');
                option.value = pkg.id;
                option.textContent = pkg.name + ' (دوسية ورقية)';
                packageSelect.appendChild(option);
            });
            
            packageSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading packages:', error);
            showAlert('error', 'خطأ في تحميل الباقات');
        });
}

// Fetch package details and check for existing inventory
function fetchPackageDetails(packageId) {
    fetch(`/admin/educational/api/package-details/${packageId}`)
        .then(response => response.json())
        .then(data => {
            selectedChain.packageData = data;
            
            if (data.is_digital) {
                showAlert('error', 'لا يمكن إنشاء مخزون للبطاقات الرقمية');
                return;
            }
            
            displayPackageInfo(data);
            checkExistingInventory();
            showQuantitiesSection();
            updateInventorySummary();
        })
        .catch(error => {
            console.error('Error fetching package details:', error);
            showAlert('error', 'خطأ في تحميل تفاصيل الباقة');
        });
}

// Display package information
function displayPackageInfo(packageData) {
    const packageInfo = document.getElementById('package-info');
    
    packageInfo.innerHTML = `
        <div class="d-flex align-items-center p-3 bg-light rounded">
            <div class="me-3">
                <i class="fas fa-book fa-2x text-warning"></i>
            </div>
            <div>
                <h6 class="mb-1">${packageData.name}</h6>
                <span class="badge bg-warning">دوسية ورقية</span>
                <div class="mt-2">
                    ${packageData.pages_count ? `<small class="d-block text-muted">عدد الصفحات: ${packageData.pages_count}</small>` : ''}
                    ${packageData.weight_grams ? `<small class="d-block text-muted">الوزن: ${packageData.weight_grams} جرام</small>` : ''}
                </div>
                ${packageData.description ? `<p class="text-muted small mb-0 mt-1">${packageData.description}</p>` : ''}
            </div>
        </div>
    `;
    
    document.getElementById('package-info-section').style.display = 'block';
}

// Check for existing inventory
function checkExistingInventory() {
    if (!selectedChain.packageData) return;
    
    fetch(`/admin/educational/api/check-existing-inventory`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            generation_id: selectedChain.generation,
            subject_id: selectedChain.subject,
            teacher_id: selectedChain.teacher,
            platform_id: selectedChain.platform,
            package_id: selectedChain.package
        })
    })
    .then(response => response.json())
    .then(data => {
        const warningDiv = document.getElementById('existing-inventory-warning');
        
        if (data.exists) {
            warningDiv.innerHTML = `
                <div class="existing-inventory-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>تحذير:</strong> يوجد مخزون لهذا المنتج بالفعل.
                    <br>الكمية الحالية: <strong>${data.inventory.quantity_available}</strong> قطعة متاحة، 
                    <strong>${data.inventory.quantity_reserved}</strong> قطعة محجوزة.
                    <br><a href="/admin/educational/inventory/${data.inventory.id}" class="btn btn-sm btn-outline-warning mt-2">
                        <i class="fas fa-eye me-1"></i>عرض المخزون الموجود
                    </a>
                </div>
            `;
            warningDiv.style.display = 'block';
        } else {
            warningDiv.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error checking existing inventory:', error);
    });
}

// Show quantities section
function showQuantitiesSection() {
    document.getElementById('quantities-section').style.display = 'block';
    updateInventoryPreview();
}

// Update step status (same as pricing)
function updateStepStatus(step, status) {
    const stepElement = document.getElementById(`step-${step}`);
    const stepNumber = stepElement.querySelector('.step-number');
    
    // Remove all status classes
    stepElement.classList.remove('active', 'completed');
    stepNumber.classList.remove('active', 'completed');
    
    // Add new status
    if (status) {
        stepElement.classList.add(status);
        stepNumber.classList.add(status);
    }
}

// Reset from specific step (same as pricing)
function resetFromStep(step) {
    const steps = ['generation', 'subject', 'teacher', 'platform', 'package'];
    const stepIndex = steps.indexOf(step);
    
    for (let i = stepIndex; i < steps.length; i++) {
        const currentStep = steps[i];
        selectedChain[currentStep] = null;
        
        if (i === stepIndex) {
            updateStepStatus(currentStep, 'active');
        } else {
            updateStepStatus(currentStep, null);
        }
        
        // Reset select elements
        if (i > stepIndex) {
            const selectElement = document.getElementById(`${currentStep}_id`);
            selectElement.innerHTML = `<option value="">اختر ${getStepLabel(currentStep)}...</option>`;
            selectElement.disabled = true;
        }
    }
    
    // Hide sections if needed
    if (stepIndex <= 4) {
        document.getElementById('package-info-section').style.display = 'none';
        document.getElementById('quantities-section').style.display = 'none';
        document.getElementById('summary-section').style.display = 'none';
    }
}

// Get step label
function getStepLabel(step) {
    const labels = {
        'generation': 'الجيل',
        'subject': 'المادة',
        'teacher': 'المعلم',
        'platform': 'المنصة',
        'package': 'الباقة'
    };
    return labels[step] || step;
}

// Set quantity helper
function setQuantity(amount) {
    document.getElementById('quantity_available').value = amount;
    updateInventoryPreview();
}

// Update inventory preview
function updateInventoryPreview() {
    const available = parseInt(document.getElementById('quantity_available').value) || 0;
    const reserved = parseInt(document.getElementById('quantity_reserved').value) || 0;
    const actualAvailable = Math.max(0, available - reserved);
    
    // Update preview
    document.getElementById('preview-quantity').textContent = actualAvailable;
    
    // Update stock calculation
    document.getElementById('total-stock').textContent = available;
    document.getElementById('reserved-stock').textContent = reserved;
    document.getElementById('actual-available').textContent = actualAvailable;
    
    // Update status
    const statusText = document.getElementById('status-text');
    if (actualAvailable <= 0) {
        statusText.textContent = 'نفدت الكمية';
        statusText.className = 'text-danger';
    } else if (actualAvailable <= 10) {
        statusText.textContent = 'مخزون منخفض';
        statusText.className = 'text-warning';
    } else if (actualAvailable <= 50) {
        statusText.textContent = 'متوفر';
        statusText.className = 'text-info';
    } else {
        statusText.textContent = 'متوفر بكثرة';
        statusText.className = 'text-success';
    }
    
    updateInventorySummary();
}

// Update inventory summary
function updateInventorySummary() {
    const summary = document.getElementById('inventory-summary');
    if (!selectedChain.packageData) return;
    
    const available = parseInt(document.getElementById('quantity_available').value) || 0;
    const reserved = parseInt(document.getElementById('quantity_reserved').value) || 0;
    const data = selectedChain.packageData;
    
    summary.innerHTML = `
        <div class="col-md-3">
            <strong>المنتج:</strong><br>
            <span class="text-muted">${data.name}</span>
            <span class="badge bg-warning ms-2">دوسية</span>
        </div>
        <div class="col-md-3">
            <strong>السلسلة:</strong><br>
            <span class="text-muted">${data.chain?.generation || 'غير محدد'} - ${data.chain?.subject || 'غير محدد'}</span>
        </div>
        <div class="col-md-2">
            <strong>الكمية المتاحة:</strong><br>
            <span class="text-success fs-5">${available}</span>
        </div>
        <div class="col-md-2">
            <strong>المحجوز:</strong><br>
            <span class="text-warning fs-5">${reserved}</span>
        </div>
        <div class="col-md-2">
            <strong>الفعلي:</strong><br>
            <span class="text-primary fs-5">${Math.max(0, available - reserved)}</span>
        </div>
    `;
    
    document.getElementById('summary-section').style.display = 'block';
}

// Form validation
document.getElementById('inventoryForm').addEventListener('submit', function(e) {
    const packageId = document.getElementById('package_id').value;
    const available = parseInt(document.getElementById('quantity_available').value);
    const reserved = parseInt(document.getElementById('quantity_reserved').value) || 0;
    
    if (!packageId) {
        e.preventDefault();
        alert('يرجى اختيار الباقة');
        return;
    }
    
    if (!available || available <= 0) {
        e.preventDefault();
        alert('يرجى إدخال كمية متاحة صحيحة');
        return;
    }
    
    if (reserved > available) {
        e.preventDefault();
        alert('الكمية المحجوزة لا يمكن أن تتجاوز الكمية المتاحة');
        return;
    }
    
    // Check if this will create very large inventory
    if (available > 10000) {
        if (!confirm(`تحذير: الكمية المطلوبة كبيرة جداً (${available} قطعة).\n\nهل أنت متأكد من المتابعة؟`)) {
            e.preventDefault();
            return;
        }
    }
});

// Show alert messages
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endpush