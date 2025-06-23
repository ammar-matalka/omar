<?php
// app/Http/Controllers/Admin/SubjectController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Generation;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * عرض قائمة المواد
     */
    public function index(Request $request)
    {
        $query = Subject::with(['generation', 'teachers']);

        // فلترة حسب الجيل
        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
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
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $subjects = $query->withCount(['teachers', 'activeTeachers'])
                         ->orderBy('name')
                         ->paginate(15);

        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();

        return view('admin.educational.subjects.index', compact('subjects', 'generations'));
    }

    /**
     * عرض نموذج إنشاء مادة جديدة
     */
    public function create()
    {
        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        
        return view('admin.educational.subjects.create', compact('generations'));
    }

    /**
     * حفظ مادة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'generation_id' => 'required|exists:generations,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:subjects,code',
            'is_active' => 'boolean'
        ], [
            'generation_id.required' => 'الجيل مطلوب',
            'generation_id.exists' => 'الجيل المحدد غير موجود',
            'name.required' => 'اسم المادة مطلوب',
            'name.max' => 'اسم المادة لا يجب أن يتجاوز 255 حرف',
            'code.max' => 'كود المادة لا يجب أن يتجاوز 50 حرف',
            'code.unique' => 'كود المادة موجود بالفعل'
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Subject::create($validated);

        return redirect()->route('admin.educational.subjects.index')
                        ->with('success', 'تم إنشاء المادة بنجاح');
    }

    /**
     * عرض تفاصيل مادة محددة
     */
    public function show(Subject $subject)
    {
        $subject->load(['generation', 'teachers.platforms']);
        
        return view('admin.educational.subjects.show', compact('subject'));
    }

    /**
     * عرض نموذج تعديل مادة
     */
    public function edit(Subject $subject)
    {
        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        
        return view('admin.educational.subjects.edit', compact('subject', 'generations'));
    }

    /**
     * تحديث مادة محددة
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'generation_id' => 'required|exists:generations,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:subjects,code,' . $subject->id,
            'is_active' => 'boolean'
        ], [
            'generation_id.required' => 'الجيل مطلوب',
            'generation_id.exists' => 'الجيل المحدد غير موجود',
            'name.required' => 'اسم المادة مطلوب',
            'name.max' => 'اسم المادة لا يجب أن يتجاوز 255 حرف',
            'code.max' => 'كود المادة لا يجب أن يتجاوز 50 حرف',
            'code.unique' => 'كود المادة موجود بالفعل'
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $subject->update($validated);

        return redirect()->route('admin.educational.subjects.index')
                        ->with('success', 'تم تحديث المادة بنجاح');
    }

    /**
     * حذف مادة محددة
     */
    public function destroy(Subject $subject)
    {
        // التحقق من وجود معلمين مرتبطين
        if ($subject->teachers()->exists()) {
            return redirect()->route('admin.educational.subjects.index')
                            ->with('error', 'لا يمكن حذف المادة لوجود معلمين مرتبطين بها');
        }

        // التحقق من وجود طلبات مرتبطة
        if ($subject->orderItems()->exists()) {
            return redirect()->route('admin.educational.subjects.index')
                            ->with('error', 'لا يمكن حذف المادة لوجود طلبات مرتبطة بها');
        }

        $subject->delete();

        return redirect()->route('admin.educational.subjects.index')
                        ->with('success', 'تم حذف المادة بنجاح');
    }

    /**
     * تبديل حالة التفعيل
     */
    public function toggleStatus(Subject $subject)
    {
        $subject->update(['is_active' => !$subject->is_active]);

        $status = $subject->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$status} المادة بنجاح",
                'is_active' => $subject->is_active
            ]);
        }

        return redirect()->back()->with('success', "{$status} المادة بنجاح");
    }

    /**
     * جلب المعلمين لمادة محددة (AJAX)
     */
    public function getTeachers(Subject $subject)
    {
        $teachers = $subject->teachers()
                           ->with('platforms')
                           ->get()
                           ->map(function($teacher) {
                               return [
                                   'id' => $teacher->id,
                                   'name' => $teacher->name,
                                   'specialization' => $teacher->specialization,
                                   'is_active' => $teacher->is_active,
                                   'platforms_count' => $teacher->platforms->count(),
                                   'image_url' => $teacher->image_url
                               ];
                           });

        return response()->json([
            'success' => true,
            'data' => $teachers
        ]);
    }

    /**
     * استنساخ مادة لجيل آخر
     */
    public function clone(Request $request, Subject $subject)
    {
        $request->validate([
            'target_generation_id' => 'required|exists:generations,id|different:' . $subject->generation_id
        ], [
            'target_generation_id.required' => 'الجيل المستهدف مطلوب',
            'target_generation_id.exists' => 'الجيل المستهدف غير موجود',
            'target_generation_id.different' => 'يجب اختيار جيل مختلف'
        ]);

        // التحقق من عدم وجود مادة بنفس الاسم في الجيل المستهدف
        $exists = Subject::where('generation_id', $request->target_generation_id)
                         ->where('name', $subject->name)
                         ->exists();

        if ($exists) {
            return back()->withErrors(['target_generation_id' => 'هذه المادة موجودة بالفعل في الجيل المستهدف']);
        }

        // إنشاء نسخة من المادة
        $newSubject = Subject::create([
            'generation_id' => $request->target_generation_id,
            'name' => $subject->name,
            'code' => $subject->code ? $subject->code . '_COPY' : null,
            'is_active' => $subject->is_active
        ]);

        return redirect()->route('admin.educational.subjects.show', $newSubject)
                        ->with('success', 'تم استنساخ المادة بنجاح');
    }

    /**
     * إحصائيات المادة
     */
    public function statistics(Subject $subject)
    {
        $stats = [
            'teachers_count' => $subject->teachers()->count(),
            'active_teachers_count' => $subject->activeTeachers()->count(),
            'platforms_count' => $subject->teachers()->withCount('platforms')->get()->sum('platforms_count'),
            'cards_count' => $subject->educationalCards()->count(),
            'active_cards_count' => $subject->educationalCards()->where('status', 'active')->count(),
            'orders_count' => $subject->orderItems()->count(),
            'total_revenue' => $subject->orderItems()->sum('price')
        ];

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('admin.educational.subjects.statistics', compact('subject', 'stats'));
    }

    /**
     * تصدير المواد
     */
    public function export(Request $request)
    {
        $query = Subject::with(['generation']);

        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }

        $subjects = $query->get();

        $filename = 'subjects_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($subjects) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للتعامل مع UTF-8 في Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // عناوين CSV
            fputcsv($file, ['المعرف', 'اسم المادة', 'كود المادة', 'الجيل', 'سنة الميلاد', 'الحالة', 'تاريخ الإنشاء']);
            
            // بيانات CSV
            foreach ($subjects as $subject) {
                fputcsv($file, [
                    $subject->id,
                    $subject->name,
                    $subject->code ?: 'غير محدد',
                    $subject->generation->display_name,
                    $subject->generation->birth_year,
                    $subject->is_active ? 'نشط' : 'غير نشط',
                    $subject->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}   