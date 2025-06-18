@extends('layouts.admin')

@section('title', __('Edit Dossier'))
@section('page-title', __('Edit Dossier'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.dossiers.index') }}" class="breadcrumb-link">{{ __('Dossiers') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <span>{{ __('Edit') }}: {{ $dossier->name }}</span>
    </div>
@endsection

@push('styles')
<style>
    .form-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .form-section {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: var(--shadow-sm);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-lg);
        padding-bottom: var(--space-md);
        border-bottom: 2px solid var(--admin-primary-500);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-lg);
    }
    
    .form-group {
        margin-bottom: var(--space-lg);
    }
    
    .form-label {
        display: block;
        margin-bottom: var(--space-sm);
        font-weight: 500;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
    }
    
    .form-label.required::after {
        content: ' *';
        color: var(--error-500);
    }
    
    .form-input {
        width: 100%;
        padding: var(--space-sm) var(--space-md);
        border: 1px solid var(--admin-secondary-300);
        border-radius: var(--radius-md);
        background: white;
        color: var(--admin-secondary-900);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-input.error {
        border-color: var(--error-500);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .form-input:disabled {
        background: var(--admin-secondary-100);
        color: var(--admin-secondary-500);
        cursor: not-allowed;
    }
    
    .form-error {
        color: var(--error-600);
        font-size: 0.75rem;
        margin-top: var(--space-xs);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .form-help {
        color: var(--admin-secondary-500);
        font-size: 0.75rem;
        margin-top: var(--space-xs);
    }
    
    .form-actions {
        display: flex;
        gap: var(--space-lg);
        justify-content: flex-end;
        padding-top: var(--space-xl);
        border-top: 1px solid var(--admin-secondary-200);
        margin-top: var(--space-xl);
    }
    
    .preview-card {
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        position: sticky;
        top: var(--space-lg);
    }
    
    .preview-header {
        text-align: center;
        margin-bottom: var(--space-lg);
    }
    
    .preview-icon {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-lg);
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 700;
        margin: 0 auto var(--space-md);
    }
    
    .preview-name {
        font-weight: 600;
        font-size: 1.125rem;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .preview-meta {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
    }
    
    .preview-details {
        margin-bottom: var(--space-lg);
    }
    
    .preview-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-sm) 0;
        border-bottom: 1px solid var(--admin-secondary-200);
        font-size: 0.875rem;
    }
    
    .preview-item:last-child {
        border-bottom: none;
    }
    
    .preview-label {
        color: var(--admin-secondary-600);
    }
    
    .preview-value {
        color: var(--admin-secondary-900);
        font-weight: 500;
    }
    
    .price-calculator {
        background: var(--admin-primary-50);
        border: 1px solid var(--admin-primary-200);
        border-radius: var(--radius-md);
        padding: var(--space-lg);
        text-align: center;
    }
    
    .base-price {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
        margin-bottom: var(--space-xs);
    }
    
    .platform-fee {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
        margin-bottom: var(--space-sm);
    }
    
    .final-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-primary-700);
    }
    
    .status-toggle {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md);
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-md);
    }
    
    .toggle-switch {
        position: relative;
        width: 50px;
        height: 24px;
        background: var(--admin-secondary-300);
        border-radius: 12px;
        cursor: pointer;
        transition: background var(--transition-fast);
    }
    
    .toggle-switch.active {
        background: var(--success-500);
    }
    
    .toggle-switch::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        transition: transform var(--transition-fast);
    }
    
    .toggle-switch.active::before {
        transform: translateX(26px);
    }
    
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-md);
        z-index: 10;
    }
    
    .loading-overlay.show {
        display: flex;
    }
    
    .stats-section {
        margin-bottom: var(--space-lg);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: var(--space-md);
    }
    
    .stat-item {
        background: white;
        border: 1px solid var(--admin-secondary-200);
        border-radius: var(--radius-md);
        padding: var(--space-md);
        text-align: center;
    }
    
    .stat-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-primary-600);
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endpush

