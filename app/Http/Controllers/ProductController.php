<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']); 
        $categories = Category::all();
        
        // Force filter active products for everyone except admin dashboard
        if (!str_contains(request()->url(), 'admin')) {
            $query->where('is_active', 1);
        }
        
        // Filter by category if provided
        if ($request->has('category')) {
            $categoryParam = $request->category;
            
            if (is_numeric($categoryParam)) {
                $query->where('category_id', $categoryParam);
            } else {
                $category = Category::where('slug', $categoryParam)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }
        }
        
        // Handle search if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // Pagination with show all option
        $perPage = $request->get('show_all') ? $query->count() : 9;
        $products = $query->paginate($perPage);
        
        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        // Load images relationship
        $product->load(['category', 'images']);
        
        // Block access to inactive products for non-admin users
        if ($product->is_active == 0 && !str_contains(request()->url(), 'admin')) {
            return redirect()->route('products.index')
                ->with('error', __('The requested product is currently unavailable.'));
        }
        
        // Get related products from the same category
        $relatedQuery = Product::with(['images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id);
            
        // Only show active related products to regular users
        if (!str_contains(request()->url(), 'admin')) {
            $relatedQuery->where('is_active', 1);
        }
        
        $relatedProducts = $relatedQuery->take(4)->get();
            
        return view('products.show', compact('product', 'relatedProducts'));
    }
    
    /**
     * Get product images as JSON for gallery
     */
    public function getImages(Product $product)
    {
        $images = $product->images()->orderBy('sort_order')->get()->map(function($image) {
            return [
                'id' => $image->id,
                'url' => asset('storage/' . $image->image_path),
                'is_primary' => $image->is_primary,
                'sort_order' => $image->sort_order
            ];
        });
        
        return response()->json($images);
    }
}