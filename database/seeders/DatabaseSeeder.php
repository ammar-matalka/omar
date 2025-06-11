<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // Seed Users
        $this->call([
            UserSeeder::class,
            // Add other seeders here:
            // CategorySeeder::class,
            // ProductSeeder::class,
            // PlatformSeeder::class,
            // GradeSeeder::class,
            // SubjectSeeder::class,
            // EducationalCardSeeder::class,
        ]);

        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('📝 Test Accounts Created:');
        $this->command->info('👨‍💼 Admin: admin@example.com / admin123');
        $this->command->info('👤 Customer: ahmed@example.com / password123');
        $this->command->info('👤 Customer: fatima@example.com / password123');
        $this->command->info('👤 Customer: john@example.com / password123');
        $this->command->newLine();
    }
}
