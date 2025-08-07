<?php
// app/Http/Controllers/Admin/DigitalCardsController.php (المُصحح)

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalPackage;
use App\Models\EducationalProductType;
use App\Models\Platform;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Generation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DigitalCardsController extends Controller
{
    /**
     * Digital product type instance
     */
    private $digitalProductType;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->digitalProductType = EducationalProductType::getDigitalCardType();
            
            if (!$this->digitalProductType) {
                throw new \Exception('نوع المنتج الرقمي غير متوفر في النظام');
            }
            
            return $next($request);
        });
    }

    /**
     * عرض قائمة البطاقات الرقمية فقط
     */
    public function index(Request $request)
    {
        // التأكد من جلب البطاقات الرقمية فقط باستخدام product_type_id
        $query = EducationalPackage::with(['productType', 'platform.teacher.subject.generation'])
                                 ->where('product_type_id', $this->digitalProductType->id)
                                 ->whereHas('productType', function($q) {
                                     $q->where('is_digital', true);
                                 });

        // فلترة حسب الجيل
        if ($request->filled('generation_id')) {
            $query->whereHas('platform.teacher.subject', function($q) use ($request) {
                $q->where('generation_id', $request->generation_id);
            });
        }

        // فلترة حسب المادة
        if ($request->filled('subject_id')) {
            $query->whereHas('platform.teacher', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        // فلترة حسب المعلم
        if ($request->filled('teacher_id')) {
            $query->whereHas('platform', function($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }

        // فلترة حسب المنصة
        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->platform_id);
        }

        // فلترة حسب الحالة
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // فلترة حسب مدة الصلاحية
        if ($request->filled('duration_filter')) {
            switch ($request->duration_filter) {
                case 'month':
                    $query->where('duration_days', '<=', 31);
                    break;
                case 'quarter':
                    $query->whereBetween('duration_days', [32, 90]);
                    break;
                case 'semester':
                    $query->whereBetween('duration_days', [91, 180]);
                    break;
                case 'year':
                    $query->where('duration_days', '>', 180);
                    break;
                case 'unlimited':
                    $query->whereNull('duration_days');
                    break;
            }
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $digitalCards = $query->orderBy('name')->paginate(15);

        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();
        $platforms = Platform::active()->with('teacher')->orderBy('name')->get();

        return view('admin.educational.digital-cards.index', compact(
            'digitalCards', 'generations', 'subjects', 'teachers', 'platforms'
        ));
    }

    /**
     * عرض نموذج إنشاء بطاقة رقمية جديدة
     */
    public function create()
    {
        $platforms = Platform::active()->with('teacher.subject.generation')->orderBy('name')->get();
        
        return view('admin.educational.digital-cards.create', compact('platforms'));
    }

    /**
     * حفظ بطاقة رقمية جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('educational_packages')->where(function ($query) use ($request) {
                    return $query->where('platform_id', $request->platform_id)
                                ->where('product_type_id', $this->digitalProductType->id);
                })
            ],
            'description' => 'nullable|string|max:1000',
            'duration_days' => 'nullable|integer|min:1|max:3650',
            'lessons_count' => 'nullable|integer|min:1|max:1000',
            'is_active' => 'boolean'
        ], [
            'platform_id.required' => 'المنصة مطلوبة',
            'platform_id.exists' => 'المنصة المختارة غير صحيحة',
            'name.required' => 'اسم البطاقة مطلوب',
            'name.max' => 'اسم البطاقة لا يجب أن يتجاوز 255 حرف',
            'name.unique' => 'يوجد بالفعل بطاقة رقمية بهذا الاسم في نفس المنصة',
            'description.max' => 'وصف البطاقة لا يجب أن يتجاوز 1000 حرف',
            'duration_days.integer' => 'مدة الصلاحية يجب أن تكون رقم صحيح',
            'duration_days.min' => 'مدة الصلاحية يجب أن تكون على الأقل يوم واحد',
            'duration_days.max' => 'مدة الصلاحية لا يمكن أن تتجاوز 10 سنوات',
            'lessons_count.integer' => 'عدد الدروس يجب أن يكون رقم صحيح',
            'lessons_count.min' => 'عدد الدروس يجب أن يكون على الأقل درس واحد',
            'lessons_count.max' => 'عدد الدروس لا يمكن أن يتجاوز 1000 درس'
        ]);

        // إعداد البيانات للبطاقة الرقمية فقط
        $digitalCardData = [
            'product_type_id' => $this->digitalProductType->id,
            'platform_id' => $validated['platform_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration_days' => $validated['duration_days'],
            'lessons_count' => $validated['lessons_count'],
            // إزالة خصائص الدوسيات الورقية بشكل صريح
            'pages_count' => null,
            'weight_grams' => null,
            'is_active' => $request->boolean('is_active', true)
        ];

        // التحقق من صحة البيانات ضد نوع المنتج
        $validation = $this->digitalProductType->validatePackageProperties($digitalCardData);
        if (!$validation['valid']) {
            return back()->withErrors(['data_integrity' => implode(', ', $validation['errors'])])
                        ->withInput();
        }

        $digitalCard = EducationalPackage::create($digitalCardData);

        return redirect()->route('admin.educational.digital-cards.index')
                        ->with('success', 'تم إنشاء البطاقة الرقمية بنجاح');
    }

    /**
     * عرض تفاصيل بطاقة رقمية محددة
     */
    public function show(EducationalPackage $digitalCard)
    {
        // التحقق مرة أخرى من أن هذه بطاقة رقمية
        $this->ensureIsDigitalCard($digitalCard);

        $digitalCard->load(['productType', 'platform.teacher.subject.generation', 'educationalPricing', 'educationalCards']);
        
        // إحصائيات خاصة بالبطاقات الرقمية
        $stats = [
            'total_cards_generated' => $digitalCard->educationalCards()->count(),
            'active_cards' => $digitalCard->educationalCards()->where('status', 'active')->count(),
            'used_cards' => $digitalCard->educationalCards()->where('status', 'used')->count(),
            'expired_cards' => $digitalCard->educationalCards()->where('status', 'expired')->count(),
            'total_revenue' => $digitalCard->orderItems()->sum('price'),
            'average_price' => $digitalCard->educationalPricing()->avg('price'),
            'usage_rate' => $digitalCard->educationalCards()->count() > 0 
                          ? round(($digitalCard->educationalCards()->where('status', 'used')->count() / $digitalCard->educationalCards()->count()) * 100, 2)
                          : 0
        ];
        
        return view('admin.educational.digital-cards.show', compact('digitalCard', 'stats'));
    }

    /**
     * عرض نموذج تعديل بطاقة رقمية
     */
    public function edit(EducationalPackage $digitalCard)
    {
        $this->ensureIsDigitalCard($digitalCard);

        $platforms = Platform::active()->with('teacher.subject.generation')->orderBy('name')->get();
        
        return view('admin.educational.digital-cards.edit', compact('digitalCard', 'platforms'));
    }

    /**
     * تحديث بطاقة رقمية محددة
     */
    public function update(Request $request, EducationalPackage $digitalCard)
    {
        $this->ensureIsDigitalCard($digitalCard);

        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('educational_packages')->where(function ($query) use ($request, $digitalCard) {
                    return $query->where('platform_id', $request->platform_id)
                                ->where('product_type_id', $this->digitalProductType->id)
                                ->where('id', '!=', $digitalCard->id);
                })
            ],
            'description' => 'nullable|string|max:1000',
            'duration_days' => 'nullable|integer|min:1|max:3650',
            'lessons_count' => 'nullable|integer|min:1|max:1000',
            'is_active' => 'boolean'
        ], [
            'platform_id.required' => 'المنصة مطلوبة',
            'name.required' => 'اسم البطاقة مطلوب',
            'name.unique' => 'يوجد بالفعل بطاقة رقمية بهذا الاسم في نفس المنصة',
            'name.max' => 'اسم البطاقة لا يجب أن يتجاوز 255 حرف',
            'description.max' => 'وصف البطاقة لا يجب أن يتجاوز 1000 حرف'
        ]);

        // تحديث البيانات مع التأكد من عدم تغيير حقول الدوسيات
        $updateData = [
            'platform_id' => $validated['platform_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration_days' => $validated['duration_days'],
            'lessons_count' => $validated['lessons_count'],
            'is_active' => $request->boolean('is_active'),
            // التأكد من عدم تغيير حقول الدوسيات
            'pages_count' => null,
            'weight_grams' => null
        ];

        // التحقق من صحة البيانات
        $validation = $this->digitalProductType->validatePackageProperties($updateData);
        if (!$validation['valid']) {
            return back()->withErrors(['data_integrity' => implode(', ', $validation['errors'])])
                        ->withInput();
        }

        $digitalCard->update($updateData);

        return redirect()->route('admin.educational.digital-cards.index')
                        ->with('success', 'تم تحديث البطاقة الرقمية بنجاح');
    }

    /**
     * حذف بطاقة رقمية محددة
     */
    public function destroy(EducationalPackage $digitalCard)
    {
        $this->ensureIsDigitalCard($digitalCard);

        // التحقق من وجود تسعير مرتبط
        if ($digitalCard->educationalPricing()->exists()) {
            return redirect()->route('admin.educational.digital-cards.index')
                            ->with('error', 'لا يمكن حذف البطاقة لوجود تسعير مرتبط بها');
        }

        // التحقق من وجود بطاقات مُنتجة
        if ($digitalCard->educationalCards()->exists()) {
            return redirect()->route('admin.educational.digital-cards.index')
                            ->with('error', 'لا يمكن حذف البطاقة لوجود بطاقات مُنتجة منها');
        }

        $digitalCard->delete();

        return redirect()->route('admin.educational.digital-cards.index')
                        ->with('success', 'تم حذف البطاقة الرقمية بنجاح');
    }

    /**
     * تبديل حالة التفعيل
     */
    public function toggleStatus(EducationalPackage $digitalCard)
    {
        $this->ensureIsDigitalCard($digitalCard);

        $digitalCard->update(['is_active' => !$digitalCard->is_active]);

        $status = $digitalCard->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$status} البطاقة الرقمية بنجاح",
                'is_active' => $digitalCard->is_active
            ]);
        }

        return redirect()->back()->with('success', "{$status} البطاقة الرقمية بنجاح");
    }

    /**
     * نسخ بطاقة لمنصة أخرى
     */
    public function clone(Request $request, EducationalPackage $digitalCard)
    {
        $this->ensureIsDigitalCard($digitalCard);

        $request->validate([
            'target_platform_id' => 'required|exists:platforms,id|different:' . $digitalCard->platform_id
        ], [
            'target_platform_id.required' => 'المنصة المستهدفة مطلوبة',
            'target_platform_id.exists' => 'المنصة المستهدفة غير موجودة',
            'target_platform_id.different' => 'يجب اختيار منصة مختلفة'
        ]);

        // التحقق من عدم وجود بطاقة رقمية بنفس الاسم في المنصة المستهدفة
        $exists = EducationalPackage::where('platform_id', $request->target_platform_id)
                                  ->where('name', $digitalCard->name)
                                  ->where('product_type_id', $this->digitalProductType->id)
                                  ->exists();

        if ($exists) {
            return back()->withErrors(['target_platform_id' => 'هذه البطاقة الرقمية موجودة بالفعل في المنصة المستهدفة']);
        }

        // إنشاء نسخة من البطاقة الرقمية
        $newCard = EducationalPackage::create([
            'product_type_id' => $this->digitalProductType->id,
            'platform_id' => $request->target_platform_id,
            'name' => $digitalCard->name,
            'description' => $digitalCard->description,
            'duration_days' => $digitalCard->duration_days,
            'lessons_count' => $digitalCard->lessons_count,
            'pages_count' => null, // مخصص للدوسيات فقط
            'weight_grams' => null, // مخصص للدوسيات فقط
            'is_active' => $digitalCard->is_active
        ]);

        return redirect()->route('admin.educational.digital-cards.show', $newCard)
                        ->with('success', 'تم نسخ البطاقة الرقمية بنجاح');
    }

    /**
     * إحصائيات البطاقات الرقمية
     */
    public function statistics()
    {
        $stats = [
            'total_packages' => EducationalPackage::where('product_type_id', $this->digitalProductType->id)->count(),
            'active_packages' => EducationalPackage::where('product_type_id', $this->digitalProductType->id)
                                                 ->where('is_active', true)->count(),
            'total_cards_generated' => \App\Models\EducationalCard::count(),
            'active_cards' => \App\Models\EducationalCard::where('status', 'active')->count(),
            'used_cards' => \App\Models\EducationalCard::where('status', 'used')->count(),
            'expired_cards' => \App\Models\EducationalCard::where('status', 'expired')->count(),
            'total_revenue' => DB::table('order_items')
                ->join('educational_packages', 'order_items.package_id', '=', 'educational_packages.id')
                ->where('educational_packages.product_type_id', $this->digitalProductType->id)
                ->sum('order_items.price'),
            'average_duration' => EducationalPackage::where('product_type_id', $this->digitalProductType->id)
                                                  ->whereNotNull('duration_days')->avg('duration_days'),
            'unlimited_duration_count' => EducationalPackage::where('product_type_id', $this->digitalProductType->id)
                                                           ->whereNull('duration_days')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * تصدير البطاقات الرقمية
     */
    public function export(Request $request)
    {
        $query = EducationalPackage::with(['productType', 'platform.teacher.subject.generation'])
                                 ->where('product_type_id', $this->digitalProductType->id);

        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->platform_id);
        }

        $digitalCards = $query->get();

        $filename = 'digital_cards_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($digitalCards) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للتعامل مع UTF-8 في Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // عناوين CSV
            fputcsv($file, [
                'المعرف', 'اسم البطاقة', 'المنصة', 'المعلم', 'المادة', 'الجيل',
                'مدة الصلاحية (أيام)', 'عدد الدروس', 'الحالة', 'تاريخ الإنشاء'
            ]);
            
            // بيانات CSV
            foreach ($digitalCards as $card) {
                fputcsv($file, [
                    $card->id,
                    $card->name,
                    $card->platform->name,
                    $card->platform->teacher->name,
                    $card->platform->teacher->subject->name,
                    $card->platform->teacher->subject->generation->display_name,
                    $card->duration_days ?: 'غير محدد',
                    $card->lessons_count ?: 'غير محدد',
                    $card->is_active ? 'نشطة' : 'غير نشطة',
                    $card->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Ensure the package is a digital card
     */
    private function ensureIsDigitalCard(EducationalPackage $package)
    {
        if ($package->product_type_id !== $this->digitalProductType->id) {
            abort(404, 'العنصر المطلوب ليس بطاقة رقمية');
        }

        if (!$package->productType || !$package->productType->is_digital) {
            abort(404, 'العنصر المطلوب ليس بطاقة رقمية');
        }

        // Additional validation for data integrity
        if (!$package->isValidDigitalCard()) {
            throw new \Exception('البطاقة تحتوي على بيانات غير صحيحة للمنتج الرقمي');
        }
    }

    /**
     * Get digital cards query with all necessary filters
     */
    private function getDigitalCardsQuery()
    {
        return EducationalPackage::where('product_type_id', $this->digitalProductType->id)
                                ->whereHas('productType', function($q) {
                                    $q->where('is_digital', true);
                                });
    }
}