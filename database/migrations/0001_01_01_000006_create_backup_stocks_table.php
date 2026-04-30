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
        // Create backup stocks table on SQL Server
        if (!Schema::connection('sqlsrv_backup')->hasTable('backup_stocks')) {
            Schema::connection('sqlsrv_backup')->create('backup_stocks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->string('product_name');
                $table->unsignedBigInteger('location_id');
                $table->string('location_name');
                $table->unsignedBigInteger('warehouse_id')->nullable();
                $table->string('warehouse_name')->nullable();
                $table->decimal('quantity', 12, 2)->default(0);
                $table->decimal('reserved_quantity', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv_backup')->dropIfExists('backup_stocks');
    }
};
