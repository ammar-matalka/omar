<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducationalGeneration;

class GenerationsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $generations = EducationalGeneration::withCount('subjects', 'orders')
            ->orderBy('order', 'asc')
            ->orderBy('year', 'desc')
            ->paginate(20);

        return view('admin.generations.index', compact('generations'));
    }

    public function create()
    {
        return view('admin.generations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:2050|unique:educational_generations,year',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ], [
            'name.required' => 'اسم الجيل مطلوب',
            'name.max' => 'اسم الجيل لا يجب أن يتجاوز 255 حرف',
            'year.required' => 'سنة الجيل مطلوبة',
            'year.integer' => 'السنة يجب أن تكون رقم صحيح',
            'year.min' => 'السنة يجب أن تكون 2000 أو أكثر',
            'year.max' => 'السنة يجب أن تكون 2050 أو أقل',
            'year.unique' => 'هذه السنة موجودة بالفعل',
            'description.max' => 'الوصف لا يجب أن يتجاوز 1000 حرف',
            'order.integer' => 'ترتيب العرض يجب أن يكون رقم صحيح',
            'order.min' => 'ترتيب العرض يجب أن يكون 0 أو أكثر'
        ]);

        EducationalGeneration::create([
            'name' => $request->name,
            'year' => $request->year,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.generations.index')
            ->with('success', 'تم إنشاء الجيل بنجاح');
    }

    public function show(EducationalGeneration $generation)
    {
        $generation->load(['subjects' => function($query) {
            $query->orderBy('order', 'asc')->orderBy('name', 'asc');
        }]);

        $orderStats = [
            'total_orders' => $generation->orders()->count(),
            'pending_orders' => $generation->orders()->pending()->count(),
            'completed_orders' => $generation->orders()->completed()->count(),
            'total_revenue' => $generation->orders()->completed()->sum('total_amount')
        ];

        return view('admin.generations.show', compact('generation', 'orderStats'));
    }

    public function edit(EducationalGeneration $generation)
    {
        return view('admin.generations.edit', compact('generation'));
    }

    public function update(Request $request, EducationalGeneration $generation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:2050|unique:educational_generations,year,' . $generation->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ], [
            'name.required' => 'اسم الجيل مطلوب',
            'name.max' => 'اسم الجيل لا يجب أن يتجاوز 255 حرف',
            'year.required' => 'سنة الجيل مطلوبة',
            'year.integer' => 'السنة يجب أن تكون رقم صحيح',
            'year.min' => 'السنة يجب أن تكون 2000 أو أكثر',
            'year.max' => 'السنة يجب أن تكون 2050 أو أقل',
            'year.unique' => 'هذه السنة موجودة بالفعل',
            'description.max' => 'الوصف لا يجب أن يتجاوز 1000 حرف',
            'order.integer' => 'ترتيب العرض يجب أن يكون رقم صحيح',
            'order.min' => 'ترتيب العرض يجب أن يكون 0 أو أكثر'
        ]);

        $generation->update([
            'name' => $request->name,
            'year' => $request->year,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.generations.index')
            ->with('success', 'تم تحديث الجيل بنجاح');
    }

    public function destroy(EducationalGeneration $generation)
    {
        // Check if generation has orders
        if ($generation->orders()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف الجيل لأنه يحتوي على طلبات']);
        }

        $generation->delete();

        return redirect()->route('admin.generations.index')
            ->with('success', 'تم حذف الجيل بنجاح');
    }

    public function toggleStatus(EducationalGeneration $generation)
    {
        $generation->update([
            'is_active' => !$generation->is_active
        ]);

        $status = $generation->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        
        return back()->with('success', "{$status} الجيل بنجاح");
    }
}