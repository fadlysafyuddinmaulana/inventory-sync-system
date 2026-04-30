<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;

class SaleOrderLine extends Model
{
    protected $connection = 'pgsql_odoo';
    protected $table = 'sale_order_line';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_uom_qty',
        'price_unit',
        'price_subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(SaleOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductProduct::class, 'product_id');
    }
}
