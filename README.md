# Book SMS - Stock Management System

A comprehensive Laravel-based Stock Management System (SMS) built with Filament admin panel for managing books, inventory, sales, purchases, and customer relationships.

## 🚀 Features

### Core Functionality
- **Product Management**: Complete product catalog with variants, categories, and inventory tracking
- **Inventory Control**: Real-time stock tracking with warehouse management
- **Sales Management**: Customer sales processing with detailed reporting
- **Purchase Management**: Supplier purchase order processing and tracking
- **Customer & Supplier Management**: Comprehensive contact and relationship management
- **Financial Tracking**: Expense management and financial reporting
- **Audit Logging**: Complete activity tracking for compliance and security

### Admin Interface
- **Modern UI**: Built with Filament v3 for a beautiful, responsive admin panel
- **Role-based Access**: User roles and permissions system
- **Data Visualization**: Apex Charts integration for analytics and reporting
- **Real-time Updates**: Livewire-powered dynamic interfaces

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Admin Panel**: Filament 3.3
- **Frontend**: Livewire 3.6
- **Charts**: Apex Charts (via leandrocfe/filament-apex-charts)
- **Database**: MySQL/PostgreSQL/SQLite
- **Real-time**: Pusher integration
- **Testing**: PHPUnit with Laravel testing suite

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL/PostgreSQL/SQLite database
- Web server (Apache/Nginx) or PHP built-in server

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd book_sms
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**
   - Update your `.env` file with database credentials
   - Create your database
   - Run migrations:
     ```bash
     php artisan migrate
     ```

6. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

   Or use the development script with multiple services:
   ```bash
   composer run dev
   ```

## 🗄️ Database Schema

The system includes a comprehensive database schema with the following main entities:

### Core Entities
- **Users & Roles**: User management with role-based permissions
- **Customers & Suppliers**: Contact management for business relationships
- **Products & Categories**: Product catalog with categorization
- **Warehouses**: Multi-location inventory management

### Transaction Entities
- **Orders**: Purchase and sales order management
- **Purchases & Sales**: Transaction processing with detailed line items
- **Stock Movements**: Inventory tracking and adjustments
- **Expenses**: Financial expense tracking

### Audit & Logging
- **Audit Logs**: Complete activity tracking for compliance

For detailed database schema, see [database_schema_diagram.md](database_schema_diagram.md)

## 🎯 Key Features Breakdown

### Product Management
- SKU-based product identification
- Barcode support
- Product variants (size, color, etc.)
- Category organization
- Expiry date tracking
- Multi-warehouse inventory

### Inventory Control
- Real-time stock levels
- Stock movement tracking
- Warehouse management
- Low stock alerts
- Inventory adjustments

### Sales & Purchasing
- Customer sales processing
- Supplier purchase orders
- Order status tracking
- Reference number generation
- Financial calculations

### Reporting & Analytics
- Sales reports
- Inventory reports
- Financial summaries
- Apex Charts integration
- Export capabilities

## 🔧 Configuration

### Environment Variables
Key environment variables to configure:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_sms
DB_USERNAME=your_username
DB_PASSWORD=your_password

PUSHER_APP_ID=your_pusher_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=your_pusher_cluster
```

### Filament Configuration
The admin panel is configured in `config/filament.php` with custom branding and navigation.

## 🧪 Testing

Run the test suite:
```bash
composer run test
```

Or run specific tests:
```bash
php artisan test
```

## 📦 Available Commands

### Development
- `composer run dev` - Start development server with all services
- `php artisan serve` - Start Laravel development server
- `npm run dev` - Start Vite development server
- `php artisan queue:listen` - Start queue worker

### Database
- `php artisan migrate` - Run database migrations
- `php artisan migrate:fresh --seed` - Fresh migration with seeding
- `php artisan db:seed` - Run database seeders

### Filament
- `php artisan filament:upgrade` - Upgrade Filament components
- `php artisan make:filament-resource` - Create new Filament resource

## 🏗️ Project Structure

```
app/
├── Console/           # Artisan commands
├── Filament/          # Admin panel resources
│   ├── Pages/         # Custom pages
│   ├── Resources/     # CRUD resources
│   └── Widgets/       # Dashboard widgets
├── Http/Controllers/  # Web controllers
├── Models/            # Eloquent models
├── Observers/         # Model observers
└── Providers/         # Service providers

database/
├── factories/         # Model factories
├── migrations/        # Database migrations
└── seeders/          # Database seeders

resources/
├── css/              # Stylesheets
├── js/               # JavaScript assets
└── views/            # Blade templates
```

## 🔐 Security Features

- **Role-based Access Control**: Granular permissions system
- **Audit Logging**: Complete activity tracking
- **CSRF Protection**: Built-in Laravel security
- **Input Validation**: Comprehensive data validation
- **SQL Injection Protection**: Eloquent ORM protection

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Build production assets: `npm run build`
6. Configure web server to serve from `public/` directory

### Environment Requirements
- PHP 8.2+
- Composer
- Node.js 16+
- MySQL 8.0+ or PostgreSQL 12+
- Web server (Apache/Nginx)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

For support and questions:
- Check the Laravel documentation: https://laravel.com/docs
- Check the Filament documentation: https://filamentphp.com/docs
- Create an issue in the repository

## 🔄 Version History

- **v1.0.0** - Initial release with core SMS functionality
- Built with Laravel 12.x and Filament 3.3

---

**Note**: This is a comprehensive stock management system designed for book retailers and general inventory management. The system provides a complete solution for managing products, customers, suppliers, sales, and inventory with a modern, user-friendly interface.

Step-by-step flow to use the system
1) Log in
Visit /admin
Sign in with your admin account.
2) Set up master data
Warehouses: Add locations where you store stock.
Categories: Create product categories.
Suppliers: Add supplier records.
Customers: Add customer records (optional now, required for sales).
3) Create your catalog
Products: Add products with SKU, price, and default warehouse.
Variants (if needed): Sizes/colors/other options under each product.
4) Bring in stock
Purchases: Create a Purchase, select a Supplier, add items and quantities.
Submit/mark as received to increase inventory in the chosen warehouse.
5) Adjust inventory (optional)
Stock Movements: Move stock between warehouses or correct counts.
6) Sell to customers
Sales: Create a Sale, choose a Customer, add items/quantities.
Confirm to deduct stock and record revenue.
7) Track expenses
Expenses: Log operating costs (rent, utilities, shipping, etc.).
8) Monitor and analyze
Dashboard: View key widgets.
Reports: Use resource lists (Sales, Purchases, Inventory) with filters/export.
9) Manage users and access
Users/Roles: Create users and assign roles/permissions as needed.
10) Audit & review
Audit Logs: Review who did what and when.
11) Maintenance
Back up database regularly.
Keep environment variables/config up-to-date.
Update dependencies as needed.


Card Status	Card Type	Card Number	Exp	CVV	3DS Enrolled
Success	Master Card	5156 8399 3770 6777	01/30	993	No
Visa Card	4286 0900 0000 0206	04/30	777	Yes
Declined	Master Card	5156 8302 7256 1029	04/30	777	Yes
Visa Card	4156 8399 3770 6777	01/30	993	No