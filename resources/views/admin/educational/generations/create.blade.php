@extends('layouts.admin')

@section('title', 'إضافة جيل جديد')
@section('page-title', 'إضافة جيل جديد')

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
    <a href="{{ route('admin.educational.generations.index') }}" class="breadcrumb-link">إدارة الأجيال</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    <span>إضافة جيل جديد</span>
</div>
@endsection

@push('styles')
<style>
    .create-form {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .intro-section {
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-secondary-50));
        border: 2px solid var(--admin-primary-200);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        margin-bottom: var(--space-2xl);
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    
    .intro-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-600));
    }
    
    .intro-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        margin: 0 auto var(--space-lg);
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
        border: 4px solid white;
    }
    
    .intro-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-md);
    }
    
    .intro-description {
        color: var(--admin-secondary-600);
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 600px;
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
    
    .year-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: var(--space-md);
        margin-top: var(--space-lg);
    }
    
    .year-option {
        position: relative;
    }
    
    .year-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    
    .year-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: var(--space-lg);
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: all var(--transition-fast);
        text-align: center;
    }
    
    .year-card:hover {
        border-color: var(--admin-primary-300);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .year-option input[type="radio"]:checked + .year-card {
        border-color: var(--admin-primary-500);
        background: linear-gradient(135deg, var(--admin-primary-50), white);
        transform: translateY(-2px);
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.2);
    }
    
    .year-number {
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--admin-primary-600);
        margin-bottom: var(--space-sm);
    }
    
    .year-option input[type="radio"]:checked + .year-card .year-number {
        color: var(--admin-primary-700);
    }
    
    .year-age {
        font-size: 0.9rem;
        color: var(--admin-secondary-600);
        font-weight: 500;
    }
    
    .year-grade {
        font-size: 0.8rem;
        color: var(--admin-secondary-500);
        margin-top: var(--space-xs);
    }
    
    .current-year-info {
        background: var(--info-50);
        border: 2px solid var(--info-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-md);
        color: var(--info-700);
    }
    
    .preview-section {
        background: white;
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        margin-bottom: var(--space-2xl);
        position: relative;
        overflow: hidden;
        opacity: 0.5;
        transition: all var(--transition-fast);
    }
    
    .preview-section.active {
        opacity: 1;
        border-color: var(--success-300);
    }
    
    .preview-section.active::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--success-500), #059669);
    }
    
    .preview-content {
        text-align: center;
    }
    
    .preview-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 900;
        margin: 0 auto var(--space-lg);
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
        border: 4px solid white;
    }
    
    .preview-name {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-md);
    }
    
    .preview-details {
        color: var(--admin-secondary-600);
        font-size: 1.1rem;
        margin-bottom: var(--space-lg);
    }
    
    .preview-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--space-lg);
        margin-top: var(--space-lg);
    }
    
    .preview-stat {
        background: var(--admin-secondary-50);
        border-radius: var(--radius-lg);
        padding: var(--space-md);
        text-align: center;
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-primary-700);
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: var(--admin-primary-600);
        font-weight: 500;
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
    
    .form-actions {
        display: flex;
        gap: var(--space-lg);
        justify-content: center;
        padding-top: var(--space-2xl);
        border-top: 2px solid var(--admin-secondary-200);
        margin-top: var(--space-2xl);
    }
    
    .existing-years {
        background: var(--warning-50);
        border: 2px solid var(--warning-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .existing-years h4 {
        color: var(--warning-700);
        font-weight: 600;
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .existing-years-list {
        display: flex;
        flex-wrap: wrap;
        gap: var(--space-sm);
    }
    
    .existing-year {
        background: var(--warning-100);
        color: var(--warning-800);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .create-form {
            max-width: 100%;
        }
        
        .year-selector {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .preview-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="create-form fade-in">
    <!-- Introduction Section -->
    <div class="intro-section">
        <div class="intro-icon">
            <i class="fas fa-users-class"></i>
        </div>
        <h1 class="intro-title">إضافة جيل دراسي جديد</h1>
        <p class="intro-description">
            قم بإضافة جيل دراسي جديد للنظام التعليمي. الجيل يمثل مجموعة الطلاب المولودين في نفس السنة، مما يساعد في تنظيم المواد والمعلمين حسب المراحل العمرية.
        </p>
    </div>
    
    <form action="{{ route('admin.educational.generations.store') }}" method="POST" id="generationForm">
        @csrf
        
        <!-- Birth Year Selection -->
        <div class="form-section">
            <h2 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                اختيار سنة الميلاد
            </h2>
            
            <div class="current-year-info">
                <i class="fas fa-info-circle" style="font-size: 1.25rem;"></i>
                <span><strong>السنة الحالية:</strong> {{ date('Y') }} • اختر سنة الميلاد لتحديد الجيل المراد إضافته</span>
            </div>
            
            @if(isset($existingYears) && $existingYears->count() > 0)
                <div class="existing-years">
                    <h4>
                        <i class="fas fa-exclamation-triangle"></i>
                        الأجيال الموجودة بالفعل
                    </h4>
                    <div class="existing-years-list">
                        @foreach($existingYears as $year)
                            <span class="existing-year">{{ $year }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <div class="year-selector">
                @php
                    $currentYear = date('Y');
                    $startYear = $currentYear - 25; // 25 سنة للوراء
                    $endYear = $currentYear - 3;   // 3 سنوات للوراء (حتى لا نضيف أطفال صغار جداً)
                @endphp
                
                @for($year = $endYear; $year >= $startYear; $year--)
                    @php
                        $age = $currentYear - $year;
                        $expectedGrade = '';
                        
                        if ($age >= 6 && $age <= 11) {
                            $gradeNum = $age - 5;
                            $expectedGrade = "الصف {$gradeNum} ابتدائي";
                        } elseif ($age >= 12 && $age <= 14) {
                            $gradeNum = $age - 11;
                            $expectedGrade = "الصف {$gradeNum} إعدادي";
                        } elseif ($age >= 15 && $age <= 17) {
                            $gradeNum = $age - 14;
                            $expectedGrade = "الصف {$gradeNum} ثانوي";
                        } elseif ($age >= 18) {
                            $expectedGrade = "جامعي";
                        } else {
                            $expectedGrade = "ما قبل المدرسة";
                        }
                        
                        $isExisting = isset($existingYears) && $existingYears->contains($year);
                    @endphp
                    
                    <div class="year-option">
                        <input type="radio" 
                               name="birth_year" 
                               value="{{ $year }}" 
                               id="year_{{ $year }}"
                               {{ old('birth_year') == $year ? 'checked' : '' }}
                               {{ $isExisting ? 'disabled' : '' }}>
                        <label for="year_{{ $year }}" class="year-card {{ $isExisting ? 'disabled' : '' }}">
                            <div class="year-number">{{ $year }}</div>
                            <div class="year-age">{{ $age }} سنة</div>
                            <div class="year-grade">{{ $expectedGrade }}</div>
                            @if($isExisting)
                                <div style="color: var(--warning-600); font-size: 0.7rem; margin-top: var(--space-xs);">
                                    <i class="fas fa-lock"></i> موجود
                                </div>
                            @endif
                        </label>
                    </div>
                @endfor
            </div>
            
            @error('birth_year')
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ $message }}
                </div>
            @enderror
            
            <div class="help-text">
                <i class="fas fa-lightbulb"></i>
                اختر سنة الميلاد التي تمثل الجيل المراد إضافته. سيتم حساب العمر الحالي والصف المتوقع تلقائياً.
            </div>
        </div>
        
        <!-- Custom Name Section -->
        <div class="form-section">
            <h2 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-tag"></i>
                </div>
                تسمية الجيل (اختياري)
            </h2>
            
            <div class="form-group">
                <label class="form-label" for="name">
                    <i class="fas fa-signature"></i>
                    اسم مخصص للجيل
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       class="form-input" 
                       value="{{ old('name') }}"
                       placeholder="مثال: جيل الألفية الجديدة، جيل كورونا، جيل التكنولوجيا...">
                
                @error('name')
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
                
                <div class="help-text">
                    <i class="fas fa-info-circle"></i>
                    إذا تركت هذا الحقل فارغاً، سيتم استخدام الاسم التلقائي "جيل [سنة الميلاد]". يمكنك إضافة اسم مميز ومعبر عن خصائص هذا الجيل.
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
                               {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                               style="width: 20px; height: 20px;">
                        <span style="color: var(--success-600); font-weight: 600;">نشط</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: var(--space-sm); cursor: pointer;">
                        <input type="radio" 
                               name="is_active" 
                               value="0" 
                               {{ old('is_active') == '0' ? 'checked' : '' }}
                               style="width: 20px; height: 20px;">
                        <span style="color: var(--error-500); font-weight: 600;">غير نشط</span>
                    </label>
                </div>
                
                <div class="help-text">
                    <i class="fas fa-info-circle"></i>
                    الأجيال النشطة فقط ستظهر في خيارات إضافة المواد والمعلمين. يُنصح بتفعيل الجيل إذا كنت تخطط لاستخدامه فوراً.
                </div>
            </div>
        </div>
        
        <!-- Preview Section -->
        <div class="preview-section" id="previewSection">
            <h2 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-eye"></i>
                </div>
                معاينة الجيل
            </h2>
            
            <div class="preview-content">
                <div class="preview-icon" id="previewIcon">
                    <span id="previewYear">----</span>
                </div>
                <h3 id="previewName" class="preview-name">اختر سنة الميلاد لرؤية المعاينة</h3>
                <p id="previewDetails" class="preview-details">سيتم عرض تفاصيل الجيل هنا</p>
                
                <div class="preview-stats" id="previewStats" style="display: none;">
                    <div class="preview-stat">
                        <div class="stat-value" id="previewAge">--</div>
                        <div class="stat-label">العمر الحالي</div>
                    </div>
                    <div class="preview-stat">
                        <div class="stat-value" id="previewGrade">--</div>
                        <div class="stat-label">الصف المتوقع</div>
                    </div>
                    <div class="preview-stat">
                        <div class="stat-value" id="previewGraduation">--</div>
                        <div class="stat-label">سنة التخرج المتوقعة</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                <i class="fas fa-plus"></i>
                إضافة الجيل
            </button>
            <a href="{{ route('admin.educational.generations.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Update preview when birth year changes
function updatePreview() {
    const selectedYear = document.querySelector('input[name="birth_year"]:checked');
    const customName = document.getElementById('name').value;
    const previewSection = document.getElementById('previewSection');
    const submitBtn = document.getElementById('submitBtn');
    
    if (selectedYear) {
        const year = parseInt(selectedYear.value);
        const currentYear = {{ date('Y') }};
        const age = currentYear - year;
        const generationName = customName || `جيل ${year}`;
        
        // Calculate expected grade
        let expectedGrade = '';
        if (age >= 6 && age <= 11) {
            expectedGrade = `الصف ${age - 5} ابتدائي`;
        } else if (age >= 12 && age <= 14) {
            expectedGrade = `الصف ${age - 11} إعدادي`;
        } else if (age >= 15 && age <= 17) {
            expectedGrade = `الصف ${age - 14} ثانوي`;
        } else if (age >= 18) {
            expectedGrade = 'جامعي';
        } else {
            expectedGrade = 'ما قبل المدرسة';
        }
        
        // Update preview
        document.getElementById('previewYear').textContent = year;
        document.getElementById('previewName').textContent = generationName;
        document.getElementById('previewDetails').textContent = `مواليد ${year} - ${age} سنة - ${expectedGrade}`;
        document.getElementById('previewAge').textContent = `${age} سنة`;
        document.getElementById('previewGrade').textContent = expectedGrade;
        document.getElementById('previewGraduation').textContent = year + 18;
        
        // Show preview
        previewSection.classList.add('active');
        document.getElementById('previewStats').style.display = 'grid';
        
        // Enable submit button
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
        submitBtn.style.cursor = 'pointer';
    } else {
        // Hide preview
        previewSection.classList.remove('active');
        document.getElementById('previewStats').style.display = 'none';
        document.getElementById('previewName').textContent = 'اختر سنة الميلاد لرؤية المعاينة';
        document.getElementById('previewDetails').textContent = 'سيتم عرض تفاصيل الجيل هنا';
        document.getElementById('previewYear').textContent = '----';
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        submitBtn.style.cursor = 'not-allowed';
    }
}

// Handle year selection changes
document.querySelectorAll('input[name="birth_year"]').forEach(radio => {
    radio.addEventListener('change', updatePreview);
});

// Handle name input changes
document.getElementById('name').addEventListener('input', updatePreview);

// Form validation and submission
document.getElementById('generationForm').addEventListener('submit', function(e) {
    const selectedYear = document.querySelector('input[name="birth_year"]:checked');
    
    if (!selectedYear) {
        e.preventDefault();
        alert('يرجى اختيار سنة الميلاد');
        return;
    }
    
    // Disable submit button to prevent double submission
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize preview
    updatePreview();
    
    // Add animation classes
    setTimeout(() => {
        document.querySelectorAll('.form-section, .intro-section, .preview-section').forEach((section, index) => {
            setTimeout(() => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                section.style.transition = 'all 0.6s ease';
                section.style.opacity = '1';
                section.style.transform = 'translateY(0)';
            }, index * 200);
        });
    }, 100);
    
    // Add hover effects to year cards
    document.querySelectorAll('.year-card:not(.disabled)').forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (!this.closest('.year-option').querySelector('input').disabled) {
                this.style.transform = 'translateY(-4px) scale(1.02)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            if (!this.closest('.year-option').querySelector('input').checked) {
                this.style.transform = 'translateY(0) scale(1)';
            }
        });
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Esc to cancel
    if (e.key === 'Escape') {
        if (confirm('هل تريد إلغاء إضافة الجيل والعودة؟')) {
            window.location.href = '{{ route("admin.educational.generations.index") }}';
        }
    }
});

// Warn about unsaved changes
let hasUnsavedChanges = false;

document.querySelectorAll('input[name="birth_year"], input[name="name"], input[name="is_active"]').forEach(input => {
    input.addEventListener('change', () => hasUnsavedChanges = true);
    input.addEventListener('input', () => hasUnsavedChanges = true);
});

document.getElementById('generationForm').addEventListener('submit', () => hasUnsavedChanges = false);

window.addEventListener('beforeunload', function(e) {
    if (hasUnsavedChanges) {
        e.preventDefault();
        e.returnValue = 'لديك تغييرات غير محفوظة. هل تريد المغادرة؟';
    }
});
</script>
@endpush