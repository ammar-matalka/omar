{{-- resources/views/admin/educational/digital-cards/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'تعديل البطاقة الرقمية - ' . $digitalCard->name)

@push('styles')
<style>
.digital-card-form {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.form-container {
    max-width: 900px;
    margin: 0 auto;
}

.form-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, #d97706, #b45309);
    color: white;
    padding: 2rem;
    text-align: center;
    position: relative;
}

.form-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="edit-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="2" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23edit-pattern)"/></svg>');
    opacity: 0.3;
}

.form-header-content {
    position: relative;
    z-index: 1;
}

.digital-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.form-title {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.form-subtitle {
    opacity: 0.9;
    font-size: 1.1rem;
}

.current-info {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1rem;
    font-size: 0.9rem;
}

.form-body {
    padding: 2.5rem;
}

.form-section {
    margin-bottom: 2.5rem;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.3rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e5e7eb;
}

.section-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #d97706, #b45309);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.form-group {
    margin-bottom: 1.75rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
    color: #374151;
    font-size: 0.95rem;
}

.label-icon {
    color: #d97706;
    font-size: 0.9rem;
}

.required-mark {
    color: #ef4444;
    margin-right: 0.25rem;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: white;
    color: #1f2937;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #d97706;
    box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.1);
    transform: translateY(-2px);
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
}

.form-help {
    font-size: 0.85rem;
    color: #6b7280;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.help-icon {
    color: #f59e0b;
}

