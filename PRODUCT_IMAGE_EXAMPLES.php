<?php

namespace App\Helpers;

/**
 * QUICK EXAMPLES: ProductImageHelper Usage
 */

// ============================================
// EXAMPLE 1: Display Thumbnail in List View
// ============================================
?>

<!-- resources/views/products/index.blade.php -->
@use('App\Helpers\ProductImageHelper')

@foreach ($products as $product)
    <tr>
        <td>
            @if (ProductImageHelper::hasImage($product))
                <img src="{{ ProductImageHelper::getThumbnail($product) }}" 
                     alt="{{ $product->name }}"
                     class="product-thumbnail"
                     style="width: 50px; height: 50px; object-fit: cover;">
            @else
                <span class="badge badge-secondary">No Image</span>
            @endif
        </td>
        <td>{{ $product->name }}</td>
    </tr>
@endforeach

<?php
// ============================================
// EXAMPLE 2: Display Large Image in Detail Page
// ============================================
?>

<!-- resources/views/products/show.blade.php -->
@use('App\Helpers\ProductImageHelper')

<div class="product-image-container">
    @if (ProductImageHelper::hasImage($product))
        <img src="{{ ProductImageHelper::getLarge($product) }}" 
             alt="{{ $product->name }}"
             class="img-fluid"
             style="max-width: 100%; border-radius: 8px;">
    @else
        <div class="alert alert-info">
            No product image available
        </div>
    @endif
</div>

<?php
// ============================================
// EXAMPLE 3: Custom Image Gallery
// ============================================
?>

<!-- Menampilkan multiple sizes dalam modal/carousel -->
@use('App\Helpers\ProductImageHelper')

<div class="product-gallery">
    @if (ProductImageHelper::hasImage($product))
        <!-- Thumbnail -->
        <div class="gallery-thumb">
            {!! ProductImageHelper::getImageTag($product, '128', ['class' => 'img-thumbnail']) !!}
        </div>
        
        <!-- Medium -->
        <div class="gallery-medium">
            {!! ProductImageHelper::getImageTag($product, '256', ['class' => 'img-fluid']) !!}
        </div>
        
        <!-- Full -->
        <div class="gallery-full">
            {!! ProductImageHelper::getImageTag($product, '1920', ['class' => 'img-fluid', 'loading' => 'lazy']) !!}
        </div>
    @endif
</div>

<?php
// ============================================
// EXAMPLE 4: Product Card with Image
// ============================================
?>

<!-- Reusable product card component -->
@use('App\Helpers\ProductImageHelper')

<div class="product-card" style="width: 200px;">
    <div class="card-image" style="height: 200px; overflow: hidden;">
        @if (ProductImageHelper::hasImage($product))
            <img src="{{ ProductImageHelper::getMedium($product) }}"
                 alt="{{ $product->name }}"
                 style="width: 100%; height: 100%; object-fit: cover;">
        @else
            <div style="width: 100%; height: 100%; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-image" style="font-size: 3rem; color: #ccc;"></i>
            </div>
        @endif
    </div>
    <div class="card-body" style="padding: 10px;">
        <h5 class="product-name">{{ $product->name }}</h5>
        <p class="product-sku">SKU: {{ $product->default_code }}</p>
        <p class="product-price">Rp {{ number_format($product->list_price, 0, ',', '.') }}</p>
    </div>
</div>

<?php
// ============================================
// EXAMPLE 5: In Controller (Return JSON)
// ============================================
?>

namespace App\Http\Controllers;

use App\Helpers\ProductImageHelper;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $products = DB::connection('pgsql_odoo')->select("
            SELECT pp.id, pp.default_code, pt.name, pt.list_price,
                   pt.image_128, pt.image_256, pt.image_1920
            FROM product_product pp
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            LIMIT 10
        ");

        return response()->json([
            'success' => true,
            'data' => array_map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->default_code,
                    'price' => $product->list_price,
                    'image_thumbnail' => ProductImageHelper::getThumbnail($product),
                    'image_medium' => ProductImageHelper::getMedium($product),
                    'image_large' => ProductImageHelper::getLarge($product),
                    'has_image' => ProductImageHelper::hasImage($product)
                ];
            }, $products)
        ]);
    }
}

