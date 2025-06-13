// ========================================
// Fixed Real-time Messaging System with Debug
// ========================================

class RealTimeMessaging {
    constructor(conversationId, isAdmin = false) {
        this.conversationId = conversationId;
        this.isAdmin = isAdmin;
        this.lastMessageId = 0;
        this.checkInterval = null;
        this.isActive = true;
        this.checkFrequency = 3000;
        this.isRealTimeEnabled = true;
        this.retryCount = 0;
        this.maxRetries = 3;
        
        this.init();
    }

    init() {
        console.log('🔄 Initializing Real-time Messaging...');
        
        // التحقق من CSRF Token
        if (!this.getCSRFToken()) {
            console.error('❌ CSRF Token not found!');
            return;
        }
        
        this.getLatestMessageId();
        this.startPolling();
        this.setupVisibilityChange();
        this.setupFormSubmission();
        
        console.log('✅ Real-time messaging initialized successfully');
    }

    getLatestMessageId() {
        const messages = document.querySelectorAll('#messagesList > div[data-message-id]');
        if (messages.length > 0) {
            const lastMessage = messages[messages.length - 1];
            const messageId = lastMessage.getAttribute('data-message-id');
            if (messageId) {
                this.lastMessageId = parseInt(messageId);
                console.log('📝 Latest message ID:', this.lastMessageId);
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
        console.log('🔄 Polling started with frequency:', this.checkFrequency + 'ms');
    }

    stopPolling() {
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
            this.checkInterval = null;
        }
        this.updateConnectionStatus(false);
        console.log('⏹️ Polling stopped');
    }

    async checkForNewMessages() {
        try {
            const url = this.isAdmin 
                ? `/admin/conversations/${this.conversationId}/check-new-messages?last_message_id=${this.lastMessageId}`
                : `/user/conversations/${this.conversationId}/check-new-messages?last_message_id=${this.lastMessageId}`;

            console.log('🔍 Checking for new messages:', url);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCSRFToken(),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            });

            console.log('📡 Response status:', response.status);

            if (response.ok) {
                const data = await response.json();
                console.log('📋 Response data:', data);
                
                if (data.has_new_messages && data.messages && data.messages.length > 0) {
                    console.log('📨 New messages received:', data.messages.length);
                    this.displayNewMessages(data.messages);
                    this.updateNotifications(data.unread_count || 0);
                    this.showNewMessageIndicator();
                }
                
                this.updateConnectionStatus(true);
                this.retryCount = 0;
            } else {
                const errorText = await response.text();
                console.error('❌ Failed to check messages:', response.status, errorText);
                this.handleConnectionError();
            }
        } catch (error) {
            console.error('❌ Error checking for new messages:', error);
            this.handleConnectionError();
        }
    }

    handleConnectionError() {
        this.retryCount++;
        this.updateConnectionStatus(false);
        
        if (this.retryCount >= this.maxRetries) {
            console.warn('⚠️ Max retries reached, reducing check frequency');
            this.checkFrequency = 10000;
            this.retryCount = 0;
            this.startPolling();
        }
    }

    async setupFormSubmission() {
        const form = document.querySelector('form[action*="reply"]');
        const textarea = document.querySelector('textarea[name="message"]');
        const submitButton = document.querySelector('button[type="submit"]');
        
        if (!form || !textarea || !submitButton) {
            console.warn('⚠️ Form elements not found for real-time submission');
            return;
        }

        console.log('📝 Form submission setup completed');
        console.log('📍 Form action:', form.action);

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const message = textarea.value.trim();
            if (!message) {
                console.log('⚠️ Empty message, skipping submission');
                return;
            }

            console.log('📤 Sending message:', message);
            await this.sendMessage(form, textarea, submitButton, message);
        });

        // إضافة دعم Ctrl+Enter
        textarea.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                form.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });
    }

    async sendMessage(form, textarea, submitButton, message) {
        const originalButtonText = submitButton.innerHTML;
        const sendingStatus = document.getElementById('sendingStatus');
        
        // تعطيل الـ form
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        textarea.disabled = true;
        
        if (sendingStatus) {
            sendingStatus.style.display = 'inline';
        }

        console.log('📤 Starting message submission...');
        console.log('🎯 Target URL:', form.action);

        try {
            const formData = new FormData(form);
            
            // إضافة CSRF token يدوياً
            formData.append('_token', this.getCSRFToken());
            
            console.log('📦 Form data prepared');
            console.log('🔑 CSRF Token:', this.getCSRFToken());

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCSRFToken(),
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            console.log('📡 Send response status:', response.status);
            console.log('📋 Send response headers:', Object.fromEntries(response.headers.entries()));

            if (response.ok) {
                const data = await response.json();
                console.log('✅ Message sent successfully:', data);
                
                if (data.success && data.message) {
                    // إضافة الرسالة فوراً
                    this.displayNewMessages([data.message]);
                    
                    // مسح النص
                    textarea.value = '';
                    
                    // تقليل حجم الـ textarea
                    this.autoResizeTextarea(textarea);
                    
                    // التمرير إلى أسفل
                    this.scrollToBottom();
                } else {
                    throw new Error('Server returned success but no message data');
                }
            } else {
                const errorText = await response.text();
                console.error('❌ Send failed with status:', response.status);
                console.error('❌ Error response:', errorText);
                
                // محاولة parse JSON للحصول على تفاصيل الخطأ
                try {
                    const errorData = JSON.parse(errorText);
                    throw new Error(errorData.error || errorData.message || `HTTP ${response.status}`);
                } catch (parseError) {
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }
            }
        } catch (error) {
            console.error('❌ Error sending message:', error);
            this.showErrorNotification('خطأ في إرسال الرسالة: ' + error.message);
        } finally {
            // إعادة تفعيل الـ form
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            textarea.disabled = false;
            textarea.focus();
            
            if (sendingStatus) {
                sendingStatus.style.display = 'none';
            }
        }
    }

    displayNewMessages(messages) {
        const messagesList = document.getElementById('messagesList');
        if (!messagesList) {
            console.error('❌ Messages list element not found');
            return;
        }

        let newMessagesAdded = false;

        messages.forEach(message => {
            if (message.id > this.lastMessageId) {
                console.log('➕ Adding new message:', message);
                
                const messageElement = this.createMessageElement(message);
                
                // إدراج قبل typing indicator إذا وجد
                const typingIndicator = document.getElementById('typingIndicator');
                if (typingIndicator) {
                    messagesList.insertBefore(messageElement, typingIndicator);
                } else {
                    messagesList.appendChild(messageElement);
                }
                
                this.lastMessageId = message.id;
                newMessagesAdded = true;
                
                // إضافة تأثير الظهور
                setTimeout(() => {
                    messageElement.style.opacity = '1';
                    messageElement.style.transform = 'translateY(0)';
                }, 50);
            }
        });

        if (newMessagesAdded) {
            this.updateMessageCount();
            this.playNotificationSound();
        }
    }

    createMessageElement(message) {
        const messageDiv = document.createElement('div');
        messageDiv.setAttribute('data-message-id', message.id);
        messageDiv.style.cssText = `
            margin-bottom: 2rem; display: flex; gap: 1rem;
            opacity: 0; transform: translateY(20px); transition: all 0.3s ease;
        `;
        
        // تحديد اتجاه الرسالة
        const isMyMessage = this.isAdmin ? message.is_from_admin : !message.is_from_admin;
        if (isMyMessage) {
            messageDiv.style.flexDirection = 'row-reverse';
        }

        // إنشاء الأفاتار
        const avatar = this.createAvatar(message);
        
        // إنشاء محتوى الرسالة
        const contentDiv = this.createMessageContent(message, isMyMessage);
        
        messageDiv.appendChild(avatar);
        messageDiv.appendChild(contentDiv);

        return messageDiv;
    }

    createAvatar(message) {
        const avatar = document.createElement('div');
        avatar.style.cssText = `
            width: 45px; height: 45px; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            color: white; font-weight: 600; font-size: 1rem; 
            flex-shrink: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        if (message.is_from_admin) {
            avatar.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
            avatar.innerHTML = '<i class="fas fa-headset"></i>';
        } else {
            avatar.style.background = 'linear-gradient(135deg, #0ea5e9, #0284c7)';
            avatar.textContent = message.avatar || message.user_name.charAt(0).toUpperCase();
        }

        return avatar;
    }

    createMessageContent(message, isMyMessage) {
        const contentDiv = document.createElement('div');
        contentDiv.style.cssText = 'flex: 1; max-width: 75%;';

        // معلومات المرسل
        const senderInfo = this.createSenderInfo(message, isMyMessage);
        
        // فقاعة الرسالة
        const messageBubble = this.createMessageBubble(message, isMyMessage);

        contentDiv.appendChild(senderInfo);
        contentDiv.appendChild(messageBubble);

        return contentDiv;
    }

    createSenderInfo(message, isMyMessage) {
        const senderInfo = document.createElement('div');
        senderInfo.style.cssText = `
            font-size: 0.75rem; font-weight: 600; color: #666; 
            margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem;
        `;
        
        if (isMyMessage) {
            senderInfo.style.justifyContent = 'flex-end';
        }

        const senderBadge = document.createElement('span');
        senderBadge.style.cssText = `
            padding: 2px 6px; border-radius: 0.375rem; font-size: 0.625rem; 
            text-transform: uppercase; letter-spacing: 0.5px;
        `;
        
        if (message.is_from_admin) {
            senderBadge.style.cssText += 'background: #dcfce7; color: #166534;';
            senderBadge.textContent = this.isAdmin ? 'You' : 'Support Team';
        } else {
            senderBadge.style.cssText += 'background: #dbeafe; color: #1e40af;';
            senderBadge.textContent = this.isAdmin ? message.user_name : 'You';
        }

        const timeSpan = document.createElement('span');
        timeSpan.style.cssText = 'font-size: 0.75rem; color: #666; display: flex; align-items: center; gap: 0.25rem;';
        timeSpan.innerHTML = `<i class="fas fa-clock"></i> ${message.created_at}`;

        senderInfo.appendChild(senderBadge);
        senderInfo.appendChild(timeSpan);

        return senderInfo;
    }

    createMessageBubble(message, isMyMessage) {
        const messageBubble = document.createElement('div');
        messageBubble.style.cssText = `
            padding: 1rem 1.5rem; border-radius: 1.5rem; margin-bottom: 0.25rem; 
            word-wrap: break-word; line-height: 1.6; position: relative; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        `;
        
        if (isMyMessage) {
            messageBubble.style.cssText += `
                background: linear-gradient(135deg, #0ea5e9, #0284c7); 
                color: white; border-bottom-right-radius: 0.375rem;
            `;
        } else {
            messageBubble.style.cssText += `
                background: white; color: #333; 
                border: 1px solid #e5e7eb; 
                border-bottom-left-radius: 0.375rem;
            `;
        }
        
        messageBubble.textContent = message.message;
        return messageBubble;
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
        if (indicator && !this.isAtBottom()) {
            indicator.classList.add('show');
        }
    }

    hideNewMessageIndicator() {
        const indicator = document.getElementById('newMessageIndicator');
        if (indicator) {
            indicator.classList.remove('show');
        }
    }

    isAtBottom() {
        const messagesList = document.getElementById('messagesList');
        if (!messagesList) return true;
        
        const threshold = 100;
        return messagesList.scrollTop + messagesList.clientHeight >= messagesList.scrollHeight - threshold;
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

        this.updatePageTitle(unreadCount);
    }

    updatePageTitle(unreadCount) {
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
            
            if (dot && text) {
                if (connected) {
                    dot.className = 'connection-dot connected';
                    text.textContent = 'Connected';
                } else {
                    dot.className = 'connection-dot disconnected';
                    text.textContent = 'Disconnected';
                }
            }
        }
    }

    playNotificationSound() {
        if (!this.shouldPlaySound()) return;
        
        try {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhCTGa2+m1bi8EJHzK7+ORSA0PUqfk7bFlHgg2jdXzzXkpBS12wuzZkT8LElyx6+2rWBULTKLh6WNHDTGLz/fbiTAKGGm/8+CK');
            audio.volume = 0.3;
            audio.play().catch(() => console.log('🔇 Sound play blocked by browser'));
        } catch (e) {
            console.log('🔇 Sound not available');
        }
    }

    shouldPlaySound() {
        return document.hidden || !document.hasFocus();
    }

    autoResizeTextarea(textarea) {
        if (textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 200) + 'px';
        }
    }

    setupVisibilityChange() {
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.isActive = false;
                console.log('👁️ Page hidden - reducing activity');
            } else {
                this.isActive = true;
                console.log('👁️ Page visible - resuming activity');
                setTimeout(() => {
                    if (this.isRealTimeEnabled) {
                        this.checkForNewMessages();
                    }
                }, 500);
            }
        });

        window.addEventListener('beforeunload', () => {
            this.destroy();
        });

        window.addEventListener('blur', () => {
            this.checkFrequency = 5000;
            this.startPolling();
        });

        window.addEventListener('focus', () => {
            this.checkFrequency = 3000;
            this.startPolling();
            if (this.isRealTimeEnabled) {
                setTimeout(() => this.checkForNewMessages(), 500);
            }
        });
    }

    toggle() {
        this.isRealTimeEnabled = !this.isRealTimeEnabled;
        
        const toggleButton = document.getElementById('realTimeToggle');
        if (toggleButton) {
            const span = toggleButton.querySelector('span');
            const icon = toggleButton.querySelector('i');
            
            if (this.isRealTimeEnabled) {
                this.startPolling();
                span.textContent = 'Disable Auto-refresh';
                icon.className = 'fas fa-sync-alt';
                toggleButton.style.background = '';
            } else {
                this.stopPolling();
                span.textContent = 'Enable Auto-refresh';
                icon.className = 'fas fa-play';
                toggleButton.style.background = '#fef3c7';
            }
        }
        
        console.log(`🔄 Real-time messaging ${this.isRealTimeEnabled ? 'enabled' : 'disabled'}`);
    }

    showErrorNotification(message) {
        console.error('🚨 Error notification:', message);
        
        // إزالة الإشعارات السابقة
        const existingNotifications = document.querySelectorAll('.error-notification');
        existingNotifications.forEach(notification => notification.remove());
        
        // إنشاء إشعار خطأ مؤقت
        const notification = document.createElement('div');
        notification.className = 'error-notification';
        notification.style.cssText = `
            position: fixed; top: 20px; right: 20px; z-index: 10000;
            background: #ef4444; color: white; padding: 1rem 1.5rem;
            border-radius: 0.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            max-width: 350px; word-wrap: break-word;
            animation: slideInRight 0.3s ease-out;
        `;
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-exclamation-triangle"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer; margin-left: auto;">×</button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // إزالة الإشعار بعد 7 ثواني
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }
        }, 7000);
    }

    getCSRFToken() {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        const token = metaTag ? metaTag.getAttribute('content') : '';
        
        if (!token) {
            console.error('❌ CSRF Token not found in meta tag');
        }
        
        return token;
    }

    destroy() {
        this.stopPolling();
        this.isActive = false;
        console.log('🗑️ Real-time messaging destroyed');
    }

    getStatus() {
        return {
            conversationId: this.conversationId,
            isAdmin: this.isAdmin,
            lastMessageId: this.lastMessageId,
            isActive: this.isActive,
            isRealTimeEnabled: this.isRealTimeEnabled,
            checkFrequency: this.checkFrequency,
            retryCount: this.retryCount
        };
    }
}

// ========================================
// Utility Functions with Debug
// ========================================

function initRealTimeMessaging() {
    console.log('🚀 Starting Real-time Messaging initialization...');
    
    // التحقق من وجود CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('❌ CSRF Token meta tag not found! Add this to your layout:');
        console.error('<meta name="csrf-token" content="{{ csrf_token() }}">');
        return false;
    }
    
    // استخراج معرف المحادثة من الـ URL
    const pathParts = window.location.pathname.split('/');
    const conversationId = pathParts[pathParts.length - 1];
    
    console.log('🔍 Path parts:', pathParts);
    console.log('🆔 Conversation ID:', conversationId);
    
    // تحديد ما إذا كان المستخدم أدمن أم لا
    const isAdmin = window.location.pathname.includes('/admin/');
    console.log('👤 Is Admin:', isAdmin);
    
    // التحقق من وجود صفحة المحادثة
    const messagesList = document.getElementById('messagesList');
    console.log('📋 Messages list found:', !!messagesList);
    
    if (messagesList && conversationId && !isNaN(conversationId)) {
        // إنشاء مثيل من نظام الرسائل الفورية
        window.realTimeMessaging = new RealTimeMessaging(conversationId, isAdmin);
        
        console.log('✅ Real-time messaging initialized successfully:', {
            conversationId,
            isAdmin,
            url: window.location.pathname
        });
        
        return true;
    }
    
    console.warn('⚠️ Real-time messaging not initialized - requirements not met');
    console.log('Requirements check:', {
        messagesList: !!messagesList,
        conversationId: conversationId,
        isValidId: !isNaN(conversationId)
    });
    return false;
}

// Helper functions
function scrollToBottom() {
    if (window.realTimeMessaging) {
        window.realTimeMessaging.scrollToBottom();
    } else {
        console.warn('⚠️ Real-time messaging not initialized');
    }
}

function toggleRealTimeMessaging() {
    if (window.realTimeMessaging) {
        window.realTimeMessaging.toggle();
    } else {
        console.warn('⚠️ Real-time messaging not initialized');
    }
}

function getRealTimeStatus() {
    if (window.realTimeMessaging) {
        return window.realTimeMessaging.getStatus();
    }
    return null;
}

// CSS Animations
function addRealTimeStyles() {
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .message-item {
            transition: all 0.3s ease;
        }
        
        .message-item:hover {
            transform: translateY(-1px);
        }
        
        .connection-dot {
            animation: connectionPulse 2s infinite;
        }
        
        @keyframes connectionPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .new-message-indicator {
            animation: indicatorBounce 0.5s ease-out;
        }
        
        @keyframes indicatorBounce {
            0% { transform: translateY(100px) scale(0.8); }
            50% { transform: translateY(-10px) scale(1.1); }
            100% { transform: translateY(0) scale(1); }
        }
    `;
    document.head.appendChild(style);
}

// Auto-initialization with Debug
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        console.log('📄 DOM Content Loaded');
        addRealTimeStyles();
        setTimeout(() => {
            initRealTimeMessaging();
        }, 1000); // زيادة التأخير للتأكد من تحميل كل شيء
    });
} else {
    console.log('📄 DOM Already Ready');
    addRealTimeStyles();
    setTimeout(() => {
        initRealTimeMessaging();
    }, 1000);
}

// Debug helper - يمكن استخدامها في console
window.debugRealTime = function() {
    console.log('🔧 Real-time Messaging Debug Info:');
    console.log('Status:', getRealTimeStatus());
    console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
    console.log('Current URL:', window.location.pathname);
    console.log('Messages List Element:', document.getElementById('messagesList'));
    console.log('Form Element:', document.querySelector('form[action*="reply"]'));
    console.log('Textarea Element:', document.querySelector('textarea[name="message"]'));
};