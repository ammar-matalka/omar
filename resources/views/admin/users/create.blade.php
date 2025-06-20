@extends('layouts.admin')

@section('title', 'إنشاء مستخدم جديد')
@section('page-title', 'إنشاء مستخدم جديد')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        <a href="{{ route('admin.users.index') }}" class="breadcrumb-link">المستخدمون</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        إنشاء جديد
    </div>
@endsection

@push('styles')
<style>
    * {
        direction: rtl;
        text-align: right;
    }
    
    body {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8fafc;
    }
    
    .create-user-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .form-header {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    
    .form-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }
    
    .form-subtitle {
        margin: 0.75rem 0 0 0;
        opacity: 0.9;
        font-size: 1rem;
    }
    
    .form-body {
        padding: 2rem;
    }
    
    .form-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.75rem;
    }
    
    .section-title i {
        color: #4f46e5;
        font-size: 1.25rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-row.single {
        grid-template-columns: 1fr;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .required {
        color: #dc2626;
    }
    
    .form-input,
    .form-select {
        padding: 0.75rem 1rem;
        border: 2px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        font-family: inherit;
        transition: all 0.2s ease;
        background: white;
    }
    
    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .form-select {
        cursor: pointer;
        appearance: auto;
    }
    
    .form-help {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        margin-top: 0.5rem;
    }
    
    .checkbox-input {
        width: 18px;
        height: 18px;
        accent-color: #4f46e5;
    }
    
    .checkbox-label {
        font-size: 0.875rem;
        color: #374151;
        margin: 0;
        font-weight: 500;
    }
    
    .avatar-upload-section {
        background: #f9fafb;
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.2s ease;
    }
    
    .avatar-upload-section:hover {
        border-color: #4f46e5;
        background: #f0f4ff;
    }
    
    .avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 auto 1rem;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        margin: 0 auto 1rem;
        display: block;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .avatar-upload-btn {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(79, 70, 229, 0.3);
    }
    
    .avatar-upload-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(79, 70, 229, 0.4);
    }
    
    .password-strength {
        margin-top: 1rem;
        padding: 1rem;
        border-radius: 8px;
        background: white;
        border: 1px solid #d1d5db;
        display: none;
    }
    
    .strength-bar {
        width: 100%;
        height: 4px;
        background: #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }
    
    .strength-fill {
        height: 100%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }
    
    .strength-weak .strength-fill {
        width: 25%;
        background: #dc2626;
    }
    
    .strength-fair .strength-fill {
        width: 50%;
        background: #f59e0b;
    }
    
    .strength-good .strength-fill {
        width: 75%;
        background: #10b981;
    }
    
    .strength-strong .strength-fill {
        width: 100%;
        background: #059669;
    }
    
    .strength-text {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
    }
    
    .strength-requirements {
        margin-top: 0.75rem;
        font-size: 0.75rem;
        color: #6b7280;
    }
    
    .requirement {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }
    
    .requirement-icon {
        width: 16px;
        color: #9ca3af;
    }
    
    .requirement.met .requirement-icon {
        color: #10b981;
    }
    
    .form-actions {
        background: #f8fafc;
        padding: 2rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    
    .btn-group {
        display: flex;
        gap: 1rem;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        box-shadow: 0 2px 4px rgba(79, 70, 229, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(79, 70, 229, 0.4);
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
        box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);
    }
    
    .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(107, 114, 128, 0.4);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    }
    
    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.4);
    }
    
    .error-message {
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.5rem;
        background: rgba(220, 38, 38, 0.1);
        border-radius: 4px;
        border-right: 3px solid #dc2626;
    }
    
    .form-input.error,
    .form-select.error {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }
    
    @media (max-width: 768px) {
        .create-user-container {
            padding: 1rem;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .form-title {
            font-size: 1.5rem;
        }
        
        .avatar-placeholder,
        .avatar-preview {
            width: 100px;
            height: 100px;
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="create-user-container">
    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-user-plus"></i>
                إنشاء مستخدم جديد
            </h1>
            <p class="form-subtitle">إضافة مستخدم جديد إلى النظام</p>
        </div>
        
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" id="userForm">
            @csrf
            
            <div class="form-body">
                <!-- قسم الصورة الشخصية -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-camera"></i>
                        الصورة الشخصية
                    </h2>
                    
                    <div class="avatar-upload-section">
                        <div id="avatarPreview" class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                        
                        <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" onchange="previewAvatar(this)">
                        <button type="button" class="avatar-upload-btn" onclick="document.getElementById('avatar').click()">
                            <i class="fas fa-upload"></i>
                            اختيار صورة
                        </button>
                        
                        @error('avatar')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <div class="form-help">رفع صورة شخصية (اختياري). الحد الأقصى: 2 ميجابايت</div>
                    </div>
                </div>
                
                <!-- المعلومات الأساسية -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        المعلومات الأساسية
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                الاسم الكامل
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-input @error('name') error @enderror"
                                value="{{ old('name') }}"
                                placeholder="أدخل الاسم الكامل"
                                required
                            >
                            @error('name')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">
                                البريد الإلكتروني
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input @error('email') error @enderror"
                                value="{{ old('email') }}"
                                placeholder="أدخل البريد الإلكتروني"
                                required
                            >
                            @error('email')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone" class="form-label">
                                رقم الهاتف
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                class="form-input @error('phone') error @enderror"
                                value="{{ old('phone') }}"
                                placeholder="أدخل رقم الهاتف"
                            >
                            @error('phone')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="role" class="form-label">
                                نوع المستخدم
                                <span class="required">*</span>
                            </label>
                            <select 
                                id="role" 
                                name="role" 
                                class="form-select @error('role') error @enderror"
                                required
                            >
                                <option value="" disabled selected>اختر نوع المستخدم</option>
                                <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>مستخدم عادي</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>مدير</option>
                            </select>
                            @error('role')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-help">اختر نوع المستخدم والصلاحيات المطلوبة</div>
                        </div>
                    </div>
                </div>
                
                <!-- قسم كلمة المرور -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-lock"></i>
                        كلمة المرور
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="form-label">
                                كلمة المرور
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input @error('password') error @enderror"
                                placeholder="أدخل كلمة المرور"
                                required
                                oninput="checkPasswordStrength(this.value)"
                            >
                            @error('password')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            
                            <div id="passwordStrength" class="password-strength">
                                <div class="strength-bar">
                                    <div class="strength-fill"></div>
                                </div>
                                <div class="strength-text"></div>
                                <div class="strength-requirements">
                                    <div class="requirement" id="req-length">
                                        <i class="fas fa-times requirement-icon"></i>
                                        8 أحرف على الأقل
                                    </div>
                                    <div class="requirement" id="req-uppercase">
                                        <i class="fas fa-times requirement-icon"></i>
                                        حرف كبير واحد
                                    </div>
                                    <div class="requirement" id="req-lowercase">
                                        <i class="fas fa-times requirement-icon"></i>
                                        حرف صغير واحد
                                    </div>
                                    <div class="requirement" id="req-number">
                                        <i class="fas fa-times requirement-icon"></i>
                                        رقم واحد
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                تأكيد كلمة المرور
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="form-input"
                                placeholder="أعد إدخال كلمة المرور"
                                required
                            >
                            <div class="form-help">أعد إدخال كلمة المرور للتأكيد</div>
                        </div>
                    </div>
                </div>
                
                <!-- الإعدادات -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-cog"></i>
                        إعدادات الحساب
                    </h2>
                    
                    <div class="form-row single">
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input 
                                    type="checkbox" 
                                    id="email_verified" 
                                    name="email_verified" 
                                    class="checkbox-input"
                                    value="1"
                                    {{ old('email_verified') ? 'checked' : '' }}
                                >
                                <label for="email_verified" class="checkbox-label">
                                    تم التحقق من البريد الإلكتروني
                                </label>
                            </div>
                            <div class="form-help">تخطي عملية التحقق من البريد الإلكتروني لهذا المستخدم</div>
                        </div>
                    </div>
                    
                    <div class="form-row single">
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input 
                                    type="checkbox" 
                                    id="send_welcome_email" 
                                    name="send_welcome_email" 
                                    class="checkbox-input"
                                    value="1"
                                    {{ old('send_welcome_email', true) ? 'checked' : '' }}
                                >
                                <label for="send_welcome_email" class="checkbox-label">
                                    إرسال رسالة ترحيب
                                </label>
                            </div>
                            <div class="form-help">إرسال إشعار إنشاء الحساب إلى المستخدم</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للمستخدمين
                    </a>
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        إعادة تعيين
                    </button>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-plus"></i>
                        إنشاء المستخدم
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // معاينة الصورة الشخصية
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                preview.innerHTML = `<img src="${e.target.result}" class="avatar-preview" alt="معاينة الصورة">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // فحص قوة كلمة المرور
    function checkPasswordStrength(password) {
        const strengthDiv = document.getElementById('passwordStrength');
        const strengthBar = strengthDiv.querySelector('.strength-fill');
        const strengthText = strengthDiv.querySelector('.strength-text');
        
        if (password.length === 0) {
            strengthDiv.style.display = 'none';
            return;
        }
        
        strengthDiv.style.display = 'block';
        
        let score = 0;
        let strength = '';
        
        // فحص المتطلبات
        const requirements = {
            'req-length': password.length >= 8,
            'req-uppercase': /[A-Z]/.test(password),
            'req-lowercase': /[a-z]/.test(password),
            'req-number': /[0-9]/.test(password)
        };
        
        // تحديث مؤشرات المتطلبات
        Object.keys(requirements).forEach(reqId => {
            const req = document.getElementById(reqId);
            const icon = req.querySelector('.requirement-icon');
            
            if (requirements[reqId]) {
                req.classList.add('met');
                icon.className = 'fas fa-check requirement-icon';
                score++;
            } else {
                req.classList.remove('met');
                icon.className = 'fas fa-times requirement-icon';
            }
        });
        
        // تحديد قوة كلمة المرور
        strengthDiv.className = 'password-strength';
        
        if (score === 0) {
            strength = 'ضعيف جداً';
            strengthDiv.classList.add('strength-weak');
        } else if (score === 1) {
            strength = 'ضعيف';
            strengthDiv.classList.add('strength-weak');
        } else if (score === 2) {
            strength = 'متوسط';
            strengthDiv.classList.add('strength-fair');
        } else if (score === 3) {
            strength = 'جيد';
            strengthDiv.classList.add('strength-good');
        } else if (score === 4) {
            strength = 'قوي';
            strengthDiv.classList.add('strength-strong');
        }
        
        strengthText.textContent = strength;
    }
    
    // إعادة تعيين النموذج
    function resetForm() {
        if (confirm('هل أنت متأكد من إعادة تعيين النموذج؟ سيتم فقدان جميع البيانات المدخلة.')) {
            document.getElementById('userForm').reset();
            document.getElementById('avatarPreview').innerHTML = '<i class="fas fa-user"></i>';
            document.getElementById('passwordStrength').style.display = 'none';
        }
    }
    
    // التحقق من صحة النموذج
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('كلمات المرور غير متطابقة');
            document.getElementById('password_confirmation').focus();
            return;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('يجب أن تكون كلمة المرور 8 أحرف على الأقل');
            document.getElementById('password').focus();
            return;
        }
        
        // إظهار حالة التحميل
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإنشاء...';
        submitBtn.disabled = true;
        
        // إعادة تفعيل الزر بعد 10 ثوان في حالة الخطأ
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
</script>
@endpush