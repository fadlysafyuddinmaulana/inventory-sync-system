<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;

class StockPicking extends Model
{
    protected $connection = 'pgsql_odoo';
    protected $table = 'stock_picking';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'picking_type_id',
        'state',
        'scheduled_date',
        'create_date',
    ];

    public function moves()
    {
        return $this->hasMany(StockMove::class, 'picking_id');
    }
}
