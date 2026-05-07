<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\OdooService;

class DashboardController extends Controller
{
    private OdooService $odooService;

    public function __construct(OdooService $odooService)
    {
        $this->odooService = $odooService;
    }
    public function index()
    {
        try {
            // Get dashboard statistics from Odoo via API
            $totalProducts = $this->odooService->execute('product.template', 'search_count', [[]]);

            $quantRows = $this->odooService->execute('stock.quant', 'search_read', [[], ['quantity'], 0, 0]);
            $totalStocks = 0;
            foreach (is_array($quantRows) ? $quantRows : [] as $q) {
                $totalStocks += isset($q['quantity']) ? (float)$q['quantity'] : 0;
            }

            $totalWarehouses = $this->odooService->execute('stock.warehouse', 'search_count', [[]]);

            $totalMovements = $this->odooService->execute('stock.move', 'search_count', [[['state', '=', 'done']]]);

            // Recent movements
            $recentRaw = $this->odooService->execute('stock.move', 'search_read', [[['state', '=', 'done']], ['id', 'product_id', 'product_uom_qty', 'location_id', 'location_dest_id', 'state', 'create_date'], 0, 10]);
            $recentMovements = [];
            foreach (is_array($recentRaw) ? $recentRaw : [] as $m) {
                $prod = $m['product_id'] ?? null;
                $prodName = is_array($prod) ? ($prod[1] ?? null) : null;
                $src = is_array($m['location_id'] ?? null) ? ($m['location_id'][1] ?? null) : null;
                $dst = is_array($m['location_dest_id'] ?? null) ? ($m['location_dest_id'][1] ?? null) : null;

                $recentMovements[] = (object)[
                    'id' => $m['id'] ?? null,
                    'product_name' => $prodName,
                    'quantity_done' => $m['product_uom_qty'] ?? null,
                    'source_location' => $src,
                    'destination_location' => $dst,
                    'state' => $m['state'] ?? null,
                    'create_date' => $m['create_date'] ?? null,
                ];
            }

            // Warehouse summary: fetch warehouses and aggregate stock via locations
            $warehouses = $this->odooService->execute('stock.warehouse', 'search_read', [[], ['id', 'name'], 0, 0]);
            $warehouseSummary = [];
            foreach (is_array($warehouses) ? $warehouses : [] as $w) {
                $wid = $w['id'];
                $locIds = $this->odooService->execute('stock.location', 'search', [[['warehouse_id', '=', $wid]]]);
                $totalQty = 0;
                if (!empty($locIds)) {
                    $quants = $this->odooService->execute('stock.quant', 'search_read', [[['location_id', 'in', $locIds]], ['quantity'], 0, 0]);
                    foreach (is_array($quants) ? $quants : [] as $q) {
                        $totalQty += isset($q['quantity']) ? (float)$q['quantity'] : 0;
                    }
                }
                $warehouseSummary[] = (object)[
                    'id' => $wid,
                    'warehouse_name' => $w['name'] ?? null,
                    'total_lines' => 0,
                    'total_quantity' => $totalQty,
                ];
            }

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
