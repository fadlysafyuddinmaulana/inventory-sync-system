<?php

namespace App\Helpers;

class ProductImageHelper
{
    /**
     * Convert base64 image string to data URI
     * Note: Images from Odoo ir_attachment are already base64 encoded
     * 
     * @param string $base64 The base64 encoded image
     * @param string $mimeType The MIME type (default: image/png)
     * @return string|null
     */
    public static function toDataUri($base64, $mimeType = 'image/png')
    {
        if (empty($base64)) {
            return null;
        }

        return 'data:' . $mimeType . ';base64,' . $base64;
    }

    /**
     * Get product image at specific size
     * Images are stored in ir_attachment table with names: image_128, image_256, image_1920
     * The controller attaches these as properties: $product->image_128, etc.
     * 
     * @param object $product Product object from database query (with attached image properties)
     * @param string $size Size of image (128, 256, 512, 1024, 1920)
     * @return string|null Data URI of the image or null if not available
     */
    public static function getProductImage($product, $size = '256')
    {
        $imageField = 'image_' . $size;
        
        if (!isset($product->$imageField) || empty($product->$imageField)) {
            // Fallback to larger image if specified size not available
            if ($size !== '1920') {
                $fallbackSizes = ['1920', '1024', '512', '256', '128'];
                foreach ($fallbackSizes as $fallbackSize) {
                    if ($fallbackSize !== $size) {
                        $result = self::getProductImage($product, $fallbackSize);
                        if ($result) {
                            return $result;
                        }
                    }
                }
            }
            return null;
        }

        return self::toDataUri($product->$imageField);
    }

    /**
     * Get product image thumbnail (128px)
     * 
     * @param object $product Product object from database query
     * @return string|null Data URI or null
     */
    public static function getThumbnail($product)
    {
        return self::getProductImage($product, '128');
    }

    /**
     * Get product image medium (256px)
     * 
     * @param object $product Product object from database query
     * @return string|null Data URI or null
     */
    public static function getMedium($product)
    {
        return self::getProductImage($product, '256');
    }

    /**
     * Get product image large (1920px)
     * 
     * @param object $product Product object from database query
     * @return string|null Data URI or null
     */
    public static function getLarge($product)
    {
        return self::getProductImage($product, '1920');
    }

    /**
     * Check if product has any image
     * 
     * @param object $product Product object from database query
     * @return bool True if has image, false otherwise
     */
    public static function hasImage($product)
    {
        return !empty($product->image_1920) || 
               !empty($product->image_1024) ||
               !empty($product->image_512) ||
               !empty($product->image_256) || 
               !empty($product->image_128);
    }

    /**
     * Get HTML img tag for product image
     * 
     * @param object $product Product object from database query
     * @param string $size Size of image to display (128, 256, 512, 1024, 1920)
     * @param array $attributes Additional HTML attributes
     * @return string HTML img tag
     */
    public static function getImageTag($product, $size = '256', $attributes = [])
    {
        $src = self::getProductImage($product, $size);
        
        if (!$src) {
            return self::getPlaceholderTag($product, $attributes);
        }

        $attrs = array_merge([
            'alt' => $product->name ?? 'Product Image',
            'class' => 'product-image',
            'style' => 'max-width: 100%; height: auto;'
        ], $attributes);

        $attrString = '';
        foreach ($attrs as $key => $value) {
            $attrString .= " {$key}=\"" . htmlspecialchars($value) . "\"";
        }

        return "<img src=\"{$src}\"{$attrString}>";
    }

    /**
     * Get placeholder HTML when no image available
     * 
     * @param object $product Product object from database query
     * @param array $attributes Additional HTML attributes
     * @return string HTML div placeholder
     */
    public static function getPlaceholderTag($product, $attributes = [])
    {
        $attrs = array_merge([
            'class' => 'product-placeholder',
            'style' => 'width: 100%; height: auto; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 4px;'
        ], $attributes);

        $attrString = '';
        foreach ($attrs as $key => $value) {
            $attrString .= " {$key}=\"" . htmlspecialchars($value) . "\"";
        }

        return "<div{$attrString}><i class=\"fas fa-image\" style=\"color: #999; font-size: 2rem;\"></i></div>";
    }
}