@section('content')
<div class="form-container fade-in">
    <form action="{{ route('admin.dossiers.update', $dossier) }}" method="POST" id="dossierForm">
        @csrf
        @method('PATCH')
        
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-xl);">
            <div>
                <!-- Basic Information -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Basic Information') }}
                    </h3>
                    
                    <div class="form-group">
                        <label for="name" class="form-label required">{{ __('Dossier Name') }}</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-input @error('name') error @enderror" 
                               value="{{ old('name', $dossier->name) }}" 
                               placeholder="{{ __('Enter dossier name') }}"
                               required
                               oninput="updatePreview()">
                        @error('name')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-help">{{ __('Name as it will appear to students') }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="form-input @error('description') error @enderror" 
                                  rows="4" 
                                  placeholder="{{ __('Brief description about the dossier content (optional)') }}">{{ old('description', $dossier->description) }}</textarea>
                        @error('description')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-help">{{ __('Maximum 1000 characters') }}</div>
                    </div>
                </div>
                
                <!-- Educational Details -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-graduation-cap"></i>
                        {{ __('Educational Details') }}
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="generation_id" class="form-label required">{{ __('Generation') }}</label>
                            <div style="position: relative;">
                                <select id="generation_id" 
                                        name="generation_id" 
                                        class="form-input @error('generation_id') error @enderror" 
                                        required
                                        onchange="loadSubjects()">
                                    <option value="">{{ __('Select Generation') }}</option>
                                    @foreach($generations as $generation)
                                        <option value="{{ $generation->id }}" {{ old('generation_id', $dossier->generation_id) == $generation->id ? 'selected' : '' }}>
                                            {{ $generation->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="loading-overlay" id="generationLoading">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                            @error('generation_id')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="subject_id" class="form-label required">{{ __('Subject') }}</label>
                            <div style="position: relative;">
                                <select id="subject_id" 
                                        name="subject_id" 
                                        class="form-input @error('subject_id') error @enderror" 
                                        required
                                        onchange="loadTeachers()">
                                    <option value="">{{ __('Select Subject') }}</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id', $dossier->subject_id) == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="loading-overlay" id="subjectLoading">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                            @error('subject_id')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="teacher_id" class="form-label required">{{ __('Teacher') }}</label>
                            <div style="position: relative;">
                                <select id="teacher_id" 
                                        name="teacher_id" 
                                        class="form-input @error('teacher_id') error @enderror" 
                                        required
                                        onchange="updatePreview()">
                                    <option value="">{{ __('Select Teacher') }}</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $dossier->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="loading-overlay" id="teacherLoading">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                            @error('teacher_id')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="platform_id" class="form-label required">{{ __('Platform') }}</label>
                            <select id="platform_id" 
                                    name="platform_id" 
                                    class="form-input @error('platform_id') error @enderror" 
                                    required
                                    onchange="updatePreview(); calculatePrice()">
                                <option value="">{{ __('Select Platform') }}</option>
                                @foreach($platforms as $platform)
                                    <option value="{{ $platform->id }}" 
                                            data-percentage="{{ $platform->price_percentage }}"
                                            {{ old('platform_id', $dossier->platform_id) == $platform->id ? 'selected' : '' }}>
                                        {{ $platform->name }} (+{{ $platform->formatted_price_percentage }})
                                    </option>
                                @endforeach
                            </select>
                            @error('platform_id')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="semester" class="form-label required">{{ __('Semester') }}</label>
                            <select id="semester" 
                                    name="semester" 
                                    class="form-input @error('semester') error @enderror" 
                                    required
                                    onchange="updatePreview()">
                                <option value="">{{ __('Select Semester') }}</option>
                                <option value="first" {{ old('semester', $dossier->semester) === 'first' ? 'selected' : '' }}>{{ __('First Semester') }}</option>
                                <option value="second" {{ old('semester', $dossier->semester) === 'second' ? 'selected' : '' }}>{{ __('Second Semester') }}</option>
                                <option value="both" {{ old('semester', $dossier->semester) === 'both' ? 'selected' : '' }}>{{ __('Both Semesters') }}</option>
                            </select>
                            @error('semester')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Pricing & Details -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-calculator"></i>
                        {{ __('Pricing & Details') }}
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="price" class="form-label required">{{ __('Base Price') }}</label>
                            <div style="position: relative;">
                                <input type="number" 
                                       id="price" 
                                       name="price" 
                                       class="form-input @error('price') error @enderror" 
                                       value="{{ old('price', $dossier->price) }}" 
                                       min="0" 
                                       max="999999.99" 
                                       step="0.01"
                                       placeholder="0.00"
                                       required
                                       oninput="calculatePrice()"
                                       style="padding-right: 40px;">
                                <span style="position: absolute; right: var(--space-md); top: 50%; transform: translateY(-50%); color: var(--admin-secondary-500); font-weight: 500;">JD</span>
                            </div>
                            @error('price')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">{{ __('Base price before platform fees') }}</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="pages_count" class="form-label">{{ __('Pages Count') }}</label>
                            <input type="number" 
                                   id="pages_count" 
                                   name="pages_count" 
                                   class="form-input @error('pages_count') error @enderror" 
                                   value="{{ old('pages_count', $dossier->pages_count) }}" 
                                   min="1" 
                                   placeholder="{{ __('Number of pages') }}"
                                   oninput="updatePreview()">
                            @error('pages_count')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="file_size" class="form-label">{{ __('File Size') }}</label>
                            <input type="text" 
                                   id="file_size" 
                                   name="file_size" 
                                   class="form-input @error('file_size') error @enderror" 
                                   value="{{ old('file_size', $dossier->file_size) }}" 
                                   placeholder="{{ __('e.g., 25 MB') }}"
                                   oninput="updatePreview()">
                            @error('file_size')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">{{ __('File size (optional)') }}</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="order" class="form-label">{{ __('Display Order') }}</label>
                            <input type="number" 
                                   id="order" 
                                   name="order" 
                                   class="form-input @error('order') error @enderror" 
                                   value="{{ old('order', $dossier->order) }}" 
                                   min="0" 
                                   placeholder="0">
                            @error('order')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">{{ __('Lower numbers appear first. 0 = automatic ordering.') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Status -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-cog"></i>
                        {{ __('Status') }}
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('Dossier Status') }}</label>
                        <div class="status-toggle">
                            <span style="color: var(--admin-secondary-600);">{{ __('Inactive') }}</span>
                            <div class="toggle-switch {{ $dossier->is_active ? 'active' : '' }}" onclick="toggleStatus()" id="statusToggle">
                                <input type="hidden" name="is_active" value="{{ $dossier->is_active ? '1' : '0' }}" id="statusInput">
                            </div>
                            <span style="color: var(--success-600); font-weight: 500;">{{ __('Active') }}</span>
                        </div>
                        <div class="form-help">{{ __('Only active dossiers will be visible to students.') }}</div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.dossiers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('Back to List') }}
                    </a>
                    
                    <a href="{{ route('admin.dossiers.show', $dossier) }}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i>
                        {{ __('View Details') }}
                    </a>
                    
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i>
                        {{ __('Update Dossier') }}
                    </button>
                </div>
            </div>
            
            <!-- Preview -->
            <div>
                <div class="preview-card">
                    <div class="preview-header">
                        <div class="preview-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="preview-name" id="previewName">
                            {{ $dossier->name }}
                        </div>
                        <div class="preview-meta" id="previewMeta">
                            {{ $dossier->generation->display_name }} • {{ $dossier->subject->name }} • {{ $dossier->teacher->name }}
                        </div>
                    </div>
                    
                    <!-- Statistics -->
                    <div class="stats-section">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">{{ $dossier->orderItems()->count() }}</div>
                                <div class="stat-label">{{ __('Orders') }}</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-number">{{ $dossier->orderItems()->sum('quantity') }}</div>
                                <div class="stat-label">{{ __('Sold') }}</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-number">{{ number_format($dossier->orderItems()->sum(\DB::raw('price * quantity')), 0) }}</div>
                                <div class="stat-label">{{ __('Revenue') }} JD</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="preview-details" id="previewDetails">
                        <div class="preview-item">
                            <span class="preview-label">{{ __('Generation') }}:</span>
                            <span class="preview-value" id="previewGeneration">{{ $dossier->generation->display_name }}</span>
                        </div>
                        
                        <div class="preview-item">
                            <span class="preview-label">{{ __('Subject') }}:</span>
                            <span class="preview-value" id="previewSubject">{{ $dossier->subject->name }}</span>
                        </div>
                        
                        <div class="preview-item">
                            <span class="preview-label">{{ __('Teacher') }}:</span>
                            <span class="preview-value" id="previewTeacher">{{ $dossier->teacher->name }}</span>
                        </div>
                        
                        <div class="preview-item">
                            <span class="preview-label">{{ __('Platform') }}:</span>
                            <span class="preview-value" id="previewPlatform">{{ $dossier->platform->name }}</span>
                        </div>
                        
                        <div class="preview-item">
                            <span class="preview-label">{{ __('Semester') }}:</span>
                            <span class="preview-value" id="previewSemester">{{ $dossier->semester_text }}</span>
                        </div>
                        
                        <div class="preview-item" id="previewPagesRow" style="{{ $dossier->pages_count ? 'display: flex;' : 'display: none;' }}">
                            <span class="preview-label">{{ __('Pages') }}:</span>
                            <span class="preview-value" id="previewPages">{{ $dossier->pages_count }} {{ __('pages') }}</span>
                        </div>
                        
                        <div class="preview-item" id="previewSizeRow" style="{{ $dossier->file_size ? 'display: flex;' : 'display: none;' }}">
                            <span class="preview-label">{{ __('Size') }}:</span>
                            <span class="preview-value" id="previewSize">{{ $dossier->file_size }}</span>
                        </div>
                    </div>
                    
                    <div class="price-calculator" id="priceCalculator">
                        <div class="base-price">
                            {{ __('Base Price') }}: <span id="basePriceDisplay">{{ $dossier->formatted_price }}</span>
                        </div>
                        <div class="platform-fee" id="platformFeeDisplay">
                            {{ __('Platform Fee') }}: +{{ number_format($dossier->final_price - $dossier->price, 2) }} JD ({{ $dossier->platform->price_percentage }}%)
                        </div>
                        <div class="final-price" id="finalPriceDisplay">
                            {{ $dossier->formatted_final_price }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Data storage
let subjects = @json($subjects);
let teachers = @json($teachers);
let platforms = @json($platforms);

// Load subjects based on generation
function loadSubjects() {
    const generationId = document.getElementById('generation_id').value;
    const subjectSelect = document.getElementById('subject_id');
    const teacherSelect = document.getElementById('teacher_id');
    const subjectLoading = document.getElementById('subjectLoading');
    
    // Reset dependent selects
    const currentSubjectId = subjectSelect.value;
    subjectSelect.innerHTML = '<option value="">{{ __('Select Subject') }}</option>';
    teacherSelect.innerHTML = '<option value="">{{ __('Select Teacher') }}</option>';
    teacherSelect.disabled = true;
    
    updatePreview();
    
    if (!generationId) return;
    
    subjectLoading.classList.add('show');
    
    fetch(`/admin/educational-subjects/generation/${generationId}`)
        .then(response => response.json())
        .then(data => {
            subjects = data;
            subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                if (subject.id == currentSubjectId || subject.id == '{{ old('subject_id', $dossier->subject_id) }}') {
                    option.selected = true;
                }
                subjectSelect.appendChild(option);
            });
            
            subjectSelect.disabled = false;
            subjectLoading.classList.remove('show');
            
            if (subjectSelect.value) {
                loadTeachers();
            }
        })
        .catch(error => {
            console.error('Error loading subjects:', error);
            subjectLoading.classList.remove('show');
        });
}

