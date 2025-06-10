<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the testimonials.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pendingTestimonials = Testimonial::with(['user', 'order'])
            ->where('status', 'pending')
            ->latest()
            ->get();
            
        $approvedTestimonials = Testimonial::with(['user', 'order'])
            ->where('status', 'approved')
            ->latest()
            ->get();
            
        $rejectedTestimonials = Testimonial::with(['user', 'order'])
            ->where('status', 'rejected')
            ->latest()
            ->get();
            
        return view('admin.testimonials.index', compact(
            'pendingTestimonials',
            'approvedTestimonials',
            'rejectedTestimonials'
        ));
    }

    /**
     * Display the specified testimonial.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\View\View
     */
   
    /**
     * Approve the specified testimonial.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['status' => 'approved']);
        
        return redirect()->back()->with('success', 'Testimonial has been approved successfully.');
    }

    /**
     * Reject the specified testimonial.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Testimonial $testimonial)
    {
        $testimonial->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Testimonial has been rejected successfully.');
    }

    /**
     * Remove the specified testimonial from storage.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial has been deleted successfully.');
    }
    public function show(Testimonial $testimonial)
{
    // Load the related user and order data
    $testimonial->load(['user', 'order']);
    
    return view('admin.testimonials.show', compact('testimonial'));
}
}