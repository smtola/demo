# Book SMS - 403 Admin Panel Access Fix Guide

## 🚨 Quick Fix Commands

Run these commands in your deployed environment:

```bash
# 1. Run the deployment fix script
bash fix-deployment.sh

# 2. Or run commands manually:
php artisan migrate --force
php artisan db:seed --class=LaravelCloudSeeder --force
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔍 Root Causes of 403 Errors

### 1. **Missing Admin User**
- **Problem**: No admin user exists in the database
- **Solution**: Run `php artisan db:seed --class=LaravelCloudSeeder`

### 2. **Session Configuration Issues**
- **Problem**: Sessions not working properly in cloud environment
- **Solution**: Ensure `.env` has:
  ```env
  SESSION_DRIVER=database
  SESSION_LIFETIME=120
  SESSION_SECURE_COOKIE=true
  SESSION_SAME_SITE=lax
  ```

### 3. **Database Connection Problems**
- **Problem**: Database not accessible or migrations not run
- **Solution**: Check database credentials and run migrations

### 4. **File Permissions**
- **Problem**: Web server can't access Laravel files
- **Solution**: Set proper permissions:
  ```bash
  chmod -R 755 storage bootstrap/cache
  chmod -R 644 .env
  ```

### 5. **Environment Configuration**
- **Problem**: Missing or incorrect environment variables
- **Solution**: Verify `.env` file has correct values

## 🛠️ Step-by-Step Troubleshooting

### Step 1: Check Database Connection
```bash
php artisan migrate:status
```

### Step 2: Verify Admin User Exists
```bash
php artisan tinker
```
Then run:
```php
$admin = \App\Models\User::where('email', 'admin@booksms.com')->first();
echo $admin ? 'Admin exists: ' . $admin->email : 'Admin not found';
```

### Step 3: Check Roles and Permissions
```bash
php artisan tinker
```
Then run:
```php
$roles = \App\Models\Role::all();
foreach($roles as $role) {
    echo $role->name . ': ' . implode(', ', $role->permissions ?? []) . PHP_EOL;
}
```

### Step 4: Test Panel Access
```bash
php artisan tinker
```
Then run:
```php
$admin = \App\Models\User::where('email', 'admin@booksms.com')->first();
if($admin) {
    echo 'Can access panel: ' . ($admin->canAccessPanel(new \Filament\Panel('admin')) ? 'Yes' : 'No');
}
```

## 🔧 Environment Configuration

### Required .env Settings for Cloud Deployment:

```env
# App Configuration
APP_NAME="Book SMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your_host
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Session (Critical for Admin Panel)
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Cache
CACHE_STORE=database

# Queue
QUEUE_CONNECTION=database
```

## 🚀 Deployment Checklist

- [ ] Database migrations run successfully
- [ ] Admin user created with proper role
- [ ] Environment variables configured correctly
- [ ] File permissions set properly
- [ ] Session driver configured for database
- [ ] Cache cleared and optimized
- [ ] Web server points to `public` directory
- [ ] HTTPS enabled (if required)

## 🆘 Emergency Admin User Creation

If the seeder fails, create admin user manually:

```bash
php artisan tinker
```

```php
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

// Create Admin role
$adminRole = Role::firstOrCreate(
    ['name' => 'Admin'],
    ['permissions' => ['*']]
);

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

## 🔍 Debug Information

The login page now includes debug information when `APP_DEBUG=true`. This shows:
- Database connection status
- User count
- Role count
- Admin user existence
- Permission details

## 📞 Support

If you continue to experience 403 errors after following this guide:

1. Check the Laravel logs: `tail -f storage/logs/laravel.log`
2. Verify web server error logs
3. Test database connectivity
4. Ensure all environment variables are set correctly
5. Check file permissions and ownership

## 🎯 Default Admin Credentials

After successful deployment:
- **URL**: `/admin`
- **Email**: `admin@booksms.com`
- **Password**: `admin123`

**⚠️ IMPORTANT**: Change these credentials immediately after first login!
