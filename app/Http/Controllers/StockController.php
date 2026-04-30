<?php

namespace App\Http\Controllers;

use App\Models\Odoo\StockQuant;
use App\Models\Odoo\StockWarehouse;
use App\Models\Odoo\StockLocation;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display stock warehouse list with multi-warehouse support
     */
    public function warehouse(Request $request)
    {
        $warehouseFilter = $request->input('warehouse');
        $search = $request->input('search');

        // Get all warehouses
        $warehouses = StockWarehouse::all();

        // Build query for stock
        $stocks = StockQuant::with(['product.template', 'location.warehouse']);

        // Filter by warehouse if specified
        if ($warehouseFilter) {
            $stocks->whereHas('location', function ($query) use ($warehouseFilter) {
                $query->where('warehouse_id', $warehouseFilter);
            });
        }

        // Search by product name or SKU
        if ($search) {
            $stocks->whereHas('product', function ($query) use ($search) {
                $query->whereHas('template', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('default_code', 'like', "%{$search}%");
                });
            });
        }

        // Filter only stocks with quantity > 0
        $stocks = $stocks->where('quantity', '>', 0)
            ->paginate(20);

        return view('warehouse-stock-bootstrap', [
            'stocks' => $stocks,
            'warehouses' => $warehouses,
            'selectedWarehouse' => $warehouseFilter,
            'search' => $search,
        ]);
    }

    /**
     * Display stock by location
     */
    public function byLocation(Request $request)
    {
        $warehouseFilter = $request->input('warehouse');

        $locations = StockLocation::query();

        if ($warehouseFilter) {
            $locations->where('warehouse_id', '=', $warehouseFilter);
        }

        $locations = $locations->with(['quants', 'warehouse'])->paginate(20);
        $warehouses = StockWarehouse::all();

        return view('stock.by-location', [
            'locations' => $locations,
            'warehouses' => $warehouses,
            'selectedWarehouse' => $warehouseFilter,
        ]);
    }

    /**
     * Export stock data
     */
    public function export(Request $request)
    {
        $stocks = StockQuant::with(['product.template', 'location.warehouse'])
            ->where('quantity', '>', 0, 'and')
            ->get();

        $filename = 'stock-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $columns = ['Produk', 'SKU', 'Warehouse', 'Lokasi', 'Qty', 'Qty Reserved'];

        $data = $stocks->map(function ($stock) {
            return [
                $stock->product->template->name ?? 'N/A',
                $stock->product->default_code ?? 'N/A',
                $stock->location->warehouse->name ?? 'N/A',
                $stock->location->name ?? 'N/A',
                $stock->quantity,
                $stock->reserved_quantity,
            ];
        });

        $callback = function () use ($columns, $data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
