<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeachersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || Auth::user()->role !== 'admin') {
                abort(403, 'Access denied. Admin only.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Teacher::query();

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Search by name or specialization
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('specialization', 'like', '%' . $search . '%');
            });
        }

        $teachers = $query->ordered()->paginate(20);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ], [
            'name.required' => 'اسم المعلم مطلوب',
            'name.max' => 'اسم المعلم لا يجب أن يتجاوز 255 حرف',
            'specialization.max' => 'التخصص لا يجب أن يتجاوز 255 حرف',
            'description.max' => 'الوصف لا يجب أن يتجاوز 1000 حرف',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 20 حرف',
            'email.email' => 'الإيميل يجب أن يكون صحيح',
            'email.max' => 'الإيميل لا يجب أن يتجاوز 255 حرف',
            'order.integer' => 'ترتيب العرض يجب أن يكون رقم صحيح',
            'order.min' => 'ترتيب العرض يجب أن يكون 0 أو أكثر'
        ]);

        Teacher::create([
            'name' => $request->name,
            'specialization' => $request->specialization,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'تم إنشاء المعلم بنجاح');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['dossiers.generation', 'dossiers.subject', 'dossiers.platform']);
        
        $stats = [
            'total_dossiers' => $teacher->dossiers()->count(),
            'active_dossiers' => $teacher->dossiers()->active()->count(),
            'total_orders' => $teacher->orderItems()->count(),
            'total_revenue' => $teacher->orderItems()->sum(DB::raw('price * quantity'))
        ];

        return view('admin.teachers.show', compact('teacher', 'stats'));
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ], [
            'name.required' => 'اسم المعلم مطلوب',
            'name.max' => 'اسم المعلم لا يجب أن يتجاوز 255 حرف',
            'specialization.max' => 'التخصص لا يجب أن يتجاوز 255 حرف',
            'description.max' => 'الوصف لا يجب أن يتجاوز 1000 حرف',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 20 حرف',
            'email.email' => 'الإيميل يجب أن يكون صحيح',
            'email.max' => 'الإيميل لا يجب أن يتجاوز 255 حرف',
            'order.integer' => 'ترتيب العرض يجب أن يكون رقم صحيح',
            'order.min' => 'ترتيب العرض يجب أن يكون 0 أو أكثر'
        ]);

        $teacher->update([
            'name' => $request->name,
            'specialization' => $request->specialization,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => $request->boolean('is_active'),
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'تم تحديث المعلم بنجاح');
    }

    public function destroy(Teacher $teacher)
    {
        // Check if teacher has dossiers
        if ($teacher->dossiers()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف المعلم لأنه يحتوي على دوسيات']);
        }

        // Check if teacher has order items
        if ($teacher->orderItems()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف المعلم لأنه موجود في طلبات']);
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'تم حذف المعلم بنجاح');
    }

    public function toggleStatus(Teacher $teacher)
    {
        $teacher->update([
            'is_active' => !$teacher->is_active
        ]);

        $status = $teacher->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        
        return back()->with('success', "{$status} المعلم بنجاح");
    }

    /**
     * Get teachers by generation and subject (AJAX)
     */
    public function getByGenerationAndSubject(Request $request, $generationId, $subjectId)
    {
        $teachers = Teacher::active()
            ->whereHas('dossiers', function($query) use ($generationId, $subjectId) {
                $query->where('generation_id', $generationId)
                      ->where('subject_id', $subjectId)
                      ->where('is_active', true);
            })
            ->ordered()
            ->get(['id', 'name', 'specialization']);

        return response()->json($teachers);
    }

    /**
     * Get all active teachers (AJAX)
     */
    public function getAll()
    {
        $teachers = Teacher::active()->ordered()->get(['id', 'name', 'specialization']);
        return response()->json($teachers);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'teachers' => 'required|array',
            'teachers.*' => 'exists:teachers,id',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        $teachers = Teacher::whereIn('id', $request->teachers);

        switch ($request->action) {
            case 'activate':
                $teachers->update(['is_active' => true]);
                $message = 'تم تفعيل المعلمين المحددين';
                break;
                
            case 'deactivate':
                $teachers->update(['is_active' => false]);
                $message = 'تم إلغاء تفعيل المعلمين المحددين';
                break;
                
            case 'delete':
                // Only delete teachers without dossiers or orders
                $teachers->whereDoesntHave('dossiers')
                        ->whereDoesntHave('orderItems')
                        ->delete();
                $message = 'تم حذف المعلمين المحددين (عدا من له دوسيات أو طلبات)';
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * Export teachers to CSV
     */
    public function export()
    {
        $teachers = Teacher::with(['dossiers', 'orderItems'])->ordered()->get();

        $csvData = "ID,الاسم,التخصص,الوصف,الهاتف,الإيميل,الحالة,عدد الدوسيات,عدد الطلبات,تاريخ الإنشاء\n";

        foreach ($teachers as $teacher) {
            $csvData .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",%d,%d,\"%s\"\n",
                $teacher->id,
                $teacher->name,
                $teacher->specialization ?? '',
                $teacher->description ?? '',
                $teacher->phone ?? '',
                $teacher->email ?? '',
                $teacher->status_text,
                $teacher->dossiers->count(),
                $teacher->orderItems->count(),
                $teacher->created_at->format('Y-m-d H:i:s')
            );
        }

        $filename = 'teachers_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response($csvData, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Encoding' => 'UTF-8',
            'BOM' => "\xEF\xBB\xBF"
        ]);
    }
}