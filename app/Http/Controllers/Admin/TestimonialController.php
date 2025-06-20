<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * عرض قائمة الشهادات.
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
     * عرض الشهادة المحددة.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\View\View
     */
    public function show(Testimonial $testimonial)
    {
        // تحميل بيانات المستخدم والطلب المرتبطة
        $testimonial->load(['user', 'order']);
        
        return view('admin.testimonials.show', compact('testimonial'));
    }
   
    /**
     * الموافقة على الشهادة المحددة.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['status' => 'approved']);
        
        return redirect()->back()->with('success', 'تم الموافقة على الشهادة بنجاح.');
    }

    /**
     * رفض الشهادة المحددة.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Testimonial $testimonial)
    {
        $testimonial->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'تم رفض الشهادة بنجاح.');
    }

    /**
     * حذف الشهادة المحددة من قاعدة البيانات.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        
        return redirect()->route('admin.testimonials.index')->with('success', 'تم حذف الشهادة بنجاح.');
    }
}