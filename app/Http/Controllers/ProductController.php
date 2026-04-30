<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = DB::connection('pgsql_odoo')->select("
            SELECT 
                pp.id AS odoo_product_id,
                pt.name->>'en_US' AS product_name,
                pt.list_price,
                -- Menghitung total quantity hanya dari lokasi internal
                COALESCE(SUM(sq.quantity), 0) AS qty_on_hand
            FROM product_product pp
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            LEFT JOIN stock_quant sq ON sq.product_id = pp.id
            LEFT JOIN stock_location sl ON sq.location_id = sl.id
            WHERE (sl.usage = 'internal' OR sl.usage IS NULL) -- Filter hanya gudang fisik/internal
            GROUP BY pp.id, pt.name, pt.list_price;
        ");

        return view('products-bootstrap', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function backup()
    {
        // 1. Ambil data dari Odoo dengan penyesuaian JSON name dan filter Lokasi Internal
        $products = DB::connection('pgsql_odoo')->select("
            SELECT 
                pp.id AS odoo_product_id,
                pt.name->>'en_US' AS product_name,
                pt.list_price,
                CAST(COALESCE(SUM(sq.quantity), 0) AS INTEGER) AS qty_on_hand
            FROM product_product pp
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            LEFT JOIN stock_quant sq ON sq.product_id = pp.id
            LEFT JOIN stock_location sl ON sq.location_id = sl.id
            -- Filter agar hanya menghitung stok di gudang internal (On Hand)
            WHERE (sl.usage = 'internal' OR sl.usage IS NULL)
            GROUP BY pp.id, pt.name, pt.list_price
        ");

        // 2. Kosongkan tabel backup di SQL Server
        DB::connection("sqlsrv_backup")->table('backup_products')->truncate();

        // 3. Insert data secara massal (Chunking/Batching disarankan jika data ribuan)
        foreach ($products as $p) {
            DB::connection('sqlsrv_backup')->table('backup_products')->insert([
                'id'             => $p->odoo_product_id,
                'product_name'   => $p->product_name,
                'list_price'     => $p->list_price,
                'qty_available'  => $p->qty_on_hand, // Sudah di-cast di SQL
            ]);
        }

        // 4. Catat Log
        DB::connection('sqlsrv_backup')->table('backup_logs')->insert([
            'total_data' => count($products),
            'status'     => 'success',
            'message'    => 'Backup completed successfully at ' . now(),
        ]);

        return back()->with('success', 'Backup completed successfully!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function testSqlServer()
    {
        try {
            DB::connection('sqlsrv_backup')->getPdo();
            return "Koneksi SQL Server berhasil!";
        } catch (\Exception $e) {
            return "Gagal: " . $e->getMessage();
        }
    }
}
