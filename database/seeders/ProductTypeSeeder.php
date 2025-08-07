<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductType;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productTypes = [
            [
                'name' => 'بطاقات رقمية',
                'is_digital' => true,
                'description' => 'بطاقات إلكترونية يحصل عليها الطلاب فوراً بعد الشراء',
                'is_active' => true,
            ],
            [
                'name' => 'دوسيات ورقية',
                'is_digital' => false,
                'description' => 'كتب ودوسيات مطبوعة يتم شحنها للطلاب',
                'is_active' => true,
            ]
        ];

        foreach ($productTypes as $type) {
            ProductType::firstOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}