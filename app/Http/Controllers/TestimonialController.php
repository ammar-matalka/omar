<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TestimonialController extends Controller
{
    /**
     * Show the form for creating a new testimonial.
     */
    public function create(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if user already submitted a testimonial for this order
        $existingTestimonial = Testimonial::where('user_id', Auth::id())
            ->where('order_id', $order->id)
            ->first();

        if ($existingTestimonial) {
            return redirect()->route('orders.show', $order)->with('info', 'You have already submitted a testimonial for this order.');
        }

        return view('testimonials.form', compact('order'));
    }

    /**
     * Store a newly created testimonial in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'comment' => 'required|string|min:5|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Check if user already submitted a testimonial for this order
        $existingTestimonial = Testimonial::where('user_id', Auth::id())
            ->where('order_id', $request->order_id)
            ->first();

        if ($existingTestimonial) {
            return redirect()->back()->with('error', 'You have already submitted a testimonial for this order.');
        }

        // Create new testimonial
        $testimonial = Testimonial::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'comment' => $request->comment,
            'rating' => $request->rating,
            'status' => 'pending', // Default status is pending until admin approves
        ]);

        return redirect()->route('home')->with('success', 'Thank you for your testimonial! It will be reviewed shortly.');
    }
}