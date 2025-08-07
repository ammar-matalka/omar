@extends('layouts.admin')

@section('title', 'إنشاء دوسية ورقية جديدة')

@push('styles')
<style>
.create-booklet-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.main-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 1rem;
}

.page-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-info h1 {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.header-subtitle {
    color: #6b7280;
    font-size: 1rem;
}

.back-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.form-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.form-grid {
    display: grid;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 0;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    background: white;
    color: #1f2937;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
}

.form-input.error, .form-select.error, .form-textarea.error {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.error-message {
    color: #ef4444;
    font-size: 0.8rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.success-message {
    color: #059669;
    font-size: 0.8rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.help-text {
    color: #6b7280;
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.checkbox-input {
    width: auto;
    margin: 0;
}

.checkbox-label {
    margin: 0;
    font-weight: 500;
    cursor: pointer;
}

.inventory-section {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.inventory-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
    color: #374151;
}

.inventory-content {
    display: none;
}

.inventory-content.show {
    display: block;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid transparent;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.btn-primary {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #047857, #065f46);
    transform: translateY(-1px);
}

.btn-secondary {
    background: white;
    color: #6b7280;
    border-color: #d1d5db;
}

.btn-secondary:hover {
    background: #f9fafb;
    border-color: #9ca3af;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #f3f4f6;
}

.platform-info {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 1rem;
    margin-top: 0.5rem;
    display: none;
}

.platform-info.show {
    display: block;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 0.25rem 0;
    font-size: 0.85rem;
}

.info-label {
    font-weight: 600;
    color: #374151;
}

.info-value {
    color: #6b7280;
}

@media (max-width: 768px) {
    .create-booklet-page {
        padding: 1rem 0;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .back-actions {
        justify-content: center;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
@endpush

@section('content')
<div class="create-booklet-page">
    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <h1>
                        <div class="header-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        إنشاء دوسية ورقية جديدة
                    </h1>
                    <p class="header-subtitle">إضافة دوسية ورقية جديدة إلى النظام</p>
                </div>
                <div class="back-actions">
                    <a href="{{ route('admin.educational.physical-booklets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Create Form -->
        <div class="form-section">
            <form method="POST" action="{{ route('admin.educational.physical-booklets.store') }}" id="createBookletForm">
                @csrf
                
                <div class="form-grid">
                    <!-- Platform Selection -->
                    <div class="form-group">
                        <label for="platform_id" class="form-label">
                            <i class="fas fa-chalkboard-teacher"></i>
                            المنصة *
                        </label>
                        <select name="platform_id" id="platform_id" class="form-select {{ $errors->has('platform_id') ? 'error' : '' }}" required>
                            <option value="">اختر المنصة</option>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform->id }}" 
                                        data-generation="{{ $platform->teacher->subject->generation->display_name }}"
                                        data-subject="{{ $platform->teacher->subject->name }}"
                                        data-teacher="{{ $platform->teacher->name }}"
                                        {{ old('platform_id') == $platform->id ? 'selected' : '' }}>
                                    {{ $platform->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('platform_id')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="platform-info" id="platformInfo">
                            <div class="info-item">
                                <span class="info-label">الجيل:</span>
                                <span class="info-value" id="platformGeneration">-</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">المادة:</span>
                                <span class="info-value" id="platformSubject">-</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">المعلم:</span>
                                <span class="info-value" id="platformTeacher">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-book"></i>
                                اسم الدوسية *
                            </label>
                            <input type="text" name="name" id="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}" 
                                   value="{{ old('name') }}" required maxlength="255" 
                                   placeholder="أدخل اسم الدوسية...">
                            @error('name')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i>
                            الوصف
                        </label>
                        <textarea name="description" id="description" class="form-textarea {{ $errors->has('description') ? 'error' : '' }}" 
                                  maxlength="1000" placeholder="وصف اختياري للدوسية...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="help-text">الحد الأقصى 1000 حرف</div>
                    </div>

                    <!-- Physical Properties -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="pages_count" class="form-label">
                                <i class="fas fa-file-alt"></i>
                                عدد الصفحات *
                            </label>
                            <input type="number" name="pages_count" id="pages_count" class="form-input {{ $errors->has('pages_count') ? 'error' : '' }}" 
                                   value="{{ old('pages_count') }}" required min="1" max="1000" 
                                   placeholder="عدد الصفحات">
                            @error('pages_count')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="weight_grams" class="form-label">
                                <i class="fas fa-weight"></i>
                                الوزن (جرام)
                            </label>
                            <input type="number" name="weight_grams" id="weight_grams" class="form-input {{ $errors->has('weight_grams') ? 'error' : '' }}" 
                                   value="{{ old('weight_grams') }}" min="1" max="10000" 
                                   placeholder="الوزن بالجرام">
                            @error('weight_grams')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="help-text">اختياري - يساعد في حساب تكلفة الشحن</div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="is_active" id="is_active" class="checkbox-input" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="checkbox-label">
                                <i class="fas fa-toggle-on" style="color: #059669;"></i>
                                الدوسية نشطة
                            </label>
                        </div>
                        <div class="help-text">الدوسيات النشطة فقط تظهر للطلاب</div>
                    </div>

                    <!-- Inventory Section -->
                    <div class="inventory-section">
                        <div class="inventory-header">
                            <input type="checkbox" name="create_inventory" id="create_inventory" class="checkbox-input" 
                                   value="1" {{ old('create_inventory') ? 'checked' : '' }}>
                            <label for="create_inventory" class="checkbox-label">
                                <i class="fas fa-boxes"></i>
                                إنشاء مخزون أولي
                            </label>
                        </div>
                        
                        <div class="inventory-content" id="inventoryContent">
                            <div class="form-group">
                                <label for="initial_quantity" class="form-label">
                                    <i class="fas fa-sort-numeric-up"></i>
                                    الكمية الأولية *
                                </label>
                                <input type="number" name="initial_quantity" id="initial_quantity" class="form-input {{ $errors->has('initial_quantity') ? 'error' : '' }}" 
                                       value="{{ old('initial_quantity') }}" min="0" max="100000" 
                                       placeholder="عدد النسخ المتاحة">
                                @error('initial_quantity')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="help-text">عدد النسخ المتاحة للبيع في البداية</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.educational.physical-booklets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        حفظ الدوسية
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const platformSelect = document.getElementById('platform_id');
    const platformInfo = document.getElementById('platformInfo');
    const createInventoryCheckbox = document.getElementById('create_inventory');
    const inventoryContent = document.getElementById('inventoryContent');
    const initialQuantityInput = document.getElementById('initial_quantity');

    // Platform selection handler
    platformSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const generation = selectedOption.dataset.generation;
            const subject = selectedOption.dataset.subject;
            const teacher = selectedOption.dataset.teacher;
            
            document.getElementById('platformGeneration').textContent = generation;
            document.getElementById('platformSubject').textContent = subject;
            document.getElementById('platformTeacher').textContent = teacher;
            
            platformInfo.classList.add('show');
        } else {
            platformInfo.classList.remove('show');
        }
    });

    // Inventory checkbox handler
    createInventoryCheckbox.addEventListener('change', function() {
        if (this.checked) {
            inventoryContent.classList.add('show');
            initialQuantityInput.required = true;
        } else {
            inventoryContent.classList.remove('show');
            initialQuantityInput.required = false;
            initialQuantityInput.value = '';
        }
    });

    // Initialize inventory section state
    if (createInventoryCheckbox.checked) {
        inventoryContent.classList.add('show');
        initialQuantityInput.required = true;
    }

    // Initialize platform info if platform is pre-selected
    if (platformSelect.value) {
        platformSelect.dispatchEvent(new Event('change'));
    }

    // Form validation
    document.getElementById('createBookletForm').addEventListener('submit', function(e) {
        const platformId = document.getElementById('platform_id').value;
        const name = document.getElementById('name').value.trim();
        const pagesCount = document.getElementById('pages_count').value;
        
        if (!platformId) {
            e.preventDefault();
            alert('يرجى اختيار المنصة');
            document.getElementById('platform_id').focus();
            return;
        }
        
        if (!name) {
            e.preventDefault();
            alert('يرجى إدخال اسم الدوسية');
            document.getElementById('name').focus();
            return;
        }
        
        if (!pagesCount || pagesCount < 1) {
            e.preventDefault();
            alert('يرجى إدخال عدد الصفحات');
            document.getElementById('pages_count').focus();
            return;
        }
        
        if (createInventoryCheckbox.checked) {
            const initialQuantity = document.getElementById('initial_quantity').value;
            if (!initialQuantity || initialQuantity < 0) {
                e.preventDefault();
                alert('يرجى إدخال الكمية الأولية');
                document.getElementById('initial_quantity').focus();
                return;
            }
        }
    });

    // Character counter for description
    const descriptionTextarea = document.getElementById('description');
    const maxLength = 1000;
    
    if (descriptionTextarea) {
        const helpText = descriptionTextarea.parentNode.querySelector('.help-text');
        
        descriptionTextarea.addEventListener('input', function() {
            const remaining = maxLength - this.value.length;
            helpText.textContent = `الحد الأقصى 1000 حرف (متبقي: ${remaining})`;
            
            if (remaining < 50) {
                helpText.style.color = '#ef4444';
            } else {
                helpText.style.color = '#6b7280';
            }
        });
    }
});
</script>
@endpush