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

        Schema::create('pages', function (Blueprint $table) {
            $table->increments('page_id');
            $table->unsignedInteger('vendor_id');
            $table->foreign('vendor_id')
                ->references('vendor_id')
                ->on('vendors')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('title', 100);
            $table->text('description');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            $table->index('vendor_id');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
