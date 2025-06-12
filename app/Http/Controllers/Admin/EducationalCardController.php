<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalCard;
use App\Models\EducationalCardImage;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EducationalCardController extends Controller
{
    public function index()
    {
        $cards = EducationalCard::with(['subject.grade.platform', 'images'])
                               ->orderBy('title')
                               ->paginate(15);
        return view('admin.educational-cards.index', compact('cards'));
    }

    public function create()
    {
        $platforms = Platform::where('is_active', true)->with(['grades.subjects'])->orderBy('name')->get();
        return view('admin.educational-cards.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'card_type' => 'required|in:digital,physical,both',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'images.*' => 'image|max:2048',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        // Create the educational card
        $card = EducationalCard::create($validated);
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $isPrimary = true;
            $sortOrder = 0;
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('educational-cards', 'public');
                
                EducationalCardImage::create([
                    'educational_card_id' => $card->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => $sortOrder,
                ]);
                
                if ($isPrimary) {
                    // Update the card's image field for backward compatibility
                    $card->update(['image' => $path]);
                    $isPrimary = false;
                }
                
                $sortOrder++;
            }
        }
        
        return redirect()->route('admin.educational-cards.index')
            ->with('success', 'Educational card created successfully.');
    }

    public function show(EducationalCard $educationalCard)
    {
        $educationalCard->load(['subject.grade.platform', 'images']);
            $card = $educationalCard;
        return view('admin.educational-cards.show', compact('educationalCard'));
    }
    

    public function edit(EducationalCard $educationalCard)
    {
        $platforms = Platform::where('is_active', true)->with(['grades.subjects'])->orderBy('name')->get();
        $educationalCard->load(['subject.grade.platform', 'images']);
        return view('admin.educational-cards.edit', compact('educationalCard', 'platforms'));
    }

    public function update(Request $request, EducationalCard $educationalCard)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'card_type' => 'required|in:digital,physical,both',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'images.*' => 'image|max:2048',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        // Update the educational card
        $educationalCard->update($validated);
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $isPrimary = !$educationalCard->images()->exists();
            $sortOrder = $educationalCard->images()->max('sort_order') + 1;
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('educational-cards', 'public');
                
                $cardImage = EducationalCardImage::create([
                    'educational_card_id' => $educationalCard->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => $sortOrder,
                ]);
                
                if ($isPrimary) {
                    $educationalCard->update(['image' => $path]);
                    $isPrimary = false;
                }
                
                $sortOrder++;
            }
        }
        
        // Handle image deletion
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = EducationalCardImage::find($imageId);
                if ($image && $image->educational_card_id == $educationalCard->id) {
                    // Delete the file
                    Storage::disk('public')->delete($image->image_path);
                    
                    // If this was the primary image, set another image as primary
                    if ($image->is_primary) {
                        $newPrimary = $educationalCard->images()->where('id', '!=', $image->id)->first();
                        if ($newPrimary) {
                            $newPrimary->update(['is_primary' => true]);
                            $educationalCard->update(['image' => $newPrimary->image_path]);
                        } else {
                            $educationalCard->update(['image' => null]);
                        }
                    }
                    
                    $image->delete();
                }
            }
        }
        
        // Handle primary image change
        if ($request->has('primary_image') && $request->primary_image) {
            $educationalCard->images()->update(['is_primary' => false]);
            
            $newPrimary = EducationalCardImage::find($request->primary_image);
            if ($newPrimary && $newPrimary->educational_card_id == $educationalCard->id) {
                $newPrimary->update(['is_primary' => true]);
                $educationalCard->update(['image' => $newPrimary->image_path]);
            }
        }
        
        return redirect()->route('admin.educational-cards.index')
            ->with('success', 'Educational card updated successfully.');
    }

    public function destroy(EducationalCard $educationalCard)
    {
        // Delete all associated images
        foreach ($educationalCard->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        
        // Delete the old main image if exists
        if ($educationalCard->image) {
            Storage::disk('public')->delete($educationalCard->image);
        }
        
        $educationalCard->delete();
        
        return redirect()->route('admin.educational-cards.index')
            ->with('success', 'Educational card deleted successfully.');
    }
    
    /**
     * Update image order via AJAX
     */
    public function updateImageOrder(Request $request, EducationalCard $educationalCard)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:educational_card_images,id',
            'images.*.order' => 'required|integer|min:0'
        ]);
        
        foreach ($request->images as $image) {
            EducationalCardImage::where('id', $image['id'])
                ->where('educational_card_id', $educationalCard->id)
                ->update(['sort_order' => $image['order']]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Get subjects by grade (AJAX)
     */
    public function getSubjectsByGrade(Grade $grade)
    {
        $subjects = $grade->subjects()->where('is_active', true)->orderBy('name')->get();
        return response()->json($subjects);
    }
}