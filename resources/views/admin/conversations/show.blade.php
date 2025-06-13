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

@section('content')
<div style="padding: var(--space-xl);">
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
            </h1>
            <p style="color: var(--admin-secondary-600); font-size: 0.875rem;">
                Conversation with {{ $conversation->user->name }} • {{ $messages->count() }} messages
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
        </div>
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
                    <div style="margin-bottom: var(--space-xl); display: flex; gap: var(--space-md); {{ $message->is_from_admin ? 'flex-direction: row-reverse;' : '' }}">
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
                </div>
                
                <!-- Reply Form -->
                <div style="border-top: 1px solid var(--admin-secondary-200); padding: var(--space-lg); background: var(--admin-secondary-50);">
                    <form action="{{ route('admin.conversations.reply', $conversation) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: var(--space-md);">
                            <label style="display: block; margin-bottom: var(--space-sm); font-weight: 500; color: var(--admin-secondary-700);">
                                <i class="fas fa-reply"></i>
                                Your Reply
                            </label>
                            <textarea 
                                name="message" 
                                class="form-input"
                                style="width: 100%; min-height: 100px; padding: var(--space-md); border: 2px solid var(--admin-secondary-300); border-radius: var(--radius-lg); font-family: inherit; resize: vertical; transition: all var(--transition-fast);"
                                placeholder="Type your reply here..."
                                required
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
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                Send Reply
                            </button>
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
                            <span style="color: var(--admin-secondary-900); font-size: 0.875rem;">{{ $conversation->user->created_at->format('M d, Y') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-100);">
                            <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">Total Orders:</span>
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
                        <span style="color: var(--admin-secondary-900); font-size: 0.875rem;">{{ $messages->count() }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--admin-secondary-100);">
                        <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">Started:</span>
                        <span style="color: var(--admin-secondary-900); font-size: 0.875rem;">{{ $conversation->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-sm) 0;">
                        <span style="font-weight: 500; color: var(--admin-secondary-700); font-size: 0.875rem;">Last Updated:</span>
                        <span style="color: var(--admin-secondary-900); font-size: 0.875rem;">{{ $conversation->updated_at->diffForHumans() }}</span>
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

<script type="text/javascript">
function scrollToBottom() {
    const messagesList = document.getElementById('messagesList');
    if (messagesList) {
        messagesList.scrollTop = messagesList.scrollHeight;
    }
}

// Auto-scroll to bottom on page load
window.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    
    // Focus on the reply textarea
    const textarea = document.querySelector('textarea[name="message"]');
    if (textarea) {
        textarea.focus();
    }
    
    // Auto-refresh for new messages every 15 seconds
    setInterval(function() {
        // You can implement AJAX to check for new messages
        console.log('Checking for new messages in conversation...');
    }, 15000);
    
    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Mark conversation as read when user scrolls to bottom
let hasScrolledToBottom = false;
document.getElementById('messagesList').addEventListener('scroll', function() {
    const element = this;
    if (element.scrollTop + element.clientHeight >= element.scrollHeight - 10 && !hasScrolledToBottom) {
        hasScrolledToBottom = true;
        // You can make an AJAX call here to mark as read
        console.log('User has read all messages');
    }
});
</script>
@endsection