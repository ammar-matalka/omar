@extends('layouts.app')

@section('title', 'Start New Conversation')

@section('content')
<div class="container" style="padding: 2rem 0; max-width: 800px;">
    <!-- Page Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: #333; margin-bottom: 1rem;">
            <i class="fas fa-comment-dots" style="color: #0ea5e9; margin-right: 0.5rem;"></i>
            Start New Conversation
        </h1>
        <p style="color: #666; font-size: 1.1rem;">
            Get in touch with our support team. We're here to help you with any questions or concerns.
        </p>
    </div>

    <!-- Conversation Form -->
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden;">
        
        <!-- Form Header -->
        <div style="background: linear-gradient(135deg, #f0f9ff, #fdf4ff); padding: 2rem; border-bottom: 1px solid #e5e7eb; text-align: center;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="fas fa-headset"></i>
                Contact Support
            </h2>
            <p style="color: #666; font-size: 0.875rem;">
                Please provide details about your inquiry so we can assist you better
            </p>
        </div>

        <form action="{{ route('user.conversations.store') }}" method="POST">
            @csrf
            
            <div style="padding: 2rem;">
                <!-- Quick Topic Selection -->
                <div style="margin-bottom: 2rem;">
                    <div style="font-size: 1rem; font-weight: 600; color: #333; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-tags"></i>
                        Choose a topic (optional)
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem;">
                        <button type="button" style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; background: white; color: #333; text-align: left; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500;" onclick="selectTopic(this, 'Order Issue')">
                            <i style="font-size: 1.125rem; width: 20px; text-align: center; color: #0ea5e9;" class="fas fa-shopping-cart"></i>
                            <span>Order Issue</span>
                        </button>
                        <button type="button" style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; background: white; color: #333; text-align: left; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500;" onclick="selectTopic(this, 'Product Question')">
                            <i style="font-size: 1.125rem; width: 20px; text-align: center; color: #0ea5e9;" class="fas fa-box"></i>
                            <span>Product Question</span>
                        </button>
                        <button type="button" style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; background: white; color: #333; text-align: left; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500;" onclick="selectTopic(this, 'Technical Support')">
                            <i style="font-size: 1.125rem; width: 20px; text-align: center; color: #0ea5e9;" class="fas fa-cog"></i>
                            <span>Technical Support</span>
                        </button>
                        <button type="button" style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; background: white; color: #333; text-align: left; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500;" onclick="selectTopic(this, 'Account Help')">
                            <i style="font-size: 1.125rem; width: 20px; text-align: center; color: #0ea5e9;" class="fas fa-user"></i>
                            <span>Account Help</span>
                        </button>
                        <button type="button" style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; background: white; color: #333; text-align: left; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500;" onclick="selectTopic(this, 'Billing Question')">
                            <i style="font-size: 1.125rem; width: 20px; text-align: center; color: #0ea5e9;" class="fas fa-credit-card"></i>
                            <span>Billing Question</span>
                        </button>
                        <button type="button" style="padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; background: white; color: #333; text-align: left; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500;" onclick="selectTopic(this, 'General Inquiry')">
                            <i style="font-size: 1.125rem; width: 20px; text-align: center; color: #0ea5e9;" class="fas fa-question-circle"></i>
                            <span>General Inquiry</span>
                        </button>
                    </div>
                </div>

                <!-- Subject Field -->
                <div style="margin-bottom: 2rem;">
                    <label for="title" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-heading"></i>
                        Subject
                        <span style="color: #ef4444; font-weight: 700;">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; background: white; color: #333; font-size: 1rem; font-family: inherit; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
                        placeholder="Brief description of your inquiry"
                        value="{{ old('title') }}"
                        required
                        maxlength="255"
                        onkeyup="updateTitleCounter(this)"
                        onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 4px rgba(14, 165, 233, 0.1)';"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';"
                    >
                    <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #666; display: flex; align-items: center; gap: 0.25rem;">
                        <i class="fas fa-info-circle"></i>
                        <span>Please provide a clear and concise subject line</span>
                    </div>
                    <div style="margin-top: 0.5rem; text-align: right; font-size: 0.75rem; color: #666;">
                        <span id="titleCounter">0</span>/255 characters
                    </div>
                    @error('title')
                        <div style="color: #ef4444; margin-top: 0.5rem; font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem;">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Message Field -->
                <div style="margin-bottom: 2rem;">
                    <label for="message" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-comment"></i>
                        Message
                        <span style="color: #ef4444; font-weight: 700;">*</span>
                    </label>
                    <textarea 
                        id="message" 
                        name="message" 
                        style="width: 100%; min-height: 150px; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; background: white; color: #333; font-size: 1rem; font-family: inherit; resize: vertical; line-height: 1.6; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
                        placeholder="Please describe your inquiry in detail. The more information you provide, the better we can assist you."
                        required
                        onkeyup="updateMessageCounter(this)"
                        onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 4px rgba(14, 165, 233, 0.1)';"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';"
                    >{{ old('message') }}</textarea>
                    <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #666; display: flex; align-items: center; gap: 0.25rem;">
                        <i class="fas fa-lightbulb"></i>
                        <span>Include any relevant details such as order numbers, error messages, or steps you've already tried</span>
                    </div>
                    <div style="margin-top: 0.5rem; text-align: right; font-size: 0.75rem; color: #666;">
                        <span id="messageCounter">0</span> characters
                    </div>
                    @error('message')
                        <div style="color: #ef4444; margin-top: 0.5rem; font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem;">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div style="padding: 2rem; background: #f8f9fa; border-top: 1px solid #e5e7eb; display: flex; gap: 1rem; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #666; font-size: 0.875rem;">
                    <i class="fas fa-clock"></i>
                    <span>We typically respond within 24 hours</span>
                </div>
                
                <div style="display: flex; gap: 1rem;">
                    <a href="{{@extends('layouts.app')

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
                            <span>Technical Support</span>@extends('layouts.app')

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