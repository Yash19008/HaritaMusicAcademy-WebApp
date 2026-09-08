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
        Schema::create('reminder_configs', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // e.g. "10 Hours Before", "30 Minutes Before"
            $table->integer('minutes_before');
            $table->boolean('notify_student')->default(true);
            $table->boolean('notify_teacher')->default(true);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Insert default configs
        \DB::table('reminder_configs')->insert([
            [
                'label' => '10 Hours Before',
                'minutes_before' => 600,
                'notify_student' => true,
                'notify_teacher' => true,
                'enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => '30 Minutes Before',
                'minutes_before' => 30,
                'notify_student' => true,
                'notify_teacher' => true,
                'enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminder_configs');
    }
};
