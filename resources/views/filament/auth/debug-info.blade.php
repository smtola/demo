@if(app()->environment('local') || config('app.debug'))
<div class="mt-4 p-4 bg-gray-100 rounded-lg text-sm">
    <h4 class="font-semibold mb-2">🔧 Debug Information</h4>
    <div class="space-y-1">
        <p><strong>Environment:</strong> {{ app()->environment() }}</p>
        <p><strong>Debug Mode:</strong> {{ config('app.debug') ? 'Enabled' : 'Disabled' }}</p>
        <p><strong>Database Connection:</strong> {{ config('database.default') }}</p>
        <p><strong>Session Driver:</strong> {{ config('session.driver') }}</p>
        <p><strong>Cache Driver:</strong> {{ config('cache.default') }}</p>
        
        @php
            $userCount = \App\Models\User::count();
            $roleCount = \App\Models\Role::count();
            $adminUser = \App\Models\User::whereHas('role', function($q) {
                $q->where('name', 'Admin');
            })->first();
        @endphp
        
        <p><strong>Total Users:</strong> {{ $userCount }}</p>
        <p><strong>Total Roles:</strong> {{ $roleCount }}</p>
        <p><strong>Admin User Exists:</strong> {{ $adminUser ? 'Yes (' . $adminUser->email . ')' : 'No' }}</p>
        
        @if($adminUser)
            <p><strong>Admin Role:</strong> {{ $adminUser->role->name ?? 'No Role' }}</p>
            <p><strong>Admin Permissions:</strong> {{ $adminUser->role->permissions ? implode(', ', $adminUser->role->permissions) : 'None' }}</p>
        @endif
    </div>
    
    @if($userCount === 0)
        <div class="mt-3 p-3 bg-red-100 border border-red-300 rounded">
            <p class="text-red-700 font-semibold">⚠️ No users found in database!</p>
            <p class="text-red-600 text-sm">Run: <code>php artisan db:seed --class=LaravelCloudSeeder</code></p>
        </div>
    @endif
    
    @if(!$adminUser)
        <div class="mt-3 p-3 bg-yellow-100 border border-yellow-300 rounded">
            <p class="text-yellow-700 font-semibold">⚠️ No admin user found!</p>
            <p class="text-yellow-600 text-sm">Run: <code>php artisan db:seed --class=LaravelCloudSeeder</code></p>
        </div>
    @endif
</div>
@endif
