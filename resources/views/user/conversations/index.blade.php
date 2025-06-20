@extends('layouts.app')

@section('title', 'محادثاتي')

@push('styles')
<style>
.conversations-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: var(--space-xl) var(--space-md);
}

.page-header {
    text-align: center;
    margin-bottom: var(--space-2xl);
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: var(--space-md);
}

.page-subtitle {
    color: var(--on-surface-variant);
    font-size: 1.125rem;
    max-width: 600px;
    margin: 0 auto;
}

.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-xl);
    padding: var(--space-lg);
    background: var(--surface);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.action-bar-info h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--on-surface);
    margin-bottom: var(--space-sm);
}

.action-bar-stats {
    display: flex;
    gap: var(--space-lg);
    color: var(--on-surface-variant);
    font-size: 0.875rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.conversations-grid {
    display: grid;
    gap: var(--space-lg);
}

.conversation-card {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all var(--transition-normal);
    position: relative;
}

.conversation-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.conversation-card.unread {
    border-right: 4px solid var(--primary-500);
    background: linear-gradient(90deg, rgba(14, 165, 233, 0.02), var(--surface));
}

.conversation-header {
    padding: var(--space-lg);
    border-bottom: 1px solid var(--border-color);
}

.conversation-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--on-surface);
    margin-bottom: var(--space-sm);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.conversation-meta {
    display: flex;
    align-items: center;
    gap: var(--space-lg);
    color: var(--on-surface-variant);
    font-size: 0.875rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
}

.conversation-preview {
    padding: var(--space-lg);
    color: var(--on-surface-variant);
    font-size: 0.875rem;
    line-height: 1.6;
    border-bottom: 1px solid var(--border-color);
}

.last-message {
    font-style: italic;
}

