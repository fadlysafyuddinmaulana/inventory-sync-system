<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;

class StockQuant extends Model
{
    protected $connection = 'pgsql_odoo';
    protected $table = 'stock_quant';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'location_id',
        'quantity',
        'reserved_quantity',
    ];

    public function product()
    {
        return $this->belongsTo(ProductProduct::class, 'product_id');
    }

    public function location()
    {
        return $this->belongsTo(StockLocation::class, 'location_id');
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->quantity - $this->reserved_quantity;
    }
}
