@extends('layouts.app')

@section('title', __('الملف الشخصي') . ' - ' . config('app.name'))

@push('styles')
<style>
    /* RTL Direction */
    body {
        direction: rtl;
        text-align: right;
    }

    .profile-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .profile-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,0 1000,0 1000,80 0,100"/></svg>');
        background-size: cover;
        background-position: bottom;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: var(--space-xl);
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: 4px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 900;
        color: white;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-info {
        flex: 1;
    }
    
    .profile-name {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .profile-meta {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        margin-bottom: var(--space-md);
        flex-wrap: wrap;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        font-size: 1rem;
        opacity: 0.9;
        flex-direction: row-reverse;
    }
    
    .profile-stats {
        display: flex;
        gap: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .stat-item {
        text-align: center;
        background: rgba(255, 255, 255, 0.1);
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        border: 1px solid rgba(255, 255, 255, 0.2);
        min-width: 100px;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        display: block;
        margin-bottom: var(--space-xs);
    }
    
    .stat-label {
        font-size: 0.75rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .profile-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
    }
    
    .profile-content {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: var(--space-2xl);
    }
    
    .profile-sidebar {
        display: flex;
        flex-direction: column;
        gap: var(--space-xl);
    }
    
    .profile-main {
        display: flex;
        flex-direction: column;
        gap: var(--space-xl);
    }
    
    .section-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-normal);
    }
    
    .section-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: var(--space-lg);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-direction: row-reverse;
    }
    
    .quick-actions {
        display: grid;
        gap: var(--space-md);
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        background: var(--surface);
        color: var(--on-surface);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-weight: 500;
        transition: all var(--transition-fast);
        flex-direction: row-reverse;
    }
    
    .action-btn:hover {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }
    
    .action-btn.primary {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border-color: var(--primary-500);
    }
    
    .action-btn.primary:hover {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        border-color: var(--primary-600);
        color: white;
    }
    
    .personal-info {
        background: linear-gradient(135deg, var(--primary-50), var(--secondary-50));
        border: 2px solid var(--primary-200);
    }
    
    .info-grid {
        display: grid;
        gap: var(--space-lg);
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: var(--space-md) 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 500;
        color: var(--on-surface-variant);
        min-width: 120px;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-direction: row-reverse;
    }
    
    .info-value {
        color: var(--on-surface);
        font-weight: 500;
        text-align: left;
        line-height: 1.4;
    }
    
    .info-value.empty {
        color: var(--on-surface-variant);
        font-style: italic;
    }
    
    .recent-activity {
        background: var(--surface);
    }
    
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-md);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-600);
        font-size: 1rem;
        flex-shrink: 0;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-title {
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
    }
    
    .activity-meta {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
    }
    
    .activity-time {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        font-weight: 500;
    }
    
    .account-security {
        background: var(--surface);
    }
    
    .security-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-lg) 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .security-item:last-child {
        border-bottom: none;
    }
    
    .security-info {
        flex: 1;
    }
    
    .security-title {
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
    }
    
    .security-description {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
        line-height: 1.4;
    }
    
    .security-status {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        font-size: 0.875rem;
        font-weight: 500;
        flex-direction: row-reverse;
    }
    
    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    .status-secure {
        background: var(--success-500);
        color: var(--success-600);
    }
    
    .status-warning {
        background: var(--warning-500);
        color: var(--warning-600);
    }
    
    .preferences {
        background: var(--surface);
    }
    
    .preference-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-md) 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .preference-item:last-child {
        border-bottom: none;
    }
    
    .preference-info {
        flex: 1;
    }
    
    .preference-title {
        font-weight: 500;
        color: var(--on-surface);
        margin-bottom: var(--space-xs);
    }
    
    .preference-description {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
    }
    
    .preference-control {
        margin-right: var(--space-md);
        margin-left: 0;
    }
    
    .toggle-switch {
        position: relative;
        width: 50px;
        height: 24px;
        background: var(--border-color);
        border-radius: 12px;
        cursor: pointer;
        transition: background var(--transition-fast);
    }
    
    .toggle-switch.active {
        background: var(--primary-500);
    }
    
    .toggle-switch::before {
        content: '';
        position: absolute;
        top: 2px;
        right: 2px;
        left: auto;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        transition: transform var(--transition-fast);
        box-shadow: var(--shadow-sm);
    }
    
    .toggle-switch.active::before {
        transform: translateX(-26px);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-2xl);
        color: var(--on-surface-variant);
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: var(--space-md);
        opacity: 0.5;
    }
    
    .empty-text {
        font-size: 0.875rem;
        line-height: 1.6;
    }
    
    @media (max-width: 768px) {
        .hero-content {
            flex-direction: column;
            text-align: center;
            gap: var(--space-lg);
        }
        
        .profile-name {
            font-size: 2rem;
        }
        
        .profile-meta {
            justify-content: center;
        }
        
        .profile-stats {
            justify-content: center;
        }
        
        .profile-content {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .info-row {
            flex-direction: column;
            gap: var(--space-sm);
        }
        
        .info-value {
            text-align: right;
        }
        
        .security-item,
        .preference-item {
            flex-direction: column;
            gap: var(--space-md);
            align-items: flex-start;
        }
        
        .preference-control {
            margin-right: 0;
        }
    }
</style>
@endpush

@section('content')
<!-- Profile Hero -->
<section class="profile-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <div class="profile-avatar">
                @if($user->profile_image)
                    <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}">
                @else
                    {{ $user->initials }}
                @endif
            </div>
            
            <div class="profile-info">
                <h1 class="profile-name">{{ $user->name }}</h1>
                
                <div class="profile-meta">
                    <div class="meta-item">
                        <i class="fas fa-envelope"></i>
                        {{ $user->email }}
                    </div>
                    
                    @if($user->phone)
                        <div class="meta-item">
                            <i class="fas fa-phone"></i>
                            {{ $user->phone }}
                        </div>
                    @endif
                    
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        {{ __('عضو منذ') }} {{ $user->created_at->format('M Y') }}
                    </div>
                </div>
                
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $user->total_orders }}</span>
                        <div class="stat-label">{{ __('الطلبات') }}</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">${{ number_format($user->total_spent, 0) }}</span>
                        <div class="stat-label">{{ __('إجمالي الإنفاق') }}</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $user->wishlist_items_count }}</span>
                        <div class="stat-label">{{ __('قائمة الرغبات') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Profile Container -->
<section class="profile-container">
    <div class="container">
        <div class="profile-content">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <!-- Quick Actions -->
                <div class="section-card fade-in">
                    <h2 class="section-title">
                        <i class="fas fa-bolt"></i>
                        {{ __('إجراءات سريعة') }}
                    </h2>
                    
                    <div class="quick-actions">
                        <a href="{{ route('user.profile.edit') }}" class="action-btn primary">
                            <i class="fas fa-edit"></i>
                            {{ __('تعديل الملف الشخصي') }}
                        </a>
                        
                        <a href="{{ route('user.profile.change-password') }}" class="action-btn">
                            <i class="fas fa-key"></i>
                            {{ __('تغيير كلمة المرور') }}
                        </a>
                        
                        <a href="{{ route('orders.index') }}" class="action-btn">
                            <i class="fas fa-shopping-bag"></i>
                            {{ __('عرض الطلبات') }}
                        </a>
                        
                        <a href="{{ route('wishlist.index') }}" class="action-btn">
                            <i class="fas fa-heart"></i>
                            {{ __('قائمة رغباتي') }}
                        </a>
                        
                        <a href="{{ route('user.conversations.index') }}" class="action-btn">
                            <i class="fas fa-comments"></i>
                            {{ __('الرسائل') }}
                        </a>
                        
                        <a href="{{ route('coupons.index') }}" class="action-btn">
                            <i class="fas fa-ticket-alt"></i>
                            {{ __('كوبوناتي') }}
                        </a>
                    </div>
                </div>
                
                <!-- Account Security -->
                <div class="section-card account-security fade-in">
                    <h2 class="section-title">
                        <i class="fas fa-shield-alt"></i>
                        {{ __('أمان الحساب') }}
                    </h2>
                    
                    <div class="security-item">
                        <div class="security-info">
                            <div class="security-title">{{ __('تأكيد البريد الإلكتروني') }}</div>
                            <div class="security-description">{{ __('تم التحقق من عنوان بريدك الإلكتروني') }}</div>
                        </div>
                        <div class="security-status status-secure">
                            <span class="status-indicator status-secure"></span>
                            {{ __('تم التحقق') }}
                        </div>
                    </div>
                    
                    <div class="security-item">
                        <div class="security-info">
                            <div class="security-title">{{ __('أمان كلمة المرور') }}</div>
                            <div class="security-description">{{ __('آخر تغيير') }} {{ $user->updated_at->diffForHumans() }}</div>
                        </div>
                        <div class="security-status status-secure">
                            <span class="status-indicator status-secure"></span>
                            {{ __('قوية') }}
                        </div>
                    </div>
                    
                    <div class="security-item">
                        <div class="security-info">
                            <div class="security-title">{{ __('المصادقة الثنائية') }}</div>
                            <div class="security-description">{{ __('أضف طبقة أمان إضافية') }}</div>
                        </div>
                        <div class="security-status status-warning">
                            <span class="status-indicator status-warning"></span>
                            {{ __('غير مفعلة') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="profile-main">
                <!-- Personal Information -->
                <div class="section-card personal-info fade-in">
                    <h2 class="section-title">
                        <i class="fas fa-user"></i>
                        {{ __('المعلومات الشخصية') }}
                    </h2>
                    
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-user"></i>
                                {{ __('الاسم الكامل') }}
                            </span>
                            <span class="info-value">{{ $user->name }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-envelope"></i>
                                {{ __('البريد الإلكتروني') }}
                            </span>
                            <span class="info-value">{{ $user->email }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-phone"></i>
                                {{ __('رقم الهاتف') }}
                            </span>
                            <span class="info-value {{ !$user->phone ? 'empty' : '' }}">
                                {{ $user->phone ?: __('غير متوفر') }}
                            </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ __('العنوان') }}
                            </span>
                            <span class="info-value {{ !$user->address ? 'empty' : '' }}">
                                {{ $user->address ?: __('غير متوفر') }}
                            </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-calendar-plus"></i>
                                {{ __('عضو منذ') }}
                            </span>
                            <span class="info-value">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-clock"></i>
                                {{ __('آخر تحديث') }}
                            </span>
                            <span class="info-value">{{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="section-card recent-activity fade-in">
                    <h2 class="section-title">
                        <i class="fas fa-history"></i>
                        {{ __('النشاط الحديث') }}
                    </h2>
                    
                    @if($user->orders->count() > 0)
                        <div class="activity-list">
                            @foreach($user->orders->take(5) as $order)
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    
                                    <div class="activity-content">
                                        <div class="activity-title">
                                            {{ __('طلب') }} #{{ $order->id }} - {{ ucfirst($order->status) }}
                                        </div>
                                        <div class="activity-meta">
                                            {{ $order->orderItems->sum('quantity') }} {{ __('عناصر') }} • ${{ number_format($order->total_amount, 2) }}
                                        </div>
                                    </div>
                                    
                                    <div class="activity-time">
                                        {{ $order->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="empty-text">{{ __('لا يوجد نشاط حديث') }}</div>
                        </div>
                    @endif
                </div>
                
                <!-- Preferences -->
                <div class="section-card preferences fade-in">
                    <h2 class="section-title">
                        <i class="fas fa-cog"></i>
                        {{ __('التفضيلات') }}
                    </h2>
                    
                    <div class="preference-item">
                        <div class="preference-info">
                            <div class="preference-title">{{ __('إشعارات البريد الإلكتروني') }}</div>
                            <div class="preference-description">{{ __('تلقي تحديثات حول طلباتك والعروض الترويجية') }}</div>
                        </div>
                        <div class="preference-control">
                            <div class="toggle-switch active" onclick="togglePreference(this)"></div>
                        </div>
                    </div>
                    
                    <div class="preference-item">
                        <div class="preference-info">
                            <div class="preference-title">{{ __('إشعارات الرسائل النصية') }}</div>
                            <div class="preference-description">{{ __('الحصول على تحديثات لحالة الطلب والتسليم') }}</div>
                        </div>
                        <div class="preference-control">
                            <div class="toggle-switch" onclick="togglePreference(this)"></div>
                        </div>
                    </div>
                    
                    <div class="preference-item">
                        <div class="preference-info">
                            <div class="preference-title">{{ __('الاتصالات التسويقية') }}</div>
                            <div class="preference-description">{{ __('تلقي العروض الترويجية وإعلانات المنتجات الجديدة') }}</div>
                        </div>
                        <div class="preference-control">
                            <div class="toggle-switch" onclick="togglePreference(this)"></div>
                        </div>
                    </div>
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Initialize animations
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
    
    // Toggle preference functionality
    function togglePreference(toggle) {
        toggle.classList.toggle('active');
        
        // You can add AJAX call here to save preference
        const isActive = toggle.classList.contains('active');
        const preferenceItem = toggle.closest('.preference-item');
        const preferenceTitle = preferenceItem.querySelector('.preference-title').textContent;
        
        // Example AJAX call (uncomment and implement as needed)
        /*
        fetch('/profile/preferences', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                preference: preferenceTitle,
                enabled: isActive
            })
        });
        */
        
        showNotification(`${preferenceTitle} ${isActive ? 'مفعل' : 'غير مفعل'}`, 'success');
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 20px;
            right: auto;
            z-index: 9999;
            max-width: 300px;
            box-shadow: var(--shadow-xl);
            animation: slideIn 0.3s ease-out;
        `;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out forwards';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Add hover effects to action buttons
    document.querySelectorAll('.action-btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1.1)';
            }
        });
        
        btn.addEventListener('mouseleave', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1)';
            }
        });
    });
    
    // Add click animation to stats
    document.querySelectorAll('.stat-item').forEach(stat => {
        stat.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
</script>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }
</style>
@endpush