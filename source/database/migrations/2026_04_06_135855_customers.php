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
        Schema::create('customers', function (Blueprint $table) {
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 30)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('profile_img', 20)->nullable();
            $table->unsignedInteger('primary_address_id')->nullable();
            $table->foreign('primary_address_id')
                ->references('address_id')
                ->on('addresses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
            # jhan: i omitted primary_banking_info as instructed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
