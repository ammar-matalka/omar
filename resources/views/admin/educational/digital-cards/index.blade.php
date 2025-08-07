@extends('layouts.admin')

@section('title', 'البطاقات التعليمية الرقمية')

@push('styles')
<style>
.digital-cards-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.main-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
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
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.header-subtitle {
    color: #6b7280;
    font-size: 1rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.stats-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--stat-color, #4f46e5);
}

.stat-number {
    font-size: 2rem;
    font-weight: 900;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6b7280;
    font-weight: 600;
    font-size: 0.9rem;
}

.filters-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.form-group {
    margin-bottom: 0;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.form-input, .form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    background: white;
    color: #1f2937;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.cards-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.card-item {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.card-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.card-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
}

.card-header {
    display: flex;
    justify-content: between;
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

.card-platform {
    color: #6b7280;
    font-size: 0.85rem;
    margin-bottom: 1rem;
}

.card-features {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #374151;
}

.feature-icon {
    color: #4f46e5;
    width: 16px;
}

.card-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.status-active {
    background: #dcfce7;
    color: #166534;
}

.status-inactive {
    background: #fef2f2;
    color: #991b1b;
}

.card-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: 2px solid transparent;
    border-radius: 8px;
    font-size: 0.85rem;
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
    transform: translateY(-1px);
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

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-1px);
}

.btn-warning {
    background: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background: #d97706;
    transform: translateY(-1px);
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .digital-cards-page {
        padding: 1rem 0;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-actions {
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .cards-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="digital-cards-page">
    <div class="main-container">
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
                    <p class="header-subtitle">إدارة وتتبع البطاقات التعليمية الرقمية وإحصائيات الاستخدام</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.educational.digital-cards.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        إنشاء بطاقة رقمية
                    </a>
                    <a href="{{ route('admin.educational.digital-cards.export') }}" class="btn btn-secondary">
                        <i class="fas fa-download"></i>
                        تصدير
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card" style="--stat-color: #4f46e5;">
                    <div class="stat-number">{{ $digitalCards->total() }}</div>
                    <div class="stat-label">إجمالي البطاقات</div>
                </div>
                <div class="stat-card" style="--stat-color: #10b981;">
                    <div class="stat-number">{{ $digitalCards->where('is_active', true)->count() }}</div>
                    <div class="stat-label">البطاقات النشطة</div>
                </div>
                <div class="stat-card" style="--stat-color: #f59e0b;">
                    <div class="stat-number">{{ $digitalCards->where('is_active', false)->count() }}</div>
                    <div class="stat-label">غير نشطة</div>
                </div>
                <div class="stat-card" style="--stat-color: #8b5cf6;">
                    <div class="stat-number">{{ $digitalCards->whereNotNull('duration_days')->count() }}</div>
                    <div class="stat-label">بصلاحية محددة</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('admin.educational.digital-cards.index') }}">
                <div class="filters-grid">
                    <div class="form-group">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" name="search" id="search" class="form-input" 
                               value="{{ request('search') }}" 
                               placeholder="ابحث في أسماء البطاقات...">
                    </div>
                    
                    <div class="form-group">
                        <label for="generation_id" class="form-label">الجيل</label>
                        <select name="generation_id" id="generation_id" class="form-select">
                            <option value="">جميع الأجيال</option>
                            @foreach($generations as $generation)
                                <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                                    {{ $generation->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="platform_id" class="form-label">المنصة</label>
                        <select name="platform_id" id="platform_id" class="form-select">
                            <option value="">جميع المنصات</option>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform->id }}" {{ request('platform_id') == $platform->id ? 'selected' : '' }}>
                                    {{ $platform->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration_filter" class="form-label">نوع الصلاحية</label>
                        <select name="duration_filter" id="duration_filter" class="form-select">
                            <option value="">جميع الأنواع</option>
                            <option value="month" {{ request('duration_filter') == 'month' ? 'selected' : '' }}>شهر أو أقل</option>
                            <option value="quarter" {{ request('duration_filter') == 'quarter' ? 'selected' : '' }}>3 أشهر</option>
                            <option value="semester" {{ request('duration_filter') == 'semester' ? 'selected' : '' }}>6 أشهر</option>
                            <option value="year" {{ request('duration_filter') == 'year' ? 'selected' : '' }}>أكثر من 6 أشهر</option>
                            <option value="unlimited" {{ request('duration_filter') == 'unlimited' ? 'selected' : '' }}>صلاحية دائمة</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="is_active" class="form-label">الحالة</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشطة</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشطة</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="margin-top: 1.7rem;">
                            <i class="fas fa-search"></i>
                            تطبيق الفلاتر
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Digital Cards Grid -->
        <div class="cards-section">
            @if($digitalCards->count() > 0)
                <div class="cards-grid">
                    @foreach($digitalCards as $card)
                        <div class="card-item">
                            <div class="card-header">
                                <div style="flex: 1;">
                                    <h3 class="card-title">{{ $card->name }}</h3>
                                    <div class="card-platform">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        {{ $card->platform->teaching_chain }}
                                    </div>
                                </div>
                            </div>

                            <div class="card-features">
                                <div class="feature-item">
                                    <i class="fas fa-clock feature-icon"></i>
                                    {{ $card->duration_display ?: 'صلاحية دائمة' }}
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-play-circle feature-icon"></i>
                                    {{ $card->lessons_display ?: 'دروس غير محدودة' }}
                                </div>
                                @if($card->description)
                                    <div class="feature-item" style="grid-column: 1 / -1;">
                                        <i class="fas fa-info-circle feature-icon"></i>
                                        {{ Str::limit($card->description, 100) }}
                                    </div>
                                @endif
                            </div>

                            <div class="card-status {{ $card->is_active ? 'status-active' : 'status-inactive' }}">
                                <i class="fas fa-{{ $card->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                {{ $card->is_active ? 'نشطة' : 'غير نشطة' }}
                            </div>

                            <div class="card-actions">
                                <a href="{{ route('admin.educational.digital-cards.show', $card) }}" class="btn btn-secondary">
                                    <i class="fas fa-eye"></i>
                                    عرض
                                </a>
                                <a href="{{ route('admin.educational.digital-cards.edit', $card) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                    تعديل
                                </a>
                                <button type="button" class="btn {{ $card->is_active ? 'btn-warning' : 'btn-success' }}" 
                                        onclick="toggleStatus({{ $card->id }}, {{ $card->is_active ? 'false' : 'true' }})">
                                    <i class="fas fa-toggle-{{ $card->is_active ? 'off' : 'on' }}"></i>
                                    {{ $card->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $digitalCards->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3>لا توجد بطاقات رقمية</h3>
                    <p>ابدأ بإنشاء أول بطاقة تعليمية رقمية</p>
                    <a href="{{ route('admin.educational.digital-cards.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        إنشاء بطاقة رقمية
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(cardId, newStatus) {
    if (confirm('هل أنت متأكد من تغيير حالة البطاقة؟')) {
        fetch(`/admin/educational/digital-cards/${cardId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                is_active: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء تغيير الحالة');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تغيير الحالة');
        });
    }
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('#generation_id, #platform_id, #duration_filter, #is_active');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush