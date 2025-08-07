@extends('layouts.admin')

@section('title', 'تفاصيل الدوسية الورقية - ' . $physicalBooklet->name)

@push('styles')
<style>
.show-booklet-page {
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
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-info {
    flex: 1;
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
    background: linear-gradient(135deg, #059669, #047857);
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
    margin-bottom: 1rem;
}

.header-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f8fafc;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-size: 0.85rem;
    color: #374151;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    flex-wrap: wrap;
}

.status-badge {
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

.content-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
}

.main-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.sidebar {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.info-section, .stats-section, .inventory-section, .actions-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.section-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.section-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.info-item {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 15px;
    border: 2px solid #e2e8f0;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #6b7280;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.info-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1f2937;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
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
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--stat-color, #059669);
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 900;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #6b7280;
    font-weight: 600;
    font-size: 0.8rem;
}

.inventory-status {
    background: #f0fdf4;
    border: 2px solid #bbf7d0;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.inventory-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.inventory-icon {
    width: 30px;
    height: 30px;
    background: #059669;
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.inventory-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.inventory-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.inventory-item {
    text-align: center;
}

.inventory-number {
    font-size: 1.3rem;
    font-weight: 800;
    color: #059669;
    margin-bottom: 0.25rem;
}

.inventory-label {
    color: #6b7280;
    font-size: 0.8rem;
    font-weight: 600;
}

.no-inventory {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.no-inventory-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid transparent;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    width: 100%;
    margin-bottom: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #047857, #065f46);
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

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-1px);
}

.btn-info {
    background: #3b82f6;
    color: white;
}

.btn-info:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.description-section {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.description-text {
    color: #374151;
    line-height: 1.6;
    margin: 0;
}

.platform-info {
    background: #f0f9ff;
    border: 2px solid #bae6fd;
    border-radius: 15px;
    padding: 1.5rem;
}

.platform-chain {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #0369a1;
    font-weight: 600;
    margin-bottom: 1rem;
}

.chain-separator {
    color: #64748b;
    font-weight: 400;
}

.platform-details {
    display: grid;
    gap: 0.75rem;
}

.platform-detail {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e0f2fe;
}

.platform-detail:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.85rem;
}

.detail-value {
    color: #6b7280;
    font-size: 0.85rem;
}

@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .sidebar {
        order: -1;
    }
    
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (max-width: 768px) {
    .show-booklet-page {
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
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .inventory-details {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="show-booklet-page">
    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <h1>
                        <div class="header-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        {{ $physicalBooklet->name }}
                    </h1>
                    <p class="header-subtitle">
                        <i class="fas fa-chalkboard-teacher"></i>
                        {{ $physicalBooklet->platform->teacher->subject->generation->display_name }} - 
                        {{ $physicalBooklet->platform->teacher->subject->name }} - 
                        {{ $physicalBooklet->platform->teacher->name }}
                    </p>
                    
                    <div class="status-badge {{ $physicalBooklet->is_active ? 'status-active' : 'status-inactive' }}">
                        <i class="fas fa-{{ $physicalBooklet->is_active ? 'check-circle' : 'times-circle' }}"></i>
                        {{ $physicalBooklet->is_active ? 'نشطة' : 'غير نشطة' }}
                    </div>
                    
                    <div class="header-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-plus"></i>
                            أُنشئت: {{ $physicalBooklet->created_at->format('Y/m/d') }}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-edit"></i>
                            آخر تحديث: {{ $physicalBooklet->updated_at->format('Y/m/d') }}
                        </div>
                        @if($physicalBooklet->pages_count)
                            <div class="meta-item">
                                <i class="fas fa-file-alt"></i>
                                {{ $physicalBooklet->pages_count }} صفحة
                            </div>
                        @endif
                        @if($physicalBooklet->weight_grams)
                            <div class="meta-item">
                                <i class="fas fa-weight"></i>
                                {{ number_format($physicalBooklet->weight_grams / 1000, 2) }} كيلو
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="header-actions">
                    <a href="{{ route('admin.educational.physical-booklets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                    <a href="{{ route('admin.educational.physical-booklets.edit', $physicalBooklet) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                        تعديل
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="content-grid">
            <!-- Main Content -->
            <div class="main-content">
                <!-- Basic Information -->
                <div class="info-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h2 class="section-title">المعلومات الأساسية</h2>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-hashtag"></i>
                                معرف الدوسية
                            </div>
                            <div class="info-value">#{{ $physicalBooklet->id }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-layer-group"></i>
                                نوع المنتج
                            </div>
                            <div class="info-value">{{ $physicalBooklet->productType->name }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-file-alt"></i>
                                عدد الصفحات
                            </div>
                            <div class="info-value">{{ $physicalBooklet->pages_count ?: 'غير محدد' }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-weight"></i>
                                الوزن
                            </div>
                            <div class="info-value">
                                @if($physicalBooklet->weight_grams)
                                    {{ $physicalBooklet->weight_grams }} جرام 
                                    ({{ number_format($physicalBooklet->weight_grams / 1000, 2) }} كيلو)
                                @else
                                    غير محدد
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($physicalBooklet->description)
                        <div class="description-section">
                            <div class="info-label">
                                <i class="fas fa-align-left"></i>
                                الوصف
                            </div>
                            <p class="description-text">{{ $physicalBooklet->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Platform Information -->
                <div class="info-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h2 class="section-title">معلومات المنصة</h2>
                    </div>
                    
                    <div class="platform-info">
                        <div class="platform-chain">
                            <i class="fas fa-sitemap"></i>
                            {{ $physicalBooklet->platform->teacher->subject->generation->display_name }}
                            <span class="chain-separator">←</span>
                            {{ $physicalBooklet->platform->teacher->subject->name }}
                            <span class="chain-separator">←</span>
                            {{ $physicalBooklet->platform->teacher->name }}
                            <span class="chain-separator">←</span>
                            {{ $physicalBooklet->platform->name }}
                        </div>
                        
                        <div class="platform-details">
                            <div class="platform-detail">
                                <span class="detail-label">الجيل:</span>
                                <span class="detail-value">{{ $physicalBooklet->platform->teacher->subject->generation->display_name }}</span>
                            </div>
                            <div class="platform-detail">
                                <span class="detail-label">المادة:</span>
                                <span class="detail-value">{{ $physicalBooklet->platform->teacher->subject->name }}</span>
                            </div>
                            <div class="platform-detail">
                                <span class="detail-label">المعلم:</span>
                                <span class="detail-value">{{ $physicalBooklet->platform->teacher->name }}</span>
                            </div>
                            <div class="platform-detail">
                                <span class="detail-label">المنصة:</span>
                                <span class="detail-value">{{ $physicalBooklet->platform->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Statistics -->
                <div class="stats-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3 class="section-title">الإحصائيات</h3>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card" style="--stat-color: #059669;">
                            <div class="stat-number">{{ $stats['total_orders'] }}</div>
                            <div class="stat-label">إجمالي الطلبات</div>
                        </div>
                        
                        <div class="stat-card" style="--stat-color: #3b82f6;">
                            <div class="stat-number">{{ $stats['total_quantity_sold'] }}</div>
                            <div class="stat-label">الكمية المباعة</div>
                        </div>
                        
                        <div class="stat-card" style="--stat-color: #f59e0b;">
                            <div class="stat-number">{{ number_format($stats['total_revenue'], 2) }}</div>
                            <div class="stat-label">إجمالي المبيعات</div>
                        </div>
                        
                        <div class="stat-card" style="--stat-color: #8b5cf6;">
                            <div class="stat-number">{{ number_format($stats['average_price'], 2) }}</div>
                            <div class="stat-label">متوسط السعر</div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Information -->
                <div class="inventory-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h3 class="section-title">حالة المخزون</h3>
                    </div>
                    
                    @if($inventory)
                        <div class="inventory-status">
                            <div class="inventory-header">
                                <div class="inventory-icon">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                                <h4 class="inventory-title">{{ $stats['stock_status'] }}</h4>
                            </div>
                            
                            <div class="inventory-details">
                                <div class="inventory-item">
                                    <div class="inventory-number">{{ $stats['inventory_available'] }}</div>
                                    <div class="inventory-label">متاح</div>
                                </div>
                                
                                <div class="inventory-item">
                                    <div class="inventory-number">{{ $stats['inventory_reserved'] }}</div>
                                    <div class="inventory-label">محجوز</div>
                                </div>
                                
                                <div class="inventory-item">
                                    <div class="inventory-number">{{ $stats['inventory_actual'] }}</div>
                                    <div class="inventory-label">فعلي متاح</div>
                                </div>
                                
                                <div class="inventory-item">
                                    <div class="inventory-number">{{ $inventory->total_quantity }}</div>
                                    <div class="inventory-label">إجمالي</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="no-inventory">
                            <div class="no-inventory-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h4>لا يوجد مخزون</h4>
                            <p>لم يتم إنشاء مخزون لهذه الدوسية بعد</p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="actions-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3 class="section-title">الإجراءات</h3>
                    </div>
                    
                    <a href="{{ route('admin.educational.physical-booklets.edit', $physicalBooklet) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                        تعديل الدوسية
                    </a>
                    
                    <button type="button" class="btn {{ $physicalBooklet->is_active ? 'btn-warning' : 'btn-success' }}" 
                            onclick="toggleStatus({{ $physicalBooklet->id }}, {{ $physicalBooklet->is_active ? 'false' : 'true' }})">
                        <i class="fas fa-toggle-{{ $physicalBooklet->is_active ? 'off' : 'on' }}"></i>
                        {{ $physicalBooklet->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}
                    </button>
                    
                    @if($inventory)
                        <button type="button" class="btn btn-info" onclick="addInventory()">
                            <i class="fas fa-plus"></i>
                            إضافة مخزون
                        </button>
                    @else
                        <button type="button" class="btn btn-primary" onclick="createInventory()">
                            <i class="fas fa-plus"></i>
                            إنشاء مخزون
                        </button>
                    @endif
                    
                    <button type="button" class="btn btn-secondary" onclick="cloneBooklet()">
                        <i class="fas fa-copy"></i>
                        نسخ لمنصة أخرى
                    </button>
                    
                    <button type="button" class="btn btn-danger" onclick="deleteBooklet()">
                        <i class="fas fa-trash"></i>
                        حذف الدوسية
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(bookletId, newStatus) {
    if (confirm('هل أنت متأكد من تغيير حالة الدوسية؟')) {
        fetch(`/admin/educational/physical-booklets/${bookletId}/toggle-status`, {
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

function addInventory() {
    const quantity = prompt('كم قطعة تريد إضافتها للمخزون؟');
    if (quantity && !isNaN(quantity) && parseInt(quantity) > 0) {
        fetch(`/admin/educational/physical-booklets/{{ $physicalBooklet->id }}/add-inventory`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: parseInt(quantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('حدث خطأ أثناء إضافة المخزون');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إضافة المخزون');
        });
    }
}

function createInventory() {
    const quantity = prompt('كم قطعة تريد إضافتها كمخزون أولي؟');
    if (quantity && !isNaN(quantity) && parseInt(quantity) >= 0) {
        addInventory();
    }
}

function cloneBooklet() {
    if (confirm('هل تريد نسخ هذه الدوسية لمنصة أخرى؟')) {
        // يمكن إضافة modal لاختيار المنصة المستهدفة
        alert('سيتم إضافة هذه الميزة قريباً');
    }
}

function deleteBooklet() {
    if (confirm('هل أنت متأكد من حذف هذه الدوسية؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/educational/physical-booklets/{{ $physicalBooklet->id }}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush