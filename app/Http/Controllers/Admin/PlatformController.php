<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlatformController extends Controller
{
    public function index()
    {
        $platforms = Platform::orderBy('name')->paginate(10);
        return view('admin.platforms.index', compact('platforms'));
    }

    public function create()
    {
        return view('admin.platforms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('platforms', 'public');
            $validated['image'] = $imagePath;
        }

        Platform::create($validated);

        return redirect()->route('admin.platforms.index')
            ->with('success', 'Platform created successfully.');
    }

    public function show(Platform $platform)
    {
        $platform->load('grades');
        return view('admin.platforms.show', compact('platform'));
    }

    public function edit(Platform $platform)
    {
        return view('admin.platforms.edit', compact('platform'));
    }

    public function update(Request $request, Platform $platform)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($platform->image) {
                Storage::disk('public')->delete($platform->image);
            }
            
            $imagePath = $request->file('image')->store('platforms', 'public');
            $validated['image'] = $imagePath;
        }

        $platform->update($validated);

        return redirect()->route('admin.platforms.index')
            ->with('success', 'Platform updated successfully.');
    }

    public function destroy(Platform $platform)
    {
        if ($platform->grades()->count() > 0) {
            return redirect()->route('admin.platforms.index')
                ->with('error', 'Cannot delete platform with associated grades.');
        }

        // Delete the image if it exists
        if ($platform->image) {
            Storage::disk('public')->delete($platform->image);
        }

        $platform->delete();

        return redirect()->route('admin.platforms.index')
            ->with('success', 'Platform deleted successfully.');
    }
}