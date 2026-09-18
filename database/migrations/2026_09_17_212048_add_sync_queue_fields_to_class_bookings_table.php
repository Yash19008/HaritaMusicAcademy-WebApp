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
            $table->unsignedTinyInteger('google_sync_attempts')->default(0)->after('google_sync_status');
            $table->timestamp('next_retry_at')->nullable()->after('google_sync_attempts');
            $table->unsignedBigInteger('meet_link_source_booking_id')->nullable()->after('google_event_payload');
            $table->timestamp('meet_link_generated_at')->nullable()->after('meet_link_source_booking_id');
            
            $table->foreign('meet_link_source_booking_id', 'fk_meet_link_source')->references('id')->on('class_bookings')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropForeign('fk_meet_link_source');
            $table->dropColumn([
                'google_sync_attempts',
                'next_retry_at',
                'meet_link_source_booking_id',
                'meet_link_generated_at'
            ]);
        });
    }
};
