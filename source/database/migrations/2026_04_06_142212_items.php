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
        Schema::create('items', function (Blueprint $table) {
            $table->increments('item_id');
            $table->unsignedInteger('vendor_id');
            $table->foreign('vendor_id')
                ->references('vendor_id')
                ->on('vendors');
            $table->decimal('price', 10, 2);
            $table->string('name', 150);
            $table->text('description');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('stock')->default(0);
            $table->string('sku', 100)->unique();
            $table->boolean('is_bundle')->default(false);
            $table->string('unit_type', 30);
            $table->decimal('unit_value', 10, 2);
            $table->string('brand', 100);
            $table->string('barcode', 100);
            $table->boolean('is_perishable')->default(false);
            $table->boolean('is_available')->default(true);
            $table->boolean('has_expiry')->default(false);
            $table->index('vendor_id');
            $table->index('name');
            $table->index('brand');
            $table->index('is_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
