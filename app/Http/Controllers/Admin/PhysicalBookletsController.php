<?php
// app/Http/Controllers/Admin/PhysicalBookletsController.php (المُصحح)

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

class PhysicalBookletsController extends Controller
{
    /**
     * Physical product type instance
     */
    private $physicalProductType;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->physicalProductType = EducationalProductType::getPhysicalBookletType();
            
            if (!$this->physicalProductType) {
                throw new \Exception('نوع المنتج الورقي غير متوفر في النظام');
            }
            
            return $next($request);
        });
    }

    /**
     * عرض قائمة الدوسيات الورقية
     */
    public function index(Request $request)
    {
        $query = EducationalPackage::with(['productType', 'platform.teacher.subject.generation', 'educationalInventory'])
                                 ->where('product_type_id', $this->physicalProductType->id)
                                 ->whereHas('productType', function($q) {
                                     $q->where('is_digital', false);
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

        // فلترة حسب حالة المخزون
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->whereHas('educationalInventory', function($q) {
                        $q->where('quantity_available', '>', 0);
                    });
                    break;
                case 'low_stock':
                    $query->whereHas('educationalInventory', function($q) {
                        $q->where('quantity_available', '>', 0)
                          ->where('quantity_available', '<=', 10);
                    });
                    break;
                case 'out_of_stock':
                    $query->whereHas('educationalInventory', function($q) {
                        $q->where('quantity_available', '<=', 0);
                    });
                    break;
                case 'no_inventory':
                    $query->whereDoesntHave('educationalInventory');
                    break;
            }
        }

        // فلترة حسب نطاق الصفحات
        if ($request->filled('pages_min')) {
            $query->where('pages_count', '>=', $request->pages_min);
        }
        if ($request->filled('pages_max')) {
            $query->where('pages_count', '<=', $request->pages_max);
        }

        // فلترة حسب نطاق الوزن
        if ($request->filled('weight_min')) {
            $query->where('weight_grams', '>=', $request->weight_min);
        }
        if ($request->filled('weight_max')) {
            $query->where('weight_grams', '<=', $request->weight_max);
        }

        // فلترة حسب الحالة
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $physicalBooklets = $query->orderBy('name')->paginate(15);

        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();
        $platforms = Platform::active()->with('teacher')->orderBy('name')->get();

        return view('admin.educational.physical-booklets.index', compact(
            'physicalBooklets', 'generations', 'subjects', 'teachers', 'platforms'
        ));
    }

    /**
     * عرض نموذج إنشاء دوسية ورقية جديدة
     */
    public function create()
    {
        $platforms = Platform::active()->with('teacher.subject.generation')->orderBy('name')->get();
        
        return view('admin.educational.physical-booklets.create', compact('platforms'));
    }

    /**
     * حفظ دوسية ورقية جديدة
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
                                ->where('product_type_id', $this->physicalProductType->id);
                })
            ],
            'description' => 'nullable|string|max:1000',
            'pages_count' => 'required|integer|min:1|max:1000',
            'weight_grams' => 'nullable|integer|min:1|max:10000',
            'is_active' => 'boolean',
            'create_inventory' => 'boolean',
            'initial_quantity' => 'required_if:create_inventory,true|nullable|integer|min:0|max:100000'
        ], [
            'platform_id.required' => 'المنصة مطلوبة',
            'platform_id.exists' => 'المنصة المختارة غير صحيحة',
            'name.required' => 'اسم الدوسية مطلوب',
            'name.max' => 'اسم الدوسية لا يجب أن يتجاوز 255 حرف',
            'name.unique' => 'يوجد بالفعل دوسية ورقية بهذا الاسم في نفس المنصة',
            'description.max' => 'وصف الدوسية لا يجب أن يتجاوز 1000 حرف',
            'pages_count.required' => 'عدد الصفحات مطلوب',
            'pages_count.integer' => 'عدد الصفحات يجب أن يكون رقم صحيح',
            'pages_count.min' => 'عدد الصفحات يجب أن يكون على الأقل صفحة واحدة',
            'pages_count.max' => 'عدد الصفحات لا يمكن أن يتجاوز 1000 صفحة',
            'weight_grams.integer' => 'الوزن يجب أن يكون رقم صحيح',
            'weight_grams.min' => 'الوزن يجب أن يكون على الأقل جرام واحد',
            'weight_grams.max' => 'الوزن لا يمكن أن يتجاوز 10 كيلو',
            'initial_quantity.required_if' => 'الكمية الأولية مطلوبة عند إنشاء مخزون',
            'initial_quantity.integer' => 'الكمية الأولية يجب أن تكون رقم صحيح'
        ]);

        // إعداد البيانات للدوسية الورقية فقط
        $bookletData = [
            'product_type_id' => $this->physicalProductType->id,
            'platform_id' => $validated['platform_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'pages_count' => $validated['pages_count'],
            'weight_grams' => $validated['weight_grams'],
            // إزالة خصائص البطاقات الرقمية بشكل صريح
            'duration_days' => null,
            'lessons_count' => null,
            'is_active' => $request->boolean('is_active', true)
        ];

        // التحقق من صحة البيانات ضد نوع المنتج
        $validation = $this->physicalProductType->validatePackageProperties($bookletData);
        if (!$validation['valid']) {
            return back()->withErrors(['data_integrity' => implode(', ', $validation['errors'])])
                        ->withInput();
        }

        // إنشاء الدوسية
        $booklet = EducationalPackage::create($bookletData);

        // إنشاء مخزون أولي إذا طُلب ذلك
        if ($request->boolean('create_inventory') && $request->filled('initial_quantity')) {
            $platform = $booklet->platform;
            $teacher = $platform->teacher;
            $subject = $teacher->subject;
            $generation = $subject->generation;

            \App\Models\EducationalInventory::create([
                'generation_id' => $generation->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'platform_id' => $platform->id,
                'package_id' => $booklet->id,
                'quantity_available' => $request->initial_quantity,
                'quantity_reserved' => 0
            ]);
        }

        return redirect()->route('admin.educational.physical-booklets.index')
                        ->with('success', 'تم إنشاء الدوسية الورقية بنجاح');
    }

    /**
     * عرض تفاصيل دوسية ورقية محددة
     */
    public function show(EducationalPackage $physicalBooklet)
    {
        $this->ensureIsPhysicalBooklet($physicalBooklet);

        $physicalBooklet->load(['productType', 'platform.teacher.subject.generation', 'educationalPricing', 'educationalInventory', 'orderItems']);
        
        // إحصائيات خاصة بالدوسيات الورقية
        $inventory = $physicalBooklet->educationalInventory()->first();
        $stats = [
            'total_pages' => $physicalBooklet->pages_count,
            'weight_kg' => $physicalBooklet->weight_grams ? round($physicalBooklet->weight_grams / 1000, 2) : 0,
            'inventory_available' => $inventory ? $inventory->quantity_available : 0,
            'inventory_reserved' => $inventory ? $inventory->quantity_reserved : 0,
            'inventory_actual' => $inventory ? $inventory->actual_available : 0,
            'total_orders' => $physicalBooklet->orderItems()->count(),
            'total_quantity_sold' => $physicalBooklet->orderItems()->sum('quantity'),
            'total_revenue' => $physicalBooklet->orderItems()->sum('price'),
            'average_price' => $physicalBooklet->educationalPricing()->avg('price'),
            'stock_status' => $inventory ? $inventory->stock_status : 'لا يوجد مخزون'
        ];
        
        return view('admin.educational.physical-booklets.show', compact('physicalBooklet', 'stats', 'inventory'));
    }

    /**
     * عرض نموذج تعديل دوسية ورقية
     */
    public function edit(EducationalPackage $physicalBooklet)
    {
        $this->ensureIsPhysicalBooklet($physicalBooklet);

        $platforms = Platform::active()->with('teacher.subject.generation')->orderBy('name')->get();
        $inventory = $physicalBooklet->educationalInventory()->first();
        
        return view('admin.educational.physical-booklets.edit', compact('physicalBooklet', 'platforms', 'inventory'));
    }

    /**
     * تحديث دوسية ورقية محددة
     */
    public function update(Request $request, EducationalPackage $physicalBooklet)
    {
        $this->ensureIsPhysicalBooklet($physicalBooklet);

        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('educational_packages')->where(function ($query) use ($request, $physicalBooklet) {
                    return $query->where('platform_id', $request->platform_id)
                                ->where('product_type_id', $this->physicalProductType->id)
                                ->where('id', '!=', $physicalBooklet->id);
                })
            ],
            'description' => 'nullable|string|max:1000',
            'pages_count' => 'required|integer|min:1|max:1000',
            'weight_grams' => 'nullable|integer|min:1|max:10000',
            'is_active' => 'boolean'
        ], [
            'platform_id.required' => 'المنصة مطلوبة',
            'name.required' => 'اسم الدوسية مطلوب',
            'name.unique' => 'يوجد بالفعل دوسية ورقية بهذا الاسم في نفس المنصة',
            'pages_count.required' => 'عدد الصفحات مطلوب'
        ]);

        // إعداد البيانات للتحديث
        $updateData = [
            'platform_id' => $validated['platform_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'pages_count' => $validated['pages_count'],
            'weight_grams' => $validated['weight_grams'],
            'is_active' => $request->boolean('is_active'),
            // التأكد من إزالة الحقول الخاصة بالبطاقات الرقمية
            'duration_days' => null,
            'lessons_count' => null
        ];

        // التحقق من صحة البيانات
        $validation = $this->physicalProductType->validatePackageProperties($updateData);
        if (!$validation['valid']) {
            return back()->withErrors(['data_integrity' => implode(', ', $validation['errors'])])
                        ->withInput();
        }

        $physicalBooklet->update($updateData);

        return redirect()->route('admin.educational.physical-booklets.index')
                        ->with('success', 'تم تحديث الدوسية الورقية بنجاح');
    }

    /**
     * حذف دوسية ورقية محددة
     */
    public function destroy(EducationalPackage $physicalBooklet)
    {
        $this->ensureIsPhysicalBooklet($physicalBooklet);

        // التحقق من وجود تسعير مرتبط
        if ($physicalBooklet->educationalPricing()->exists()) {
            return redirect()->route('admin.educational.physical-booklets.index')
                            ->with('error', 'لا يمكن حذف الدوسية لوجود تسعير مرتبط بها');
        }

        // التحقق من وجود مخزون
        if ($physicalBooklet->educationalInventory()->exists()) {
            return redirect()->route('admin.educational.physical-booklets.index')
                            ->with('error', 'لا يمكن حذف الدوسية لوجود مخزون مرتبط بها');
        }

        // التحقق من وجود طلبات مرتبطة
        if ($physicalBooklet->orderItems()->exists()) {
            return redirect()->route('admin.educational.physical-booklets.index')
                            ->with('error', 'لا يمكن حذف الدوسية لوجود طلبات مرتبطة بها');
        }

        $physicalBooklet->delete();

        return redirect()->route('admin.educational.physical-booklets.index')
                        ->with('success', 'تم حذف الدوسية الورقية بنجاح');
    }

    /**
     * تبديل حالة التفعيل
     */
    public function toggleStatus(EducationalPackage $physicalBooklet)
    {
        $this->ensureIsPhysicalBooklet($physicalBooklet);

        $physicalBooklet->update(['is_active' => !$physicalBooklet->is_active]);

        $status = $physicalBooklet->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$status} الدوسية الورقية بنجاح",
                'is_active' => $physicalBooklet->is_active
            ]);
        }

        return redirect()->back()->with('success', "{$status} الدوسية الورقية بنجاح");
    }

    /**
     * نسخ دوسية لمنصة أخرى
     */
    public function clone(Request $request, EducationalPackage $physicalBooklet)
    {
        $this->ensureIsPhysicalBooklet($physicalBooklet);

        $request->validate([
            'target_platform_id' => 'required|exists:platforms,id|different:' . $physicalBooklet->platform_id,
            'copy_inventory' => 'boolean',
            'inventory_quantity' => 'required_if:copy_inventory,true|nullable|integer|min:0|max:100000'
        ], [
            'target_platform_id.required' => 'المنصة المستهدفة مطلوبة',
            'target_platform_id.exists' => 'المنصة المستهدفة غير موجودة',
            'target_platform_id.different' => 'يجب اختيار منصة مختلفة',
            'inventory_quantity.required_if' => 'كمية المخزون مطلوبة عند نسخ المخزون'
        ]);

        // التحقق من عدم وجود دوسية بنفس الاسم في المنصة المستهدفة
        $exists = EducationalPackage::where('platform_id', $request->target_platform_id)
                                  ->where('name', $physicalBooklet->name)
                                  ->where('product_type_id', $this->physicalProductType->id)
                                  ->exists();

        if ($exists) {
            return back()->withErrors(['target_platform_id' => 'هذه الدوسية موجودة بالفعل في المنصة المستهدفة']);
        }

        // إنشاء نسخة من الدوسية
        $newBooklet = EducationalPackage::create([
            'product_type_id' => $this->physicalProductType->id,
            'platform_id' => $request->target_platform_id,
            'name' => $physicalBooklet->name,
            'description' => $physicalBooklet->description,
            'duration_days' => null,
            'lessons_count' => null,
            'pages_count' => $physicalBooklet->pages_count,
            'weight_grams' => $physicalBooklet->weight_grams,
            'is_active' => $physicalBooklet->is_active
        ]);

        // نسخ المخزون إذا طُلب ذلك
        if ($request->boolean('copy_inventory') && $request->filled('inventory_quantity')) {
            $targetPlatform = \App\Models\Platform::find($request->target_platform_id);
            $teacher = $targetPlatform->teacher;
            $subject = $teacher->subject;
            $generation = $subject->generation;

            \App\Models\EducationalInventory::create([
                'generation_id' => $generation->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'platform_id' => $targetPlatform->id,
                'package_id' => $newBooklet->id,
                'quantity_available' => $request->inventory_quantity,
                'quantity_reserved' => 0
            ]);
        }

        return redirect()->route('admin.educational.physical-booklets.show', $newBooklet)
                        ->with('success', 'تم نسخ الدوسية الورقية بنجاح');
    }

    /**
     * إضافة مخزون سريع للدوسية
     */
    public function addInventory(Request $request, EducationalPackage $physicalBooklet)
    {
        $this->ensureIsPhysicalBooklet($physicalBooklet);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:100000'
        ]);

        $platform = $physicalBooklet->platform;
        $teacher = $platform->teacher;
        $subject = $teacher->subject;
        $generation = $subject->generation;

        // البحث عن مخزون موجود أو إنشاء جديد
        $inventory = \App\Models\EducationalInventory::firstOrCreate([
            'generation_id' => $generation->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'platform_id' => $platform->id,
            'package_id' => $physicalBooklet->id,
        ], [
            'quantity_available' => 0,
            'quantity_reserved' => 0
        ]);

        $inventory->addStock($request->quantity);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "تم إضافة {$request->quantity} قطعة للمخزون",
                'new_quantity' => $inventory->fresh()->quantity_available
            ]);
        }

        return redirect()->back()->with('success', "تم إضافة {$request->quantity} قطعة للمخزون");
    }

    /**
     * إحصائيات الدوسيات الورقية
     */
    public function statistics()
    {
        $stats = [
            'total_booklets' => EducationalPackage::where('product_type_id', $this->physicalProductType->id)->count(),
            'active_booklets' => EducationalPackage::where('product_type_id', $this->physicalProductType->id)
                                                 ->where('is_active', true)->count(),
            'total_inventory_items' => \App\Models\EducationalInventory::count(),
            'total_stock_available' => \App\Models\EducationalInventory::sum('quantity_available'),
            'total_stock_reserved' => \App\Models\EducationalInventory::sum('quantity_reserved'),
            'out_of_stock_count' => \App\Models\EducationalInventory::where('quantity_available', '<=', 0)->count(),
            'low_stock_count' => \App\Models\EducationalInventory::where('quantity_available', '>', 0)
                                                                ->where('quantity_available', '<=', 10)->count(),
            'total_revenue' => DB::table('order_items')
                ->join('educational_packages', 'order_items.package_id', '=', 'educational_packages.id')
                ->where('educational_packages.product_type_id', $this->physicalProductType->id)
                ->sum('order_items.price'),
            'average_pages' => EducationalPackage::where('product_type_id', $this->physicalProductType->id)
                                                ->avg('pages_count'),
            'average_weight' => EducationalPackage::where('product_type_id', $this->physicalProductType->id)
                                                 ->avg('weight_grams'),
            'total_quantity_sold' => DB::table('order_items')
                ->join('educational_packages', 'order_items.package_id', '=', 'educational_packages.id')
                ->where('educational_packages.product_type_id', $this->physicalProductType->id)
                ->sum('order_items.quantity'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * تصدير الدوسيات الورقية
     */
    public function export(Request $request)
    {
        $query = EducationalPackage::with(['productType', 'platform.teacher.subject.generation', 'educationalInventory'])
                                 ->where('product_type_id', $this->physicalProductType->id);

        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->platform_id);
        }

        $physicalBooklets = $query->get();

        $filename = 'physical_booklets_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($physicalBooklets) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للتعامل مع UTF-8 في Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // عناوين CSV
            fputcsv($file, [
                'المعرف', 'اسم الدوسية', 'المنصة', 'المعلم', 'المادة', 'الجيل',
                'عدد الصفحات', 'الوزن (جرام)', 'المخزون المتاح', 'المخزون المحجوز', 'الحالة', 'تاريخ الإنشاء'
            ]);
            
            // بيانات CSV
            foreach ($physicalBooklets as $booklet) {
                $inventory = $booklet->educationalInventory->first();
                
                fputcsv($file, [
                    $booklet->id,
                    $booklet->name,
                    $booklet->platform->name,
                    $booklet->platform->teacher->name,
                    $booklet->platform->teacher->subject->name,
                    $booklet->platform->teacher->subject->generation->display_name,
                    $booklet->pages_count ?: 'غير محدد',
                    $booklet->weight_grams ?: 'غير محدد',
                    $inventory ? $inventory->quantity_available : 'لا يوجد مخزون',
                    $inventory ? $inventory->quantity_reserved : 'لا يوجد مخزون',
                    $booklet->is_active ? 'نشطة' : 'غير نشطة',
                    $booklet->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * تقرير المخزون المنخفض
     */
    public function lowStockReport()
    {
        $lowStockBooklets = EducationalPackage::where('product_type_id', $this->physicalProductType->id)
                                           ->whereHas('educationalInventory', function($q) {
                                               $q->where('quantity_available', '>', 0)
                                                 ->where('quantity_available', '<=', 10);
                                           })
                                           ->with(['platform.teacher.subject.generation', 'educationalInventory'])
                                           ->get();

        return view('admin.educational.physical-booklets.low-stock-report', compact('lowStockBooklets'));
    }

    /**
     * تقرير الدوسيات بدون مخزون
     */
    public function noInventoryReport()
    {
        $noInventoryBooklets = EducationalPackage::where('product_type_id', $this->physicalProductType->id)
                                                ->whereDoesntHave('educationalInventory')
                                                ->with(['platform.teacher.subject.generation'])
                                                ->get();

        return view('admin.educational.physical-booklets.no-inventory-report', compact('noInventoryBooklets'));
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Ensure the package is a physical booklet
     */
    private function ensureIsPhysicalBooklet(EducationalPackage $package)
    {
        if ($package->product_type_id !== $this->physicalProductType->id) {
            abort(404, 'العنصر المطلوب ليس دوسية ورقية');
        }

        if (!$package->productType || $package->productType->is_digital) {
            abort(404, 'العنصر المطلوب ليس دوسية ورقية');
        }

        // Additional validation for data integrity
        if (!$package->isValidPhysicalBooklet()) {
            throw new \Exception('الدوسية تحتوي على بيانات غير صحيحة للمنتج الورقي');
        }
    }

    /**
     * Get physical booklets query with all necessary filters
     */
    private function getPhysicalBookletsQuery()
    {
        return EducationalPackage::where('product_type_id', $this->physicalProductType->id)
                                ->whereHas('productType', function($q) {
                                    $q->where('is_digital', false);
                                });
    }
}