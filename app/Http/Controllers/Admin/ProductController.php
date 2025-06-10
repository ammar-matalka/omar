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
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        // Create the product
        $product = Product::create($validated);
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $isPrimary = true; // First image is primary
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
                    // Update the product's image field for backward compatibility
                    $product->update(['image' => $path]);
                    $isPrimary = false;
                }
                
                $sortOrder++;
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
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
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        // Update the product
        $product->update($validated);
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $isPrimary = !$product->images()->exists(); // First image is primary only if no images exist
            $sortOrder = $product->images()->max('sort_order') + 1; // Start after the last image
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                $productImage = ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => $sortOrder,
                ]);
                
                if ($isPrimary) {
                    // Update the product's image field for backward compatibility
                    $product->update(['image' => $path]);
                    $isPrimary = false;
                }
                
                $sortOrder++;
            }
        }
        
        // Handle image deletion
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id == $product->id) {
                    // Delete the file
                    Storage::disk('public')->delete($image->image_path);
                    
                    // If this was the primary image, set another image as primary
                    if ($image->is_primary) {
                        $newPrimary = $product->images()->where('id', '!=', $image->id)->first();
                        if ($newPrimary) {
                            $newPrimary->update(['is_primary' => true]);
                            $product->update(['image' => $newPrimary->image_path]);
                        } else {
                            $product->update(['image' => null]);
                        }
                    }
                    
                    // Delete the record
                    $image->delete();
                }
            }
        }
        
        // Handle primary image change
        if ($request->has('primary_image') && $request->primary_image) {
            // Reset all to non-primary
            $product->images()->update(['is_primary' => false]);
            
            // Set the new primary
            $newPrimary = ProductImage::find($request->primary_image);
            if ($newPrimary && $newPrimary->product_id == $product->id) {
                $newPrimary->update(['is_primary' => true]);
                $product->update(['image' => $newPrimary->image_path]);
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete all associated images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        
        // Delete the old main image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
    
    // Add a method to update image order via AJAX
    public function updateImageOrder(Request $request, Product $product)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:product_images,id',
            'images.*.order' => 'required|integer|min:0'
        ]);
        
        foreach ($request->images as $image) {
            ProductImage::where('id', $image['id'])
                ->where('product_id', $product->id)
                ->update(['sort_order' => $image['order']]);
        }
        
        return response()->json(['success' => true]);
    }
}