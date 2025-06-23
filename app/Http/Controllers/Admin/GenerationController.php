<?php
// app/Http/Controllers/Admin/GenerationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Generation;
use Illuminate\Http\Request;

class GenerationController extends Controller
{
    /**
     * عرض قائمة الأجيال
     */
    public function index()
    {
        $generations = Generation::withCount(['subjects', 'activeSubjects'])
                                ->orderBy('birth_year', 'desc')
                                ->paginate(15);

        return view('admin.educational.generations.index', compact('generations'));
    }

    /**
     * عرض نموذج إنشاء جيل جديد
     */
    public function create()
    {
        return view('admin.educational.generations.create');
    }

    /**
     * حفظ جيل جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'birth_year' => 'required|integer|min:1990|max:' . (date('Y') + 5) . '|unique:generations,birth_year',
            'name' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ], [
            'birth_year.required' => 'سنة الميلاد مطلوبة',
            'birth_year.integer' => 'سنة الميلاد يجب أن تكون رقم صحيح',
            'birth_year.min' => 'سنة الميلاد لا يمكن أن تكون أقل من 1990',
            'birth_year.max' => 'سنة الميلاد لا يمكن أن تكون أكثر من ' . (date('Y') + 5),
            'birth_year.unique' => 'هذا الجيل موجود بالفعل',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف'
        ]);

        // توليد اسم تلقائي إذا لم يتم تقديمه
        if (empty($validated['name'])) {
            $validated['name'] = "جيل {$validated['birth_year']}";
        }

        $validated['is_active'] = $request->boolean('is_active');

        Generation::create($validated);

        return redirect()->route('admin.educational.generations.index')
                        ->with('success', 'تم إنشاء الجيل بنجاح');
    }

    /**
     * عرض تفاصيل جيل محدد
     */
    public function show(Generation $generation)
    {
        $generation->load(['subjects.teachers.platforms']);
        
        return view('admin.educational.generations.show', compact('generation'));
    }

    /**
     * عرض نموذج تعديل جيل
     */
    public function edit(Generation $generation)
    {
        return view('admin.educational.generations.edit', compact('generation'));
    }

    /**
     * تحديث جيل محدد
     */
    public function update(Request $request, Generation $generation)
    {
        $validated = $request->validate([
            'birth_year' => 'required|integer|min:1990|max:' . (date('Y') + 5) . '|unique:generations,birth_year,' . $generation->id,
            'name' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ], [
            'birth_year.required' => 'سنة الميلاد مطلوبة',
            'birth_year.integer' => 'سنة الميلاد يجب أن تكون رقم صحيح',
            'birth_year.min' => 'سنة الميلاد لا يمكن أن تكون أقل من 1990',
            'birth_year.max' => 'سنة الميلاد لا يمكن أن تكون أكثر من ' . (date('Y') + 5),
            'birth_year.unique' => 'هذا الجيل موجود بالفعل',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف'
        ]);

        // توليد اسم تلقائي إذا لم يتم تقديمه
        if (empty($validated['name'])) {
            $validated['name'] = "جيل {$validated['birth_year']}";
        }

        $validated['is_active'] = $request->boolean('is_active');

        $generation->update($validated);

        return redirect()->route('admin.educational.generations.index')
                        ->with('success', 'تم تحديث الجيل بنجاح');
    }

    /**
     * حذف جيل محدد
     */
    public function destroy(Generation $generation)
    {
        // التحقق من وجود مواد مرتبطة
        if ($generation->subjects()->exists()) {
            return redirect()->route('admin.educational.generations.index')
                            ->with('error', 'لا يمكن حذف الجيل لوجود مواد مرتبطة به');
        }

        // التحقق من وجود طلبات مرتبطة
        if ($generation->orderItems()->exists()) {
            return redirect()->route('admin.educational.generations.index')
                            ->with('error', 'لا يمكن حذف الجيل لوجود طلبات مرتبطة به');
        }

        $generation->delete();

        return redirect()->route('admin.educational.generations.index')
                        ->with('success', 'تم حذف الجيل بنجاح');
    }

    /**
     * تبديل حالة التفعيل
     */
    public function toggleStatus(Generation $generation)
    {
        $generation->update(['is_active' => !$generation->is_active]);

        $status = $generation->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$status} الجيل بنجاح",
                'is_active' => $generation->is_active
            ]);
        }

        return redirect()->back()->with('success', "{$status} الجيل بنجاح");
    }

    /**
     * جلب المواد لجيل محدد (AJAX)
     */
    public function getSubjects(Generation $generation)
    {
        $subjects = $generation->subjects()
                              ->with('teachers')
                              ->get()
                              ->map(function($subject) {
                                  return [
                                      'id' => $subject->id,
                                      'name' => $subject->name,
                                      'code' => $subject->code,
                                      'is_active' => $subject->is_active,
                                      'teachers_count' => $subject->teachers->count()
                                  ];
                              });

        return response()->json([
            'success' => true,
            'data' => $subjects
        ]);
    }

    /**
     * إحصائيات الجيل
     */
    public function statistics(Generation $generation)
    {
        $stats = [
            'subjects_count' => $generation->subjects()->count(),
            'active_subjects_count' => $generation->activeSubjects()->count(),
            'teachers_count' => $generation->subjects()->withCount('teachers')->get()->sum('teachers_count'),
            'platforms_count' => $generation->subjects()
                                           ->join('teachers', 'subjects.id', '=', 'teachers.subject_id')
                                           ->join('platforms', 'teachers.id', '=', 'platforms.teacher_id')
                                           ->count(),
            'cards_count' => $generation->educationalCards()->count(),
            'active_cards_count' => $generation->educationalCards()->where('status', 'active')->count(),
            'orders_count' => $generation->orderItems()->count(),
            'total_revenue' => $generation->orderItems()->sum('price')
        ];

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('admin.educational.generations.statistics', compact('generation', 'stats'));
    }
}