<?php
// ============================================
// EXAMPLE 6: Image Fallback Pattern
// ============================================
?>

<!-- Component yang reusable -->
@use('App\Helpers\ProductImageHelper')

@if (ProductImageHelper::hasImage($product))
    <!-- Jika ada gambar -->
    <div class="product-image-wrapper">
        <img src="{{ ProductImageHelper::getMedium($product) }}"
             alt="{{ $product->name }}"
             loading="lazy"
             class="product-image">
    </div>
@else
    <!-- Fallback placeholder -->
    <div class="product-image-wrapper product-no-image">
        <div class="placeholder">
            <i class="fas fa-camera"></i>
            <p>No Image</p>
        </div>
    </div>
@endif

<?php
// ============================================
// EXAMPLE 7: Conditional Display Styles
// ============================================
?>

@use('App\Helpers\ProductImageHelper')

@foreach ($products as $product)
    <div class="product-item @if(!ProductImageHelper::hasImage($product)) no-image @endif">
        @if (ProductImageHelper::hasImage($product))
            <div class="product-image-featured">
                <img src="{{ ProductImageHelper::getMedium($product) }}"
                     alt="{{ $product->name }}">
            </div>
        @else
            <div class="product-image-placeholder">
                <i class="fas fa-box"></i>
            </div>
        @endif
        <div class="product-info">
            <h4>{{ $product->name }}</h4>
            <p>{{ $product->default_code }}</p>
        </div>
    </div>
@endforeach

<?php
// ============================================
// EXAMPLE 8: Performance: Batch Processing
// ============================================
?>

namespace App\Http\Controllers;

use App\Helpers\ProductImageHelper;
use Illuminate\Support\Facades\Cache;

class ProductCacheController extends Controller
{
    public function cachedProducts()
    {
        // Cache products for 1 hour
        $products = Cache::remember('products_with_images', 3600, function () {
            return DB::connection('pgsql_odoo')->select("
                SELECT pp.id, pt.name, pt.list_price,
                       pt.image_128, pt.image_256, pt.image_1920
                FROM product_product pp
                JOIN product_template pt ON pp.product_tmpl_id = pt.id
                LIMIT 50
            ");
        });

        // Format with images
        $formatted = array_map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->list_price,
                'thumbnail' => ProductImageHelper::getThumbnail($product),
                'has_image' => ProductImageHelper::hasImage($product)
            ];
        }, $products);

        return view('products.cached', ['products' => $formatted]);
    }
}

<?php
// ============================================
// EXAMPLE 9: Error Handling
// ============================================
?>

@use('App\Helpers\ProductImageHelper')

<div class="product-image-container">
    @try
        @if (ProductImageHelper::hasImage($product))
            <img src="{{ ProductImageHelper::getLarge($product) }}"
                 alt="{{ $product->name }}"
                 onerror="this.style.display='none';">
        @else
            <div class="image-placeholder">No Image Available</div>
        @endif
    @catch (\Exception $e)
        <div class="alert alert-danger">
            Error loading image: {{ $e->getMessage() }}
        </div>
    @endtry
</div>

<?php
// ============================================
// EXAMPLE 10: CLI Command untuk Download Images
// ============================================
?>

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DownloadProductImages extends Command
{
    protected $signature = 'products:download-images {--size=1920}';
    protected $description = 'Download product images dari Odoo ke storage';

    public function handle()
    {
        $size = $this->option('size');
        $field = "image_{$size}";

        $products = DB::connection('pgsql_odoo')
            ->select("SELECT id, {$field} FROM product_template WHERE {$field} IS NOT NULL");

        $this->info("Downloading " . count($products) . " images...");

        foreach ($products as $product) {
            $filename = "product_{$product->id}_{$size}.png";
            Storage::disk('public')->put(
                "products/{$filename}",
                base64_decode($product->$field)
            );
            $this->line("✓ Downloaded: {$filename}");
        }

        $this->info("All images downloaded successfully!");
    }
}

// ============ Usage ============
// php artisan products:download-images
// php artisan products:download-images --size=256
?>
