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
        Schema::table('teacher_payroll', function (Blueprint $table) {
            $table->integer('demo_classes')->default(0)->after('classes_taken');
            $table->integer('emergency_classes')->default(0)->after('demo_classes');
            $table->integer('referrals')->default(0)->after('emergency_classes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_payroll', function (Blueprint $table) {
            $table->dropColumn(['demo_classes', 'emergency_classes', 'referrals']);
        });
    }
};
