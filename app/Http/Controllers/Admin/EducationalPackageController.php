<?php
// app/Http/Controllers/Admin/EducationalPackageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalPackage;
use App\Models\EducationalProductType;
use App\Models\Platform;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Generation;
use Illuminate\Http\Request;

class EducationalPackageController extends Controller
{
    /**
     * عرض قائمة الباقات التعليمية
     */
    public function index(Request $request)
    {
        $query = EducationalPackage::with(['productType', 'platform.teacher.subject.generation']);

        // فلترة حسب نوع المنتج
        if ($request->filled('product_type_id')) {
            $query->where('product_type_id', $request->product_type_id);
        }

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

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $packages = $query->orderBy('name')->paginate(15);

        $productTypes = EducationalProductType::all();
        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();
        $platforms = Platform::active()->with('teacher')->orderBy('name')->get();

        return view('admin.educational.packages.index', compact(
            'packages', 'productTypes', 'generations', 'subjects', 'teachers', 'platforms'
        ));
    }

    /**
     * عرض نموذج إنشاء باقة جديدة
     */
    public function create()
    {
        $productTypes = EducationalProductType::all();
        $platforms = Platform::active()->with('teacher.subject.generation')->orderBy('name')->get();
        
        return view('admin.educational.packages.create', compact('productTypes', 'platforms'));
    }

    /**
     * حفظ باقة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_type_id' => 'required|exists:educational_product_types,id',
            'platform_id' => 'required|exists:platforms,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            // حقول البطاقات الرقمية
            'duration_days' => 'nullable|integer|min:1|max:3650',
            'lessons_count' => 'nullable|integer|min:1|max:1000',
            // حقول الدوسيات الورقية
            'pages_count' => 'nullable|integer|min:1|max:1000',
            'weight_grams' => 'nullable|integer|min:1|max:10000',
            'is_active' => 'boolean'
        ], [
            'product_type_id.required' => 'نوع المنتج مطلوب',
            'platform_id.required' => 'المنصة مطلوبة',
            'name.required' => 'اسم الباقة مطلوب',
            'name.max' => 'اسم الباقة لا يجب أن يتجاوز 255 حرف',
            'description.max' => 'وصف الباقة لا يجب أن يتجاوز 1000 حرف',
            'duration_days.integer' => 'مدة الصلاحية يجب أن تكون رقم صحيح',
            'duration_days.min' => 'مدة الصلاحية يجب أن تكون على الأقل يوم واحد',
            'duration_days.max' => 'مدة الصلاحية لا يمكن أن تتجاوز 10 سنوات',
            'lessons_count.integer' => 'عدد الدروس يجب أن يكون رقم صحيح',
            'lessons_count.min' => 'عدد الدروس يجب أن يكون على الأقل درس واحد',
            'lessons_count.max' => 'عدد الدروس لا يمكن أن يتجاوز 1000 درس',
            'pages_count.integer' => 'عدد الصفحات يجب أن يكون رقم صحيح',
            'pages_count.min' => 'عدد الصفحات يجب أن يكون على الأقل صفحة واحدة',
            'pages_count.max' => 'عدد الصفحات لا يمكن أن يتجاوز 1000 صفحة',
            'weight_grams.integer' => 'الوزن يجب أن يكون رقم صحيح',
            'weight_grams.min' => 'الوزن يجب أن يكون على الأقل جرام واحد',
            'weight_grams.max' => 'الوزن لا يمكن أن يتجاوز 10 كيلو'
        ]);

        // التحقق من نوع المنتج وتنظيف الحقول غير المناسبة
        $productType = EducationalProductType::find($validated['product_type_id']);
        
        if ($productType->is_digital) {
            // للمنتجات الرقمية - إزالة حقول الدوسيات
            $validated['pages_count'] = null;
            $validated['weight_grams'] = null;
        } else {
            // للمنتجات الورقية - إزالة حقول البطاقات
            $validated['duration_days'] = null;
            $validated['lessons_count'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active');

        EducationalPackage::create($validated);

        return redirect()->route('admin.educational.packages.index')
                        ->with('success', 'تم إنشاء الباقة بنجاح');
    }

    /**
     * عرض تفاصيل باقة محددة
     */
    public function show(EducationalPackage $package)
    {
        $package->load(['productType', 'platform.teacher.subject.generation', 'educationalPricing', 'educationalInventory']);
        
        return view('admin.educational.packages.show', compact('package'));
    }

