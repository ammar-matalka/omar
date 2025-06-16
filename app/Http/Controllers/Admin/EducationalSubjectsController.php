<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducationalSubject;
use App\Models\EducationalGeneration;

class EducationalSubjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = EducationalSubject::with('generation');

        // Filter by generation
        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
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

        $subjects = $query->orderBy('generation_id', 'desc')
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(20);

        $generations = EducationalGeneration::active()->ordered()->get();

        return view('admin.educational-subjects.index', compact('subjects', 'generations'));
    }

    public function create()
    {
        $generations = EducationalGeneration::active()->ordered()->get();
        return view('admin.educational-subjects.create', compact('generations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'generation_id' => 'required|exists:educational_generations,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999.99',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ], [
            'generation_id.required' => 'يرجى اختيار الجيل',
            'generation_id.exists' => 'الجيل المحدد غير موجود',
            'name.required' => 'اسم المادة مطلوب',
            'name.max' => 'اسم المادة لا يجب أن يتجاوز 255 حرف',
            'price.required' => 'سعر المادة مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر يجب أن يكون 0 أو أكثر',
            'price.max' => 'السعر لا يجب أن يتجاوز 999999.99',
            'order.integer' => 'ترتيب العرض يجب أن يكون رقم صحيح',
            'order.min' => 'ترتيب العرض يجب أن يكون 0 أو أكثر'
        ]);

        EducationalSubject::create([
            'generation_id' => $request->generation_id,
            'name' => $request->name,
            'price' => $request->price,
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.educational-subjects.index')
            ->with('success', 'تم إنشاء المادة بنجاح');
    }

    public function show(EducationalSubject $educationalSubject)
    {
        $educationalSubject->load('generation');
        
        $orderStats = [
            'total_orders' => $educationalSubject->orderItems()->count(),
            'total_quantity' => $educationalSubject->orderItems()->sum('quantity'),
            'total_revenue' => $educationalSubject->orderItems()->sum(\DB::raw('price * quantity'))
        ];

        return view('admin.educational-subjects.show', compact('educationalSubject', 'orderStats'));
    }

    public function edit(EducationalSubject $educationalSubject)
    {
        $generations = EducationalGeneration::active()->ordered()->get();
        return view('admin.educational-subjects.edit', compact('educationalSubject', 'generations'));
    }

    public function update(Request $request, EducationalSubject $educationalSubject)
    {
        $request->validate([
            'generation_id' => 'required|exists:educational_generations,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999.99',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ], [
            'generation_id.required' => 'يرجى اختيار الجيل',
            'generation_id.exists' => 'الجيل المحدد غير موجود',
            'name.required' => 'اسم المادة مطلوب',
            'name.max' => 'اسم المادة لا يجب أن يتجاوز 255 حرف',
            'price.required' => 'سعر المادة مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر يجب أن يكون 0 أو أكثر',
            'price.max' => 'السعر لا يجب أن يتجاوز 999999.99',
            'order.integer' => 'ترتيب العرض يجب أن يكون رقم صحيح',
            'order.min' => 'ترتيب العرض يجب أن يكون 0 أو أكثر'
        ]);

        $educationalSubject->update([
            'generation_id' => $request->generation_id,
            'name' => $request->name,
            'price' => $request->price,
            'is_active' => $request->boolean('is_active'),
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.educational-subjects.index')
            ->with('success', 'تم تحديث المادة بنجاح');
    }

    public function destroy(EducationalSubject $educationalSubject)
    {
        // Check if subject has order items
        if ($educationalSubject->orderItems()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف المادة لأنها موجودة في طلبات']);
        }

        $educationalSubject->delete();

        return redirect()->route('admin.educational-subjects.index')
            ->with('success', 'تم حذف المادة بنجاح');
    }

    public function toggleStatus(EducationalSubject $educationalSubject)
    {
        $educationalSubject->update([
            'is_active' => !$educationalSubject->is_active
        ]);

        $status = $educationalSubject->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        
        return back()->with('success', "{$status} المادة بنجاح");
    }

    /**
     * Get subjects by generation (AJAX)
     */
    public function getByGeneration(Request $request, $generationId)
    {
        $subjects = EducationalSubject::active()
            ->forGeneration($generationId)
            ->ordered()
            ->get(['id', 'name', 'price']);

        return response()->json($subjects);
    }
}