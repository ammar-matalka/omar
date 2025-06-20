<?php

// ===================================
// TestimonialController - وحدة تحكم الشهادات (للمستخدمين)
// ===================================

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
     * عرض نموذج إنشاء شهادة جديدة.
     */
    public function create(Order $order)
    {
        // التأكد من أن الطلب ينتمي للمستخدم المصادق عليه
        if ($order->user_id !== Auth::id()) {
            abort(403, 'إجراء غير مخول.');
        }

        // التحقق من عدم تقديم المستخدم شهادة لهذا الطلب مسبقاً
        $existingTestimonial = Testimonial::where('user_id', Auth::id())
            ->where('order_id', $order->id)
            ->first();

        if ($existingTestimonial) {
            return redirect()->route('orders.show', $order)->with('info', 'لقد قمت بتقديم شهادة لهذا الطلب بالفعل.');
        }

        return view('testimonials.form', compact('order'));
    }

    /**
     * حفظ شهادة جديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'comment' => 'required|string|min:5|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ], [
            'order_id.required' => 'معرف الطلب مطلوب.',
            'order_id.exists' => 'الطلب المحدد غير موجود.',
            'comment.required' => 'التعليق مطلوب.',
            'comment.string' => 'التعليق يجب أن يكون نص.',
            'comment.min' => 'التعليق يجب أن يكون على الأقل 5 أحرف.',
            'comment.max' => 'التعليق لا يجب أن يتجاوز 500 حرف.',
            'rating.required' => 'التقييم مطلوب.',
            'rating.integer' => 'التقييم يجب أن يكون رقم صحيح.',
            'rating.min' => 'التقييم يجب أن يكون على الأقل نجمة واحدة.',
            'rating.max' => 'التقييم لا يمكن أن يتجاوز 5 نجوم.',
        ]);

        // التحقق من عدم تقديم المستخدم شهادة لهذا الطلب مسبقاً
        $existingTestimonial = Testimonial::where('user_id', Auth::id())
            ->where('order_id', $request->order_id)
            ->first();

        if ($existingTestimonial) {
            return redirect()->back()->with('error', 'لقد قمت بتقديم شهادة لهذا الطلب بالفعل.');
        }

        // إنشاء شهادة جديدة
        $testimonial = Testimonial::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'comment' => $request->comment,
            'rating' => $request->rating,
            'status' => 'pending', // الحالة الافتراضية معلقة حتى موافقة المدير
        ]);

        return redirect()->route('home')->with('success', 'شكراً لك على شهادتك! سيتم مراجعتها قريباً.');
    }
}