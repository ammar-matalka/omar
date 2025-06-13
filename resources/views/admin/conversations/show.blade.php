@extends('layouts.admin')

@section('title', 'Conversation Details')
@section('page-title', 'Conversation with ' . $conversation->user->name)

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
/* تحسينات CSS للرسائل الفورية */
.message-sending {
    opacity: 0.7;
    pointer-events: none;
}

.new-message-indicator {
    position: fixed;
    bottom: 100px;
    right: 20px;
    background: var(--admin-primary-500);
    color: white;
    padding: var(--space-sm) var(--space-md);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    cursor: pointer;
    z-index: 1000;
    transform: translateY(100px);
    transition: transform 0.3s ease;
    display: none;
}

.new-message-indicator.show {
    display: block;
    transform: translateY(0);
}

.typing-indicator {
    display: none;
    padding: var(--space-sm) var(--space-md);
    background: var(--admin-secondary-100);
    border-radius: var(--radius-lg);
    margin: var(--space-sm) 0;
    font-style: italic;
    color: var(--admin-secondary-600);
}

.typing-indicator.show {
    display: block;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.pulse {
    animation: pulse 1s infinite;
}

.connection-status {
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: var(--space-xs);
}

.connection-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.connection-dot.connected {
    background: var(--success-500);
}

.connection-dot.disconnected {
    background: var(--error-500);
}

.unread-count {
    background: var(--error-500);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
}
</style>
@endpush

@section('content')
<div style="padding: var(--space-xl);">
    <!-- CSRF Token for JavaScript -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-2xl);">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm);">
                <i class="fas fa-comment-dots" style="color: var(--admin-primary-500); margin-right: var(--space-sm);"></i>
                {{ $conversation->title }}
                @if(!$conversation->is_read_by_admin)
                    <span class="badge badge-danger">Unread</span>
                @else
                    <span class="badge badge-success">Read</span>
                @endif
                <span class="unread-count" id="unreadCount" style="display: none;">0</span>
            </h1>
            <p style="color: var(--admin-secondary-600); font-size: 0.875rem;">
                Conversation with {{ $conversation->user->name }} • <span id="messageCount">{{ $messages->count() }}</span> messages
            </p>
        </div>
        
        <div style="display: flex; gap: var(--space-md);">
            <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Conversations
            </a>
            @if(!$conversation->is_read_by_admin)
            <form action="{{ route('admin.conversations.mark-read', $conversation) }}" method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i>
                    Mark as Read
                </button>
            </form>
            @endif
            <span id="connectionStatus" class="connection-status">
                <div class="connection-dot connected"></div>
                <span>Connected</span>
            </span>
        </div>
    </div>

    <!-- New Message Indicator -->
    <div id="newMessageIndicator" class="new-message-indicator" onclick="scrollToBottom()">
        <i class="fas fa-arrow-down"></i>
        رسائل جديدة
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: var(--space-xl);">
        <!-- Main Conversation Area -->
        <div style="display: flex; flex-direction: column; height: calc(100vh - 200px); min-height: 600px;">
            
            <!-- Messages Container -->
            <div class="card" style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-comments"></i>
                        Conversation Messages
                    </h3>
                    <button onclick="scrollToBottom()" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-down"></i>
                        Latest
                    </button>
                </div>
                
                <!-- Messages List -->
                <div id="messagesList" style="flex: 1; padding: var(--space-lg); overflow-y: auto; background: linear-gradient(180deg, white 0%, var(--admin-secondary-50) 100%);">
                    @foreach($messages as $message)
                    <div data-message-id="{{ $message->id }}" style="margin-bottom: var(--space-xl); display: flex; gap: var(--space-md); {{ $message->is_from_admin ? 'flex-direction: row-reverse;' : '' }}">
                        <!-- Avatar -->
                        <div style="width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1rem; flex-shrink: 0; box-shadow: var(--shadow-md); {{ $message->is_from_admin ? 'background: linear-gradient(135deg, var(--success-500), var(--success-600));' : 'background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));' }}">
                            @if($message->is_from_admin)
                                <i class="fas fa-user-shield"></i>
                            @else
                                {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                            @endif
                        </div>
                        
                        <!-- Message Content -->
                        <div style="flex: 1; max-width: 75%;">
                            <!-- Sender Info -->
                            <div style="font-size: 0.8rem; font-weight: 600; color: var(--admin-secondary-600); margin-bottom: var(--space-xs); display: flex; align-items: center; gap: var(--space-sm); {{ $message->is_from_admin ? 'justify-content: flex-end;' : '' }}">
                                @if($message->is_from_admin)
                                    <span style="padding: 2px 8px; border-radius: var(--radius-sm); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; background: var(--success-100); color: var(--success-700);">Admin</span>
                                @else
                                    <span style="padding: 2px 8px; border-radius: var(--radius-sm); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; background: var(--admin-primary-100); color: var(--admin-primary-700);">{{ $conversation->user->name }}</span>
                                @endif
                                <span style="font-size: 0.75rem; color: var(--admin-secondary-500); display: flex; align-items: center; gap: var(--space-xs);">
                                    <i class="fas fa-clock"></i>
                                    {{ $message->created_at->format('M d, Y h:i A') }}
                                </span>
                            </div>
                            
                            <!-- Message Bubble -->
                            <div style="padding: var(--space-md) var(--space-lg); border-radius: var(--radius-xl); margin-bottom: var(--space-xs); word-wrap: break-word; line-height: 1.6; position: relative; box-shadow: var(--shadow-sm); {{ $message->is_from_admin ? 'background: linear-gradient(135deg, var(--success-500), var(--success-600)); color: white; border-bottom-right-radius: var(--radius-sm);' : 'background: white; color: var(--admin-secondary-900); border: 1px solid var(--admin-secondary-200); border-bottom-left-radius: var(--radius-sm);' }}">
                                {{ $message->message }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Typing Indicator -->
                    <div id="typingIndicator" class="typing-indicator">
                        <i class="fas fa-pencil-alt"></i>
                        Customer is typing...
                    </div>
                </div>
                
                <!-- Reply Form -->
                <div style="border-top: 1px solid var(--admin-secondary-200); padding: var(--space-lg); background: var(--admin-secondary-50);">
                    <form action="{{ route('admin.conversations.reply', $conversation) }}" method="POST" id="messageForm">
                        @csrf
                        <div style="margin-bottom: var(--space-md);">
                            <label style="display: block; margin-bottom: var(--space-sm); font-weight: 500; color: var(--admin-secondary-700);">
                                <i class="fas fa-reply"></i>
                                Your Reply
                            </label>
                            <textarea 
                                name="message" 
                                id="messageTextarea"
                                class="form-input"
                                style="width: 100%; min-height: 100px; padding: var(--space-md); border: 2px solid var(--admin-secondary-300); border-radius: var(--radius-lg); font-family: inherit; resize: vertical; transition: all var(--transition-fast);"
                                placeholder="Type your reply here..."
                                required
                                onkeydown="handleKeyDown(event)"
                                oninput="autoResize(this)"
                                onfocus="this.style.borderColor='var(--admin-primary-500)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                                onblur="this.style.borderColor='var(--admin-secondary-300)'; this.style.boxShadow='none';"
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <div style="color: var(--error-500); font-size: 0.875rem; margin-top: var(--space-sm);">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="font-size: 0.875rem; color: var(--admin-secondary-600);">
                                <i class="fas fa-info-circle"></i>
                                This will be sent to {{ $conversation->user->name }}
                                <span id="sendingStatus" style="display: none; margin-left: var(--space-sm); color: var(--admin-primary-500);">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    Sending...
                                </span>
                            </div>
                            <div style="display: flex; gap: var(--space-sm); align-items: center;">
                                <button type="button" onclick="toggleRealTimeMessaging()" id="realTimeToggle" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-sync-alt"></i>
                                    <span>Disable Auto-refresh</span>
                                </button>
                                <button type="submit" id="sendButton" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                    Send Reply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div style="display: flex; flex-direction: column; gap: var(--space-lg);">
            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user"></i>
                        Customer Information
                    </h3>
                </div>
                <div class="card-body">
                    <div style="text-align: center; margin-bottom: var(--space-lg);">
                        <div style="width: 80px; height: 80px; margin: 0 auto var(--space-md); border-radius: 50%; background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600)); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 700; box-shadow: var(--shadow-lg);">
                            {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                        </div>
                        <h4 style="font-size: 1.125rem; font-weight: 600; color: var(--admin-secondary-900); margin-bottom: var(--space-xs);">
                            {{ $conversation->user->name }}
                        </h4>
                        <p style="color: var(--admin-secondary-600); font-size: 0.875rem;">
                            {{ $conversation->user->email }}
                        </p>
                    </div>
                    
                    <div style="border-top: 1px solid var(--admin-secondary-200); padding-top: var(--space-lg);">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-100);">
                            <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">Member Since:</span>
                            <span style="color: var(--admin-secondary-900); font-size: 0.875rem;">{{ $conversation->user->orders->count() ?? 0 }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0;">
                            <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">Role:</span>
                            <span class="badge badge-secondary">{{ ucfirst($conversation->user->role ?? 'customer') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversation Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        Conversation Details
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-100);">
                        <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">Status:</span>
                        <span>
                            @if(!$conversation->is_read_by_admin)
                                <span class="badge badge-warning">Needs Attention</span>
                            @else
                                <span class="badge badge-success">Handled</span>
                            @endif
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-100);">
                        <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">Messages:</span>
                        <span style="color: var(--admin-secondary-900); font-size: 0.875rem;" id="sidebarMessageCount">{{ $messages->count() }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-100);">
                        <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">Started:</span>
                        <span style="color: var(--admin-secondary-900); font-size: 0.875rem;">{{ $conversation->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0;">
                        <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">Last Updated:</span>
                        <span style="color: var(--admin-secondary-900); font-size: 0.875rem;" id="lastReplyTime">{{ $conversation->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
                        @if(!$conversation->is_read_by_admin)
                        <form action="{{ route('admin.conversations.mark-read', $conversation) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                <i class="fas fa-check"></i>
                                Mark as Read
                            </button>
                        </form>
                        @endif
                        
                        <a href="{{ route('admin.users.show', $conversation->user) }}" class="btn btn-secondary" style="width: 100%; text-decoration: none;">
                            <i class="fas fa-user"></i>
                            View Customer Profile
                        </a>
                        
                        <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary" style="width: 100%; text-decoration: none;">
                            <i class="fas fa-arrow-left"></i>
                            Back to Conversations
                        </a>
                        
                        <button onclick="window.print()" class="btn btn-secondary" style="width: 100%;">
                            <i class="fas fa-print"></i>
                            Print Conversation
                        </button>
                    </div>
                </div>
            </div>

            <!-- Support Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle"></i>
                        Support Guidelines
                    </h3>
                </div>
                <div class="card-body">
                    <div style="font-size: 0.875rem; color: var(--admin-secondary-700); line-height: 1.6;">
                        <div style="margin-bottom: var(--space-md);">
                            <strong>Response Goals:</strong>
                            <ul style="margin: var(--space-sm) 0 0 var(--space-lg); padding: 0;">
                                <li>First response: < 2 hours</li>
                                <li>Resolution: < 24 hours</li>
                                <li>Follow-up: Within 48 hours</li>
                            </ul>
                        </div>
                        <div>
                            <strong>Tips:</strong>
                            <ul style="margin: var(--space-sm) 0 0 var(--space-lg); padding: 0;">
                                <li>Be empathetic and professional</li>
                                <li>Provide clear, actionable solutions</li>
                                <li>Ask for clarification if needed</li>
                                <li>Follow up to ensure satisfaction</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced message animations */
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

div[style*="margin-bottom: var(--space-xl)"] {
    animation: messageSlideIn 0.3s ease-out;
}

/* Hover effects */
.card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn:hover {
    transform: translateY(-1px);
}

/* Message bubble hover effects */
div[style*="border-radius: var(--radius-xl)"]:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-md) !important;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 350px"] {
        grid-template-columns: 1fr !important;
    }
    
    div[style*="height: calc(100vh - 200px)"] {
        height: calc(100vh - 300px) !important;
        min-height: 400px !important;
    }
    
    div[style*="max-width: 75%"] {
        max-width: 85% !important;
    }
    
    div[style*="display: flex"][style*="justify-content: space-between"] {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: var(--space-md) !important;
    }
}

/* Print styles */
@media print {
    .btn, .card-header, nav, .sidebar {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection

@push('scripts')
<script type="text/javascript">
// ========================================
// Real-time Messaging System for Admin
// ========================================

class RealTimeMessaging {
    constructor(conversationId, isAdmin = false) {
        this.conversationId = conversationId;
        this.isAdmin = isAdmin;
        this.lastMessageId = 0;
        this.checkInterval = null;
        this.isActive = true;
        this.checkFrequency = 3000; // 3 ثواني
        this.isRealTimeEnabled = true;
        
        this.init();
    }

    init() {
        // الحصول على آخر معرف رسالة عند التحميل
        this.getLatestMessageId();
        
        // بدء التحقق الدوري
        this.startPolling();
        
        // إيقاف التحقق عند مغادرة الصفحة
        this.setupVisibilityChange();
        
        // تحسين الـ form submission
        this.setupFormSubmission();
        
        console.log('Real-time messaging initialized for admin');
    }

    getLatestMessageId() {
        const messages = document.querySelectorAll('#messagesList > div[data-message-id]');
        if (messages.length > 0) {
            const lastMessage = messages[messages.length - 1];
            const messageId = lastMessage.getAttribute('data-message-id');
            if (messageId) {
                this.lastMessageId = parseInt(messageId);
            }
        }
    }

    startPolling() {
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
        }

        if (!this.isRealTimeEnabled) return;

        this.checkInterval = setInterval(() => {
            if (this.isActive && document.hasFocus()) {
                this.checkForNewMessages();
            }
        }, this.checkFrequency);

        this.updateConnectionStatus(true);
    }

    stopPolling() {
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
            this.checkInterval = null;
        }
        this.updateConnectionStatus(false);
    }

    async checkForNewMessages() {
        try {
            const url = `/admin/conversations/${this.conversationId}/check-new-messages?last_message_id=${this.lastMessageId}`;

            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            if (response.ok) {
                const data = await response.json();
                
                if (data.has_new_messages && data.messages.length > 0) {
                    this.displayNewMessages(data.messages);
                    this.showNewMessageIndicator();
                }
                this.updateConnectionStatus(true);
            } else {
                this.updateConnectionStatus(false);
            }
        } catch (error) {
            console.error('Error checking for new messages:', error);
            this.updateConnectionStatus(false);
        }
    }

    displayNewMessages(messages) {
        const messagesList = document.getElementById('messagesList');
        if (!messagesList) return;

        messages.forEach(message => {
            if (message.id > this.lastMessageId) {
                const messageElement = this.createMessageElement(message);
                
                // إدراج قبل typing indicator إذا وجد
                const typingIndicator = document.getElementById('typingIndicator');
                if (typingIndicator) {
                    messagesList.insertBefore(messageElement, typingIndicator);
                } else {
                    messagesList.appendChild(messageElement);
                }
                
                this.lastMessageId = message.id;
                
                // إضافة تأثير الظهور
                setTimeout(() => {
                    messageElement.style.opacity = '1';
                    messageElement.style.transform = 'translateY(0)';
                }, 50);

                // تحديث العدادات
                this.updateMessageCount();
            }
        });

        // تشغيل صوت إشعار
        this.playNotificationSound();
    }

    createMessageElement(message) {
        const messageDiv = document.createElement('div');
        messageDiv.setAttribute('data-message-id', message.id);
        messageDiv.style.cssText = `
            margin-bottom: var(--space-xl); display: flex; gap: var(--space-md);
            opacity: 0; transform: translateY(20px); transition: all 0.3s ease;
        `;
        
        if (message.is_from_admin) {
            messageDiv.style.flexDirection = 'row-reverse';
        }

        // إنشاء الأفاتار
        const avatar = document.createElement('div');
        avatar.style.cssText = `
            width: 45px; height: 45px; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            color: white; font-weight: 600; font-size: 1rem; 
            flex-shrink: 0; box-shadow: var(--shadow-md);
        `;
        
        if (message.is_from_admin) {
            avatar.style.background = 'linear-gradient(135deg, var(--success-500), var(--success-600))';
            avatar.innerHTML = '<i class="fas fa-user-shield"></i>';
        } else {
            avatar.style.background = 'linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600))';
            avatar.textContent = message.avatar;
        }

        // إنشاء محتوى الرسالة
        const contentDiv = document.createElement('div');
        contentDiv.style.cssText = 'flex: 1; max-width: 75%;';

        // معلومات المرسل
        const senderInfo = document.createElement('div');
        senderInfo.style.cssText = `
            font-size: 0.8rem; font-weight: 600; color: var(--admin-secondary-600); 
            margin-bottom: var(--space-xs); display: flex; align-items: center; gap: var(--space-sm);
        `;
        
        if (message.is_from_admin) {
            senderInfo.style.justifyContent = 'flex-end';
        }

        const senderBadge = document.createElement('span');
        senderBadge.style.cssText = `
            padding: 2px 8px; border-radius: var(--radius-sm); font-size: 0.65rem; 
            text-transform: uppercase; letter-spacing: 0.5px;
        `;
        
        if (message.is_from_admin) {
            senderBadge.style.cssText += 'background: var(--success-100); color: var(--success-700);';
            senderBadge.textContent = 'Admin';
        } else {
            senderBadge.style.cssText += 'background: var(--admin-primary-100); color: var(--admin-primary-700);';
            senderBadge.textContent = message.user_name;
        }

        const timeSpan = document.createElement('span');
        timeSpan.style.cssText = 'font-size: 0.75rem; color: var(--admin-secondary-500); display: flex; align-items: center; gap: var(--space-xs);';
        timeSpan.innerHTML = `<i class="fas fa-clock"></i> ${message.created_at}`;

        senderInfo.appendChild(senderBadge);
        senderInfo.appendChild(timeSpan);

        // فقاعة الرسالة
        const messageBubble = document.createElement('div');
        messageBubble.style.cssText = `
            padding: var(--space-md) var(--space-lg); border-radius: var(--radius-xl); 
            margin-bottom: var(--space-xs); word-wrap: break-word; line-height: 1.6; 
            position: relative; box-shadow: var(--shadow-sm);
        `;
        
        if (message.is_from_admin) {
            messageBubble.style.cssText += `
                background: linear-gradient(135deg, var(--success-500), var(--success-600)); 
                color: white; border-bottom-right-radius: var(--radius-sm);
            `;
        } else {
            messageBubble.style.cssText += `
                background: white; color: var(--admin-secondary-900); 
                border: 1px solid var(--admin-secondary-200); 
                border-bottom-left-radius: var(--radius-sm);
            `;
        }
        
        messageBubble.textContent = message.message;

        contentDiv.appendChild(senderInfo);
        contentDiv.appendChild(messageBubble);
        
        messageDiv.appendChild(avatar);
        messageDiv.appendChild(contentDiv);

        return messageDiv;
    }

    async setupFormSubmission() {
        const form = document.getElementById('messageForm');
        const textarea = document.getElementById('messageTextarea');
        const submitButton = document.getElementById('sendButton');
        const sendingStatus = document.getElementById('sendingStatus');
        
        if (!form || !textarea || !submitButton) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const message = textarea.value.trim();
            if (!message) return;

            // تعطيل الـ form
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            textarea.disabled = true;
            sendingStatus.style.display = 'inline';

            try {
                const formData = new FormData(form);
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success && data.message) {
                        // إضافة الرسالة فوراً
                        this.displayNewMessages([data.message]);
                        
                        // مسح النص
                        textarea.value = '';
                        
                        // تقليل حجم الـ textarea
                        textarea.style.height = 'auto';
                        
                        // التمرير إلى أسفل
                        this.scrollToBottom();
                    }
                } else {
                    throw new Error('Failed to send message');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                alert('خطأ في إرسال الرسالة. يرجى المحاولة مرة أخرى.');
            } finally {
                // إعادة تفعيل الـ form
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-paper-plane"></i> Send Reply';
                textarea.disabled = false;
                textarea.focus();
                sendingStatus.style.display = 'none';
            }
        });
    }

    scrollToBottom() {
        const messagesList = document.getElementById('messagesList');
        if (messagesList) {
            messagesList.scrollTo({
                top: messagesList.scrollHeight,
                behavior: 'smooth'
            });
        }
        this.hideNewMessageIndicator();
    }

    showNewMessageIndicator() {
        const indicator = document.getElementById('newMessageIndicator');
        if (indicator) {
            indicator.classList.add('show');
        }
    }

    hideNewMessageIndicator() {
        const indicator = document.getElementById('newMessageIndicator');
        if (indicator) {
            indicator.classList.remove('show');
        }
    }

    updateMessageCount() {
        const messageCountElements = document.querySelectorAll('#messageCount, #sidebarMessageCount');
        const currentCount = document.querySelectorAll('#messagesList > div[data-message-id]').length;
        
        messageCountElements.forEach(element => {
            element.textContent = currentCount;
        });
    }

    updateConnectionStatus(connected) {
        const statusElement = document.getElementById('connectionStatus');
        if (statusElement) {
            const dot = statusElement.querySelector('.connection-dot');
            const text = statusElement.querySelector('span');
            
            if (connected) {
                dot.className = 'connection-dot connected';
                text.textContent = 'Connected';
            } else {
                dot.className = 'connection-dot disconnected';
                text.textContent = 'Disconnected';
            }
        }
    }

    playNotificationSound() {
        try {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhCTGa2+m1bi8EJHzK7+ORSA0PUqfk7bFlHgg2jdXzzXkpBS12wuzZkT8LElyx6+2rWBULTKLh6WNHDTGLz/fbiTAKGGm/8+CK');
            audio.volume = 0.3;
            audio.play().catch(() => {}); // تجاهل الأخطاء
        } catch (e) {
            // تجاهل أخطاء الصوت
        }
    }

    setupVisibilityChange() {
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.isActive = false;
            } else {
                this.isActive = true;
                // تحقق فوري عند العودة للصفحة
                setTimeout(() => {
                    if (this.isRealTimeEnabled) {
                        this.checkForNewMessages();
                    }
                }, 500);
            }
        });

        // إيقاف عند مغادرة الصفحة
        window.addEventListener('beforeunload', () => {
            this.stopPolling();
        });
    }

    toggle() {
        this.isRealTimeEnabled = !this.isRealTimeEnabled;
        
        const toggleButton = document.getElementById('realTimeToggle');
        if (toggleButton) {
            const span = toggleButton.querySelector('span');
            if (this.isRealTimeEnabled) {
                this.startPolling();
                span.textContent = 'Disable Auto-refresh';
                toggleButton.style.background = '';
            } else {
                this.stopPolling();
                span.textContent = 'Enable Auto-refresh';
                toggleButton.style.background = 'var(--warning-100)';
            }
        }
    }

    // تدمير المثيل
    destroy() {
        this.stopPolling();
        this.isActive = false;
    }
}

