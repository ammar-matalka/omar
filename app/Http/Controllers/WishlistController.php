<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product')->get();
        
        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add a product to the wishlist.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlistItem = Wishlist::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
            ]
        );

        return redirect()->back()->with('success', 'Product added to wishlist!');
    }

    /**
     * Remove a product from the wishlist.
     */
    public function destroy($productId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        return redirect()->back()->with('success', 'Product removed from wishlist!');
    }

    /**
     * Toggle a product in the wishlist (add if not exists, remove if exists).
     */
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->exists();

        if ($exists) {
            // Remove from wishlist
            Wishlist::where('user_id', Auth::id())
                ->where('product_id', $validated['product_id'])
                ->delete();
            $message = 'Product removed from wishlist!';
            $inWishlist = false;
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
            ]);
            $message = 'Product added to wishlist!';
            $inWishlist = true;
        }
        
        // Get updated count for the badge
        $count = Auth::user()->wishlist()->count();

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