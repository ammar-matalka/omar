@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')
@section('page-title', 'إدارة المستخدمين')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        المستخدمون
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
    
    .users-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        flex-wrap: wrap;
        gap: var(--space-md);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: var(--space-xl);
        border-radius: var(--radius-2xl);
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .users-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        animation: backgroundMove 15s linear infinite;
    }
    
    @keyframes backgroundMove {
        0% { transform: translate(0, 0); }
        100% { transform: translate(-20px, -20px); }
    }
    
    .users-title {
        font-size: 2rem;
        font-weight: 800;
        color: white;
        display: flex;
        align-items: center;
        gap: var(--space-md);
        position: relative;
        z-index: 1;
    }
    
    .users-stats {
        display: flex;
        gap: var(--space-lg);
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: var(--space-md) var(--space-lg);
        background: rgba(255, 255, 255, 0.15);
        border-radius: var(--radius-xl);
        border: 1px solid rgba(255, 255, 255, 0.2);
        min-width: 120px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .stat-item:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    
    .stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: white;
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.9);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .btn-add-user {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: var(--space-md) var(--space-xl);
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        position: relative;
        z-index: 1;
    }
    
    .btn-add-user:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, #059669, #047857);
    }
    
    .filters-section {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: var(--radius-2xl);
        padding: var(--space-2xl);
        margin-bottom: var(--space-xl);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 2px solid var(--admin-secondary-100);
        position: relative;
        overflow: hidden;
    }
    
    .filters-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #4f46e5, #7c3aed, #ec4899);
    }
    
    .filters-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-xl);
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .filters-title i {
        background: linear-gradient(45deg, #4f46e5, #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1.8rem;
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-xl);
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .filter-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--admin-secondary-800);
    }
    
    .filter-input {
        padding: var(--space-md) var(--space-lg);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-lg);
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        transform: translateY(-1px);
    }
    
    .filter-actions {
        display: flex;
        gap: var(--space-md);
        justify-content: flex-end;
        flex-wrap: wrap;
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
    
    .btn-primary {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
    }
    
    .users-table-container {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: var(--radius-2xl);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        border: 2px solid var(--admin-secondary-100);
        position: relative;
    }
    
    .table-header {
        background: linear-gradient(135deg, #1e293b, #334155);
        color: white;
        padding: var(--space-xl);
        position: relative;
        overflow: hidden;
    }
    
    .table-header::before {
        content: '';
        position: absolute;
        top: -100%;
        right: -100%;
        width: 300%;
        height: 300%;
        background: conic-gradient(from 0deg, transparent, rgba(255,255,255,0.1), transparent);
        animation: rotate 10s linear infinite;
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .table-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-md);
        position: relative;
        z-index: 1;
    }
    
    .users-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }
    
    .users-table th {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        padding: var(--space-lg);
        text-align: right;
        font-weight: 700;
        color: var(--admin-secondary-800);
        border-bottom: 2px solid var(--admin-secondary-200);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .users-table td {
        padding: var(--space-lg);
        border-bottom: 1px solid var(--admin-secondary-100);
        color: var(--admin-secondary-700);
        vertical-align: middle;
        transition: all 0.3s ease;
    }
    
    .users-table tbody tr {
        transition: all 0.3s ease;
    }
    
    .users-table tbody tr:hover {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        transform: scale(1.01);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .user-avatar:hover {
        border-color: #4f46e5;
        transform: scale(1.1);
    }
    
    .user-avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }
    
    .user-avatar-placeholder:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .user-details {
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
    }
    
    .user-name {
        font-weight: 700;
        color: var(--admin-secondary-900);
        font-size: 1rem;
    }
    
    .user-email {
        font-size: 0.9rem;
        color: var(--admin-secondary-600);
    }
    
    .user-role {
        display: inline-block;
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-md);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .role-admin {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
    }
    
    .role-user {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: white;
        box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3);
    }
    
    .status-badge {
        display: inline-block;
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-md);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-active {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
    }
    
    .status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }
    
    .user-actions {
        display: flex;
        gap: var(--space-xs);
        align-items: center;
        justify-content: center;
    }
    
    .action-btn {
        padding: var(--space-sm);
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        position: relative;
        overflow: hidden;
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transition: all 0.3s ease;
        transform: translate(-50%, -50%);
    }
    
    .action-btn:hover::before {
        width: 100px;
        height: 100px;
    }
    
    .action-btn.view {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: white;
        box-shadow: 0 3px 10px rgba(14, 165, 233, 0.3);
    }
    
    .action-btn.view:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 5px 15px rgba(14, 165, 233, 0.4);
    }
    
    .action-btn.edit {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
    }
    
    .action-btn.edit:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 5px 15px rgba(245, 158, 11, 0.4);
    }
    
    .action-btn.delete {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        box-shadow: 0 3px 10px rgba(220, 38, 38, 0.3);
    }
    
    .action-btn.delete:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }
    
    .empty-icon {
        font-size: 5rem;
        margin-bottom: var(--space-xl);
        background: linear-gradient(135deg, #6b7280, #4b5563);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .empty-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: var(--space-md);
        color: var(--admin-secondary-800);
    }
    
    .empty-text {
        margin-bottom: var(--space-2xl);
        font-size: 1.2rem;
        color: var(--admin-secondary-600);
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: var(--space-xl);
    }
    
    .join-date {
        font-size: 0.9rem;
        color: var(--admin-secondary-700);
        font-weight: 500;
    }
    
    .join-date small {
        color: var(--admin-secondary-500);
        font-size: 0.8rem;
    }
    
    .last-login {
        font-size: 0.9rem;
        color: var(--admin-secondary-700);
        font-weight: 500;
    }
    
    .last-login small {
        color: var(--admin-secondary-500);
        font-size: 0.8rem;
    }
    
    .text-muted {
        color: var(--admin-secondary-400) !important;
        font-style: italic;
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
    
    /* نمط النافذة المنبثقة */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }
    
    .modal-content {
        background: white;
        padding: var(--space-2xl);
        border-radius: var(--radius-2xl);
        max-width: 450px;
        width: 90%;
        text-align: center;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        transform: scale(0.7);
        transition: transform 0.3s ease;
    }
    
    .modal.show .modal-content {
        transform: scale(1);
    }
    
    .modal-icon {
        font-size: 4rem;
        margin-bottom: var(--space-lg);
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-md);
        color: var(--admin-secondary-900);
    }
    
    .modal-text {
        margin-bottom: var(--space-xl);
        color: var(--admin-secondary-600);
        font-size: 1.1rem;
        line-height: 1.6;
    }
    
    .modal-actions {
        display: flex;
        gap: var(--space-md);
        justify-content: center;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
    }
    
    @media (max-width: 768px) {
        .users-header {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        
        .users-stats {
            width: 100%;
            justify-content: center;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .users-table-container {
            overflow-x: auto;
        }
        
        .users-table {
            min-width: 700px;
        }
        
        .user-info {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-sm);
        }
        
        .filter-actions {
            justify-content: center;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .users-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- رأس صفحة المستخدمين -->
<div class="users-header fade-in">
    <div>
        <h1 class="users-title">
            <i class="fas fa-users"></i>
            إدارة المستخدمين
        </h1>
    </div>
    
    <div class="users-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $users->total() }}</div>
            <div class="stat-label">إجمالي المستخدمين</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $users->where('role', 'admin')->count() }}</div>
            <div class="stat-label">المديرون</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $users->where('email_verified_at', '!=', null)->count() }}</div>
            <div class="stat-label">المتحققون</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</div>
            <div class="stat-label">جدد (30 يوم)</div>
        </div>
        
        <a href="{{ route('admin.users.create') }}" class="btn-add-user">
            <i class="fas fa-plus"></i>
            إضافة مستخدم
        </a>
    </div>
