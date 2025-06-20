@extends('layouts.app')

@section('title', __('مشاركة تجربتك') . ' - ' . config('app.name'))

@push('styles')
<style>
    /* RTL Direction */
    html[dir="rtl"] {
        direction: rtl;
        text-align: right;
    }

    .testimonial-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-2xl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .testimonial-hero::before {
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
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: var(--space-sm);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        font-size: 1.125rem;
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
    
    .testimonial-container {
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
    
    .testimonial-content {
        max-width: 800px;
        margin: 0 auto;
        display: grid;
        gap: var(--space-xl);
    }
    
    .order-summary {
        background: linear-gradient(135deg, var(--info-50), var(--info-100));
        border: 2px solid var(--info-200);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        position: relative;
        overflow: hidden;
    }
    
    .order-summary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--info-500), var(--info-600));
    }
    
    .summary-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: var(--space-lg);
        color: var(--info-700);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .order-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-lg);
    }
    
    .detail-item {
        background: white;
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        border: 1px solid var(--info-200);
    }
    
    .detail-label {
        font-size: 0.75rem;
        color: var(--info-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--space-xs);
        font-weight: 600;
    }
    
    .detail-value {
        font-size: 1rem;
        color: var(--info-700);
        font-weight: 600;
    }
    
    .order-items {
        background: white;
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--info-200);
    }
    
    .items-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--info-700);
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .items-list {
        display: flex;
        flex-wrap: wrap;
        gap: var(--space-sm);
    }
    
    .item-tag {
        background: var(--info-100);
        color: var(--info-700);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        font-weight: 500;
        border: 1px solid var(--info-200);
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
    
    .rating-section {
        margin-bottom: var(--space-xl);
    }
    
    .rating-label {
        font-size: 1rem;
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .rating-container {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .star-rating {
        display: flex;
        gap: var(--space-xs);
    }
    
    .star {
        font-size: 2rem;
        color: var(--border-color);
        cursor: pointer;
        transition: all var(--transition-fast);
        position: relative;
    }
    
    .star:hover,
    .star.active {
        color: var(--warning-500);
        transform: scale(1.1);
    }
    
    .star:hover::before,
    .star.active::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 120%;
        height: 120%;
        background: var(--warning-100);
        border-radius: 50%;
        z-index: -1;
    }
    
    .rating-text {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--on-surface);
        min-width: 100px;
    }
    
    .rating-description {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
        margin-top: var(--space-sm);
        text-align: center;
    }
    
    .form-group {
        margin-bottom: var(--space-xl);
    }
    
    .form-label {
        font-weight: 600;
        color: var(--on-surface);
        font-size: 1rem;
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .form-label.required::after {
        content: '*';
        color: var(--error-500);
        margin-left: var(--space-xs);
    }
    
    .form-textarea {
        width: 100%;
        min-height: 150px;
        padding: var(--space-lg);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 1rem;
        font-family: inherit;
        resize: vertical;
        transition: all var(--transition-fast);
        line-height: 1.6;
    }
    
    .form-textarea:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    
    .form-textarea.error {
        border-color: var(--error-500);
    }
    
    .form-error {
        font-size: 0.75rem;
        color: var(--error-600);
        margin-top: var(--space-sm);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .character-count {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        text-align: right;
        margin-top: var(--space-sm);
    }
    
    .character-count.warning {
        color: var(--warning-600);
    }
    
    .character-count.error {
        color: var(--error-600);
    }
    
    .form-help {
        font-size: 0.875rem;
        color: var(--on-surface-variant);
        line-height: 1.5;
        margin-top: var(--space-sm);
    }
    
    .suggestion-prompts {
        background: var(--surface-variant);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-md);
    }
    
    .prompts-title {
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: var(--space-md);
        color: var(--on-surface);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .prompt-buttons {
        display: flex;
        gap: var(--space-sm);
        flex-wrap: wrap;
    }
    
    .prompt-btn {
        padding: var(--space-xs) var(--space-sm);
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        color: var(--on-surface-variant);
        font-size: 0.75rem;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .prompt-btn:hover {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
    }
    
    .privacy-notice {
        background: linear-gradient(135deg, var(--success-50), var(--success-100));
        border: 2px solid var(--success-200);
        border-radius: var(--radius-xl);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
    }
    
    .privacy-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: var(--space-md);
        color: var(--success-700);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .privacy-text {
        font-size: 0.875rem;
        color: var(--success-600);
        line-height: 1.5;
    }
    
    .form-actions {
        display: flex;
        gap: var(--space-lg);
        justify-content: flex-start;
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
        min-width: 140px;
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
    
    .reward-preview {
        background: linear-gradient(135deg, var(--warning-50), var(--warning-100));
        border: 2px solid var(--warning-200);
        border-radius: var(--radius-xl);
        padding: var(--space-lg);
        text-align: center;
        margin-bottom: var(--space-xl);
    }
    
    .reward-icon {
        font-size: 2.5rem;
        color: var(--warning-500);
        margin-bottom: var(--space-md);
    }
    
    .reward-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--warning-700);
        margin-bottom: var(--space-sm);
    }
    
    .reward-text {
        font-size: 0.875rem;
        color: var(--warning-600);
        line-height: 1.5;
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .testimonial-content {
            padding: 0 var(--space-md);
        }
        
        .order-details {
            grid-template-columns: 1fr;
        }
        
        .rating-container {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-md);
        }
        
        .star-rating {
            justify-content: center;
            width: 100%;
        }
        
        .form-actions {
            flex-direction: column;
            gap: var(--space-md);
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
        
        .breadcrumb {
            justify-content: center;
        }
        
        .prompt-buttons {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Testimonial Hero -->
<section class="testimonial-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    {{ __('الرئيسية') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('orders.index') }}" class="breadcrumb-link">
                    {{ __('الطلبات') }}
                </a>
                <span class="breadcrumb-separator">/</span>
                <span>{{ __('التقييم') }}</span>
            </nav>
            
            <h1 class="hero-title">{{ __('شارك تجربتك') }}</h1>
            <p class="hero-subtitle">{{ __('ساعد العملاء الآخرين بمشاركة تقييمك الصادق لمنتجاتنا وخدماتنا') }}</p>
        </div>
    </div>
</section>

<!-- Testimonial Container -->
<section class="testimonial-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('orders.show', $order) }}" class="back-button fade-in">
            <i class="fas fa-arrow-left"></i>
            {{ __('العودة للطلب') }}
        </a>
        
        <div class="testimonial-content">
            <!-- Order Summary -->
            <div class="order-summary fade-in">
                <h3 class="summary-title">
                    <i class="fas fa-receipt"></i>
                    {{ __('ملخص الطلب') }}
                </h3>
                
                <div class="order-details">
                    <div class="detail-item">
                        <div class="detail-label">{{ __('رقم الطلب') }}</div>
                        <div class="detail-value">#{{ $order->id }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">{{ __('تاريخ الطلب') }}</div>
                        <div class="detail-value">{{ $order->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">{{ __('المبلغ الإجمالي') }}</div>
                        <div class="detail-value">${{ number_format($order->total_amount, 2) }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">{{ __('الحالة') }}</div>
                        <div class="detail-value">{{ ucfirst($order->status) }}</div>
                    </div>
                </div>
                
                <div class="order-items">
                    <div class="items-title">
                        <i class="fas fa-box"></i>
                        {{ __('المنتجات في هذا الطلب') }}
                    </div>
                    <div class="items-list">
                        @foreach($order->orderItems as $item)
                            <span class="item-tag">
                                {{ $item->item_name }}
                                @if($item->quantity > 1)
                                    ({{ $item->quantity }}x)
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Reward Preview -->
            <div class="reward-preview fade-in">
                <div class="reward-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <div class="reward-title">{{ __('مكافأة الشكر!') }}</div>
                <div class="reward-text">
                    {{ __('أكمل تقييمك لتحصل على كوبون خصص خاص لشرائك القادم') }}
                </div>
            </div>
            
            <!-- Testimonial Form -->
            <div class="form-card fade-in">
                <form action="{{ route('testimonials.store') }}" method="POST" id="testimonial-form">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    
                    <h2 class="form-title">
                        <i class="fas fa-star"></i>
                        {{ __('تقييمك') }}
                    </h2>
                    
                    <!-- Rating Section -->
                    <div class="rating-section">
                        <div class="rating-label required">
                            <i class="fas fa-star"></i>
                            {{ __('التقييم العام') }}
                        </div>
                        
                        <div class="rating-container">
                            <div class="star-rating" id="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star" data-rating="{{ $i }}" onclick="setRating({{ $i }})">
                                        <i class="fas fa-star"></i>
                                    </span>
                                @endfor
                            </div>
                            
                            <div class="rating-text" id="rating-text">{{ __('انقر للتقييم') }}</div>
                        </div>
                        
                        <div class="rating-description" id="rating-description">
                            {{ __('كيف تقيم تجربتك العامة مع هذا الطلب؟') }}
                        </div>
                        
                        <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', '') }}" required>
                        
                        @error('rating')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Comment Section -->
                    <div class="form-group">
                        <label for="comment" class="form-label required">
                            <i class="fas fa-comment-alt"></i>
                            {{ __('تقييمك') }}
                        </label>
                        
                        <textarea 
                            id="comment" 
                            name="comment" 
                            class="form-textarea {{ $errors->has('comment') ? 'error' : '' }}"
                            placeholder="{{ __('شارك أفكارك عن المنتجات، التوصيل، والتجربة العامة...') }}"
                            required
                            minlength="5"
                            maxlength="500"
                            oninput="updateCharacterCount()"
                        >{{ old('comment') }}</textarea>
                        
                        @error('comment')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <div class="character-count" id="character-count">0 / 500</div>
                        
                        <div class="form-help">
                            {{ __('يرجى تقديم ملاحظات صادقة عن تجربتك. تقييمك سيساعد العملاء الآخرين على اتخاذ قرارات مستنيرة.') }}
                        </div>
                        
                        <!-- Suggestion Prompts -->
                        <div class="suggestion-prompts">
                            <div class="prompts-title">
                                <i class="fas fa-lightbulb"></i>
                                {{ __('بحاجة إلى أفكار؟ انقر لإضافة:') }}
                            </div>
                            <div class="prompt-buttons">
                                <button type="button" class="prompt-btn" onclick="addPrompt('product_quality')">{{ __('جودة المنتج') }}</button>
                                <button type="button" class="prompt-btn" onclick="addPrompt('delivery_speed')">{{ __('سرعة التوصيل') }}</button>
                                <button type="button" class="prompt-btn" onclick="addPrompt('packaging')">{{ __('التغليف') }}</button>
                                <button type="button" class="prompt-btn" onclick="addPrompt('customer_service')">{{ __('خدمة العملاء') }}</button>
                                <button type="button" class="prompt-btn" onclick="addPrompt('value_money')">{{ __('القيمة مقابل السعر') }}</button>
                                <button type="button" class="prompt-btn" onclick="addPrompt('recommend')">{{ __('سأوصي به') }}</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Privacy Notice -->
                    <div class="privacy-notice">
                        <div class="privacy-title">
                            <i class="fas fa-shield-alt"></i>
                            {{ __('سياسة الخصوصية') }}
                        </div>
                        <div class="privacy-text">
                            {{ __('سيتم نشر تقييمك علناً بعد المراجعة. سيتم الحفاظ على خصوصية المعلومات الشخصية. يمكنك تقديم تقييم واحد فقط لكل طلب.') }}
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            {{ __('إلغاء') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                            <i class="fas fa-paper-plane"></i>
                            {{ __('إرسال التقييم') }}
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
    let currentRating = 0;
    
    const ratingDescriptions = {
        1: '{{ __("سيء - غير راضٍ تمامًا عن التجربة") }}',
        2: '{{ __("متوسط - أقل من التوقعات") }}',
        3: '{{ __("جيد - يلبي التوقعات") }}',
        4: '{{ __("جيد جدًا - يفوق التوقعات") }}',
        5: '{{ __("ممتاز - تجربة استثنائية") }}'
    };
    
    const ratingTexts = {
        1: '{{ __("سيء") }}',
        2: '{{ __("متوسط") }}',
        3: '{{ __("جيد") }}',
        4: '{{ __("جيد جدًا") }}',
        5: '{{ __("ممتاز") }}'
    };
    
    // Suggestion prompts
    const prompts = {
        product_quality: '{{ __("جودة المنتج كانت ممتازة ووافقت توقعاتي.") }}',
        delivery_speed: '{{ __("التوصيل كان سريعًا ووصل في الوقت المحدد.") }}',
        packaging: '{{ __("التغليف كان آمنًا وحافظ على المنتجات جيدًا.") }}',
        customer_service: '{{ __("خدمة العملاء كانت مفيدة وسريعة الاستجابة.") }}',
        value_money: '{{ __("قيمة ممتازة مقابل السعر، راضٍ جدًا عن الشراء.") }}',
        recommend: '{{ __("سأوصي بهذا المنتج للآخرين بالتأكيد.") }}'
    };
    
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
    
    // Set rating
    function setRating(rating) {
        currentRating = rating;
        document.getElementById('rating-input').value = rating;
        
        // Update star display
        const stars = document.querySelectorAll('.star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
        
        // Update rating text and description
        document.getElementById('rating-text').textContent = ratingTexts[rating];
        document.getElementById('rating-description').textContent = ratingDescriptions[rating];
        
        validateForm();
    }
    
    // Add hover effects to stars
    document.querySelectorAll('.star').forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            const rating = index + 1;
            const stars = document.querySelectorAll('.star');
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.style.color = 'var(--warning-500)';
                    s.style.transform = 'scale(1.1)';
                } else {
                    s.style.color = 'var(--border-color)';
                    s.style.transform = 'scale(1)';
                }
            });
        });
        
        star.addEventListener('mouseleave', function() {
            const stars = document.querySelectorAll('.star');
            stars.forEach((s, i) => {
                if (i < currentRating) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });
    });
    
    // Character count functionality
    function updateCharacterCount() {
        const textarea = document.getElementById('comment');
        const counter = document.getElementById('character-count');
        const currentLength = textarea.value.length;
        const maxLength = 500;
        
        counter.textContent = `${currentLength} / ${maxLength}`;
        
        // Update counter color
        counter.classList.remove('warning', 'error');
        if (currentLength > maxLength * 0.9) {
            counter.classList.add('error');
        } else if (currentLength > maxLength * 0.75) {
            counter.classList.add('warning');
        }
        
        validateForm();
    }
    
    // Add suggestion prompt
    function addPrompt(type) {
        const textarea = document.getElementById('comment');
        const prompt = prompts[type];
        
        if (prompt) {
            const currentText = textarea.value.trim();
            const newText = currentText 
                ? currentText + '\n\n' + prompt 
                : prompt;
            
            if (newText.length <= 500) {
                textarea.value = newText;
                updateCharacterCount();
                
                // Focus and scroll to end
                textarea.focus();
                textarea.setSelectionRange(textarea.value.length, textarea.value.length);
                
                showNotification('{{ __("تمت إضافة الاقتراح إلى تقييمك") }}', 'success');
            } else {
                showNotification('{{ __("لا يوجد مساحة كافية لإضافة هذا الاقتراح") }}', 'warning');
            }
        }
    }
    
    // Form validation
    function validateForm() {
        const rating = document.getElementById('rating-input').value;
        const comment = document.getElementById('comment').value.trim();
        const submitBtn = document.getElementById('submit-btn');
        
        const isValid = rating && rating > 0 && comment.length >= 5 && comment.length <= 500;
        
        submitBtn.disabled = !isValid;
        submitBtn.style.opacity = isValid ? '1' : '0.6';
    }
    
    // Form submission
    document.getElementById('testimonial-form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submit-btn');
        const rating = document.getElementById('rating-input').value;
        const comment = document.getElementById('comment').value.trim();
        
        // Final validation
        if (!rating || rating < 1 || rating > 5) {
            e.preventDefault();
            showNotification('{{ __("الرجاء اختيار تقييم") }}', 'error');
            return;
        }
        
        if (comment.length < 5) {
            e.preventDefault();
            showNotification('{{ __("يجب أن يكون التقييم 5 أحرف على الأقل") }}', 'error');
            return;
        }
        
        if (comment.length > 500) {
            e.preventDefault();
            showNotification('{{ __("التقييم طويل جدًا") }}', 'error');
            return;
        }
        
        // Show loading state
        submitBtn.innerHTML = '<div class="loading-spinner"></div> {{ __("جاري الإرسال...") }}';
        submitBtn.disabled = true;
    });
    
    // Real-time validation
    document.getElementById('comment').addEventListener('input', function() {
        updateCharacterCount();
        validateForm();
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCharacterCount();
        
        // Set initial rating if exists
        const initialRating = document.querySelector('input[name="rating"]').value;
        if (initialRating && initialRating > 0) {
            setRating(parseInt(initialRating));
        }
        
        validateForm();
    });
    
    // Auto-resize textarea
    document.getElementById('comment').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 300) + 'px';
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Submit with Ctrl+Enter
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            if (!document.getElementById('submit-btn').disabled) {
                document.getElementById('testimonial-form').submit();
            }
        }
        
        // Rate with number keys
        if (e.key >= '1' && e.key <= '5') {
            const rating = parseInt(e.key);
            setRating(rating);
        }
        
        // Cancel with Escape
        if (e.key === 'Escape') {
            if (confirm('{{ __("هل أنت متأكد أنك تريد الإلغاء؟ سيتم فقدان تقييمك.") }}')) {
                window.location.href = '{{ route("orders.show", $order) }}';
            }
        }
    });
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} slide-in`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 20px;
            right: auto;
            z-index: 9999;
            max-width: 300px;
            box-shadow: var(--shadow-xl);
            animation: slideIn 0.3s ease-out;
        `;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
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
    
    // Prevent multiple submissions
    let isSubmitting = false;
    document.getElementById('testimonial-form').addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }
        isSubmitting = true;
    });
    
    // Auto-save draft (optional)
    let autoSaveTimeout;
    function autoSaveDraft() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            const formData = {
                rating: document.getElementById('rating-input').value,
                comment: document.getElementById('comment').value,
                order_id: '{{ $order->id }}'
            };
            
            localStorage.setItem('testimonial_draft_' + '{{ $order->id }}', JSON.stringify(formData));
        }, 2000);
    }
    
    function loadDraft() {
        const draft = localStorage.getItem('testimonial_draft_' + '{{ $order->id }}');
        if (draft) {
            try {
                const data = JSON.parse(draft);
                
                if (confirm('{{ __("تم العثور على مسودة تقييم. هل ترغب في استعادتها؟") }}')) {
                    if (data.rating) {
                        setRating(parseInt(data.rating));
                    }
                    if (data.comment) {
                        document.getElementById('comment').value = data.comment;
                        updateCharacterCount();
                    }
                } else {
                    localStorage.removeItem('testimonial_draft_' + '{{ $order->id }}');
                }
            } catch (e) {
                localStorage.removeItem('testimonial_draft_' + '{{ $order->id }}');
            }
        }
    }
    
    // Enable auto-save
    document.getElementById('comment').addEventListener('input', autoSaveDraft);
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', autoSaveDraft);
    });
    
    // Load draft on page load
    loadDraft();
    
    // Clear draft on successful submission
    document.getElementById('testimonial-form').addEventListener('submit', () => {
        localStorage.removeItem('testimonial_draft_' + '{{ $order->id }}');
    });
    
    // Animate prompt buttons
    document.querySelectorAll('.prompt-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
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
</style>
@endpush