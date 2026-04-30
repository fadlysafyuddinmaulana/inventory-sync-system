<?php

namespace App\Http\Controllers;

use App\Models\Odoo\StockMove;
use App\Models\Odoo\StockPicking;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    /**
     * Display stock movements
     */
    public function index(Request $request)
    {
        $typeFilter = $request->input('type'); // 'in', 'out', 'internal'
        $statusFilter = $request->input('status'); // 'done', 'pending', 'cancelled'
        $search = $request->input('search');

        $movements = StockMove::with(['product.template', 'locationFrom', 'locationTo', 'picking']);

        // Filter by type
        if ($typeFilter) {
            $movements->where('move_type', $typeFilter);
        }

        // Filter by status
        if ($statusFilter) {
            $movements->where('state', $statusFilter);
        }

        // Search by product name or picking code
        if ($search) {
            $movements->where(function ($query) use ($search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->whereHas('template', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
                })->orWhereHas('picking', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        $movements = $movements->orderByDesc('create_date')
            ->paginate(20);

        return view('stock-movements-bootstrap', [
            'movements' => $movements,
            'typeFilter' => $typeFilter,
            'statusFilter' => $statusFilter,
            'search' => $search,
        ]);
    }

    /**
     * Get movement statistics
     */
    public function statistics(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30));
        $endDate = $request->input('end_date', now());

        $inCount = StockMove::whereBetween('create_date', [$startDate, $endDate])
            ->where('state', 'done')
            ->where('move_type', 'in')
            ->count();

        $outCount = StockMove::whereBetween('create_date', [$startDate, $endDate])
            ->where('state', 'done')
            ->where('move_type', 'out')
            ->count();

        $internalCount = StockMove::whereBetween('create_date', [$startDate, $endDate])
            ->where('state', 'done')
            ->where('move_type', 'internal')
            ->count();

        return response()->json([
            'in' => $inCount,
            'out' => $outCount,
            'internal' => $internalCount,
        ]);
    }
}
