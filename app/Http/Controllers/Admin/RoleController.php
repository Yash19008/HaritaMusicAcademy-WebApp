<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display roles and users management page
     */
    public function index(): View
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
        $users = User::with('roles')->get();

        return view('admin.roles.index', compact('roles', 'permissions', 'users'));
    }

    /**
     * Get all users with their roles (API endpoint for DataTable)
     */
    public function getUsers(): JsonResponse
    {
        $users = User::with('roles')->get()->map(function ($user) {
            return [
                'id' => 'USR' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
                'name' => $user->name,
                'email' => $user->email,
                'password' => '••••••••', // Don't send real password
                'role' => $user->roles->first()?->name ?? 'No Role',
                'status' => $user->status ?? 'active',
            ];
        });

        return response()->json($users);
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name',
            'status' => 'required|in:active,inactive',
        ]);

        // Create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => strtolower($data['status']),
        ]);

        // Assign role
        $user->assignRole($data['role']);

        // If Teacher role, create teacher record
        if ($data['role'] === 'Teacher') {
            \App\Models\Teacher::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'status' => 'active',
            ]);
        }

        // If Student role, create student record
        if ($data['role'] === 'Student') {
            \App\Models\Student::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'status' => 'active',
            ]);
        }

        return back()->with('success', 'User created successfully!');
    }

    /**
     * Update existing user
     */
    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|exists:roles,name',
            'status' => 'required|in:active,inactive',
        ]);

        // Update user
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => strtolower($data['status']),
        ]);

        // Update password if provided
        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        // Sync role
        $user->syncRoles([$data['role']]);

        return back()->with('success', 'User updated successfully!');
    }

    /**
     * Delete user
     */
    public function destroyUser(User $user): RedirectResponse
    {
        // Prevent deleting first user (Super Admin)
        if ($user->id === 1) {
            return back()->with('error', 'Cannot delete the primary administrator account!');
        }

        // Delete user
        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }

    /**
     * Update role (not used yet but available for future)
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $role->update($data);

        return back()->with('success', 'Role updated successfully!');
    }

    /**
     * Update role permissions
     */
    public function updatePermissions(Request $request): JsonResponse
    {
        $data = $request->validate([
            'role' => 'required|string|exists:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::where('name', $data['role'])->firstOrFail();
        
        // Build permission names from the nested structure
        $permissionNames = [];
        foreach ($data['permissions'] as $module => $actions) {
            foreach ($actions as $action => $enabled) {
                if ($enabled) {
                    $permissionNames[] = "{$module}.{$action}";
                }
            }
        }

        // Sync permissions
        $role->syncPermissions($permissionNames);

        return response()->json([
            'success' => true,
            'message' => "Permissions for {$role->name} role updated successfully!",
        ]);
    }

    /**
     * Get role permissions (API endpoint)
     */
    public function getRolePermissions(Request $request): JsonResponse
    {
        $roleName = $request->input('role');
        $role = Role::where('name', $roleName)->with('permissions')->first();

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        // Group permissions by module
        $permissionsByModule = [];
        foreach ($role->permissions as $permission) {
            [$module, $action] = explode('.', $permission->name);
            if (!isset($permissionsByModule[$module])) {
                $permissionsByModule[$module] = [
                    'view' => false,
                    'create' => false,
                    'edit' => false,
                    'delete' => false,
                    'approve' => false,
                ];
            }
            $permissionsByModule[$module][$action] = true;
        }

        return response()->json([
            'name' => $role->name,
            'permissions' => $permissionsByModule,
        ]);
    }

    /**
     * Clone role (create a copy)
     */
    public function clone(Request $request, Role $role): RedirectResponse
    {
        $newRole = Role::create([
            'name' => $role->name . ' (Copy)',
            'description' => $role->description,
            'status' => 'inactive',
            'guard_name' => 'web',
        ]);

        $newRole->syncPermissions($role->permissions);

        return back()->with('success', 'Role cloned successfully!');
    }

    /**
     * Delete role
     */
    public function destroy(Role $role): RedirectResponse
    {
        // Prevent deleting system roles
        if (in_array($role->name, ['Super Admin', 'Admin', 'Teacher', 'Student'])) {
            return back()->with('error', 'Cannot delete system roles!');
        }

        $role->delete();

        return back()->with('success', 'Role deleted successfully!');
    }
}
