<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);

            // Shipping Info
            $table->string('full_name');
            $table->string('phone');
            $table->string('address_line');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');

            // Payment Info
            $table->string('payment_method')->default('cod');
            $table->string('payment_status')->default('pending');

            // Order status: pending, confirmed, processing, shipped, delivered, cancelled
            $table->string('status')->default('pending');

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
