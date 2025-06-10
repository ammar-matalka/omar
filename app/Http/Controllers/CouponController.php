<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * Display a listing of the user's coupons.
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
     * Display the specified coupon.
     */
    public function show(Coupon $coupon)
    {
        if ($coupon->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('coupons.show', compact('coupon'));
    }
}