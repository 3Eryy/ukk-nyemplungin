<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique(); // Format: INV-{rental_id}-{timestamp}
            $table->string('payment_type')->nullable(); // bank_transfer, credit_card, etc
            $table->enum('status', [
                'pending', 'settlement', 'capture', 
                'deny', 'expire', 'cancel', 'refund'
            ])->default('pending');
            $table->bigInteger('amount');
            $table->string('va_number')->nullable(); // Virtual Account number
            $table->string('bank')->nullable(); // Bank name (BCA, BNI, etc)
            $table->json('midtrans_response')->nullable(); // Simpan raw response dari Midtrans
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('order_id');
            $table->index(['rental_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};