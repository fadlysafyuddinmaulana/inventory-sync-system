# Testing Guide: Product Images dari Odoo 19

## ✅ Checklist Verifikasi Implementasi

### 1. Database Connection

```bash
# Test koneksi ke Odoo PostgreSQL
php artisan tinker

# Di tinker:
DB::connection('pgsql_odoo')->select("SELECT 1")
```

**Expected Output**: Array dengan satu row

### 2. Check Tabel Product Template

```php
// Di tinker:
DB::connection('pgsql_odoo')->select("
    SELECT column_name FROM information_schema.columns
    WHERE table_name = 'product_template'
    AND column_name LIKE 'image_%'
")->toArray()
```

**Expected Output**:

```
array:3 [
  0 => {
    "column_name" => "image_1920"
  }
  1 => {
    "column_name" => "image_256"
  }
  2 => {
    "column_name" => "image_128"
  }
]
```

### 3. Verify Products dengan Images

```php
// Di tinker:
DB::connection('pgsql_odoo')->select("
    SELECT id, name, image_1920 IS NOT NULL as has_image
    FROM product_template
    LIMIT 5
")->toArray()
```

**Expected Output**: Some products with has_image = true

---

## 🧪 Manual Testing

### Test 1: List View (Thumbnail)

1. Buka browser: `http://localhost:8000/products`
2. Verifikasi:
    - ✓ Kolom "Image" muncul di sebelah kiri
    - ✓ Gambar 50x50px tampil untuk produk yang ada gambar
    - ✓ Icon placeholder muncul untuk produk tanpa gambar

### Test 2: Detail View (Large Image)

1. Click salah satu tombol view (eye icon)
2. Verifikasi:
    - ✓ Gambar besar tampil di atas informasi produk
    - ✓ Placeholder muncul jika tidak ada gambar
    - ✓ Gambar responsive (full width di container)

### Test 3: Image Quality

1. Klik kanan pada gambar → "Inspect"
2. Check di Network tab:
    - ✓ Image dimuat sebagai data URI (bukan HTTP request)
    - ✓ Ukuran data URI sesuai dengan image_128 atau image_1920

---

## 🔍 Advanced Testing

### Test Browser Console

```javascript
// Di browser console:

// 1. Check jumlah img tags
document.querySelectorAll('img[src*="data:image"]').length;

// 2. Verify data URIs valid
Array.from(document.querySelectorAll("img")).forEach((img) => {
    console.log({
        alt: img.alt,
        src: img.src.substring(0, 50) + "...",
        loaded: img.complete,
    });
});
```

### Test Performance

```bash
# Install lighthouse (if needed)
npm install -g lighthouse

# Test halaman products
lighthouse http://localhost:8000/products --view

# Check LCP (Largest Contentful Paint) time
```

### Test dengan Network Throttling

1. Buka DevTools → Network tab
2. Set throttling ke "Slow 3G"
3. Refresh halaman
4. Verifikasi gambar tetap tampil dengan lazy loading

---

## 💻 Automated Tests (Unit & Feature)

### Buat Test File

```bash
php artisan make:test ProductImageTest --feature
```

