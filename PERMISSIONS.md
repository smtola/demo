# Permission System Documentation

## Overview

The Book SMS application now includes a comprehensive permission system that allows you to control user access to different features and actions. Users are assigned roles, and roles have specific permissions.

## Permission Types

### Basic CRUD Permissions
- **Read** - View records and data
- **Create** - Add new records  
- **Update** - Edit existing records
- **Delete** - Remove records

### Module-Specific Permissions
- **Manage Users** - Full user management access
- **Manage Roles** - Role and permission management
- **Manage Products** - Product catalog management
- **Manage Sales** - Sales and orders management
- **Manage Purchases** - Purchase and supplier management
- **Manage Inventory** - Stock and warehouse management
- **View Reports** - Access to reports and analytics
- **Manage Settings** - System configuration access

## Default Roles

### Admin
- **Description**: Full system access with all permissions
- **Permissions**: All permissions (`*`)
- **Access**: Complete system control

### Manager
- **Description**: Management access with most permissions
- **Permissions**: Read, Create, Update, Manage Products, Manage Sales, Manage Purchases, Manage Inventory, View Reports
- **Access**: Most management functions except user/role management

### Accountant
- **Description**: Financial management and reporting access
- **Permissions**: Read, Create, Update, Manage Sales, Manage Purchases, View Reports
- **Access**: Financial operations and reporting

### Sales
- **Description**: Sales and customer management access
- **Permissions**: Read, Create, Update, Manage Sales, Manage Products
- **Access**: Sales operations and product management

### Support
- **Description**: Basic support and read-only access
- **Permissions**: Read only
- **Access**: View-only access to most features

## How to Use Permissions

### In Filament Resources

```php
use App\Traits\HasPermissions;

class YourResource extends Resource
{
    use HasPermissions;

    // Check if user can view the resource
    public static function canViewAny(): bool
    {
        return self::userCanAny(['read', 'manage_your_module']) || self::userIsAdmin();
    }

    // Check if user can create records
    public static function canCreate(): bool
    {
        return self::userCanAny(['create', 'manage_your_module']) || self::userIsAdmin();
    }

    // Check if user can edit records
    public static function canEdit($record): bool
    {
        return self::userCanAny(['update', 'manage_your_module']) || self::userIsAdmin();
    }

    // Check if user can delete records
    public static function canDelete($record): bool
    {
        return self::userCanAny(['delete', 'manage_your_module']) || self::userIsAdmin();
    }
}
```

### In Controllers

```php
use App\Models\User;

class YourController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Check specific permission
        if ($user->hasPermission('read')) {
            // Allow access
        }
        
        // Check multiple permissions (any)
        if ($user->hasAnyPermission(['read', 'manage_products'])) {
            // Allow access
        }
        
        // Check multiple permissions (all)
        if ($user->hasAllPermissions(['read', 'create', 'update'])) {
            // Allow access
        }
        
        // Check role
        if ($user->isAdmin()) {
            // Admin access
        }
    }
}
```

### In Blade Templates

```blade
@if(auth()->user()->hasPermission('read'))
    <div>Content for users with read permission</div>
@endif

@if(auth()->user()->hasAnyPermission(['create', 'update']))
    <button>Action button</button>
@endif

@if(auth()->user()->isAdmin())
    <div>Admin only content</div>
@endif
```

## Managing Roles and Permissions

### Creating a New Role

1. Go to **Admin Panel** → **User Management** → **Roles**
2. Click **Create Role**
3. Enter role name and description
4. Select appropriate permissions
5. Save the role

### Assigning Roles to Users

1. Go to **Admin Panel** → **User Management** → **Users**
2. Edit a user
3. Select the appropriate role from the dropdown
4. Save the user

### Permission Checking Methods

#### Role Model Methods
```php
$role = Role::find(1);

// Check if role has permission
$role->hasPermission('read'); // true/false

// Check if role has any of the permissions
$role->hasAnyPermission(['read', 'create']); // true/false

// Check if role has all permissions
$role->hasAllPermissions(['read', 'create', 'update']); // true/false

// Get formatted permissions
$role->formatted_permissions; // "Read, Create, Update"
```

#### User Model Methods
```php
$user = User::find(1);

// Check if user has permission
$user->hasPermission('read'); // true/false

// Check if user has any of the permissions
$user->hasAnyPermission(['read', 'create']); // true/false

// Check if user has all permissions
$user->hasAllPermissions(['read', 'create', 'update']); // true/false

// Check if user is admin
$user->isAdmin(); // true/false

// Check if user is manager
$user->isManager(); // true/false

// Get user's permissions
$user->getPermissions(); // ['read', 'create', 'update']
```

## Best Practices

1. **Use specific permissions** rather than broad ones when possible
2. **Check permissions at multiple levels** (resource, action, and UI)
3. **Provide clear feedback** when users don't have permission
4. **Regularly audit permissions** to ensure they're appropriate
5. **Use role-based access** for common permission sets
6. **Test permission changes** thoroughly before deploying

## Security Considerations

1. **Always check permissions** on the server side, not just client side
2. **Use middleware** for route-level permission checking
3. **Log permission-related actions** for audit trails
4. **Regularly review** user roles and permissions
5. **Implement principle of least privilege** - give users only what they need

## Troubleshooting

### Common Issues

1. **User can't access admin panel**
   - Check if user has a role assigned
   - Verify role has appropriate permissions
   - Ensure user is properly authenticated

2. **Permission not working**
   - Check if permission is correctly assigned to role
   - Verify user has the correct role
   - Check if permission checking code is correct

3. **Role not showing in dropdown**
   - Ensure role exists in database
   - Check if role is properly seeded
   - Verify database connection

### Debugging Permissions

```php
// Check user's role and permissions
$user = auth()->user();
dd([
    'user_id' => $user->id,
    'role' => $user->role?->name,
    'permissions' => $user->getPermissions(),
    'is_admin' => $user->isAdmin(),
    'has_read' => $user->hasPermission('read'),
]);
```
