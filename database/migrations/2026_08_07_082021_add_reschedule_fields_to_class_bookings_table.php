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
            $table->string('rescheduled_by')->nullable();
            $table->dateTime('reschedule_requested_datetime')->nullable();
            $table->text('reschedule_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropColumn(['rescheduled_by', 'reschedule_requested_datetime', 'reschedule_reason']);
        });
    }
};
