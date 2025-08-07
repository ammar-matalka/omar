<?php
// database/seeders/EducationalSystemSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducationalProductType;
use App\Models\Generation;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Platform;
use App\Models\EducationalPackage;
use App\Models\ShippingRegion;
use Illuminate\Support\Facades\DB;

class EducationalSystemSeeder extends Seeder
{
    public function run(): void
    {
        // تنظيف البيانات القديمة أولاً
        $this->cleanOldData();
        
        // 1. إنشاء أنواع المنتجات التعليمية
        $this->createProductTypes();
        
        // 2. إنشاء الأجيال
        $this->createGenerations();
        
        // 3. إنشاء المواد
        $this->createSubjects();
        
        // 4. إنشاء المعلمين
        $this->createTeachers();
        
        // 5. إنشاء المنصات
        $this->createPlatforms();
        
        // 6. إنشاء مناطق الشحن
        $this->createShippingRegions();
        
        // 7. إنشاء باقات تجريبية منفصلة
        $this->createSeparatePackages();
        
        $this->command->info('تم إنشاء بيانات النظام التعليمي بنجاح!');
    }

    private function cleanOldData()
    {
        $this->command->info('تنظيف البيانات القديمة...');
        
        // حذف البيانات بترتيب صحيح لتجنب مشاكل الـ foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        try {
            EducationalPackage::truncate();
            ShippingRegion::truncate();
            Platform::truncate();
            Teacher::truncate();
            Subject::truncate();
            Generation::truncate();
            EducationalProductType::truncate();
        } catch (\Exception $e) {
            // تجاهل الأخطاء إذا كانت الجداول فارغة
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createProductTypes()
    {
        $this->command->info('إنشاء أنواع المنتجات التعليمية...');
        
        EducationalProductType::create([
            'name' => 'بطاقة رقمية',
            'code' => 'DIGITAL_CARD',
            'is_digital' => true,
            'requires_shipping' => false,
        ]);

        EducationalProductType::create([
            'name' => 'دوسية ورقية',
            'code' => 'PHYSICAL_BOOKLET',
            'is_digital' => false,
            'requires_shipping' => true,
        ]);
    }

    private function createGenerations()
    {
        $this->command->info('إنشاء الأجيال...');
        
        $generations = [
            ['birth_year' => 2007, 'name' => 'جيل 2007', 'is_active' => true],
            ['birth_year' => 2006, 'name' => 'جيل 2006', 'is_active' => true],
            ['birth_year' => 2005, 'name' => 'جيل 2005', 'is_active' => true],
            ['birth_year' => 2004, 'name' => 'جيل 2004', 'is_active' => true],
        ];

        foreach ($generations as $generation) {
            Generation::create($generation);
        }
    }

    private function createSubjects()
    {
        $this->command->info('إنشاء المواد...');
        
        $generations = Generation::all();
        $subjects = ['الرياضيات', 'الفيزياء', 'الكيمياء', 'الأحياء', 'اللغة العربية', 'اللغة الإنجليزية'];

        foreach ($generations as $generation) {
            foreach ($subjects as $subjectName) {
                Subject::create([
                    'generation_id' => $generation->id,
                    'name' => $subjectName,
                    'code' => strtolower(str_replace([' ', 'ا'], ['_', 'a'], $subjectName)),
                    'is_active' => true
                ]);
            }
        }
    }

    private function createTeachers()
    {
        $this->command->info('إنشاء المعلمين...');
        
        $subjects = Subject::all();
        $teacherNames = [
            'الرياضيات' => ['أ. أحمد محمد', 'أ. فاطمة أحمد'],
            'الفيزياء' => ['أ. محمد علي', 'أ. سارة محمد'],
            'الكيمياء' => ['أ. علي حسن', 'أ. نور أحمد'],
            'الأحياء' => ['أ. مريم علي', 'أ. خالد محمد'],
            'اللغة العربية' => ['أ. حسام الدين', 'أ. زينب أحمد'],
            'اللغة الإنجليزية' => ['أ. رانيا محمد', 'أ. عمر علي']
        ];

        foreach ($subjects as $subject) {
            $names = $teacherNames[$subject->name] ?? ['أ. معلم عام'];
            
            foreach ($names as $name) {
                Teacher::create([
                    'subject_id' => $subject->id,
                    'name' => $name,
                    'specialization' => $subject->name,
                    'is_active' => true
                ]);
            }
        }
    }

    private function createPlatforms()
    {
        $this->command->info('إنشاء المنصات...');
        
        $teachers = Teacher::all();

        foreach ($teachers as $teacher) {
            Platform::create([
                'teacher_id' => $teacher->id,
                'name' => "منصة {$teacher->name}",
                'description' => "منصة تعليمية متخصصة في {$teacher->specialization}",
                'is_active' => true
            ]);
        }
    }

    private function createShippingRegions()
    {
        $this->command->info('إنشاء مناطق الشحن...');
        
        $regions = [
            ['name' => 'عمان', 'shipping_cost' => 2.00],
            ['name' => 'إربد', 'shipping_cost' => 3.00],
            ['name' => 'الزرقاء', 'shipping_cost' => 2.50],
            ['name' => 'العقبة', 'shipping_cost' => 5.00],
            ['name' => 'الكرك', 'shipping_cost' => 4.00],
            ['name' => 'معان', 'shipping_cost' => 4.50],
        ];

        foreach ($regions as $region) {
            ShippingRegion::create([
                'name' => $region['name'],
                'shipping_cost' => $region['shipping_cost'],
                'is_active' => true
            ]);
        }
    }

    private function createSeparatePackages()
    {
        $this->command->info('إنشاء الباقات التعليمية المنفصلة...');
        
        $digitalProductType = EducationalProductType::where('is_digital', true)->first();
        $physicalProductType = EducationalProductType::where('is_digital', false)->first();
        $platforms = Platform::take(5)->get(); // أول 5 منصات

        // إنشاء بطاقات رقمية منفصلة
        $this->command->info('إنشاء البطاقات الرقمية...');
        foreach ($platforms as $index => $platform) {
            EducationalPackage::create([
                'product_type_id' => $digitalProductType->id,
                'platform_id' => $platform->id,
                'name' => "بطاقة رقمية - {$platform->teacher->subject->name} - {$platform->teacher->name}",
                'description' => "بطاقة تعليمية رقمية شاملة لمادة {$platform->teacher->subject->name}",
                'duration_days' => [30, 90, 180, 365, null][$index % 5], // تنويع فترات الصلاحية
                'lessons_count' => [10, 20, 50, 100, null][$index % 5], // تنويع عدد الدروس
                'pages_count' => null, // مخصص للدوسيات فقط
                'weight_grams' => null, // مخصص للدوسيات فقط
                'is_active' => true
            ]);
        }

        // إنشاء دوسيات ورقية منفصلة
        $this->command->info('إنشاء الدوسيات الورقية...');
        foreach ($platforms as $index => $platform) {
            EducationalPackage::create([
                'product_type_id' => $physicalProductType->id,
                'platform_id' => $platform->id,
                'name' => "دوسية ورقية - {$platform->teacher->subject->name} - {$platform->teacher->name}",
                'description' => "دوسية ورقية شاملة لمادة {$platform->teacher->subject->name}",
                'duration_days' => null, // مخصص للبطاقات الرقمية فقط
                'lessons_count' => null, // مخصص للبطاقات الرقمية فقط
                'pages_count' => [50, 100, 150, 200, 250][$index % 5], // تنويع عدد الصفحات
                'weight_grams' => [200, 400, 600, 800, 1000][$index % 5], // تنويع الأوزان
                'is_active' => true
            ]);
        }
    }
}