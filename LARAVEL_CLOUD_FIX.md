# Laravel Cloud Deployment Commands

## Quick Fix for 403 Error

Run these commands in your Laravel Cloud terminal or via SSH:

```bash
# 1. Run migrations (if not already done)
php artisan migrate --force

# 2. Create admin user specifically for Laravel Cloud
php artisan db:seed --class=LaravelCloudSeeder

# 3. Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 4. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Set proper permissions (if needed)
chmod -R 755 storage bootstrap/cache
```

## Alternative: Manual User Creation

If the seeder doesn't work, create the admin user manually:

```bash
php artisan tinker
```

Then run in tinker:
```php
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

// Create Admin role
$adminRole = Role::firstOrCreate(['name' => 'Admin'], ['permissions' => json_encode(['*'])]);

// Create admin user
$admin = User::firstOrCreate(
    ['email' => 'admin@booksms.com'],
    [
        'name' => 'Admin User',
        'password' => Hash::make('admin123'),
        'role_id' => $adminRole->id,
        'email_verified_at' => now(),
    ]
);

echo "Admin user created: " . $admin->email;
exit
```

## Laravel Cloud Specific Environment Variables

Make sure your `.env` file on Laravel Cloud has:

```env
APP_NAME="Book SMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://demo-main-0sudro.laravel.cloud

# Database (Laravel Cloud provides these automatically)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database
```

## Troubleshooting Steps

1. **Check if admin user exists**:
   ```bash
   php artisan tinker
   User::where('email', 'admin@booksms.com')->first();
   ```

2. **Check roles**:
   ```bash
   php artisan tinker
   Role::all();
   ```

3. **Check database connection**:
   ```bash
   php artisan migrate:status
   ```

4. **Check logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## After Fixing

Once the admin user is created, you should be able to login at:
- **URL**: https://demo-main-0sudro.laravel.cloud/admin
- **Email**: admin@booksms.com
- **Password**: admin123

**⚠️ Remember to change the password after first login!**
