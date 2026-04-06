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
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('item_id');
            $table->foreign('item_id')
                ->references('item_id')
                ->on('items')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedInteger('old_stock');
            $table->unsignedInteger('new_stock');
            $table->unsignedInteger('quantity_changed');
            $table->string('remarks', 255);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
