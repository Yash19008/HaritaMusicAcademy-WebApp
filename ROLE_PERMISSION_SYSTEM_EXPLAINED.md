# 🔐 Role & Permission Management System - Complete Explanation

## 📚 Overview

Your system uses **Spatie Laravel Permission** package to manage roles and permissions. This provides a flexible, database-driven access control system with three main user types: **Admin**, **Teacher**, and **Student**.

---

## 🎯 System Architecture

### 1. **Core Components**

```
┌─────────────────────────────────────────────────────────────┐
│                  Spatie Permission Package                   │
│  (Database-driven roles & permissions management)            │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      User Model                              │
│  - HasRoles trait (from Spatie)                             │
│  - Relationships: student(), teacher()                       │
│  - Fields: role (legacy), status                            │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│              Middleware: EnsureUserHasRole                   │
│  - Checks if user has required role                          │
│  - Redirects to appropriate dashboard if unauthorized        │
│  - Handles both Spatie roles AND legacy 'role' field        │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│              Routes (admin, teacher, student)                │
│  - Protected by middleware: 'role.access:rolename'           │
│  - Further protected by permissions: 'can:permission.name'   │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                  UI (Sidebar, Header, Views)                 │
│  - Dynamically shows menu items based on role                │
│  - Checks permissions using @can directive or $user->can()   │
└─────────────────────────────────────────────────────────────┘
```

---

## 🗄️ Database Structure

### Tables Created by Spatie Permission:

1. **`roles`** - Stores all roles
   ```sql
   - id
   - name (Super Admin, Admin, Teacher, Student)
   - description
   - status (active/inactive)
   - guard_name (web)
   - created_at, updated_at
   ```

2. **`permissions`** - Stores all permissions
   ```sql
   - id
   - name (dashboard.view, students.create, etc.)
   - guard_name (web)
   - created_at, updated_at
   ```

3. **`role_has_permissions`** - Links roles to permissions
   ```sql
   - permission_id
   - role_id
   ```

4. **`model_has_roles`** - Links users to roles
   ```sql
   - role_id
   - model_type (App\Models\User)
   - model_id (user_id)
   ```

5. **`model_has_permissions`** - Direct user permissions (optional)
   ```sql
   - permission_id
   - model_type
   - model_id
   ```

---

## 👥 Roles & Their Permissions

### 1. **Super Admin** Role
- **Description**: Full system access
- **Status**: Active
- **Permissions**: ALL permissions (50+ permissions)

```php
Modules:
- dashboard: view
- students: view, create, edit, delete, approve
- teachers: view, create, edit, delete, approve
- classes: view, create, edit, delete, approve
- bookings: view, create, edit, delete, approve
- attendance: view, create, edit, delete, approve
- credits: view, create, edit, delete, approve
- reports: view, approve
- rules: view, create, edit, delete, approve
- roles: view, create, edit, delete, approve
```

**Access**: Can do EVERYTHING in the system

---

### 2. **Admin** Role
- **Description**: Manage academy operations
- **Status**: Active
- **Permissions**: Almost all (except `roles.delete`)

```php
Same as Super Admin, but CANNOT:
- roles.delete (cannot delete roles)
```

**Access**: Can manage all operations except deleting roles

---

### 3. **Teacher** Role
- **Description**: Manage classes and attendance
- **Status**: Active
- **Permissions**: LIMITED to teaching activities

```php
Allowed Permissions:
- dashboard.view        → Can view teacher dashboard
- classes.view          → Can view their classes
- attendance.view       → Can view attendance
- attendance.create     → Can mark attendance
- attendance.edit       → Can edit attendance
- students.view         → Can view student list
```

**Access**: 
- ✅ Can view dashboard
- ✅ Can see their assigned classes
- ✅ Can mark and manage attendance
- ✅ Can view student information
- ❌ Cannot create/edit students
- ❌ Cannot manage bookings
- ❌ Cannot view reports
- ❌ Cannot manage roles

---

### 4. **Student** Role
- **Description**: View classes and bookings
- **Status**: Active
- **Permissions**: LIMITED to student activities

```php
Allowed Permissions:
- dashboard.view        → Can view student dashboard
- classes.view          → Can view their classes
- bookings.view         → Can view bookings
- bookings.create       → Can book classes
- teachers.view         → Can view teacher list
```

**Access**:
- ✅ Can view dashboard
- ✅ Can see their classes
- ✅ Can book new classes
- ✅ Can view their bookings
- ✅ Can view teachers
- ❌ Cannot see other students
- ❌ Cannot manage attendance
- ❌ Cannot view reports
- ❌ Cannot access admin features

---

## 🔐 How It Works

### Step 1: User Login
```php
// When user logs in
$user = User::find(1);
auth()->login($user);
```

