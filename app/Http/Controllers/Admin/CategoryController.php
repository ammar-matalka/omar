<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        // مهم: استخدم Laravel paginator وليس Collection
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'اسم الفئة مطلوب.',
            'name.string' => 'اسم الفئة يجب أن يكون نص.',
            'name.max' => 'اسم الفئة لا يجب أن يتجاوز 255 حرف.',
            'description.string' => 'الوصف يجب أن يكون نص.',
            'image.image' => 'يجب أن يكون الملف صورة.',
            'image.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // التعامل مع رفع الصورة
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم إنشاء الفئة بنجاح.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'اسم الفئة مطلوب.',
            'name.string' => 'اسم الفئة يجب أن يكون نص.',
            'name.max' => 'اسم الفئة لا يجب أن يتجاوز 255 حرف.',
            'description.string' => 'الوصف يجب أن يكون نص.',
            'image.image' => 'يجب أن يكون الملف صورة.',
            'image.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // التعامل مع رفع الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'لا يمكن حذف الفئة التي تحتوي على منتجات مرتبطة بها.');
        }

        // حذف الصورة إذا كانت موجودة
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح.');
    }
}