<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'permission_key',
        'permission_name',
        'permission_group',
        'roles',
        'display_order',
    ];

    protected $casts = [
        'roles' => 'array',
        'display_order' => 'integer',
    ];

    /**
     * Check if a role has access to this permission.
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles ?? []);
    }

    /**
     * Get all permissions grouped by permission group.
     */
    public static function getGrouped(): array
    {
        return static::orderBy('display_order')
            ->get()
            ->groupBy('permission_group')
            ->toArray();
    }

    /**
     * Get permissions formatted for the permissions view.
     */
    public static function getFormattedForView(): array
    {
        $permissions = static::orderBy('display_order')->get();
        $formatted = [];

        foreach ($permissions as $permission) {
            $formatted[$permission->permission_name] = [
                'admin' => $permission->hasRole('admin'),
                'hr_manager' => $permission->hasRole('hr_manager'),
                'editor' => $permission->hasRole('editor'),
                'user' => $permission->hasRole('user'),
                'key' => $permission->permission_key,
            ];
        }

        return $formatted;
    }
}
