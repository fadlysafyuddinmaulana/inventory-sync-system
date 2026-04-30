<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;

class StockMove extends Model
{
    protected $connection = 'pgsql_odoo';
    protected $table = 'stock_move';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'location_id',
        'location_dest_id',
        'quantity_done',
        'state',
        'create_date',
        'picking_id',
        'move_type',
    ];

    public function product()
    {
        return $this->belongsTo(ProductProduct::class, 'product_id');
    }

    public function locationFrom()
    {
        return $this->belongsTo(StockLocation::class, 'location_id');
    }

    public function locationTo()
    {
        return $this->belongsTo(StockLocation::class, 'location_dest_id');
    }

    public function picking()
    {
        return $this->belongsTo(StockPicking::class, 'picking_id');
    }
}
