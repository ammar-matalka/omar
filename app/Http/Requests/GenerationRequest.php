<?php
// app/Http/Requests/GenerationRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class GenerationRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم مخولاً لتقديم هذا الطلب
     */
public function authorize(): bool
{
    /** @var User|null $user */
    $user = Auth::user();
    return $user && $user->isAdmin();
}

    /**
     * الحصول على قواعد التحقق المطبقة على الطلب
     */
    public function rules(): array
    {
        $generationId = $this->route('generation') ? $this->route('generation')->id : null;
        
        return [
            'birth_year' => [
                'required',
                'integer',
                'min:1990',
                'max:' . (date('Y') + 10),
                Rule::unique('generations', 'birth_year')->ignore($generationId)
            ],
            'name' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ];
    }

    /**
     * رسائل الخطأ المخصصة
     */
    public function messages(): array
    {
        return [
            'birth_year.required' => 'سنة الميلاد مطلوبة',
            'birth_year.integer' => 'سنة الميلاد يجب أن تكون رقم صحيح',
            'birth_year.min' => 'سنة الميلاد لا يمكن أن تكون أقل من 1990',
            'birth_year.max' => 'سنة الميلاد لا يمكن أن تكون أكثر من ' . (date('Y') + 10),
            'birth_year.unique' => 'هذا الجيل موجود بالفعل',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف'
        ];
    }

    /**
     * تحضير البيانات للتحقق
     */
    protected function prepareForValidation(): void
    {
        // توليد اسم تلقائي إذا لم يتم تقديمه
        if (!$this->filled('name') && $this->filled('birth_year')) {
            $this->merge([
                'name' => "جيل {$this->birth_year}"
            ]);
        }

        // التأكد من أن is_active boolean
        $this->merge([
            'is_active' => $this->boolean('is_active')
        ]);
    }

    /**
     * الحصول على البيانات المنظمة
     */
    public function getGenerationData(): array
    {
        $data = $this->validated();
        
        // التأكد من وجود اسم
        if (empty($data['name'])) {
            $data['name'] = "جيل {$data['birth_year']}";
        }

        return $data;
    }
}