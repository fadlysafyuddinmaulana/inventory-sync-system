<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display stock warehouse list
     */
    public function warehouse(Request $request)
    {
        try {
            $warehouseFilter = $request->input('warehouse');
            $search = $request->input('search');

            // Get all warehouses
            $warehouses = DB::connection('pgsql_odoo')
                ->table('stock_warehouse')
                ->select('id', 'name', 'code')
                ->get();

            // Build query for stock
            $query = "
                SELECT 
                    sq.id,
                    pt.name as product_name,
                    pp.default_code,
                    sl.name as location_name,
                    sw.name as warehouse_name,
                    sq.quantity
                FROM stock_quant sq
                LEFT JOIN product_product pp ON sq.product_id = pp.id
                LEFT JOIN product_template pt ON pp.product_tmpl_id = pt.id
                LEFT JOIN stock_location sl ON sq.location_id = sl.id
                LEFT JOIN stock_warehouse sw ON sl.warehouse_id = sw.id
                WHERE sq.quantity > 0
            ";

            $params = [];

            if ($warehouseFilter) {
                $query .= " AND sl.warehouse_id = ?";
                $params[] = $warehouseFilter;
            }

            if ($search) {
                $query .= " AND (pt.name ILIKE ? OR pp.default_code ILIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }

            $query .= " ORDER BY sw.name, sl.name, pt.name";

            $stocks = DB::connection('pgsql_odoo')->select($query, $params);

            return view('stocks.warehouse', [
                'stocks' => $stocks,
                'warehouses' => $warehouses,
                'selectedWarehouse' => $warehouseFilter,
                'search' => $search,
            ]);
        } catch (\Exception $e) {
            return view('stocks.warehouse', [
                'error' => 'Error fetching stocks: ' . $e->getMessage(),
                'stocks' => [],
                'warehouses' => [],
            ]);
        }
    }

    /**
     * Display stock by location
     */
    public function byLocation(Request $request)
    {
        try {
            $locationFilter = $request->input('location');

            $query = "
                SELECT 
                    sl.id,
                    sl.name as location_name,
                    COUNT(DISTINCT sq.id) as total_lines,
                    COALESCE(SUM(sq.quantity), 0) as total_quantity
                FROM stock_location sl
                LEFT JOIN stock_quant sq ON sl.id = sq.location_id
                GROUP BY sl.id, sl.name
                ORDER BY sl.name
            ";

            $locations = DB::connection('pgsql_odoo')->select($query);

            return view('stocks.by-location', [
                'locations' => $locations,
                'selectedLocation' => $locationFilter,
            ]);
        } catch (\Exception $e) {
            return view('stocks.by-location', [
                'error' => 'Error fetching locations: ' . $e->getMessage(),
                'locations' => [],
            ]);
        }
    }

    /**
     * Export stock data
     */
    public function export(Request $request)
    {
        try {
            $stocks = DB::connection('pgsql_odoo')->select("
                SELECT 
                    pt.name as product_name,
                    pp.default_code,
                    sl.name as location_name,
                    sw.name as warehouse_name,
                    sq.quantity
                FROM stock_quant sq
                LEFT JOIN product_product pp ON sq.product_id = pp.id
                LEFT JOIN product_template pt ON pp.product_tmpl_id = pt.id
                LEFT JOIN stock_location sl ON sq.location_id = sl.id
                LEFT JOIN stock_warehouse sw ON sl.warehouse_id = sw.id
                WHERE sq.quantity > 0
                ORDER BY sw.name, sl.name, pt.name
            ");

            // Create CSV
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="stock_' . now()->format('Y-m-d_His') . '.csv"',
            ];

            $callback = function() use ($stocks) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Product Name', 'SKU', 'Location', 'Warehouse', 'Quantity']);
                
                foreach ($stocks as $stock) {
                    fputcsv($file, [
                        $stock->product_name,
                        $stock->default_code,
                        $stock->location_name,
                        $stock->warehouse_name,
                        $stock->quantity,
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }
}

