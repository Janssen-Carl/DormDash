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
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('discount_id');
            $table->unsignedInteger('item_id');
            $table->foreign('item_id')
                ->references('item_id')
                ->on('items')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->dateTime('date_end');
            $table->dateTime('date_start');
            $table->enum('type',['percentage', 'fixed']);
            $table->decimal('value', 10, 2);
            $table->unsignedInteger('use_limit')->nullable();
            $table->text('description')->nullable();
            $table->string('name', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('item_id');
            $table->index('date_start');
            $table->index('date_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
