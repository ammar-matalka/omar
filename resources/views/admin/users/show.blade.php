@extends('layouts.admin')

@section('title', 'تفاصيل المستخدم')
@section('page-title', 'تفاصيل المستخدم')

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
        {{ $user->name }}
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
    
    .user-show-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .user-header {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .user-hero {
        display: grid;
        grid-template-columns: 120px 1fr auto;
        gap: var(--space-lg);
        padding: var(--space-xl);
        align-items: center;
    }
    
    .user-avatar-section {
        text-align: center;
    }
    
    .user-profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--admin-secondary-200);
        margin-bottom: var(--space-sm);
        box-shadow: var(--shadow-md);
    }
    
    .user-profile-avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--admin-primary-100);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-primary-600);
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 auto var(--space-sm);
        border: 3px solid var(--admin-secondary-200);
        box-shadow: var(--shadow-md);
    }
    
    /* تنظيم الهيدر مثل صفحات Categories والProducts */
    .main-header,
    .admin-header,
    .top-navigation {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 1rem 2rem !important;
        background: white !important;
        border-bottom: 1px solid #e5e7eb !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 100 !important;
    }
    
    /* قسم معلومات الأدمن على اليسار */
    .admin-section {
        display: flex !important;
        align-items: center !important;
        gap: 1rem !important;
    }
    
    .admin-dropdown {
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-start !important;
    }
    
    .admin-name {
        font-size: 1rem !important;
        font-weight: 600 !important;
        color: #1f2937 !important;
        margin: 0 !important;
        line-height: 1.2 !important;
    }
    
    .admin-role {
        font-size: 0.75rem !important;
        color: #6b7280 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin: 0 !important;
        line-height: 1.2 !important;
    }
    
    .dropdown-arrow {
        margin-right: 0.5rem !important;
        color: #6b7280 !important;
    }
    
    /* قسم عنوان الصفحة والـ breadcrumb على اليمين */
    .page-title-section {
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-end !important;
        text-align: right !important;
    }
    
    .page-title-main {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
        margin: 0 !important;
        line-height: 1.2 !important;
    }
    
    .breadcrumb-section {
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        font-size: 0.875rem !important;
        color: #6b7280 !important;
        margin-top: 0.25rem !important;
    }
    
    .breadcrumb-link {
        color: #3b82f6 !important;
        text-decoration: none !important;
    }
    
    .breadcrumb-link:hover {
        text-decoration: underline !important;
    }
    
    .breadcrumb-separator {
        color: #9ca3af !important;
    }
    
    /* الأفاتار */
    .header-avatar,
    .admin-avatar,
    .user-avatar:not(.user-profile-avatar) {
        width: 40px !important;
        height: 40px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
        border: 2px solid #e5e7eb !important;
        margin: 0 !important;
        padding: 0 !important;
        flex-shrink: 0 !important;
    }
    
    .header-avatar-placeholder,
    .admin-avatar-placeholder,
    .user-avatar-placeholder:not(.user-profile-avatar-placeholder) {
        width: 40px !important;
        height: 40px !important;
        font-size: 1rem !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 !important;
        padding: 0 !important;
        min-width: 40px !important;
        max-width: 40px !important;
        min-height: 40px !important;
        max-height: 40px !important;
        flex-shrink: 0 !important;
        background: #3b82f6 !important;
        color: white !important;
        font-weight: 600 !important;
    }
    
    /* أيقونة الإشعارات */
    .notification-icon {
        width: 40px !important;
        height: 40px !important;
        border-radius: 50% !important;
        background: #f3f4f6 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #6b7280 !important;
        font-size: 1.1rem !important;
        margin-left: 0.75rem !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
    }
    
    .notification-icon:hover {
        background: #e5e7eb !important;
        color: #374151 !important;
    }
    
    /* تحسين التجاوب */
    @media (max-width: 768px) {
        .main-header,
        .admin-header,
        .top-navigation {
            padding: 0.75rem 1rem !important;
        }
        
        .page-title-main {
            font-size: 1.25rem !important;
        }
        
        .admin-name {
            font-size: 0.9rem !important;
        }
        
        .admin-role {
            font-size: 0.7rem !important;
        }
        
        .breadcrumb-section {
            font-size: 0.8rem !important;
        }
    }
    
    @media (max-width: 480px) {
        .admin-section {
            gap: 0.5rem !important;
        }
        
        .page-title-section {
            align-items: center !important;
        }
        
        .notification-icon {
            margin-left: 0.5rem !important;
        }
    }
    
    .user-status {
        display: inline-block;
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-verified {
        background: var(--success-100);
        color: var(--success-700);
    }
    
    .status-unverified {
        background: var(--warning-100);
        color: var(--warning-700);
    }
    
    .user-info {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .user-name {
        font-size: 2rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        margin: 0;
    }
    
    .user-email {
        font-size: 1.125rem;
        color: var(--admin-secondary-600);
        margin: 0;
    }
    
    .user-role {
        display: inline-block;
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-sm);
    }
    
    .role-admin {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .role-user {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        border: 1px solid var(--admin-primary-200);
    }
    
    .user-meta {
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .user-actions {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
        align-items: flex-end;
    }
    
    .action-btn {
        padding: var(--space-sm) var(--space-lg);
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        min-width: 120px;
        justify-content: center;
    }
    
    .action-btn.edit {
        background: var(--warning-500);
        color: white;
    }
    
    .action-btn.edit:hover {
        background: var(--warning-600);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .action-btn.delete {
        background: var(--error-500);
        color: white;
    }
    
    .action-btn.delete:hover {
        background: var(--error-600);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .action-btn.back {
        background: var(--admin-secondary-500);
        color: white;
    }
    
    .action-btn.back:hover {
        background: var(--admin-secondary-600);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .user-stats {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .stats-header {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        padding: var(--space-lg) var(--space-xl);
    }
    
    .stats-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-xl);
        padding: var(--space-xl);
    }
    
    .stat-item {
        text-align: center;
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        transition: all var(--transition-fast);
    }
    
    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .stat-icon {
        font-size: 2rem;
        margin-bottom: var(--space-sm);
        color: var(--admin-primary-500);
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }
    
    .user-details {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .details-header {
        background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600));
        color: white;
        padding: var(--space-lg) var(--space-xl);
    }
    
    .details-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .details-content {
        padding: var(--space-xl);
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-xl);
    }
    
    .detail-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
    }
    
    .detail-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .detail-value {
        font-size: 1rem;
        color: var(--admin-secondary-900);
        font-weight: 500;
    }
    
    .detail-value.empty {
        color: var(--admin-secondary-400);
        font-style: italic;
    }
    
    .recent-activity {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .activity-header {
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
        color: white;
        padding: var(--space-lg) var(--space-xl);
    }
    
    .activity-content {
        padding: var(--space-xl);
    }
    
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        transition: all var(--transition-fast);
    }
    
    .activity-item:hover {
        background: var(--admin-secondary-100);
        transform: translateX(4px);
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
    }
    
    .activity-order {
        background: var(--admin-primary-500);
    }
    
    .activity-login {
        background: var(--success-500);
    }
    
    .activity-register {
        background: var(--warning-500);
    }
    
    .activity-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
    }
    
    .activity-title {
        font-weight: 600;
        color: var(--admin-secondary-900);
    }
    
    .activity-description {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
    }
    
    .activity-time {
        font-size: 0.75rem;
        color: var(--admin-secondary-500);
        font-weight: 500;
    }
    
    .empty-activity {
        text-align: center;
        padding: var(--space-2xl);
        color: var(--admin-secondary-500);
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: var(--space-md);
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .user-show-container {
            margin: 0;
        }
        
        .user-hero {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
            text-align: center;
        }
        
        .user-actions {
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .details-grid {
            grid-template-columns: 1fr;
        }
        
        .activity-item {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="user-show-container">
    <!-- رأس بيانات المستخدم -->
    <div class="user-header fade-in">
        <div class="user-hero">
            <!-- قسم الصورة الشخصية -->
            <div class="user-avatar-section">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="user-profile-avatar">
                @else
                    <div class="user-profile-avatar-placeholder">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                
                @if($user->email_verified_at)
                    <div class="user-status status-verified">
                        <i class="fas fa-check-circle"></i>
                        متحقق
                    </div>
                @else
                    <div class="user-status status-unverified">
                        <i class="fas fa-clock"></i>
                        غير متحقق
                    </div>
                @endif
            </div>
            
            <!-- معلومات المستخدم -->
            <div class="user-info">
                <h1 class="user-name">{{ $user->name }}</h1>
                <p class="user-email">{{ $user->email }}</p>
                
                <div class="user-role role-{{ $user->role ?? 'user' }}">
                    <i class="fas fa-{{ ($user->role ?? 'user') === 'admin' ? 'crown' : 'user' }}"></i>
                    {{ ($user->role ?? 'user') === 'admin' ? 'مدير' : 'مستخدم' }}
                </div>
                
                <div class="user-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-plus"></i>
                        انضم في {{ $user->created_at->format('d/m/Y') }}
                    </div>
                    
                    @if($user->last_login_at)
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            آخر تسجيل دخول {{ $user->last_login_at->diffForHumans() }}
                        </div>
                    @endif
                    
                    @if($user->phone)
                        <div class="meta-item">
                            <i class="fas fa-phone"></i>
                            {{ $user->phone }}
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- الإجراءات -->
            <div class="user-actions">
                <a href="{{ route('admin.users.edit', $user) }}" class="action-btn edit">
                    <i class="fas fa-edit"></i>
                    تعديل المستخدم
                </a>
                
                @if($user->id !== auth()->id())
                    <button class="action-btn delete" onclick="deleteUser('{{ $user->id }}')">
                        <i class="fas fa-trash"></i>
                        حذف المستخدم
                    </button>
                @endif
                
                <a href="{{ route('admin.users.index') }}" class="action-btn back">
                    <i class="fas fa-arrow-right"></i>
                    العودة للمستخدمين
                </a>
            </div>
        </div>
    </div>
    
    <!-- إحصائيات المستخدم -->
    <div class="user-stats fade-in">
        <div class="stats-header">
            <h2 class="stats-title">
                <i class="fas fa-chart-bar"></i>
                إحصائيات المستخدم
            </h2>
        </div>
        
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-value">{{ $user->orders->count() ?? 0 }}</div>
                <div class="stat-label">إجمالي الطلبات</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value">${{ number_format($user->orders->sum('total') ?? 0, 2) }}</div>
                <div class="stat-label">إجمالي المبلغ المدفوع</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value">{{ $user->testimonials->count() ?? 0 }}</div>
                <div class="stat-label">التقييمات</div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-value">{{ $user->wishlist->count() ?? 0 }}</div>
                <div class="stat-label">قائمة الرغبات</div>
            </div>
        </div>
    </div>
    
    <!-- تفاصيل المستخدم -->
    <div class="user-details fade-in">
        <div class="details-header">
            <h2 class="details-title">
                <i class="fas fa-info-circle"></i>
                تفاصيل الحساب
            </h2>
        </div>
        
        <div class="details-content">
            <div class="details-grid">
                <div class="detail-group">
                    <div class="detail-item">
                        <div class="detail-label">الاسم الكامل</div>
                        <div class="detail-value">{{ $user->name }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">البريد الإلكتروني</div>
                        <div class="detail-value">{{ $user->email }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">رقم الهاتف</div>
                        <div class="detail-value {{ $user->phone ? '' : 'empty' }}">
                            {{ $user->phone ?: 'غير مقدم' }}
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">نوع المستخدم</div>
                        <div class="detail-value">{{ ($user->role ?? 'user') === 'admin' ? 'مدير' : 'مستخدم' }}</div>
                    </div>
                </div>
                
                <div class="detail-group">
                    <div class="detail-item">
                        <div class="detail-label">تاريخ التسجيل</div>
                        <div class="detail-value">{{ $user->created_at->format('d/m/Y \f\i g:i A') }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">التحقق من البريد الإلكتروني</div>
                        <div class="detail-value">
                            @if($user->email_verified_at)
                                تم التحقق في {{ $user->email_verified_at->format('d/m/Y \f\i g:i A') }}
                            @else
                                لم يتم التحقق
                            @endif
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">آخر تسجيل دخول</div>
                        <div class="detail-value {{ $user->last_login_at ? '' : 'empty' }}">
                            @if($user->last_login_at)
                                {{ $user->last_login_at->format('d/m/Y \f\i g:i A') }}
                                <br><small>({{ $user->last_login_at->diffForHumans() }})</small>
                            @else
                                لم يقم بتسجيل الدخول أبداً
                            @endif
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">حالة الحساب</div>
                        <div class="detail-value">
                            @if($user->email_verified_at)
                                <span style="color: var(--success-600);">نشط</span>
                            @else
                                <span style="color: var(--warning-600);">في انتظار التحقق</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- النشاط الحديث -->
    <div class="recent-activity fade-in">
        <div class="activity-header">
            <h2 class="details-title">
                <i class="fas fa-history"></i>
                النشاط الحديث
            </h2>
        </div>
        
        <div class="activity-content">
            @if($user->orders->count() > 0 || $user->last_login_at)
                <div class="activity-list">
                    @if($user->last_login_at)
                        <div class="activity-item">
                            <div class="activity-icon activity-login">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div class="activity-info">
                                <div class="activity-title">آخر تسجيل دخول</div>
                                <div class="activity-description">قام المستخدم بتسجيل الدخول إلى النظام</div>
                                <div class="activity-time">{{ $user->last_login_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @endif
                    
                    @foreach($user->orders->take(5) as $order)
                        <div class="activity-item">
                            <div class="activity-icon activity-order">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="activity-info">
                                <div class="activity-title">الطلب #{{ $order->order_number }}</div>
                                <div class="activity-description">
                                    قام بتقديم طلب بقيمة ${{ number_format($order->total, 2) }}
                                </div>
                                <div class="activity-time">{{ $order->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="activity-item">
                        <div class="activity-icon activity-register">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-info">
                            <div class="activity-title">إنشاء الحساب</div>
                            <div class="activity-description">تم تسجيل المستخدم في المنصة</div>
                            <div class="activity-time">{{ $user->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-activity">
                    <div class="empty-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="empty-title">لا يوجد نشاط حديث</div>
                    <p class="empty-text">هذا المستخدم ليس لديه نشاط حديث لعرضه.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- نافذة تأكيد الحذف -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-xl); max-width: 400px; width: 90%; text-align: center;">
        <div style="color: var(--error-500); font-size: 3rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">حذف المستخدم</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            هل أنت متأكد من حذف هذا المستخدم؟ هذا الإجراء لا يمكن التراجع عنه وسيؤدي إلى إزالة جميع البيانات المرتبطة.
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center;">
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
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        userToDelete = null;
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
</script>
@endpush