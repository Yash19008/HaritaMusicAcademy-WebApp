# 🔒 Adding @can Directives to Harita - Complete Guide

## ✅ ANSWER: YES, Approve is SEPARATE from Edit!

### Permission Types:
1. **`module.view`** - View/read data
2. **`module.create`** - Add new records
3. **`module.edit`** - Edit existing records
4. **`module.delete`** - Delete records
5. **`module.approve`** - **SEPARATE!** Approve/Reject items (workflow control)

---

## 📋 WHERE TO ADD @can DIRECTIVES

### Pattern from Rockforsure:
```php
@can('permission.name')
    <button>Action Button</button>
@endcan
```

---

## 1️⃣ STUDENTS PAGE
**File:** `resources/views/admin/students.blade.php`

### Add Button (Line ~49-56):
**WRAP WITH:**
```php
@can('students.create')
    <button class="btn btn-primary" onclick="openAddModal()">
        Add Student
    </button>
@endcan
```

### Bulk Upload Button (Line ~42-48):
**WRAP WITH:**
```php
@can('students.create')
    <button class="btn btn-secondary" onclick="showModal('bulkUploadModal')">
        Bulk Upload
    </button>
@endcan
```

### Edit Button (Line ~94-97):
**WRAP WITH:**
```php
@can('students.edit')
    <button class="btn btn-secondary btn-icon" title="Edit Student">
        Edit
    </button>
@endcan
```

### Create Group Button (Line ~126-133):
**WRAP WITH:**
```php
@can('students.create')
    <button class="btn btn-primary" onclick="openAddGroupModal()">
        Create Group
    </button>
@endcan
```

---

## 2️⃣ TEACHERS PAGE
**File:** `resources/views/admin/teachers.blade.php`

Find all action buttons and wrap them:

### Add Teacher Button:
```php
@can('teachers.create')
    <button onclick="openAddModal()">Add Teacher</button>
@endcan
```

### Edit Button:
```php
@can('teachers.edit')
    <button>Edit</button>
@endcan
```

### Delete Button:
```php
@can('teachers.delete')
    <button>Delete</button>
@endcan
```

---

## 3️⃣ REFERRALS PAGE
**File:** `resources/views/admin/referrals/index.blade.php`

### Approve Button (Line ~103):
**WRAP WITH:**
```php
@can('referrals.approve')
    <form action="{{ route('admin.referrals.update', $ref->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="approved">
        <button type="submit" class="badge badge-success">Approve</button>
    </form>
@endcan
```

### Reject Button (Line ~109):
**WRAP WITH:**
```php
@can('referrals.approve')
    <form action="{{ route('admin.referrals.update', $ref->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="rejected">
        <button type="submit" class="badge badge-danger">Reject</button>
    </form>
@endcan
```

