<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['grade.platform'])->orderBy('name')->paginate(15);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $platforms = Platform::where('is_active', true)->with('grades')->orderBy('name')->get();
        return view('admin.subjects.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
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
            $imagePath = $request->file('image')->store('subjects', 'public');
            $validated['image'] = $imagePath;
        }

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['grade.platform', 'educationalCards']);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $platforms = Platform::where('is_active', true)->with('grades')->orderBy('name')->get();
        return view('admin.subjects.edit', compact('subject', 'platforms'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
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
            if ($subject->image) {
                Storage::disk('public')->delete($subject->image);
            }
            
            $imagePath = $request->file('image')->store('subjects', 'public');
            $validated['image'] = $imagePath;
        }

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->educationalCards()->count() > 0) {
            return redirect()->route('admin.subjects.index')
                ->with('error', 'Cannot delete subject with associated educational cards.');
        }

        // Delete the image if it exists
        if ($subject->image) {
            Storage::disk('public')->delete($subject->image);
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
    
    /**
     * Get grades by platform (AJAX)
     */
    public function getGradesByPlatform(Platform $platform)
    {
        $grades = $platform->grades()->where('is_active', true)->orderBy('grade_number')->get();
        return response()->json($grades);
    }
}