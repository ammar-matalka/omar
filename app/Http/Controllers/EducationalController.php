<?php
// app/Http/Controllers/EducationalController.php (النسخة المُحدثة)

namespace App\Http\Controllers;

use App\Models\EducationalProductType;
use App\Models\EducationalCard;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationalController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للنظام التعليمي
     */
    public function index()
    {
        // استخدام بيانات ثابتة بدلاً من قاعدة البيانات مؤقتاً
        $productTypes = $this->getDefaultProductTypes();
        
        // جلب الفئات للتخطيط
        $categories = Category::all();
        
        return view('educational.index', compact('productTypes', 'categories'));
    }

    /**
     * عرض نموذج اختيار المنتج التعليمي
     */
    public function form(Request $request)
    {
        $request->validate([
            'product_type' => 'required|in:cards,booklets'
        ]);

        // استخدام بيانات ثابتة
        $productTypes = $this->getDefaultProductTypes();
        $productType = collect($productTypes)->firstWhere('code', $request->product_type);
        
        if (!$productType) {
            return redirect()->route('educational.index')->withErrors(['error' => 'نوع المنتج غير صحيح']);
        }
        
        $categories = Category::all();

        // تحديد نوع الفورم المطلوب
        $viewName = $request->product_type === 'cards' ? 'educational.digital-form' : 'educational.physical-form';
        
        return view($viewName, compact('productType', 'categories'));
    }

    /**
     * عرض صفحة تفعيل البطاقة التعليمية
     */
    public function verifyCard()
    {
        $categories = Category::all();
        return view('educational.cards.verify', compact('categories'));
    }

    /**
     * معالجة تفعيل البطاقة التعليمية
     */
    public function processCardVerification(Request $request)
    {
        $request->validate([
            'card_code' => 'required|string',
            'pin_code' => 'required|string|size:6'
        ], [
            'card_code.required' => 'كود البطاقة مطلوب',
            'pin_code.required' => 'الرقم السري مطلوب',
            'pin_code.size' => 'الرقم السري يجب أن يكون 6 أرقام'
        ]);

        // التحقق من وجود نموذج EducationalCard
        if (!class_exists(\App\Models\EducationalCard::class)) {
            return back()->withErrors(['verification' => 'نظام البطاقات التعليمية غير متوفر حالياً']);
        }

        $verification = EducationalCard::verifyCard($request->card_code, $request->pin_code);

        if (!$verification['valid']) {
            return back()->withErrors(['verification' => $verification['message']]);
        }

        $card = $verification['card'];

        // تفعيل البطاقة
        if (!$card->useByStudent(Auth::user())) {
            return back()->withErrors(['verification' => 'فشل في تفعيل البطاقة']);
        }

        return redirect()->back()->with('success', 'تم تفعيل البطاقة بنجاح!')
                                 ->with('card_info', $card->card_info);
    }

    /**
     * عرض البطاقات التعليمية للمستخدم
     */
    public function myCards()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // التحقق من وجود نموذج EducationalCard
        if (!class_exists(\App\Models\EducationalCard::class)) {
            $categories = Category::all();
            return view('educational.cards.my-cards', [
                'usedCards' => collect(),
                'purchasedCards' => collect(),
                'categories' => $categories,
                'message' => 'نظام البطاقات التعليمية غير متوفر حالياً'
            ]);
        }

        // جلب البطاقات المفعلة من قبل المستخدم
        $usedCards = EducationalCard::where('used_by_student_id', Auth::id())
                                   ->with(['generation', 'subject', 'teacher', 'platform', 'package'])
                                   ->latest('used_at')
                                   ->paginate(10);

        // جلب البطاقات المشتراة (لم يتم تفعيلها بعد)
        $purchasedCards = EducationalCard::whereHas('orderItem.order', function($query) {
                                            $query->where('user_id', Auth::id());
                                        })
                                        ->where('status', 'active')
                                        ->whereNull('used_by_student_id')
                                        ->with(['generation', 'subject', 'teacher', 'platform', 'package'])
                                        ->latest()
                                        ->paginate(10);

        $categories = Category::all();

        return view('educational.cards.my-cards', compact('usedCards', 'purchasedCards', 'categories'));
    }

    /**
     * عرض تفاصيل البطاقة التعليمية
     */
    public function showCard(EducationalCard $card)
    {
        // التحقق من أن المستخدم يملك هذه البطاقة
        $hasAccess = false;
        
        if ($card->used_by_student_id === Auth::id()) {
            $hasAccess = true;
        } elseif ($card->orderItem && $card->orderItem->order->user_id === Auth::id()) {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            abort(403, 'ليس لديك صلاحية لعرض هذه البطاقة');
        }

        $categories = Category::all();
        
        return view('educational.cards.show', compact('card', 'categories'));
    }

    /**
     * تحديث انتهاء صلاحية البطاقات المنتهية
     */
    public function updateExpiredCards()
    {
        // التحقق من وجود نموذج EducationalCard
        if (!class_exists(\App\Models\EducationalCard::class)) {
            return response()->json([
                'success' => false,
                'message' => 'نظام البطاقات التعليمية غير متوفر حالياً'
            ]);
        }

        $expiredCards = EducationalCard::where('status', 'active')
                                      ->where('expires_at', '<', now())
                                      ->get();

        foreach ($expiredCards as $card) {
            $card->checkExpiration();
        }

        return response()->json([
            'success' => true,
            'updated' => $expiredCards->count()
        ]);
    }

    /**
     * الحصول على أنواع المنتجات الافتراضية
     */
    private function getDefaultProductTypes()
    {
        // التحقق من وجود الجدول في قاعدة البيانات
        try {
            if (class_exists(\App\Models\EducationalProductType::class)) {
                return EducationalProductType::all();
            }
        } catch (\Exception $e) {
            // في حالة عدم وجود الجدول، استخدم البيانات الثابتة
        }

        // بيانات ثابتة كبديل
        return collect([
            (object) [
                'id' => 1,
                'name' => 'بطاقات تعليمية رقمية',
                'code' => 'cards',
                'is_digital' => true,
                'requires_shipping' => false,
                'display_type' => 'رقمي',
                'full_description' => 'بطاقات تعليمية رقمية (رقمي - بدون شحن)'
            ],
            (object) [
                'id' => 2,
                'name' => 'دوسيات ورقية',
                'code' => 'booklets',
                'is_digital' => false,
                'requires_shipping' => true,
                'display_type' => 'ورقي',
                'full_description' => 'دوسيات ورقية (ورقي - مع شحن)'
            ]
        ]);
    }
}