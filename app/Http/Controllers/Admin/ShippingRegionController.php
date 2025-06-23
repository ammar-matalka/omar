<?php
// app/Http/Controllers/Admin/ShippingRegionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRegion;
use Illuminate\Http\Request;

class ShippingRegionController extends Controller
{
    /**
     * عرض قائمة مناطق الشحن
     */
    public function index(Request $request)
    {
        $query = ShippingRegion::query();

        // فلترة حسب الحالة
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // فلترة حسب نوع الشحن
        if ($request->filled('shipping_type')) {
            if ($request->shipping_type === 'free') {
                $query->where('shipping_cost', 0);
            } elseif ($request->shipping_type === 'paid') {
                $query->where('shipping_cost', '>', 0);
            }
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // ترتيب
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');
        
        if (in_array($sortBy, ['name', 'shipping_cost', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('name', 'asc');
        }

        $regions = $query->withCount('orderItems')->paginate(15);

        // إحصائيات
        $stats = [
            'total_regions' => ShippingRegion::count(),
            'active_regions' => ShippingRegion::where('is_active', true)->count(),
            'free_shipping_regions' => ShippingRegion::where('shipping_cost', 0)->count(),
            'paid_shipping_regions' => ShippingRegion::where('shipping_cost', '>', 0)->count(),
            'average_shipping_cost' => ShippingRegion::where('shipping_cost', '>', 0)->avg('shipping_cost')
        ];

        return view('admin.educational.regions.index', compact('regions', 'stats'));
    }

    /**
     * عرض نموذج إنشاء منطقة شحن جديدة
     */
    public function create()
    {
        return view('admin.educational.regions.create');
    }

    /**
     * حفظ منطقة شحن جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipping_regions,name',
            'shipping_cost' => 'required|numeric|min:0|max:999.99',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'اسم المنطقة مطلوب',
            'name.max' => 'اسم المنطقة لا يجب أن يتجاوز 255 حرف',
            'name.unique' => 'اسم المنطقة موجود بالفعل',
            'shipping_cost.required' => 'تكلفة الشحن مطلوبة',
            'shipping_cost.numeric' => 'تكلفة الشحن يجب أن تكون رقم',
            'shipping_cost.min' => 'تكلفة الشحن لا يمكن أن تكون سالبة',
            'shipping_cost.max' => 'تكلفة الشحن لا يمكن أن تتجاوز 999.99 دينار'
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        ShippingRegion::create($validated);

        return redirect()->route('admin.educational.regions.index')
                        ->with('success', 'تم إنشاء منطقة الشحن بنجاح');
    }

    /**
     * عرض تفاصيل منطقة شحن محددة
     */
    public function show(ShippingRegion $region)
    {
        $region->load('orderItems.order.user');
        
        // إحصائيات المنطقة
        $stats = [
            'orders_count' => $region->orderItems()->count(),
            'total_revenue' => $region->orderItems()->sum('price'),
            'total_shipping_revenue' => $region->orderItems()->sum('shipping_cost'),
            'unique_customers' => $region->orderItems()
                                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                        ->distinct('orders.user_id')
                                        ->count('orders.user_id')
        ];
        
        return view('admin.educational.regions.show', compact('region', 'stats'));
    }

    /**
     * عرض نموذج تعديل منطقة شحن
     */
    public function edit(ShippingRegion $region)
    {
        return view('admin.educational.regions.edit', compact('region'));
    }

    /**
     * تحديث منطقة شحن محددة
     */
    public function update(Request $request, ShippingRegion $region)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipping_regions,name,' . $region->id,
            'shipping_cost' => 'required|numeric|min:0|max:999.99',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'اسم المنطقة مطلوب',
            'name.max' => 'اسم المنطقة لا يجب أن يتجاوز 255 حرف',
            'name.unique' => 'اسم المنطقة موجود بالفعل',
            'shipping_cost.required' => 'تكلفة الشحن مطلوبة',
            'shipping_cost.numeric' => 'تكلفة الشحن يجب أن تكون رقم',
            'shipping_cost.min' => 'تكلفة الشحن لا يمكن أن تكون سالبة',
            'shipping_cost.max' => 'تكلفة الشحن لا يمكن أن تتجاوز 999.99 دينار'
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $region->update($validated);

        return redirect()->route('admin.educational.regions.index')
                        ->with('success', 'تم تحديث منطقة الشحن بنجاح');
    }

    /**
     * حذف منطقة شحن محددة
     */
    public function destroy(ShippingRegion $region)
    {
        // التحقق من وجود تسعير مرتبط
        if ($region->educationalPricing()->exists()) {
            return redirect()->route('admin.educational.regions.index')
                            ->with('error', 'لا يمكن حذف المنطقة لوجود تسعير مرتبط بها');
        }

        // التحقق من وجود طلبات مرتبطة
        if ($region->orderItems()->exists()) {
            return redirect()->route('admin.educational.regions.index')
                            ->with('error', 'لا يمكن حذف المنطقة لوجود طلبات مرتبطة بها');
        }

        $region->delete();

        return redirect()->route('admin.educational.regions.index')
                        ->with('success', 'تم حذف منطقة الشحن بنجاح');
    }

    /**
     * تبديل حالة التفعيل
     */
    public function toggleStatus(ShippingRegion $region)
    {
        $region->update(['is_active' => !$region->is_active]);

        $status = $region->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$status} المنطقة بنجاح",
                'is_active' => $region->is_active
            ]);
        }

        return redirect()->back()->with('success', "{$status} المنطقة بنجاح");
    }

    /**
     * تحديث جماعي لأسعار الشحن
     */
    public function bulkUpdateShipping(Request $request)
    {
        $validated = $request->validate([
            'regions' => 'required|array|min:1',
            'regions.*' => 'exists:shipping_regions,id',
            'action' => 'required|in:update_cost,set_free,add_percentage',
            'shipping_cost' => 'required_if:action,update_cost|nullable|numeric|min:0|max:999.99',
            'percentage' => 'required_if:action,add_percentage|nullable|numeric|min:-100|max:500'
        ], [
            'regions.required' => 'يجب اختيار منطقة واحدة على الأقل',
            'regions.min' => 'يجب اختيار منطقة واحدة على الأقل',
            'action.required' => 'نوع العملية مطلوب',
            'shipping_cost.required_if' => 'تكلفة الشحن مطلوبة للتحديث المباشر',
            'percentage.required_if' => 'النسبة المئوية مطلوبة'
        ]);

        $regions = ShippingRegion::whereIn('id', $validated['regions'])->get();
        $updated = 0;

        foreach ($regions as $region) {
            switch ($validated['action']) {
                case 'update_cost':
                    $region->update(['shipping_cost' => $validated['shipping_cost']]);
                    $updated++;
                    break;
                    
                case 'set_free':
                    $region->update(['shipping_cost' => 0]);
                    $updated++;
                    break;
                    
                case 'add_percentage':
                    $newCost = $region->shipping_cost * (1 + ($validated['percentage'] / 100));
                    $newCost = max(0, min(999.99, $newCost)); // التأكد من البقاء ضمن الحدود
                    $region->update(['shipping_cost' => round($newCost, 2)]);
                    $updated++;
                    break;
            }
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "تم تحديث {$updated} منطقة بنجاح",
                'updated_count' => $updated
            ]);
        }

        return redirect()->back()->with('success', "تم تحديث {$updated} منطقة بنجاح");
    }

    /**
     * تفعيل أو إلغاء تفعيل مناطق متعددة
     */
    public function bulkToggleStatus(Request $request)
    {
        $validated = $request->validate([
            'regions' => 'required|array|min:1',
            'regions.*' => 'exists:shipping_regions,id',
            'status' => 'required|boolean'
        ], [
            'regions.required' => 'يجب اختيار منطقة واحدة على الأقل',
            'regions.min' => 'يجب اختيار منطقة واحدة على الأقل',
            'status.required' => 'حالة التفعيل مطلوبة'
        ]);

        $updated = ShippingRegion::whereIn('id', $validated['regions'])
                                ->update(['is_active' => $validated['status']]);

        $action = $validated['status'] ? 'تفعيل' : 'إلغاء تفعيل';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "تم {$action} {$updated} منطقة بنجاح",
                'updated_count' => $updated
            ]);
        }

        return redirect()->back()->with('success', "تم {$action} {$updated} منطقة بنجاح");
    }

    /**
     * تصدير مناطق الشحن إلى Excel
     */
    public function export(Request $request)
    {
        $query = ShippingRegion::query();

        // تطبيق نفس الفلاتر من الـ index
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('shipping_type')) {
            if ($request->shipping_type === 'free') {
                $query->where('shipping_cost', 0);
            } elseif ($request->shipping_type === 'paid') {
                $query->where('shipping_cost', '>', 0);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $regions = $query->withCount('orderItems')->get();

        // يمكنك استخدام مكتبة مثل Laravel Excel هنا
        // return Excel::download(new ShippingRegionsExport($regions), 'shipping-regions.xlsx');

        // مؤقتاً، نرجع JSON للتصدير
        return response()->json([
            'success' => true,
            'data' => $regions,
            'message' => 'سيتم تنزيل الملف قريباً'
        ]);
    }
}