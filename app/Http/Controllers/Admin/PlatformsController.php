<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatformsController extends Controller
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
        $query = Platform::query();

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $platforms = $query->ordered()->paginate(20);

        return view('admin.platforms.index', compact('platforms'));
    }

    public function create()
    {
        return view('admin.platforms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website_url' => 'nullable|url|max:255',
            'price_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ], [
            'name.required' => 'اسم المنصة مطلوب',
            'name.max' => 'اسم المنصة لا يجب أن يتجاوز 255 حرف',
            'description.max' => 'الوصف لا يجب أن يتجاوز 1000 حرف',
            'website_url.url' => 'رابط الموقع يجب أن يكون صحيح',
            'website_url.max' => 'رابط الموقع لا يجب أن يتجاوز 255 حرف',
            'price_percentage.required' => 'نسبة السعر مطلوبة',
            'price_percentage.numeric' => 'نسبة السعر يجب أن تكون رقم',
            'price_percentage.min' => 'نسبة السعر يجب أن تكون 0 أو أكثر',
            'price_percentage.max' => 'نسبة السعر يجب أن تكون 100 أو أقل',
            'order.integer' => 'ترتيب العرض يجب أن يكون رقم صحيح',
            'order.min' => 'ترتيب العرض يجب أن يكون 0 أو أكثر'
        ]);

        Platform::create([
            'name' => $request->name,
            'description' => $request->description,
            'website_url' => $request->website_url,
            'price_percentage' => $request->price_percentage,
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.platforms.index')
            ->with('success', 'تم إنشاء المنصة بنجاح');
    }

    public function show(Platform $platform)
    {
        $platform->load(['dossiers.generation', 'dossiers.subject', 'dossiers.teacher']);
        
        $stats = [
            'total_dossiers' => $platform->dossiers()->count(),
            'active_dossiers' => $platform->dossiers()->active()->count(),
            'total_orders' => $platform->orderItems()->count(),
        ];

        return view('admin.platforms.show', compact('platform', 'stats'));
    }

    public function edit(Platform $platform)
    {
        return view('admin.platforms.edit', compact('platform'));
    }

    public function update(Request $request, Platform $platform)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website_url' => 'nullable|url|max:255',
            'price_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ], [
            'name.required' => 'اسم المنصة مطلوب',
            'name.max' => 'اسم المنصة لا يجب أن يتجاوز 255 حرف',
            'description.max' => 'الوصف لا يجب أن يتجاوز 1000 حرف',
            'website_url.url' => 'رابط الموقع يجب أن يكون صحيح',
            'website_url.max' => 'رابط الموقع لا يجب أن يتجاوز 255 حرف',
            'price_percentage.required' => 'نسبة السعر مطلوبة',
            'price_percentage.numeric' => 'نسبة السعر يجب أن تكون رقم',
            'price_percentage.min' => 'نسبة السعر يجب أن تكون 0 أو أكثر',
            'price_percentage.max' => 'نسبة السعر يجب أن تكون 100 أو أقل',
            'order.integer' => 'ترتيب العرض يجب أن يكون رقم صحيح',
            'order.min' => 'ترتيب العرض يجب أن يكون 0 أو أكثر'
        ]);

        $platform->update([
            'name' => $request->name,
            'description' => $request->description,
            'website_url' => $request->website_url,
            'price_percentage' => $request->price_percentage,
            'is_active' => $request->boolean('is_active'),
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.platforms.index')
            ->with('success', 'تم تحديث المنصة بنجاح');
    }

    public function destroy(Platform $platform)
    {
        // Check if platform has dossiers
        if ($platform->dossiers()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف المنصة لأنها تحتوي على دوسيات']);
        }

        $platform->delete();

        return redirect()->route('admin.platforms.index')
            ->with('success', 'تم حذف المنصة بنجاح');
    }

    public function toggleStatus(Platform $platform)
    {
        $platform->update([
            'is_active' => !$platform->is_active
        ]);

        $status = $platform->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        
        return back()->with('success', "{$status} المنصة بنجاح");
    }

    /**
     * Get platforms (AJAX)
     */
    public function getAll()
    {
        $platforms = Platform::active()->ordered()->get(['id', 'name', 'price_percentage']);
        return response()->json($platforms);
    }
}