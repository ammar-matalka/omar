<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct()
    {
        if (class_exists('App\Services\CouponService')) {
            $this->couponService = app(CouponService::class);
        }
    }

    /**
     * عرض قائمة الكوبونات
     */
    public function index(Request $request)
    {
        $query = Coupon::with('user', 'order');

        // فلترة حسب الحالة
        if ($request->has('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_used', false)->where('valid_until', '>=', now());
                    break;
                case 'used':
                    $query->where('is_used', true);
                    break;
                case 'expired':
                    $query->where('is_used', false)->where('valid_until', '<', now());
                    break;
            }
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // ترتيب
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $coupons = $query->paginate(15);

        // إحصائيات
        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_used', false)->where('valid_until', '>=', now())->count(),
            'used' => Coupon::where('is_used', true)->count(),
            'expired' => Coupon::where('is_used', false)->where('valid_until', '<', now())->count(),
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    /**
     * عرض نموذج إنشاء كوبون
     */
    public function create()
    {
        $users = User::select('id', 'name', 'email')->get();
        return view('admin.coupons.create', compact('users'));
    }

    /**
     * حفظ كوبون جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:50|unique:coupons,code',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:fixed,percentage',
            'user_id' => 'required|exists:users,id',
            'valid_until' => 'required|date|after:today',
            'minimum_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $data = $request->all();
            
            // توليد كود إذا لم يتم إدخاله
            if (empty($data['code'])) {
                $data['code'] = 'ADMIN-' . strtoupper(Str::random(8));
                // التأكد من عدم تكرار الكود
                while (Coupon::where('code', $data['code'])->exists()) {
                    $data['code'] = 'ADMIN-' . strtoupper(Str::random(8));
                }
            }

            Coupon::create($data);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'تم إنشاء الكوبون بنجاح!');

        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء الكوبون', [
                'message' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->withInput()
                ->with('error', 'حدث خطأ في إنشاء الكوبون.');
        }
    }

    /**
     * عرض تفاصيل كوبون
     */
    public function show(Coupon $coupon)
    {
        $coupon->load('user', 'order', 'generatedFromOrder');
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * عرض نموذج تعديل كوبون
     */
    public function edit(Coupon $coupon)
    {
        $users = User::select('id', 'name', 'email')->get();
        return view('admin.coupons.edit', compact('coupon', 'users'));
    }

    /**
     * تحديث كوبون
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:fixed,percentage',
            'user_id' => 'required|exists:users,id',
            'valid_until' => 'required|date',
            'minimum_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $coupon->update($request->all());

            return redirect()->route('admin.coupons.index')
                ->with('success', 'تم تحديث الكوبون بنجاح!');

        } catch (\Exception $e) {
            Log::error('خطأ في تحديث الكوبون', [
                'message' => $e->getMessage(),
                'coupon_id' => $coupon->id,
                'data' => $request->all()
            ]);

            return back()->withInput()
                ->with('error', 'حدث خطأ في تحديث الكوبون.');
        }
    }

    /**
     * حذف كوبون
     */
    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();

            return redirect()->route('admin.coupons.index')
                ->with('success', 'تم حذف الكوبون بنجاح!');

        } catch (\Exception $e) {
            Log::error('خطأ في حذف الكوبون', [
                'message' => $e->getMessage(),
                'coupon_id' => $coupon->id
            ]);

            return back()->with('error', 'حدث خطأ في حذف الكوبون.');
        }
    }

    /**
     * عرض صفحة توليد كوبونات متعددة
     */
    public function generateMultiple()
    {
        $users = User::select('id', 'name', 'email')->get();
        return view('admin.coupons.generate', compact('users'));
    }

    /**
     * توليد كوبونات متعددة
     */
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:100',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:fixed,percentage',
            'valid_until' => 'required|date|after:today',
            'minimum_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'prefix' => 'nullable|string|max:10',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            $createdCoupons = [];
            $prefix = $request->prefix ?: 'BULK';
            
            for ($i = 0; $i < $request->count; $i++) {
                foreach ($request->user_ids as $userId) {
                    // توليد كود فريد
                    do {
                        $code = $prefix . '-' . strtoupper(Str::random(6));
                    } while (Coupon::where('code', $code)->exists());

                    $coupon = Coupon::create([
                        'code' => $code,
                        'amount' => $request->amount,
                        'type' => $request->type,
                        'user_id' => $userId,
                        'valid_until' => $request->valid_until,
                        'minimum_amount' => $request->minimum_amount,
                        'max_discount_amount' => $request->max_discount_amount,
                        'description' => "كوبون مُولد تلقائياً - دفعة {$prefix}",
                    ]);

                    $createdCoupons[] = $coupon;
                }
            }

            return redirect()->route('admin.coupons.index')
                ->with('success', 'تم توليد ' . count($createdCoupons) . ' كوبون بنجاح!');

        } catch (\Exception $e) {
            Log::error('خطأ في توليد الكوبونات المتعددة', [
                'message' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->withInput()
                ->with('error', 'حدث خطأ في توليد الكوبونات.');
        }
    }
}