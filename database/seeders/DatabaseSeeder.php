<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Generation;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Platform;
use App\Models\EducationalPackage;
use App\Models\EducationalProductType;
use App\Models\ShippingRegion;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تنظيف البيانات القديمة أولاً
        $this->cleanOldData();
        
        // إنشاء أنواع المنتجات
        $this->createProductTypes();
        
        // إنشاء مناطق الشحن
        $this->createShippingRegions();
        
        // إنشاء الأجيال
        $this->createGenerations();
        
        // إنشاء المواد لكل جيل
        $this->createSubjects();
        
        // إنشاء معلمين نموذجيين
        $this->createTeachers();
        
        // إنشاء منصات
        $this->createPlatforms();
        
        // إنشاء الباقات التعليمية
        $this->createEducationalPackages();
        
        $this->command->info('تم إنشاء بيانات النظام التعليمي بنجاح!');
    }

    private function cleanOldData()
    {
        $this->command->info('تنظيف البيانات القديمة...');
        
        // حذف البيانات بترتيب صحيح لتجنب مشاكل الـ foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        try {
            EducationalPackage::truncate();
            Platform::truncate();
            Teacher::truncate();
            Subject::truncate();
            Generation::truncate();
            ShippingRegion::truncate();
            EducationalProductType::truncate();
        } catch (\Exception $e) {
            // تجاهل الأخطاء إذا كانت الجداول فارغة
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createProductTypes()
    {
        $this->command->info('إنشاء أنواع المنتجات...');
        
        $productTypes = [
            [
                'name' => 'بطاقات تعليمية',
                'code' => 'cards',
                'is_digital' => true,
                'requires_shipping' => false,
            ],
            [
                'name' => 'دوسيات ورقية',
                'code' => 'booklets',
                'is_digital' => false,
                'requires_shipping' => true,
            ],
        ];

        foreach ($productTypes as $type) {
            EducationalProductType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }

    private function createShippingRegions()
    {
        $this->command->info('إنشاء مناطق الشحن...');
        
        $regions = [
            ['name' => 'عمان', 'shipping_cost' => 2.00],
            ['name' => 'إربد', 'shipping_cost' => 3.00],
            ['name' => 'الزرقاء', 'shipping_cost' => 2.50],
            ['name' => 'المفرق', 'shipping_cost' => 4.00],
            ['name' => 'جرش', 'shipping_cost' => 3.50],
            ['name' => 'عجلون', 'shipping_cost' => 4.00],
            ['name' => 'البلقاء', 'shipping_cost' => 3.00],
            ['name' => 'مادبا', 'shipping_cost' => 3.50],
            ['name' => 'الكرك', 'shipping_cost' => 5.00],
            ['name' => 'الطفيلة', 'shipping_cost' => 6.00],
            ['name' => 'معان', 'shipping_cost' => 7.00],
            ['name' => 'العقبة', 'shipping_cost' => 8.00],
        ];

        foreach ($regions as $region) {
            ShippingRegion::updateOrCreate(
                ['name' => $region['name']],
                $region
            );
        }
    }

    private function createGenerations()
    {
        $this->command->info('إنشاء الأجيال...');
        
        $generations = [
            ['name' => 'جيل 2000', 'birth_year' => 2000],
            ['name' => 'جيل 2001', 'birth_year' => 2001],
            ['name' => 'جيل 2002', 'birth_year' => 2002],
            ['name' => 'جيل 2003', 'birth_year' => 2003],
            ['name' => 'جيل 2004', 'birth_year' => 2004],
            ['name' => 'جيل 2005', 'birth_year' => 2005],
            ['name' => 'جيل 2006', 'birth_year' => 2006],
            ['name' => 'جيل 2007', 'birth_year' => 2007],
        ];

        foreach ($generations as $generation) {
            Generation::updateOrCreate(
                ['birth_year' => $generation['birth_year']],
                $generation
            );
        }
    }

    private function createSubjects()
    {
        $this->command->info('إنشاء المواد...');
        
        $subjects = [
            'الرياضيات', 'الفيزياء', 'الكيمياء', 'الأحياء',
            'اللغة العربية', 'اللغة الإنجليزية', 'التاريخ', 'الجغرافيا',
            'التربية الإسلامية', 'الحاسوب', 'العلوم'
        ];

        foreach (Generation::all() as $generation) {
            foreach ($subjects as $subjectName) {
                Subject::updateOrCreate(
                    ['generation_id' => $generation->id, 'name' => $subjectName],
                    [
                        'code' => strtolower(str_replace(' ', '_', $subjectName)),
                    ]
                );
            }
        }
    }

    private function createTeachers()
    {
        $this->command->info('إنشاء المعلمين...');
        
        $teacherNames = [
            'أ. محمد أحمد', 'أ. سارة عبدالله', 'أ. خالد محمود', 'أ. فاطمة علي',
            'أ. أحمد حسن', 'أ. مريم خالد', 'أ. يوسف إبراهيم', 'أ. نور الدين'
        ];

        foreach (Subject::take(8)->get() as $index => $subject) {
            Teacher::updateOrCreate(
                ['subject_id' => $subject->id, 'name' => $teacherNames[$index]],
                [
                    'specialization' => $subject->name,
                    'bio' => "معلم متخصص في {$subject->name} مع خبرة 10+ سنوات",
                ]
            );
        }
    }

    private function createPlatforms()
    {
        $this->command->info('إنشاء المنصات...');
        
        $platformNames = [
            'منصة التعليم الذكي', 'أكاديمية المستقبل', 'تعلم معنا', 'منصة النجاح'
        ];

        foreach (Teacher::take(4)->get() as $index => $teacher) {
            Platform::updateOrCreate(
                ['teacher_id' => $teacher->id, 'name' => $platformNames[$index]],
                [
                    'description' => "منصة تعليمية متخصصة في {$teacher->subject->name}",
                    'website_url' => 'https://platform' . ($index + 1) . '.example.com',
                ]
            );
        }
    }

    private function createEducationalPackages()
    {
        $this->command->info('إنشاء الباقات التعليمية...');
        
        $digitalPackages = [
            ['name' => 'باقة شهرية', 'duration_days' => 30, 'lessons_count' => 20],
            ['name' => 'باقة ثلاثة أشهر', 'duration_days' => 90, 'lessons_count' => 60],
            ['name' => 'باقة نصف سنة', 'duration_days' => 180, 'lessons_count' => 120],
            ['name' => 'باقة سنوية كاملة', 'duration_days' => 365, 'lessons_count' => null],
        ];

        $physicalPackages = [
            ['name' => 'دوسية الفصل الأول', 'pages_count' => 100, 'weight_grams' => 200],
            ['name' => 'دوسية الفصل الثاني', 'pages_count' => 120, 'weight_grams' => 240],
            ['name' => 'دوسية السنة الكاملة', 'pages_count' => 250, 'weight_grams' => 500],
            ['name' => 'مراجعة نهائية', 'pages_count' => 80, 'weight_grams' => 160],
        ];

        $digitalType = EducationalProductType::where('code', 'cards')->first();
        $physicalType = EducationalProductType::where('code', 'booklets')->first();

        foreach (Platform::all() as $platform) {
            // إنشاء باقات رقمية
            foreach ($digitalPackages as $package) {
                EducationalPackage::updateOrCreate(
                    [
                        'platform_id' => $platform->id, 
                        'name' => $package['name'], 
                        'product_type_id' => $digitalType->id
                    ],
                    [
                        'description' => "باقة رقمية - {$package['name']}",
                        'duration_days' => $package['duration_days'],
                        'lessons_count' => $package['lessons_count'],
                    ]
                );
            }

            // إنشاء باقات ورقية
            foreach ($physicalPackages as $package) {
                EducationalPackage::updateOrCreate(
                    [
                        'platform_id' => $platform->id, 
                        'name' => $package['name'], 
                        'product_type_id' => $physicalType->id
                    ],
                    [
                        'description' => "دوسية ورقية - {$package['name']}",
                        'pages_count' => $package['pages_count'],
                        'weight_grams' => $package['weight_grams'],
                    ]
                );
            }
        }
    }
}