<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demo_bookings', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('demo_bookings', 'payment_id')) {
                $table->foreignId('payment_id')->nullable()->after('id')->constrained('payments')->nullOnDelete();
            }
            if (!Schema::hasColumn('demo_bookings', 'email')) {
                $table->string('email')->nullable()->after('student_name');
            }
            if (!Schema::hasColumn('demo_bookings', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('demo_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('demo_bookings', 'payment_id')) {
                $table->dropForeign(['payment_id']);
                $table->dropColumn('payment_id');
            }
            if (Schema::hasColumn('demo_bookings', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('demo_bookings', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }
};
