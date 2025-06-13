@extends('layouts.admin')

@section('title', 'Conversation Details')
@section('page-title', 'Conversation Details')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">Dashboard</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('admin.conversations.index') }}" class="breadcrumb-link">Conversations</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
    {{ Str::limit($conversation->title, 30) }}
</div>
@endsection

@push('styles')
<style>
.conversation-container {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: var(--space-xl);
}

.conversation-main {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 200px);
}

.conversation-header {
    background: white;
    border: 1px solid var(--admin-secondary-200);
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    margin-bottom: var(--space-lg);
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

.conversation-meta {
    display: flex;
    align-items: center;
    gap: var(--space-lg);
    color: var(--admin-secondary-600);
    font-size: 0.875rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1rem;
}

.messages-container {
    flex: 1;
    background: white;
    border: 1px solid var(--admin-secondary-200);
    border-radius: var(--radius-lg);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.messages-header {
    padding: var(--space-lg);
    border-bottom: 1px solid var(--admin-secondary-200);
    background: var(--admin-secondary-50);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.messages-list {
    flex: 1;
    padding: var(--space-lg);
    overflow-y: auto;
    max-height: 400px;
}

.message-item {
    margin-bottom: var(--space-lg);
    display: flex;
    gap: var(--space-md);
}

.message-item.admin {
    flex-direction: row-reverse;
}

.message-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.message-avatar.user {
    background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
}

.message-avatar.admin {
    background: linear-gradient(135deg, var(--success-500), var(--success-600));
}

.message-content {
    flex: 1;
    max-width: 70%;
}

.message-bubble {
    padding: var(--space-md);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-xs);
    word-wrap: break-word;
}

.message-bubble.user {
    background: var(--admin-secondary-100);
    color: var(--admin-secondary-900);
    border-bottom-left-radius: var(--radius-sm);
}

.message-bubble.admin {
    background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
    color: white;
    border-bottom-right-radius: var(--radius-sm);
}

.message-time {
    font-size: 0.75rem;
    color: var(--admin-secondary-500);
    margin-left: var(--space-sm);
}

.message-item.admin .message-time {
    text-align: right;
    margin-left: 0;
    margin-right: var(--space-sm);
}

.reply-form {
    border-top: 1px solid var(--admin-secondary-200);
    padding: var(--space-lg);
    background: var(--admin-secondary-50);
}

.reply-textarea {
    width: 100%;
    min-height: 80px;
    padding: var(--space-md);
    border: 1px solid var(--admin-secondary-300);
    border-radius: var(--radius-md);
    resize: vertical;
    font-family: inherit;
    font-size: 0.875rem;
    transition: all var(--transition-fast);
}

.reply-textarea:focus {
    outline: none;
    border-color: var(--admin-primary-500);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.reply-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: var(--space-md);
}

.sidebar-info {
    display: flex;
    flex-direction: column;
    gap: var(--space-lg);
}

.info-card {
    background: white;
    border: 1px solid var(--admin-secondary-200);
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.info-card-header {
    padding: var(--space-md) var(--space-lg);
    background: var(--admin-secondary-50);
    border-bottom: 1px solid var(--admin-secondary-200);
    font-weight: 600;
    color: var(--admin-secondary-900);
}

.info-card-body {
    padding: var(--space-lg);
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-sm) 0;
    border-bottom: 1px solid var(--admin-secondary-100);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 500;
    color: var(--admin-secondary-700);
    font-size: 0.875rem;
}

.info-value {
    color: var(--admin-secondary-900);
    font-size: 0.875rem;
}

.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: var(--space-sm);
}

.status-indicator.read {
    background: var(--success-500);
}

.status-indicator.unread {
    background: var(--error-500);
}

@media (max-width: 768px) {
    .conversation-container {
        grid-template-columns: 1fr;
    }
    
    .sidebar-info {
        order: -1;
    }
    
    .message-content {
        max-width: 85%;
    }
}
</style>
@endpush

@section('content')
<div class="conversation-container">
    <div class="conversation-main">
        <!-- Conversation Header -->
        <div class="conversation-header">
            <div class="conversation-title">
                {{ $conversation->title }}
                @if(!$conversation->is_read_by_admin)
                    <span class="badge badge-danger">Unread</span>
                @else
                    <span class="badge badge-success">Read</span>
                @endif
            </div>
            <div class="conversation-meta">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 600;">{{ $conversation->user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--admin-secondary-500);">
                            {{ $conversation->user->email }}
                        </div>
                    </div>
                </div>
                <div>
                    <i class="fas fa-clock"></i>
                    Started {{ $conversation->created_at->diffForHumans() }}
                </div>
                <div>
                    <i class="fas fa-comment"></i>
                    {{ $messages->count() }} messages
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container">
            <div class="messages-header">
                <h3 style="margin: 0; font-size: 1rem; font-weight: 600;">
                    <i class="fas fa-comments"></i>
                    Conversation Messages
                </h3>
                <div>
                    <button class="btn btn-secondary btn-sm" onclick="scrollToBottom()">
                        <i class="fas fa-arrow-down"></i>
                        Scroll to Bottom
                    </button>
                </div>
            </div>

            <div class="messages-list" id="messagesList">
                @foreach($messages as $message)
                <div class="message-item {{ $message->is_from_admin ? 'admin' : 'user' }}">
                    <div class="message-avatar {{ $message->is_from_admin ? 'admin' : 'user' }}">
                        @if($message->is_from_admin)
                            A
                        @else
                            {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="message-content">
                        <div class="message-bubble {{ $message->is_from_admin ? 'admin' : 'user' }}">
                            {{ $message->message }}
                        </div>
                        <div class="message-time">
                            {{ $message->created_at->format('M d, Y at h:i A') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Reply Form -->
            <form action="{{ route('admin.conversations.reply', $conversation) }}" method="POST" class="reply-form">
                @csrf
                <div class="form-group" style="margin-bottom: var(--space-md);">
                    <textarea 
                        name="message" 
                        class="reply-textarea" 
                        placeholder="Type your reply here..."
                        required
                    ></textarea>
                </div>
                <div class="reply-actions">
                    <div style="font-size: 0.75rem; color: var(--admin-secondary-500);">
                        <i class="fas fa-info-circle"></i>
                        This will be sent to {{ $conversation->user->name }}
                    </div>
                    <div style="display: flex; gap: var(--space-sm);">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearReply()">
                            <i class="fas fa-times"></i>
                            Clear
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Send Reply
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="sidebar-info">
        <!-- User Information -->
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-user"></i>
                Customer Information
            </div>
            <div class="info-card-body">
                <div class="info-item">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $conversation->user->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $conversation->user->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Joined:</span>
                    <span class="info-value">{{ $conversation->user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Orders:</span>
                    <span class="info-value">{{ $conversation->user->orders->count() ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Conversation Status -->
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-info-circle"></i>
                Conversation Status
            </div>
            <div class="info-card-body">
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-indicator {{ $conversation->is_read_by_admin ? 'read' : 'unread' }}"></span>
                        {{ $conversation->is_read_by_admin ? 'Read' : 'Unread' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Created:</span>
                    <span class="info-value">{{ $conversation->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Updated:</span>
                    <span class="info-value">{{ $conversation->updated_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Messages:</span>
                    <span class="info-value">{{ $messages->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-bolt"></i>
                Quick Actions
            </div>
            <div class="info-card-body" style="display: flex; flex-direction: column; gap: var(--space-sm);">
                @if(!$conversation->is_read_by_admin)
                <form action="{{ route('admin.conversations.mark-read', $conversation) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm" style="width: 100%;">
                        <i class="fas fa-check"></i>
                        Mark as Read
                    </button>
                </form>
                @endif
                
                <a href="{{ route('admin.users.show', $conversation->user) }}" class="btn btn-secondary btn-sm" style="width: 100%;">
                    <i class="fas fa-user"></i>
                    View Customer Profile
                </a>
                
                <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary btn-sm" style="width: 100%;">
                    <i class="fas fa-arrow-left"></i>
                    Back to Conversations
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function scrollToBottom() {
    const messagesList = document.getElementById('messagesList');
    messagesList.scrollTop = messagesList.scrollHeight;
}

function clearReply() {
    document.querySelector('textarea[name="message"]').value = '';
}

// Auto-scroll to bottom on page load
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
});

// Auto-refresh for new messages every 15 seconds
setInterval(function() {
    // You can implement AJAX to check for new messages
    // For now, we'll just indicate that auto-refresh is available
}, 15000);
</script>
@endpush