<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;

class ProductTemplate extends Model
{
    protected $connection = 'pgsql_odoo';
    protected $table = 'product_template';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'default_code',
        'list_price',
        'categ_id',
    ];

    public function products()
    {
        return $this->hasMany(ProductProduct::class, 'product_tmpl_id');
    }
}
