<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationalGeneration;
use App\Models\EducationalSubject;
use App\Models\Teacher;
use App\Models\Platform;
use App\Models\Dossier;
use App\Models\EducationalOrder;
use App\Models\EducationalOrderItem;
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
     * Get teachers for subject and generation (AJAX)
     */
    public function getTeachers(Request $request, $generationId, $subjectId)
    {
        $teachers = Teacher::active()
            ->whereHas('dossiers', function($query) use ($generationId, $subjectId) {
                $query->where('generation_id', $generationId)
                      ->where('subject_id', $subjectId)
                      ->where('is_active', true);
            })
            ->ordered()
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'teachers' => $teachers->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                        'specialization' => $teacher->specialization
                    ];
                })
            ]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Get platforms for teacher (AJAX)
     */
    public function getPlatforms(Request $request, $generationId, $subjectId, $teacherId)
    {
        $platforms = Platform::active()
            ->whereHas('dossiers', function($query) use ($generationId, $subjectId, $teacherId) {
                $query->where('generation_id', $generationId)
                      ->where('subject_id', $subjectId)
                      ->where('teacher_id', $teacherId)
                      ->where('is_active', true);
            })
            ->ordered()
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'platforms' => $platforms->map(function ($platform) {
                    return [
                        'id' => $platform->id,
                        'name' => $platform->name,
                        'price_percentage' => $platform->price_percentage
                    ];
                })
            ]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Get dossiers for platform (AJAX)
     */
    public function getDossiers(Request $request, $generationId, $subjectId, $teacherId, $platformId, $semester)
    {
        $query = Dossier::active()
            ->where('generation_id', $generationId)
            ->where('subject_id', $subjectId)
            ->where('teacher_id', $teacherId)
            ->where('platform_id', $platformId)
            ->with(['teacher', 'platform']);

        // Filter by semester
        if ($semester !== 'both') {
            $query->where(function($q) use ($semester) {
                $q->where('semester', $semester)
                  ->orWhere('semester', 'both');
            });
        }

        $dossiers = $query->ordered()->get();

        if ($request->ajax()) {
            return response()->json([
                'dossiers' => $dossiers->map(function ($dossier) {
                    return [
                        'id' => $dossier->id,
                        'name' => $dossier->name,
                        'price' => $dossier->price,
                        'final_price' => $dossier->final_price,
                        'formatted_final_price' => $dossier->formatted_final_price,
                        'pages_count' => $dossier->pages_count,
                        'file_size' => $dossier->file_size
                    ];
                })
            ]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Submit educational order
     */
    public function submitOrder(Request $request)
    {
        $request->validate([
            'generation_id' => 'required|exists:educational_generations,id',
            'student_name' => 'required|string|max:255',
            'order_type' => 'required|in:card,dossier',
            'semester' => 'required|in:first,second,both',
            'quantity' => 'required|integer|min:1|max:10',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ], [
            'generation_id.required' => 'يرجى اختيار الجيل',
            'generation_id.exists' => 'الجيل المحدد غير موجود',
            'student_name.required' => 'يرجى إدخال اسم الطالب',
            'student_name.max' => 'اسم الطالب لا يجب أن يتجاوز 255 حرف',
            'order_type.required' => 'يرجى اختيار نوع الطلب',
            'order_type.in' => 'نوع الطلب غير صحيح',
            'semester.required' => 'يرجى اختيار الفصل',
            'semester.in' => 'قيمة الفصل غير صحيحة',
            'quantity.required' => 'يرجى إدخال الكمية',
            'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'quantity.max' => 'الكمية لا يجب أن تتجاوز 10',
        ]);

        // Additional validation based on order type
        if ($request->order_type === 'card') {
            $request->validate([
                'subjects' => 'required|array|min:1',
                'subjects.*' => 'exists:educational_subjects,id',
                'teacher_id' => 'required|exists:teachers,id',
                'platform_id' => 'required|exists:platforms,id',
            ], [
                'subjects.required' => 'يرجى اختيار مادة واحدة على الأقل',
                'subjects.min' => 'يرجى اختيار مادة واحدة على الأقل',
                'subjects.*.exists' => 'إحدى المواد المحددة غير موجودة',
                'teacher_id.required' => 'يرجى اختيار المعلم',
                'teacher_id.exists' => 'المعلم المحدد غير موجود',
                'platform_id.required' => 'يرجى اختيار المنصة',
                'platform_id.exists' => 'المنصة المحددة غير موجودة',
            ]);
        } else {
            $request->validate([
                'dossiers' => 'required|array|min:1',
                'dossiers.*' => 'exists:dossiers,id',
            ], [
                'dossiers.required' => 'يرجى اختيار دوسية واحدة على الأقل',
                'dossiers.min' => 'يرجى اختيار دوسية واحدة على الأقل',
                'dossiers.*.exists' => 'إحدى الدوسيات المحددة غير موجودة',
            ]);
        }

        try {
            return DB::transaction(function () use ($request) {
                $totalAmount = 0;

                // Create the order
                $order = EducationalOrder::create([
                    'user_id' => Auth::id(),
                    'generation_id' => $request->generation_id,
                    'student_name' => $request->student_name,
                    'order_type' => $request->order_type,
                    'semester' => $request->semester,
                    'quantity' => $request->quantity,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'notes' => $request->notes,
                    'status' => 'pending'
                ]);

                if ($request->order_type === 'card') {
                    // Handle educational cards
                    $subjects = EducationalSubject::whereIn('id', $request->subjects)
                        ->where('is_active', true)
                        ->get();

                    if ($subjects->isEmpty()) {
                        throw new \Exception('لم يتم العثور على المواد المحددة');
                    }

                    $teacher = Teacher::findOrFail($request->teacher_id);
                    $platform = Platform::findOrFail($request->platform_id);

                    foreach ($subjects as $subject) {
                        $price = $platform->calculatePrice($subject->price);
                        $totalAmount += $price * $request->quantity;

                        EducationalOrderItem::create([
                            'order_id' => $order->id,
                            'subject_id' => $subject->id,
                            'subject_name' => $subject->name,
                            'teacher_id' => $teacher->id,
                            'teacher_name' => $teacher->name,
                            'platform_id' => $platform->id,
                            'platform_name' => $platform->name,
                            'price' => $price,
                            'quantity' => $request->quantity
                        ]);
                    }
                } else {
                    // Handle dossiers
                    $dossiers = Dossier::whereIn('id', $request->dossiers)
                        ->where('is_active', true)
                        ->with(['teacher', 'platform'])
                        ->get();

                    if ($dossiers->isEmpty()) {
                        throw new \Exception('لم يتم العثور على الدوسيات المحددة');
                    }

                    foreach ($dossiers as $dossier) {
                        $price = $dossier->final_price;
                        $totalAmount += $price * $request->quantity;

                        EducationalOrderItem::create([
                            'order_id' => $order->id,
                            'dossier_id' => $dossier->id,
                            'dossier_name' => $dossier->name,
                            'teacher_id' => $dossier->teacher->id,
                            'teacher_name' => $dossier->teacher->name,
                            'platform_id' => $dossier->platform->id,
                            'platform_name' => $dossier->platform->name,
                            'price' => $price,
                            'quantity' => $request->quantity
                        ]);
                    }
                }

                // Update total amount
                $order->update(['total_amount' => $totalAmount]);

                return redirect()->route('educational-cards.index')
                    ->with('success', 'تم إرسال طلبك بنجاح! سيتم التواصل معك قريباً.');
            });

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء معالجة طلبك: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show user's educational orders
     */
    public function myOrders()
    {
        $orders = EducationalOrder::where('user_id', Auth::id())
            ->with(['generation', 'orderItems'])
            ->recent()
            ->paginate(10);

        return view('educational-cards.my-orders', compact('orders'));
    }

    /**
     * Show specific order details
     */
    public function showOrder(EducationalOrder $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض هذا الطلب');
        }

        $order->load(['generation', 'orderItems']);

        return view('educational-cards.show-order', compact('order'));
    }

    /**
     * Search for orders (for user)
     */
    public function searchOrders(Request $request)
    {
        $query = EducationalOrder::where('user_id', Auth::id())
            ->with(['generation', 'orderItems']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
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

    /**
     * Calculate price for order preview (AJAX)
     */
    public function calculatePrice(Request $request)
    {
        try {
            $totalPrice = 0;
            $items = [];

            if ($request->order_type === 'card') {
                $subjects = EducationalSubject::whereIn('id', $request->subjects)->get();
                $platform = Platform::find($request->platform_id);

                if (!$platform) {
                    throw new \Exception('المنصة غير موجودة');
                }

                foreach ($subjects as $subject) {
                    $price = $platform->calculatePrice($subject->price);
                    $totalPrice += $price;
                    $items[] = [
                        'name' => $subject->name,
                        'price' => $price,
                        'formatted_price' => number_format($price, 2) . ' JD'
                    ];
                }
            } else {
                $dossiers = Dossier::whereIn('id', $request->dossiers)->with('platform')->get();

                foreach ($dossiers as $dossier) {
                    $price = $dossier->final_price;
                    $totalPrice += $price;
                    $items[] = [
                        'name' => $dossier->name,
                        'price' => $price,
                        'formatted_price' => number_format($price, 2) . ' JD'
                    ];
                }
            }

            $quantity = (int) $request->quantity ?: 1;
            $finalTotal = $totalPrice * $quantity;

            return response()->json([
                'success' => true,
                'items' => $items,
                'total_price' => $totalPrice,
                'quantity' => $quantity,
                'final_total' => $finalTotal,
                'formatted_final_total' => number_format($finalTotal, 2) . ' JD'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}