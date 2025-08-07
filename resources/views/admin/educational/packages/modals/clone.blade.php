<!-- Clone Package Modal -->
<div class="modal fade" id="clonePackageModal" tabindex="-1" aria-labelledby="clonePackageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clonePackageModalLabel">
                    <i class="fas fa-copy me-2"></i>نسخ الباقة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="clonePackageForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>ملاحظة:</strong> سيتم نسخ جميع تفاصيل الباقة الحالية إلى المنصة المختارة.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Package Info -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">الباقة المصدر</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>اسم الباقة:</strong>
                                        <span id="source-package-name"></span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>نوع المنتج:</strong>
                                        <span id="source-product-type"></span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>المنصة الحالية:</strong>
                                        <span id="source-platform"></span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>المعلم:</strong>
                                        <span id="source-teacher"></span>
                                    </div>
                                    <div class="mb-0">
                                        <strong>المادة:</strong>
                                        <span id="source-subject"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Target Platform Selection -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">المنصة المستهدفة</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="target_platform_id" class="form-label">اختر المنصة المستهدفة <span class="text-danger">*</span></label>
                                        <select name="target_platform_id" id="target_platform_id" class="form-select" required>
                                            <option value="">-- اختر المنصة --</option>
                                            @foreach($platforms ?? [] as $platform)
                                                <option value="{{ $platform->id }}" 
                                                        data-teacher="{{ $platform->teacher->name }}"
                                                        data-subject="{{ $platform->teacher->subject->name }}"
                                                        data-generation="{{ $platform->teacher->subject->generation->display_name }}">
                                                    {{ $platform->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            يرجى اختيار المنصة المستهدفة
                                        </div>
                                    </div>

                                    <!-- Target Platform Details -->
                                    <div id="target-platform-details" class="d-none">
                                        <div class="alert alert-success">
                                            <div class="mb-1">
                                                <strong>المعلم:</strong>
                                                <span id="target-teacher"></span>
                                            </div>
                                            <div class="mb-1">
                                                <strong>المادة:</strong>
                                                <span id="target-subject"></span>
                                            </div>
                                            <div class="mb-0">
                                                <strong>الجيل:</strong>
                                                <span id="target-generation"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Package Details -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">تفاصيل الباقة التي ستُنسخ</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div id="package-digital-details" class="d-none">
                                                <h6 class="text-primary mb-2">تفاصيل البطاقة الرقمية:</h6>
                                                <div class="mb-2">
                                                    <strong>مدة الصلاحية:</strong>
                                                    <span id="source-duration"></span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>عدد الدروس:</strong>
                                                    <span id="source-lessons"></span>
                                                </div>
                                            </div>
                                            
                                            <div id="package-physical-details" class="d-none">
                                                <h6 class="text-warning mb-2">تفاصيل الدوسية الورقية:</h6>
                                                <div class="mb-2">
                                                    <strong>عدد الصفحات:</strong>
                                                    <span id="source-pages"></span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>الوزن:</strong>
                                                    <span id="source-weight"></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <strong>الوصف:</strong>
                                                <div id="source-description" class="text-muted"></div>
                                            </div>
                                            <div class="mb-2">
                                                <strong>الحالة:</strong>
                                                <span id="source-status"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Warning Messages -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>تحذير:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>تأكد من اختيار المنصة الصحيحة قبل المتابعة</li>
                                    <li>في حالة وجود باقة بنفس الاسم في المنصة المستهدفة، ستظهر رسالة خطأ</li>
                                    <li>لن يتم نسخ التسعير أو المخزون - يجب إعدادهما منفصلاً</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary" id="confirmCloneBtn">
                        <i class="fas fa-copy me-2"></i>نسخ الباقة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Clone Package Modal Functions
function openCloneModal(packageId, packageData) {
    // Set form action
    document.getElementById('clonePackageForm').action = `/admin/educational/packages/${packageId}/clone`;
    
    // Fill source package data
    document.getElementById('source-package-name').textContent = packageData.name;
    document.getElementById('source-product-type').textContent = packageData.product_type;
    document.getElementById('source-platform').textContent = packageData.platform;
    document.getElementById('source-teacher').textContent = packageData.teacher;
    document.getElementById('source-subject').textContent = packageData.subject;
    
    // Fill package details based on type
    if (packageData.is_digital) {
        document.getElementById('package-digital-details').classList.remove('d-none');
        document.getElementById('package-physical-details').classList.add('d-none');
        
        document.getElementById('source-duration').textContent = packageData.duration || 'غير محدد';
        document.getElementById('source-lessons').textContent = packageData.lessons || 'غير محدد';
    } else {
        document.getElementById('package-physical-details').classList.remove('d-none');
        document.getElementById('package-digital-details').classList.add('d-none');
        
        document.getElementById('source-pages').textContent = packageData.pages || 'غير محدد';
        document.getElementById('source-weight').textContent = packageData.weight || 'غير محدد';
    }
    
    document.getElementById('source-description').textContent = packageData.description || 'لا يوجد وصف';
    document.getElementById('source-status').innerHTML = packageData.is_active ? 
        '<span class="badge bg-success">نشط</span>' : 
        '<span class="badge bg-secondary">غير نشط</span>';
    
    // Reset target platform selection
    document.getElementById('target_platform_id').value = '';
    document.getElementById('target-platform-details').classList.add('d-none');
    
    // Remove current platform from options
    const currentPlatformId = packageData.platform_id;
    const platformSelect = document.getElementById('target_platform_id');
    Array.from(platformSelect.options).forEach(option => {
        if (option.value == currentPlatformId) {
            option.style.display = 'none';
        } else {
            option.style.display = 'block';
        }
    });
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('clonePackageModal'));
    modal.show();
}

// Handle target platform selection
document.getElementById('target_platform_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const detailsDiv = document.getElementById('target-platform-details');
    
    if (this.value) {
        document.getElementById('target-teacher').textContent = selectedOption.dataset.teacher;
        document.getElementById('target-subject').textContent = selectedOption.dataset.subject;
        document.getElementById('target-generation').textContent = selectedOption.dataset.generation;
        
        detailsDiv.classList.remove('d-none');
        this.classList.remove('is-invalid');
    } else {
        detailsDiv.classList.add('d-none');
    }
});

// Form validation
document.getElementById('clonePackageForm').addEventListener('submit', function(e) {
    const targetPlatformId = document.getElementById('target_platform_id').value;
    
    if (!targetPlatformId) {
        e.preventDefault();
        document.getElementById('target_platform_id').classList.add('is-invalid');
        return false;
    }
    
    // Confirm before submitting
    if (!confirm('هل أنت متأكد من نسخ هذه الباقة إلى المنصة المختارة؟')) {
        e.preventDefault();
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('confirmCloneBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري النسخ...';
});
</script>