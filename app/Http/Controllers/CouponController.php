<?php

// ===================================
// CouponController - وحدة تحكم القسائم (للمستخدمين)
// ===================================

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * عرض قائمة قسائم المستخدم.
     */
    public function index()
    {
        $coupons = Coupon::where('user_id', Auth::id())
            ->orderBy('is_used')
            ->orderByDesc('created_at')
            ->paginate(10);
            
        return view('coupons.index', compact('coupons'));
    }

    /**
     * عرض القسيمة المحددة.
     */
    public function show(Coupon $coupon)
    {
        if ($coupon->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('coupons.show', compact('coupon'));
    }
}