<?php

// ===================================
// ProductController - وحدة تحكم المنتجات (للواجهة الأمامية)
// ===================================

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
        
        // فرض فلترة المنتجات النشطة للجميع باستثناء لوحة تحكم المدير
        if (!str_contains(request()->url(), 'admin')) {
            $query->where('is_active', 1);
        }
        
        // فلترة حسب الفئة إذا تم توفيرها
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
        
        // التعامل مع البحث إذا تم توفيره
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // التصفح مع خيار عرض الكل
        $perPage = $request->get('show_all') ? $query->count() : 9;
        $products = $query->paginate($perPage);
        
        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        // تحميل علاقة الصور
        $product->load(['category', 'images']);
        
        // منع الوصول للمنتجات غير النشطة للمستخدمين غير المديرين
        if ($product->is_active == 0 && !str_contains(request()->url(), 'admin')) {
            return redirect()->route('products.index')
                ->with('error', 'المنتج المطلوب غير متوفر حالياً.');
        }
        
        // الحصول على المنتجات ذات الصلة من نفس الفئة
        $relatedQuery = Product::with(['images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id);
            
        // إظهار المنتجات النشطة فقط للمستخدمين العاديين
        if (!str_contains(request()->url(), 'admin')) {
            $relatedQuery->where('is_active', 1);
        }
        
        $relatedProducts = $relatedQuery->take(4)->get();
            
        return view('products.show', compact('product', 'relatedProducts'));
    }
    
    /**
     * الحصول على صور المنتج كـ JSON للمعرض
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