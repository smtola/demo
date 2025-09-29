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
        Schema::table('stock_movements', function (Blueprint $table) {
            // Add foreign keys with proper error handling
            try {
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
            } catch (Exception $e) {
                // Foreign key might already exist, continue
            }

            try {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            } catch (Exception $e) {
                // Foreign key might already exist, continue
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            try {
                $table->dropForeign(['product_id']);
            } catch (Exception $e) {
                // Foreign key might not exist, continue
            }

            try {
                $table->dropForeign(['user_id']);
            } catch (Exception $e) {
                // Foreign key might not exist, continue
            }
        });
    }
};