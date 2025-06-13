@extends('layouts.admin')

@section('title', 'Conversations')
@section('page-title', 'Conversations')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">Dashboard</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
    Conversations
</div>
@endsection

@push('styles')
<style>
.conversations-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-xl);
}

.conversation-card {
    background: white;
    border: 1px solid var(--admin-secondary-200);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-md);
    overflow: hidden;
    transition: all var(--transition-normal);
}

.conversation-card:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--admin-primary-300);
}

.conversation-card.unread {
    border-left: 4px solid var(--admin-primary-500);
    background: linear-gradient(90deg, rgba(59, 130, 246, 0.02), white);
}

.conversation-header {
    padding: var(--space-lg);
    border-bottom: 1px solid var(--admin-secondary-100);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.conversation-info {
    flex: 1;
}

.conversation-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--admin-secondary-900);
    margin-bottom: var(--space-xs);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.conversation-meta {
    display: flex;
    align-items: center;
    gap: var(--space-lg);
    color: var(--admin-secondary-600);
    font-size: 0.875rem;
}

.conversation-user {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.75rem;
}

.conversation-status {
    display: flex;
    align-items: center;
    gap: var(--space-md);
}

.status-badge {
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.unread {
    background: var(--error-100);
    color: var(--error-700);
}

.status-badge.read {
    background: var(--success-100);
    color: var(--success-700);
}

.conversation-preview {
    padding: var(--space-lg);
    color: var(--admin-secondary-600);
    font-size: 0.875rem;
    line-height: 1.5;
}

.conversation-actions {
    padding: var(--space-md) var(--space-lg);
    background: var(--admin-secondary-50);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.action-buttons {
    display: flex;
    gap: var(--space-sm);
}

.empty-state {
    text-align: center;
    padding: var(--space-3xl) var(--space-lg);
    color: var(--admin-secondary-500);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: var(--space-lg);
    opacity: 0.5;
}

.bulk-actions {
    background: white;
    border: 1px solid var(--admin-secondary-200);
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    margin-bottom: var(--space-xl);
    display: none;
}

.bulk-actions.visible {
    display: block;
}
</style>
@endpush

@section('content')
<div class="conversations-header">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm);">
            Conversations Management
        </h2>
        <p style="color: var(--admin-secondary-600); font-size: 0.875rem;">
            Manage customer inquiries and support conversations
        </p>
    </div>
    
    <div style="display: flex; gap: var(--space-md);">
        @if($conversations->where('is_read_by_admin', false)->count() > 0)
        <form action="{{ route('admin.conversations.mark-all-read') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check-double"></i>
                Mark All as Read ({{ $conversations->where('is_read_by_admin', false)->count() }})
            </button>
        </form>
        @endif
        
        <button class="btn btn-secondary" onclick="refreshConversations()">
            <i class="fas fa-sync-alt"></i>
            Refresh
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-2xl);">
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <div style="font-size: 2rem; color: var(--admin-primary-500); margin-bottom: var(--space-sm);">
                <i class="fas fa-comments"></i>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--admin-secondary-900);">
                {{ $conversations->total() }}
            </div>
            <div style="color: var(--admin-secondary-600); font-size: 0.875rem;">
                Total Conversations
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <div style="font-size: 2rem; color: var(--error-500); margin-bottom: var(--space-sm);">
                <i class="fas fa-envelope"></i>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--admin-secondary-900);">
                {{ $conversations->where('is_read_by_admin', false)->count() }}
            </div>
            <div style="color: var(--admin-secondary-600); font-size: 0.875rem;">
                Unread Messages
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <div style="font-size: 2rem; color: var(--success-500); margin-bottom: var(--space-sm);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--admin-secondary-900);">
                {{ $conversations->where('is_read_by_admin', true)->count() }}
            </div>
            <div style="color: var(--admin-secondary-600); font-size: 0.875rem;">
                Resolved
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <div style="font-size: 2rem; color: var(--info-500); margin-bottom: var(--space-sm);">
                <i class="fas fa-users"></i>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--admin-secondary-900);">
                {{ $conversations->unique('user_id')->count() }}
            </div>
            <div style="color: var(--admin-secondary-600); font-size: 0.875rem;">
                Active Users
            </div>
        </div>
    </div>
</div>

<!-- Conversations List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            All Conversations
        </h3>
    </div>
    
    <div class="card-body" style="padding: 0;">
        @if($conversations->count() > 0)
            @foreach($conversations as $conversation)
            <div class="conversation-card {{ !$conversation->is_read_by_admin ? 'unread' : '' }}">
                <div class="conversation-header">
                    <div class="conversation-info">
                        <div class="conversation-title">
                            {{ $conversation->title }}
                            @if(!$conversation->is_read_by_admin)
                                <span class="status-badge unread">New</span>
                            @endif
                        </div>
                        <div class="conversation-meta">
                            <div class="conversation-user">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                                </div>
                                <span>{{ $conversation->user->name }}</span>
                            </div>
                            <span>
                                <i class="fas fa-clock"></i>
                                {{ $conversation->updated_at->diffForHumans() }}
                            </span>
                            <span>
                                <i class="fas fa-envelope"></i>
                                {{ $conversation->messages->count() }} messages
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($conversation->lastMessage)
                <div class="conversation-preview">
                    <strong>{{ $conversation->lastMessage->is_from_admin ? 'Admin' : $conversation->user->name }}:</strong>
                    {{ Str::limit($conversation->lastMessage->message, 150) }}
                </div>
                @endif
                
                <div class="conversation-actions">
                    <div style="color: var(--admin-secondary-500); font-size: 0.75rem;">
                        Last updated: {{ $conversation->updated_at->format('M d, Y at h:i A') }}
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('admin.conversations.show', $conversation) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i>
                            View Conversation
                        </a>
                        @if(!$conversation->is_read_by_admin)
                        <form action="{{ route('admin.conversations.mark-read', $conversation) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-secondary btn-sm">
                                <i class="fas fa-check"></i>
                                Mark as Read
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            
            <!-- Pagination -->
            <div style="padding: var(--space-lg);">
                {{ $conversations->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--admin-secondary-700); margin-bottom: var(--space-md);">
                    No Conversations Yet
                </h3>
                <p style="color: var(--admin-secondary-500); margin-bottom: var(--space-lg);">
                    Customer conversations will appear here when they start contacting support.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function refreshConversations() {
    window.location.reload();
}

// Auto-refresh every 30 seconds for new messages
setInterval(function() {
    // Optional: Check for new messages via AJAX
    fetch('{{ route("admin.conversations.check-new") }}')
        .then(response => response.json())
        .then(data => {
            if (data.hasNew) {
                // Show notification or refresh
                const notification = document.createElement('div');
                notification.className = 'alert alert-info';
                notification.innerHTML = '<i class="fas fa-info-circle"></i> New messages received. <a href="#" onclick="window.location.reload()">Refresh page</a>';
                document.querySelector('.content-area').insertBefore(notification, document.querySelector('.conversations-header'));
            }
        })
        .catch(error => console.log('Error checking for new messages:', error));
}, 30000);
</script>
@endpush