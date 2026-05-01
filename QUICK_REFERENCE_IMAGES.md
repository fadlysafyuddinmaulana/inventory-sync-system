# 🚀 QUICK REFERENCE: Gambar Produk Odoo

## ⚡ Quickstart (30 Detik)

### Step 1: View Halaman Products

```bash
php artisan serve
# Buka http://localhost:8000/products
```

### Step 2: Lihat Gambar

- **List View**: Thumbnail 50x50px di kolom pertama
- **Detail View**: Gambar besar (1920px) saat klik view

### Step 3: Done! ✅

Gambar sudah menampilkan dari Odoo tanpa setup tambahan.

---

## 📌 3 Cara Menggunakan

### Cara 1: Blade Direct (Paling Simple)

```blade
<img src="data:image/png;base64,{{ $product->image_128 }}" alt="">
```

### Cara 2: Helper (Recommended)

```blade
@use('App\Helpers\ProductImageHelper')
<img src="{{ ProductImageHelper::getThumbnail($product) }}" alt="">
```

### Cara 3: Tag Helper (Auto Placeholder)

```blade
@use('App\Helpers\ProductImageHelper')
{!! ProductImageHelper::getImageTag($product, '256') !!}
```

---

## 🎯 Ukuran Image

| Size   | Usage          | Quality |
| ------ | -------------- | ------- |
| 128px  | Thumbnail list | Low     |
| 256px  | Card/medium    | Medium  |
| 1920px | Detail page    | High    |

---

## 🔧 Troubleshooting

### ❌ Gambar tidak tampil

```bash
# 1. Check database connection
php artisan tinker
DB::connection('pgsql_odoo')->select("SELECT 1")

# 2. Check ada image di database
DB::connection('pgsql_odoo')->select("
    SELECT COUNT(*) FROM product_template WHERE image_1920 IS NOT NULL
")

# 3. Check view source di browser (Ctrl+U)
# Cari: <img src="data:image/png;base64,...">
```

### ❌ Database error

```bash
# Pastikan di .env:
DB_ODOO_HOST=localhost
DB_ODOO_DATABASE=odoo
DB_ODOO_USERNAME=odoo
DB_ODOO_PASSWORD=password

# Restart server
php artisan serve
```

### ❌ Memory error

```php
// Di controller: gunakan image_256 saja
pt.image_256  // Bukan image_1920
```

---

## 📍 File Locations

| File                                         | Purpose                        |
| -------------------------------------------- | ------------------------------ |
| `app/Helpers/ProductImageHelper.php`         | Helper class                   |
| `app/Models/Odoo/ProductTemplate.php`        | Model dengan image methods     |
| `app/Http/Controllers/ProductController.php` | Query image data               |
| `resources/views/products/index.blade.php`   | List view dengan thumbnail     |
| `resources/views/products/show.blade.php`    | Detail view dengan large image |

---

## 💡 Common Code Snippets

### Display Thumbnail (50x50)

```blade
<img src="{{ ProductImageHelper::getThumbnail($product) }}"
     style="width: 50px; height: 50px; object-fit: cover;">
```

### Display Medium (250x250)

```blade
<img src="{{ ProductImageHelper::getMedium($product) }}"
     class="img-fluid">
```

### Display Large (Full Width)

```blade
<img src="{{ ProductImageHelper::getLarge($product) }}"
     class="img-fluid"
     style="max-width: 100%;">
```

### Check Ada Gambar

```blade
@if (ProductImageHelper::hasImage($product))
    <img src="{{ ProductImageHelper::getThumbnail($product) }}">
@else
    <span class="badge">No Image</span>
@endif
```

### API Response

```php
return response()->json([
    'id' => $product->id,
    'name' => $product->name,
    'image' => ProductImageHelper::getLarge($product)
]);
```

---

## 🧪 Testing

### Manual Test

```bash
1. Browser: http://localhost:8000/products
2. Click view icon pada produk
3. Lihat halaman detail dengan gambar besar
```

### Console Test

```php
php artisan tinker
$p = DB::connection('pgsql_odoo')->selectOne("SELECT * FROM product_template WHERE image_1920 IS NOT NULL LIMIT 1");
ProductImageHelper::hasImage($p)  // true
ProductImageHelper::getThumbnail($p)  // data:image/png;base64,...
```

---

## 🎨 CSS Classes

### Untuk Styling Gambar

```css
.product-image {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-thumbnail {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.product-no-image {
    background-color: #e9ecef;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 200px;
}
```

---

## 🔄 Database Queries

### List Products dengan Images

```sql
SELECT pp.id, pt.name, pt.image_256
FROM product_product pp
JOIN product_template pt ON pp.product_tmpl_id = pt.id
WHERE pt.image_256 IS NOT NULL
LIMIT 10;
```

### Check Image Sizes

```sql
SELECT
    id, name,
    LENGTH(image_128)/1024 as size_128_kb,
    LENGTH(image_256)/1024 as size_256_kb,
    LENGTH(image_1920)/1024 as size_1920_kb
FROM product_template
WHERE image_1920 IS NOT NULL
ORDER BY size_1920_kb DESC
LIMIT 5;
```

---

## 🚀 Performance Tips

1. **Gunakan Lazy Loading**

    ```blade
    <img src="..." loading="lazy">
    ```

2. **Cache Results** (Recommended)

    ```php
    $products = Cache::remember('products', 3600, fn() =>
        DB::connection('pgsql_odoo')->select("...")
    );
    ```

3. **Gunakan Size Terkecil**
    - List: `image_128` atau `image_256`
    - Detail: `image_1920`

4. **Kompres Image** (Optional)
    - Implementasi queue untuk download & compress

---

## 📞 Help & Documentation

| Resource      | Location                           |
| ------------- | ---------------------------------- |
| Full Guide    | `PRODUCT_IMAGE_GUIDE.md`           |
| Code Examples | `PRODUCT_IMAGE_EXAMPLES.php`       |
| Testing Guide | `PRODUCT_IMAGE_TESTING_GUIDE.md`   |
| Summary       | `IMPLEMENTATION_SUMMARY_IMAGES.md` |

---

## ✅ Verification Checklist

- [ ] Buka http://localhost:8000/products
- [ ] Lihat thumbnail di kolom Image
- [ ] Klik view icon untuk detail
- [ ] Lihat gambar besar di halaman detail
- [ ] Check browser console (F12) - tidak ada error
- [ ] Check Network tab - image loaded as data URI

---

## 🎯 Next Steps (Optional)

### 1. Implementasi Caching

```php
Cache::remember('products', 3600, function() {
    return DB::connection('pgsql_odoo')->select("...");
});
```

### 2. Download Images to Storage

```bash
php artisan products:download-images
```

### 3. Add Image Cropping

```php
Image::make($imageData)->resize(256, 256)->save();
```

---

**Status**: ✅ PRODUCTION READY

Hubungi jika ada pertanyaan atau issues!