.conversation-actions {
    padding: var(--space-md) var(--space-lg);
    background: var(--surface-variant);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.conversation-status {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    font-size: 0.75rem;
    color: var(--on-surface-variant);
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-dot.read {
    background: var(--success-500);
}

.status-dot.unread {
    background: var(--primary-500);
}

.action-buttons {
    display: flex;
    gap: var(--space-sm);
}

.badge {
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-info {
    background: var(--info-100);
    color: var(--info-700);
}

.empty-state {
    text-align: center;
    padding: var(--space-3xl) var(--space-lg);
    background: var(--surface);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.empty-icon {
    font-size: 4rem;
    color: var(--on-surface-variant);
    margin-bottom: var(--space-lg);
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--on-surface);
    margin-bottom: var(--space-md);
}

.empty-description {
    color: var(--on-surface-variant);
    margin-bottom: var(--space-xl);
    max-width: 400px;
    margin-right: auto;
    margin-left: auto;
}

.new-conversation-badge {
    position: absolute;
    top: var(--space-md);
    left: var(--space-md);
    background: var(--primary-500);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    font-size: 0.625rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

@media (max-width: 768px) {
    .conversations-container {
        padding: var(--space-lg) var(--space-sm);
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .action-bar {
        flex-direction: column;
        gap: var(--space-md);
        text-align: center;
    }
    
    .action-bar-stats {
        flex-wrap: wrap;
        justify-content: center;
        gap: var(--space-md);
    }
    
    .conversation-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-sm);
    }
    
    .conversation-actions {
        flex-direction: column;
        gap: var(--space-sm);
        text-align: center;
    }
    
    .action-buttons {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@section('content')
<div class="conversations-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-comments" style="color: var(--primary-500); margin-left: var(--space-sm);"></i>
            محادثاتي
        </h1>
        <p class="page-subtitle">
            إدارة محادثاتك والحصول على الدعم من فريقنا
        </p>
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
        <div class="action-bar-info">
            <h2>محادثات الدعم الفني</h2>
            <div class="action-bar-stats">
                <div class="stat-item">
                    <i class="fas fa-comments"></i>
                    <span>{{ $conversations && method_exists($conversations, 'total') ? $conversations->total() : ($conversations ? $conversations->count() : 0) }} المجموع</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $conversations && method_exists($conversations, 'where') ? $conversations->where('is_read_by_user', false)->count() : 0 }} غير مقروءة</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <span>دعم نشط</span>
                </div>
            </div>
        </div>
        
        <a href="{{ route('user.conversations.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i>
            بدء محادثة جديدة
        </a>
    </div>

    <!-- Conversations List -->
    @if($conversations && $conversations->count() > 0)
        <div class="conversations-grid">
            @foreach($conversations as $conversation)
            <div class="conversation-card {{ !($conversation->is_read_by_user ?? true) ? 'unread' : '' }}">
                
                @if(!($conversation->is_read_by_user ?? true))
                <div class="new-conversation-badge">
                    جديد
                </div>
                @endif

                <div class="conversation-header">
                    <div class="conversation-title">
                        <i class="fas fa-comment-dots" style="color: var(--primary-500);"></i>
                        {{ $conversation->title ?? 'محادثة بدون عنوان' }}
                    </div>
                    <div class="conversation-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>بدأت {{ isset($conversation->created_at) ? $conversation->created_at->diffForHumans() : 'مؤخراً' }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>محدثة {{ isset($conversation->updated_at) ? $conversation->updated_at->diffForHumans() : 'مؤخراً' }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-comment"></i>
                            <span>{{ isset($conversation->messages) ? $conversation->messages->count() : 0 }} رسالة</span>
                        </div>
                    </div>
                </div>

                @if(isset($conversation->lastMessage) && $conversation->lastMessage)
                <div class="conversation-preview">
                    <strong>
                        {{ $conversation->lastMessage->is_from_admin ? 'فريق الدعم' : 'أنت' }}:
                    </strong>
                    <span class="last-message">
                        "{{ Str::limit($conversation->lastMessage->message, 120) }}"
                    </span>
                </div>
                @endif

                <div class="conversation-actions">
                    <div class="conversation-status">
                        <div class="status-dot {{ ($conversation->is_read_by_user ?? true) ? 'read' : 'unread' }}"></div>
                        <span>
                            {{ ($conversation->is_read_by_user ?? true) ? 'مقروءة' : 'رسائل جديدة متاحة' }}
                        </span>
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('user.conversations.show', $conversation->id ?? 1) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            عرض المحادثة
                        </a>
                        @if(!($conversation->is_read_by_user ?? true))
                        <span class="badge badge-info">
                            رد جديد
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($conversations, 'links'))
        <div style="display: flex; justify-content: center; margin-top: var(--space-2xl);">
            {{ $conversations->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-comments"></i>
            </div>
            <h3 class="empty-title">لا توجد محادثات بعد</h3>
            <p class="empty-description">
                لم تبدأ أي محادثات مع فريق الدعم الفني بعد. 
                تحتاج مساعدة في شيء ما؟ ابدأ محادثة وسنكون سعداء لمساعدتك!
            </p>
            <a href="{{ route('user.conversations.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                ابدأ محادثتك الأولى
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Add some interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects for conversation cards
    const conversationCards = document.querySelectorAll('.conversation-card');
    
    conversationCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-2px)';
        });
    });
    
    // Auto-refresh for new messages every 30 seconds
    setInterval(function() {
        // Check for new messages indicator
        const unreadCount = document.querySelectorAll('.conversation-card.unread').length;
        
        // You can implement AJAX call here to check for new messages
        // For now, just update the page title if there are unread messages
        if (unreadCount > 0) {
            document.title = `(${unreadCount}) محادثاتي - {{ config('app.name') }}`;
        } else {
            document.title = `محادثاتي - {{ config('app.name') }}`;
        }
    }, 30000);
    
    // Update conversations badge in header
    @auth
    if (typeof updateConversationsBadge === 'function') {
        updateConversationsBadge();
    }
    @endauth

    console.log('💬 تم تحميل صفحة المحادثات بنجاح');
});
</script>
@endpush