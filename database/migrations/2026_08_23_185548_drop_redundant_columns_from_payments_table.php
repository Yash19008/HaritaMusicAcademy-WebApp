<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop contact — superseded by dedicated email + phone columns
            if (Schema::hasColumn('payments', 'contact')) {
                $table->dropColumn('contact');
            }
            // Drop preferred_slot — superseded by preferred_date + preferred_time
            if (Schema::hasColumn('payments', 'preferred_slot')) {
                $table->dropColumn('preferred_slot');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('contact')->nullable();
            $table->string('preferred_slot')->nullable();
        });
    }
};
