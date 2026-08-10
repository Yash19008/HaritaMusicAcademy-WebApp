<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Courses ───────────────────────────────────────────────────────────
        $courses = ['Vocals', 'Sitar', 'Violin', 'Flute', 'Tabla'];
        foreach ($courses as $courseName) {
            \App\Models\Course::firstOrCreate(
                ['name' => $courseName],
                ['description' => $courseName . ' classes', 'status' => 'active']
            );
        }
        // ── Roles ─────────────────────────────────────────────────────────────
        $adminRole   = Role::firstOrCreate(['name' => 'admin',   'guard_name' => 'web']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // ── Admin User ────────────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@haritamusic.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'status'   => 'active',
                'phone'    => '+91 98765 43210',
            ]
        );
        $admin->syncRoles([$adminRole]);

        // ── Demo Teacher User ─────────────────────────────────────────────────
        $teacherUser = User::firstOrCreate(
            ['email' => 'meera@haritamusic.com'],
            [
                'name'     => 'Meera Sharma',
                'password' => Hash::make('password'),
                'role'     => 'teacher',
                'status'   => 'active',
                'phone'    => '+91 87654 32109',
            ]
        );
        $teacherUser->syncRoles([$teacherRole]);

        $vocalCourse = \App\Models\Course::where('name', 'Vocals')->first();

        $teacher = \App\Models\Teacher::firstOrCreate(
            ['email' => 'meera@haritamusic.com'],
            [
                'user_id'        => $teacherUser->id,
                'name'           => 'Meera Sharma',
                'phone'          => '+91 87654 32109',
                'course_id'      => $vocalCourse->id ?? null,
                'week_off'       => 'Sunday',
                'status'         => 'active',
                'bio'            => 'Classical vocalist with 12+ years of teaching experience.',
                'per_class_rate' => 500,
            ]
        );

        // ── Demo Student User ─────────────────────────────────────────────────
        $studentUser = User::firstOrCreate(
            ['email' => 'aria@haritamusic.com'],
            [
                'name'     => 'Aria Sharma',
                'password' => Hash::make('password'),
                'role'     => 'student',
                'status'   => 'active',
            ]
        );
        $studentUser->syncRoles([$studentRole]);

        \App\Models\Student::firstOrCreate(
            ['email' => 'aria@haritamusic.com'],
            [
                'user_id'      => $studentUser->id,
                'teacher_id'   => $teacher->id,
                'name'         => 'Aria Sharma',
                'phone'        => '+91 99887 76655',
                'course_id'    => $vocalCourse->id ?? null,
                'credits'      => 8,
                'status'       => 'active',
                'joining_date' => '2026-01-10',
            ]
        );

        // ── Default Settings ──────────────────────────────────────────────────
        $defaults = [
            'academy_name'                 => 'Harita Music Academy',
            'contact_email'                => 'admin@haritamusic.com',
            'support_phone'                => '+91 98765 43210',
            'address'                      => 'Bangalore, Karnataka, India',
            'class_duration'               => '40',
            'reschedule_lock_hours'        => '4',
            'require_approval'             => '1',
            'auto_deduct_credits'          => '1',
            'opportunity_teacher_pct'      => '10',
            'opportunity_student_credits'  => '2',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
