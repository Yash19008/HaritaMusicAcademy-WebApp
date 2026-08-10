-- Verification SQL Queries for Student Conversion
-- Run these queries to verify the conversion worked correctly

-- ============================================
-- 1. CHECK IF USER WAS CREATED
-- ============================================
-- Replace 'your-email@example.com' with the email you used
SELECT 
    id, 
    name, 
    email, 
    status, 
    created_at 
FROM users 
WHERE email = 'your-email@example.com';

-- Expected Result: 1 row with user details


-- ============================================
-- 2. CHECK IF STUDENT ROLE WAS ASSIGNED
-- ============================================
-- Get the user_id from query above, replace USER_ID below
SELECT 
    u.id as user_id,
    u.name,
    u.email,
    r.name as role_name
FROM users u
INNER JOIN model_has_roles mhr ON u.id = mhr.model_id
INNER JOIN roles r ON mhr.role_id = r.id
WHERE u.email = 'your-email@example.com'
  AND mhr.model_type = 'App\\Models\\User';

-- Expected Result: 1 row showing role = 'student'


-- ============================================
-- 3. CHECK IF STUDENT RECORD WAS CREATED
-- ============================================
SELECT 
    s.id,
    s.user_id,
    s.name,
    s.email,
    s.phone,
    s.enrolled_level,
    s.course_id,
    s.teacher_id,
    s.credits,
    s.status,
    s.joining_date,
    c.name as course_name,
    t.name as teacher_name
FROM students s
LEFT JOIN courses c ON s.course_id = c.id
LEFT JOIN teachers t ON s.teacher_id = t.id
WHERE s.email = 'your-email@example.com';

-- Expected Result: 1 row with student details, credits, course, teacher


-- ============================================
-- 4. CHECK IF PAYMENT/LEAD WAS UPDATED
-- ============================================
-- Find the lead by student name or email in contact field
SELECT 
    id,
    student_name,
    contact,
    instrument,
    amount,
    payment_mode,
    status,
    transaction_date
FROM payments
WHERE contact LIKE '%your-email@example.com%'
  OR student_name LIKE '%Your Name%';

-- Expected Result: 1 row with status = 'converted'


-- ============================================
-- 5. CHECK IF DEMO BOOKING EXISTS AND WAS UPDATED
-- ============================================
-- Find demo by email
SELECT 
    id,
    payment_id,
    student_name,
    email,
    phone,
    instrument,
    teacher_id,
    scheduled_at,
    status,
    converted_student_id
FROM demo_bookings
WHERE email = 'your-email@example.com';

-- Expected Result: 1 row with status = 'converted' (if demo was booked)


-- ============================================
-- 6. VERIFY COMPLETE RELATIONSHIP
-- ============================================
-- This query shows the complete relationship between all tables
SELECT 
    u.id as user_id,
    u.name as user_name,
    u.email as user_email,
    u.status as user_status,
    r.name as role,
    s.id as student_id,
    s.credits,
    s.enrolled_level,
    c.name as course,
    t.name as teacher,
    p.id as payment_id,
    p.amount as paid_amount,
    p.payment_mode,
    p.status as payment_status,
    d.id as demo_id,
    d.scheduled_at as demo_date,
    d.status as demo_status
FROM users u
LEFT JOIN model_has_roles mhr ON u.id = mhr.model_id AND mhr.model_type = 'App\\Models\\User'
LEFT JOIN roles r ON mhr.role_id = r.id
LEFT JOIN students s ON u.id = s.user_id
LEFT JOIN courses c ON s.course_id = c.id
LEFT JOIN teachers t ON s.teacher_id = t.id
LEFT JOIN payments p ON p.contact LIKE CONCAT('%', u.email, '%') AND p.status = 'converted'
LEFT JOIN demo_bookings d ON d.email = u.email
WHERE u.email = 'your-email@example.com';

-- Expected Result: 1 row showing all connected data


-- ============================================
-- 7. CHECK RECENT CONVERSIONS (ALL)
-- ============================================
-- Shows all students converted today
SELECT 
    s.id,
    s.name,
    s.email,
    s.credits,
    s.status,
    s.joining_date,
    c.name as course,
    t.name as teacher
FROM students s
LEFT JOIN courses c ON s.course_id = c.id
LEFT JOIN teachers t ON s.teacher_id = t.id
WHERE s.joining_date = CURDATE()
ORDER BY s.created_at DESC;

-- Expected Result: List of all students converted today


-- ============================================
-- 8. CHECK FOR DUPLICATE EMAILS (TROUBLESHOOTING)
-- ============================================
-- Find if email already exists (causes validation error)
SELECT 'users' as table_name, id, name, email, created_at FROM users WHERE email = 'your-email@example.com'
UNION ALL
SELECT 'students' as table_name, id, name, email, created_at FROM students WHERE email = 'your-email@example.com';

-- Expected Result: 
-- 1 row from users table
-- 1 row from students table
-- (If more than this, you have duplicates!)


-- ============================================
-- 9. CHECK PASSWORD HASH (TROUBLESHOOTING)
-- ============================================
-- Verify password was hashed correctly
SELECT 
    id,
    name,
    email,
    SUBSTRING(password, 1, 10) as password_hash_start,
    LENGTH(password) as password_length,
    created_at
FROM users
WHERE email = 'your-email@example.com';

-- Expected Result: 
-- password_hash_start = '$2y$12$...'
-- password_length = 60


-- ============================================
-- 10. CLEANUP TEST DATA (OPTIONAL)
-- ============================================
-- ⚠️ DANGER: Only run this if you want to delete test data!
-- Uncomment below to use:

/*
-- Delete in reverse order to maintain foreign key integrity
DELETE FROM demo_bookings WHERE email = 'your-email@example.com';
DELETE FROM students WHERE email = 'your-email@example.com';
DELETE FROM model_has_roles WHERE model_id IN (SELECT id FROM users WHERE email = 'your-email@example.com');
DELETE FROM users WHERE email = 'your-email@example.com';
DELETE FROM payments WHERE contact LIKE '%your-email@example.com%';
*/


-- ============================================
-- QUICK REFERENCE: Common Issues
-- ============================================

-- Issue: "Email already taken"
-- Solution: Check query 8 above, delete duplicate if needed

-- Issue: "No user found"
-- Solution: Check query 1, verify conversion completed

-- Issue: "No role assigned"
-- Solution: Check query 2, manually assign if needed:
-- INSERT INTO model_has_roles (role_id, model_type, model_id) 
-- VALUES ((SELECT id FROM roles WHERE name = 'student'), 'App\\Models\\User', YOUR_USER_ID);

-- Issue: "Student can't login"
-- Solution: Check queries 1 and 9, verify user exists and password is hashed
