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
            $table->string('google_calendar_id')->nullable()->after('google_event_id');
            $table->string('google_sync_status')->default('not_configured')->index()->after('google_calendar_id');
            $table->text('google_sync_message')->nullable()->after('google_sync_status');
            $table->json('google_event_payload')->nullable()->after('google_sync_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'google_calendar_id',
                'google_sync_status',
                'google_sync_message',
                'google_event_payload'
            ]);
        });
    }
};
