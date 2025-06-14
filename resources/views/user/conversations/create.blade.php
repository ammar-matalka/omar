@extends('layouts.app')

@section('title', 'Start New Conversation')

@push('styles')
<style>
.create-conversation-container {
    max-width: 800px;
    margin: 0 auto;
    padding: var(--space-2xl) var(--space-md);
}

.page-header {
    text-align: center;
    margin-bottom: var(--space-2xl);
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: var(--space-md);
}

.page-subtitle {
    color: var(--on-surface-variant);
    font-size: 1.125rem;
    max-width: 600px;
    margin: 0 auto;
}

.conversation-form {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, var(--primary-50), var(--secondary-50));
    padding: var(--space-xl);
    border-bottom: 1px solid var(--border-color);
    text-align: center;
}

.form-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--on-surface);
    margin-bottom: var(--space-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-sm);
}

.form-description {
    color: var(--on-surface-variant);
    font-size: 0.875rem;
}

.form-body {
    padding: var(--space-2xl);
}

.form-group {
    margin-bottom: var(--space-xl);
}

.form-label {
    display: block;
    margin-bottom: var(--space-sm);
    font-weight: 600;
    color: var(--on-surface);
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.required-indicator {
    color: var(--error-500);
    font-weight: 700;
}

.form-input {
    width: 100%;
    padding: var(--space-md);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-lg);
    background: var(--surface);
    color: var(--on-surface);
    font-size: 1rem;
    font-family: inherit;
    transition: all var(--transition-normal);
    box-shadow: var(--shadow-sm);
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
    transform: translateY(-1px);
}

.form-textarea {
    min-height: 150px;
    resize: vertical;
    font-family: inherit;
    line-height: 1.6;
}

.form-help {
    margin-top: var(--space-sm);
    font-size: 0.75rem;
    color: var(--on-surface-variant);
    display: flex;
    align-items: center;
    gap: var(--space-xs);
}

.character-counter {
    margin-top: var(--space-sm);
    text-align: right;
    font-size: 0.75rem;
    color: var(--on-surface-variant);
}

.form-actions {
    padding: var(--space-xl);
    background: var(--surface-variant);
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: var(--space-md);
    justify-content: space-between;
    align-items: center;
}

.action-buttons {
    display: flex;
    gap: var(--space-md);
}

.form-note {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    color: var(--on-surface-variant);
    font-size: 0.875rem;
}

.quick-topics {
    margin-bottom: var(--space-xl);
}

.topics-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--on-surface);
    margin-bottom: var(--space-md);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
}

.topics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-sm);
}

.topic-button {
    padding: var(--space-md);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-lg);
    background: var(--surface);
    color: var(--on-surface);
    text-align: left;
    cursor: pointer;
    transition: all var(--transition-fast);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    font-size: 0.875rem;
    font-weight: 500;
}

