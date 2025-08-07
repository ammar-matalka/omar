/**
 * Educational System JavaScript
 * Handles all interactions for digital and physical educational forms
 */

// Global state
window.EducationalSystem = {
    currentStep: 1,
    maxSteps: 5, // Will be 6 for physical forms
    selectedData: {
        generation_id: null,
        subject_id: null,
        teacher_id: null,
        platform_id: null,
        package_id: null,
        region_id: null,
        product_type: 'digital'
    },
    apiEndpoints: {
        generations: '/educational/api/generations',
        subjects: '/educational/api/subjects',
        teachers: '/educational/api/teachers',
        platforms: '/educational/api/platforms',
        packages: '/educational/api/packages',
        regions: '/educational/api/regions',
        calculatePrice: '/educational/api/calculate-price',
        checkInventory: '/educational/api/check-inventory'
    },
    isLoading: false,
    formType: 'digital' // 'digital' or 'physical'
};

/**
 * Initialize Digital Educational Form
 */
function initializeDigitalForm() {
    console.log('🎓 Initializing Digital Educational Form');
    
    window.EducationalSystem.formType = 'digital';
    window.EducationalSystem.maxSteps = 5;
    window.EducationalSystem.selectedData.product_type = 'digital';
    
    initializeForm();
    loadGenerations();
}

/**
 * Initialize Physical Educational Form
 */
function initializePhysicalForm() {
    console.log('📚 Initializing Physical Educational Form');
    
    window.EducationalSystem.formType = 'physical';
    window.EducationalSystem.maxSteps = 6;
    window.EducationalSystem.selectedData.product_type = 'physical';
    
    initializeForm();
    loadGenerations();
}

/**
 * Common form initialization
 */
function initializeForm() {
    setupEventListeners();
    setupQuantityControls();
    showCurrentStep();
    updateNavigationButtons();
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Navigation buttons
    const nextBtn = document.getElementById('nextBtn');
    const backBtn = document.getElementById('backBtn');
    
    if (nextBtn) {
        nextBtn.addEventListener('click', nextStep);
    }
    
    if (backBtn) {
        backBtn.addEventListener('click', previousStep);
    }
    
    // Form submission
    const form = document.querySelector('#digitalEducationalForm, #physicalEducationalForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmission);
    }
    
    // Quantity input changes
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('input', handleQuantityChange);
    }
}

/**
 * Setup quantity controls
 */
function setupQuantityControls() {
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const quantityInput = document.getElementById('quantity');
    
    if (decreaseBtn && increaseBtn && quantityInput) {
        decreaseBtn.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            const newValue = Math.max(1, currentValue - 1);
            quantityInput.value = newValue;
            handleQuantityChange();
        });
        
        increaseBtn.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            const maxValue = window.EducationalSystem.formType === 'digital' ? 10 : 5;
            const newValue = Math.min(maxValue, currentValue + 1);
            quantityInput.value = newValue;
            handleQuantityChange();
        });
    }
}

/**
 * Load generations from API
 */
async function loadGenerations() {
    try {
        showLoading('generationsLoading');
        hideContent('generationsContainer');
        
        const response = await fetch(window.EducationalSystem.apiEndpoints.generations);
        const data = await response.json();
        
        if (Array.isArray(data)) {
            renderGenerations(data);
        } else {
            showError('فشل في تحميل الأجيال المتاحة');
        }
    } catch (error) {
        console.error('Error loading generations:', error);
        showError('حدث خطأ في تحميل الأجيال');
    } finally {
        hideLoading('generationsLoading');
        showContent('generationsContainer');
    }
}

/**
 * Load subjects for selected generation
 */
async function loadSubjects(generationId) {
    try {
        showLoading('subjectsLoading');
        hideContent('subjectsContainer');
        
        const response = await fetch(`${window.EducationalSystem.apiEndpoints.subjects}?generation_id=${generationId}`);
        const data = await response.json();
        
        if (Array.isArray(data)) {
            renderSubjects(data);
        } else {
            showError('فشل في تحميل المواد المتاحة');
        }
    } catch (error) {
        console.error('Error loading subjects:', error);
        showError('حدث خطأ في تحميل المواد');
    } finally {
        hideLoading('subjectsLoading');
        showContent('subjectsContainer');
    }
}

/**
 * Load teachers for selected subject
 */
async function loadTeachers(subjectId) {
    try {
        showLoading('teachersLoading');
        hideContent('teachersContainer');
        
        const response = await fetch(`${window.EducationalSystem.apiEndpoints.teachers}?subject_id=${subjectId}`);
        const data = await response.json();
        
        if (Array.isArray(data)) {
            renderTeachers(data);
        } else {
            showError('فشل في تحميل المعلمين المتاحين');
        }
    } catch (error) {
        console.error('Error loading teachers:', error);
        showError('حدث خطأ في تحميل المعلمين');
    } finally {
        hideLoading('teachersLoading');
        showContent('teachersContainer');
    }
}

/**
 * Load platforms for selected teacher
 */
async function loadPlatforms(teacherId) {
    try {
        showLoading('platformsLoading');
        hideContent('platformsContainer');
        
        const response = await fetch(`${window.EducationalSystem.apiEndpoints.platforms}?teacher_id=${teacherId}`);
        const data = await response.json();
        
        if (Array.isArray(data)) {
            renderPlatforms(data);
        } else {
            showError('فشل في تحميل المنصات المتاحة');
        }
    } catch (error) {
        console.error('Error loading platforms:', error);
        showError('حدث خطأ في تحميل المنصات');
    } finally {
        hideLoading('platformsLoading');
        showContent('platformsContainer');
    }
}

/**
 * Load packages for selected platform
 */
async function loadPackages(platformId) {
    try {
        showLoading('packagesLoading');
        hideContent('packagesContainer');
        
        const response = await fetch(`${window.EducationalSystem.apiEndpoints.packages}?platform_id=${platformId}`);
        const data = await response.json();
        
        if (Array.isArray(data)) {
            renderPackages(data);
        } else {
            showError('فشل في تحميل الباقات المتاحة');
        }
    } catch (error) {
        console.error('Error loading packages:', error);
        showError('حدث خطأ في تحميل الباقات');
    } finally {
        hideLoading('packagesLoading');
        showContent('packagesContainer');
    }
}

/**
 * Load shipping regions (for physical forms only)
 */
async function loadRegions() {
    if (window.EducationalSystem.formType !== 'physical') return;
    
    try {
        showLoading('regionsLoading');
        hideContent('regionsContainer');
        
        const response = await fetch(window.EducationalSystem.apiEndpoints.regions);
        const data = await response.json();
        
        if (Array.isArray(data)) {
            renderRegions(data);
        } else {
            showError('فشل في تحميل مناطق الشحن');
        }
    } catch (error) {
        console.error('Error loading regions:', error);
        showError('حدث خطأ في تحميل مناطق الشحن');
    } finally {
        hideLoading('regionsLoading');
        showContent('regionsContainer');
    }
}

/**
 * Render generations
 */
function renderGenerations(generations) {
    const container = document.getElementById('generationsContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    generations.forEach(generation => {
        const card = createOptionCard({
            id: generation.id,
            title: generation.name,
            subtitle: `${generation.birth_year}`,
            icon: '🎓',
            onClick: () => selectGeneration(generation.id)
        });
        
        container.appendChild(card);
    });
}

/**
 * Render subjects
 */
function renderSubjects(subjects) {
    const container = document.getElementById('subjectsContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    subjects.forEach(subject => {
        const card = createOptionCard({
            id: subject.id,
            title: subject.name,
            subtitle: 'مادة دراسية',
            icon: '📖',
            onClick: () => selectSubject(subject.id)
        });
        
        container.appendChild(card);
    });
}

/**
 * Render teachers
 */
function renderTeachers(teachers) {
    const container = document.getElementById('teachersContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    teachers.forEach(teacher => {
        const card = createTeacherCard(teacher);
        container.appendChild(card);
    });
}

/**
 * Render platforms
 */
function renderPlatforms(platforms) {
    const container = document.getElementById('platformsContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    platforms.forEach(platform => {
        const card = createOptionCard({
            id: platform.id,
            title: platform.name,
            subtitle: platform.description || 'منصة تعليمية',
            icon: '💻',
            onClick: () => selectPlatform(platform.id)
        });
        
        container.appendChild(card);
    });
}

/**
 * Render packages
 */
function renderPackages(packages) {
    const container = document.getElementById('packagesContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    packages.forEach(pkg => {
        const card = createPackageCard(pkg);
        container.appendChild(card);
    });
}

/**
 * Render shipping regions (physical forms only)
 */
function renderRegions(regions) {
    const container = document.getElementById('regionsContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    regions.forEach(region => {
        const card = createRegionCard(region);
        container.appendChild(card);
    });
}

/**
 * Create option card
 */
function createOptionCard({ id, title, subtitle, icon, onClick, extra = '' }) {
    const card = document.createElement('div');
    card.className = 'option-card';
    card.dataset.id = id;
    card.onclick = onClick;
    
    card.innerHTML = `
        <div style="text-align: center;">
            <div style="font-size: 2rem; margin-bottom: var(--space-sm);">${icon}</div>
            <h4 style="font-weight: 600; margin-bottom: var(--space-xs); color: var(--gray-800);">${title}</h4>
            <p style="font-size: 0.9rem; color: var(--gray-600); margin-bottom: ${extra ? 'var(--space-sm)' : '0'};">${subtitle}</p>
            ${extra}
        </div>
    `;
    
    return card;
}

/**
 * Create teacher card
 */
function createTeacherCard(teacher) {
    const card = document.createElement('div');
    card.className = 'option-card teacher-card';
    card.dataset.id = teacher.id;
    card.onclick = () => selectTeacher(teacher.id);
    
    const initials = teacher.name.split(' ').map(word => word[0]).join('').substring(0, 2);
    
    card.innerHTML = `
        <div style="display: flex; align-items: center; gap: var(--space-md);">
            <div class="teacher-avatar">${initials}</div>
            <div style="flex: 1;">
                <h4 style="font-weight: 600; margin-bottom: var(--space-xs); color: var(--gray-800);">${teacher.name}</h4>
                <p style="font-size: 0.9rem; color: var(--gray-600); margin-bottom: var(--space-xs);">معلم متخصص</p>
                <p style="font-size: 0.8rem; color: var(--gray-500);">منصة تعليمية</p>
            </div>
        </div>
    `;
    
    return card;
}

/**
 * Create package card
 */
function createPackageCard(pkg) {
    const card = document.createElement('div');
    card.className = 'option-card package-card';
    card.dataset.id = pkg.id;
    card.onclick = () => selectPackage(pkg.id);
    
    card.innerHTML = `
        <div style="text-align: center;">
            <div style="font-size: 2rem; margin-bottom: var(--space-sm);">📦</div>
            <h4 style="font-weight: 600; margin-bottom: var(--space-sm); color: var(--gray-800);">${pkg.name}</h4>
            <div style="margin-bottom: var(--space-md);">
                <span style="background: var(--primary-100); color: var(--primary-700); padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600;">
                    باقة تعليمية
                </span>
            </div>
            ${pkg.description ? `<p style="font-size: 0.85rem; color: var(--gray-500);">${pkg.description}</p>` : ''}
        </div>
    `;
    
    return card;
}

/**
 * Create region card (for physical forms)
 */
function createRegionCard(region) {
    const card = document.createElement('div');
    card.className = 'option-card region-card';
    card.dataset.id = region.id;
    card.onclick = () => selectRegion(region.id);
    
    card.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h4 style="font-weight: 600; margin-bottom: var(--space-xs); color: var(--gray-800);">
                    <i class="fas fa-map-marker-alt" style="color: var(--primary-600); margin-left: var(--space-sm);"></i>
                    ${region.name}
                </h4>
            </div>
            <div>
                <span class="shipping-cost">
                    ${region.shipping_cost} دينار
                </span>
            </div>
        </div>
    `;
    
    return card;
}

/**
 * Selection handlers
 */
function selectGeneration(id) {
    window.EducationalSystem.selectedData.generation_id = id;
    document.getElementById('generation_id').value = id;
    
    selectOption('generationsContainer', id);
    updateProgress(1);
    
    // Reset subsequent selections
    resetSelections(['subject_id', 'teacher_id', 'platform_id', 'package_id', 'region_id']);
    
    loadSubjects(id);
    nextStep();
}

function selectSubject(id) {
    window.EducationalSystem.selectedData.subject_id = id;
    document.getElementById('subject_id').value = id;
    
    selectOption('subjectsContainer', id);
    updateProgress(2);
    
    // Reset subsequent selections
    resetSelections(['teacher_id', 'platform_id', 'package_id', 'region_id']);
    
    loadTeachers(id);
    nextStep();
}

function selectTeacher(id) {
    window.EducationalSystem.selectedData.teacher_id = id;
    document.getElementById('teacher_id').value = id;
    
    selectOption('teachersContainer', id);
    updateProgress(3);
    
    // Reset subsequent selections
    resetSelections(['platform_id', 'package_id', 'region_id']);
    
    loadPlatforms(id);
    nextStep();
}

function selectPlatform(id) {
    window.EducationalSystem.selectedData.platform_id = id;
    document.getElementById('platform_id').value = id;
    
    selectOption('platformsContainer', id);
    updateProgress(4);
    
    // Reset subsequent selections
    resetSelections(['package_id', 'region_id']);
    
    loadPackages(id);
    nextStep();
}

function selectPackage(id) {
    window.EducationalSystem.selectedData.package_id = id;
    document.getElementById('package_id').value = id;
    
    selectOption('packagesContainer', id);
    updateProgress(5);
    
    if (window.EducationalSystem.formType === 'physical') {
        // Reset region selection
        resetSelections(['region_id']);
        loadRegions();
        nextStep();
    } else {
        // For digital, proceed to quantity/price
        showQuantityStep();
    }
}

function selectRegion(id) {
    window.EducationalSystem.selectedData.region_id = id;
    document.getElementById('region_id').value = id;
    
    selectOption('regionsContainer', id);
    updateProgress(6);
    
    showQuantityStep();
}

/**
 * Show quantity and price step
 */
async function showQuantityStep() {
    // Show quantity step
    document.getElementById('quantityStep').style.display = 'block';
    document.getElementById('quantityStep').classList.add('active');
    
    // Update selection summary
    updateSelectionSummary();
    
    // Calculate initial price
    await calculatePrice();
    
    // Update navigation
    updateNavigationButtons();
    
    // For physical forms, check inventory
    if (window.EducationalSystem.formType === 'physical') {
        await checkInventory();
    }
}

/**
 * Update selection summary
 */
function updateSelectionSummary() {
    const container = document.getElementById('summaryContent');
    if (!container) return;
    
    const selections = [
        { label: 'الجيل', value: getSelectedText('generationsContainer') },
        { label: 'المادة', value: getSelectedText('subjectsContainer') },
        { label: 'المعلم', value: getSelectedText('teachersContainer') },
        { label: 'المنصة', value: getSelectedText('platformsContainer') },
        { label: 'الباقة', value: getSelectedText('packagesContainer') }
    ];
    
    if (window.EducationalSystem.formType === 'physical') {
        selections.push({ label: 'منطقة الشحن', value: getSelectedText('regionsContainer') });
    }
    
    container.innerHTML = selections.map(item => `
        <div style="background: white; padding: var(--space-sm); border-radius: var(--radius-sm); border: 1px solid var(--gray-200);">
            <div style="font-weight: 600; color: var(--gray-700); font-size: 0.8rem;">${item.label}</div>
            <div style="color: var(--gray-800);">${item.value}</div>
        </div>
    `).join('');
}

/**
 * Calculate price
 */
async function calculatePrice() {
    try {
        const data = {
            generation_id: window.EducationalSystem.selectedData.generation_id,
            subject_id: window.EducationalSystem.selectedData.subject_id,
            teacher_id: window.EducationalSystem.selectedData.teacher_id,
            platform_id: window.EducationalSystem.selectedData.platform_id,
            package_id: window.EducationalSystem.selectedData.package_id,
            quantity: parseInt(document.getElementById('quantity').value) || 1
        };
        
        if (window.EducationalSystem.formType === 'physical') {
            data.region_id = window.EducationalSystem.selectedData.region_id;
        }
        
        const response = await fetch(window.EducationalSystem.apiEndpoints.calculatePrice, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                               document.querySelector('input[name="_token"]')?.value
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.price !== undefined) {
            updatePriceDisplay(result);
        } else {
            showError(result.error || 'فشل في حساب السعر');
        }
    } catch (error) {
        console.error('Error calculating price:', error);
        showError('حدث خطأ في حساب السعر');
    }
}

/**
 * Check inventory (for physical forms)
 */
async function checkInventory() {
    if (window.EducationalSystem.formType !== 'physical') return;
    
    try {
        const data = {
            generation_id: window.EducationalSystem.selectedData.generation_id,
            subject_id: window.EducationalSystem.selectedData.subject_id,
            teacher_id: window.EducationalSystem.selectedData.teacher_id,
            platform_id: window.EducationalSystem.selectedData.platform_id,
            package_id: window.EducationalSystem.selectedData.package_id,
            quantity: parseInt(document.getElementById('quantity').value) || 1
        };
        
        const response = await fetch(window.EducationalSystem.apiEndpoints.checkInventory, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                               document.querySelector('input[name="_token"]')?.value
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.available !== undefined) {
            updateInventoryDisplay(result);
        }
    } catch (error) {
        console.error('Error checking inventory:', error);
    }
}

/**
 * Update price display
 */
function updatePriceDisplay(priceData) {
    const quantity = parseInt(document.getElementById('quantity').value) || 1;
    const unitPrice = priceData.price || 0;
    const shippingCost = priceData.shipping_cost || 0;
    const subtotal = unitPrice * quantity;
    const totalPrice = subtotal + shippingCost;
    
    document.getElementById('unitPrice').textContent = `${unitPrice.toFixed(2)} دينار`;
    document.getElementById('subtotal').textContent = `${subtotal.toFixed(2)} دينار`;
    document.getElementById('totalPrice').textContent = `${totalPrice.toFixed(2)} دينار`;
    
    if (window.EducationalSystem.formType === 'physical') {
        document.getElementById('shippingCost').textContent = shippingCost > 0 ? `${shippingCost.toFixed(2)} دينار` : 'مجاني';
    } else {
        document.getElementById('shippingCost').textContent = 'مجاني';
    }
}

/**
 * Update inventory display (for physical forms)
 */
function updateInventoryDisplay(inventoryData) {
    const stockInfo = document.getElementById('stockInfo');
    
    if (stockInfo && inventoryData && !inventoryData.is_digital) {
        stockInfo.style.display = 'block';
        
        document.getElementById('stockStatus').innerHTML = `
            <div style="color: var(--success-600);">
                <i class="fas fa-check-circle" style="margin-left: var(--space-xs);"></i>
                ${inventoryData.message || 'متوفر في المخزون'}
            </div>
        `;
    }
}

/**
 * Handle quantity change
 */
function handleQuantityChange() {
    calculatePrice();
    
    if (window.EducationalSystem.formType === 'physical') {
        checkInventory();
    }
}

/**
 * Navigation functions
 */
function nextStep() {
    if (window.EducationalSystem.currentStep < window.EducationalSystem.maxSteps) {
        hideCurrentStep();
        window.EducationalSystem.currentStep++;
        showCurrentStep();
        updateNavigationButtons();
    }
}

function previousStep() {
    if (window.EducationalSystem.currentStep > 1) {
        hideCurrentStep();
        window.EducationalSystem.currentStep--;
        showCurrentStep();
        updateNavigationButtons();
    }
}

function showCurrentStep() {
    const currentStepElement = document.getElementById(`step${window.EducationalSystem.currentStep}`);
    if (currentStepElement) {
        currentStepElement.style.display = 'block';
        setTimeout(() => {
            currentStepElement.classList.add('active');
        }, 10);
    }
}

function hideCurrentStep() {
    const currentStepElement = document.getElementById(`step${window.EducationalSystem.currentStep}`);
    if (currentStepElement) {
        currentStepElement.classList.remove('active');
        setTimeout(() => {
            currentStepElement.style.display = 'none';
        }, 300);
    }
    
    // Hide quantity step if visible
    const quantityStep = document.getElementById('quantityStep');
    if (quantityStep && quantityStep.classList.contains('active')) {
        quantityStep.classList.remove('active');
        setTimeout(() => {
            quantityStep.style.display = 'none';
        }, 300);
    }
}

/**
 * Update navigation buttons
 */
function updateNavigationButtons() {
    const nextBtn = document.getElementById('nextBtn');
    const backBtn = document.getElementById('backBtn');
    const addToCartBtn = document.getElementById('addToCartBtn');
    
    // Show/hide back button
    if (backBtn) {
        backBtn.style.display = window.EducationalSystem.currentStep > 1 ? 'inline-flex' : 'none';
    }
    
    // Check if we're at quantity step
    const isAtQuantityStep = document.getElementById('quantityStep')?.classList.contains('active');
    
    if (isAtQuantityStep) {
        // At quantity step - show add to cart button
        if (nextBtn) nextBtn.style.display = 'none';
        if (addToCartBtn) addToCartBtn.style.display = 'inline-flex';
    } else {
        // Not at quantity step - show next button if not at last step
        const showNext = window.EducationalSystem.currentStep < window.EducationalSystem.maxSteps;
        if (nextBtn) nextBtn.style.display = showNext ? 'inline-flex' : 'none';
        if (addToCartBtn) addToCartBtn.style.display = 'none';
    }
}

/**
 * Update progress indicator
 */
function updateProgress(stepNumber) {
    const progressLine = document.getElementById('progressLine');
    const progressSteps = document.querySelectorAll('.progress-step');
    
    // Update progress line
    const progressPercentage = ((stepNumber - 1) / (window.EducationalSystem.maxSteps - 1)) * 100;
    if (progressLine) {
        progressLine.style.width = `${progressPercentage}%`;
    }
    
    // Update step indicators
    progressSteps.forEach((step, index) => {
        const stepCircle = step.querySelector('.step-circle');
        if (index + 1 <= stepNumber) {
            step.classList.add('completed');
            if (stepCircle) {
                stepCircle.style.background = 'var(--success-500)';
            }
        } else {
            step.classList.remove('completed');
            if (stepCircle) {
                stepCircle.style.background = 'var(--gray-300)';
            }
        }
        
        if (index + 1 === stepNumber) {
            step.classList.add('active');
        } else {
            step.classList.remove('active');
        }
    });
}

/**
 * Form submission handler
 */
function handleFormSubmission(e) {
    e.preventDefault();
    
    // Show loading
    showLoadingOverlay();
    
    // Validate all required fields
    const requiredFields = [
        'generation_id', 'subject_id', 'teacher_id', 'platform_id', 'package_id'
    ];
    
    if (window.EducationalSystem.formType === 'physical') {
        requiredFields.push('region_id');
    }
    
    for (const field of requiredFields) {
        const input = document.getElementById(field);
        if (!input || !input.value) {
            hideLoadingOverlay();
            showError(`يرجى إكمال جميع الخطوات المطلوبة`);
            return;
        }
    }
    
    // Submit form
    e.target.submit();
}

/**
 * Utility functions
 */
function selectOption(containerId, optionId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    // Remove previous selection
    container.querySelectorAll('.option-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selection to current option
    const selectedCard = container.querySelector(`[data-id="${optionId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('selected');
    }
}

