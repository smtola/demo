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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference')->nullable();
            $table->decimal('subtotal',12,2)->default(0);
            $table->decimal('discount',12,2)->default(0);
            $table->integer('tax')->default(0);
            $table->decimal('total_amount',12,2)->default(0);
            $table->enum('payment_method',['CASH','ABAPAY_KHQR', 'CARDS','ABAPAY','ALIPAY', 'WECHAT'])->default('CASH');
            $table->enum('status',['paid','pending','failed'])->default('pending');
            $table->date('sale_date')->nullable();
            $table->text('customer_info')->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
