<?php

namespace App\Http\Controllers;

use App\Models\Odoo\ProductTemplate;
use App\Models\Odoo\StockQuant;
use App\Models\Odoo\StockWarehouse;
use App\Models\Odoo\StockMove;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get dashboard statistics from Odoo
            $totalProducts = DB::connection('pgsql_odoo')
                ->table('product_template')
                ->count();

            $totalStocks = DB::connection('pgsql_odoo')
                ->table('stock_quant')
                ->sum('quantity');

            $totalWarehouses = DB::connection('pgsql_odoo')
                ->table('stock_warehouse')
                ->count();

            $totalMovements = DB::connection('pgsql_odoo')
                ->table('stock_move')
                ->where('state', 'done')
                ->count();

            // Get recent movements
            $recentMovements = DB::connection('pgsql_odoo')->select(<<<'SQL'
                SELECT 
                    sm.id,
                    pt.name ->> 'en_US' AS product_name,
                    sm.product_uom_qty AS quantity_done,
                    sl1.name as source_location,
                    sl2.name as destination_location,
                    sm.state,
                    sm.create_date
                FROM stock_move sm
                LEFT JOIN product_product pp ON sm.product_id = pp.id
                LEFT JOIN product_template pt ON pp.product_tmpl_id = pt.id
                LEFT JOIN stock_location sl1 ON sm.location_id = sl1.id
                LEFT JOIN stock_location sl2 ON sm.location_dest_id = sl2.id
                WHERE sm.state = 'done'
                ORDER BY sm.create_date DESC
                LIMIT 10
            SQL
            );

            // Get warehouse summary with stock
            $warehouseSummary = DB::connection('pgsql_odoo')->select("
                SELECT 
                    sw.id,
                    sw.name as warehouse_name,
                    COUNT(DISTINCT sq.id) as total_lines,
                    COALESCE(SUM(sq.quantity), 0) as total_quantity
                FROM stock_warehouse sw
                LEFT JOIN stock_location sl ON sw.id = sl.warehouse_id
                LEFT JOIN stock_quant sq ON sl.id = sq.location_id
                GROUP BY sw.id, sw.name
                ORDER BY sw.name
            ");

            return view('dashboard.index', [
                'totalProducts' => $totalProducts,
                'totalStocks' => $totalStocks,
                'totalWarehouses' => $totalWarehouses,
                'totalMovements' => $totalMovements,
                'recentMovements' => $recentMovements,
                'warehouseSummary' => $warehouseSummary,
            ]);
        } catch (\Exception $e) {
            return view('dashboard.index', [
                'error' => 'Error connecting to Odoo database: ' . $e->getMessage(),
                'totalProducts' => 0,
                'totalStocks' => 0,
                'totalWarehouses' => 0,
                'totalMovements' => 0,
                'recentMovements' => [],
                'warehouseSummary' => [],
            ]);
        }
    }
}
