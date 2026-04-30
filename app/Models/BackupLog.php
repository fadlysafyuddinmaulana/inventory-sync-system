<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    protected $connection = 'sqlsrv_backup';
    protected $table = 'backup_logs';

    protected $fillable = [
        'product_count',
        'stock_count',
        'warehouse_count',
        'backup_size',
        'status',
        'message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
