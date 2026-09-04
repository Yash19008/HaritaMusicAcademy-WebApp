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
            $table->foreignId('student_id')->nullable()->change();
            $table->foreignId('student_group_id')->nullable()->after('student_id')->constrained('student_groups')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropForeign(['student_group_id']);
            $table->dropColumn('student_group_id');
            // Revert student_id nullable change
            $table->foreignId('student_id')->nullable(false)->change();
        });
    }
};
