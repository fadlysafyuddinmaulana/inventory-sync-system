<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller
{
    /**
     * Display stock movements
     */
    public function index(Request $request)
    {
        try {
            $statusFilter = $request->input('status');
            $search = $request->input('search');

            $query = "
                SELECT 
                    sm.id,
                    pt.name ->> 'en_US' AS product_name,
                    pp.default_code,
                    sl1.name as source_location,
                    sl2.name as destination_location,
                    sm.product_uom_qty AS quantity_done,
                    sm.state,
                    sm.create_date
                FROM stock_move sm
                LEFT JOIN product_product pp ON sm.product_id = pp.id
                LEFT JOIN product_template pt ON pp.product_tmpl_id = pt.id
                LEFT JOIN stock_location sl1 ON sm.location_id = sl1.id
                LEFT JOIN stock_location sl2 ON sm.location_dest_id = sl2.id
                WHERE 1=1
            ";

            $params = [];

            if ($statusFilter) {
                $query .= " AND sm.state = ?";
                $params[] = $statusFilter;
            }

            if ($search) {
                $query .= " AND (pt.name ILIKE ? OR pp.default_code ILIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }

            $query .= " ORDER BY sm.create_date DESC";

            $movements = DB::connection('pgsql_odoo')->select($query, $params);

            return view('movements.index', [
                'movements' => $movements,
                'statusFilter' => $statusFilter,
                'search' => $search,
            ]);
        } catch (\Exception $e) {
            return view('movements.index', [
                'error' => 'Error fetching movements: ' . $e->getMessage(),
                'movements' => [],
            ]);
        }
    }

    /**
     * Get movement statistics
     */
    public function statistics(Request $request)
    {
        try {
            $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->format('Y-m-d'));

            $stats = DB::connection('pgsql_odoo')->select("
                SELECT 
                    sm.state,
                    COUNT(*) as count
                FROM stock_move sm
                WHERE DATE(sm.create_date) BETWEEN ? AND ?
                GROUP BY sm.state
            ", [$startDate, $endDate]);

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
