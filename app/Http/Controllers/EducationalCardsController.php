<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationalGeneration;
use App\Models\EducationalSubject;
use App\Models\EducationalCardOrder;
use App\Models\EducationalCardOrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EducationalCardsController extends Controller
{
    /**
     * Display educational cards page with generations
     */
    public function index()
    {
        $generations = EducationalGeneration::active()
            ->ordered()
            ->withCount('subjects')
            ->get();

        return view('educational-cards.index', compact('generations'));
    }

    /**
     * Get subjects for a specific generation (AJAX)
     */
    public function getSubjects(Request $request, $generationId)
    {
        $subjects = EducationalSubject::active()
            ->forGeneration($generationId)
            ->ordered()
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'subjects' => $subjects->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'price' => $subject->price,
                        'formatted_price' => $subject->formatted_price
                    ];
                })
            ]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Submit educational card order
     */
    public function submitOrder(Request $request)
    {
        $request->validate([
            'generation_id' => 'required|exists:educational_generations,id',
            'student_name' => 'required|string|max:255',
            'semester' => 'required|in:first,second,both',
            'quantity' => 'required|integer|min:1|max:10',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:educational_subjects,id'
        ], [
            'generation_id.required' => 'يرجى اختيار الجيل',
            'generation_id.exists' => 'الجيل المحدد غير موجود',
            'student_name.required' => 'يرجى إدخال اسم الطالب',
            'student_name.max' => 'اسم الطالب لا يجب أن يتجاوز 255 حرف',
            'semester.required' => 'يرجى اختيار الفصل',
            'semester.in' => 'قيمة الفصل غير صحيحة',
            'quantity.required' => 'يرجى إدخال الكمية',
            'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'quantity.max' => 'الكمية لا يجب أن تتجاوز 10',
            'subjects.required' => 'يرجى اختيار مادة واحدة على الأقل',
            'subjects.min' => 'يرجى اختيار مادة واحدة على الأقل',
            'subjects.*.exists' => 'إحدى المواد المحددة غير موجودة'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // Get selected subjects with their prices
                $subjects = EducationalSubject::whereIn('id', $request->subjects)
                    ->where('is_active', true)
                    ->get();

                if ($subjects->isEmpty()) {
                    return back()->withErrors(['subjects' => 'لم يتم العثور على المواد المحددة']);
                }

                // Calculate total amount
                $totalAmount = $subjects->sum('price') * $request->quantity;

                // Create the order
                $order = EducationalCardOrder::create([
                    'user_id' => Auth::id(),
                    'generation_id' => $request->generation_id,
                    'student_name' => $request->student_name,
                    'semester' => $request->semester,
                    'quantity' => $request->quantity,
                    'total_amount' => $totalAmount,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'notes' => $request->notes,
                    'status' => 'pending'
                ]);

                // Create order items
                foreach ($subjects as $subject) {
                    EducationalCardOrderItem::create([
                        'order_id' => $order->id,
                        'subject_id' => $subject->id,
                        'subject_name' => $subject->name,
                        'price' => $subject->price,
                        'quantity' => $request->quantity
                    ]);
                }

                return redirect()->route('educational-cards.index')
                    ->with('success', 'تم إرسال طلبك بنجاح! سيتم التواصل معك قريباً.');
            });

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى.'])
                ->withInput();
        }
    }

    /**
     * Show user's educational card orders
     */
    public function myOrders()
    {
        $orders = EducationalCardOrder::where('user_id', Auth::id())
            ->with(['generation', 'orderItems.subject'])
            ->recent()
            ->paginate(10);

        return view('educational-cards.my-orders', compact('orders'));
    }

    /**
     * Show specific order details
     */
    public function showOrder(EducationalCardOrder $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض هذا الطلب');
        }

        $order->load(['generation', 'orderItems.subject']);

        return view('educational-cards.show-order', compact('order'));
    }

    /**
     * Search for orders (for user)
     */
    public function searchOrders(Request $request)
    {
        $query = EducationalCardOrder::where('user_id', Auth::id())
            ->with(['generation', 'orderItems.subject']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }

        if ($request->filled('student_name')) {
            $query->where('student_name', 'like', '%' . $request->student_name . '%');
        }

        $orders = $query->recent()->paginate(10);
        $generations = EducationalGeneration::active()->ordered()->get();

        return view('educational-cards.my-orders', compact('orders', 'generations'));
    }
}