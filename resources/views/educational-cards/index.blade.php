@extends('layouts.app')

@section('title', __('Educational Cards') . ' - ' . config('app.name'))

@push('styles')
<style>
    .educational-hero {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-3xl) 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .educational-hero::before {
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
        max-width: 800px;
        margin: 0 auto;
    }
    
    .hero-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-lg);
        font-size: 2rem;
    }
    
    .hero-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: var(--space-md);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: var(--space-xl);
        line-height: 1.6;
    }

    .cards-container {
        padding: var(--space-3xl) 0;
        background: var(--background);
    }

    .section-header {
        text-align: center;
        margin-bottom: var(--space-2xl);
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: var(--space-md);
        background: linear-gradient(135deg, var(--primary-600), var(--secondary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .section-subtitle {
        font-size: 1.125rem;
        color: var(--on-surface-variant);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.7;
    }

    .year-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-2xl);
    }

    .year-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: all var(--transition-normal);
        position: relative;
        cursor: pointer;
        box-shadow: var(--shadow-sm);
    }

    .year-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
        transform: scaleX(0);
        transition: transform var(--transition-normal);
    }

    .year-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-200);
    }

    .year-card:hover::before {
        transform: scaleX(1);
    }

    .year-card-header {
        background: linear-gradient(135deg, var(--primary-100), var(--secondary-100));
        padding: var(--space-xl);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .year-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><circle cx="30" cy="30" r="20"/><circle cx="70" cy="70" r="15"/></svg>');
        animation: float 20s ease-in-out infinite;
    }

    .year-number {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-md);
        font-size: 1.5rem;
        font-weight: 900;
        position: relative;
        z-index: 1;
        box-shadow: var(--shadow-md);
    }

    .year-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-700);
        position: relative;
        z-index: 1;
    }

    .year-card-content {
        padding: var(--space-xl);
    }

    .year-description {
        color: var(--on-surface-variant);
        margin-bottom: var(--space-lg);
        line-height: 1.6;
        text-align: center;
    }

    .year-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: var(--space-lg);
        padding: var(--space-md);
        background: var(--surface-variant);
        border-radius: var(--radius-lg);
    }

    .year-stat {
        text-align: center;
        flex: 1;
    }

    .year-stat-number {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-600);
        display: block;
    }

    .year-stat-label {
        font-size: 0.75rem;
        color: var(--on-surface-variant);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-xs);
    }

    .year-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-50), var(--secondary-50));
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        transition: all var(--transition-fast);
        gap: var(--space-sm);
    }

    .cta-text {
        font-weight: 600;
        color: var(--primary-700);
    }

    .cta-icon {
        color: var(--primary-600);
        font-size: 1.125rem;
        transition: transform var(--transition-fast);
    }

    .year-card:hover .cta-icon {
        transform: translateX(4px);
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease-out;
    }

    .modal-content {
        background: var(--surface);
        border-radius: var(--radius-2xl);
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
        box-shadow: var(--shadow-2xl);
        animation: slideIn 0.3s ease-out;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
        padding: var(--space-xl);
        border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
        position: relative;
        text-align: center;
    }

    .modal-close {
        position: absolute;
        top: var(--space-md);
        right: var(--space-md);
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: all var(--transition-fast);
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-sm);
    }

    .modal-subtitle {
        opacity: 0.9;
        font-size: 1rem;
    }

    .modal-body {
        padding: var(--space-2xl);
    }

    .order-form {
        display: grid;
        gap: var(--space-lg);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-lg);
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-weight: 600;
        color: var(--on-surface);
        font-size: 0.875rem;
        text-align: right;
    }

    .form-control {
        padding: var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--on-surface);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        text-align: right;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }

    .form-control:disabled {
        background: var(--surface-variant);
        color: var(--on-surface-variant);
        cursor: not-allowed;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: var(--space-xl);
        padding-top: var(--space-lg);
        border-top: 1px solid var(--border-color);
        gap: var(--space-md);
    }

    .btn {
        padding: var(--space-md) var(--space-xl);
        border-radius: var(--radius-lg);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        text-decoration: none;
        border: none;
        font-size: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
        color: white;
    }

    .btn-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        color: white;
    }

    .btn-secondary {
        background: var(--surface-variant);
        color: var(--on-surface-variant);
        border: 1px solid var(--border-color);
    }

    .btn-secondary:hover {
        background: var(--surface);
        color: var(--on-surface);
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from { 
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to { 
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(5deg); }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .year-cards-grid {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }

        .modal-content {
            width: 95%;
            margin: var(--space-md);
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: var(--space-md);
        }

        .form-actions {
            flex-direction: column;
            gap: var(--space-md);
        }

        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="educational-hero">
    <div class="container">
        <div class="hero-content fade-in">
            <div class="hero-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1 class="hero-title">{{ __('Educational Cards') }}</h1>
            <p class="hero-subtitle">{{ __('Choose your academic year to access educational cards') }}</p>
        </div>
    </div>
</section>

<!-- Cards Container -->
<section class="cards-container">
    <div class="container">
        <div class="section-header fade-in">
            <h2 class="section-title">{{ __('Select Your Academic Year') }}</h2>
            <p class="section-subtitle">{{ __('Browse educational cards by academic year. Each year contains comprehensive study materials designed by expert educators.') }}</p>
        </div>
        
        <div class="year-cards-grid">
            @php
                $years = [
                    ['year' => '2024', 'description' => 'Latest curriculum with updated content', 'subjects' => 12, 'cards' => 150],
                    ['year' => '2023', 'description' => 'Comprehensive study materials', 'subjects' => 11, 'cards' => 140],
                    ['year' => '2022', 'description' => 'Well-structured educational content', 'subjects' => 10, 'cards' => 130],
                    ['year' => '2021', 'description' => 'Quality learning resources', 'subjects' => 9, 'cards' => 120],
                    ['year' => '2020', 'description' => 'Proven educational methods', 'subjects' => 8, 'cards' => 110],
                    ['year' => '2019', 'description' => 'Time-tested curriculum', 'subjects' => 8, 'cards' => 100],
                    ['year' => '2018', 'description' => 'Classic educational approach', 'subjects' => 7, 'cards' => 90],
                    ['year' => '2017', 'description' => 'Traditional learning methods', 'subjects' => 7, 'cards' => 85],
                    ['year' => '2016', 'description' => 'Established curriculum', 'subjects' => 6, 'cards' => 80],
                    ['year' => '2015', 'description' => 'Fundamental learning', 'subjects' => 6, 'cards' => 75],
                    ['year' => '2014', 'description' => 'Core educational content', 'subjects' => 5, 'cards' => 70],
                    ['year' => '2013', 'description' => 'Basic study materials', 'subjects' => 5, 'cards' => 65],
                ];
            @endphp

            @foreach($years as $index => $yearData)
                <div class="year-card fade-in" onclick="openOrderModal('{{ $yearData['year'] }}')" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="year-card-header">
                        <div class="year-number">{{ $yearData['year'] }}</div>
                        <h3 class="year-title">{{ __('Year') }} {{ $yearData['year'] }}</h3>
                    </div>
                    
                    <div class="year-card-content">
                        <p class="year-description">{{ $yearData['description'] }}</p>
                        
                        <div class="year-stats">
                            <div class="year-stat">
                                <span class="year-stat-number">{{ $yearData['subjects'] }}</span>
                                <div class="year-stat-label">{{ __('Subjects') }}</div>
                            </div>
                            <div class="year-stat">
                                <span class="year-stat-number">{{ $yearData['cards'] }}</span>
                                <div class="year-stat-label">{{ __('Cards') }}</div>
                            </div>
                        </div>
                        
                        <div class="year-cta">
                            <span class="cta-text">{{ __('Order Card') }}</span>
                            <i class="cta-icon fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Order Modal -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <button class="modal-close" onclick="closeOrderModal()">
                <i class="fas fa-times"></i>
            </button>
            <h2 class="modal-title">{{ __('Educational Card Order') }}</h2>
            <p class="modal-subtitle" id="selectedYear"></p>
        </div>
        
        <div class="modal-body">
            <form id="orderForm" class="order-form">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="generation">{{ __('Generation') }}</label>
                        <select id="generation" name="generation" class="form-control" required>
                            <option value="">-- {{ __('Select Generation') }} --</option>
                            <option value="2024">{{ __('Generation 2024') }}</option>
                            <option value="2023">{{ __('Generation 2023') }}</option>
                            <option value="2022">{{ __('Generation 2022') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="subject">{{ __('Subject') }}</label>
                        <select id="subject" name="subject" class="form-control" required>
                            <option value="">-- {{ __('Select Subject') }} --</option>
                            <option value="math">{{ __('Mathematics') }}</option>
                            <option value="science">{{ __('Science') }}</option>
                            <option value="english">{{ __('English') }}</option>
                            <option value="arabic">{{ __('Arabic') }}</option>
                            <option value="history">{{ __('History') }}</option>
                            <option value="geography">{{ __('Geography') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="teacher">{{ __('Teacher') }}</label>
                        <select id="teacher" name="teacher" class="form-control" required>
                            <option value="">-- {{ __('Select Teacher') }} --</option>
                            <option value="teacher1">{{ __('Teacher A') }}</option>
                            <option value="teacher2">{{ __('Teacher B') }}</option>
                            <option value="teacher3">{{ __('Teacher C') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="semester">{{ __('Semester') }}</label>
                        <select id="semester" name="semester" class="form-control" required>
                            <option value="">-- {{ __('Select Semester') }} --</option>
                            <option value="first">{{ __('First Semester') }}</option>
                            <option value="second">{{ __('Second Semester') }}</option>
                            <option value="full_year">{{ __('Full Year') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="platform">{{ __('Platform') }}</label>
                        <select id="platform" name="platform" class="form-control" required>
                            <option value="">-- {{ __('Select Platform') }} --</option>
                            <option value="platform1">{{ __('Platform A') }}</option>
                            <option value="platform2">{{ __('Platform B') }}</option>
                            <option value="platform3">{{ __('Platform C') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="notebook_type">{{ __('Notebook Type') }}</label>
                        <select id="notebook_type" name="notebook_type" class="form-control" required>
                            <option value="">-- {{ __('Select Notebook Type') }} --</option>
                            <option value="digital">{{ __('Digital') }}</option>
                            <option value="physical">{{ __('Physical') }}</option>
                            <option value="both">{{ __('Both') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="quantity">{{ __('Quantity') }}</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" max="10" value="1" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="notes">{{ __('Notes') }}</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="{{ __('Any additional notes or requirements...') }}"></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeOrderModal()">
                        <i class="fas fa-times"></i>
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i>
                        {{ __('Confirm Order') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation for cards
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });

    // Form submission
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Processing...") }}';
        submitBtn.disabled = true;
        
        // Get form data
        const formData = new FormData(this);
        
        // Submit via AJAX
        fetch('{{ route("educational-cards.submit-order") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            if (data.success) {
                // Close modal
                closeOrderModal();
                
                // Show success message with order details
                showNotification(
                    `{{ __("Order submitted successfully!") }}<br>
                    {{ __("Order ID:") }} #${data.order_id}<br>
                    {{ __("Total:") }} ${data.total_amount}`, 
                    'success'
                );
                
                // Reset form
                this.reset();
            } else {
                showNotification(data.message || '{{ __("Error submitting order") }}', 'error');
            }
        })
        .catch(error => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            showNotification('{{ __("Error submitting order. Please try again.") }}', 'error');
        });
    });
});

function openOrderModal(year) {
    const modal = document.getElementById('orderModal');
    const selectedYearEl = document.getElementById('selectedYear');
    
    selectedYearEl.textContent = `{{ __('Academic Year') }} ${year}`;
    modal.classList.add('active');
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeOrderModal() {
    const modal = document.getElementById('orderModal');
    modal.classList.remove('active');
    
    // Restore body scroll
    document.body.style.overflow = '';
}

// Close modal when clicking outside
document.getElementById('orderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeOrderModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeOrderModal();
    }
});

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        max-width: 300px;
        padding: 1rem;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideInNotification 0.3s ease-out;
    `;
    
    if (type === 'success') {
        notification.style.background = 'linear-gradient(135deg, #10b981, #059669)';
    } else if (type === 'error') {
        notification.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
    } else {
        notification.style.background = 'linear-gradient(135deg, #3b82f6, #2563eb)';
    }
    
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutNotification 0.3s ease-out forwards';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Add notification animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInNotification {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutNotification {
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush