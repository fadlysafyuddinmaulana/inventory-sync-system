<?php

namespace App\Http\Controllers;

use App\Services\OdooService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ProductController extends Controller
{
    private OdooService $odooService;

    public function __construct(OdooService $odooService)
    {
        $this->odooService = $odooService;
    }

    /**
     * Display a paginated list of products from Odoo
     */
    public function index(Request $request)
    {
        try {
            // Get pagination and search parameters
            $page = max(1, (int) $request->get('page', 1));
            $perPage = config('odoo.pagination.per_page', 20);
            $search = trim($request->get('search', ''));

            // Calculate offset
            $offset = ($page - 1) * $perPage;

            // Build filters
            $filters = $this->buildProductFilters($search);

            // Fetch products from Odoo
            $data = $this->odooService->searchProducts(
                $filters,
                $offset,
                $perPage,
                ['name', 'ASC']
            );

            $products = $data['products'] ?? [];
            $total = $data['total'] ?? 0;
            $pages = $data['pages'] ?? 0;
            $currentPage = $data['current_page'] ?? 1;

            // Use qty_available returned from Odoo (fetched in product fields)
            // Also add image_url for each product using base64 data
            foreach ($products as &$product) {
                $product['qty_on_hand'] = data_get($product, 'qty_available', 0);
                // Use base64 image data from Odoo if available, otherwise generate URL
                if (!empty($product['image_256'])) {
                    $product['image_url'] = 'data:image/jpeg;base64,' . $product['image_256'];
                } else {
                    $productId = data_get($product, 'id');
                    $product['image_url'] = $this->odooService->getImageUrl($productId, 256);
                }
            }

            Log::info('Products fetched successfully', [
                'total' => $total,
                'page' => $currentPage,
                'search' => $search,
            ]);

            return view('products.index', [
                'products' => $products,
                'total' => $total,
                'pages' => $pages,
                'currentPage' => $currentPage,
                'perPage' => $perPage,
                'search' => $search,
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching products', ['error' => $e->getMessage()]);

            return view('products.index', [
                'error' => 'Error fetching products: ' . $e->getMessage(),
                'products' => [],
                'total' => 0,
                'pages' => 0,
                'currentPage' => 1,
                'perPage' => config('odoo.pagination.per_page', 20),
                'search' => '',
            ]);
        }
    }

    /**
     * Display a single product details
     */
    public function show($id)
    {
        try {
            $product = $this->odooService->getProduct((int) $id);

            if (!$product) {
                return redirect()->route('products')
                    ->with('error', 'Product not found');
            }

            // Use qty_available returned from Odoo
            $product['qty_on_hand'] = data_get($product, 'qty_available', 0);
            // Add image_url for both thumbnail and full-size using base64 data
            if (!empty($product['image_256'])) {
                $product['image_url'] = 'data:image/jpeg;base64,' . $product['image_256'];
            } else {
                $product['image_url'] = $this->odooService->getImageUrl(data_get($product, 'id'), 256);
            }
            
            if (!empty($product['image_1920'])) {
                $product['image_url_large'] = 'data:image/jpeg;base64,' . $product['image_1920'];
            } else {
                $product['image_url_large'] = $this->odooService->getImageUrl(data_get($product, 'id'), 1920);
            }

            Log::info('Product details viewed', ['id' => $id]);

            return view('products.show', compact('product'));
        } catch (Exception $e) {
            Log::error('Error fetching product details', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('products')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display product image from Odoo
     */
    public function getImage($template_id, $size = 128)
    {
        try {
            // Validate size is in allowed range
            $size = (int) $size;
            $allowedSizes = array_values(config('odoo.image.sizes', [128, 256, 512, 1024, 1920]));

            if (!in_array($size, $allowedSizes)) {
                $size = 128;
            }

            $imageUrl = $this->odooService->getImageUrl((int) $template_id, $size);

            $response = Http::timeout(config('odoo.timeout', 30))->get($imageUrl);

            if ($response->successful()) {
                return response($response->body(), 200)
                    ->header('Content-Type', $response->header('Content-Type') ?? 'image/png')
                    ->header('Cache-Control', config('odoo.image.cache_control', 'max-age=86400'));
            }

            Log::warning('Odoo image fetch failed', [
                'template_id' => $template_id,
                'size' => $size,
                'status' => $response->status(),
                'url' => $imageUrl,
            ]);

            return $this->placeholderResponse();
        } catch (Exception $e) {
            Log::error('Error fetching product image', [
                'template_id' => $template_id,
                'size' => $size,
                'error' => $e->getMessage(),
            ]);

            return $this->placeholderResponse();
        }
    }

    /**
     * Build product search filters
     */
    private function buildProductFilters(string $search): array
    {
        if (empty($search)) {
            return [];
        }

        // Search by name or SKU (default_code)
        return [
            '|',
            ['name', 'ilike', $search],
            ['default_code', 'ilike', $search],
        ];
    }

    // Stock quantities are provided by Odoo as `qty_available` field on product.template

    /**
     * Return placeholder/fallback image response
     */
    private function placeholderResponse()
    {
        $placeholderPath = public_path(trim(config('odoo.image.fallback', 'images/no-image.png'), '/'));

        if (file_exists($placeholderPath)) {
            return response()->file($placeholderPath, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'max-age=3600',
            ]);
        }

        // Return a simple SVG placeholder if no file exists
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="128" height="128" xmlns="http://www.w3.org/2000/svg">
  <rect width="128" height="128" fill="#e0e0e0"/>
  <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#999" font-size="14">No Image</text>
</svg>';

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'max-age=3600');
    }
}