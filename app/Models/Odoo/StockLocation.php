<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;

class StockLocation extends Model
{
    protected $connection = 'pgsql_odoo';
    protected $table = 'stock_location';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'location_id',
        'usage',
        'warehouse_id',
    ];

    public function warehouse()
    {
        return $this->belongsTo(StockWarehouse::class, 'warehouse_id');
    }

    public function parent()
    {
        return $this->belongsTo(StockLocation::class, 'location_id');
    }

    public function quants()
    {
        return $this->hasMany(StockQuant::class, 'location_id');
    }
}
