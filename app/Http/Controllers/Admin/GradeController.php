<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Platform;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::with('platform')->orderBy('platform_id')->orderBy('grade_number')->paginate(15);
        return view('admin.grades.index', compact('grades'));
    }

    public function create()
    {
        $platforms = Platform::where('is_active', true)->orderBy('name')->get();
        return view('admin.grades.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'grade_number' => 'required|integer|min:1|max:12',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // Check if grade number already exists for this platform
        $exists = Grade::where('platform_id', $validated['platform_id'])
                      ->where('grade_number', $validated['grade_number'])
                      ->exists();
                      
        if ($exists) {
            return back()->withErrors(['grade_number' => 'This grade number already exists for the selected platform.']);
        }

        Grade::create($validated);

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade created successfully.');
    }

    public function show(Grade $grade)
    {
        $grade->load(['platform', 'subjects']);
        return view('admin.grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        $platforms = Platform::where('is_active', true)->orderBy('name')->get();
        return view('admin.grades.edit', compact('grade', 'platforms'));
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'grade_number' => 'required|integer|min:1|max:12',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // Check if grade number already exists for this platform (excluding current record)
        $exists = Grade::where('platform_id', $validated['platform_id'])
                      ->where('grade_number', $validated['grade_number'])
                      ->where('id', '!=', $grade->id)
                      ->exists();
                      
        if ($exists) {
            return back()->withErrors(['grade_number' => 'This grade number already exists for the selected platform.']);
        }

        $grade->update($validated);

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        if ($grade->subjects()->count() > 0) {
            return redirect()->route('admin.grades.index')
                ->with('error', 'Cannot delete grade with associated subjects.');
        }

        $grade->delete();

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade deleted successfully.');
    }
}