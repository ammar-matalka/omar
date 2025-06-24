@extends('layouts.admin')

@section('title', 'تعديل الجيل - ' . $generation->display_name)
@section('page-title', 'تعديل الجيل')

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
    <a href="{{ route('admin.educational.generations.show', $generation) }}" class="breadcrumb-link">{{ $generation->display_name }}</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    <span>تعديل</span>
</div>
@endsection

@push('styles')
<style>
    .edit-form {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .current-info {
        background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-secondary-50));
        border: 2px solid var(--admin-primary-200);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        margin-bottom: var(--space-2xl);
        position: relative;
        overflow: hidden;
    }
    
    .current-info::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-600));
    }
    
    .current-generation {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .current-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 900;
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
        border: 4px solid white;
    }
    
    .current-details {
        flex: 1;
    }
    
    .current-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
    }
    
    .current-subtitle {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        margin-bottom: var(--space-md);
    }
    
    .current-status {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-active {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        color: #991b1b;
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
    
    .year-display {
        background: var(--admin-secondary-50);
        border: 2px solid var(--admin-secondary-300);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        text-align: center;
        margin-bottom: var(--space-lg);
        position: relative;
    }
    
    .year-number {
        font-size: 3rem;
        font-weight: 900;
        color: var(--admin-primary-600);
        margin-bottom: var(--space-md);
    }
    
    .year-description {
        color: var(--admin-secondary-600);
        font-size: 1.1rem;
        line-height: 1.6;
    }
    
    .locked-icon {
        position: absolute;
        top: var(--space-md);
        right: var(--space-md);
        color: var(--warning-500);
        font-size: 1.25rem;
    }
    
    .year-info {
        background: var(--admin-primary-50);
        border: 2px solid var(--admin-primary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-lg);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--space-lg);
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
    
    .subjects-info {
        background: var(--info-50);
        border: 2px solid var(--info-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-md);
        color: var(--info-700);
    }
    
    .subjects-count {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--info-600);
    }
    
    @media (max-width: 768px) {
        .edit-form {
            max-width: 100%;
        }
        
        .current-generation {
            flex-direction: column;
            text-align: center;
            gap: var(--space-lg);
        }
        
        .current-icon {
            width: 60px;
            height: 60px;
            font-size: 1.25rem;
        }
        
        .current-title {
            font-size: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="edit-form fade-in">
    <!-- Current Generation Info -->
    <div class="current-info">
        <div class="current-generation">
            <div class="current-icon">
                {{ $generation->birth_year }}
            </div>
            <div class="current-details">
                <h2 class="current-title">{{ $generation->display_name }}</h2>
                <p class="current-subtitle">
                    مواليد {{ $generation->birth_year }} • {{ $generation->current_grade }} • {{ $generation->age_range }}
                </p>
                <div class="current-status {{ $generation->is_active ? 'status-active' : 'status-inactive' }}">
                    <i class="fas fa-{{ $generation->is_active ? 'check-circle' : 'times-circle' }}"></i>
                    {{ $generation->is_active ? 'نشط' : 'غير نشط' }}
                </div>
            </div>
        </div>
        
        @if($generation->subjects->count() > 0)
            <div class="subjects-info">
                <i class="fas fa-info-circle"></i>
                <span>هذا الجيل يحتوي على <strong class="subjects-count">{{ $generation->subjects->count() }}</strong> مادة دراسية و <strong class="subjects-count">{{ $generation->subjects->sum(function($s) { return $s->teachers->count(); }) }}</strong> معلم</span>
            </div>
        @endif
    </div>
    
    <form action="{{ route('admin.educational.generations.update', $generation) }}" method="POST" id="generationForm">
        @csrf
        @method('PATCH')
        
        <!-- Birth Year Section (Read-only) -->
        <div class="form-section">
            <h3 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                سنة الميلاد
            </h3>
            
            <div class="warning-box">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <strong>تنبيه:</strong> لا يمكن تعديل سنة الميلاد بعد إنشاء الجيل لضمان سلامة البيانات. إذا كنت بحاجة لتغيير سنة الميلاد، يرجى إنشاء جيل جديد.
                </div>
            </div>
            
            <div class="year-display">
                <div class="locked-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="year-number">{{ $generation->birth_year }}</div>
                <div class="year-description">
                    سنة ميلاد هذا الجيل محفوظة ولا يمكن تعديلها
                </div>
            </div>
            
            <div class="year-info">
                <h4 style="font-weight: 600; color: var(--admin-primary-700); margin-bottom: var(--space-md); text-align: center;">
                    <i class="fas fa-info-circle" style="margin-left: var(--space-sm);"></i>
                    معلومات الجيل الحالية
                </h4>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-value">{{ $generation->age_range }}</div>
                        <div class="info-label">العمر الحالي</div>
                    </div>
                    <div class="info-item">
                        <div class="info-value">{{ $generation->current_grade }}</div>
                        <div class="info-label">الصف المتوقع</div>
                    </div>
                    <div class="info-item">
                        <div class="info-value">{{ $generation->birth_year + 18 }}</div>
                        <div class="info-label">سنة التخرج المتوقعة</div>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="birth_year" value="{{ $generation->birth_year }}">
        </div>
        
        <!-- Name and Settings Section -->
        <div class="form-section">
            <h3 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-tag"></i>
                </div>
                التسمية والإعدادات
            </h3>
            
            <!-- Custom Name -->
            <div class="form-group">
                <label class="form-label" for="name">
                    <i class="fas fa-signature"></i>
                    اسم مخصص للجيل (اختياري)
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       class="form-input" 
                       value="{{ old('name', $generation->name) }}"
                       placeholder="مثال: جيل الألفية الجديدة، جيل كورونا، إلخ...">
                
                @error('name')
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
                
                <div class="help-text">
                    <i class="fas fa-lightbulb"></i>
                    إذا تركت هذا الحقل فارغاً، سيتم عرض الاسم التلقائي "جيل {{ $generation->birth_year }}"
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
                               {{ old('is_active', $generation->is_active) ? 'checked' : '' }}
                               style="width: 20px; height: 20px;">
                        <span style="color: var(--success-600); font-weight: 600;">نشط</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: var(--space-sm); cursor: pointer;">
                        <input type="radio" 
                               name="is_active" 
                               value="0" 
                               {{ !old('is_active', $generation->is_active) ? 'checked' : '' }}
                               style="width: 20px; height: 20px;">
                        <span style="color: var(--error-500); font-weight: 600;">غير نشط</span>
                    </label>
                </div>
                
                <div class="help-text">
                    <i class="fas fa-info-circle"></i>
                    الأجيال النشطة فقط ستظهر في خيارات إضافة المواد والمعلمين.
                    @if($generation->subjects->count() > 0)
                        <br><strong>تنبيه:</strong> إلغاء تفعيل هذا الجيل سيؤثر على {{ $generation->subjects->count() }} مادة مرتبطة به.
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Preview Section -->
        <div class="preview-section">
            <h3 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-eye"></i>
                </div>
                معاينة التغييرات
            </h3>
            
            <div class="preview-content">
                <div class="preview-icon">
                    <span id="previewIcon">{{ $generation->birth_year }}</span>
                </div>
                <h4 id="previewName" class="preview-name">
                    {{ $generation->display_name }}
                </h4>
                <p id="previewDescription" class="preview-description">
                    مواليد {{ $generation->birth_year }} - {{ $generation->age_range }}
                </p>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                <i class="fas fa-save"></i>
                حفظ التغييرات
            </button>
            <a href="{{ route('admin.educational.generations.show', $generation) }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <a href="{{ route('admin.educational.generations.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-list"></i>
                العودة للقائمة
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Update preview in real-time
function updatePreview() {
    const customName = document.getElementById('name').value;
    const generationName = customName || `جيل {{ $generation->birth_year }}`;
    
    document.getElementById('previewName').textContent = generationName;
}

// Handle name input changes
document.getElementById('name').addEventListener('input', function() {
    updatePreview();
});

// Form validation and submission
document.getElementById('generationForm').addEventListener('submit', function(e) {
    // Disable submit button to prevent double submission
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    
    // Re-enable button after 3 seconds in case of error
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> حفظ التغييرات';
    }, 3000);
});

// Initialize animations and effects
document.addEventListener('DOMContentLoaded', function() {
    // Initialize preview
    updatePreview();
    
    // Add animation classes
    setTimeout(() => {
        document.querySelectorAll('.form-section, .current-info, .preview-section').forEach((section, index) => {
            setTimeout(() => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                section.style.transition = 'all 0.6s ease';
                section.style.opacity = '1';
                section.style.transform = 'translateY(0)';
            }, index * 200);
        });
    }, 100);
    
    // Add status change handler
    document.querySelectorAll('input[name="is_active"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if ({{ $generation->subjects->count() }} > 0) {
                if (this.value === '0') {
                    if (!confirm('تحذير: إلغاء تفعيل هذا الجيل سيؤثر على {{ $generation->subjects->count() }} مادة مرتبطة به. هل أنت متأكد؟')) {
                        document.querySelector('input[name="is_active"][value="1"]').checked = true;
                    }
                }
            }
        });
    });
    
    // Highlight changes
    const originalName = '{{ $generation->name }}';
    const originalStatus = {{ $generation->is_active ? 'true' : 'false' }};
    
    function checkForChanges() {
        const currentName = document.getElementById('name').value;
        const currentStatus = document.querySelector('input[name="is_active"]:checked').value === '1';
        
        const hasChanges = currentName !== originalName || currentStatus !== originalStatus;
        
        const submitBtn = document.getElementById('submitBtn');
        if (hasChanges) {
            submitBtn.style.background = 'linear-gradient(135deg, var(--warning-500), #d97706)';
            submitBtn.querySelector('i').className = 'fas fa-exclamation-triangle';
        } else {
            submitBtn.style.background = '';
            submitBtn.querySelector('i').className = 'fas fa-save';
        }
    }
    
    // Monitor form changes
    document.getElementById('name').addEventListener('input', checkForChanges);
    document.querySelectorAll('input[name="is_active"]').forEach(radio => {
        radio.addEventListener('change', checkForChanges);
    });
    
    // Initial check
    checkForChanges();
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+S to save
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        document.getElementById('generationForm').submit();
    }
    
    // Esc to cancel
    if (e.key === 'Escape') {
        if (confirm('هل تريد إلغاء التعديل والعودة؟')) {
            window.location.href = '{{ route("admin.educational.generations.show", $generation) }}';
        }
    }
});

// Warn about unsaved changes
let hasUnsavedChanges = false;

document.getElementById('name').addEventListener('input', () => hasUnsavedChanges = true);
document.querySelectorAll('input[name="is_active"]').forEach(radio => {
    radio.addEventListener('change', () => hasUnsavedChanges = true);
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