**NOTE:** Both approve AND reject use `referrals.approve` permission (it's the same workflow action)

---

## 4️⃣ LEAVES PAGE
**File:** `resources/views/admin/leaves/index.blade.php`

### Approve Leave Button:
**WRAP WITH:**
```php
@can('leaves.approve')
    <form method="POST" action="{{ route('admin.leaves.approve', $leave->id) }}">
        @csrf
        <button>Approve</button>
    </form>
@endcan
```

### Reject Leave Button:
**WRAP WITH:**
```php
@can('leaves.approve')
    <form method="POST" action="{{ route('admin.leaves.reject', $leave->id) }}">
        @csrf
        <button>Reject</button>
    </form>
@endcan
```

---

## 5️⃣ DEMOS PAGE
**File:** `resources/views/admin/demos.blade.php`

### Add Demo Button:
```php
@can('demos.create')
    <button>Add Demo</button>
@endcan
```

### Edit Button:
```php
@can('demos.edit')
    <button>Edit</button>
@endcan
```

### Delete Button:
```php
@can('demos.delete')
    <button>Delete</button>
@endcan
```

### Convert to Student Button (if exists):
```php
@can('demos.approve')
    <button>Convert to Student</button>
@endcan
```

---

## 6️⃣ CREDITS PAGE
**File:** `resources/views/admin/credits.blade.php`

### Add Credits Button:
```php
@can('credits.create')
    <button>Add Credits</button>
@endcan
```

### Adjust Credits Button:
```php
@can('credits.edit')
    <button>Adjust</button>
@endcan
```

---

## 7️⃣ BOOKINGS PAGE
**File:** `resources/views/admin/bookings/*.blade.php`

### Create Booking Button:
```php
@can('bookings.create')
    <button>Create Booking</button>
@endcan
```

### Edit Booking Button:
```php
@can('bookings.edit')
    <button>Edit</button>
@endcan
```

### Delete Booking Button:
```php
@can('bookings.delete')
    <button>Delete</button>
@endcan
```

### Approve Booking Button:
```php
@can('bookings.approve')
    <button>Approve</button>
@endcan
```

---

## 8️⃣ SALES PAGE
**File:** `resources/views/admin/sales.blade.php`

### Add Payment Button:
```php
@can('sales.create')
    <button>Add Payment</button>
@endcan
```

### Edit Payment Button:
```php
@can('sales.edit')
    <button>Edit</button>
@endcan
```

### Delete Payment Button:
```php
@can('sales.delete')
    <button>Delete</button>
@endcan
```

### Approve Payment Button:
```php
@can('sales.approve')
    <button>Approve</button>
@endcan
```

---

## 9️⃣ REPORTS PAGE
**File:** `resources/views/admin/reports.blade.php`

### Export Button:
```php
@can('reports.create')
    <button>Export Report</button>
@endcan
```

---

## 🔟 ROLES PAGE
**File:** `resources/views/admin/roles/index.blade.php`

### Create Role Button:
```php
@can('roles.create')
    <button>Create Role</button>
@endcan
```

### Edit Permissions:
```php
@can('roles.edit')
    <!-- Permission checkboxes -->
@endcan
```

---

## 📊 COMPLETE PERMISSION LIST

### All Modules with Approve:

| Module | View | Create | Edit | Delete | Approve |
|--------|------|--------|------|--------|---------|
| **students** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **teachers** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **bookings** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **demos** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **sales** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **credits** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **referrals** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **leaves** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **reports** | ✅ | ✅ | ❌ | ✅ | ❌ |
| **roles** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **dashboard** | ✅ | ❌ | ❌ | ❌ | ❌ |

---

## 🎯 WHY APPROVE IS SEPARATE

### Real-World Use Cases:

#### Scenario 1: Junior Admin
```
✅ students.view    - Can see student list
✅ students.edit    - Can update student details
❌ students.approve - CANNOT approve registrations
```
**Result:** Can edit data but senior admin must approve new registrations.

#### Scenario 2: Accountant
```
✅ sales.view       - Can see payment list
❌ sales.edit       - CANNOT edit payment details
✅ sales.approve    - CAN approve/reject payments
```
**Result:** Can approve payments but cannot modify payment data.

#### Scenario 3: Reception
```
✅ demos.view       - Can see demo requests
✅ demos.create     - Can book new demos
❌ demos.approve    - CANNOT convert to student
```
**Result:** Can schedule demos but teacher must convert to student.

---

## 🔄 WORKFLOW EXAMPLE

### Demo to Student Conversion:

**Step 1:** Reception books demo
- Permission: `demos.create` ✅

**Step 2:** Demo class happens
- Student attends

**Step 3:** Teacher decides to convert
- Permission: `demos.approve` ✅
- Action: Convert demo to regular student

**Step 4:** Payment approved
- Permission: `sales.approve` ✅
- Accountant confirms payment

---

## ✅ HOW TO TEST

### Test 1: Remove Create Permission
1. Go to Roles page
2. Uncheck `students.create` for Admin role
3. Refresh page
4. ✅ "Add Student" button should DISAPPEAR

### Test 2: Remove Edit Permission
1. Uncheck `students.edit` for Admin role
2. Refresh page
3. ✅ "Edit" buttons should DISAPPEAR

### Test 3: Remove Approve Permission
1. Uncheck `referrals.approve` for Admin role
2. Go to Referrals page
3. ✅ "Approve" and "Reject" buttons should DISAPPEAR

### Test 4: Keep Only View Permission
1. Uncheck ALL except `students.view`
2. Refresh Students page
3. ✅ Only student list visible
4. ✅ NO action buttons visible
5. ✅ Can still see sidebar menu item (has view permission)

---

## 🚨 IMPORTANT NOTES

### 1. Both Approve AND Reject use same permission:
```php
@can('module.approve')
    <button>Approve</button>
    <button>Reject</button>
@endcan
```
**Why?** Both are workflow actions. If you can approve, you can reject.

### 2. Create permission covers:
- Add new record
- Bulk upload
- Import CSV
- Duplicate record

### 3. Edit permission covers:
- Edit existing record
- Update fields
- Change status (active/inactive)

### 4. Approve permission covers:
- Approve pending items
- Reject pending items
- Convert demo to student
- Confirm payments
- Accept leave requests

---

## 📝 QUICK COPY-PASTE SNIPPETS

### For Add/Create Buttons:
```php
@can('MODULE.create')
    <button>Add New</button>
@endcan
```

### For Edit Buttons:
```php
@can('MODULE.edit')
    <button>Edit</button>
@endcan
```

### For Delete Buttons:
```php
@can('MODULE.delete')
    <form method="POST">
        @csrf
        @method('DELETE')
        <button>Delete</button>
    </form>
@endcan
```

### For Approve/Reject Buttons:
```php
@can('MODULE.approve')
    <button>Approve</button>
    <button>Reject</button>
@endcan
```

**Replace `MODULE` with:** students, teachers, bookings, demos, sales, credits, referrals, leaves, reports, roles

---

## 🎯 IMPLEMENTATION CHECKLIST

### Priority 1 (High Traffic Pages):
- [ ] Students page - Add, Edit, Create Group buttons
- [ ] Teachers page - Add, Edit, Delete buttons
- [ ] Bookings page - Create, Edit, Delete buttons
- [ ] Referrals page - Approve, Reject buttons

### Priority 2 (Admin Actions):
- [ ] Demos page - Add, Edit, Convert buttons
- [ ] Sales page - Add, Edit, Approve buttons
- [ ] Leaves page - Approve, Reject buttons
- [ ] Credits page - Add, Adjust buttons

### Priority 3 (Settings):
- [ ] Roles page - Create role, Edit permissions
- [ ] Reports page - Export button

---

## 📄 NEXT STEPS

1. ✅ **Read this guide** - Understand approve vs edit
2. ✅ **Find buttons** - Open each admin blade file
3. ✅ **Wrap with @can** - Add directives around buttons
4. ✅ **Test each page** - Remove permissions and verify
5. ✅ **Clear cache** - Run `php artisan view:clear`

---

**Last Updated:** August 9, 2026  
**Status:** Ready to implement  
**Estimated Time:** 2-3 hours for all pages

