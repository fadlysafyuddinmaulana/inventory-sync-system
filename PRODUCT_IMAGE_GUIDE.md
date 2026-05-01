# Panduan Menampilkan Gambar Produk dari Odoo 19

## 📋 Daftar Isi

1. [Pengenalan](#pengenalan)
2. [Cara Kerja](#cara-kerja)
3. [Implementasi](#implementasi)
4. [Penggunaan Helper](#penggunaan-helper)
5. [Performa & Optimasi](#performa--optimasi)
6. [Troubleshooting](#troubleshooting)
7. [Alternatif Solusi](#alternatif-solusi)

---

## 🎯 Pengenalan

Gambar produk di Odoo 19 disimpan dalam format **Base64** di database PostgreSQL. Solusi ini mengakses gambar langsung dari database Odoo tanpa perlu menyimpannya di Laravel.

### Keuntungan:

- ✅ Tidak perlu storage di server Laravel
- ✅ Gambar selalu sinkron dengan Odoo
- ✅ Mendukung multiple ukuran gambar (128px, 256px, 1920px)
- ✅ Performa baik untuk aplikasi yang sudah ada

### Keterbatasan:

- ⚠️ Tidak ideal untuk aplikasi dengan traffic tinggi (perlu cache)
- ⚠️ Gambar ukuran besar (1920px) meningkatkan ukuran payload

---

## 🔧 Cara Kerja

### Struktur Data di Odoo PostgreSQL

```
Tabel: product_template
Kolom: image_128    (small thumbnail, 128x128px, base64)
Kolom: image_256    (medium, 256x256px, base64)
Kolom: image_1920   (large, 1920x1920px, base64)
```

### Flow Data:

```
Odoo Database (PostgreSQL)
    ↓
ProductController (Query image fields)
    ↓
Blade Template (Display as <img> tag)
    ↓
Browser (Render data URI image)
```

---

## 🚀 Implementasi

### File yang Diubah:

1. **Model**: `app/Models/Odoo/ProductTemplate.php`
    - Tambah method `getImageDataUri()` dan `hasImage()`

2. **Controller**: `app/Http/Controllers/ProductController.php`
    - Tambah kolom `image_128`, `image_256`, `image_1920` ke query SQL

3. **Views**:
    - `resources/views/products/index.blade.php` (Thumbnail di list)
    - `resources/views/products/show.blade.php` (Large image di detail)

4. **Helper**: `app/Helpers/ProductImageHelper.php`
    - Utility class untuk menangani gambar

---

## 💡 Penggunaan Helper

### Import Helper di View:

```blade
@use('App\Helpers\ProductImageHelper')
```

### Contoh Penggunaan:

#### 1. Display Gambar dengan Helper

```blade
<!-- Thumbnail (128px) -->
<img src="{{ ProductImageHelper::getThumbnail($product) }}"
     alt="{{ $product->name }}"
     style="width: 50px; height: 50px;">

<!-- Medium (256px) -->
<img src="{{ ProductImageHelper::getMedium($product) }}"
     alt="{{ $product->name }}">

<!-- Large (1920px) -->
<img src="{{ ProductImageHelper::getLarge($product) }}"
     alt="{{ $product->name }}">
```

#### 2. Display dengan Tag Helper

```blade
<!-- Automatic placeholder jika tidak ada gambar -->
{!! ProductImageHelper::getImageTag($product, '256') !!}

<!-- Custom attributes -->
{!! ProductImageHelper::getImageTag($product, '1920', [
    'class' => 'img-fluid product-image',
    'style' => 'max-width: 100%; border-radius: 8px;'
]) !!}
```

#### 3. Check Gambar Ada atau Tidak

```blade
@if (ProductImageHelper::hasImage($product))
    <img src="{{ ProductImageHelper::getThumbnail($product) }}" />
@else
    <div class="no-image">No Image Available</div>
@endif
```

#### 4. Di Controller (Optional)

```php
<?php
namespace App\Http\Controllers;

use App\Helpers\ProductImageHelper;

class ProductController extends Controller {
    public function show($id) {
        $product = Product::find($id);

        // Get image URL untuk dikirim ke API/JSON
        $imageUrl = ProductImageHelper::getLarge($product);

        return view('products.show', [
            'product' => $product,
            'imageUrl' => $imageUrl
        ]);
    }
}
```

---

## ⚡ Performa & Optimasi

### 1. Query Optimization

Gunakan `select()` untuk hanya mengambil kolom yang dibutuhkan:

```php
$products = DB::connection('pgsql_odoo')->select("
    SELECT
        pp.id,
        pp.default_code,
        pt.name,
        pt.list_price,
        pt.image_256,  -- Ambil ukuran yang diperlukan
        COALESCE(SUM(sq.quantity), 0) AS qty_on_hand
    FROM product_product pp
    JOIN product_template pt ON pp.product_tmpl_id = pt.id
    ...
");
```

### 2. Caching Gambar

Untuk aplikasi dengan traffic tinggi, implementasikan caching:

```php
// Di Controller
$products = Cache::remember('products_list', 3600, function () {
    return DB::connection('pgsql_odoo')->select("...");
});
```

### 3. Lazy Loading untuk Gambar Besar

```blade
<!-- Lazy load gambar besar -->
<img src="{{ ProductImageHelper::getLarge($product) }}"
     alt="{{ $product->name }}"
     loading="lazy"
     style="max-width: 100%;">
```

### 4. Resizing (Optional)

Jika ingin resize gambar di sisi client:

```blade
<img src="{{ ProductImageHelper::getLarge($product) }}"
     alt="{{ $product->name }}"
     style="width: 300px; height: 300px; object-fit: cover;">
```

---

## 🐛 Troubleshooting

### 1. Gambar Tidak Tampil

**Masalah**: `<img>` tag muncul tapi gambar kosong

**Solusi**:

```blade
<!-- Debug: lihat data URI yang dihasilkan -->
{{ ProductImageHelper::getLarge($product) }}

<!-- Pastikan base64 valid -->
@if (ProductImageHelper::hasImage($product))
    Gambar tersedia
@else
    Tidak ada gambar
@endif
```

### 2. Error: Column 'image_1920' doesn't exist

**Masalah**: Database Odoo tidak memiliki kolom gambar

**Solusi**:

- Pastikan menggunakan Odoo versi 19+
- Check database dengan query:

```sql
SELECT * FROM information_schema.columns
WHERE table_name = 'product_template'
AND column_name LIKE 'image_%';
```

### 3. Performa Lambat (Large Payload)

**Masalah**: Loading data menjadi lambat

**Solusi**:

- Gunakan `image_128` atau `image_256` untuk list view
- Gunakan `image_1920` hanya untuk detail view
- Implementasi caching Redis

### 4. CORS/Security Issues

Jika menggunakan data URI, tidak ada CORS issue karena gambar embed di response.

---

## 🔄 Alternatif Solusi

### Alternatif 1: Download & Store Gambar di Laravel

```php
// Buat migration
Schema::create('product_images', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('product_id');
    $table->string('image_path');
    $table->string('image_url')->nullable();
    $table->timestamps();
});

// Download gambar dari Odoo
use Illuminate\Support\Facades\Storage;

public function downloadProductImages()
{
    $products = ProductTemplate::whereNotNull('image_1920')->get();

    foreach ($products as $product) {
        if ($product->image_1920) {
            $imageName = "product_{$product->id}.png";
            Storage::disk('public')->put(
                "products/{$imageName}",
                base64_decode($product->image_1920)
            );
        }
    }
}
```

**Keuntungan**: Performa lebih baik, tidak perlu query gambar terus-menerus  
**Kekurangan**: Perlu sync rutin dari Odoo

### Alternatif 2: Gunakan Odoo REST API

```php
// Jika Odoo API tersedia
$client = new GuzzleHttp\Client();
$response = $client->get('https://odoo.example.com/api/product/' . $id . '/image');
$image = base64_encode($response->getBody());
```

**Keuntungan**: Fleksibel, bisa ambil data lainnya  
**Kekurangan**: Dependency pada network Odoo API

### Alternatif 3: Proxy Image melalui Controller

```php
// routes/web.php
Route::get('/product-image/{id}/{size}', 'ProductController@getImage');

// ProductController.php
public function getImage($id, $size = '256')
{
    $product = DB::connection('pgsql_odoo')
        ->selectOne("SELECT image_{$size} FROM product_template WHERE id = ?", [$id]);

    return response()
        ->make(base64_decode($product->{"image_{$size}"}), 200, [
            'Content-Type' => 'image/png'
        ]);
}
```

**Keuntungan**: Kontrol penuh, bisa add watermark/filter  
**Kekurangan**: Lebih resource-intensive

---

## 📝 Checklist Implementasi

- [x] Update `ProductTemplate` model
- [x] Update `ProductController` (index & show methods)
- [x] Create `ProductImageHelper` class
- [x] Update `products/index.blade.php` (thumbnail)
- [x] Update `products/show.blade.php` (large image)
- [ ] Test gambar tampil dengan benar
- [ ] Implementasi caching (opsional)
- [ ] Setup error handling untuk missing images
- [ ] Performance testing dengan banyak produk

---

## 🔗 Query SQL Reference

### Get All Products dengan Images:

```sql
SELECT
    pp.id,
    pp.default_code,
    pt.name,
    pt.list_price,
    pt.image_128,
    pt.image_256,
    pt.image_1920,
    LENGTH(pt.image_1920) as image_size_bytes
FROM product_product pp
JOIN product_template pt ON pp.product_tmpl_id = pt.id
WHERE pt.image_1920 IS NOT NULL
ORDER BY pt.name;
```

### Check Image Size:

```sql
SELECT
    id,
    name,
    LENGTH(image_1920)/1024 as size_kb
FROM product_template
WHERE image_1920 IS NOT NULL
ORDER BY size_kb DESC;
```

---

## 📞 Support

Untuk pertanyaan atau masalah lebih lanjut, silakan cek:

- Database connection di `.env` (DB_ODOO_HOST, DB_ODOO_DATABASE, dll)
- Query result di database tools
- Browser console untuk JavaScript errors
