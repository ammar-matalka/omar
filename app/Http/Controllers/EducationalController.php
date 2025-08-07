<?php
// app/Http/Controllers/EducationalController.php (المُصحح بالكامل)

namespace App\Http\Controllers;

use App\Models\EducationalProductType;
use App\Models\EducationalCard;
use App\Models\EducationalPackage;
use App\Models\Category;
use App\Models\Generation;
use App\Models\ShippingRegion;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EducationalController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للنظام التعليمي
     */
    public function index()
    {
        // جلب أنواع المنتجات من قاعدة البيانات
        $productTypes = $this->getDefaultProductTypes();
        
        // جلب الفئات للتخطيط
        $categories = Category::all();

        // جلب إحصائيات منفصلة للبطاقات الرقمية والدوسيات الورقية
        $digitalCardsCount = 0;
        $physicalBookletsCount = 0;
        $latestDigitalCards = collect();
        $latestPhysicalBooklets = collect();

        try {
            // Get digital product type
            $digitalProductType = EducationalProductType::getDigitalCardType();
            if ($digitalProductType) {
                $digitalCardsCount = EducationalPackage::where('product_type_id', $digitalProductType->id)
                                                     ->where('is_active', true)
                                                     ->count();

                $latestDigitalCards = EducationalPackage::where('product_type_id', $digitalProductType->id)
                                                       ->where('is_active', true)
                                                       ->with(['platform.teacher.subject.generation'])
                                                       ->latest()
                                                       ->take(6)
                                                       ->get();
            }

            // Get physical product type
            $physicalProductType = EducationalProductType::getPhysicalBookletType();
            if ($physicalProductType) {
                $physicalBookletsCount = EducationalPackage::where('product_type_id', $physicalProductType->id)
                                                          ->where('is_active', true)
                                                          ->count();

                $latestPhysicalBooklets = EducationalPackage::where('product_type_id', $physicalProductType->id)
                                                           ->where('is_active', true)
                                                           ->with(['platform.teacher.subject.generation'])
                                                           ->latest()
                                                           ->take(6)
                                                           ->get();
            }
        } catch (\Exception $e) {
            Log::warning('Error loading educational data: ' . $e->getMessage());
        }
        
        return view('educational.index', compact(
            'productTypes', 
            'categories', 
            'digitalCardsCount', 
            'physicalBookletsCount',
            'latestDigitalCards',
            'latestPhysicalBooklets'
        ));
    }

    /**
     * عرض البطاقات الرقمية فقط
     */
    public function digitalCards(Request $request)
    {
        $digitalProductType = EducationalProductType::getDigitalCardType();
        
        if (!$digitalProductType) {
            return redirect()->route('educational.index')
                ->with('error', 'نوع المنتج الرقمي غير متوفر في النظام');
        }

        $query = EducationalPackage::where('product_type_id', $digitalProductType->id)
                                 ->where('is_active', true)
                                 ->with(['platform.teacher.subject.generation', 'educationalPricing']);

        // فلترة حسب الجيل
        if ($request->filled('generation_id')) {
            $query->whereHas('platform.teacher.subject', function($q) use ($request) {
                $q->where('generation_id', $request->generation_id);
            });
        }

        // فلترة حسب المادة
        if ($request->filled('subject_id')) {
            $query->whereHas('platform.teacher', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        // فلترة حسب المعلم
        if ($request->filled('teacher_id')) {
            $query->whereHas('platform', function($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }

        // فلترة حسب مدة الصلاحية
        if ($request->filled('duration')) {
            switch ($request->duration) {
                case 'month':
                    $query->where('duration_days', '<=', 31);
                    break;
                case 'quarter':
                    $query->whereBetween('duration_days', [32, 90]);
                    break;
                case 'semester':
                    $query->whereBetween('duration_days', [91, 180]);
                    break;
                case 'year':
                    $query->where('duration_days', '>', 180);
                    break;
                case 'unlimited':
                    $query->whereNull('duration_days');
                    break;
            }
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $digitalCards = $query->orderBy('name')->paginate(12);

        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();
        $categories = Category::all();
        
        return view('educational.digital-cards', compact(
            'digitalCards', 
            'generations', 
            'subjects', 
            'teachers', 
            'categories'
        ));
    }

    /**
     * عرض الدوسيات الورقية فقط
     */
    public function physicalBooklets(Request $request)
    {
        $physicalProductType = EducationalProductType::getPhysicalBookletType();
        
        if (!$physicalProductType) {
            return redirect()->route('educational.index')
                ->with('error', 'نوع المنتج الورقي غير متوفر في النظام');
        }

        $query = EducationalPackage::where('product_type_id', $physicalProductType->id)
                                 ->where('is_active', true)
                                 ->with(['platform.teacher.subject.generation', 'educationalPricing', 'educationalInventory']);

        // فلترة حسب الجيل
        if ($request->filled('generation_id')) {
            $query->whereHas('platform.teacher.subject', function($q) use ($request) {
                $q->where('generation_id', $request->generation_id);
            });
        }

        // فلترة حسب المادة
        if ($request->filled('subject_id')) {
            $query->whereHas('platform.teacher', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        // فلترة حسب المعلم
        if ($request->filled('teacher_id')) {
            $query->whereHas('platform', function($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }

        // فلترة حسب حالة المخزون
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->whereHas('educationalInventory', function($q) {
                        $q->where('quantity_available', '>', 0);
                    });
                    break;
                case 'out_of_stock':
                    $query->whereHas('educationalInventory', function($q) {
                        $q->where('quantity_available', '<=', 0);
                    });
                    break;
                case 'no_inventory':
                    $query->whereDoesntHave('educationalInventory');
                    break;
            }
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $physicalBooklets = $query->orderBy('name')->paginate(12);

        $generations = Generation::active()->orderBy('birth_year', 'desc')->get();
        $subjects = Subject::active()->with('generation')->orderBy('name')->get();
        $teachers = Teacher::active()->with('subject')->orderBy('name')->get();
        $regions = ShippingRegion::active()->orderBy('name')->get();
        $categories = Category::all();
        
        return view('educational.physical-booklets', compact(
            'physicalBooklets', 
            'generations', 
            'subjects', 
            'teachers', 
            'regions', 
            'categories'
        ));
    }

    /**
     * عرض تفاصيل بطاقة رقمية
     */
    public function showDigitalCard(EducationalPackage $digitalCard)
    {
        $digitalProductType = EducationalProductType::getDigitalCardType();
        
        // التحقق من أن هذه بطاقة رقمية
        if (!$digitalProductType || $digitalCard->product_type_id !== $digitalProductType->id) {
            abort(404, 'البطاقة المطلوبة ليست بطاقة رقمية');
        }

        $digitalCard->load(['productType', 'platform.teacher.subject.generation', 'educationalPricing']);
        $categories = Category::all();
        
        return view('educational.digital-card-details', compact('digitalCard', 'categories'));
    }

    /**
     * عرض تفاصيل دوسية ورقية
     */
    public function showPhysicalBooklet(EducationalPackage $physicalBooklet)
    {
        $physicalProductType = EducationalProductType::getPhysicalBookletType();
        
        // التحقق من أن هذه دوسية ورقية
        if (!$physicalProductType || $physicalBooklet->product_type_id !== $physicalProductType->id) {
            abort(404, 'الدوسية المطلوبة ليست دوسية ورقية');
        }

        $physicalBooklet->load(['productType', 'platform.teacher.subject.generation', 'educationalPricing', 'educationalInventory']);
        $regions = ShippingRegion::active()->orderBy('name')->get();
        $categories = Category::all();
        
        return view('educational.physical-booklet-details', compact('physicalBooklet', 'regions', 'categories'));
    }

    /**
     * عرض نموذج اختيار المنتج التعليمي (مُصحح)
     */
    public function form(Request $request)
    {
        // التحقق من وجود المعامل المطلوب
        if (!$request->has('product_type')) {
            return redirect()->route('educational.index')
                ->with('info', 'يرجى اختيار نوع المنتج التعليمي');
        }

        // التحقق من صحة نوع المنتج
        $productType = $request->product_type;
        
        if (!in_array($productType, ['cards', 'booklets'])) {
            return redirect()->route('educational.index')
                ->withErrors(['error' => 'نوع المنتج غير صحيح. يرجى اختيار نوع المنتج من الصفحة الرئيسية.']);
        }

        // توجيه للصفحة المناسبة حسب الـ routes المتوفرة
        if ($productType === 'cards') {
            return redirect()->route('educational.digital-cards');
        } else {
            return redirect()->route('educational.physical-booklets');
        }
    }

    /**
     * عرض صفحة تفعيل البطاقة التعليمية
     */
    public function verifyCard()
    {
        $categories = Category::all();
        return view('educational.verify-card', compact('categories'));
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

        try {
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
        } catch (\Exception $e) {
            Log::error('Card verification error: ' . $e->getMessage());
            return back()->withErrors(['verification' => 'حدث خطأ في تفعيل البطاقة']);
        }
    }

    /**
     * عرض البطاقات التعليمية للمستخدم
     */
    public function myCards()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $categories = Category::all();

        // التحقق من وجود نموذج EducationalCard
        if (!class_exists(\App\Models\EducationalCard::class)) {
            return view('educational.my-cards', [
                'usedCards' => collect(),
                'purchasedCards' => collect(),
                'categories' => $categories,
                'message' => 'نظام البطاقات التعليمية غير متوفر حالياً'
            ]);
        }

        try {
            $digitalProductType = EducationalProductType::getDigitalCardType();
            
            if (!$digitalProductType) {
                return view('educational.my-cards', [
                    'usedCards' => collect(),
                    'purchasedCards' => collect(),
                    'categories' => $categories,
                    'message' => 'نوع المنتج الرقمي غير متوفر'
                ]);
            }

            // جلب البطاقات المفعلة من قبل المستخدم (البطاقات الرقمية فقط)
            $usedCards = EducationalCard::where('used_by_student_id', Auth::id())
                                       ->with(['generation', 'subject', 'teacher', 'platform', 'package'])
                                       ->whereHas('package', function($q) use ($digitalProductType) {
                                           $q->where('product_type_id', $digitalProductType->id);
                                       })
                                       ->latest('used_at')
                                       ->paginate(10, ['*'], 'used_page');

            // جلب البطاقات المشتراة (لم يتم تفعيلها بعد) - البطاقات الرقمية فقط
            $purchasedCards = EducationalCard::whereHas('orderItem.order', function($query) {
                                                $query->where('user_id', Auth::id());
                                            })
                                            ->where('status', 'active')
                                            ->whereNull('used_by_student_id')
                                            ->whereHas('package', function($q) use ($digitalProductType) {
                                                $q->where('product_type_id', $digitalProductType->id);
                                            })
                                            ->with(['generation', 'subject', 'teacher', 'platform', 'package'])
                                            ->latest()
                                            ->paginate(10, ['*'], 'purchased_page');

            return view('educational.my-cards', compact('usedCards', 'purchasedCards', 'categories'));
        } catch (\Exception $e) {
            Log::error('Error loading my cards: ' . $e->getMessage());
            
            return view('educational.my-cards', [
                'usedCards' => collect(),
                'purchasedCards' => collect(),
                'categories' => $categories,
                'message' => 'حدث خطأ في تحميل البطاقات'
            ]);
        }
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
        
        return view('educational.card-details', compact('card', 'categories'));
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

        try {
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
        } catch (\Exception $e) {
            Log::error('Error updating expired cards: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحديث البطاقات المنتهية'
            ]);
        }
    }

    /**
     * الحصول على أنواع المنتجات الافتراضية
     */
    private function getDefaultProductTypes()
    {
        // التحقق من وجود الجدول في قاعدة البيانات
        try {
            if (class_exists(\App\Models\EducationalProductType::class)) {
                $dbProductTypes = EducationalProductType::all();
                
                // إذا كان الجدول يحتوي على بيانات، استخدمها
                if ($dbProductTypes->count() > 0) {
                    return $dbProductTypes->map(function ($type) {
                        return (object) [
                            'id' => $type->id,
                            'name' => $type->name,
                            'code' => $type->is_digital ? 'cards' : 'booklets',
                            'is_digital' => $type->is_digital,
                            'requires_shipping' => $type->requires_shipping,
                            'display_type' => $type->is_digital ? 'رقمي' : 'ورقي',
                            'full_description' => $type->name . ' (' . ($type->is_digital ? 'رقمي - بدون شحن' : 'ورقي - مع شحن') . ')'
                        ];
                    });
                }
            }
        } catch (\Exception $e) {
            // في حالة عدم وجود الجدول أو حدوث خطأ، استخدم البيانات الثابتة
            Log::warning('Could not load EducationalProductType from database: ' . $e->getMessage());
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

    /**
     * Get educational package by ID (helper method for routes)
     */
    public function getPackage($id)
    {
        $package = EducationalPackage::with('productType')->findOrFail($id);
        
        $digitalProductType = EducationalProductType::getDigitalCardType();
        $physicalProductType = EducationalProductType::getPhysicalBookletType();
        
        if ($digitalProductType && $package->product_type_id === $digitalProductType->id) {
            return $this->showDigitalCard($package);
        } elseif ($physicalProductType && $package->product_type_id === $physicalProductType->id) {
            return $this->showPhysicalBooklet($package);
        }
        
        abort(404, 'المنتج التعليمي غير موجود');
    }
}