<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->string('week_off')->nullable();   // e.g. "MON,TUE"
            $table->string('status')->default('active');
            $table->text('bio')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('certifications')->nullable(); // comma-separated
            $table->decimal('per_class_rate', 10, 2)->default(0);
            // Additional fields from static template
            $table->string('experience')->nullable();          // e.g. "8 Years"
            $table->string('specialization')->nullable();
            $table->date('joining_date')->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->string('level')->nullable();              // Foundation / Intermediate / Advanced
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
