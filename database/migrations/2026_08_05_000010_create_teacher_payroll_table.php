<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_payroll', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->string('month'); // e.g. "July 2026"
            $table->decimal('per_class_rate', 10, 2)->default(0);
            $table->integer('classes_taken')->default(0);
            $table->integer('opportunity_taken')->default(0);
            $table->decimal('formula_salary', 10, 2)->default(0); // static/base
            $table->decimal('calculated_salary', 10, 2)->default(0); // actual computed
            $table->string('status')->default('pending'); // pending, paid
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_payroll');
    }
};
