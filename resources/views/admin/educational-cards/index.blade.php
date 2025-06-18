@extends('layouts.app')

@section('title', __('Educational System') . ' - ' . config('app.name'))

@push('styles')
<style>
    .educational-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        text-align: center;
        margin-bottom: var(--space-2xl);
        border-radius: var(--radius-lg);
    }
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-md);
    }
    
    .hero-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: var(--space-lg);
    }
    
    .generation-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .generation-card {
        background: var(--surface);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        text-align: center;
        transition: all var(--transition-normal);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .generation-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
        transform: scaleX(0);
        transition: transform var(--transition-normal);
    }
    
    .generation-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-300);
    }
    
    .generation-card:hover::before {
        transform: scaleX(1);
    }
    
    .generation-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto var(--space-lg);
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--primary-600);
        transition: all var(--transition-normal);
    }
    
    .generation-card:hover .generation-icon {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        transform: scale(1.1);
    }
    
    .generation-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: var(--space-sm);
    }
    
    .generation-description {
        color: var(--on-surface-variant);
        margin-bottom: var(--space-lg);
        line-height: 1.6;
    }
    
    .generation-stats {
        display: flex;
        justify-content: space-around;
        margin-bottom: var(--space-lg);
        padding: var(--space-md) 0;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-600);
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .order-form {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-2xl);
        margin-bottom: var(--space-2xl);
        box-shadow: var(--shadow-md);
        display: none;
    }
    
    .form-section {
        margin-bottom: var(--space-xl);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: var(--space-lg);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-lg);
    }
    
    .form-group {
        margin-bottom: var(--space-lg);
    }
    
    .form-label {
        display: block;
        margin-bottom: var(--space-sm);
        font-weight: 500;
        color: var(--on-surface);
        font-size: 0.875rem;
    }
    
    .form-label.required::after {
        content: ' *';
        color: var(--error-500);
    }
    
    .form-input {
        width: 100%;
        padding: var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--focus-ring);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .form-input:disabled {
        background: var(--surface-variant);
        color: var(--on-surface-variant);
        cursor: not-allowed;
    }
    
    .form-help {
        color: var(--on-surface-variant);
        font-size: 0.75rem;
        margin-top: var(--space-xs);
    }
    
    .order-type-selection {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-lg);
        margin-bottom: var(--space-xl);
    }
    
    .order-type-card {
        background: var(--surface);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        text-align: center;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .order-type-card:hover {
        border-color: var(--primary-400);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .order-type-card.selected {
        border-color: var(--primary-500);
        background: var(--primary-50);
    }
    
    .order-type-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto var(--space-md);
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--primary-600);
        transition: all var(--transition-normal);
    }
    
    .order-type-card.selected .order-type-icon {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
    }
    
    .order-type-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: var(--space-sm);
        color: var(--on-surface);
    }
    
    .order-type-description {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .price-calculator {
        background: var(--primary-50);
        border: 1px solid var(--primary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-lg);
        text-align: center;
    }
    
    .price-title {
        font-weight: 600;
        margin-bottom: var(--space-md);
        color: var(--primary-700);
    }
    
    .price-breakdown {
        margin-bottom: var(--space-md);
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-sm) 0;
        border-bottom: 1px solid var(--primary-200);
    }
    
    .price-row:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 1.125rem;
        color: var(--primary-700);
        margin-top: var(--space-sm);
        padding-top: var(--space-md);
        border-top: 2px solid var(--primary-300);
    }
    
    .loading-spinner {
        display: none;
        text-align: center;
        padding: var(--space-lg);
        color: var(--primary-600);
    }
    
    .loading-spinner.show {
        display: block;
    }
    
    .error-message {
        background: var(--error-100);
        color: var(--error-800);
        padding: var(--space-md);
        border-radius: var(--radius-md);
        margin-top: var(--space-md);
        display: none;
    }
    
    .error-message.show {
        display: block;
    }
    
    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: var(--space-2xl);
    }
    
    .step {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-lg);
        background: var(--surface-variant);
        color: var(--on-surface-variant);
        border-radius: var(--radius-md);
        margin: 0 var(--space-xs);
        font-size: 0.875rem;
        font-weight: 500;
        transition: all var(--transition-fast);
    }
    
    .step.active {
        background: var(--primary-500);
        color: white;
    }
    
    .step.completed {
        background: var(--success-500);
        color: white;
    }
    
    .form-actions {
        display: flex;
        gap: var(--space-lg);
        justify-content: flex-end;
        padding-top: var(--space-xl);
        border-top: 1px solid var(--border-color);
    }
    
    @media (max-width: 768px) {
        .generation-cards {
            grid-template-columns: 1fr;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .order-type-selection {
            grid-template-columns: 1fr;
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .generation-icon {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="educational-hero fade-in">
        <h1 class="hero-title">{{ __('Educational System') }}</h1>
        <p class="hero-subtitle">{{ __('Choose your generation and start your educational journey') }}</p>
    </div>
    
    <!-- Generation Selection -->
    <div class="generation-cards fade-in" id="generationCards">
        @foreach($generations as $generation)
            <div class="generation-card" onclick="selectGeneration({{ $generation->id }}, '{{ $generation->display_name }}')">
                <div class="generation-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="generation-name">{{ $generation->display_name }}</h3>
                @if($generation->description)
                    <p class="generation-description">{{ $generation->description }}</p>
                @endif
                
                <div class="generation-stats">
                    <div class="stat-item">
                        <div class="stat-number">{{ $generation->subjects_count ?? 0 }}</div>
                        <div class="stat-label">{{ __('Subjects') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $generation->orders_count ?? 0 }}</div>
                        <div class="stat-label">{{ __('Orders') }}</div>
                    </div>
                </div>
                
                <button class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Select Generation') }}
                </button>
            </div>
        @endforeach
    </div>
    
    <!-- Order Form -->
    <div class="order-form" id="orderForm">
        <div class="step-indicator">
            <div class="step active" id="step1">
                <i class="fas fa-user-graduate"></i>
                {{ __('Generation') }}
            </div>
            <div class="step" id="step2">
                <i class="fas fa-clipboard-list"></i>
                {{ __('Order Type') }}
            </div>
            <div class="step" id="step3">
                <i class="fas fa-shopping-cart"></i>
                {{ __('Details') }}
            </div>
            <div class="step" id="step4">
                <i class="fas fa-credit-card"></i>
                {{ __('Checkout') }}
            </div>
        </div>
        
        <form id="educationalOrderForm" action="{{ route('educational-cards.submit-order') }}" method="POST">
            @csrf
            <input type="hidden" name="generation_id" id="selectedGenerationId">
            <input type="hidden" name="order_type" id="selectedOrderType">
            
            <!-- Step 1: Generation (Already Selected) -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user-graduate"></i>
                    {{ __('Selected Generation') }}
                </h3>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span id="selectedGenerationName"></span>
                </div>
            </div>
            
            <!-- Step 2: Order Type Selection -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-clipboard-list"></i>
                    {{ __('Choose Order Type') }}
                </h3>
                
                <div class="order-type-selection">
                    <div class="order-type-card" onclick="selectOrderType('card')">
                        <div class="order-type-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <h4 class="order-type-title">{{ __('Educational Card') }}</h4>
                        <p class="order-type-description">{{ __('Order subjects for educational cards') }}</p>
                    </div>
                    
                    <div class="order-type-card" onclick="selectOrderType('dossier')">
                        <div class="order-type-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h4 class="order-type-title">{{ __('Dossier') }}</h4>
                        <p class="order-type-description">{{ __('Order specific teacher dossiers') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Step 3: Order Details -->
            <div class="form-section" id="orderDetailsSection" style="display: none;">
                <h3 class="section-title">
                    <i class="fas fa-shopping-cart"></i>
                    {{ __('Order Details') }}
                </h3>
                
                <!-- Student Information -->
                <div class="form-group">
                    <label for="student_name" class="form-label required">{{ __('Student Name') }}</label>
                    <input type="text" 
                           id="student_name" 
                           name="student_name" 
                           class="form-input"
                           placeholder="{{ __('Enter student full name') }}"
                           required>
                    <div class="form-help">{{ __('Full name of the student') }}</div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               class="form-input"
                               placeholder="{{ __('Phone number') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="address" class="form-label">{{ __('Address') }}</label>
                        <input type="text" 
                               id="address" 
                               name="address" 
                               class="form-input"
                               placeholder="{{ __('Delivery address') }}">
                    </div>
                </div>
                
                <!-- Educational Details -->
                <div class="form-grid" id="educationalDetails">
                    <!-- Subject Selection (for both types) -->
                    <div class="form-group">
                        <label for="subject_id" class="form-label required">{{ __('Subject') }}</label>
                        <select id="subject_id" 
                                name="subject_id" 
                                class="form-input"
                                required
                                onchange="loadTeachers()"
                                disabled>
                            <option value="">{{ __('Select Subject') }}</option>
                        </select>
                        <div class="loading-spinner" id="subjectLoading">
                            <i class="fas fa-spinner fa-spin"></i> {{ __('Loading subjects...') }}
                        </div>
                    </div>
                    
                    <!-- Semester Selection -->
                    <div class="form-group">
                        <label for="semester" class="form-label required">{{ __('Semester') }}</label>
                        <select id="semester" 
                                name="semester" 
                                class="form-input"
                                required
                                onchange="updateDossiers()">
                            <option value="">{{ __('Select Semester') }}</option>
                            <option value="first">{{ __('First Semester') }}</option>
                            <option value="second">{{ __('Second Semester') }}</option>
                            <option value="both">{{ __('Both Semesters') }}</option>
                        </select>
                    </div>
                    
                    <!-- Teacher Selection (for dossiers) -->
                    <div class="form-group" id="teacherGroup" style="display: none;">
                        <label for="teacher_id" class="form-label required">{{ __('Teacher') }}</label>
                        <select id="teacher_id" 
                                name="teacher_id" 
                                class="form-input"
                                onchange="loadPlatforms()"
                                disabled>
                            <option value="">{{ __('Select Teacher') }}</option>
                        </select>
                        <div class="loading-spinner" id="teacherLoading">
                            <i class="fas fa-spinner fa-spin"></i> {{ __('Loading teachers...') }}
                        </div>
                    </div>
                    
                    <!-- Platform Selection (for dossiers) -->
                    <div class="form-group" id="platformGroup" style="display: none;">
                        <label for="platform_id" class="form-label required">{{ __('Platform') }}</label>
                        <select id="platform_id" 
                                name="platform_id" 
                                class="form-input"
                                onchange="loadDossiers()"
                                disabled>
                            <option value="">{{ __('Select Platform') }}</option>
                        </select>
                        <div class="loading-spinner" id="platformLoading">
                            <i class="fas fa-spinner fa-spin"></i> {{ __('Loading platforms...') }}
                        </div>
                    </div>
                    
                    <!-- Dossier Selection (for dossiers only) -->
                    <div class="form-group" id="dossierGroup" style="display: none;">
                        <label for="dossier_id" class="form-label required">{{ __('Dossier') }}</label>
                        <select id="dossier_id" 
                                name="dossier_id" 
                                class="form-input"
                                onchange="calculatePrice()"
                                disabled>
                            <option value="">{{ __('Select Dossier') }}</option>
                        </select>
                        <div class="loading-spinner" id="dossierLoading">
                            <i class="fas fa-spinner fa-spin"></i> {{ __('Loading dossiers...') }}
                        </div>
                    </div>
                    
                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="quantity" class="form-label required">{{ __('Quantity') }}</label>
                        <input type="number" 
                               id="quantity" 
                               name="quantity" 
                               class="form-input"
                               value="1"
                               min="1"
                               max="10"
                               required
                               onchange="calculatePrice()">
                        <div class="form-help">{{ __('Number of copies needed') }}</div>
                    </div>
                </div>
                
                <!-- Notes -->
                <div class="form-group">
                    <label for="notes" class="form-label">{{ __('Additional Notes') }}</label>
                    <textarea id="notes" 
                              name="notes" 
                              class="form-input"
                              rows="3"
                              placeholder="{{ __('Any additional notes or requests...') }}"></textarea>
                </div>
            </div>
            
            <!-- Price Calculator -->
            <div class="price-calculator" id="priceCalculator" style="display: none;">
                <div class="price-title">{{ __('Order Summary') }}</div>
                <div class="price-breakdown" id="priceBreakdown">
                    <!-- Price details will be populated here -->
                </div>
            </div>
            
            <!-- Error Messages -->
            <div class="error-message" id="errorMessage"></div>
            
            <!-- Form Actions -->
            <div class="form-actions" id="formActions" style="display: none;">
                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel') }}
                </button>
                
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-shopping-cart"></i>
                    {{ __('Place Order') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variables
let selectedGeneration = null;
let selectedOrderType = null;
let subjects = [];
let teachers = [];
let platforms = [];
let dossiers = [];

// Select generation
function selectGeneration(generationId, generationName) {
    selectedGeneration = { id: generationId, name: generationName };
    
    // Update form
    document.getElementById('selectedGenerationId').value = generationId;
    document.getElementById('selectedGenerationName').textContent = generationName;
    
    // Show order form
    document.getElementById('generationCards').style.display = 'none';
    document.getElementById('orderForm').style.display = 'block';
    
    // Update steps
    document.getElementById('step1').classList.add('completed');
    document.getElementById('step2').classList.add('active');
    
    // Load subjects for this generation
    loadSubjects();
    
    // Scroll to form
    document.getElementById('orderForm').scrollIntoView({ behavior: 'smooth' });
}

// Select order type
function selectOrderType(type) {
    selectedOrderType = type;
    document.getElementById('selectedOrderType').value = type;
    
    // Update UI
    document.querySelectorAll('.order-type-card').forEach(card => {
        card.classList.remove('selected');
    });
    event.currentTarget.classList.add('selected');
    
    // Show/hide relevant fields
    const teacherGroup = document.getElementById('teacherGroup');
    const platformGroup = document.getElementById('platformGroup');
    const dossierGroup = document.getElementById('dossierGroup');
    
    if (type === 'dossier') {
        teacherGroup.style.display = 'block';
        platformGroup.style.display = 'block';
        dossierGroup.style.display = 'block';
        
        // Make teacher and platform required
        document.getElementById('teacher_id').required = true;
        document.getElementById('platform_id').required = true;
        document.getElementById('dossier_id').required = true;
    } else {
        teacherGroup.style.display = 'none';
        platformGroup.style.display = 'none';
        dossierGroup.style.display = 'none';
        
        // Remove requirements
        document.getElementById('teacher_id').required = false;
        document.getElementById('platform_id').required = false;
        document.getElementById('dossier_id').required = false;
    }
    
    // Show order details section
    document.getElementById('orderDetailsSection').style.display = 'block';
    document.getElementById('formActions').style.display = 'flex';
    
    // Update steps
    document.getElementById('step2').classList.add('completed');
    document.getElementById('step3').classList.add('active');
    
    // Calculate price if we have enough data
    calculatePrice();
    
    // Scroll to details
    document.getElementById('orderDetailsSection').scrollIntoView({ behavior: 'smooth' });
}

// Load subjects
async function loadSubjects() {
    if (!selectedGeneration) return;
    
    const subjectSelect = document.getElementById('subject_id');
    const loading = document.getElementById('subjectLoading');
    
    subjectSelect.disabled = true;
    loading.classList.add('show');
    
    try {
        const response = await fetch(`/educational-cards/subjects/${selectedGeneration.id}`);
        const data = await response.json();
        
        subjects = data;
        
        // Clear and populate select
        subjectSelect.innerHTML = '<option value="">{{ __('Select Subject') }}</option>';
        subjects.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = subject.name;
            option.dataset.price = subject.price;
            subjectSelect.appendChild(option);
        });
        
        subjectSelect.disabled = false;
    } catch (error) {
        showError('Failed to load subjects. Please try again.');
        console.error('Error loading subjects:', error);
    } finally {
        loading.classList.remove('show');
    }
}

// Load teachers
async function loadTeachers() {
    if (!selectedGeneration || !document.getElementById('subject_id').value) return;
    if (selectedOrderType !== 'dossier') return;
    
    const teacherSelect = document.getElementById('teacher_id');
    const loading = document.getElementById('teacherLoading');
    const subjectId = document.getElementById('subject_id').value;
    
    teacherSelect.disabled = true;
    teacherSelect.innerHTML = '<option value="">{{ __('Select Teacher') }}</option>';
    loading.classList.add('show');
    
    try {
        const response = await fetch(`/educational-cards/teachers/${selectedGeneration.id}/${subjectId}`);
        const data = await response.json();
        
        teachers = data;
        
        teachers.forEach(teacher => {
            const option = document.createElement('option');
            option.value = teacher.id;
            option.textContent = teacher.name;
            teacherSelect.appendChild(option);
        });
        
        teacherSelect.disabled = false;
    } catch (error) {
        showError('Failed to load teachers. Please try again.');
        console.error('Error loading teachers:', error);
    } finally {
        loading.classList.remove('show');
    }
}

// Load platforms
async function loadPlatforms() {
    if (!selectedGeneration || !document.getElementById('subject_id').value || !document.getElementById('teacher_id').value) return;
    if (selectedOrderType !== 'dossier') return;
    
    const platformSelect = document.getElementById('platform_id');
    const loading = document.getElementById('platformLoading');
    const subjectId = document.getElementById('subject_id').value;
    const teacherId = document.getElementById('teacher_id').value;
    
    platformSelect.disabled = true;
    platformSelect.innerHTML = '<option value="">{{ __('Select Platform') }}</option>';
    loading.classList.add('show');
    
    try {
        const response = await fetch(`/educational-cards/platforms/${selectedGeneration.id}/${subjectId}/${teacherId}`);
        const data = await response.json();
        
        platforms = data;
        
        platforms.forEach(platform => {
            const option = document.createElement('option');
            option.value = platform.id;
            option.textContent = `${platform.name} (+${platform.price_percentage}%)`;
            option.dataset.percentage = platform.price_percentage;
            platformSelect.appendChild(option);
        });
        
        platformSelect.disabled = false;
    } catch (error) {
        showError('Failed to load platforms. Please try again.');
        console.error('Error loading platforms:', error);
    } finally {
        loading.classList.remove('show');
    }
}

// Load dossiers
async function loadDossiers() {
    if (!selectedGeneration || !document.getElementById('subject_id').value || 
        !document.getElementById('teacher_id').value || !document.getElementById('platform_id').value ||
        !document.getElementById('semester').value) return;
    if (selectedOrderType !== 'dossier') return;
    
    const dossierSelect = document.getElementById('dossier_id');
    const loading = document.getElementById('dossierLoading');
    const subjectId = document.getElementById('subject_id').value;
    const teacherId = document.getElementById('teacher_id').value;
    const platformId = document.getElementById('platform_id').value;
    const semester = document.getElementById('semester').value;
    
    dossierSelect.disabled = true;
    dossierSelect.innerHTML = '<option value="">{{ __('Select Dossier') }}</option>';
    loading.classList.add('show');
    
    try {
        const response = await fetch(`/educational-cards/dossiers/${selectedGeneration.id}/${subjectId}/${teacherId}/${platformId}/${semester}`);
        const data = await response.json();
        
        dossiers = data;
        
        dossiers.forEach(dossier => {
            const option = document.createElement('option');
            option.value = dossier.id;
            option.textContent = dossier.name;
            option.dataset.price = dossier.price;
            option.dataset.finalPrice = dossier.final_price;
            dossierSelect.appendChild(option);
        });
        
        dossierSelect.disabled = false;
        calculatePrice();
    } catch (error) {
        showError('Failed to load dossiers. Please try again.');
        console.error('Error loading dossiers:', error);
    } finally {
        loading.classList.remove('show');
    }
}

// Update dossiers when semester changes
function updateDossiers() {
    if (selectedOrderType === 'dossier') {
        loadDossiers();
    } else {
        calculatePrice();
    }
}

// Calculate price
async function calculatePrice() {
    const priceCalculator = document.getElementById('priceCalculator');
    const priceBreakdown = document.getElementById('priceBreakdown');
    
    if (!selectedOrderType || !document.getElementById('subject_id').value || !document.getElementById('semester').value) {
        priceCalculator.style.display = 'none';
        return;
    }
    
    const quantity = parseInt(document.getElementById('quantity').value) || 1;
    let itemPrice = 0;
    let itemName = '';
    
    if (selectedOrderType === 'card') {
        // Educational card pricing
        const subjectSelect = document.getElementById('subject_id');
        const selectedOption = subjectSelect.options[subjectSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.price) {
            itemPrice = parseFloat(selectedOption.dataset.price);
            itemName = selectedOption.textContent;
        }
    } else if (selectedOrderType === 'dossier') {
        // Dossier pricing
        const dossierSelect = document.getElementById('dossier_id');
        const selectedOption = dossierSelect.options[dossierSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.finalPrice) {
            itemPrice = parseFloat(selectedOption.dataset.finalPrice);
            itemName = selectedOption.textContent;
        }
    }
    
    if (itemPrice > 0) {
        const subtotal = itemPrice * quantity;
        const total = subtotal;
        
        priceBreakdown.innerHTML = `
            <div class="price-row">
                <span>${itemName}</span>
                <span>${itemPrice.toFixed(2)} JD</span>
            </div>
            <div class="price-row">
                <span>{{ __('Quantity') }}</span>
                <span>${quantity}</span>
            </div>
            <div class="price-row">
                <span>{{ __('Total') }}</span>
                <span>${total.toFixed(2)} JD</span>
            </div>
        `;
        
        priceCalculator.style.display = 'block';
        
        // Update step indicator
        document.getElementById('step3').classList.add('completed');
        document.getElementById('step4').classList.add('active');
    } else {
        priceCalculator.style.display = 'none';
    }
}

// Show error message
function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.textContent = message;
    errorDiv.classList.add('show');
    
    setTimeout(() => {
        errorDiv.classList.remove('show');
    }, 5000);
}

// Reset form
function resetForm() {
    // Hide form and show generations
    document.getElementById('orderForm').style.display = 'none';
    document.getElementById('generationCards').style.display = 'grid';
    
    // Reset form data
    document.getElementById('educationalOrderForm').reset();
    selectedGeneration = null;
    selectedOrderType = null;
    
    // Reset steps
    document.querySelectorAll('.step').forEach(step => {
        step.classList.remove('active', 'completed');
    });
    document.getElementById('step1').classList.add('active');
    
    // Reset order type cards
    document.querySelectorAll('.order-type-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Hide sections
    document.getElementById('orderDetailsSection').style.display = 'none';
    document.getElementById('priceCalculator').style.display = 'none';
    document.getElementById('formActions').style.display = 'none';
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Form submission
document.getElementById('educationalOrderForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    
    // Validate required fields
    const requiredFields = ['student_name', 'subject_id', 'semester', 'quantity'];
    if (selectedOrderType === 'dossier') {
        requiredFields.push('teacher_id', 'platform_id', 'dossier_id');
    }
    
    let isValid = true;
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            isValid = false;
            field.focus();
            showError(`{{ __('Please fill in all required fields') }}`);
            return;
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        return;
    }
    
    // Prevent double submission
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Processing...') }}';
    
    // Re-enable after 5 seconds as fallback
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-shopping-cart"></i> {{ __('Place Order') }}';
    }, 5000);
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Educational cards page loaded');
});
</script>
@endpush