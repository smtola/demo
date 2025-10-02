<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    // Available permissions
    public const PERMISSIONS = [
        'read' => 'Read',
        'create' => 'Add/Create',
        'update' => 'Edit/Update',
        'delete' => 'Delete',
        'manage_users' => 'Manage Users',
        'manage_roles' => 'Manage Roles',
        'manage_products' => 'Manage Products',
        'manage_sales' => 'Manage Sales',
        'manage_purchases' => 'Manage Purchases',
        'manage_inventory' => 'Manage Inventory',
        'view_reports' => 'View Reports',
        'manage_settings' => 'Manage Settings',
    ];

    // Permission descriptions
    public const PERMISSION_DESCRIPTIONS = [
        'read' => 'View records and data',
        'create' => 'Add new records',
        'update' => 'Edit existing records',
        'delete' => 'Remove records',
        'manage_users' => 'Full user management access',
        'manage_roles' => 'Role and permission management',
        'manage_products' => 'Product catalog management',
        'manage_sales' => 'Sales and orders management',
        'manage_purchases' => 'Purchase and supplier management',
        'manage_inventory' => 'Stock and warehouse management',
        'view_reports' => 'Access to reports and analytics',
        'manage_settings' => 'System configuration access',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->permissions) {
            return false;
        }

        return in_array($permission, $this->permissions) || in_array('*', $this->permissions);
    }

    /**
     * Check if role has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if (!$this->permissions) {
            return false;
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if role has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        if (!$this->permissions) {
            return false;
        }

        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get formatted permissions list
     */
    public function getFormattedPermissionsAttribute(): string
    {
        if (!$this->permissions) {
            return 'No permissions';
        }

        $labels = array_map(function($permission) {
            return self::PERMISSIONS[$permission] ?? $permission;
        }, $this->permissions);

        return implode(', ', $labels);
    }

    /**
     * Get all available permissions
     */
    public static function getAvailablePermissions(): array
    {
        return self::PERMISSIONS;
    }

    /**
     * Get permission descriptions
     */
    public static function getPermissionDescriptions(): array
    {
        return self::PERMISSION_DESCRIPTIONS;
    }
}
