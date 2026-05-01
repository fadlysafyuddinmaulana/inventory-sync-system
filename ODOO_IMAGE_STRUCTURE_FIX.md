# 🔧 FIX: Gambar Produk dari ir_attachment Table

## ✅ Masalah & Solusi

### Masalah Awal

```
Error: column pt.image_128 does not exist
```

**Penyebab**: Gambar produk di Odoo 19 TIDAK disimpan di tabel `product_template`, tetapi di tabel `ir_attachment`.

---

## 📊 Struktur Data yang Benar

### Tabel `ir_attachment` (Bukan product_template)

```sql
SELECT * FROM ir_attachment
WHERE res_model = 'product.template'
  AND name IN ('image_128', 'image_256', 'image_1920')
LIMIT 5;
```

**Struktur Kolom:**

| Kolom       | Tipe    | Deskripsi                                                            |
| ----------- | ------- | -------------------------------------------------------------------- |
| `id`        | integer | ID attachment                                                        |
| `name`      | varchar | Nama gambar: image_1920, image_1024, image_512, image_256, image_128 |
| `res_model` | varchar | 'product.template' (menunjuk ke product template)                    |
| `res_id`    | integer | ID product_template yang punya gambar ini                            |
| `db_datas`  | bytea   | Binary image data (BUKAN base64)                                     |
| `type`      | varchar | 'binary' (tipe attachment)                                           |
| `mimetype`  | varchar | 'image/png' atau 'image/jpeg'                                        |

---

## 🔍 Query Yang Benar

### Get Image dengan Base64 Encode

```sql
-- Single product image
SELECT
    name,
    encode(db_datas, 'base64') as image_data
FROM ir_attachment
WHERE res_model = 'product.template'
  AND res_id = 1  -- Product template ID
  AND name IN ('image_128', 'image_256', 'image_1920')
ORDER BY
    CASE
        WHEN name = 'image_1920' THEN 1
        WHEN name = 'image_1024' THEN 2
        WHEN name = 'image_512' THEN 3
        WHEN name = 'image_256' THEN 4
        WHEN name = 'image_128' THEN 5
    END;
```

**Penting**: Gunakan `encode(db_datas, 'base64')` untuk konversi bytea → base64

---

## 🛠️ Implementasi di Laravel

### ProductController

```php
// Get products with images
$products = DB::connection('pgsql_odoo')->select("
    SELECT
        pp.id,
        pp.default_code,
        pt.name ->> 'en_US' AS name,
        pt.list_price,
        pt.id as template_id,  -- Perlu ini untuk query images
        COALESCE(SUM(sq.quantity), 0) AS qty_on_hand
    FROM product_product pp
    JOIN product_template pt ON pp.product_tmpl_id = pt.id
    LEFT JOIN stock_quant sq ON sq.product_id = pp.id
    LEFT JOIN stock_location sl ON sq.location_id = sl.id
    WHERE (sl.usage = 'internal' OR sl.usage IS NULL)
    GROUP BY pp.id, pp.default_code, pt.name, pt.list_price, pt.id
    ORDER BY pt.name
");

// Fetch images from ir_attachment for each product
foreach ($products as $product) {
    $images = DB::connection('pgsql_odoo')->select("
        SELECT name, encode(db_datas, 'base64') as data
        FROM ir_attachment
        WHERE res_model = 'product.template'
        AND res_id = ?
        AND name IN ('image_128', 'image_256', 'image_1920', 'image_1024', 'image_512')
    ", [$product->template_id]);

    // Attach image properties to product object
    foreach ($images as $img) {
        $product->{$img->name} = $img->data;  // e.g., $product->image_1920 = "base64data..."
    }
}

return view('products.index', compact('products'));
```

---

## 📝 Blade View Usage

### Tanpa Helper (Direct)

```blade
@if (!empty($product->image_256))
    <img src="data:image/png;base64,{{ $product->image_256 }}"
         alt="{{ $product->name }}"
         style="width: 50px; height: 50px; object-fit: cover;">
@else
    <span class="badge badge-secondary">No Image</span>
@endif
```

### Dengan Helper (Recommended)

```blade
@use('App\Helpers\ProductImageHelper')

<!-- Thumbnail -->
<img src="{{ ProductImageHelper::getThumbnail($product) }}" alt="">

<!-- Medium -->
<img src="{{ ProductImageHelper::getMedium($product) }}" alt="">

<!-- Large -->
<img src="{{ ProductImageHelper::getLarge($product) }}" alt="">

<!-- Auto with placeholder -->
{!! ProductImageHelper::getImageTag($product, '256') !!}
```

---

## 🔄 Flow Data