    /**
     * عرض نموذج تعديل باقة
     */
    public function edit(EducationalPackage $package)
    {
        $productTypes = EducationalProductType::all();
        $platforms = Platform::active()->with('teacher.subject.generation')->orderBy('name')->get();
        
        return view('admin.educational.packages.edit', compact('package', 'productTypes', 'platforms'));
    }

    /**
     * تحديث باقة محددة
     */
    public function update(Request $request, EducationalPackage $package)
    {
        $validated = $request->validate([
            'product_type_id' => 'required|exists:educational_product_types,id',
            'platform_id' => 'required|exists:platforms,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            // حقول البطاقات الرقمية
            'duration_days' => 'nullable|integer|min:1|max:3650',
            'lessons_count' => 'nullable|integer|min:1|max:1000',
            // حقول الدوسيات الورقية
            'pages_count' => 'nullable|integer|min:1|max:1000',
            'weight_grams' => 'nullable|integer|min:1|max:10000',
            'is_active' => 'boolean'
        ], [
            'product_type_id.required' => 'نوع المنتج مطلوب',
            'platform_id.required' => 'المنصة مطلوبة',
            'name.required' => 'اسم الباقة مطلوب',
            'name.max' => 'اسم الباقة لا يجب أن يتجاوز 255 حرف',
            'description.max' => 'وصف الباقة لا يجب أن يتجاوز 1000 حرف'
        ]);

        // التحقق من نوع المنتج وتنظيف الحقول غير المناسبة
        $productType = EducationalProductType::find($validated['product_type_id']);
        
        if ($productType->is_digital) {
            // للمنتجات الرقمية - إزالة حقول الدوسيات
            $validated['pages_count'] = null;
            $validated['weight_grams'] = null;
        } else {
            // للمنتجات الورقية - إزالة حقول البطاقات
            $validated['duration_days'] = null;
            $validated['lessons_count'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active');

        $package->update($validated);

        return redirect()->route('admin.educational.packages.index')
                        ->with('success', 'تم تحديث الباقة بنجاح');
    }

    /**
     * حذف باقة محددة
     */
    public function destroy(EducationalPackage $package)
    {
        // التحقق من وجود تسعير مرتبط
        if ($package->educationalPricing()->exists()) {
            return redirect()->route('admin.educational.packages.index')
                            ->with('error', 'لا يمكن حذف الباقة لوجود تسعير مرتبط بها');
        }

        // التحقق من وجود طلبات مرتبطة
        if ($package->orderItems()->exists()) {
            return redirect()->route('admin.educational.packages.index')
                            ->with('error', 'لا يمكن حذف الباقة لوجود طلبات مرتبطة بها');
        }

        $package->delete();

        return redirect()->route('admin.educational.packages.index')
                        ->with('success', 'تم حذف الباقة بنجاح');
    }

    /**
     * تبديل حالة التفعيل
     */
    public function toggleStatus(EducationalPackage $package)
    {
        $package->update(['is_active' => !$package->is_active]);

        $status = $package->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$status} الباقة بنجاح",
                'is_active' => $package->is_active
            ]);
        }

        return redirect()->back()->with('success', "{$status} الباقة بنجاح");
    }

    /**
     * نسخ باقة لمنصة أخرى
     */
    public function clone(Request $request, EducationalPackage $package)
    {
        $request->validate([
            'target_platform_id' => 'required|exists:platforms,id|different:' . $package->platform_id
        ], [
            'target_platform_id.required' => 'المنصة المستهدفة مطلوبة',
            'target_platform_id.exists' => 'المنصة المستهدفة غير موجودة',
            'target_platform_id.different' => 'يجب اختيار منصة مختلفة'
        ]);

        // التحقق من عدم وجود باقة بنفس الاسم في المنصة المستهدفة
        $exists = EducationalPackage::where('platform_id', $request->target_platform_id)
                                  ->where('name', $package->name)
                                  ->where('product_type_id', $package->product_type_id)
                                  ->exists();

        if ($exists) {
            return back()->withErrors(['target_platform_id' => 'هذه الباقة موجودة بالفعل في المنصة المستهدفة']);
        }

        // إنشاء نسخة من الباقة
        $newPackage = EducationalPackage::create([
            'product_type_id' => $package->product_type_id,
            'platform_id' => $request->target_platform_id,
            'name' => $package->name,
            'description' => $package->description,
            'duration_days' => $package->duration_days,
            'lessons_count' => $package->lessons_count,
            'pages_count' => $package->pages_count,
            'weight_grams' => $package->weight_grams,
            'is_active' => $package->is_active
        ]);

        return redirect()->route('admin.educational.packages.show', $newPackage)
                        ->with('success', 'تم نسخ الباقة بنجاح');
    }

    /**
     * إحصائيات الباقة
     */
    public function statistics(EducationalPackage $package)
    {
        $stats = [
            'pricing_count' => $package->educationalPricing()->count(),
            'active_pricing_count' => $package->educationalPricing()->where('is_active', true)->count(),
            'cards_count' => $package->educationalCards()->count(),
            'active_cards_count' => $package->educationalCards()->where('status', 'active')->count(),
            'orders_count' => $package->orderItems()->count(),
            'total_revenue' => $package->orderItems()->sum('price'),
            'average_price' => $package->educationalPricing()->avg('price')
        ];

        // إضافة إحصائيات المخزون للدوسيات الورقية
        if (!$package->is_digital) {
            $inventory = $package->educationalInventory()->first();
            $stats['inventory_available'] = $inventory ? $inventory->quantity_available : 0;
            $stats['inventory_reserved'] = $inventory ? $inventory->quantity_reserved : 0;
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('admin.educational.packages.statistics', compact('package', 'stats'));
    }

    /**
     * تصدير الباقات
     */
    public function export(Request $request)
    {
        $query = EducationalPackage::with(['productType', 'platform.teacher.subject.generation']);

        if ($request->filled('product_type_id')) {
            $query->where('product_type_id', $request->product_type_id);
        }

        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->platform_id);
        }

        $packages = $query->get();

        $filename = 'packages_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($packages) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للتعامل مع UTF-8 في Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // عناوين CSV
            fputcsv($file, [
                'المعرف', 'اسم الباقة', 'نوع المنتج', 'المنصة', 'المعلم', 'المادة', 'الجيل',
                'مدة الصلاحية (أيام)', 'عدد الدروس', 'عدد الصفحات', 'الوزن (جرام)', 'الحالة', 'تاريخ الإنشاء'
            ]);
            
            // بيانات CSV
            foreach ($packages as $package) {
                fputcsv($file, [
                    $package->id,
                    $package->name,
                    $package->productType->name,
                    $package->platform->name,
                    $package->platform->teacher->name,
                    $package->platform->teacher->subject->name,
                    $package->platform->teacher->subject->generation->display_name,
                    $package->duration_days ?: 'غير محدد',
                    $package->lessons_count ?: 'غير محدد',
                    $package->pages_count ?: 'غير محدد',
                    $package->weight_grams ?: 'غير محدد',
                    $package->is_active ? 'نشط' : 'غير نشط',
                    $package->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}