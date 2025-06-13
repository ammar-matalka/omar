@extends('layouts.app')

@section('title', $conversation->title)

@push('styles')
<style>
.conversation-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: var(--space-xl) var(--space-md);
}

.conversation-wrapper {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: var(--space-xl);
}

.conversation-main {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 150px);
    min-height: 600px;
}

.conversation-header {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: var(--space-xl);
    margin-bottom: var(--space-lg);
    box-shadow: var(--shadow-sm);
}

.conversation-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--on-surface);
    margin-bottom: var(--space-md);
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

.messages-container {
    flex: 1;
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.messages-header {
    padding: var(--space-lg);
    border-bottom: 1px solid var(--border-color);
    background: var(--surface-variant);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.messages-list {
    flex: 1;
    padding: var(--space-lg);
    overflow-y: auto;
    background: linear-gradient(180deg, var(--surface) 0%, var(--surface-variant) 100%);
}

.message-item {
    margin-bottom: var(--space-xl);
    display: flex;
    gap: var(--space-md);
    animation: fadeInMessage 0.3s ease-out;
}

.message-item.user {
    flex-direction: row-reverse;
}

@keyframes fadeInMessage {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    flex-shrink: 0;
    box-shadow: var(--shadow-md);
}

.message-avatar.user {
    background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
}

.message-avatar.admin {
    background: linear-gradient(135deg, var(--success-500), var(--success-600));
}

.message-content {
    flex: 1;
    max-width: 75%;
}

.message-sender {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--on-surface-variant);
    margin-bottom: var(--space-xs);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.message-item.user .message-sender {
    justify-content: flex-end;
}

.sender-badge {
    padding: 2px 6px;
    border-radius: var(--radius-sm);
    font-size: 0.625rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sender-badge.admin {
    background: var(--success-100);
    color: var(--success-700);
}

.sender-badge.user {
    background: var(--primary-100);
    color: var(--primary-700);
}

.message-bubble {
    padding: var(--space-md) var(--space-lg);
    border-radius: var(--radius-xl);
    margin-bottom: var(--space-xs);
    word-wrap: break-word;
    line-height: 1.6;
    position: relative;
    box-shadow: var(--shadow-sm);
}

.message-bubble.user {
    background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
    color: white;
    border-bottom-right-radius: var(--radius-sm);
}

.message-bubble.admin {
    background: var(--surface);
    color: var(--on-surface);
    border: 1px solid var(--border-color);
    border-bottom-left-radius: var(--radius-sm);
}

.message-time {
    font-size: 0.75rem;
    color: var(--on-surface-variant);
    margin-left: var(--space-sm);
    display: flex;
    align-items: center;
    gap: var(--space-xs);
}

.message-item.user .message-time {
    justify-content: flex-end;
    margin-left: 0;
    margin-right: var(--space-sm);
    color: rgba(255, 255, 255, 0.8);
}

.reply-form {
    border-top: 1px solid var(--border-color);
    padding: var(--space-lg);
    background: var(--surface);
}

.reply-input-group {
    display: flex;
    gap: var(--space-md);
    align-items: flex-end;
}

.reply-textarea {
    flex: 1;
    min-height: 60px;
    max-height: 120px;
    padding: var(--space-md);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-lg);
    background: var(--surface);
    color: var(--on-surface);
    font-size: 0.875rem;
    font-family: inherit;
    resize: none;
    transition: all var(--transition-fast);
}

.reply-textarea:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

.reply-textarea::placeholder {
    color: var(--on-surface-variant);
}

.send-button {
    padding: var(--space-md);
    background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: all var(--transition-fast);
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 50px;
    height: 50px;
    box-shadow: var(--shadow-md);
}

.send-button:hover {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

.send-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.reply-help {
    margin-top: var(--space-sm);
    font-size: 0.75rem;
    color: var(--on-surface-variant);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sidebar-info {
    display: flex;
    flex-direction: column;
    gap: var(--space-lg);
}

.info-card {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.info-card-header {
    padding: var(--space-md) var(--space-lg);
    background: var(--surface-variant);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    color: var(--on-surface);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.info-card-body {
    padding: var(--space-lg);
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-sm) 0;
    border-bottom: 1px solid var(--border-color);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 500;
    color: var(--on-surface-variant);
    font-size: 0.875rem;
}

.info-value {
    color: var(--on-surface);
    font-size: 0.875rem;
    font-weight: 500;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-dot.active {
    background: var(--success-500);
    animation: pulse 2s infinite;
}

.status-dot.read {
    background: var(--primary-500);
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-sm);
}

.action-button {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    padding: var(--space-sm) var(--space-md);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background: var(--surface);
    color: var(--on-surface);
    text-decoration: none;
    font-size: 0.875rem;
    transition: all var(--transition-fast);
}

.action-button:hover {
    background: var(--surface-variant);
    border-color: var(--primary-300);
    transform: translateY(-1px);
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: var(--space-sm);
    color: var(--primary-600);
    text-decoration: none;
    font-weight: 500;
    margin-bottom: var(--space-lg);
    transition: color var(--transition-fast);
}

.back-link:hover {
    color: var(--primary-700);
}

@media (max-width: 768px) {
    .conversation-container {
        padding: var(--space-md) var(--space-sm);
    }
    
    .conversation-wrapper {
        grid-template-columns: 1fr;
    }
    
    .sidebar-info {
        order: -1;
    }
    
    .conversation-main {
        height: calc(100vh - 300px);
        min-height: 400px;
    }
    
    .message-content {
        max-width: 85%;
    }
    
    .conversation-meta {
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .reply-input-group {
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .send-button {
        align-self: flex-end;
        min-width: 100px;
    }
}
</style>
@endpush

@section('content')
<div class="conversation-container">
    <!-- Back Link -->
    <a href="{{ route('user.conversations.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Back to Conversations
    </a>

    <div class="conversation-wrapper">
        <div class="conversation-main">
            <!-- Conversation Header -->
            <div class="conversation-header">
                <div class="conversation-title">
                    <i class="fas fa-comment-dots" style="color: var(--primary-500);"></i>
                    {{ $conversation->title }}
                </div>
                <div class="conversation-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Started {{ $conversation->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>Last updated {{ $conversation->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-comment"></i>
                        <span>{{ $messages->count() }} messages</span>
                    </div>
                </div>
            </div>

            <!-- Messages Container -->
            <div class="messages-container">
                <div class="messages-header">
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 600; display: flex; align-items: center; gap: var(--space-sm);">
                        <i class="fas fa-comments"></i>
                        Conversation with Support Team
                    </h3>
                    <button class="btn btn-secondary btn-sm" onclick="scrollToBottom()">
                        <i class="fas fa-arrow-down"></i>
                        Latest
                    </button>
                </div>

                <div class="messages-list" id="messagesList">
                    @foreach($messages as $message)
                    <div class="message-item {{ $message->is_from_admin ? 'admin' : 'user' }}">
                        <div class="message-avatar {{ $message->is_from_admin ? 'admin' : 'user' }}">
                            @if($message->is_from_admin)
                                <i class="fas fa-headset"></i>
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="message-content">
                            <div class="message-sender">
                                @if($message->is_from_admin)
                                    <span class="sender-badge admin">Support Team</span>
                                @else
                                    <span class="sender-badge user">You</span>
                                @endif
                                <span class="message-time">
                                    <i class="fas fa-clock"></i>
                                    {{ $message->created_at->format('M d, h:i A') }}
                                </span>
                            </div>
                            <div class="message-bubble {{ $message->is_from_admin ? 'admin' : 'user' }}">
                                {{ $message->message }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Reply Form -->
                <form action="{{ route('user.conversations.reply', $conversation) }}" method="POST" class="reply-form">
                    @csrf
                    <div class="reply-input-group">
                        <textarea 
                            name="message" 
                            class="reply-textarea" 
                            placeholder="Type your message here..."
                            required
                            onkeydown="handleKeyDown(event)"
                            oninput="autoResize(this)"
                        ></textarea>
                        <button type="submit" class="send-button">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <div class="reply-help">
                        <span>
                            <i class="fas fa-info-circle"></i>
                            Press Ctrl+Enter to send quickly
                        </span>
                        <span id="typingIndicator" style="display: none; color: var(--primary-500);">
                            <i class="fas fa-pencil-alt"></i>
                            Typing...
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="sidebar-info">
            <!-- Conversation Status -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-info-circle"></i>
                    Status
                </div>
                <div class="info-card-body">
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <div class="status-indicator">
                                <div class="status-dot active"></div>
                                Active
                            </div>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Messages:</span>
                        <span class="info-value">{{ $messages->count() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Created:</span>
                        <span class="info-value">{{ $conversation->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last Reply:</span>
                        <span class="info-value">{{ $conversation->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </div>
                <div class="info-card-body">
                    <div class="quick-actions">
                        <a href="{{ route('user.conversations.index') }}" class="action-button">
                            <i class="fas fa-list"></i>
                            View All@extends('layouts.app')

@section('title', $conversation->title)

@push('styles')
<style>
.conversation-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: var(--space-xl) var(--space-md);
}

.conversation-wrapper {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: var(--space-xl);
}

.conversation-main {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 150px);
    min-height: 600px;
}

.conversation-header {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: var(--space-xl);
    margin-bottom: var(--space-lg);
    box-shadow: var(--shadow-sm);
}

.conversation-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--on-surface);
    margin-bottom: var(--space-md);
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

.messages-container {
    flex: 1;
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.messages-header {
    padding: var(--space-lg);
    border-bottom: 1px solid var(--border-color);
    background: var(--surface-variant);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.messages-list {
    flex: 1;
    padding: var(--space-lg);
    overflow-y: auto;
    background: linear-gradient(180deg, var(--surface) 0%, var(--surface-variant) 100%);
}

.message-item {
    margin-bottom: var(--space-xl);
    display: flex;
    gap: var(--space-md);
    animation: fadeInMessage 0.3s ease-out;
}

.message-item.user {
    flex-direction: row-reverse;
}

@keyframes fadeInMessage {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    flex-shrink: 0;
    box-shadow: var(--shadow-md);
}

.message-avatar.user {
    background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
}

.message-avatar.admin {
    background: linear-gradient(135deg, var(--success-500), var(--success-600));
}

.message-content {
    flex: 1;
    max-width: 75%;
}

.message-sender {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--on-surface-variant);
    margin-bottom: var(--space-xs);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.message-item.user .message-sender {
    justify-content: flex-end;
}

.sender-badge {
    padding: 2px 6px;
    border-radius: var(--radius-sm);
    font-size: 0.625rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sender-badge.admin {
    background: var(--success-100);
    color: var(--success-700);
}

.sender-badge.user {
    background: var(--primary-100);
    color: var(--primary-700);
}

.message-bubble {
    padding: var(--space-md) var(--space-lg);
    border-radius: var(--radius-xl);
    margin-bottom: var(--space-xs);
    word-wrap: break-word;
    line-height: 1.6;
    position: relative;
    box-shadow: var(--shadow-sm);
}

.message-bubble.user {
    background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
    color: white;
    border-bottom-right-radius: var(--radius-sm);
}

.message-bubble.admin {
    background: var(--surface);
    color: var(--on-surface);
    border: 1px solid var(--border-color);
    border-bottom-left-radius: var(--radius-sm);
}

.message-time {
    font-size: 0.75rem;
    color: var(--on-surface-variant);
    margin-left: var(--space-sm);
    display: flex;
    align-items: center;
    gap: var(--space-xs);
}

.message-item.user .message-time {
    justify-content: flex-end;
    margin-left: 0;
    margin-right: var(--space-sm);
    color: rgba(255, 255, 255, 0.8);
}

.reply-form {
    border-top: 1px solid var(--border-color);
    padding: var(--space-lg);
    background: var(--surface);
}

.reply-input-group {
    display: flex;
    gap: var(--space-md);
    align-items: flex-end;
}

.reply-textarea {
    flex: 1;
    min-height: 60px;
    max-height: 120px;
    padding: var(--space-md);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-lg);
    background: var(--surface);
    color: var(--on-surface);
    font-size: 0.875rem;
    font-family: inherit;
    resize: none;
    transition: all var(--transition-fast);
}

.reply-textarea:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

.reply-textarea::placeholder {
    color: var(--on-surface-variant);
}

.send-button {
    padding: var(--space-md);
    background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: all var(--transition-fast);
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 50px;
    height: 50px;
    box-shadow: var(--shadow-md);
}

.send-button:hover {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

.send-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.reply-help {
    margin-top: var(--space-sm);
    font-size: 0.75rem;
    color: var(--on-surface-variant);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sidebar-info {
    display: flex;
    flex-direction: column;
    gap: var(--space-lg);
}

.info-card {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.info-card-header {
    padding: var(--space-md) var(--space-lg);
    background: var(--surface-variant);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    color: var(--on-surface);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.info-card-body {
    padding: var(--space-lg);
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-sm) 0;
    border-bottom: 1px solid var(--border-color);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 500;
    color: var(--on-surface-variant);
    font-size: 0.875rem;
}

.info-value {
    color: var(--on-surface);
    font-size: 0.875rem;
    font-weight: 500;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-dot.active {
    background: var(--success-500);
    animation: pulse 2s infinite;
}

.status-dot.read {
    background: var(--primary-500);
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-sm);
}

.action-button {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    padding: var(--space-sm) var(--space-md);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background: var(--surface);
    color: var(--on-surface);
    text-decoration: none;
    font-size: 0.875rem;
    transition: all var(--transition-fast);
}

.action-button:hover {
    background: var(--surface-variant);
    border-color: var(--primary-300);
    transform: translateY(-1px);
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: var(--space-sm);
    color: var(--primary-600);
    text-decoration: none;
    font-weight: 500;
    margin-bottom: var(--space-lg);
    transition: color var(--transition-fast);
}

.back-link:hover {
    color: var(--primary-700);
}

@media (max-width: 768px) {
    .conversation-container {
        padding: var(--space-md) var(--space-sm);
    }
    
    .conversation-wrapper {
        grid-template-columns: 1fr;
    }
    
    .sidebar-info {
        order: -1;
    }
    
    .conversation-main {
        height: calc(100vh - 300px);
        min-height: 400px;
    }
    
    .message-content {
        max-width: 85%;
    }
    
    .conversation-meta {
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .reply-input-group {
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .send-button {
        align-self: flex-end;
        min-width: 100px;
    }
}
</style>
@endpush

@section('content')
<div class="conversation-container">
    <!-- Back Link -->
    <a href="{{ route('user.conversations.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Back to Conversations
    </a>

    <div class="conversation-wrapper">
        <div class="conversation-main">
            <!-- Conversation Header -->
            <div class="conversation-header">
                <div class="conversation-title">
                    <i class="fas fa-comment-dots" style="color: var(--primary-500);"></i>
                    {{ $conversation->title }}
                </div>
                <div class="conversation-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Started {{ $conversation->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>Last updated {{ $conversation->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-comment"></i>
                        <span>{{ $messages->count() }} messages</span>
                    </div>
                </div>
            </div>

            <!-- Messages Container -->
            <div class="messages-container">
                <div class="messages-header">
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 600; display: flex; align-items: center; gap: var(--space-sm);">
                        <i class="fas fa-comments"></i>
                        Conversation with Support Team
                    </h3>
                    <button class="btn btn-secondary btn-sm" onclick="scrollToBottom()">
                        <i class="fas fa-arrow-down"></i>
                        Latest
                    </button>
                </div>

                <div class="messages-list" id="messagesList">
                    @foreach($messages as $message)
                    <div class="message-item {{ $message->is_from_admin ? 'admin' : 'user' }}">
                        <div class="message-avatar {{ $message->is_from_admin ? 'admin' : 'user' }}">
                            @if($message->is_from_admin)
                                <i class="fas fa-headset"></i>
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="message-content">
                            <div class="message-sender">
                                @if($message->is_from_admin)
                                    <span class="sender-badge admin">Support Team</span>
                                @else
                                    <span class="sender-badge user">You</span>
                                @endif
                                <span class="message-time">
                                    <i class="fas fa-clock"></i>
                                    {{ $message->created_at->format('M d, h:i A') }}
                                </span>
                            </div>
                            <div class="message-bubble {{ $message->is_from_admin ? 'admin' : 'user' }}">
                                {{ $message->message }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Reply Form -->
                <form action="{{ route('user.conversations.reply', $conversation) }}" method="POST" class="reply-form">
                    @csrf
                    <div class="reply-input-group">
                        <textarea 
                            name="message" 
                            class="reply-textarea" 
                            placeholder="Type your message here..."
                            required
                            onkeydown="handleKeyDown(event)"
                            oninput="autoResize(this)"
                        ></textarea>
                        <button type="submit" class="send-button">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <div class="reply-help">
                        <span>
                            <i class="fas fa-info-circle"></i>
                            Press Ctrl+Enter to send quickly
                        </span>
                        <span id="typingIndicator" style="display: none; color: var(--primary-500);">
                            <i class="fas fa-pencil-alt"></i>
                            Typing...
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="sidebar-info">
            <!-- Conversation Status -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-info-circle"></i>
                    Status
                </div>
                <div class="info-card-body">
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <div class="status-indicator">
                                <div class="status-dot active"></div>
                                Active
                            </div>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Messages:</span>
                        <span class="info-value">{{ $messages->count() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Created:</span>
                        <span class="info-value">{{ $conversation->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last Reply:</span>
                        <span class="info-value">{{ $conversation->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </div>
                <div class="info-card-body">
                    <div class="quick-actions">
                        <a href="{{ route('user.conversations.index') }}" class="action-button">
                            <i class="fas fa-list"></i>
                            View All Conversations
                        </a>
                        <a href="{{ route('user.conversations.create') }}" class="action-button">
                            <i class="fas fa-plus"></i>
                            Start New Conversation
                        </a>
                        <a href="{{ route('user.profile.show') }}" class="action-button">
                            <i class="fas fa-user"></i>
                            My Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Support Info -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-question-circle"></i>
                    Support Info
                </div>
                <div class="info-card-body">
                    <p style="font-size: 0.875rem; color: var(--on-surface-variant); line-height: 1.6; margin-bottom: var(--space-md);">
                        Our support team typically responds within 24 hours during business days.
                    </p>
                    <div class="info-item">
                        <span class="info-label">Response Time:</span>
                        <span class="info-value">< 24 hours</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Business Hours:</span>
                        <span class="info-value">9 AM - 6 PM</span>
                    </div>
                </div>
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

function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

function handleKeyDown(event) {
    // Send message with Ctrl+Enter
    if (event.ctrlKey && event.key === 'Enter') {
        event.preventDefault();
        event.target.closest('form').submit();
    }
}

// Auto-scroll to bottom on page load
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    
    // Focus on the reply textarea
    const textarea = document.querySelector('.reply-textarea');
    if (textarea) {
        textarea.focus();
    }
});

// Auto-refresh for new messages every 20 seconds
setInterval(function() {
    // Check for new messages (you can implement AJAX here)
    // For now, we'll just show a subtle indicator
    const currentMessageCount = {{ $messages->count() }};
    
    // You can implement AJAX call here to check for new messages
    // and update the conversation in real-time
}, 20000);

// Typing indicator (placeholder for real-time features)
let typingTimer;
const textarea = document.querySelector('.reply-textarea');
const typingIndicator = document.getElementById('typingIndicator');

if (textarea) {
    textarea.addEventListener('input', function() {
        // Show typing indicator
        typingIndicator.style.display = 'block';
        
        // Clear existing timer
        clearTimeout(typingTimer);
        
        // Hide indicator after 2 seconds of no typing
        typingTimer = setTimeout(function() {
            typingIndicator.style.display = 'none';
        }, 2000);
    });
}
</script>

@endpush