```
1. Product Template di product_template table
   ├─ id: 1
   ├─ name: 'Product A'
   └─ ...

2. Images di ir_attachment table (SEPARATE)
   ├─ res_model: 'product.template'
   ├─ res_id: 1 (link ke product template)
   ├─ name: 'image_1920'
   ├─ db_datas: BYTEA (binary data)
   └─ ...

3. ProductController
   ├─ Query product dari product_template
   ├─ Query gambar dari ir_attachment dengan JOIN res_id
   ├─ Encode bytea ke base64: encode(db_datas, 'base64')
   └─ Attach ke product object

4. Blade View
   ├─ Terima product object dengan image properties
   ├─ Display: <img src="data:image/png;base64,{{ $product->image_256 }}">
   └─ Browser render image
```

---

## 🐛 Debugging

### Check Available Images di Database

```bash
php artisan tinker
```

```php
// Lihat semua image untuk product template id=1
DB::connection('pgsql_odoo')->select("
    SELECT id, name, res_model, res_id, type
    FROM ir_attachment
    WHERE res_model = 'product.template'
    AND res_id = 1
")

// Check if data ada (shouldn't decode/print large binary)
DB::connection('pgsql_odoo')->selectOne("
    SELECT name, type, file_size
    FROM ir_attachment
    WHERE res_model = 'product.template'
    AND res_id = 1
    AND name = 'image_1920'
    LIMIT 1
")

// Test encode function
DB::connection('pgsql_odoo')->selectOne("
    SELECT
        name,
        LENGTH(db_datas) as original_size,
        LENGTH(encode(db_datas, 'base64')) as base64_size
    FROM ir_attachment
    WHERE res_model = 'product.template'
    AND name = 'image_1920'
    LIMIT 1
")
```

---

## 📋 Struktur Lengkap ProductTemplate

```php
// app/Models/Odoo/ProductTemplate.php

namespace App\Models\Odoo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductTemplate extends Model
{
    protected $connection = 'pgsql_odoo';
    protected $table = 'product_template';
    public $timestamps = false;

    protected $fillable = ['name', 'default_code', 'list_price', 'categ_id'];

    public function products()
    {
        return $this->hasMany(ProductProduct::class, 'product_tmpl_id');
    }

    /**
     * Get image dari ir_attachment
     * Images are stored in ir_attachment, NOT in this table
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
```

---

## 💾 Alternative: Cache Images (Untuk Performance)

Jika query ir_attachment sering dipanggil, bisa cache:

```php
// ProductController@index
$products = collect(DB::connection('pgsql_odoo')->select("..."))->map(function($product) {
    $cacheKey = 'product_images_' . $product->template_id;

    $images = Cache::remember($cacheKey, 3600, function() use ($product) {
        return DB::connection('pgsql_odoo')->select("
            SELECT name, encode(db_datas, 'base64') as data
            FROM ir_attachment
            WHERE res_model = 'product.template'
            AND res_id = ?
            AND name IN ('image_128', 'image_256', 'image_1920')
        ", [$product->template_id]);
    });

    foreach ($images as $img) {
        $product->{$img->name} = $img->data;
    }

    return $product;
})->all();
```

---

## ✅ Checklist Verifikasi

- [x] Gambar ada di `ir_attachment` table (bukan `product_template`)
- [x] Query pakai `encode(db_datas, 'base64')` untuk convert bytea
- [x] `res_model = 'product.template'` untuk filter produk
- [x] `res_id` = product template ID
- [x] ProductController query dan attach images ke product object
- [x] Helper terima product dengan image properties
- [x] Blade render `data:image/png;base64,{{ $product->image_256 }}`
- [x] Test di browser - gambar muncul

---

## 🚀 Testing

```bash
# Terminal 1: Start server
php artisan serve

# Terminal 2: Test di tinker
php artisan tinker

# Check images exist
DB::connection('pgsql_odoo')->select("
    SELECT COUNT(*) as count
    FROM ir_attachment
    WHERE res_model = 'product.template'
    AND name IN ('image_128', 'image_256', 'image_1920')
")
```

Buka browser: `http://localhost:8000/products`

**Expected Result:**

- ✅ List view: Thumbnail 50x50px di kolom Image
- ✅ Detail view: Gambar besar 1920px tampil
- ✅ Fallback: Placeholder jika tidak ada gambar

---

## 📚 Key Differences vs Original Plan

| Aspek             | Original Plan            | Realitas Odoo                |
| ----------------- | ------------------------ | ---------------------------- |
| **Lokasi Gambar** | `product_template` table | `ir_attachment` table        |
| **Format Data**   | Base64 string            | BYTEA binary                 |
| **Join Key**      | Direct column            | `res_model` & `res_id`       |
| **Konversi**      | Langsung                 | `encode(db_datas, 'base64')` |
| **Ukuran**        | 128, 256, 1920           | 128, 256, 512, 1024, 1920    |

---

**Status**: ✅ FIXED & READY TO USE

Sekarang query sesuai dengan struktur database Odoo 19 yang sesungguhnya!
