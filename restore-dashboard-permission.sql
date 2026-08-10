-- Restore dashboard.view permission to Admin role
-- Run this in your MySQL client or phpMyAdmin

-- First, find the permission ID for dashboard.view
SET @permission_id = (SELECT id FROM permissions WHERE name = 'dashboard.view' LIMIT 1);

-- Find the role ID for Admin (or the role you removed the permission from)
SET @role_id = (SELECT id FROM roles WHERE name = 'Admin' LIMIT 1);

-- Re-add the permission to the role
INSERT IGNORE INTO role_has_permissions (permission_id, role_id) 
VALUES (@permission_id, @role_id);

-- Verify it's added
SELECT 
    r.name as role_name,
    p.name as permission_name
FROM role_has_permissions rhp
JOIN roles r ON r.id = rhp.role_id
JOIN permissions p ON p.id = rhp.permission_id
WHERE r.name = 'Admin' AND p.name = 'dashboard.view';

-- If you removed it from multiple roles, run for each:
-- For Super Admin:
SET @super_admin_id = (SELECT id FROM roles WHERE name = 'Super Admin' LIMIT 1);
INSERT IGNORE INTO role_has_permissions (permission_id, role_id) 
VALUES (@permission_id, @super_admin_id);

-- For Teacher:
SET @teacher_id = (SELECT id FROM roles WHERE name = 'Teacher' LIMIT 1);
INSERT IGNORE INTO role_has_permissions (permission_id, role_id) 
VALUES (@permission_id, @teacher_id);

-- For Student:
SET @student_id = (SELECT id FROM roles WHERE name = 'Student' LIMIT 1);
INSERT IGNORE INTO role_has_permissions (permission_id, role_id) 
VALUES (@permission_id, @student_id);
