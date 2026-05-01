<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = DB::connection('pgsql_odoo')->select("
                SELECT 
                    pp.id,
                    pp.default_code,
                    pt.name ->> 'en_US' AS name,
                    pt.list_price,
                    pt.id as template_id,
                    COALESCE(SUM(sq.quantity), 0) AS qty_on_hand
                FROM product_product pp
                JOIN product_template pt ON pp.product_tmpl_id = pt.id
                LEFT JOIN stock_quant sq ON sq.product_id = pp.id
                LEFT JOIN stock_location sl ON sq.location_id = sl.id
                WHERE (sl.usage = 'internal' OR sl.usage IS NULL)
                GROUP BY pp.id, pp.default_code, pt.name, pt.list_price, pt.id
                ORDER BY pt.name
            ");

            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            return view('products.index', [
                'error'    => 'Error fetching products: ' . $e->getMessage(),
                'products' => [],
            ]);
        }
    }

    public function show($id)
    {
        try {
            $product = DB::connection('pgsql_odoo')->selectOne("
                SELECT 
                    pp.id,
                    pp.default_code,
                    pt.name ->> 'en_US' AS name,
                    pt.list_price,
                    pt.id as template_id,
                    COALESCE(SUM(sq.quantity), 0) AS qty_on_hand
                FROM product_product pp
                JOIN product_template pt ON pp.product_tmpl_id = pt.id
                LEFT JOIN stock_quant sq ON sq.product_id = pp.id
                WHERE pp.id = ?
                GROUP BY pp.id, pp.default_code, pt.name, pt.list_price, pt.id
            ", [$id]);

            if (!$product) {
                return redirect()->route('products')->with('error', 'Product not found');
            }

            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('products')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getImage($template_id, $size = 128)
    {
        try {
            // ✅ FIX Bug 1: gunakan res_field bukan name
            $attachment = DB::connection('pgsql_odoo')->selectOne("
                SELECT id, res_field
                FROM ir_attachment
                WHERE res_model = 'product.template'
                AND res_id      = ?
                AND res_field   = ?
                LIMIT 1
            ", [$template_id, "image_{$size}"]);

            // ✅ FIX Bug 2: fallback ke placeholder jika tidak ada gambar
            if (!$attachment) {
                // Coba fallback ke image_1920 kalau size tidak ditemukan
                $attachment = DB::connection('pgsql_odoo')->selectOne("
                    SELECT id, res_field
                    FROM ir_attachment
                    WHERE res_model = 'product.template'
                    AND res_id      = ?
                    AND res_field   LIKE 'image_%'
                    ORDER BY id DESC
                    LIMIT 1
                ", [$template_id]);
            }

            if (!$attachment) {
                // Return placeholder image dari public folder
                $placeholder = public_path('images/no-image.png');
                if (file_exists($placeholder)) {
                    return response()->file($placeholder, [
                        'Content-Type'  => 'image/png',
                        'Cache-Control' => 'max-age=3600',
                    ]);
                }
                return response()->noContent();
            }

            // ✅ FIX Bug 3: gunakan env() dengan fallback yang benar
            $odooUrl = env('ODOO_URL', 'http://localhost:8069');
            $url     = "{$odooUrl}/web/image/ir.attachment/{$attachment->id}";
            $url    .= "?max_width={$size}&max_height={$size}";

            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                $contentType = $response->header('Content-Type') ?? 'image/png';
                return response($response->body(), 200)
                    ->header('Content-Type', $contentType)
                    ->header('Cache-Control', 'max-age=86400');
            }

            // Kalau Odoo return error, tampilkan placeholder
            $placeholder = public_path('images/no-image.png');
            if (file_exists($placeholder)) {
                return response()->file($placeholder, ['Content-Type' => 'image/png']);
            }

            return response()->noContent();

        } catch (\Exception $e) {
            Log::error("Error fetching product image: " . $e->getMessage());

            $placeholder = public_path('images/no-image.png');
            if (file_exists($placeholder)) {
                return response()->file($placeholder, ['Content-Type' => 'image/png']);
            }

            return response()->noContent();
        }
    }
}