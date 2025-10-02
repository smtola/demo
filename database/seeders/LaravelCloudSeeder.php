<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LaravelCloudSeeder extends Seeder
{
    /**
     * Run the database seeds for Laravel Cloud deployment.
     */
    public function run(): void
    {
        // Ensure we're in a transaction
        DB::transaction(function () {
            // Create Admin role with full permissions
            $adminRole = Role::firstOrCreate(
                ['name' => 'Admin'],
                [
                    'permissions' => json_encode(['*']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Create Manager role
            $managerRole = Role::firstOrCreate(
                ['name' => 'Manager'],
                [
                    'permissions' => json_encode(['read', 'write', 'update']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Create default admin user
            $adminUser = User::firstOrCreate(
                ['email' => 'admin@booksms.com'],
                [
                    'name' => 'Admin User',
                    'email' => 'admin@booksms.com',
                    'password' => Hash::make('admin123'),
                    'role_id' => $adminRole->id,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Create additional roles if they don't exist
            $roles = ['Accountant', 'Sales', 'Support'];
            foreach ($roles as $roleName) {
                Role::firstOrCreate(
                    ['name' => $roleName],
                    [
                        'permissions' => json_encode([]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            // Log the creation
            \Log::info('Laravel Cloud: Admin user created', [
                'email' => $adminUser->email,
                'role' => $adminRole->name,
                'user_id' => $adminUser->id,
            ]);
        });

        $this->command->info('✅ Laravel Cloud admin user created successfully!');
        $this->command->info('📧 Email: admin@booksms.com');
        $this->command->info('🔑 Password: admin123');
        $this->command->info('🌐 Access: /admin');
    }
}
