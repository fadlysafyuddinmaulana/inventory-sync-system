<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    protected $connection = 'pgsql_odoo';
    protected $table = 'sale_order';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'partner_id',
        'amount_total',
        'state',
        'date_order',
    ];

    public function partner()
    {
        return $this->belongsTo(ResPartner::class, 'partner_id');
    }

    public function lines()
    {
        return $this->hasMany(SaleOrderLine::class, 'order_id');
    }
}