### Step 2: Role Check (Middleware)
```php
// In routes/web/admin.php
Route::middleware(['auth', 'role.access:admin'])
    ->prefix('admin')
    ->group(function () {
        // Admin routes
    });
```

**Middleware Logic** (`EnsureUserHasRole`):
```php
public function handle(Request $request, Closure $next, string ...$roles)
{
    $user = $request->user();
    
    // 1. Check if authenticated
    if (!$user) return redirect()->route('login');
    
    // 2. Check if account is active
    if ($user->status !== 'active') abort(403);
    
    // 3. Check if user has required role (Spatie)
    if ($user->hasAnyRole($roles)) return $next($request);
    
    // 4. Check legacy 'role' column
    if (in_array($user->role, $roles)) return $next($request);
    
    // 5. If not authorized, redirect to their dashboard
    return $this->redirectToDashboard($user);
}
```

### Step 3: Permission Check (in routes)
```php
// Additional permission check on specific routes
Route::get('/students', 'students')
    ->middleware('can:students.view');
```

**Permission Logic**:
```php
// Checks if user's role has this permission
if ($user->can('students.view')) {
    // Allow access
}
```

### Step 4: UI Display (Sidebar)
```php
// In sidebar.blade.php
@php
    $user = auth()->user();
    $isAdmin = !in_array($user->role, ['student', 'teacher']) 
               && !$user->hasRole('Teacher') 
               && !$user->hasRole('Student');
    
    if ($isAdmin) {
        $navItems = [
            ['route' => 'admin.students', 'permission' => 'students.view'],
            // ... more admin menu items
        ];
    } elseif ($isTeacher) {
        $navItems = [
            ['route' => 'teacher.classes', 'permission' => 'classes.view'],
            // ... teacher menu items
        ];
    } else {
        $navItems = [
            ['route' => 'student.bookings', 'permission' => 'bookings.view'],
            // ... student menu items
        ];
    }
    
    // Filter menu items by permissions
    $navItems = array_filter($navItems, function($item) use ($user) {
        return empty($item['permission']) || $user->can($item['permission']);
    });
@endphp
```

---

## 🛠️ How to Use in Code

### Check if User Has Role
```php
// Method 1: Using Spatie
if ($user->hasRole('Admin')) {
    // User is Admin
}

// Method 2: Check multiple roles
if ($user->hasAnyRole(['Admin', 'Super Admin'])) {
    // User is Admin OR Super Admin
}

// Method 3: Check all roles
if ($user->hasAllRoles(['Admin', 'Teacher'])) {
    // User has BOTH roles
}

// Method 4: Legacy role column
if ($user->role === 'admin') {
    // Check legacy field
}
```

### Check if User Has Permission
```php
// Method 1: Using can()
if ($user->can('students.view')) {
    // User can view students
}

// Method 2: In Blade template
@can('students.create')
    <button>Add Student</button>
@endcan

// Method 3: Check multiple permissions
if ($user->canAny(['students.view', 'students.create'])) {
    // User can view OR create students
}

// Method 4: Middleware on route
Route::get('/students', 'index')
    ->middleware('can:students.view');
```

### Assign Role to User
```php
// When creating new user
$user = User::create([...]);

// Assign role
$user->assignRole('Student');

// Or assign multiple roles
$user->assignRole(['Student', 'Teacher']);

// Remove role
$user->removeRole('Student');

// Sync roles (replace all roles)
$user->syncRoles(['Admin']);
```

### Give Direct Permission to User
```php
// Give specific permission (bypasses role)
$user->givePermissionTo('students.delete');

// Revoke permission
$user->revokePermissionTo('students.delete');

// Sync permissions
$user->syncPermissions(['students.view', 'students.edit']);
```

---

## 🎨 UI Examples

### Admin Panel Sidebar
```php
✓ Dashboard            (dashboard.view)
✓ Students             (students.view)
✓ Teachers             (teachers.view)
✓ Classes              (classes.view)
✓ Class Bookings       (bookings.view)
✓ Attendance           (attendance.view)
✓ Credits Management   (credits.view)
✓ Reports & Analytics  (reports.view)
✓ Sales Dashboard      (sales.view)
✓ Business Rules       (rules.view)
✓ User/Role Management (roles.view)
✓ Notifications        (no permission required)
```

### Teacher Panel Sidebar
```php
✓ Teacher Dashboard    (dashboard.view)
✓ My Classes           (classes.view)
✓ Attendance           (attendance.view)
✓ My Profile           (no permission required)
```

### Student Panel Sidebar
```php
✓ Student Dashboard    (dashboard.view)
✓ Book Classes         (bookings.view, bookings.create)
✓ My Classes           (classes.view)
✓ Credits              (credits.view)
✓ My Profile           (no permission required)
```

