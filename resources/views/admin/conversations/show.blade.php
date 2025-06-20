@extends('layouts.admin')

@section('title', 'تفاصيل المحادثة')
@section('page-title', 'محادثة مع ' . $conversation->user->name)

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    <a href="{{ route('admin.conversations.index') }}" class="breadcrumb-link">المحادثات</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-left"></i>
    {{ Str::limit($conversation->title, 30) }}
</div>
@endsection

@push('styles')
<style>
/* متغيرات التصميم */
:root {
    --admin-primary-500: #3b82f6;
    --admin-primary-600: #2563eb;
    --admin-secondary-50: #f8fafc;
    --admin-secondary-100: #f1f5f9;
    --admin-secondary-200: #e2e8f0;
    --admin-secondary-300: #cbd5e1;
    --admin-secondary-600: #475569;
    --admin-secondary-700: #334155;
    --admin-secondary-900: #0f172a;
    
    --success-100: #dcfce7;
    --success-500: #22c55e;
    --success-600: #16a34a;
    --success-700: #15803d;
    
    --error-50: #fef2f2;
    --error-200: #fecaca;
    --error-500: #ef4444;
    --error-700: #b91c1c;
    
    --warning-100: #fef3c7;
    --info-500: #3b82f6;
    
    --space-xs: 0.25rem;
    --space-sm: 0.5rem;
    --space-md: 1rem;
    --space-lg: 1.5rem;
    --space-xl: 2rem;
    --space-2xl: 3rem;
    --space-3xl: 4rem;
    
    --radius-sm: 0.25rem;
    --radius-md: 0.375rem;
    --radius-lg: 0.5rem;
    --radius-xl: 1rem;
    
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    
    --transition-fast: 150ms ease-in-out;
    --transition-normal: 300ms ease-in-out;
}

/* تنسيقات عامة للصفحة */
body {
    direction: rtl;
    text-align: right;
}

/* تحسينات CSS للرسائل الفورية */
.message-sending {
    opacity: 0.7;
    pointer-events: none;
}

.new-message-indicator {
    position: fixed;
    bottom: 100px;
    left: 20px;
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

/* تأثير التمرير السلس المحسن */
.smooth-scroll {
    scroll-behavior: smooth;
}

.auto-scroll-indicator {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: var(--admin-primary-500);
    color: white;
    padding: 0.5rem;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: var(--shadow-lg);
    transition: all 0.3s ease;
    opacity: 0;
    transform: scale(0.8);
}

.auto-scroll-indicator.show {
    opacity: 1;
    transform: scale(1);
}

.auto-scroll-indicator:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}

/* Base Styles */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: var(--transition-fast);
}

.btn-primary {
    background: var(--admin-primary-500);
    color: white;
}

.btn-primary:hover {
    background: var(--admin-primary-600);
}

.btn-secondary {
    background: var(--admin-secondary-200);
    color: var(--admin-secondary-700);
}

