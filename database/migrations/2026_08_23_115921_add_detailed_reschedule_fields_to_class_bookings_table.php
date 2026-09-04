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
            $table->string('reschedule_status')->nullable()->after('status'); // pending, approved, rejected
            $table->foreignId('reschedule_requested_teacher_id')->nullable()->constrained('teachers')->nullOnDelete()->after('reschedule_status');
            $table->dateTime('reschedule_requested_starts_at')->nullable()->after('reschedule_requested_teacher_id');
            $table->dateTime('reschedule_requested_ends_at')->nullable()->after('reschedule_requested_starts_at');
            $table->string('google_event_id')->nullable()->after('google_meet_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropForeign(['reschedule_requested_teacher_id']);
            $table->dropColumn([
                'reschedule_status',
                'reschedule_requested_teacher_id',
                'reschedule_requested_starts_at',
                'reschedule_requested_ends_at',
                'google_event_id'
            ]);
        });
    }
};
