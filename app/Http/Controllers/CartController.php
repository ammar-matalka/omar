<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\EducationalCard;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $categories = Category::all();

        return view('cart.index', compact('cart', 'categories'));
    }

    public function addItem(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'product_id' => 'required_without:educational_card_id|exists:products,id',
            'educational_card_id' => 'required_without:product_id|exists:educational_cards,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:product,educational_card'
        ]);

        $cart = $this->getCart();
        $type = $validated['type'];
        $quantity = $validated['quantity'];

        if ($type === 'product') {
            $item = Product::findOrFail($validated['product_id']);
            $existingCartItem = $cart->cartItems()
                ->where('product_id', $validated['product_id'])
                ->where('type', 'product')
                ->first();
        } else {
            $item = EducationalCard::findOrFail($validated['educational_card_id']);
            $existingCartItem = $cart->cartItems()
                ->where('educational_card_id', $validated['educational_card_id'])
                ->where('type', 'educational_card')
                ->first();
        }

        // Check if item is active
        if (!$item->is_active) {
            return back()->with('error', __('This item is currently unavailable.'));
        }

        $existingQuantity = $existingCartItem ? $existingCartItem->quantity : 0;
        $totalQuantity = $quantity + $existingQuantity;

        // Check stock
        if ($totalQuantity > $item->stock) {
            return back()->with('error', __('The quantity ordered exceeds the available stock.'));
        }

        if ($existingCartItem) {
            // Update the quantity
            $existingCartItem->update(['quantity' => $totalQuantity]);
        } else {
            // Add new item
            $data = [
                'quantity' => $quantity,
                'type' => $type
            ];
            
            if ($type === 'product') {
                $data['product_id'] = $validated['product_id'];
            } else {
                $data['educational_card_id'] = $validated['educational_card_id'];
            }
            
            $cart->cartItems()->create($data);
        }

        return back()->with('success', __('The item has been added to the cart.'));
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $item = $cartItem->item;
        
        if (!$item) {
            return back()->with('error', __('Item not found.'));
        }

        if ($item->stock < $validated['quantity']) {
            return back()->with('error', __('The requested quantity is currently unavailable.'));
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('success', __('The cart has been updated.'));
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', __('The item has been removed from the cart.'));
    }

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

        return $cart->load(['cartItems.product', 'cartItems.educationalCard']);
    }
    
    /**
     * Get cart count for header badge
     */
    public function getCartCount()
    {
        $cart = Auth::user()->cart;
        $count = $cart ? $cart->cartItems->sum('quantity') : 0;
        
        return response()->json(['count' => $count]);
    }
    
    /**
     * Clear entire cart
     */
    public function clear()
    {
        $cart = $this->getCart();
        $cart->cartItems()->delete();
        
        return back()->with('success', __('Cart has been cleared.'));
    }
}