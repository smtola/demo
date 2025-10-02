<?php

namespace App\Traits;

use App\Models\Role;

trait HasPermissions
{
    /**
     * Get available permissions for forms
     */
    public static function getPermissionOptions(): array
    {
        return Role::getAvailablePermissions();
    }

    /**
     * Get permission descriptions for forms
     */
    public static function getPermissionDescriptions(): array
    {
        return Role::getPermissionDescriptions();
    }

    /**
     * Check if current user has permission
     */
    public static function userCan(string $permission): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        
        // Check if user has the specific permission or wildcard permission
        return $user->hasPermission($permission) || $user->hasPermission('*');
    }

    /**
     * Check if current user has any of the permissions
     */
    public static function userCanAny(array $permissions): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        
        // Check if user has wildcard permission first
        if ($user->hasPermission('*')) return true;
        
        return $user->hasAnyPermission($permissions);
    }

    /**
     * Check if current user has all permissions
     */
    public static function userCanAll(array $permissions): bool
    {
        $user = auth()->user();
        return $user ? $user->hasAllPermissions($permissions) : false;
    }

    /**
     * Check if current user is admin
     */
    public static function userIsAdmin(): bool
    {
        $user = auth()->user();
        return $user ? $user->isAdmin() : false;
    }

    /**
     * Check if current user is manager
     */
    public static function userIsManager(): bool
    {
        $user = auth()->user();
        return $user ? $user->isManager() : false;
    }
}
