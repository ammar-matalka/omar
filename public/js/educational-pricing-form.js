// Educational Pricing Form JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // عناصر النموذج
    const generationSelect = document.getElementById('generation_id');
    const subjectSelect = document.getElementById('subject_id');
    const teacherSelect = document.getElementById('teacher_id');
    const platformSelect = document.getElementById('platform_id');
    const packageSelect = document.getElementById('package_id');
    const regionSelect = document.getElementById('region_id');
    const priceInput = document.getElementById('price');
    const priceDisplay = document.getElementById('price_display');

    // URLs للـ API
    const apiUrls = {
        subjects: '/educational/api/subjects',
        teachers: '/educational/api/teachers',
        platforms: '/educational/api/platforms',
        packages: '/educational/api/packages',
        calculatePrice: '/educational/api/calculate-price'
    };

    // تفعيل/تعطيل العناصر
    function toggleSelect(selectElement, enabled) {
        selectElement.disabled = !enabled;
        if (!enabled) {
            selectElement.innerHTML = '<option value="">اختر...</option>';
        }
    }

    // إعادة تعيين العناصر التابعة
    function resetDependentSelects(fromIndex) {
        const selects = [subjectSelect, teacherSelect, platformSelect, packageSelect];
        for (let i = fromIndex; i < selects.length; i++) {
            toggleSelect(selects[i], false);
        }
        
        // إخفاء منطقة السعر
        if (priceDisplay) {
            priceDisplay.style.display = 'none';
        }
    }

    // تحميل البيانات من API
    async function loadData(url, params = {}) {
        try {
            const queryString = new URLSearchParams(params).toString();
            const fullUrl = queryString ? `${url}?${queryString}` : url;
            
            const response = await fetch(fullUrl);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error loading data:', error);
            return [];
        }
    }

    // ملء dropdown بالبيانات
    function populateSelect(selectElement, data, valueField = 'id', textField = 'name') {
        selectElement.innerHTML = '<option value="">اختر...</option>';
        
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueField];
            option.textContent = item[textField];
            selectElement.appendChild(option);
        });
        
        toggleSelect(selectElement, data.length > 0);
    }

    // تحديث السعر
    async function updatePrice() {
        if (!generationSelect.value || !subjectSelect.value || !teacherSelect.value || 
            !platformSelect.value || !packageSelect.value) {
            return;
        }

        const params = {
            generation_id: generationSelect.value,
            subject_id: subjectSelect.value,
            teacher_id: teacherSelect.value,
            platform_id: platformSelect.value,
            package_id: packageSelect.value,
            region_id: regionSelect.value || null
        };

        try {
            const response = await fetch(apiUrls.calculatePrice, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(params)
            });

            if (response.ok) {
                const data = await response.json();
                if (data.price !== undefined) {
                    priceInput.value = data.price;
                    if (priceDisplay) {
                        priceDisplay.textContent = `السعر المقترح: ${data.price} دينار`;
                        priceDisplay.style.display = 'block';
                    }
                }
            }
        } catch (error) {
            console.error('Error calculating price:', error);
        }
    }

    // معالجات الأحداث

    // 1. تغيير الجيل
    generationSelect.addEventListener('change', async function() {
        resetDependentSelects(0);
        
        if (this.value) {
            const subjects = await loadData(apiUrls.subjects, { generation_id: this.value });
            populateSelect(subjectSelect, subjects);
        }
    });

    // 2. تغيير المادة
    subjectSelect.addEventListener('change', async function() {
        resetDependentSelects(1);
        
        if (this.value) {
            const teachers = await loadData(apiUrls.teachers, { subject_id: this.value });
            populateSelect(teacherSelect, teachers);
        }
    });

    // 3. تغيير المعلم
    teacherSelect.addEventListener('change', async function() {
        resetDependentSelects(2);
        
        if (this.value) {
            const platforms = await loadData(apiUrls.platforms, { teacher_id: this.value });
            populateSelect(platformSelect, platforms);
        }
    });

    // 4. تغيير المنصة
    platformSelect.addEventListener('change', async function() {
        resetDependentSelects(3);
        
        if (this.value) {
            const packages = await loadData(apiUrls.packages, { platform_id: this.value });
            populateSelect(packageSelect, packages);
        }
    });

    // 5. تغيير الباقة أو المنطقة
    packageSelect.addEventListener('change', updatePrice);
    regionSelect.addEventListener('change', updatePrice);

    // التحقق من نوع الباقة وإظهار/إخفاء منطقة الشحن
    packageSelect.addEventListener('change', async function() {
        if (this.value) {
            try {
                const packages = await loadData(apiUrls.packages, { id: this.value });
                const selectedPackage = packages.find(pkg => pkg.id == this.value);
                
                if (selectedPackage) {
                    const regionContainer = regionSelect.closest('.form-group') || regionSelect.closest('.mb-3');
                    if (regionContainer) {
                        if (selectedPackage.requires_shipping) {
                            regionContainer.style.display = 'block';
                            regionSelect.required = true;
                        } else {
                            regionContainer.style.display = 'none';
                            regionSelect.required = false;
                            regionSelect.value = '';
                        }
                    }
                }
            } catch (error) {
                console.error('Error checking package type:', error);
            }
        }
    });

    // تحسين تجربة المستخدم
    function addLoadingState(selectElement) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'جاري التحميل...';
        selectElement.innerHTML = '';
        selectElement.appendChild(option);
        selectElement.disabled = true;
    }

    // إضافة مؤشرات التحميل
    const originalLoadData = loadData;
    window.loadData = async function(url, params = {}) {
        // يمكن إضافة مؤشر تحميل هنا
        return await originalLoadData(url, params);
    };

    // التحقق من صحة النموذج قبل الإرسال
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = [generationSelect, subjectSelect, teacherSelect, platformSelect, packageSelect];
            
            for (let field of requiredFields) {
                if (!field.value) {
                    e.preventDefault();
                    alert('يرجى ملء جميع الحقول المطلوبة');
                    field.focus();
                    return false;
                }
            }

            // التحقق من منطقة الشحن للباقات الورقية
            const selectedPackageOption = packageSelect.options[packageSelect.selectedIndex];
            if (selectedPackageOption && selectedPackageOption.dataset.requiresShipping === 'true') {
                if (!regionSelect.value) {
                    e.preventDefault();
                    alert('يرجى اختيار منطقة الشحن للباقات الورقية');
                    regionSelect.focus();
                    return false;
                }
            }
        });
    }

    // إعداد أولي للنموذج
    resetDependentSelects(0);
    
    console.log('Educational Pricing Form JavaScript loaded successfully');
});