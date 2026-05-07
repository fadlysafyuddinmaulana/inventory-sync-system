# Odoo 19 + Laravel Integration - Complete Implementation Guide

## 📋 Overview

This implementation provides a production-ready integration between **Odoo 19** and **Laravel 12**, enabling:

- ✅ Direct API communication using JSON-RPC protocol
- ✅ Product data synchronization with pagination & search
- ✅ Image proxying from Odoo with caching
- ✅ Stock quantity aggregation from PostgreSQL
- ✅ Comprehensive error handling & logging
- ✅ Request caching to minimize API calls
- ✅ Clean service layer architecture
- ✅ AdminLTE UI integration

---

## 🔧 Installation & Configuration Steps

### Step 1: Get Your Odoo API Key

1. Log in to Odoo 19 as admin
2. Go to **Settings** → **Users & Companies** → **Users**
3. Click on your user
4. Scroll down to **Access Tokens** section
5. Click **Generate Token** or create one manually
6. Copy the API key

### Step 2: Update Environment Configuration

Update your `.env` file with your Odoo credentials:

```bash
# .env
ODOO_URL=http://your-odoo-instance.com:8069
ODOO_DATABASE=odoo_db_name
ODOO_USERNAME=admin
ODOO_API_KEY=your_generated_api_key_here
ODOO_TIMEOUT=30

# Caching (reduce API calls)
ODOO_CACHE_ENABLED=true
ODOO_CACHE_TTL=3600    # 1 hour

# Pagination
ODOO_PRODUCTS_PER_PAGE=20
ODOO_PRODUCTS_MAX_LIMIT=500
```

### Step 3: Clear Configuration Cache

```bash
php artisan config:cache
```

### Step 4: Test the Connection

Create a quick test command to verify Odoo connectivity:

```bash
php artisan tinker
```

Then in the Tinker shell:

```php
$odoo = app(App\Services\OdooService::class);
$odoo->testConnection();  // Should return true
```

---

## 📁 File Structure

```
app/
├── Services/
│   └── OdooService.php           # Core Odoo API integration
└── Http/Controllers/
    └── ProductController.php      # Product CRUD operations

config/
└── odoo.php                       # Centralized configuration

resources/views/products/
├── index.blade.php                # Product listing with search
└── show.blade.php                 # Product detail view

public/images/
└── no-image.png                   # Fallback image
```

---

## 🎯 Key Features

### 1. **OdooService Class** (`app/Services/OdooService.php`)

Core service providing:

```php
// Search products with filters
$products = $odooService->searchProducts(
    filters: [['name', 'ilike', 'Product Name']],
    offset: 0,
    limit: 20,
    order: ['name', 'ASC']
);

// Get single product
$product = $odooService->getProduct($productId);

// Full-text search by name/SKU
$results = $odooService->searchByQuery('search term', limit: 50);

// Get product image URL
$imageUrl = $odooService->getImageUrl($templateId, size: 1920);

// Test connection
$connected = $odooService->testConnection();
```

**Features:**

- JSON-RPC authentication caching
- Request result caching (configurable TTL)
- Automatic field selection
- Error logging on every call
- Retry-safe implementation

### 2. **ProductController** (`app/Http/Controllers/ProductController.php`)

**index()** - Display paginated product list:

- Query parameter search support
- Server-side pagination
- Stock data aggregation from PostgreSQL
- Error handling with user-friendly messages

**show()** - Display product details:

- Fetch from Odoo API
- Merge with PostgreSQL stock data
- Image display from Odoo

**getImage()** - Proxy product images:

- Direct Odoo image URL proxying
- HTTP caching headers
- Fallback to placeholder image
- Size validation (128, 256, 512, 1024, 1920)

### 3. **Configuration** (`config/odoo.php`)

Centralized settings:

```php
[
    'url' => env('ODOO_URL'),
    'database' => env('ODOO_DATABASE'),
    'username' => env('ODOO_USERNAME'),
    'password' => env('ODOO_API_KEY'),  // Note: Password field uses API key
    'timeout' => env('ODOO_TIMEOUT', 30),
    'cache' => [
        'enabled' => true,
        'ttl' => 3600,
    ],
    'pagination' => [
        'per_page' => 20,
        'max_limit' => 500,
    ],
    'image' => [
        'fallback' => '/images/no-image.png',
        'sizes' => [128, 256, 512, 1024, 1920],
        'cache_control' => 'max-age=86400, public',
    ],
]
```

---

## 🔌 API Response Format

### Product Object (from Odoo)

```json
{
    "id": 1,
    "name": "Product Name",
    "default_code": "SKU001",
    "list_price": 100000.0,
    "image_1920": "/9j/4AAQSkZJ...", // Base64 encoded
    "image_128": "/9j/4AAQSkZJ...",
    "image_256": "/9j/4AAQSkZJ...",
    "image_512": "/9j/4AAQSkZJ...",
    "description": "Product description",
    "description_sale": "Sales description",
    "category_id": [1, "Category Name"],
    "type": "product",
    "is_product_variant": false,
    "create_date": "2026-01-01 10:30:00",
    "write_date": "2026-05-03 15:45:00"
}
```

### Search Response

```php
[
    'products' => [...],      // Array of product objects
    'total' => 150,           // Total matching products
    'offset' => 0,
    'limit' => 20,
    'pages' => 8,             // Total pages
    'current_page' => 1,
]
```

---

## 📊 Blade View Markup

### Index View with Search

```blade
<!-- Search Form -->
<form method="GET" action="{{ route('products') }}">
    <input type="text" name="search"
        placeholder="Search by name or SKU..."
        value="{{ $search }}">
    <button type="submit">Search</button>
</form>

<!-- Product Table -->
@foreach ($products as $product)
    <tr>
        <td>
            <img src="{{ route('products.image', [$product['id'], 128]) }}"
                onerror="this.src='{{ asset(config('odoo.image.fallback')) }}';"
                alt="{{ $product['name'] }}">
        </td>
        <td>{{ $product['name'] }}</td>
        <td>{{ $product['default_code'] ?? 'N/A' }}</td>
        <td>Rp {{ number_format($product['list_price'], 0) }}</td>
        <td>{{ $product['qty_on_hand'] ?? 0 }}</td>
        <td>
            <a href="{{ route('products.show', $product['id']) }}">
                View Details
            </a>
        </td>
    </tr>
@endforeach

<!-- Pagination -->
@if ($pages > 1)
    {{ Paginator::make($products, $total, 'products', $currentPage, $perPage) }}
@endif
```

### Show View with Image

```blade
<!-- Full Product Image -->
<img src="{{ route('products.image', [$product['id'], 1920]) }}"
    alt="{{ $product['name'] }}"
    onerror="this.src='{{ asset(config('odoo.image.fallback')) }}';"
    class="img-fluid">

<!-- Product Details -->
<dl>
    <dt>Product Name:</dt>
    <dd>{{ $product['name'] }}</dd>

    <dt>SKU:</dt>
    <dd>{{ $product['default_code'] }}</dd>

    <dt>Price:</dt>
    <dd>Rp {{ number_format($product['list_price'], 0) }}</dd>

    <dt>Quantity:</dt>
    <dd>{{ $product['qty_on_hand'] }} units</dd>
</dl>
```

---

## 🔐 Authentication & Security

### Odoo API Authentication Flow

1. **Initialize** → Create `OdooService` instance
2. **Authenticate** → JSON-RPC call to `/jsonrpc` endpoint
3. **Get UID** → Receive user ID from Odoo
4. **Cache UID** → Store in Laravel cache (1 hour default)
5. **Make Calls** → Use UID for subsequent API calls

### Security Best Practices

✅ **API Key Management:**

- Store API key in `.env` file (never in code)
- Use strong, unique API keys
- Regenerate keys periodically
- Restrict to necessary permissions in Odoo

✅ **Timeout Protection:**

- Default 30-second timeout
- Prevents infinite hangs
- Configurable per environment

✅ **Error Handling:**

- Never expose API keys in error messages
- Log errors to secure channel
- Return generic user-friendly messages

✅ **Caching Strategy:**

- Reduces API load on Odoo
- 1-hour default TTL
- Manual invalidation supported
- Configurable per environment

---

## 🚀 Usage Examples

### Get Products with Search

```php
// ProductController@index
$search = request('search', '');
$page = request('page', 1);
$perPage = config('odoo.pagination.per_page', 20);

$filters = empty($search) ? [] : [
    '|',
    ['name', 'ilike', $search],
    ['default_code', 'ilike', $search],
];

$data = $this->odooService->searchProducts(
    $filters,
    ($page - 1) * $perPage,
    $perPage
);

return view('products.index', $data);
```

### Display Product with Stock

```php
// ProductController@show
$product = $this->odooService->getProduct($id);
$stock = $this->getStockData([$product['id']]);
$product['qty_on_hand'] = $stock[$product['id']] ?? 0;

return view('products.show', compact('product'));
```

### Cache Invalidation

```php
// When product is updated in Odoo:
$this->odooService->invalidateCache($productId);

// Or clear all product caches:
$this->odooService->invalidateCache();
```

---

## 📝 Logging & Debugging

### Log Levels

All operations are logged to `storage/logs/`:

```
[2026-05-03 15:30:45] local.DEBUG: Odoo products fetched successfully
[2026-05-03 15:31:10] local.INFO: Products fetched successfully
[2026-05-03 15:32:05] local.WARNING: Odoo API image fetch failed
[2026-05-03 15:33:20] local.ERROR: Odoo authentication failed: ...
```

### Debug Mode

Enable detailed logging:

```bash
# .env
APP_DEBUG=true
ODOO_LOG_CHANNEL=stack
LOG_LEVEL=debug
```

### Tinker Testing

```bash
php artisan tinker

# Test authentication
$odoo = app(App\Services\OdooService::class);
$odoo->testConnection();  // true/false

# Search products
$results = $odoo->searchProducts([], 0, 10);
dump($results);

# Get single product
$product = $odoo->getProduct(1);
dump($product);

# Get image URL
$url = $odoo->getImageUrl(1, 1920);
echo $url;
```

---

## ⚡ Performance Optimization

### Caching Strategy

```php
// Cache TTLs (seconds)
ODOO_CACHE_TTL=3600      // Products: 1 hour
Session Cache: 120 min    // User session
HTTP Cache: 86400 sec     // Images: 24 hours
```

### Database Optimization

For PostgreSQL (Odoo) connection:

- Use indexes on `product_template(name, default_code)`
- Optimize `stock_quant` joins with proper indexes
- Consider materialized views for frequent queries

### Query Optimization

```php
// Good - limits results
$data = $odoo->searchProducts([], 0, 20);

// Better - with filters
$data = $odoo->searchProducts([['type', '=', 'product']], 0, 20);

// Cache-aware
if ($cached = Cache::get('products-page-1')) {
    return $cached;
}
```

---

## 🧪 Testing

### Test Odoo Connection

```php
// Command
php artisan tinker

// Test
$service = app(App\Services\OdooService::class);
$service->testConnection();  // true if successful
```

### Test Product Fetch

```bash
php artisan tinker

# Search all
$results = app(App\Services\OdooService::class)
    ->searchProducts([], 0, 5);
echo json_encode($results, JSON_PRETTY_PRINT);

# Search specific
$results = app(App\Services\OdooService::class)
    ->searchByQuery('product name', 10);
echo json_encode($results, JSON_PRETTY_PRINT);
```

### Test Image Proxying

```
GET /products/1/image/128
GET /products/1/image/512
GET /products/1/image/1920
```

---

## 🐛 Troubleshooting

### Issue: "Authentication failed"

**Solution:**

1. Verify ODOO_URL is correct and accessible
2. Check ODOO_DATABASE name matches
3. Confirm API key is valid (regenerate if needed)
4. Check Odoo user has necessary permissions

```bash
php artisan config:clear
php artisan cache:clear
```

### Issue: "Connection timeout"

**Solution:**

1. Increase `ODOO_TIMEOUT` value
2. Check network connectivity to Odoo
3. Verify Odoo server is running
4. Check firewall rules

```env
ODOO_TIMEOUT=60  # Increase from 30 to 60 seconds
```

### Issue: "No images displaying"

**Solution:**

1. Check fallback image exists: `public/images/no-image.png`
2. Verify Odoo `/web/image/` endpoint is accessible
3. Check product has images in Odoo
4. Verify image caching is working

### Issue: "Slow product list loading"

**Solution:**

1. Enable caching: `ODOO_CACHE_ENABLED=true`
2. Reduce `ODOO_CACHE_TTL` if fresh data needed
3. Limit `ODOO_PRODUCTS_PER_PAGE` to 15-20
4. Use search filters to narrow results

---

## 📦 Dependencies

The implementation uses these Laravel features:

- `Illuminate\Support\Facades\Http` - HTTP requests
- `Illuminate\Support\Facades\Cache` - Caching
- `Illuminate\Support\Facades\Log` - Logging
- `Illuminate\Support\Facades\DB` - Database

No additional Composer packages required!

---

## 🔄 Maintenance

### Regular Tasks

**Weekly:**

- Monitor logs for API errors
- Check cache hit rates
- Verify image syncing

**Monthly:**

- Clear old cache entries
- Review and optimize slow queries
- Update Odoo API if needed

**Quarterly:**

- Regenerate API keys
- Update fallback images
- Review caching strategy

---

## 📚 Next Steps

1. **Implement webhooks** - Auto-sync when Odoo products change
2. **Add stock synchronization** - Periodic stock updates
3. **Build product import** - Bulk import functionality
4. **Add favorites** - Let users save products
5. **Create API endpoints** - For external integrations
6. **Setup real-time notifications** - WebSocket product updates

---

## 📞 Support

For issues or questions:

1. Check logs: `storage/logs/laravel.log`
2. Test connection: `php artisan tinker`
3. Verify configuration: `php artisan config:show odoo`
4. Check Odoo API documentation: https://www.odoo.com/documentation/19.0/developer/

---

**Implementation Complete!** 🎉

Your Laravel application is now fully integrated with Odoo 19 for product management, image serving, and inventory synchronization.
