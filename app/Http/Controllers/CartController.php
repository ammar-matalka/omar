<?php

// ===================================
// CartController - وحدة تحكم سلة التسوق
// ===================================

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * عرض سلة التسوق
     */
    public function index()
    {
        $cart = $this->getCart();
        $categories = Category::all();

        return view('cart.index', compact('cart', 'categories'));
    }

    /**
     * إضافة عنصر إلى السلة
     */
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ], [
            'product_id.required' => 'معرف المنتج مطلوب.',
            'product_id.exists' => 'المنتج المحدد غير موجود.',
            'quantity.required' => 'الكمية مطلوبة.',
            'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح.',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1.',
        ]);

        $cart = $this->getCart();
        $product = Product::findOrFail($validated['product_id']);

        // التحقق من تفعيل المنتج
        if (!$product->is_active) {
            return back()->with('error', 'هذا المنتج غير متوفر حالياً.');
        }

        // البحث عن عنصر سلة موجود
        $existingCartItem = $cart->cartItems()
            ->where('product_id', $validated['product_id'])
            ->first();

        $existingQuantity = $existingCartItem ? $existingCartItem->quantity : 0;
        $totalQuantity = $validated['quantity'] + $existingQuantity;

        // التحقق من توفر المخزون
        if ($totalQuantity > $product->stock) {
            return back()->with('error', 'الكمية المطلوبة تتجاوز المخزون المتاح. المتاح: ' . $product->stock);
        }

        if ($existingCartItem) {
            // تحديث كمية العنصر الموجود
            $existingCartItem->update(['quantity' => $totalQuantity]);
        } else {
            // إنشاء عنصر سلة جديد
            $cart->cartItems()->create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity']
            ]);
        }

        return back()->with('success', 'تم إضافة المنتج إلى السلة بنجاح.');
    }

    /**
     * تحديث كمية عنصر السلة
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ], [
            'quantity.required' => 'الكمية مطلوبة.',
            'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح.',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1.',
        ]);

        // التأكد من أن عنصر السلة ينتمي للمستخدم المصادق عليه
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'إجراء غير مخول.');
        }

        $product = $cartItem->product;
        
        if (!$product) {
            return back()->with('error', 'المنتج غير موجود.');
        }

        // التحقق من أن المنتج لا يزال نشطاً
        if (!$product->is_active) {
            return back()->with('error', 'هذا المنتج لم يعد متوفراً.');
        }

        // التحقق من توفر المخزون
        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'الكمية المطلوبة غير متوفرة. المخزون المتاح: ' . $product->stock);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('success', 'تم تحديث السلة بنجاح.');
    }

    /**
     * إزالة عنصر من السلة
     */
    public function remove(CartItem $cartItem)
    {
        // التأكد من أن عنصر السلة ينتمي للمستخدم المصادق عليه
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'إجراء غير مخول.');
        }

        $cartItem->delete();

        return back()->with('success', 'تم إزالة العنصر من السلة.');
    }

    /**
     * مسح السلة بالكامل
     */
    public function clear()
    {
        $cart = $this->getCart();
        $cart->cartItems()->delete();
        
        return back()->with('success', 'تم مسح السلة بنجاح.');
    }

    /**
     * الحصول على عدد عناصر السلة للشارة في الرأس (AJAX)
     */
    public function getCartCount()
    {
        $cart = Auth::user()->cart;
        $count = $cart ? $cart->cartItems->sum('quantity') : 0;
        
        return response()->json(['count' => $count]);
    }

    // ====================================
    // طرق مساعدة خاصة
    // ====================================

    /**
     * الحصول على سلة المستخدم أو إنشاؤها
     */
    private function getCart()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $cart = $user->cart;

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $user->id,
            ]);
        }

        return $cart->load(['cartItems.product']);
    }
}