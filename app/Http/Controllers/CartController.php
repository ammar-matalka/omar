<?php

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
     * Display the shopping cart
     */
    public function index()
    {
        $cart = $this->getCart();
        $categories = Category::all();

        return view('cart.index', compact('cart', 'categories'));
    }

    /**
     * Add item to cart
     */
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getCart();
        $product = Product::findOrFail($validated['product_id']);

        // Check if product is active
        if (!$product->is_active) {
            return back()->with('error', __('This product is currently unavailable.'));
        }

        // Check for existing cart item
        $existingCartItem = $cart->cartItems()
            ->where('product_id', $validated['product_id'])
            ->first();

        $existingQuantity = $existingCartItem ? $existingCartItem->quantity : 0;
        $totalQuantity = $validated['quantity'] + $existingQuantity;

        // Check stock availability
        if ($totalQuantity > $product->stock) {
            return back()->with('error', __('The quantity requested exceeds available stock. Available: :stock', ['stock' => $product->stock]));
        }

        if ($existingCartItem) {
            // Update existing item quantity
            $existingCartItem->update(['quantity' => $totalQuantity]);
        } else {
            // Create new cart item
            $cart->cartItems()->create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity']
            ]);
        }

        return back()->with('success', __('Product added to cart successfully.'));
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $product = $cartItem->product;
        
        if (!$product) {
            return back()->with('error', __('Product not found.'));
        }

        // Check if product is still active
        if (!$product->is_active) {
            return back()->with('error', __('This product is no longer available.'));
        }

        // Check stock availability
        if ($product->stock < $validated['quantity']) {
            return back()->with('error', __('Requested quantity not available. Available stock: :stock', ['stock' => $product->stock]));
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('success', __('Cart updated successfully.'));
    }

    /**
     * Remove item from cart
     */
    public function remove(CartItem $cartItem)
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $cartItem->delete();

        return back()->with('success', __('Item removed from cart.'));
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $cart = $this->getCart();
        $cart->cartItems()->delete();
        
        return back()->with('success', __('Cart cleared successfully.'));
    }

    /**
     * Get cart count for header badge (AJAX)
     */
    public function getCartCount()
    {
        $cart = Auth::user()->cart;
        $count = $cart ? $cart->cartItems->sum('quantity') : 0;
        
        return response()->json(['count' => $count]);
    }

    // ====================================
    // PRIVATE HELPER METHODS
    // ====================================

    /**
     * Get or create user's cart
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