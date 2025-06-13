@extends('layouts.app')

@section('title', $conversation->title)

@section('content')
<div class="container" style="padding: 2rem 0; max-width: 1000px;">
    <!-- Back Link -->
    <a href="{{ route('user.conversations.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #0ea5e9; text-decoration: none; font-weight: 500; margin-bottom: 2rem; transition: color 0.2s ease;">
        <i class="fas fa-arrow-left"></i>
        Back to Conversations
    </a>

    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem;">
        <div style="display: flex; flex-direction: column; height: calc(100vh - 150px); min-height: 600px;">
            <!-- Conversation Header -->
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 1rem; padding: 2rem; margin-bottom: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-comment-dots" style="color: #0ea5e9;"></i>
                    {{ $conversation->title }}
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
                        {{ $messages->count() }} messages
                    </span>
                </div>
            </div>

            <!-- Messages Container -->
            <div style="flex: 1; background: white; border: 1px solid #e5e7eb; border-radius: 1rem; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                
                <!-- Messages Header -->
                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; background: #f8f9fa; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-comments"></i>
                        Conversation with Support Team
                    </h3>
                    <button onclick="scrollToBottom()" style="background: #6b7280; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.75rem; cursor: pointer;">
                        <i class="fas fa-arrow-down"></i>
                        Latest
                    </button>
                </div>

                <!-- Messages List -->
                <div id="messagesList" style="flex: 1; padding: 1.5rem; overflow-y: auto; background: linear-gradient(180deg, white 0%, #f8f9fa 100%);">
                    @foreach($messages as $message)
                    <div style="margin-bottom: 2rem; display: flex; gap: 1rem; {{ $message->is_from_admin ? '' : 'flex-direction: row-reverse;' }}">
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
                </div>

                <!-- Reply Form -->
                <form action="{{ route('user.conversations.reply', $conversation) }}" method="POST" style="border-top: 1px solid #e5e7eb; padding: 1.5rem; background: white;">
                    @csrf
                    <div style="display: flex; gap: 1rem; align-items: flex-end;">
                        <textarea 
                            name="message" 
                            style="flex: 1; min-height: 60px; max-height: 120px; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; background: white; color: #333; font-size: 0.875rem; font-family: inherit; resize: none; transition: all 0.2s ease;"
                            placeholder="Type your message here..."
                            required
                            onkeydown="handleKeyDown(event)"
                            oninput="autoResize(this)"
                            onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14, 165, 233, 0.1)';"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                        ></textarea>
                        <button type="submit" style="padding: 1rem; background: linear-gradient(135deg, #0ea5e9, #0284c7); color: white; border: none; border-radius: 0.75rem; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; min-width: 50px; height: 50px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #666; display: flex; justify-content: space-between; align-items: center;">
                        <span>
                            <i class="fas fa-info-circle"></i>
                            Press Ctrl+Enter to send quickly
                        </span>
                        <span id="typingIndicator" style="display: none; color: #0ea5e9;">
                            <i class="fas fa-pencil-alt"></i>
                            Typing...
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
                        <span style="color: #333; font-size: 0.875rem; font-weight: 500;">{{ $messages->count() }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 500; color: #666; font-size: 0.875rem;">Created:</span>
                        <span style="color: #333; font-size: 0.875rem; font-weight: 500;">{{ $conversation->created_at->format('M d, Y') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0;">
                        <span style="font-weight: 500; color: #666; font-size: 0.875rem;">Last Reply:</span>
                        <span style="color: #333; font-size: 0.875rem; font-weight: 500;">{{ $conversation->updated_at->diffForHumans() }}</span>
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

<style>
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

/* Mobile responsiveness */
@media (max-width: 768px) {
    .container {
        padding: 1rem 0.5rem !important;
    }
    
    div[style*="grid-template-columns: 1fr 300px"] {
        grid-template-columns: 1fr !important;
    }
    
    div[style*="height: calc(100vh - 150px)"] {
        height: calc(100vh - 300px) !important;
        min-height: 400px !important;
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
</style>

<script type="text/javascript">
function scrollToBottom() {
    const messagesList = document.getElementById('messagesList');
    if (messagesList) {
        messagesList.scrollTop = messagesList.scrollHeight;
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
            form.submit();
        }
    }
}

window.addEventListener('DOMContentLoaded', function() {
    // Auto-scroll to bottom
    scrollToBottom();
    
    // Focus on textarea
    const textarea = document.querySelector('textarea[name="message"]');
    if (textarea) {
        textarea.focus();
    }
    
    // Typing indicator
    let timer;
    const indicator = document.getElementById('typingIndicator');
    
    if (textarea && indicator) {
        textarea.addEventListener('input', function() {
            indicator.style.display = 'block';
            clearTimeout(timer);
            timer = setTimeout(function() {
                indicator.style.display = 'none';
            }, 2000);
        });
    }
    
    // Auto refresh every 20 seconds
    setInterval(function() {
        const messages = document.querySelectorAll('#messagesList > div');
        console.log('Current messages count:', messages.length);
    }, 20000);
});
</script>
@endsection