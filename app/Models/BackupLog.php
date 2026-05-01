<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder whereDate($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder orderByDesc($column)
 * @method static |null first($columns = ['*'])
 */
class BackupLog extends Model
{
    // Backup logs are stored in SQL Server data warehouse
    protected $connection = 'sqlsrv_backup';
    protected $table = 'backup_logs';

    protected $fillable = [
        'product_count',
        'stock_count',
        'warehouse_count',
        'backup_size',
        'total_data',
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
