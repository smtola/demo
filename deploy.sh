#!/bin/bash

# Deployment script for Book SMS
# Run this script after deploying to fix common deployment issues

echo "🚀 Starting Book SMS deployment setup..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the Laravel root directory."
    exit 1
fi

echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

echo "🔧 Setting up environment..."
# Copy .env.example to .env if .env doesn't exist
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "⚠️  Please configure your .env file with database credentials and other settings"
fi

echo "🗄️  Running database migrations..."
php artisan migrate --force

echo "🌱 Seeding database with admin user..."
php artisan db:seed --class=AdminUserSeeder

echo "🔑 Generating application key..."
php artisan key:generate --force

echo "📁 Setting up storage links..."
php artisan storage:link

echo "🎨 Building assets..."
npm install
npm run build

echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment setup completed!"
echo ""
echo "🔐 Admin Login Credentials:"
echo "   Email: admin@booksms.com"
echo "   Password: admin123"
echo ""
echo "🌐 Access your admin panel at: /admin"
echo ""
echo "⚠️  Remember to:"
echo "   1. Change the default admin password"
echo "   2. Configure your .env file properly"
echo "   3. Set up proper file permissions (755 for directories, 644 for files)"
echo "   4. Configure your web server to point to the public directory"
