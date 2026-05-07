<?php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @deprecated Use OdooService::execute() instead of this Eloquent model.
 * This model connects to pgsql_odoo (Odoo PostgreSQL), but new code should use OdooService for JSON-RPC API calls.
 */
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
        $service = resolve(\App\Services\OdooImageService::class);
        return $service->getImageDataUri($this->id, 'image_' . $size);
    }

    /**
     * Check if product has any image in ir_attachment
     */
    public function hasImage()
    {
        $service = resolve(\App\Services\OdooImageService::class);
        $img = $service->getProductImage($this->id, 'image_128');
        return !empty($img);
    }

    /**
     * Get product image URL via Odoo web/image endpoint
     * 
     * @param int $size Image size (128, 256, 512, 1920)
     * @return string Image URL
     */
    public function getImageUrl($size = 256)
    {
        $service = resolve(\App\Services\OdooService::class);
        return $service->getImageUrl($this->id, $size);
    }

    /**
     * Get thumbnail image URL
     */
    public function getThumbnailUrl()
    {
        return $this->getImageUrl(128);
    }

    /**
     * Get medium image URL
     */
    public function getMediumUrl()
    {
        return $this->getImageUrl(256);
    }

    /**
     * Get large image URL
     */
    public function getLargeUrl()
    {
        return $this->getImageUrl(1920);
    }
}
