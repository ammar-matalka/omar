@extends('layouts.app')

@section('title', __('Start New Conversation') . ' - ' . config('app.name'))

@push('styles')
<style>
    .create-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .create-hero::before {
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
        text-align: center;
    }
    
    .hero-title {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: var(--space-lg);
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        font-size: 0.875rem;
        opacity: 0.9;
        flex-wrap: wrap;
    }
    
    .breadcrumb-link {
        color: white;
        text-decoration: none;
        transition: opacity var(--transition-fast);
    }
    
    .breadcrumb-link:hover {
        opacity: 0.8;
        text-decoration: underline;
    }
    
    .breadcrumb-separator {
        opacity: 0.6;
    }
    
    .create-container {
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
    
    .create-content {
        max-width: 800px;
        margin: 0 auto;
        display: grid;
        gap: var(--space-xl);
    }
    
    .help-section {
        background: linear-gradient(135deg, var(--info-50), var(--info-100));
        border: 2px solid var(--info-200);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
    }
    
    .help-title {
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: var(--space-md);
        color: var(--info-700);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .help-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .help-item {
        background: white;
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--info-200);
    }
    
    .help-item-title {
        font-weight: 600;
        color: var(--info-700);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .help-item-text {
        color: var(--info-600);
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .help-tips {
        background: white;
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--info-200);
    }
    
    .tips-title {
        font-weight: 600;
        color: var(--info-700);
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: var(--space-sm);
    }
    
    .tips-list li {
        display: flex;
        align-items: flex-start;
        gap: var(--space-sm);
        color: var(--info-600);
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .tips-list li::before {
        content: '💡';
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .form-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: var(--space-2xl);
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }
    
    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
    }
    
    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-xl);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .form-grid {
        display: grid;
        gap: var(--space-lg);
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }
    
    .form-label {
        font-weight: 500;
        color: var(--on-surface);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .form-label.required::after {
        content: '*';
        color: var(--error-500);
        margin-left: var(--space-xs);
    }
    
    .form-input,
    .form-select,
    .form-textarea {
        padding: var(--space-md);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 1rem;
        transition: all var(--transition-fast);
        font-family: inherit;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        background: var(--surface);
    }
    
    .form-input.error,
    .form-select.error,
    .form-textarea.error {
        border-color: var(--error-500);
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 150px;
        line-height: 1.6;
    }
    
    .form-help {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        line-height: 1.4;
    }
    
    .form-error {
        font-size: 0.75rem;
        color: var(--error-600);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .character-count {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        text-align: right;
        margin-top: var(--space-xs);
    }
    
    .character-count.warning {
        color: var(--warning-600);
    }
    
    .character-count.error {
        color: var(--error-600);
    }
    
    .input-icon {
        position: relative;
    }
    
    .input-icon .form-input,
    .input-icon .form-select {
        padding-left: var(--space-3xl);
    }
    
    .input-icon i {
        position: absolute;
        left: var(--space-md);
        top: 50%;
        transform: translateY(-50%);
        color: var(--on-surface-variant);
        font-size: 1rem;
    }
    
    .quick-templates {
        background: var(--surface-variant);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-md);
    }
    
    .templates-title {
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: var(--space-md);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .template-buttons {
        display: flex;
        gap: var(--space-sm);
        flex-wrap: wrap;
    }
    
    .template-btn {
        padding: var(--space-xs) var(--space-sm);
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        color: var(--on-surface-variant);
        font-size: 0.75rem;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .template-btn:hover {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
    }
    
    .form-actions {
        display: flex;
        gap: var(--space-lg);
        justify-content: flex-end;
        margin-top: var(--space-2xl);
        padding-top: var(--space-xl);
        border-top: 1px solid var(--border-color);
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-md) var(--space-xl);
        border: 2px solid transparent;
        border-radius: var(--radius-lg);
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition-fast);
        min-width: 120px;
    }
    
    .btn:focus {
        outline: 2px solid var(--primary-500);
        outline-offset: 2px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        box-shadow: var(--shadow-md);
    }
    
    .btn-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
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
        transform: translateY(-1px);
    }
    
    .loading-spinner {
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 1.5rem;
        }
        
        .create-content {
            padding: 0 var(--space-md);
        }
        
        .help-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column-reverse;
            gap: var(--space-md);
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
        
        .breadcrumb {
            justify-content: center;
        }
        
        .template-buttons {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Create Hero -->
<section class="create-hero">
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
                <span>{{ __('New Conversation') }}</span>
            </nav>
            
            <h1 class="hero-title">{{ __('Start New Conversation') }}</h1>
            <p class="hero-subtitle">{{ __('Get in touch with our support team for help and assistance') }}</p>
        </div>
    </div>
</section>

<!-- Create Container -->
<section class="create-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('user.conversations.index') }}" class="back-button fade-in">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Messages') }}
        </a>
        
        <div class="create-content">
            <!-- Help Section -->
            <div class="help-section fade-in">
                <h3 class="help-title">
                    <i class="fas fa-question-circle"></i>
                    {{ __('How Can We Help You?') }}
                </h3>
                
                <div class="help-grid">
                    <div class="help-item">
                        <div class="help-item-title">
                            <i class="fas fa-shopping-cart"></i>
                            {{ __('Order Support') }}
                        </div>
                        <div class="help-item-text">
                            {{ __('Questions about your orders, shipping, returns, or product issues') }}
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <div class="help-item-title">
                            <i class="fas fa-credit-card"></i>
                            {{ __('Payment Issues') }}
                        </div>
                        <div class="help-item-text">
                            {{ __('Problems with payments, refunds, or billing inquiries') }}
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <div class="help-item-title">
                            <i class="fas fa-user-cog"></i>
                            {{ __('Account Help') }}
                        </div>
                        <div class="help-item-text">
                            {{ __('Account settings, password reset, or profile updates') }}
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <div class="help-item-title">
                            <i class="fas fa-graduation-cap"></i>
                            {{ __('Educational Cards') }}
                        </div>
                        <div class="help-item-text">
                            {{ __('Questions about educational content, downloads, or usage') }}
                        </div>
                    </div>
                </div>
                
                <div class="help-tips">
                    <div class="tips-title">
                        <i class="fas fa-lightbulb"></i>
                        {{ __('Tips for Better Support') }}
                    </div>
                    <ul class="tips-list">
                        <li>{{ __('Be specific about your issue or question') }}</li>
                        <li>{{ __('Include relevant order numbers or product details') }}</li>
                        <li>{{ __('Describe what you\'ve already tried to resolve the issue') }}</li>
                        <li>{{ __('Attach screenshots if they help explain your problem') }}</li>
                    </ul>
                </div>
            </div>
            
            <!-- Conversation Form -->
            <div class="form-card fade-in">
                <form action="{{ route('user.conversations.store') }}" method="POST" id="conversation-form">
                    @csrf
                    
                    <h2 class="form-title">
                        <i class="fas fa-comments"></i>
                        {{ __('Create New Conversation') }}
                    </h2>
                    
                    <div class="form-grid">
                        <!-- Subject/Title -->
                        <div class="form-group">
                            <label for="title" class="form-label required">
                                <i class="fas fa-tag"></i>
                                {{ __('Subject') }}
                            </label>
                            <div class="input-icon">
                                <i class="fas fa-tag"></i>
                                <input 
                                    type="text" 
                                    id="title" 
                                    name="title" 
                                    class="form-input {{ $errors->has('title') ? 'error' : '' }}"
                                    value="{{ old('title') }}"
                                    placeholder="{{ __('Enter conversation subject...') }}"
                                    required
                                    maxlength="255"
                                    oninput="updateCharacterCount('title', 255)"
                                >
                            </div>
                            @error('title')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="character-count" id="title-count">0 / 255</div>
                            <div class="form-help">{{ __('Choose a clear, descriptive subject for your message') }}</div>
                            
                            <!-- Quick Templates -->
                            <div class="quick-templates">
                                <div class="templates-title">
                                    <i class="fas fa-magic"></i>
                                    {{ __('Quick Templates') }}
                                </div>
                                <div class="template-buttons">
                                    <button type="button" class="template-btn" onclick="setTemplate('order')">
                                        {{ __('Order Issue') }}
                                    </button>
                                    <button type="button" class="template-btn" onclick="setTemplate('payment')">
                                        {{ __('Payment Problem') }}
                                    </button>
                                    <button type="button" class="template-btn" onclick="setTemplate('account')">
                                        {{ __('Account Help') }}
                                    </button>
                                    <button type="button" class="template-btn" onclick="setTemplate('product')">
                                        {{ __('Product Question') }}
                                    </button>
                                    <button type="button" class="template-btn" onclick="setTemplate('general')">
                                        {{ __('General Inquiry') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Priority Level -->
                        <div class="form-group">
                            <label for="priority" class="form-label">
                                <i class="fas fa-flag"></i>
                                {{ __('Priority Level') }}
                            </label>
                            <div class="input-icon">
                                <i class="fas fa-flag"></i>
                                <select 
                                    id="priority" 
                                    name="priority" 
                                    class="form-select"
                                >
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>
                                        {{ __('Low - General questions') }}
                                    </option>
                                    <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>
                                        {{ __('Normal - Standard support') }}
                                    </option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>
                                        {{ __('High - Urgent issues') }}
                                    </option>
                                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>
                                        {{ __('Urgent - Critical problems') }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-help">{{ __('Select the appropriate priority level for your message') }}</div>
                        </div>
                        
                        <!-- Message -->
                        <div class="form-group">
                            <label for="message" class="form-label required">
                                <i class="fas fa-comment-alt"></i>
                                {{ __('Message') }}
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                class="form-textarea {{ $errors->has('message') ? 'error' : '' }}"
                                placeholder="{{ __('Describe your issue or question in detail...') }}"
                                required
                                maxlength="2000"
                                oninput="updateCharacterCount('message', 2000)"
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="character-count" id="message-count">0 / 2000</div>
                            <div class="form-help">{{ __('Provide as much detail as possible to help us assist you better') }}</div>
                        </div>
                        
                        <!-- Order Reference (Optional) -->
                        <div class="form-group">
                            <label for="order_reference" class="form-label">
                                <i class="fas fa-receipt"></i>
                                {{ __('Order Reference') }} <span style="color: var(--on-surface-variant);">({{ __('Optional') }})</span>
                            </label>
                            <div class="input-icon">
                                <i class="fas fa-receipt"></i>
                                <input 
                                    type="text" 
                                    id="order_reference" 
                                    name="order_reference" 
                                    class="form-input"
                                    value="{{ old('order_reference') }}"
                                    placeholder="{{ __('e.g., Order #12345') }}"
                                >
                            </div>
                            <div class="form-help">{{ __('Include order number if your question is related to a specific order') }}</div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('user.conversations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('Send Message') }}
                        </button>
                    </div>
                </form>
            </div>
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
    function updateCharacterCount(fieldId, maxLength) {
        const field = document.getElementById(fieldId);
        const counter = document.getElementById(fieldId + '-count');
        const currentLength = field.value.length;
        
        counter.textContent = `${currentLength} / ${maxLength}`;
        
        // Update counter color based on usage
        counter.classList.remove('warning', 'error');
        if (currentLength > maxLength * 0.9) {
            counter.classList.add('error');
        } else if (currentLength > maxLength * 0.75) {
            counter.classList.add('warning');
        }
    }
    
    // Template functionality
    const templates = {
        order: {
            title: '{{ __("Order Issue - Order #") }}',
            message: '{{ __("Hello,\n\nI am experiencing an issue with my order. Here are the details:\n\nOrder Number: #\nIssue Description: \nSteps I have already taken: \n\nThank you for your assistance.") }}'
        },
        payment: {
            title: '{{ __("Payment Issue") }}',
            message: '{{ __("Hello,\n\nI am having trouble with a payment. Details:\n\nPayment Method: \nAmount: \nError Message: \nDate of Transaction: \n\nPlease help me resolve this issue.") }}'
        },
        account: {
            title: '{{ __("Account Help Needed") }}',
            message: '{{ __("Hello,\n\nI need assistance with my account:\n\nIssue: \nAccount Email: \nDescription: \n\nThank you for your help.") }}'
        },
        product: {
            title: '{{ __("Product Question") }}',
            message: '{{ __("Hello,\n\nI have a question about a product:\n\nProduct Name: \nQuestion: \nAdditional Details: \n\nI look forward to your response.") }}'
        },
        general: {
            title: '{{ __("General Inquiry") }}',
            message: '{{ __("Hello,\n\nI have a general question:\n\nSubject: \nDetails: \n\nThank you for your time.") }}'
        }
    };
    
    function setTemplate(type) {
        const template = templates[type];
        if (template) {
            document.getElementById('title').value = template.title;
            document.getElementById('message').value = template.message;
            
            // Update character counts
            updateCharacterCount('title', 255);
            updateCharacterCount('message', 2000);
            
            showNotification('{{ __("Template applied successfully") }}', 'success');
        }
    }
    
    // Form submission with loading state
    document.getElementById('conversation-form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submit-btn');
        
        // Show loading state
        submitBtn.innerHTML = '<div class="loading-spinner"></div> {{ __("Sending...") }}';
        submitBtn.disabled = true;
        
        // Validate form before submission
        const title = document.getElementById('title').value.trim();
        const message = document.getElementById('message').value.trim();
        
        if (!title || !message) {
            e.preventDefault();
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> {{ __("Send Message") }}';
            submitBtn.disabled = false;
            showNotification('{{ __("Please fill in all required fields") }}', 'error');
            return;
        }
        
        if (title.length > 255 || message.length > 2000) {
            e.preventDefault();
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> {{ __("Send Message") }}';
            submitBtn.disabled = false;
            showNotification('{{ __("Please check the character limits") }}', 'error');
            return;
        }
    });
    
    // Real-time validation
    document.getElementById('title').addEventListener('input', function() {
        const submitBtn = document.getElementById('submit-btn');
        const isValid = this.value.trim().length > 0 && this.value.length <= 255;
        updateSubmitButton();
    });
    
    document.getElementById('message').addEventListener('input', function() {
        updateSubmitButton();
    });
    
    function updateSubmitButton() {
        const title = document.getElementById('title').value.trim();
        const message = document.getElementById('message').value.trim();
        const submitBtn = document.getElementById('submit-btn');
        
        const isValid = title.length > 0 && title.length <= 255 && 
                       message.length > 0 && message.length <= 2000;
        
        submitBtn.disabled = !isValid;
        submitBtn.style.opacity = isValid ? '1' : '0.6';
    }
    
    // Auto-resize textarea
    document.getElementById('message').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 300) + 'px';
    });
    
    // Initialize character counts
    document.addEventListener('DOMContentLoaded', function() {
        updateCharacterCount('title', 255);
        updateCharacterCount('message', 2000);
        updateSubmitButton();
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Send with Ctrl+Enter
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            if (!document.getElementById('submit-btn').disabled) {
                document.getElementById('conversation-form').submit();
            }
        }
        
        // Cancel with Escape
        if (e.key === 'Escape') {
            if (confirm('{{ __("Are you sure you want to cancel? Your message will be lost.") }}')) {
                window.location.href = '{{ route("user.conversations.index") }}';
            }
        }
    });
    
    // Auto-save draft (optional)
    let autoSaveTimeout;
    
    function autoSaveDraft() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            const formData = {
                title: document.getElementById('title').value,
                message: document.getElementById('message').value,
                priority: document.getElementById('priority').value,
                order_reference: document.getElementById('order_reference').value
            };
            
            localStorage.setItem('conversation_draft', JSON.stringify(formData));
            showAutoSaveIndicator();
        }, 2000);
    }
    
    function loadDraft() {
        const draft = localStorage.getItem('conversation_draft');
        if (draft) {
            try {
                const data = JSON.parse(draft);
                
                if (confirm('{{ __("A draft was found. Would you like to restore it?") }}')) {
                    document.getElementById('title').value = data.title || '';
                    document.getElementById('message').value = data.message || '';
                    document.getElementById('priority').value = data.priority || 'normal';
                    document.getElementById('order_reference').value = data.order_reference || '';
                    
                    updateCharacterCount('title', 255);
                    updateCharacterCount('message', 2000);
                    updateSubmitButton();
                } else {
                    localStorage.removeItem('conversation_draft');
                }
            } catch (e) {
                localStorage.removeItem('conversation_draft');
            }
        }
    }
    
    function showAutoSaveIndicator() {
        const indicator = document.createElement('div');
        indicator.textContent = '{{ __("Draft saved") }}';
        indicator.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: var(--success-500);
            color: white;
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            z-index: 9999;
            animation: slideInLeft 0.3s ease-out;
        `;
        
        document.body.appendChild(indicator);
        
        setTimeout(() => {
            indicator.style.animation = 'slideOutLeft 0.3s ease-out forwards';
            setTimeout(() => {
                if (document.body.contains(indicator)) {
                    document.body.removeChild(indicator);
                }
            }, 300);
        }, 2000);
    }
    
    // Enable auto-save
    document.querySelectorAll('#title, #message, #priority, #order_reference').forEach(input => {
        input.addEventListener('input', autoSaveDraft);
    });
    
    // Load draft on page load
    loadDraft();
    
    // Clear draft on successful submission
    document.getElementById('conversation-form').addEventListener('submit', () => {
        localStorage.removeItem('conversation_draft');
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
            box-shadow: var(--shadow-xl);
            animation: slideIn 0.3s ease-out;
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
    
    @keyframes slideInLeft {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutLeft {
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }
</style>
@endpush