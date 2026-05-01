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
        // Create backup products table on SQL Server
        if (!Schema::connection('sqlsrv_backup')->hasTable('backup_products')) {
            Schema::connection('sqlsrv_backup')->create('backup_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->string('name');
                $table->string('code')->nullable();
                $table->decimal('price', 12, 2)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv_backup')->dropIfExists('backup_products');
    }
};
