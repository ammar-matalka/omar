@extends('layouts.app')

@section('title', 'البطاقات التعليمية')

@section('content')
<div class="container" style="margin-top: var(--space-xl);">
    <!-- Header Section -->
    <div style="text-align: center; margin-bottom: var(--space-3xl);">
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: var(--space-md); background: linear-gradient(135deg, var(--primary-500), var(--secondary-500)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            البطاقات التعليمية
        </h1>
        <p style="font-size: 1.125rem; color: var(--gray-600); max-width: 600px; margin: 0 auto;">
            اختر الجيل المناسب لك واطلب الدوسية التي تحتاجها بسهولة
        </p>
    </div>

    <!-- Generations Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" style="margin-bottom: var(--space-3xl);">
        @forelse($generations as $generation)
            <div class="generation-card card" 
                 style="cursor: pointer; transition: all var(--transition-normal); border: 2px solid transparent;"
                 onclick="selectGeneration({{ $generation->id }}, '{{ $generation->display_name }}')">
                <div class="card-body" style="text-align: center; padding: var(--space-xl);">
                    <!-- Generation Icon -->
                    <div style="width: 80px; height: 80px; margin: 0 auto var(--space-lg); background: linear-gradient(135deg, var(--primary-500), var(--secondary-500)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 700;">
                        {{ $generation->year }}
                    </div>
                    
                    <!-- Generation Name -->
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: var(--space-sm); color: var(--gray-900);">
                        {{ $generation->display_name }}
                    </h3>
                    
                    <!-- Description -->
                    @if($generation->description)
                        <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: var(--space-md);">
                            {{ Str::limit($generation->description, 100) }}
                        </p>
                    @endif
                    
                    <!-- Subjects Count -->
                    <div style="display: flex; align-items: center; justify-content: center; gap: var(--space-xs); color: var(--primary-600); font-size: 0.875rem; font-weight: 500;">
                        <i class="fas fa-book"></i>
                        <span>{{ $generation->subjects_count }} مادة متاحة</span>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: var(--space-3xl);">
                <div style="color: var(--gray-400); font-size: 3rem; margin-bottom: var(--space-lg);">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 style="color: var(--gray-600); margin-bottom: var(--space-sm);">لا توجد أجيال متاحة حالياً</h3>
                <p style="color: var(--gray-500);">سيتم إضافة الأجيال قريباً</p>
            </div>
        @endforelse
    </div>

    <!-- Order Form Modal -->
    <div id="orderModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: var(--z-modal); align-items: center; justify-content: center; padding: var(--space-md);">
        <div style="background: white; border-radius: var(--radius-xl); max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto;">
            <!-- Modal Header -->
            <div style="padding: var(--space-xl); border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--gray-900); margin: 0;">
                    طلب دوسية - <span id="selectedGenerationName"></span>
                </h2>
                <button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--gray-400); cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Order Form -->
            <form id="orderForm" action="{{ route('educational-cards.submit-order') }}" method="POST" style="padding: var(--space-xl);">
                @csrf
                <input type="hidden" id="generationId" name="generation_id" value="">

                <!-- Student Name -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user"></i>
                        اسم الطالب *
                    </label>
                    <input type="text" name="student_name" class="form-input" required 
                           placeholder="أدخل اسم الطالب" value="{{ old('student_name') }}">
                </div>

                <!-- Semester Selection -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar"></i>
                        الفصل الدراسي *
                    </label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-sm);">
                        <label style="display: flex; align-items: center; padding: var(--space-md); border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; transition: all var(--transition-fast);" class="semester-option">
                            <input type="radio" name="semester" value="first" style="margin-right: var(--space-sm);" {{ old('semester') == 'first' ? 'checked' : '' }}>
                            <span>الأول</span>
                        </label>
                        <label style="display: flex; align-items: center; padding: var(--space-md); border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; transition: all var(--transition-fast);" class="semester-option">
                            <input type="radio" name="semester" value="second" style="margin-right: var(--space-sm);" {{ old('semester') == 'second' ? 'checked' : '' }}>
                            <span>الثاني</span>
                        </label>
                        <label style="display: flex; align-items: center; padding: var(--space-md); border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; transition: all var(--transition-fast);" class="semester-option">
                            <input type="radio" name="semester" value="both" style="margin-right: var(--space-sm);" {{ old('semester', 'both') == 'both' ? 'checked' : '' }}>
                            <span>كلاهما</span>
                        </label>
                    </div>
                </div>

                <!-- Subjects Selection -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-book"></i>
                        المواد المطلوبة *
                    </label>
                    <div id="subjectsContainer" style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: var(--space-md); background: var(--gray-50); min-height: 120px; display: flex; align-items: center; justify-content: center; color: var(--gray-500);">
                        <div style="text-align: center;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem; margin-bottom: var(--space-sm);"></i>
                            <p>جاري تحميل المواد...</p>
                        </div>
                    </div>
                    <div id="totalPriceContainer" style="margin-top: var(--space-md); padding: var(--space-md); background: var(--primary-50); border-radius: var(--radius-md); border: 1px solid var(--primary-200); display: none;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 600; color: var(--primary-700);">المجموع الفرعي:</span>
                            <span id="subtotalPrice" style="font-size: 1.125rem; font-weight: 700; color: var(--primary-700);">0 JD</span>
                        </div>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-sort-numeric-up"></i>
                        الكمية *
                    </label>
                    <input type="number" name="quantity" class="form-input" min="1" max="10" value="{{ old('quantity', 1) }}" 
                           onchange="updateTotalPrice()" required>
                </div>

                <!-- Total Price Display -->
                <div id="totalPriceDisplay" style="margin-bottom: var(--space-lg); padding: var(--space-lg); background: linear-gradient(135deg, var(--success-50), var(--success-100)); border: 2px solid var(--success-200); border-radius: var(--radius-lg); text-align: center; display: none;">
                    <div style="font-size: 0.875rem; color: var(--success-700); margin-bottom: var(--space-xs);">المجموع النهائي</div>
                    <div id="finalTotalPrice" style="font-size: 2rem; font-weight: 800; color: var(--success-800);">0 JD</div>
                </div>

                <!-- Contact Information -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md); margin-bottom: var(--space-lg);">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">
                            <i class="fas fa-phone"></i>
                            رقم الهاتف
                        </label>
                        <input type="tel" name="phone" class="form-input" placeholder="07xxxxxxxx" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            المدينة/المنطقة
                        </label>
                        <input type="text" name="address" class="form-input" placeholder="مثال: إربد - الحي الشرقي" value="{{ old('address') }}">
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-sticky-note"></i>
                        ملاحظات إضافية
                    </label>
                    <textarea name="notes" class="form-input" rows="3" placeholder="أي ملاحظات أو طلبات خاصة...">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div style="display: flex; gap: var(--space-md); justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" class="btn btn-secondary">
                        إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        إرسال الطلب
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@auth
    <!-- User Orders Section -->
    <div class="container" style="margin-top: var(--space-3xl); margin-bottom: var(--space-3xl);">
        <div style="text-align: center; margin-bottom: var(--space-xl);">
            <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: var(--space-sm);">طلباتي</h2>
            <p style="color: var(--gray-600);">يمكنك متابعة حالة طلباتك من هنا</p>
        </div>
        
        <div style="text-align: center;">
            <a href="{{ route('user.educational-orders.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-list"></i>
                عرض جميع طلباتي
            </a>
        </div>
    </div>
