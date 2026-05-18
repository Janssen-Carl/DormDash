<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('item_id');
            $table->unsignedInteger('vendor_id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->string('sku', 100)->unique()->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('barcode', 100)->nullable();
            $table->string('unit_type', 30)->nullable();
            $table->decimal('unit_value', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_bundle')->default(false);
            $table->boolean('is_perishable')->default(false);
            $table->boolean('is_available')->default(true);
            $table->boolean('has_expiry')->default(false);
            $table->timestamps();

            // Foreign key
            $table->foreign('vendor_id')
                ->references('vendor_id')
                ->on('vendors')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Indexes
            $table->index(['vendor_id', 'name', 'brand', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
