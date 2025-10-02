<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--email=admin@booksms.com} {--password=admin123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');

        try {
            // Create Admin role if it doesn't exist
            $adminRole = Role::firstOrCreate(
                ['name' => 'Admin'],
                ['permissions' => json_encode(['*'])]
            );

            // Create admin user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Admin User',
                    'password' => Hash::make($password),
                    'role_id' => $adminRole->id,
                    'email_verified_at' => now(),
                ]
            );

            if ($user->wasRecentlyCreated) {
                $this->info("✅ Admin user created successfully!");
            } else {
                $this->info("ℹ️  Admin user already exists.");
            }

            $this->info("📧 Email: {$email}");
            $this->info("🔑 Password: {$password}");
            $this->info("🌐 Access: /admin");
            $this->info("⚠️  Please change the password after first login!");

        } catch (\Exception $e) {
            $this->error("❌ Error creating admin user: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
