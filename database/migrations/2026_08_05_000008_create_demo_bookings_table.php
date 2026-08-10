<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demo_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('instrument')->nullable();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes')->default(40);
            $table->string('status')->default('scheduled'); // scheduled, completed, converted, cancelled
            $table->foreignId('converted_student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_bookings');
    }
};
