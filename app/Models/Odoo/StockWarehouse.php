<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;

class StockWarehouse extends Model
{
    protected $connection = 'pgsql_odoo';
    protected $table = 'stock_warehouse';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'company_id',
    ];

    public function locations()
    {
        return $this->hasMany(StockLocation::class, 'warehouse_id');
    }

    public function quants()
    {
        return $this->hasManyThrough(
            StockQuant::class,
            StockLocation::class,
            'warehouse_id',
            'location_id'
        );
    }
}
