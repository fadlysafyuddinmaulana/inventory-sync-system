<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class OdooImageService
{
    private $odooUrl;
    private $odooUsername;
    private $odooPassword;
    private $odooDatabase;
    private $sessionId;

    public function __construct()
    {
        $this->odooUrl = env('ODOO_URL', 'http://localhost:8069');
        $this->odooUsername = env('ODOO_USERNAME', 'openpg');
        $this->odooPassword = env('ODOO_PASSWORD', 'openpgpwd');
        $this->odooDatabase = env('ODOO_DATABASE', 'odoo_inventory_db');
    }

    /**
     * Get image from Odoo REST API
     * URL Format: /web/image/ir.attachment/{id}
     * 
     * @param int $attachmentId Attachment ID from ir_attachment table
     * @param string $size Image size (optional, e.g., 256)
     * @return string|null Base64 encoded image or null if failed
     */
    public function getImageFromOdoo($attachmentId, $size = null)
    {
        $cacheKey = "odoo_image_{$attachmentId}_{$size}";
        
        // Check cache first (1 hour)
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        try {
            // Build URL: /web/image/ir.attachment/{id}
            $url = "{$this->odooUrl}/web/image/ir.attachment/{$attachmentId}";
            
            // Add size parameter if specified
            if ($size) {
                $url .= "?max_width={$size}&max_height={$size}";
            }

            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                // Convert binary to base64
                $base64Image = base64_encode($response->body());
                
                // Cache untuk 1 jam
                Cache::put($cacheKey, $base64Image, 3600);
                
                return $base64Image;
            }
        } catch (\Exception $e) {
            \Log::error("Error fetching image from Odoo: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Get product image by template ID
     * Query ir_attachment untuk find image ID, kemudian fetch dari API
     * 
     * @param int $templateId Product template ID
     * @param string $imageSize image_128, image_256, image_1024, image_1920
     * @return string|null Base64 encoded image or null
     */
    public function getProductImage($templateId, $imageSize = 'image_256')
    {
        try {
            // Use Odoo web image endpoint for product.template fields
            // Example: /web/image/product.template/{id}/image_256
            $cacheKey = "odoo_product_image_{$templateId}_{$imageSize}";

            $cached = Cache::get($cacheKey);
            if ($cached) {
                return $cached;
            }

            $url = rtrim($this->odooUrl, '/') . "/web/image/product.template/{$templateId}/{$imageSize}";

            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                $base64Image = base64_encode($response->body());
                Cache::put($cacheKey, $base64Image, 3600);
                return $base64Image;
            }

            return null;
        } catch (\Exception $e) {
            \Log::error("Error getting product image: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get image as data URI
     * 
     * @param int $templateId Product template ID
     * @param string $imageSize Image size name
     * @return string|null Data URI or null
     */
    public function getImageDataUri($templateId, $imageSize = 'image_256')
    {
        $base64 = $this->getProductImage($templateId, $imageSize);
        
        if (!$base64) {
            return null;
        }

        return 'data:image/png;base64,' . $base64;
    }

    /**
     * Download image and store to Laravel storage
     * Gunakan ini jika ingin persistent cache di storage
     * 
     * @param int $templateId Product template ID
     * @param string $imageSize Image size name
     * @param string $disk Storage disk (default: public)
     * @return string|null Relative path to stored image or null
     */
    public function downloadAndStore($templateId, $imageSize = 'image_256', $disk = 'public')
    {
        $base64 = $this->getProductImage($templateId, $imageSize);
        
        if (!$base64) {
            return null;
        }

        try {
            $binary = base64_decode($base64);
            $filename = "product_{$templateId}_{$imageSize}.png";
            $path = "products/{$filename}";

            Storage::disk($disk)->put($path, $binary);

            return $path;
        } catch (\Exception $e) {
            \Log::error("Error storing image: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all product images (all sizes)
     * 
     * @param int $templateId Product template ID
     * @return array Array with keys: image_128, image_256, image_1024, image_1920
     */
    public function getAllProductImages($templateId)
    {
        $sizes = ['image_128', 'image_256', 'image_512', 'image_1024', 'image_1920'];
        $images = [];

        foreach ($sizes as $size) {
            $base64 = $this->getProductImage($templateId, $size);
            if ($base64) {
                $images[$size] = $base64;
            }
        }

        return $images;
    }

    /**
     * Get product thumbnail
     * 
     * @param int $templateId Product template ID
     * @return string|null Data URI or null
     */
    public function getThumbnail($templateId)
    {
        return $this->getImageDataUri($templateId, 'image_128');
    }

    /**
     * Get product medium image
     * 
     * @param int $templateId Product template ID
     * @return string|null Data URI or null
     */
    public function getMedium($templateId)
    {
        return $this->getImageDataUri($templateId, 'image_256');
    }

    /**
     * Get product large image
     * 
     * @param int $templateId Product template ID
     * @return string|null Data URI or null
     */
    public function getLarge($templateId)
    {
        return $this->getImageDataUri($templateId, 'image_1920');
    }
}
