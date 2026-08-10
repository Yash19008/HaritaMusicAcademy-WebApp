# 🚨 EMERGENCY: Restore Dashboard Permission

## Problem
You unchecked "dashboard.view" permission and now you can't access the admin panel to fix it!

---

## ✅ Solution 1: Run PHP Script (EASIEST)

```bash
cd d:\all_project\harita-project\harita
php restore-permissions.php
```

Then clear cache:
```bash
php artisan cache:clear
php artisan permission:cache-reset
```

**Refresh browser** (Ctrl + F5)

---

## ✅ Solution 2: Use Laravel Tinker

```bash
php artisan tinker
```

Then run this in Tinker:

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Get the permission
$perm = Permission::where('name', 'dashboard.view')->first();

// Restore to all roles
Role::all()->each(fn($role) => $role->givePermissionTo($perm));

// Clear cache
Artisan::call('permission:cache-reset');

// Check it worked
Role::where('name', 'Admin')->first()->permissions->pluck('name');
```

Exit tinker: `exit`

---

## ✅ Solution 3: Direct Database (MySQL/phpMyAdmin)

Run this SQL:

```sql
-- Find IDs
SELECT id, name FROM permissions WHERE name = 'dashboard.view';
SELECT id, name FROM roles;

-- Restore to Admin (replace @permission_id and @role_id with actual IDs)
INSERT IGNORE INTO role_has_permissions (permission_id, role_id) 
VALUES (1, 2);  -- Change these IDs based on your database

-- Or use variables:
SET @perm_id = (SELECT id FROM permissions WHERE name = 'dashboard.view');
SET @admin_id = (SELECT id FROM roles WHERE name = 'Admin');
INSERT IGNORE INTO role_has_permissions (permission_id, role_id) 
VALUES (@perm_id, @admin_id);
```

---

## ✅ Solution 4: Re-run Seeder (NUCLEAR OPTION)

⚠️ **WARNING:** This will reset ALL permissions to default!

```bash
php artisan db:seed --class=RolePermissionSeeder
```

Then clear cache:
```bash
php artisan cache:clear
php artisan permission:cache-reset
```

---

## 🔍 Verify It's Fixed

Check in database:
```sql
SELECT 
    r.name as role_name,
    p.name as permission_name
FROM role_has_permissions rhp
JOIN roles r ON r.id = rhp.role_id
JOIN permissions p ON p.id = rhp.permission_id
WHERE p.name = 'dashboard.view'
ORDER BY r.name;
```

Should show:
```
role_name      | permission_name
---------------|----------------
Admin          | dashboard.view
Super Admin    | dashboard.view
Teacher        | dashboard.view
Student        | dashboard.view
```

---

## 🎯 Quick Fix Summary

**Fastest:**
```bash
php restore-permissions.php
php artisan permission:cache-reset
```

**If that fails:**
```bash
php artisan tinker
Role::all()->each(fn($r) => $r->givePermissionTo('dashboard.view'));
exit
```

**Then always:**
- Clear browser cache (Ctrl + Shift + Delete)
- Hard refresh (Ctrl + F5)

---

## 🛡️ Prevent This in Future

### Option 1: Protect Critical Permissions in UI

Add this to `resources/views/admin/roles/index.blade.php` in the `renderPermissionsMatrix()` function:

```javascript
function renderPermissionsMatrix() {
  const container = document.getElementById("permissionsContainer");
  container.innerHTML = "";

  const currentRole = ROLES_DATA[selectedRoleKey];
  
  // Critical permissions that shouldn't be removed
  const criticalPerms = ['dashboard.view', 'roles.view'];

  Object.keys(currentRole.permissions).forEach(moduleKey => {
    const actions = currentRole.permissions[moduleKey];
    const row = document.createElement("div");
    row.className = "permission-row";

    row.innerHTML = `
      <div class="font-semibold text-primary" style="text-transform: capitalize;">${moduleKey} Manager</div>
      <div class="text-center">
        <input type="checkbox" 
          ${actions.view ? 'checked' : ''} 
          ${criticalPerms.includes(moduleKey + '.view') ? 'disabled title="Required permission"' : ''}
          onchange="updatePermission('${moduleKey}', 'view', this.checked)">
      </div>
      <!-- ... other checkboxes ... -->
    `;

    container.appendChild(row);
  });
}
```

### Option 2: Add Backend Validation

In `RoleController@updatePermissions()`:

```php
public function updatePermissions(Request $request): JsonResponse
{
    $data = $request->validate([
        'role' => 'required|string|exists:roles,name',
        'permissions' => 'required|array',
    ]);

    $role = Role::where('name', $data['role'])->firstOrFail();
    
    // Build permission names
    $permissionNames = [];
    foreach ($data['permissions'] as $module => $actions) {
        foreach ($actions as $action => $enabled) {
            if ($enabled) {
                $permissionNames[] = "{$module}.{$action}";
            }
        }
    }
    
    // ✅ ALWAYS include critical permissions
    $criticalPermissions = ['dashboard.view', 'roles.view'];
    $permissionNames = array_unique(array_merge($permissionNames, $criticalPermissions));

    // Sync permissions
    $role->syncPermissions($permissionNames);

    return response()->json([
        'success' => true,
        'message' => "Permissions for {$role->name} role updated successfully!",
    ]);
}
```

---

**Last Updated:** August 9, 2026  
**Status:** Emergency fix ready to deploy