.topic-button:hover {
    border-color: var(--primary-300);
    background: var(--primary-50);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.topic-button.selected {
    border-color: var(--primary-500);
    background: var(--primary-50);
    color: var(--primary-700);
}

.topic-icon {
    font-size: 1.125rem;
    width: 20px;
    text-align: center;
}

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.btn-primary {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: white;
    box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
}

.btn-secondary {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-secondary:hover {
    background: #e2e8f0;
    transform: translateY(-1px);
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .create-conversation-container {
        padding: var(--space-lg) var(--space-sm);
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .form-body {
        padding: var(--space-lg);
    }
    
    .form-actions {
        flex-direction: column;
        gap: var(--space-md);
        text-align: center;
    }
    
    .action-buttons {
        width: 100%;
        justify-content: center;
    }
    
    .topics-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="create-conversation-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Start New Conversation</h1>
        <p class="page-subtitle">
            Get in touch with our support team. We're here to help you with any questions or concerns.
        </p>
    </div>

    <!-- Conversation Form -->
    <div class="conversation-form">
        <div class="form-header">
            <h2 class="form-title">
                <i class="fas fa-comment-dots"></i>
                Contact Support
            </h2>
            <p class="form-description">
                Please provide details about your inquiry so we can assist you better
            </p>
        </div>

        <form action="{{ route('user.conversations.store') }}" method="POST">
            @csrf
            
            <div class="form-body">
                <!-- Quick Topic Selection -->
                <div class="quick-topics">
                    <div class="topics-title">
                        <i class="fas fa-tags"></i>
                        Choose a topic (optional)
                    </div>
                    <div class="topics-grid">
                        <button type="button" class="topic-button" onclick="selectTopic(this, 'Order Issue')">
                            <i class="topic-icon fas fa-shopping-cart"></i>
                            <span>Order Issue</span>
                        </button>
                        <button type="button" class="topic-button" onclick="selectTopic(this, 'Product Question')">
                            <i class="topic-icon fas fa-box"></i>
                            <span>Product Question</span>
                        </button>
                        <button type="button" class="topic-button" onclick="selectTopic(this, 'Technical Support')">
                            <i class="topic-icon fas fa-cog"></i>
                            <span>Technical Support</span>
                        </button>
                        <button type="button" class="topic-button" onclick="selectTopic(this, 'Account Help')">
                            <i class="topic-icon fas fa-user"></i>
                            <span>Account Help</span>
                        </button>
                        <button type="button" class="topic-button" onclick="selectTopic(this, 'Billing Question')">
                            <i class="topic-icon fas fa-credit-card"></i>
                            <span>Billing Question</span>
                        </button>
                        <button type="button" class="topic-button" onclick="selectTopic(this, 'General Inquiry')">
                            <i class="topic-icon fas fa-question-circle"></i>
                            <span>General Inquiry</span>
                        </button>
                    </div>
                </div>

                <!-- Subject Field -->
                <div class="form-group">
                    <label for="title" class="form-label">
                        <i class="fas fa-heading"></i>
                        Subject
                        <span class="required-indicator">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        class="form-input" 
                        placeholder="Brief description of your inquiry"
                        value="{{ old('title') }}"
                        required
                        maxlength="255"
                        onkeyup="updateTitleCounter(this)"
                    >
                    <div class="form-help">
                        <i class="fas fa-info-circle"></i>
                        <span>Please provide a clear and concise subject line</span>
                    </div>
                    <div class="character-counter">
                        <span id="titleCounter">0</span>/255 characters
                    </div>
                    @error('title')
                        <div class="form-help" style="color: var(--error-500); margin-top: var(--space-sm);">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Message Field -->
                <div class="form-group">
                    <label for="message" class="form-label">
                        <i class="fas fa-comment"></i>
                        Message
                        <span class="required-indicator">*</span>
                    </label>
                    <textarea 
                        id="message" 
                        name="message" 
                        class="form-input form-textarea" 
                        placeholder="Please describe your inquiry in detail. The more information you provide, the better we can assist you."
                        required
                        onkeyup="updateMessageCounter(this)"
                    >{{ old('message') }}</textarea>
                    <div class="form-help">
                        <i class="fas fa-lightbulb"></i>
                        <span>Include any relevant details such as order numbers, error messages, or steps you've already tried</span>
                    </div>
                    <div class="character-counter">
                        <span id="messageCounter">0</span> characters
                    </div>
                    @error('message')
                        <div class="form-help" style="color: var(--error-500); margin-top: var(--space-sm);">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <div class="form-note">
                    <i class="fas fa-clock"></i>
                    <span>We typically respond within 24 hours</span>
                </div>
                
                <div class="action-buttons">
                    <a href="{{ route('user.conversations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Conversations
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i>
                        Start Conversation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectTopic(button, topic) {
    // Remove selected class from all topic buttons
    document.querySelectorAll('.topic-button').forEach(btn => {
        btn.classList.remove('selected');
    });
    
    // Add selected class to clicked button
    button.classList.add('selected');
    
    // Update the title field if it's empty
    const titleField = document.getElementById('title');
    if (!titleField.value.trim()) {
        titleField.value = topic;
        updateTitleCounter(titleField);
    }
}

function updateTitleCounter(input) {
    const counter = document.getElementById('titleCounter');
    counter.textContent = input.value.length;
    
    // Change color based on length
    if (input.value.length > 240) {
        counter.style.color = 'var(--warning-500)';
    } else if (input.value.length > 250) {
        counter.style.color = 'var(--error-500)';
    } else {
        counter.style.color = 'var(--on-surface-variant)';
    }
}

function updateMessageCounter(textarea) {
    const counter = document.getElementById('messageCounter');
    counter.textContent = textarea.value.length;
}

// Initialize counters on page load
document.addEventListener('DOMContentLoaded', function() {
    const titleField = document.getElementById('title');
    const messageField = document.getElementById('message');
    
    if (titleField.value) {
        updateTitleCounter(titleField);
    }
    
    if (messageField.value) {
        updateMessageCounter(messageField);
    }
    
    // Auto-resize textarea
    messageField.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
});
</script>
@endpush