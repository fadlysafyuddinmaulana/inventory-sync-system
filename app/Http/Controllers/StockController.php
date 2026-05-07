<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\OdooService;

class StockController extends Controller
{
    private OdooService $odooService;

    public function __construct(OdooService $odooService)
    {
        $this->odooService = $odooService;
    }
    /**
     * Display stock warehouse list
     */
    public function warehouse(Request $request)
    {
        try {
            $warehouseFilter = $request->input('warehouse');
            $search = $request->input('search');

            // Get all warehouses via Odoo API
            $warehouses = $this->odooService->execute('stock.warehouse', 'search_read', [[], ['id', 'name', 'code'], 0, 0]);

            // Normalize warehouses to objects
            $warehouses = array_map(function ($w) {
                return (object)[
                    'id' => $w['id'] ?? null,
                    'name' => $w['name'] ?? null,
                    'code' => $w['code'] ?? null,
                ];
            }, is_array($warehouses) ? $warehouses : []);

            // Build query for stock

            // Build domain for stock.quant
            $domain = [['quantity', '>', 0]];

            if ($warehouseFilter) {
                // find locations for this warehouse
                $locationIds = $this->odooService->execute('stock.location', 'search', [[['warehouse_id', '=', (int)$warehouseFilter]]]);
                if (!empty($locationIds) && is_array($locationIds)) {
                    $domain[] = ['location_id', 'in', $locationIds];
                }
            }

            if ($search) {
                // Search for products matching the search term, then get their stock
                $productDomain = [['|', ['name', 'ilike', $search], ['default_code', 'ilike', $search]]];
                $matchingProducts = $this->odooService->execute('product.product', 'search', [$productDomain]);
                if (!empty($matchingProducts) && is_array($matchingProducts)) {
                    $domain[] = ['product_id', 'in', $matchingProducts];
                }
            }

            $fields = ['id', 'product_id', 'quantity', 'reserved_quantity', 'location_id'];

            $stocksRaw = $this->odooService->execute('stock.quant', 'search_read', [$domain, $fields, 0, 0]);

            $stocks = [];
            // collect location ids for mapping
            $locationIds = [];
            foreach (is_array($stocksRaw) ? $stocksRaw : [] as $s) {
                if (isset($s['location_id']) && is_array($s['location_id'])) {
                    $locationIds[] = $s['location_id'][0];
                }
            }

            $locationMap = [];
            if (!empty($locationIds)) {
                $locations = $this->odooService->execute('stock.location', 'read', [$locationIds, ['id', 'name', 'warehouse_id']]);
                foreach (is_array($locations) ? $locations : [] as $loc) {
                    $locationMap[$loc['id']] = [
                        'name' => $loc['name'] ?? null,
                        'warehouse_id' => isset($loc['warehouse_id'][0]) ? $loc['warehouse_id'][0] : null,
                    ];
                }
            }

            // build warehouse map
            $warehouseMap = [];
            foreach ($warehouses as $w) {
                $warehouseMap[$w->id] = $w->name;
            }

            foreach (is_array($stocksRaw) ? $stocksRaw : [] as $row) {
                $prod = $row['product_id'] ?? null;
                $prodName = is_array($prod) ? ($prod[1] ?? null) : null;
                $prodCode = null; // default_code not always present via product_id tuple

                $locId = is_array($row['location_id'] ?? null) ? ($row['location_id'][0] ?? null) : ($row['location_id'] ?? null);
                $locName = $locationMap[$locId]['name'] ?? null;
                $whId = $locationMap[$locId]['warehouse_id'] ?? null;
                $whName = $warehouseMap[$whId] ?? null;

                $obj = (object)[
                    'id' => $row['id'] ?? null,
                    'product_name' => $prodName,
                    'default_code' => $prodCode,
                    'location_name' => $locName,
                    'warehouse_name' => $whName,
                    'quantity' => $row['quantity'] ?? 0,
                ];

                $stocks[] = $obj;
            }

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


            $locations = $this->odooService->execute('stock.location', 'search_read', [[], ['id', 'name'], 0, 0]);

            $locations = array_map(function ($l) {
                return (object)[
                    'id' => $l['id'] ?? null,
                    'location_name' => $l['name'] ?? null,
                ];
            }, is_array($locations) ? $locations : []);

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
            $stocksRaw = $this->odooService->execute('stock.quant', 'search_read', [[['quantity', '>', 0]], ['id', 'product_id', 'quantity', 'location_id'], 0, 0]);

            // map locations and warehouses
            $locIds = [];
            foreach (is_array($stocksRaw) ? $stocksRaw : [] as $s) {
                if (isset($s['location_id']) && is_array($s['location_id'])) {
                    $locIds[] = $s['location_id'][0];
                }
            }

            $locationMap = [];
            if (!empty($locIds)) {
                $locations = $this->odooService->execute('stock.location', 'read', [$locIds, ['id', 'name', 'warehouse_id']]);
                foreach (is_array($locations) ? $locations : [] as $loc) {
                    $locationMap[$loc['id']] = [
                        'name' => $loc['name'] ?? null,
                        'warehouse_id' => isset($loc['warehouse_id'][0]) ? $loc['warehouse_id'][0] : null,
                    ];
                }
            }

            $warehouseMap = [];
            $warehouses = $this->odooService->execute('stock.warehouse', 'search_read', [[], ['id', 'name'], 0, 0]);
            foreach (is_array($warehouses) ? $warehouses : [] as $w) {
                $warehouseMap[$w['id']] = $w['name'] ?? null;
            }

            $stocks = [];
            foreach (is_array($stocksRaw) ? $stocksRaw : [] as $row) {
                $prod = $row['product_id'] ?? null;
                $prodName = is_array($prod) ? ($prod[1] ?? null) : null;
                $prodCode = null;
                $locId = is_array($row['location_id'] ?? null) ? ($row['location_id'][0] ?? null) : ($row['location_id'] ?? null);
                $locName = $locationMap[$locId]['name'] ?? null;
                $whName = $warehouseMap[$locationMap[$locId]['warehouse_id'] ?? null] ?? null;

                $stocks[] = (object)[
                    'product_name' => $prodName,
                    'default_code' => $prodCode,
                    'location_name' => $locName,
                    'warehouse_name' => $whName,
                    'quantity' => $row['quantity'] ?? 0,
                ];
            }

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

