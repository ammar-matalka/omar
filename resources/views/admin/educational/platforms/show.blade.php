@extends('layouts.admin')

@section('title', 'تفاصيل المنصة')
@section('page-title', 'تفاصيل المنصة')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">
    <a href="{{ route('admin.educational.platforms.index') }}" class="breadcrumb-link">المنصات التعليمية</a>
</div>
<div class="breadcrumb-item">
    <i class="fas fa-chevron-right"></i>
</div>
<div class="breadcrumb-item">{{ $platform->name }}</div>
@endsection

@push('styles')
<style>
.platform-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.platform-header::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -20%;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.platform-header::after {
    content: '';
    position: absolute;
    bottom: -20%;
    left: -15%;
    width: 150px;
    height: 150px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
}

.platform-logo {
    width: 100px;
    height: 100px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 1.5rem;
    border: 3px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.status-badge {
    position: absolute;
    top: 20px;
    left: 20px;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.9rem;
    backdrop-filter: blur(10px);
}

.status-active {
    background: rgba(40, 167, 69, 0.9);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.status-inactive {
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.info-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-left: 5px solid #667eea;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.info-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    border-radius: 0 0 0 100px;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.info-card h6 {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
}

.info-card .value {
    font-size: 2rem;
    font-weight: 900;
    color: #495057;
    margin-bottom: 0.5rem;
}

.info-card .label {
    color: #6c757d;
    font-size: 0.9rem;
}

.teacher-section {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 2px solid #dee2e6;
}

.teacher-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.teacher-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.teacher-avatar-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 900;
    color: white;
    border: 4px solid white;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.teacher-details h4 {
    margin: 0 0 0.5rem 0;
    color: #495057;
    font-weight: 700;
}

.teacher-details p {
    margin: 0 0 0.25rem 0;
    color: #6c757d;
}

.packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
}

.package-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 2px solid transparent;
    position: relative;
}

.package-card:hover {
    transform: translateY(-5px);
    border-color: #667eea;
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.package-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
}

