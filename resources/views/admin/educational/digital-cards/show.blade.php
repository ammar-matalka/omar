@extends('layouts.admin')

@section('title', 'تفاصيل البطاقة الرقمية - ' . $digitalCard->name)

@push('styles')
<style>
.digital-card-details {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.details-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.card-header-section {
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
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 2rem;
}

.header-info {
    flex: 1;
}

.card-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.title-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
}

.card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.95rem;
}

.meta-icon {
    color: #4f46e5;
}

.card-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-size: 0.9rem;
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

.header-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.main-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.content-section, .sidebar-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e5e7eb;
}

.section-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.feature-card {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
}

.feature-icon {
    font-size: 2rem;
    color: #4f46e5;
    margin-bottom: 1rem;
}

.feature-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.feature-label {
    color: #6b7280;
    font-weight: 600;
    font-size: 0.9rem;
}

.stats-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.stat-item {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 900;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6b7280;
    font-weight: 600;
    font-size: 0.85rem;
}

.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-value {
    color: #6b7280;
    font-weight: 500;
}

.description-section {
    margin-top: 2rem;
}

.description-text {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    color: #374151;
    line-height: 1.6;
    font-size: 0.95rem;
    border-left: 4px solid #4f46e5;
}

.actions-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border: 2px solid transparent;
    border-radius: 12px;
    font-size: 0.95rem;
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

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-2px);
}

