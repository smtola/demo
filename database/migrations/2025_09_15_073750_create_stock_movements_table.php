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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type',['in','out']);
            $table->integer('quantity');
            $table->decimal('cost_price',12,2)->nullable();
            $table->decimal('selling_price',12,2)->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->date('movement_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });              
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
