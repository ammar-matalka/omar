@extends('layouts.admin')

@section('title', 'إدارة الكوبونات')

@push('styles')
<style>
    .coupons-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .active-stat { color: #10b981; }
    .used-stat { color: #3b82f6; }
    .expired-stat { color: #ef4444; }
    .total-stat { color: #8b5cf6; }
    
    .filters-section {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #374151;
    }
    
    .form-input, .form-select {
        padding: 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }
    
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    .btn-info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    
    .coupons-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table th {
        background: #f9fafb;
        padding: 1rem;
        text-align: right;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    
    .table tbody tr:hover {
        background: #f9fafb;
    }
    
    .coupon-code {
        font-family: monospace;
        background: #f3f4f6;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: bold;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-used {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .status-expired {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .actions-dropdown {
        position: relative;
        display: inline-block;
    }
    
    .dropdown-menu {
        position: absolute;
        left: 0;
        top: 100%;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        min-width: 150px;
        z-index: 1000;
        display: none;
    }
    
    .dropdown-menu.show {
        display: block;
    }
    
    .dropdown-item {
        display: block;
        padding: 0.75rem 1rem;
        color: #374151;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }
    
    .dropdown-item:hover {
        background: #f3f4f6;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert-success {
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }
    
    .alert-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .coupons-header {
            padding: 1rem;
            text-align: center;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="coupons-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">
                    <i class="fas fa-ticket-alt me-2"></i>
                    إدارة الكوبونات
                </h1>
                <p class="mb-0 opacity-75">إدارة كوبونات الخصم لعملائك</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    كوبون جديد
                </a>
                <a href="{{ route('admin.coupons.generate') }}" class="btn btn-info">
                    <i class="fas fa-magic"></i>
                    توليد متعدد
                </a>
            </div>
        </div>
    </div>

    <!-- إظهار الرسائل -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- إحصائيات -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number total-stat">{{ $stats['total'] }}</div>
            <div class="stat-label">إجمالي الكوبونات</div>
        </div>
        <div class="stat-card">
            <div class="stat-number active-stat">{{ $stats['active'] }}</div>
            <div class="stat-label">الكوبونات النشطة</div>
        </div>
        <div class="stat-card">
            <div class="stat-number used-stat">{{ $stats['used'] }}</div>
            <div class="stat-label">الكوبونات المستخدمة</div>
        </div>
        <div class="stat-card">
            <div class="stat-number expired-stat">{{ $stats['expired'] }}</div>
            <div class="stat-label">الكوبونات المنتهية</div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.coupons.index') }}">
            <div class="filters-grid">
                <div class="form-group">
                    <label class="form-label">البحث</label>
                    <input type="text" name="search" class="form-input" 
                           placeholder="ابحث بالكود أو الوصف أو المستخدم..." 
                           value="{{ request('search') }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>مستخدم</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">ترتيب حسب</label>
                    <select name="sort" class="form-select">
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>تاريخ الإنشاء</option>
                        <option value="valid_until" {{ request('sort') == 'valid_until' ? 'selected' : '' }}>تاريخ الانتهاء</option>
                        <option value="amount" {{ request('sort') == 'amount' ? 'selected' : '' }}>القيمة</option>
                        <option value="code" {{ request('sort') == 'code' ? 'selected' : '' }}>الكود</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        بحث
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- جدول الكوبونات -->
    <div class="coupons-table">
        @if($coupons->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الكود</th>
                            <th>المبلغ</th>
                            <th>النوع</th>
                            <th>المستخدم</th>
                            <th>الحالة</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                            <tr>
                                <td>
                                    <span class="coupon-code">{{ $coupon->code }}</span>
                                </td>
                                <td>
                                    @if($coupon->type == 'percentage')
                                        {{ $coupon->amount }}%
                                    @else
                                        ${{ number_format($coupon->amount, 2) }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $coupon->type == 'percentage' ? 'info' : 'success' }}">
                                        {{ $coupon->type == 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت' }}
                                    </span>
                                </td>
                                <td>
                                    @if($coupon->user)
                                        <div>
                                            <strong>{{ $coupon->user->name }}</strong><br>
                                            <small class="text-muted">{{ $coupon->user->email }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->is_used)
                                        <span class="status-badge status-used">مستخدم</span>
                                    @elseif($coupon->valid_until < now())
                                        <span class="status-badge status-expired">منتهي</span>
                                    @else
                                        <span class="status-badge status-active">نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        {{ $coupon->valid_until->format('Y-m-d') }}<br>
                                        <small class="text-muted">{{ $coupon->valid_until->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="actions-dropdown">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" 
                                                onclick="toggleDropdown({{ $coupon->id }})">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu" id="dropdown-{{ $coupon->id }}">
                                            <a href="{{ route('admin.coupons.show', $coupon) }}" class="dropdown-item">
                                                <i class="fas fa-eye me-2"></i>عرض
                                            </a>
                                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="dropdown-item">
                                                <i class="fas fa-edit me-2"></i>تعديل
                                            </a>
                                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الكوبون؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i>حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $coupons->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-ticket-alt"></i>
                <h3>لا توجد كوبونات</h3>
                <p>لم يتم العثور على أي كوبونات تطابق معايير البحث.</p>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    إنشاء كوبون جديد
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(`dropdown-${id}`);
        const allDropdowns = document.querySelectorAll('.dropdown-menu');
        
        // إغلاق جميع القوائم الأخرى
        allDropdowns.forEach(menu => {
            if (menu.id !== `dropdown-${id}`) {
                menu.classList.remove('show');
            }
        });
        
        // تبديل القائمة الحالية
        dropdown.classList.toggle('show');
    }
    
    // إغلاق القوائم عند النقر خارجها
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.actions-dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
    
    // تحديث الصفحة تلقائياً كل 30 ثانية لتحديث الحالات
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            // تحديث فقط إذا كانت الصفحة مرئية
            const currentUrl = new URL(window.location.href);
            fetch(currentUrl.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if (response.ok) {
                    // يمكن إضافة تحديث للإحصائيات هنا
                }
            }).catch(error => {
                console.log('تعذر تحديث البيانات');
            });
        }
    }, 30000);
</script>
@endpush