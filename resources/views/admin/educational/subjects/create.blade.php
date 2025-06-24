@extends('layouts.admin')

@section('title', 'إضافة مادة دراسية جديدة')
@section('page-title', 'إضافة مادة دراسية جديدة')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    <span>النظام التعليمي</span>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    <a href="{{ route('admin.educational.subjects.index') }}" class="breadcrumb-link">إدارة المواد</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    <span>إضافة مادة جديدة</span>
</div>
@endsection

@push('styles')
<style>
    .create-form {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-section {
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        margin-bottom: var(--space-2xl);
        position: relative;
        overflow: hidden;
    }
    
    .form-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-600));
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xl);
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .section-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
    }
    
    .generation-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-md);
        margin-top: var(--space-md);
    }
    
    .generation-option {
        padding: var(--space-lg);
        border: 2px solid var(--admin-secondary-300);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: all var(--transition-fast);
        background: white;
        text-align: center;
    }
    
    .generation-option:hover {
        border-color: var(--admin-primary-500);
        background: var(--admin-primary-50);
        transform: translateY(-2px);
    }
    
    .generation-option.selected {
        border-color: var(--admin-primary-500);
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }
    
    .generation-year {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
    }
    
    .generation-name {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-bottom: var(--space-xs);
    }
    
    .generation-info {
        font-size: 0.8rem;
        opacity: 0.7;
    }
    
    .generation-details {
        background: var(--admin-primary-50);
        border: 2px solid var(--admin-primary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-lg);
        display: none;
    }
    
    .generation-details.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--space-lg);
        margin-top: var(--space-md);
    }
    
    .info-item {
        text-align: center;
    }
    
    .info-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-primary-700);
        margin-bottom: var(--space-xs);
    }
    
    .info-label {
        font-size: 0.9rem;
        color: var(--admin-primary-600);
        font-weight: 500;
    }
    
    .code-generator {
        display: flex;
        gap: var(--space-md);
        align-items: end;
    }
    
    .generate-btn {
        background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600));
        color: white;
        border: none;
        padding: var(--space-md) var(--space-lg);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: all var(--transition-fast);
        font-weight: 600;
        white-space: nowrap;
    }
    
    .generate-btn:hover {
        background: linear-gradient(135deg, var(--admin-secondary-600), var(--admin-secondary-700));
        transform: translateY(-2px);
    }
    
    .form-actions {
        display: flex;
        gap: var(--space-lg);
        justify-content: center;
        padding-top: var(--space-2xl);
        border-top: 2px solid var(--admin-secondary-200);
        margin-top: var(--space-2xl);
    }
    
    .help-text {
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-md);
        font-size: 0.9rem;
        color: var(--admin-secondary-600);
        line-height: 1.6;
    }
    
    .help-text .fas {
        color: var(--admin-primary-500);
        margin-left: var(--space-sm);
    }
    
    .error-message {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        border: 2px solid #f87171;
        color: #991b1b;
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        margin-top: var(--space-md);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .preview-section {
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        margin-bottom: var(--space-2xl);
        position: relative;
        overflow: hidden;
        display: none;
    }
    
    .preview-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--success-500), #059669);
    }
    
    .preview-content {
        background: var(--admin-secondary-50);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        text-align: center;
    }
    
    .preview-icon {
        font-size: 2rem;
        margin-bottom: var(--space-lg);
    }
    
    .preview-icon span {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        padding: var(--space-lg);
        border-radius: 50%;
        width: 80px;
        height: 80px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }
    
    .preview-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-md);
    }
    
    .preview-description {
        color: var(--admin-secondary-600);
        font-size: 1.1rem;
    }
    
    .warning-box {
        background: linear-gradient(135deg, #fffbeb, #fde68a);
        border: 2px solid #f59e0b;
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-md);
        color: #92400e;
        font-size: 0.9rem;
        line-height: 1.6;
    }
    
    .warning-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    @media (max-width: 768px) {
        .create-form {
            max-width: 100%;
        }
        
        .generation-selector {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .code-generator {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endpush

@section('content')
<div class="create-form fade-in">
    <form action="{{ route('admin.educational.subjects.store') }}" method="POST" id="subjectForm">
        @csrf
        
        <!-- Generation Selection Section -->
        <div class="form-section">
            <h3 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-users-class"></i>
                </div>
                اختيار الجيل الدراسي
            </h3>
            
            @if($generations->count() == 0)
                <div class="warning-box">
                    <div class="warning-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <strong>تنبيه:</strong> لا توجد أجيال دراسية متاحة. يجب إنشاء جيل دراسي أولاً قبل إضافة المواد.
                        <br>
                        <a href="{{ route('admin.educational.generations.create') }}" class="btn btn-primary btn-sm" style="margin-top: var(--space-md);">
                            <i class="fas fa-plus"></i>
                            إنشاء جيل دراسي جديد
                        </a>
                    </div>
                </div>
            @else
                <div class="generation-selector">
                    @foreach($generations as $generation)
                        <div class="generation-option" 
                             data-generation="{{ $generation->id }}" 
                             onclick="selectGeneration({{ $generation->id }}, '{{ $generation->display_name }}', '{{ $generation->current_grade }}', '{{ $generation->age_range }}')">
                            <div class="generation-year">{{ $generation->birth_year }}</div>
                            <div class="generation-name">{{ $generation->display_name }}</div>
                            <div class="generation-info">{{ $generation->current_grade }}</div>
                        </div>
                    @endforeach
                </div>
                
                <input type="hidden" name="generation_id" id="generation_id" value="{{ old('generation_id', request('generation_id')) }}">
                
                @error('generation_id')
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
                
                <div class="help-text">
                    <i class="fas fa-lightbulb"></i>
                    اختر الجيل الدراسي الذي تريد إضافة المادة له. يمكنك إضافة نفس المادة لأجيال مختلفة بشكل منفصل.
                </div>
                
                <!-- Generation Details Display -->
                <div id="generationDetails" class="generation-details">
                    <h4 style="font-weight: 600; color: var(--admin-primary-700); margin-bottom: var(--space-md);">
                        <i class="fas fa-info-circle" style="margin-left: var(--space-sm);"></i>
                        معلومات الجيل المختار
                    </h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-value" id="selectedGenerationName">--</div>
                            <div class="info-label">اسم الجيل</div>
                        </div>
                        <div class="info-item">
                            <div class="info-value" id="selectedGenerationGrade">--</div>
                            <div class="info-label">الصف الحالي</div>
                        </div>
                        <div class="info-item">
                            <div class="info-value" id="selectedGenerationAge">--</div>
                            <div class="info-label">العمر</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Subject Information Section -->
        <div class="form-section">
            <h3 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-book"></i>
                </div>
                معلومات المادة
            </h3>
            
            <!-- Subject Name -->
            <div class="form-group">
                <label class="form-label" for="name">
                    <i class="fas fa-signature"></i>
                    اسم المادة <span style="color: var(--error-500);">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       class="form-input" 
                       value="{{ old('name') }}"
                       placeholder="مثال: الرياضيات، العلوم، اللغة العربية..."
                       required>
                
                @error('name')
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
                
                <div class="help-text">
                    <i class="fas fa-lightbulb"></i>
                    أدخل اسم المادة بوضوح. يُفضل استخدام الأسماء المعيارية المتعارف عليها في المناهج الدراسية.
                </div>
            </div>
            
            <!-- Subject Code -->
            <div class="form-group">
                <label class="form-label" for="code">
                    <i class="fas fa-tag"></i>
                    كود المادة (اختياري)
                </label>
                <div class="code-generator">
                    <input type="text" 
                           name="code" 
                           id="code" 
                           class="form-input" 
                           value="{{ old('code') }}"
                           placeholder="مثال: MATH101، SCI201، AR301..."
                           style="flex: 1;">
                    <button type="button" class="generate-btn" onclick="generateCode()">
                        <i class="fas fa-magic"></i>
                        توليد تلقائي
                    </button>
                </div>
                
                @error('code')
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
                
                <div class="help-text">
                    <i class="fas fa-info-circle"></i>
                    كود المادة مفيد لتنظيم المواد وسهولة البحث. يمكن تركه فارغاً أو الضغط على "توليد تلقائي" لإنشاء كود تلقائياً.
                </div>
            </div>
            
            <!-- Status -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-toggle-on"></i>
                    حالة التفعيل
                </label>
                <div style="display: flex; align-items: center; gap: var(--space-lg); margin-top: var(--space-md);">
                    <label style="display: flex; align-items: center; gap: var(--space-sm); cursor: pointer;">
                        <input type="radio" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', '1') === '1' ? 'checked' : '' }}
                               style="width: 20px; height: 20px;">
                        <span style="color: var(--success-600); font-weight: 600;">نشط</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: var(--space-sm); cursor: pointer;">
                        <input type="radio" 
                               name="is_active" 
                               value="0" 
                               {{ old('is_active') === '0' ? 'checked' : '' }}
                               style="width: 20px; height: 20px;">
                        <span style="color: var(--error-500); font-weight: 600;">غير نشط</span>
                    </label>
                </div>
                
                <div class="help-text">
                    <i class="fas fa-info-circle"></i>
                    المواد النشطة فقط ستظهر في خيارات إضافة المعلمين والمنصات التعليمية.
                </div>
            </div>
        </div>
        
        <!-- Preview Section -->
        <div class="preview-section" id="previewSection">
            <h3 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-eye"></i>
                </div>
                معاينة المادة
            </h3>
            
            <div class="preview-content">
                <div class="preview-icon">
                    <span id="previewIcon">م</span>
                </div>
                <h4 id="previewName" class="preview-name">
                    اسم المادة
                </h4>
                <p id="previewDescription" class="preview-description">
                    وصف المادة سيظهر هنا
                </p>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                <i class="fas fa-save"></i>
                حفظ المادة
            </button>
            <a href="{{ route('admin.educational.subjects.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let selectedGeneration = null;

// Select generation function
function selectGeneration(generationId, generationName, grade, age) {
    selectedGeneration = generationId;
    
    // Update UI
    document.querySelectorAll('.generation-option').forEach(option => {
        option.classList.remove('selected');
    });
    document.querySelector(`[data-generation="${generationId}"]`).classList.add('selected');
    
    // Update hidden input
    document.getElementById('generation_id').value = generationId;
    
    // Update generation details
    document.getElementById('selectedGenerationName').textContent = generationName;
    document.getElementById('selectedGenerationGrade').textContent = grade;
    document.getElementById('selectedGenerationAge').textContent = age;
    
    // Show generation details
    document.getElementById('generationDetails').classList.add('show');
    
    // Update preview
    updatePreview();
    
    // Show preview section
    document.getElementById('previewSection').style.display = 'block';
    
    // Generate code if name is filled
    if (document.getElementById('name').value) {
        generateCode();
    }
}

// Generate subject code
function generateCode() {
    const name = document.getElementById('name').value;
    if (!name || !selectedGeneration) {
        alert('يرجى اختيار الجيل وإدخال اسم المادة أولاً');
        return;
    }
    
    // Get first 3 letters of subject name
    const nameCode = name.replace(/\s+/g, '').substring(0, 3).toUpperCase();
    
    // Get generation year
    const generationYear = document.querySelector(`[data-generation="${selectedGeneration}"] .generation-year`).textContent;
    
    // Generate code
    const code = nameCode + generationYear;
    document.getElementById('code').value = code;
    
    updatePreview();
}

// Update preview
function updatePreview() {
    const name = document.getElementById('name').value || 'اسم المادة';
    const code = document.getElementById('code').value;
    const generationName = document.getElementById('selectedGenerationName').textContent;
    
    // Update icon (first letter of subject name)
    const firstLetter = name.charAt(0).toUpperCase() || 'م';
    document.getElementById('previewIcon').textContent = firstLetter;
    
    // Update name
    document.getElementById('previewName').textContent = name;
    
    // Update description
    let description = '';
    if (generationName && generationName !== '--') {
        description = `مادة ${name} - ${generationName}`;
        if (code) {
            description += ` (${code})`;
        }
    } else {
        description = 'يرجى اختيار الجيل الدراسي';
    }
    
    document.getElementById('previewDescription').textContent = description;
}

// Handle name input changes
document.getElementById('name').addEventListener('input', function() {
    updatePreview();
    
    // Auto-generate code if generation is selected
    if (selectedGeneration && this.value) {
        generateCode();
    }
});

// Handle code input changes
document.getElementById('code').addEventListener('input', function() {
    updatePreview();
});

// Form validation
document.getElementById('subjectForm').addEventListener('submit', function(e) {
    if (!selectedGeneration) {
        e.preventDefault();
        alert('يرجى اختيار الجيل الدراسي');
        return false;
    }
    
    const name = document.getElementById('name').value.trim();
    if (!name) {
        e.preventDefault();
        alert('يرجى إدخال اسم المادة');
        return false;
    }
    
    // Disable submit button to prevent double submission
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
});

// Initialize with old values if exists
document.addEventListener('DOMContentLoaded', function() {
    const oldGenerationId = '{{ old("generation_id", request("generation_id")) }}';
    if (oldGenerationId) {
        const generationOption = document.querySelector(`[data-generation="${oldGenerationId}"]`);
        if (generationOption) {
            generationOption.click();
        }
    }
    
    // Update preview on load
    updatePreview();
    
    // Add animation classes
    setTimeout(() => {
        document.querySelectorAll('.form-section').forEach((section, index) => {
            setTimeout(() => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                section.style.transition = 'all 0.6s ease';
                section.style.opacity = '1';
                section.style.transform = 'translateY(0)';
            }, index * 200);
        });
    }, 100);
});

// Real-time validation
document.getElementById('name').addEventListener('input', function() {
    const value = this.value.trim();
    if (value.length < 2) {
        this.setCustomValidity('اسم المادة يجب أن يكون أكثر من حرف واحد');
    } else if (value.length > 255) {
        this.setCustomValidity('اسم المادة لا يجب أن يتجاوز 255 حرف');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('code').addEventListener('input', function() {
    const value = this.value.trim();
    if (value && value.length < 3) {
        this.setCustomValidity('كود المادة يجب أن يكون 3 أحرف على الأقل');
    } else if (value.length > 50) {
        this.setCustomValidity('كود المادة لا يجب أن يتجاوز 50 حرف');
    } else {
        this.setCustomValidity('');
    }
});

// Keyboard navigation for generation selection
document.addEventListener('keydown', function(e) {
    if (e.target.classList.contains('generation-option')) {
        const options = Array.from(document.querySelectorAll('.generation-option'));
        const currentIndex = options.indexOf(e.target);
        
        let newIndex;
        switch(e.key) {
            case 'ArrowRight':
                newIndex = Math.max(0, currentIndex - 1);
                break;
            case 'ArrowLeft':
                newIndex = Math.min(options.length - 1, currentIndex + 1);
                break;
            case 'ArrowUp':
                newIndex = Math.max(0, currentIndex - 2);
                break;
            case 'ArrowDown':
                newIndex = Math.min(options.length - 1, currentIndex + 2);
                break;
            case 'Enter':
            case ' ':
                e.preventDefault();
                e.target.click();
                return;
            default:
                return;
        }
        
        if (newIndex !== undefined) {
            e.preventDefault();
            options[newIndex].focus();
        }
    }
});

// Make generation options focusable
document.querySelectorAll('.generation-option').forEach(option => {
    option.setAttribute('tabindex', '0');
});
</script>
@endpush