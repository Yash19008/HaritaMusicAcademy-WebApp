<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('demo_bookings', function (Blueprint $table) {
            $table->boolean('teacher_attended')->nullable()->after('status');
            $table->boolean('student_attended')->nullable()->after('teacher_attended');
            $table->string('teacher_join_token')->nullable()->unique()->after('google_meet_link');
            $table->string('student_join_token')->nullable()->unique()->after('teacher_join_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'teacher_attended',
                'student_attended',
                'teacher_join_token',
                'student_join_token',
            ]);
        });
    }
};
