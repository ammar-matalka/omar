{{-- resources/views/educational/digital-cards.blade.php --}}
@extends('layouts.app')

@section('title', 'البطاقات التعليمية الرقمية')

@push('styles')
<style>
.digital-cards-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.page-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-info h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.header-subtitle {
    color: #6b7280;
    font-size: 1.1rem;
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.9rem;
}

.breadcrumb-nav a {
    color: #4f46e5;
    text-decoration: none;
}

.breadcrumb-nav a:hover {
    text-decoration: underline;
}

.filters-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.filters-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.3rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1.5rem;
}

.filters-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.filter-select, .filter-input {
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: white;
    color: #1f2937;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.filter-select:focus, .filter-input:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.filters-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    align-items: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid transparent;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #3730a3, #6b21a8);
    transform: translateY(-2px);
}

.btn-secondary {
    background: white;
    color: #6b7280;
    border-color: #d1d5db;
}

.btn-secondary:hover {
    background: #f9fafb;
    border-color: #9ca3af;
}

.cards-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.section-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.results-info {
    color: #6b7280;
    font-size: 0.9rem;
}

.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.digital-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
}

.digital-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
}

.digital-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border-color: #4f46e5;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.card-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.card-chain {
    margin-bottom: 1rem;
}

.chain-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
    font-size: 0.85rem;
    color: #6b7280;
}

.chain-icon {
    color: #4f46e5;
    font-size: 0.8rem;
}

.card-features {
    margin-bottom: 1.5rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: #f8fafc;
    border-radius: 8px;
    font-size: 0.8rem;
}

.feature-icon {
    color: #059669;
    font-size: 0.9rem;
}

.card-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
}

.btn-outline {
    background: transparent;
    border: 2px solid #4f46e5;
    color: #4f46e5;
}

.btn-outline:hover {
    background: #4f46e5;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6b7280;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination {
    display: flex;
    gap: 0.5rem;
}

.pagination .page-link {
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.3s ease;
}

.pagination .page-link:hover,
.pagination .page-item.active .page-link {
    background: #4f46e5;
    border-color: #4f46e5;
    color: white;
}

