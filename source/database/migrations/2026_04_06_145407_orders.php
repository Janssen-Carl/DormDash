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
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('order_id');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')
                ->references('customer_id')
                ->on('customers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedInteger('address_id');
            $table->foreign('address_id')
                ->references('address_id')
                ->on('addresses')
                ->onDelete('restrict')
                ->cascadeOnUpdate();
            $table->string('shipping_method', 50);
            $table->decimal('order_total', 10, 2);
            $table->date('send_date')->nullable();
            $table->date('receive_date')->nullable();
            $table->string('order_status', 50)->default('pending');
            $table->string('tracking_number', 100)->nullable();
            $table->timestamps();
            $table->index('customer_id');
            $table->index('address_id');
            $table->index('order_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
