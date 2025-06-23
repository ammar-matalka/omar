<?php
// app/Http/Requests/EducationalCartRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\EducationalPackage;
use App\Models\EducationalInventory;
use Illuminate\Support\Facades\Auth;


class EducationalCartRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم مخولاً لتقديم هذا الطلب
     */
 public function authorize(): bool
{
    return Auth::check();
}

    /**
     * الحصول على قواعد التحقق المطبقة على الطلب
     */
    public function rules(): array
    {
        return [
            'generation_id' => 'required|exists:generations,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'platform_id' => 'required|exists:platforms,id',
            'package_id' => 'required|exists:educational_packages,id',
            'region_id' => 'nullable|exists:shipping_regions,id',
            'quantity' => 'required|integer|min:1|max:10'
        ];
    }

    /**
     * رسائل الخطأ المخصصة
     */
    public function messages(): array
    {
        return [
            'generation_id.required' => 'يجب اختيار الجيل',
            'generation_id.exists' => 'الجيل المحدد غير صحيح',
            'subject_id.required' => 'يجب اختيار المادة',
            'subject_id.exists' => 'المادة المحددة غير صحيحة',
            'teacher_id.required' => 'يجب اختيار المعلم',
            'teacher_id.exists' => 'المعلم المحدد غير صحيح',
            'platform_id.required' => 'يجب اختيار المنصة',
            'platform_id.exists' => 'المنصة المحددة غير صحيحة',
            'package_id.required' => 'يجب اختيار الباقة',
            'package_id.exists' => 'الباقة المحددة غير صحيحة',
            'region_id.exists' => 'منطقة الشحن المحددة غير صحيحة',
            'quantity.required' => 'يجب تحديد الكمية',
            'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'quantity.max' => 'الكمية لا يمكن أن تتجاوز 10'
        ];
    }

    /**
     * تحضير البيانات للتحقق
     */
    protected function prepareForValidation(): void
    {
        // التأكد من أن quantity رقم صحيح
        if ($this->has('quantity')) {
            $this->merge([
                'quantity' => (int) $this->quantity
            ]);
        }
    }

    /**
     * التحقق بعد قواعد التحقق الأساسية
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            
            // التحقق من صحة السلسلة التعليمية
            if (!$this->validateEducationalChain()) {
                $validator->errors()->add('chain', 'الاختيار غير صحيح، يرجى التأكد من السلسلة التعليمية');
            }

            // التحقق من متطلبات الشحن
            if (!$this->validateShippingRequirements()) {
                $validator->errors()->add('region_id', 'منطقة الشحن مطلوبة للدوسيات الورقية');
            }

            // التحقق من المخزون
            if (!$this->validateInventory()) {
                $validator->errors()->add('quantity', 'الكمية المطلوبة غير متوفرة في المخزون');
            }
        });
    }

    /**
     * التحقق من صحة السلسلة التعليمية
     */
    private function validateEducationalChain(): bool
    {
        if (!$this->filled(['generation_id', 'subject_id', 'teacher_id', 'platform_id', 'package_id'])) {
            return false;
        }

        // التحقق من أن المادة تنتمي للجيل
        $subject = \App\Models\Subject::where('id', $this->subject_id)
                                     ->where('generation_id', $this->generation_id)
                                     ->first();
        if (!$subject) {
            return false;
        }

        // التحقق من أن المعلم يدرس المادة
        $teacher = \App\Models\Teacher::where('id', $this->teacher_id)
                                     ->where('subject_id', $this->subject_id)
                                     ->first();
        if (!$teacher) {
            return false;
        }

        // التحقق من أن المنصة تخص المعلم
        $platform = \App\Models\Platform::where('id', $this->platform_id)
                                       ->where('teacher_id', $this->teacher_id)
                                       ->first();
        if (!$platform) {
            return false;
        }

        // التحقق من أن الباقة تخص المنصة
        $package = EducationalPackage::where('id', $this->package_id)
                                   ->where('platform_id', $this->platform_id)
                                   ->first();
        if (!$package) {
            return false;
        }

        return true;
    }

    /**
     * التحقق من متطلبات الشحن
     */
    private function validateShippingRequirements(): bool
    {
        if (!$this->filled('package_id')) {
            return true; // سيتم رفضه في التحقق الأساسي
        }

        $package = EducationalPackage::with('productType')->find($this->package_id);
        
        if (!$package) {
            return true; // سيتم رفضه في التحقق الأساسي
        }

        // إذا كانت الباقة تحتاج شحن ولم يتم تحديد منطقة
        if ($package->productType->requires_shipping && !$this->filled('region_id')) {
            return false;
        }

        return true;
    }

    /**
     * التحقق من المخزون
     */
    private function validateInventory(): bool
    {
        if (!$this->filled(['generation_id', 'subject_id', 'teacher_id', 'platform_id', 'package_id', 'quantity'])) {
            return true; // سيتم رفضه في التحقق الأساسي
        }

        // التحقق من نوع الباقة
        $package = EducationalPackage::with('productType')->find($this->package_id);
        
        if (!$package) {
            return true; // سيتم رفضه في التحقق الأساسي
        }

        // البطاقات الرقمية لا تحتاج مخزون
        if ($package->productType->is_digital) {
            return true;
        }

        // التحقق من المخزون للدوسيات الورقية
        $inventory = EducationalInventory::where('generation_id', $this->generation_id)
                                        ->where('subject_id', $this->subject_id)
                                        ->where('teacher_id', $this->teacher_id)
                                        ->where('platform_id', $this->platform_id)
                                        ->where('package_id', $this->package_id)
                                        ->first();

        if (!$inventory) {
            return false; // لا يوجد مخزون
        }

        // التحقق من الكمية المتاحة
        return $inventory->isInStock($this->quantity);
    }

    /**
     * الحصول على البيانات المنظمة
     */
    public function getEducationalData(): array
    {
        $data = $this->validated();
        
        // التحقق من نوع الباقة وإزالة المنطقة للمنتجات الرقمية
        $package = EducationalPackage::with('productType')->find($data['package_id']);
        
        if ($package && !$package->productType->requires_shipping) {
            $data['region_id'] = null;
        }

        return $data;
    }
}