.btn-warning {
    background: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background: #d97706;
    transform: translateY(-2px);
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

.usage-rate-chart {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-top: 1rem;
}

.progress-bar {
    background: #e5e7eb;
    border-radius: 10px;
    height: 20px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.progress-text {
    text-align: center;
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .digital-card-details {
        padding: 1rem 0;
    }
    
    .card-header-section {
        padding: 1.5rem;
    }
    
    .header-content {
        flex-direction: column;
        gap: 1rem;
    }
    
    .card-title {
        font-size: 1.75rem;
        flex-direction: column;
        text-align: center;
    }
    
    .card-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .main-content {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .features-grid, .stats-grid, .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .content-section, .sidebar-section {
        padding: 1.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="digital-card-details">
    <div class="details-container">
        <!-- Card Header -->
        <div class="card-header-section">
            <div class="header-content">
                <div class="header-info">
                    <h1 class="card-title">
                        <div class="title-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        {{ $digitalCard->name }}
                    </h1>
                    
                    <div class="card-meta">
                        <div class="meta-item">
                            <i class="fas fa-chalkboard-teacher meta-icon"></i>
                            {{ $digitalCard->platform->teaching_chain }}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar-plus meta-icon"></i>
                            تم الإنشاء: {{ $digitalCard->created_at->format('Y-m-d') }}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-edit meta-icon"></i>
                            آخر تحديث: {{ $digitalCard->updated_at->format('Y-m-d') }}
                        </div>
                    </div>

                    <div class="card-status {{ $digitalCard->is_active ? 'status-active' : 'status-inactive' }}">
                        <i class="fas fa-{{ $digitalCard->is_active ? 'check-circle' : 'times-circle' }}"></i>
                        {{ $digitalCard->is_active ? 'بطاقة نشطة' : 'بطاقة غير نشطة' }}
                    </div>
                </div>

                <div class="header-actions">
                    <a href="{{ route('admin.educational.digital-cards.edit', $digitalCard) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                        تعديل البطاقة
                    </a>
                    <a href="{{ route('admin.educational.digital-cards.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Content Section -->
            <div class="content-section">
                <h2 class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    مواصفات البطاقة الرقمية
                </h2>

                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="feature-value">
                            {{ $digitalCard->duration_display ?: 'دائمة' }}
                        </div>
                        <div class="feature-label">مدة الصلاحية</div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="feature-value">
                            {{ $digitalCard->lessons_display ?: 'غير محدود' }}
                        </div>
                        <div class="feature-label">عدد الدروس</div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <div class="feature-value">رقمية</div>
                        <div class="feature-label">نوع البطاقة</div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="feature-value">فورية</div>
                        <div class="feature-label">التسليم</div>
                    </div>
                </div>

                @if($digitalCard->description)
                    <div class="description-section">
                        <h3 class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-align-left"></i>
                            </div>
                            وصف البطاقة
                        </h3>
                        <div class="description-text">
                            {{ $digitalCard->description }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Section -->
            <div class="sidebar-section">
                <h2 class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    معلومات تقنية
                </h2>

                <ul class="info-list">
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-hashtag"></i>
                            معرف البطاقة
                        </span>
                        <span class="info-value">{{ $digitalCard->id }}</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-layer-group"></i>
                            نوع المنتج
                        </span>
                        <span class="info-value">{{ $digitalCard->productType->name }}</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-shipping-fast"></i>
                            يتطلب شحن
                        </span>
                        <span class="info-value">لا</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-warehouse"></i>
                            يتطلب مخزون
                        </span>
                        <span class="info-value">لا</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-desktop"></i>
                            المنصة
                        </span>
                        <span class="info-value">{{ $digitalCard->platform->name }}</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-user"></i>
                            المعلم
                        </span>
                        <span class="info-value">{{ $digitalCard->platform->teacher->name }}</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-book"></i>
                            المادة
                        </span>
                        <span class="info-value">{{ $digitalCard->platform->teacher->subject->name }}</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label">
                            <i class="fas fa-users"></i>
                            الجيل
                        </span>
                        <span class="info-value">{{ $digitalCard->platform->teacher->subject->generation->display_name }}</span>
                    </li>
                </ul>

                @if(isset($stats['usage_rate']) && $stats['total_cards_generated'] > 0)
                    <div class="usage-rate-chart">
                        <h4 style="margin-bottom: 1rem; color: #374151;">معدل الاستخدام</h4>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $stats['usage_rate'] }}%"></div>
                        </div>
                        <div class="progress-text">{{ $stats['usage_rate'] }}% من البطاقات مُستخدمة</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <h2 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                إحصائيات البطاقة
            </h2>

            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($stats['total_cards_generated'] ?? 0) }}</div>
                    <div class="stat-label">إجمالي البطاقات المُولدة</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($stats['active_cards'] ?? 0) }}</div>
                    <div class="stat-label">البطاقات النشطة</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($stats['used_cards'] ?? 0) }}</div>
                    <div class="stat-label">البطاقات المُستخدمة</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($stats['expired_cards'] ?? 0) }}</div>
                    <div class="stat-label">البطاقات المنتهية</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($stats['total_revenue'] ?? 0, 2) }} د.أ</div>
                    <div class="stat-label">إجمالي الإيرادات</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($stats['average_price'] ?? 0, 2) }} د.أ</div>
                    <div class="stat-label">متوسط السعر</div>
                </div>
            </div>
        </div>

        <!-- Actions Section -->
        <div class="actions-section">
            <h2 class="section-title">
                <div class="section-icon">
                    <i class="fas fa-tools"></i>
                </div>
                الإجراءات المتاحة
            </h2>

            <div class="actions-grid">
                <a href="{{ route('admin.educational.digital-cards.edit', $digitalCard) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    تعديل البطاقة
                </a>
                
                <button type="button" class="btn {{ $digitalCard->is_active ? 'btn-danger' : 'btn-success' }}" 
                        onclick="toggleStatus()">
                    <i class="fas fa-toggle-{{ $digitalCard->is_active ? 'off' : 'on' }}"></i>
                    {{ $digitalCard->is_active ? 'إلغاء التفعيل' : 'تفعيل البطاقة' }}
                </button>
                
                <a href="{{ route('admin.educational.pricing.create', ['package_id' => $digitalCard->id]) }}" class="btn btn-primary">
                    <i class="fas fa-dollar-sign"></i>
                    إدارة التسعير
                </a>
                
                <button type="button" class="btn btn-secondary" onclick="showCloneModal()">
                    <i class="fas fa-copy"></i>
                    نسخ لمنصة أخرى
                </button>
                
                <a href="{{ route('admin.educational.digital-cards.export', ['package_id' => $digitalCard->id]) }}" class="btn btn-secondary">
                    <i class="fas fa-download"></i>
                    تصدير البيانات
                </a>
                
                @if($stats['total_cards_generated'] == 0)
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i>
                        حذف البطاقة
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus() {
    const isActive = {{ $digitalCard->is_active ? 'true' : 'false' }};
    const action = isActive ? 'إلغاء تفعيل' : 'تفعيل';
    
    if (confirm(`هل أنت متأكد من ${action} هذه البطاقة؟`)) {
        fetch(`{{ route('admin.educational.digital-cards.toggle-status', $digitalCard) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
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

function showCloneModal() {
    // يمكنك إضافة modal لاختيار المنصة المستهدفة
    const targetPlatform = prompt('أدخل معرف المنصة المستهدفة:');
    
    if (targetPlatform) {
        cloneCard(targetPlatform);
    }
}

function cloneCard(targetPlatformId) {
    fetch(`{{ route('admin.educational.digital-cards.clone', $digitalCard) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            target_platform_id: targetPlatformId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم نسخ البطاقة بنجاح');
            window.location.href = data.redirect_url;
        } else {
            alert('حدث خطأ أثناء نسخ البطاقة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء نسخ البطاقة');
    });
}

function confirmDelete() {
    if (confirm('هل أنت متأكد من حذف هذه البطاقة؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.educational.digital-cards.destroy', $digitalCard) }}`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        
        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush