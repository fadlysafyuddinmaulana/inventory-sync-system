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
            $attachment = DB::connection('pgsql_odoo')->selectOne("
                SELECT
                    pt.id AS product_template_id,
                    pt.name,
                    ia.id AS attachment_id,
                    ia.res_field,
                    ia.name AS attachment_name,
                    ia.store_fname,
                    ia.mimetype,
                    ia.file_size
                FROM product_template pt
                JOIN product_product pp ON pp.product_tmpl_id = pt.id
                JOIN stock_move sm ON sm.product_id = pp.id
                LEFT JOIN ir_attachment ia
                    ON ia.res_model = 'product.template'
                   AND ia.res_id = pt.id
                   AND ia.res_field IN ('image_128', 'image_1920', 'image_512', 'image_256')
                WHERE pt.id = ?
                  AND sm.state = 'done'
                  AND ia.res_field = ?
                ORDER BY sm.create_date DESC, pt.id, ia.res_field
                LIMIT 1
            ", [$template_id, "image_{$size}"]);

            if (!$attachment) {
                $attachment = DB::connection('pgsql_odoo')->selectOne("
                    SELECT
                        pt.id AS product_template_id,
                        pt.name,
                        ia.id AS attachment_id,
                        ia.res_field,
                        ia.name AS attachment_name,
                        ia.store_fname,
                        ia.mimetype,
                        ia.file_size
                    FROM product_template pt
                    JOIN product_product pp ON pp.product_tmpl_id = pt.id
                    JOIN stock_move sm ON sm.product_id = pp.id
                    LEFT JOIN ir_attachment ia
                        ON ia.res_model = 'product.template'
                       AND ia.res_id = pt.id
                       AND ia.res_field IN ('image_128', 'image_1920', 'image_512', 'image_256')
                    WHERE pt.id = ?
                      AND sm.state = 'done'
                      AND ia.res_field IS NOT NULL
                    ORDER BY sm.create_date DESC, pt.id, ia.res_field
                    LIMIT 1
                ", [$template_id]);
            }

            // Tidak ada attachment → placeholder
            if (!$attachment || !$attachment->attachment_id) {
                return $this->placeholderResponse();
            }

            // 1) Coba baca langsung dari filesystem Odoo jika path memang tersedia di host ini
            $filestorePath = env('ODOO_FILESTORE_PATH', '/var/lib/odoo/filestore');
            $database      = env('ODOO_DATABASE', 'odoo_inventory_db');
            $fullPath      = "{$filestorePath}/{$database}/{$attachment->store_fname}";

            if ($attachment->store_fname && file_exists($fullPath)) {
                return response()->file($fullPath, [
                    'Content-Type'  => $attachment->mimetype ?? 'image/webp',
                    'Cache-Control' => 'max-age=86400',
                ]);
            }

            // 2) Fallback ke Odoo API jika filestore lokal tidak ada / tidak bisa diakses
            $odooUrl = env('ODOO_URL', 'http://localhost:8069');
            $url = "{$odooUrl}/web/image/ir.attachment/{$attachment->attachment_id}";
            $url .= "?max_width={$size}&max_height={$size}";

            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                return response($response->body(), 200)
                    ->header('Content-Type', $response->header('Content-Type') ?? ($attachment->mimetype ?? 'image/png'))
                    ->header('Cache-Control', 'max-age=86400');
            }

            Log::warning('Odoo API image fetch failed', [
                'template_id' => $template_id,
                'attachment_id' => $attachment->attachment_id,
                'status' => $response->status(),
                'url' => $url,
            ]);

            return $this->placeholderResponse();

        } catch (\Exception $e) {
            \Log::error("Error fetching product image: " . $e->getMessage());
            return $this->placeholderResponse();
        }
    }

    private function placeholderResponse()
    {
        $placeholder = public_path('images/no-image.svg');
        if (file_exists($placeholder)) {
            return response()->file($placeholder, [
                'Content-Type'  => 'image/svg+xml',
                'Cache-Control' => 'max-age=3600',
            ]);
        }
        return response()->noContent();
    }
}