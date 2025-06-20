<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images'])->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'sometimes|boolean',
            'images.*' => 'image|max:2048',
        ], [
            'name.required' => 'اسم المنتج مطلوب.',
            'name.string' => 'اسم المنتج يجب أن يكون نص.',
            'name.max' => 'اسم المنتج لا يجب أن يتجاوز 255 حرف.',
            'description.required' => 'وصف المنتج مطلوب.',
            'description.string' => 'وصف المنتج يجب أن يكون نص.',
            'price.required' => 'سعر المنتج مطلوب.',
            'price.numeric' => 'سعر المنتج يجب أن يكون رقم.',
            'price.min' => 'سعر المنتج لا يمكن أن يكون سالب.',
            'stock.required' => 'كمية المخزون مطلوبة.',
            'stock.integer' => 'كمية المخزون يجب أن تكون رقم صحيح.',
            'stock.min' => 'كمية المخزون لا يمكن أن تكون سالبة.',
            'category_id.required' => 'فئة المنتج مطلوبة.',
            'category_id.exists' => 'الفئة المحددة غير موجودة.',
            'images.*.image' => 'جميع الملفات يجب أن تكون صور.',
            'images.*.max' => 'حجم كل صورة يجب ألا يتجاوز 2 ميجابايت.',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        // إنشاء المنتج
        $product = Product::create($validated);
        
        // التعامل مع رفع الصور
        if ($request->hasFile('images')) {
            $isPrimary = true; // الصورة الأولى هي الرئيسية
            $sortOrder = 0;
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => $sortOrder,
                ]);
                
                if ($isPrimary) {
                    // تحديث حقل الصورة في المنتج للتوافق مع النسخ القديمة
                    $product->update(['image' => $path]);
                    $isPrimary = false;
                }
                
                $sortOrder++;
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'تم إنشاء المنتج بنجاح.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'sometimes|boolean',
            'images.*' => 'image|max:2048',
        ], [
            'name.required' => 'اسم المنتج مطلوب.',
            'name.string' => 'اسم المنتج يجب أن يكون نص.',
            'name.max' => 'اسم المنتج لا يجب أن يتجاوز 255 حرف.',
            'description.required' => 'وصف المنتج مطلوب.',
            'description.string' => 'وصف المنتج يجب أن يكون نص.',
            'price.required' => 'سعر المنتج مطلوب.',
            'price.numeric' => 'سعر المنتج يجب أن يكون رقم.',
            'price.min' => 'سعر المنتج لا يمكن أن يكون سالب.',
            'stock.required' => 'كمية المخزون مطلوبة.',
            'stock.integer' => 'كمية المخزون يجب أن تكون رقم صحيح.',
            'stock.min' => 'كمية المخزون لا يمكن أن تكون سالبة.',
            'category_id.required' => 'فئة المنتج مطلوبة.',
            'category_id.exists' => 'الفئة المحددة غير موجودة.',
            'images.*.image' => 'جميع الملفات يجب أن تكون صور.',
            'images.*.max' => 'حجم كل صورة يجب ألا يتجاوز 2 ميجابايت.',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        // تحديث المنتج
        $product->update($validated);
        
        // التعامل مع رفع الصور
        if ($request->hasFile('images')) {
            $isPrimary = !$product->images()->exists(); // الصورة الأولى رئيسية فقط إذا لم توجد صور
            $sortOrder = $product->images()->max('sort_order') + 1; // البدء بعد آخر صورة
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                $productImage = ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => $sortOrder,
                ]);
                
                if ($isPrimary) {
                    // تحديث حقل الصورة في المنتج للتوافق مع النسخ القديمة
                    $product->update(['image' => $path]);
                    $isPrimary = false;
                }
                
                $sortOrder++;
            }
        }
        
        // التعامل مع حذف الصور
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id == $product->id) {
                    // حذف الملف
                    Storage::disk('public')->delete($image->image_path);
                    
                    // إذا كانت هذه الصورة الرئيسية، تعيين صورة أخرى كرئيسية
                    if ($image->is_primary) {
                        $newPrimary = $product->images()->where('id', '!=', $image->id)->first();
                        if ($newPrimary) {
                            $newPrimary->update(['is_primary' => true]);
                            $product->update(['image' => $newPrimary->image_path]);
                        } else {
                            $product->update(['image' => null]);
                        }
                    }
                    
                    // حذف السجل
                    $image->delete();
                }
            }
        }
        
        // التعامل مع تغيير الصورة الرئيسية
        if ($request->has('primary_image') && $request->primary_image) {
            // إعادة تعيين جميع الصور لغير رئيسية
            $product->images()->update(['is_primary' => false]);
            
            // تعيين الصورة الجديدة كرئيسية
            $newPrimary = ProductImage::find($request->primary_image);
            if ($newPrimary && $newPrimary->product_id == $product->id) {
                $newPrimary->update(['is_primary' => true]);
                $product->update(['image' => $newPrimary->image_path]);
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح.');
    }

    public function destroy(Product $product)
    {
        // حذف جميع الصور المرتبطة
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        
        // حذف الصورة الرئيسية القديمة إذا كانت موجودة
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح.');
    }
    
    // إضافة طريقة لتحديث ترتيب الصور عبر AJAX
    public function updateImageOrder(Request $request, Product $product)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:product_images,id',
            'images.*.order' => 'required|integer|min:0'
        ], [
            'images.required' => 'بيانات الصور مطلوبة.',
            'images.array' => 'بيانات الصور يجب أن تكون مصفوفة.',
            'images.*.id.required' => 'معرف الصورة مطلوب.',
            'images.*.id.exists' => 'الصورة المحددة غير موجودة.',
            'images.*.order.required' => 'ترتيب الصورة مطلوب.',
            'images.*.order.integer' => 'ترتيب الصورة يجب أن يكون رقم صحيح.',
            'images.*.order.min' => 'ترتيب الصورة لا يمكن أن يكون سالب.',
        ]);
        
        foreach ($request->images as $image) {
            ProductImage::where('id', $image['id'])
                ->where('product_id', $product->id)
                ->update(['sort_order' => $image['order']]);
        }
        
        return response()->json(['success' => true]);
    }
}