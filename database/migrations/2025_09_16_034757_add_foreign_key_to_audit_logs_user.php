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
        Schema::table('audit_logs', function (Blueprint $table) {
            // Add user_id column if it doesn't exist
            if (!Schema::hasColumn('audit_logs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
        });

        // Add foreign key in a separate operation with error handling
        Schema::table('audit_logs', function (Blueprint $table) {
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
        Schema::table('audit_logs', function (Blueprint $table) {
            try {
                $table->dropForeign(['user_id']);
            } catch (Exception $e) {
                // Foreign key might not exist, continue
            }
        });
    }
};