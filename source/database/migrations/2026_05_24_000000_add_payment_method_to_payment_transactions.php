<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * FR-18: Record payment method (cod / card)
     * FR-19: COD orders default to 'pending' payment status, not 'success'
     */
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            // FR-18: store which payment method was used
            $table->string('payment_method', 20)->default('cod')->after('order_id');
        });

        // FR-19: patch any existing COD rows that were incorrectly set to 'success'
        // (they should start as 'pending' until cash is collected on delivery)
        DB::table('payment_transactions')
            ->where('status', 'success')
            ->whereNull('token')          // token is only set for card payments
            ->whereNull('acc_last4_no')   // card details absent → COD
            ->update(['status' => 'pending', 'payment_method' => 'cod']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
