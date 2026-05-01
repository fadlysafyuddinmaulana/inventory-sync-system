<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index()
    {
        try {
            $products = DB::connection('pgsql_odoo')->select("
                SELECT 
                    pp.id,
                    pp.default_code,
                    pt.name ->> 'en_US' AS name,
                    pt.list_price,
                    COALESCE(SUM(sq.quantity), 0) AS qty_on_hand
                FROM product_product pp
                JOIN product_template pt ON pp.product_tmpl_id = pt.id
                LEFT JOIN stock_quant sq ON sq.product_id = pp.id
                LEFT JOIN stock_location sl ON sq.location_id = sl.id
                WHERE (sl.usage = 'internal' OR sl.usage IS NULL)
                GROUP BY pp.id, pp.default_code, pt.name, pt.list_price
                ORDER BY pt.name
            ");

            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            return view('products.index', [
                'error' => 'Error fetching products: ' . $e->getMessage(),
                'products' => [],
            ]);
        }
    }

    /**
     * Show product details
     */
    public function show($id)
    {
        try {
            $product = DB::connection('pgsql_odoo')->selectOne("
                SELECT 
                    pp.id,
                    pp.default_code,
                    pt.name ->> 'en_US' AS name,
                    pt.list_price,
                    COALESCE(SUM(sq.quantity), 0) AS qty_on_hand
                FROM product_product pp
                JOIN product_template pt ON pp.product_tmpl_id = pt.id
                LEFT JOIN stock_quant sq ON sq.product_id = pp.id
                WHERE pp.id = ?
                GROUP BY pp.id, pp.default_code, pt.name, pt.list_price
            ", [$id]);

            if (!$product) {
                return redirect()->route('products')->with('error', 'Product not found');
            }

            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('products')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