---

## 🔄 Authentication Flow

```
User Login
    ↓
Laravel Auth
    ↓
Check User Status (active/inactive)
    ↓
Load User Roles (from model_has_roles)
    ↓
Load Role Permissions (from role_has_permissions)
    ↓
Middleware: EnsureUserHasRole
    ├─ Check if user has required role
    ├─ If YES → Allow access
    └─ If NO → Redirect to user's dashboard
    ↓
Route Permission Check (optional)
    ├─ Check if user has specific permission
    ├─ If YES → Allow access
    └─ If NO → 403 Forbidden
    ↓
Controller Action
    ↓
View with Permission Checks
    ├─ Show/hide elements based on permissions
    └─ Use @can, @cannot, @role directives
```

---

## 🎯 Permission Naming Convention

```
Format: {module}.{action}

Modules:
- dashboard
- students
- teachers
- classes
- bookings
- attendance
- credits
- reports
- rules
- roles

Actions:
- view      → Can see/list items
- create    → Can create new items
- edit      → Can update existing items
- delete    → Can remove items
- approve   → Can approve/reject items

Examples:
- students.view    → Can view student list
- students.create  → Can add new students
- teachers.edit    → Can edit teacher details
- roles.delete     → Can delete roles
```

---

## 📋 Seeding (Initial Setup)

```php
// database/seeders/RolePermissionSeeder.php
public function run()
{
    // 1. Create all permissions
    Permission::create(['name' => 'dashboard.view']);
    Permission::create(['name' => 'students.view']);
    // ... 50+ permissions
    
    // 2. Create roles
    $superAdmin = Role::create(['name' => 'Super Admin']);
    $admin = Role::create(['name' => 'Admin']);
    $teacher = Role::create(['name' => 'Teacher']);
    $student = Role::create(['name' => 'Student']);
    
    // 3. Assign permissions to roles
    $superAdmin->syncPermissions(Permission::all());
    $admin->syncPermissions(/* all except roles.delete */);
    $teacher->syncPermissions(['dashboard.view', 'classes.view', ...]);
    $student->syncPermissions(['dashboard.view', 'bookings.view', ...]);
}
```

---

## 🆕 Creating New Users

### Admin Creates Student (Your Current Flow):
```php
// 1. Create User
$user = User::create([
    'name' => $name,
    'email' => $email,
    'password' => Hash::make($password),
    'status' => 'active',
]);

// 2. Assign Role
$user->assignRole('Student');  // ← This is KEY!

// 3. Create Student Record
$student = Student::create([
    'user_id' => $user->id,
    'name' => $name,
    'email' => $email,
    // ... other fields
]);

// 4. Send Welcome Email
Mail::to($user->email)->send(new StudentCreatedMail($user, $password));
```

### What Happens in Database:
```sql
-- 1. Insert into users table
INSERT INTO users (name, email, password, status) VALUES (...);
-- user_id = 10

-- 2. Insert into model_has_roles table
INSERT INTO model_has_roles (role_id, model_type, model_id) 
VALUES (4, 'App\\Models\\User', 10);
-- Links user_id=10 to role_id=4 (Student)

-- 3. Insert into students table
INSERT INTO students (user_id, name, email, ...) VALUES (10, ...);
```

---

## 🚀 Benefits of This System

### 1. **Flexible** ✅
- Easy to add new roles
- Easy to add new permissions
- Easy to change role permissions

### 2. **Scalable** ✅
- Can handle thousands of users
- Database-driven (no code changes needed)
- Cached for performance

### 3. **Secure** ✅
- Multiple layers of protection:
  - Middleware (role check)
  - Route permissions (fine-grained)
  - UI permissions (show/hide elements)

### 4. **Maintainable** ✅
- Clear separation of concerns
- Easy to understand
- Well-documented by Spatie

---

## 🎓 Summary

**Your system uses a TWO-LEVEL security model:**

1. **Level 1: Role-based Access (Middleware)**
   - Admin can access `/admin/*` routes
   - Teacher can access `/teacher/*` routes  
   - Student can access `/student/*` routes

2. **Level 2: Permission-based Access (Routes & UI)**
   - Within their panel, users can only do what their role allows
   - Example: Teacher can VIEW classes but cannot DELETE students

**Key Files:**
- `User.php` - Has `HasRoles` trait
- `EnsureUserHasRole.php` - Middleware for role check
- `RolePermissionSeeder.php` - Sets up roles & permissions
- `sidebar.blade.php` - Shows menu based on role/permissions
- Routes - Protected by middleware and permissions

**This creates a secure, flexible, and maintainable access control system!** 🔐✨

