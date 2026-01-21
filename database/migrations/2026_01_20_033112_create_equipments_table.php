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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('equipment_categories')
                ->onDelete('cascade');
            $table->text('description')->nullable();
            $table->decimal('hourly_price', 10, 2);
            $table->integer('stock')->default(0);
            $table->enum('condition_status', ['baik', 'rusak'])->default('baik');
            $table->enum('available_status', ['tersedia', 'tidak_tersedia', 'dipinjam'])->default('tersedia');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
