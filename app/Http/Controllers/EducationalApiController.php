<?php
// app/Http/Controllers/EducationalApiController.php

namespace App\Http\Controllers;

use App\Models\Generation;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Platform;
use App\Models\EducationalPackage;
use App\Models\ShippingRegion;
use App\Models\EducationalPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EducationalApiController extends Controller
{
    /**
     * Get all active generations
     */
    public function generations()
    {
        try {
            $generations = Generation::where('is_active', true)
                ->orderBy('birth_year', 'desc')
                ->select('id', 'name', 'birth_year')
                ->get()
                ->map(function ($generation) {
                    return [
                        'id' => $generation->id,
                        'name' => $generation->name,
                        'birth_year' => $generation->birth_year
                    ];
                });

            return response()->json($generations);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل في تحميل الأجيال'], 500);
        }
    }

    /**
     * Get subjects by generation
     */
    public function subjects(Request $request)
    {
        try {
            $query = Subject::where('is_active', true);

            if ($request->filled('generation_id')) {
                $query->where('generation_id', $request->generation_id);
            }

            $subjects = $query->orderBy('name')
                ->select('id', 'name', 'generation_id')
                ->get()
                ->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'generation_id' => $subject->generation_id
                    ];
                });

            return response()->json($subjects);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل في تحميل المواد'], 500);
        }
    }

    /**
     * Get teachers by subject
     */
    public function teachers(Request $request)
    {
        try {
            $query = Teacher::where('is_active', true);

            if ($request->filled('subject_id')) {
                $query->where('subject_id', $request->subject_id);
            }

            $teachers = $query->orderBy('name')
                ->select('id', 'name', 'subject_id')
                ->get()
                ->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                        'subject_id' => $teacher->subject_id
                    ];
                });

            return response()->json($teachers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل في تحميل المعلمين'], 500);
        }
    }

    /**
     * Get platforms by teacher
     */
    public function platforms(Request $request)
    {
        try {
            $query = Platform::where('is_active', true);

            if ($request->filled('teacher_id')) {
                $query->where('teacher_id', $request->teacher_id);
            }

            $platforms = $query->orderBy('name')
                ->select('id', 'name', 'teacher_id')
                ->get()
                ->map(function ($platform) {
                    return [
                        'id' => $platform->id,
                        'name' => $platform->name,
                        'teacher_id' => $platform->teacher_id
                    ];
                });

            return response()->json($platforms);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل في تحميل المنصات'], 500);
        }
    }

    /**
     * Get packages by platform */
    public function packages(Request $request)
    {
        try {
            $query = EducationalPackage::where('is_active', true);

            if ($request->filled('platform_id')) {
                $query->where('platform_id', $request->platform_id);
            }

            $packages = $query->orderBy('name')
                ->select('id', 'name', 'platform_id', 'description')
                ->get();

            return response()->json($packages);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Get shipping regions
     */
    public function regions()
    {
        try {
            $regions = ShippingRegion::where('is_active', true)
                ->orderBy('name')
                ->select('id', 'name', 'shipping_cost')
                ->get()
                ->map(function ($region) {
                    return [
                        'id' => $region->id,
                        'name' => $region->name,
                        'shipping_cost' => $region->shipping_cost
                    ];
                });

            return response()->json($regions);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل في تحميل المناطق'], 500);
        }
    }

    /**
     * Calculate price for the given selection
     */
    public function calculatePrice(Request $request)
    {
        try {
            $request->validate([
                'generation_id' => 'required|exists:generations,id',
                'subject_id' => 'required|exists:subjects,id',
                'teacher_id' => 'required|exists:teachers,id',
                'platform_id' => 'required|exists:platforms,id',
                'package_id' => 'required|exists:educational_packages,id',
                'region_id' => 'nullable|exists:shipping_regions,id'
            ]);

            // البحث عن تسعير موجود
            $pricing = EducationalPricing::where('generation_id', $request->generation_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $request->teacher_id)
                ->where('platform_id', $request->platform_id)
                ->where('package_id', $request->package_id)
                ->where('region_id', $request->region_id)
                ->where('is_active', true)
                ->first();

            if ($pricing) {
                $shippingCost = $pricing->region ? $pricing->region->shipping_cost : 0;
                return response()->json([
                    'price' => $pricing->price,
                    'shipping_cost' => $shippingCost,
                    'total_cost' => $pricing->price + $shippingCost,
                    'exists' => true
                ]);
            }

            // إذا لم يوجد تسعير، نحسب سعر افتراضي
            $basePrice = 50.00; // سعر افتراضي

            return response()->json([
                'price' => $basePrice,
                'shipping_cost' => 0,
                'total_cost' => $basePrice,
                'exists' => false
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل في حساب السعر'], 500);
        }
    }

    /**
     * Check inventory availability
     */
    public function checkInventory(Request $request)
    {
        try {
            $request->validate([
                'generation_id' => 'required|exists:generations,id',
                'subject_id' => 'required|exists:subjects,id',
                'teacher_id' => 'required|exists:teachers,id',
                'platform_id' => 'required|exists:platforms,id',
                'package_id' => 'required|exists:educational_packages,id',
                'region_id' => 'nullable|exists:shipping_regions,id',
                'quantity' => 'required|integer|min:1'
            ]);

            // فحص المخزون (يمكن تطويره لاحقاً)
            return response()->json([
                'available' => true,
                'stock_quantity' => 100, // قيمة افتراضية
                'message' => 'متوفر في المخزون'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل في فحص المخزون'], 500);
        }
    }

    /**
     * Get product types
     */
    public function productTypes()
    {
        try {
            // التحقق من وجود جدول product_types أولاً
            if (Schema::hasTable('product_types')) {
                $productTypes = DB::table('product_types')->where('is_active', true)
                    ->orderBy('name')
                    ->select('id', 'name', 'code', 'is_digital', 'requires_shipping')
                    ->get();
            } else {
                // إذا لم يوجد الجدول، نعطي قيم افتراضية
                $productTypes = [
                    [
                        'id' => 1,
                        'name' => 'بطاقات تعليمية',
                        'code' => 'cards',
                        'is_digital' => true,
                        'requires_shipping' => false
                    ],
                    [
                        'id' => 2,
                        'name' => 'دوسيات ورقية',
                        'code' => 'booklets',
                        'is_digital' => false,
                        'requires_shipping' => true
                    ]
                ];
            }

            return response()->json($productTypes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'فشل في تحميل أنواع المنتجات'], 500);
        }
    }
}
