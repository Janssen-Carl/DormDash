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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->increments('order_id');
            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->string('status', 50)->default('pending');
            $table->string('reference_no', 50)->nullable()->default('pending');
            $table->string('token', 255)->nullable()->default(null);
            $table->char('acc_last4_no', 4)->nullable()->default(null);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
