<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * عرض قائمة أمنيات المستخدم.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $wishlistItems = $user->wishlist()->with('product')->get();
        
        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * إضافة منتج إلى قائمة الأمنيات.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ], [
            'product_id.required' => 'معرف المنتج مطلوب.',
            'product_id.exists' => 'المنتج المحدد غير موجود.',
        ]);

        $wishlistItem = Wishlist::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
            ]
        );

        return redirect()->back()->with('success', 'تم إضافة المنتج إلى قائمة الأمنيات!');
    }

    /**
     * إزالة منتج من قائمة الأمنيات.
     */
    public function destroy($productId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        return redirect()->back()->with('success', 'تم إزالة المنتج من قائمة الأمنيات!');
    }

    /**
     * تبديل منتج في قائمة الأمنيات (إضافة إذا لم يكن موجود، إزالة إذا كان موجود).
     */
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ], [
            'product_id.required' => 'معرف المنتج مطلوب.',
            'product_id.exists' => 'المنتج المحدد غير موجود.',
        ]);

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->exists();

        if ($exists) {
            // إزالة من قائمة الأمنيات
            Wishlist::where('user_id', Auth::id())
                ->where('product_id', $validated['product_id'])
                ->delete();
            $message = 'تم إزالة المنتج من قائمة الأمنيات!';
            $inWishlist = false;
        } else {
            // إضافة إلى قائمة الأمنيات
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
            ]);
            $message = 'تم إضافة المنتج إلى قائمة الأمنيات!';
            $inWishlist = true;
        }
        
        // الحصول على العدد المحدث للشارة
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $count = $user->wishlist()->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => $message,
                'in_wishlist' => $inWishlist,
                'count' => $count
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}