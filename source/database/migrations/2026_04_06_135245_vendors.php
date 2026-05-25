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
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('vendor_id');
            $table->foreign('vendor_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('email', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('website', 255)->nullable();
            $table->unsignedInteger('address_id')->nullable();
            $table->foreign('address_id')
                ->references('address_id')
                ->on('addresses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->boolean('active')->default(true);
            $table->string('cover_img', 255)->nullable();
            $table->string('profile_img', 255)->nullable();
            $table->timestamps();
            $table->index('address_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
