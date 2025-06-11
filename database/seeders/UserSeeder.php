<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check which columns exist in the users table
        $hasPhone = Schema::hasColumn('users', 'phone');
        $hasAddress = Schema::hasColumn('users', 'address');
        $hasRole = Schema::hasColumn('users', 'role');
        $hasProfileImage = Schema::hasColumn('users', 'profile_image');

        $this->command->info('📋 Checking users table structure...');
        $this->command->info("✅ Phone column: " . ($hasPhone ? 'exists' : 'missing'));
        $this->command->info("✅ Address column: " . ($hasAddress ? 'exists' : 'missing'));
        $this->command->info("✅ Role column: " . ($hasRole ? 'exists' : 'missing'));
        $this->command->info("✅ Profile image column: " . ($hasProfileImage ? 'exists' : 'missing'));

        // Create Admin User
        $adminData = [
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ];

        // Add optional fields if they exist
        if ($hasRole) {
            $adminData['role'] = 'admin';
        }
        if ($hasPhone) {
            $adminData['phone'] = '+962-7-1234-5678';
        }
        if ($hasAddress) {
            $adminData['address'] = 'Amman, Jordan';
        }

        User::create($adminData);

        // Create Test Customer Users
        $customers = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+962-7-9876-5432',
                'address' => 'عمان، الأردن',
                'role' => 'customer',
            ],
            [
                'name' => 'فاطمة علي', 
                'email' => 'fatima@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+962-7-5555-1234',
                'address' => 'إربد، الأردن',
                'role' => 'customer',
            ],
            [
                'name' => 'John Smith',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+962-7-1111-2222',
                'address' => 'Amman, Jordan',
                'role' => 'customer',
            ],
            [
                'name' => 'سارة أحمد',
                'email' => 'sara@gmail.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+962-7-2222-3333',
                'address' => 'العقبة، الأردن',
                'role' => 'customer',
            ],
        ];

        foreach ($customers as $customerData) {
            // Create base user data
            $userData = [
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'password' => $customerData['password'],
                'email_verified_at' => $customerData['email_verified_at'],
            ];

            // Add optional fields if they exist
            if ($hasRole) {
                $userData['role'] = $customerData['role'];
            }
            if ($hasPhone) {
                $userData['phone'] = $customerData['phone'];
            }
            if ($hasAddress) {
                $userData['address'] = $customerData['address'];
            }

            User::create($userData);
        }

        // Create unverified user for testing
        $unverifiedData = [
            'name' => 'غير مفعل',
            'email' => 'unverified@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => null, // Not verified
        ];

        if ($hasRole) {
            $unverifiedData['role'] = 'customer';
        }
        if ($hasPhone) {
            $unverifiedData['phone'] = '+962-7-3333-4444';
        }
        if ($hasAddress) {
            $unverifiedData['address'] = 'عمان، الأردن';
        }

        User::create($unverifiedData);

        $this->command->info('✅ Users seeded successfully!');
        $this->command->newLine();
        $this->command->info('📝 Test Accounts Created:');
        $this->command->info('👨‍💼 Admin: admin@example.com / admin123');
        $this->command->info('👤 Customer: ahmed@example.com / password123');
        $this->command->info('👤 Customer: fatima@example.com / password123');
        $this->command->info('👤 Customer: john@example.com / password123');
        $this->command->info('👤 Customer: sara@gmail.com / password123');
        $this->command->info('🔒 Unverified: unverified@example.com / password123');
        $this->command->newLine();
    }
}