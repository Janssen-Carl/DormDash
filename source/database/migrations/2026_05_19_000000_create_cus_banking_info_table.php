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
        Schema::create('cus_banking_info', function (Blueprint $table) {
            $table->increments('banking_id');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')
                ->references('customer_id')
                ->on('customers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('payment_method', 50);
            $table->string('provider', 100)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->string('phone_number', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('token', 255)->nullable();
            $table->char('acc_last4_no', 4)->nullable();
            $table->string('account_type', 50)->nullable();
            $table->timestamps();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedInteger('primary_banking_info')->nullable()->after('primary_address_id');
            $table->foreign('primary_banking_info')
                ->references('banking_id')
                ->on('cus_banking_info')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['primary_banking_info']);
            $table->dropColumn('primary_banking_info');
        });

        Schema::dropIfExists('cus_banking_info');
    }
};
