<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->hasAnyPermission($permissions);
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->hasAllPermissions($permissions);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'Admin';
    }

    /**
     * Check if user is manager
     */
    public function isManager(): bool
    {
        return $this->role && $this->role->name === 'Manager';
    }

    /**
     * Get user's permissions
     */
    public function getPermissions(): array
    {
        if (!$this->role) {
            return [];
        }

        return $this->role->permissions ?? [];
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Allow access if user is authenticated and has a role
        if (!$this->role) {
            \Log::warning('User access denied: No role assigned', [
                'user_id' => $this->id,
                'email' => $this->email,
                'panel' => $panel->getId()
            ]);
            return false;
        }

        // Allow Admin and Manager roles
        $hasAccess = $this->isAdmin() || $this->isManager();
        
        // Log access attempts for debugging
        \Log::info('Panel access check', [
            'user_id' => $this->id,
            'email' => $this->email,
            'role' => $this->role->name,
            'panel' => $panel->getId(),
            'has_access' => $hasAccess
        ]);

        return $hasAccess;
    }
}
