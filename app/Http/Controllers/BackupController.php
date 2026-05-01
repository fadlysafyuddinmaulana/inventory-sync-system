<?php

namespace App\Http\Controllers;

use App\Models\BackupLog;
use App\Models\Odoo\ProductTemplate;
use App\Models\Odoo\StockQuant;
use App\Models\Odoo\StockWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    /**
     * Show backup page
     */
    public function index()
    {
        $lastBackup = BackupLog::where('status', 'success')
            ->orderByDesc('created_at')
            ->first();

        return view('backup.index', [
            'lastBackup' => $lastBackup,
        ]);
    }

    /**
     * Execute backup
     */
    public function backup(Request $request)
    {
        $backupLog = new BackupLog();
        $backupLog->status = 'pending';
        $backupLog->started_at = Carbon::now();
        $backupLog->save();

        try {
            // Backup products from Odoo to SQL Server
            $products = DB::connection('pgsql_odoo')->select("
                SELECT 
                    pp.id,
                    pt.name,
                    pp.default_code,
                    pt.list_price
                FROM product_product pp
                JOIN product_template pt ON pp.product_tmpl_id = pt.id
                ORDER BY pt.name
            ");

            $productCount = $this->backupProducts($products);

            // Backup stocks from Odoo to SQL Server
            $stocks = DB::connection('pgsql_odoo')->select("
                SELECT 
                    sq.id,
                    pp.id as product_id,
                    pt.name as product_name,
                    sl.id as location_id,
                    sl.name as location_name,
                    sw.id as warehouse_id,
                    sw.name as warehouse_name,
                    sq.quantity,
                    sq.reserved_quantity
                FROM stock_quant sq
                LEFT JOIN product_product pp ON sq.product_id = pp.id
                LEFT JOIN product_template pt ON pp.product_tmpl_id = pt.id
                LEFT JOIN stock_location sl ON sq.location_id = sl.id
                LEFT JOIN stock_warehouse sw ON sl.warehouse_id = sw.id
                ORDER BY sw.name, sl.name
            ");

            $stockCount = $this->backupStocks($stocks);

            // Count warehouses
            $warehouseCount = DB::connection('pgsql_odoo')
                ->table('stock_warehouse')
                ->count();

            // Update backup log
            $backupLog->update([
                'status' => 'success',
                'total_data' => $productCount + $stockCount,
                'completed_at' => Carbon::now(),
                'message' => 'Backup berhasil dilakukan',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dilakukan',
                'data' => [
                    'product_count' => $productCount,
                    'stock_count' => $stockCount,
                    'warehouse_count' => $warehouseCount,
                ],
            ]);
        } catch (\Exception $e) {
            $backupLog->update([
                'status' => 'failed',
                'completed_at' => Carbon::now(),
                'message' => 'Backup gagal: ' . $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Backup gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Backup products to SQL Server
     */
    private function backupProducts(array $products): int
    {
        $backupData = [];

        foreach ($products as $product) {
            $backupData[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'code' => $product->default_code,
                'price' => $product->list_price,
                'created_at' => Carbon::now(),
            ];
        }

        // Clear existing backup data
        DB::connection('sqlsrv_backup')->table('backup_products')->truncate();

        // Insert backup data in chunks
        if (!empty($backupData)) {
            foreach (array_chunk($backupData, 100) as $chunk) {
                DB::connection('sqlsrv_backup')->table('backup_products')->insert($chunk);
            }
        }

        return count($backupData);
    }

    /**
     * Backup stocks to SQL Server
     */
    private function backupStocks(array $stocks): int
    {
        $backupData = [];

        foreach ($stocks as $stock) {
            $backupData[] = [
                'product_id' => $stock->product_id,
                'product_name' => $stock->product_name,
                'location_id' => $stock->location_id,
                'location_name' => $stock->location_name,
                'warehouse_id' => $stock->warehouse_id,
                'warehouse_name' => $stock->warehouse_name,
                'quantity' => $stock->quantity,
                'reserved_quantity' => $stock->reserved_quantity ?? 0,
                'created_at' => Carbon::now(),
            ];
        }

        // Clear existing backup data
        DB::connection('sqlsrv_backup')->table('backup_stocks')->truncate();

        // Insert backup data in chunks
        if (!empty($backupData)) {
            foreach (array_chunk($backupData, 100) as $chunk) {
                DB::connection('sqlsrv_backup')->table('backup_stocks')->insert($chunk);
            }
        }

        return count($backupData);
    }

    /**
     * Download backup
     */
    public function download(int $id)
    {
        $backup = BackupLog::findOrFail($id);

        if ($backup->status !== 'success') {
            return back()->with('error', 'Backup tidak tersedia');
        }

        // Here you would generate CSV or export data
        // This is a placeholder for actual download functionality

        return back()->with('success', 'Backup berhasil diunduh');
    }
}