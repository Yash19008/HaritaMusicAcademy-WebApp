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
            $table->index(['teacher_id', 'status', 'starts_at'], 'idx_teacher_status_starts');
            $table->index(['student_id', 'status'], 'idx_student_status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('razorpay_order_id', 'idx_razorpay_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropIndex('idx_teacher_status_starts');
            $table->dropIndex('idx_student_status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_razorpay_order');
        });
    }
};
