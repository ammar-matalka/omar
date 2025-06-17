<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\EducationalGeneration;
use App\Models\EducationalSubject;
use App\Models\Teacher;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DossiersController extends Controller
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
        $query = Dossier::with(['generation', 'subject', 'teacher', 'platform']);

        // Filter by generation
        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by platform
        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->platform_id);
        }

        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $dossiers = $query->ordered()->paginate(20);

        // Get filter options
        $generations = EducationalGeneration::active()->ordered()->get();
        $subjects = EducationalSubject::active()->ordered()->get();
        $teachers = Teacher::active()->ordered()->get();
        $platforms = Platform::active()->ordered()->get();

        return view('admin.dossiers.index', compact('dossiers', 'generations', 'subjects', 'teachers', 'platforms'));
    }

    public function create()
    {
        $generations = EducationalGeneration::active()->ordered()->get();
        $subjects = EducationalSubject::active()->ordered()->get();
        $teachers = Teacher::active()->ordered()->get();
        $platforms = Platform::active()->ordered()->get();

        return view('admin.dossiers.create', compact('generations', 'subjects', 'teachers', 'platforms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'generation_id' => 'required|exists:educational_generations,id',
            'subject_id' => 'required|exists:educational_subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'platform_id' => 'required|exists:platforms,id',
            'semester' => 'required|in:first,second,both',
            'price' => 'required|numeric|min:0|max:999999.99',
            'description' => 'nullable|string|max:1000',
            'pages_count' => 'nullable|integer|min:1',
            'file_size' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ], [
            'name.required' => 'اسم الدوسية مطلوب',
            'name.max' => 'اسم الدوسية لا يجب أن يتجاوز 255 حرف',
            'generation_id.required' => 'يرجى اختيار الجيل',
            'generation_id.exists' => 'الجيل المحدد غير موجود',
            'subject_id.required' => 'يرجى اختيار المادة',
            'subject_id.exists' => 'المادة المحددة غير موجودة',
            'teacher_id.required' => 'يرجى اختيار المعلم',
            'teacher_id.exists' => 'المعلم المحدد غير موجود',
            'platform_id.required' => 'يرجى اختيار المنصة',
            'platform_id.exists' => 'المنصة المحددة غير موجودة',
            'semester.required' => 'يرجى اختيار الفصل',
            'semester.in' => 'قيمة الفصل غير صحيحة',
            'price.required' => 'سعر الدوسية مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر يجب أن يكون 0 أو أكثر',
            'price.max' => 'السعر لا يجب أن يتجاوز 999999.99',
            'description.max' => 'الوصف لا يجب أن يتجاوز 1000 حرف',
            'pages_count.integer' => 'عدد الصفحات يجب أن يكون رقم صحيح',
            'pages_count.min' => 'عدد الصفحات يجب أن يكون 1 أو أكثر',
            'file_size.max' => 'حجم الملف لا يجب أن يتجاوز 50 حرف',
            'order.integer' => 'ترتيب العرض يجب أن يكون رقم صحيح',
            'order.min' => 'ترتيب العرض يجب أن يكون 0 أو أكثر'
        ]);

        Dossier::create([
            'name' => $request->name,
            'generation_id' => $request->generation_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'platform_id' => $request->platform_id,
            'semester' => $request->semester,
            'price' => $request->price,
            'description' => $request->description,
            'pages_count' => $request->pages_count,
            'file_size' => $request->file_size,
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.dossiers.index')
            ->with('success', 'تم إنشاء الدوسية بنجاح');
    }

    public function show(Dossier $dossier)
    {
        $dossier->load(['generation', 'subject', 'teacher', 'platform']);
        
        $stats = [
            'total_orders' => $dossier->orderItems()->count(),
            'total_quantity' => $dossier->orderItems()->sum('quantity'),
            'total_revenue' => $dossier->orderItems()->sum(DB::raw('price * quantity'))
        ];

        return view('admin.dossiers.show', compact('dossier', 'stats'));
    }

    public function edit(Dossier $dossier)
    {
        $generations = EducationalGeneration::active()->ordered()->get();
        $subjects = EducationalSubject::active()->ordered()->get();
        $teachers = Teacher::active()->ordered()->get();
        $platforms = Platform::active()->ordered()->get();

        return view('admin.dossiers.edit', compact('dossier', 'generations', 'subjects', 'teachers', 'platforms'));
    }

    public function update(Request $request, Dossier $dossier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'generation_id' => 'required|exists:educational_generations,id',
            'subject_id' => 'required|exists:educational_subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'platform_id' => 'required|exists:platforms,id',
            'semester' => 'required|in:first,second,both',
            'price' => 'required|numeric|min:0|max:999999.99',
            'description' => 'nullable|string|max:1000',
            'pages_count' => 'nullable|integer|min:1',
            'file_size' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ]);

        $dossier->update([
            'name' => $request->name,
            'generation_id' => $request->generation_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'platform_id' => $request->platform_id,
            'semester' => $request->semester,
            'price' => $request->price,
            'description' => $request->description,
            'pages_count' => $request->pages_count,
            'file_size' => $request->file_size,
            'is_active' => $request->boolean('is_active'),
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.dossiers.index')
            ->with('success', 'تم تحديث الدوسية بنجاح');
    }

    public function destroy(Dossier $dossier)
    {
        // Check if dossier has orders
        if ($dossier->orderItems()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف الدوسية لأنها موجودة في طلبات']);
        }

        $dossier->delete();

        return redirect()->route('admin.dossiers.index')
            ->with('success', 'تم حذف الدوسية بنجاح');
    }

    public function toggleStatus(Dossier $dossier)
    {
        $dossier->update([
            'is_active' => !$dossier->is_active
        ]);

        $status = $dossier->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        
        return back()->with('success', "{$status} الدوسية بنجاح");
    }

    /**
     * Get dossiers by filters (AJAX)
     */
    public function getFiltered(Request $request)
    {
        $query = Dossier::active()->with(['teacher', 'platform']);

        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->platform_id);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $dossiers = $query->ordered()->get()->map(function ($dossier) {
            return [
                'id' => $dossier->id,
                'name' => $dossier->name,
                'price' => $dossier->price,
                'final_price' => $dossier->final_price,
                'formatted_final_price' => $dossier->formatted_final_price,
                'teacher_name' => $dossier->teacher->name,
                'platform_name' => $dossier->platform->name,
                'display_name' => $dossier->display_name
            ];
        });

        return response()->json($dossiers);
    }
}