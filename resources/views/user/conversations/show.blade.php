@extends('layouts.app')

@section('content')
<div class="conversation-page py-5">
    <div class="container">
        <!-- Conversation Header -->
        <div class="conversation-header mb-4">
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <div class="conversation-info p-4">
                                <div class="d-flex align-items-center">
                                    <div class="conversation-avatar me-3">
                                        <div class="avatar-outline">
                                            <div class="avatar-inner rounded-circle d-flex align-items-center justify-content-center text-white">
                                                {{ strtoupper(substr($conversation->title, 0, 1)) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h1 class="h2 fw-bold mb-0" style="color: #a18664;">{{ $conversation->title }}</h1>
                                        <div class="conversation-meta d-flex align-items-center flex-wrap mt-2">
                                           
                                          
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="conversation-actions h-100 d-flex align-items-center justify-content-center justify-content-md-end p-4" style="background-color: rgba(161, 134, 100, 0.05);">
                                <a href="{{ route('user.conversations.index') }}" class="btn btn-outline-secondary btn-icon-split rounded-pill me-2">
                                    <span class="icon">
                                        <i class="fas fa-arrow-left"></i>
                                    </span>
                                    <span class="text">Back</span>
                                </a>
                                <div class="dropdown">
                                  
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline & Message Content -->
        <div class="row g-4">
            <!-- Sidebar Timeline -->
            <div class="col-lg-3">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-header text-white py-3 px-4 rounded-top-4" style="background-color: #a18664;">
                        <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i> Timeline</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="chat-timeline p-3">
                            <div class="timeline-stream">
                                @foreach($messages as $index => $message)
                                    <div class="timeline-item {{ $loop->last ? 'last-item' : '' }}">
                                        <div class="timeline-marker {{ $message->is_from_admin ? 'admin-marker' : 'user-marker' }}"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1 fw-bold">{{ $message->is_from_admin ? 'Support Team' : 'You' }}</h6>
                                            <p class="text-muted small mb-0">{{ $message->created_at->format('M d, h:i A') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="col-lg-9">
                <div class="chat-container">
                    <!-- Messages Section -->
                    <div class="card border-0 rounded-4 p-3 shadow-sm mb-4">
                        <div class="card-header p-3 rounded-top-4" style="background-color: #a18664;">
                        <ul class="nav nav-tabs card-header-tabs border-0 w-100 justify-content-center">
                        <li class="nav-item">
                                <a class="nav-link active text-white fw-bold rounded-top-0 bg-transparent border-0">
                                <i class="fas fa-comment-dots me-2"></i> Messages
                                    </a>
                             
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4" id="conversation-messages">
                            <div class="chat-messages">
                                @foreach($messages as $message)
                                    <div class="message-wrapper {{ $message->is_from_admin ? 'admin-wrapper' : 'user-wrapper' }}">
                                        <div class="message-container {{ $message->is_from_admin ? 'admin-container' : 'user-container' }}">
                                            <div class="message-sender">
                                                <div class="avatar {{ $message->is_from_admin ? 'admin-avatar' : 'user-avatar' }}">
                                                    {!! $message->is_from_admin ? '<i class="fas fa-headset"></i>' : '<i class="fas fa-user"></i>' !!}
                                                </div>
                                            </div>
                                            <div class="message-content">
                                                <div class="message-header">
                                                    <span class="sender-name">{{ $message->is_from_admin ? 'Support Team' : 'You' }}</span>
                                                    <span class="message-time">
                                                        <i class="far fa-clock me-1"></i>{{ $message->created_at->format('M d, Y h:i A') }}
                                                    </span>
                                                </div>
                                                <div class="message-bubble {{ $message->is_from_admin ? 'admin-bubble' : 'user-bubble' }}">
                                                    <p class="mb-0">{{ $message->message }}</p>
                                                </div>
                                                <div class="message-actions">
                                                    <button type="button" class="btn btn-sm text-muted" data-bs-toggle="tooltip" title="Copy message">
                                                        <i class="far fa-copy"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm text-muted" data-bs-toggle="tooltip" title="Quote message">
                                                        <i class="fas fa-quote-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Reply Section -->
                    <div class="card border-0 rounded-4 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-3" style="color: #a18664;"><i class="fas fa-reply me-2"></i>Your Response</h5>
                            <form action="{{ route('user.conversations.reply', $conversation) }}" method="POST" class="reply-form">
                                @csrf
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" style="height: 120px; border-radius: 15px;" 
                                              placeholder="Type your message here..." required>{{ old('message') }}</textarea>
                                        <label for="message">Type your message here...</label>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="message-tools">
                                        <button type="button" class="btn btn-light rounded-circle me-2" data-bs-toggle="tooltip" title="Attach file">
                                            <i class="fas fa-paperclip"></i>
                                        </button>
                                        <button type="button" class="btn btn-light rounded-circle me-2" data-bs-toggle="tooltip" title="Emoji">
                                            <i class="far fa-smile"></i>
                                        </button>
                                    </div>
                                    <button type="submit" class="btn btn-lg rounded-pill px-4" style="background-color: #a18664; color: white;">
                                        <i class="fas fa-paper-plane me-2"></i> Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Premium Conversation Styles */
    :root {
        --primary: #a18664;       /* Darker brown */
        --primary-medium: #c1a276; /* Medium brown */
        --primary-light: #e6cfae; /* Lighter brown */
        --primary-fade: rgba(161, 134, 100, 0.1);
        --secondary: #858796;
        --success: #1cc88a;
        --info: #c1a276;         /* Using medium brown for info color */
        --light-bg: #f8f9fc;
        --dark-bg: #5a5c69;
        --white: #fff;
        --shadow: 0 .15rem 1.75rem 0 rgba(161, 134, 100, .15);
        --border-radius: 1rem;
    }
    
    /* Avatar Styling */
    .avatar-outline {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(145deg, var(--primary), #8a7254);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(161, 134, 100, 0.3);
    }
    
    .avatar-inner {
        width: 50px;
        height: 50px;
        font-size: 1.4rem;
        font-weight: bold;
    }
    
    /* Button Styling */
    .btn-icon-split .icon {
        background: rgba(0, 0, 0, 0.1);
        display: inline-block;
        padding: .375rem .75rem;
        border-radius: 50rem 0 0 50rem;
        margin-right: -1px;
    }
    
    .btn-icon-split .text {
        display: inline-block;
        padding: .375rem .75rem;
        border-radius: 0 50rem 50rem 0;
    }
    
    /* Timeline Styling */
    .chat-timeline {
        height: 100%;
        max-height: 600px;
        overflow-y: auto;
    }
    
    .timeline-stream {
        position: relative;
        padding-left: 1.5rem;
    }
    
    .timeline-stream:before {
        content: '';
        position: absolute;
        top: 0;
        left: 7px;
        height: 100%;
        width: 2px;
        background-color: rgba(161, 134, 100, 0.2);
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-marker {
        position: absolute;
        top: 0;
        left: -1.5rem;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 2px solid var(--white);
        box-shadow: 0 0 0 2px rgba(161, 134, 100, 0.2);
    }
    
    .admin-marker {
        background-color: var(--info);
    }
    
    .user-marker {
        background-color: var(--primary);
    }
    
    .timeline-content {
        padding-left: 0.5rem;
    }
    
    .last-item .timeline-marker {
        box-shadow: 0 0 0 4px rgba(161, 134, 100, 0.2);
    }
    
    /* Message Styling */
    .chat-messages {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        max-height: 500px;
        overflow-y: auto;
        padding: 1rem 0;
    }
    
    .message-wrapper {
        display: flex;
        flex-direction: column;
    }
    
    .message-container {
        display: flex;
        max-width: 85%;
    }
    
    .admin-wrapper {
        align-self: flex-start;
    }
    
    .user-wrapper {
        align-self: flex-end;
    }
    
    .user-container {
        flex-direction: row-reverse;
        margin-left: auto;
    }
    
    .message-sender {
        display: flex;
        align-items: flex-start;
        margin: 0 1rem;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .admin-avatar {
        background: linear-gradient(145deg, var(--info), #a58b5e);
    }
    
    .user-avatar {
        background: linear-gradient(145deg, var(--primary), #8a7254);
    }
    
    .message-content {
        display: flex;
        flex-direction: column;
    }
    
    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .sender-name {
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    .message-time {
        font-size: 0.8rem;
        color: var(--secondary);
    }
    
    .message-bubble {
        padding: 1rem;
        border-radius: 1rem;
        position: relative;
        max-width: 100%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .admin-bubble {
        background-color: var(--primary-light);
        color: var(--dark-bg);
        border-top-left-radius: 0;
    }
    
    .user-bubble {
        background: linear-gradient(145deg, var(--primary), #8a7254);
        color: var(--white);
        border-top-right-radius: 0;
    }
    
    .message-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 0.5rem;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .message-container:hover .message-actions {
        opacity: 1;
    }
    
    /* Form Styling */
    .form-floating > .form-control {
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .form-floating > .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.25rem rgba(161, 134, 100, 0.25);
    }
    
    /* Tooltip styling */
    .tooltip {
        font-size: 0.8rem;
    }
    
    /* Card and buttons hover effects */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .3rem 3rem rgba(161, 134, 100, .175) !important;
    }
    
    .btn {
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
    
    .btn-brown {
        background: linear-gradient(145deg, var(--primary), #8a7254);
        border: none;
        box-shadow: 0 4px 10px rgba(161, 134, 100, 0.3);
        color: white;
    }
    
    .btn-brown:hover {
        background: linear-gradient(145deg, #b19a7a, #7e6849);
        box-shadow: 0 6px 15px rgba(161, 134, 100, 0.4);
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-scroll to bottom of conversation
        const messagesContainer = document.querySelector('.chat-messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Flash effect for newest message
        const messages = document.querySelectorAll('.message-bubble');
        if (messages.length > 0) {
            const latestMessage = messages[messages.length - 1];
            latestMessage.style.animation = 'flash-highlight 2s ease';
        }
    });
    
    // Animation for new messages
    document.querySelector('.reply-form').addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending...';
        submitBtn.disabled = true;
    });
</script>

<style>
    @keyframes flash-highlight {
        0% { box-shadow: 0 0 0 0 rgba(161, 134, 100, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(161, 134, 100, 0); }
        100% { box-shadow: 0 0 0 0 rgba(161, 134, 100, 0); }
    }
</style>
@endpush