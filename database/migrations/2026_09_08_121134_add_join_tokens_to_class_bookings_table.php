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
        Schema::table('class_bookings', function (Blueprint $table) {
            // Role-specific tokens — teacher token can ONLY mark teacher_attended,
            // student token can ONLY mark student_attended. No cross-role possible.
            $table->string('teacher_join_token')->nullable()->unique()->after('google_meet_link');
            $table->string('student_join_token')->nullable()->unique()->after('teacher_join_token');
        });
    }

    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropColumn(['teacher_join_token', 'student_join_token']);
        });
    }
};
