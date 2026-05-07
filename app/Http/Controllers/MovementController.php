<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\OdooService;

class MovementController extends Controller
{
    private OdooService $odooService;

    public function __construct(OdooService $odooService)
    {
        $this->odooService = $odooService;
    }
    /**
     * Display stock movements
     */
    public function index(Request $request)
    {
        try {
            $statusFilter = $request->input('status');
            $search = $request->input('search');

            $domain = [];
            if ($statusFilter) {
                $domain[] = ['state', '=', $statusFilter];
            }

            if ($search) {
                $domain[] = ['|', ['product_id', 'ilike', $search], ['product_id', 'ilike', $search]];
            }

            $fields = ['id', 'product_id', 'product_uom_qty', 'location_id', 'location_dest_id', 'state', 'create_date'];

            $movementsRaw = $this->odooService->execute('stock.move', 'search_read', [$domain, $fields, 0, 0]);

            $movements = [];
            foreach (is_array($movementsRaw) ? $movementsRaw : [] as $m) {
                $prod = $m['product_id'] ?? null;
                $prodName = is_array($prod) ? ($prod[1] ?? null) : null;
                $src = is_array($m['location_id'] ?? null) ? ($m['location_id'][1] ?? null) : null;
                $dst = is_array($m['location_dest_id'] ?? null) ? ($m['location_dest_id'][1] ?? null) : null;

                $movements[] = (object)[
                    'id' => $m['id'] ?? null,
                    'product_name' => $prodName,
                    'default_code' => null,
                    'source_location' => $src,
                    'destination_location' => $dst,
                    'quantity_done' => $m['product_uom_qty'] ?? null,
                    'state' => $m['state'] ?? null,
                    'create_date' => $m['create_date'] ?? null,
                ];
            }

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

            $domain = [["&", ['create_date', '>=', $startDate . ' 00:00:00'], ['create_date', '<=', $endDate . ' 23:59:59']]];

            // Use search_read and aggregate locally
            $rows = $this->odooService->execute('stock.move', 'search_read', [$domain, ['state'], 0, 0]);

            $counts = [];
            foreach (is_array($rows) ? $rows : [] as $r) {
                $state = $r['state'] ?? 'unknown';
                if (!isset($counts[$state])) $counts[$state] = 0;
                $counts[$state]++;
            }

            $result = [];
            foreach ($counts as $k => $v) {
                $result[] = (object)['state' => $k, 'count' => $v];
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