.btn-success {
    background: var(--success-500);
    color: white;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-success {
    background: var(--success-100);
    color: var(--success-700);
}

.badge-danger {
    background: var(--error-200);
    color: var(--error-700);
}

.badge-warning {
    background: var(--warning-100);
    color: #92400e;
}

.badge-secondary {
    background: var(--admin-secondary-100);
    color: var(--admin-secondary-700);
}

.card {
    background: white;
    border: 1px solid var(--admin-secondary-200);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    transition: var(--transition-normal);
}

.card-header {
    padding: var(--space-lg);
    border-bottom: 1px solid var(--admin-secondary-200);
    background: var(--admin-secondary-50);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-body {
    padding: var(--space-lg);
}

.card-title {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--admin-secondary-900);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.form-input {
    width: 100%;
    padding: var(--space-md);
    border: 2px solid var(--admin-secondary-300);
    border-radius: var(--radius-md);
    font-family: inherit;
    transition: var(--transition-fast);
}

.form-input:focus {
    outline: none;
    border-color: var(--admin-primary-500);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

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
@endpush

@section('content')
<div style="padding: var(--space-xl);">
    <!-- CSRF Token for JavaScript -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-2xl);">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--admin-secondary-900); margin-bottom: var(--space-sm);">
                <i class="fas fa-comment-dots" style="color: var(--admin-primary-500); margin-left: var(--space-sm);"></i>
                {{ $conversation->title }}
                @if(!$conversation->is_read_by_admin)
                    <span class="badge badge-danger">غير مقروء</span>
                @else
                    <span class="badge badge-success">مقروء</span>
                @endif
                <span class="unread-count" id="unreadCount" style="display: none;">0</span>
            </h1>
            <p style="color: var(--admin-secondary-600); font-size: 0.875rem;">
                محادثة مع {{ $conversation->user->name }} • <span id="messageCount">{{ $messages->count() }}</span> رسائل
            </p>
        </div>
        
        <div style="display: flex; gap: var(--space-md);">
            <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i>
                العودة للمحادثات
            </a>
            @if(!$conversation->is_read_by_admin)
            <form action="{{ route('admin.conversations.mark-read', $conversation) }}" method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i>
                    تعليم كمقروء
                </button>
            </form>
            @endif
            <span id="connectionStatus" class="connection-status">
                <div class="connection-dot connected"></div>
                <span>متصل</span>
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
            <div class="card" style="flex: 1; display: flex; flex-direction: column; overflow: hidden; position: relative;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-comments"></i>
                        رسائل المحادثة
                    </h3>
                    <button onclick="scrollToBottom()" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-down"></i>
                        أحدث الرسائل
                    </button>
                </div>
                
                <!-- Messages List -->
                <div id="messagesList" class="smooth-scroll" style="flex: 1; padding: var(--space-lg); overflow-y: auto; background: linear-gradient(180deg, white 0%, var(--admin-secondary-50) 100%); position: relative;">
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
                                    <span style="padding: 2px 8px; border-radius: var(--radius-sm); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; background: var(--success-100); color: var(--success-700);">المشرف</span>
                                @else
                                    <span style="padding: 2px 8px; border-radius: var(--radius-sm); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; background: var(--admin-primary-100); color: var(--admin-primary-700);">{{ $conversation->user->name }}</span>
                                @endif
                                <span style="font-size: 0.75rem; color: var(--admin-secondary-500); display: flex; align-items: center; gap: var(--space-xs);">
                                    <i class="fas fa-clock"></i>
                                    {{ $message->created_at->format('M d, Y h:i A') }}
                                </span>
                            </div>
                            
                            <!-- Message Bubble -->
                            <div style="padding: var(--space-md) var(--space-lg); border-radius: var(--radius-xl); margin-bottom: var(--space-xs); word-wrap: break-word; line-height: 1.6; position: relative; box-shadow: var(--shadow-sm); {{ $message->is_from_admin ? 'background: linear-gradient(135deg, var(--success-500), var(--success-600)); color: white; border-bottom-left-radius: var(--radius-sm);' : 'background: white; color: var(--admin-secondary-900); border: 1px solid var(--admin-secondary-200); border-bottom-right-radius: var(--radius-sm);' }}">
                                {{ $message->message }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Typing Indicator -->
                    <div id="typingIndicator" class="typing-indicator">
                        <i class="fas fa-pencil-alt"></i>
                        العميل يكتب الآن...
                    </div>
                </div>
                
                <!-- Auto Scroll Indicator -->
                <div id="autoScrollIndicator" class="auto-scroll-indicator" onclick="scrollToBottom()">
                    <i class="fas fa-arrow-down"></i>
                </div>
                
                <!-- Reply Form -->
                <div style="border-top: 1px solid var(--admin-secondary-200); padding: var(--space-lg); background: var(--admin-secondary-50);">
                    <form action="{{ route('admin.conversations.reply', $conversation) }}" method="POST" id="messageForm">
                        @csrf
                        <div style="margin-bottom: var(--space-md);">
                            <label style="display: block; margin-bottom: var(--space-sm); font-weight: 500; color: var(--admin-secondary-700);">
                                <i class="fas fa-reply"></i>
                                ردك
                            </label>
                            <textarea 
                                name="message" 
                                id="messageTextarea"
                                class="form-input"
                                style="width: 100%; min-height: 100px; padding: var(--space-md); border: 2px solid var(--admin-secondary-300); border-radius: var(--radius-lg); font-family: inherit; resize: vertical; transition: all var(--transition-fast);"
                                placeholder="اكتب ردك هنا..."
                                required
                                onkeydown="handleKeyDown(event)"
                                oninput="autoResize(this)"
                                onfocus="this.style.borderColor='var(--admin-primary-500)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                                onblur="this.style.borderColor='var(--admin-secondary-300)'; this.style.boxShadow='none';"
                            >{{ old('message') }}</textarea>
                            
                            <!-- Error Display -->
                            @if ($errors->any())
                            <div style="margin-top: var(--space-sm); padding: var(--space-sm); background: var(--error-50); border: 1px solid var(--error-200); border-radius: var(--radius-md); color: var(--error-700);">
                                <ul style="margin: 0; padding-right: var(--space-md);">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="font-size: 0.875rem; color: var(--admin-secondary-600);">
                                <i class="fas fa-info-circle"></i>
                                سيتم إرسال هذا إلى {{ $conversation->user->name }}
                                <span id="sendingStatus" style="display: none; margin-right: var(--space-sm); color: var(--admin-primary-500);">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    جاري الإرسال...
                                </span>
                            </div>
                            <div style="display: flex; gap: var(--space-sm); align-items: center;">
                                <button type="button" onclick="toggleRealTimeMessaging()" id="realTimeToggle" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-sync-alt"></i>
                                    <span>تعطيل التحديث التلقائي</span>
                                </button>
                                <button type="submit" id="sendButton" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                    إرسال الرد
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
                        معلومات العميل
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
                            <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">عضو منذ:</span>
                            <span style="color: var(--admin-secondary-900); font-size: 0.875rem;">{{ $conversation->user->created_at->format('M d, Y') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0;">
                            <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">الدور:</span>
                            <span class="badge badge-secondary">{{ ucfirst($conversation->user->role ?? 'عميل') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversation Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        تفاصيل المحادثة
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-100);">
                        <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">الحالة:</span>
                        <span>
                            @if(!$conversation->is_read_by_admin)
                                <span class="badge badge-warning">بحاجة لاهتمام</span>
                            @else
                                <span class="badge badge-success">تمت معالجته</span>
                            @endif
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-100);">
                        <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">الرسائل:</span>
                        <span style="color: var(--admin-secondary-900); font-size: 0.875rem;" id="sidebarMessageCount">{{ $messages->count() }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-100);">
                        <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">بدأت في:</span>
                        <span style="color: var(--admin-secondary-900); font-size: 0.875rem;">{{ $conversation->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0;">
                        <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">آخر تحديث:</span>
                        <span style="color: var(--admin-secondary-900); font-size: 0.875rem;" id="lastReplyTime">{{ $conversation->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt"></i>
                        إجراءات سريعة
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
                                تعليم كمقروء
                            </button>
                        </form>
                        @endif
                        
                        <a href="{{ route('admin.users.show', $conversation->user) }}" class="btn btn-secondary" style="width: 100%; text-decoration: none;">
                            <i class="fas fa-user"></i>
                            عرض ملف العميل
                        </a>
                        
                        <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary" style="width: 100%; text-decoration: none;">
                            <i class="fas fa-arrow-right"></i>
                            العودة للمحادثات
                        </a>
                        
                        <button onclick="window.print()" class="btn btn-secondary" style="width: 100%;">
                            <i class="fas fa-print"></i>
                            طباعة المحادثة
                        </button>
                    </div>
                </div>
            </div>

            <!-- Support Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle"></i>
                        إرشادات الدعم
                    </h3>
                </div>
                <div class="card-body">
                    <div style="font-size: 0.875rem; color: var(--admin-secondary-700); line-height: 1.6;">
                        <div style="margin-bottom: var(--space-md);">
                            <strong>أهداف الرد:</strong>
                            <ul style="margin: var(--space-sm) 0 0 var(--space-lg); padding: 0;">
                                <li>الرد الأول: أقل من ساعتين</li>
                                <li>الحل: أقل من 24 ساعة</li>
                                <li>المتابعة: خلال 48 ساعة</li>
                            </ul>
                        </div>
                        <div>
                            <strong>نصائح:</strong>
                            <ul style="margin: var(--space-sm) 0 0 var(--space-lg); padding: 0;">
                                <li>كن متعاطفًا ومحترفًا</li>
                                <li>قدم حلولًا واضحة وقابلة للتنفيذ</li>
                                <li>اطلب التوضيح إذا لزم الأمر</li>
                                <li>تابع للتأكد من الرضا</li>
                            </ul>
                        </div>
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
// نظام الرسائل الفورية للأدمن (نسخة عربية)
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
        this.autoScroll = true; // التمرير التلقائي
        this.isUserScrolledUp = false; // تتبع إذا كان المستخدم مرر لأعلى
        
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
        
        // إعداد مراقبة التمرير
        this.setupScrollDetection();
        
        console.log('تم تهيئة نظام الرسائل الفورية للأدمن');
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

    setupScrollDetection() {
        const messagesList = document.getElementById('messagesList');
        const autoScrollIndicator = document.getElementById('autoScrollIndicator');
        
        if (!messagesList) return;

        messagesList.addEventListener('scroll', () => {
            const scrollTop = messagesList.scrollTop;
            const scrollHeight = messagesList.scrollHeight;
            const clientHeight = messagesList.clientHeight;
            
            // تحقق إذا كان المستخدم قريب من الأسفل (ضمن 100px)
            const isNearBottom = scrollTop + clientHeight >= scrollHeight - 100;
            
            this.isUserScrolledUp = !isNearBottom;
            this.autoScroll = isNearBottom;
            
            // إظهار/إخفاء مؤشر التمرير التلقائي
            if (autoScrollIndicator) {
                if (this.isUserScrolledUp) {
                    autoScrollIndicator.classList.add('show');
                } else {
                    autoScrollIndicator.classList.remove('show');
                }
            }
        });
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
                    // عرض الرسائل الجديدة من العميل فقط (ليس من الأدمن)
                    const customerMessages = data.messages.filter(msg => !msg.is_from_admin);
                    if (customerMessages.length > 0) {
                        this.displayNewMessages(customerMessages);
                        this.showNewMessageIndicator();
                    }
                    
                    // تحديث آخر معرف رسالة
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
            console.error('خطأ في التحقق من الرسائل الجديدة:', error);
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
                console.log('الرسالة موجودة بالفعل، تخطي:', message.id);
                return;
            }

            const messageElement = this.createMessageElement(message);
            
            // إدراج قبل typing indicator إذا وجد
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator) {
                messagesList.insertBefore(messageElement, typingIndicator);
            } else {
                messagesList.appendChild(messageElement);
            }
            
            // إضافة تأثير الظهور
            setTimeout(() => {
                messageElement.style.opacity = '1';
                messageElement.style.transform = 'translateY(0)';
            }, 50);

            // تحديث العدادات
            this.updateMessageCount();
        });

        // تشغيل صوت إشعار
        this.playNotificationSound();
        
        // التمرير التلقائي إذا كان المستخدم لم يمرر لأعلى
        if (this.autoScroll) {
            setTimeout(() => {
                this.scrollToBottom(true); // تمرير سلس
            }, 100);
        }
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
            senderBadge.textContent = 'المشرف';
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
                color: white; border-bottom-left-radius: var(--radius-sm);
            `;
        } else {
            messageBubble.style.cssText += `
                background: white; color: var(--admin-secondary-900); 
                border: 1px solid var(--admin-secondary-200); 
                border-bottom-right-radius: var(--radius-sm);
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

        let isSubmitting = false;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (isSubmitting) return;
            
            const message = textarea.value.trim();
            if (!message) {
                alert('الرجاء إدخال رسالة قبل الإرسال.');
                return;
            }

            isSubmitting = true;
            
            // تعطيل الـ form
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            textarea.disabled = true;
            if (sendingStatus) sendingStatus.style.display = 'inline';

            try {
                // إنشاء FormData بشكل صحيح
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('message', message);
                
                console.log('الأدمن يرسل رسالة:', message);
                console.log('مسار النموذج:', form.action);

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('حالة الاستجابة:', response.status);
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('بيانات الاستجابة:', data);
                    
                    if (data.success && data.message) {
                        // إضافة الرسالة فوراً
                        this.displayNewMessages([data.message]);
                        
                        // تحديث آخر معرف رسالة
                        this.lastMessageId = data.message.id;
                        
                        // مسح النص
                        textarea.value = '';
                        
                        // تقليل حجم الـ textarea
                        textarea.style.height = 'auto';
                        
                        // التمرير إلى أسفل (فوري للرسائل المرسلة)
                        setTimeout(() => {
                            this.scrollToBottom(true);
                        }, 100);
                    } else {
                        throw new Error(data.error || 'فشل في إرسال الرسالة');
                    }
                } else {
                    const errorData = await response.json();
                    console.error('استجابة الخطأ:', errorData);
                    throw new Error(errorData.error || `HTTP ${response.status}`);
                }
            } catch (error) {
                console.error('خطأ في إرسال الرسالة:', error);
                alert('خطأ في إرسال الرسالة: ' + error.message + '. يرجى المحاولة مرة أخرى.');
            } finally {
                // إعادة تفعيل الـ form
                isSubmitting = false;
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-paper-plane"></i> إرسال الرد';
                textarea.disabled = false;
                textarea.focus();
                if (sendingStatus) sendingStatus.style.display = 'none';
            }
        });
    }

    scrollToBottom(smooth = true) {
        const messagesList = document.getElementById('messagesList');
        const autoScrollIndicator = document.getElementById('autoScrollIndicator');
        
        if (messagesList) {
            if (smooth) {
                messagesList.scrollTo({
                    top: messagesList.scrollHeight,
                    behavior: 'smooth'
                });
            } else {
                messagesList.scrollTop = messagesList.scrollHeight;
            }
            
            // إخفاء مؤشر التمرير
            if (autoScrollIndicator) {
                autoScrollIndicator.classList.remove('show');
            }
            
            // تحديث حالة التمرير
            this.isUserScrolledUp = false;
            this.autoScroll = true;
        }
        this.hideNewMessageIndicator();
    }

    showNewMessageIndicator() {
        const indicator = document.getElementById('newMessageIndicator');
        if (indicator && this.isUserScrolledUp) {
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
                text.textContent = 'متصل';
            } else {
                dot.className = 'connection-dot disconnected';
                text.textContent = 'غير متصل';
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
                span.textContent = 'تعطيل التحديث التلقائي';
                toggleButton.style.background = '';
            } else {
                this.stopPolling();
                span.textContent = 'تمكين التحديث التلقائي';
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
// دوال مساعدة
// ========================================

function scrollToBottom(smooth = true) {
    if (window.realTimeMessaging) {
        window.realTimeMessaging.scrollToBottom(smooth);
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
// التهيئة التلقائية
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('تم تحميل الصفحة، جاري التهيئة...');
    
    // استخراج معرف المحادثة من الـ URL
    const pathParts = window.location.pathname.split('/');
    const conversationId = pathParts[pathParts.length - 1];
    
    // التحقق من وجود صفحة المحادثة
    const messagesList = document.getElementById('messagesList');
    
    console.log('معرف المحادثة:', conversationId);
    console.log('تم العثور على قائمة الرسائل:', !!messagesList);
    
    if (messagesList && conversationId && !isNaN(conversationId)) {
        // إنشاء مثيل من نظام الرسائل الفورية للأدمن
        window.realTimeMessaging = new RealTimeMessaging(conversationId, true);
        
        console.log('بدأ نظام الرسائل الفورية لمحادثة الأدمن:', conversationId);
        
        // التمرير إلى أسفل عند التحميل
        setTimeout(() => {
            scrollToBottom(false); // تمرير فوري عند التحميل
        }, 500);
        
        // تركيز على حقل النص
        const textarea = document.getElementById('messageTextarea');
        if (textarea) {
            textarea.focus();
        }
    } else {
        console.error('فشل في تهيئة نظام الرسائل الفورية للأدمن:', {
            messagesList: !!messagesList,
            conversationId,
            isValidId: !isNaN(conversationId)
        });
    }
});
</script>
@endpush