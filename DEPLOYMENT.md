# Book SMS - Deployment Environment Configuration

## Required Environment Variables

Create a `.env` file in your project root with the following configuration:

```env
APP_NAME="Book SMS"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://yourdomain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=your_database_host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

# Cache Configuration
CACHE_STORE=database
CACHE_PREFIX=

# Queue Configuration
QUEUE_CONNECTION=database

# Mail Configuration (Optional)
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# File Storage
FILESYSTEM_DISK=public

# PayWay Configuration
ABA_API_URL=https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/purchase
ABA_API_KEY=your_payway_api_key
ABA_MERCHANT_ID=your_merchant_id

# AWS S3 Configuration (if using S3 for file storage)
AWS_ACCESS_KEY_ID=your_aws_key
AWS_SECRET_ACCESS_KEY=your_aws_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
AWS_USE_PATH_STYLE_ENDPOINT=false
```

## Deployment Steps

1. **Upload your code** to the server
2. **Run the deployment script**: `bash deploy.sh`
3. **Configure your web server** to point to the `public` directory
4. **Set proper file permissions**:
   ```bash
   chmod -R 755 storage bootstrap/cache
   chmod -R 644 .env
   ```

## Default Admin Credentials

After running the seeder, you can login with:
- **Email**: admin@booksms.com
- **Password**: admin123

**⚠️ IMPORTANT**: Change these credentials immediately after first login!

## Troubleshooting 403 Errors

If you still get 403 errors:

1. **Check file permissions**:
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

2. **Clear all caches**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Re-run migrations**:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Check web server configuration**:
   - Ensure document root points to `public` directory
   - Enable mod_rewrite (Apache) or proper nginx configuration
   - Check error logs for specific issues

## Security Checklist

- [ ] Change default admin password
- [ ] Set `APP_DEBUG=false` in production
- [ ] Use HTTPS in production
- [ ] Configure proper file permissions
- [ ] Set up database backups
- [ ] Configure firewall rules
- [ ] Enable SSL/TLS certificates
