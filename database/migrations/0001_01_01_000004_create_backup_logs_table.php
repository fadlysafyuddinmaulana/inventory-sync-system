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
        // This migration is for SQL Server backup database
        // Execute this manually or use a separate migration path for SQL Server
        
        if (!Schema::connection('sqlsrv_backup')->hasTable('backup_logs')) {
            Schema::connection('sqlsrv_backup')->create('backup_logs', function (Blueprint $table) {
                $table->id();
                $table->integer('product_count')->default(0);
                $table->integer('stock_count')->default(0);
                $table->integer('warehouse_count')->default(0);
                $table->integer('total_data')->default(0);
                $table->string('backup_size', 255)->nullable();
                $table->string('status', 255)->default('pending');
                $table->text('message')->nullable();
                $table->dateTime('started_at')->nullable();
                $table->dateTime('completed_at')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv_backup')->dropIfExists('backup_logs');
    }
};
