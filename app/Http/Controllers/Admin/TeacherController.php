<?php
// app/Http/Controllers/Admin/TeacherController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Generation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * عرض قائمة المعلمين
     */
    public function index(Request $request)
    {
        $query = Teacher::with(['subject.generation', 'platforms']);

        // فلترة حسب الجيل
        if ($request->filled('generation_id')) {
            $query->whereHas('subject', function($q) use ($request) {
                $q->where('generation_id', $request->generation_id);
            });
        }

        // فلترة حسب المادة
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
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
                  ->orWhere('specialization', 'LIKE', "%{$search}%");
            });
        }

        $teachers = $query->withCount(['platforms', 'activePlatforms'])
                         ->orderBy('name')
                         ->paginate(15);

        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();

        return view('admin.educational.teachers.index', compact('teachers', 'generations', 'subjects'));
    }

    /**
     * عرض نموذج إنشاء معلم جديد
     */
    public function create()
    {
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        
        return view('admin.educational.teachers.create', compact('subjects'));
    }

    /**
     * حفظ معلم جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ], [
            'subject_id.required' => 'المادة مطلوبة',
            'subject_id.exists' => 'المادة المحددة غير موجودة',
            'name.required' => 'اسم المعلم مطلوب',
            'name.max' => 'اسم المعلم لا يجب أن يتجاوز 255 حرف',
            'specialization.max' => 'التخصص لا يجب أن يتجاوز 255 حرف',
            'bio.max' => 'السيرة الذاتية لا يجب أن تتجاوز 1000 حرف',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت'
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // التعامل مع رفع الصورة
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('teachers', 'public');
            $validated['image'] = $imagePath;
        }

        Teacher::create($validated);

        return redirect()->route('admin.educational.teachers.index')
                        ->with('success', 'تم إنشاء المعلم بنجاح');
    }

    /**
     * عرض تفاصيل معلم محدد
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['subject.generation', 'platforms.educationalPackages']);
        
        return view('admin.educational.teachers.show', compact('teacher'));
    }

    /**
     * عرض نموذج تعديل معلم
     */
    public function edit(Teacher $teacher)
    {
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        
        return view('admin.educational.teachers.edit', compact('teacher', 'subjects'));
    }

    /**
     * تحديث معلم محدد
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'remove_image' => 'boolean'
        ], [
            'subject_id.required' => 'المادة مطلوبة',
            'subject_id.exists' => 'المادة المحددة غير موجودة',
            'name.required' => 'اسم المعلم مطلوب',
            'name.max' => 'اسم المعلم لا يجب أن يتجاوز 255 حرف',
            'specialization.max' => 'التخصص لا يجب أن يتجاوز 255 حرف',
            'bio.max' => 'السيرة الذاتية لا يجب أن تتجاوز 1000 حرف',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت'
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // التعامل مع إزالة الصورة
        if ($request->boolean('remove_image') && $teacher->image) {
            Storage::disk('public')->delete($teacher->image);
            $validated['image'] = null;
        }
        // التعامل مع رفع صورة جديدة
        elseif ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($teacher->image) {
                Storage::disk('public')->delete($teacher->image);
            }
            $validated['image'] = $request->file('image')->store('teachers', 'public');
        } else {
            // الاحتفاظ بالصورة الموجودة
            unset($validated['image']);
        }

        unset($validated['remove_image']);

        $teacher->update($validated);

        return redirect()->route('admin.educational.teachers.index')
                        ->with('success', 'تم تحديث المعلم بنجاح');
    }

    /**
     * حذف معلم محدد
     */
    public function destroy(Teacher $teacher)
    {
        // التحقق من وجود منصات مرتبطة
        if ($teacher->platforms()->exists()) {
            return redirect()->route('admin.educational.teachers.index')
                            ->with('error', 'لا يمكن حذف المعلم لوجود منصات مرتبطة به');
        }

        // التحقق من وجود طلبات مرتبطة
        if ($teacher->orderItems()->exists()) {
            return redirect()->route('admin.educational.teachers.index')
                            ->with('error', 'لا يمكن حذف المعلم لوجود طلبات مرتبطة به');
        }

        // حذف الصورة إذا كانت موجودة
        if ($teacher->image) {
            Storage::disk('public')->delete($teacher->image);
        }

        $teacher->delete();

        return redirect()->route('admin.educational.teachers.index')
                        ->with('success', 'تم حذف المعلم بنجاح');
    }

    /**
     * تبديل حالة التفعيل
     */
    public function toggleStatus(Teacher $teacher)
    {
        $teacher->update(['is_active' => !$teacher->is_active]);

        $status = $teacher->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$status} المعلم بنجاح",
                'is_active' => $teacher->is_active
            ]);
        }

        return redirect()->back()->with('success', "{$status} المعلم بنجاح");
    }

    /**
     * جلب المنصات لمعلم محدد (AJAX)
     */
    public function getPlatforms(Teacher $teacher)
    {
        $platforms = $teacher->platforms()
                            ->with('educationalPackages')
                            ->get()
                            ->map(function($platform) {
                                return [
                                    'id' => $platform->id,
                                    'name' => $platform->name,
                                    'description' => $platform->description,
                                    'website_url' => $platform->website_url,
                                    'is_active' => $platform->is_active,
                                    'packages_count' => $platform->educationalPackages->count()
                                ];
                            });

        return response()->json([
            'success' => true,
            'data' => $platforms
        ]);
    }

    /**
     * نسخ معلم لمادة أخرى
     */
    public function clone(Request $request, Teacher $teacher)
    {
        $request->validate([
            'target_subject_id' => 'required|exists:subjects,id|different:' . $teacher->subject_id
        ], [
            'target_subject_id.required' => 'المادة المستهدفة مطلوبة',
            'target_subject_id.exists' => 'المادة المستهدفة غير موجودة',
            'target_subject_id.different' => 'يجب اختيار مادة مختلفة'
        ]);

        // التحقق من عدم وجود معلم بنفس الاسم في المادة المستهدفة
        $exists = Teacher::where('subject_id', $request->target_subject_id)
                         ->where('name', $teacher->name)
                         ->exists();

        if ($exists) {
            return back()->withErrors(['target_subject_id' => 'هذا المعلم موجود بالفعل في المادة المستهدفة']);
        }

        // إنشاء نسخة من المعلم
        $newTeacher = Teacher::create([
            'subject_id' => $request->target_subject_id,
            'name' => $teacher->name,
            'specialization' => $teacher->specialization,
            'bio' => $teacher->bio,
            'image' => $teacher->image, // نفس الصورة
            'is_active' => $teacher->is_active
        ]);

        return redirect()->route('admin.educational.teachers.show', $newTeacher)
                        ->with('success', 'تم نسخ المعلم بنجاح');
    }

    /**
     * إحصائيات المعلم
     */
    public function statistics(Teacher $teacher)
    {
        $stats = [
            'platforms_count' => $teacher->platforms()->count(),
            'active_platforms_count' => $teacher->activePlatforms()->count(),
            'packages_count' => $teacher->platforms()->withCount('educationalPackages')->get()->sum('educational_packages_count'),
            'cards_count' => $teacher->educationalCards()->count(),
            'active_cards_count' => $teacher->educationalCards()->where('status', 'active')->count(),
            'orders_count' => $teacher->orderItems()->count(),
            'total_revenue' => $teacher->orderItems()->sum('price')
        ];

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('admin.educational.teachers.statistics', compact('teacher', 'stats'));
    }

    /**
     * تصدير المعلمين
     */
    public function export(Request $request)
    {
        $query = Teacher::with(['subject.generation']);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('generation_id')) {
            $query->whereHas('subject', function($q) use ($request) {
                $q->where('generation_id', $request->generation_id);
            });
        }

        $teachers = $query->get();

        $filename = 'teachers_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($teachers) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للتعامل مع UTF-8 في Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // عناوين CSV
            fputcsv($file, ['المعرف', 'اسم المعلم', 'التخصص', 'المادة', 'الجيل', 'الحالة', 'عدد المنصات', 'تاريخ الإنشاء']);
            
            // بيانات CSV
            foreach ($teachers as $teacher) {
                fputcsv($file, [
                    $teacher->id,
                    $teacher->name,
                    $teacher->specialization ?: 'غير محدد',
                    $teacher->subject->name,
                    $teacher->subject->generation->display_name,
                    $teacher->is_active ? 'نشط' : 'غير نشط',
                    $teacher->platforms->count(),
                    $teacher->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}