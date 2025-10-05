#!/bin/bash

# Laravel Cloud Specific Fix Script
# For: https://demo-main-0sudro.laravel.cloud/admin

echo "🔧 Laravel Cloud 403 Fix Script"
echo "Target: https://demo-main-0sudro.laravel.cloud/admin"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_status "Step 1: Checking database connection..."
php artisan migrate:status

print_status "Step 2: Checking if admin user exists..."
php artisan tinker --execute="
\$admin = \App\Models\User::where('email', 'admin@booksms.com')->first();
if (\$admin) {
    echo '✅ Admin user found: ' . \$admin->email . PHP_EOL;
    echo 'Role: ' . (\$admin->role ? \$admin->role->name : 'No role') . PHP_EOL;
    echo 'Permissions: ' . (\$admin->role ? implode(', ', \$admin->role->permissions ?? []) : 'None') . PHP_EOL;
} else {
    echo '❌ Admin user NOT found!' . PHP_EOL;
}
"

print_status "Step 3: Checking all users..."
php artisan tinker --execute="
\$users = \App\Models\User::with('role')->get();
echo 'Total users: ' . \$users->count() . PHP_EOL;
foreach (\$users as \$user) {
    echo '- ' . \$user->email . ' (Role: ' . (\$user->role ? \$user->role->name : 'None') . ')' . PHP_EOL;
}
"

print_status "Step 4: Checking roles..."
php artisan tinker --execute="
\$roles = \App\Models\Role::all();
echo 'Total roles: ' . \$roles->count() . PHP_EOL;
foreach (\$roles as \$role) {
    echo '- ' . \$role->name . ' (Permissions: ' . implode(', ', \$role->permissions ?? []) . ')' . PHP_EOL;
}
"

print_status "Step 5: Creating admin user if missing..."
php artisan tinker --execute="
\$admin = \App\Models\User::where('email', 'admin@booksms.com')->first();
if (!\$admin) {
    echo 'Creating admin user...' . PHP_EOL;
    
    // Create Admin role
    \$adminRole = \App\Models\Role::firstOrCreate(
        ['name' => 'Admin'],
        [
            'description' => 'Full system access',
            'permissions' => ['*'],
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );
    
    // Create admin user
    \$admin = \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@booksms.com',
        'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
        'role_id' => \$adminRole->id,
        'email_verified_at' => now(),
    ]);
    
    echo '✅ Admin user created: ' . \$admin->email . PHP_EOL;
} else {
    echo 'Admin user already exists.' . PHP_EOL;
}
"

print_status "Step 6: Testing panel access..."
php artisan tinker --execute="
\$admin = \App\Models\User::where('email', 'admin@booksms.com')->first();
if (\$admin) {
    \$canAccess = \$admin->canAccessPanel(new \Filament\Panel('admin'));
    echo 'Can access admin panel: ' . (\$canAccess ? '✅ Yes' : '❌ No') . PHP_EOL;
    
    if (!\$canAccess) {
        echo 'Debug info:' . PHP_EOL;
        echo '- User ID: ' . \$admin->id . PHP_EOL;
        echo '- Role: ' . (\$admin->role ? \$admin->role->name : 'None') . PHP_EOL;
        echo '- Is Admin: ' . (\$admin->isAdmin() ? 'Yes' : 'No') . PHP_EOL;
        echo '- Is Manager: ' . (\$admin->isManager() ? 'Yes' : 'No') . PHP_EOL;
    }
}
"

print_status "Step 7: Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

print_status "Step 8: Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_success "Laravel Cloud fix completed!"
echo ""
print_status "Try logging in with:"
echo "  📧 Email: admin@booksms.com"
echo "  🔑 Password: admin123"
echo "  🌐 URL: https://demo-main-0sudro.laravel.cloud/admin"
echo ""
print_warning "If still getting 403, check Laravel Cloud logs:"
echo "  - Check the Laravel Cloud dashboard for error logs"
echo "  - Look for database connection issues"
echo "  - Verify environment variables are set correctly"