</div>

<!-- قسم الفلاتر -->
<div class="filters-section fade-in">
    <h2 class="filters-title">
        <i class="fas fa-filter"></i>
        تصفية المستخدمين
    </h2>
    
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">البحث</label>
                <input 
                    type="text" 
                    name="search" 
                    class="filter-input" 
                    placeholder="البحث بالاسم أو البريد الإلكتروني..."
                    value="{{ request('search') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">النوع</label>
                <select name="role" class="filter-input">
                    <option value="">جميع الأنواع</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مدير</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>مستخدم</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">الحالة</label>
                <select name="status" class="filter-input">
                    <option value="">جميع الحالات</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>متحقق</option>
                    <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>غير متحقق</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">تاريخ التسجيل</label>
                <select name="date_filter" class="filter-input">
                    <option value="">جميع الأوقات</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>اليوم</option>
                    <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                    <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>هذا الشهر</option>
                    <option value="year" {{ request('date_filter') == 'year' ? 'selected' : '' }}>هذا العام</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">ترتيب حسب</label>
                <select name="sort" class="filter-input">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                    <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>البريد الإلكتروني</option>
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>تاريخ التسجيل</option>
                    <option value="last_login" {{ request('sort') == 'last_login' ? 'selected' : '' }}>آخر تسجيل دخول</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">الترتيب</label>
                <select name="order" class="filter-input">
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                </select>
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                تصفية
            </button>
            
            @if(request()->hasAny(['search', 'role', 'status', 'date_filter', 'sort', 'order']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    مسح الفلاتر
                </a>
            @endif
        </div>
    </form>
</div>

<!-- جدول المستخدمين -->
@if($users->count() > 0)
    <div class="users-table-container fade-in">
        <div class="table-header">
            <h2 class="table-title">
                <i class="fas fa-table"></i>
                قائمة المستخدمين
            </h2>
        </div>
        
        <table class="users-table">
            <thead>
                <tr>
                    <th>المستخدم</th>
                    <th>النوع</th>
                    <th>الحالة</th>
                    <th>تاريخ الانضمام</th>
                    <th>آخر تسجيل دخول</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="user-info">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="user-avatar">
                                @else
                                    <div class="user-avatar-placeholder">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                                
                                <div class="user-details">
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <span class="user-role role-{{ $user->role ?? 'user' }}">
                                {{ $user->role == 'admin' ? 'مدير' : 'مستخدم' }}
                            </span>
                        </td>
                        
                        <td>
                            @if($user->email_verified_at)
                                <span class="status-badge status-active">متحقق</span>
                            @else
                                <span class="status-badge status-pending">غير متحقق</span>
                            @endif
                        </td>
                        
                        <td>
                            <div class="join-date">
                                {{ $user->created_at->format('d/m/Y') }}<br>
                                <small>{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </td>
                        
                        <td>
                            <div class="last-login">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('d/m/Y') }}<br>
                                    <small>{{ $user->last_login_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">أبداً</span>
                                @endif
                            </div>
                        </td>
                        
                        <td>
                            <div class="user-actions">
                                <a href="{{ route('admin.users.show', $user) }}" class="action-btn view" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.users.edit', $user) }}" class="action-btn edit" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($user->id !== auth()->id())
                                    <button class="action-btn delete" onclick="deleteUser('{{ $user->id }}')" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- الترقيم -->
    @if($users->hasPages())
        <div class="pagination-wrapper">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @endif
@else
    <div class="users-table-container fade-in">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="empty-title">لا توجد مستخدمون</h3>
            <p class="empty-text">
                @if(request()->hasAny(['search', 'role', 'status', 'date_filter']))
                    لا يوجد مستخدمون يطابقون معايير البحث الخاصة بك.
                @else
                    ابدأ بإضافة أول مستخدم إلى النظام.
                @endif
            </p>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                إضافة أول مستخدم
            </a>
        </div>
    </div>
@endif

<!-- نافذة تأكيد الحذف -->
<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="modal-title">حذف المستخدم</h3>
        <p class="modal-text">
            هل أنت متأكد من حذف هذا المستخدم؟ هذا الإجراء لا يمكن التراجع عنه.
        </p>
        <div class="modal-actions">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">إلغاء</button>
            <button onclick="confirmDelete()" class="btn btn-danger">حذف</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let userToDelete = null;
    
    // دوال حذف المستخدم
    function deleteUser(userId) {
        userToDelete = userId;
        const modal = document.getElementById('deleteModal');
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
            userToDelete = null;
        }, 300);
    }
    
    function confirmDelete() {
        if (userToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userToDelete}`;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // إغلاق النافذة المنبثقة عند النقر خارجها
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
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
    
    // تأثيرات تفاعلية إضافية
    document.querySelectorAll('.stat-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.05)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
</script>
@endpush