// Load teachers based on generation and subject
function loadTeachers() {
    const generationId = document.getElementById('generation_id').value;
    const subjectId = document.getElementById('subject_id').value;
    const teacherSelect = document.getElementById('teacher_id');
    const teacherLoading = document.getElementById('teacherLoading');
    
    const currentTeacherId = teacherSelect.value;
    teacherSelect.innerHTML = '<option value="">{{ __('Select Teacher') }}</option>';
    teacherSelect.disabled = true;
    
    updatePreview();
    
    if (!generationId || !subjectId) return;
    
    teacherLoading.classList.add('show');
    
    fetch(`/admin/teachers/generation/${generationId}/subject/${subjectId}`)
        .then(response => response.json())
        .then(data => {
            teachers = data;
            teachers.forEach(teacher => {
                const option = document.createElement('option');
                option.value = teacher.id;
                option.textContent = teacher.name;
                if (teacher.id == currentTeacherId || teacher.id == '{{ old('teacher_id', $dossier->teacher_id) }}') {
                    option.selected = true;
                }
                teacherSelect.appendChild(option);
            });
            
            teacherSelect.disabled = false;
            teacherLoading.classList.remove('show');
            updatePreview();
        })
        .catch(error => {
            console.error('Error loading teachers:', error);
            teacherLoading.classList.remove('show');
        });
}

// Update preview
function updatePreview() {
    const name = document.getElementById('name').value || '{{ $dossier->name }}';
    const generationId = document.getElementById('generation_id').value;
    const subjectId = document.getElementById('subject_id').value;
    const teacherId = document.getElementById('teacher_id').value;
    const platformId = document.getElementById('platform_id').value;
    const semester = document.getElementById('semester').value;
    const pagesCount = document.getElementById('pages_count').value;
    const fileSize = document.getElementById('file_size').value;
    
    // Update name
    document.getElementById('previewName').textContent = name;
    
    // Update meta
    const generation = generationId ? document.querySelector(`#generation_id option[value="${generationId}"]`).textContent : '';
    const subject = subjects.find(s => s.id == subjectId)?.name || '';
    const teacher = teachers.find(t => t.id == teacherId)?.name || '';
    
    if (generation && subject && teacher) {
        document.getElementById('previewMeta').textContent = `${generation} • ${subject} • ${teacher}`;
    } else {
        document.getElementById('previewMeta').textContent = '{{ $dossier->generation->display_name }} • {{ $dossier->subject->name }} • {{ $dossier->teacher->name }}';
    }
    
    // Update details
    document.getElementById('previewGeneration').textContent = generation || '{{ $dossier->generation->display_name }}';
    document.getElementById('previewSubject').textContent = subject || '{{ $dossier->subject->name }}';
    document.getElementById('previewTeacher').textContent = teacher || '{{ $dossier->teacher->name }}';
    
    const platform = platformId ? document.querySelector(`#platform_id option[value="${platformId}"]`).textContent : '';
    document.getElementById('previewPlatform').textContent = platform || '{{ $dossier->platform->name }}';
    
    const semesterText = semester ? document.querySelector(`#semester option[value="${semester}"]`).textContent : '';
    document.getElementById('previewSemester').textContent = semesterText || '{{ $dossier->semester_text }}';
    
    // Update pages and size
    const pagesRow = document.getElementById('previewPagesRow');
    const sizeRow = document.getElementById('previewSizeRow');
    
    if (pagesCount) {
        document.getElementById('previewPages').textContent = pagesCount + ' {{ __('pages') }}';
        pagesRow.style.display = 'flex';
    } else {
        pagesRow.style.display = 'none';
    }
    
    if (fileSize) {
        document.getElementById('previewSize').textContent = fileSize;
        sizeRow.style.display = 'flex';
    } else {
        sizeRow.style.display = 'none';
    }
}

// Calculate price
function calculatePrice() {
    const basePrice = parseFloat(document.getElementById('price').value) || 0;
    const platformId = document.getElementById('platform_id').value;
    
    let platformPercentage = 0;
    if (platformId) {
        const platformOption = document.querySelector(`#platform_id option[value="${platformId}"]`);
        platformPercentage = parseFloat(platformOption.dataset.percentage) || 0;
    }
    
    const platformFee = basePrice * (platformPercentage / 100);
    const finalPrice = basePrice + platformFee;
    
    document.getElementById('basePriceDisplay').textContent = basePrice.toFixed(2) + ' JD';
    document.getElementById('platformFeeDisplay').textContent = `{{ __('Platform Fee') }}: +${platformFee.toFixed(2)} JD (${platformPercentage}%)`;
    document.getElementById('finalPriceDisplay').textContent = finalPrice.toFixed(2) + ' JD';
}

// Toggle status
function toggleStatus() {
    const toggle = document.getElementById('statusToggle');
    const input = document.getElementById('statusInput');
    
    if (toggle.classList.contains('active')) {
        toggle.classList.remove('active');
        input.value = '0';
    } else {
        toggle.classList.add('active');
        input.value = '1';
    }
}

// Form validation
document.getElementById('dossierForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const requiredFields = ['name', 'generation_id', 'subject_id', 'teacher_id', 'platform_id', 'semester', 'price'];
    
    let isValid = true;
    let firstInvalidField = null;
    
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            isValid = false;
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('{{ __('Please fill all required fields') }}');
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
        return;
    }
    
    // Prevent double submission
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Updating...') }}';
    
    // Re-enable after 5 seconds as fallback
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> {{ __('Update Dossier') }}';
    }, 5000);
});

// Auto-resize textarea
document.getElementById('description').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 200) + 'px';
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    calculatePrice();
    
    // Load subjects if generation is pre-selected
    if (document.getElementById('generation_id').value) {
        loadSubjects();
    }
});
</script>
@endpush