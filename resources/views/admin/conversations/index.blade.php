@extends('layouts.admin')

@section('title', 'المحادثات')
@section('page-title', 'محادثات العملاء')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    المحادثات
</div>
@endsection

@push('styles')
<style>
    * {
        direction: rtl;
        text-align: right;
    }
    
    .conversations-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-2xl);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: var(--space-xl);
        border-radius: var(--radius-xl);
        color: white;
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
    }
    
    .conversations-title {
        font-size: 2rem;
        font-weight: 800;
        color: white;
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .conversations-description {
        color: rgba(255,255,255,0.9);
        font-size: 1rem;
        font-weight: 500;
    }
    
    .conversations-actions {
        display: flex;
        gap: var(--space-md);
        flex-wrap: wrap;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-2xl);
    }
    
    .stat-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border: 1px solid var(--admin-secondary-100);
        overflow: hidden;
        transition: all var(--transition-normal);
        position: relative;
    }
    
    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .stat-card-body {
        text-align: center;
        padding: var(--space-xl);
        position: relative;
        z-index: 2;
    }
    
    .stat-icon {
        font-size: 3rem;
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        margin: 0 auto var(--space-lg);
        border-radius: 50%;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        background: linear-gradient(135deg, var(--admin-secondary-900), var(--admin-secondary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-label {
        color: var(--admin-secondary-600);
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-card.total {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .stat-card.total .stat-icon {
        background: rgba(255,255,255,0.15);
        color: white;
    }
    
    .stat-card.total .stat-number,
    .stat-card.total .stat-label {
        color: white;
        background: none;
        -webkit-text-fill-color: white;
    }
    
    .stat-card.unread {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .stat-card.unread .stat-icon {
        background: rgba(255,255,255,0.15);
        color: white;
    }
    
    .stat-card.unread .stat-number,
    .stat-card.unread .stat-label {
        color: white;
        background: none;
        -webkit-text-fill-color: white;
    }
    
    .stat-card.resolved {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .stat-card.resolved .stat-icon {
        background: rgba(255,255,255,0.15);
        color: white;
    }
    
    .stat-card.resolved .stat-number,
    .stat-card.resolved .stat-label {
        color: white;
        background: none;
        -webkit-text-fill-color: white;
    }
    
    .stat-card.users {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    .stat-card.users .stat-icon {
        background: rgba(255,255,255,0.15);
        color: white;
    }
    
    .stat-card.users .stat-number,
    .stat-card.users .stat-label {
        color: white;
        background: none;
        -webkit-text-fill-color: white;
    }
    
    .conversations-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border: 1px solid var(--admin-secondary-100);
        overflow: hidden;
    }
    
    .conversations-header-card {
        background: linear-gradient(135deg, var(--admin-secondary-50), #f1f5f9);
        padding: var(--space-xl);
        border-bottom: 2px solid var(--admin-secondary-200);
    }
    
    .conversations-title-card {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .conversation-item {
        border-bottom: 1px solid var(--admin-secondary-200);
        padding: var(--space-xl);
        transition: all var(--transition-fast);
        position: relative;
        background: white;
    }
    
    .conversation-item:hover {
        background: linear-gradient(135deg, var(--admin-secondary-25), #f8fafc);
        transform: translateX(-5px);
        box-shadow: 5px 0 15px rgba(0,0,0,0.1);
    }
    
    .conversation-item.unread {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.02), rgba(59, 130, 246, 0.01));
        border-right: 4px solid var(--admin-primary-500);
    }
    
    .conversation-item.unread:hover {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(59, 130, 246, 0.02));
    }
    
    .conversation-content {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: var(--space-lg);
    }
    
    .conversation-main {
        flex: 1;
    }
    
    .conversation-header {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        margin-bottom: var(--space-md);
    }
    
    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }
    
    .conversation-info {
        flex: 1;
    }
    
    .conversation-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .new-badge {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-lg);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }
    
    .conversation-meta {
        display: flex;
        align-items: center;
        gap: var(--space-xl);
        color: var(--admin-secondary-600);
        font-size: 0.9rem;
        flex-wrap: wrap;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        font-weight: 500;
    }
    
    .last-message {
        background: linear-gradient(135deg, var(--admin-secondary-50), #f1f5f9);
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        margin-top: var(--space-lg);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .last-message-text {
        color: var(--admin-secondary-700);
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
        font-style: italic;
    }
    
    .conversation-sidebar {
        display: flex;
        flex-direction: column;
        align-items: end;
        gap: var(--space-lg);
        min-width: 200px;
    }
    
    .conversation-status {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .conversation-actions {
        display: flex;
        gap: var(--space-sm);
        flex-wrap: wrap;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        border: none;
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600));
        color: white;
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    .btn-sm {
        padding: var(--space-sm) var(--space-md);
        font-size: 0.8rem;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-lg);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        backdrop-filter: blur(10px);
    }
    
    .badge-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
    
    .badge-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    
    .empty-icon {
        font-size: 5rem;
        margin-bottom: var(--space-xl);
        opacity: 0.6;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .empty-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--admin-secondary-700);
        margin-bottom: var(--space-md);
    }
    
    .empty-text {
        color: var(--admin-secondary-500);
        font-size: 1.1rem;
        line-height: 1.6;
    }
    
    /* RTL Improvements */
    .fas {
        margin-left: var(--space-xs);
        margin-right: 0;
    }
    
    .breadcrumb-item .fas {
        margin: 0 var(--space-xs);
    }
    
    /* Animation Classes */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .conversations-header {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-lg);
        }
        
        .conversations-actions {
            width: 100%;
            justify-content: flex-start;
        }
        
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
        
        .conversation-content {
            flex-direction: column;
            align-items: stretch;
        }
        
        .conversation-sidebar {
            align-items: stretch;
            min-width: auto;
        }
        
        .conversation-actions {
            justify-content: flex-start;
        }
        
        .conversation-meta {
            gap: var(--space-md);
        }
    }
    
    /* Print styles */
    @media print {
        .conversations-actions,
        .btn {
            display: none !important;
        }
        
        .conversations-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }
</style>
@endpush

@section('content')
<div style="padding: var(--space-xl);">
    <!-- Header Section -->
    <div class="conversations-header fade-in">
        <div>
            <h1 class="conversations-title">
                <i class="fas fa-comments"></i>
                محادثات العملاء
            </h1>
            <p class="conversations-description">
                إدارة استفسارات العملاء ومحادثات الدعم الفني
            </p>
        </div>
        
        <div class="conversations-actions">
            @if($conversations->where('is_read_by_admin', false)->count() > 0)
            <form action="{{ route('admin.conversations.mark-all-read') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-double"></i>
                    تحديد الكل كمقروء ({{ $conversations->where('is_read_by_admin', false)->count() }})
                </button>
            </form>
            @endif
            
            <button class="btn btn-secondary" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
                تحديث
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid fade-in">
        <div class="stat-card total">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stat-number">
                    {{ $conversations->count() }}
                </div>
                <div class="stat-label">
                    إجمالي المحادثات
                </div>
            </div>
        </div>
        
        <div class="stat-card unread">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-number">
                    {{ $conversations->where('is_read_by_admin', false)->count() }}
                </div>
                <div class="stat-label">
                    رسائل غير مقروءة
                </div>
            </div>
        </div>
        
        <div class="stat-card resolved">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number">
                    {{ $conversations->where('is_read_by_admin', true)->count() }}
                </div>
                <div class="stat-label">
                    تم حلها
                </div>
            </div>
        </div>
        
        <div class="stat-card users">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">
                    {{ $conversations->unique('user_id')->count() }}
                </div>
                <div class="stat-label">
                    المستخدمون النشطون
                </div>
            </div>
        </div>
    </div>

    <!-- Conversations List -->
    <div class="conversations-card fade-in">
        <div class="conversations-header-card">
            <h3 class="conversations-title-card">
                <i class="fas fa-list"></i>
                جميع المحادثات
            </h3>
        </div>
        
        <div style="padding: 0;">
            @if($conversations->count() > 0)
                @foreach($conversations as $conversation)
                <div class="conversation-item {{ !$conversation->is_read_by_admin ? 'unread' : '' }}">
                    <div class="conversation-content">
                        <!-- Conversation Info -->
                        <div class="conversation-main">
                            <div class="conversation-header">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                                </div>
                                <div class="conversation-info">
                                    <h4 class="conversation-title">
                                        {{ $conversation->title }}
                                        @if(!$conversation->is_read_by_admin)
                                            <span class="new-badge">جديد</span>
                                        @endif
                                    </h4>
                                    <div class="conversation-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-user"></i>
                                            {{ $conversation->user->name }}
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-envelope"></i>
                                            {{ $conversation->user->email }}
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-clock"></i>
                                            {{ $conversation->updated_at->format('d M Y H:i') }}
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-comment"></i>
                                            {{ $conversation->messages->count() ?? 0 }} رسالة
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($conversation->lastMessage ?? false)
                            <div class="last-message">
                                <p class="last-message-text">
                                    <strong>{{ $conversation->lastMessage->is_from_admin ? 'الإدارة' : $conversation->user->name }}:</strong>
                                    "{{ Str::limit($conversation->lastMessage->message, 120) }}"
                                </p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Status & Actions -->
                        <div class="conversation-sidebar">
                            <div class="conversation-status">
                                @if(!$conversation->is_read_by_admin)
                                    <span class="badge badge-warning">رسالة جديدة</span>
                                @else
                                    <span class="badge badge-success">مقروءة</span>
                                @endif
                            </div>
                            
                            <div class="conversation-actions">
                                <a href="{{ route('admin.conversations.show', $conversation) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                    عرض
                                </a>
                                @if(!$conversation->is_read_by_admin)
                                <form action="{{ route('admin.conversations.mark-read', $conversation) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-check"></i>
                                        تحديد كمقروءة
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <!-- Pagination -->
                <div style="padding: var(--space-xl); display: flex; justify-content: center; background: linear-gradient(135deg, var(--admin-secondary-50), #f1f5f9);">
                    {{ $conversations->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="empty-title">
                        لا توجد محادثات بعد
                    </h3>
                    <p class="empty-text">
                        ستظهر محادثات العملاء هنا عندما يبدأون في التواصل مع الدعم الفني.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Auto-refresh every 30 seconds for new messages
let autoRefreshInterval;

function startAutoRefresh() {
    autoRefreshInterval = setInterval(function() {
        // تحقق من وجود رسائل جديدة عبر AJAX
        checkForNewMessages();
    }, 30000);
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
}

async function checkForNewMessages() {
    try {
        const response = await fetch('/admin/conversations/check-updates', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.hasNewMessages) {
                // إظهار إشعار بوجود رسائل جديدة
                showNotification('توجد رسائل جديدة!', 'info');
                
                // تحديث البيانات في الصفحة
                updateConversationCounts(data);
            }
        }
    } catch (error) {
        console.error('Error checking for new messages:', error);
    }
}

function updateConversationCounts(data) {
    // تحديث الإحصائيات
    const unreadCount = document.querySelector('.stat-card.unread .stat-number');
    if (unreadCount && data.unreadCount !== undefined) {
        unreadCount.textContent = data.unreadCount;
    }
    
    // تحديث زر "تحديد الكل كمقروء" إذا لزم الأمر
    if (data.unreadCount > 0) {
        const markAllButton = document.querySelector('button[type="submit"]');
        if (markAllButton && markAllButton.textContent.includes('تحديد الكل كمقروء')) {
            markAllButton.innerHTML = `
                <i class="fas fa-check-double"></i>
                تحديد الكل كمقروء (${data.unreadCount})
            `;
        }
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        left: 20px;
        background: ${type === 'info' ? 'linear-gradient(135deg, #3b82f6, #2563eb)' : 'linear-gradient(135deg, #10b981, #059669)'};
        color: white;
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        z-index: 10000;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        max-width: 300px;
    `;
    
    notification.innerHTML = `
        <i class="fas fa-${type === 'info' ? 'info-circle' : 'check-circle'}"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // تحريك الإشعار للظهور
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // إخفاء الإشعار بعد 4 ثواني
    setTimeout(() => {
        notification.style.transform = 'translateX(-100%)';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Initialize animations and features
document.addEventListener('DOMContentLoaded', function() {
    // Fade-in animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
    
    // Enhanced hover effects
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(-5px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // Add loading states to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.type === 'submit' && !this.disabled) {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحميل...';
                this.disabled = true;
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            }
        });
    });
    
    // Start auto-refresh for new messages
    startAutoRefresh();
    
    // Stop auto-refresh when page becomes hidden
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });
    
    // Stop auto-refresh before page unload
    window.addEventListener('beforeunload', function() {
        stopAutoRefresh();
    });
    
    // Real-time status updates
    document.querySelectorAll('form[action*="mark-read"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if (response.ok) {
                    // إزالة الشارة "جديد" والكلاس "unread"
                    const conversationItem = this.closest('.conversation-item');
                    if (conversationItem) {
                        conversationItem.classList.remove('unread');
                        
                        const newBadge = conversationItem.querySelector('.new-badge');
                        if (newBadge) {
                            newBadge.remove();
                        }
                        
                        const statusBadge = conversationItem.querySelector('.badge-warning');
                        if (statusBadge) {
                            statusBadge.className = 'badge badge-success';
                            statusBadge.textContent = 'مقروءة';
                        }
                        
                        // إزالة الزر
                        this.remove();
                    }
                    
                    showNotification('تم تحديد المحادثة كمقروءة', 'success');
                    
                    // تحديث العدادات
                    setTimeout(() => {
                        checkForNewMessages();
                    }, 500);
                } else {
                    showNotification('حدث خطأ، يرجى المحاولة مرة أخرى', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            }).catch(error => {
                console.error('Error:', error);
                showNotification('حدث خطأ في الاتصال', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });
    });
    
    // Enhanced search functionality (if you want to add search)
    if (window.location.search.includes('search')) {
        const searchResults = document.querySelectorAll('.conversation-item');
        searchResults.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.classList.add('search-result');
        });
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // F5 or Ctrl+R for refresh
        if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
            e.preventDefault();
            window.location.reload();
        }
        
        // Ctrl+A for mark all as read
        if (e.ctrlKey && e.key === 'a') {
            e.preventDefault();
            const markAllForm = document.querySelector('form[action*="mark-all-read"]');
            if (markAllForm) {
                markAllForm.submit();
            }
        }
    });
    
    // Add ripple effect to buttons
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Performance monitoring
    const performanceObserver = new PerformanceObserver((list) => {
        for (const entry of list.getEntries()) {
            if (entry.entryType === 'navigation') {
                console.log('Page load time:', entry.loadEventEnd - entry.loadEventStart, 'ms');
            }
        }
    });
    performanceObserver.observe({ entryTypes: ['navigation'] });
});

