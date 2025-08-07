@extends('layouts.admin')

@section('title', 'الدوسيات الورقية')

@push('styles')
<style>
.physical-booklets-page {
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
    background: var(--stat-color, #059669);
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
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
}

.booklets-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.booklets-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.booklet-item {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.booklet-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.booklet-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #059669, #047857);
}

.booklet-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.booklet-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.booklet-platform {
    color: #6b7280;
    font-size: 0.85rem;
    margin-bottom: 1rem;
}

.booklet-features {
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
    color: #059669;
    width: 16px;
}

.booklet-status {
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

.stock-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.stock-in-stock {
    background: #dcfce7;
    color: #166534;
}

.stock-low-stock {
    background: #fef3c7;
    color: #92400e;
}

.stock-out-of-stock {
    background: #fef2f2;
    color: #991b1b;
}

.stock-no-inventory {
    background: #f3f4f6;
    color: #6b7280;
}

.booklet-actions {
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

.btn-info {
    background: #3b82f6;
    color: white;
}

.btn-info:hover {
    background: #2563eb;
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

.quick-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .physical-booklets-page {
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
    
    .booklets-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="physical-booklets-page">
    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <h1>
                        <div class="header-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        الدوسيات الورقية
                    </h1>
                    <p class="header-subtitle">إدارة وتتبع الدوسيات الورقية والمخزون</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.educational.physical-booklets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        إنشاء دوسية ورقية
                    </a>
                    <a href="{{ route('admin.educational.physical-booklets.export') }}" class="btn btn-secondary">
                        <i class="fas fa-download"></i>
                        تصدير
                    </a>
                    <a href="{{ route('admin.educational.physical-booklets.low-stock-report') }}" class="btn btn-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        تقرير المخزون المنخفض
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card" style="--stat-color: #059669;">
                    <div class="stat-number">{{ $physicalBooklets->total() }}</div>
                    <div class="stat-label">إجمالي الدوسيات</div>
                </div>
                <div class="stat-card" style="--stat-color: #10b981;">
                    <div class="stat-number">{{ $physicalBooklets->where('is_active', true)->count() }}</div>
                    <div class="stat-label">الدوسيات النشطة</div>
                </div>
                <div class="stat-card" style="--stat-color: #f59e0b;">
                    <div class="stat-number">{{ $physicalBooklets->where('is_active', false)->count() }}</div>
                    <div class="stat-label">غير نشطة</div>
                </div>
                <div class="stat-card" style="--stat-color: #ef4444;">
                    <div class="stat-number">{{ $physicalBooklets->filter(function($booklet) { return $booklet->educationalInventory->isEmpty() || $booklet->educationalInventory->first()->actual_available <= 0; })->count() }}</div>
                    <div class="stat-label">نفدت الكمية</div>
                </div>
                <div class="stat-card" style="--stat-color: #8b5cf6;">
                    <div class="stat-number">{{ $physicalBooklets->sum(function($booklet) { return $booklet->educationalInventory->sum('quantity_available'); }) }}</div>
                    <div class="stat-label">إجمالي المخزون</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('admin.educational.physical-booklets.index') }}">
                <div class="filters-grid">
                    <div class="form-group">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" name="search" id="search" class="form-input" 
                               value="{{ request('search') }}" 
                               placeholder="ابحث في أسماء الدوسيات...">
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
                        <label for="subject_id" class="form-label">المادة</label>
                        <select name="subject_id" id="subject_id" class="form-select">
                            <option value="">جميع المواد</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="teacher_id" class="form-label">المعلم</label>
                        <select name="teacher_id" id="teacher_id" class="form-select">
                            <option value="">جميع المعلمين</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
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
                        <label for="stock_status" class="form-label">حالة المخزون</label>
                        <select name="stock_status" id="stock_status" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>متوفر</option>
                            <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>كمية قليلة</option>
                            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>نفدت الكمية</option>
                            <option value="no_inventory" {{ request('stock_status') == 'no_inventory' ? 'selected' : '' }}>لا يوجد مخزون</option>
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

        <!-- Physical Booklets Grid -->
        <div class="booklets-section">
            @if($physicalBooklets->count() > 0)
                <div class="booklets-grid">
                    @foreach($physicalBooklets as $booklet)
                        @php
                            $inventory = $booklet->educationalInventory->first();
                            $stockStatus = 'no-inventory';
                            $stockText = 'لا يوجد مخزون';
                            
                            if ($inventory) {
                                $available = $inventory->actual_available;
                                if ($available <= 0) {
                                    $stockStatus = 'out-of-stock';
                                    $stockText = 'نفدت الكمية';
                                } elseif ($available <= 10) {
                                    $stockStatus = 'low-stock';
                                    $stockText = "كمية قليلة ({$available})";
                                } else {
                                    $stockStatus = 'in-stock';
                                    $stockText = "متوفر ({$available})";
                                }
                            }
                        @endphp
                        
                        <div class="booklet-item">
                            <div class="booklet-header">
                                <div style="flex: 1;">
                                    <h3 class="booklet-title">{{ $booklet->name }}</h3>
                                    <div class="booklet-platform">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        {{ $booklet->platform->teacher->subject->generation->display_name }} - 
                                        {{ $booklet->platform->teacher->subject->name }} - 
                                        {{ $booklet->platform->teacher->name }}
                                    </div>
                                </div>
                            </div>

                            <div class="booklet-features">
                                <div class="feature-item">
                                    <i class="fas fa-file-alt feature-icon"></i>
                                    {{ $booklet->pages_count ?: 'غير محدد' }} صفحة
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-weight feature-icon"></i>
                                    {{ $booklet->weight_grams ? ($booklet->weight_grams . ' جرام') : 'غير محدد' }}
                                </div>
                                @if($booklet->description)
                                    <div class="feature-item" style="grid-column: 1 / -1;">
                                        <i class="fas fa-info-circle feature-icon"></i>
                                        {{ Str::limit($booklet->description, 100) }}
                                    </div>
                                @endif
                            </div>

                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <div class="booklet-status {{ $booklet->is_active ? 'status-active' : 'status-inactive' }}">
                                    <i class="fas fa-{{ $booklet->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                    {{ $booklet->is_active ? 'نشطة' : 'غير نشطة' }}
                                </div>
                                
                                <div class="stock-status stock-{{ $stockStatus }}">
                                    <i class="fas fa-{{ $stockStatus == 'in-stock' ? 'check-circle' : ($stockStatus == 'low-stock' ? 'exclamation-triangle' : 'times-circle') }}"></i>
                                    {{ $stockText }}
                                </div>
                            </div>

                            <div class="booklet-actions">
                                <a href="{{ route('admin.educational.physical-booklets.show', $booklet) }}" class="btn btn-secondary">
                                    <i class="fas fa-eye"></i>
                                    عرض
                                </a>
                                <a href="{{ route('admin.educational.physical-booklets.edit', $booklet) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                    تعديل
                                </a>
                                <button type="button" class="btn {{ $booklet->is_active ? 'btn-warning' : 'btn-success' }}" 
                                        onclick="toggleStatus({{ $booklet->id }}, {{ $booklet->is_active ? 'false' : 'true' }})">
                                    <i class="fas fa-toggle-{{ $booklet->is_active ? 'off' : 'on' }}"></i>
                                    {{ $booklet->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}
                                </button>
                            </div>

                            <!-- Quick Actions for Inventory -->
                            @if($inventory)
                                <div class="quick-actions">
                                    <button type="button" class="btn btn-info" onclick="addInventory({{ $booklet->id }})">
                                        <i class="fas fa-plus"></i>
                                        إضافة مخزون
                                    </button>
                                </div>
                            @else
                                <div class="quick-actions">
                                    <button type="button" class="btn btn-primary" onclick="createInventory({{ $booklet->id }})">
                                        <i class="fas fa-plus"></i>
                                        إنشاء مخزون
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $physicalBooklets->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>لا توجد دوسيات ورقية</h3>
                    <p>ابدأ بإنشاء أول دوسية ورقية</p>
                    <a href="{{ route('admin.educational.physical-booklets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        إنشاء دوسية ورقية
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Inventory Modal -->
<div id="addInventoryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 15px; max-width: 400px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">إضافة مخزون</h3>
        <form id="addInventoryForm">
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="inventory_quantity" class="form-label">الكمية</label>
                <input type="number" id="inventory_quantity" name="quantity" class="form-input" min="1" max="100000" required>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="closeAddInventoryModal()" class="btn btn-secondary">إلغاء</button>
                <button type="submit" class="btn btn-primary">إضافة</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentBookletId = null;

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

function addInventory(bookletId) {
    currentBookletId = bookletId;
    document.getElementById('addInventoryModal').style.display = 'flex';
    document.getElementById('inventory_quantity').focus();
}

function createInventory(bookletId) {
    const quantity = prompt('كم قطعة تريد إضافتها للمخزون؟');
    if (quantity && !isNaN(quantity) && parseInt(quantity) > 0) {
        addInventoryRequest(bookletId, parseInt(quantity));
    }
}

function closeAddInventoryModal() {
    document.getElementById('addInventoryModal').style.display = 'none';
    document.getElementById('addInventoryForm').reset();
    currentBookletId = null;
}

function addInventoryRequest(bookletId, quantity) {
    fetch(`/admin/educational/physical-booklets/${bookletId}/add-inventory`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: quantity
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

document.getElementById('addInventoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const quantity = parseInt(document.getElementById('inventory_quantity').value);
    if (quantity > 0 && currentBookletId) {
        addInventoryRequest(currentBookletId, quantity);
        closeAddInventoryModal();
    }
});

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('#generation_id, #subject_id, #teacher_id, #platform_id, #stock_status, #is_active');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
    
    // Close modal when clicking outside
    document.getElementById('addInventoryModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddInventoryModal();
        }
    });
});
</script>
@endpush