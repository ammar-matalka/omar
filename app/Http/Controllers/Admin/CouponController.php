<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    /**
     * عرض قائمة القسائم.
     */
    public function index()
    {
        $activeCoupons = Coupon::with('user')
            ->where('is_used', false)
            ->where('valid_until', '>=', now())
            ->latest()
            ->get();
            
        $usedCoupons = Coupon::with(['user', 'order'])
            ->where('is_used', true)
            ->latest()
            ->get();
            
        $expiredCoupons = Coupon::with('user')
            ->where('is_used', false)
            ->where('valid_until', '<', now())
            ->latest()
            ->get();
            
        return view('admin.coupons.index', compact(
            'activeCoupons',
            'usedCoupons',
            'expiredCoupons'
        ));
    }

    /**
     * عرض نموذج إنشاء قسيمة جديدة.
     */
    public function create()
    {
        // إصلاح: تغيير 'user' إلى 'customer' لتطابق قاعدة البيانات
        $users = User::where('role', 'customer')->get();
        return view('admin.coupons.create', compact('users'));
    }

    /**
     * حفظ قسيمة جديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id', // جعل user_id اختياري للقسائم العامة
            'amount' => 'required|numeric|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'valid_months' => 'required|integer|min:1',
            'code' => 'nullable|string|unique:coupons,code',
        ], [
            'user_id.exists' => 'المستخدم المحدد غير موجود.',
            'amount.required' => 'قيمة القسيمة مطلوبة.',
            'amount.numeric' => 'قيمة القسيمة يجب أن تكون رقم.',
            'amount.min' => 'قيمة القسيمة يجب أن تكون على الأقل 1.',
            'min_purchase_amount.numeric' => 'الحد الأدنى للشراء يجب أن يكون رقم.',
            'min_purchase_amount.min' => 'الحد الأدنى للشراء لا يمكن أن يكون سالب.',
            'valid_months.required' => 'مدة صلاحية القسيمة مطلوبة.',
            'valid_months.integer' => 'مدة الصلاحية يجب أن تكون رقم صحيح.',
            'valid_months.min' => 'مدة الصلاحية يجب أن تكون شهر واحد على الأقل.',
            'code.string' => 'رمز القسيمة يجب أن يكون نص.',
            'code.unique' => 'رمز القسيمة موجود مسبقاً.',
        ]);
        
        // تحويل إلى أعداد صحيحة لتجنب مشاكل Carbon
        $validMonths = (int) $validated['valid_months'];
        $amount = (float) $validated['amount'];
        $minPurchaseAmount = isset($validated['min_purchase_amount']) ? (float) $validated['min_purchase_amount'] : 0;
        
        // توليد رمز قسيمة فريد إذا لم يتم توفيره
        if (empty($validated['code'])) {
            $code = strtoupper(Str::random(8));
            
            // التأكد من أن الرمز فريد
            while (Coupon::where('code', $code)->exists()) {
                $code = strtoupper(Str::random(8));
            }
        } else {
            $code = $validated['code'];
        }
        
        Coupon::create([
            'code' => $code,
            'amount' => $amount,
            'min_purchase_amount' => $minPurchaseAmount,
            'valid_from' => now(),
            'valid_until' => now()->addMonths($validMonths),
            'is_used' => false,
            'user_id' => $validated['user_id'] ?? null, // السماح بـ null للقسائم العامة
            'order_id' => null,
        ]);
        
        return redirect()->route('admin.coupons.index')
            ->with('success', 'تم إنشاء القسيمة بنجاح.');
    }

    /**
     * عرض القسيمة المحددة.
     */
    public function show(Coupon $coupon)
    {
        $coupon->load(['user', 'order']);
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * عرض نموذج تعديل القسيمة المحددة.
     */
    public function edit(Coupon $coupon)
    {
        // عدم السماح بتعديل القسائم المستخدمة
        if ($coupon->is_used) {
            return redirect()->route('admin.coupons.show', $coupon)
                ->with('error', 'لا يمكن تعديل القسائم المستخدمة.');
        }
        
        // إصلاح: تغيير 'user' إلى 'customer'
        $users = User::where('role', 'customer')->get();
        return view('admin.coupons.edit', compact('coupon', 'users'));
    }

    /**
     * تحديث القسيمة المحددة في قاعدة البيانات.
     */
    public function update(Request $request, Coupon $coupon)
    {
        // عدم السماح بتحديث القسائم المستخدمة
        if ($coupon->is_used) {
            return redirect()->route('admin.coupons.show', $coupon)
                ->with('error', 'لا يمكن تحديث القسائم المستخدمة.');
        }
        
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id', // جعله اختياري
            'amount' => 'required|numeric|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'valid_until' => 'required|date|after:today',
            'code' => 'required|string|unique:coupons,code,'.$coupon->id,
        ], [
            'user_id.exists' => 'المستخدم المحدد غير موجود.',
            'amount.required' => 'قيمة القسيمة مطلوبة.',
            'amount.numeric' => 'قيمة القسيمة يجب أن تكون رقم.',
            'amount.min' => 'قيمة القسيمة يجب أن تكون على الأقل 1.',
            'min_purchase_amount.numeric' => 'الحد الأدنى للشراء يجب أن يكون رقم.',
            'min_purchase_amount.min' => 'الحد الأدنى للشراء لا يمكن أن يكون سالب.',
            'valid_until.required' => 'تاريخ انتهاء الصلاحية مطلوب.',
            'valid_until.date' => 'تاريخ انتهاء الصلاحية يجب أن يكون تاريخ صحيح.',
            'valid_until.after' => 'تاريخ انتهاء الصلاحية يجب أن يكون بعد اليوم.',
            'code.required' => 'رمز القسيمة مطلوب.',
            'code.string' => 'رمز القسيمة يجب أن يكون نص.',
            'code.unique' => 'رمز القسيمة موجود مسبقاً.',
        ]);
        
        $coupon->update([
            'code' => $validated['code'],
            'amount' => $validated['amount'],
            'min_purchase_amount' => $validated['min_purchase_amount'] ?? 0,
            'valid_until' => $validated['valid_until'],
            'user_id' => $validated['user_id'] ?? null,
        ]);
        
        return redirect()->route('admin.coupons.show', $coupon)
            ->with('success', 'تم تحديث القسيمة بنجاح.');
    }

    /**
     * حذف القسيمة المحددة من قاعدة البيانات.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        
        return redirect()->route('admin.coupons.index')
            ->with('success', 'تم حذف القسيمة بنجاح.');
    }
    
    /**
     * توليد عدة قسائم في مرة واحدة.
     */
    public function generateMultiple()
    {
        // إصلاح: تغيير 'user' إلى 'customer'
        $users = User::where('role', 'customer')->get();
        return view('admin.coupons.generate', compact('users'));
    }
    
    /**
     * حفظ عدة قسائم.
     */
    public function storeMultiple(Request $request)
    {
        // التحقق إذا كانت قسائم عامة
        $isGeneral = $request->has('generate_for_all') && $request->generate_for_all == '1';
        
        $rules = [
            'amount' => 'required|numeric|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'valid_months' => 'required|integer|min:1',
            'quantity_per_user' => 'required|integer|min:1|max:100',
        ];
        
        // طلب user_ids فقط إذا لم تكن قسائم عامة
        if (!$isGeneral) {
            $rules['user_ids'] = 'required|array';
            $rules['user_ids.*'] = 'exists:users,id';
        }
        
        $validated = $request->validate($rules, [
            'amount.required' => 'قيمة القسيمة مطلوبة.',
            'amount.numeric' => 'قيمة القسيمة يجب أن تكون رقم.',
            'amount.min' => 'قيمة القسيمة يجب أن تكون على الأقل 1.',
            'min_purchase_amount.numeric' => 'الحد الأدنى للشراء يجب أن يكون رقم.',
            'min_purchase_amount.min' => 'الحد الأدنى للشراء لا يمكن أن يكون سالب.',
            'valid_months.required' => 'مدة صلاحية القسيمة مطلوبة.',
            'valid_months.integer' => 'مدة الصلاحية يجب أن تكون رقم صحيح.',
            'valid_months.min' => 'مدة الصلاحية يجب أن تكون شهر واحد على الأقل.',
            'quantity_per_user.required' => 'عدد القسائم لكل مستخدم مطلوب.',
            'quantity_per_user.integer' => 'عدد القسائم يجب أن يكون رقم صحيح.',
            'quantity_per_user.min' => 'يجب إنشاء قسيمة واحدة على الأقل.',
            'quantity_per_user.max' => 'لا يمكن إنشاء أكثر من 100 قسيمة لكل مستخدم.',
            'user_ids.required' => 'يرجى اختيار المستخدمين.',
            'user_ids.array' => 'المستخدمون يجب أن يكونوا في شكل مصفوفة.',
            'user_ids.*.exists' => 'أحد المستخدمين المحددين غير موجود.',
        ]);
        
        // تحويل إلى الأنواع المناسبة لتجنب مشاكل Carbon
        $validMonths = (int) $validated['valid_months'];
        $amount = (float) $validated['amount'];
        $minPurchaseAmount = isset($validated['min_purchase_amount']) ? (float) $validated['min_purchase_amount'] : 0;
        $quantityPerUser = (int) $validated['quantity_per_user'];
        
        DB::beginTransaction();
        
        try {
            $couponsCreated = 0;
            
            // التحقق إذا كانت للقسائم العامة (جميع المستخدمين)
            if ($isGeneral) {
                // توليد قسائم عامة (غير مرتبطة بمستخدمين محددين)
                for ($i = 0; $i < $quantityPerUser; $i++) {
                    $code = strtoupper(Str::random(8));
                    
                    while (Coupon::where('code', $code)->exists()) {
                        $code = strtoupper(Str::random(8));
                    }
                    
                    Coupon::create([
                        'code' => $code,
                        'amount' => $amount,
                        'min_purchase_amount' => $minPurchaseAmount,
                        'valid_from' => now(),
                        'valid_until' => now()->addMonths($validMonths),
                        'is_used' => false,
                        'user_id' => null, // قسيمة عامة
                        'order_id' => null,
                    ]);
                    
                    $couponsCreated++;
                }
            } else {
                // توليد قسائم لمستخدمين محددين
                foreach ($validated['user_ids'] as $userId) {
                    for ($i = 0; $i < $quantityPerUser; $i++) {
                        $code = strtoupper(Str::random(8));
                        
                        while (Coupon::where('code', $code)->exists()) {
                            $code = strtoupper(Str::random(8));
                        }
                        
                        Coupon::create([
                            'code' => $code,
                            'amount' => $amount,
                            'min_purchase_amount' => $minPurchaseAmount,
                            'valid_from' => now(),
                            'valid_until' => now()->addMonths($validMonths),
                            'is_used' => false,
                            'user_id' => $userId,
                            'order_id' => null,
                        ]);
                        
                        $couponsCreated++;
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.coupons.index')
                ->with('success', "تم إنشاء $couponsCreated قسيمة بنجاح.");
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'فشل في إنشاء القسائم: ' . $e->getMessage());
        }
    }
}