function getSelectedText(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return '';
    
    const selectedCard = container.querySelector('.option-card.selected');
    if (!selectedCard) return '';
    
    const titleElement = selectedCard.querySelector('h4');
    return titleElement ? titleElement.textContent.trim() : '';
}

function resetSelections(fieldIds) {
    fieldIds.forEach(fieldId => {
        window.EducationalSystem.selectedData[fieldId] = null;
        const input = document.getElementById(fieldId);
        if (input) input.value = '';
    });
}

function showLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.display = 'block';
    }
}

function hideLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.display = 'none';
    }
}

function showContent(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.display = 'grid';
    }
}

function hideContent(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.display = 'none';
    }
}

function showLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'flex';
    }
}

function hideLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

function showError(message) {
    // Create or update error notification
    let errorNotification = document.querySelector('.error-notification');
    
    if (!errorNotification) {
        errorNotification = document.createElement('div');
        errorNotification.className = 'error-notification';
        errorNotification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ef4444;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            z-index: 10000;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        document.body.appendChild(errorNotification);
    }
    
    errorNotification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-exclamation-triangle"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer; margin-right: auto;">×</button>
        </div>
    `;
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (errorNotification.parentNode) {
            errorNotification.parentNode.removeChild(errorNotification);
        }
    }, 5000);
}

function showSuccess(message) {
    // Create success notification
    const successNotification = document.createElement('div');
    successNotification.className = 'success-notification';
    successNotification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #10b981;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        z-index: 10000;
        max-width: 400px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    successNotification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-check-circle"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer; margin-right: auto;">×</button>
        </div>
    `;
    
    document.body.appendChild(successNotification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (successNotification.parentNode) {
            successNotification.parentNode.removeChild(successNotification);
        }
    }, 3000);
}