.package-type {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.package-digital {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    color: #1565c0;
}

.package-physical {
    background: linear-gradient(135deg, #fff3e0, #ffcc02);
    color: #ef6c00;
}

.package-status {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-left: 10px;
}

.package-active {
    background: #28a745;
    animation: pulse 2s infinite;
}

.package-inactive {
    background: #dc3545;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
}

.package-details {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.package-detail {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.package-detail strong {
    color: #495057;
}

.package-detail span {
    color: #6c757d;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 5rem;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.btn {
    padding: 0.875rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.website-section {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 2px solid #e9ecef;
}

.website-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.website-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.description-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 2px solid #e9ecef;
}

.description-content {
    line-height: 1.8;
    color: #495057;
    font-size: 1.05rem;
}

.statistics-modal .modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
}

.statistics-modal .modal-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
}

.stat-item {
    text-align: center;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.stat-item:hover {
    border-color: #667eea;
    transform: translateY(-2px);
}

.dropdown-menu {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border: none;
    overflow: hidden;
}

.dropdown-item {
    padding: 1rem 1.5rem;
    transition: all 0.3s ease;
    border: none;
    background: none;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #e3f2fd, #f8f9fa);
    transform: translateX(-5px);
    padding-right: 2rem;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Platform Header -->
    <div class="platform-header">
        <div class="status-badge {{ $platform->is_active ? 'status-active' : 'status-inactive' }}">
            {{ $platform->is_active ? 'نشط' : 'غير نشط' }}
        </div>
        
        <div style="position: relative; z-index: 2;">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <div class="platform-logo">
                        {{ strtoupper(substr($platform->name, 0, 2)) }}
                    </div>
                </div>
                
                <div class="col-md-9">
                    <h2 class="mb-3">{{ $platform->name }}</h2>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="fas fa-user-tie"></i>
                                <strong>المعلم:</strong> {{ $platform->teacher->name }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-book"></i>
                                <strong>المادة:</strong> {{ $platform->teacher->subject->name }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="fas fa-graduation-cap"></i>
                                <strong>الجيل:</strong> {{ $platform->teacher->subject->generation->display_name }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-calendar-alt"></i>
                                <strong>تاريخ الإنشاء:</strong> {{ $platform->created_at->format('Y-m-d') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="{{ route('admin.educational.platforms.edit', $platform) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i>
                            تعديل المنصة
                        </a>
                        
                        <button type="button" 
                                class="btn {{ $platform->is_active ? 'btn-secondary' : 'btn-success' }}" 
                                onclick="toggleStatus({{ $platform->id }})">
                            <i class="fas fa-{{ $platform->is_active ? 'pause' : 'play' }}"></i>
                            {{ $platform->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                        </button>
                        
                        <button type="button" class="btn btn-info" onclick="getStatistics({{ $platform->id }})">
                            <i class="fas fa-chart-bar"></i>
                            الإحصائيات
                        </button>
                        
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                                المزيد
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.educational.packages.create') }}?platform_id={{ $platform->id }}">
                                        <i class="fas fa-plus text-success"></i>
                                        إضافة باقة جديدة
                                    </a>
                                </li>
                                <li>
                                    <button class="dropdown-item" onclick="clonePlatform({{ $platform->id }})">
                                        <i class="fas fa-copy text-info"></i>
                                        نسخ المنصة
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item text-danger" onclick="deletePlatform({{ $platform->id }})">
                                        <i class="fas fa-trash"></i>
                                        حذف المنصة
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="info-cards">
        <div class="info-card">
            <h6><i class="fas fa-box"></i> إجمالي الباقات</h6>
            <div class="value">{{ $platform->educationalPackages->count() }}</div>
            <div class="label">باقة تعليمية</div>
        </div>
        
        <div class="info-card" style="border-left-color: #28a745;">
            <h6><i class="fas fa-check-circle"></i> الباقات النشطة</h6>
            <div class="value text-success">{{ $platform->educationalPackages->where('is_active', true)->count() }}</div>
            <div class="label">باقة نشطة</div>
        </div>
        
        <div class="info-card" style="border-left-color: #ffc107;">
            <h6><i class="fas fa-credit-card"></i> البطاقات الرقمية</h6>
            <div class="value text-warning">{{ $platform->educationalPackages->where('productType.is_digital', true)->count() }}</div>
            <div class="label">باقة رقمية</div>
        </div>
        
        <div class="info-card" style="border-left-color: #fd7e14;">
            <h6><i class="fas fa-book-open"></i> الدوسيات الورقية</h6>
            <div class="value" style="color: #fd7e14;">{{ $platform->educationalPackages->where('productType.is_digital', false)->count() }}</div>
            <div class="label">دوسية ورقية</div>
        </div>
    </div>

    <div class="row">
        <!-- Teacher Section -->
        <div class="col-12 mb-4">
            <div class="teacher-section">
                <h4 class="mb-3 text-primary">
                    <i class="fas fa-user-tie"></i>
                    معلومات المعلم
                </h4>
                
                <div class="teacher-info">
                    @if($platform->teacher->image)
                        <img src="{{ $platform->teacher->image_url }}" alt="{{ $platform->teacher->name }}" class="teacher-avatar">
                    @else
                        <div class="teacher-avatar-placeholder">
                            {{ strtoupper(substr($platform->teacher->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <div class="teacher-details">
                        <h4>{{ $platform->teacher->name }}</h4>
                        <p><strong>المادة:</strong> {{ $platform->teacher->subject->name }}</p>
                        <p><strong>الجيل:</strong> {{ $platform->teacher->subject->generation->display_name }}</p>
                        @if($platform->teacher->specialization)
                            <p><strong>التخصص:</strong> {{ $platform->teacher->specialization }}</p>
                        @endif
                        <p><strong>الحالة:</strong> 
                            <span class="badge {{ $platform->teacher->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $platform->teacher->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="ms-auto">
                        <a href="{{ route('admin.educational.teachers.show', $platform->teacher) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            عرض ملف المعلم
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        @if($platform->description)
        <div class="col-12 mb-4">
            <div class="description-section">
                <h4 class="mb-3 text-primary">
                    <i class="fas fa-align-left"></i>
                    وصف المنصة
                </h4>
                <div class="description-content">
                    {{ $platform->description }}
                </div>
            </div>
        </div>
        @endif

        <!-- Website Section -->
        @if($platform->website_url)
        <div class="col-12 mb-4">
            <div class="website-section">
                <h4 class="mb-3 text-primary">
                    <i class="fas fa-globe"></i>
                    الموقع الإلكتروني
                </h4>
                
                <a href="{{ $platform->formatted_website_url }}" target="_blank" class="website-link">
                    <i class="fas fa-external-link-alt"></i>
                    زيارة موقع المنصة
                </a>
                
                <div class="mt-3">
                    <small class="text-muted">الرابط: {{ $platform->website_url }}</small>
                </div>
            </div>
        </div>
        @endif

        <!-- Packages Section -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-box"></i>
                            الباقات التعليمية
                        </h4>
                        
                        <a href="{{ route('admin.educational.packages.create') }}?platform_id={{ $platform->id }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            إضافة باقة جديدة
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($platform->educationalPackages->count() > 0)
                        <div class="packages-grid">
                            @foreach($platform->educationalPackages as $package)
                                <div class="package-card">
                                    <div class="package-header">
                                        <h5 class="mb-0">
                                            <div class="package-status {{ $package->is_active ? 'package-active' : 'package-inactive' }}"></div>
                                            {{ $package->name }}
                                        </h5>
                                        
                                        <div class="package-type {{ $package->is_digital ? 'package-digital' : 'package-physical' }}">
                                            {{ $package->package_type }}
                                        </div>
                                    </div>
                                    
                                    @if($package->description)
                                        <p class="text-muted">{{ Str::limit($package->description, 100) }}</p>
                                    @endif
                                    
                                    <div class="package-details">
                                        @if($package->is_digital)
                                            @if($package->duration_days)
                                                <div class="package-detail">
                                                    <strong>مدة الصلاحية:</strong>
                                                    <span>{{ $package->duration_display }}</span>
                                                </div>
                                            @endif
                                            @if($package->lessons_count)
                                                <div class="package-detail">
                                                    <strong>عدد الدروس:</strong>
                                                    <span>{{ $package->lessons_display }}</span>
                                                </div>
                                            @endif
                                        @else
                                            @if($package->pages_count)
                                                <div class="package-detail">
                                                    <strong>عدد الصفحات:</strong>
                                                    <span>{{ $package->pages_display }}</span>
                                                </div>
                                            @endif
                                            @if($package->weight_grams)
                                                <div class="package-detail">
                                                    <strong>الوزن:</strong>
                                                    <span>{{ $package->weight_display }}</span>
                                                </div>
                                            @endif
                                        @endif
                                        
                                        <div class="package-detail">
                                            <strong>الحالة:</strong>
                                            <span class="badge {{ $package->is_active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $package->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 d-flex gap-2">
                                        <a href="{{ route('admin.educational.packages.show', $package) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                            عرض
                                        </a>
                                        
                                        <a href="{{ route('admin.educational.packages.edit', $package) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                            تعديل
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-box"></i>
                            <h5>لا توجد باقات تعليمية</h5>
                            <p>لم يتم إنشاء أي باقات لهذه المنصة بعد</p>
                            <a href="{{ route('admin.educational.packages.create') }}?platform_id={{ $platform->id }}" 
                               class="btn btn-primary btn-lg">
                                <i class="fas fa-plus"></i>
                                إضافة باقة جديدة
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Modal -->
<div class="modal fade statistics-modal" id="statisticsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إحصائيات المنصة: {{ $platform->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="statisticsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="mt-3">جاري تحميل الإحصائيات...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clone Platform Modal -->
<div class="modal fade" id="cloneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">نسخ المنصة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.educational.platforms.clone', $platform) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="target_teacher_id" class="form-label">المعلم المستهدف</label>
                        <select name="target_teacher_id" id="target_teacher_id" class="form-select" required>
                            <option value="">اختر المعلم</option>
                            @foreach($allTeachers ?? [] as $teacher)
                                @if($teacher->id !== $platform->teacher_id)
                                    <option value="{{ $teacher->id }}">
                                        {{ $teacher->name }} - {{ $teacher->subject->name }} ({{ $teacher->subject->generation->display_name }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <p class="text-muted mt-3">
                        <i class="fas fa-info-circle"></i>
                        سيتم نسخ المنصة مع جميع بياناتها للمعلم المختار
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">نسخ المنصة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <h6 class="text-center">هل أنت متأكد من حذف هذه المنصة؟</h6>
                <p class="text-center text-muted">
                    سيتم حذف المنصة "{{ $platform->name }}" وجميع الباقات المرتبطة بها
                </p>
                <div class="alert alert-danger">
                    <strong>تحذير:</strong> هذا الإجراء لا يمكن التراجع عنه!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form action="{{ route('admin.educational.platforms.destroy', $platform) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        حذف نهائياً
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(platformId) {
    fetch(`/admin/educational/platforms/${platformId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', 'حدث خطأ أثناء تحديث الحالة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'حدث خطأ أثناء تحديث الحالة');
    });
}

function getStatistics(platformId) {
    const modal = new bootstrap.Modal(document.getElementById('statisticsModal'));
    modal.show();
    
    fetch(`/admin/educational/platforms/${platformId}/statistics`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayStatistics(data.data);
            } else {
                document.getElementById('statisticsContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        حدث خطأ في تحميل الإحصائيات
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('statisticsContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    حدث خطأ في الاتصال بالخادم
                </div>
            `;
        });
}

function displayStatistics(stats) {
    const content = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="stat-item">
                    <h4 class="text-primary">${stats.packages_count || 0}</h4>
                    <p class="mb-0">إجمالي الباقات</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="stat-item">
                    <h4 class="text-success">${stats.active_packages_count || 0}</h4>
                    <p class="mb-0">الباقات النشطة</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="stat-item">
                    <h4 class="text-info">${stats.digital_packages_count || 0}</h4>
                    <p class="mb-0">البطاقات الرقمية</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="stat-item">
                    <h4 class="text-warning">${stats.physical_packages_count || 0}</h4>
                    <p class="mb-0">الدوسيات الورقية</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="stat-item">
                    <h4 class="text-purple">${stats.cards_count || 0}</h4>
                    <p class="mb-0">البطاقات المُصدرة</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="stat-item">
                    <h4 class="text-success">${stats.orders_count || 0}</h4>
                    <p class="mb-0">إجمالي الطلبات</p>
                </div>
            </div>
            <div class="col-12">
                <div class="stat-item">
                    <h4 class="text-primary">${stats.total_revenue || 0} دينار</h4>
                    <p class="mb-0">إجمالي الإيرادات</p>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('statisticsContent').innerHTML = content;
}

function clonePlatform(platformId) {
    const modal = new bootstrap.Modal(document.getElementById('cloneModal'));
    modal.show();
}

function deletePlatform(platformId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${icon}"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.container-fluid').firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Animation on page load
document.addEventListener('DOMContentLoaded', function() {
    // Animate info cards
    const cards = document.querySelectorAll('.info-card, .package-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });

    // Enhance dropdowns
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // Add hover effects to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endpush@extends('layouts.admin')

@section('title', 'تفاصيل المنصة')
@section('page-title', 'تفاصيل المنصة')

@section('breadcrumb')
<div class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
</div>
<div class="brea