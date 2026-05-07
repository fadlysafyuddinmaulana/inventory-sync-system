<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OdooService
{
    private string $odooUrl;
    private string $database;
    private string $username;
    private string $password;
    private int $timeout;
    private array $config;
    private ?int $uid = null;

    public function __construct()
    {
        $this->config = config('odoo');
        $this->odooUrl = $this->config['url'];
        $this->database = $this->config['database'];
        $this->username = $this->config['username'];
        $this->password = $this->config['password'];
        $this->timeout = $this->config['timeout'];
    }

    /**
     * Authenticate with Odoo and get user ID
     *
     * @return int
     * @throws Exception
     */
    private function authenticate(): int
    {
        if ($this->uid !== null) {
            return $this->uid;
        }

        $cacheKey = 'odoo:auth:uid:' . md5($this->database . $this->username);

        if ($this->config['cache']['enabled']) {
            $cachedUid = Cache::get($cacheKey);
            if ($cachedUid) {
                $this->uid = $cachedUid;
                return $this->uid;
            }
        }

        try {
            $response = Http::timeout($this->timeout)
                ->post($this->odooUrl . '/jsonrpc', [
                    'jsonrpc' => '2.0',
                    'method' => 'call',
                    'params' => [
                        'service' => 'common',
                        'method' => 'authenticate',
                        'args' => [$this->database, $this->username, $this->password, []],
                    ],
                    'id' => 1,
                ]);

            if ($response->failed()) {
                throw new Exception('Authentication failed: ' . $response->body());
            }

            $data = $response->json();

            if (isset($data['error'])) {
                Log::error('Odoo authentication error', $data['error']);
                throw new Exception('Odoo authentication failed: ' . json_encode($data['error']));
            }

            $this->uid = $data['result'];

            if ($this->config['cache']['enabled']) {
                Cache::put($cacheKey, $this->uid, $this->config['cache']['ttl']);
            }

            return $this->uid;
        } catch (Exception $e) {
            Log::error('Odoo authentication exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Search for products from Odoo
     * Filters automatically exclude service products (only inventory items)
     *
     * @param array $filters
     * @param int $offset
     * @param int $limit
     * @param array $order
     * @return array
     */
    public function searchProducts(
        array $filters = [],
        int $offset = 0,
        int $limit = 20,
        array $order = []
    ): array {
        try {
            // Ensure limit doesn't exceed max
            $limit = min($limit, $this->config['pagination']['max_limit']);

            // Build base filters - exclude service type products (only inventory items)
            $baseFilters = [
                '&',
                ['type', '!=', 'service'],  // Exclude services (deposit, etc)
                ['sale_ok', '=', true],      // Only saleable products
            ];

            // Merge with user filters
            if (!empty($filters)) {
                // If user provided filters, add them
                $filters = array_merge($baseFilters, $filters);
            } else {
                $filters = $baseFilters;
            }

            // Build cache key
            $cacheKey = 'odoo:products:search:' . md5(json_encode([
                'filters' => $filters,
                'offset' => $offset,
                'limit' => $limit,
                'order' => $order,
            ]));

            // Check cache
            if ($this->config['cache']['enabled']) {
                $cached = Cache::get($cacheKey);
                if ($cached) {
                    Log::debug('Odoo products from cache', ['offset' => $offset, 'limit' => $limit]);
                    return $cached;
                }
            }

            // First, search for all product IDs matching the filter (for count)
            $allIds = $this->call(
                'product.template',
                'search',
                [$filters]  // domain
            );
            $totalCount = count($allIds);

            // Search products with pagination
            $orderBy = !empty($order) ? [implode(' ', $order)] : [];
            $response = $this->call(
                'product.template',
                'search_read',
                [$filters, $this->getProductFields(), $offset, $limit]
            );

            $result = [
                'products' => $response,
                'total' => $totalCount,
                'offset' => $offset,
                'limit' => $limit,
                'pages' => ceil($totalCount / $limit),
                'current_page' => floor($offset / $limit) + 1,
            ];

            // Cache results
            if ($this->config['cache']['enabled']) {
                Cache::put($cacheKey, $result, $this->config['cache']['ttl']);
            }

            Log::debug('Odoo products fetched', [
                'count' => count($response),
                'total' => $totalCount,
                'offset' => $offset,
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error('Error searching Odoo products', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get a single product by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getProduct(int $id): ?array
    {
        try {
            $cacheKey = 'odoo:product:' . $id;

            if ($this->config['cache']['enabled']) {
                $cached = Cache::get($cacheKey);
                if ($cached) {
                    return $cached;
                }
            }

            $response = $this->call('product.template', 'read', [[$id], $this->getProductFields()]);

            if (!$response || !isset($response[0])) {
                return null;
            }

            $product = $response[0];

            if ($this->config['cache']['enabled']) {
                Cache::put($cacheKey, $product, $this->config['cache']['ttl']);
            }

            return $product;
        } catch (Exception $e) {
            Log::error('Error fetching Odoo product', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Search for products by name or SKU
     *
     * @param string $query
     * @param int $limit
     * @return array
     */
    public function searchByQuery(string $query, int $limit = 50): array
    {
        try {
            $filters = [
                '|',
                ['name', 'ilike', $query],
                ['default_code', 'ilike', $query],
            ];

            return $this->searchProducts($filters, 0, $limit);
        } catch (Exception $e) {
            Log::error('Error searching Odoo products by query', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);
            return ['products' => [], 'total' => 0];
        }
    }

    /**
     * Get product image URL
     *
     * @param int $templateId
     * @param int $size
     * @return string
     */
    public function getImageUrl(int $templateId, int $size = 1920): string
    {
        return sprintf(
            '%s/web/image/product.template/%d/image_%d',
            rtrim($this->odooUrl, '/'),
            $templateId,
            $size
        );
    }

    /**
     * Invalidate product cache
     *
     * @param int|null $id
     * @return void
     */
    public function invalidateCache(?int $id = null): void
    {
        if ($id) {
            Cache::forget('odoo:product:' . $id);
        } else {
            // Invalidate all product-related caches
            Cache::flush();
            Log::info('Odoo product cache invalidated');
        }
    }

    /**
     * Get fields to retrieve for product.template
     *
     * @return array
     */
    private function getProductFields(): array
    {
        return [
            'id',
            'name',
            'default_code',
            'list_price',
            'qty_available',
            'virtual_available',
            'incoming_qty',
            'outgoing_qty',
            'image_1920',
            'image_128',
            'image_256',
            'image_512',
            'description',
            'description_sale',
            'categ_id',  // Odoo 19 uses categ_id not category_id
            'type',
            'is_product_variant',
            'create_date',
            'write_date',
        ];
    }

    /**
     * Make an Odoo JSON-RPC call
     *
     * @param string $model
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws Exception
     */
    private function call(string $model, string $method, array $args = []): mixed
    {
        try {
            $uid = $this->authenticate();

            // For Odoo 19, execute_kw requires: [args_list, kwargs_dict]
            $response = Http::timeout($this->timeout)
                ->post($this->odooUrl . '/jsonrpc', [
                    'jsonrpc' => '2.0',
                    'method' => 'call',
                    'params' => [
                        'service' => 'object',
                        'method' => 'execute_kw',
                        'args' => [$this->database, $uid, $this->password, $model, $method, $args, (object)[]],
                    ],
                    'id' => time(),
                ]);

            if ($response->failed()) {
                throw new Exception('Odoo API call failed: ' . $response->body());
            }

            $data = $response->json();

            if (isset($data['error'])) {
                Log::error('Odoo API error', [
                    'model' => $model,
                    'method' => $method,
                    'error' => $data['error'],
                ]);
                throw new Exception('Odoo API error: ' . json_encode($data['error']));
            }

            return $data['result'] ?? null;
        } catch (Exception $e) {
            Log::error('Odoo API call exception', [
                'model' => $model,
                'method' => $method,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Public wrapper to execute Odoo JSON-RPC calls from other services/controllers
     *
     * @param string $model
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function execute(string $model, string $method, array $args = []): mixed
    {
        return $this->call($model, $method, $args);
    }

    /**
     * Test the connection to Odoo
     *
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $this->authenticate();
            return true;
        } catch (Exception $e) {
            Log::error('Odoo connection test failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
