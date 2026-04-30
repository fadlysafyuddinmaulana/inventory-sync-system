<?php

namespace App\Http\Controllers;

use App\Models\Odoo\ProductTemplate;
use App\Models\Odoo\StockQuant;
use App\Models\Odoo\StockWarehouse;
use App\Models\Odoo\StockMove;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics from Odoo
        $totalProducts = ProductTemplate::query()->count('*');
        $totalStocks = StockQuant::sum('quantity');
        $totalWarehouses = StockWarehouse::query()->count('*');
        $totalMovements = StockMove::where('state', '=', 'done')->count();

        // Get recent movements
        $recentMovements = StockMove::where('state', '=', 'done')
            ->with(['product', 'locationFrom', 'locationTo'])
            ->orderByDesc('create_date')
            ->limit(10)
            ->get();

        // Get warehouse summary
        $warehouseSummary = StockWarehouse::with([
            'locations' => function ($query) {
                $query->with('quants');
            }
        ])->get();

        return view('dashboard', [
            'totalProducts' => $totalProducts,
            'totalStocks' => $totalStocks,
            'totalWarehouses' => $totalWarehouses,
            'totalMovements' => $totalMovements,
            'recentMovements' => $recentMovements,
            'warehouseSummary' => $warehouseSummary,
        ]);
    }
}
