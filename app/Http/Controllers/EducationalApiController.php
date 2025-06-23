<?php
// app/Http/Controllers/EducationalApiController.php

namespace App\Http\Controllers;

use App\Models\EducationalProductType;
use App\Models\Generation;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Platform;
use App\Models\EducationalPackage;
use App\Models\ShippingRegion;
use App\Models\EducationalPricing;
use App\Models\EducationalInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducationalApiController extends Controller
{
    /**
     * جلب أنواع المنتجات التعليمية
     */
    public function productTypes()
    {
        $productTypes = EducationalProductType::all()->map(function($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'code' => $type->code,
                'is_digital' => $type->is_digital,
                'requires_shipping' => $type->requires_shipping,
                'display_type' => $type->display_type,
                'description' => $type->full_description
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $productTypes
        ]);
    }

    /**
     * جلب الأجيال المتاحة
     */
    public function generations()
    {
        $generations = Generation::active()
                                ->whereHas('activeSubjects')
                                ->orderBy('birth_year', 'desc')
                                ->get()
                                ->map(function($generation) {
                                    return [
                                        'id' => $generation->id,
                                        'birth_year' => $generation->birth_year,
                                        'name' => $generation->name,
                                        'display_name' => $generation->display_name,
                                        'student_age' => $generation->student_age,
                                        'grade_level' => $generation->grade_level
                                    ];
                                });

        return response()->json([
            'success' => true,
            'data' => $generations
        ]);
    }

    /**
     * جلب المواد حسب الجيل
     */
    public function subjects(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'generation_id' => 'required|exists:generations,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'معرف الجيل مطلوب',
                'errors' => $validator->errors()
            ], 422);
        }

        $subjects = Subject::active()
                          ->byGeneration($request->generation_id)
                          ->withActiveTeachers()
                          ->with('generation')
                          ->orderBy('name')
                          ->get()
                          ->map(function($subject) {
                              return [
                                  'id' => $subject->id,
                                  'name' => $subject->name,
                                  'code' => $subject->code,
                                  'full_name' => $subject->full_name,
                                  'teachers_count' => $subject->active_teachers_count
                              ];
                          });

        return response()->json([
            'success' => true,
            'data' => $subjects
        ]);
    }

    /**
     * جلب المعلمين حسب المادة
     */
    public function teachers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'معرف المادة مطلوب',
                'errors' => $validator->errors()
            ], 422);
        }

        $teachers = Teacher::active()
                          ->bySubject($request->subject_id)
                          ->withActivePlatforms()
                          ->with(['subject.generation'])
                          ->orderBy('name')
                          ->get()
                          ->map(function($teacher) {
                              return [
                                  'id' => $teacher->id,
                                  'name' => $teacher->name,
                                  'specialization' => $teacher->specialization,
                                  'bio' => $teacher->bio,
                                  'image_url' => $teacher->image_url,
                                  'full_info' => $teacher->full_info,
                                  'teaching_info' => $teacher->teaching_info,
                                  'platforms_count' => $teacher->active_platforms_count
                              ];
                          });

        return response()->json([
            'success' => true,
            'data' => $teachers
        ]);
    }

    /**
     * جلب المنصات حسب المعلم
     */
    public function platforms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'معرف المعلم مطلوب',
                'errors' => $validator->errors()
            ], 422);
        }

        $platforms = Platform::active()
                            ->byTeacher($request->teacher_id)
                            ->withActivePackages()
                            ->with('teacher')
                            ->orderBy('name')
                            ->get()
                            ->map(function($platform) {
                                return [
                                    'id' => $platform->id,
                                    'name' => $platform->name,
                                    'description' => $platform->description,
                                    'website_url' => $platform->formatted_website_url,
                                    'full_info' => $platform->full_info,
                                    'teaching_chain' => $platform->teaching_chain,
                                    'packages_count' => $platform->active_packages_count
                                ];
                            });

        return response()->json([
            'success' => true,
            'data' => $platforms
        ]);
    }

    /**
     * جلب الباقات حسب نوع المنتج والمنصة
     */
    public function packages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_type_id' => 'required|exists:educational_product_types,id',
            'platform_id' => 'required|exists:platforms,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'معرف نوع المنتج والمنصة مطلوبان',
                'errors' => $validator->errors()
            ], 422);
        }

        $packages = EducationalPackage::active()
                                    ->byProductType($request->product_type_id)
                                    ->byPlatform($request->platform_id)
                                    ->with(['productType', 'platform'])
                                    ->orderBy('name')
                                    ->get()
                                    ->map(function($package) {
                                        return [
                                            'id' => $package->id,
                                            'name' => $package->name,
                                            'description' => $package->description,
                                            'package_type' => $package->package_type,
                                            'is_digital' => $package->is_digital,
                                            'requires_shipping' => $package->requires_shipping,
                                            'duration_display' => $package->duration_display,
                                            'lessons_display' => $package->lessons_display,
                                            'pages_display' => $package->pages_display,
                                            'weight_display' => $package->weight_display,
                                            'full_info' => $package->full_info
                                        ];
                                    });

        return response()->json([
            'success' => true,
            'data' => $packages
        ]);
    }

    /**
     * جلب مناطق الشحن (للدوسيات فقط)
     */
    public function regions()
    {
        $regions = ShippingRegion::active()
                                ->orderBy('name')
                                ->get()
                                ->map(function($region) {
                                    return [
                                        'id' => $region->id,
                                        'name' => $region->name,
                                        'shipping_cost' => $region->shipping_cost,
                                        'formatted_shipping_cost' => $region->formatted_shipping_cost,
                                        'is_free_shipping' => $region->isFreeShipping(),
                                        'full_info' => $region->full_info
                                    ];
                                });

        return response()->json([
            'success' => true,
            'data' => $regions
        ]);
    }

    /**
     * حساب السعر النهائي
     */
    public function calculatePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'generation_id' => 'required|exists:generations,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'platform_id' => 'required|exists:platforms,id',
            'package_id' => 'required|exists:educational_packages,id',
            'region_id' => 'nullable|exists:shipping_regions,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // التحقق من أن الباقة رقمية إذا لم يتم تحديد منطقة
        $package = EducationalPackage::find($request->package_id);
        if ($package->requires_shipping && !$request->region_id) {
            return response()->json([
                'success' => false,
                'message' => 'منطقة الشحن مطلوبة للدوسيات الورقية'
            ], 422);
        }

        // جلب التسعير
        $pricing = EducationalPricing::active()
                                   ->forSelection(
                                       $request->generation_id,
                                       $request->subject_id,
                                       $request->teacher_id,
                                       $request->platform_id,
                                       $request->package_id,
                                       $request->region_id
                                   )
                                   ->with(['region', 'package'])
                                   ->first();

        if (!$pricing) {
            return response()->json([
                'success' => false,
                'message' => 'التسعير غير متوفر لهذا الاختيار'
            ], 404);
        }

        // التحقق من المخزون للدوسيات الورقية
        $availableQuantity = null;
        if (!$package->is_digital) {
            $inventory = EducationalInventory::forSelection(
                $request->generation_id,
                $request->subject_id,
                $request->teacher_id,
                $request->platform_id,
                $request->package_id
            )->first();

            if (!$inventory || !$inventory->isInStock($request->quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكمية المطلوبة غير متوفرة في المخزون',
                    'available_quantity' => $inventory ? $inventory->actual_available : 0
                ], 400);
            }

            $availableQuantity = $inventory->actual_available;
        }

        // حساب التكلفة
        $calculation = $pricing->calculateTotal($request->quantity);

        $response = [
            'success' => true,
            'data' => [
                'unit_price' => $calculation['unit_price'],
                'shipping_cost' => $calculation['total_shipping'],
                'subtotal' => $calculation['subtotal'],
                'total_price' => $calculation['total_cost'],
                'quantity' => $request->quantity,
                'product_info' => $pricing->product_info,
                'is_digital' => $package->is_digital,
                'requires_shipping' => $package->requires_shipping,
                'formatted_unit_price' => number_format($calculation['unit_price'], 2) . ' دينار',
                'formatted_shipping_cost' => $calculation['total_shipping'] > 0 ? number_format($calculation['total_shipping'], 2) . ' دينار' : 'مجاني',
                'formatted_total_price' => number_format($calculation['total_cost'], 2) . ' دينار'
            ]
        ];

        // إضافة معلومات المخزون للدوسيات
        if (!$package->is_digital && $availableQuantity !== null) {
            $response['data']['available_quantity'] = $availableQuantity;
            $response['data']['stock_status'] = $availableQuantity > 10 ? 'متوفر' : 'كمية قليلة';
        }

        return response()->json($response);
    }

    /**
     * التحقق من توفر المخزون
     */
    public function checkInventory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'generation_id' => 'required|exists:generations,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'platform_id' => 'required|exists:platforms,id',
            'package_id' => 'required|exists:educational_packages,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // التحقق من أن الباقة تحتاج مخزون (ورقية)
        $package = EducationalPackage::find($request->package_id);
        if ($package->is_digital) {
            return response()->json([
                'success' => true,
                'data' => [
                    'is_digital' => true,
                    'available' => true,
                    'message' => 'المنتج الرقمي متوفر دائماً'
                ]
            ]);
        }

        $inventory = EducationalInventory::forSelection(
            $request->generation_id,
            $request->subject_id,
            $request->teacher_id,
            $request->platform_id,
            $request->package_id
        )->first();

        if (!$inventory) {
            return response()->json([
                'success' => false,
                'data' => [
                    'available' => false,
                    'message' => 'المنتج غير متوفر في المخزون'
                ]
            ]);
        }

        $isAvailable = $inventory->isInStock($request->quantity);

        return response()->json([
            'success' => true,
            'data' => [
                'is_digital' => false,
                'available' => $isAvailable,
                'available_quantity' => $inventory->actual_available,
                'requested_quantity' => $request->quantity,
                'stock_status' => $inventory->stock_status,
                'message' => $isAvailable ? 'المنتج متوفر' : 'الكمية المطلوبة غير متوفرة'
            ]
        ]);
    }
}