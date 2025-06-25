@extends('layouts.admin')

@section('title', 'إدارة المخزون التعليمي')

@push('styles')
<style>
.inventory-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    background: white;
}

.inventory-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stock-status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
}

.stock-available { background: #dcfce7; color: #166534; }
.stock-low { background: #fef3c7; color: #d97706; }
.stock-out { background: #fecaca; color: #dc2626; }
.stock-reserved { background: #dbeafe; color: #1d4ed8; }

.quantity-display {
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
}

.available-qty { background: #dcfce7; color: #166534; }
.reserved-qty { background: #dbeafe; color: #1d4ed8; }
.actual-qty { background: #f0fdf4; color: #15803d; }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    text-align: center;
}

.filters-section {
    background: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    margin-bottom: 1.5rem;
}

.bulk-actions {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: none;
}

.inventory-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.product-chain {
    font-size: 0.8rem;
    color: #6b7280;
    line-height: 1.2;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.stock-progress {
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
    margin-top: 0.5rem;
}

.stock-progress-bar {
    height: 100%;
    transition: width 0.3s ease;
}

.progress-available { background: #10b981; }
.progress-reserved { background: #3b82f6; }

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .action-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .quantity-display {
        font-size: 1.2rem;
        padding: 0.75rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">إدارة المخزون التعليمي</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">النظام التعليمي</a></li>
                    <li class="breadcrumb-item active">المخزون</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.educational.inventory.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus me-2"></i>إضافة مخزون جديد
            </a>
            <a href="{{ route('admin.educational.inventory.low-stock-report') }}" class="btn btn-outline-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>تقرير المخزون المنخفض
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">إجمالي العناصر</h6>
                    <h3 class="mb-0 text-primary">{{ number_format($stats['total_items']) }}</h3>
                </div>
                <div class="text-primary">
                    <i class="fas fa-cubes fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">متوفر</h6>
                    <h3 class="mb-0 text-success">{{ number_format($stats['available_items']) }}</h3>
                </div>
                <div class="text-success">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">مخزون منخفض</h6>
                    <h3 class="mb-0 text-warning">{{ number_format($stats['low_stock_items']) }}</h3>
                </div>
                <div class="text-warning">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">نفدت الكمية</h6>
                    <h3 class="mb-0 text-danger">{{ number_format($stats['out_of_stock_items']) }}</h3>
                </div>
                <div class="text-danger">
                    <i class="fas fa-times-circle fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">إجمالي الكمية</h6>
                    <h3 class="mb-0 text-info">{{ number_format($stats['total_quantity']) }}</h3>
                </div>
                <div class="text-info">
                    <i class="fas fa-warehouse fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">محجوز</h6>
                    <h3 class="mb-0 text-secondary">{{ number_format($stats['total_reserved']) }}</h3>
                </div>
                <div class="text-secondary">
                    <i class="fas fa-lock fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.educational.inventory.index') }}">
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">الجيل</label>
                    <select name="generation_id" class="form-select">
                        <option value="">جميع الأجيال</option>
                        @foreach($generations as $generation)
                            <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                                {{ $generation->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">المادة</label>
                    <select name="subject_id" class="form-select">
                        <option value="">جميع المواد</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">المعلم</label>
                    <select name="teacher_id" class="form-select">
                        <option value="">جميع المعلمين</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">الباقة</label>
                    <select name="package_id" class="form-select">
                        <option value="">جميع الباقات</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">حالة المخزون</label>
                    <select name="stock_status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>متوفر</option>
                        <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>مخزون منخفض</option>
                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>نفدت الكمية</option>
                        <option value="reserved" {{ request('stock_status') == 'reserved' ? 'selected' : '' }}>محجوز</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">الترتيب</label>
                    <select name="sort" class="form-select">
                        <option value="quantity_available" {{ request('sort') == 'quantity_available' ? 'selected' : '' }}>الكمية المتاحة</option>
                        <option value="quantity_reserved" {{ request('sort') == 'quantity_reserved' ? 'selected' : '' }}>الكمية المحجوزة</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>تاريخ الإنشاء</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <label class="form-label">نطاق الكمية</label>
                    <div class="input-group">
                        <input type="number" name="quantity_min" class="form-control" placeholder="من" 
                               value="{{ request('quantity_min') }}" min="0">
                        <span class="input-group-text">إلى</span>
                        <input type="number" name="quantity_max" class="form-control" placeholder="إلى" 
                               value="{{ request('quantity_max') }}" min="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">الاتجاه</label>
                    <select name="order" class="form-select">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i>فلترة
                        </button>
                        <a href="{{ route('admin.educational.inventory.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>مسح
                        </a>
                        <a href="{{ route('admin.educational.inventory.export', request()->query()) }}" class="btn btn-outline-success">
                            <i class="fas fa-file-excel me-2"></i>تصدير
                        </a>
                        <button type="button" class="btn btn-outline-warning" onclick="toggleBulkActions()">
                            <i class="fas fa-edit me-2"></i>تحديث جماعي
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActionsPanel">
        <form method="POST" action="{{ route('admin.educational.inventory.bulk-update') }}" id="bulkForm">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">العملية</label>
                    <select name="action" id="bulkAction" class="form-select" required>
                        <option value="">اختر العملية...</option>
                        <option value="add_stock">إضافة مخزون</option>
                        <option value="set_stock">تحديد المخزون</option>
                        <option value="clear_reserved">مسح المحجوز</option>
                    </select>
                </div>
                <div class="col-md-2" id="quantityField" style="display: none;">
                    <label class="form-label">الكمية</label>
                    <input type="number" name="quantity" class="form-control" min="0" max="100000">
                </div>
                <div class="col-md-5">
                    <button type="submit" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>تطبيق على المحدد
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="toggleBulkActions()">
                        إلغاء
                    </button>
                    <div class="mt-2">
                        <small class="text-muted">
                            <span id="selectedCount">0</span> عنصر محدد
                        </small>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Inventory List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة المخزون ({{ $inventory->total() }} عنصر)</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll">
                <label class="form-check-label" for="selectAll">
                    تحديد الكل
                </label>
            </div>
        </div>

        <div class="card-body">
            @if($inventory->count() > 0)
                <div class="row g-3">
                    @foreach($inventory as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="inventory-card p-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input inventory-checkbox" type="checkbox" 
                                               value="{{ $item->id }}" name="inventory_ids[]">
                                    </div>
                                    
                                    <div class="inventory-info flex-grow-1 mx-3">
                                        <h6 class="mb-1">{{ $item->package->name }}</h6>
                                        <div class="product-chain">
                                            <div>{{ $item->generation->display_name }} - {{ $item->subject->name }}</div>
                                            <div>{{ $item->teacher->name }} - {{ $item->platform->name }}</div>
                                        </div>
                                        
                                        <span class="stock-status-badge stock-{{ str_replace(' ', '-', strtolower($item->stock_status_class)) }} mt-2">
                                            {{ $item->stock_status }}
                                        </span>
                                    </div>
                                    
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('admin.educational.inventory.show', $item) }}">
                                                <i class="fas fa-eye me-2"></i>عرض التفاصيل
                                            </a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin.educational.inventory.edit', $item) }}">
                                                <i class="fas fa-edit me-2"></i>تعديل
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><button class="dropdown-item text-success" onclick="addStock({{ $item->id }})">
                                                <i class="fas fa-plus me-2"></i>إضافة مخزون
                                            </button></li>
                                            <li><button class="dropdown-item text-info" onclick="adjustReserved({{ $item->id }})">
                                                <i class="fas fa-lock me-2"></i>تعديل المحجوز
                                            </button></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><button class="dropdown-item text-danger" onclick="deleteInventory({{ $item->id }})">
                                                <i class="fas fa-trash me-2"></i>حذف
                                            </button></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Quantity Display -->
                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <div class="quantity-display available-qty">
                                            {{ number_format($item->quantity_available) }}
                                        </div>
                                        <small class="text-muted">متاح</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="quantity-display reserved-qty">
                                            {{ number_format($item->quantity_reserved) }}
                                        </div>
                                        <small class="text-muted">محجوز</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="quantity-display actual-qty">
                                            {{ number_format($item->actual_available) }}
                                        </div>
                                        <small class="text-muted">فعلي</small>
                                    </div>
                                </div>

                                <!-- Stock Progress Bar -->
                                @if($item->quantity_available > 0)
                                    <div class="stock-progress">
                                        @php
                                            $availablePercent = ($item->actual_available / $item->quantity_available) * 100;
                                            $reservedPercent = ($item->quantity_reserved / $item->quantity_available) * 100;
                                        @endphp
                                        <div class="stock-progress-bar progress-available" style="width: {{ $availablePercent }}%"></div>
                                        <div class="stock-progress-bar progress-reserved" style="width: {{ $reservedPercent }}%; margin-top: -8px; opacity: 0.7;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-success">{{ number_format($availablePercent, 1) }}% متاح</small>
                                        <small class="text-primary">{{ number_format($reservedPercent, 1) }}% محجوز</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $inventory->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-warehouse fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا يوجد مخزون</h5>
                    <p class="text-muted">لم يتم العثور على أي مخزون يطابق المعايير المحددة</p>
                    <a href="{{ route('admin.educational.inventory.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>إضافة مخزون جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Action Modals -->
@include('admin.educational.inventory.modals.add-stock')
@include('admin.educational.inventory.modals.adjust-reserved')
@endsection

@push('scripts')
<script>
// Bulk actions toggle
function toggleBulkActions() {
    const panel = document.getElementById('bulkActionsPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    updateSelectedCount();
}

// Bulk action type change
document.getElementById('bulkAction').addEventListener('change', function() {
    const action = this.value;
    const showQuantity = ['add_stock', 'set_stock'].includes(action);
    document.getElementById('quantityField').style.display = showQuantity ? 'block' : 'none';
});

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.inventory-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
});

// Individual checkbox change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('inventory-checkbox')) {
        updateSelectedCount();
        
        // Update select all checkbox
        const allCheckboxes = document.querySelectorAll('.inventory-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.inventory-checkbox:checked');
        const selectAllCheckbox = document.getElementById('selectAll');
        
        if (checkedCheckboxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedCheckboxes.length === allCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }
});

// Update selected count
function updateSelectedCount() {
    const checkedBoxes = document.querySelectorAll('.inventory-checkbox:checked');
    document.getElementById('selectedCount').textContent = checkedBoxes.length;
}

// Bulk form submission
document.getElementById('bulkForm').addEventListener('submit', function(e) {
    const checkedBoxes = document.querySelectorAll('.inventory-checkbox:checked');
    if (checkedBoxes.length === 0) {
        e.preventDefault();
        alert('يرجى تحديد عنصر واحد على الأقل');
        return;
    }
    
    const action = document.getElementById('bulkAction').value;
    if (!action) {
        e.preventDefault();
        alert('يرجى اختيار نوع العملية');
        return;
    }
    
    // Add selected IDs to form
    checkedBoxes.forEach(checkbox => {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'inventory_ids[]';
        hiddenInput.value = checkbox.value;
        this.appendChild(hiddenInput);
    });
    
    if (!confirm(`هل أنت متأكد من تطبيق هذه العملية على ${checkedBoxes.length} عنصر؟`)) {
        e.preventDefault();
    }
});

// Quick action functions
function addStock(inventoryId) {
    // This would open a modal for adding stock
    const quantity = prompt('كم قطعة تريد إضافتها للمخزون؟');
    if (quantity && !isNaN(quantity) && parseInt(quantity) > 0) {
        fetch(`/admin/educational/inventory/${inventoryId}/add-stock`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity: parseInt(quantity) })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('error', 'حدث خطأ أثناء إضافة المخزون');
            }
        })
        .catch(error => {
            showAlert('error', 'حدث خطأ في الاتصال');
        });
    }
}

function adjustReserved(inventoryId) {
    const quantity = prompt('كم قطعة تريد حجزها؟ (0 لإلغاء الحجز)');
    if (quantity !== null && !isNaN(quantity) && parseInt(quantity) >= 0) {
        fetch(`/admin/educational/inventory/${inventoryId}/adjust-reserved`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity: parseInt(quantity) })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('error', 'حدث خطأ أثناء تعديل الكمية المحجوزة');
            }
        })
        .catch(error => {
            showAlert('error', 'حدث خطأ في الاتصال');
        });
    }
}

function deleteInventory(inventoryId) {
    if(confirm('هل أنت متأكد من حذف هذا المخزون؟ لا يمكن التراجع عن هذا الإجراء.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/educational/inventory/${inventoryId}`;
        form.innerHTML = `
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Show alert messages
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endpush