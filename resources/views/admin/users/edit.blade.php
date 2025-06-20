@extends('layouts.admin')

@section('title', 'تعديل المستخدم')
@section('page-title', 'تعديل المستخدم')

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
        تعديل - {{ $user->name }}
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
    }
    
    .edit-user-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: var(--space-lg);
    }
    
    .form-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: var(--radius-2xl);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        border: 2px solid var(--admin-secondary-100);
        position: relative;
    }
    
    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
    }
    
    .form-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: var(--space-2xl);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .form-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 30px 30px;
        animation: bgMove 20s ease-in-out infinite;
    }
    
    @keyframes bgMove {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(-30px, -30px); }
    }
    
    .form-title {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-md);
        position: relative;
        z-index: 1;
    }
    
    .form-subtitle {
        margin: var(--space-md) 0 0 0;
        opacity: 0.95;
        font-size: 1rem;
        position: relative;
        z-index: 1;
        font-weight: 500;
    }
    
    .form-body {
        padding: var(--space-2xl);
        background: white;
    }
    
    .form-section {
        margin-bottom: var(--space-2xl);
        background: #fafbfc;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .form-section:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        border-bottom: 3px solid var(--admin-secondary-200);
        padding-bottom: var(--space-md);
        position: relative;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -3px;
        right: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #f093fb, #f5576c);
        border-radius: 2px;
    }
    
    .section-title i {
        background: linear-gradient(45deg, #f093fb, #f5576c);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1.5rem;
    }
    
    .change-indicator {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        margin-right: var(--space-sm);
        display: none;
        font-weight: 600;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .form-grid {
        display: grid;
        gap: var(--space-lg);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-lg);
    }
    
    .form-row.single {
        grid-template-columns: 1fr;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        position: relative;
    }
    
    .form-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--admin-secondary-800);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .required {
        color: #e53e3e;
        font-size: 1.2em;
    }
    
    .form-input,
    .form-textarea,
    .form-select {
        padding: var(--space-md) var(--space-lg);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.3s ease;
        background: white;
        position: relative;
    }
    
    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: #f093fb;
        box-shadow: 0 0 0 4px rgba(240, 147, 251, 0.15);
        transform: translateY(-1px);
    }
    
    .form-help {
        font-size: 0.8rem;
        color: var(--admin-secondary-500);
        margin-top: var(--space-xs);
        padding-right: var(--space-xs);
    }
    
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-top: var(--space-sm);
        padding: var(--space-md);
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--admin-secondary-200);
        transition: all 0.3s ease;
    }
    
    .checkbox-group:hover {
        background: #fef7ff;
        border-color: #f093fb;
    }
    
    .checkbox-input {
        width: 20px;
        height: 20px;
        accent-color: #f093fb;
        margin-left: var(--space-sm);
    }
    
    .checkbox-label {
        font-size: 0.95rem;
        color: var(--admin-secondary-700);
        margin: 0;
        font-weight: 500;
    }
    
    .avatar-upload-section {
        background: linear-gradient(135deg, #fef7ff, #f3e8ff);
        border: 2px dashed var(--admin-secondary-300);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .avatar-upload-section:hover {
        border-color: #f093fb;
        background: linear-gradient(135deg, #fdf4ff, #f0e6ff);
    }
    
    .current-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        margin: 0 auto var(--space-md);
        display: block;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .avatar-placeholder {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f093fb, #f5576c);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        font-weight: 700;
        margin: 0 auto var(--space-md);
        border: 4px solid white;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .avatar-preview {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #f093fb;
        margin: 0 auto var(--space-md);
        display: none;
        box-shadow: 0 10px 25px rgba(240, 147, 251, 0.3);
    }
    
    .avatar-actions {
        display: flex;
        gap: var(--space-sm);
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .avatar-upload-btn {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
        border: none;
        padding: var(--space-md) var(--space-xl);
        border-radius: var(--radius-lg);
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(240, 147, 251, 0.3);
    }
    
    .avatar-upload-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
    }
    
    .avatar-remove-btn {
        background: linear-gradient(135deg, #e53e3e, #c53030);
        color: white;
        border: none;
        padding: var(--space-md) var(--space-xl);
        border-radius: var(--radius-lg);
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3);
    }
    
    .avatar-remove-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(229, 62, 62, 0.4);
    }
    
    .password-change-section {
        background: linear-gradient(135deg, #fff7ed, #fef2e2);
        border: 2px solid #fed7aa;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-lg);
    }
    
    .password-change-toggle {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
    }
    
    .password-fields {
        display: none;
        animation: slideDown 0.3s ease-out;
    }
    
    .password-fields.active {
        display: block;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .password-strength {
        margin-top: var(--space-md);
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        background: white;
        border: 2px solid var(--admin-secondary-200);
        display: none;
    }
    
    .strength-bar {
        width: 100%;
        height: 6px;
        background: var(--admin-secondary-200);
        border-radius: var(--radius-sm);
        overflow: hidden;
        margin-bottom: var(--space-sm);
    }
    
    .strength-fill {
        height: 100%;
        transition: all 0.4s ease;
        border-radius: var(--radius-sm);
    }
    
    .strength-weak .strength-fill {
        width: 25%;
        background: linear-gradient(90deg, #e53e3e, #fc8181);
    }
    
    .strength-fair .strength-fill {
        width: 50%;
        background: linear-gradient(90deg, #dd6b20, #f6ad55);
    }
    
    .strength-good .strength-fill {
        width: 75%;
        background: linear-gradient(90deg, #38a169, #68d391);
    }
    
    .strength-strong .strength-fill {
        width: 100%;
        background: linear-gradient(90deg, #00b894, #00cec9);
    }
    
    .strength-text {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
    }
    
    .user-stats {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border: 2px solid #7dd3fc;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-lg);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: var(--space-lg);
    }
    
    .stat-item {
        text-align: center;
        padding: var(--space-lg);
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid #bae6fd;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-item::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #0ea5e9, #0284c7);
    }
    
    .stat-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(14, 165, 233, 0.15);
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0c4a6e;
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #075985;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .form-actions {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: var(--space-2xl);
        border-top: 2px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-md);
    }
    
    .btn-group {
        display: flex;
        gap: var(--space-md);
    }
    
    .btn {
        padding: var(--space-md) var(--space-xl);
        border: none;
        border-radius: var(--radius-lg);
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        position: relative;
        overflow: hidden;
    }
    
    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        right: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: right 0.5s;
    }
    
    .btn:hover::before {
        right: 100%;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
        box-shadow: 0 4px 15px rgba(240, 147, 251, 0.3);
    }
    
    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #a0aec0, #718096);
        color: white;
        box-shadow: 0 4px 15px rgba(113, 128, 150, 0.3);
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(113, 128, 150, 0.4);
    }
    
    .error-message {
        color: #e53e3e;
        font-size: 0.8rem;
        margin-top: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        padding: var(--space-xs) var(--space-sm);
        background: rgba(229, 62, 62, 0.1);
        border-radius: var(--radius-md);
        border-right: 3px solid #e53e3e;
    }
    
    .form-input.error,
    .form-textarea.error,
    .form-select.error {
        border-color: #e53e3e;
        box-shadow: 0 0 0 4px rgba(229, 62, 62, 0.1);
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }
    
    .fade-in {
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (max-width: 768px) {
        .edit-user-container {
            margin: 0;
            padding: var(--space-md);
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
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .avatar-actions {
            flex-direction: column;
        }
        
        .form-title {
            font-size: 1.5rem;
        }
        
        .avatar-placeholder,
        .avatar-preview,
        .current-avatar {
            width: 100px;
            height: 100px;
            font-size: 2rem;
        }
    }
    
    /* تحسينات إضافية للتفاعل */
    .form-group:hover .form-label {
        color: #f093fb;
        transform: translateX(-2px);
    }
    
    .section-title:hover i {
        transform: scale(1.1);
    }
    
    /* تأثيرات الإنتقال الناعم */
    * {
        transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
    }
</style>
@endpush

@section('content')
<div class="edit-user-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-user-edit"></i>
                تعديل المستخدم
            </h1>
            <p class="form-subtitle">تحديث معلومات وإعدادات المستخدم</p>
        </div>
        
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" id="userForm">
            @csrf
            @method('PUT')
            
            <div class="form-body">
                <!-- إحصائيات المستخدم -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-chart-bar"></i>
                        إحصائيات المستخدم
                    </h2>
                    
                    <div class="user-stats">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->orders->count() ?? 0 }}</div>
                                <div class="stat-label">إجمالي الطلبات</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value">${{ number_format($user->orders->sum('total') ?? 0, 2) }}</div>
                                <div class="stat-label">إجمالي المبلغ المدفوع</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->created_at->format('d/m/Y') }}</div>
                                <div class="stat-label">عضو منذ</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value">
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        أبداً
                                    @endif
                                </div>
                                <div class="stat-label">آخر تسجيل دخول</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- قسم الصورة الشخصية -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-camera"></i>
                        الصورة الشخصية
                        <span class="change-indicator" id="avatarChangeIndicator">تم التعديل</span>
                    </h2>
                    
                    <div class="avatar-upload-section">
                        @if($user->avatar)
                            <img id="currentAvatar" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="current-avatar">
                        @else
                            <div id="currentAvatar" class="avatar-placeholder">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                        
                        <img id="avatarPreview" class="avatar-preview" alt="معاينة الصورة">
                        
                        <div class="avatar-actions">
                            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" onchange="previewAvatar(this)">
                            <button type="button" class="avatar-upload-btn" onclick="document.getElementById('avatar').click()">
                                <i class="fas fa-upload"></i>
                                تغيير الصورة
                            </button>
                            
                            @if($user->avatar)
                                <button type="button" class="avatar-remove-btn" onclick="removeAvatar()">
                                    <i class="fas fa-trash"></i>
                                    حذف الصورة
                                </button>
                                <input type="hidden" id="removeAvatar" name="remove_avatar" value="0">
                            @endif
                        </div>
                        
                        @error('avatar')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <div class="form-help">رفع صورة شخصية جديدة (اختياري). الحد الأقصى: 2 ميجابايت</div>
                    </div>
                </div>
                
                <!-- المعلومات الأساسية -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        المعلومات الأساسية
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    الاسم الكامل
                                    <span class="required">*</span>
                                    <span class="change-indicator" id="nameChangeIndicator">تم التعديل</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    class="form-input @error('name') error @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    placeholder="أدخل الاسم الكامل"
                                    required
                                    oninput="trackChanges('name')"
                                    data-original="{{ $user->name }}"
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
                                    <span class="change-indicator" id="emailChangeIndicator">تم التعديل</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="form-input @error('email') error @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="أدخل البريد الإلكتروني"
                                    required
                                    oninput="trackChanges('email')"
                                    data-original="{{ $user->email }}"
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
                                    <span class="change-indicator" id="phoneChangeIndicator">تم التعديل</span>
                                </label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    class="form-input @error('phone') error @enderror"
                                    value="{{ old('phone', $user->phone) }}"
                                    placeholder="أدخل رقم الهاتف"
                                    oninput="trackChanges('phone')"
                                    data-original="{{ $user->phone }}"
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
                                    <span class="change-indicator" id="roleChangeIndicator">تم التعديل</span>
                                </label>
                                <select 
                                    id="role" 
                                    name="role" 
                                    class="form-select @error('role') error @enderror"
                                    required
                                    onchange="trackChanges('role')"
                                    data-original="{{ $user->role ?? 'user' }}"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                >
                                    <option value="user" {{ old('role', $user->role ?? 'user') == 'user' ? 'selected' : '' }}>مستخدم</option>
                                    <option value="admin" {{ old('role', $user->role ?? 'user') == 'admin' ? 'selected' : '' }}>مدير</option>
                                </select>
                                @error('role')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if($user->id === auth()->id())
                                    <div class="form-help">لا يمكنك تغيير نوع حسابك الخاص</div>
                                @else
                                    <div class="form-help">اختر نوع المستخدم والصلاحيات</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- قسم كلمة المرور -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-lock"></i>
                        كلمة المرور
                    </h2>
                    
                    <div class="password-change-section">
                        <div class="password-change-toggle">
                            <input 
                                type="checkbox" 
                                id="changePassword" 
                                class="checkbox-input"
                                onchange="togglePasswordFields(this)"
                            >
                            <label for="changePassword" class="checkbox-label">
                                تغيير كلمة المرور
                            </label>
                        </div>
                        
                        <div id="passwordFields" class="password-fields">
                            <div class="form-grid">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="password" class="form-label">
                                            كلمة المرور الجديدة
                                        </label>
                                        <input 
                                            type="password" 
                                            id="password" 
                                            name="password" 
                                            class="form-input @error('password') error @enderror"
                                            placeholder="أدخل كلمة المرور الجديدة"
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
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">
                                            تأكيد كلمة المرور الجديدة
                                        </label>
                                        <input 
                                            type="password" 
                                            id="password_confirmation" 
                                            name="password_confirmation" 
                                            class="form-input"
                                            placeholder="أعد إدخال كلمة المرور الجديدة"
                                        >
                                        <div class="form-help">أعد إدخال كلمة المرور الجديدة للتأكيد</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- إعدادات الحساب -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-cog"></i>
                        إعدادات الحساب
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-row single">
                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input 
                                        type="checkbox" 
                                        id="email_verified" 
                                        name="email_verified" 
                                        class="checkbox-input"
                                        value="1"
                                        {{ old('email_verified', $user->email_verified_at ? true : false) ? 'checked' : '' }}
                                        onchange="trackChanges('email_verified')"
                                        data-original="{{ $user->email_verified_at ? '1' : '0' }}"
                                    >
                                    <label for="email_verified" class="checkbox-label">
                                        تم التحقق من البريد الإلكتروني
                                    </label>
                                    <span class="change-indicator" id="email_verifiedChangeIndicator">تم التعديل</span>
                                </div>
                                <div class="form-help">
                                    @if($user->email_verified_at)
                                        تم التحقق من البريد الإلكتروني في {{ $user->email_verified_at->format('d/m/Y \f\i g:i A') }}
                                    @else
                                        لم يتم التحقق من البريد الإلكتروني للمستخدم بعد
                                    @endif
                                </div>
                            </div>
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
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i>
                        عرض المستخدم
                    </a>
                    
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        إعادة تعيين
                    </button>
                    
                    <button type="submit" class="btn btn-warning" id="updateBtn">
                        <i class="fas fa-save"></i>
                        تحديث المستخدم
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let hasChanges = false;
    const originalValues = {};
    
    // حفظ القيم الأصلية عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-original]').forEach(field => {
            originalValues[field.id] = field.dataset.original;
        });
    });
    
    // تتبع التغييرات في حقول النموذج
    function trackChanges(fieldName) {
        const field = document.getElementById(fieldName);
        const indicator = document.getElementById(fieldName + 'ChangeIndicator');
        let currentValue;
        
        if (field.type === 'checkbox') {
            currentValue = field.checked ? '1' : '0';
        } else {
            currentValue = field.value;
        }
        
        const originalValue = originalValues[fieldName] || '';
        
        if (currentValue !== originalValue) {
            if (indicator) indicator.style.display = 'inline-block';
            hasChanges = true;
        } else {
            if (indicator) indicator.style.display = 'none';
        }
        
        updateFormState();
    }
    
    // تحديث حالة النموذج بناءً على التغييرات
    function updateFormState() {
        const updateBtn = document.getElementById('updateBtn');
        hasChanges = document.querySelector('.change-indicator[style*="inline-block"]') !== null;
        
        if (hasChanges) {
            updateBtn.classList.remove('btn-secondary');
            updateBtn.classList.add('btn-warning');
        } else {
            updateBtn.classList.remove('btn-warning');
            updateBtn.classList.add('btn-secondary');
        }
    }
    
    // معاينة الصورة الشخصية
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const currentAvatar = document.getElementById('currentAvatar');
                const preview = document.getElementById('avatarPreview');
                
                currentAvatar.style.display = 'none';
                preview.src = e.target.result;
                preview.style.display = 'block';
                
                document.getElementById('avatarChangeIndicator').style.display = 'inline-block';
                hasChanges = true;
                updateFormState();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // حذف الصورة الشخصية
    function removeAvatar() {
        if (confirm('هل أنت متأكد من حذف الصورة الشخصية؟')) {
            const currentAvatar = document.getElementById('currentAvatar');
            const preview = document.getElementById('avatarPreview');
            const removeInput = document.getElementById('removeAvatar');
            
            if (removeInput) {
                removeInput.value = '1';
            }
            
            currentAvatar.style.display = 'none';
            preview.style.display = 'none';
            
            // إظهار العنصر البديل
            const placeholder = document.createElement('div');
            placeholder.className = 'avatar-placeholder';
            placeholder.id = 'currentAvatar';
            placeholder.innerHTML = '{{ strtoupper(substr($user->name, 0, 2)) }}';
            currentAvatar.parentNode.insertBefore(placeholder, currentAvatar);
            
            document.getElementById('avatarChangeIndicator').style.display = 'inline-block';
            hasChanges = true;
            updateFormState();
        }
    }
    
    // تبديل حقول كلمة المرور
    function togglePasswordFields(checkbox) {
        const passwordFields = document.getElementById('passwordFields');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        
        if (checkbox.checked) {
            passwordFields.classList.add('active');
            passwordInput.required = true;
            confirmInput.required = true;
        } else {
            passwordFields.classList.remove('active');
            passwordInput.required = false;
            passwordInput.value = '';
            confirmInput.required = false;
            confirmInput.value = '';
            document.getElementById('passwordStrength').style.display = 'none';
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
        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        
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
        if (confirm('هل أنت متأكد من إعادة تعيين النموذج؟ سيتم فقدان جميع التغييرات.')) {
            location.reload();
        }
    }
    
    // التحقق من صحة النموذج
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const changePasswordCheckbox = document.getElementById('changePassword');
        
        if (changePasswordCheckbox.checked) {
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
        }
        
        // إظهار حالة التحميل
        const submitBtn = document.getElementById('updateBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
        submitBtn.disabled = true;
        
        // إعادة تفعيل الزر بعد 10 ثوان في حالة الخطأ
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
    
    // تحذير المستخدم من التغييرات غير المحفوظة
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = 'لديك تغييرات غير محفوظة. هل أنت متأكد من المغادرة؟';
            return e.returnValue;
        }
    });
    
    // تفعيل الحركات
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    });
</script>
@endpush