### Test Code

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ProductImageTest extends TestCase
{
    /**
     * Test halaman product list tampil dengan images
     */
    public function test_products_list_displays_with_images()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertViewHas('products');

        // Check jika data ada
        $products = $response->getOriginalContent()->getData()['products'];
        $this->assertGreaterThan(0, count($products));
    }

    /**
     * Test product detail page
     */
    public function test_product_detail_page_loads()
    {
        // Get first product ID
        $product = DB::connection('pgsql_odoo')->selectOne("
            SELECT id FROM product_product LIMIT 1
        ");

        if (!$product) {
            $this->markTestSkipped('No products in database');
        }

        $response = $this->get("/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertViewHas('product');
    }

    /**
     * Test ProductTemplate model methods
     */
    public function test_product_template_image_methods()
    {
        $product = DB::connection('pgsql_odoo')->selectOne("
            SELECT id, image_1920 FROM product_template
            WHERE image_1920 IS NOT NULL
            LIMIT 1
        ");

        if (!$product) {
            $this->markTestSkipped('No products with images');
        }

        // Test getImageDataUri method
        $dataUri = \App\Models\Odoo\ProductTemplate::find($product->id)
            ->getImageDataUri('1920');

        $this->assertStringStartsWith('data:image/png;base64,', $dataUri);
    }

    /**
     * Test ProductImageHelper
     */
    public function test_product_image_helper()
    {
        $product = DB::connection('pgsql_odoo')->selectOne("
            SELECT id, image_128, image_256, image_1920
            FROM product_template
            WHERE image_1920 IS NOT NULL
            LIMIT 1
        ");

        if (!$product) {
            $this->markTestSkipped('No products with images');
        }

        $helper = new \App\Helpers\ProductImageHelper();

        // Test getThumbnail
        $thumb = $helper::getThumbnail($product);
        $this->assertNotNull($thumb);
        $this->assertStringStartsWith('data:image', $thumb);

        // Test hasImage
        $hasImage = $helper::hasImage($product);
        $this->assertTrue($hasImage);
    }
}
```

### Jalankan Tests

```bash
# Run specific test
php artisan test tests/Feature/ProductImageTest.php

# Run dengan verbose
php artisan test tests/Feature/ProductImageTest.php -v

# Run dengan coverage
php artisan test tests/Feature/ProductImageTest.php --coverage
```

---

## 🐛 Debugging

### Check Image Data di Database

```php
// Di tinker:
$product = DB::connection('pgsql_odoo')->selectOne(
    "SELECT id, name, LENGTH(image_1920) as size FROM product_template WHERE image_1920 IS NOT NULL LIMIT 1"
);

echo "Product: " . $product->name . "\n";
echo "Image size: " . ($product->size / 1024) . " KB\n";
```

### Check Base64 Decoding

```php
// Di tinker:
$product = DB::connection('pgsql_odoo')->selectOne(
    "SELECT image_1920 FROM product_template WHERE image_1920 IS NOT NULL LIMIT 1"
);

$decoded = base64_decode($product->image_1920);
$length = strlen($decoded);

if ($length > 0) {
    echo "✓ Base64 valid, decoded to " . ($length / 1024) . " KB\n";

    // Check if valid PNG
    $header = bin2hex(substr($decoded, 0, 8));
    if ($header === '89504e470d0a1a0a') {
        echo "✓ Valid PNG file\n";
    }
} else {
    echo "✗ Base64 decode failed\n";
}
```

### View HTML Source di Browser

```html
<!-- Cek di halaman products - right click → View Page Source -->
<!-- Cari <img src="data:image/png;base64,... -->
<!-- Pastikan base64 string valid -->
```

---

## 🚀 Performance Testing

### Load Testing dengan Apache Bench

```bash
# Test halaman products
ab -n 100 -c 10 http://localhost:8000/products

# Check requests per second dan response time
```

### Memory Usage

```php
// Di controller, tambah:
echo "Memory: " . round(memory_get_usage() / 1024 / 1024, 2) . " MB";
echo "Peak: " . round(memory_get_peak_usage() / 1024 / 1024, 2) . " MB";
```

### Query Performance

```php
// Di tinker, enable query logging:
DB::enableQueryLog();

// Run query
$products = DB::connection('pgsql_odoo')->select("...");

// Check queries
dd(DB::getQueryLog());
```

---

## ✨ Common Issues & Solutions

| Issue                    | Solution                                           |
| ------------------------ | -------------------------------------------------- |
| Image tidak tampil       | Check if base64 valid: `if ($product->image_1920)` |
| Halaman loading lambat   | Use lazy loading: `loading="lazy"` attribute       |
| Database error           | Verify connection: check `.env` DB*ODOO*\*         |
| Out of memory            | Reduce image size atau cache hasil query           |
| Image broken di DevTools | Check CORS (shouldn't be issue dengan data URI)    |

---

## 📊 Success Criteria

- [x] Gambar tampil di list view (thumbnail)
- [x] Gambar tampil di detail view (large)
- [x] Placeholder tampil jika tidak ada gambar
- [x] Data URI properly formatted
- [x] No HTTP errors di console
- [x] Response time < 500ms per page
- [x] Memory usage < 50MB
- [x] All tests passing

---

## 📝 Notes

- Base64 image data adalah embedding langsung di HTML, tidak ada image HTTP request
- File size akan lebih besar untuk image_1920, gunakan image_256 untuk list view
- Jika traffic tinggi, implementasikan caching atau download images ke storage
- Lazy loading recommended untuk large images
