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
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('product_count')->default(0);
            $table->integer('stock_count')->default(0);
            $table->integer('warehouse_count')->default(0);
            $table->string('backup_size')->nullable();
            $table->integer('total_data')->default(0);
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->text('message')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
    }
};