@endauth

<style>
    .generation-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-500);
    }

    .generation-card:hover .card-body > div:first-child {
        background: linear-gradient(135deg, var(--secondary-500), var(--primary-500));
        transform: scale(1.05);
    }

    .semester-option:hover {
        border-color: var(--primary-500);
        background: var(--primary-50);
    }

    .semester-option:has(input:checked) {
        border-color: var(--primary-500);
        background: var(--primary-100);
        color: var(--primary-700);
    }

    .subject-checkbox {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: var(--space-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        margin-bottom: var(--space-sm);
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .subject-checkbox:hover {
        border-color: var(--primary-500);
        background: var(--primary-50);
    }

    .subject-checkbox:has(input:checked) {
        border-color: var(--primary-500);
        background: var(--primary-100);
    }

    .subject-info {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }

    .subject-price {
        font-weight: 600;
        color: var(--success-600);
    }

    #orderModal.show {
        display: flex !important;
    }

    @media (max-width: 768px) {
        #orderModal > div {
            margin: var(--space-md);
            max-height: calc(100vh - 2rem);
        }
        
        .form-group:has([name="semester"]) > div {
            grid-template-columns: 1fr;
        }
        
        .form-group:has([name="phone"]) + .form-group {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    let selectedSubjects = [];
    let subjectsData = [];

    function selectGeneration(generationId, generationName) {
        @guest
            // إذا كان المستخدم غير مسجل دخول
            alert('يرجى تسجيل الدخول أولاً لإرسال الطلبات');
            window.location.href = '{{ route("login") }}';
            return;
        @endguest

        document.getElementById('generationId').value = generationId;
        document.getElementById('selectedGenerationName').textContent = generationName;
        
        // Load subjects for this generation
        loadSubjects(generationId);
        
        // Show modal
        document.getElementById('orderModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('orderModal').classList.remove('show');
        document.body.style.overflow = 'auto';
        
        // Reset form
        document.getElementById('orderForm').reset();
        selectedSubjects = [];
        subjectsData = [];
        updateTotalPrice();
    }

    function loadSubjects(generationId) {
        const container = document.getElementById('subjectsContainer');
        container.innerHTML = `
            <div style="text-align: center;">
                <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem; margin-bottom: var(--space-sm);"></i>
                <p>جاري تحميل المواد...</p>
            </div>
        `;

        fetch(`/educational-cards/generations/${generationId}/subjects`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            subjectsData = data.subjects || [];
            renderSubjects();
        })
        .catch(error => {
            console.error('Error loading subjects:', error);
            container.innerHTML = `
                <div style="text-align: center; color: var(--error-500);">
                    <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; margin-bottom: var(--space-sm);"></i>
                    <p>فشل في تحميل المواد. يرجى المحاولة مرة أخرى.</p>
                </div>
            `;
        });
    }

    function renderSubjects() {
        const container = document.getElementById('subjectsContainer');
        
        if (subjectsData.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; color: var(--gray-500);">
                    <i class="fas fa-book-open" style="font-size: 1.5rem; margin-bottom: var(--space-sm);"></i>
                    <p>لا توجد مواد متاحة لهذا الجيل حالياً</p>
                </div>
            `;
            return;
        }

        let html = '';
        subjectsData.forEach(subject => {
            const isChecked = selectedSubjects.includes(subject.id);
            html += `
                <label class="subject-checkbox">
                    <div class="subject-info">
                        <input type="checkbox" name="subjects[]" value="${subject.id}" 
                               onchange="toggleSubject(${subject.id})" ${isChecked ? 'checked' : ''}>
                        <span style="font-weight: 500;">${subject.name}</span>
                    </div>
                    <span class="subject-price">${subject.formatted_price}</span>
                </label>
            `;
        });

        container.innerHTML = html;
        updateTotalPrice();
    }

    function toggleSubject(subjectId) {
        const index = selectedSubjects.indexOf(subjectId);
        if (index > -1) {
            selectedSubjects.splice(index, 1);
        } else {
            selectedSubjects.push(subjectId);
        }
        updateTotalPrice();
    }

    function updateTotalPrice() {
        const quantity = parseInt(document.querySelector('[name="quantity"]').value) || 1;
        let subtotal = 0;

        // Calculate subtotal
        selectedSubjects.forEach(subjectId => {
            const subject = subjectsData.find(s => s.id === subjectId);
            if (subject) {
                subtotal += parseFloat(subject.price);
            }
        });

        const total = subtotal * quantity;

        // Update subtotal display
        const subtotalContainer = document.getElementById('totalPriceContainer');
        const subtotalPrice = document.getElementById('subtotalPrice');
        
        if (selectedSubjects.length > 0) {
            subtotalContainer.style.display = 'block';
            subtotalPrice.textContent = subtotal.toFixed(2) + ' JD';
        } else {
            subtotalContainer.style.display = 'none';
        }

        // Update final total display
        const totalDisplay = document.getElementById('totalPriceDisplay');
        const finalTotalPrice = document.getElementById('finalTotalPrice');
        
        if (selectedSubjects.length > 0) {
            totalDisplay.style.display = 'block';
            finalTotalPrice.textContent = total.toFixed(2) + ' JD';
        } else {
            totalDisplay.style.display = 'none';
        }
    }

    // Form submission
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        if (selectedSubjects.length === 0) {
            e.preventDefault();
            alert('يرجى اختيار مادة واحدة على الأقل');
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
    });

    // Close modal when clicking outside
    document.getElementById('orderModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Handle escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('orderModal').classList.contains('show')) {
            closeModal();
        }
    });

    // Initialize quantity change listener
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.querySelector('[name="quantity"]');
        if (quantityInput) {
            quantityInput.addEventListener('change', updateTotalPrice);
            quantityInput.addEventListener('input', updateTotalPrice);
        }
    });

    // Handle old form values
    @if(old('generation_id') && old('subjects'))
        document.addEventListener('DOMContentLoaded', function() {
            // If there are old values, show the modal with previous data
            const oldGenerationId = '{{ old("generation_id") }}';
            const oldSubjects = @json(old('subjects', []));
            
            if (oldGenerationId) {
                // Find generation name
                const generationCards = document.querySelectorAll('.generation-card');
                generationCards.forEach(card => {
                    if (card.getAttribute('onclick').includes(oldGenerationId)) {
                        const generationName = card.querySelector('h3').textContent;
                        document.getElementById('generationId').value = oldGenerationId;
                        document.getElementById('selectedGenerationName').textContent = generationName;
                        
                        loadSubjects(oldGenerationId);
                        selectedSubjects = oldSubjects.map(id => parseInt(id));
                        
                        document.getElementById('orderModal').classList.add('show');
                        document.body.style.overflow = 'hidden';
                    }
                });
            }
        });
    @endif
</script>
@endsection