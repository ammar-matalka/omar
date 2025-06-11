@extends('layouts.app')

@section('title', $conversation->title . ' - ' . config('app.name'))

@push('styles')
<style>
    .conversation-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .conversation-hero::before {
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
    }
    
    .conversation-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .conversation-info {
        flex: 1;
    }
    
    .conversation-title {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        line-height: 1.2;
    }
    
    .conversation-meta {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        font-size: 0.875rem;
        opacity: 0.9;
        flex-wrap: wrap;
        margin-bottom: var(--space-md);
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        font-size: 0.75rem;
        opacity: 0.8;
        flex-wrap: wrap;
    }
    
    .breadcrumb-link {
        color: white;
        text-decoration: none;
        transition: opacity var(--transition-fast);
    }
    
    .breadcrumb-link:hover {
        opacity: 0.9;
        text-decoration: underline;
    }
    
    .breadcrumb-separator {
        opacity: 0.6;
    }
    
    .conversation-status {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: var(--space-sm);
    }
    
    .status-badge {
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-xl);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.1);
    }
    
    .conversation-actions {
        display: flex;
        gap: var(--space-sm);
        flex-wrap: wrap;
    }
    
    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-md);
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: var(--radius-md);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all var(--transition-fast);
    }
    
    .hero-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-1px);
    }
    
    .conversation-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
        min-height: 60vh;
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-lg);
        background: var(--surface);
        color: var(--primary-600);
        border: 2px solid var(--primary-200);
        border-radius: var(--radius-lg);
        text-decoration: none;
        font-weight: 600;
        transition: all var(--transition-fast);
        margin-bottom: var(--space-xl);
    }
    
    .back-button:hover {
        background: var(--primary-50);
        border-color: var(--primary-300);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .conversation-content {
        max-width: 800px;
        margin: 0 auto;
        display: grid;
        gap: var(--space-xl);
    }
    
    .messages-container {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    
    .messages-header {
        background: linear-gradient(135deg, var(--primary-50), var(--secondary-50));
        border-bottom: 1px solid var(--border-color);
        padding: var(--space-lg);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .messages-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .messages-count {
        color: var(--on-surface-variant);
        font-size: 0.875rem;
    }
    
    .messages-list {
        max-height: 500px;
        overflow-y: auto;
        padding: var(--space-lg);
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .message {
        display: flex;
        gap: var(--space-md);
        animation: messageSlideIn 0.3s ease-out;
    }
    
    .message.from-admin {
        flex-direction: row;
    }
    
    .message.from-user {
        flex-direction: row-reverse;
    }
    
    @keyframes messageSlideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
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
        font-size: 1rem;
        font-weight: 600;
        flex-shrink: 0;
        color: white;
        position: relative;
    }
    
    .message.from-admin .message-avatar {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
    }
    
    .message.from-user .message-avatar {
        background: linear-gradient(135deg, var(--secondary-500), var(--secondary-600));
    }
    
    .message-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    
    .admin-badge {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 16px;
        height: 16px;
        background: var(--success-500);
        border: 2px solid var(--surface);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
        color: white;
    }
    
    .message-content {
        flex: 1;
        max-width: 70%;
    }
    
    .message-bubble {
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        position: relative;
        word-wrap: break-word;
        line-height: 1.5;
        cursor: pointer;
        transition: transform var(--transition-fast);
    }
    
    .message-bubble:hover {
        transform: scale(1.02);
    }
    
    .message.from-admin .message-bubble {
        background: var(--surface-variant);
        color: var(--on-surface);
        border-bottom-left-radius: var(--radius-sm);
    }
    
    .message.from-user .message-bubble {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border-bottom-right-radius: var(--radius-sm);
    }
    
    .message-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: var(--space-xs);
        font-size: 0.75rem;
        color: var(--on-surface-variant);
    }
    
    .message.from-user .message-info {
        justify-content: flex-end;
    }
    
    .message-sender {
        font-weight: 500;
    }
    
    .message-time {
        opacity: 0.8;
    }
    
    .empty-messages {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--on-surface-variant);
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: var(--space-md);
        opacity: 0.5;
    }
    
    .reply-form {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }
    
    .reply-form::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
    }
    
    .reply-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: var(--space-lg);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .reply-textarea {
        width: 100%;
        min-height: 120px;
        padding: var(--space-md);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 1rem;
        font-family: inherit;
        resize: vertical;
        transition: all var(--transition-fast);
        line-height: 1.6;
    }
    
    .reply-textarea:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .reply-textarea.error {
        border-color: var(--error-500);
    }
    
    .reply-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: var(--space-lg);
        flex-wrap: wrap;
        gap: var(--space-md);
    }
    
    .character-count {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
    }
    
    .character-count.warning {
        color: var(--warning-600);
    }
    
    .character-count.error {
        color: var(--error-600);
    }
    
    .reply-buttons {
        display: flex;
        gap: var(--space-md);
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-xl);
        border: 2px solid transparent;
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .btn:focus {
        outline: 2px solid var(--primary-500);
        outline-offset: 2px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        box-shadow: var(--shadow-sm);
    }
    
    .btn-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }
    
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-secondary {
        background: var(--surface);
        color: var(--on-surface-variant);
        border-color: var(--border-color);
    }
    
    .btn-secondary:hover {
        background: var(--surface-variant);
        border-color: var(--border-hover);
        color: var(--on-surface);
    }
    
    .loading-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .conversation-closed {
        background: linear-gradient(135deg, var(--warning-50), var(--warning-100));
        border: 2px solid var(--warning-200);
        border-radius: var(--radius-xl);
        padding: var(--space-lg);
        text-align: center;
        color: var(--warning-700);
    }
    
    .closed-icon {
        font-size: 2rem;
        margin-bottom: var(--space-md);
    }
    
    .closed-title {
        font-weight: 600;
        margin-bottom: var(--space-sm);
    }
    
    .closed-text {
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .messages-list::-webkit-scrollbar {
        width: 6px;
    }
    
    .messages-list::-webkit-scrollbar-track {
        background: var(--surface-variant);
        border-radius: 3px;
    }
    
    .messages-list::-webkit-scrollbar-thumb {
        background: var(--primary-300);
        border-radius: 3px;
    }
    
    .messages-list::-webkit-scrollbar-thumb:hover {
        background: var(--primary-500);
    }
    
    @media (max-width: 768px) {
        .conversation-title {
            font-size: 1.5rem;
        }
        
        .conversation-header {
            flex-direction: column;
            gap: var(--space-md);
        }
        
        .conversation-status {
            align-items: flex-start;
        }
        
        .conversation-actions {
            width: 100%;
            justify-content: stretch;
        }
        
        .hero-btn {
            flex: 1;
            justify-content: center;
        }
        
        .message-content {
            max-width: 85%;
        }
        
        .conversation-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-sm);
        }
        
        .reply-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .reply-buttons {
            width: 100%;
        }
        
        .btn {
            flex: 1;
            justify-content: center;
        }
        
        .messages-list {
            max-height: 400px;
        }
    }
