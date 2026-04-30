<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;

class ResPartner extends Model
{
    protected $connection = 'pgsql_odoo';
    protected $table = 'res_partner';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'street',
        'city',
        'country_id',
        'customer_rank',
    ];

    public function orders()
    {
        return $this->hasMany(SaleOrder::class, 'partner_id');
    }
}