// ========================================
// Helper Functions
// ========================================

function scrollToBottom() {
    if (window.realTimeMessaging) {
        window.realTimeMessaging.scrollToBottom();
    }
}

function autoResize(textarea) {
    if (textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }
}

function handleKeyDown(event) {
    if (event.ctrlKey && event.key === 'Enter') {
        event.preventDefault();
        const form = event.target.closest('form');
        if (form) {
            form.dispatchEvent(new Event('submit', { cancelable: true }));
        }
    }
}

function toggleRealTimeMessaging() {
    if (window.realTimeMessaging) {
        window.realTimeMessaging.toggle();
    }
}

// ========================================
// Auto-initialization
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // استخراج معرف المحادثة من الـ URL
    const pathParts = window.location.pathname.split('/');
    const conversationId = pathParts[pathParts.length - 1];
    
    // التحقق من وجود صفحة المحادثة
    const messagesList = document.getElementById('messagesList');
    
    if (messagesList && conversationId && !isNaN(conversationId)) {
        // إنشاء مثيل من نظام الرسائل الفورية للأدمن
        window.realTimeMessaging = new RealTimeMessaging(conversationId, true);
        
        console.log('Real-time messaging started for admin conversation:', conversationId);
        
        // التمرير إلى أسفل عند التحميل
        setTimeout(() => {
            scrollToBottom();
        }, 500);
        
        // تركيز على حقل النص
        const textarea = document.getElementById('messageTextarea');
        if (textarea) {
            textarea.focus();
        }
    }
});
</script>
@endpush-size: 0.875rem;">{{ $conversation->user->created_at->format('M d, Y') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-100);">
                            <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">Total Orders:</span>
                            <span style="color: var(--admin-secondary-900); font