/**
 * Enhanced error handling for API calls
 */
function handleApiError(error, context = '') {
    console.error(`API Error ${context}:`, error);
    
    let message = 'حدث خطأ غير متوقع';
    
    if (error.response) {
        switch (error.response.status) {
            case 422:
                message = 'بيانات غير صحيحة. يرجى التحقق من اختياراتك';
                break;
            case 404:
                message = 'الخدمة المطلوبة غير متوفرة حالياً';
                break;
            case 500:
                message = 'خطأ في الخادم. يرجى المحاولة لاحقاً';
                break;
            default:
                message = 'فشل في الاتصال بالخادم';
        }
    } else if (error.name === 'NetworkError' || !navigator.onLine) {
        message = 'لا يوجد اتصال بالإنترنت';
    }
    
    showError(message);
}

/**
 * Debounce function for API calls
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Enhanced quantity change handler with debouncing
 */
const debouncedQuantityChange = debounce(handleQuantityChange, 500);

/**
 * Initialize enhanced form features
 */
function initializeEnhancedFeatures() {
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft' && document.getElementById('nextBtn')?.style.display !== 'none') {
            nextStep();
        } else if (e.key === 'ArrowRight' && document.getElementById('backBtn')?.style.display !== 'none') {
            previousStep();
        }
    });
    
    // Add form validation on input change
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            const max = parseInt(this.max) || (window.EducationalSystem.formType === 'digital' ? 10 : 5);
            
            if (value < 1) {
                this.value = 1;
            } else if (value > max) {
                this.value = max;
                showError(`الحد الأقصى للكمية هو ${max}`);
            }
            
            debouncedQuantityChange();
        });
    }
}

/**
 * Export functions for global access
 */
window.EducationalSystem.functions = {
    initializeDigitalForm,
    initializePhysicalForm,
    selectGeneration,
    selectSubject,
    selectTeacher,
    selectPlatform,
    selectPackage,
    selectRegion,
    nextStep,
    previousStep,
    calculatePrice,
    checkInventory,
    handleQuantityChange: debouncedQuantityChange,
    showError,
    showSuccess
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeEnhancedFeatures);
} else {
    initializeEnhancedFeatures();
}

console.log('🎓 Educational System JavaScript loaded successfully');