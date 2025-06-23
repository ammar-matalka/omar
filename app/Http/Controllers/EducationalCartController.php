<?php
// app/Http/Controllers/EducationalCartController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\EducationalPricing;
use App\Models\EducationalInventory;
use App\Models\EducationalPackage;
use App\Models\Generation;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Platform;
use App\Models\ShippingRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EducationalCartController extends Controller
{
    /**
     * إضافة منتج تعليمي إلى السلة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'generation_id' => 'required|exists:generations,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'platform_id' => 'required|exists:platforms,id',
            'package_id' => 'required|exists:educational_packages,id',
            'region_id' => 'nullable|exists:shipping_regions,id',
            'quantity' => 'required|integer|min:1|max:10'
        ], [
            'generation_id.required' => 'يجب اختيار الجيل',
            'subject_id.required' => 'يجب اختيار المادة',
            'teacher_id.required' => 'يجب اختيار المعلم',
            'platform_id.required' => 'يجب اختيار المنصة',
            'package_id.required' => 'يجب اختيار الباقة',
            'quantity.required' => 'يجب تحديد الكمية',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'quantity.max' => 'الكمية لا يمكن أن تتجاوز 10'
        ]);

        // التحقق من صحة السلسلة (generation -> subject -> teacher -> platform -> package)
        if (!$this->validateEducationalChain($validated)) {
            return back()->withErrors(['selection' => 'الاختيار غير صحيح، يرجى المحاولة مرة أخرى']);
        }

        // جلب الباقة للتحقق من نوعها
        $package = EducationalPackage::find($validated['package_id']);
        
        // التحقق من أن الباقة الورقية تحتاج منطقة شحن
        if ($package->requires_shipping && !$validated['region_id']) {
            return back()->withErrors(['region_id' => 'يجب اختيار منطقة الشحن للدوسيات الورقية']);
        }

        // التحقق من أن الباقة الرقمية لا تحتاج منطقة شحن
        if (!$package->requires_shipping && $validated['region_id']) {
            $validated['region_id'] = null; // إزالة المنطقة للمنتجات الرقمية
        }

        // جلب التسعير
        $pricing = EducationalPricing::active()
                                   ->forSelection(
                                       $validated['generation_id'],
                                       $validated['subject_id'],
                                       $validated['teacher_id'],
                                       $validated['platform_id'],
                                       $validated['package_id'],
                                       $validated['region_id']
                                   )
                                   ->first();

        if (!$pricing) {
            return back()->withErrors(['pricing' => 'التسعير غير متوفر لهذا الاختيار']);
        }

        // التحقق من المخزون للدوسيات الورقية
        if (!$package->is_digital) {
            $inventory = EducationalInventory::forSelection(
                $validated['generation_id'],
                $validated['subject_id'],
                $validated['teacher_id'],
                $validated['platform_id'],
                $validated['package_id']
            )->first();

            if (!$inventory || !$inventory->isInStock($validated['quantity'])) {
                $available = $inventory ? $inventory->actual_available : 0;
                return back()->withErrors(['quantity' => "الكمية المطلوبة غير متوفرة. المتاح: {$available}"]);
            }
        }

        DB::beginTransaction();

        try {
            // الحصول على سلة المستخدم أو إنشاؤها
            $cart = $this->getOrCreateCart();

            // التحقق من وجود نفس المنتج في السلة
            $existingCartItem = $cart->cartItems()
                                   ->where('is_educational', true)
                                   ->where('generation_id', $validated['generation_id'])
                                   ->where('subject_id', $validated['subject_id'])
                                   ->where('teacher_id', $validated['teacher_id'])
                                   ->where('platform_id', $validated['platform_id'])
                                   ->where('package_id', $validated['package_id'])
                                   ->where('region_id', $validated['region_id'])
                                   ->first();

            $shippingCost = $validated['region_id'] ? 
                          ShippingRegion::find($validated['region_id'])->shipping_cost : 0;

            if ($existingCartItem) {
                // تحديث الكمية في العنصر الموجود
                $newQuantity = $existingCartItem->quantity + $validated['quantity'];
                
                // إعادة التحقق من المخزون بالكمية الجديدة
                if (!$package->is_digital) {
                    $inventory = EducationalInventory::forSelection(
                        $validated['generation_id'],
                        $validated['subject_id'],
                        $validated['teacher_id'],
                        $validated['platform_id'],
                        $validated['package_id']
                    )->first();

                    if (!$inventory || !$inventory->isInStock($newQuantity)) {
                        DB::rollBack();
                        $available = $inventory ? $inventory->actual_available : 0;
                        return back()->withErrors(['quantity' => "الكمية الإجمالية تتجاوز المخزون المتاح. المتاح: {$available}"]);
                    }
                }

                $existingCartItem->update(['quantity' => $newQuantity]);
                $cartItem = $existingCartItem;
            } else {
                // إنشاء عنصر سلة جديد
                $cartItem = $cart->cartItems()->create([
                    'product_id' => null, // null للمنتجات التعليمية
                    'quantity' => $validated['quantity'],
                    'is_educational' => true,
                    'generation_id' => $validated['generation_id'],
                    'subject_id' => $validated['subject_id'],
                    'teacher_id' => $validated['teacher_id'],
                    'platform_id' => $validated['platform_id'],
                    'package_id' => $validated['package_id'],
                    'region_id' => $validated['region_id'],
                    'price' => $pricing->price,
                    'shipping_cost' => $shippingCost
                ]);
            }

            // حجز المخزون للدوسيات الورقية
            if (!$package->is_digital) {
                $inventory = EducationalInventory::forSelection(
                    $validated['generation_id'],
                    $validated['subject_id'],
                    $validated['teacher_id'],
                    $validated['platform_id'],
                    $validated['package_id']
                )->first();

                if ($inventory) {
                    $inventory->reserveQuantity($validated['quantity']);
                }
            }

            DB::commit();

            $message = $package->is_digital ? 
                      'تم إضافة البطاقة التعليمية إلى السلة بنجاح' : 
                      'تم إضافة الدوسية إلى السلة بنجاح';

            return redirect()->route('cart.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء إضافة المنتج إلى السلة']);
        }
    }

    /**
     * تحديث عنصر تعليمي في السلة
     */
    public function update(Request $request, CartItem $cartItem)
    {
        // التحقق من أن العنصر تعليمي وينتمي للمستخدم
        if (!$cartItem->is_educational || $cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'إجراء غير مخول');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ], [
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'quantity.max' => 'الكمية لا يمكن أن تتجاوز 10'
        ]);

        // التحقق من المخزون للدوسيات الورقية
        $package = EducationalPackage::find($cartItem->package_id);
        if (!$package->is_digital) {
            $inventory = EducationalInventory::forSelection(
                $cartItem->generation_id,
                $cartItem->subject_id,
                $cartItem->teacher_id,
                $cartItem->platform_id,
                $cartItem->package_id
            )->first();

            if (!$inventory || !$inventory->isInStock($validated['quantity'])) {
                $available = $inventory ? $inventory->actual_available : 0;
                return back()->withErrors(['quantity' => "الكمية المطلوبة غير متوفرة. المتاح: {$available}"]);
            }

            // تحديث الحجز في المخزون
            $oldQuantity = $cartItem->quantity;
            $inventory->releaseReservedQuantity($oldQuantity);
            $inventory->reserveQuantity($validated['quantity']);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('success', 'تم تحديث الكمية بنجاح');
    }

    /**
     * إزالة عنصر تعليمي من السلة
     */
    public function destroy(CartItem $cartItem)
    {
        // التحقق من أن العنصر تعليمي وينتمي للمستخدم
        if (!$cartItem->is_educational || $cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'إجراء غير مخول');
        }

        // تحرير المخزون المحجوز للدوسيات الورقية
        $package = EducationalPackage::find($cartItem->package_id);
        if (!$package->is_digital) {
            $inventory = EducationalInventory::forSelection(
                $cartItem->generation_id,
                $cartItem->subject_id,
                $cartItem->teacher_id,
                $cartItem->platform_id,
                $cartItem->package_id
            )->first();

            if ($inventory) {
                $inventory->releaseReservedQuantity($cartItem->quantity);
            }
        }

        $cartItem->delete();

        return back()->with('success', 'تم إزالة العنصر من السلة');
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * الحصول على سلة المستخدم أو إنشاؤها
     */
    private function getOrCreateCart()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

        return $cart->load(['cartItems']);
    }

    /**
     * التحقق من صحة السلسلة التعليمية
     */
    private function validateEducationalChain(array $data)
    {
        // التحقق من أن المادة تنتمي للجيل
        $subject = Subject::where('id', $data['subject_id'])
                         ->where('generation_id', $data['generation_id'])
                         ->first();
        if (!$subject) {
            return false;
        }

        // التحقق من أن المعلم يدرس المادة
        $teacher = Teacher::where('id', $data['teacher_id'])
                         ->where('subject_id', $data['subject_id'])
                         ->first();
        if (!$teacher) {
            return false;
        }

        // التحقق من أن المنصة تخص المعلم
        $platform = Platform::where('id', $data['platform_id'])
                           ->where('teacher_id', $data['teacher_id'])
                           ->first();
        if (!$platform) {
            return false;
        }

        // التحقق من أن الباقة تخص المنصة
        $package = EducationalPackage::where('id', $data['package_id'])
                                   ->where('platform_id', $data['platform_id'])
                                   ->first();
        if (!$package) {
            return false;
        }

        return true;
    }
}