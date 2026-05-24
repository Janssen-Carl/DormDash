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
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('admin_id');  // matches users.user_id (increments = unsigned int)
            $table->foreign('admin_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('action'); // approved_vendor, deleted_vendor, deleted_customer, etc.
            $table->unsignedInteger('target_user_id'); // no FK — logs persist after user deletion
            $table->string('target_username');
            $table->string('target_role')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
