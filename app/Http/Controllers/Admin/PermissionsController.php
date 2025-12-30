<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionsController extends Controller
{
    /**
     * Display the role permissions matrix.
     */
    public function index(): View
    {
        $permissions = RolePermission::orderBy('display_order')->get();
        $roles = [
            'admin' => 'Administrator',
            'hr_manager' => 'HR Manager',
            'editor' => 'Editor',
            'client' => 'Client (Company Admin)',
            'user' => 'User',
        ];

        return view('admin.permissions.index', compact('permissions', 'roles'));
    }

    /**
     * Update role permissions.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'array',
            'permissions.*.*' => 'boolean',
        ]);

        foreach ($request->input('permissions', []) as $permissionKey => $rolePermissions) {
            $permission = RolePermission::where('permission_key', $permissionKey)->first();
            
            if ($permission) {
                $roles = [];
                foreach ($rolePermissions as $role => $hasAccess) {
                    if ($hasAccess) {
                        $roles[] = $role;
                    }
                }
                
                $permission->update(['roles' => $roles]);
            }
        }

        return redirect()
            ->route('admin.permissions.index')
            ->with('status', 'Permissions updated successfully.');
    }
}
