<?php
// app/Http/Controllers/Admin/EducationalPricingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalPricing;
use App\Models\Generation;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Platform;
use App\Models\EducationalPackage;
use App\Models\ShippingRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EducationalPricingController extends Controller
{
    /**
     * عرض قائمة التسعير
     */
    public function index(Request $request)
    {
        $query = EducationalPricing::with([
            'generation', 'subject', 'teacher', 'platform', 'package.productType', 'region'
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

        // فلترة حسب المنطقة
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        } elseif ($request->filled('product_type')) {
            if ($request->product_type === 'digital') {
                $query->whereNull('region_id');
            } elseif ($request->product_type === 'physical') {
                $query->whereNotNull('region_id');
            }
        }

        // فلترة حسب الحالة
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // فلترة حسب نطاق السعر
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // ترتيب
        $sortBy = $request->get('sort', 'price');
        $sortOrder = $request->get('order', 'asc');
        
        if (in_array($sortBy, ['price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('price', 'asc');
        }

        $pricing = $query->paginate(20);

        // بيانات الفلاتر
        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();
        $platforms = Platform::active()->with('teacher')->orderBy('name')->get();
        $packages = EducationalPackage::active()->with('productType')->orderBy('name')->get();
        $regions = ShippingRegion::active()->orderBy('name')->get();

        // إحصائيات
        $stats = [
            'total_pricing' => EducationalPricing::count(),
            'active_pricing' => EducationalPricing::where('is_active', true)->count(),
            'digital_pricing' => EducationalPricing::whereNull('region_id')->count(),
            'physical_pricing' => EducationalPricing::whereNotNull('region_id')->count(),
            'average_price' => EducationalPricing::avg('price'),
            'min_price' => EducationalPricing::min('price'),
            'max_price' => EducationalPricing::max('price')
        ];

        return view('admin.educational.pricing.index', compact(
            'pricing', 'generations', 'subjects', 'teachers', 'platforms', 'packages', 'regions', 'stats'
        ));
    }

    /**
     * عرض نموذج إنشاء تسعير جديد
     */
    public function create()
    {
        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();
        $platforms = Platform::active()->with('teacher')->orderBy('name')->get();
        $packages = EducationalPackage::active()->with('productType')->orderBy('name')->get();
        $regions = ShippingRegion::active()->orderBy('name')->get();

        return view('admin.educational.pricing.create', compact(
            'generations', 'subjects', 'teachers', 'platforms', 'packages', 'regions'
        ));
    }

    /**
     * حفظ تسعير جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'generation_id' => 'required|exists:generations,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'platform_id' => 'required|exists:platforms,id',
            'package_id' => 'required|exists:educational_packages,id',
            'region_id' => 'nullable|exists:shipping_regions,id',
            'price' => 'required|numeric|min:0|max:9999.99',
            'is_active' => 'boolean'
        ], [
            'generation_id.required' => 'الجيل مطلوب',
            'subject_id.required' => 'المادة مطلوبة',
            'teacher_id.required' => 'المعلم مطلوب',
            'platform_id.required' => 'المنصة مطلوبة',
            'package_id.required' => 'الباقة مطلوبة',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر لا يمكن أن يكون سالب',
            'price.max' => 'السعر لا يمكن أن يتجاوز 9999.99 دينار'
        ]);

        // التحقق من صحة السلسلة التعليمية
        if (!$this->validateEducationalChain($validated)) {
            return back()->withErrors(['chain' => 'الاختيار غير صحيح، يرجى التأكد من السلسلة التعليمية'])
                        ->withInput();
        }

        // التحقق من نوع الباقة ومطابقة المنطقة
        $package = EducationalPackage::find($validated['package_id']);
        if ($package->requires_shipping && !$validated['region_id']) {
            return back()->withErrors(['region_id' => 'منطقة الشحن مطلوبة للدوسيات الورقية'])
                        ->withInput();
        }

        if (!$package->requires_shipping && $validated['region_id']) {
            $validated['region_id'] = null; // إزالة المنطقة للمنتجات الرقمية
        }

        // التحقق من عدم التكرار
        $exists = EducationalPricing::where('generation_id', $validated['generation_id'])
                                  ->where('subject_id', $validated['subject_id'])
                                  ->where('teacher_id', $validated['teacher_id'])
                                  ->where('platform_id', $validated['platform_id'])
                                  ->where('package_id', $validated['package_id'])
                                  ->where('region_id', $validated['region_id'])
                                  ->exists();

        if ($exists) {
            return back()->withErrors(['duplicate' => 'هذا التسعير موجود بالفعل'])
                        ->withInput();
        }

        $validated['is_active'] = $request->boolean('is_active');

        EducationalPricing::create($validated);

        return redirect()->route('admin.educational.pricing.index')
                        ->with('success', 'تم إنشاء التسعير بنجاح');
    }

    /**
     * عرض تفاصيل تسعير محدد
     */
    public function show(EducationalPricing $pricing)
    {
        $pricing->load([
            'generation', 'subject', 'teacher', 'platform', 'package.productType', 'region'
        ]);
        
        return view('admin.educational.pricing.show', compact('pricing'));
    }

    /**
     * عرض نموذج تعديل تسعير
     */
    public function edit(EducationalPricing $pricing)
    {
        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();
        $platforms = Platform::active()->with('teacher')->orderBy('name')->get();
        $packages = EducationalPackage::active()->with('productType')->orderBy('name')->get();
        $regions = ShippingRegion::active()->orderBy('name')->get();

        return view('admin.educational.pricing.edit', compact(
            'pricing', 'generations', 'subjects', 'teachers', 'platforms', 'packages', 'regions'
        ));
    }

    /**
     * تحديث تسعير محدد
     */
    public function update(Request $request, EducationalPricing $pricing)
    {
        $validated = $request->validate([
            'generation_id' => 'required|exists:generations,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'platform_id' => 'required|exists:platforms,id',
            'package_id' => 'required|exists:educational_packages,id',
            'region_id' => 'nullable|exists:shipping_regions,id',
            'price' => 'required|numeric|min:0|max:9999.99',
            'is_active' => 'boolean'
        ], [
            'generation_id.required' => 'الجيل مطلوب',
            'subject_id.required' => 'المادة مطلوبة',
            'teacher_id.required' => 'المعلم مطلوب',
            'platform_id.required' => 'المنصة مطلوبة',
            'package_id.required' => 'الباقة مطلوبة',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر لا يمكن أن يكون سالب',
            'price.max' => 'السعر لا يمكن أن يتجاوز 9999.99 دينار'
        ]);

        // التحقق من صحة السلسلة التعليمية
        if (!$this->validateEducationalChain($validated)) {
            return back()->withErrors(['chain' => 'الاختيار غير صحيح، يرجى التأكد من السلسلة التعليمية'])
                        ->withInput();
        }

        // التحقق من نوع الباقة ومطابقة المنطقة
        $package = EducationalPackage::find($validated['package_id']);
        if ($package->requires_shipping && !$validated['region_id']) {
            return back()->withErrors(['region_id' => 'منطقة الشحن مطلوبة للدوسيات الورقية'])
                        ->withInput();
        }

        if (!$package->requires_shipping && $validated['region_id']) {
            $validated['region_id'] = null; // إزالة المنطقة للمنتجات الرقمية
        }

        // التحقق من عدم التكرار (باستثناء السجل الحالي)
        $exists = EducationalPricing::where('generation_id', $validated['generation_id'])
                                  ->where('subject_id', $validated['subject_id'])
                                  ->where('teacher_id', $validated['teacher_id'])
                                  ->where('platform_id', $validated['platform_id'])
                                  ->where('package_id', $validated['package_id'])
                                  ->where('region_id', $validated['region_id'])
                                  ->where('id', '!=', $pricing->id)
                                  ->exists();

        if ($exists) {
            return back()->withErrors(['duplicate' => 'هذا التسعير موجود بالفعل'])
                        ->withInput();
        }

        $validated['is_active'] = $request->boolean('is_active');

        $pricing->update($validated);

        return redirect()->route('admin.educational.pricing.index')
                        ->with('success', 'تم تحديث التسعير بنجاح');
    }

    /**
     * حذف تسعير محدد
     */
    public function destroy(EducationalPricing $pricing)
    {
        // التحقق من وجود طلبات مرتبطة
        $ordersCount = DB::table('order_items')
                        ->where('generation_id', $pricing->generation_id)
                        ->where('subject_id', $pricing->subject_id)
                        ->where('teacher_id', $pricing->teacher_id)
                        ->where('platform_id', $pricing->platform_id)
                        ->where('package_id', $pricing->package_id)
                        ->where('region_id', $pricing->region_id)
                        ->count();

        if ($ordersCount > 0) {
            return redirect()->route('admin.educational.pricing.index')
                            ->with('error', 'لا يمكن حذف التسعير لوجود طلبات مرتبطة به');
        }

        $pricing->delete();

        return redirect()->route('admin.educational.pricing.index')
                        ->with('success', 'تم حذف التسعير بنجاح');
    }

    /**
     * تبديل حالة التفعيل
     */
    public function toggleStatus(EducationalPricing $pricing)
    {
        $pricing->update(['is_active' => !$pricing->is_active]);

        $status = $pricing->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$status} التسعير بنجاح",
                'is_active' => $pricing->is_active
            ]);
        }

        return redirect()->back()->with('success', "{$status} التسعير بنجاح");
    }

    /**
     * تحديث جماعي للأسعار
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'pricing_ids' => 'required|array|min:1',
            'pricing_ids.*' => 'exists:educational_pricing,id',
            'action' => 'required|in:update_price,add_percentage,set_status',
            'price' => 'required_if:action,update_price|nullable|numeric|min:0|max:9999.99',
            'percentage' => 'required_if:action,add_percentage|nullable|numeric|min:-100|max:500',
            'status' => 'required_if:action,set_status|nullable|boolean'
        ], [
            'pricing_ids.required' => 'يجب اختيار تسعير واحد على الأقل',
            'action.required' => 'نوع العملية مطلوب',
            'price.required_if' => 'السعر مطلوب للتحديث المباشر',
            'percentage.required_if' => 'النسبة المئوية مطلوبة'
        ]);

        $pricingItems = EducationalPricing::whereIn('id', $validated['pricing_ids'])->get();
        $updated = 0;

        foreach ($pricingItems as $item) {
            switch ($validated['action']) {
                case 'update_price':
                    $item->update(['price' => $validated['price']]);
                    $updated++;
                    break;
                    
                case 'add_percentage':
                    $newPrice = $item->price * (1 + ($validated['percentage'] / 100));
                    $newPrice = max(0, min(9999.99, $newPrice)); // التأكد من البقاء ضمن الحدود
                    $item->update(['price' => round($newPrice, 2)]);
                    $updated++;
                    break;
                    
                case 'set_status':
                    $item->update(['is_active' => $validated['status']]);
                    $updated++;
                    break;
            }
        }

        return redirect()->back()->with('success', "تم تحديث {$updated} تسعير بنجاح");
    }

    /**
     * تصدير التسعير
     */
    public function export(Request $request)
    {
        $query = EducationalPricing::with([
            'generation', 'subject', 'teacher', 'platform', 'package.productType', 'region'
        ]);

        // تطبيق نفس الفلاتر
        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $pricing = $query->get();

        $filename = 'educational_pricing_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($pricing) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للتعامل مع UTF-8 في Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // عناوين CSV
            fputcsv($file, [
                'المعرف', 'الجيل', 'المادة', 'المعلم', 'المنصة', 'الباقة', 'نوع المنتج', 
                'المنطقة', 'السعر', 'الحالة', 'تاريخ الإنشاء'
            ]);
            
            // بيانات CSV
            foreach ($pricing as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->generation->display_name,
                    $item->subject->name,
                    $item->teacher->name,
                    $item->platform->name,
                    $item->package->name,
                    $item->package->productType->name,
                    $item->region ? $item->region->name : 'رقمي',
                    number_format($item->price, 2),
                    $item->is_active ? 'نشط' : 'غير نشط',
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