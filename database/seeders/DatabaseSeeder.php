<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\Expense;
use App\Models\AuditLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Roles & Users ---
        $this->command->info('Creating roles...');
        $roles = ['Admin', 'Manager', 'Accountant', 'Sales', 'Support'];

        foreach ($roles as $roleName) {
            Role::updateOrCreate(
                ['name' => $roleName],
                ['permissions' => json_encode([])]
            );
        }
        
        // Create admin user first
        $this->command->info('Creating admin user...');
        $adminRole = Role::where('name', 'Admin')->first();
        User::firstOrCreate(
            ['email' => 'admin@booksms.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@booksms.com',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );
        
        $this->command->info('Creating other users...');
        $users = User::factory(5)->create();

        // --- Customers & Suppliers ---
        $this->command->info('Creating customers and suppliers...');
        $customers = Customer::factory(50)->create();
        $suppliers = Supplier::factory(10)->create();

        // --- Categories & Warehouses ---
        $this->command->info('Creating categories and warehouses...');
        $categories = Category::factory(5)->create();
        $warehouses = Warehouse::factory(3)->create();

        // --- Products & Variants ---
        $this->command->info('Creating products and variants...');
        $products = Product::factory(100)->create();
        ProductVariant::factory(200)->create();

        // --- Supplier Products ---
        $this->command->info('Creating supplier products...');
        foreach ($suppliers as $supplier) {
            $randomProducts = $products->random(rand(5, 15));
            $supplier->products()->attach($randomProducts);
        }

        // --- Purchases & PurchaseItems ---
        $this->command->info('Creating purchases and purchase items...');
        $purchases = Purchase::factory(20)->create();
        foreach ($purchases as $purchase) {
            PurchaseItem::factory(rand(1, 3))->create([
                'purchase_id' => $purchase->id,
            ]);
        }

        // --- Sales & SaleItems ---
        $this->command->info('Creating sales and sale items...');
        $sales = Sale::factory(40)->create();
        foreach ($sales as $sale) {
            SaleItem::factory(rand(1, 3))->create([
                'sale_id' => $sale->id,
            ]);
        }

        // --- Stock Movements ---
        $this->command->info('Creating stock movements...');
        StockMovement::factory(100)->create();

        // --- Expenses ---
        $this->command->info('Creating expenses...');
        Expense::factory(20)->create();

        // --- Audit Logs ---
        $this->command->info('Creating audit logs...');
        AuditLog::factory(50)->create();
        
        $this->command->info('Database seeding completed successfully!');
    }
}