.digital-features {
    background: linear-gradient(135deg, #fef3c7, #fed7aa);
    border: 2px solid #f59e0b;
    border-radius: 16px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.features-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 1rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.25rem;
}

.duration-presets {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1rem;
}

.presets-title {
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.preset-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.75rem;
}

.preset-btn {
    padding: 0.75rem 1rem;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    color: #475569;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
    text-align: center;
}

.preset-btn:hover {
    border-color: #d97706;
    background: #f8fafc;
    color: #d97706;
    transform: translateY(-2px);
}

.current-values-display {
    background: #ecfdf5;
    border: 2px solid #10b981;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.current-values-title {
    font-weight: 600;
    color: #047857;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.current-value-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #d1fae5;
}

.current-value-item:last-child {
    border-bottom: none;
}

.current-value-label {
    color: #047857;
    font-weight: 500;
}

.current-value-text {
    color: #065f46;
    font-weight: 600;
}

.preview-section {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    margin-top: 1.5rem;
}

.preview-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.preview-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.preview-content {
    opacity: 0.9;
    font-size: 0.95rem;
    white-space: pre-line;
}

.form-actions {
    background: #f8fafc;
    padding: 2rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    border: 2px solid transparent;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.btn-primary {
    background: linear-gradient(135deg, #d97706, #b45309);
    color: white;
    box-shadow: 0 4px 14px 0 rgba(217, 119, 6, 0.39);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #b45309, #92400e);
    box-shadow: 0 8px 25px 0 rgba(217, 119, 6, 0.6);
    transform: translateY(-2px);
}

.btn-secondary {
    background: white;
    color: #6b7280;
    border-color: #d1d5db;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}

.btn-secondary:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    transform: translateY(-1px);
}

.error-message {
    color: #ef4444;
    font-size: 0.85rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.changes-indicator {
    background: #fef3c7;
    border: 1px solid #f59e0b;
    border-radius: 8px;
    padding: 0.75rem;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: #92400e;
    display: none;
}

.changes-indicator.show {
    display: block;
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .digital-card-form {
        padding: 1rem;
    }
    
    .form-body {
        padding: 1.5rem;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .preset-buttons {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
@endpush

@section('content')
<div class="digital-card-form">
    <div class="container-fluid">
        <div class="form-container">
            <div class="form-card">
                <!-- Header -->
                <div class="form-header">
                    <div class="form-header-content">
                        <div class="digital-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h1 class="form-title">تعديل البطاقة الرقمية</h1>
                        <p class="form-subtitle">قم بتحديث مواصفات وإعدادات البطاقة التعليمية الرقمية</p>
                        
                        <div class="current-info">
                            <strong>البطاقة الحالية:</strong> {{ $digitalCard->name }} |
                            <strong>المنصة:</strong> {{ $digitalCard->platform->name }} |
                            <strong>تم الإنشاء:</strong> {{ $digitalCard->created_at->format('Y-m-d') }}
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.educational.digital-cards.update', $digitalCard) }}" method="POST" id="digitalCardForm">
                    @csrf
                    @method('PATCH')
                    
                    <div class="form-body">
                        <!-- Current Values Display -->
                        <div class="current-values-display">
                            <div class="current-values-title">
                                <i class="fas fa-info-circle"></i>
                                القيم الحالية
                            </div>
                            <div class="current-value-item">
                                <span class="current-value-label">مدة الصلاحية:</span>
                                <span class="current-value-text">{{ $digitalCard->duration_display ?: 'صلاحية دائمة' }}</span>
                            </div>
                            <div class="current-value-item">
                                <span class="current-value-label">عدد الدروس:</span>
                                <span class="current-value-text">{{ $digitalCard->lessons_display ?: 'دروس غير محدودة' }}</span>
                            </div>
                            <div class="current-value-item">
                                <span class="current-value-label">الحالة:</span>
                                <span class="current-value-text">{{ $digitalCard->is_active ? 'نشطة' : 'غير نشطة' }}</span>
                            </div>
                        </div>

                        <!-- Platform Selection -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                المنصة التعليمية
                            </h2>

                            <div class="form-group">
                                <label for="platform_id" class="form-label">
                                    <i class="fas fa-desktop label-icon"></i>
                                    المنصة التعليمية
                                    <span class="required-mark">*</span>
                                </label>
                                <select name="platform_id" id="platform_id" class="form-select" required>
                                    <option value="">اختر المنصة التعليمية...</option>
                                    @foreach($platforms as $platform)
                                        <option value="{{ $platform->id }}" {{ old('platform_id', $digitalCard->platform_id) == $platform->id ? 'selected' : '' }}>
                                            {{ $platform->teaching_chain }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-help">
                                    <i class="fas fa-info-circle help-icon"></i>
                                    يمكنك تغيير المنصة لنقل البطاقة لمنصة أخرى
                                </div>
                                @error('platform_id')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="changes-indicator" id="platformChange">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    تغيير المنصة سينقل البطاقة لسلسلة تعليمية مختلفة
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                المعلومات الأساسية
                            </h2>

                            <div class="features-grid">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-tag label-icon"></i>
                                        اسم البطاقة الرقمية
                                        <span class="required-mark">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" class="form-input" 
                                           value="{{ old('name', $digitalCard->name) }}" required maxlength="255"
                                           placeholder="مثل: باقة الفيزياء الشاملة">
                                    <div class="form-help">
                                        <i class="fas fa-lightbulb help-icon"></i>
                                        اختر اسماً واضحاً يعبر عن محتوى البطاقة
                                    </div>
                                    @error('name')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="is_active" class="form-label">
                                        <i class="fas fa-toggle-on label-icon"></i>
                                        حالة البطاقة
                                    </label>
                                    <select name="is_active" id="is_active" class="form-select">
                                        <option value="1" {{ old('is_active', $digitalCard->is_active) == '1' ? 'selected' : '' }}>
                                            نشطة - متاحة للطلاب
                                        </option>
                                        <option value="0" {{ old('is_active', $digitalCard->is_active) == '0' ? 'selected' : '' }}>
                                            غير نشطة - مخفية
                                        </option>
                                    </select>
                                    <div class="form-help">
                                        <i class="fas fa-eye help-icon"></i>
                                        البطاقات النشطة فقط تظهر للطلاب
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left label-icon"></i>
                                    وصف البطاقة
                                </label>
                                <textarea name="description" id="description" class="form-textarea" 
                                          maxlength="1000" placeholder="وصف تفصيلي لمحتوى البطاقة الرقمية وما تقدمه للطلاب">{{ old('description', $digitalCard->description) }}</textarea>
                                <div class="form-help">
                                    <i class="fas fa-file-alt help-icon"></i>
                                    وصف يساعد الطلاب على فهم قيمة البطاقة (اختياري - حد أقصى 1000 حرف)
                                </div>
                                @error('description')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Digital Features -->
                        <div class="form-section">
                            <h2 class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-microchip"></i>
                                </div>
                                خصائص البطاقة الرقمية
                            </h2>

                            <div class="digital-features">
                                <div class="features-title">
                                    <i class="fas fa-star"></i>
                                    المواصفات الرقمية
                                </div>

                                <div class="features-grid">
                                    <div class="form-group">
                                        <label for="duration_days" class="form-label">
                                            <i class="fas fa-calendar-alt label-icon"></i>
                                            مدة صلاحية البطاقة
                                        </label>
                                        <input type="number" name="duration_days" id="duration_days" class="form-input" 
                                               value="{{ old('duration_days', $digitalCard->duration_days) }}" min="1" max="3650"
                                               placeholder="365">
                                        <div class="form-help">
                                            <i class="fas fa-clock help-icon"></i>
                                            عدد الأيام التي تبقى فيها البطاقة صالحة (اتركه فارغاً للصلاحية الدائمة)
                                        </div>
                                        @error('duration_days')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        <!-- Duration Presets -->
                                        <div class="duration-presets">
                                            <div class="presets-title">
                                                <i class="fas fa-magic"></i>
                                                اختيارات سريعة
                                            </div>
                                            <div class="preset-buttons">
                                                <button type="button" class="preset-btn" onclick="setDuration(30)">شهر واحد</button>
                                                <button type="button" class="preset-btn" onclick="setDuration(90)">3 أشهر</button>
                                                <button type="button" class="preset-btn" onclick="setDuration(180)">6 أشهر</button>
                                                <button type="button" class="preset-btn" onclick="setDuration(365)">سنة كاملة</button>
                                                <button type="button" class="preset-btn" onclick="setDuration('')">دائمة</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="lessons_count" class="form-label">
                                            <i class="fas fa-play-circle label-icon"></i>
                                            عدد الدروس المتاحة
                                        </label>
                                        <input type="number" name="lessons_count" id="lessons_count" class="form-input" 
                                               value="{{ old('lessons_count', $digitalCard->lessons_count) }}" min="1" max="1000"
                                               placeholder="50">
                                        <div class="form-help">
                                            <i class="fas fa-infinity help-icon"></i>
                                            عدد الدروس التي يمكن للطالب الوصول إليها (اتركه فارغاً للدروس غير المحدودة)
                                        </div>
                                        @error('lessons_count')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="form-section">
                            <div class="preview-section" id="cardPreview">
                                <div class="preview-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="preview-title">معاينة البطاقة المُحدثة</div>
                                <div class="preview-content" id="previewContent">
                                    يتم تحديث المعاينة تلقائياً
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('admin.educational.digital-cards.show', $digitalCard) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" name="action" value="save" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                حفظ التعديلات
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Original values for comparison
const originalValues = {
    platform_id: {{ $digitalCard->platform_id }},
    name: '{{ $digitalCard->name }}',
    description: '{{ $digitalCard->description }}',
    duration_days: {{ $digitalCard->duration_days ?: 'null' }},
    lessons_count: {{ $digitalCard->lessons_count ?: 'null' }},
    is_active: {{ $digitalCard->is_active ? 'true' : 'false' }}
};

// Set duration helper function
function setDuration(days) {
    document.getElementById('duration_days').value = days;
    updatePreview();
    checkForChanges();
}

// Update preview function
function updatePreview() {
    const name = document.getElementById('name').value;
    const platformSelect = document.getElementById('platform_id');
    const platformText = platformSelect.options[platformSelect.selectedIndex]?.text;
    const duration = document.getElementById('duration_days').value;
    const lessons = document.getElementById('lessons_count').value;
    const description = document.getElementById('description').value;
    
    let previewText = '';
    
    if (name) {
        previewText += `📱 ${name}\n`;
    }
    
    if (platformText && platformText !== 'اختر المنصة التعليمية...') {
        previewText += `🎓 ${platformText}\n`;
    }
    
    if (duration) {
        const durationText = getDurationText(parseInt(duration));
        previewText += `⏰ صالحة لمدة: ${durationText}\n`;
    } else {
        previewText += `♾️ صالحة دائماً\n`;
    }
    
    if (lessons) {
        previewText += `📚 ${lessons} درس متاح\n`;
    } else {
        previewText += `🔄 دروس غير محدودة\n`;
    }
    
    if (description) {
        previewText += `\n"${description.substring(0, 100)}${description.length > 100 ? '...' : ''}"`;
    }
    
    document.getElementById('previewContent').innerHTML = previewText || 'املأ النموذج لرؤية معاينة البطاقة';
}

// Get duration text helper
function getDurationText(days) {
    if (days === 30) return 'شهر واحد';
    if (days === 90) return '3 أشهر';
    if (days === 180) return '6 أشهر';
    if (days === 365) return 'سنة كاملة';
    if (days < 30) return `${days} يوم`;
    if (days < 365) {
        const months = Math.round(days / 30);
        return `${months} شهر`;
    }
    const years = Math.round(days / 365);
    return `${years} سنة`;
}

// Check for changes
function checkForChanges() {
    const currentValues = {
        platform_id: parseInt(document.getElementById('platform_id').value) || null,
        name: document.getElementById('name').value,
        description: document.getElementById('description').value,
        duration_days: parseInt(document.getElementById('duration_days').value) || null,
        lessons_count: parseInt(document.getElementById('lessons_count').value) || null,
        is_active: document.getElementById('is_active').value === '1'
    };
    
    // Check platform change
    const platformChange = document.getElementById('platformChange');
    if (currentValues.platform_id !== originalValues.platform_id && currentValues.platform_id) {
        platformChange.classList.add('show');
    } else {
        platformChange.classList.remove('show');
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Update preview on input changes
    document.getElementById('name').addEventListener('input', function() {
        updatePreview();
        checkForChanges();
    });
    document.getElementById('platform_id').addEventListener('change', function() {
        updatePreview();
        checkForChanges();
    });
    document.getElementById('duration_days').addEventListener('input', function() {
        updatePreview();
        checkForChanges();
    });
    document.getElementById('lessons_count').addEventListener('input', function() {
        updatePreview();
        checkForChanges();
    });
    document.getElementById('description').addEventListener('input', function() {
        updatePreview();
        checkForChanges();
    });
    document.getElementById('is_active').addEventListener('change', checkForChanges);
    
    // Form validation
    document.getElementById('digitalCardForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const platformId = document.getElementById('platform_id').value;
        
        if (!name) {
            e.preventDefault();
            alert('يرجى إدخال اسم البطاقة الرقمية');
            document.getElementById('name').focus();
            return;
        }
        
        if (!platformId) {
            e.preventDefault();
            alert('يرجى اختيار المنصة التعليمية');
            document.getElementById('platform_id').focus();
            return;
        }
        
        // Show loading state
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        submitBtn.disabled = true;
        
        // Re-enable button after 5 seconds (in case of slow response)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
    
    // Initialize preview
    updatePreview();
});
</script>
@endpush