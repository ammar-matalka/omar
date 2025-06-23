<?php
// app/Http/Controllers/Admin/PlatformController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Generation;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    /**
     * عرض قائمة المنصات
     */
    public function index(Request $request)
    {
        $query = Platform::with(['teacher.subject.generation']);

        // فلترة حسب الجيل
        if ($request->filled('generation_id')) {
            $query->whereHas('teacher.subject', function($q) use ($request) {
                $q->where('generation_id', $request->generation_id);
            });
        }

        // فلترة حسب المادة
        if ($request->filled('subject_id')) {
            $query->whereHas('teacher', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        // فلترة حسب المعلم
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
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
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('website_url', 'LIKE', "%{$search}%");
            });
        }

        $platforms = $query->withCount(['educationalPackages', 'activeEducationalPackages'])
                          ->orderBy('name')
                          ->paginate(15);

        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();

        return view('admin.educational.platforms.index', compact('platforms', 'generations', 'subjects', 'teachers'));
    }

    /**
     * عرض نموذج إنشاء منصة جديدة
     */
    public function create()
    {
        $teachers = Teacher::active()->with('subject.generation')->orderBy('name')->get();
        
        return view('admin.educational.platforms.create', compact('teachers'));
    }

    /**
     * حفظ منصة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website_url' => 'nullable|url|max:255',
            'is_active' => 'boolean'
        ], [
            'teacher_id.required' => 'المعلم مطلوب',
            'teacher_id.exists' => 'المعلم المحدد غير موجود',
            'name.required' => 'اسم المنصة مطلوب',
            'name.max' => 'اسم المنصة لا يجب أن يتجاوز 255 حرف',
            'description.max' => 'وصف المنصة لا يجب أن يتجاوز 1000 حرف',
            'website_url.url' => 'رابط الموقع يجب أن يكون صحيح',
            'website_url.max' => 'رابط الموقع لا يجب أن يتجاوز 255 حرف'
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Platform::create($validated);

        return redirect()->route('admin.educational.platforms.index')
                        ->with('success', 'تم إنشاء المنصة بنجاح');
    }

    /**
     * عرض تفاصيل منصة محددة
     */
    public function show(Platform $platform)
    {
        $platform->load(['teacher.subject.generation', 'educationalPackages.productType']);
        
        return view('admin.educational.platforms.show', compact('platform'));
    }

    /**
     * عرض نموذج تعديل منصة
     */
    public function edit(Platform $platform)
    {
        $teachers = Teacher::active()->with('subject.generation')->orderBy('name')->get();
        
        return view('admin.educational.platforms.edit', compact('platform', 'teachers'));
    }

    /**
     * تحديث منصة محددة
     */
    public function update(Request $request, Platform $platform)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website_url' => 'nullable|url|max:255',
            'is_active' => 'boolean'
        ], [
            'teacher_id.required' => 'المعلم مطلوب',
            'teacher_id.exists' => 'المعلم المحدد غير موجود',
            'name.required' => 'اسم المنصة مطلوب',
            'name.max' => 'اسم المنصة لا يجب أن يتجاوز 255 حرف',
            'description.max' => 'وصف المنصة لا يجب أن يتجاوز 1000 حرف',
            'website_url.url' => 'رابط الموقع يجب أن يكون صحيح',
            'website_url.max' => 'رابط الموقع لا يجب أن يتجاوز 255 حرف'
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $platform->update($validated);

        return redirect()->route('admin.educational.platforms.index')
                        ->with('success', 'تم تحديث المنصة بنجاح');
    }

    /**
     * حذف منصة محددة
     */
    public function destroy(Platform $platform)
    {
        // التحقق من وجود باقات مرتبطة
        if ($platform->educationalPackages()->exists()) {
            return redirect()->route('admin.educational.platforms.index')
                            ->with('error', 'لا يمكن حذف المنصة لوجود باقات مرتبطة بها');
        }

        // التحقق من وجود طلبات مرتبطة
        if ($platform->orderItems()->exists()) {
            return redirect()->route('admin.educational.platforms.index')
                            ->with('error', 'لا يمكن حذف المنصة لوجود طلبات مرتبطة بها');
        }

        $platform->delete();

        return redirect()->route('admin.educational.platforms.index')
                        ->with('success', 'تم حذف المنصة بنجاح');
    }

    /**
     * تبديل حالة التفعيل
     */
    public function toggleStatus(Platform $platform)
    {
        $platform->update(['is_active' => !$platform->is_active]);

        $status = $platform->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$status} المنصة بنجاح",
                'is_active' => $platform->is_active
            ]);
        }

        return redirect()->back()->with('success', "{$status} المنصة بنجاح");
    }

    /**
     * جلب الباقات لمنصة محددة (AJAX)
     */
    public function getPackages(Platform $platform)
    {
        $packages = $platform->educationalPackages()
                            ->with('productType')
                            ->get()
                            ->map(function($package) {
                                return [
                                    'id' => $package->id,
                                    'name' => $package->name,
                                    'description' => $package->description,
                                    'product_type' => $package->productType->name,
                                    'is_digital' => $package->is_digital,
                                    'requires_shipping' => $package->requires_shipping,
                                    'is_active' => $package->is_active,
                                    'full_info' => $package->full_info
                                ];
                            });

        return response()->json([
            'success' => true,
            'data' => $packages
        ]);
    }

    /**
     * نسخ منصة لمعلم آخر
     */
    public function clone(Request $request, Platform $platform)
    {
        $request->validate([
            'target_teacher_id' => 'required|exists:teachers,id|different:' . $platform->teacher_id
        ], [
            'target_teacher_id.required' => 'المعلم المستهدف مطلوب',
            'target_teacher_id.exists' => 'المعلم المستهدف غير موجود',
            'target_teacher_id.different' => 'يجب اختيار معلم مختلف'
        ]);

        // التحقق من عدم وجود منصة بنفس الاسم للمعلم المستهدف
        $exists = Platform::where('teacher_id', $request->target_teacher_id)
                         ->where('name', $platform->name)
                         ->exists();

        if ($exists) {
            return back()->withErrors(['target_teacher_id' => 'هذه المنصة موجودة بالفعل للمعلم المستهدف']);
        }

        // إنشاء نسخة من المنصة
        $newPlatform = Platform::create([
            'teacher_id' => $request->target_teacher_id,
            'name' => $platform->name,
            'description' => $platform->description,
            'website_url' => $platform->website_url,
            'is_active' => $platform->is_active
        ]);

        return redirect()->route('admin.educational.platforms.show', $newPlatform)
                        ->with('success', 'تم نسخ المنصة بنجاح');
    }

    /**
     * إحصائيات المنصة
     */
    public function statistics(Platform $platform)
    {
        $stats = [
            'packages_count' => $platform->educationalPackages()->count(),
            'active_packages_count' => $platform->activeEducationalPackages()->count(),
            'digital_packages_count' => $platform->educationalPackages()->digital()->count(),
            'physical_packages_count' => $platform->educationalPackages()->physical()->count(),
            'cards_count' => $platform->educationalCards()->count(),
            'active_cards_count' => $platform->educationalCards()->where('status', 'active')->count(),
            'orders_count' => $platform->orderItems()->count(),
            'total_revenue' => $platform->orderItems()->sum('price')
        ];

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('admin.educational.platforms.statistics', compact('platform', 'stats'));
    }

    /**
     * تصدير المنصات
     */
    public function export(Request $request)
    {
        $query = Platform::with(['teacher.subject.generation']);

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('teacher', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('generation_id')) {
            $query->whereHas('teacher.subject', function($q) use ($request) {
                $q->where('generation_id', $request->generation_id);
            });
        }

        $platforms = $query->get();

        $filename = 'platforms_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($platforms) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للتعامل مع UTF-8 في Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // عناوين CSV
            fputcsv($file, ['المعرف', 'اسم المنصة', 'المعلم', 'المادة', 'الجيل', 'رابط الموقع', 'الحالة', 'عدد الباقات', 'تاريخ الإنشاء']);
            
            // بيانات CSV
            foreach ($platforms as $platform) {
                fputcsv($file, [
                    $platform->id,
                    $platform->name,
                    $platform->teacher->name,
                    $platform->teacher->subject->name,
                    $platform->teacher->subject->generation->display_name,
                    $platform->website_url ?: 'غير محدد',
                    $platform->is_active ? 'نشط' : 'غير نشط',
                    $platform->educationalPackages->count(),
                    $platform->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}