@media (max-width: 768px) {
    .digital-cards-page {
        padding: 1rem;
    }
    
    .page-header,
    .filters-section,
    .cards-section {
        padding: 1.5rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .cards-grid {
        grid-template-columns: 1fr;
    }
    
    .filters-actions {
        justify-content: center;
    }
}
</style>
@endpush

@section('content')
<div class="digital-cards-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <h1>
                        <div class="header-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        البطاقات التعليمية الرقمية
                    </h1>
                    <p class="header-subtitle">تصفح واختر البطاقات التعليمية الرقمية المناسبة لك</p>
                    <div class="breadcrumb-nav">
                        <a href="{{ route('educational.index') }}">النظام التعليمي</a>
                        <i class="fas fa-chevron-left"></i>
                        <span>البطاقات الرقمية</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <div class="filters-title">
                <div class="filters-icon">
                    <i class="fas fa-filter"></i>
                </div>
                فلترة البطاقات الرقمية
            </div>

            <form method="GET" action="{{ route('educational.digital-cards') }}" id="filtersForm">
                <div class="filters-grid">
                    <!-- Generation Filter -->
                    <div class="filter-group">
                        <label class="filter-label">الجيل</label>
                        <select name="generation_id" class="filter-select">
                            <option value="">كل الأجيال</option>
                            @foreach($generations as $generation)
                                <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                                    {{ $generation->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Duration Filter -->
                    <div class="filter-group">
                        <label class="filter-label">مدة الصلاحية</label>
                        <select name="duration" class="filter-select">
                            <option value="">كل المدد</option>
                            <option value="month" {{ request('duration') == 'month' ? 'selected' : '' }}>شهر واحد</option>
                            <option value="quarter" {{ request('duration') == 'quarter' ? 'selected' : '' }}>3 أشهر</option>
                            <option value="semester" {{ request('duration') == 'semester' ? 'selected' : '' }}>6 أشهر</option>
                            <option value="year" {{ request('duration') == 'year' ? 'selected' : '' }}>سنة كاملة</option>
                            <option value="unlimited" {{ request('duration') == 'unlimited' ? 'selected' : '' }}>غير محدود</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="filter-group">
                        <label class="filter-label">البحث</label>
                        <input type="text" name="search" class="filter-input" placeholder="ابحث عن بطاقة..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="filters-actions">
                    <a href="{{ route('educational.digital-cards') }}" class="btn btn-secondary">
                        <i class="fas fa-undo"></i>
                        إعادة تعيين
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        فلترة
                    </button>
                </div>
            </form>
        </div>

        <!-- Cards Section -->
        <div class="cards-section">
            <div class="section-header">
                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-th-large"></i>
                    </div>
                    النتائج
                </div>
                <div class="results-info">
                    {{ $digitalCards->total() }} بطاقة رقمية متاحة
                </div>
            </div>

            @if($digitalCards->count() > 0)
                <div class="cards-grid">
                    @foreach($digitalCards as $card)
                        <div class="digital-card">
                            <div class="card-header">
                                <div>
                                    <h3 class="card-title">{{ $card->name }}</h3>
                                </div>
                                <span class="card-status {{ $card->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $card->is_active ? 'متاحة' : 'غير متاحة' }}
                                </span>
                            </div>

                            <!-- Chain Information -->
                            <div class="card-chain">
                                <div class="chain-item">
                                    <i class="fas fa-graduation-cap chain-icon"></i>
                                    <span>{{ $card->platform->teacher->subject->generation->display_name }}</span>
                                </div>
                                <div class="chain-item">
                                    <i class="fas fa-book chain-icon"></i>
                                    <span>{{ $card->platform->teacher->subject->name }}</span>
                                </div>
                                <div class="chain-item">
                                    <i class="fas fa-user-tie chain-icon"></i>
                                    <span>{{ $card->platform->teacher->name }}</span>
                                </div>
                                <div class="chain-item">
                                    <i class="fas fa-desktop chain-icon"></i>
                                    <span>{{ $card->platform->name }}</span>
                                </div>
                            </div>

                            <!-- Digital Features -->
                            <div class="card-features">
                                <div class="features-grid">
                                    <div class="feature-item">
                                        <i class="fas fa-clock feature-icon"></i>
                                        <span>{{ $card->duration_display ?: 'صلاحية دائمة' }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-play-circle feature-icon"></i>
                                        <span>{{ $card->lessons_display ?: 'دروس غير محدودة' }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-mobile-alt feature-icon"></i>
                                        <span>رقمية</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-shipping-fast feature-icon"></i>
                                        <span>فورية</span>
                                    </div>
                                </div>
                            </div>

                            @if($card->description)
                                <p class="card-description" style="color: #6b7280; font-size: 0.85rem; margin-bottom: 1rem; line-height: 1.5;">
                                    {{ Str::limit($card->description, 100) }}
                                </p>
                            @endif

                            <!-- Actions -->
                            <div class="card-actions">
                                <a href="{{ route('educational.digital-cards.show', $card) }}" class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i>
                                    عرض التفاصيل
                                </a>
                                @auth
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addToCart({{ $card->id }})">
                                        <i class="fas fa-cart-plus"></i>
                                        إضافة للسلة
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-sign-in-alt"></i>
                                        تسجيل الدخول
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($digitalCards->hasPages())
                    <div class="pagination-wrapper">
                        {{ $digitalCards->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>لا توجد بطاقات رقمية</h3>
                    <p>لم يتم العثور على بطاقات رقمية تطابق معايير البحث المحددة.</p>
                    <a href="{{ route('educational.digital-cards') }}" class="btn btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-undo"></i>
                        عرض كل البطاقات
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('.filter-select');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filtersForm').submit();
        });
    });
    
    // Search input with delay
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filtersForm').submit();
            }, 1000);
        });
    }
});

// Add to cart function
function addToCart(cardId) {
    @auth
        // AJAX call to add to cart
        fetch('{{ route("educational.cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                package_id: cardId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showNotification('تم إضافة البطاقة إلى السلة بنجاح', 'success');
                // Update cart count if exists
                updateCartCount();
            } else {
                showNotification(data.message || 'حدث خطأ', 'error');
            }
        })
        .catch(error => {
            showNotification('حدث خطأ في الاتصال', 'error');
        });
    @else
        window.location.href = '{{ route("login") }}';
    @endauth
}

// Notification function
function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Update cart count
function updateCartCount() {
    fetch('{{ route("cart.count") }}')
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.querySelector('.cart-count');
            if (cartBadge) {
                cartBadge.textContent = data.count;
            }
        });
}

// CSS for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
document.head.appendChild(style);
</script>
@endpush