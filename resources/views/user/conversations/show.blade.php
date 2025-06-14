@extends('layouts.app')

@section('title', $conversation->title)

@push('styles')
<style>
/* تحسينات CSS للرسائل الفورية مع تحسين التمرير */
.message-sending {
    opacity: 0.7;
    pointer-events: none;
}

.new-message-indicator {
    position: fixed;
    bottom: 100px;
    right: 20px;
    background: #0ea5e9;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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
    padding: 0.5rem 1rem;
    background: #f3f4f6;
    border-radius: 1rem;
    margin: 0.5rem 0;
    font-style: italic;
    color: #6b7280;
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
    gap: 0.25rem;
}

.connection-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.connection-dot.connected {
    background: #22c55e;
}

.connection-dot.disconnected {
    background: #ef4444;
}

.unread-count {
    background: #ef4444;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
}

.success-message {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #10b981;
    color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    z-index: 1001;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.success-message.show {
    transform: translateX(0);
}

.error-message {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #ef4444;
    color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    z-index: 1001;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.error-message.show {
    transform: translateX(0);
}

/* تحسينات التمرير المحسنة */
.conversation-container {
    height: calc(100vh - 100px) !important; /* تقليل المساحة المحجوزة */
    min-height: 700px !important; /* زيادة الحد الأدنى للارتفاع */
}

.messages-container {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
}

#messagesList {
    flex: 1;
    padding: 1.5rem;
    overflow-y: auto;
    background: linear-gradient(180deg, white 0%, #f8f9fa 100%);
    max-height: calc(100vh - 320px) !important; /* تحسين الارتفاع */
    min-height: 450px !important;
    scroll-behavior: smooth;
    scrollbar-width: thin;
    scrollbar-color: rgba(0,0,0,0.2) transparent;
}

#messagesList::-webkit-scrollbar {
    width: 8px;
}

#messagesList::-webkit-scrollbar-track {
    background: transparent;
}

#messagesList::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.2);
    border-radius: 4px;
}

#messagesList::-webkit-scrollbar-thumb:hover {
    background: rgba(0,0,0,0.3);
}

/* إضافة مساحة إضافية في الأسفل */
#messagesList::after {
    content: '';
    display: block;
    height: 20px;
    width: 100%;
}

/* تحسين التمرير السلس */
.smooth-scroll {
    scroll-behavior: smooth;
}

/* Hover effects */
button:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2) !important;
}

a[style*="display: flex"]:hover {
    background: #f8f9fa !important;
    border-color: #0ea5e9 !important;
    transform: translateY(-1px);
}

/* Mobile responsiveness محسنة */
@media (max-width: 768px) {
    .container {
        padding: 1rem 0.5rem !important;
    }
    
    .conversation-container {
        height: calc(100vh - 60px) !important;
        min-height: 500px !important;
    }
    
    div[style*="grid-template-columns: 1fr 300px"] {
        grid-template-columns: 1fr !important;
    }
    
    #messagesList {
        max-height: calc(100vh - 250px) !important;
        min-height: 350px !important;
    }
    
    div[style*="max-width: 75%"] {
        max-width: 85% !important;
    }
    
    div[style*="flex-wrap: wrap"] {
        flex-wrap: wrap !important;
        gap: 1rem !important;
    }
    
    div[style*="display: flex"][style*="align-items: flex-end"] {
        flex-direction: column !important;
        gap: 0.5rem !important;
    }
    
    button[type="submit"] {
        align-self: flex-end !important;
        min-width: 100px !important;
    }
}

/* تحسين عرض الرسائل */
.message-bubble {
    margin-bottom: 1rem;
    animation: messageSlideIn 0.3s ease-out;
}

@keyframes messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@section('content')
<div class="container" style="padding: 2rem 0; max-width: 1000px;">
    <!-- CSRF Token for JavaScript -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Back Link -->
    <a href="{{ route('user.conversations.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #0ea5e9; text-decoration: none; font-weight: 500; margin-bottom: 2rem; transition: color 0.2s ease;">
        <i class="fas fa-arrow-left"></i>
        Back to Conversations
    </a>

    <!-- New Message Indicator -->
    <div id="newMessageIndicator" class="new-message-indicator" onclick="scrollToBottom(true, true)">
        <i class="fas fa-arrow-down"></i>
        رسائل جديدة
    </div>

    <!-- Success/Error Messages -->
    <div id="successMessage" class="success-message">
        <i class="fas fa-check"></i>
        <span id="successText"></span>
    </div>
    <div id="errorMessage" class="error-message">
        <i class="fas fa-exclamation-triangle"></i>
        <span id="errorText"></span>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem;">
        <div class="conversation-container">
            <!-- Conversation Header -->
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 1rem; padding: 2rem; margin-bottom: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-comment-dots" style="color: #0ea5e9;"></i>
                    {{ $conversation->title }}
                    <span class="unread-count" id="unreadCount" style="display: none;">0</span>
                </div>
                <div style="display: flex; align-items: center; gap: 1.5rem; color: #666; font-size: 0.875rem; flex-wrap: wrap;">
                    <span>
                        <i class="fas fa-calendar-alt" style="margin-right: 0.25rem;"></i>
                        Started {{ $conversation->created_at->format('M d, Y') }}
                    </span>
                    <span>
                        <i class="fas fa-clock" style="margin-right: 0.25rem;"></i>
                        Last updated {{ $conversation->updated_at->diffForHumans() }}
                    </span>
                    <span>
                        <i class="fas fa-comment" style="margin-right: 0.25rem;"></i>
                        <span id="messageCount">{{ $messages->count() }}</span> messages
                    </span>
                    <span id="connectionStatus" class="connection-status">
                        <div class="connection-dot connected"></div>
                        <span>Connected</span>
                    </span>
                </div>
            </div>

            <!-- Messages Container -->
            <div class="messages-container" style="background: white; border: 1px solid #e5e7eb; border-radius: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                
                <!-- Messages Header -->
                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; background: #f8f9fa; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-comments"></i>
                        Conversation with Support Team
                    </h3>
                    <button onclick="scrollToBottom(true, true)" style="background: #6b7280; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.75rem; cursor: pointer;">
                        <i class="fas fa-arrow-down"></i>
                        Latest
                    </button>
                </div>

                <!-- Messages List -->
                <div id="messagesList" class="smooth-scroll">
                    @foreach($messages as $message)
                    <div data-message-id="{{ $message->id }}" class="message-bubble" style="margin-bottom: 2rem; display: flex; gap: 1rem; {{ $message->is_from_admin ? '' : 'flex-direction: row-reverse;' }}">
                        <!-- Avatar -->
                        <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.15); {{ $message->is_from_admin ? 'background: linear-gradient(135deg, #22c55e, #16a34a);' : 'background: linear-gradient(135deg, #0ea5e9, #0284c7);' }}">
                            @if($message->is_from_admin)
                                <i class="fas fa-headset"></i>
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @endif
                        </div>
                        
                        <!-- Message Content -->
                        <div style="flex: 1; max-width: 75%;">
                            <!-- Sender Info -->
                            <div style="font-size: 0.75rem; font-weight: 600; color: #666; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem; {{ $message->is_from_admin ? '' : 'justify-content: flex-end;' }}">
                                @if($message->is_from_admin)
                                    <span style="padding: 2px 6px; border-radius: 0.375rem; font-size: 0.625rem; text-transform: uppercase; letter-spacing: 0.5px; background: #dcfce7; color: #166534;">Support Team</span>
                                @else
                                    <span style="padding: 2px 6px; border-radius: 0.375rem; font-size: 0.625rem; text-transform: uppercase; letter-spacing: 0.5px; background: #dbeafe; color: #1e40af;">You</span>
                                @endif
                                <span style="font-size: 0.75rem; color: #666; display: flex; align-items: center; gap: 0.25rem;">
                                    <i class="fas fa-clock"></i>
                                    {{ $message->created_at->format('M d, h:i A') }}
                                </span>
                            </div>
                            
                            <!-- Message Bubble -->
                            <div style="padding: 1rem 1.5rem; border-radius: 1.5rem; margin-bottom: 0.25rem; word-wrap: break-word; line-height: 1.6; position: relative; box-shadow: 0 2px 10px rgba(0,0,0,0.05); {{ $message->is_from_admin ? 'background: white; color: #333; border: 1px solid #e5e7eb; border-bottom-left-radius: 0.375rem;' : 'background: linear-gradient(135deg, #0ea5e9, #0284c7); color: white; border-bottom-right-radius: 0.375rem;' }}">
                                {{ $message->message }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Typing Indicator -->
                    <div id="typingIndicator" class="typing-indicator">
                        <i class="fas fa-pencil-alt"></i>
                        Support team is typing...
                    </div>
                </div>

                <!-- Reply Form -->
                <form action="{{ route('user.conversations.reply', $conversation) }}" method="POST" id="messageForm" style="border-top: 1px solid #e5e7eb; padding: 1.5rem; background: white;">
                    @csrf
                    <div style="display: flex; gap: 1rem; align-items: flex-end;">
                        <textarea 
                            name="message" 
                            id="messageTextarea"
                            style="flex: 1; min-height: 60px; max-height: 120px; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; background: white; color: #333; font-size: 0.875rem; font-family: inherit; resize: none; transition: all 0.2s ease;"
                            placeholder="Type your message here... (Press Enter to send, Shift+Enter for new line)"
                            required
                            onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14, 165, 233, 0.1)';"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                        ></textarea>
                        <button type="submit" id="sendButton" style="padding: 1rem; background: linear-gradient(135deg, #0ea5e9, #0284c7); color: white; border: none; border-radius: 0.75rem; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; min-width: 50px; height: 50px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    
                    <!-- Error Display -->
                    @if ($errors->any())
                    <div style="margin-top: 1rem; padding: 0.75rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem; color: #b91c1c;">
                        <ul style="margin: 0; padding-left: 1rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #666; display: flex; justify-content: space-between; align-items: center;">
                        <span>
                            <i class="fas fa-info-circle"></i>
                            Press Enter to send, Shift+Enter for new line
                        </span>
                        <span id="sendingStatus" style="display: none; color: #0ea5e9;">
                            <i class="fas fa-spinner fa-spin"></i>
                            Sending...
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Conversation Status -->
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 1rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="padding: 1rem 1.5rem; background: #f8f9fa; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #333; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-info-circle"></i>
                    Status
                </div>
                <div style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 500; color: #666; font-size: 0.875rem;">Status:</span>
                        <span style="color: #333; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 0.25rem;">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e;"></div>
                            Active
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 500; color: #666; font-size: 0.875rem;">Messages:</span>
                        <span style="color: #333; font-size: 0.875rem; font-weight: 500;" id="sidebarMessageCount">{{ $messages->count() }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 500; color: #666; font-size: 0.875rem;">Created:</span>
                        <span style="color: #333; font-size: 0.875rem; font-weight: 500;">{{ $conversation->created_at->format('M d, Y') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0;">
                        <span style="font-weight: 500; color: #666; font-size: 0.875rem;">Last Reply:</span>
                        <span style="color: #333; font-size: 0.875rem; font-weight: 500;" id="lastReplyTime">{{ $conversation->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 1rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="padding: 1rem 1.5rem; background: #f8f9fa; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #333; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </div>
                <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="{{ route('user.conversations.index') }}" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: white; color: #333; text-decoration: none; font-size: 0.875rem; transition: all 0.2s ease;">
                        <i class="fas fa-list"></i>
                        View All Conversations
                    </a>
                    <a href="{{ route('user.conversations.create') }}" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: white; color: #333; text-decoration: none; font-size: 0.875rem; transition: all 0.2s ease;">
                        <i class="fas fa-plus"></i>
                        Start New Conversation
                    </a>
                    <button onclick="toggleRealTimeMessaging()" id="realTimeToggle" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: white; color: #333; font-size: 0.875rem; transition: all 0.2s ease; cursor: pointer; width: 100%;">
                        <i class="fas fa-sync-alt"></i>
                        <span>Disable Auto-refresh</span>
                    </button>
                </div>
            </div>

            <!-- Support Info -->
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 1rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="padding: 1rem 1.5rem; background: #f8f9fa; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #333; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-question-circle"></i>
                    Support Info
                </div>
                <div style="padding: 1.5rem;">
                    <p style="font-size: 0.875rem; color: #666; line-height: 1.6; margin-bottom: 1rem;">
                        Our support team typically responds within 24 hours during business days.
                    </p>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 500; color: #666; font-size: 0.875rem;">Response Time:</span>
                        <span style="color: #333; font-size: 0.875rem; font-weight: 500;">< 24 hours</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0;">
                        <span style="font-weight: 500; color: #666; font-size: 0.875rem;">Business Hours:</span>
                        <span style="color: #333; font-size: 0.875rem; font-weight: 500;">9 AM - 6 PM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
// ========================================
// Real-time Messaging System (مُحدث مع تحسين التمرير)
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
        this.getLatestMessageId();
        this.startPolling();
        this.setupVisibilityChange();
        console.log('Real-time messaging initialized');
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
            const url = `/user/conversations/${this.conversationId}/check-new-messages?last_message_id=${this.lastMessageId}`;

            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            if (response.ok) {
                const data = await response.json();
                
                if (data.has_new_messages && data.messages.length > 0) {
                    // فقط عرض الرسائل من الأدمن (ليس من المستخدم نفسه)
                    const adminMessages = data.messages.filter(msg => msg.is_from_admin);
                    if (adminMessages.length > 0) {
                        this.displayNewMessages(adminMessages);
                        this.updateNotifications(data.unread_count || 0);
                        this.showNewMessageIndicator();
                    }
                    
                    // تحديث آخر معرف رسالة للرسائل الجديدة كلها
                    data.messages.forEach(msg => {
                        if (msg.id > this.lastMessageId) {
                            this.lastMessageId = msg.id;
                        }
                    });
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
            // تحقق من عدم وجود الرسالة مسبقاً
            const existingMessage = document.querySelector(`[data-message-id="${message.id}"]`);
            if (existingMessage) {
                console.log('Message already exists, skipping:', message.id);
                return;
            }

            const messageElement = this.createMessageElement(message);
            
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator) {
                messagesList.insertBefore(messageElement, typingIndicator);
            } else {
                messagesList.appendChild(messageElement);
            }
            
            setTimeout(() => {
                messageElement.style.opacity = '1';
                messageElement.style.transform = 'translateY(0)';
            }, 50);

            this.updateMessageCount();
        });

        this.playNotificationSound();
        
        // التمرير التلقائي للرسائل الجديدة
        setTimeout(() => {
            scrollToBottom(true, false);
        }, 200);
    }

    createMessageElement(message) {
        const messageDiv = document.createElement('div');
        messageDiv.setAttribute('data-message-id', message.id);
        messageDiv.className = 'message-bubble';
        messageDiv.style.cssText = `
            margin-bottom: 2rem; display: flex; gap: 1rem;
            opacity: 0; transform: translateY(20px); transition: all 0.3s ease;
        `;
        
        if (!message.is_from_admin) {
            messageDiv.style.flexDirection = 'row-reverse';
        }

        const avatar = document.createElement('div');
        avatar.style.cssText = `
            width: 40px; height: 40px; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            color: white; font-weight: 600; font-size: 1rem; 
            flex-shrink: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        if (message.is_from_admin) {
            avatar.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
            avatar.innerHTML = '<i class="fas fa-headset"></i>';
        } else {
            avatar.style.background = 'linear-gradient(135deg, #0ea5e9, #0284c7)';
            avatar.textContent = message.avatar;
        }

        const contentDiv = document.createElement('div');
        contentDiv.style.cssText = 'flex: 1; max-width: 75%;';

        const senderInfo = document.createElement('div');
        senderInfo.style.cssText = `
            font-size: 0.75rem; font-weight: 600; color: #666; 
            margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem;
        `;
        
        if (!message.is_from_admin) {
            senderInfo.style.justifyContent = 'flex-end';
        }

        const senderBadge = document.createElement('span');
        senderBadge.style.cssText = `
            padding: 2px 6px; border-radius: 0.375rem; font-size: 0.625rem; 
            text-transform: uppercase; letter-spacing: 0.5px;
        `;
        
        if (message.is_from_admin) {
            senderBadge.style.cssText += 'background: #dcfce7; color: #166534;';
            senderBadge.textContent = 'Support Team';
        } else {
            senderBadge.style.cssText += 'background: #dbeafe; color: #1e40af;';
            senderBadge.textContent = 'You';
        }

        const timeSpan = document.createElement('span');
        timeSpan.style.cssText = 'font-size: 0.75rem; color: #666; display: flex; align-items: center; gap: 0.25rem;';
        timeSpan.innerHTML = `<i class="fas fa-clock"></i> ${message.created_at}`;

        senderInfo.appendChild(senderBadge);
        senderInfo.appendChild(timeSpan);

        const messageBubble = document.createElement('div');
        messageBubble.style.cssText = `
            padding: 1rem 1.5rem; border-radius: 1.5rem; margin-bottom: 0.25rem; 
            word-wrap: break-word; line-height: 1.6; position: relative; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        `;
        
        if (message.is_from_admin) {
            messageBubble.style.cssText += `
                background: white; color: #333; border: 1px solid #e5e7eb; 
                border-bottom-left-radius: 0.375rem;
            `;
        } else {
            messageBubble.style.cssText += `
                background: linear-gradient(135deg, #0ea5e9, #0284c7); 
                color: white; border-bottom-right-radius: 0.375rem;
            `;
        }
        
        messageBubble.textContent = message.message;

        contentDiv.appendChild(senderInfo);
        contentDiv.appendChild(messageBubble);
        
        messageDiv.appendChild(avatar);
        messageDiv.appendChild(contentDiv);

        return messageDiv;
    }

    scrollToBottom(smooth = true) {
        scrollToBottom(smooth, false);
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

    updateNotifications(unreadCount) {
        const unreadCountElement = document.getElementById('unreadCount');
        if (unreadCountElement) {
            if (unreadCount > 0) {
                unreadCountElement.textContent = unreadCount;
                unreadCountElement.style.display = 'inline';
            } else {
                unreadCountElement.style.display = 'none';
            }
        }

        const title = document.title;
        const baseName = title.replace(/^\(\d+\)\s*/, '');
        
        if (unreadCount > 0) {
            document.title = `(${unreadCount}) ${baseName}`;
        } else {
            document.title = baseName;
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
            audio.play().catch(() => {});
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
                setTimeout(() => {
                    if (this.isRealTimeEnabled) {
                        this.checkForNewMessages();
                    }
                }, 500);
            }
        });

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
                toggleButton.style.background = 'white';
            } else {
                this.stopPolling();
                span.textContent = 'Enable Auto-refresh';
                toggleButton.style.background = '#fef3c7';
            }
        }
    }

    destroy() {
        this.stopPolling();
        this.isActive = false;
    }
}

// ========================================
// Message Form Handler (محمي من التكرار والازدواجية)
// ========================================

// منع إعادة التهيئة
if (!window.messageFormInitialized) {
    window.messageFormInitialized = true;

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('messageForm');
        const textarea = document.getElementById('messageTextarea');
        const submitButton = document.getElementById('sendButton');
        const sendingStatus = document.getElementById('sendingStatus');
        
        if (!form || !textarea || !submitButton) {
            console.error('Form elements not found');
            return;
        }

        let isSubmitting = false; // منع الإرسال المتعدد

        // منع الإرسال التقليدي للفورم
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!isSubmitting) {
                sendMessage();
            }
        });

        // معالجة ضغط Enter
        textarea.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                if (e.ctrlKey || e.shiftKey) {
                    // Ctrl+Enter أو Shift+Enter = سطر جديد
                    return;
                } else {
                    // Enter عادي = إرسال الرسالة
                    e.preventDefault();
                    if (!isSubmitting) {
                        sendMessage();
                    }
                }
            }
        });

        // دالة إرسال الرسالة (محمية من التكرار)
        async function sendMessage() {
            if (isSubmitting) {
                console.log('Already submitting, ignoring...');
                return;
            }

            const message = textarea.value.trim();
            if (!message) {
                showErrorMessage('الرجاء كتابة رسالة قبل الإرسال');
                return;
            }

            isSubmitting = true; // قفل الإرسال
            
            const originalButtonText = submitButton.innerHTML;
            
            // تعطيل الـ form
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            textarea.disabled = true;
            if (sendingStatus) sendingStatus.style.display = 'inline';

            try {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('message', message);
                
                console.log('Sending message:', message);

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                console.log('Response status:', response.status);
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Response data:', data);
                    
                    if (data.success) {
                        // إضافة الرسالة إلى المحادثة
                        if (data.message) {
                            addMessageToChat(data.message);
                            
                            // تحديث آخر معرف رسالة في النظام
                            if (window.realTimeMessaging) {
                                window.realTimeMessaging.lastMessageId = data.message.id;
                            }
                        }
                        
                        // مسح النص
                        textarea.value = '';
                        textarea.style.height = 'auto';
                        
                        // التمرير إلى أسفل مع قوة إضافية
                        setTimeout(() => {
                            scrollToBottom(true, true);
                        }, 100);
                        
                        showSuccessMessage('تم إرسال الرسالة بنجاح');
                        console.log('Message sent successfully');
                    } else {
                        throw new Error(data.error || 'فشل في إرسال الرسالة');
                    }
                } else {
                    const errorData = await response.json();
                    let errorMessage = 'خطأ في الخادم';
                    
                    if (errorData.errors) {
                        errorMessage = Object.values(errorData.errors).flat().join(', ');
                    } else if (errorData.error) {
                        errorMessage = errorData.error;
                    }
                    
                    throw new Error(errorMessage);
                }
                
            } catch (error) {
                console.error('Error sending message:', error);
                showErrorMessage('خطأ في إرسال الرسالة: ' + error.message);
                
            } finally {
                // إعادة تفعيل الـ form دائماً
                isSubmitting = false; // إلغاء القفل
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                textarea.disabled = false;
                textarea.focus();
                if (sendingStatus) sendingStatus.style.display = 'none';
            }
        }

        // دالة إضافة رسالة جديدة للمحادثة (بدون ازدواجية)
        function addMessageToChat(message) {
            const messagesList = document.getElementById('messagesList');
            if (!messagesList) return;

            // تحقق من عدم وجود الرسالة مسبقاً (إضافة مهمة)
            const existingMessage = document.querySelector(`[data-message-id="${message.id}"]`);
            if (existingMessage) {
                console.log('Message already exists, skipping...');
                return;
            }

            const messageDiv = document.createElement('div');
            messageDiv.setAttribute('data-message-id', message.id);
            messageDiv.className = 'message-bubble';
            messageDiv.style.cssText = `
                margin-bottom: 2rem; display: flex; gap: 1rem; flex-direction: row-reverse;
                opacity: 0; transform: translateY(20px); transition: all 0.3s ease;
            `;

            messageDiv.innerHTML = `
                <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.15); background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                    ${message.avatar}
                </div>
                <div style="flex: 1; max-width: 75%;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #666; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem; justify-content: flex-end;">
                        <span style="padding: 2px 6px; border-radius: 0.375rem; font-size: 0.625rem; text-transform: uppercase; letter-spacing: 0.5px; background: #dbeafe; color: #1e40af;">You</span>
                        <span style="font-size: 0.75rem; color: #666; display: flex; align-items: center; gap: 0.25rem;">
                            <i class="fas fa-clock"></i>
                            ${message.created_at}
                        </span>
                    </div>
                    <div style="padding: 1rem 1.5rem; border-radius: 1.5rem; margin-bottom: 0.25rem; word-wrap: break-word; line-height: 1.6; position: relative; box-shadow: 0 2px 10px rgba(0,0,0,0.05); background: linear-gradient(135deg, #0ea5e9, #0284c7); color: white; border-bottom-right-radius: 0.375rem;">
                        ${message.message}
                    </div>
                </div>
            `;

            // إدراج قبل typing indicator إذا وجد
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator) {
                messagesList.insertBefore(messageDiv, typingIndicator);
            } else {
                messagesList.appendChild(messageDiv);
            }

            // إضافة تأثير الظهور
            setTimeout(() => {
                messageDiv.style.opacity = '1';
                messageDiv.style.transform = 'translateY(0)';
            }, 50);

            // تحديث عداد الرسائل
            updateMessageCount();
        }

        // دالة تغيير حجم textarea تلقائياً
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        console.log('Message form initialized successfully');
    });
}

// ========================================
// Helper Functions (محسنة مع التمرير القوي)
// ========================================

function scrollToBottom(smooth = true, force = false) {
    const messagesList = document.getElementById('messagesList');
    if (!messagesList) return;
    
    // حساب الارتفاع الكامل مع إضافة مساحة إضافية قوية
    const scrollHeight = messagesList.scrollHeight;
    const extraSpace = force ? 200 : 50; // مساحة إضافية أكبر عند الإرسال
    
    console.log('Scrolling to bottom:', {
        scrollHeight,
        extraSpace,
        total: scrollHeight + extraSpace,
        force,
        smooth
    });
    
    if (smooth) {
        messagesList.scrollTo({
            top: scrollHeight + extraSpace,
            behavior: 'smooth'
        });
        
        // تأكيد إضافي للتمرير بعد انتهاء الحركة السلسة
        setTimeout(() => {
            messagesList.scrollTop = messagesList.scrollHeight + extraSpace;
        }, 300);
    } else {
        // تمرير فوري
        messagesList.scrollTop = scrollHeight + extraSpace;
    }
    
    // إخفاء مؤشر الرسائل الجديدة
    const indicator = document.getElementById('newMessageIndicator');
    if (indicator) {
        indicator.classList.remove('show');
    }
}

function ensureScrollToBottom() {
    const messagesList = document.getElementById('messagesList');
    if (!messagesList) return;
    
    // انتظار تحميل أي محتوى إضافي
    setTimeout(() => {
        scrollToBottom(false, true); // تمرير فوري وقوي
    }, 50);
    
    // تأكيد إضافي
    setTimeout(() => {
        scrollToBottom(false, true);
    }, 200);
}

function updateMessageCount() {
    const messageCountElements = document.querySelectorAll('#messageCount, #sidebarMessageCount');
    const currentCount = document.querySelectorAll('#messagesList > div[data-message-id]').length;
    
    messageCountElements.forEach(element => {
        element.textContent = currentCount;
    });
}

function toggleRealTimeMessaging() {
    if (window.realTimeMessaging) {
        window.realTimeMessaging.toggle();
    }
}

function showSuccessMessage(message) {
    const successElement = document.getElementById('successMessage');
    const successText = document.getElementById('successText');
    
    if (successElement && successText) {
        successText.textContent = message;
        successElement.classList.add('show');
        
        setTimeout(() => {
            successElement.classList.remove('show');
        }, 3000);
    }
}

function showErrorMessage(message) {
    const errorElement = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
    
    if (errorElement && errorText) {
        errorText.textContent = message;
        errorElement.classList.add('show');
        
        setTimeout(() => {
            errorElement.classList.remove('show');
        }, 5000);
    }
}

// ========================================
// Auto-initialization مع تحسين التمرير
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing real-time messaging...');
    
    // استخراج معرف المحادثة من الـ URL
    const pathParts = window.location.pathname.split('/');
    const conversationId = pathParts[pathParts.length - 1];
    
    // التحقق من وجود صفحة المحادثة
    const messagesList = document.getElementById('messagesList');
    
    console.log('Conversation ID:', conversationId);
    console.log('Messages list found:', !!messagesList);
    
    if (messagesList && conversationId && !isNaN(conversationId)) {
        // إنشاء مثيل من نظام الرسائل الفورية (مرة واحدة فقط)
        if (!window.realTimeMessaging) {
            window.realTimeMessaging = new RealTimeMessaging(conversationId, false);
            console.log('Real-time messaging started for conversation:', conversationId);
        }
        
        // التمرير إلى أسفل عند التحميل مع تحسينات
        setTimeout(() => {
            scrollToBottom(false, true); // تمرير فوري وقوي عند التحميل
        }, 100);
        
        // تأكيد إضافي للتمرير
        setTimeout(() => {
            ensureScrollToBottom();
        }, 500);
        
        // تأكيد نهائي بعد تحميل كامل
        window.addEventListener('load', () => {
            setTimeout(() => {
                ensureScrollToBottom();
            }, 200);
        });
        
        // تركيز على حقل النص
        const textarea = document.getElementById('messageTextarea');
        if (textarea) {
            textarea.focus();
        }
    } else {
        console.error('Failed to initialize real-time messaging:', {
            messagesList: !!messagesList,
            conversationId,
            isValidId: !isNaN(conversationId)
        });
    }
});
</script>
@endpush