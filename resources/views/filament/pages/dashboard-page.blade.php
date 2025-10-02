<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <aside class="md:col-span-3">
            <div class="rounded-xl border bg-white p-4 dark:bg-gray-900 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Getting Started</h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Follow these steps to set up the system.</p>
                <ol class="mt-3 space-y-2 text-sm">
                    <li class="flex gap-2">
                        <span class="text-gray-400">1)</span>
                        <a class="text-primary-600 hover:underline dark:text-primary-400" href="{{ \App\Filament\Resources\WarehouseResource::getUrl() }}">Warehouses</a>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-gray-400">2)</span>
                        <a class="text-primary-600 hover:underline dark:text-primary-400" href="{{ \App\Filament\Resources\CategoryResource::getUrl() }}">Categories</a>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-gray-400">4)</span>
                        <a class="text-primary-600 hover:underline dark:text-primary-400" href="{{ \App\Filament\Resources\CustomerResource::getUrl() }}">Customers</a>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-gray-400">5)</span>
                        <a class="text-primary-600 hover:underline dark:text-primary-400" href="{{ \App\Filament\Resources\ProductResource::getUrl() }}">Products</a>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-gray-400">6)</span>
                        <a class="text-primary-600 hover:underline dark:text-primary-400" href="{{ \App\Filament\Resources\ProductVariantResource::getUrl() }}">Variants (optional)</a>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-gray-400">8)</span>
                        <a class="text-primary-600 hover:underline dark:text-primary-400" href="{{ \App\Filament\Resources\StockMovementResource::getUrl() }}">Stock Movements (optional)</a>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-gray-400">9)</span>
                        <a class="text-primary-600 hover:underline dark:text-primary-400" href="{{ \App\Filament\Resources\SaleResource::getUrl() }}">Sales</a>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-gray-400">10)</span>
                        <a class="text-primary-600 hover:underline dark:text-primary-400" href="{{ \App\Filament\Resources\ExpenseResource::getUrl() }}">Expenses</a>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-gray-400">11)</span>
                        <a class="text-primary-600 hover:underline dark:text-primary-400" href="{{ \App\Filament\Resources\UserResource::getUrl() }}">Users</a>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-gray-400">12)</span>
                        <a class="text-primary-600 hover:underline dark:text-primary-400" href="{{ \App\Filament\Resources\RoleResource::getUrl() }}">Roles</a>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-gray-400">13)</span>
                        <a class="text-primary-600 hover:underline dark:text-primary-400" href="{{ \App\Filament\Resources\AuditLogResource::getUrl() }}">Audit Logs</a>
                    </li>
                </ol>
            </div>
        </aside>

        <main class="md:col-span-9">
            <x-filament-widgets::widgets
                :columns="$this->getColumn()"
                :widgets="$this->getWidgets()"
            />
        </main>
    </div>
</x-filament::page>