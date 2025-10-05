#!/bin/bash

# Book SMS - Deployment Fix Script for 403 Admin Panel Access
# This script addresses common issues that cause 403 errors in deployed environments

echo "🚀 Starting Book SMS Deployment Fix..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
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

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

print_status "Setting proper file permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 .env 2>/dev/null || print_warning ".env file not found (this is normal for some deployments)"

print_status "Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan filament:clear-cached-components

print_status "Running database migrations..."
php artisan migrate --force

print_status "Creating admin user and roles..."
php artisan db:seed --class=LaravelCloudSeeder --force

print_status "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_status "Checking database connection and user creation..."
php artisan tinker --execute="
\$userCount = \App\Models\User::count();
\$roleCount = \App\Models\Role::count();
\$adminUser = \App\Models\User::whereHas('role', function(\$q) {
    \$q->where('name', 'Admin');
})->first();

echo 'Database Status:' . PHP_EOL;
echo 'Total Users: ' . \$userCount . PHP_EOL;
echo 'Total Roles: ' . \$roleCount . PHP_EOL;
echo 'Admin User: ' . (\$adminUser ? 'Exists (' . \$adminUser->email . ')' : 'Missing') . PHP_EOL;

if (!\$adminUser) {
    echo 'Creating emergency admin user...' . PHP_EOL;
    \$adminRole = \App\Models\Role::firstOrCreate(['name' => 'Admin'], ['permissions' => ['*']]);
    \$admin = \App\Models\User::firstOrCreate(
        ['email' => 'admin@booksms.com'],
        [
            'name' => 'Admin User',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role_id' => \$adminRole->id,
            'email_verified_at' => now(),
        ]
    );
    echo 'Emergency admin user created: ' . \$admin->email . PHP_EOL;
}
"

print_status "Testing admin panel access..."
php artisan tinker --execute="
\$admin = \App\Models\User::where('email', 'admin@booksms.com')->first();
if (\$admin) {
    echo 'Admin user found: ' . \$admin->email . PHP_EOL;
    echo 'Role: ' . (\$admin->role ? \$admin->role->name : 'No role') . PHP_EOL;
    echo 'Can access panel: ' . (\$admin->canAccessPanel(new \Filament\Panel('admin')) ? 'Yes' : 'No') . PHP_EOL;
} else {
    echo 'Admin user not found!' . PHP_EOL;
}
"

print_success "Deployment fix completed!"
print_status "Admin Panel Access Information:"
echo "  🌐 URL: /admin"
echo "  📧 Email: admin@booksms.com"
echo "  🔑 Password: admin123"
echo ""
print_warning "⚠️  IMPORTANT: Change the admin password after first login!"
echo ""
print_status "If you still get 403 errors, check:"
echo "  1. Web server configuration (document root should point to 'public' directory)"
echo "  2. .env file has correct APP_URL and database settings"
echo "  3. Session and cache drivers are properly configured"
echo "  4. File permissions are correct (755 for directories, 644 for files)"
echo ""
print_status "For debugging, check the logs:"
echo "  tail -f storage/logs/laravel.log"
