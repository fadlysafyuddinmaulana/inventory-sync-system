<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * Get the product image from ir_attachment as data URI
     * Images are stored in ir_attachment table, not in product_template
     */
    public function getImageDataUri($size = '1920')
    {
        $image = DB::connection('pgsql_odoo')->selectOne("
            SELECT encode(db_datas, 'base64') as data
            FROM ir_attachment
            WHERE res_model = 'product.template'
            AND res_id = ?
            AND name = ?
            LIMIT 1
        ", [$this->id, 'image_' . $size]);

        if (!$image || !$image->data) {
            return null;
        }

        return 'data:image/png;base64,' . $image->data;
    }

    /**
     * Check if product has any image in ir_attachment
     */
    public function hasImage()
    {
        $result = DB::connection('pgsql_odoo')->selectOne("
            SELECT COUNT(*) as count
            FROM ir_attachment
            WHERE res_model = 'product.template'
            AND res_id = ?
            AND name IN ('image_128', 'image_256', 'image_1920')
        ", [$this->id]);

        return $result && $result->count > 0;
    }
}
