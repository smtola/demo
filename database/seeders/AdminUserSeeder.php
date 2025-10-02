<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['permissions' => json_encode(['*'])] // Full permissions
        );

        // Create Manager role if it doesn't exist
        $managerRole = Role::firstOrCreate(
            ['name' => 'Manager'],
            ['permissions' => json_encode(['read', 'write', 'update'])]
        );

        // Create default admin user
        User::firstOrCreate(
            ['email' => 'admin@booksms.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@booksms.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Create default manager user
        User::firstOrCreate(
            ['email' => 'manager@booksms.com'],
            [
                'name' => 'Manager User',
                'email' => 'manager@booksms.com',
                'password' => Hash::make('manager123'),
                'role_id' => $managerRole->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Admin: admin@booksms.com / admin123');
        $this->command->info('Manager: manager@booksms.com / manager123');
    }
}
