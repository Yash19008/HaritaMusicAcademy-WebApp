<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->string('instrument');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->integer('duration_minutes')->default(40);
            $table->string('type')->default('one-time'); // one-time, recurring
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $table->string('google_meet_link')->nullable();
            $table->boolean('student_attended')->nullable();
            $table->boolean('teacher_attended')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_bookings');
    }
};
