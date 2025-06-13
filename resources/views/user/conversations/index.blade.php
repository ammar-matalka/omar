@extends('layouts.app')

@section('title', 'My Conversations')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <!-- Page Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: #333; margin-bottom: 1rem;">
            <i class="fas fa-comments" style="color: #0ea5e9; margin-right: 0.5rem;"></i>
            My Conversations
        </h1>
        <p style="color: #666; font-size: 1.1rem;">
            Manage your conversations and get support from our team
        </p>
    </div>

    <!-- Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding: 1.5rem; background: white; border-radius: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div>
            <h2 style="font-size: 1.2rem; font-weight: 600; color: #333; margin-bottom: 0.5rem;">
                Your Support Conversations
            </h2>
            <div style="display: flex; gap: 1.5rem; color: #666; font-size: 0.9rem;">
                <span>
                    <i class="fas fa-comments" style="margin-right: 0.5rem;"></i>
                    {{ $conversations && method_exists($conversations, 'total') ? $conversations->total() : ($conversations ? $conversations->count() : 0) }} Total
                </span>
                <span>
                    <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>
                    {{ $conversations && method_exists($conversations, 'where') ? $conversations->where('is_read_by_user', false)->count() : 0 }} Unread
                </span>
                <span>
                    <i class="fas fa-clock" style="margin-right: 0.5rem;"></i>
                    Active Support
                </span>
            </div>
        </div>
        
        <a href="{{ route('user.conversations.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i>
            Start New Conversation
        </a>
    </div>

    <!-- Conversations List -->
    @if($conversations && $conversations->count() > 0)
        <div style="display: grid; gap: 1.5rem;">
            @foreach($conversations as $conversation)
            <div class="card {{ !($conversation->is_read_by_user ?? true) ? 'unread-conversation' : '' }}" style="border: 1px solid #e5e7eb; border-radius: 1rem; overflow: hidden; transition: all 0.3s ease; position: relative;">
                
                @if(!($conversation->is_read_by_user ?? true))
                <div style="position: absolute; top: 1rem; right: 1rem; background: #0ea5e9; color: white; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.625rem; font-weight: 700; letter-spacing: 0.5px;">
                    NEW
                </div>
                @endif

                <div class="card-header" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem; font-weight: 700; color: #333; margin-bottom: 0.5rem;">
                        <i class="fas fa-comment-dots" style="color: #0ea5e9;"></i>
                        {{ $conversation->title ?? 'Untitled Conversation' }}
                    </div>
                    <div style="display: flex; align-items: center; gap: 1.5rem; color: #666; font-size: 0.875rem; flex-wrap: wrap;">
                        <span>
                            <i class="fas fa-calendar-alt" style="margin-right: 0.25rem;"></i>
                            Started {{ isset($conversation->created_at) ? $conversation->created_at->diffForHumans() : 'recently' }}
                        </span>
                        <span>
                            <i class="fas fa-clock" style="margin-right: 0.25rem;"></i>
                            Updated {{ isset($conversation->updated_at) ? $conversation->updated_at->diffForHumans() : 'recently' }}
                        </span>
                        <span>
                            <i class="fas fa-comment" style="margin-right: 0.25rem;"></i>
                            {{ isset($conversation->messages) ? $conversation->messages->count() : 0 }} messages
                        </span>
                    </div>
                </div>

                @if(isset($conversation->lastMessage) && $conversation->lastMessage)
                <div class="card-body" style="padding: 1.5rem; color: #666; font-size: 0.875rem; line-height: 1.6; border-bottom: 1px solid #e5e7eb;">
                    <strong>{{ $conversation->lastMessage->is_from_admin ? 'Support Team' : 'You' }}:</strong>
                    <span style="font-style: italic;">"{{ Str::limit($conversation->lastMessage->message, 120) }}"</span>
                </div>
                @endif

                <div style="padding: 1rem 1.5rem; background: #f8f9fa; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: #666;">
                        @if($conversation->is_read_by_user ?? true)
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e;"></div>
                            <span>Read</span>
                        @else
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: #0ea5e9;"></div>
                            <span>New messages available</span>
                        @endif
                    </div>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <a href="{{ route('user.conversations.show', $conversation->id ?? 1) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            View Conversation
                        </a>
                        @if(!($conversation->is_read_by_user ?? true))
                        <span style="background: #0ea5e9; color: white; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500;">
                            New Reply
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($conversations, 'links'))
        <div style="display: flex; justify-content: center; margin-top: 2rem;">
            {{ $conversations->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div style="text-align: center; padding: 3rem 1rem; background: white; border-radius: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="font-size: 4rem; color: #ccc; margin-bottom: 1.5rem;">
                <i class="fas fa-comments"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 1rem;">
                No Conversations Yet
            </h3>
            <p style="color: #666; margin-bottom: 2rem; max-width: 400px; margin-left: auto; margin-right: auto;">
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

<style>
/* Simple hover effects */
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

/* Unread conversation styling */
.unread-conversation {
    border-left: 4px solid #0ea5e9 !important;
    background: linear-gradient(90deg, rgba(14, 165, 233, 0.02), white) !important;
}

@media (max-width: 768px) {
    .container {
        padding: 1rem 0.5rem !important;
    }
    
    div[style*="display: flex"] {
        flex-direction: column !important;
        gap: 1rem !important;
        text-align: center !important;
    }
    
    div[style*="gap: 1.5rem"] {
        flex-wrap: wrap !important;
        justify-content: center !important;
    }
}
</style>
@endsection

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