// Add CSS for additional animations
const additionalStyles = document.createElement('style');
additionalStyles.textContent = `
    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    .search-result {
        animation: highlightSearch 0.6s ease-out;
    }
    
    @keyframes highlightSearch {
        0% {
            background: rgba(59, 130, 246, 0.1);
            transform: scale(1.02);
        }
        100% {
            background: transparent;
            transform: scale(1);
        }
    }
    
    /* Loading skeleton animation */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }
    
    /* Pulse animation for new messages */
    .pulse-new {
        animation: pulseNew 1s ease-in-out infinite;
    }
    
    @keyframes pulseNew {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
    
    /* Enhanced hover effects */
    .conversation-item:hover .user-avatar {
        transform: scale(1.1);
        box-shadow: 0 12px 25px rgba(59, 130, 246, 0.4);
    }
    
    .conversation-item:hover .conversation-title {
        color: var(--admin-primary-600);
    }
    
    /* Smooth transitions for status changes */
    .badge {
        transition: all 0.3s ease;
    }
    
    .conversation-item.unread .badge-warning {
        animation: pulseWarning 2s ease-in-out infinite;
    }
    
    @keyframes pulseWarning {
        0%, 100% {
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }
        50% {
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.6);
            transform: scale(1.05);
        }
    }
    
    /* Accessibility improvements */
    @media (prefers-reduced-motion: reduce) {
        .fade-in,
        .stat-card,
        .conversation-item,
        .btn {
            transition: none;
            animation: none;
        }
        
        .fade-in {
            opacity: 1;
            transform: none;
        }
    }
    
    /* Focus indicators for keyboard navigation */
    .btn:focus,
    .conversation-item:focus-within {
        outline: 3px solid var(--admin-primary-400);
        outline-offset: 2px;
    }
    
    /* High contrast mode support */
    @media (prefers-contrast: high) {
        .badge,
        .btn {
            border: 2px solid currentColor;
        }
        
        .conversation-item.unread {
            border-right-width: 6px;
        }
    }
    
    /* Dark mode support (optional) */
    @media (prefers-color-scheme: dark) {
        .conversations-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }
        
        .conversations-card,
        .stat-card {
            background: #1e293b;
            color: #e2e8f0;
            border-color: #334155;
        }
        
        .conversations-header-card {
            background: linear-gradient(135deg, #334155, #475569);
        }
        
        .conversation-item {
            background: #1e293b;
            border-color: #334155;
        }
        
        .conversation-item:hover {
            background: #334155;
        }
    }
`;
document.head.appendChild(additionalStyles);
</script>
@endsection