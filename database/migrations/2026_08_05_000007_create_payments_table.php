<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Payments = Sales leads & inquiries (sales page)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('contact')->nullable(); // phone or email
            $table->string('instrument')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('payment_mode')->nullable(); // UPI, Cash, Net Banking, Card
            $table->date('transaction_date')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, cancelled
            $table->foreignId('converted_student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
