<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;

class ProductProduct extends Model
{
    protected $connection = 'pgsql_odoo';
    protected $table = 'product_product';
    public $timestamps = false;

    protected $fillable = [
        'product_tmpl_id',
        'default_code',
        'barcode',
    ];

    public function template()
    {
        return $this->belongsTo(ProductTemplate::class, 'product_tmpl_id');
    }

    public function stocks()
    {
        return $this->hasMany(StockQuant::class, 'product_id');
    }
}
