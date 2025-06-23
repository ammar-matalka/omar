<?php
// app/Http/Controllers/Admin/EducationalInventoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalInventory;
use App\Models\Generation;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Platform;
use App\Models\EducationalPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EducationalInventoryController extends Controller
{
    /**
     * عرض قائمة المخزون
     */
    public function index(Request $request)
    {
        $query = EducationalInventory::with([
            'generation', 'subject', 'teacher', 'platform', 'package.productType'
        ]);

        // فلترة حسب الجيل
        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }

        // فلترة حسب المادة
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // فلترة حسب المعلم
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // فلترة حسب المنصة
        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->platform_id);
        }

        // فلترة حسب الباقة
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        // فلترة حسب حالة المخزون
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'available':
                    $query->where('quantity_available', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('quantity_available', '<=', 0);
                    break;
                case 'low_stock':
                    $query->where('quantity_available', '>', 0)
                          ->where('quantity_available', '<=', 10);
                    break;
                case 'reserved':
                    $query->where('quantity_reserved', '>', 0);
                    break;
            }
        }

        // فلترة حسب نطاق الكمية
        if ($request->filled('quantity_min')) {
            $query->where('quantity_available', '>=', $request->quantity_min);
        }
        if ($request->filled('quantity_max')) {
            $query->where('quantity_available', '<=', $request->quantity_max);
        }

        // ترتيب
        $sortBy = $request->get('sort', 'quantity_available');
        $sortOrder = $request->get('order', 'asc');
        
        if (in_array($sortBy, ['quantity_available', 'quantity_reserved', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('quantity_available', 'asc');
        }

        $inventory = $query->paginate(20);

        // بيانات الفلاتر
        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();
        $platforms = Platform::active()->with('teacher')->orderBy('name')->get();
        $packages = EducationalPackage::whereHas('productType', function($q) {
            $q->where('is_digital', false); // الدوسيات الورقية فقط
        })->with('productType')->orderBy('name')->get();

        // إحصائيات
        $stats = [
            'total_items' => EducationalInventory::count(),
            'available_items' => EducationalInventory::where('quantity_available', '>', 0)->count(),
            'out_of_stock_items' => EducationalInventory::where('quantity_available', '<=', 0)->count(),
            'low_stock_items' => EducationalInventory::where('quantity_available', '>', 0)
                                                   ->where('quantity_available', '<=', 10)->count(),
            'total_quantity' => EducationalInventory::sum('quantity_available'),
            'total_reserved' => EducationalInventory::sum('quantity_reserved'),
            'average_stock' => EducationalInventory::avg('quantity_available')
        ];

        return view('admin.educational.inventory.index', compact(
            'inventory', 'generations', 'subjects', 'teachers', 'platforms', 'packages', 'stats'
        ));
    }

    /**
     * عرض نموذج إنشاء مخزون جديد
     */
    public function create()
    {
        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();
        $platforms = Platform::active()->with('teacher')->orderBy('name')->get();
        $packages = EducationalPackage::whereHas('productType', function($q) {
            $q->where('is_digital', false); // الدوسيات الورقية فقط
        })->with('productType')->orderBy('name')->get();

        return view('admin.educational.inventory.create', compact(
            'generations', 'subjects', 'teachers', 'platforms', 'packages'
        ));
    }

    /**
     * حفظ مخزون جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'generation_id' => 'required|exists:generations,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'platform_id' => 'required|exists:platforms,id',
            'package_id' => 'required|exists:educational_packages,id',
            'quantity_available' => 'required|integer|min:0|max:100000',
            'quantity_reserved' => 'nullable|integer|min:0|max:100000'
        ], [
            'generation_id.required' => 'الجيل مطلوب',
            'subject_id.required' => 'المادة مطلوبة',
            'teacher_id.required' => 'المعلم مطلوب',
            'platform_id.required' => 'المنصة مطلوبة',
            'package_id.required' => 'الباقة مطلوبة',
            'quantity_available.required' => 'الكمية المتاحة مطلوبة',
            'quantity_available.integer' => 'الكمية المتاحة يجب أن تكون رقم صحيح',
            'quantity_available.min' => 'الكمية المتاحة لا يمكن أن تكون سالبة',
            'quantity_available.max' => 'الكمية المتاحة لا يمكن أن تتجاوز 100,000'
        ]);

        // التحقق من صحة السلسلة التعليمية
        if (!$this->validateEducationalChain($validated)) {
            return back()->withErrors(['chain' => 'الاختيار غير صحيح، يرجى التأكد من السلسلة التعليمية'])
                        ->withInput();
        }

        // التحقق من أن الباقة ورقية (تحتاج مخزون)
        $package = EducationalPackage::with('productType')->find($validated['package_id']);
        if ($package->productType->is_digital) {
            return back()->withErrors(['package_id' => 'لا يمكن إضافة مخزون للمنتجات الرقمية'])
                        ->withInput();
        }

        // التحقق من عدم التكرار
        $exists = EducationalInventory::where('generation_id', $validated['generation_id'])
                                    ->where('subject_id', $validated['subject_id'])
                                    ->where('teacher_id', $validated['teacher_id'])
                                    ->where('platform_id', $validated['platform_id'])
                                    ->where('package_id', $validated['package_id'])
                                    ->exists();

        if ($exists) {
            return back()->withErrors(['duplicate' => 'هذا المخزون موجود بالفعل'])
                        ->withInput();
        }

        $validated['quantity_reserved'] = $validated['quantity_reserved'] ?? 0;

        EducationalInventory::create($validated);

        return redirect()->route('admin.educational.inventory.index')
                        ->with('success', 'تم إنشاء المخزون بنجاح');
    }

    /**
     * عرض تفاصيل مخزون محدد
     */
    public function show(EducationalInventory $inventory)
    {
        $inventory->load([
            'generation', 'subject', 'teacher', 'platform', 'package.productType'
        ]);
        
        return view('admin.educational.inventory.show', compact('inventory'));
    }

    /**
     * عرض نموذج تعديل مخزون
     */
    public function edit(EducationalInventory $inventory)
    {
        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();
        $platforms = Platform::active()->with('teacher')->orderBy('name')->get();
        $packages = EducationalPackage::whereHas('productType', function($q) {
            $q->where('is_digital', false); // الدوسيات الورقية فقط
        })->with('productType')->orderBy('name')->get();

        return view('admin.educational.inventory.edit', compact(
            'inventory', 'generations', 'subjects', 'teachers', 'platforms', 'packages'
        ));
    }

    /**
     * تحديث مخزون محدد
     */
    public function update(Request $request, EducationalInventory $inventory)
    {
        $validated = $request->validate([
            'generation_id' => 'required|exists:generations,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'platform_id' => 'required|exists:platforms,id',
            'package_id' => 'required|exists:educational_packages,id',
            'quantity_available' => 'required|integer|min:0|max:100000',
            'quantity_reserved' => 'nullable|integer|min:0|max:100000'
        ], [
            'generation_id.required' => 'الجيل مطلوب',
            'subject_id.required' => 'المادة مطلوبة',
            'teacher_id.required' => 'المعلم مطلوب',
            'platform_id.required' => 'المنصة مطلوبة',
            'package_id.required' => 'الباقة مطلوبة',
            'quantity_available.required' => 'الكمية المتاحة مطلوبة',
            'quantity_available.integer' => 'الكمية المتاحة يجب أن تكون رقم صحيح',
            'quantity_available.min' => 'الكمية المتاحة لا يمكن أن تكون سالبة',
            'quantity_available.max' => 'الكمية المتاحة لا يمكن أن تتجاوز 100,000'
        ]);

        // التحقق من صحة السلسلة التعليمية
        if (!$this->validateEducationalChain($validated)) {
            return back()->withErrors(['chain' => 'الاختيار غير صحيح، يرجى التأكد من السلسلة التعليمية'])
                        ->withInput();
        }

        // التحقق من أن الباقة ورقية (تحتاج مخزون)
        $package = EducationalPackage::with('productType')->find($validated['package_id']);
        if ($package->productType->is_digital) {
            return back()->withErrors(['package_id' => 'لا يمكن إضافة مخزون للمنتجات الرقمية'])
                        ->withInput();
        }

        // التحقق من عدم التكرار (باستثناء السجل الحالي)
        $exists = EducationalInventory::where('generation_id', $validated['generation_id'])
                                    ->where('subject_id', $validated['subject_id'])
                                    ->where('teacher_id', $validated['teacher_id'])
                                    ->where('platform_id', $validated['platform_id'])
                                    ->where('package_id', $validated['package_id'])
                                    ->where('id', '!=', $inventory->id)
                                    ->exists();

        if ($exists) {
            return back()->withErrors(['duplicate' => 'هذا المخزون موجود بالفعل'])
                        ->withInput();
        }

        $validated['quantity_reserved'] = $validated['quantity_reserved'] ?? 0;

        $inventory->update($validated);

        return redirect()->route('admin.educational.inventory.index')
                        ->with('success', 'تم تحديث المخزون بنجاح');
    }

    /**
     * حذف مخزون محدد
     */
    public function destroy(EducationalInventory $inventory)
    {
        // التحقق من وجود كمية محجوزة
        if ($inventory->quantity_reserved > 0) {
            return redirect()->route('admin.educational.inventory.index')
                            ->with('error', 'لا يمكن حذف المخزون لوجود كمية محجوزة');
        }

        // التحقق من وجود طلبات مرتبطة
        $ordersCount = DB::table('order_items')
                        ->where('generation_id', $inventory->generation_id)
                        ->where('subject_id', $inventory->subject_id)
                        ->where('teacher_id', $inventory->teacher_id)
                        ->where('platform_id', $inventory->platform_id)
                        ->where('package_id', $inventory->package_id)
                        ->count();

        if ($ordersCount > 0) {
            return redirect()->route('admin.educational.inventory.index')
                            ->with('error', 'لا يمكن حذف المخزون لوجود طلبات مرتبطة به');
        }

        $inventory->delete();

        return redirect()->route('admin.educational.inventory.index')
                        ->with('success', 'تم حذف المخزون بنجاح');
    }

    /**
     * إضافة كمية للمخزون
     */
    public function addStock(Request $request, EducationalInventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:10000'
        ], [
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'quantity.max' => 'الكمية لا يمكن أن تتجاوز 10,000'
        ]);

        $inventory->addStock($validated['quantity']);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "تم إضافة {$validated['quantity']} قطعة للمخزون",
                'new_quantity' => $inventory->fresh()->quantity_available
            ]);
        }

        return redirect()->back()->with('success', "تم إضافة {$validated['quantity']} قطعة للمخزون");
    }

    /**
     * تعديل المخزون المحجوز
     */
    public function adjustReserved(Request $request, EducationalInventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0|max:' . $inventory->quantity_available
        ], [
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
            'quantity.min' => 'الكمية لا يمكن أن تكون سالبة',
            'quantity.max' => 'الكمية المحجوزة لا يمكن أن تتجاوز المخزون المتاح'
        ]);

        $inventory->update(['quantity_reserved' => $validated['quantity']]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "تم تعديل الكمية المحجوزة إلى {$validated['quantity']}",
                'new_reserved' => $validated['quantity']
            ]);
        }

        return redirect()->back()->with('success', "تم تعديل الكمية المحجوزة إلى {$validated['quantity']}");
    }

    /**
     * تحديث جماعي للمخزون
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'inventory_ids' => 'required|array|min:1',
            'inventory_ids.*' => 'exists:educational_inventory,id',
            'action' => 'required|in:add_stock,set_stock,clear_reserved',
            'quantity' => 'required_if:action,add_stock,set_stock|nullable|integer|min:0|max:100000'
        ], [
            'inventory_ids.required' => 'يجب اختيار مخزون واحد على الأقل',
            'action.required' => 'نوع العملية مطلوب',
            'quantity.required_if' => 'الكمية مطلوبة'
        ]);

        $inventoryItems = EducationalInventory::whereIn('id', $validated['inventory_ids'])->get();
        $updated = 0;

        foreach ($inventoryItems as $item) {
            switch ($validated['action']) {
                case 'add_stock':
                    $item->addStock($validated['quantity']);
                    $updated++;
                    break;
                    
                case 'set_stock':
                    $item->setStock($validated['quantity']);
                    $updated++;
                    break;
                    
                case 'clear_reserved':
                    $item->update(['quantity_reserved' => 0]);
                    $updated++;
                    break;
            }
        }

        return redirect()->back()->with('success', "تم تحديث {$updated} عنصر مخزون بنجاح");
    }

    /**
     * تقرير المخزون المنخفض
     */
    public function lowStockReport(Request $request)
    {
        $threshold = $request->get('threshold', 10);
        
        $lowStockItems = EducationalInventory::with([
            'generation', 'subject', 'teacher', 'platform', 'package'
        ])
        ->where('quantity_available', '>', 0)
        ->where('quantity_available', '<=', $threshold)
        ->orderBy('quantity_available', 'asc')
        ->get();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $lowStockItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'product_info' => $item->product_info,
                        'quantity_available' => $item->quantity_available,
                        'stock_status' => $item->stock_status
                    ];
                })
            ]);
        }

        return view('admin.educational.inventory.low-stock', compact('lowStockItems', 'threshold'));
    }

    /**
     * تصدير المخزون
     */
    public function export(Request $request)
    {
        $query = EducationalInventory::with([
            'generation', 'subject', 'teacher', 'platform', 'package.productType'
        ]);

        // تطبيق نفس الفلاتر
        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $inventory = $query->get();

        $filename = 'educational_inventory_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($inventory) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للتعامل مع UTF-8 في Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // عناوين CSV
            fputcsv($file, [
                'المعرف', 'الجيل', 'المادة', 'المعلم', 'المنصة', 'الباقة', 
                'الكمية المتاحة', 'الكمية المحجوزة', 'الكمية الفعلية', 'حالة المخزون', 'تاريخ الإنشاء'
            ]);
            
            // بيانات CSV
            foreach ($inventory as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->generation->display_name,
                    $item->subject->name,
                    $item->teacher->name,
                    $item->platform->name,
                    $item->package->name,
                    $item->quantity_available,
                    $item->quantity_reserved,
                    $item->actual_available,
                    $item->stock_status,
                    $item->created_at->format('Y-m-d H:i:s'),
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
     * التحقق من صحة السلسلة التعليمية
     */
    private function validateEducationalChain(array $data)
    {
        // التحقق من أن المادة تنتمي للجيل
        $subject = Subject::where('id', $data['subject_id'])
                         ->where('generation_id', $data['generation_id'])
                         ->first();
        if (!$subject) {
            return false;
        }

        // التحقق من أن المعلم يدرس المادة
        $teacher = Teacher::where('id', $data['teacher_id'])
                         ->where('subject_id', $data['subject_id'])
                         ->first();
        if (!$teacher) {
            return false;
        }

        // التحقق من أن المنصة تخص المعلم
        $platform = Platform::where('id', $data['platform_id'])
                           ->where('teacher_id', $data['teacher_id'])
                           ->first();
        if (!$platform) {
            return false;
        }

        // التحقق من أن الباقة تخص المنصة
        $package = EducationalPackage::where('id', $data['package_id'])
                                   ->where('platform_id', $data['platform_id'])
                                   ->first();
        if (!$package) {
            return false;
        }

        return true;
    }
}