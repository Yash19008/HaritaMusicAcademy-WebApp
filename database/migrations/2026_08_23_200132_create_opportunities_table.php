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
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('original_teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('accepted_teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('status')->default('pending'); // pending, accepted, expired
            $table->dateTime('active_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('opportunity_rejections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('opportunities')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunity_rejections');
        Schema::dropIfExists('opportunities');
    }
};
