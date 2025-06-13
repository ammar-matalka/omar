@extends('layouts.app')

@section('title', 'My Conversations')

@push('styles')
<style>
.conversations-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: var(--space-2xl) var(--space-md);
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
    margin: 0 auto var(--space-xl);
}

.conversations-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-xl);
    padding: var(--space-lg);
    background: var(--surface);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.conversations-stats {
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

.conversation-card {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-lg);
    overflow: hidden;
    transition: all var(--transition-normal);
    position: relative;
}

.conversation-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
    border-color: var(--primary-300);
}

.conversation-card.unread {
    border-left: 4px solid var(--primary-500);
    background: linear-gradient(90deg, rgba(14, 165, 233, 0.02), var(--surface));
}

.conversation-card.unread::before {
    content: 'NEW';
    position: absolute;
    top: var(--space-md);
    right: var(--space-md);
    background: var(--primary-500);
    color: white;
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-sm);
    font-size: 0.625rem;
    font-weight: 700;
    letter-spacing: 0.5px;
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
    color: var(--on-surface-variant);
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
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.action-buttons {
    display: flex;
    gap: var(--space-sm);
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
    opacity: 0.5;
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
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 768px) {
    .conversations-container {
        padding: var(--space-lg) var(--space-sm);
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .conversations-header {
        flex-direction: column;
        gap: var(--space-md);
        text-align: center;
    }
    
    .conversations-stats {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .conversation-meta {
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .conversation-actions {
        flex-direction: column;
        gap: var(--space-md);
        text-align: center;
    }
}
</style>
@endpush

@section('content')
<div class="conversations-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">My Conversations</h1>
        <p class="page-subtitle">
            Manage your conversations and get support from our team
        </p>
    </div>

    <!-- Conversations Header -->
    <div class="conversations-header">
        <div>
            <h2 style="font-size: 1.125rem; font-weight: 600; color: var(--on-surface); margin-bottom: var(--space-xs);">
                Your Support Conversations
            </h2>
            <div class="conversations-stats">
                <div class="stat-item">
                    <i class="fas fa-comments"></i>
                    <span>{{ $conversations && method_exists($conversations, 'total') ? $conversations->total() : ($conversations ? $conversations->count() : 0) }} Total</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $conversations && method_exists($conversations, 'where') ? $conversations->where('is_read_by_user', false)->count() : 0 }} Unread</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <span>Active Support</span>
                </div>
            </div>
        </div>
        
        <div>
            <a href="{{ route('user.conversations.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                Start New Conversation
            </a>
        </div>
    </div>

    <!-- Conversations List -->
    @if($conversations && $conversations->count() > 0)
        @foreach($conversations as $conversation)
        <div class="conversation-card {{ !($conversation->is_read_by_user ?? true) ? 'unread' : '' }}">
            <div class="conversation-header">
                <div class="conversation-title">
                    <i class="fas fa-comment-dots" style="color: var(--primary-500);"></i>
                    {{ $conversation->title ?? 'Untitled Conversation' }}
                </div>
                <div class="conversation-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Started {{ isset($conversation->created_at) ? $conversation->created_at->diffForHumans() : 'recently' }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>Updated {{ isset($conversation->updated_at) ? $conversation->updated_at->diffForHumans() : 'recently' }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-comment"></i>
                        <span>{{ isset($conversation->messages) ? $conversation->messages->count() : 0 }} messages</span>
                    </div>
                </div>
            </div>

            @if(isset($conversation->lastMessage) && $conversation->lastMessage)
            <div class="conversation-preview">
                <strong>
                    {{ $conversation->lastMessage->is_from_admin ? 'Support Team' : 'You' }}:
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
                        {{ ($conversation->is_read_by_user ?? true) ? 'Read' : 'New messages available' }}
                    </span>
                </div>
                <div class="action-buttons">
                    <a href="{{ route('user.conversations.show', $conversation->id ?? 1) }}" class="btn btn-primary">
                        <i class="fas fa-eye"></i>
                        View Conversation
                    </a>
                    @if(!($conversation->is_read_by_user ?? true))
                    <span class="badge badge-info">
                        New Reply
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

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
            <h3 class="empty-title">No Conversations Yet</h3>
            <p class="empty-description">
                You haven't started any conversations with our support team yet. 
                Need help with something? Start a conversation and we'll be happy to assist you!
            </p>
            <a href="{{ route('user.conversations.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                Start Your First Conversation
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
            document.title = `(${unreadCount}) My Conversations - {{ config('app.name') }}`;
        }
    }, 30000);
});
</script>
@endpush

    <!-- Conversations List -->
    @if($conversations && $conversations->count() > 0)
        @foreach($conversations as $conversation)
        <div class="conversation-card {{ !($conversation->is_read_by_user ?? true) ? 'unread' : '' }}">
            <!-- Conversation content here -->
        </div>
        @endforeach
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-comments"></i>
            </div>
            <h3 class="empty-title">No Conversations Yet</h3>
            <p class="empty-description">
                You haven't started any conversations with our support team yet. 
                Need help with something? Start a conversation and we'll be happy to assist you!
            </p>
            <a href="{{ route('user.conversations.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                Start Your First Conversation
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
            document.title = `(${unreadCount}) My Conversations - ${document.title.split(' - ')[1] || 'Site'}`;
        }
    }, 30000);
});
</script>
@endpush