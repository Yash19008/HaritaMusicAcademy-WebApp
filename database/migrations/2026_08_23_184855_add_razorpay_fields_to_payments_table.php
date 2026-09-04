<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('email')->nullable()->after('contact');
            $table->string('phone')->nullable()->after('email');
            $table->string('razorpay_order_id')->nullable()->unique()->after('status');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['email', 'phone', 'preferred_slot', 'razorpay_order_id', 'razorpay_payment_id']);
        });
    }
};