</style>
@endpush

@section('content')
<!-- Conversation Hero -->
<section class="conversation-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    {{ __('Home') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('user.profile.show') }}" class="breadcrumb-link">
                    {{ __('Profile') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('user.conversations.index') }}" class="breadcrumb-link">
                    {{ __('Messages') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ __('Conversation') }}</span>
            </nav>
            
            <div class="conversation-header">
                <div class="conversation-info">
                    <h1 class="conversation-title">{{ $conversation->title }}</h1>
                    
                    <div class="conversation-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            {{ __('Started') }} {{ $conversation->created_at->format('M d, Y') }}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            {{ __('Last updated') }} {{ $conversation->updated_at->diffForHumans() }}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-comment-dots"></i>
                            {{ $messages->count() }} {{ __('messages') }}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            {{ $conversation->user->name }}
                        </div>
                    </div>
                </div>
                
                <div class="conversation-status">
                    <span class="status-badge">
                        <i class="fas fa-{{ ($conversation->status ?? 'open') === 'open' ? 'envelope-open' : 'archive' }}"></i>
                        {{ ucfirst($conversation->status ?? 'open') }}
                    </span>
                    
                    <div class="conversation-actions">
                        <a href="{{ route('user.conversations.index') }}" class="hero-btn">
                            <i class="fas fa-list"></i>
                            {{ __('All Messages') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Conversation Container -->
<section class="conversation-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('user.conversations.index') }}" class="back-button fade-in">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Messages') }}
        </a>
        
        <div class="conversation-content">
            <!-- Messages Container -->
            <div class="messages-container fade-in">
                <div class="messages-header">
                    <div class="messages-title">
                        <i class="fas fa-comments"></i>
                        {{ __('Conversation') }}
                    </div>
                    <div class="messages-count">
                        {{ $messages->count() }} {{ __('messages') }}
                    </div>
                </div>
                
                <div class="messages-list" id="messages-list">
                    @if($messages->count() > 0)
                        @foreach($messages as $message)
                            <div class="message {{ $message->is_from_admin ? 'from-admin' : 'from-user' }}">
                                <div class="message-avatar">
                                    @if($message->is_from_admin)
                                        @if($message->user && $message->user->profile_image)
                                            <img src="{{ $message->user->profile_image_url }}" alt="Admin">
                                        @else
                                            <i class="fas fa-user-tie"></i>
                                        @endif
                                        <span class="admin-badge">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @else
                                        @if($conversation->user->profile_image)
                                            <img src="{{ $conversation->user->profile_image_url }}" alt="{{ $conversation->user->name }}">
                                        @else
                                            {{ $conversation->user->initials }}
                                        @endif
                                    @endif
                                </div>
                                
                                <div class="message-content">
                                    <div class="message-bubble">
                                        {{ $message->message }}
                                    </div>
                                    <div class="message-info">
                                        <span class="message-sender">
                                            {{ $message->is_from_admin ? __('Support Team') : $conversation->user->name }}
                                        </span>
                                        <span class="message-time">
                                            {{ $message->created_at->format('M d, Y h:i A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-messages">
                            <div class="empty-icon">
                                <i class="fas fa-comment-slash"></i>
                            </div>
                            <p>{{ __('No messages in this conversation yet.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Reply Form -->
            @if(($conversation->status ?? 'open') === 'open')
                <div class="reply-form fade-in">
                    <form action="{{ route('user.conversations.reply', $conversation) }}" method="POST" id="reply-form">
                        @csrf
                        
                        <h3 class="reply-title">
                            <i class="fas fa-reply"></i>
                            {{ __('Send Reply') }}
                        </h3>
                        
                        <textarea 
                            name="message" 
                            id="reply-message"
                            class="reply-textarea {{ $errors->has('message') ? 'error' : '' }}"
                            placeholder="{{ __('Type your reply here...') }}"
                            required
                            maxlength="2000"
                            oninput="updateCharacterCount()"
                        >{{ old('message') }}</textarea>
                        
                        @error('message')
                            <div style="margin-top: var(--space-sm); font-size: 0.75rem; color: var(--error-600);">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <div class="reply-actions">
                            <div class="character-count" id="character-count">0 / 2000</div>
                            
                            <div class="reply-buttons">
                                <button type="button" class="btn btn-secondary" onclick="clearMessage()">
                                    <i class="fas fa-eraser"></i>
                                    {{ __('Clear') }}
                                </button>
                                <button type="submit" class="btn btn-primary" id="reply-btn">
                                    <i class="fas fa-paper-plane"></i>
                                    {{ __('Send Reply') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <!-- Conversation Closed -->
                <div class="conversation-closed fade-in">
                    <div class="closed-icon">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div class="closed-title">{{ __('Conversation Closed') }}</div>
                    <div class="closed-text">
                        {{ __('This conversation has been closed. If you need further assistance, please start a new conversation.') }}
                    </div>
                </div>
            @endif
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
    
    // Character count functionality
    function updateCharacterCount() {
        const textarea = document.getElementById('reply-message');
        const counter = document.getElementById('character-count');
        
        if (textarea && counter) {
            const currentLength = textarea.value.length;
            const maxLength = 2000;
            
            counter.textContent = `${currentLength} / ${maxLength}`;
            
            // Update counter color
            counter.classList.remove('warning', 'error');
            if (currentLength > maxLength * 0.9) {
                counter.classList.add('error');
            } else if (currentLength > maxLength * 0.75) {
                counter.classList.add('warning');
            }
            
            // Update button state
            const replyBtn = document.getElementById('reply-btn');
            if (replyBtn) {
                const isValid = currentLength > 0 && currentLength <= maxLength;
                replyBtn.disabled = !isValid;
                replyBtn.style.opacity = isValid ? '1' : '0.6';
            }
        }
    }
    
    // Clear message
    function clearMessage() {
        const textarea = document.getElementById('reply-message');
        if (textarea) {
            textarea.value = '';
            updateCharacterCount();
            textarea.focus();
        }
    }
    
    // Auto-resize textarea
    const textarea = document.getElementById('reply-message');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 200) + 'px';
            updateCharacterCount();
        });
    }
    
    // Form submission with loading state
    const replyForm = document.getElementById('reply-form');
    if (replyForm) {
        replyForm.addEventListener('submit', function(e) {
            const replyBtn = document.getElementById('reply-btn');
            const messageTextarea = document.getElementById('reply-message');
            
            if (messageTextarea && replyBtn) {
                const message = messageTextarea.value.trim();
                
                if (!message) {
                    e.preventDefault();
                    showNotification('{{ __("Please enter a message") }}', 'error');
                    return;
                }
                
                if (message.length > 2000) {
                    e.preventDefault();
                    showNotification('{{ __("Message is too long") }}', 'error');
                    return;
                }
                
                // Show loading state
                replyBtn.innerHTML = '<div class="loading-spinner"></div> {{ __("Sending...") }}';
                replyBtn.disabled = true;
            }
        });
    }
    
    // Scroll to bottom of messages
    function scrollToBottom() {
        const messagesList = document.getElementById('messages-list');
        if (messagesList) {
            messagesList.scrollTop = messagesList.scrollHeight;
        }
    }
    
    // Initialize features
    document.addEventListener('DOMContentLoaded', function() {
        updateCharacterCount();
        scrollToBottom();
        
        // Add click event to message bubbles for copying
        document.querySelectorAll('.message-bubble').forEach(bubble => {
            bubble.addEventListener('click', function() {
                const text = this.textContent;
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(text).then(() => {
                        showNotification('{{ __("Message copied to clipboard") }}', 'success');
                    });
                } else {
                    // Fallback for older browsers
                    const textarea = document.createElement('textarea');
                    textarea.value = text;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    showNotification('{{ __("Message copied to clipboard") }}', 'success');
                }
            });
        });
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Send with Ctrl+Enter
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            const replyBtn = document.getElementById('reply-btn');
            if (replyBtn && !replyBtn.disabled) {
                document.getElementById('reply-form').submit();
            }
        }
        
        // Focus textarea with Ctrl+R
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            const textarea = document.getElementById('reply-message');
            if (textarea) {
                textarea.focus();
            }
        }
    });
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
            padding: var(--space-md);
            background: var(--${type === 'success' ? 'success' : type === 'error' ? 'error' : 'info'}-100);
            color: var(--${type === 'success' ? 'success' : type === 'error' ? 'error' : 'info'}-700);
            border: 1px solid var(--${type === 'success' ? 'success' : type === 'error' ? 'error' : 'info'}-200);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            animation: slideIn 0.3s ease-out;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
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
</script>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
@endpush